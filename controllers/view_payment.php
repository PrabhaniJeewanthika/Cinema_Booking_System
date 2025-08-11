<?php

require_once 'db.php';

try {
    // Get database connection
    $database = new Database();
    $db = $database->getConnection();
    
    if (!$db) {
        throw new Exception('Database connection failed');
    }
    
    // Get all payments with booking and user details
    $query = "
        SELECT 
            p.id as payment_id,
            p.booking_id,
            p.payment_method,
            p.payment_status,
            p.payment_date,
            b.seats,
            b.booking_date,
            u.name as user_name,
            u.email as user_email,
            m.title as movie_title
        FROM payments p
        JOIN bookings b ON p.booking_id = b.id
        JOIN users u ON b.user_id = u.id
        JOIN movies m ON b.movie_id = m.id
        ORDER BY p.payment_date DESC
    ";
    
    $stmt = $db->prepare($query);
    $stmt->execute();
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
} catch (Exception $e) {
    $error_message = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Records - Cinema Booking System</title>
    <link rel="stylesheet" href="../assets/css/Payment-style.css">
    <style>
        .payments-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .payments-table th,
        .payments-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .payments-table th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        
        .status-paid {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-pending {
            color: #ffc107;
            font-weight: bold;
        }
        
        .method-cash {
            background-color: #e7f3ff;
            padding: 4px 8px;
            border-radius: 4px;
        }
        
        .method-online {
            background-color: #e7ffe7;
            padding: 4px 8px;
            border-radius: 4px;
        }
        
        .no-payments {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #007bff;
            text-decoration: none;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="payment-form">
            <a href="payment_form.php" class="back-link">← Back to Payment Form</a>
            
            <h2>Payment Records</h2>
            
            <?php if (isset($error_message)): ?>
                <div class="status-message error">
                    Error: <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php elseif (empty($payments)): ?>
                <div class="no-payments">
                    <p>No payment records found.</p>
                    <p>Make a payment first to see records here.</p>
                </div>
            <?php else: ?>
                <table class="payments-table">
                    <thead>
                        <tr>
                            <th>Payment ID</th>
                            <th>Booking ID</th>
                            <th>User</th>
                            <th>Movie</th>
                            <th>Seats</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                            <th>Payment Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($payment['payment_id']); ?></td>
                                <td><?php echo htmlspecialchars($payment['booking_id']); ?></td>
                                <td>
                                    <?php echo htmlspecialchars($payment['user_name']); ?><br>
                                    <small><?php echo htmlspecialchars($payment['user_email']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($payment['movie_title']); ?></td>
                                <td><?php echo htmlspecialchars($payment['seats']); ?></td>
                                <td>
                                    <span class="method-<?php echo $payment['payment_method']; ?>">
                                        <?php echo ucfirst($payment['payment_method']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="status-<?php echo $payment['payment_status']; ?>">
                                        <?php echo ucfirst($payment['payment_status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M j, Y g:i A', strtotime($payment['payment_date'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

