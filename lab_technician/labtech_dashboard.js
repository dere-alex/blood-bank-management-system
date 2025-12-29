// LAB TECH DASHBOARD PAGE LOADER

document.querySelectorAll(".sidebar a").forEach(link => {
    link.addEventListener("click", function (e) {
        e.preventDefault();

        let page = this.dataset.page;
        if (!page || page === "home") {
            showHome();
            return;
        }

        loadPage(page + ".html");
    });
});

function showHome() {
    document.getElementById("home").classList.add("active");
    document.getElementById("pageContent").innerHTML = "";
}

function loadPage(file) {
    fetch(file)
        .then(res => res.text())
        .then(html => {
            document.getElementById("home").classList.remove("active");
            document.getElementById("pageContent").innerHTML = html;

            // re-run scripts inside loaded HTML
            const scripts = document.getElementById("pageContent").querySelectorAll("script");

            scripts.forEach(oldScript => {
                const newScript = document.createElement("script");
                newScript.textContent = oldScript.textContent;
                document.body.appendChild(newScript);
                document.body.removeChild(newScript);
            });
        })
        .catch(() => {
            document.getElementById("pageContent").innerHTML =
                "<p style='color:red;'>Failed to load page.</p>";
        });
}
