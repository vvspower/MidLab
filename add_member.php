<?php
include 'db.php';
if(!isset($_SESSION['user'])) header("Location: index.php");

// Pre-select plan if coming from membership_plans.php
$selectedPlan = isset($_GET['plan']) ? $_GET['plan'] : '';
$planPrices = ["Basic"=>30,"Standard"=>50,"Premium"=>80];

if(isset($_POST['submit'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $plan = $_POST['plan'];
    $months = (int)$_POST['months'];
    $price = $planPrices[$plan];
    $discount = $months >= 6 ? 0.1 : 0; // 10% discount for 6+ months
    $fee = $price * $months * (1-$discount);

    $image = $_FILES['image']['name'];
    $target = "uploads/" . basename($image);
    move_uploaded_file($_FILES['image']['tmp_name'], $target);

    if($name && $email && $plan && $months > 0){
        mysqli_query($conn,"INSERT INTO members (name,email,phone,plan,join_date,fee,image,months)
                  VALUES ('$name','$email','$phone','$plan',CURDATE(),'$fee','$image','$months')");
        echo "<script>alert('Member added successfully');window.location='members.php';</script>";
    } else {
        echo "<p class='error'>Please fill all fields!</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Member</title>
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
            const prePlan = '<?= $selectedPlan ?>';
            if(prePlan){
                document.getElementById('planInput').value = prePlan;
                document.querySelectorAll('.plan-btn').forEach(btn=>{
                    if(btn.innerText.includes(prePlan)) btn.classList.add('selected');
                });
                updateFee();
            }
        }
    </script>
</head>
<body>
<div class="navbar">
    <a href="dashboard.php">Dashboard</a>
    <a href="members.php">Back to Members</a>
    <a href="logout.php" class="logout">Logout</a>
</div>

<div class="content">
    <h2>Add New Member</h2>
    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="name" placeholder="Full Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="text" name="phone" placeholder="Phone"><br>

        <p>Select Membership Plan:</p>
        <button type="button" class="plan-btn basic" onclick="selectPlan('Basic',this)">Basic ($30)</button>
        <button type="button" class="plan-btn standard" onclick="selectPlan('Standard',this)">Standard ($50)</button>
        <button type="button" class="plan-btn premium" onclick="selectPlan('Premium',this)">Premium ($80)</button>

        <input type="hidden" name="plan" id="planInput" required><br>
        <label>Months:</label>
        <input type="number" name="months" id="monthsInput" value="1" min="1" onchange="updateFee()" required><br>
        <input type="number" name="fee" id="feeInput" readonly placeholder="Fee will auto-update"><br>
        <div class="calc-display" id="calcDisplay"></div>

        <input type="file" name="image" required><br>
        <button name="submit">Add Member</button>
    </form>
</div>
</body>
</html>
