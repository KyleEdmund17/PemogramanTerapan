
const playlistAudio = document.getElementById('audio');
let savedPlaylist = [];

function load(){
  const raw = localStorage.getItem('nb_playlist');
  savedPlaylist = raw ? JSON.parse(raw) : [];
  render();
}
function render(){
  const el = document.getElementById('items');
  if(savedPlaylist.length === 0){
    el.innerHTML = `<div class="muted">Belum ada lagu di playlist</div>`;
    return;
  }
  el.innerHTML = savedPlaylist.map((s,i)=>`
    <div class="playlist-item">
      <div>
        <div style="font-weight:600">${s.title}</div>
        <div class="muted">${s.artist} · ${s.region}</div>
      </div>
      <div>
        <button class="btn" onclick="play(${i})">Putar</button>
        <button class="btn" onclick="remove(${i})">Hapus</button>
      </div>
    </div>
  `).join('');
}

window.play = function(i){
  const s = savedPlaylist[i];
  if(!s) return;
  playlistAudio.src = s.src;
  playlistAudio.play();
}

window.remove = function(i){
  if(!confirm('Hapus lagu dari playlist?')) return;
  savedPlaylist.splice(i,1);
  localStorage.setItem('nb_playlist', JSON.stringify(savedPlaylist));
  render();
}

load();
