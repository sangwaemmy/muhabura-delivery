<?php

    require_once 'Fpdf/fpdf.php';
    require_once '../web_db/connection.php';
    require_once './preppared_footer.php';
    require_once '../web_db/fin_books_sum_views.php';
    require_once '../web_db/other_fx.php';
    require_once '../web_db/Reports.php';

    class PDF extends FPDF {

// Load data
        function get_font() {
            $obj = new preppared_footer();
            return $font = $obj->fonts();
        }

        function LoadData() {
            // Read file lines


            $rpt=  new Reports();
            $database = new dbconnection();
            $db = $database->openconnection();
            $sql = "SELECT  * from p_type_project   ";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            // <editor-fold defaultstate="collapsed" desc="----text Above (header = company addresses) ------">
            $this->Image('../web_images/report_header.png');

            $this->Ln();
            $this->Ln();
            $this->Ln();
            $this->Ln();
            $this->Ln();
            $this->Ln();
            $this->Ln();
            $this->SetFont("Arial", 'B', 14);
            $this->Cell(170, 7, 'BUDGET LINES REPORT PREPARATION' );

            $this->Ln();
            $this->Ln();
            $this->SetFont("Arial", '', $this->get_font());
// </editor-fold>

            $this->Cell(15, 7, 'S/N', 1, 0, 'L');
            $this->Cell(45, 7, 'PROJECT NAME', 1, 0, 'L');
            $this->Cell(35, 7, 'REVENUES', 1, 0, 'L');
            $this->Cell(35, 7, 'EXPENSES', 1, 0, 'L');
            $this->Cell(35, 7, ' PROFIT', 1, 0, 'L');
            $this->Cell(25, 7, '%', 1, 0, 'L');
            $this->Ln();
            $this->SetFont("Arial", '', 10);
            while ($row = $stmt->fetch()) {
                
            $expense=$rpt->get_budget_Amount('expense',$row['p_type_project_id']);
              $revenue=$rpt->get_budget_Amount('revenue',$row['p_type_project_id']);
              $balance =$revenue-$expense;
              $rate=0;
              if ($revenue!=0)
              {
                $rate=$balance/$revenue;
              }
              else $rate=$balance;

                $this->cell(15, 7, $row['p_type_project_id'], 1, 0, 'L');
                $this->cell(45, 7, $row['name'], 1, 0, 'L');
                $this->cell(35, 7, number_format($revenue), 1, 0, 'L');
                $this->cell(35, 7, number_format($expense), 1, 0, 'L');
                $this->cell(35, 7, number_format($balance), 1, 0, 'L');
                $this->cell(25, 7, number_format($rate), 1, 0, 'L');
//                $pdf->SetTextColor(194,8,8)
                //$perc = $row5['tot'];
//                if ($perc < 50) {
//                    $this->SetTextColor(194, 8, 8);
//                    $this->cell(40, 7, $perc = number_format($row2['tot'] - $row5['tot']), 1, 0, 'L');
//                } else {
               // $this->cell(35, 7, $perc = number_format($row2['tot'] - $row5['tot']), 1, 0, 'L');
                //$this->cell(15, 7, number_format(($row2['tot'] - $row5['tot']) / $tot1 * 100) . '%', 1, 0, 'L');

//                }
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

    }

//    parent::__construct('L', 'mm', 'A2', true, 'UTF-8', $use_cache, false);
    $pdf = new PDF();

    $pdf->SetFont('Arial', '', 10);
    $pdf->AddPage();
    $pdf->LoadData();
    $pdf->prepared_by();
    $pdf->Output();
    