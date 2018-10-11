
//<editor-fold defaultstate="collapsed" desc="----These variables are for adding new accounts ------------">
var menu_index = '';//this 40 is made as initial 40 will not be used
var menu_itself = '';
var accountid = 0, item = 0;
//these are used on the journal entry line
var end_balance = 0;
var end_date = '';



//</editor-fold>
var fin_res = '';//this is the fincial results that come when someone has clicked the income statement of balance sheet or the cash flow

$(document).ready(function () {
    try {
        mainLink_click();
        navigation();//  function has all naviagations
        slide_fromleft();
        chart_of_accounts();
        Projects();
        journal();
        //<editor-fold defaultstate="collapsed" desc="---Journal---">
        get_new_account_from_combo();
        get_second_pane();
        ok_opening_bal();
        save_close();
        //</editor-fold>
        //<editor-fold defaultstate="collapsed" desc="---budget implementation---">
//      get_activityy_by_proj();
        get_cbo_refil_changed();
        get_rev_expenses_combo();
        show_rep_details();
        //</editor-fold>
        get_accountid_id_combo();
        change_hider_txt();
        center_combobox();// this is the combo box on the field page
        budget();
        slideUp_figures();
        keep_menu_open();
        get_index_session();
        request_auto_typing();
        admin_reprt_nav();
        alert_unfished_nav();
        show_alerts();
        financial_details();
        Retrieve_date();
        show_print();
        hide_any_bg();
        cbo_rep_period();
        switch_page_submenu();
        validate_activity_radio();
        view_link_click();
        //        hide_footer();
    } catch (err) {
        alert(err.message);
    }
});
function mainLink_click() {
    $('.main_link').click(function () {
        var me = $(this).children('span').is(':hidden');
        if (me) {
            $('.main_link').children('span').slideUp();
            $(this).children('span').slideToggle();
        } else {
            $(this).children('span').slideUp();
        }

        menu_index = $('.main_link').index(this);
        keep_menu_open();
        $.post('handler.php', {menu_index: menu_index}, function (data) {
            menu_index = data;
        });
        $('#navigated_menu').val(menu_index);
    });
}
function me_open() {


}
function some_menu_open() {
    var span1 = $('#span1').is(':hidden'), span2 = $('#span2').is(':hidden'), span3 = $('#span3').is(':hidden'), span4 = $('#span4').is(':hidden');
    if (span1 && span2 && span3 && span4) {
        return false;
    } else {
        return true;
    }
//    var open=$('#span1').is(':visible');
}
function slide_fromleft() {
    $('.slid_left').delay(1000).slideDown(500);
}
function get_accountid_id_combo() {
    try {
        $('.cbo_accountid').change(function () {

        });
    } catch (err) {
        alert(err.message);
    }
}
function change_hider_txt() {
//    $('.new_data_hider').html('Add New');
}
function center_combobox() {
    var box_width = $('.location_small_cbo').width();
    var cbo_width = $('.middle_cbo').width();
    var left_space = (box_width - cbo_width) / 2;

}
//<editor-fold defaultstate="collapsed" desc="------------Navigation of administrator------------">
function navigation() {
    try {
        $('.pages').hide();
        chart_acc();
        journal();
        t_balance();
        income();
        balance_sheet();
        projects();
        get_cbo_acc_rec_accid();

    } catch (err) {
        alert(err.message);
    }
}
function hide_anyother_pane() {
    $('.pages').hide();
    $('.slid_left').hide();
    //these down are the page specific items ( hider, new data form and the data list)
    $('.new_data_hider').hide();
    $('.datalist_box').hide();
    $('.new_data_box').hide();
}
//start
function chart_acc() {
    $('#pane_chart_acc').click(function () {
//        hide_anyother_pane();
//        change_nav_title('Chart of Accounts');
//        $('.charts_acc').show();
        //new temporaly functionality
        window.location.replace('new_account.php');

    });
}
function journal() {
    $('#pane_journal').click(function () {
//        hide_anyother_pane();
//        change_nav_title('General Journal entries');
//        $('.journal_sub_p').show();
        window.location.replace('new_journal_entry_line.php');

    });
}
function t_balance() {
    $('#pane_t_balance').click(function () {
//        hide_anyother_pane();
//        change_nav_title('Trial Balance');
//        $('.tb_sub_p').show();
        window.location.replace('report_trial_balance.php');
    });
}
function income() {
    $('#pane_income_stmt').click(function () {
//        hide_anyother_pane();
//        change_nav_title('Income Statement');
//        $('.income_sub_p').show();
//        window.location.replace('report_journal_entry.php');
    });
}
function balance_sheet() {
    $('#pane_b_sheet').click(function () {
//        hide_anyother_pane();
//        change_nav_title('Balance Sheet');
//        $('.balance_sub_p').show();
        window.location.replace('report_balanceshett.php');
    });
}
function projects() {
    $('#pane_projects').click(function () {
//        hide_anyother_pane();
//        change_nav_title('MMC Projects');
//        $('.projects_sub_p').show();
    });
}
function change_nav_title(title) {
    $('.changing_title').html(title);

}
//end
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="Chart of Accounts new_account.php">

