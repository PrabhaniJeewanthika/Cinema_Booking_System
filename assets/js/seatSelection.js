document.querySelectorAll('.seat').forEach(seat => {
    seat.addEventListener('click', () => {
        if (!seat.classList.contains('booked')) {
            seat.classList.toggle('selected');
        }
    });
});

document.getElementById('bookingForm').addEventListener('submit', function(e) {
    const selectedSeats = [];
    document.querySelectorAll('.seat.selected').forEach(seat => {
        selectedSeats.push(seat.getAttribute('data-seat'));
    });

    if (selectedSeats.length === 0) {
        e.preventDefault();
        alert('Please select at least one seat.');
        return;
    }

    document.getElementById('selected_seats').value = selectedSeats.join(',');
});
