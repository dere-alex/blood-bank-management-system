// patientdashboard.js  (REPLACE your current file with this)

// ---------- Menu click handling ----------
document.querySelectorAll(".sidebar a").forEach(link => {
  link.addEventListener("click", function (e) {
    const page = this.getAttribute("data-page");

    // If this is a SPA link (has data-page) handle it here
    if (page) {
      e.preventDefault();
      if (page === "home") {
        document.getElementById("pageContent").innerHTML =
          `<section class="card"><h1>Welcome Patient</h1><p>Select an option from the sidebar.</p></section>`;
        return;
      }
      loadPage(page + ".html");
      return;
    }

    // not a data-page link → allow normal navigation (e.g. logout.html)
    // DO NOT call e.preventDefault() here so logout works
  });
});


// ---------- Load HTML into container ----------
function loadPage(pageFile) {
  fetch(pageFile)
    .then(resp => {
      if (!resp.ok) throw new Error("Page not found: " + pageFile);
      return resp.text();
    })
    .then(html => {
      const container = document.getElementById("pageContent");
      container.innerHTML = html;

      // Optionally: dynamically load page-specific CSS (uncomment if using)
      // loadPageCSS(pageFile.replace('.html','.css'));

      // Attach validation & success handlers for the loaded page
      attachPageHandlers();
    })
    .catch(err => {
      console.error(err);
      document.getElementById("pageContent").innerHTML =
        `<div class="card"><p style="color:red">Error loading page: ${pageFile}</p></div>`;
    });
}


// Optional helper to inject page CSS (if you want it dynamic)
// function loadPageCSS(cssFile) {
//   // remove existing dynamic page CSS (if any) - keep simple
//   const prev = document.querySelector('link[data-dyn="page-css"]');
//   if (prev) prev.remove();
//   const link = document.createElement('link');
//   link.rel = 'stylesheet';
//   link.href = cssFile;
//   link.setAttribute('data-dyn','page-css');
//   document.head.appendChild(link);
// }


