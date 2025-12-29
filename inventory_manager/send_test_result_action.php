 <?php
session_start();
require_once "config.php";
if(!isset($_SESSION['labtech_id'])){ echo "Unauthorized"; exit(); }
$id=$_POST['id'];
$stmt=$conn->prepare("UPDATE test_results SET status='sent' WHERE id=? AND labtech_id=?");
$stmt->bind_param("ii",$id,$_SESSION['labtech_id']);
if($stmt->execute()) echo "Test result sent successfully."; else echo "Failed to send test result.";
