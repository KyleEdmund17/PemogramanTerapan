<?php
require "../auth.php";
if ($_SESSION['role'] !== 'admin') die("Akses ditolak");

if ($_FILES['song']['type'] !== 'audio/mpeg') {
    die("File harus MP3");
}

$fileName = time() . "_" . basename($_FILES['song']['name']);
$target = "../songs/" . $fileName;

move_uploaded_file($_FILES['song']['tmp_name'], $target);

$songs = json_decode(file_get_contents("../data/songs.json"), true);

$songs[] = [
  "title" => htmlspecialchars($_POST['title']),
  "artist" => htmlspecialchars($_POST['artist']),
  "file" => $fileName
];

file_put_contents("../data/songs.json", json_encode($songs, JSON_PRETTY_PRINT));

header("Location: dashboard.php?success=1");
