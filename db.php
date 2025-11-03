<?php
session_start();
$conn = mysqli_connect("localhost", "root", "", "fitness_center_db");

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
