 document.getElementById('bloodForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const blood_group = document.getElementById('blood_group').value;

    fetch('check_blood_handler.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `blood_group=${blood_group}`
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('result').innerHTML = data;
    })
    .catch(error => {
        document.getElementById('result').innerHTML = 'Error checking availability';
    });
});
