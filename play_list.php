<?php
include "db.php";

session_start();
if(!isset($_SESSION['email'])) header("Location: login.php");

// ===== CREATE PLAYLIST =====
if(isset($_POST['create_playlist'])){
    $playlist_name = mysqli_real_escape_string($conn, $_POST['playlist_name']);
    $created_by = $_SESSION['email'];
    if(!empty($playlist_name)){
        mysqli_query($conn, "INSERT INTO playlists(name, created_by) VALUES('$playlist_name','$created_by')");
        echo "<script>alert('Playlist Created Successfully');</script>";
    } else {
        echo "<script>alert('Please enter a playlist name');</script>";
    }
}

// ===== ADD VIDEO TO PLAYLIST =====
if(isset($_POST['add_video'])){
    $playlist_id = intval($_POST['playlist_id']);
    $video_id = intval($_POST['video_id']);
    $check = mysqli_query($conn, "SELECT * FROM playlist_videos WHERE playlist_id='$playlist_id' AND video_id='$video_id'");
    if(mysqli_num_rows($check) == 0){
        mysqli_query($conn, "INSERT INTO playlist_videos(playlist_id, video_id) VALUES('$playlist_id','$video_id')");
        echo "<script>alert('Video added to playlist');</script>";
    } else {
        echo "<script>alert('Video already in playlist');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>All-in-One Playlists</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    color:#fff;
    min-height:100vh;
    font-family:Segoe UI, sans-serif;
    margin:0; padding:0;
}
.sidebar{
    position:fixed; left:0; top:0; width:220px; height:100vh;
    background:linear-gradient(180deg,#1b1d3a,#000); padding:20px; z-index:1000;
}
.sidebar a{
    display:block; color:#fff; padding:12px; margin-bottom:10px;
    border-radius:12px; text-decoration:none; transition:0.3s;
}
.sidebar a:hover{ background:#7b5cff; }
.container{ padding:30px 40px; margin-left:240px; transition: margin-left 0.3s; }
.create-form{ background:#111; padding:20px; border-radius:15px; margin-bottom:25px; }
.create-form input, .create-form select{ width:100%; padding:12px; border-radius:10px; border:none; margin-bottom:10px; background:#222; color:#fff; }
.create-form button{ padding:12px; width:100%; border:none; border-radius:10px; background:#7b5cff; color:#fff; font-weight:bold; cursor:pointer; transition:0.3s; }
.create-form button:hover{ background:#a56eff; }
.search-box{ width:100%; max-width:400px; padding:10px; border-radius:10px; border:none; margin-bottom:20px; }
.grid{ display:grid; grid-template-columns:repeat(auto-fill,minmax(260px,1fr)); gap:25px; }
.card{ background:#111; border-radius:15px; overflow:hidden; cursor:pointer; transform:scale(1); transition:0.4s; animation:fadeUp 0.7s ease; padding:15px; text-align:center; position:relative; }
.card:hover{ transform:scale(1.05); box-shadow:0 20px 40px rgba(0,0,0,0.6); }
.title{ font-size:16px; margin-bottom:6px; }
.meta{ font-size:12px; opacity:0.5; }
.playlist-videos{ display:none; margin-top:10px; background:#222; padding:10px; border-radius:10px; }
.playlist-videos ul{ list-style:none; padding:0; margin:0; }
.playlist-videos li{ padding:6px 0; border-bottom:1px solid #333; }
.show{ display:block !important; }
@keyframes fadeUp{ from{opacity:0; transform:translateY(40px);} to{opacity:1; transform:translateY(0);} }
footer{text-align:center; padding:30px; opacity:0.6;}
@media (max-width: 1024px){ .container{ margin-left:200px; padding:20px;} }
@media (max-width: 768px){ .sidebar{ width:70px; padding:10px;} .sidebar a{ padding:10px 5px; font-size:12px;} .container{ margin-left:80px; padding:15px;} .grid{ gap:15px;} }
@media (max-width: 480px){ .container{ margin-left:0; padding:10px;} .grid{ grid-template-columns:1fr; gap:15px;} .search-box{ width:100%; font-size:14px; padding:8px;} }
</style>
</head>
<body>

<div class="sidebar">
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="add_music.php">⬆ Upload Music</a>
    <a href="play_list_view.php">🎵 Playlists</a>
    <a href="logout.php">Logout</a>
</div>

<div class="container">
    <!-- CREATE PLAYLIST -->
    <div class="create-form">
        <h2>Create Playlist</h2>
        <form method="POST">
            <input type="text" name="playlist_name" placeholder="Enter Playlist Name" required>
            <button type="submit" name="create_playlist">Create Playlist</button>
        </form>
    </div>

    <input type="text" id="search" class="search-box" placeholder="🔍 Search playlist">

    <!-- PLAYLIST GRID -->
    <div class="grid" id="playlistGrid">
        <?php
        $playlists = mysqli_query($conn, "SELECT p.*, COUNT(pv.id) as video_count 
                                         FROM playlists p
                                         LEFT JOIN playlist_videos pv ON p.id = pv.playlist_id
                                         GROUP BY p.id
                                         ORDER BY p.id DESC");
        while($p = mysqli_fetch_assoc($playlists)){
            $pid = $p['id'];
            $pname = htmlspecialchars($p['name']);
            $vcount = $p['video_count'];
        ?>
        <div class="card" data-name="<?php echo strtolower($pname); ?>">
            <div class="title"><?php echo $pname; ?></div>
            <div class="meta"><?php echo $vcount; ?> videos</div>
            <button onclick="toggleVideos(<?php echo $pid; ?>)" style="margin-top:10px; padding:6px 10px; border:none; border-radius:6px; background:#7b5cff; color:#fff; cursor:pointer;">View/Add Videos</button>
            
            <div class="playlist-videos" id="videos-<?php echo $pid; ?>">
                <?php
                $videos = mysqli_query($conn, "SELECT v.* 
                                               FROM videos v 
                                               INNER JOIN playlist_videos pv ON v.id = pv.video_id
                                               WHERE pv.playlist_id='$pid'");
                if(mysqli_num_rows($videos) == 0) echo "<p>No videos yet.</p>";
                else echo "<ul>";
                while($v = mysqli_fetch_assoc($videos)){
                    echo "<li>".htmlspecialchars($v['title'])."</li>";
                }
                if(mysqli_num_rows($videos) > 0) echo "</ul>";
                ?>

                <form method="POST" style="margin-top:10px;">
                    <input type="hidden" name="playlist_id" value="<?php echo $pid; ?>">
                    <select name="video_id" required>
                        <option value="">Select Video to Add</option>
                        <?php
                        $all_videos = mysqli_query($conn, "SELECT * FROM videos");
                        while($av = mysqli_fetch_assoc($all_videos)){
                            echo '<option value="'.$av['id'].'">'.htmlspecialchars($av['title']).'</option>';
                        }
                        ?>
                    </select>
                    <button type="submit" name="add_video">Add Video</button>
                </form>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<footer>© 2026 Music Platform • Playlists</footer>

<script>
// Search functionality
document.getElementById("search").addEventListener("keyup", function(){
    let val = this.value.toLowerCase();
    document.querySelectorAll(".card").forEach(card=>{
        let name = card.dataset.name;
        card.style.display = name.includes(val) ? "block" : "none";
    });
});

// Toggle playlist videos
function toggleVideos(id){
    let div = document.getElementById("videos-" + id);
    if(div.classList.contains("show")) div.classList.remove("show");
    else div.classList.add("show");
}
</script>

</body>
</html>
