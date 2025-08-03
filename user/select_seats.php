<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Number of Seats</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            text-align: center;
            width: 90%;
            max-width: 500px;
            animation: fadeIn 1s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            margin-bottom: 25px;
            color: #ff6f61;
            font-size: 2rem;
        }

        .seat-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            justify-items: center;
            margin: 20px 0;
        }

        .seat-option {
            width: 60px;
            height: 60px;
            background: #f0f0f0;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        }

        .seat-option:hover {
            transform: translateY(-3px);
            background: #ffe0e0;
        }

        .seat-option.selected {
            background: #ff6f61;
            color: #fff;
            transform: scale(1.1);
        }

        .proceed-btn {
            background: #1e88e5;
            color: #fff;
            padding: 12px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .proceed-btn:hover {
            background: #1565c0;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Select Number of Seats</h1>
        <form method="GET" action="film_details.php" id="seatForm">
            <input type="hidden" name="seat_count" id="seat_count" value="">
            <div class="seat-grid">
                <?php for ($i = 1; $i <= 10; $i++): ?>
                    <div class="seat-option" data-value="<?= $i ?>"><?= $i ?></div>
                <?php endfor; ?>
            </div>
            <button type="submit" class="proceed-btn">Proceed to Seat Selection</button>
        </form>
    </div>

    <script>
        const seatOptions = document.querySelectorAll('.seat-option');
        const seatCountInput = document.getElementById('seat_count');
        const form = document.getElementById('seatForm');

        seatOptions.forEach(option => {
            option.addEventListener('click', () => {
                seatOptions.forEach(opt => opt.classList.remove('selected'));
                option.classList.add('selected');
                seatCountInput.value = option.getAttribute('data-value');
            });
        });

        form.addEventListener('submit', function(e) {
            if (seatCountInput.value === "") {
                e.preventDefault();
                alert("Please select the number of seats.");
            }
        });
    </script>
</body>
</html>
