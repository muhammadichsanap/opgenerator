function updateDuration() {
    const durasiColumns = document.querySelectorAll('.durasi-column');
    durasiColumns.forEach(column => {
        const startTime = new Date(column.dataset.startTime);
        const endTime = new Date(column.dataset.endTime);
        const now = new Date();

        let duration;
        if (now > endTime) {
            duration = (endTime - startTime) / 1000;
        } else {
            duration = (now - startTime) / 1000;
        }

        const hours = Math.floor(duration / 3600);
        const minutes = Math.floor((duration % 3600) / 60);
        const seconds = Math.floor(duration % 60);

        column.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
    });
}

setInterval(updateDuration, 1000);