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
$middleWalkwayAfterColumn = 10;  // Vertical Aisle after Column 10
$rowWalkwayAfter = 5;            // Horizontal Aisle after Row E (Row 5)
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
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.8);
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
                box-shadow: 0 0 15px rgba(255,255,255,0.6);
                font-size: 14px;
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

            .available {
                background: rgba(255, 255, 255, 0.5);
            }
            .selected {
                background: rgba(76, 175, 80, 0.8);
            }
            .booked {
                background: rgba(255, 0, 0, 0.6);
                pointer-events: none;
            }

            .layout-wrapper {
                position: relative;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .seat-layout {
                display: grid;
                grid-template-columns: 30px repeat(10, 32px) 30px repeat(10, 32px);
                grid-auto-rows: 32px;
                gap: 6px;
            }

            .row-label {
                font-size: 12px;
                font-weight: bold;
                text-align: center;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .col-header {
                writing-mode: vertical-rl;
                transform: rotate(180deg);
                font-size: 12px;
                text-align: center;
                font-weight: bold;
                display: flex;
                align-items: center;
                justify-content: center;
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

            .walkway-space {
                grid-column: span 22;
                height: 20px;
            }

            .side-door {
                position: absolute;
                width: 35px;
                height: 50px;
                display: flex;
                justify-content: center;
                align-items: center;
                font-size: 35px;
                z-index: 2;
            }

            .left-door {
                left: -45px;
                top: 50%;
                transform: translateY(-50%);
            }
            .right-door {
                right: -45px;
                top: 50%;
                transform: translateY(-50%);
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
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
                transition: all 0.3s ease;
            }

            .submit-btn:hover {
                background: rgba(255, 255, 255, 0.4);
                color: #000;
                box-shadow: 0 6px 25px rgba(0, 0, 0, 0.4);
            }

            /* Responsive Adjustments */
            @media (max-width: 768px) {
                .seat-layout {
                    grid-template-columns: 20px repeat(10, 24px) 20px repeat(10, 24px);
                    grid-auto-rows: 24px;
                    gap: 4px;
                }
                .seat {
                    width: 24px;
                    height: 24px;
                }
                .col-header, .row-label {
                    font-size: 10px;
                }
                .side-door {
                    font-size: 25px;
                    width: 30px;
                    height: 40px;
                }
            }

        </style>
    </head>
    <body>
        <div class="main-content">
            <div class="container">
                <br><br>
                <div><h2>Select <?php echo $seat_count; ?> Seat(s)</h2></div>
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

                    <div class="layout-wrapper">
                        <div class="side-door left-door">🚪</div>

                        <div class="seat-layout">
                            <!-- Top Row: Column Numbers -->
                            <div></div>
                            <?php for ($i = 1; $i <= $middleWalkwayAfterColumn; $i++): ?>
                                <div class="col-header"><?php echo $i; ?></div>
                            <?php endfor; ?>
                            <div></div> <!-- Middle Column Walkway -->
                            <?php for ($i = $middleWalkwayAfterColumn + 1; $i <= $seatsPerColumn; $i++): ?>
                                <div class="col-header"><?php echo $i; ?></div>
                            <?php endfor; ?>

                            <!-- Seat Rows with Row Labels and Seat Elements -->
                            <?php foreach ($rows as $index => $row): ?>
                                <div class="row-label"><?php echo $row; ?></div>
                                <?php for ($i = 1; $i <= $middleWalkwayAfterColumn; $i++): ?>
                                    <?php
                                    $seatNum = $row . $i;
                                    $disabled = (is_array($bookedSeats) && in_array($seatNum, $bookedSeats)) ? 'booked' : '';
                                    ?>
                                    <div class="seat <?php echo $disabled; ?>" data-seat="<?php echo $seatNum; ?>"></div>
                                <?php endfor; ?>
                                <div></div> <!-- Middle Walkway Column Gap -->
                                <?php for ($i = $middleWalkwayAfterColumn + 1; $i <= $seatsPerColumn; $i++): ?>
                                    <?php
                                    $seatNum = $row . $i;
                                    $disabled = (is_array($bookedSeats) && in_array($seatNum, $bookedSeats)) ? 'booked' : '';
                                    ?>
                                    <div class="seat <?php echo $disabled; ?>" data-seat="<?php echo $seatNum; ?>"></div>
                                <?php endfor; ?>

                                <?php if ($index + 1 == $rowWalkwayAfter): ?>
                                    <div class="walkway-space"></div> <!-- Horizontal Walkway Row Gap -->
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>

                        <div class="side-door right-door">🚪</div>
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
                    if (seat.classList.contains('booked'))
                        return;
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