function chart_of_accounts() {
    defaults();
    get_accounts_done();
    click_acc_table();
    hide_menu();
    changed_acc_type_combo();
    other_accounts();
    sub_account_checkbox();
    opening_balance_btn();
    cancel_opening_balance();
    other_pane_ok();//ok button
    cancel_acctype_step1_2();

}
function get_accounts_done() {
    $('.radios').click(function () {
        item = $(this).val().trim();
        $('#txt_acc_type_id').val(item);
        $('#acc_next').prop('disabled', false);
        $('.drop_down').prop('disabled', true);
        shall_save_journal(item);
        if (item == 1) {
            $('.accounts_expln').html('Categorizes money earned from normal business operations, such as: \n\
            ' + '  <br/>     <ul> '
                    + '  <li>Product sales</li>'
                    + ' <li>Services sales</li>'
                    + ' <li>Discounts to customers</li>'
                    + ' </ul>');
        } else if (item == 2) {
            $('.accounts_expln').html('Categorizes money spend in the course  of normal business operations, such as:<br/>\n\
                ' + '<ul>\n\
                    ' + '<li>Advertising and promotion</li>\n\
                      ' + '<li>Office sipplies</li>\n\
                    ' + '<li>Insurances</li>\n\
                    ' + '<li>Legal fees</li>\n\
                    ' + '<li>Charitable Contributions</li>\n\
                    ' + '<li>Rent</li><ul>');
        } else if (item == 7) {
            $('.accounts_expln').html('Tracks the values of significant items that have a useful life of more than one year, such as: <br/>\n\
                ' + '<ul>\n\
                ' + '<li>Buildings</li>\n\
                ' + '<li>Land</li>\n\
                ' + '<li>Machinery and equipment</li>\n\
                ' + '<li>Vehicles</li></ul>');
        } else if (item == 17) {
            $('.accounts_expln').html('Create one for each cah such as:<br/>\n\
                ' + '<ul>\n\
                    <li>Petty cash</li>\n\
                    <li>Checking</li>\n\
                    <li>Savings</li>\n\
                    <li>Money market</li>\n\
                 ' + '</ul>');
        } else if (item == 18) {
            $('.accounts_expln').html('Track the principal your business owes for a \n\
                loan or line of credit');
        } else if (item == 9) {
            $('.accounts_expln').html('Tacks the money you invested in, or money taken out of the business by owners or shareholders.<br/> Payroll and reimbursement expenses should not be included');
        }
    });
    $('#other').click(function () {
        item = 0;
    });
    $('.drop_down').change(function () {
        $('#other').is(':selected');
        var droped = $('.drop_down option:selected').val();
        $('#txt_acc_type_id').val(droped);
        item = droped;
        shall_save_journal(item);
        $('#acc_next').prop('disabled', false);
        if (droped == '6') {
            $('.accounts_expln').html('Tracks money your customer owe you on unpaid invoices;<br/><br/>\n\
                ' + 'Most business require only the A/R account that The system automatically creates<br/>');
        } else if (droped == '10') {
            $('.accounts_expln').html('Track the value of things that can be converted to cash or used up within one year\n\
            ' + ', such as:<br/><br/>\n\
             ' + '<li>Prepaid expenses</li>\n\
             ' + '<li>Employee cash advances</li>\n\
             ' + '<li>Incentory</li>\n\
             ' + '<li>Loans from your business</li>'
                    + '</ul>');
        } else if (droped == '11') {
            $('.accounts_expln').html('Track the value of things that are neither Fixed Assets nor Other Current Assets, such as:<br/><br/>\n\
               ' + '<ul>\n\
                <li>Goodwill</li>\n\
                <li>Long term notes receiveable</li>\n\
                <li>Security de[posits paid</li>\n\
                 \n\
                </ul>');
        } else if (droped == 5) {//Account payable
            $('.accounts_expln').html('Tracks money you owe to vendor for purchase made on credit.<br/>\n\
                <br/>\n\
                Most business require only the A/P account that the system automatically creates.');
        } else if (droped == '12') {//Other current liabilties
            $('.accounts_expln').html('Track money your business owes and expects to pay within one year such as:<br/><br/>\n\
               ' + '<ul>\n\
               ' + '<li>SAles tax</li>\n\
               ' + '<li>Security deposits/retainers from cistomers</li>\n\
                ' + '<li>Payroll taxes</li>\n\
                </ul>');
        } else if (droped == '13') {//Long term liabilities
            $('.accounts_expln').html('Tracks money your business owes and expects to pay back over more than one year, such as:<br/><br/>\n\
             ' + '<ul>\n\
             <li>mortgages</li>\n\
             <li>Long term loans</li>\n\
             <li>Notes payable</li>\n\
              \n\
                </ul>');
        } else if (droped == '14') {//Cost of Goods sold
            $('.accounts_expln').html('Tracks the direct costs to produces the otems that your business sales, such as:<br/><br/>\n\
              \n\
                <ul>\n\
                <li>Cost of materials</li>\n\
                <li>Cost of labor</li>\n\
                <li>shipping, freght and delivery</li>\n\
                <li>Subcontractors</li>\n\
                </ul>');
        } else if (droped == '15') {
            $('.accounts_expln').html('Categorizes money your business erans that is unrelated to normal business operations, such as:\n\
                ' + '<ul>\n\
                <li>Dividend income</li>\n\
                <li>Interest income</li>\n\
                <li>Insurance reimbursements</li>\n\
                </ul>');
        } else if (droped == '16') {
            $('.accounts_expln').html('Categorizes money your business spends that is unrelated to normal business operations, such as:<br/><br/>\n\
            ' + '<ul>\n\
            <li>Corporation taxes</li>\n\
            <li>Penalities and legal settlements</li>\n\
            </ul>');
        }
    });
    $('#acc_next').click(function () {
        if (item != 0) {
            accountid = $('#txt_acc_type_id').val();
            var cont = 'c';
            $('.tb_rows').hide(0, function () {
                if (item == '1' || item == '2') {
                    $('.next_row').show();
                    $('.next_button').show();

                    $('.acc_bo_row').hide();
                    $('.opn_b_row').hide();
                } else {
                    $('.next_row').show();
                    $('.next_button').show();
                }

            });

            //$.post('../admin/handler.php', {accountid: accountid}, function (data) {
//                alert(data);
//            }).complete(function () {
//
//            });
        } else {
            alert('You have to choose the account type');
        }

    });

}
function shall_save_journal(item) {
    if (item != 1 && item != 2) {
        //tell that we shall save in the journal (already a transaction)
        $('#txt_if_save_Journal').val('yes');
    } else {
        $('#txt_if_save_Journal').val('');
    }
}
function click_acc_table() {
    $('.dataList_table tr').click(function () {
//        $('.ftd').css('background', 'none').css('color', '#000');
//        $(this).css('background-color', '#178d31').css('color', '#fff');
    });
}
function hide_menu() {
    $('.hide_menu').click(function () {
        $('#header2').slideToggle();
        $('.changing_title').slideToggle();
    });
}
function changed_acc_type_combo() {
    $('.cbo_account').change(function () {
        var cbo_account = $('.cbo_account option:selected').val();
        $('#txt_account_id').val(cbo_account);
        $('#txt_subacc_id').val(cbo_account);
        $('#txt_chart_account_id').val(cbo_account);
        $('#txt_acc_type_id').val(cbo_account);

    });
}
function other_accounts() {
    $('#other').click(function () {
        $('.drop_down').prop('disabled', false);
    });
}
function sub_account_checkbox() {
    $('#check_acc').on('change', function () {
        if ($(this).is(':checked')) {
            $('.cbo_account').prop('disabled', false);
        } else {
            $('.cbo_account').prop('disabled', true);
        }
    });
}
function opening_balance_btn() {
    $('#balance_btn').click(function () {
        $('.abs_full').fadeIn(function () {
//            item_center('other_pane');
            $('.other_pane').show('slide', {direction: 'up'}).css('display', 'block');
        });
        return false;
    });
}
function cancel_opening_balance() {
    $('.js_cancel_btn').click(function () {
        $('.other_pane').slideUp(100, function () {
            $('.abs_full').fadeOut();
        });
    });
}
function cancel_acctype_step1_2() {
    $('.cancel_acc_type').click(function () {
        window.location.reload();
    });
}
function defaults() {
    item_center('other_pane');
    //    var left_margin=$();
    //    $('.other_pane').css('margin-left',parent_width);
}
function item_center(item) {// it is supposed that these items are inside "abs full" idv
    var parent_width = $('.' + item).parent().width();
    var parent_height = $('.abs_full').height();
    var item_w = $('.' + item).width();
    var item_h = $('.' + item).height();
    var left_space = (parent_width - item_w) / 2;
    var top_space = (parent_height - item_h) / 4;
    $('.' + item).css('margin-left', left_space);
    $('.' + item).css('margin-top', top_space);
}
function other_pane_ok() {//opening balance ok buttons
    $('#opn_balnce_btn').click(function () {
        var end_balance = $('#ending_balance').val();
        var end_date = $('#ending_date').val();
        if (end_balance != '' && end_date != '') {
            $('#txt_end_balance_id').val(end_balance);
            $('#txt_end_date_id').val(end_date);
            $('#txt_if_save_Journal').val('yes');
            //clear fields
            $('#ending_balance').val('');
            $('#ending_date').val('');
            //hide the pane and full bg
            $('.other_pane').slideUp(100, function () {
                $('.abs_full').fadeOut();
            });
        }
    });
}
//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="-----projects -----------">
function Projects() {
    select_a_budget();
    choose_budget();
    select_project();
    proj_select_link();
    select_item();
    show_items();
    get_proj_type_select();
}
function select_a_budget() {
    $('.p_budget_select_link').click(function () {
        var budget_id = $(this).data('bud_id');
        $('#txt_budget_id').val(budget_id);
        $('.selectable_table').hide(20);
        $('.new_data_table').show(20);
    });
}
function select_project() {
    $('.p_project_select_link').click(function () {
        var proj_id = $(this).data('proj_id');
        $('#txt_project_id').val(proj_id);
        $('.new_data_table').show(20);
        $('.selectable_table').hide();
    });
}
function proj_select_link() {
    $('.proj_select_link').click(function () {

        $('.new_data_table').hide(20);
        $('.selectable_table').show(20);

    });
}

