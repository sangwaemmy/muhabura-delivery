<?php
    session_start();
    require_once '../web_db/multi_values.php';
    if (!isset($_SESSION)) {
        session_start();
    }
    if (!isset($_SESSION['login_token'])) {
        header('location:../index.php');
    }
    if (isset($_POST['send_purchase_invoice_line'])) {
        if (isset($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_invoice_line') {
                require_once '../web_db/updates.php';
                $upd_obj = new updates();
                $purchase_invoice_line_id = $_SESSION['id_upd'];
                $entry_date = date("y-m-d");
                $User = $_SESSION['userid'];
                $quantity = $_POST['txt_quantity'];
                $unit_cost = $_POST['txt_unit_cost'];
                $amount = $_POST['txt_amount'];
                $purchase_order = $_POST['txt_purchase_order_id'];
                $activity = $_POST['txt_activity_id'];
                $upd_obj->update_purchase_invoice_line($entry_date, $User, $quantity, $unit_cost, $amount, $purchase_order, $activity, $purchase_invoice_line_id);
                unset($_SESSION['table_to_update']);
            }
        } else {
            $entry_date = date("y-m-d h:m:s");
            $User = $_SESSION['userid'];
            $quantity = $_POST['txt_quantity'];
            $unit_cost = filter_input(INPUT_POST, 'txt_unit_cost', FILTER_SANITIZE_NUMBER_INT);
            $amount = filter_input(INPUT_POST, 'txt_amount', FILTER_SANITIZE_NUMBER_INT);
            $purchase_order = trim($_POST['txt_purchase_order_id']);
            $activity = trim($_POST['txt_activity_id']);
            $expense_cbo = trim($_POST['expense_cbo']);
            $cash_cbo = trim($_POST['asset_cbo']);
            $supplier = trim($_POST['txt_supplier_id']);
            $tot = $unit_cost * $quantity;
            require_once '../web_db/new_values.php';
            $obj = new new_values();
            $tax_inclusive = (filter_input(INPUT_POST, 'chk_tax_inc')) ? 'yes' : 'no';
            $obj->new_purchase_invoice_line($entry_date, $User, $quantity, $unit_cost, filter_var($tot, FILTER_SANITIZE_NUMBER_INT), $purchase_order, $activity, $expense_cbo, $supplier, $tax_inclusive, $cash_cbo);
//            now save the account payable in the journal;

            $obj->new_journal_entry_header($supplier, 'supplier', $entry_date, "Purchasing", 0, 'yes');
            $m = new multi_values();
            $last_head = $m->get_last_journal_entry_header();

            $pay_method = (filter_input(INPUT_POST, 'rd_bank_cash') == 'Bank') ? 'not reconciled' : 'neutral';
            $obj->new_journal_entry_line($expense_cbo, 'Debit', $tot, "Expenses incurred on purchase", $last_head, date('y-m-d h:m:s'), "purchase", "neutral", $activity, $User);
            $obj->new_journal_entry_line($cash_cbo, 'credit', $tot, "Cash out on fom cash bank on purchase", $last_head, date('y-m-d h:m:s'), "purchase", $pay_method, $activity, $User);
            if (!empty($tax_inclusive)) {
                $percentage = filter_input(INPUT_POST, 'txt_percentage');
                $amount = $tot / 1.18;
                $m = new multi_values();
                $lat_purchase = $m->get_last_purchase_invoice_line();
                $vat_amount = $amount;
                $pur_sale = 'sale';
//                $obj->new_vat($Total_val_of_Sup, $exempted_sales, $exports, $total_not_taxable, $taxable_sales_sub_vat, $vat_on_taxable_sales, $vat_reversecharge, $vat_payable, $vat_paid_on_imports, $vat_paidon_local_pur, $vat_rev_char_deduct, $vat_pay_cre_ref, $vat_with_hold_pub, $vat_due_cred_pay, $vat_refund, $vat_due);
                $obj->new_vat_calculation($lat_purchase, $vat_amount, $entry_date, $User, 0, $pur_sale);
            }
        }
    }
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>
            purchase_invoice_line
        </title>
        <link href="web_style/styles.css" rel="stylesheet" type="text/css"/>
        <link href="web_style/StylesAddon.css" rel="stylesheet" type="text/css"/>
        <link href="admin_style.css" rel="stylesheet" type="text/css"/> <meta name="viewport" content="width=device-width, initial scale=1.0"/>
    </head>
    <body>
        <?php
            include 'admin_header.php';
        ?>
        <!--Start of Type Details-->
        <div class="parts p_project_type_details data_details_pane abs_full margin_free white_bg">

        </div>
        <!--End Tuype Details-->   
        <!--Start of opening Type project Pane(The pane that allow the user to add the data in a different table without leaving the current one)-->
        <div class="parts abs_full eighty_centered onfly_pane_p_type_project">
            <div class="parts  full_center_two_h heit_free">
                <div class="parts pane_opening_balance  full_center_two_h heit_free no_shade_noBorder">
                    <table class="new_data_table" >
                        <thead>Add new Budget Line</thead>
                        <tr>
                            <td>Name</td> <td><input type="text"  class="textbox"  id="onfly_txt_name" />  </td>
                        </tr>
                        <tr>
                            <td colspan="2">                                
                                <input type="button" class="confirm_buttons btn_onfly_save_p_type_project" name="send_account" value="Save & close"/> 
                                <input type="button" class="confirm_buttons reverse_bg_btn reverse_color cancel_btn_onfly" name="send_account" value="Cancel"/> 
                            </td>
                        </tr>
                    </table>
                </div>
            </div>  
        </div>
        <!--End of opening Pane-->

        <!--Start of opening p_activity Pane(The pane that allow the user to add the data in a different table without leaving the current one)-->
        <div class="parts abs_full eighty_centered onfly_pane_p_activity">
            <div class="parts  full_center_two_h heit_free">
                <div class="parts pane_opening_balance  full_center_two_h heit_free no_shade_noBorder">
                    <table class="new_data_table" >
                        <thead>Add new p_activity</thead>
                        <tr>     <td>name</td> <td><input type="text"  class="textbox"  id="onfly_txt_name" />  </td>
                        <tr>     <td>fisc_year</td> <td><input type="text"  class="textbox"  id="onfly_txt_fisc_year" />  </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="button" class="confirm_buttons btn_onfly_save_p_activity" name="send_p_activity" value="Save & close"/> 
                                <input type="button" class="confirm_buttons reverse_bg_btn reverse_color cancel_btn_onfly" name="send_account" value="Cancel"/> 
                            </td>
                        </tr>
                    </table>
                </div>
            </div>  
        </div>
        <!--End of opening Pane-->
        <div class="parts eighty_centered no_paddin_shade_no_Border">  
            <div class="parts  no_paddin_shade_no_Border new_data_hider"> Add Purchase Invoice   </div>
            <div class="parts no_paddin_shade_no_Border page_search link_cursor">Search</div>
        </div>
        <div class="parts eighty_centered off saved_dialog">
            purchase_invoice_line saved successfully!</div>
        <div class="parts eighty_centered new_data_box off">
            <div class="parts eighty_centered ">  purchase invoice </div>

            <form action="new_purchase_invoice_line.php" method="post" enctype="multipart/form-data">
                <input type="hidden" id="txt_shall_expand_toUpdate" value="<?php echo (isset($_SESSION['table_to_update'])) ? trim($_SESSION['table_to_update']) : '' ?>" />
                <!--this field  (shall_expand_toUpdate)above is for letting the form expand using js for updating-->
                <input type="hidden" id="txt_item_id"   name="txt_item_id"/><input type="hidden" id="txt_measurement_id"   name="txt_measurement_id"/>
                <input type="hidden" id="txt_pur_invoice_header_id"   name="txt_pur_invoice_header_id"/>
                <input type="hidden" id="txt_pur_order_line_id"   name="txt_pur_order_line_id"/>
                <input type="hidden" id="txt_inventory_control_journal_id"   name="txt_inventory_control_journal_id"/>
                <input type="hidden" id="txt_purchase_order_id"   name="txt_purchase_order_id"/>
                <input type="hidden" id="txt_measurement_id"   name="txt_measurement_id"/>
                <input type="hidden" id="txt_pur_order_line_id"   name="txt_pur_order_line_id"/>
                <input type="hidden" id="txt_inventory_control_journal_id"   name="txt_inventory_control_journal_id"/>
                <input type="hidden" id="txt_activity_id"   name="txt_activity_id"/>
                <input type="hidden" id="txt_account_id"   name="txt_account_id"/>
                <input type="hidden"   id="txt_supplier_id"   name="txt_supplier_id"/>
                <input type="hidden"  id="txt_p_type_project_id"    name="txt_type_project_id"/>
                <table class="new_data_table  rehide">


                    <tr class="off">
                        <td>Select Year</td>
                        <td><?php get_fisc_year_combo(); ?></td>
                    </tr>
                    <tr><td class="new_data_tb_frst_cols">Budget line </td><td> <?php get_type_project_combo(); ?>  </td></tr> 
                    <tr>
                        <td>Project</td><td>
                            <select class="textbox cbo_fill_projects cbo_onfly_p_project_change">
                                <option> --Projects--</option>
                            </select>  
                        </td>
                    </tr>
                    <tr>
                        <td>Select the Activity</td>
                        <td> <select class="textbox tobe_refilled cbo_fill_activity  cbo_onfly_p_activity_change">
                                <option> --Add new --</option>
                            </select>  
                        </td>
                    </tr>
                    <tr><td class="new_data_tb_frst_cols">Supplier </td><td> <?php get_supplier_combo(); ?>  </td></tr>
                    <tr><td class="new_data_tb_frst_cols">Account </td><td>
                            <table class="margin_free">
                                <tr><td>Assets (Debit)</td><td>Bank/Cash at hand (Credit)</td></tr>
                                <tr><td> <?php get_expenses_combo(); ?></td><td><?php get_asset_combo(); ?> </td></tr>
                            </table>
                            <br/>
                        </td></tr>
                    <tr><td class="new_data_tb_frst_cols"><label for="chk_tax_inc">Is tax inclusive</label> </td><td> <input type="checkbox"id="chk_tax_inc" name="chk_tax_inc" />  </td></tr>
                    <tr>
                        <td>Payment method</td><td> 
                            <input type="radio" name="rd_bank_cash" class="push_left" id="rd_bank"><label for="rd_bank" class="push_left">Bank </label>
                            <input type="radio" name="rd_bank_cash" class="push_left" id="rd_cash"><label for="rd_cash" class="push_left">Cash </label>
                        </td>
                    </tr>
                    <tr><td class="new_data_tb_frst_cols">Purchase Order </td><td> <?php get_purchase_order_line(); ?>  </td></tr>

                    <tr>
                        <td colspan="2">
                            <span class="parts no_paddin_shade_no_Border load_res off"></span>
                            <div class="parts continuous_res no_paddin_shade_no_Border ">

                            </div>
                        </td>
                    </tr>
                    <tr><td colspan="2">
                            <input type="submit" class="confirm_buttons" name="send_purchase_invoice_line" value="Save"/> 
                            <button class="back_wiz push_right">Back</button>
                        </td></tr>
                </table>
            </form> 
        </div>

        <div class="parts eighty_centered datalist_box" >
            <div class="parts no_shade_noBorder xx_titles no_bg whilte_text">purchase invoice  List</div>
            <?php
                // <editor-fold defaultstate="collapsed" desc="--paging init---">
                if (isset($_POST['prev'])) {
                    $_SESSION['page'] = ($_SESSION['page'] < 1) ? 1 : ($_SESSION['page'] -= 1);
                    $last = ($_SESSION['page'] > 1) ? $_SESSION['page'] * 30 : 30;
                    $first = $last - 30;
                } else if (isset($_POST['page_level'])) {//this is next
                    $_SESSION['page'] = ($_SESSION['page'] < 1) ? 1 : ($_SESSION['page'] += 1);
                    $last = $_SESSION['page'] * 30;
                    $first = $last - 30;
                } else if (isset($_SESSION['page']) && isset($_POST['paginated']) && $_SESSION['page'] > 0) {// the use is on another page(other than the first) and clicked the page inside
                    $last = $_POST['paginated'] * 30;
                    $first = $last - 30;
                } else if (isset($_POST['paginated']) && $_SESSION['page'] = 0) {
                    $first = 0;
                } else if (isset($_POST['paginated'])) {
                    $last = $_POST['paginated'] * 30;
                    $first = $last - 30;
                } else if (isset($_POST['first'])) {
                    $_SESSION['page'] = 1;
                    $first = 0;
                } else {
                    $first = 0;
                }

