<?php
require "auth.php";
$songs = json_decode(file_get_contents("data/songs.json"), true);
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Irantara - Discover</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="app">

  <aside class="sidebar">
    <div class="logo">
      <div class="dot">I</div>
      <div>Irantara</div>
    </div>

    <nav>
      <a class="nav-item active" href="#">Discover</a>
      <a class="nav-item" href="playlist.php">Playlistku</a>
    </nav>

    <div class="section-title">Daerah</div>
    <div class="region-list">
      <div class="region">Semua Daerah</div>
      <div class="region">Jawa Barat</div>
      <div class="region">Jawa Tengah</div>
      <div class="region">Maluku</div>
      <div class="region">Papua</div>
    </div>

    <div class="section-title">Tentang</div>
    <p class="muted">
      Platform ini menampilkan koleksi musik tradisional Indonesia untuk pelestarian budaya.
    </p>
  </aside>

  <main class="main">

    <header class="topbar">
      <div class="left">
        <h2>Discover</h2>
        <input class="search-input" placeholder="Cari lagu, daerah, atau seniman">
      </div>

      <div class="right">
        <span class="role-label"><?= strtoupper($_SESSION['role']) ?></span>
        <?php if ($_SESSION['role'] === 'admin'): ?>
          <a class="btn" href="admin/dashboard.php">Admin</a>
        <?php endif; ?>
        <a class="btn" href="logout.php">Logout</a>
      </div>
    </header>

    <section class="content">

      <div class="left-col">
        <h3 class="section-heading">Koleksi Unggulan</h3>

        <div class="cards">
          <?php foreach ($songs as $song): ?>
            <div class="card">
              <div class="thumb" style="background-image:url('assets/images/default.jpg')"></div>
              <h4><?= $song['title'] ?></h4>
              <p class="muted"><?= $song['artist'] ?></p>

              <div class="actions">
                <audio controls style="width:100%">
                  <source src="songs/<?= $song['file'] ?>" type="audio/mpeg">
                </audio>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <aside class="right-col">
        <div class="player-box">
          <div class="player-title">Pilih lagu untuk memutar</div>
          <div class="controls">
            <button class="btn">⏮</button>
            <button class="btn primary">Play</button>
            <button class="btn">⏭</button>
          </div>
        </div>

        <div class="playlist-box">
          <div class="section-title">Playlistku</div>
          <p class="muted">Belum ada lagu di playlist</p>
        </div>
      </aside>

    </section>

  </main>

</div>

</body>
</html>
