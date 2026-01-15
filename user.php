<?php
include "db.php";
if(!isset($_SESSION['email'])) header("Location: login.php");
?>

<h2>All Users</h2>

<table border="1" cellpadding="10">
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
</tr>

<?php
$data = mysqli_query($conn,"SELECT * FROM users");
while($row = mysqli_fetch_assoc($data)){
?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['name'] ?></td>
    <td><?= $row['email'] ?></td>
</tr>
<?php } ?>
</table>
