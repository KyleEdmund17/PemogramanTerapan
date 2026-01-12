<?php
require "../auth.php";
if ($_SESSION['role'] !== 'admin') die("Akses ditolak");

$songs = json_decode(file_get_contents("../data/songs.json"), true);
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Irantara — Admin</title>
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="app">

  <!-- SIDEBAR -->
  <aside class="sidebar">
    <div class="logo">
      <div class="dot">I</div><div>Irantara</div>
    </div>

    <nav>
      <a class="nav-item active">Admin</a>
      <a class="nav-item" href="../index.php">Discover</a>
      <a class="nav-item" href="../logout.php">Logout</a>
    </nav>
  </aside>

  <!-- MAIN -->
  <main class="main">
    <header class="topbar">
      <h2>Dashboard Admin</h2>
    </header>

    <!-- Upload -->
    <section class="card" style="margin-bottom:20px">
      <h3>Upload Lagu</h3>
      <form action="upload.php" method="POST" enctype="multipart/form-data">
        <input class="btn" name="title" placeholder="Judul Lagu" required>
        <input class="btn" name="artist" placeholder="Artis" required>
        <input class="btn" type="file" name="song" accept=".mp3" required>
        <button class="btn primary">Upload</button>
      </form>
    </section>

    <!-- List Lagu -->
    <section>
      <h3 style="margin-bottom:10px">Daftar Lagu</h3>

      <div class="cards">
        <?php foreach ($songs as $i => $s): ?>
          <article class="card">
            <h4><?= $s['title'] ?></h4>
            <p class="muted"><?= $s['artist'] ?></p>

            <form action="delete.php" method="POST">
              <input type="hidden" name="index" value="<?= $i ?>">
              <button class="btn danger"
                onclick="return confirm('Yakin hapus lagu ini?')">
                🗑 Hapus
              </button>
            </form>
          </article>
        <?php endforeach; ?>
      </div>
    </section>
  </main>

</div>
</body>
</html>