// </editor-fold>
                $obj = new multi_values();
                $other = new other_fx();
                $first = $obj->get_first_purchase_invoice_line();
                //start this year
                $start_year = date('Y');
                $start_month = '0' . 1;
                $start_date = '0' . 1;

                //End this year
                $end_year = date('Y');
                $end_month = date('m');
                $end_date = date('d');
//                echo $start_year . '-' . $start_month . '-' . $start_date;
                echo $other->get_this_year_start_date() . ' - ' . $other->get_this_year_end_date();
                $obj->list_purchase_invoice_line($first);
            ?>
        </div> 
        <div class="parts eighty_centered  no_paddin_shade_no_Border no_shade_noBorder check_loaded" >
            <?php require_once './navigation/add_nav.php'; ?> 
        </div>
        <script src="../web_scripts/jquery-2.1.3.min.js" type="text/javascript"></script>
        <script src="admin_script.js" type="text/javascript"></script>  
        <script src="../web_scripts/web_scripts.js" type="text/javascript"></script>

        <div class="parts full_center_two_h heit_free footer"> Copyrights <?php echo '2018 - ' . date("Y") . ' MUHABURA MULTICHOICE COMPANY LTD Version 1.0' ?></div>
    </body>
</hmtl>
<div class="parts  eighty_centered no_paddin_shade_no_Border no_bg margin_free export_btn_box">
    <table class="margin_free">
        <td>
            <form action="../web_exports/excel_export.php" method="post">
                <input type="hidden" name="purchase_invoice_line" value="a"/>
                <input type="submit" name="export" class="btn_export btn_export_excel" value="Export"/>
            </form>
        </td>
        <td>
            <form action="../prints/print_purchase_invoice_line.php"target="blank" method="post">
                <input type="submit" name="export" class="btn_export btn_export_pdf" value="Export"/>
            </form>
        </td>
    </table>
