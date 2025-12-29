<?php
session_start();
require_once "config.php";

// Only inventory manager
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'inventory manager') {
    exit("<p style='color:red'>Unauthorized</p>");
}

$blood_group = $_POST['blood_group'];
$quantity = (int)$_POST['quantity'];
$collection_date = $_POST['collection_date'];

// Calculate expiry: 21 days
$expiry_date = date('Y-m-d', strtotime($collection_date . ' +21 days'));
$stored_by = $_SESSION['user_id'] ?? null;

// Insert into database
$stmt = $conn->prepare("
    INSERT INTO blood_inventory 
    (blood_group, quantity, collection_date, expiry_date, stored_by)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("sissi", $blood_group, $quantity, $collection_date, $expiry_date, $stored_by);
$stmt->execute();
$stmt->close();
$conn->close();

// Return success + form inline
$_SESSION['success'] = "Blood unit stored successfully.";
include 'store_blood.php';
