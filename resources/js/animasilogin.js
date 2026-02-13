console.log("Script Animasi Login Terkoneksi!"); // Buat ngecek di Inspect Element (F12)

document.addEventListener('DOMContentLoaded', function() {
    // Pastikan jQuery aman
    if (typeof jQuery === 'undefined') {
        console.error("Wah, jQuery-nya belum ada nih Bre!");
        return;
    }

    $('#formLogin').on('submit', function() {
        Swal.fire({
            title: 'Memproses Username dan Password...',
            text: 'Data sedang dimuat, mohon tunggu.',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });
    });
});