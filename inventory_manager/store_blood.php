<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Only inventory manager allowed
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'inventory manager') {
    echo "<p style='color:red'>Unauthorized access</p>";
    exit();
}

// If success message exists (from previous submit)
$successMsg = '';
if (isset($_SESSION['success'])) {
    $successMsg = $_SESSION['success'];
    unset($_SESSION['success']);
}
?>

<div class="box">
    <h2>Store Blood Unit</h2>

    <?php if($successMsg): ?>
        <div class="success-msg"><?php echo htmlspecialchars($successMsg); ?></div>
    <?php endif; ?>

    <form id="storeBloodForm">
        <label>Blood Group</label>
        <select name="blood_group" required>
            <option value="">-- Select --</option>
            <option>A+</option><option>A-</option>
            <option>B+</option><option>B-</option>
            <option>O+</option><option>O-</option>
            <option>AB+</option><option>AB-</option>
        </select>

        <label>Quantity (Units)</label>
        <input type="number" name="quantity" min="1" required>

        <label>Collection Date</label>
        <input type="date" name="collection_date" required>

        <button type="submit">Store Blood</button>
    </form>
</div>

<style>
.box { 
    width: 420px; 
    margin: 20px auto; 
    padding: 25px; 
    background: white; 
    border-radius: 10px; 
    box-shadow: 0 4px 12px rgba(0,0,0,.1); 
}
label { display:block; margin-top:12px; }
input, select, button { width:100%; padding:10px; margin-top:6px; }
button { background:#2a9d8f; color:white; border:none; margin-top:20px; cursor:pointer; }
.success-msg {
    background: #d4edda;
    color: #155724;
    padding: 10px;
    border-radius: 6px;
    margin-bottom: 10px;
    text-align:center;
}
</style>

<script>
// AJAX form submit to stay in dashboard
document.getElementById('storeBloodForm').addEventListener('submit', function(e){
    e.preventDefault();

    const formData = new FormData(this);

    fetch('store_blood_handler.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.text())
    .then(response => {
        document.querySelector('.box').innerHTML = response; // replace content with success + form
    })
    .catch(err => console.error(err));
});
</script>
