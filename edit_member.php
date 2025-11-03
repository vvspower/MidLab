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

$planPrices = ["Basic"=>30,"Standard"=>50,"Premium"=>80];

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $plan = $_POST['plan'];
    $months = (int)$_POST['months'];
    $price = $planPrices[$plan];
    $discount = $months >= 6 ? 0.1 : 0;
    $fee = $price * $months * (1-$discount);

    $image = $_FILES['image']['name'] ? $_FILES['image']['name'] : $member['image'];
    if($_FILES['image']['name']){
        move_uploaded_file($_FILES['image']['tmp_name'], "uploads/".$image);
    }

    if($name && $email && $plan && $months > 0){
        mysqli_query($conn,"UPDATE members SET 
            name='$name', email='$email', phone='$phone', plan='$plan', 
            months='$months', fee='$fee', image='$image'
            WHERE id=$id
        ");
        echo "<script>alert('Member updated successfully');window.location='members.php';</script>";
    } else {
        echo "<p class='error'>Please fill all fields!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Member</title>
    <link rel="stylesheet" href="assets/style.css">
    <style>
        .plan-btn { 
            padding: 10px 20px; 
            margin: 5px; 
            border-radius: 6px; 
            border: none; 
            cursor: pointer; 
            font-weight: bold;
        }
        .plan-btn.selected { background: #5a2ca0; color: white; }
        .plan-btn.basic { background: #d1b3ff; }
        .plan-btn.standard { background: #a366ff; color:white; }
        .plan-btn.premium { background: #5a2ca0; color:white; }
        .calc-display { margin-top: 15px; font-weight:bold; font-size:18px; }
    </style>
    <script>
        let planPrices = { Basic:30, Standard:50, Premium:80 };

        function selectPlan(plan, btn){
            document.getElementById('planInput').value = plan;
            updateFee();
            document.querySelectorAll('.plan-btn').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
        }

        function updateFee(){
            let plan = document.getElementById('planInput').value;
            let months = parseInt(document.getElementById('monthsInput').value) || 1;
            if(plan){
                let price = planPrices[plan];
                let discount = months >= 6 ? 0.1 : 0;
                let total = price * months * (1 - discount);
                document.getElementById('feeInput').value = total.toFixed(2);
                document.getElementById('calcDisplay').innerText = "Total Fee: $" + total.toFixed(2) + (discount>0 ? " (10% discount applied)" : "");
            }
        }

        window.onload = function(){
            const currentPlan = '<?= $member['plan'] ?>';
            const currentMonths = <?= $member['months'] ?? 1 ?>;
            document.getElementById('planInput').value = currentPlan;
            document.getElementById('monthsInput').value = currentMonths;
            document.querySelectorAll('.plan-btn').forEach(btn=>{
                if(btn.innerText.includes(currentPlan)) btn.classList.add('selected');
            });
            updateFee();
        }
    </script>
</head>
<body>
<div class="navbar">
    <div class="logo">Fitness Center</div>
    <div>
        <a href="dashboard.php">Dashboard</a>
        <a href="members.php">Back to Members</a>
        <a href="logout.php" class="logout">Logout</a>
    </div>
</div>

<div class="content">
    <h2>Edit Member</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Full Name" value="<?= $member['name'] ?>" required><br>
        <input type="email" name="email" placeholder="Email" value="<?= $member['email'] ?>" required><br>
        <input type="text" name="phone" placeholder="Phone" value="<?= $member['phone'] ?>"><br>

        <p>Select Membership Plan:</p>
        <button type="button" class="plan-btn basic" onclick="selectPlan('Basic',this)">Basic ($30)</button>
        <button type="button" class="plan-btn standard" onclick="selectPlan('Standard',this)">Standard ($50)</button>
        <button type="button" class="plan-btn premium" onclick="selectPlan('Premium',this)">Premium ($80)</button>

        <input type="hidden" name="plan" id="planInput" required><br>
        <label>Months:</label>
        <input type="number" name="months" id="monthsInput" min="1" value="<?= $member['months'] ?? 1 ?>" onchange="updateFee()" required><br>
        <input type="number" name="fee" id="feeInput" readonly placeholder="Fee will auto-update"><br>
        <div class="calc-display" id="calcDisplay"></div>

        <label>Member Image:</label><br>
        <img src="uploads/<?= $member['image'] ?>" style="width:100px;height:100px;border-radius:50%;border:2px solid #5a2ca0;"><br>
        <input type="file" name="image"><br>
        <button name="submit">Update Member</button>
    </form>
</div>
</body>
</html>
