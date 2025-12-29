function submitBloodRequest() {

    let type = document.getElementById("bloodType").value;
    let units = document.getElementById("units").value;
    let hospital = document.getElementById("hospital").value.trim();
    let email = document.getElementById("email").value.trim();
    let pass = document.getElementById("password").value.trim();

    let msg = document.getElementById("msg");

    msg.textContent = "";

    // Validation
    if (!type || !units || !hospital || !email || !pass) {
        msg.textContent = "Please fill all fields!";
        msg.style.color = "red";
        return;
    }

    if (units <= 0) {
        msg.textContent = "Units must be greater than 0!";
        msg.style.color = "red";
        return;
    }

    // Success message
    msg.textContent = "Blood request submitted successfully!";
    msg.style.color = "green";

    // Placeholder for PHP processing later
}
