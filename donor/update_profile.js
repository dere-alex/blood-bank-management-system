function updateProfile() {
    const name = document.getElementById("name").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const email = document.getElementById("email").value.trim();
    const msg = document.getElementById("msg");

    if (name === "" || phone === "" || email === "") {
        msg.innerText = "Please fill required fields.";
        msg.style.color = "red";
        return;
    }

    msg.innerText = "Profile updated successfully!";
    msg.style.color = "green";

    // Later you will add PHP here
}
