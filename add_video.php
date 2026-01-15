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
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h2>Add Video</h2>
    <form method="POST" enctype="multipart/form-data">
        <input class="form-control mb-3" type="text" name="title" placeholder="Video Title" required>
        <input class="form-control mb-3" type="file" name="video" required>
        <button class="btn btn-primary" name="upload">Upload Video</button>
    </form>
</div>

</body>
</html>
