document.addEventListener("DOMContentLoaded", function () {

    const path = window.location.pathname;

    if (path.includes("dashboard")) {
        document.getElementById("menu-dashboard")?.classList.add("active");
    }
    if (path.includes("transaksi")) {
        document.getElementById("menu-transaksi")?.classList.add("active");
    }
    if (path.includes("prediksi")) {
        document.getElementById("menu-prediksi")?.classList.add("active");
    }
    if (path.includes("profil")) {
        document.getElementById("menu-profil")?.classList.add("active");
    }

});