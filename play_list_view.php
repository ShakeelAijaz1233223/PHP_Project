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
    }
}

// ===== DELETE PLAYLIST =====
if(isset($_POST['delete_playlist'])){
    $pid = intval($_POST['playlist_id']);
    mysqli_query($conn, "DELETE FROM playlists WHERE id='$pid'");
    echo "<script>alert('Playlist deleted');</script>";
}

// ===== ADD ITEM TO PLAYLIST =====
if(isset($_POST['add_item'])){
    $pid = intval($_POST['playlist_id']);
    $item_id = intval($_POST['item_id']);
    $item_type = $_POST['item_type'];
    $check = mysqli_query($conn, "SELECT * FROM playlist_items WHERE playlist_id='$pid' AND item_id='$item_id' AND item_type='$item_type'");
    if(mysqli_num_rows($check) == 0){
        mysqli_query($conn, "INSERT INTO playlist_items(playlist_id,item_id,item_type) VALUES('$pid','$item_id','$item_type')");
        echo "<script>alert('Item added to playlist');</script>";
    } else echo "<script>alert('Item already in playlist');</script>";
}

// ===== REMOVE ITEM =====
if(isset($_POST['remove_item'])){
    $piid = intval($_POST['playlist_item_id']);
    mysqli_query($conn, "DELETE FROM playlist_items WHERE id='$piid'");
    echo "<script>alert('Item removed');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Playlists - Videos & Music</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
body{ background:linear-gradient(135deg,#0f2027,#203a43,#2c5364); color:#fff; font-family:Segoe UI,sans-serif; margin:0; padding:0; }
.sidebar{position:fixed;left:0;top:0;width:220px;height:100vh;background:linear-gradient(180deg,#1b1d3a,#000);padding:20px;z-index:1000;}
.sidebar a{display:block;color:#fff;padding:12px;margin-bottom:10px;border-radius:12px;text-decoration:none;transition:0.3s;}
.sidebar a:hover{background:#7b5cff;}
.container{padding:30px 40px;margin-left:240px;transition:0.3s;}
.create-form{background:#111;padding:20px;border-radius:15px;margin-bottom:25px;}
.create-form input, .create-form select{width:100%;padding:12px;border-radius:10px;border:none;margin-bottom:10px;background:#222;color:#fff;}
.create-form button{padding:12px;width:100%;border:none;border-radius:10px;background:#7b5cff;color:#fff;font-weight:bold;cursor:pointer;transition:0.3s;}
.create-form button:hover{background:#a56eff;}
.search-box{width:100%;max-width:400px;padding:10px;border-radius:10px;border:none;margin-bottom:20px;}
.grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:25px;}
.card{background:#111;border-radius:15px;overflow:hidden;cursor:pointer;transform:scale(1);transition:0.4s;animation:fadeUp 0.7s ease;padding:15px;text-align:center;position:relative;}
.card:hover{transform:scale(1.05);box-shadow:0 20px 40px rgba(0,0,0,0.6);}
.title{font-size:16px;margin-bottom:6px;}
.meta{font-size:12px;opacity:0.5;}
.playlist-items{display:none;margin-top:10px;background:#222;padding:10px;border-radius:10px;}
.playlist-items ul{list-style:none;padding:0;margin:0;}
.playlist-items li{padding:6px 0;border-bottom:1px solid #333; display:flex; justify-content:space-between; align-items:center;}
.show{display:block !important;}
.item-btn{padding:4px 8px;border:none;border-radius:5px;background:red;color:#fff;cursor:pointer;}
@keyframes fadeUp{from{opacity:0; transform:translateY(40px);} to{opacity:1; transform:translateY(0);}}
footer{text-align:center;padding:30px;opacity:0.6;}
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
$playlists = mysqli_query($conn, "SELECT p.*, COUNT(pi.id) AS item_count FROM playlists p LEFT JOIN playlist_items pi ON p.id=pi.playlist_id GROUP BY p.id ORDER BY p.id DESC");
while($p = mysqli_fetch_assoc($playlists)){
    $pid = $p['id'];
    $pname = htmlspecialchars($p['name']);
    $count = $p['item_count'];
?>
<div class="card" data-name="<?php echo strtolower($pname); ?>">
    <div class="title"><?php echo $pname; ?></div>
    <div class="meta"><?php echo $count; ?> items</div>

    <form method="POST" style="margin-bottom:10px;">
        <input type="hidden" name="playlist_id" value="<?php echo $pid; ?>">
        <button type="submit" name="delete_playlist" style="padding:6px 10px;border:none;border-radius:6px;background:red;color:#fff;cursor:pointer;margin-bottom:5px;">Delete Playlist</button>
    </form>

    <button onclick="toggleItems(<?php echo $pid; ?>)" style="padding:6px 10px;border:none;border-radius:6px;background:#7b5cff;color:#fff;cursor:pointer;">View/Add Items</button>

    <div class="playlist-items" id="items-<?php echo $pid; ?>">
        <?php
        $items = mysqli_query($conn, "SELECT pi.id as piid, v.title, v.type FROM playlist_items pi JOIN videos v ON pi.item_id=v.id WHERE pi.playlist_id='$pid'");
        if(mysqli_num_rows($items)==0) echo "<p>No items yet.</p>";
        else echo "<ul>";
        while($it = mysqli_fetch_assoc($items)){
            echo '<li>'.$it['title'].' ('.$it['type'].') 
            <form method="POST" style="margin:0;">
                <input type="hidden" name="playlist_item_id" value="'.$it['piid'].'">
                <button type="submit" name="remove_item" class="item-btn">Remove</button>
            </form>
            </li>';
        }
        if(mysqli_num_rows($items)>0) echo "</ul>";
        ?>

        <form method="POST" style="margin-top:10px;">
            <input type="hidden" name="playlist_id" value="<?php echo $pid; ?>">
            <select name="item_type" required>
                <option value="">Select Type</option>
                <option value="video">Video</option>
                <option value="music">Music</option>
            </select>
            <select name="item_id" required>
                <option value="">Select Item</option>
                <?php
                $all_items = mysqli_query($conn, "SELECT * FROM videos");
                while($ai = mysqli_fetch_assoc($all_items)){
                    echo '<option value="'.$ai['id'].'">'.$ai['title'].' ('.$ai['type'].')</option>';
                }
                ?>
            </select>
            <button type="submit" name="add_item">Add to Playlist</button>
        </form>
    </div>
</div>
<?php } ?>
</div>

</div>

<footer>© 2026 Music Platform • Playlists</footer>

<script>
document.getElementById("search").addEventListener("keyup", function(){
    let val = this.value.toLowerCase();
    document.querySelectorAll(".card").forEach(card=>{
        let name = card.dataset.name;
        card.style.display = name.includes(val) ? "block" : "none";
    });
});

function toggleItems(id){
    let div = document.getElementById("items-"+id);
    div.classList.toggle("show");
}
</script>
</body>
</html>
