<?php
session_start();
require_once "config.php";
if (!isset($_SESSION['labtech_id'])) { echo "Login required"; exit(); }

$stmt = $conn->prepare("
SELECT tr.id, tr.test_name, tr.result, tr.test_date,
       u.fullname AS person_name,
       tr.person_type
FROM test_results tr
JOIN userss u ON tr.patient_or_donor_id = u.id
WHERE tr.labtech_id=? AND tr.status='pending'
ORDER BY tr.test_date DESC
");
$stmt->bind_param("i", $_SESSION['labtech_id']);
$stmt->execute();
$result = $stmt->get_result();
?>

<h3>Send Test Results</h3>
<table border="1" cellpadding="8" style="width:100%; text-align:left; border-collapse: collapse;">
<tr><th>Person</th><th>Type</th><th>Test Name</th><th>Result</th><th>Test Date</th><th>Action</th></tr>
<?php while($row=$result->fetch_assoc()): ?>
<tr>
<td><?php echo htmlspecialchars($row['person_name']); ?></td>
<td><?php echo htmlspecialchars($row['person_type']); ?></td>
<td><?php echo htmlspecialchars($row['test_name']); ?></td>
<td><?php echo htmlspecialchars($row['result']); ?></td>
<td><?php echo htmlspecialchars($row['test_date']); ?></td>
<td><button class="sendBtn" data-id="<?php echo $row['id']; ?>">Send</button></td>
</tr>
<?php endwhile; ?>
</table>

<script>
document.querySelectorAll('.sendBtn').forEach(btn=>{
    btn.addEventListener('click', function(){
        const id = this.dataset.id;
        fetch('send_test_result_action.php', {
            method:'POST',
            headers:{'Content-Type':'application/x-www-form-urlencoded'},
            body:'id='+encodeURIComponent(id)
        })
        .then(res=>res.text())
        .then(data=>{ alert(data); loadPage('send_test_result.php'); })
        .catch(()=>{ alert('Error sending result'); });
    });
});
</script>
