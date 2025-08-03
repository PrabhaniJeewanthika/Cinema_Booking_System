document.addEventListener('DOMContentLoaded', function () {
    const seats = document.querySelectorAll('.seat:not(.booked)');
    const selectedSeatsInput = document.getElementById('selectedSeats');
    let selectedSeats = [];

    seats.forEach(seat => {
        seat.addEventListener('click', function () {
            const seatID = this.getAttribute('data-seat');
            if (this.classList.contains('selected')) {
                this.classList.remove('selected');
                selectedSeats = selectedSeats.filter(s => s !== seatID);
            } else {
                this.classList.add('selected');
                selectedSeats.push(seatID);
            }
            selectedSeatsInput.value = selectedSeats.join(',');
        });
    });
});
