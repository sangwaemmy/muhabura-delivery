<?php

    require_once 'Fpdf/fpdf.php';
    require_once '../web_db/connection.php';
    require_once './preppared_footer.php';

    class PDF extends FPDF {

// Load data
        function get_font() {
            $obj = new preppared_footer();
            return $font = $obj->fonts();
        }

        function LoadData() {
            // Read file lines
            $database = new dbconnection();
            $db = $database->openconnection();
           $sql = " SELECT  * from p_request
                 join p_budget_items  on  p_budget_items.p_budget_items_id= p_request.item 
                 join user on user.StaffID = p_request.User 
                 join measurement on measurement.measurement_id=p_request.measurement
                 where p_request.main_req= 53 ";
            // <editor-fold defaultstate="collapsed" desc="----text Above (header = company addresses) ------">

            $this->Image('../web_images/report_header.png');

            $this->Ln();
            $this->Ln();
            $this->Ln();
            $this->Ln();
            $this->Ln();
            $this->Ln();
            $this->Ln();

            $this->Ln();
            $this->SetFont("Arial", 'B', 14);
            $this->Cell(270, 7, 'REQUESTS REPORT ', 0, 0, 'C');

            $this->Ln();
            $this->Ln();
            $this->SetFont("Arial", '', 11);
// </editor-fold>
//            $this->Cell(30, 7, 'S/N', 1, 0, 'L');
            $this->SetFont('Arial', '', $this->get_font());
//          $this->Cell(30, 7, 'measurement', 1, 0, 'L');
            $this->Cell(40, 7, strtoupper('item'), 1, 0, 'L');
            $this->Cell(30, 7, strtoupper('quantity'), 1, 0, 'L');
            $this->Cell(30, 7, strtoupper('unit cost'), 1, 0, 'L');
            $this->Cell(30, 7, strtoupper('amount'), 1, 0, 'L');
            $this->Cell(40, 7, strtoupper('entry date'), 1, 0, 'L');
            $this->Cell(65, 7, strtoupper('User'), 1, 0, 'L');
//            $this->Cell(30, 7, 'request_no', 1, 0, 'L');
            $this->Ln();
            $this->SetFont("Arial", '', $this->get_font());
            foreach ($db->query($sql) as $row) {
//                $this->cell(30, 7, $row['p_request_id'], 1, 0, 'L');
//                $this->cell(30, 7, $row['measurement'], 1, 0, 'L');
                $this->cell(40, 7, $row['item_name'], 1, 0, 'L');
                $this->cell(30, 7, $row['quantity'] . ' ' . $row['code'], 1, 0, 'L');
                $this->cell(30, 7, $row['unit_cost'], 1, 0, 'L');
                $this->cell(30, 7, $row['amount'], 1, 0, 'L');
                $this->cell(40, 7, $row['entry_date'], 1, 0, 'L');
                $this->cell(65, 7, $row['Firstname'] . ' ' . $row['Lastname'], 1, 0, 'L');
//                $this->cell(30, 7, $row['request_no'], 1, 0, 'L');
                $this->Ln();
            }
        }

        function prepared_by() {
            $this->SetFont('Arial', 'I', 8);
            // Print centered page number
            $this->Cell(0, 10, ' ', 0, 0, 'R');
            $this->Ln();
            $this->Cell(0, 10, ' ', 0, 0, 'R');
            $this->Ln();

            $this->Image('../web_images/preparedby_footer.png');
        }

        function Approved_by() {
            // Go to 1.5 cm from bottom
//            $this->SetY(-32);
            // Select Arial italic 8
            $this->SetFont('Arial', 'I', 8);
            // Print centered page number
            $this->Cell(0, 30, 'Approved by........................................................ ', 0, 0, 'R');
//            $this->Ln();
//            $this->Cell(0, 10, 'Approved by............................. ', 1, 0, 'R');
        }

    }

    $pdf = new PDF();
    $pdf->SetFont('Arial', '', $pdf->get_font());
    $pdf->AddPage('L');
    $pdf->LoadData();
    $pdf->Ln();
    $pdf->prepared_by();

    $pdf->Output();

//    $pdf->Footer();
   
    