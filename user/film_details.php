<?php
require_once(__DIR__ . '/../controllers/BookingController.php');

$movie_id = isset($_GET['movie_id']) ? $_GET['movie_id'] : 1;
$seat_count = isset($_GET['seat_count']) ? (int)$_GET['seat_count'] : 0;

if ($seat_count <= 0) {
    header("Location: select_seats.php");
    exit;
}

$controller = new BookingController();
$bookedSeats = $controller->fetchBookedSeats($movie_id);

// Define Row Labels
$rows = ['A', 'B', 'C', 'D', 'E'];
$seatsPerRow = 10;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film Hall Seat Layout</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: url('../assets/img/booking1.jpg') no-repeat center center/cover;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Roboto', sans-serif;
        }

        .container {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 30px;
            width: 95%;
            max-width: 900px;
            box-shadow: 0 8px 32px rgba(31, 38, 135, 0.37);
            color: #fff;
            text-align: center;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 15px;
        }

        .screen {
            background: rgba(255, 255, 255, 0.5);
            padding: 10px;
            border-radius: 10px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .seat-layout {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
        }

        .row {
            display: flex;
            gap: 10px;
        }

        .seat {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-weight: bold;
            font-size: 14px;
        }

        .seat:hover {
            transform: scale(1.05);
        }

        .seat.booked {
            background: rgba(255, 0, 0, 0.6);
            cursor: not-allowed;
        }

        .seat.selected {
            background: rgba(76, 175, 80, 0.8);
        }

        .submit-btn {
            margin-top: 20px;
            background: rgba(30, 136, 229, 0.8);
            color: #fff;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .submit-btn:hover {
            background: rgba(21, 101, 192, 0.9);
        }

        .legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 20px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .legend-color {
            width: 20px;
            height: 20px;
            border-radius: 5px;
        }

        .available { background: rgba(255, 255, 255, 0.3); }
        .booked { background: rgba(255, 0, 0, 0.6); }
        .selected { background: rgba(76, 175, 80, 0.8); }
    </style>
</head>
<body>
    <div class="container">
        <h1>Select <?php echo $seat_count; ?> Seat(s)</h1>

        <div class="screen">SCREEN THIS WAY</div>

        <form method="POST" action="booking_process.php" id="bookingForm">
            <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
            <input type="hidden" name="seat_count" value="<?php echo $seat_count; ?>">
            <div class="seat-layout">
                <?php foreach ($rows as $row): ?>
                    <div class="row">
                        <?php for ($i = 1; $i <= $seatsPerRow; $i++):
                            $seatNum = $row . $i;
                            $disabled = in_array($seatNum, $bookedSeats) ? 'booked' : '';
                        ?>
                            <div class="seat <?php echo $disabled; ?>" data-seat="<?php echo $seatNum; ?>">
                                <?php echo $seatNum; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <input type="hidden" name="selected_seats" id="selected_seats">
            <button type="submit" class="submit-btn">Confirm Booking</button>
        </form>

        <div class="legend">
            <div class="legend-item">
                <div class="legend-color available"></div> Available
            </div>
            <div class="legend-item">
                <div class="legend-color booked"></div> Booked
            </div>
            <div class="legend-item">
                <div class="legend-color selected"></div> Selected
            </div>
        </div>
    </div>

    <script>
        const seats = document.querySelectorAll('.seat');
        const selectedSeatsInput = document.getElementById('selected_seats');
        const form = document.getElementById('bookingForm');
        const seatLimit = <?php echo $seat_count; ?>;

        seats.forEach(seat => {
            seat.addEventListener('click', () => {
                if (seat.classList.contains('booked')) return;

                seat.classList.toggle('selected');
                const selectedSeats = document.querySelectorAll('.seat.selected');

                if (selectedSeats.length > seatLimit) {
                    seat.classList.remove('selected');
                    alert(`You can only select ${seatLimit} seat(s).`);
                }
            });
        });

        form.addEventListener('submit', function(e) {
            const selectedSeats = [];
            document.querySelectorAll('.seat.selected').forEach(seat => {
                selectedSeats.push(seat.getAttribute('data-seat'));
            });

            if (selectedSeats.length !== seatLimit) {
                e.preventDefault();
                alert(`Please select exactly ${seatLimit} seat(s).`);
                return;
            }

            selectedSeatsInput.value = selectedSeats.join(',');
        });
    </script>
</body>
</html>
