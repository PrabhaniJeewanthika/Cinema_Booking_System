<?php
require_once(__DIR__ . '/../controllers/TicketController.php');

if (isset($_GET['booking_id'])) {
    $controller = new TicketController();
    $controller->generatePDF((int)$_GET['booking_id']);
} else {
    echo "Booking ID is missing.";
}
