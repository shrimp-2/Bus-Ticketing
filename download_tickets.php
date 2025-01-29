<?php
require('fpdf/fpdf.php');
include 'connection.php'; 

if (isset($_GET['booking_id'])) {
    $booking_id = $_GET['booking_id'];

    $sql = "
        SELECT 
            bk.id AS booking_id,
            b.bus_number, 
            r.pickup_location, 
            r.dropoff_location, 
            b.departure_time, 
            b.arrival_time, 
            u.full_name, 
            u.email, 
            bk.seats, 
            bk.total_price
        FROM bookings bk
        JOIN buses b ON bk.bus_id = b.id
        JOIN routes r ON b.current_route_id = r.id
        JOIN users u ON bk.username = u.username
        WHERE bk.id = ?
    ";

    if ($stmt = $con->prepare($sql)) {
        $stmt->bind_param('i', $booking_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $data = $result->fetch_assoc();

            $pdf = new FPDF();
            $pdf->AddPage();
            
        
            $pdf->SetFont('Arial', 'B', 16);
            $pdf->Cell(0, 10, 'Bus Ticket', 0, 1, 'C');
            $pdf->Ln(5);

            // Passenger Information
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->SetFillColor(230, 230, 230); 
            $pdf->Cell(0, 10, 'Passenger Information', 1, 1, 'C', true);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(50, 10, 'Name:', 1, 0, 'L');
            $pdf->Cell(0, 10, $data['full_name'], 1, 1);
            $pdf->Cell(50, 10, 'Email:', 1, 0, 'L');
            $pdf->Cell(0, 10, $data['email'], 1, 1);

            $pdf->Ln(5);

            // Travel Information
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'Travel Information', 1, 1, 'C', true);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(50, 10, 'Bus Number:', 1, 0, 'L');
            $pdf->Cell(0, 10, $data['bus_number'], 1, 1);
            $pdf->Cell(50, 10, 'Pickup:', 1, 0, 'L');
            $pdf->Cell(0, 10, $data['pickup_location'], 1, 1);
            $pdf->Cell(50, 10, 'Dropoff:', 1, 0, 'L');
            $pdf->Cell(0, 10, $data['dropoff_location'], 1, 1);
            $pdf->Cell(50, 10, 'Departure Time:', 1, 0, 'L');
            $pdf->Cell(0, 10, $data['departure_time'], 1, 1);
            $pdf->Cell(50, 10, 'Arrival Time:', 1, 0, 'L');
            $pdf->Cell(0, 10, $data['arrival_time'], 1, 1);

            $pdf->Ln(5);

            // Booking Details
            $pdf->SetFont('Arial', 'B', 12);
            $pdf->Cell(0, 10, 'Booking Details', 1, 1, 'C', true);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(50, 10, 'Seats:', 1, 0, 'L');
            $pdf->Cell(0, 10, $data['seats'], 1, 1);
            $pdf->Cell(50, 10, 'Total Price:', 1, 0, 'L');
            $pdf->Cell(0, 10, 'Rs. ' . $data['total_price'], 1, 1);

            $pdf->Ln(10);
            $pdf->SetFont('Arial', 'I', 10);
            $pdf->SetTextColor(128, 128, 128);
            $pdf->Cell(0, 10, 'Thank you for traveling with us!', 0, 1, 'C');

            $pdf->Output('D', 'ticket_' . $booking_id . '.pdf');
        } else {
            echo "Invalid Booking ID or no matching records found.";
        }

        $stmt->close();
    } else {
        echo "Error in preparing the SQL statement.";
    }
} else {
    echo "No booking ID provided.";
}

$con->close();
?>
