<?php
require "auth.php";
$songs = json_decode(file_get_contents("data/songs.json"), true);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Music Player</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="card">
  <h2>🎶 Music Player</h2>

  <?php foreach ($songs as $song): ?>
    <p><b><?= $song['title'] ?></b> - <?= $song['artist'] ?></p>
    <audio controls style="width:100%">
      <source src="songs/<?= $song['file'] ?>" type="audio/mpeg">
    </audio>
    <hr style="margin:15px 0">
  <?php endforeach; ?>

  <?php if ($_SESSION['role'] === 'admin'): ?>
    <a class="logout" href="admin/dashboard.php">⚙ Admin Dashboard</a>
  <?php endif; ?>

  <a class="logout" href="logout.php">🚪 Logout</a>
</div>

</body>
</html>
