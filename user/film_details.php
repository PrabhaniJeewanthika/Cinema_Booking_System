<?php
require_once(__DIR__ . '/../controllers/BookingController.php');
include('../includes/header.php');

$movie_id = isset($_GET['movie_id']) ? $_GET['movie_id'] : 1;
$seat_count = isset($_GET['seat_count']) ? (int) $_GET['seat_count'] : 0;

if ($seat_count <= 0) {
    header("Location: select_seats.php");
    exit;
}

$controller = new BookingController();
$bookedSeats = $controller->fetchBookedSeats($movie_id);

$rows = range('A', 'J');
$seatsPerColumn = 20;
$middleWalkwayAfterColumn = 10;
$rowWalkwayAfter = 5;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seat Selection</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            background: url('../assets/img/booking2.jpg') no-repeat center center/cover;
            font-family: 'Roboto', sans-serif;
        }
        .main-content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: calc(100vh - 80px);
        }
        .container {
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            padding: 10px;
            width: 80%;
            max-width: 1000px;
            color: #fff;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .screen {
            width: 100%;
            height: 30px;
            background: rgba(255,255,255,0.7);
            border-radius: 0 0 50% 50% / 0 0 25% 25%;
            margin-bottom: 20px;
            font-weight: bold;
            color: #000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .legend {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 10px;
            font-size: 14px;
        }
        .legend-box {
            width: 20px;
            height: 20px;
            border-radius: 4px;
            display: inline-block;
        }
        .available { background: rgba(255, 255, 255, 0.5); }
        .selected  { background: rgba(76, 175, 80, 0.8); }
        .booked {
            background: rgba(255, 0, 0, 0.6) !important;
            background-image: none !important;
            pointer-events: none;
        }

        .seat-layout {
            display: grid;
            grid-template-columns: 30px repeat(10, 32px) 30px repeat(10, 32px);
            grid-auto-rows: 32px;
            gap: 6px;
        }
        .seat {
            width: 30px;
            height: 30px;
            background: rgba(255,255,255,0.3);
            border-radius: 6px;
            background-image: url('../assets/img/3d-chair.png');
            background-size: contain;
            background-position: center;
            cursor: pointer;
            transition: 0.3s;
        }
        .seat.selected {
            background: rgba(76, 175, 80, 0.8) !important;
            background-image: none;
        }
        .row-label, .col-header {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .walkway-space {
            grid-column: span 22;
            height: 20px;
        }
        .submit-btn {
            margin-top: 20px;
            padding: 12px 35px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            font-weight: bold;
            cursor: pointer;
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            backdrop-filter: blur(10px);
        }
        .submit-btn:hover {
            background: rgba(255, 255, 255, 0.4);
            color: #000;
        }
    </style>
</head>
<body>
<div class="main-content">
    <div class="container">
        <h2>Select <?php echo $seat_count; ?> Seat(s)</h2>

        <div class="legend">
            <div><span class="legend-box available"></span> Available</div>
            <div><span class="legend-box selected"></span> Selected</div>
            <div><span class="legend-box booked"></span> Booked</div>
        </div>

        <div class="screen">SCREEN</div>

        <form method="POST" action="booking_process.php" id="bookingForm">
            <input type="hidden" name="movie_id" value="<?php echo $movie_id; ?>">
            <input type="hidden" name="seat_count" value="<?php echo $seat_count; ?>">
            <input type="hidden" name="selected_seats" id="selected_seats">

            <div class="seat-layout">
                <!-- Column Header -->
                <div></div>
                <?php for ($i = 1; $i <= $middleWalkwayAfterColumn; $i++): ?>
                    <div class="col-header"><?php echo $i; ?></div>
                <?php endfor; ?>
                <div></div>
                <?php for ($i = $middleWalkwayAfterColumn + 1; $i <= $seatsPerColumn; $i++): ?>
                    <div class="col-header"><?php echo $i; ?></div>
                <?php endfor; ?>

                <!-- Seat Grid -->
                <?php foreach ($rows as $index => $row): ?>
                    <div class="row-label"><?php echo $row; ?></div>
                    <?php for ($i = 1; $i <= $middleWalkwayAfterColumn; $i++): ?>
                        <?php $seatNum = $row . $i; ?>
                        <div class="seat <?php echo in_array($seatNum, $bookedSeats) ? 'booked' : ''; ?>" data-seat="<?php echo $seatNum; ?>"></div>
                    <?php endfor; ?>
                    <div></div>
                    <?php for ($i = $middleWalkwayAfterColumn + 1; $i <= $seatsPerColumn; $i++): ?>
                        <?php $seatNum = $row . $i; ?>
                        <div class="seat <?php echo in_array($seatNum, $bookedSeats) ? 'booked' : ''; ?>" data-seat="<?php echo $seatNum; ?>"></div>
                    <?php endfor; ?>

                    <?php if ($index + 1 == $rowWalkwayAfter): ?>
                        <div class="walkway-space"></div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="submit-btn">Confirm Booking</button>
        </form>
    </div>
</div>

<script>
    const seats = document.querySelectorAll('.seat');
    const selectedSeatsInput = document.getElementById('selected_seats');
    const form = document.getElementById('bookingForm');
    const seatLimit = <?php echo $seat_count; ?>;

    seats.forEach(seat => {
        seat.addEventListener('click', () => {
            if (seat.classList.contains('booked')) {
                alert("This seat is already booked.");
                return;
            }

            seat.classList.toggle('selected');
            const selectedSeats = document.querySelectorAll('.seat.selected');
            if (selectedSeats.length > seatLimit) {
                seat.classList.remove('selected');
                alert(`You can only select ${seatLimit} seat(s).`);
            }
        });
    });

    form.addEventListener('submit', function (e) {
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
