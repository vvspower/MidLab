<?php include 'db.php'; ?>
<?php if(!isset($_SESSION['user'])) header("Location: index.php"); ?>

<?php
$id = $_GET['id'];
mysqli_query($conn,"DELETE FROM members WHERE id='$id'");
header("Location: members.php");
