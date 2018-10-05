<?php
    session_start();
    require_once '../web_db/multi_values.php';
    if (!isset($_SESSION)) {
        session_start();
    }if (!isset($_SESSION['login_token'])) {
        header('location:../index.php');
    }

// <editor-fold defaultstate="collapsed" desc="--save journal in db ---">
    function save_request() {
        if (isset($_POST['send_p_request'])) {
            require_once '../web_db/new_values.php';
            //get sums
            $items = $_POST['acc_item_combo'];
            $measurement = $_POST['measurement_array'];
            $quantity = $_POST['txt_qty'];
            $unit_c = $_POST['txt_unit_c'];

            $index_item = new ArrayIterator($items);
            $index_measurement = new ArrayIterator($measurement);
            $index_quantity = new ArrayIterator($quantity);
            $index_unit_c = new ArrayIterator($unit_c);
            $mit = new MultipleIterator(MultipleIterator::MIT_KEYS_ASSOC);

            $mit->attachIterator($index_item, "Item");
            $mit->attachIterator($index_measurement, "measurement");
            $mit->attachIterator($index_quantity, "quantity");
            $mit->attachIterator($index_unit_c, "unit_c");
            $entry_date = date('Y-m-d h:m:s');
            $user = $_SESSION['userid'];
            $field = filter_input(INPUT_POST, 'cbo_field');

            $obj = new new_values();
            $obj->new_Main_Request($entry_date, $user);
            $m = new multi_values();
            $last_req = $m->get_last_Main_Request();
            foreach ($mit as $fruit) {
                $dc = json_decode(json_encode($fruit), true);
                foreach ($dc as $val) {
                    if (!empty($dc['Item'])) {
                        $newobj = new new_values();
                        $item = $dc['Item'];
                        $quantity = (!empty($dc['quantity'])) ? $dc['quantity'] : 0;
                        $unit_c = (!empty($dc['unit_c'])) ? $dc['unit_c'] : 0;
                        $amount = $unit_c * $quantity;
                        $request_no = '';
                        $status = 0;
                        $DAF ="";
                        $DG  ="";
                        $newobj->new_p_request($item, filter_var($quantity, FILTER_SANITIZE_NUMBER_INT), filter_var($unit_c, FILTER_SANITIZE_NUMBER_INT), filter_var($amount, FILTER_SANITIZE_NUMBER_INT), $entry_date, $user, $dc['measurement'], $request_no, $last_req, $field,$status,$DAF,$DG);
//                        $m = new multi_values();
//                        $last_header = $m->get_last_journal_entry_header();
//                        $newobj->new_journal_entry_line($dc['Account'], $switch_dc, $amount, $dc['memo'], $last_header);
                        break;
                    }
                }
            }
        }
    }

// </editor-fold>
?>

