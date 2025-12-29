 <?php
session_start();
require_once "config.php";

if ($_SESSION['user_role'] !== 'inventory manager') exit("Unauthorized");

$id = (int)$_POST['id'];
$action = $_POST['action'];

$conn->begin_transaction();

$req = $conn->query("
SELECT blood_group, quantity FROM blood_requests
WHERE id=$id AND status='pending'
")->fetch_assoc();

if(!$req){
    $conn->rollback();
    exit("Invalid request");
}

if($action === 'approved'){
    $stock = $conn->query("
    SELECT id FROM blood_inventory
    WHERE blood_group='{$req['blood_group']}'
    AND quantity >= {$req['quantity']}
    ORDER BY expiry_date ASC LIMIT 1
    ")->fetch_assoc();

    if(!$stock){
        $conn->rollback();
        exit("Insufficient stock");
    }

    $conn->query("
    UPDATE blood_inventory
    SET quantity = quantity - {$req['quantity']}
    WHERE id={$stock['id']}
    ");
}

$conn->query("
UPDATE blood_requests
SET status='$action',
processed_by={$_SESSION['user_id']},
processed_at=NOW()
WHERE id=$id
");

$conn->commit();

header("Location: inventorymanager_dashboard.php?page=requestapproval");
exit();