function select_item() {
    $('.p_budget_items_select_link').click(function () {
        var itemid = $(this).data('item_id');
        $('#txt_item_id').val(itemid);
        $('.selectable_table').hide(20);
        $('.new_data_table').show(20);
    });
}
function show_items() {
    $('#item_link').click(function () {
        $('.selectable_table').show(20);
        $('.new_data_table').hide(20);
    });
}
function choose_budget() {
    $('#choose_budget_again').click(function () {
        $('.selectable_table').show(20);
        $('.new_data_table').hide(20);
    });
}

//</editor-fold>

//<editor-fold defaultstate="collapsed" desc="-----Journal entry line-----">
function get_new_account_from_combo() {
    $('.cbo_account_arr').change(function () {
        var item = $(this, 'option:selected').val();
        if (item === 'new_item') {
            $('.acc_bo_row').hide();
            $('.next_button').hide();
            $('.second_pane').hide(20);
            $('.first_pane').show(10);
            $('.tb_rows').show(10);
            $('.next_row').hide();
            $('.radios').prop('checked', false);
            new_account_pane();

        }
    });
}
function get_proj_type_select() {
    $('.p_type_project_select_link').click(function () {
        var typeid = $(this).data('type_id');
        $('#txt_type_project_id').val(typeid);
        $('.selectable_table').hide(20);
        $('.new_data_table').show(20);
    });
}
function get_second_pane() {
    $('.second_pane_caller').click(function () {
        $('.first_pane').slideUp(20);
        item_center('second_pane');
        $('.second_pane').slideDown(20);

    });
}
function ok_opening_bal() {
    $('.ok_opening').click(function () {
        end_balance = $('#ending_balance').val();
        end_date = $('#ending_date').val();
        if (end_balance != '' && end_date != '') {
            $('#txt_end_balance_id').val(end_balance);
            $('#txt_end_date_id').val(end_date);
            //clear fields
            $('#ending_balance').val('');
            $('#ending_date').val('');
            //hide the second pane and show the first pane
            $('.second_pane').slideUp(100, function () {
                $('.first_pane').slideDown(10);
            });
        }
    });
}
function save_close() {//while adding new account
    $('.save_close').click(function () {
        if (end_balance !== '') {
            var save_account = 'c';

            $.post('../admin/handler.php', {save_account: save_account, end_balance: end_balance, end_date: end_date}, function (data) {
                alert('reached: ' + data);
            }).complete(function () {

            });
        } else {

        }

    });
}
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="----Budget implementatioon---------">
function budget() {
    select_fical_year();
}
function select_fical_year() {
    $('#selct_f_year').click(function () {
//        var activity = $('.cbo_fisc_year option:selected').val();
        var the_year_name = $('.cbo_fisc_year option:selected').text();
        var selected_activity = 'c';
        var activity = $('.cbo_activity option:selected').val().trim();
        if (activity % 1 !== 0) {
            alert('You have to select an activity');
        } else {
            $('.new_data_table').show(2);
            $.post('handler.php', {selected_activity: selected_activity, activity: activity}, function (data) {
//            alert('From handler: '+ data);
            }).complete(function () {
                $('.thehidden_part').show(20);
                $('.selectable_table').hide(20);
                $('.new_data_box').slideDown(300);
                $(window).scrollTop(170);

            });
        }
    });
    $('.back_wiz').click(function () {
//        $('.thehidden_part').hide(20);
        $('.selectable_table').show(20);
        $('.rehide').hide(1);
        return false;
    });
    $('.select_activity_purchase_order_line').click(function () {
        var on_from_purchase_order_line = 'on_from_purchase_order_line';
        $.post('../admin/handler.php', {on_from_purchase_order_line: on_from_purchase_order_line}, function (data) {

        }).complete(function () {
            window.location.reload(2);
        });
    });
    $('.select_activity_purchase_invoice_line').click(function () {
        var on_from_purchase_invoice_line = 'on_from_purchase_invoice_line';
        $.post('../admin/handler.php', {on_from_purchase_invoice_line: on_from_purchase_invoice_line}, function (data) {

        }).complete(function () {

            window.location.reload(2);
        });
    });
    $('.select_activity_sales_invoice_line').click(function () {
        var on_from_sales_invoice_line = 'on_from_sales_invoice_line';
        $.post('../admin/handler.php', {on_from_sales_invoice_line: on_from_sales_invoice_line}, function (data) {

        }).complete(function () {

            window.location.reload(2);
        });
    });
}
function get_activityy_by_proj() {
    $('.cbo_proj_refill').change(function () {
        var act_by_proj = $(this, 'option:selected').val().trim();
        $('.tobe_refilled').empty();
        $('#onfly_selected_project').val();
        $('.tobe_refilled').append('<option value="fly_new_p_activity"> -- Add new -- </option>');
        $.post('../admin/handler.php', {act_by_proj: act_by_proj}, function (data) {
            var final = $.parseJSON(data.trim());
            $.each(final, function (i, option) {
                $('.tobe_refilled').append($('<option/>').attr("value", option.id).text(option.name));
            });
        });
    });
}
function get_cbo_refil_changed() {
    $('.tobe_refilled').change(function () {
        var item = $(this, 'option:selected').val().trim();
        $('#txt_selected_activity_id').val(item);
    });
}

