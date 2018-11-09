<?php
    session_start();
    require_once '../web_db/multi_values.php';
    if (!isset($_SESSION)) {
        session_start();
    }if (!isset($_SESSION['login_token'])) {
        header('location:../index.php');
    }
    if (isset($_POST['send_purchase_receit_line'])) {
        if (isset($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_receit_line') {
                require_once '../web_db/updates.php';
                $upd_obj = new updates();
                $purchase_receit_line_id = $_SESSION['id_upd'];
                $amount = $_POST['txt_amount'];
                $purchase_invoice = $_POST['txt_purchase_invoice_id'];
                $entry_date = date("y-m-d h:m:s");
                $User = $_SESSION['userid'];
                $upd_obj->update_purchase_receit_line($amount, $purchase_invoice, $entry_date, $User, $purchase_receit_line_id);
                unset($_SESSION['table_to_update']);
            }
        } else {
            $entry_date = date("y-m-d h:m:s");
            $User = $_SESSION['userid'];
            $quantity = $_POST['txt_quantity'];
            $unit_cost = filter_var($_POST['txt_unit_cost'], FILTER_SANITIZE_NUMBER_INT);
            $purchase_invoice = trim($_POST['txt_purchase_invoice_id']);
            $activity = $_POST['txt_activity_id'];
            $account = $_POST['txt_account_id'];
            $suplier = $_POST['txt_supplier_id'];
            $tot = $unit_cost * $quantity;
            require_once '../web_db/new_values.php';
            $obj = new new_values();
            $obj->new_purchase_receit_line($entry_date, $User, $quantity, $unit_cost, $tot, $purchase_invoice, $activity, $account, $suplier);
            require_once '../web_db/other_fx.php';
            $m = new other_fx();

            $item = $m->get_item_by_pur_invoice($purchase_invoice);
            $obj->new_main_stock($item, $quantity, $quantity, 'in', 0, $entry_date, $User, $unit_cost, $tot);
        }
    }
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>
            purchase_receit_line</title>
        <link href="web_style/styles.css" rel="stylesheet" type="text/css"/>
        <link href="web_style/StylesAddon.css" rel="stylesheet" type="text/css"/>
        <link href="admin_style.css" rel="stylesheet" type="text/css"/> 
        <meta name="viewport" content="width=device-width, initial scale=1.0"/>
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
                            <td>Name</td> <td><input type="text" required class="textbox"  id="onfly_txt_name" />  </td>
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
        <div class="parts eighty_centered no_paddin_shade_no_Border">  
            <div class="parts  no_paddin_shade_no_Border new_data_hider"> Add Purchase Receipt </div>
            <div class="parts no_paddin_shade_no_Border page_search link_cursor">Search</div>
        </div>
        <div class="parts eighty_centered off saved_dialog">
            purchase_receit_line saved successfully!
        </div>
        <div class="parts eighty_centered new_data_box off">
            <div class="parts eighty_centered ">  purchase receipt  </div>
            <table class="new_data_table selectable_table">
                <input type="hidden" name="txt_project_id" id="onfly_txt_project_id" />
                <tr class="off">
                    <td>Select Year</td>
                    <td><?php get_fisc_year_combo(); ?></td>
                </tr>
                <tr><td class="new_data_tb_frst_cols">Budget line </td><td> <?php get_type_project_combo(); ?>  </td></tr>   <tr>
                    <td>project</td><td>
                        <select class="textbox cbo_fill_projects cbo_project cbo_onfly_p_project_change">
                            <option> --Activities--</option>
                        </select>  
                    </td>
                </tr>
                <tr>
                    <td> Select the Activity</td>
                    <td> <select class="textbox tobe_refilled cbo_fill_activity cbo_activity  cbo_onfly_p_activity_change">
                            <option> --Activities--</option>
                        </select>  
                    </td>
                </tr>
                <tr>
                    <td> </td> <td><input type="submit" class="confirm_buttons" id="selct_f_year" value="Select" /> </td>
                </tr>
            </table>
            <form action="new_purchase_receit_line.php" method="post" enctype="multipart/form-data">
                <input type="hidden" id="txt_shall_expand_toUpdate" value="<?php echo (isset($_SESSION['table_to_update'])) ? trim($_SESSION['table_to_update']) : '' ?>" />
                <!--this field  (shall_expand_toUpdate)above is for letting the form expand using js for updating-->
                <input type="hidden" id="txt_pur_recceit_header_id"   name="txt_pur_recceit_header_id"/>
                <input type="hidden" id="txt_item_id"   name="txt_item_id"/>
                <input type="hidden" id="txt_Inventory_control_Journal_id"   name="txt_Inventory_control_Journal_id"/>
                <input type="hidden" id="txt_measurement_id"   name="txt_measurement_id"/>
                <input type="hidden" id="txt_purchase_invoice_id"   name="txt_purchase_invoice_id"/>
                <input type="hidden" id="txt_account_id"   name="txt_account_id"/> 
                <input type="hidden" id="txt_activity_id"   name="txt_activity_id"/>
                <input type="hidden"   id="txt_supplier_id"   name="txt_supplier_id"/>
                <table class="new_data_table off rehide">
                    <tr><td><label for="txt_suplier">Supplier </label></td><td> <?php get_supplier_combo(); ?>  </td></tr>
                    <tr><td><label for="txt_account">Account </label></td><td> <?php get_account_combo(); ?> </td></tr>
                    <tr><td class="new_data_tb_frst_cols">Purchase_invoicer </td><td> <?php get_purchase_invoice_combo(); ?>  </td></tr>
                    <tr>
                        <td colspan="2">
                            <span class="parts no_paddin_shade_no_Border load_res off">..</span>
                            <div class="parts continuous_res no_paddin_shade_no_Border ">

                            </div>
                        </td>
                    </tr>


                    <tr><td colspan="2">
                            <input type="submit" class="confirm_buttons" name="send_purchase_receit_line" value="Save"/>
                            <button class="back_wiz push_right">Back</button>
                        </td></tr>
                </table>  
            </form>
        </div>
        <div class="parts eighty_centered datalist_box" >
            <div class="parts no_shade_noBorder xx_titles no_bg whilte_text">List of purchase receipts  </div>
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
                // $obj = new multi_values();
                // $first = $obj->get_first_purchase_receit_line();
                // $obj->list_purchase_receit_line();
                require_once '../web_db/multi_values.php';
                require_once '../web_db/other_fx.php';
                $obj = new multi_values();
                $other = new other_fx();
                $min_date = $other->get_this_year_start_date();
                $max_date = $other->get_this_year_end_date();
                echo $other->get_account_details($min_date, $max_date, 'Accounts Receivable');
            ?>
        </div>  
        <div class="parts no_paddin_shade_no_Border eighty_centered no_bg">
            <table>
                <td>
                    <form action="../web_exports/excel_export.php" method="post">
                        <input type="hidden" name="purchase_receit_line" value="a"/>
                        <input type="submit" name="export" class="btn_export btn_export_excel" value="Export"/>
                    </form>
                </td>
                <td>
                    <form action="../prints/print_purchase_receit_line.php"target="blank" method="post">
                        <input type="submit" name="export" class="btn_export btn_export_pdf" value="Export"/>
                    </form>
                </td></table>
        </div>
        <div class="parts eighty_centered  no_paddin_shade_no_Border no_shade_noBorder check_loaded" >
            <?php require_once './navigation/add_nav.php'; ?> 
        </div> 
        <div class="parts full_center_two_h heit_free footer"> Copyrights <?php echo '2018 - ' . date("Y") . ' MUHABURA MULTICHOICE COMPANY LTD Version 1.0' ?></div>
        <script src="../web_scripts/jquery-2.1.3.min.js" type="text/javascript"></script>
        <script src="admin_script.js" type="text/javascript"></script>  
        <script src="../web_scripts/web_scripts.js" type="text/javascript"></script>
    </body>
