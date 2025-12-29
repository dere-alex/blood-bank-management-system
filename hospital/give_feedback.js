function submitFeedback() {
    const feedback = document.getElementById("feedback").value.trim();
    const msg = document.getElementById("msg");

    if (feedback === "") {
        msg.innerText = "Feedback cannot be empty!";
        msg.style.color = "red";
        return;
    }

    msg.innerText = "Feedback submitted successfully!";
    msg.style.color = "green";

    // PHP insert feedback code will go here later
}
