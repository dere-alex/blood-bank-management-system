<?php
require_once "config.php";

header("Content-Type: application/json");

$role = isset($_GET['role']) ? strtolower(trim($_GET['role'])) : '';

$response = [];

if ($role === 'patient' || $role === 'donor') {

    // IMPORTANT: role matching is case-insensitive
    $stmt = $conn->prepare("
        SELECT id, fullname 
        FROM userss 
        WHERE LOWER(role) = ?
        ORDER BY fullname ASC
    ");

    $stmt->bind_param("s", $role);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $response[] = $row;
    }
}

echo json_encode($response);