<html>
    <head>
        <meta charset="UTF-8">
        <title>
            p_budget
        </title>
        <link href="web_style/styles.css" rel="stylesheet" type="text/css"/>
        <link href="web_style/StylesAddon.css" rel="stylesheet" type="text/css"/>
        <link href="admin_style.css" rel="stylesheet" type="text/css"/>
        <link href="admin_style.css" rel="stylesheet" type="text/css"/> 
        <meta name="viewport" content="width=device-width, initial scale=1.0"/>
        <style>
            .journal_entry_table thead{
                background-color: #234094;
            }
            .journal_entry_table .textbox{
                background-color: #f0f2f7;
                width: 100%;
                margin: auto;
            }
        </style>
    </head>
    <body>

        <?php include 'admin_header.php'; ?>
        <!--Start of Type Details-->
        <div class="parts p_project_type_details data_details_pane abs_full margin_free white_bg">

        </div>
        <!--End Tuype Details-->   
        <!--Start dialog's-->
        <div class="parts abs_full y_n_dialog off">
            <div class="parts dialog_yes_no no_paddin_shade_no_Border reverse_border">
                <div class="parts full_center_two_h heit_free margin_free skin">
                    Do you really want to delete this record?
                </div>
                <div class="parts full_center_two_h heit_free no_paddin_shade_no_Border top_off_x margin_free">
                    <div class="parts yes_no_btns no_shade_noBorder reverse_border left_off_xx link_cursor yes_dlg_btn" id="citizen_yes_btn">Yes</div>
                    <div class="parts yes_no_btns no_shade_noBorder reverse_border left_off_seventy link_cursor no_btn" id="no_btn">No</div>
                </div>
            </div>
        </div>   
        <!--End dialog-->

        <div class="parts eighty_centered no_paddin_shade_no_Border hider_box">  
            <div class="parts  no_paddin_shade_no_Border new_data_hider"> Add Purchase Request </div>
            <div class="parts no_paddin_shade_no_Border page_search link_cursor">Search</div>
        </div>
        <div class="parts eighty_centered off saved_dialog">
            p_budget saved successfully!
        </div>
        <!--Start of opening measurement Pane(The pane that allow the user to add the data in a different table without leaving the current one)-->
        <div class="parts abs_full eighty_centered onfly_pane_measurement">
            <div class="parts  full_center_two_h heit_free">
                <div class="parts pane_opening_balance  full_center_two_h heit_free no_shade_noBorder">
                    <table class="new_data_table" >
                        <thead>Add new measurement</thead>
                        <tr>     <td>code</td> <td><input type="text" required class="textbox"  id="onfly_txt_code" />  </td>
                        <tr>     <td>description</td> <td><input type="text" required class="textbox"  id="onfly_txt_description" />  </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <input type="button" class="confirm_buttons btn_onfly_save_measurement" name="send_measurement" value="Save & close"/> 
                                <input type="button" class="confirm_buttons reverse_bg_btn reverse_color cancel_btn_onfly" name="send_account" value="Cancel"/> 
                            </td>
                        </tr>
                    </table>
                </div>
            </div>                        
        </div>
        <!--End of opening Pane-->

        <!--Start of opening p_budget_itemsPane(The pane that allow the user to add the data in a different table without leaving the current one)-->
        <div class="parts abs_full eighty_centered onfly_pane_p_budget_items">
            <div class="parts  full_center_two_h heit_free">
                <div class="parts pane_opening_balance  full_center_two_h heit_free no_shade_noBorder">
                    <table class="new_data_table" >
                        <thead>Add new  Items</thead>
                        <tr>     <td>item name</td> <td><input type="text" required class="textbox"  id="onfly_txt_item_name" />  </td>
                        <tr>     <td>Description</td> <td><input type="text" required class="textbox"  id="onfly_txt_description" />  </td>
                        <tr>     <td>Account</td> <td><input type="text" required class="textbox"  id="onfly_txt_chart_account" />  </td>
                        </tr>
                        <tr>
                            <td colspan="2">                                 
                                <input type="button" class="confirm_buttons btn_onfly_save_p_budget_items" name="send_p_budget_items" value="Save & close"/> 
                                <input type="button" class="confirm_buttons reverse_bg_btn reverse_color cancel_btn_onfly" name="send_account" value="Cancel"/> 
                            </td>
                        </tr>
                    </table>
                </div>
            </div>  
        </div>
        <!--End of opening Pane--> 



        <div class="parts eighty_centered new_data_box off">
            <input type="hidden" style="float: right;" id="txt_project_id"   name="txt_project_id"/>
            <!--Below fields get the session values when the user has selected the year and the activity-->
            <input type="hidden" style="float: right;" id="txt_selected_fisc_year_id"   name="txt_f_year_process" value="<?php echo get_fisc_year_process(); ?>"  />
            <input type="hidden" style="float: right;" id="txt_selected_activity_id"   name="txt_f_activity_process" value="<?php echo get_activity_process(); ?>" />

            <table class="new_data_table selectable_table off">
                <tr class="off">
                    <td>Select Year</td>
                    <td><?php get_fisc_year_combo(); ?></td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="parts full_center_two_h heit_free no_shade_noBorder big_title ">
                            Select Budget line <br/>
                            <span class="parts sml_title iconed pane_icon no_shade_noBorder">    This request will be saved along with the provided project</span>
                        </div>
                    </td>
                </tr>
                <tr><td class="new_data_tb_frst_cols">Budget Line </td><td> <?php get_project_combo(); ?>  </td></tr>
                <tr>
                    <td>Select Project</td>
                    <td><select class="textbox">
                            <option></option>
                        </select>
                    </td>
                </tr>

                <tr>
                    <td>Select the Activity</td>
                    <td> <select class="textbox tobe_refilled">
                            <option> --Activities--</option>
                        </select>  
                    </td>
                </tr>
                <tr>
                    <td> </td> <td><input type="submit" class="confirm_buttons" id="selct_f_year" value="Select" /> </td>
                </tr>
            </table>
            <form action="new_p_request.php" method="post" >
                <input type="hidden" id="txt_shall_expand_toUpdate" value="<?php echo (isset($_SESSION['table_to_update'])) ? trim($_SESSION['table_to_update']) : '' ?>" />
                <!--this field  (shall_expand_toUpdate)above is for letting the form expand using js for updating-->
                <input type="hidden" id="txt_account_id"   name="txt_account_id"/>
                <input type="hidden" id="txt_activity_id"   name="txt_activity_id"/>
                <input type="hidden"   id="txt_fisc_year_id"   name="txt_fisc_year_id"/>
                <div class="parts full_center_two_h heit_free no_shade_noBorder">  
                    <div class="parts margin_free x_width_thr_h heit_free no_paddin_shade_no_Border ">
                        <table>
                            <tr>
                                <td>Choose</td>
                                <td> <?php get_field_combo(); ?></td>
                            </tr>
                        </table>
                    </div>
                    <span class="parts full_center_two_h no_paddin_shade_no_Border warn_msg off heit_free">
                        You have to choose the field
                    </span>
                </div>
                <table class="dataList_table journal_entry_table thehidden_part " style="width: 80%;">
                    <thead>
                    <td>Item   </td><td>Measurement</td> <td>Quantity</td>    <td> Unit Cost  </td>   <td> Total amount  </td>  
                    </thead>
                    <tr class="ftd runtime">     <td>  <?php get_items_in_combo(); ?>  </td>   <td><?php get_measurement_combo(); ?></td>    <td>  <input type="text" autocomplete="off" id="txt_qty1"  class="textbox bgt_txt_unitC only_numbers" name="txt_qty[]"/> </td> <td>  <input type="text" autocomplete="off" id="txt_unitc1"  class="textbox item_txt_qty only_numbers" name="txt_unit_c[]"/> </td>  <td>  <input type="text" autocomplete="off" id="txt_amnt1"  class="textbox item_txt_amnt only_numbers" disabled name="txt_amount[]"/> </td> </tr>
                    <tr class="ftd runtime">     <td>  <?php get_items_in_combo(); ?>  </td>   <td><?php get_measurement_combo(); ?></td>    <td>  <input type="text" autocomplete="off" id="txt_qty2"  class="textbox bgt_txt_unitC only_numbers" name="txt_qty[]"/> </td> <td>  <input type="text" autocomplete="off" id="txt_unitc2"  class="textbox item_txt_qty only_numbers" name="txt_unit_c[]"/> </td>  <td>  <input type="text" autocomplete="off" id="txt_amnt2"  class="textbox item_txt_amnt only_numbers" disabled name="txt_amount[]"/> </td> </tr>
                    <tr class="ftd runtime">     <td>  <?php get_items_in_combo(); ?>  </td>   <td><?php get_measurement_combo(); ?></td>    <td>  <input type="text" autocomplete="off" id="txt_qty3"  class="textbox bgt_txt_unitC only_numbers" name="txt_qty[]"/> </td> <td>  <input type="text" autocomplete="off" id="txt_unitc3"  class="textbox item_txt_qty only_numbers" name="txt_unit_c[]"/> </td>  <td>  <input type="text" autocomplete="off" id="txt_amnt3"  class="textbox item_txt_amnt only_numbers" disabled name="txt_amount[]"/> </td> </tr>
                    <tr class="ftd runtime">     <td>  <?php get_items_in_combo(); ?>  </td>   <td><?php get_measurement_combo(); ?></td>    <td>  <input type="text" autocomplete="off" id="txt_qty4"  class="textbox bgt_txt_unitC only_numbers" name="txt_qty[]"/> </td> <td>  <input type="text" autocomplete="off" id="txt_unitc4"  class="textbox item_txt_qty only_numbers" name="txt_unit_c[]"/> </td>  <td>  <input type="text" autocomplete="off" id="txt_amnt4"  class="textbox item_txt_amnt only_numbers" disabled name="txt_amount[]"/> </td> </tr>
                    <tr class="ftd runtime">     <td>  <?php get_items_in_combo(); ?>  </td>   <td><?php get_measurement_combo(); ?></td>    <td>  <input type="text" autocomplete="off" id="txt_qty5"  class="textbox bgt_txt_unitC only_numbers" name="txt_qty[]"/> </td> <td>  <input type="text" autocomplete="off" id="txt_unitc5"  class="textbox item_txt_qty only_numbers" name="txt_unit_c[]"/> </td>  <td>  <input type="text" autocomplete="off" id="txt_amnt5"  class="textbox item_txt_amnt only_numbers" disabled name="txt_amount[]"/> </td> </tr>
                    <tr class="ftd runtime">     <td>  <?php get_items_in_combo(); ?>  </td>   <td><?php get_measurement_combo(); ?></td>    <td>  <input type="text" autocomplete="off" id="txt_qty7"  class="textbox bgt_txt_unitC only_numbers" name="txt_qty[]"/> </td> <td>  <input type="text" autocomplete="off" id="txt_unitc6"  class="textbox item_txt_qty only_numbers" name="txt_unit_c[]"/> </td>  <td>  <input type="text" autocomplete="off" id="txt_amnt6"  class="textbox item_txt_amnt only_numbers" disabled name="txt_amount[]"/> </td> </tr>
                    <tr class="ftd runtime">     <td>  <?php get_items_in_combo(); ?>  </td>   <td><?php get_measurement_combo(); ?></td>    <td>  <input type="text" autocomplete="off" id="txt_qty8"  class="textbox bgt_txt_unitC only_numbers" name="txt_qty[]"/> </td> <td>  <input type="text" autocomplete="off" id="txt_unitc7"  class="textbox item_txt_qty only_numbers" name="txt_unit_c[]"/> </td>  <td>  <input type="text" autocomplete="off" id="txt_amnt7"  class="textbox item_txt_amnt only_numbers" disabled name="txt_amount[]"/> </td> </tr>
                    <tr class="ftd runtime">     <td>  <?php get_items_in_combo(); ?>  </td>   <td><?php get_measurement_combo(); ?></td>    <td>  <input type="text" autocomplete="off" id="txt_qty9"  class="textbox bgt_txt_unitC only_numbers" name="txt_qty[]"/> </td> <td>  <input type="text" autocomplete="off" id="txt_unitc8"  class="textbox item_txt_qty only_numbers" name="txt_unit_c[]"/> </td>  <td>  <input type="text" autocomplete="off" id="txt_amnt8"  class="textbox item_txt_amnt only_numbers" disabled name="txt_amount[]"/> </td> </tr>
                    <tr class="ftd runtime">     <td>  <?php get_items_in_combo(); ?>  </td>   <td><?php get_measurement_combo(); ?></td>    <td>  <input type="text" autocomplete="off" id="txt_qty10" class="textbox bgt_txt_unitC only_numbers" name="txt_qty[]"/> </td> <td>  <input type="text" autocomplete="off" id="txt_unitc9"  class="textbox item_txt_qty only_numbers" name="txt_unit_c[]"/> </td>  <td>  <input type="text" autocomplete="off" id="txt_amnt9"  class="textbox item_txt_amnt only_numbers" disabled name="txt_amount[]"/> </td> </tr>
                    <tr class="ftd runtime">     <td>  <?php get_items_in_combo(); ?>  </td>   <td><?php get_measurement_combo(); ?></td>    <td>  <input type="text" autocomplete="off" id="txt_qty11" class="textbox bgt_txt_unitC only_numbers" name="txt_qty[]"/> </td> <td>  <input type="text" autocomplete="off" id="txt_unitc10" class="textbox item_txt_qty only_numbers" name="txt_unit_c[]"/> </td>  <td>  <input type="text" autocomplete="off" id="txt_amnt10" class="textbox item_txt_amnt only_numbers" disabled name="txt_amount[]"/> </td> </tr>
                    <tr class="ftd runtime">     <td>  <?php get_items_in_combo(); ?>  </td>   <td><?php get_measurement_combo(); ?></td>    <td>  <input type="text" autocomplete="off" id="txt_qty12" class="textbox bgt_txt_unitC only_numbers" name="txt_qty[]"/> </td> <td>  <input type="text" autocomplete="off" id="txt_unitc11" class="textbox item_txt_qty only_numbers" name="txt_unit_c[]"/> </td>  <td>  <input type="text" autocomplete="off" id="txt_amnt11" class="textbox item_txt_amnt only_numbers" disabled name="txt_amount[]"/> </td> </tr>
                    <tr class="ftd runtime">     <td>  <?php get_items_in_combo(); ?>  </td>   <td><?php get_measurement_combo(); ?></td>    <td>  <input type="text" autocomplete="off" id="txt_qty13" class="textbox bgt_txt_unitC only_numbers" name="txt_qty[]"/> </td> <td>  <input type="text" autocomplete="off" id="txt_unitc12" class="textbox item_txt_qty only_numbers" name="txt_unit_c[]"/> </td>  <td>  <input type="text" autocomplete="off" id="txt_amnt12" class="textbox item_txt_amnt only_numbers" disabled name="txt_amount[]"/> </td> </tr>
                    <tr class="ftd runtime">     <td>  <?php get_items_in_combo(); ?>  </td>   <td><?php get_measurement_combo(); ?></td>    <td>  <input type="text" autocomplete="off" id="txt_qty14" class="textbox bgt_txt_unitC only_numbers" name="txt_qty[]"/> </td> <td>  <input type="text" autocomplete="off" id="txt_unitc13" class="textbox item_txt_qty only_numbers" name="txt_unit_c[]"/> </td>  <td>  <input type="text" autocomplete="off" id="txt_amnt13" class="textbox item_txt_amnt only_numbers" disabled name="txt_amount[]"/> </td> </tr>
                    <tr class="ftd runtime">     <td>  <?php get_items_in_combo(); ?>  </td>   <td><?php get_measurement_combo(); ?></td>    <td>  <input type="text" autocomplete="off" id="txt_qty15" class="textbox bgt_txt_unitC only_numbers" name="txt_qty[]"/> </td> <td>  <input type="text" autocomplete="off" id="txt_unitc14" class="textbox item_txt_qty only_numbers" name="txt_unit_c[]"/> </td>  <td>  <input type="text" autocomplete="off" id="txt_amnt14" class="textbox item_txt_amnt only_numbers" disabled name="txt_amount[]"/> </td> </tr>
                    <tr class="ftd runtime">     <td>  <?php get_items_in_combo(); ?>  </td>   <td><?php get_measurement_combo(); ?></td>    <td>  <input type="text" autocomplete="off" id="txt_qty16" class="textbox bgt_txt_unitC only_numbers" name="txt_qty[]"/> </td> <td>  <input type="text" autocomplete="off" id="txt_unitc15" class="textbox item_txt_qty only_numbers" name="txt_unit_c[]"/> </td>  <td>  <input type="text" autocomplete="off" id="txt_amnt15" class="textbox item_txt_amnt only_numbers" disabled name="txt_amount[]"/> </td> </tr>

                    <tr><td colspan="5"> <input class="confirm_buttons save_request" type="submit" id="save_budget" name="send_p_request" value="Save" > </td></tr>
                </table>
            </form> 
            <table class=" off">
                <tr><td><label for="txt_"budget_value>budget value </label></td><td> <input type="text"     name="txt_budget_value" required id="txt_budget_value" class="textbox" value="<?php echo trim(chosen_budget_value_upd()); ?>"   />  </td></tr>
                <tr><td><label for="txt_"description>Description </label></td><td> <input type="text"     name="txt_description" required id="txt_description" class="textbox" value="<?php echo trim(chosen_description_upd()); ?>"   />  </td></tr>
                <tr><td><label for="txt_"is_active>Is Active </label></td><td> <input type="text"     name="txt_is_active" required id="txt_is_active" class="textbox" value="<?php echo trim(chosen_is_active_upd()); ?>"   />  </td></tr>
                <tr><td class="new_data_tb_frst_cols">Activity </td><td> <?php get_activity_combo(); ?>  </td></tr>
                <tr><td colspan="2"> <input type="submit" class="confirm_buttons" name="send_p_request" value="Save"/>  </td></tr>
            </table>

        </div>
        <div class="parts eighty_centered datalist_box" >
            <div class="parts no_shade_noBorder xx_titles no_bg whilte_text dataList_title">Requests </div>
            <?php
                save_request();
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
                $first = $obj->get_first_p_budget();
                echo 'Now date: ' . date('Y-m-d H:i:s');
                $obj->list_p_request($first)
            ?>
        </div>  <div class="parts no_paddin_shade_no_Border eighty_centered no_bg">
            <table>
                <td>
                    <form action="../web_exports/excel_export.php" method="post">
                        <input type="hidden" name="p_request" value="a"/>
                        <input type="submit" name="export" class="btn_export btn_export_excel" value="Export"/>
                    </form>
                </td>
                <td>
                    <form action="../prints/print_p_request.php" target="blank" method="post">
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
        <script>
            $(document).ready(function () {
                $('.item_txt_qty').keydown(function (e) {
                    $('.journal_entry_table > .item_txt_qty').css('background-color:', 'blue');
                    if (e.which === 9) {
                        var my_index = $(this).parent().index() + 1;
                        var closst = $(this).closest('tr').index() + 1;
                    }
                });
                $('.item_txt_amnt').focus(function () {
                    var item = $(this).val();
//                    alert('Got focus: ' + item  );
                    return false;
                });

                $('.save_request').click(function () {
                    var the_field = $('.cbo_field option:selected').val();
                    if (the_field == '') {
                        $('.warn_msg').show();
                        $(document).scrollTop(100);

                        return false;
                    } else {
                        $('.warn_msg').hide();
                        return true;
                    }
                });
            });
        </script>
    </body>