//</editor-fold>
////<editor-fold defaultstate="collapsed" desc="---Budget preparation---">
function get_rev_expenses_combo() {
    $('.dcbo_rev_expeses').change(function () {
        var item = $(this, 'option:selected').val();
    });
}

function show_rep_details() {
    $('.details').click(function () {

        $(this).children('span').slideToggle();
    });
}
//</editor-fold>
//<editor-fold defaultstate="collapsed" desc="----Customers-----">
function get_cbo_acc_rec_accid() {
    $('.cbo_acc_rec_accid').change(function () {
        var acc = $('.cbo_acc_rec_accid option:selected').val();

    });
}
function new_account_pane() {
    $('.abs_full').fadeIn(function () {
        item_center('other_pane');
        $('.other_pane').slideDown(100);
    });
    return false;
}
//</editor-fold>

function slideUp_figures() {

    $('.pages_bottom').show(300);
}

function hide_footer() {
    $(window).load(function () {
        $('.footer').hide();
    });
}
function get_index_session() {
    var get_menu_index = 'c';
    $.post('handler.php', {get_menu_index: get_menu_index}, function (data) {
        menu_index = data;
    }).complete(function () {

        $('#navigated_menu').val(menu_index);
        if (menu_index == 0) {
            $('.main_link_budgt').children('span').slideDown(50);
        } else if (menu_index == 1) {
            $('.main_link_implmnt').children('span').slideDown(50);
        } else if (menu_index == 2) {
            $('.main_link_financ').children('span').slideDown(50);
        } else if (menu_index == 3) {
            $('.main_link_sales').children('span').slideDown(50);
        } else if (menu_index == 4) {
            $('.main_link_purhcase').children('span').slideDown(50);
        } else if (menu_index == 5) {
            $('.main_link_stock').children('span').slideDown(50);
        } else if (menu_index == 6) {
            $('.main_link_reporting').children('span').slideDown(50);
        } else if (menu_index == 7) {
            $('.main_link_settings').children('span').slideDown(50);
        }
    });
}
function keep_menu_open() {


}

