<?php
session_start();

$payment_id = $_GET['payment_id'] ?? 0;
$success_message = $_SESSION['payment_success'] ?? 'Payment completed successfully!';
$transaction_id = $_SESSION['transaction_id'] ?? null;

// Clear session messages
unset($_SESSION['payment_success'], $_SESSION['transaction_id']);

if (!$payment_id) {
    header('Location: index.php');
    exit;
}

// Mock payment data (in real implementation, fetch from database using PaymentController)
$payment_data = [
    'id' => $payment_id,
    'booking_id' => 1,
    'movie_title' => 'Avengers: Endgame',
    'showtime' => '2025-08-15 19:30:00',
    'seats' => ['A1', 'A2', 'A3'],
    'theater' => 'Theater 1',
    'amount' => 45.00,
    'payment_method' => 'online',
    'payment_status' => 'paid',
    'payment_date' => date('Y-m-d H:i:s'),
    'transaction_id' => $transaction_id
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Successful - Cinema Booking System</title>
    <link rel="stylesheet" href="../assets/css/payment.css">
    <style>
        .success-container {
            max-width: 600px;
            margin: 2rem auto;
            text-align: center;
        }
        
        .success-icon {
            font-size: 4rem;
            color: var(--success-color);
            margin-bottom: 1rem;
            animation: bounce 1s ease-in-out;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-10px);
            }
            60% {
                transform: translateY(-5px);
            }
        }
        
        .success-title {
            font-size: 2.5rem;
            color: var(--success-color);
            margin-bottom: 1rem;
            font-weight: 700;
        }
        
        .success-message {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            color: var(--text-light);
        }
        
        .payment-details {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: var(--border-radius);
            padding: 2rem;
            margin: 2rem 0;
            text-align: left;
        }
        
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .detail-row:last-child {
            border-bottom: none;
            font-weight: bold;
            color: var(--accent-color);
        }
        
        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }
        
        .btn-icon {
            margin-right: 0.5rem;
        }
        
        @media (max-width: 768px) {
            .success-title {
                font-size: 2rem;
            }
            
            .action-buttons {
                flex-direction: column;
                align-items: center;
            }
            
            .btn {
                width: 100%;
                max-width: 300px;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="container">
            <h1>🎬 Cinema Booking System</h1>
            <p class="subtitle">Payment Confirmation</p>
        </div>
    </div>

    <div class="container">
        <div class="success-container">
            <div class="success-icon">✅</div>
            <h1 class="success-title">Payment Successful!</h1>
            <p class="success-message"><?php echo htmlspecialchars($success_message); ?></p>
            
            <div class="payment-details">
                <h3 style="color: var(--accent-color); margin-bottom: 1rem; text-align: center;">📋 Payment Details</h3>
                
                <div class="detail-row">
                    <span>Payment ID:</span>
                    <span>#<?php echo str_pad($payment_data['id'], 6, '0', STR_PAD_LEFT); ?></span>
                </div>
                
                <div class="detail-row">
                    <span>Movie:</span>
                    <span><?php echo htmlspecialchars($payment_data['movie_title']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span>Showtime:</span>
                    <span><?php echo date('M d, Y - g:i A', strtotime($payment_data['showtime'])); ?></span>
                </div>
                
                <div class="detail-row">
                    <span>Theater:</span>
                    <span><?php echo htmlspecialchars($payment_data['theater']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span>Seats:</span>
                    <span><?php echo implode(', ', $payment_data['seats']); ?></span>
                </div>
                
                <div class="detail-row">
                    <span>Payment Method:</span>
                    <span><?php echo ucfirst($payment_data['payment_method']); ?></span>
                </div>
                
                <?php if ($payment_data['transaction_id']): ?>
                <div class="detail-row">
                    <span>Transaction ID:</span>
                    <span><?php echo htmlspecialchars($payment_data['transaction_id']); ?></span>
                </div>
                <?php endif; ?>
                
                <div class="detail-row">
                    <span>Payment Date:</span>
                    <span><?php echo date('M d, Y - g:i A', strtotime($payment_data['payment_date'])); ?></span>
                </div>
                
                <div class="detail-row">
                    <span>Total Amount:</span>
                    <span>$<?php echo number_format($payment_data['amount'], 2); ?></span>
                </div>
            </div>
            
            <div class="action-buttons">
                <a href="generate_ticket.php?payment_id=<?php echo $payment_id; ?>" class="btn btn-primary">
                    <span class="btn-icon">🎫</span>
                    Download Ticket
                </a>
                
                <a href="my_bookings.php" class="btn btn-secondary">
                    <span class="btn-icon">📋</span>
                    View My Bookings
                </a>
                
                <a href="index.php" class="btn btn-secondary">
                    <span class="btn-icon">🏠</span>
                    Back to Home
                </a>
            </div>
            
            <div style="margin-top: 2rem; padding: 1rem; background: rgba(40, 167, 69, 0.2); border-radius: var(--border-radius); color: var(--success-color);">
                <p><strong>📧 Confirmation Email Sent</strong></p>
                <p style="font-size: 0.9rem; margin-top: 0.5rem;">
                    A confirmation email with your ticket details has been sent to your registered email address.
                </p>
            </div>
            
            <?php if ($payment_data['payment_method'] === 'cash'): ?>
            <div style="margin-top: 1rem; padding: 1rem; background: rgba(255, 193, 7, 0.2); border-radius: var(--border-radius); color: var(--warning-color);">
                <p><strong>💰 Cash Payment Reminder</strong></p>
                <p style="font-size: 0.9rem; margin-top: 0.5rem;">
                    Please pay $<?php echo number_format($payment_data['amount'], 2); ?> at the cinema counter before the show starts.
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Auto-redirect after 30 seconds (optional)
        // setTimeout(() => {
        //     window.location.href = 'index.php';
        // }, 30000);
        
        // Print functionality
        function printReceipt() {
            window.print();
        }
        
        // Add print button if needed
        document.addEventListener('DOMContentLoaded', function() {
            const actionButtons = document.querySelector('.action-buttons');
            const printBtn = document.createElement('button');
            printBtn.className = 'btn btn-secondary';
            printBtn.innerHTML = '<span class="btn-icon">🖨️</span>Print Receipt';
            printBtn.onclick = printReceipt;
            actionButtons.appendChild(printBtn);
        });
    </script>
</body>
</html>

