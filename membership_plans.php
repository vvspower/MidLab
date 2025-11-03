<?php
include 'db.php';
if(!isset($_SESSION['user'])) header("Location: index.php");

// Define plan prices
$plans = [
    "Basic" => 30,
    "Standard" => 50,
    "Premium" => 80
];

// Fetch stats for each plan
$planStats = [];
foreach($plans as $planName => $price){
    $memberQuery = mysqli_query($conn,"SELECT * FROM members WHERE plan='$planName'");
    $membersCount = mysqli_num_rows($memberQuery);
    $totalMonths = 0;
    $totalRevenue = 0;

    while($m = mysqli_fetch_assoc($memberQuery)){
        $totalMonths += $m['months'] ?? 0;   // FIXED: default to 0 if key missing
        $totalRevenue += $m['fee'] ?? 0;     // FIXED: default to 0 if key missing
    }

    $avgRevenue = $membersCount > 0 ? $totalRevenue / $membersCount : 0;

    $planStats[$planName] = [
        "price"=>$price,
        "members"=>$membersCount,
        "months"=>$totalMonths,
        "revenue"=>$totalRevenue,
        "avg"=>$avgRevenue
    ];
}

// Calculate overall totals
$totalRevenueAll = array_sum(array_column($planStats, 'revenue'));
$totalMembersAll = array_sum(array_column($planStats, 'members'));
$totalMonthsAll = array_sum(array_column($planStats, 'months'));
?>

<!DOCTYPE html>
<html>
<head>
    <title>Membership Plans - Money Stats</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        table { width: 100%; border-collapse: collapse; margin-top:20px; }
        table, th, td { border:1px solid #ccc; }
        th, td { padding: 10px; text-align:center; }
        th { background: #5a2ca0; color:white; }
        td { background:#f5f5f5; }
        h2 { color:#5a2ca0; }
        .totals { margin-top:20px; font-weight:bold; }
        .navbar { display:flex; justify-content:space-between; background:#5a2ca0; padding:10px; color:white; }
        .navbar a { color:white; margin-left:15px; text-decoration:none; }
    </style>
</head>
<body>
<div class="navbar">
    <div class="logo">Fitness Center</div>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="members.php">Members</a>
        <a href="logout.php">Logout</a>
    </div>
</div>

<div class="content">
    <h2>Membership Revenue Stats</h2>
    <table>
        <tr>
            <th>Plan</th>
            <th>Price / Month</th>
            <th>Members</th>
            <th>Total Months Sold</th>
            <th>Total Revenue</th>
            <th>Avg Revenue / Member</th>
        </tr>
        <?php foreach($planStats as $plan=>$stats): ?>
        <tr>
            <td><?= $plan ?></td>
            <td>$<?= $stats['price'] ?></td>
            <td><?= $stats['members'] ?></td>
            <td><?= $stats['months'] ?></td>
            <td>$<?= number_format($stats['revenue'],2) ?></td>
            <td>$<?= number_format($stats['avg'],2) ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <div class="totals">
        <p>Total Members: <?= $totalMembersAll ?></p>
        <p>Total Months Sold: <?= $totalMonthsAll ?></p>
        <p>Total Revenue: $<?= number_format($totalRevenueAll,2) ?></p>
    </div>
</div>
</body>
</html>
