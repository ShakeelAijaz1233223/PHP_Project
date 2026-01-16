<?php
include "db.php";
if (!isset($_SESSION['email'])) header("Location: login.php");

if(isset($_POST['upload'])){

    $title  = $_POST['title'];
    $artist = $_POST['artist'];

    $file = $_FILES['music']['name'];
    $tmp  = $_FILES['music']['tmp_name'];

    $folder = "uploads/music/";

    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $newName = time() . "_" . $file;

    if(move_uploaded_file($tmp, $folder.$newName)){
        mysqli_query($conn, "INSERT INTO music(title,artist,file) VALUES('$title','$artist','$newName')");
        echo "<script>alert('Music Uploaded Successfully');</script>";
    } else {
        echo "<script>alert('Upload Failed');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Music</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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

<div class="container">
    <h2><i class="fa fa-upload"></i> Upload Music</h2>
    <form method="POST" enctype="multipart/form-data">
        <label for="title">Song Title</label>
        <input class="form-control mb-3" type="text" id="title" name="title" placeholder="Enter Song Title" required>

        
        <label for="music">Select Music File</label>
        <input class="form-control mb-3" type="file" id="music" name="music" required>

        <button class="btn btn-primary" name="upload"><i class="fa fa-music"></i> Upload Music</button>
    </form>
</div>

</body>
</html>
