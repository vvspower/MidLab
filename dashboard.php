<?php include 'db.php'; ?>
<?php if(!isset($_SESSION['user'])) header("Location: index.php"); ?>

<?php
$totalMembers = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS count FROM members"))['count'];
$totalPlans = 3; // Basic, Standard, Premium
?>

<!-- 4343 -->

<?php
$totalMembers = mysqli_fetch_assoc(mysqli_query($conn,"SELECT COUNT(*) AS count FROM members"))['count'];
$totalPlans = 3; // Basic, Standard, Premium
$totalSales = mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM(fee) AS total FROM members"))['total'];
$totalSales = $totalSales ? $totalSales : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="navbar">
    <div class="logo">Fitness Center</div>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="members.php">Members</a>
        <a href="membership_plans.php">Plans</a>
        <a href="add_member.php">Add Member</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>

<div class="content">
    <h1>Welcome, <?= $_SESSION['user']['name'] ?> 👋</h1>
    <div style="display:flex;">
        <div class="card">
            <h3>Total Members</h3>
            <p style="font-size:24px;font-weight:bold;"><?= $totalMembers ?></p>
            <a href="members.php"><button>View Members</button></a>
        </div>

        <div class="card">
            <h3>Membership Plans</h3>
            <p style="font-size:24px;font-weight:bold;"><?= $totalPlans ?></p>
            <a href="membership_plans.php"><button>View Plans</button></a>
        </div>

        <div class="card">
            <h3>Add New Member</h3>
            <br><br><br>
            <a href="add_member.php"><button>Add Member</button></a>
        </div>
    </div>
    <div style="width:940px;" class="card">
        <h3>Total Sales 💰</h3>
        <p style="font-size:24px;font-weight:bold;">$<?= number_format($totalSales,2) ?></p>
    </div>
    
</div>
</body>
</html>
