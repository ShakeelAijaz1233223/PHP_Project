<?php
include "db.php";
if (!isset($_SESSION['email'])) header("Location: login.php");

if(isset($_POST['upload'])){

    $title  = $_POST['title'];
    $artist = $_POST['artist'];

    $file = $_FILES['music']['name'];
    $tmp  = $_FILES['music']['tmp_name'];

    $folder = "uploads/music/";

    // Folder auto create
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
<html>
<head>
<title>Add Music</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Add Music</h2>
    <form method="POST" enctype="multipart/form-data">
        <input class="form-control mb-3" type="text" name="title" placeholder="Song Title" required>
        <input class="form-control mb-3" type="text" name="artist" placeholder="Artist Name" required>
        <input class="form-control mb-3" type="file" name="music" required>
        <button class="btn btn-primary" name="upload">Upload Music</button>
    </form>
</div>

</body>
</html>
