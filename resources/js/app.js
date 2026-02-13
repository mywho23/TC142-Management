//import './bootstrap';
console.log("Vite live reload aktif");

document.addEventListener('DOMContentLoaded', () => {
    const checkboxes = document.querySelectorAll('.note-checkbox');

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const id = this.getAttribute('data-id');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            console.log('Checkbox clicked:', id);

            fetch(`/ceklis/update-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: id })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    console.log(`Status note ${id} berhasil diubah jadi: ${data.status}`);
                } else {
                    console.error('Gagal ubah status:', data);
                }
            })
            .catch(err => console.error('Error:', err));
        });
    });
});

