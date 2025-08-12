<?php
session_start();
require_once __DIR__ . '/../models/Payment.php';

class PaymentController {
    private $paymentModel;
    
    /**
     * Constructor - Initialize payment model
     */
    public function __construct() {
        $this->paymentModel = new Payment();
    }
    
    /**
     * Display payment method selection page
     * @param int $booking_id
     */
    public function showPaymentMethods($booking_id) {
        // Validate booking ID
        if (!$booking_id || !is_numeric($booking_id)) {
            $this->redirectWithError('Invalid booking ID');
            return;
        }
        
        // Check if payment already exists for this booking
        $existing_payment = $this->paymentModel->getPaymentByBookingId($booking_id);
        if ($existing_payment && $existing_payment['payment_status'] === 'paid') {
            $this->redirectWithError('Payment already completed for this booking');
            return;
        }
        
        // Get booking details (you would need to implement this in Booking model)
        // For now, we'll pass the booking_id to the view
        $_SESSION['current_booking_id'] = $booking_id;
        
        // Redirect to payment method page
        header('Location: ../user/payment_method.php?booking_id=' . $booking_id);
        exit;
    }
    
    /**
     * Process payment method selection
     */
    public function processPaymentMethod() {
        try {
            // Validate session and POST data
            if (!isset($_POST['booking_id']) || !isset($_POST['payment_method'])) {
                throw new Exception('Missing required payment data');
            }
            
            $booking_id = (int)$_POST['booking_id'];
            $payment_method = trim($_POST['payment_method']);
            $amount = isset($_POST['amount']) ? (float)$_POST['amount'] : 0.00;
            
            // Validate payment method
            if (!$this->paymentModel->validatePaymentMethod($payment_method)) {
                throw new Exception('Invalid payment method selected');
            }
            
            // Check if payment record already exists
            $existing_payment = $this->paymentModel->getPaymentByBookingId($booking_id);
            
            if (!$existing_payment) {
                // Create new payment record
                $payment_id = $this->paymentModel->createPayment($booking_id, $payment_method, $amount);
                if (!$payment_id) {
                    throw new Exception('Failed to create payment record');
                }
            } else {
                $payment_id = $existing_payment['id'];
            }
            
            // Process based on payment method
            if ($payment_method === 'cash') {
                $this->processCashPayment($payment_id);
            } else {
                $this->processOnlinePayment($payment_id);
            }
            
        } catch (Exception $e) {
            $this->redirectWithError($e->getMessage());
        }
    }
    
    /**
     * Process cash payment
     * @param int $payment_id
     */
    private function processCashPayment($payment_id) {
        // For cash payments, we mark as paid immediately
        $success = $this->paymentModel->updatePaymentStatus($payment_id, 'paid');
        
        if ($success) {
            $_SESSION['payment_success'] = 'Cash payment confirmed. Please pay at the counter.';
            header('Location: ../user/payment_success.php?payment_id=' . $payment_id);
        } else {
            $this->redirectWithError('Failed to process cash payment');
        }
        exit;
    }
    
