<?php
require_once(__DIR__ . '/../controllers/ReceiptController.php');

if (isset($_GET['booking_id'])) {
    $controller = new ReceiptController();
    $controller->generatePDF((int)$_GET['booking_id']);
} else {
    echo "Booking ID is missing.";
}
?>



<?php
require('fpdf.php');
require_once(__DIR__ . '/../models/Booking.php');

if (!isset($_GET['booking_id'])) {
    die("Booking ID is missing.");
}

$model = new Booking();
$booking = $model->getFullReceiptDetails((int)$_GET['booking_id']);
if (!$booking) {
    die("Booking not found.");
}

class PDF extends FPDF
{
    function Header()
    {
        // Title
        $this->SetFont('Arial', 'B', 16);
        $this->Cell(0, 10, 'Movie Ticket Receipt', 0, 1, 'C');
        $this->Ln(5);
    }

    function ReceiptBody($booking)
    {
        $this->SetFont('Arial', '', 12);

        // Row 1
        $this->Cell(50, 10, 'Movie Name:', 1);
        $this->Cell(140, 10, $booking['movie_name'], 1);
        $this->Ln();

        // Row 2
        $this->Cell(50, 10, 'Date:', 1);
        $this->Cell(140, 10, $booking['date'], 1);
        $this->Ln();

        // Row 3
        $this->Cell(50, 10, 'Time:', 1);
        $this->Cell(140, 10, $booking['time'], 1);
        $this->Ln();

        // Row 4
        $this->Cell(50, 10, 'Seats:', 1);
        $this->Cell(140, 10, $booking['seats'], 1);
        $this->Ln();

        // Row 5
        $this->Cell(50, 10, 'Price:', 1);
        $this->Cell(140, 10, '$' . number_format($booking['price'], 2), 1);
        $this->Ln();
    }

    function Footer()
    {
        $this->SetY(-20);
        $this->SetFont('Arial', 'I', 10);
        $this->Cell(0, 10, 'Thank you for your booking!', 0, 0, 'C');
    }
}

$pdf = new PDF();
$pdf->AddPage();
$pdf->ReceiptBody($booking);
$pdf->Output('D', 'receipt.pdf');

