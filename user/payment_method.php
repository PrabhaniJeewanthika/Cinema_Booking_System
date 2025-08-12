<?php
session_start();


$booking_id = $_GET['booking_id'] ?? $_SESSION['current_booking_id'] ?? 0;

if (!$booking_id) {
    $_SESSION['error'] = 'Invalid booking ID';
    header('Location: index.php');
    exit;
}


$booking_data = [
    'id' => $booking_id,
    'movie_title' => 'Avengers: Endgame',
    'showtime' => '2025-08-15 19:30:00',
    'seats' => ['A1', 'A2', 'A3'],
    'theater' => 'Theater 1',
    'price_per_seat' => 15.00
];

$total_amount = count($booking_data['seats']) * $booking_data['price_per_seat'];

// Handle alert messages
$success_message = $_SESSION['payment_success'] ?? '';
$error_message = $_SESSION['payment_error'] ?? '';
unset($_SESSION['payment_success'], $_SESSION['payment_error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Method - Cinema Booking System</title>
    <link rel="stylesheet" href="../assets/css/payment.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>🎬 Cinema Booking System</h1>
            <p class="subtitle">Complete Your Payment</p>
        </div>
    </div>

    <div class="container">
        <!-- Alert Messages -->
        <?php if ($success_message): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($success_message); ?>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-error">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <div class="payment-container">
            <!-- Booking Summary -->
            <div class="booking-summary">
                <h2>📋 Booking Summary</h2>
                <div class="summary-item">
                    <span class="summary-label">Movie:</span>
                    <span class="summary-value"><?php echo htmlspecialchars($booking_data['movie_title']); ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Showtime:</span>
                    <span class="summary-value"><?php echo date('M d, Y - g:i A', strtotime($booking_data['showtime'])); ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Theater:</span>
                    <span class="summary-value"><?php echo htmlspecialchars($booking_data['theater']); ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Seats:</span>
                    <span class="summary-value"><?php echo implode(', ', $booking_data['seats']); ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Quantity:</span>
                    <span class="summary-value"><?php echo count($booking_data['seats']); ?> tickets</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Price per ticket:</span>
                    <span class="summary-value">$<?php echo number_format($booking_data['price_per_seat'], 2); ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">Total Amount:</span>
                    <span class="summary-value">$<?php echo number_format($total_amount, 2); ?></span>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="payment-form-card">
                <h2>💳 Payment Method</h2>
                
                <form id="paymentForm" action="../controllers/PaymentController.php" method="POST">
                    <input type="hidden" name="booking_id" value="<?php echo $booking_id; ?>">
                    <input type="hidden" name="amount" value="<?php echo $total_amount; ?>">
                    
                    <!-- Payment Method Selection -->
                    <div class="payment-methods">
                        <div class="payment-method" data-method="cash">
                            <input type="radio" id="cash" name="payment_method" value="cash" required>
                            <div class="payment-method-info">
                                <div class="payment-method-title">Cash Payment</div>
                                <div class="payment-method-desc">Pay at the counter when you arrive</div>
                            </div>
                            <div class="payment-method-icon">💵</div>
                        </div>

                        <div class="payment-method" data-method="online">
                            <input type="radio" id="online" name="payment_method" value="online" required>
                            <div class="payment-method-info">
                                <div class="payment-method-title">Online Payment</div>
                                <div class="payment-method-desc">Pay now with your credit/debit card</div>
                            </div>
                            <div class="payment-method-icon">💳</div>
                        </div>
                    </div>

                    <!-- Online Payment Form -->
                    <div id="onlinePaymentForm" class="online-payment-form">
                        <h3 style="color: var(--accent-color); margin-bottom: 1rem;">💳 Card Details</h3>
                        
                        <div class="form-group">
                            <label for="cardholder_name">Cardholder Name</label>
                            <input type="text" id="cardholder_name" name="cardholder_name" 
                                   placeholder="John Doe" autocomplete="cc-name">
                            <div class="error-message" id="cardholder_name_error"></div>
                        </div>

                        <div class="form-group">
                            <label for="card_number">Card Number</label>
                            <input type="text" id="card_number" name="card_number" 
                                   class="card-number-input" placeholder="1234 5678 9012 3456" 
                                   autocomplete="cc-number" maxlength="19">
                            <div class="error-message" id="card_number_error"></div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="expiry_date">Expiry Date</label>
                                <input type="text" id="expiry_date" name="expiry_date" 
                                       placeholder="MM/YY" autocomplete="cc-exp" maxlength="5">
                                <div class="error-message" id="expiry_date_error"></div>
                            </div>

                            <div class="form-group">
                                <label for="cvv">CVV</label>
                                <input type="text" id="cvv" name="cvv" 
                                       placeholder="123" autocomplete="cc-csc" maxlength="4">
                                <div class="error-message" id="cvv_error"></div>
                            </div>
                        </div>

                        <div class="security-badge">
                            Your payment information is secure and encrypted
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-full-width" id="submitBtn">
                        <span class="loading-spinner"></span>
                        <span class="btn-text">Complete Payment</span>
                    </button>
                </form>

                <!-- Back Button -->
                <a href="film_details.php?booking_id=<?php echo $booking_id; ?>" class="btn btn-secondary btn-full-width">
                    ← Back to Seat Selection
                </a>
            </div>
        </div>
    </div>

    <script src="../assets/js/payment.js"></script>
</body>
</html>

