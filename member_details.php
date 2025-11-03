<?php
include 'db.php';
if(!isset($_SESSION['user'])) header("Location: index.php");

$id = $_GET['id'] ?? 0;
$result = mysqli_query($conn,"SELECT * FROM members WHERE id=$id");
$member = mysqli_fetch_assoc($result);

if(!$member){
    echo "<p>Member not found.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Member Details</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .member-card {
            max-width: 600px;
            margin: 40px auto;
            background: #f5f5f5;
            border-radius: 12px;
            box-shadow: 0 0 10px rgba(0,0,0,0.2);
            padding: 20px;
            text-align: center;
        }
        .member-card img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 20px;
            border: 3px solid #5a2ca0;
        }
        .member-card h2 { color: #5a2ca0; margin-bottom: 10px; }
        .member-card p { font-size: 16px; margin: 5px 0; }
        .back-btn {
            display:inline-block;
            margin-top: 15px;
            padding: 8px 15px;
            background:#5a2ca0;
            color:white;
            border-radius:6px;
            text-decoration:none;
        }
    </style>
</head>
<body>
<div class="navbar">
    <div class="logo">Fitness Center</div>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="members.php">Members</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>

<div class="content">
    <div class="member-card">
        <img src="uploads/<?= $member['image'] ?>" alt="Member Image">
        <h2><?= $member['name'] ?></h2>
        <p><strong>Email:</strong> <?= $member['email'] ?></p>
        <p><strong>Phone:</strong> <?= $member['phone'] ?></p>
        <p><strong>Membership Plan:</strong> <?= $member['plan'] ?></p>
        <p><strong>Months:</strong> <?= $member['months'] ?? 1 ?></p>
        <p><strong>Total Fee Paid:</strong> $<?= number_format($member['fee'],2) ?></p>
        <p><strong>Joined On:</strong> <?= $member['join_date'] ?></p>

        <a href="members.php" class="back-btn">Back to Members</a>
    </div>
</div>
</body>
</html>
