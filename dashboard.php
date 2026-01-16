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
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Music Admin Dashboard</title>

<!-- Bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
<style>

</style>
</head>
<body>

<div class="sidebar">
    <h2><i class="fa-solid fa-music"></i> Music Admin</h2>
    <a href="dashboard.php"><i class="fa fa-house"></i> Dashboard</a>
    <a href="Music_View.php"><i class="fa fa-music"></i> Musics</a>
   <a href="Video_View.php"><i class="fa fa-video"></i> Videos</a>
    <a href="play_list.php"><i class="fa fa-compact-disc"></i> Play List</a>
    <a href="users.php"><i class="fa fa-users"></i> Users</a>
    <hr style="border-color: #333;">
    <a href="add_music.php"><i class="fa fa-upload"></i> Upload Music</a>
    <a href="add_video.php"><i class="fa fa-video"></i> Upload Video</a>
    <hr style="border-color: #333;">
    <a href="logout.php" class="text-danger"><i class="fa fa-right-from-bracket"></i> Logout</a>
</div>



<div class="main-content">
    <h1>Welcome, <?php echo $_SESSION['email']; ?> </h1>

    <div class="row">
        <div class="col-md-4" >
            <div class="card-stats">
                <p>Total Music</p>
                <h3><?php echo $musicCount; ?></h3>
            </div>  
        </div>
        <div class="col-md-4">
            <div class="card-stats">
                <p>Total Videos</p>
                <h3><?php echo $videoCount; ?></h3>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-stats">
                <p>Total Users</p>
                <h3><?php echo $userCount; ?></h3>
            </div>
        </div>
        
    </div>
    

    

    <div class="hero-section">
        <h4>🔥 Trending Now</h4>
        <h1>Top Tracks Worldwide</h1>
        <button>▶ Play </button>
        <button>⭐ Follow</button>
    </div>

    

</div>

</body>
</html>

