 function loadPage(page) {
    const iframe = document.getElementById("adminFrame");
    iframe.src = page + "?t=" + new Date().getTime();
}