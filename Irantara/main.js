
const DATA_PATH = 'assets/data/songs.json';

let SONGS = [];
let state = { current: null, paused: true, playlist: [], regionFilter: null, search: '' };
const audio = document.getElementById('audio');

async function loadData(){
  try {
    const res = await fetch(DATA_PATH);
    SONGS = await res.json();
  } catch (e) {
   
    SONGS = [
      {
        "id": 1,
        "title": "Gending Pawuh",
        "artist": "Gamelan Kraton",
        "region": "Jawa Tengah",
        "thumb": "foto/1.png",
        "src": "lagu/lagu1.mp3"
      },
      {
        "id": 3,
        "title": "Ayo Mama",
        "artist": "Van Dijk Band",
        "region": "Maluku",
        "thumb": "foto/3.png",
        "src": "lagu/lagu3.mp3"
      },
      {
        "id": 4,
        "title": "Apuse",
        "artist": "Black Brothers",
        "region": "Papua",
        "thumb": "foto/4.png",
        "src": "lagu/lagu4.mp3"
      }

    ];
  }
  initRegions();
  renderCards();
  loadPlaylist();
  updateNow();
}

//region
function initRegions(){
  const regions = Array.from(new Set(SONGS.map(s=>s.region))).sort();
  const el = document.getElementById('regions');
  el.innerHTML = `<button class="btn" onclick="clearRegion()">Semua Daerah</button>` + regions.map(r=>`<div class="region" onclick="setRegion('${r}')">${r}</div>`).join('');
}

//card lagu
function renderCards(){
  const q = document.getElementById('search').value.trim().toLowerCase();
  state.search = q;
  const list = SONGS.filter(s=>{
    if(state.regionFilter && s.region !== state.regionFilter) return false;
    if(!q) return true;
    return s.title.toLowerCase().includes(q) || s.artist.toLowerCase().includes(q) || s.region.toLowerCase().includes(q);
  });
  const cards = document.getElementById('cards');
  if(!cards) return;
  cards.innerHTML = list.map(s=>`
    <article class='card'>
      <div class='thumb' style="background-image:url('${s.thumb}')"></div>
      <h4>${s.title}</h4>
      <p class="muted">${s.artist} • ${s.region}</p>
      <div class="actions">
        <button class="btn primary" onclick="playTrack(${s.id})">Putar</button>
        <button class="btn" onclick="addToPlaylist(${s.id})">+ Playlist</button>
        <button class="btn" onclick="like(${s.id})">❤</button>
      </div>
    </article>
  `).join('');
}

/* Region filter helpers (global functions used in generated HTML) */
window.setRegion = function(r){ state.regionFilter = r; renderCards(); }
window.clearRegion = function(){ state.regionFilter = null; renderCards(); }

//buat play
window.playTrack = function(id){
  const s = SONGS.find(x=>x.id===id);
  if(!s) return;
  state.current = s;
  audio.src = s.src;
  audio.play();
  state.paused = false;
  updateNow();
  showMini();
}

document.getElementById('play').addEventListener('click', togglePlay);
document.getElementById('prev').addEventListener('click', prevTrack);
document.getElementById('next').addEventListener('click', nextTrack);
document.getElementById('mini-play').addEventListener('click', togglePlay);
document.getElementById('progress').addEventListener('click', seek);
document.getElementById('search').addEventListener('input', renderCards);
document.getElementById('virtual-btn').addEventListener('click', playVirtualInstrument);
document.getElementById('like').addEventListener('click', likeNow);

function togglePlay(){
  if(!state.current){ playTrack(SONGS[0].id); return; }
  if(state.paused){ audio.play(); state.paused=false } else { audio.pause(); state.paused=true }
  updateNow();
}

audio.addEventListener('pause', ()=>{ state.paused=true; updateNow(); });
audio.addEventListener('play', ()=>{ state.paused=false; updateNow(); });
audio.addEventListener('timeupdate', ()=>{ const p = audio.currentTime / (audio.duration || 1) * 100; document.getElementById('progbar').style.width = p + '%'; });
audio.addEventListener('ended', ()=> nextTrack());

