<?php
require "../auth.php";
if ($_SESSION['role'] !== 'admin') die("Akses ditolak");

$index = $_POST['index'];

$songs = json_decode(file_get_contents("../data/songs.json"), true);

// Hapus file mp3
$filePath = "../songs/" . $songs[$index]['file'];
if (file_exists($filePath)) {
    unlink($filePath);
}

// Hapus data dari array
array_splice($songs, $index, 1);

// Simpan ulang JSON
file_put_contents("../data/songs.json", json_encode($songs, JSON_PRETTY_PRINT));

header("Location: dashboard.php?deleted=1");
