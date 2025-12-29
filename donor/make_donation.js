function submitAppointment() {
    const date = document.getElementById("date").value;
    const time = document.getElementById("time").value;
    const center = document.getElementById("center").value;
    const lastdate = document.getElementById("lastdate").value;
    const msg = document.getElementById("msg");

    if (!date || !time || !center) {
        msg.innerText = "Please fill all fields!";
        msg.style.color = "red";
        return;
    }

    msg.innerText = "Appointment submitted successfully!";
    msg.style.color = "green";

    // PHP back-end will go here later
}
