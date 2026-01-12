<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $users = json_decode(file_get_contents("data/users.json"), true);

  foreach ($users as $u) {
    if (
      $_POST['username'] === $u['username'] &&
      $_POST['password'] === $u['password']
    ) {
      $_SESSION['login'] = true;
      $_SESSION['role'] = $u['role'];
      header("Location: index.php");
      exit;
    }
  }
  $error = "Username atau password salah";
}
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Irantara — Masuk</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<div class="auth-wrapper">

  <div class="auth-card">
    <div class="auth-logo">
      <div class="dot">I</div>
      <div>Irantara</div>
    </div>

    <div class="auth-sub">
      Masuk untuk menjelajahi musik tradisional nusantara
    </div>

    <?php if(isset($error)): ?>
      <div class="auth-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
      <input name="username" placeholder="Username" required>
      <input name="password" type="password" placeholder="Password" required>
      <button>Masuk</button>
    </form>
  </div>

</div>

</body>
</html>
