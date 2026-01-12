<?php
require "auth.php";

$playlist = json_decode(file_get_contents("data/song.json"), true) ?? [];
?>

<!DOCTYPE html>
<html>
<head>
  <title>Playlistku - Irantara</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/playlist.css">
</head>
<body>

<div class="app-container">

  <!-- SIDEBAR -->
  <div class="sidebar">
    <div class="logo">
      <div class="dot">I</div>
      <div>Irantara</div>
    </div>

    <a class="nav-item" href="index.php">Discover</a>
    <a class="nav-item active" href="playlist.php">Playlistku</a>

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
  </div>

  <!-- MAIN -->
  <div class="main-content">

    <!-- TOP BAR -->
    <div class="topbar">
      <input type="text" class="search" placeholder="Cari lagu, daerah, atau seniman">
      <div class="user-info">
        USER | <a href="logout.php">Logout</a>
      </div>
    </div>

    <h2 class="page-title">Playlistku</h2>

    <div class="playlist-grid">

      <?php if (count($playlist) == 0): ?>
        <div class="empty">Belum ada lagu di playlist</div>
      <?php endif; ?>

      <?php foreach ($playlist as $song): ?>
        <div class="playlist-card">
          <div class="thumb" style="background-image:url('assets/img/default.jpg')"></div>
          <div class="info">
            <h4><?= $song['title'] ?></h4>
            <p><?= $song['artist'] ?></p>
          </div>
          <audio controls>
            <source src="songs/<?= $song['file'] ?>" type="audio/mpeg">
          </audio>
        </div>
      <?php endforeach; ?>

    </div>

  </div>
</div>

</body>
</html>
