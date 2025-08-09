<?php
require_once(__DIR__ . '/../models/Booking.php');
require_once(__DIR__ . '/../libs/fpdf.php'); // Path to FPDF

class TicketController {
    public function generatePDF($booking_id) {
        $model = new Booking();
        $booking = $model->getFullReceiptDetails($booking_id);

        if (!$booking) {
            die("Booking not found.");
        }

        $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 18);
        $pdf->Cell(0, 10, 'Cinema Booking Receipt', 0, 1, 'C');
        $pdf->Ln(10);

        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(50, 8, 'Booking ID:', 0, 0);
        $pdf->Cell(0, 8, $booking['booking_id'], 0, 1);

        $pdf->Cell(50, 8, 'Customer Name:', 0, 0);
        $pdf->Cell(0, 8, $booking['user_name'], 0, 1);

        $pdf->Cell(50, 8, 'Email:', 0, 0);
        $pdf->Cell(0, 8, $booking['user_email'], 0, 1);

        $pdf->Ln(5);
        $pdf->Cell(50, 8, 'Movie:', 0, 0);
        $pdf->Cell(0, 8, $booking['movie_title'], 0, 1);

        $pdf->Cell(50, 8, 'Description:', 0, 1);
        $pdf->MultiCell(0, 8, $booking['movie_description']);

        $pdf->Cell(50, 8, 'Duration:', 0, 0);
        $pdf->Cell(0, 8, $booking['movie_duration'] . ' mins', 0, 1);

        $pdf->Ln(5);
        $pdf->Cell(50, 8, 'Seats:', 0, 0);
        $pdf->Cell(0, 8, $booking['seats'], 0, 1);

        $pdf->Cell(50, 8, 'Booking Date:', 0, 0);
        $pdf->Cell(0, 8, $booking['booking_date'], 0, 1);

        $pdf->Ln(5);
        $pdf->Cell(50, 8, 'Payment Method:', 0, 0);
        $pdf->Cell(0, 8, ucfirst($booking['payment_method']), 0, 1);

        $pdf->Cell(50, 8, 'Payment Status:', 0, 0);
        $pdf->Cell(0, 8, ucfirst($booking['payment_status']), 0, 1);

        $pdf->Cell(50, 8, 'Payment Date:', 0, 0);
        $pdf->Cell(0, 8, $booking['payment_date'], 0, 1);

        $pdf->Ln(15);
        $pdf->Cell(0, 10, 'Thank you for booking with us!', 0, 1, 'C');

        // Force download
        $pdf->Output('D', 'receipt_booking_' . $booking_id . '.pdf');
    }
}
