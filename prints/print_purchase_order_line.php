<?php

    require_once 'Fpdf/fpdf.php';
    require_once '../web_db/connection.php';
    require_once './preppared_footer.php';
     require_once '../web_db/Reports.php';

    class PDF extends FPDF {

// Load data
        function LoadData() {
            // Read file lines
            $rpt= new Reports ();
            $database = new dbconnection();
            $db = $database->openconnection();
            $sql = "SELECT  * from purchase_order_line  join  user on user.StaffID=purchase_order_line.User   JOIN p_request on p_request.p_request_id = purchase_order_line.request ";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            // <editor-fold defaultstate="collapsed" desc="----text Above (header = company addresses) ------">
            $this->Cell(120, 7, 'MUHABURA MULTI CHOICE COMPANY', 0, 0, 'L');
            $this->Cell(60, 7, 'DISTRICT: GASABO', 0, 0, 'L');
            $this->Ln();
            $this->Cell(120, 7, 'RWANDA ', 0, 0, 'E');
            $this->Cell(60, 7, 'TELEPHONE: . ', 0, 0, 'L');

            $this->Ln();
            $this->Ln();
            $this->Ln();
            $this->Ln();
            $this->SetFont("Arial", 'B', 14);
            $this->Cell(170, 7, 'PURCHASE ORDERS REPORT ', 0, 0, 'C');

            $this->Ln();
            $this->Ln();
            $this->SetFont("Arial", '', 10);
// </editor-fold>
            $this->Cell(12, 7, 'S/N', 1, 0, 'L');
            $this->Cell(35, 7, 'Date', 1, 0, 'L');
            $this->Cell(50, 7, 'Done by', 1, 0, 'L');
            $this->Cell(50, 7, 'Requested by ', 1, 0, 'L');
            $this->Cell(17, 7, 'quantity', 1, 0, 'L');
            $this->Cell(17, 7, 'cost', 1, 0, 'L');
//            $this->Cell(30, 7, 'discount', 1, 0, 'L');
            $this->Cell(17, 7, 'amount', 1, 0, 'L');
            $this->Ln();
            $this->SetFont("Arial", '', $this->get_font());
            while ($row = $stmt->fetch()) {
                $this->cell(12, 7, $row['purchase_order_line_id'], 1, 0, 'L');
                $this->cell(35, 7, $row['entry_date'], 1, 0, 'L');
                $this->cell(50, 7, $row['Firstname'] . '  ' . $row['Lastname'], 1, 0, 'L');
                $this->cell(50, 7, $rpt->get_requester($row['p_request_id']), 1, 0, 'L');
                $this->cell(17, 7, $row['quantity'], 1, 0, 'L');
                $this->cell(17, 7, number_format($row['unit_cost']), 1, 0, 'L');
//              $this->cell(30, 7, $row['discount'], 1, 0, 'L');
                $this->cell(17, 7, number_format($row['amount']), 1, 0, 'L');


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

            $this->Image('../web_images/prepared_by_protrait.png');
        }

        function get_font() {
            $obj = new preppared_footer();
            return $font = $obj->fonts();
        }

    }

    $pdf = new PDF();
    $pdf->SetFont('Arial', '', 10);
    $pdf->AddPage();
    $pdf->LoadData();
    $pdf->prepared_by();
    $pdf->Output();
    