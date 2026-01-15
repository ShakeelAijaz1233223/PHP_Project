<?php
include "db.php";


if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$musicCount = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM music"));
$videoCount = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM videos"));
$userCount  = mysqli_num_rows(mysqli_query($conn,"SELECT id FROM users"));
?>

<!DOCTYPE html>
<html>`
<head>
<title>Music Admin Dashboard</title>`
<link rel="stylesheet" href="style.css">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">


</head>
<body>

<div class="container-fluid">
<div class="row">

<!-- Sidebar -->
<div class="col-md-2 sidebar p-3">

    <h4 class="mb-4"><i class="fa-solid fa-music"></i> Music Admin</h4>

    <a href="dashboard.php"><i class="fa fa-home"></i> Dashboard</a>
    <a href="songs.php"><i class="fa fa-music"></i> Songs</a>
    <a href="artists.php"><i class="fa fa-microphone"></i> Artists</a>
    <a href="albums.php"><i class="fa fa-compact-disc"></i> Albums</a>
    <a href="users.php"><i class="fa fa-users"></i> Users</a>

    <hr>
    <a href="add_music.php"><i class="fa fa-upload"></i> Add Music</a>
    <a href="add_video.php"><i class="fa fa-video"></i> Add Video</a>

    <hr>
    <a href="logout.php" class="text-danger"><i class="fa fa-sign-out"></i> Logout</a>

</div>

<!-- Main -->
<div class="col-md-10 p-4">

<h2 class="mb-4" style="color: white;">Welcome, <?php echo $_SESSION['email']; ?> 🎵</h2>

<!-- Stats -->
<div class="row mb-4">

<div class="col-md-3">
<div class="stat-card" style="background:linear-gradient(135deg,#4facfe,#00f2fe)">
Total Music<br><b><?php echo $musicCount; ?></b>
</div>
</div>

<div class="col-md-3">
<div class="stat-card" style="background:linear-gradient(135deg,#43e97b,#38f9d7)">
Total Videos<br><b><?php echo $videoCount; ?></b>
</div>
</div>

<div class="col-md-3">
<div class="stat-card" style="background:linear-gradient(135deg,#fbc531,#e84118)">
Total Users<br><b><?php echo $userCount; ?></b>
</div>
</div>

<div class="col-md-3">
<div class="stat-card" style="background:linear-gradient(135deg,#fa709a,#fee140)">
Total Plays<br><b>120K</b>
</div>
</div>

</div>

<!-- Latest Music -->
<div class="table-box">
<h4>🎵 Latest Uploaded Music</h4>

<div class="media-grid">

<?php
$q = mysqli_query($conn,"SELECT * FROM music ORDER BY id DESC LIMIT 6");
while($row=mysqli_fetch_assoc($q)){
?>
<div class="media-box">

    <div class="media-title">
        🎵 <?php echo $row['title']; ?> — <?php echo $row['artist']; ?>
    </div>

    <audio controls>
        <source src="uploads/music/<?php echo $row['file']; ?>">
    </audio>

</div>
<?php } ?>

</div>
</div>

<!-- Latest Videos -->
<div class="table-box">
<h4>🎬 Latest Uploaded Videos</h4>

<div class="media-grid">

<?php
$q = mysqli_query($conn,"SELECT * FROM videos ORDER BY id DESC LIMIT 6");
while($row=mysqli_fetch_assoc($q)){
?>
<div class="media-box">

    <video controls>
        <source src="uploads/videos/<?php echo $row['file']; ?>">
    </video>

    <div class="media-title">
        <?php echo $row['title']; ?>
    </div>

</div>
<?php } ?>

</div>
</div>

</div>
</div>

</body>
</html>
