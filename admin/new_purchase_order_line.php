<?php
    session_start();
    require_once '../web_db/multi_values.php';
    if (!isset($_SESSION)) {
        session_start();
    }if (!isset($_SESSION['login_token'])) {
        header('location:../index.php');
    }
    if (isset($_POST['send_po'])) {
        if (isset($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_order_line') {
                require_once '../web_db/updates.php';
                $upd_obj = new updates();
                $purchase_order_line_id = $_SESSION['id_upd'];
                $entry_date = date("y-m-d");
                $User = $_SESSION['userid'];
                $quantity = $_POST['txt_quantity'];
                $unit_cost = $_POST['txt_unit_cost'];
                $amount = $_POST['txt_amount'];
                $request = $_POST['txt_request_id'];
                $measurement = $_POST['txt_measurement_id'];
                $supplier = $_POST['txt_client_id'];
                $upd_obj->update_purchase_order_line($entry_date, $User, $quantity, $unit_cost, $amount, $request, $measurement, $supplier, $purchase_order_line_id);
                unset($_SESSION['table_to_update']);
            }
        } else {
            require_once '../web_db/new_values.php';
            $quantity = $_POST['txt_quantity'];
            $unit_c = $_POST['txt_unit_cost'];
            $items = $_POST['txt_itemid'];
            $supplier = $_POST['suppliers_cbo'];
            $requestid = $_POST['txt_requestid'];

            $index_item = new ArrayIterator($items);
            $index_quantity = new ArrayIterator($quantity);
            $index_unit_c = new ArrayIterator($unit_c);
            $index_supplier = new ArrayIterator($supplier);
            $index_requestid = new ArrayIterator($requestid);

            $mit = new MultipleIterator(MultipleIterator::MIT_KEYS_ASSOC);
            $mit->attachIterator($index_item, "Item");
//            $mit->attachIterator($index_measurement, "measurement");
            $mit->attachIterator($index_quantity, "quantity");
            $mit->attachIterator($index_unit_c, "unit_c");
            $mit->attachIterator($index_supplier, "suplier");
            $mit->attachIterator($index_requestid, "requestid");

            $entry_date = date('y-m-d h:m:s');
            $User = $_SESSION['userid'];
            $obj = new new_values();
            foreach ($mit as $fruit) {
                $dc = json_decode(json_encode($fruit), true);
                foreach ($dc as $val) {
                    if (!empty($dc['Item'])) {
                        $item = $dc['Item'];
                        $quantity = (!empty($dc['quantity'])) ? $dc['quantity'] : 0;
                        $unit_c = (!empty($dc['unit_c'])) ? $dc['unit_c'] : 0;
                        $amount = $unit_c * $quantity;
                        $requestid = $dc['requestid'];
                        $supplier = $dc['suplier'];
                        $obj->new_purchase_order_line($entry_date, $User, filter_var($quantity, FILTER_SANITIZE_NUMBER_INT), filter_var($unit_c, FILTER_SANITIZE_NUMBER_INT), filter_var($amount, FILTER_SANITIZE_NUMBER_INT), $requestid, 0, $supplier);
                        break;
                    }
                }
            }
            ?><script>alert('The purchase order Saved successfully');</script><?php
//            $obj = new new_values();
//            $obj->new_purchase_order_line($entry_date, $User, $quantity, filter_var($unit_cost, FILTER_SANITIZE_NUMBER_INT), filter_var($amount, FILTER_SANITIZE_NUMBER_INT), $request, $measurement, $supplier);
        }
    }
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>
            Purchase Order</title>
        <link href="web_style/styles.css" rel="stylesheet" type="text/css"/>
        <link href="web_style/StylesAddon.css" rel="stylesheet" type="text/css"/>
        <link href="admin_style.css" rel="stylesheet" type="text/css"/> 
        <meta name="viewport" content="width=device-width, initial scale=1.0"/>
        <link rel="shortcut icon" href="../web_images/tab_icon.png" type="image/x-icon"> 
    </head>
    <body>
        <?php
            include 'admin_header.php';
        ?>

        <!--Start of Purcahse order Details-->
        <div class="parts p_project_type_details data_details_pane abs_full margin_free white_bg">
            <div class="parts fifty_centered no_paddin_shade_no_Border heit_free add_load_gif">.</div> 
            <div class="parts no_paddin_shade_no_Border data_res full_center_two_h heit_free">

            </div>
        </div>

        <!--End Purchase Details-->   
        <!--Start of opening Type project Pane(The pane that allow the user to add the data in a different table without leaving the current one)-->
        <div class="parts abs_full eighty_centered onfly_pane_p_type_project">
            <div class="parts  full_center_two_h heit_free">
                <div class="parts pane_opening_balance  full_center_two_h heit_free no_shade_noBorder">
                    <table class="new_data_table" >
                        <thead>Add new Budget Line</thead>
                        <tr>
                            <td>Name</td> <td>
                                <input type="text"  class="textbox"  id="onfly_txt_name" />  </td>
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
            <div class="parts  no_paddin_shade_no_Border new_data_hider"> Add Purchase Order </div>
            <div class="parts no_paddin_shade_no_Border page_search link_cursor">Search</div>
        </div>
        <div class="parts eighty_centered off saved_dialog">
            purchase_order_line saved successfully!</div>
        <div class="parts eighty_centered new_data_box off">
            <div class="parts eighty_centered off">  purchase order  </div>
            <?php ?>
            <form action="new_purchase_order_line.php" method="post" enctype="multipart/form-data">
                <input type="hidden" style="float: right;" id="txt_shall_expand_toUpdate" value="<?php echo (isset($_SESSION['table_to_update'])) ? trim($_SESSION['table_to_update']) : '' ?>" />
                <!--this field  (shall_expand_toUpdate)above is for letting the form expand using js for updating-->
                <input type="hidden" style="float: right" id="txt_measurement_id"   name="txt_measurement_id"/>
                <input type="hidden" id="txt_pur_order_header_id"   name="txt_pur_order_header_id"/>
                <input type="hidden" id="txt_item_id"   name="txt_item_id"/>
                <input type="hidden" class="push_right"  id="txt_request_id"   name="txt_request_id"/>
                <input type="hidden" id="txt_supplier_id"   name="txt_supplier_id"/>
                <input type="hidden" id="txt_client_id"   name="txt_client_id"/>
                <input type="hidden" id="txt_cbo_main_request_id"   name="txt_cbo_main_request_id"/>
                <input type="hidden"  id="txt_p_type_project_id"    name="txt_type_project_id"/>

                <table class="new_data_table off new_po_hidable rehide">
                    <tr><td class="new_data_tb_frst_cols">Request </td><td> <?php get_request_combo(); ?>  </td></tr>
                    <tr>
                        <td colspan="2">
                            <span class="req_res"> </span>
                        </td>
                    </tr>

                </table>  
            </form> 
            <table class="new_data_table selectable_table ">
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
                <tr>
                    <td> </td> <td>
                        <div class="parts confirm_buttons  new_select_po link_cursor">Select</div>
                        <input type="submit" class="confirm_buttons select_activity_purchase_order_line off" id="selct_f_year" value="Select" /> </td>
                </tr>
            </table>
        </div>
        <div class="parts eighty_centered datalist_box" >
            <div class="parts no_shade_noBorder xx_titles no_bg whilte_text">All purchase orders </div>
            <?php
                    $menu = new extra_sub_menus();
                    $menu->purchase_order_line_smenu();
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
                $first = $obj->get_first_purchase_order_line();
                $obj->list_purchase_order_line();
            ?>
        </div> 

        <div class="parts no_paddin_shade_no_Border eighty_centered no_bg">
            <table>
                <td>
                    <form action="../web_exports/excel_export.php" method="post">
                        <input type="hidden" name="purchase_order_line" value="a"/>
                        <input type="submit" name="export" class="btn_export btn_export_excel" value="Export"/>
                    </form>
                </td>
                <td>
                    <form action="../prints/print_purchase_order_line.php"target="blank" method="post">
                        <input type="submit" name="export" class="btn_export btn_export_pdf" value="Export"/>
                    </form>
                </td>
            </table>
        </div>
        <div class="parts eighty_centered  no_paddin_shade_no_Border no_shade_noBorder check_loaded" >
            <?php require_once './navigation/add_nav.php'; ?> 
        </div>

        <script src="../web_scripts/jquery-2.1.3.min.js" type="text/javascript"></script>
        <script src="admin_script.js" type="text/javascript"></script> 
        <script src="../web_scripts/web_scripts.js" type="text/javascript"></script>
        <script>
                $(document).ready(function () {
                    $('.new_select_po').click(function () {//this is hiding the first pane, afterwards it show the next pane
                        $('.selectable_table').hide(200, function () {
                            $('.new_po_hidable').slideDown(200);
                        });
                    });
                });

        </script>
        <div class="parts full_center_two_h heit_free footer"> Copyrights <?php echo '2018 - ' . date("Y") . ' MUHABURA MULTICHOICE COMPANY LTD Version 1.0' ?></div>
    </body>
