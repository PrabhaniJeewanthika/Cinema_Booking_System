<?php

require_once 'db.php';

// Initialize variables
$errors = [];
$success_message = '';

// Only process if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    try {
        // Get database connection
        $database = new Database();
        $db = $database->getConnection();
        
        if (!$db) {
            throw new Exception('Database connection failed');
        }
        
        // Get and validate form data
        $booking_id = trim($_POST['booking_id'] ?? '');
        $payment_method = trim($_POST['payment_method'] ?? '');
        
        // Basic validation
        if (empty($booking_id)) {
            $errors[] = 'Booking ID is required';
        } elseif (!is_numeric($booking_id) || $booking_id <= 0) {
            $errors[] = 'Booking ID must be a positive number';
        }
        
        if (empty($payment_method)) {
            $errors[] = 'Payment method is required';
        } elseif (!in_array($payment_method, ['cash', 'online'])) {
            $errors[] = 'Invalid payment method';
        }
        
        // Check if booking exists
        if (empty($errors)) {
            $check_booking = $db->prepare("SELECT id FROM bookings WHERE id = ?");
            $check_booking->execute([$booking_id]);
            
            if ($check_booking->rowCount() == 0) {
                $errors[] = 'Booking not found';
            }
        }
        
        // Check if payment already exists for this booking
        if (empty($errors)) {
            $check_payment = $db->prepare("SELECT id FROM payments WHERE booking_id = ?");
            $check_payment->execute([$booking_id]);
            
            if ($check_payment->rowCount() > 0) {
                $errors[] = 'Payment already exists for this booking';
            }
        }
        
        // Validate online payment details if online method is selected
        if (empty($errors) && $payment_method === 'online') {
            $card_number = trim($_POST['card_number'] ?? '');
            $expiry_month = trim($_POST['expiry_month'] ?? '');
            $expiry_year = trim($_POST['expiry_year'] ?? '');
            $cvv = trim($_POST['cvv'] ?? '');
            $cardholder_name = trim($_POST['cardholder_name'] ?? '');
            
            // Validate card number
            if (empty($card_number)) {
                $errors[] = 'Card number is required for online payment';
            } elseif (!ctype_digit($card_number) || strlen($card_number) !== 16) {
                $errors[] = 'Card number must be exactly 16 digits';
            }
            
            // Validate expiry month
            if (empty($expiry_month)) {
                $errors[] = 'Expiry month is required for online payment';
            } elseif (!in_array($expiry_month, ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'])) {
                $errors[] = 'Invalid expiry month';
            }
            
            // Validate expiry year
            if (empty($expiry_year)) {
                $errors[] = 'Expiry year is required for online payment';
            } elseif (!in_array($expiry_year, ['25', '26', '27', '28', '29', '30'])) {
                $errors[] = 'Invalid expiry year';
            }
            
            // Validate CVV
            if (empty($cvv)) {
                $errors[] = 'CVV is required for online payment';
            } elseif (!ctype_digit($cvv) || strlen($cvv) !== 3) {
                $errors[] = 'CVV must be exactly 3 digits';
            }
            
            // Validate cardholder name
            if (empty($cardholder_name)) {
                $errors[] = 'Cardholder name is required for online payment';
            } elseif (strlen($cardholder_name) < 2 || strlen($cardholder_name) > 50) {
                $errors[] = 'Cardholder name must be between 2 and 50 characters';
            } elseif (!preg_match('/^[a-zA-Z\s]+$/', $cardholder_name)) {
                $errors[] = 'Cardholder name can only contain letters and spaces';
            }
            
            // Check if card is expired
            if (empty($errors)) {
                $current_year = date('y');
                $current_month = date('m');
                
                if ($expiry_year < $current_year || 
                    ($expiry_year == $current_year && $expiry_month < $current_month)) {
                    $errors[] = 'Card has expired';
                }
            }
            
            // Simulate payment processing (fail if card starts with 0000)
            if (empty($errors) && substr($card_number, 0, 4) === '0000') {
                $errors[] = 'Payment declined by bank. Please try a different card.';
            }
        }
        
        // If no errors, process the payment
        if (empty($errors)) {
            $payment_status = ($payment_method === 'online') ? 'paid' : 'pending';
            
            $insert_payment = $db->prepare("
                INSERT INTO payments (booking_id, payment_method, payment_status, payment_date) 
                VALUES (?, ?, ?, NOW())
            ");
            
            $insert_payment->execute([$booking_id, $payment_method, $payment_status]);
            $payment_id = $db->lastInsertId();
            
            if ($payment_method === 'cash') {
                $success_message = "Cash payment recorded successfully! Payment ID: $payment_id. Please pay at the counter.";
            } else {
                $success_message = "Online payment processed successfully! Payment ID: $payment_id.";
            }
        }
        
    } catch (Exception $e) {
        $errors[] = 'Database error: ' . $e->getMessage();
    }
}

// Redirect back to form with message
if (!empty($success_message)) {
    header("Location: payment_form.php?message=" . urlencode($success_message) . "&type=success");
    exit;
} elseif (!empty($errors)) {
    $error_message = implode('. ', $errors);
    header("Location: payment_form.php?message=" . urlencode($error_message) . "&type=error");
    exit;
} else {
    // If accessed directly without POST, redirect to form
    header("Location: payment_form.php");
    exit;
}
?>

