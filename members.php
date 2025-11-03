<?php include 'db.php'; ?>
<?php if(!isset($_SESSION['user'])) header("Location: index.php"); ?>

<!DOCTYPE html>
<html>
<head>
    <title>Members List</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="navbar">
    <a href="dashboard.php">Dashboard</a>
    <a href="add_member.php">Add Member</a>
    <a href="logout.php" class="logout">Logout</a>
</div>

<div class="content">
    <h2>Members</h2>
    <form method="GET">
        <input type="text" name="search" placeholder="Search by name">
        <button>Search</button>
    </form>

    <?php
    $search = $_GET['search'] ?? '';
    $result = mysqli_query($conn, "SELECT * FROM members WHERE name LIKE '%$search%'");
    ?>

    <table>
        <tr><th>Image</th><th>Name</th><th>Plan</th><th>Fee</th><th>Action</th></tr>
        <?php while($m=mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><img src="uploads/<?= $m['image'] ?>" width="50"></td>
                <td><?= $m['name'] ?></td>
                <td><?= $m['plan'] ?></td>
                <td>$<?= $m['fee'] ?></td>
                <td>
                    <a href="member_details.php?id=<?= $m['id'] ?>">View</a> |
                    <a href="edit_member.php?id=<?= $m['id'] ?>">Edit</a> |
                    <a href="delete_member.php?id=<?= $m['id'] ?>">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
</body>
</html>