</hmtl>
<?php

    function get_supplier_combo() {
        $obj = new multi_values();
        $obj->get_supplier_in_combo();
    }

    function get_fisc_year_combo() {
        $obj = new multi_values();
        $obj->get_fisc_year_in_combo();
    }

    function get_type_project_combo() {
        $obj = new multi_values();
        $obj->get_type_project_in_combo();
    }

    function get_request_combo() {
        $obj = new multi_values();
        $obj->get_main_req_in_combo();
    }

    function get_project_combo() {
        $obj = new multi_values();
        $obj->get_project_in_combo_refill();
    }

    function get_pur_order_header_combo() {
        $obj = new multi_values();
        $obj->get_pur_order_header_in_combo();
    }

    function get_item_combo() {
        $obj = new multi_values();
        $obj->get_item_in_combo();
    }

    function get_measurement_combo() {
        $obj = new multi_values();
        $obj->get_measurement_in_combo();
    }

    function chosen_pur_order_header_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_order_line') {
                $id = $_SESSION['id_upd'];
                $pur_order_header = new multi_values();
                return $pur_order_header->get_chosen_purchase_order_line_pur_order_header($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_item_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_order_line') {
                $id = $_SESSION['id_upd'];
                $item = new multi_values();
                return $item->get_chosen_purchase_order_line_item($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_measurement_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_order_line') {
                $id = $_SESSION['id_upd'];
                $measurement = new multi_values();
                return $measurement->get_chosen_purchase_order_line_measurement($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_quanitity_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_order_line') {
                $id = $_SESSION['id_upd'];
                $quanitity = new multi_values();
                return $quanitity->get_chosen_purchase_order_line_quanitity($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_cost_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_order_line') {
                $id = $_SESSION['id_upd'];
                $cost = new multi_values();
                return $cost->get_chosen_purchase_order_line_cost($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_discount_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_order_line') {
                $id = $_SESSION['id_upd'];
                $discount = new multi_values();
                return $discount->get_chosen_purchase_order_line_discount($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_quantity_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_order_line') {
                $id = $_SESSION['id_upd'];
                $quantity = new multi_values();
                return $quantity->get_chosen_purchase_order_line_quantity($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_unit_cost_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'purchase_order_line') {
                $id = $_SESSION['id_upd'];
                $unit_cost = new multi_values();
                return $unit_cost->get_chosen_purchase_order_line_unit_cost($id);
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
    