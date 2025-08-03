<?php include('../includes/header.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Number of Seats</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('../assets/img/booking2.jpg') no-repeat center center/cover;
            color: #fff;
        }
        .form-container {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(15px);
            border-radius: 25px;
            padding: 50px 40px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.3);
            text-align: center;
            width: 90%;
            max-width: 400px;
            margin: 100px auto;
        }
        h1 {
            font-size: 2rem;
            margin-bottom: 30px;
        }
        .counter-box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 30px;
            margin: 30px 0;
        }
        .counter-btn {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: #fff;
            width: 60px;
            height: 60px;
            font-size: 2rem;
            border-radius: 50%;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .counter-btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: scale(1.1);
        }
        .seat-count {
            font-size: 3rem;
            font-weight: bold;
            width: 60px;
            text-align: center;
        }
        .proceed-btn {
            background: linear-gradient(to right, #00c6ff, #0072ff);
            color: #fff;
            padding: 12px 35px;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            transition: all 0.3s ease;
        }
        .proceed-btn:hover {
            transform: translateY(-3px) scale(1.05);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1><i class="fas fa-chair"></i> Select Seats</h1>
        <form method="GET" action="film_details.php" id="seatForm">
            <input type="hidden" name="seat_count" id="seat_count" value="1">
            <div class="counter-box">
                <button type="button" class="counter-btn" id="decrease"><i class="fas fa-minus"></i></button>
                <div class="seat-count" id="seatDisplay">1</div>
                <button type="button" class="counter-btn" id="increase"><i class="fas fa-plus"></i></button>
            </div>
            <button type="submit" class="proceed-btn"><i class="fas fa-arrow-right"></i> Proceed</button>
        </form>
    </div>

    <script>
        const increaseBtn = document.getElementById('increase');
        const decreaseBtn = document.getElementById('decrease');
        const seatDisplay = document.getElementById('seatDisplay');
        const seatCountInput = document.getElementById('seat_count');
        const maxSeats = 10;
        let seatCount = 1;

        increaseBtn.addEventListener('click', () => {
            if (seatCount < maxSeats) {
                seatCount++;
                updateDisplay();
            }
        });

        decreaseBtn.addEventListener('click', () => {
            if (seatCount > 1) {
                seatCount--;
                updateDisplay();
            }
        });

        function updateDisplay() {
            seatDisplay.textContent = seatCount;
            seatCountInput.value = seatCount;
        }
    </script>
<?php include('../includes/footer.php'); ?>
</body>
</html>
