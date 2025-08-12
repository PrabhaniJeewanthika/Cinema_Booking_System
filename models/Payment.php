<?php
require_once __DIR__ . '/../config/db.php';

class Payment {
    private $db;
    private $table = 'payments';
    
    // Payment properties
    public $id;
    public $booking_id;
    public $payment_method;
    public $payment_status;
    public $payment_date;
    public $amount;
    public $transaction_id;
    
    /**
     * Constructor - Initialize database connection
     */
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Create a new payment record
     * @param int $booking_id
     * @param string $payment_method
     * @param float $amount
     * @return bool|int Returns payment ID on success, false on failure
     */
    public function createPayment($booking_id, $payment_method = 'cash', $amount = 0.00) {
        try {
            $query = "INSERT INTO {$this->table} (booking_id, payment_method, payment_status, amount, payment_date) 
                     VALUES (:booking_id, :payment_method, 'pending', :amount, NOW())";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt->bindParam(':payment_method', $payment_method, PDO::PARAM_STR);
            $stmt->bindParam(':amount', $amount, PDO::PARAM_STR);
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch (PDOException $e) {
            error_log("Payment creation error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update payment status
     * @param int $payment_id
     * @param string $status
     * @param string $transaction_id
     * @return bool
     */
    public function updatePaymentStatus($payment_id, $status, $transaction_id = null) {
        try {
            $query = "UPDATE {$this->table} 
                     SET payment_status = :status, payment_date = NOW()";
            
            if ($transaction_id) {
                $query .= ", transaction_id = :transaction_id";
            }
            
            $query .= " WHERE id = :payment_id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
            
            if ($transaction_id) {
                $stmt->bindParam(':transaction_id', $transaction_id, PDO::PARAM_STR);
            }
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Payment status update error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get payment by ID
     * @param int $payment_id
     * @return array|false
     */
    public function getPaymentById($payment_id) {
        try {
            $query = "SELECT p.*, b.user_id, b.movie_id, b.seats 
                     FROM {$this->table} p 
                     JOIN bookings b ON p.booking_id = b.id 
                     WHERE p.id = :payment_id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':payment_id', $payment_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Get payment error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get payment by booking ID
     * @param int $booking_id
     * @return array|false
     */
    public function getPaymentByBookingId($booking_id) {
        try {
            $query = "SELECT * FROM {$this->table} WHERE booking_id = :booking_id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Get payment by booking error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all payments for a user
     * @param int $user_id
     * @return array|false
     */
    public function getUserPayments($user_id) {
        try {
            $query = "SELECT p.*, b.movie_id, b.seats, m.title as movie_title 
                     FROM {$this->table} p 
                     JOIN bookings b ON p.booking_id = b.id 
                     JOIN movies m ON b.movie_id = m.id 
                     WHERE b.user_id = :user_id 
                     ORDER BY p.payment_date DESC";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Get user payments error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Validate payment method
     * @param string $method
     * @return bool
     */
    public function validatePaymentMethod($method) {
        $valid_methods = ['cash', 'online'];
        return in_array($method, $valid_methods);
    }
    
    /**
     * Validate payment status
     * @param string $status
     * @return bool
     */
    public function validatePaymentStatus($status) {
        $valid_statuses = ['pending', 'paid', 'failed', 'cancelled'];
        return in_array($status, $valid_statuses);
    }
    
    /**
     * Calculate total amount based on seats and movie pricing
     * @param array $seats
     * @param float $price_per_seat
     * @return float
     */
    public function calculateAmount($seats, $price_per_seat = 15.00) {
        if (is_string($seats)) {
            $seats = json_decode($seats, true);
        }
        
        if (!is_array($seats)) {
            return 0.00;
        }
        
        return count($seats) * $price_per_seat;
    }
    
    /**
     * Process online payment (simulation)
     * @param array $payment_data
     * @return array
     */
    public function processOnlinePayment($payment_data) {
        // Simulate online payment processing
        $response = [
            'success' => false,
            'transaction_id' => null,
            'message' => ''
        ];
        
        // Basic validation
        if (empty($payment_data['card_number']) || 
            empty($payment_data['expiry_date']) || 
            empty($payment_data['cvv']) || 
            empty($payment_data['cardholder_name'])) {
            $response['message'] = 'All payment fields are required';
            return $response;
        }
        
        // Simulate payment processing delay
        usleep(500000); // 0.5 second delay
        
        // Simulate success (90% success rate)
        if (rand(1, 10) <= 9) {
            $response['success'] = true;
            $response['transaction_id'] = 'TXN_' . time() . '_' . rand(1000, 9999);
            $response['message'] = 'Payment processed successfully';
        } else {
            $response['message'] = 'Payment processing failed. Please try again.';
        }
        
        return $response;
    }
}
?>

