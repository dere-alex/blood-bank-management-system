function checkStatus() {

    let email = document.getElementById("email").value.trim();
    let pass = document.getElementById("password").value.trim();
    let msg = document.getElementById("msg");
    let result = document.getElementById("result");

    msg.textContent = "";
    result.innerHTML = "";

    if (!email || !pass) {
        msg.textContent = "Please enter email and password!";
        msg.style.color = "red";
        return;
    }

    // DEMO STATUS (later replaced with PHP database fetch)
    const demoStatus = ["Approved", "Rejected", "Pending"];
    let randomStatus = demoStatus[Math.floor(Math.random() * 3)];

    msg.textContent = "Status loaded successfully!";
    msg.style.color = "green";

    result.innerHTML = `
        <strong>Your Request Status:</strong> ${randomStatus}
    `;
}