</div>

<?php

    function get_supplier_combo() {
        $obj = new multi_values();
        $obj->get_supplier_in_combo();
    }

    function get_expenses_combo() {
        $obj = new multi_values();
        $obj->get_expenses_sml_cbo();
    }

    function get_asset_combo() {
        $obj = new multi_values();
        $obj->get_asset_sml_cbo();
    }

    function get_purchase_order_line() {
        require_once '../web_db/other_fx.php';
        $other = new other_fx();
        $other->get_purchase_order_in_combo();
    }

    function get_type_project_combo() {
        $obj = new multi_values();
        $obj->get_type_project_in_combo();
    }

    function get_fisc_year_combo() {
        $obj = new multi_values();
        $obj->get_fisc_year_in_combo();
    }

    function get_project_combo() {
        $obj = new multi_values();
        $obj->get_project_in_combo_refill();
    }

    function get_item_combo() {
        $obj = new multi_values();
        $obj->get_item_in_combo();
    }

    function get_measurement_combo() {
        $obj = new multi_values();
        $obj->get_measurement_in_combo();
    }

    function get_pur_invoice_header_combo() {
        $obj = new multi_values();
        $obj->get_pur_invoice_header_in_combo();
    }

    function get_pur_order_line_combo() {
        $obj = new multi_values();
        $obj->get_pur_order_line_in_combo();
    }

    function get_inventory_control_journal_combo() {
        $obj = new multi_values();
        $obj->get_inventory_control_journal_in_combo();
    }

    function chosen_item_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_invoice_line') {
                $id = $_SESSION['id_upd'];
                $item = new multi_values();
                return $item->get_chosen_purchase_invoice_line_item($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_measurement_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_invoice_line') {
                $id = $_SESSION['id_upd'];
                $measurement = new multi_values();
                return $measurement->get_chosen_purchase_invoice_line_measurement($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_pur_invoice_header_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_invoice_line') {
                $id = $_SESSION['id_upd'];
                $pur_invoice_header = new multi_values();
                return $pur_invoice_header->get_chosen_purchase_invoice_line_pur_invoice_header($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_cost_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_invoice_line') {
                $id = $_SESSION['id_upd'];
                $cost = new multi_values();
                return $cost->get_chosen_purchase_invoice_line_cost($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_discount_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_invoice_line') {
                $id = $_SESSION['id_upd'];
                $discount = new multi_values();
                return $discount->get_chosen_purchase_invoice_line_discount($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_quantity_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_invoice_line') {
                $id = $_SESSION['id_upd'];
                $quantity = new multi_values();
                return $quantity->get_chosen_purchase_invoice_line_quantity($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_unit_cost_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_invoice_line') {
                $id = $_SESSION['id_upd'];
                $unit_cost = new multi_values();
                return $unit_cost->get_chosen_purchase_invoice_line_unit_cost($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_amount_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_invoice_line') {
                $id = $_SESSION['id_upd'];
                $amount = new multi_values();
                return $amount->get_chosen_purchase_invoice_line_amount($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_pur_order_line_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_invoice_line') {
                $id = $_SESSION['id_upd'];
                $pur_order_line = new multi_values();
                return $pur_order_line->get_chosen_purchase_invoice_line_pur_order_line($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_inventory_control_journal_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_invoice_line') {
                $id = $_SESSION['id_upd'];
                $inventory_control_journal = new multi_values();
                return $inventory_control_journal->get_chosen_purchase_invoice_line_inventory_control_journal($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_received_quantity_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_invoice_line') {
                $id = $_SESSION['id_upd'];
                $received_quantity = new multi_values();
                return $received_quantity->get_chosen_purchase_invoice_line_received_quantity($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }
    