// ---------- Attach handlers for forms loaded into #pageContent ----------
function attachPageHandlers() {
  // Clear any previously displayed messages in container
  const msgs = document.querySelectorAll("#pageContent #msg");
  msgs.forEach(m => (m.textContent = ""));

  // 1) Update Profile page
  // Accept both ids: "updateBtn" or "profileUpdateBtn" (fallback)
  const updateBtn = document.getElementById("updateBtn") || document.getElementById("profileUpdateBtn");
  if (updateBtn) {
    updateBtn.onclick = function () {
      // Find inputs with common IDs or names
      const name = document.getElementById("name") ? document.getElementById("name").value.trim() : "";
      const phone = document.getElementById("phone") ? document.getElementById("phone").value.trim() : "";
      const email = document.getElementById("email") ? document.getElementById("email").value.trim() : "";
      const msg = document.querySelector("#pageContent #msg") || document.createElement("p");

      if (!name || !phone || !email) {
        msg.textContent = "Please fill required fields.";
        msg.style.color = "red";
        ensureMsgInContainer(msg);
        return;
      }

      msg.textContent = "Profile updated successfully!";
      msg.style.color = "green";
      ensureMsgInContainer(msg);

      // TODO: call backend (AJAX / PHP) here
    };
  }

  // 2) Give Feedback page
  const feedbackBtn = document.getElementById("feedbackBtn") || document.getElementById("feedbackSubmit");
  if (feedbackBtn) {
    feedbackBtn.onclick = function () {
      const feedbackEl = document.getElementById("feedback") || document.getElementById("message");
      const msg = document.querySelector("#pageContent #msg") || document.createElement("p");
      const txt = feedbackEl ? feedbackEl.value.trim() : "";

      if (!txt) {
        msg.textContent = "Feedback cannot be empty!";
        msg.style.color = "red";
        ensureMsgInContainer(msg);
        return;
      }

      msg.textContent = "Feedback submitted successfully!";
      msg.style.color = "green";
      ensureMsgInContainer(msg);

      // TODO: AJAX to send feedback to server
    };
  }

  // 3) Check Blood Availability page
  const checkBtn = document.getElementById("checkBtn");
  if (checkBtn) {
    checkBtn.onclick = function () {
      const type = document.getElementById("bloodType") ? document.getElementById("bloodType").value : "";
      const quantity = document.getElementById("quantity") ? document.getElementById("quantity").value : "";
      const expiry = document.getElementById("expiry") ? document.getElementById("expiry").value : "";
      const msg = document.querySelector("#pageContent #msg") || document.createElement("p");
      const result = document.getElementById("result");

      if (!type || !quantity || !expiry) {
        msg.textContent = "Please fill all fields!";
        msg.style.color = "red";
        ensureMsgInContainer(msg);
        return;
      }

      if (parseInt(quantity, 10) <= 0) {
        msg.textContent = "Quantity must be greater than 0";
        msg.style.color = "red";
        ensureMsgInContainer(msg);
        return;
      }

      // Demo behaviour (replace with server call later)
      if (result) {
        result.innerHTML = `<strong>Blood Type:</strong> ${escapeHtml(type)}<br>
                            <strong>Requested Quantity:</strong> ${escapeHtml(quantity)}<br>
                            <strong>Expiry Date:</strong> ${escapeHtml(expiry)}<br>
                            <strong>Available Units:</strong> ${Math.floor(Math.random()*20+1)}`;
      }

      msg.textContent = "Availability checked successfully!";
      msg.style.color = "green";
      ensureMsgInContainer(msg);
    };
  }

  // 4) Request Blood page
  const requestBtn = document.getElementById("requestBtn");
  if (requestBtn) {
    requestBtn.onclick = function () {
      const type = document.getElementById("bloodNeeded") ? document.getElementById("bloodNeeded").value : "";
      const units = document.getElementById("units") ? document.getElementById("units").value : "";
      const hospital = document.getElementById("hospital") ? document.getElementById("hospital").value.trim() : "";
      const email = document.getElementById("email") ? document.getElementById("email").value.trim() : "";
      const password = document.getElementById("password") ? document.getElementById("password").value.trim() : "";
      const msg = document.querySelector("#pageContent #msg") || document.createElement("p");

      if (!type || !units || !hospital || !email || !password) {
        msg.textContent = "Please fill all fields!";
        msg.style.color = "red";
        ensureMsgInContainer(msg);
        return;
      }

      if (parseInt(units,10) <= 0) {
        msg.textContent = "Units must be at least 1!";
        msg.style.color = "red";
        ensureMsgInContainer(msg);
        return;
      }

      msg.textContent = "Blood request submitted successfully!";
      msg.style.color = "green";
      ensureMsgInContainer(msg);

      // TODO: send to server via fetch/ajax
    };
  }

  // 5) View Request Status page
  const viewBtn = document.getElementById("viewBtn");
  if (viewBtn) {
    viewBtn.onclick = function () {
      const email = document.getElementById("vr_email") ? document.getElementById("vr_email").value.trim() : (document.getElementById("email") ? document.getElementById("email").value.trim() : "");
      const password = document.getElementById("vr_password") ? document.getElementById("vr_password").value.trim() : (document.getElementById("password") ? document.getElementById("password").value.trim() : "");
      const msg = document.querySelector("#pageContent #msg") || document.createElement("p");
      const out = document.getElementById("statusResult") || document.getElementById("result");

      if (!email || !password) {
        msg.textContent = "Email and password are required.";
        msg.style.color = "red";
        ensureMsgInContainer(msg);
        return;
      }

      // Demo: random status
      const status = Math.random() > 0.5 ? "Approved" : "Rejected";
      if (out) out.innerHTML = `<strong>Status:</strong> ${status}<br><strong>Email:</strong> ${escapeHtml(email)}`;

      msg.textContent = "Status retrieved successfully.";
      msg.style.color = "green";
      ensureMsgInContainer(msg);
    };
  }
}


// ---------- Helpers ----------

// Ensure #msg exists inside pageContent and append provided node
function ensureMsgInContainer(msgNode) {
  const container = document.getElementById("pageContent");
  let existing = container.querySelector("#msg");
  if (!existing) {
    msgNode.id = "msg";
    msgNode.style.marginTop = "10px";
    container.appendChild(msgNode);
  } else {
    // update existing
    existing.textContent = msgNode.textContent;
    existing.style.color = msgNode.style.color;
  }
}

// basic HTML escape
function escapeHtml(s) {
  if (!s) return "";
  return s.replace(/[&<>"']/g, function (c) {
    return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];
  });
}