</hmtl>


<?php

    function get_field_combo() {
        $obj = new multi_values();
        $obj->get_field_in_combo();
    }

    function get_items_in_combo() {
        require_once '../web_db/other_fx.php';

        $ot = new other_fx();
        $ot->get_items_in_combo();
    }

    function get_measurement_combo() {
        $obj = new other_fx();
        $obj->get_measurement_in_combo_arr();
    }

    function get_project_combo() {
        $obj = new multi_values();
        $obj->get_project_in_combo_refill();
    }

    function get_fisc_year_combo() {
        $obj = new multi_values();
        $obj->get_fisc_year_in_combo();
    }

    function get_account_in_combo() {
        require_once '../web_db/other_fx.php';
        $obj = new other_fx();
        $obj->get_account_in_combo_array();
    }

    function get_account_combo() {
        require_once '../web_db/other_fx.php';
        $obj = new multi_values();
        $obj->get_account_in_combo();
    }

    function chosen_budget_value_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'p_budget') {
                $id = $_SESSION['id_upd'];
                $budget_value = new multi_values();
                return $budget_value->get_chosen_p_budget_budget_value($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_description_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'p_budget') {
                $id = $_SESSION['id_upd'];
                $description = new multi_values();
                return $description->get_chosen_p_budget_description($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_account_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'p_budget') {
                $id = $_SESSION['id_upd'];
                $account = new multi_values();
                return $account->get_chosen_p_budget_account($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_entry_date_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'p_budget') {
                $id = $_SESSION['id_upd'];
                $entry_date = new multi_values();
                return $entry_date->get_chosen_p_budget_entry_date($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_is_active_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'p_budget') {
                $id = $_SESSION['id_upd'];
                $is_active = new multi_values();
                return $is_active->get_chosen_p_budget_is_active($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function chosen_status_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'p_budget') {
                $id = $_SESSION['id_upd'];
                $status = new multi_values();
                return $status->get_chosen_p_budget_status($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function get_activity_combo() {

        $obj = new multi_values();
        $obj->get_activity_in_combo();
    }

    function chosen_activity_upd() {
        if (!empty($_SESSION['table_to_update'])) {
            if ($_SESSION['table_to_update'] == 'p_budget') {
                $id = $_SESSION['id_upd'];
                $activity = new multi_values();
                return $activity->get_chosen_p_budget_activity($id);
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    function get_fisc_year_process() {
        if (isset($_SESSION['selected_fisc_year'])) {
            return (isset($_SESSION['selected_fisc_year'])) ? $_SESSION['selected_fisc_year'] : '';
        }
    }

    function get_activity_process() {
        return (isset($_SESSION['activity'])) ? $_SESSION['activity'] : '';
    }
    