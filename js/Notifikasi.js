// FUNGSI MEMBUAT NOTIFIKASI
function showNotification(message, type) {

    // buat elemen notifikasi
    var notif = document.createElement("div");
    notif.className = "notifBox " + type;  
    notif.innerHTML = message;

    // masukkan ke dalam body
    document.body.appendChild(notif);

    // agar notifikasi muncul dengan animasi fade-in
    setTimeout(function() {
        notif.classList.add("show");
    }, 10);

    // hilang otomatis setelah 3 detik
    setTimeout(function() {
        notif.classList.remove("show");

        // hapus setelah animasi fade-out selesai
        setTimeout(function() {
            notif.remove();
        }, 300);

    }, 3000);
}

// Notifikasi sukses
function notifySuccess(msg) {
    showNotification(msg, "success");
}

// Notifikasi gagal / error
function notifyError(msg) {
    showNotification(msg, "error");
}

// Notifikasi informasi
function notifyInfo(msg) {
    showNotification(msg, "info");
}