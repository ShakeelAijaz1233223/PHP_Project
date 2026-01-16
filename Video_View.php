<?php
include "db.php";

if (!isset($_SESSION['email'])) header("Location: login.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Video Gallery</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body{
    background:linear-gradient(135deg,#0f2027,#203a43,#2c5364);
    color:#fff;
    min-height:100vh;
    font-family:Segoe UI, sans-serif;
}

/* ===== SIDEBAR ===== */
.sidebar{
    position:fixed;
    left:0;
    top:0;
    width:220px;
    height:100vh;
    background:linear-gradient(180deg,#1b1d3a,#000);
    padding:20px;
}

.sidebar a{
    display:block;
    color:#fff;
    padding:12px;
    margin-bottom:10px;
    border-radius:12px;
    text-decoration:none;
    transition:0.3s;
}

.sidebar a:hover{
    background:#7b5cff;
}

/* ===== VIDEO GRID ===== */
.container{
    padding:30px 40px;
    margin-left:240px;
}

.search-box{
    width:100%;
    max-width:400px;
    padding:10px;
    border-radius:10px;
    border:none;
    margin-bottom:20px;
}

.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(260px,1fr));
    gap:25px;
}

.card{
    background:#111;
    border-radius:15px;
    overflow:hidden;
    cursor:pointer;
    transform:scale(1);
    transition:0.4s;
    animation:fadeUp 0.7s ease;
}

.card:hover{
    transform:scale(1.05);
    box-shadow:0 20px 40px rgba(0,0,0,0.6);
}

.thumbnail{
    height:160px;
    background:#222;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:24px;
    font-weight:bold;
    color:#fff;
}

.info{
    padding:15px;
}

.title{
    font-size:16px;
    margin-bottom:6px;
}

.meta{
    font-size:13px;
    opacity:0.7;
}

@keyframes fadeUp{
    from{opacity:0; transform:translateY(40px);}
    to{opacity:1; transform:translateY(0);}
}

footer{
    text-align:center;
    padding:30px;
    opacity:0.6;
}
</style>
</head>
<body>

<!-- SIDEBAR -->
<div class="sidebar">
    <a href="dashboard.php">🏠 Dashboard</a>
    <a href="add_video.php">⬆ Upload Video</a>
    <a href="#">❓ Help</a>
</div>

<!-- CONTENT -->
<div class="container">
    <input type="text" id="search" class="search-box" placeholder="🔍 Search video title">
    <div class="grid" id="videoGrid">
        <?php
        $res = mysqli_query($conn, "SELECT * FROM videos ORDER BY id DESC");
        while($row = mysqli_fetch_assoc($res)){
            $title = htmlspecialchars($row['title']);
            $file  = $row['file'];
            echo '
            <div class="card" data-title="'.strtolower($title).'">
                <div class="thumbnail">
                    <video width="100%" height="100%" src="uploads/videos/'.$file.'" controls></video>
                </div>
                <div class="info">
                    <div class="title">'.$title.'</div>
                    <div class="meta">Uploaded</div>
                </div>
            </div>
            ';
        }
        ?>
    </div>
</div>

<footer>
    © 2026 Video Platform • Fully Animated Page
</footer>

<script>
// ===== SEARCH FUNCTIONALITY =====
document.getElementById("search").addEventListener("keyup", function(){
    let val = this.value.toLowerCase();
    document.querySelectorAll(".card").forEach(card => {
        card.style.display = card.dataset.title.includes(val) ? "block" : "none";
    });
});
</script>

</body>
</html>
