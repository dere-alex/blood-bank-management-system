<?php
session_start();
require_once "config.php";

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'nurse') {
    echo "<p style='color:red'>Unauthorized</p>";
    exit();
}
?>

<div class="test-card">
    <h3>Test Results</h3>

    <table class="result-table">
        <thead>
            <tr>
                <th>Person</th>
                <th>Type</th>
                <th>Test Name</th>
                <th>Result</th>
                <th>Test Date</th>
                <th>Lab Technician</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $sql = "
            SELECT tr.test_name, tr.result, tr.test_date, tr.person_type,
                   u.fullname AS person_name,
                   s.fullname AS labtech_name
            FROM test_results tr
            JOIN userss u ON tr.patient_or_donor_id = u.id
            JOIN staff s ON tr.labtech_id = s.id
            WHERE tr.status = 'sent'
            ORDER BY tr.test_date DESC
        ";

        $res = $conn->query($sql);

        if ($res->num_rows === 0) {
            echo "<tr><td colspan='6'>No test results available</td></tr>";
        } else {
            while ($row = $res->fetch_assoc()) {
                echo "<tr>
                    <td>".htmlspecialchars($row['person_name'])."</td>
                    <td>".ucfirst($row['person_type'])."</td>
                    <td>".htmlspecialchars($row['test_name'])."</td>
                    <td><strong>".htmlspecialchars($row['result'])."</strong></td>
                    <td>".htmlspecialchars($row['test_date'])."</td>
                    <td>".htmlspecialchars($row['labtech_name'])."</td>
                </tr>";
            }
        }
        ?>
        </tbody>
    </table>
</div>

<style>
.test-card {
    background: #fff;
    padding: 20px;
    margin: 20px auto;
    border-radius: 10px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}

.test-card h3 {
    text-align: center;
    margin-bottom: 15px;
}

.result-table {
    width: 100%;
    border-collapse: collapse;
}

.result-table th, .result-table td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: center;
}

.result-table th {
    background: #007bff;
    color: #fff;
}

.result-table tr:nth-child(even) {
    background: #f9f9f9;
}
</style>