function request_auto_typing() {
    $('#txt_unitc1').keyup(function () {
        var qty = $('#txt_qty1').val();
        $('#txt_amnt1').val($(this).val() * qty);
    });
    $('#txt_unitc2').keyup(function () {
        var qty = $('#txt_qty2').val();
        $('#txt_amnt2').val($(this).val() * qty);
    });
    $('#txt_unitc3').keyup(function () {
        var qty = $('#txt_qty3').val();
        $('#txt_amnt3').val($(this).val() * qty);
    });
    $('#txt_unitc4').keyup(function () {
        var qty = $('#txt_qty4').val();
        $('#txt_amnt4').val($(this).val() * qty);
    });
    $('#txt_unitc5').keyup(function () {
        var qty = $('#txt_qty5').val();
        $('#txt_amnt5').val($(this).val() * qty);
    });
    $('#txt_unitc6').keyup(function () {
        var qty = $('#txt_qty7').val();
        $('#txt_amnt7').val($(this).val() * qty);
    });
    $('#txt_unitc7').keyup(function () {
        var qty = $('#txt_qty8').val();
        $('#txt_amnt8').val($(this).val() * qty);
    });
    $('#txt_unitc8').keyup(function () {
        var qty = $('#txt_qty9').val();
        $('#txt_amnt9').val($(this).val() * qty);
    });
    $('#txt_unitc9').keyup(function () {
        var qty = $('#txt_qty9').val();
        $('#txt_amnt9').val($(this).val() * qty);
    });
    $('#txt_unitc10').keyup(function () {
        var qty = $('#txt_qty10').val();
        $('#txt_amnt10').val($(this).val() * qty);
    });
    $('#txt_unitc11').keyup(function () {
        var qty = $('#txt_qty11').val();
        $('#txt_amnt11').val($(this).val() * qty);
    });
    $('#txt_unitc11').keyup(function () {
        var qty = $('#txt_qty12').val();
        $('#txt_amnt12').val($(this).val() * qty);
    });
    $('#txt_unitc12').keyup(function () {
        var qty = $('#txt_qty12').val();
        $('#txt_amnt12').val($(this).val() * qty);
    });
}

