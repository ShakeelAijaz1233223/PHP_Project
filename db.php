<?php
$conn = mysqli_connect("localhost", "root", "", "music_admin");

if (!$conn) {
    die("Database Connection Failed: " . mysqli_connect_error());
}
session_start();
?>
    