</hmtl>
<?php

    function get_supplier_combo() {
        $obj = new multi_values();
        $obj->get_supplier_in_combo();
    }

    function get_account_combo() {
        $obj = new multi_values();
        $obj->get_account_in_combo_enabled_payable();
    }

    function get_fisc_year_combo() {
        $obj = new multi_values();
        $obj->get_fisc_year_in_combo();
    }

    function get_pur_recceit_header_combo() {
        $obj = new multi_values();
        $obj->get_pur_recceit_header_in_combo();
    }

    function get_type_project_combo() {
        $obj = new multi_values();
        $obj->get_type_project_in_combo();
    }

    function get_item_combo() {
        $obj = new multi_values();
        $obj->get_item_in_combo();
    }

    function get_Inventory_control_Journal_combo() {
        $obj = new multi_values();
        $obj->get_Inventory_control_Journal_in_combo();
    }

    function get_measurement_combo() {
        $obj = new multi_values();
        $obj->get_measurement_in_combo();
    }

    function chosen_pur_recceit_header_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_receit_line') {
                $id = $_SESSION['id_upd'];
                $pur_recceit_header = new multi_values();
                return $pur_recceit_header->get_chosen_purchase_receit_line_pur_recceit_header($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_item_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_receit_line') {
                $id = $_SESSION['id_upd'];
                $item = new multi_values();
                return $item->get_chosen_purchase_receit_line_item($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_Inventory_control_Journal_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_receit_line') {
                $id = $_SESSION['id_upd'];
                $Inventory_control_Journal = new multi_values();
                return $Inventory_control_Journal->get_chosen_purchase_receit_line_Inventory_control_Journal($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_measurement_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_receit_line') {
                $id = $_SESSION['id_upd'];
                $measurement = new multi_values();
                return $measurement->get_chosen_purchase_receit_line_measurement($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_received_qty_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_receit_line') {
                $id = $_SESSION['id_upd'];
                $received_qty = new multi_values();
                return $received_qty->get_chosen_purchase_receit_line_received_qty($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_cost_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_receit_line') {
                $id = $_SESSION['id_upd'];
                $cost = new multi_values();
                return $cost->get_chosen_purchase_receit_line_cost($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_amount_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_order_line') {
                $id = $_SESSION['id_upd'];
                $amount = new multi_values();
                return $amount->get_chosen_purchase_order_line_amount($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    // <editor-fold defaultstate="collapsed" desc="-- new purchase receiprt with all fields">

    function get_purchase_invoice_combo() {
        $obj = new multi_values();
        $obj->get_purchase_invoice_in_combo();
    }

    function chosen_entry_date_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_receit_line') {
                $id = $_SESSION['id_upd'];
                $entry_date = new multi_values();
                return $entry_date->get_chosen_purchase_receit_line_entry_date($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_User_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_receit_line') {
                $id = $_SESSION['id_upd'];
                $User = new multi_values();
                return $User->get_chosen_purchase_receit_line_User($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_quantity_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_receit_line') {
                $id = $_SESSION['id_upd'];
                $quantity = new multi_values();
                return $quantity->get_chosen_purchase_receit_line_quantity($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_unit_cost_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_receit_line') {
                $id = $_SESSION['id_upd'];
                $unit_cost = new multi_values();
                return $unit_cost->get_chosen_purchase_receit_line_unit_cost($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_purchase_invoice_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_receit_line') {
                $id = $_SESSION['id_upd'];
                $purchase_invoice = new multi_values();
                return $purchase_invoice->get_chosen_purchase_receit_line_purchase_invoice($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_activity_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_receit_line') {
                $id = $_SESSION['id_upd'];
                $activity = new multi_values();
                return $activity->get_chosen_purchase_receit_line_activity($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_account_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_receit_line') {
                $id = $_SESSION['id_upd'];
                $account = new multi_values();
                return $account->get_chosen_purchase_receit_line_account($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_suplier_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_receit_line') {
                $id = $_SESSION['id_upd'];
                $suplier = new multi_values();
                return $suplier->get_chosen_purchase_receit_line_suplier($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

// </editor-fold>