//<editor-fold defaultstate="collapsed" desc="--------Admin reports navigation --------------">
function admin_reprt_nav() {
    $('.admin_rep_panelevel1').click(function () {
        var get_locations_by_budgetid = $(this).data('bind');
        var name = $(this).data('proj_name');

        $.post('../admin/handler_report.php', {name: name, get_locations_by_budgetid: get_locations_by_budgetid}, function (data) {
        }).complete(function () {
            window.location.replace('admin_reports_lv2.php');
        });
    });

    //this is t=level two
    $('.admin_rep_loc_box').click(function () {//this is level 2
        var get_budgets_by_location_id = $(this).data('bind');

        var loc_name = $(this).data('loc_name');

        $.post('../admin/handler_report.php', {get_budgets_by_location_id: get_budgets_by_location_id, loc_name: loc_name}, function (data) {
        }).complete(function () {
            window.location.replace('admin_reports_lv3.php');
        });
    });
}
//</editor-fold>
function alert_unfished_nav() {
    $('.issue_title, .data_details_pane_close_btn').click(function () {
        $('.alert_pane').fadeOut(200);
    });
}
function show_hide_percentage() {
    $('#chk_tax_inc').on('change', function () {
        if ($(this).is(':checked')) {
//            $('.cbo_account').prop('disabled', false);
            $('.perc_row').show();
        } else {
            $('.perc_row').hide();
        }
    });
    $('.perc_row').show();
}
function show_alerts() {// these are the unfinished processes
    $('.pre_alert').click(function () {
        $('.alert_pane').fadeIn();
    });


}

