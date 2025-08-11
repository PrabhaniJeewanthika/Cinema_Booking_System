<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cinema Payment System</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <div class="payment-form">
            <h2>Cinema Payment System</h2>
            
            <!-- Display any messages -->
            <?php if (isset($_GET["message"])): ?>
                <div class="message <?php echo isset($_GET["type"]) ? $_GET["type"] : "info"; ?>">
                    <?php echo htmlspecialchars($_GET["message"]); ?>
                </div>
            <?php endif; ?>

            <!-- Payment Form -->
            <form action="process_payment.php" method="POST">
                <div class="form-group">
                    <label for="booking-id">Booking ID:</label>
                    <input type="number" id="booking-id" name="booking_id" required value="<?php echo isset($_GET["booking_id"]) ? htmlspecialchars($_GET["booking_id"]) : ""; ?>">
                </div>

                <div class="form-group">
                    <label for="payment-method">Payment Method:</label>
                    <select id="payment-method" name="payment_method" required>
                        <option value="">Select Payment Method</option>
                        <option value="cash" <?php echo (isset($_GET["payment_method"]) && $_GET["payment_method"] == "cash") ? "selected" : ""; ?>>Cash</option>
                        <option value="online" <?php echo (isset($_GET["payment_method"]) && $_GET["payment_method"] == "online") ? "selected" : ""; ?>>Online Payment</option>
                    </select>
                </div>

                <!-- Online Payment Details -->
                <div class="payment-details">
                    <h3>Online Payment Details (Fill only if Online Payment selected)</h3>
                    
                    <div class="form-group">
                        <label for="card-number">Card Number:</label>
                        <input type="text" id="card-number" name="card_number" placeholder="1234567890123456" maxlength="16" value="<?php echo isset($_GET["card_number"]) ? htmlspecialchars($_GET["card_number"]) : ""; ?>">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="expiry-month">Expiry Month:</label>
                            <select id="expiry-month" name="expiry_month">
                                <option value="">Month</option>
                                <?php for ($i = 1; $i <= 12; $i++): ?>
                                    <option value="<?php echo sprintf("%02d", $i); ?>" <?php echo (isset($_GET["expiry_month"]) && $_GET["expiry_month"] == sprintf("%02d", $i)) ? "selected" : ""; ?>><?php echo sprintf("%02d", $i); ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="expiry-year">Expiry Year:</label>
                            <select id="expiry-year" name="expiry_year">
                                <option value="">Year</option>
                                <?php for ($i = date("y"); $i <= date("y") + 5; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo (isset($_GET["expiry_year"]) && $_GET["expiry_year"] == $i) ? "selected" : ""; ?>>20<?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="cvv">CVV:</label>
                        <input type="text" id="cvv" name="cvv" placeholder="123" maxlength="3" value="<?php echo isset($_GET["cvv"]) ? htmlspecialchars($_GET["cvv"]) : ""; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="cardholder-name">Cardholder Name:</label>
                        <input type="text" id="cardholder-name" name="cardholder_name" placeholder="John Doe" value="<?php echo isset($_GET["cardholder_name"]) ? htmlspecialchars($_GET["cardholder_name"]) : ""; ?>">
                    </div>
                </div>

                <button type="submit" class="pay-button">Process Payment</button>
            </form>

            <div class="links">
                <a href="view_payments.php">View Payment Records</a>
            </div>
        </div>
    </div>
</body>
</html>



