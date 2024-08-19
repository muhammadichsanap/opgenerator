function updateDuration() {
    const durasiColumns = document.querySelectorAll('.durasi-column');
    durasiColumns.forEach(column => {
        const startTime = new Date(column.dataset.startTime);
        const endTime = new Date(column.dataset.endTime);
        const createdAt = new Date(parseInt(column.dataset.createdAt) * 1000); // Konversi timestamp ke Date
        const now = new Date();

        let duration;
        if (now > endTime) {
            // Jika waktu sudah lewat, tampilkan 00:00:00
            duration = 0;
        } else if (now < startTime) {
            // Jika belum dimulai, tampilkan durasi penuh
            duration = (endTime - startTime) / 1000;
        } else {
            // Jika sedang berjalan, hitung waktu yang tersisa
            duration = (endTime - now) / 1000;
        }

        // Pastikan durasi tidak negatif
        duration = Math.max(duration, 0);

        const hours = Math.floor(duration / 3600);
        const minutes = Math.floor((duration % 3600) / 60);
        const seconds = Math.floor(duration % 60);

        column.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    });
}

// Jalankan updateDuration setiap detik
setInterval(updateDuration, 1000);

// Jalankan updateDuration segera saat script dimuat
updateDuration();