//<editor-fold defaultstate="collapsed" desc="----------- Financial statements (book)  --------------">
function financial_details() {
    $('.main_group_label').click(function () {

    });
    $('.btn_close_details').click(function () {

    });
    $('.fin_details_box').click(function () {
        $('.fin_details_box').fadeOut(300);
        $('.rep_data_box').fadeOut(200);
    });
    $('.group_label').click(function () {
        $('.fin_details_box').fadeIn(200);
        $('.rep_data_box').fadeIn(200);

    });
    //Income statement
    //The namings of the classes (prefix) below are wrong but realized too late. it should be inc
    $('.balnc_sales_revenue').click(function () {
        var balnc_sales_revenue = 'c';
        $('.rep_title').html('Sales revenues');
        $.post('handler_report.php', {balnc_sales_revenue: balnc_sales_revenue}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.balnc_cogs').click(function () {
        var balnc_cogs = 'c';
        $('.rep_title').html('Cost of good sold');
        $.post('handler_report.php', {balnc_cogs: balnc_cogs}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res  ').html(fin_res);
            });
        });
    });
    $('.balnc_research').click(function () {
        var balnc_research = 'c';
        $('.rep_title').html('Reasrch');
        $.post('handler_report.php', {balnc_research: balnc_research}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.balnc_gen_exp').click(function () {
        var balnc_gen_exp = 'c';
        $('.rep_title').html('General expenses');
        $.post('handler_report.php', {balnc_gen_exp: balnc_gen_exp}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res  ').html(fin_res);
            });
        });
    });
    $('.balnc_income_op').click(function () {
        var balnc_income_op = 'c';
        $('.rep_title').html('Income operations');
        $.post('handler_report.php', {balnc_income_op: balnc_income_op}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res  ').html(fin_res);
            });
        });
    });
    $('.balnc_interest_income').click(function () {
        var balnc_interest_income = 'c';
        $('.rep_title').html('Interese income');
        $.post('handler_report.php', {balnc_interest_income: balnc_interest_income}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res  ').html(fin_res);
            });
        });
    });
    $('.balnc_income_tx').click(function () {
        var balnc_income_tx = 'c';
        $('.rep_title').html('Income tax');
        $.post('handler_report.php', {balnc_income_tx: balnc_income_tx}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res  ').html(fin_res);
            });
        });
    });

    //Balance sheet
    //The namings of the classes  (prefix) below are wrong because they should be balnc

    $('.inc_cash').click(function () {
        var inc_cash = 'c';
        $('.rep_title').html('Cash report');

        $.post('handler_report.php', {inc_cash: inc_cash}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.inc_other_c_assets').click(function () {
        var inc_other_c_assets = 'c';
        $('.rep_title').html('Cash report');

        $.post('handler_report.php', {inc_other_c_assets: inc_other_c_assets}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.inc_acc_rec').click(function () {
        var inc_acc_rec = 'c';
        $('.rep_title').html('Account receivable');

        $.post('handler_report.php', {inc_acc_rec: inc_acc_rec}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.inc_acc_pay').click(function () {
        var inc_acc_pay = 'c';

        $('.rep_title').html('Account payable');
        $.post('handler_report.php', {inc_acc_pay: inc_acc_pay}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.inc_accrued_expe').click(function () {
        var inc_accrued_expe = 'c';
        $('.rep_title').html('Accrued expense');
        $.post('handler_report.php', {inc_accrued_expe: inc_accrued_expe}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.inc_income_tx').click(function () {
        var inc_income_tx = 'c';
        $('.rep_title').html('Income tax');
        $.post('handler_report.php', {inc_income_tx: inc_income_tx}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.inc_current_portion').click(function () {
        var inc_current_portion = 'c';
        $('.rep_title').html('current portion');
        $.post('handler_report.php', {inc_current_portion: inc_current_portion}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.inc_longterm_debt').click(function () {
        var inc_longterm_debt = 'c';
        $('.rep_title').html('long term debt');
        $.post('handler_report.php', {inc_longterm_debt: inc_longterm_debt}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.inc_capital_stock').click(function () {
        var inc_capital_stock = 'c';
        $('.rep_title').html('Capital stock report');
        $.post('handler_report.php', {inc_capital_stock: inc_capital_stock}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.inc_inventory').click(function () {
        var inc_inventory = 'c';
        $('.rep_title').html('Inventory');
        $.post('handler_report.php', {inc_inventory: inc_inventory}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.inc_prep_expense').click(function () {
        var inc_prep_expense = 'c';

        $('.rep_title').html('Prepaid expenses');
        $.post('handler_report.php', {inc_prep_expense: inc_prep_expense}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.inc_fixed_assets').click(function () {
        var inc_fixed_assets = 'c';

        $('.rep_title').html('Fixed assets');
        $.post('handler_report.php', {inc_fixed_assets: inc_fixed_assets}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.inc_accumulated_deprec').click(function () {
        var inc_accumulated_deprec = 'c';
        $('.fin_data_res').html('');
        $('.rep_title').html('Accumulated depreciations');
        $.post('handler_report.php', {inc_accumulated_deprec: inc_accumulated_deprec}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.inc_longterm_debt').click(function () {
        var inc_longterm_debt = 'c';
        $('.fin_data_res').html('');
        $('.rep_title').html('Long Term debt');
        $.post('handler_report.php', {inc_longterm_debt: inc_longterm_debt}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.inc_capital_stock').click(function () {
        var inc_capital_stock = 'c';
        $('.fin_data_res').html('');
        $('.rep_title').html('Other Liabilities');
        $.post('handler_report.php', {inc_capital_stock: inc_capital_stock}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.inc_retained_earnings').click(function () {
        var inc_retained_earnings = 'c';
        $('.fin_data_res').html('');
        $('.rep_title').html('Retained earnings');
        $.post('handler_report.php', {inc_retained_earnings: inc_retained_earnings}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });


//    Cash flow
    $('.ch_fl_cash').click(function () {
        var ch_fl_cash = 'c';
        $('.rep_title').html('Cash Report');
        $.post('handler_report.php', {ch_fl_cash: ch_fl_cash}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.ch_fl_cash_receit').click(function () {
        var ch_fl_cash_receit = 'c';
        $('.rep_title').html('Cash receit');
        $.post('handler_report.php', {ch_fl_cash_receit: ch_fl_cash_receit}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.ch_fl_disbursemt').click(function () {
        var ch_fl_disbursemt = 'c';
        $('.rep_title').html('Disbursement report');
        $.post('handler_report.php', {ch_fl_disbursemt: ch_fl_disbursemt}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.ch_fl_fixed_asst').click(function () {
        var ch_fl_fixed_asst = 'c';
        $('.rep_title').html('Cash Fixed asset purchase');
        $.post('handler_report.php', {ch_fl_fixed_asst: ch_fl_fixed_asst}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.ch_fl_net_borow').click(function () {
        var ch_fl_net_borow = 'c';
        $('.rep_title').html('Net borrowing report');
        $.post('handler_report.php', {ch_fl_net_borow: ch_fl_net_borow}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.ch_fl_incom_tx').click(function () {
        var ch_fl_incom_txn = 'c';
        $('.rep_title').html('income tax report');
        $.post('handler_report.php', {ch_fl_incom_tx: ch_fl_incom_tx}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });
    $('.ch_fl_sale_stock').click(function () {
        var ch_fl_sale_stock = 'c';
        $('.rep_title').html('sale stock');
        $.post('handler_report.php', {ch_fl_sale_stock: ch_fl_sale_stock}, function (data) {
            fin_res = data;
        }).complete(function () {
            $('.load_gif').hide(5, function () {
                $('.fin_data_res').html(fin_res);
            });
        });
    });

    $('.report_note').click(function () {
        $('.fin_details_box').fadeIn(200);
        $('.rep_data_box').fadeIn(200);

        var rep_type = $(this).data('bind');
        $('.fin_data_res').html('');
        if (rep_type === 'inc_note') {
            var full_book_income_statement = 'c';
            $.post('handler_report.php', {full_book_income_statement: full_book_income_statement}, function (data) {
                fin_res = data;
            }).complete(function () {
                $('.load_gif').hide(5, function () {
                    $('.fin_data_res').html(fin_res);
                });
            });

        } else if (rep_type === 'cash_flow_note') {
            var full_book_income_statement = 'c';
            $.post('handler_report.php', {full_book_income_statement: full_book_income_statement}, function (data) {
                fin_res = data;
            }).complete(function () {
                $('.load_gif').hide(5, function () {
                    $('.fin_data_res').html(fin_res);
                });
            });
        } else if (rep_type === 'balance_note') {
            var full_book_balance_sheet = 'c';
            $.post('handler_report.php', {full_book_balance_sheet: full_book_balance_sheet}, function (data) {
                fin_res = data;
            }).complete(function () {
                $('.load_gif').hide(5, function () {
                    $('.fin_data_res').html(fin_res);
                });
            });
        }
    });
    $('.report_note').delay(1000).show(2000);
}

function Retrieve_date() {//this is tp ge the date present in the textboxes once clicked on the search (All on ioncome sytatement, balance sheet and cashflow)
    $('.btn_books_date').click(function () {
        //Initialize the sessions
        var the_rep_dates = '';
        var date1 = $('#date1').val();
        var date2 = $('#date2').val();
        var bind = $(this).data('bind');
        if (bind == 'incm') {//This is the income statement
            the_rep_dates = 'income';
        } else if (bind == 'blns') {//This is the balance sheet
            the_rep_dates = 'balance';
        } else if (bind == 'cshfl') {//This is the cash flow
            the_rep_dates = 'cash';
        }

        $.post('handler_report.php', {the_rep_dates: the_rep_dates, date1: date1, date2: date2}, function (data) {
//            alert('The dates changed: ' + data);
        }).complete(function () {

            window.location.reload();
        });


    });
}
//</editor-fold>

function show_print() {//On rep_by_date.xxx
    $('.row_font_h1').hover(function () {
        $('.no_padding_print_btn').css('opacity', '1');
    });
    $('.row_font_h1').mouseleave(function () {
        $('.no_padding_print_btn').css('opacity', '0');
    });
}

function hide_any_bg() {//This is the bg that is firstly used on the new_journal_entry_line
    $('.any_full_bg').click(function () {
        $('.sub_full_bg').hide('slide', {direction: 'up'}, function () {
            $('.any_full_bg').fadeOut();
        });
    });
}

function cbo_rep_period() {//cm
    $('.cbo_rep_period_options').change(function () {
        var item = $(this, 'option:selected').val().trim();
        var rep_date_cbo = '';

        if (item == 'Today') {
            $.post('handler_report.php', {rep_date_cbo: rep_date_cbo}, function (data) {

            }).complete(function () {
//                window.location.reload();
            });
        } else if (item == 'This week') {
            rep_date_cbo = new Date().getDay();

        } else if (item == 'This month') {

        } else if (item == 'This month-to-date') {

        } else if (item == 'This fiscal quarter') {

        } else if (item == 'This fiscal quarter-to-date') {

        } else if (item == 'This fiscal year') {

        } else if (item == 'This fiscal year-to-Last Month') {

        } else if (item == 'This fiscal year-to-date') {

        } else if (item == 'Yesterday') {

        } else if (item == 'Last week-to-date') {

        } else if (item == 'Last month') {

        } else if (item == 'Last month-to-date') {

        } else if (item == 'Last fiscal quarter') {

        } else if (item == 'Last Last fiscal quarter-to-date quarter') {

        } else if (item == 'Last Year') {

        } else if (item == 'Last Year-to-date') {

        }
        $.post('handler_report.php', {rep_date_cbo: rep_date_cbo}, function (data) {
            alert(data);

        }).complete(function () {
            window.location.reload();
        });


    });
}

function switch_page_submenu() {//This is firstly used in the p_type_projct
    $('#smenu1').addClass('selected_smenu');
    $('#smenu1, #smenu2').click(function () {
        $('.page_pane').hide(1);
//        $('.smenu_item').removeClass('selected_smenu'); 

    });
    $('#smenu1').click(function () {
        $('#smenu1').addClass('selected_smenu');
        $('#smenu2').removeClass('selected_smenu');
        $('#page_pane1').slideDown(function () {
            $('#page_pane2').hide(1);
        });
    });
    $('#smenu2').click(function () {
        $('#smenu2').addClass('selected_smenu');
        $('#smenu1').removeClass('selected_smenu');
        $('#page_pane2').slideDown(function () {
            $('#page_pane1').hide(1);
        });
    });

}

function validate_activity_radio() {
    var rad1_checked = $('#revenue').is(':checked');
    var rad2_checked = $('#expense').is(':checked');


}

function view_link_click() {
    var res = '';
    $('.p_request_view_link').click(function () {
        var cont = 'c';
        $('.data_details_pane').fadeIn();
        var view_request = $(this).data('table_id');
        $.post('../admin/handler_update_details.php', {view_request: view_request}, function (data) {
            res = data;
        }).complete(function () {
            $('.data_res').html(res);
        });
    });
}