    /**
     * Process online payment
     * @param int $payment_id
     */
    private function processOnlinePayment($payment_id) {
        // Validate online payment data
        $required_fields = ['card_number', 'expiry_date', 'cvv', 'cardholder_name'];
        $payment_data = [];
        
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty(trim($_POST[$field]))) {
                $this->redirectWithError('All payment fields are required');
                return;
            }
            $payment_data[$field] = trim($_POST[$field]);
        }
        
        // Additional validation
        if (!$this->validateCardNumber($payment_data['card_number'])) {
            $this->redirectWithError('Invalid card number format');
            return;
        }
        
        if (!$this->validateExpiryDate($payment_data['expiry_date'])) {
            $this->redirectWithError('Invalid or expired card');
            return;
        }
        
        if (!$this->validateCVV($payment_data['cvv'])) {
            $this->redirectWithError('Invalid CVV');
            return;
        }
        
        // Process online payment
        $payment_result = $this->paymentModel->processOnlinePayment($payment_data);
        
        if ($payment_result['success']) {
            // Update payment status to paid
            $success = $this->paymentModel->updatePaymentStatus(
                $payment_id, 
                'paid', 
                $payment_result['transaction_id']
            );
            
            if ($success) {
                $_SESSION['payment_success'] = $payment_result['message'];
                $_SESSION['transaction_id'] = $payment_result['transaction_id'];
                header('Location: ../user/payment_success.php?payment_id=' . $payment_id);
            } else {
                $this->redirectWithError('Payment processed but failed to update record');
            }
        } else {
            // Update payment status to failed
            $this->paymentModel->updatePaymentStatus($payment_id, 'failed');
            $this->redirectWithError($payment_result['message']);
        }
        exit;
    }
    
    /**
     * Get payment details for display
     * @param int $payment_id
     * @return array|false
     */
    public function getPaymentDetails($payment_id) {
        return $this->paymentModel->getPaymentById($payment_id);
    }
    
    /**
     * Validate card number (basic Luhn algorithm)
     * @param string $card_number
     * @return bool
     */
    private function validateCardNumber($card_number) {
        // Remove spaces and non-numeric characters
        $card_number = preg_replace('/\D/', '', $card_number);
        
        // Check length (13-19 digits for most cards)
        if (strlen($card_number) < 13 || strlen($card_number) > 19) {
            return false;
        }
        
        // Simple Luhn algorithm check
        $sum = 0;
        $alternate = false;
        
        for ($i = strlen($card_number) - 1; $i >= 0; $i--) {
            $digit = (int)$card_number[$i];
            
            if ($alternate) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit = ($digit % 10) + 1;
                }
            }
            
            $sum += $digit;
            $alternate = !$alternate;
        }
        
        return ($sum % 10) === 0;
    }
    
    /**
     * Validate expiry date
     * @param string $expiry_date Format: MM/YY
     * @return bool
     */
    private function validateExpiryDate($expiry_date) {
        if (!preg_match('/^(0[1-9]|1[0-2])\/([0-9]{2})$/', $expiry_date, $matches)) {
            return false;
        }
        
        $month = (int)$matches[1];
        $year = (int)$matches[2] + 2000; // Convert YY to YYYY
        
        $current_year = (int)date('Y');
        $current_month = (int)date('m');
        
        // Check if card is expired
        if ($year < $current_year || ($year === $current_year && $month < $current_month)) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Validate CVV
     * @param string $cvv
     * @return bool
     */
    private function validateCVV($cvv) {
        return preg_match('/^[0-9]{3,4}$/', $cvv);
    }
    
    /**
     * Redirect with error message
     * @param string $message
     */
    private function redirectWithError($message) {
        $_SESSION['payment_error'] = $message;
        header('Location: ../user/payment_method.php');
        exit;
    }
    
    /**
     * Handle AJAX requests for payment processing
     */
    public function handleAjaxRequest() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            exit;
        }
        
        try {
            $action = $_POST['action'] ?? '';
            
            switch ($action) {
                case 'validate_card':
                    $this->validateCardAjax();
                    break;
                case 'process_payment':
                    $this->processPaymentAjax();
                    break;
                default:
                    throw new Exception('Invalid action');
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
    
    /**
     * AJAX card validation
     */
    private function validateCardAjax() {
        $card_number = $_POST['card_number'] ?? '';
        $is_valid = $this->validateCardNumber($card_number);
        
        echo json_encode([
            'success' => true,
            'valid' => $is_valid,
            'message' => $is_valid ? 'Valid card number' : 'Invalid card number'
        ]);
    }
    
    /**
     * AJAX payment processing
     */
    private function processPaymentAjax() {
        // This would be similar to processOnlinePayment but return JSON
        // Implementation depends on specific requirements
        echo json_encode(['success' => true, 'message' => 'AJAX payment processing not implemented']);
    }
}

// Handle direct requests to this controller
if (basename($_SERVER['PHP_SELF']) === 'PaymentController.php') {
    $controller = new PaymentController();
    
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'show_methods':
                $booking_id = $_GET['booking_id'] ?? 0;
                $controller->showPaymentMethods($booking_id);
                break;
            case 'ajax':
                $controller->handleAjaxRequest();
                break;
        }
    } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->processPaymentMethod();
    }
}
?>