function prevTrack(){
  if(!state.current) return;
  const idx = SONGS.findIndex(s=>s.id===state.current.id);
  const prev = SONGS[(idx-1+SONGS.length)%SONGS.length];
  playTrack(prev.id);
}
function nextTrack(){
  if(!state.current) return;
  const idx = SONGS.findIndex(s=>s.id===state.current.id);
  const next = SONGS[(idx+1)%SONGS.length];
  playTrack(next.id);
}
function seek(e){
  const rect = e.currentTarget.getBoundingClientRect();
  const x = e.clientX - rect.left;
  const pct = x / rect.width;
  audio.currentTime = (audio.duration || 1) * pct;
}

//tambah playlist
window.addToPlaylist = function(id){
  const s = SONGS.find(x=>x.id===id); if(!s) return;
  state.playlist.push(s); savePlaylist(); renderPlaylist();
  alert('Ditambahkan ke playlist: ' + s.title);
}
function renderPlaylist(){
  const el = document.getElementById('playlist-list');
  if(!el) return;
  if(state.playlist.length === 0) { el.innerHTML = `<div class="muted">Belum ada lagu di playlist</div>`; return; }
  el.innerHTML = state.playlist.map((p,i)=>`
    <div class="playlist-item">
      <div>${p.title} <span class="muted">· ${p.artist}</span></div>
      <div>
        <button class="btn" onclick="playTrack(${p.id})">Putar</button>
        <button class="btn" onclick="removeFromPlaylist(${i})">Hapus</button>
      </div>
    </div>
  `).join('');
}
window.removeFromPlaylist = function(i){ state.playlist.splice(i,1); savePlaylist(); renderPlaylist(); }
function savePlaylist(){ localStorage.setItem('nb_playlist', JSON.stringify(state.playlist)); }
function loadPlaylist(){ const raw = localStorage.getItem('nb_playlist'); if(raw) state.playlist = JSON.parse(raw); renderPlaylist(); }

window.like = function(id){ const s = SONGS.find(x=>x.id===id); if(!s) return; alert('Kamu menyukai: ' + s.title); }
function likeNow(){ if(state.current) like(state.current.id); }


function updateNow(){
  if(!state.current){
    document.getElementById('now-title').innerText = 'Pilih lagu untuk memutar';
    document.getElementById('now-sub').innerText = '—';
    document.getElementById('now-cover').style.backgroundImage = 'none';
    document.getElementById('play').innerText = 'Play';
    document.getElementById('mini-title').innerText = '-';
    document.getElementById('mini-play').innerText = 'Play';
    return;
  }
  document.getElementById('now-title').innerText = state.current.title;
  document.getElementById('now-sub').innerText = `${state.current.artist} · ${state.current.region}`;
  document.getElementById('now-cover').style.backgroundImage = `url('${state.current.thumb}')`;
  document.getElementById('play').innerText = state.paused ? 'Play' : 'Pause';
  document.getElementById('mini-title').innerText = state.current.title;
  document.getElementById('mini-play').innerText = state.paused ? 'Play' : 'Pause';
}


//play lagu
let audioCtx;
function playVirtualInstrument(){
  if(!audioCtx) audioCtx = new (window.AudioContext || window.webkitAudioContext)();
  const notes=[523,587,659,698,784];
  let t=0;
  notes.forEach((n,i)=>{
    const o = audioCtx.createOscillator();
    const g = audioCtx.createGain();
    o.type='sine'; o.frequency.value = n;
    g.gain.value = 0.0001;
    o.connect(g); g.connect(audioCtx.destination);
    o.start(audioCtx.currentTime + t);
    g.gain.linearRampToValueAtTime(0.08, audioCtx.currentTime + t + 0.02);
    g.gain.linearRampToValueAtTime(0.0001, audioCtx.currentTime + t + 0.22);
    o.stop(audioCtx.currentTime + t + 0.25);
    t += 0.28;
  });
}


window.openArtist = function(id){
  const s = SONGS.find(x=>x.id===id);
  if(!s) return;
  const artist = encodeURIComponent(s.artist);
  window.location.href = `artist.html?artist=${artist}&song=${encodeURIComponent(s.title)}`;
}


loadData();
