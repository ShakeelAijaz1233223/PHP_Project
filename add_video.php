<?php
include "db.php";
if (!isset($_SESSION['email'])) header("Location: login.php");

if(isset($_POST['upload'])){

    $title = $_POST['title'];

    $file = $_FILES['video']['name'];
    $tmp  = $_FILES['video']['tmp_name'];

    $folder = "uploads/videos/";

    // Folder check
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $newName = time() . "_" . $file;

    if(move_uploaded_file($tmp, $folder.$newName)){
        mysqli_query($conn, "INSERT INTO videos(title,file) VALUES('$title','$newName')");
        echo "<script>alert('Video Uploaded Successfully');</script>";
    } else {
        echo "<script>alert('Upload Failed');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Add Video</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body {
    background: #121212;
    font-family: 'Segoe UI', sans-serif;
    color: #fff;
    background: url('bg.jpg') no-repeat center center;
    background-size: auto;
    position: relative;
}

/* BACK BUTTON */
.back-btn {
    position: sticky;
    top: 10px;
    left: 20px;
    background: #000;
    color: #fff;
    padding: 10px 18px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: bold;
    transition: all 0.3s ease;
    box-shadow: 0 0 10px rgba(0,0,0,0.5);
}

.back-btn:hover {
    background: #1daa35;
    color: #000;
}

.container {
    max-width: 500px;
    margin-top: 80px;
    background: #1f1f1f;
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 0 20px rgba(255,111,97,0.3);
}

h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #ffffff;
}

.form-control {
    background: #2a2a2a;
    border: 1px solid #444;
    color: #fff;
    border-radius: 12px;
    padding: 12px;
    transition: all 0.2s;
}

.form-control:focus {
    border-color: #ffffff;
    box-shadow: 0 0 8px #000000;
    background: #2a2a2a;
    color: #fff;
}

.btn-primary {
    width: 100%;
    background: #000000;
    border: none;
    padding: 12px;
    font-weight: bold;
    font-size: 16px;
    border-radius: 12px;
    transition: all 0.3s;
}

.btn-primary:hover {
    background: #ffffff;
    color: #000000;
}

input[type="file"] {
    padding: 5px;
    border-radius: 10px;
    color: #fff;
}

label {
    display: block;
    margin-bottom: 5px;
    color: #1daa35;
    font-weight: bold;
}
</style>
</head>

<body>

<!-- BACK BUTTON -->
<a href="dashboard.php" class="back-btn">
    <i class="fa fa-arrow-left"></i> Back
</a>

<div class="container">
    <h2>Add Video</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="title">Video Title</label>
        <input id="title" class="form-control mb-3" type="text" name="title" placeholder="Enter video title" required>

        <label for="video">Select Video</label>
        <input id="video" class="form-control mb-3" type="file" name="video" required>

        <button class="btn btn-primary" name="upload">Upload Video</button>
    </form>
</div>


</body>
</html>
