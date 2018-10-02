<?php
    /*
     * This is the class that will help user to navigate through the system of each view
     * The methodology is done using the js by positing to php
     */

    /**
     * Description of Details_by_click
     *
     * SANGWA ON 12 June 2018
     */
    require_once '../web_db/connection.php';

    class Details_by_click {

        function det_by_click_type_project($type) {
            //This one gets the projects, activities
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_budget_prep.p_budget_prep_id,  p_budget_prep.project_type,  p_budget_prep.user,  p_budget_prep.entry_date,  p_budget_prep.budget_type,  p_budget_prep.activity_desc, p_activity.amount,   p_budget_prep.name, user.Firstname, user,Lastname, p_type_project.name as type
                    from p_budget_prep
                    join user on p_budget_prep.user = user.StaffID 
                    join p_type_project on p_budget_prep.project_type=p_type_project.p_type_project_id
                    join p_activity on p_activity.project=p_budget_prep.p_budget_prep_id
                    where p_type_project_id=:type   ";
            $stmt = $db->prepare($sql);
            $budget_id = $type;
            $stmt->execute(array(":type" => $type));
            ?>
            <!--this is js-->
            <script>
                $('.data_details_pane_close_btn').click(function () {
                    $('.data_details_pane').fadeOut(10);
                    $('.data_details_pane').html('');
                });
            </script>
            <!--ending js-->

            <div class="parts full_center_two_h heit_free margin_free details_box">
                <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                    Budget Line/Projects
                </div>
                <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                <div class="parts  no_shade_noBorder data_details_pane_load">

                </div>
            </div>
            <div class="clickable_row_table_box">
                <table class="dataList_table clickable_row_table">
                    <thead><tr><td> S/N </td>
                            <td> project </td><td> user </td>
                            <td> entry date </td>
                            <td> Budget type </td>
                            <td> activity desc </td>
                            <td> amount </td>
                            <td> name </td>
                        </tr></thead>
                    <?php while ($row = $stmt->fetch()) { ?><tr> 
                            <td><?php echo $row['p_budget_prep_id']; ?> </td>
                            <td><?php echo $row['type']; ?> </td>
                            <td><?php
                                echo $row['Firstname'] . '  ';
                                echo $row['Lastname']
                                ?> </td>
                            <td>        <?php echo $row['entry_date']; ?> </td>
                            <td>        <?php echo $row['budget_type']; ?> </td>
                            <td>        <?php echo $row['activity_desc']; ?> </td>
                            <td>        <?php echo number_format($row['amount']); ?> </td>
                            <td>        <?php echo $row['name']; ?> </td>

                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="7">
                            <span class="push_right data_total">
                                <?php echo 'Total ' . number_format($this->get_total_budget_line($budget_id)); ?>
                            </span>
                        </td>
                    </tr>
                </table>
                <div class="parts full_center_two_h heit_free no_paddin_shade_no_Border"><a href="../admin/rep_by_date.php">View more report details</a></div>
            </div>
            <?php
        }

        function det_by_click_account($id) {
            //This one gets the account details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select account_id, account.name, account_type.name as acc_type,sum(journal_entry_line.amount) as amount from account "
                        . "  join account_type on account.acc_type=account_type.account_type_id "
                        . " left join journal_entry_line on journal_entry_line.accountid =account.account_id"
                        . "  where account.account_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var acc_type = $('#det_txt_acc_type').val();
                        var acc_class = $('#det_txt_acc_class').val();
                        var name = $('#det_txt_name').val();
                        var DrCrSide = $('#det_txt_DrCrSide').val();
                        var acc_code = $('#det_txt_acc_code').val();
                        var acc_desc = $('#det_txt_acc_desc').val();
                        var is_cash = $('#det_txt_is_cash').val();
                        var is_contra_acc = $('#det_txt_is_contra_acc').val();
                        var is_row_version = $('#det_txt_is_row_version').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, acc_type: acc_type, acc_class: acc_class, name: name, DrCrSide: DrCrSide, acc_code: acc_code, acc_desc: acc_desc, is_cash: is_cash, is_contra_acc: is_contra_acc, is_row_version: is_row_version}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        account Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">   
                    <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>     <tr><td><label for="det_txt_acc_type">account type</label></td><td><input type="text" id="det_txt_acc_type"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['acc_type']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_name">name</label></td><td><input type="text" id="det_txt_name"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['name']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_DrCrSide">Amount</label></td><td><input type="text" id="det_txt_DrCrSide"  class="details_txt white_bg no_bg only_numbers" style="min-width: 300px;"  value="<?php echo number_format($row['amount']); ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));  ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="account">Save Changes</button>
                            </td><td colspan="1"></td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_account_type($id) {
            //This one gets the account_type details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from account_type where account_type.account_type_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var name = $('#det_txt_name').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, name: name}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        account_type Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_name">name</label></td><td><input type="text" id="det_txt_name"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['name']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));  ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="account_type">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_ledger_settings($id) {
            //This one gets the ledger_settings details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from ledger_settings where ledger_settings.ledger_settings_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, }, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        ledger_settings Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td></td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));  ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="ledger_settings">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_bank($id) {
            //This one gets the bank details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from bank where bank.bank_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var account = $('#det_txt_account').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, account: account}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        bank Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_account">account</label></td><td><input type="text" id="det_txt_account"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['account']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));  ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="bank">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_account_class($id) {
            //This one gets the account_class details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from account_class where account_class.account_class_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, }, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        account_class Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td></td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));  ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="account_class">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_general_ledger_line($id) {
            //This one gets the general_ledger_line details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from general_ledger_line where general_ledger_line.general_ledger_line_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var general_ledge_header = $('#det_txt_general_ledge_header').val();
                        var accountid = $('#det_txt_accountid').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, general_ledge_header: general_ledge_header, accountid: accountid}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        general_ledger_line Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>      <tr><td><label for="det_txt_general_ledge_header">general_ledge_header</label></td><td><input type="text" id="det_txt_general_ledge_header"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['general_ledge_header']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_accountid">accountid</label></td><td><input type="text" id="det_txt_accountid"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['accountid']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));  ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="general_ledger_line">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_main_contra_account($id) {
            //This one gets the main_contra_account details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from main_contra_account where main_contra_account.main_contra_account_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var main_contra_acc = $('#det_txt_main_contra_acc').val();
                        var related_contra_acc = $('#det_txt_related_contra_acc').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, main_contra_acc: main_contra_acc, related_contra_acc: related_contra_acc}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        main_contra_account Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_main_contra_acc">main_contra_acc</label></td><td><input type="text" id="det_txt_main_contra_acc"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['main_contra_acc']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_related_contra_acc">related_contra_acc</label></td><td><input type="text" id="det_txt_related_contra_acc"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['related_contra_acc']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id)); ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="main_contra_account">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_sales_receit_header($id) {
            //This one gets the sales_receit_header details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select  sales_receit_header.sales_receit_header_id,  sales_receit_header.sales_invoice,  sales_receit_header.quantity,  sales_receit_header.unit_cost,  sales_receit_header.amount,  sales_receit_header.entry_date,  sales_receit_header.User,  party.name as client,    sales_receit_header.budget_prep,  sales_receit_header.account "
                        . ",user.Firstname,user.Lastname  "
                        . " from sales_receit_header "
                        . " join user on user.StaffID= sales_receit_header.User "
                        . " join party on party_id=sales_receit_header.client "
                        . " where sales_receit_header.sales_receit_header_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var sales_invoice = $('#det_txt_sales_invoice').val();
                        var entry_date = $('#det_txt_entry_date').val();
                        var User = $('#det_txt_User').val();
                        var amount = $('#det_txt_amount').val();
                        var approved = $('#det_txt_approved').val();
                        var sales_invoice = $('#det_txt_sales_invoice').val();
                        var quantity = $('#det_txt_quantity').val();
                        var unit_cost = $('#det_txt_unit_cost').val();
                        var amount = $('#det_txt_amount').val();
                        var entry_date = $('#det_txt_entry_date').val();
                        var User = $('#det_txt_User').val();
                        var client = $('#det_txt_client').val();
                        var sale_order = $('#det_txt_sale_order').val();
                        var budget_prep = $('#det_txt_budget_prep').val();
                        var account = $('#det_txt_account').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, sales_invoice: sales_invoice, entry_date: entry_date, User: User, amount: amount, approved: approved, sales_invoice: sales_invoice, quantity: quantity, unit_cost: unit_cost, amount: amount, entry_date: entry_date, User: User, client: client, sale_order: sale_order, budget_prep: budget_prep, account: account}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        sales receipt Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?>

                            <tr><td><label for="det_txt_entry_date">entry date</label></td><td><input type="text" id="det_txt_entry_date"  class="details_txt white_bg no_bg" style="min-width: 300px;" disabled=""  value="<?php echo $row['entry_date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_User">User</label></td><td><input type="text" id="det_txt_User"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php
                                    echo $row['Firstname'] . ' ';
                                    echo $row['Lastname'];
                                    ?>" /> </td></tr>
                            <tr><td><label for="det_txt_quantity">quantity</label></td><td><input type="text" id="det_txt_quantity"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['quantity']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_unit_cost">unit cost</label></td><td><input type="text" id="det_txt_unit_cost"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['unit_cost']; ?>" /> </td></tr>

                            <tr><td><label for="det_txt_amount">amount</label></td><td><input type="text" id="det_txt_amount"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo number_format($row['amount']); ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_approved">approved</label></td><td><input type="text" id="det_txt_approved"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['approved']; ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_sales_invoice">sales invoice</label></td><td><input type="text" id="det_txt_sales_invoice"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['sales_invoice']; ?>" /> </td></tr>

                            <tr class="off"><td><label for="det_txt_User">User</label></td><td><input type="text" id="det_txt_User"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['User']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_client">client</label></td><td><input type="text" id="det_txt_client"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['client']; ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_sale_order">sale order</label></td><td><input type="text" id="det_txt_sale_order"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['sale_order']; ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_budget_prep">budget prep</label></td><td><input type="text" id="det_txt_budget_prep"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['budget_prep']; ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_account">account</label></td><td><input type="text" id="det_txt_account"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['account']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="sales_receit_header">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_measurement($id) {
            //This one gets the measurement details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from measurement where measurement.measurement_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var code = $('#det_txt_code').val();
                        var description = $('#det_txt_description').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, code: code, description: description}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        measurement Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_code">code</label></td><td><input type="text" id="det_txt_code"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['code']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_description">description</label></td><td><input type="text" id="det_txt_description"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['description']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="measurement">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_journal_entry_line($id) {
            //This one gets the journal_entry_line details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from journal_entry_line where journal_entry_line.journal_entry_line_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var accountid = $('#det_txt_accountid').val();
                        var dr_cr = $('#det_txt_dr_cr').val();
                        var amount = $('#det_txt_amount').val();
                        var memo = $('#det_txt_memo').val();
                        var journal_entry_header = $('#det_txt_journal_entry_header').val();
                        var entry_date = $('#det_txt_entry_date').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, accountid: accountid, dr_cr: dr_cr, amount: amount, memo: memo, journal_entry_header: journal_entry_header, entry_date: entry_date}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        journal_entry_line Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_accountid">accountid</label></td><td><input type="text" id="det_txt_accountid"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['accountid']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_dr_cr">dr_cr</label></td><td><input type="text" id="det_txt_dr_cr"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['dr_cr']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_amount">amount</label></td><td><input type="text" id="det_txt_amount"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['amount']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_memo">memo</label></td><td><input type="text" id="det_txt_memo"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['memo']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_journal_entry_header">journal_entry_header</label></td><td><input type="text" id="det_txt_journal_entry_header"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['journal_entry_header']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_entry_date">entry_date</label></td><td><input type="text" id="det_txt_entry_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['entry_date']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="journal_entry_line">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_tax($id) {
            //This one gets the tax details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from tax where tax.tax_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var sales_accid = $('#det_txt_sales_accid').val();
                        var purchase_accid = $('#det_txt_purchase_accid').val();
                        var tax_name = $('#det_txt_tax_name').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, sales_accid: sales_accid, purchase_accid: purchase_accid, tax_name: tax_name}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        tax Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_sales_accid">sales_accid</label></td><td><input type="text" id="det_txt_sales_accid"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['sales_accid']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_purchase_accid">purchase_accid</label></td><td><input type="text" id="det_txt_purchase_accid"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['purchase_accid']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_tax_name">tax_name</label></td><td><input type="text" id="det_txt_tax_name"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['tax_name']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="tax">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_vendor($id) {
            //This one gets the vendor details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from vendor where vendor.vendor_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var venndor_number = $('#det_txt_venndor_number').val();
                        var party = $('#det_txt_party').val();
                        var payment_term = $('#det_txt_payment_term').val();
                        var tax_group = $('#det_txt_tax_group').val();
                        var purchase_acc = $('#det_txt_purchase_acc').val();
                        var pur_discount_accid = $('#det_txt_pur_discount_accid').val();
                        var primary_contact = $('#det_txt_primary_contact').val();
                        var acc_payable = $('#det_txt_acc_payable').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, venndor_number: venndor_number, party: party, payment_term: payment_term, tax_group: tax_group, purchase_acc: purchase_acc, pur_discount_accid: pur_discount_accid, primary_contact: primary_contact, acc_payable: acc_payable}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        vendor Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_venndor_number">venndor_number</label></td><td><input type="text" id="det_txt_venndor_number"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['venndor_number']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_party">party</label></td><td><input type="text" id="det_txt_party"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['party']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_payment_term">payment_term</label></td><td><input type="text" id="det_txt_payment_term"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['payment_term']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_tax_group">tax_group</label></td><td><input type="text" id="det_txt_tax_group"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['tax_group']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_purchase_acc">purchase_acc</label></td><td><input type="text" id="det_txt_purchase_acc"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['purchase_acc']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_pur_discount_accid">pur_discount_accid</label></td><td><input type="text" id="det_txt_pur_discount_accid"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['pur_discount_accid']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_primary_contact">primary_contact</label></td><td><input type="text" id="det_txt_primary_contact"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['primary_contact']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_acc_payable">acc_payable</label></td><td><input type="text" id="det_txt_acc_payable"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['acc_payable']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="vendor">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_general_ledger_header($id) {
            //This one gets the general_ledger_header details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from general_ledger_header where general_ledger_header.general_ledger_header_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var date = $('#det_txt_date').val();
                        var doc_type = $('#det_txt_doc_type').val();
                        var desc = $('#det_txt_desc').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, date: date, doc_type: doc_type, desc: desc}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        general_ledger_header Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_date">date</label></td><td><input type="text" id="det_txt_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_doc_type">doc_type</label></td><td><input type="text" id="det_txt_doc_type"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['doc_type']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_desc">desc</label></td><td><input type="text" id="det_txt_desc"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['desc']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="general_ledger_header">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_party($id) {
            //This one gets the party details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from party where party.party_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var party_type = $('#det_txt_party_type').val();
                        var name = $('#det_txt_name').val();
                        var email = $('#det_txt_email').val();
                        var website = $('#det_txt_website').val();
                        var phone = $('#det_txt_phone').val();
                        var is_active = $('#det_txt_is_active').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, party_type: party_type, name: name, email: email, website: website, phone: phone, is_active: is_active}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        party Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_party_type">party_type</label></td><td><input type="text" id="det_txt_party_type"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['party_type']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_name">name</label></td><td><input type="text" id="det_txt_name"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['name']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_email">email</label></td><td><input type="text" id="det_txt_email"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['email']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_website">website</label></td><td><input type="text" id="det_txt_website"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['website']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_phone">phone</label></td><td><input type="text" id="det_txt_phone"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['phone']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_is_active">is_active</label></td><td><input type="text" id="det_txt_is_active"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['is_active']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));     ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="party">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_contact($id) {
            //This one gets the contact details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from contact where contact.contact_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var party = $('#det_txt_party').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, party: party}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        contact Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_party">party</label></td><td><input type="text" id="det_txt_party"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['party']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));     ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="contact">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_customer($id) {
            //This one gets the customer details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select customer.customer_id,  customer.contact ,party.name,  party.email,  party.website,  party.phone from customer"
                        . " join party on party.party_id=customer.party_id "
                        . " where party.party_type='customer'"
                        . "  and customer.customer_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var party_id = $('#det_txt_party_id').val();
                        var contact = $('#det_txt_contact').val();
                        var number = $('#det_txt_number').val();
                        var tax_group = $('#det_txt_tax_group').val();
                        var payment_term = $('#det_txt_payment_term').val();
                        var sales_accid = $('#det_txt_sales_accid').val();
                        var acc_rec_accid = $('#det_txt_acc_rec_accid').val();
                        var promp_pyt_disc_accid = $('#det_txt_promp_pyt_disc_accid').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, party_id: party_id, contact: contact, number: number, tax_group: tax_group, payment_term: payment_term, sales_accid: sales_accid, acc_rec_accid: acc_rec_accid, promp_pyt_disc_accid: promp_pyt_disc_accid}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        customer Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">         
                    <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 

                            <tr><td><label for="det_txt_contact">Customer</label></td><td><input type="text" id="det_txt_contact"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['name']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_number">phone</label></td><td><input type="text" id="det_txt_number"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['phone']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_tax_group">email</label></td><td><input type="text" id="det_txt_tax_group"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['email']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_payment_term">website</label></td><td><input type="text" id="det_txt_payment_term"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['website']; ?>" /> </td></tr>

                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));     ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="customer">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_taxgroup($id) {
            //This one gets the taxgroup details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from taxgroup where taxgroup.taxgroup_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var description = $('#det_txt_description').val();
                        var tax_applied = $('#det_txt_tax_applied').val();
                        var is_active = $('#det_txt_is_active').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, description: description, tax_applied: tax_applied, is_active: is_active}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        taxgroup Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_description">description</label></td><td><input type="text" id="det_txt_description"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['description']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_tax_applied">tax_applied</label></td><td><input type="text" id="det_txt_tax_applied"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['tax_applied']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_is_active">is_active</label></td><td><input type="text" id="det_txt_is_active"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['is_active']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));     ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="taxgroup">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_journal_entry_header($id) {
            //This one gets the journal_entry_header details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from journal_entry_header where journal_entry_header.journal_entry_header_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var party = $('#det_txt_party').val();
                        var voucher_type = $('#det_txt_voucher_type').val();
                        var date = $('#det_txt_date').val();
                        var memo = $('#det_txt_memo').val();
                        var reference_number = $('#det_txt_reference_number').val();
                        var posted = $('#det_txt_posted').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, party: party, voucher_type: voucher_type, date: date, memo: memo, reference_number: reference_number, posted: posted}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        journal_entry_header Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_party">party</label></td><td><input type="text" id="det_txt_party"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['party']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_voucher_type">voucher_type</label></td><td><input type="text" id="det_txt_voucher_type"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['voucher_type']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_date">date</label></td><td><input type="text" id="det_txt_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_memo">memo</label></td><td><input type="text" id="det_txt_memo"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['memo']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_reference_number">reference_number</label></td><td><input type="text" id="det_txt_reference_number"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['reference_number']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_posted">posted</label></td><td><input type="text" id="det_txt_posted"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['posted']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));     ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="journal_entry_header">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_Payment_term($id) {
            //This one gets the Payment_term details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from Payment_term where Payment_term.Payment_term_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var description = $('#det_txt_description').val();
                        var payment_type = $('#det_txt_payment_type').val();
                        var due_after_days = $('#det_txt_due_after_days').val();
                        var is_active = $('#det_txt_is_active').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, description: description, payment_type: payment_type, due_after_days: due_after_days, is_active: is_active}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        Payment_term Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_description">description</label></td><td><input type="text" id="det_txt_description"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['description']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_payment_type">payment_type</label></td><td><input type="text" id="det_txt_payment_type"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['payment_type']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_due_after_days">due_after_days</label></td><td><input type="text" id="det_txt_due_after_days"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['due_after_days']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_is_active">is_active</label></td><td><input type="text" id="det_txt_is_active"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['is_active']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));     ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="Payment_term">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_item($id) {
            //This one gets the item details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from item where item.item_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var measurement = $('#det_txt_measurement').val();
                        var vendor = $('#det_txt_vendor').val();
                        var item_group = $('#det_txt_item_group').val();
                        var item_category = $('#det_txt_item_category').val();
                        var smallest_measurement = $('#det_txt_smallest_measurement').val();
                        var sale_measurement = $('#det_txt_sale_measurement').val();
                        var purchase_measurement = $('#det_txt_purchase_measurement').val();
                        var sales_account = $('#det_txt_sales_account').val();
                        var inventory_accid = $('#det_txt_inventory_accid').val();
                        var inventoty_adj_accid = $('#det_txt_inventoty_adj_accid').val();
                        var number = $('#det_txt_number').val();
                        var Code = $('#det_txt_Code').val();
                        var description = $('#det_txt_description').val();
                        var purchase_desc = $('#det_txt_purchase_desc').val();
                        var sale_desc = $('#det_txt_sale_desc').val();
                        var cost = $('#det_txt_cost').val();
                        var price = $('#det_txt_price').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, measurement: measurement, vendor: vendor, item_group: item_group, item_category: item_category, smallest_measurement: smallest_measurement, sale_measurement: sale_measurement, purchase_measurement: purchase_measurement, sales_account: sales_account, inventory_accid: inventory_accid, inventoty_adj_accid: inventoty_adj_accid, number: number, Code: Code, description: description, purchase_desc: purchase_desc, sale_desc: sale_desc, cost: cost, price: price}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        item Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_measurement">measurement</label></td><td><input type="text" id="det_txt_measurement"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['measurement']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_vendor">vendor</label></td><td><input type="text" id="det_txt_vendor"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['vendor']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_item_group">item_group</label></td><td><input type="text" id="det_txt_item_group"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['item_group']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_item_category">item_category</label></td><td><input type="text" id="det_txt_item_category"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['item_category']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_smallest_measurement">smallest_measurement</label></td><td><input type="text" id="det_txt_smallest_measurement"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['smallest_measurement']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_sale_measurement">sale_measurement</label></td><td><input type="text" id="det_txt_sale_measurement"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['sale_measurement']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_purchase_measurement">purchase_measurement</label></td><td><input type="text" id="det_txt_purchase_measurement"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['purchase_measurement']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_sales_account">sales_account</label></td><td><input type="text" id="det_txt_sales_account"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['sales_account']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_inventory_accid">inventory_accid</label></td><td><input type="text" id="det_txt_inventory_accid"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['inventory_accid']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_inventoty_adj_accid">inventoty_adj_accid</label></td><td><input type="text" id="det_txt_inventoty_adj_accid"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['inventoty_adj_accid']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_number">number</label></td><td><input type="text" id="det_txt_number"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['number']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_Code">Code</label></td><td><input type="text" id="det_txt_Code"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['Code']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_description">description</label></td><td><input type="text" id="det_txt_description"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['description']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_purchase_desc">purchase_desc</label></td><td><input type="text" id="det_txt_purchase_desc"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['purchase_desc']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_sale_desc">sale_desc</label></td><td><input type="text" id="det_txt_sale_desc"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['sale_desc']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_cost">cost</label></td><td><input type="text" id="det_txt_cost"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['cost']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_price">price</label></td><td><input type="text" id="det_txt_price"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['price']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));     ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="item">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_item_group($id) {
            //This one gets the item_group details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from item_group where item_group.item_group_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var name = $('#det_txt_name').val();
                        var is_full_exempt = $('#det_txt_is_full_exempt').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, name: name, is_full_exempt: is_full_exempt}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        item_group Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_name">name</label></td><td><input type="text" id="det_txt_name"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['name']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_is_full_exempt">is_full_exempt</label></td><td><input type="text" id="det_txt_is_full_exempt"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['is_full_exempt']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));     ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="item_group">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_item_category($id) {
            //This one gets the item_category details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from item_category where item_category.item_category_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var measurement = $('#det_txt_measurement').val();
                        var sales_accid = $('#det_txt_sales_accid').val();
                        var inventory_accid = $('#det_txt_inventory_accid').val();
                        var cost_good_sold_accid = $('#det_txt_cost_good_sold_accid').val();
                        var adjustment_accid = $('#det_txt_adjustment_accid').val();
                        var assembly_accid = $('#det_txt_assembly_accid').val();
                        var name = $('#det_txt_name').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, measurement: measurement, sales_accid: sales_accid, inventory_accid: inventory_accid, cost_good_sold_accid: cost_good_sold_accid, adjustment_accid: adjustment_accid, assembly_accid: assembly_accid, name: name}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        item_category Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_measurement">measurement</label></td><td><input type="text" id="det_txt_measurement"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['measurement']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_sales_accid">sales_accid</label></td><td><input type="text" id="det_txt_sales_accid"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['sales_accid']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_inventory_accid">inventory_accid</label></td><td><input type="text" id="det_txt_inventory_accid"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['inventory_accid']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_cost_good_sold_accid">cost_good_sold_accid</label></td><td><input type="text" id="det_txt_cost_good_sold_accid"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['cost_good_sold_accid']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_adjustment_accid">adjustment_accid</label></td><td><input type="text" id="det_txt_adjustment_accid"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['adjustment_accid']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_assembly_accid">assembly_accid</label></td><td><input type="text" id="det_txt_assembly_accid"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['assembly_accid']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_name">name</label></td><td><input type="text" id="det_txt_name"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['name']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));     ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="item_category">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_vendor_payment($id) {
            //This one gets the vendor_payment details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from vendor_payment where vendor_payment.vendor_payment_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var vendor = $('#det_txt_vendor').val();
                        var gen_ledger_header = $('#det_txt_gen_ledger_header').val();
                        var pur_invoice_header = $('#det_txt_pur_invoice_header').val();
                        var number = $('#det_txt_number').val();
                        var date = $('#det_txt_date').val();
                        var amount = $('#det_txt_amount').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, vendor: vendor, gen_ledger_header: gen_ledger_header, pur_invoice_header: pur_invoice_header, number: number, date: date, amount: amount}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        vendor_payment Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_vendor">vendor</label></td><td><input type="text" id="det_txt_vendor"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['vendor']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_gen_ledger_header">gen_ledger_header</label></td><td><input type="text" id="det_txt_gen_ledger_header"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['gen_ledger_header']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_pur_invoice_header">pur_invoice_header</label></td><td><input type="text" id="det_txt_pur_invoice_header"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['pur_invoice_header']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_number">number</label></td><td><input type="text" id="det_txt_number"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['number']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_date">date</label></td><td><input type="text" id="det_txt_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_amount">amount</label></td><td><input type="text" id="det_txt_amount"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['amount']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));     ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="vendor_payment">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_sale_delivery_line($id) {
            //This one gets the sale_delivery_line details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from sale_delivery_line where sale_delivery_line.sale_delivery_line_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var item = $('#det_txt_item').val();
                        var measurement = $('#det_txt_measurement').val();
                        var sales_delivery_header = $('#det_txt_sales_delivery_header').val();
                        var sales_invoice_line = $('#det_txt_sales_invoice_line').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, item: item, measurement: measurement, sales_delivery_header: sales_delivery_header, sales_invoice_line: sales_invoice_line}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        sale_delivery_line Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_item">item</label></td><td><input type="text" id="det_txt_item"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['item']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_measurement">measurement</label></td><td><input type="text" id="det_txt_measurement"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['measurement']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_sales_delivery_header">sales_delivery_header</label></td><td><input type="text" id="det_txt_sales_delivery_header"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['sales_delivery_header']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_sales_invoice_line">sales_invoice_line</label></td><td><input type="text" id="det_txt_sales_invoice_line"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['sales_invoice_line']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));     ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="sale_delivery_line">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_sales_invoice_line($id) {
            //This one gets the sales_invoice_line details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select  sales_invoice_line.sales_invoice_line_id,  sales_invoice_line.quantity,  sales_invoice_line.unit_cost,  sales_invoice_line.amount,  sales_invoice_line.entry_date,  sales_invoice_line.User,  party.name  as client,  sales_invoice_line.sales_order,  sales_invoice_line.budget_prep_id,  sales_invoice_line.account, "
                        . " user.Firstname, user.Lastname ,"
                        . " p_budget_items.item_name  as item,sales_invoice_line.pay_method  "
                        . " from sales_invoice_line "
                        . " join user on user.StaffID=sales_invoice_line.User "
                        . " join sales_order_line on sales_order_line.sales_order_line_id=sales_invoice_line.sales_order
                            join sales_quote_line on sales_quote_line.sales_quote_line_id=sales_order_line.quotationid
                            join p_budget_items on p_budget_items.p_budget_items_id=sales_quote_line.item 
                            join party on party.party_id =sales_invoice_line.client
                            where sales_invoice_line.sales_invoice_line_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var quantity = $('#det_txt_quantity').val();
                        var unit_cost = $('#det_txt_unit_cost').val();
                        var amount = $('#det_txt_amount').val();
                        var entry_date = $('#det_txt_entry_date').val();
                        var User = $('#det_txt_User').val();
                        var client = $('#det_txt_client').val();
                        var sales_order = $('#det_txt_sales_order').val();
                        var budget_prep_id = $('#det_txt_budget_prep_id').val();
                        var account = $('#det_txt_account').val();
                        var pay_method = $('#det_txt_pay_method').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, quantity: quantity, unit_cost: unit_cost, amount: amount, entry_date: entry_date, User: User, client: client, sales_order: sales_order, budget_prep_id: budget_prep_id, account: account, pay_method: pay_method}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        sales invoice  Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">  <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?>
                            <tr><td> <tr><td><label for="det_txt_quantity">Item</label></td><td><input type="text" id="det_txt_quantity"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['item']; ?>" /> </td></tr>
                            <td> <tr><td><label for="det_txt_quantity">quantity</label></td><td><input type="text" id="det_txt_quantity"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['quantity']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_unit_cost">unit cost</label></td><td><input type="text" id="det_txt_unit_cost"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo number_format($row['unit_cost']); ?>" /> </td></tr>
                            <tr><td><label for="det_txt_amount">amount</label></td><td><input type="text" id="det_txt_amount"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo number_format($row['amount']); ?>" /> </td></tr>
                            <tr><td><label for="det_txt_entry_date">entry date</label></td><td><input type="text" disabled id="det_txt_entry_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['entry_date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_User">User</label></td><td><input type="text" id="det_txt_User"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php
                                    echo $row['Firstname'] . ' ';
                                    echo $row['Lastname'];
                                    ?>" /> </td></tr>
                            <tr><td><label for="det_txt_client">client</label></td><td><input type="text" id="det_txt_client"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['client']; ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_sales_order">sales order</label></td><td><input type="text" id="det_txt_sales_order"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['sales_order']; ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_budget_prep_id">budget prep id</label></td><td><input type="text" id="det_txt_budget_prep_id"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['budget_prep_id']; ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_account">account</label></td><td><input type="text" id="det_txt_account"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['account']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_pay_method">pay method</label></td><td><input type="text" id="det_txt_pay_method"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['pay_method']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));        ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="sales_invoice_line">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_sales_invoice_header($id) {
            //This one gets the sales_invoice_header details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from sales_invoice_header where sales_invoice_header.sales_invoice_header_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var customer = $('#det_txt_customer').val();
                        var payment_term = $('#det_txt_payment_term').val();
                        var gen_ledger_header = $('#det_txt_gen_ledger_header').val();
                        var number = $('#det_txt_number').val();
                        var date = $('#det_txt_date').val();
                        var Shipping = $('#det_txt_Shipping').val();
                        var status = $('#det_txt_status').val();
                        var reference_no = $('#det_txt_reference_no').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, customer: customer, payment_term: payment_term, gen_ledger_header: gen_ledger_header, number: number, date: date, Shipping: Shipping, status: status, reference_no: reference_no}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        sales_invoice_header Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_customer">customer</label></td><td><input type="text" id="det_txt_customer"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['customer']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_payment_term">payment_term</label></td><td><input type="text" id="det_txt_payment_term"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['payment_term']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_gen_ledger_header">gen_ledger_header</label></td><td><input type="text" id="det_txt_gen_ledger_header"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['gen_ledger_header']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_number">number</label></td><td><input type="text" id="det_txt_number"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['number']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_date">date</label></td><td><input type="text" id="det_txt_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_Shipping">Shipping</label></td><td><input type="text" id="det_txt_Shipping"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['Shipping']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_status">status</label></td><td><input type="text" id="det_txt_status"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['status']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_reference_no">reference_no</label></td><td><input type="text" id="det_txt_reference_no"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['reference_no']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));        ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="sales_invoice_header">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_sales_order_line($id) {
            //This one gets the sales_order_line details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select   sales_order_line.sales_order_line_id,  sales_order_line.quantity,  sales_order_line.unit_cost,  sales_order_line.amount,  sales_order_line.entry_date,  sales_order_line.User,  sales_order_line.quotationid, user.Firstname, user.Lastname, p_budget_items.item_name as item  
                            ,sales_quote_line.sales_quote_line_id,  sales_quote_line.quantity as q_quantity,  measurement.code as measurement_unit,
                            sales_quote_line.unit_cost as q_unit_cost,  sales_quote_line.entry_date as q_entry_date, 
                            user.Firstname,user.Lastname,  sales_quote_line.amount, p_budget_items.item_name as item 
                             from sales_order_line "
                        . " join user on user.StaffID=sales_order_line.User "
                        . " join sales_quote_line on sales_quote_line.sales_quote_line_id=sales_order_line.quotationid "
                        . " join measurement on sales_quote_line.measurement = measurement.measurement_id  "
                        . " join p_budget_items on p_budget_items.p_budget_items_id=sales_quote_line.item "
                        . " where sales_order_line.sales_order_line_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var quantity = $('#det_txt_quantity').val();
                        var unit_cost = $('#det_txt_unit_cost').val();
                        var amount = $('#det_txt_amount').val();
                        var entry_date = $('#det_txt_entry_date').val();
                        var User = $('#det_txt_User').val();
                        var quotationid = $('#det_txt_quotationid').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, quantity: quantity, unit_cost: unit_cost, amount: amount, entry_date: entry_date, User: User, quotationid: quotationid}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        sales order  Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">    <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?>
                            <tr> 
                                <td>  <tr><td><label for="det_txt_quantity">quantity</label></td><td><input type="text" id="det_txt_quantity"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['quantity']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_unit_cost">unit cost</label></td><td><input type="text" id="det_txt_unit_cost"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['unit_cost']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_amount">amount</label></td><td><input type="text" id="det_txt_amount"  class="details_txt white_bg no_bg only_numbers" style="min-width: 300px;"  value="<?php echo number_format($row['amount']); ?>" /> </td></tr>
                            <tr><td><label for="det_txt_entry_date">entry date</label></td><td><input type="text" id="det_txt_entry_date"  class="details_txt white_bg no_bg" disabled style="min-width: 300px;"  value="<?php echo $row['entry_date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_User">User</label></td><td><input type="text" id="det_txt_User"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php
                                    echo $row['Firstname'] . ' ';
                                    echo $row['Lastname'];
                                    ?>" /> </td></tr>
                            <tr class="big_title"><td><label for="det_txt_quotationid"><u>QUOTATION</u></label></td><td><input type="text" id="det_txt_quotationid"  class="details_txt white_bg no_bg" style="min-width: 300px;"   /> </td></tr>
                            <td> <tr><td><label for="det_txt_quantity">quantity</label></td><td><input type="text" id="det_txt_quantity"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['q_quantity']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_unit_cost">unit cost</label></td><td><input type="text" id="det_txt_unit_cost"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['q_unit_cost']; ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_entry_date">entry date</label></td><td><input type="text" id="det_txt_entry_date" disabled  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['q_entry_date']; ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_User">User</label></td><td><input type="text" id="det_txt_User"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php
                                    echo $row['q_Firstname'] . ' ';
                                    echo $row['q_Lastname'];
                                    ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_amount">amount</label></td><td><input type="text" id="det_txt_amount"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo number_format($row['amount']); ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));           ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="sales_order_line">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_sales_order_header($id) {
            //This one gets the sales_order_header details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from sales_order_header where sales_order_header.sales_order_header_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var customer = $('#det_txt_customer').val();
                        var payment_term = $('#det_txt_payment_term').val();
                        var number = $('#det_txt_number').val();
                        var reference_number = $('#det_txt_reference_number').val();
                        var date = $('#det_txt_date').val();
                        var status = $('#det_txt_status').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, customer: customer, payment_term: payment_term, number: number, reference_number: reference_number, date: date, status: status}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        sales_order_header Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_customer">customer</label></td><td><input type="text" id="det_txt_customer"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['customer']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_payment_term">payment_term</label></td><td><input type="text" id="det_txt_payment_term"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['payment_term']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_number">number</label></td><td><input type="text" id="det_txt_number"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['number']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_reference_number">reference_number</label></td><td><input type="text" id="det_txt_reference_number"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['reference_number']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_date">date</label></td><td><input type="text" id="det_txt_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_status">status</label></td><td><input type="text" id="det_txt_status"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['status']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));           ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="sales_order_header">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_sales_quote_line($id) {
            //This one gets the sales_quote_line details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select sales_quote_line.sales_quote_line_id,  sales_quote_line.quantity,  measurement.code as measurement_unit,
                            sales_quote_line.unit_cost,  sales_quote_line.entry_date, 
                            user.Firstname,user.Lastname,  sales_quote_line.amount, p_budget_items.item_name as item
                            from sales_quote_line "
                        . " join measurement on sales_quote_line.measurement = measurement.measurement_id 
                            join user on user.StaffID=sales_quote_line.User 
                            join p_budget_items on p_budget_items.p_budget_items_id =sales_quote_line.item "
                        . " where sales_quote_line.sales_quote_line_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var quantity = $('#det_txt_quantity').val();
                        var unit_cost = $('#det_txt_unit_cost').val();
                        var entry_date = $('#det_txt_entry_date').val();
                        var User = $('#det_txt_User').val();
                        var amount = $('#det_txt_amount').val();
                        var measurement = $('#det_txt_measurement').val();
                        var item = $('#det_txt_item').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, quantity: quantity, unit_cost: unit_cost, entry_date: entry_date, User: User, amount: amount, measurement: measurement, item: item}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        sales quote  Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">       
                    <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?>
                            <tr><td><label for="det_txt_item">item</label></td><td><input type="text" id="det_txt_item"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['item']; ?>" /> </td></tr>
                            <tr> 
                                <td> <tr><td><label for="det_txt_quantity">quantity</label></td><td><input type="text" id="det_txt_quantity"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['quantity']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_unit_cost">unit cost</label></td><td><input type="text" id="det_txt_unit_cost"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['unit_cost']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_entry_date">entry date</label></td><td><input type="text" id="det_txt_entry_date" disabled  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['entry_date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_User">User</label></td><td><input type="text" id="det_txt_User"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php
                                    echo $row['Firstname'] . ' ';
                                    echo $row['Lastname'];
                                    ?>" /> </td></tr>
                            <tr><td><label for="det_txt_amount">amount</label></td><td><input type="text" id="det_txt_amount"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo number_format($row['amount']); ?>" /> </td></tr>
                            <tr><td><label for="det_txt_measurement">measurement</label></td><td><input type="text" id="det_txt_measurement"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['measurement_unit']; ?>" /> </td></tr>

                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));              ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="sales_quote_line">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_sales_quote_header($id) {
            //This one gets the sales_quote_header details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from sales_quote_header where sales_quote_header.sales_quote_header_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var customer = $('#det_txt_customer').val();
                        var date = $('#det_txt_date').val();
                        var payment_term = $('#det_txt_payment_term').val();
                        var reference_number = $('#det_txt_reference_number').val();
                        var number = $('#det_txt_number').val();
                        var status = $('#det_txt_status').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, customer: customer, date: date, payment_term: payment_term, reference_number: reference_number, number: number, status: status}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        sales_quote_header Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_customer">customer</label></td><td><input type="text" id="det_txt_customer"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['customer']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_date">date</label></td><td><input type="text" id="det_txt_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_payment_term">payment_term</label></td><td><input type="text" id="det_txt_payment_term"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['payment_term']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_reference_number">reference_number</label></td><td><input type="text" id="det_txt_reference_number"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['reference_number']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_number">number</label></td><td><input type="text" id="det_txt_number"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['number']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_status">status</label></td><td><input type="text" id="det_txt_status"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['status']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));              ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="sales_quote_header">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_purchase_invoice_header($id) {
            //This one gets the purchase_invoice_header details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from purchase_invoice_header where purchase_invoice_header.purchase_invoice_header_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var inv_control_journal = $('#det_txt_inv_control_journal').val();
                        var item = $('#det_txt_item').val();
                        var measurement = $('#det_txt_measurement').val();
                        var quantity = $('#det_txt_quantity').val();
                        var receieved_qusntinty = $('#det_txt_receieved_qusntinty').val();
                        var cost = $('#det_txt_cost').val();
                        var discount = $('#det_txt_discount').val();
                        var purchase_order_line = $('#det_txt_purchase_order_line').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, inv_control_journal: inv_control_journal, item: item, measurement: measurement, quantity: quantity, receieved_qusntinty: receieved_qusntinty, cost: cost, discount: discount, purchase_order_line: purchase_order_line}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        purchase_invoice_header Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_inv_control_journal">inv_control_journal</label></td><td><input type="text" id="det_txt_inv_control_journal"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['inv_control_journal']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_item">item</label></td><td><input type="text" id="det_txt_item"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['item']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_measurement">measurement</label></td><td><input type="text" id="det_txt_measurement"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['measurement']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_quantity">quantity</label></td><td><input type="text" id="det_txt_quantity"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['quantity']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_receieved_qusntinty">receieved_qusntinty</label></td><td><input type="text" id="det_txt_receieved_qusntinty"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['receieved_qusntinty']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_cost">cost</label></td><td><input type="text" id="det_txt_cost"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['cost']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_discount">discount</label></td><td><input type="text" id="det_txt_discount"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['discount']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_purchase_order_line">purchase_order_line</label></td><td><input type="text" id="det_txt_purchase_order_line"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['purchase_order_line']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));               ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="purchase_invoice_header">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_purchase_invoice_line($id) {
            //This one gets the purchase_invoice_line details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select purchase_invoice_line.purchase_invoice_line_id,  purchase_invoice_line.entry_date,  purchase_invoice_line.User,  purchase_invoice_line.quantity,  purchase_invoice_line.unit_cost,  purchase_invoice_line.amount,  purchase_invoice_line.purchase_order,  purchase_invoice_line.activity,  purchase_invoice_line.acc_debit,  purchase_invoice_line.supplier, user.Firstname,user.Lastname "
                        . " from purchase_invoice_line "
                        . " join user on user.StaffID=purchase_invoice_line.user "
                        . " where purchase_invoice_line.purchase_invoice_line_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var entry_date = $('#det_txt_entry_date').val();
                        var User = $('#det_txt_User').val();
                        var quantity = $('#det_txt_quantity').val();
                        var unit_cost = $('#det_txt_unit_cost').val();
                        var amount = $('#det_txt_amount').val();
                        var purchase_order = $('#det_txt_purchase_order').val();
                        var activity = $('#det_txt_activity').val();
                        var account = $('#det_txt_account').val();
                        var supplier = $('#det_txt_supplier').val();
                        var pay_method = $('#det_txt_pay_method').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, entry_date: entry_date, User: User, quantity: quantity, unit_cost: unit_cost, amount: amount, purchase_order: purchase_order, activity: activity, account: account, supplier: supplier, pay_method: pay_method}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        purchase invoice Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?>
                            <tr>  <td>  <tr><td><label for="det_txt_entry_date">entry date</label></td><td><input type="text" id="det_txt_entry_date" disabled class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['entry_date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_User">User</label></td><td><input type="text" id="det_txt_User"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php
                                    echo $row['Firstname'] . ' ';
                                    echo $row['Lastname']
                                    ?>" /> </td></tr>
                            <tr><td><label for="det_txt_quantity">quantity</label></td><td><input type="text" id="det_txt_quantity"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['quantity']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_unit_cost">unit cost</label></td><td><input type="text" id="det_txt_unit_cost"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['unit_cost']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_amount">amount</label></td><td><input type="text" id="det_txt_amount"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['amount']; ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_purchase_order">purchase order</label></td><td><input type="text" id="det_txt_purchase_order"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['purchase_order']; ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_activity">activity</label></td><td><input type="text" id="det_txt_activity"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['activity']; ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_account">account</label></td><td><input type="text" id="det_txt_account"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['account']; ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_supplier">supplier</label></td><td><input type="text" id="det_txt_supplier"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['supplier']; ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_pay_method">pay method</label></td><td><input type="text" id="det_txt_pay_method"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                  ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="purchase_invoice_line">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_purchase_order_header($id) {
            //This one gets the purchase_order_header details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from purchase_order_header where purchase_order_header.purchase_order_header_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var vendor = $('#det_txt_vendor').val();
                        var gen_ledger_header = $('#det_txt_gen_ledger_header').val();
                        var date = $('#det_txt_date').val();
                        var number = $('#det_txt_number').val();
                        var vendor_invoice_number = $('#det_txt_vendor_invoice_number').val();
                        var description = $('#det_txt_description').val();
                        var payment_term = $('#det_txt_payment_term').val();
                        var reference_number = $('#det_txt_reference_number').val();
                        var status = $('#det_txt_status').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, vendor: vendor, gen_ledger_header: gen_ledger_header, date: date, number: number, vendor_invoice_number: vendor_invoice_number, description: description, payment_term: payment_term, reference_number: reference_number, status: status}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        purchase_order_header Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_vendor">vendor</label></td><td><input type="text" id="det_txt_vendor"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['vendor']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_gen_ledger_header">gen_ledger_header</label></td><td><input type="text" id="det_txt_gen_ledger_header"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['gen_ledger_header']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_date">date</label></td><td><input type="text" id="det_txt_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_number">number</label></td><td><input type="text" id="det_txt_number"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['number']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_vendor_invoice_number">vendor_invoice_number</label></td><td><input type="text" id="det_txt_vendor_invoice_number"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['vendor_invoice_number']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_description">description</label></td><td><input type="text" id="det_txt_description"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['description']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_payment_term">payment_term</label></td><td><input type="text" id="det_txt_payment_term"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['payment_term']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_reference_number">reference_number</label></td><td><input type="text" id="det_txt_reference_number"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['reference_number']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_status">status</label></td><td><input type="text" id="det_txt_status"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['status']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                  ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="purchase_order_header">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_purchase_order_line($id) {
            //This one gets the purchase_order_line details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select  purchase_order_line.purchase_order_line_id,  purchase_order_line.entry_date,  purchase_order_line.User,  purchase_order_line.quantity,  purchase_order_line.unit_cost,  purchase_order_line.amount,  purchase_order_line.request,  measurement.code as measurement,  purchase_order_line.supplier, user.Firstname,user.Lastname"
                        . " from purchase_order_line "
                        . " join user on user.StaffID=purchase_order_line.User "
                        . " join measurement on measurement.measurement_id=purchase_order_line.measurement"
                        . " where purchase_order_line.purchase_order_line_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var entry_date = $('#det_txt_entry_date').val();
                        var User = $('#det_txt_User').val();
                        var quantity = $('#det_txt_quantity').val();
                        var unit_cost = $('#det_txt_unit_cost').val();
                        var amount = $('#det_txt_amount').val();
                        var request = $('#det_txt_request').val();
                        var measurement = $('#det_txt_measurement').val();
                        var supplier = $('#det_txt_supplier').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, entry_date: entry_date, User: User, quantity: quantity, unit_cost: unit_cost, amount: amount, request: request, measurement: measurement, supplier: supplier}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        purchase order  Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_entry_date">entry_date</label></td><td><input type="text" id="det_txt_entry_date" disabled  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['entry_date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_User">User</label></td><td><input type="text" id="det_txt_User"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php
                                    echo $row['Firstname'] . ' ';
                                    echo $row['Lastname'];
                                    ?>" /> </td></tr>
                            <tr><td><label for="det_txt_quantity">quantity</label></td><td><input type="text" id="det_txt_quantity"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['quantity']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_unit_cost">unit cost</label></td><td><input type="text" id="det_txt_unit_cost"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo number_format($row['unit_cost']); ?>" /> </td></tr>
                            <tr><td><label for="det_txt_amount">amount</label></td><td><input type="text" id="det_txt_amount"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo number_format($row['amount']); ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_request">request</label></td><td><input type="text" id="det_txt_request"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['request']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_measurement">measurement</label></td><td><input type="text" id="det_txt_measurement"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['measurement']; ?>" /> </td></tr>
                            <tr class="off"><td><label for="det_txt_supplier">supplier</label></td><td><input type="text" id="det_txt_supplier"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['supplier']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                    ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="purchase_order_line">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_purchase_receit_header($id) {
            //This one gets the purchase_receit_header details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from purchase_receit_header where purchase_receit_header.purchase_receit_header_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var gen_ledger_header = $('#det_txt_gen_ledger_header').val();
                        var date = $('#det_txt_date').val();
                        var status = $('#det_txt_status').val();
                        var number = $('#det_txt_number').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, gen_ledger_header: gen_ledger_header, date: date, status: status, number: number}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        purchase_receit_header Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_gen_ledger_header">gen_ledger_header</label></td><td><input type="text" id="det_txt_gen_ledger_header"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['gen_ledger_header']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_date">date</label></td><td><input type="text" id="det_txt_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_status">status</label></td><td><input type="text" id="det_txt_status"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['status']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_number">number</label></td><td><input type="text" id="det_txt_number"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['number']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                     ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="purchase_receit_header">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_purchase_receit_line($id) {
            //This one gets the purchase_receit_line details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select purchase_receit_line.purchase_receit_line_id,  purchase_receit_line.entry_date,  purchase_receit_line.User,  purchase_receit_line.quantity,  purchase_receit_line.unit_cost,  purchase_receit_line.amount,  purchase_receit_line.purchase_invoice,  purchase_receit_line.activity,  purchase_receit_line.account,  purchase_receit_line.suplier, user.Firstname, user.Lastname"
                        . " from purchase_receit_line "
                        . " join user on user.StaffID = purchase_receit_line.User "
                        . " join party on party.party_id=purchase_receit_line.suplier "
                        . " where purchase_receit_line.purchase_receit_line_id=:id "
                        . " ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var entry_date = $('#det_txt_entry_date').val();
                        var User = $('#det_txt_User').val();
                        var quantity = $('#det_txt_quantity').val();
                        var unit_cost = $('#det_txt_unit_cost').val();
                        var amount = $('#det_txt_amount').val();
                        var purchase_invoice = $('#det_txt_purchase_invoice').val();
                        var activity = $('#det_txt_activity').val();
                        var account = $('#det_txt_account').val();
                        var suplier = $('#det_txt_suplier').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, entry_date: entry_date, User: User, quantity: quantity, unit_cost: unit_cost, amount: amount, purchase_invoice: purchase_invoice, activity: activity, account: account, suplier: suplier}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        purchase receipt Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">  <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>     <tr><td><label for="det_txt_entry_date">entry date</label></td><td><input type="text" disabled id="det_txt_entry_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['entry_date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_User">User</label></td><td><input type="text" id="det_txt_User"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php
                                    echo $row['Firstname'] . ' ';
                                    echo $row['Lastname'];
                                    ?>" /> </td></tr>
                            <tr><td><label for="det_txt_quantity">quantity</label></td><td><input type="text" id="det_txt_quantity"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['quantity']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_unit_cost">unit cost</label></td><td><input type="text" id="det_txt_unit_cost"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo number_format($row['unit_cost']); ?>" /> </td></tr>
                            <tr><td><label for="det_txt_amount">amount</label></td><td><input type="text" id="det_txt_amount"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo number_format($row['amount']); ?>" /> </td></tr>

                            <tr><td><label for="det_txt_suplier">suplier</label></td><td><input type="text" id="det_txt_suplier"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['suplier']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                        ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="purchase_receit_line">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_Inventory_control_journal($id) {
            //This one gets the Inventory_control_journal details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from Inventory_control_journal where Inventory_control_journal.Inventory_control_journal_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var measurement = $('#det_txt_measurement').val();
                        var item = $('#det_txt_item').val();
                        var doc_type = $('#det_txt_doc_type').val();
                        var In_qty = $('#det_txt_In_qty').val();
                        var Out_qty = $('#det_txt_Out_qty').val();
                        var date = $('#det_txt_date').val();
                        var total_cost = $('#det_txt_total_cost').val();
                        var tot_amount = $('#det_txt_tot_amount').val();
                        var is_reverse = $('#det_txt_is_reverse').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, measurement: measurement, item: item, doc_type: doc_type, In_qty: In_qty, Out_qty: Out_qty, date: date, total_cost: total_cost, tot_amount: tot_amount, is_reverse: is_reverse}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        Inventory_control_journal Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_measurement">measurement</label></td><td><input type="text" id="det_txt_measurement"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['measurement']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_item">item</label></td><td><input type="text" id="det_txt_item"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['item']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_doc_type">doc_type</label></td><td><input type="text" id="det_txt_doc_type"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['doc_type']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_In_qty">In_qty</label></td><td><input type="text" id="det_txt_In_qty"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['In_qty']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_Out_qty">Out_qty</label></td><td><input type="text" id="det_txt_Out_qty"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['Out_qty']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_date">date</label></td><td><input type="text" id="det_txt_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_total_cost">total_cost</label></td><td><input type="text" id="det_txt_total_cost"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['total_cost']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_tot_amount">tot_amount</label></td><td><input type="text" id="det_txt_tot_amount"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['tot_amount']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_is_reverse">is_reverse</label></td><td><input type="text" id="det_txt_is_reverse"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['is_reverse']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                        ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="Inventory_control_journal">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_user($id) {
            //This one gets the user details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from user where user.user_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var LastName = $('#det_txt_LastName').val();
                        var FirstName = $('#det_txt_FirstName').val();
                        var UserName = $('#det_txt_UserName').val();
                        var EmailAddress = $('#det_txt_EmailAddress').val();
                        var IsActive = $('#det_txt_IsActive').val();
                        var Password = $('#det_txt_Password').val();
                        var Roleid = $('#det_txt_Roleid').val();
                        var position_depart = $('#det_txt_position_depart').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, LastName: LastName, FirstName: FirstName, UserName: UserName, EmailAddress: EmailAddress, IsActive: IsActive, Password: Password, Roleid: Roleid, position_depart: position_depart}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        user Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_LastName">LastName</label></td><td><input type="text" id="det_txt_LastName"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['LastName']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_FirstName">FirstName</label></td><td><input type="text" id="det_txt_FirstName"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['FirstName']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_UserName">UserName</label></td><td><input type="text" id="det_txt_UserName"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['UserName']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_EmailAddress">EmailAddress</label></td><td><input type="text" id="det_txt_EmailAddress"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['EmailAddress']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_IsActive">IsActive</label></td><td><input type="text" id="det_txt_IsActive"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['IsActive']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_Password">Password</label></td><td><input type="text" id="det_txt_Password"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['Password']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_Roleid">Roleid</label></td><td><input type="text" id="det_txt_Roleid"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['Roleid']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_position_depart">position_depart</label></td><td><input type="text" id="det_txt_position_depart"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['position_depart']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                        ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="user">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_role($id) {
            //This one gets the role details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from role where role.role_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var name = $('#det_txt_name').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, name: name}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        role Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_name">name</label></td><td><input type="text" id="det_txt_name"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['name']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                        ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="role">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_staff_positions($id) {
            //This one gets the staff_positions details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from staff_positions where staff_positions.staff_positions_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var name = $('#det_txt_name').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, name: name}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        staff_positions Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_name">name</label></td><td><input type="text" id="det_txt_name"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['name']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                        ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="staff_positions">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_request($id) {
            //This one gets the request details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from p_request where p_request.p_request_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var entry_date = $('#det_txt_entry_date').val();
                        var User = $('#det_txt_User').val();
                        var item = $('#det_txt_item').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, entry_date: entry_date, User: User, item: item}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        request Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_entry_date">entry_date</label></td><td><input type="text" id="det_txt_entry_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['entry_date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_User">User</label></td><td><input type="text" id="det_txt_User"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['User']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_item">item</label></td><td><input type="text" id="det_txt_item"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['item']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                        ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="request">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_supplier($id) {
            //This one gets the supplier details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from supplier where supplier.supplier_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var party = $('#det_txt_party').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, party: party}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        supplier Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_party">party</label></td><td><input type="text" id="det_txt_party"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['party']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                       ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="supplier">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_client($id) {
            //This one gets the client details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from client where client.client_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var party = $('#det_txt_party').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, party: party}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        client Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_party">party</label></td><td><input type="text" id="det_txt_party"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['party']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                       ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="client">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_p_budget($id) {
            //This one gets the p_budget details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from p_budget where p_budget.p_budget_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var entry_date = $('#det_txt_entry_date').val();
                        var User = $('#det_txt_User').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, entry_date: entry_date, User: User}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        p_budget Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_entry_date">entry_date</label></td><td><input type="text" id="det_txt_entry_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['entry_date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_User">User</label></td><td><input type="text" id="det_txt_User"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['User']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                       ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="p_budget">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_p_activity($id) {
            //This one gets the p_activity details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select p_activity.p_activity_id,p_activity.name, p_activity.amount,  p_budget_prep.name as project, p_fiscal_year.fiscal_year_name as fisc_year from  p_activity  "
                        . " join p_budget_prep on p_budget_prep.p_budget_prep_id=p_activity.project "
                        . " join p_fiscal_year on p_fiscal_year.p_fiscal_year_id=p_activity.fisc_year"
                        . " where p_activity.p_activity_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var project = $('#det_txt_project').val();
                        var name = $('#det_txt_name').val();
                        var fisc_year = $('#det_txt_fisc_year').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, project: project, name: name, fisc_year: fisc_year}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        activity Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?>
                            <tr> 
                                <td>   <tr><td><label for="det_txt_project">project</label></td><td><input type="text" id="det_txt_project"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['project']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_name">name</label></td><td><input type="text" id="det_txt_name"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['name']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_fisc_year">fiscal year</label></td><td><input type="text" id="det_txt_fisc_year"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['fisc_year']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_fisc_year">Amount</label></td><td><input type="text" id="det_txt_fisc_year"  class="details_txt white_bg no_bg only_numbers" style="min-width: 300px;"  value="<?php echo number_format($row['amount']); ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                       ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="p_activity">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_Main_Request($id) {
            //This one gets the Main_Request details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from Main_Request where Main_Request.Main_Request_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var entry_date = $('#det_txt_entry_date').val();
                        var User = $('#det_txt_User').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, entry_date: entry_date, User: User}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        Main_Request Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_entry_date">entry_date</label></td><td><input type="text" id="det_txt_entry_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['entry_date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_User">User</label></td><td><input type="text" id="det_txt_User"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['User']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                        ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="Main_Request">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_p_budget_prep($id) {
            //This one gets the p_budget_prep details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select p_budget_prep.p_budget_prep_id,  p_type_project.name as project_type,  p_budget_prep.user,  p_budget_prep.entry_date,  p_budget_prep.budget_type ,     p_type_project.name,p_budget_prep.activity_desc, p_budget_prep.name as activity,user.Firstname, user.Lastname,p_activity.amount b_amount,p_type_project.p_type_project_id   from p_budget_prep 
                     join p_type_project on p_type_project.p_type_project_id=p_budget_prep.project_type
                     join user on user.StaffID=p_budget_prep.User
                     join p_activity on p_activity.project=p_budget_prep.p_budget_prep_id
                     where p_budget_prep.p_budget_prep_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var project_type = $('#det_txt_project_type').val();
                        var user = $('#det_txt_user').val();
                        var entry_date = $('#det_txt_entry_date').val();
                        var budget_type = $('#det_txt_budget_type').val();
                        var activity_desc = $('#det_txt_activity_desc').val();
                        var name = $('#det_txt_name').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, project_type: project_type, user: user, entry_date: entry_date, budget_type: budget_type, activity_desc: activity_desc, name: name}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        budget preparation Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">   
                    <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td> <tr><td><label for="det_txt_project_type">project type</label></td><td><input type="text" id="det_txt_project_type"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['project_type']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_user">user</label></td><td><input type="text" id="det_txt_user"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php
                                    echo $row['Firstname'] . ' ';
                                    echo $row['Lastname']
                                    ?>" /> </td></tr>
                            <tr><td><label for="det_txt_entry_date">entry date</label></td><td><input type="text" id="det_txt_entry_date" disabled  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['entry_date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_budget_type">budget type</label></td><td><input type="text" id="det_txt_budget_type"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['budget_type']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_activity_desc">activity desc</label></td><td><input type="text" id="det_txt_activity_desc"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['activity_desc']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_name">name</label></td><td><input type="text" id="det_txt_name"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['name']; ?>" /> </td></tr>
                            <tr>
                                <td colspan="2">
                                    <button class="details_send_update link_cursor push_right" data-bind="p_budget_prep">Save Changes</button>
                                </td>
                            </tr>    <tr><td colspan="2"><a href="#" style="color: #0000ff;"> Show/Hide Activities details</a>  </td>       </tr> <tr><td colspan="2">   
                                    <?php $this->det_by_click_type_project_sub($row['p_type_project_id']); ?>
                                </td>   
                            </tr>

                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                           ?>
                                </span>
                            </td>
                        </tr>

                    </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_payment_voucher($id) {
            //This one gets the payment_voucher details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from payment_voucher where payment_voucher.payment_voucher_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var item = $('#det_txt_item').val();
                        var entry_date = $('#det_txt_entry_date').val();
                        var User = $('#det_txt_User').val();
                        var quantity = $('#det_txt_quantity').val();
                        var unit_cost = $('#det_txt_unit_cost').val();
                        var amount = $('#det_txt_amount').val();
                        var budget_prep = $('#det_txt_budget_prep').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, item: item, entry_date: entry_date, User: User, quantity: quantity, unit_cost: unit_cost, amount: amount, budget_prep: budget_prep}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        payment_voucher Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_item">item</label></td><td><input type="text" id="det_txt_item"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['item']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_entry_date">entry_date</label></td><td><input type="text" id="det_txt_entry_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['entry_date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_User">User</label></td><td><input type="text" id="det_txt_User"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['User']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_quantity">quantity</label></td><td><input type="text" id="det_txt_quantity"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['quantity']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_unit_cost">unit_cost</label></td><td><input type="text" id="det_txt_unit_cost"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['unit_cost']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_amount">amount</label></td><td><input type="text" id="det_txt_amount"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['amount']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_budget_prep">budget_prep</label></td><td><input type="text" id="det_txt_budget_prep"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['budget_prep']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                          ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="payment_voucher">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_delete_update_permission($id) {
            //This one gets the delete_update_permission details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from delete_update_permission where delete_update_permission.delete_update_permission_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var user = $('#det_txt_user').val();
                        var permission = $('#det_txt_permission').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, user: user, permission: permission}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        delete_update_permission Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_user">user</label></td><td><input type="text" id="det_txt_user"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['user']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_permission">permission</label></td><td><input type="text" id="det_txt_permission"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['permission']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                          ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="delete_update_permission">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_stock_taking($id) {
            //This one gets the stock_taking details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select * from stock_taking where stock_taking.stock_taking_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var item = $('#det_txt_item').val();
                        var quantity = $('#det_txt_quantity').val();
                        var entry_date = $('#det_txt_entry_date').val();
                        var User = $('#det_txt_User').val();
                        var available_quantity = $('#det_txt_available_quantity').val();
                        var in_or_out = $('#det_txt_in_or_out').val();
                        var measurement = $('#det_txt_measurement').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, item: item, quantity: quantity, entry_date: entry_date, User: User, available_quantity: available_quantity, in_or_out: in_or_out, measurement: measurement}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        stock_taking Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_item">item</label></td><td><input type="text" id="det_txt_item"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['item']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_quantity">quantity</label></td><td><input type="text" id="det_txt_quantity"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['quantity']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_entry_date">entry_date</label></td><td><input type="text" id="det_txt_entry_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['entry_date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_User">User</label></td><td><input type="text" id="det_txt_User"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['User']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_available_quantity">available_quantity</label></td><td><input type="text" id="det_txt_available_quantity"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['available_quantity']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_in_or_out">in_or_out</label></td><td><input type="text" id="det_txt_in_or_out"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['in_or_out']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_measurement">measurement</label></td><td><input type="text" id="det_txt_measurement"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['measurement']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));                           ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="stock_taking">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_p_budget_items($id) {
            //This one gets the p_budget_items details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select p_budget_items.p_budget_items_id,  p_budget_items.item_name,  p_budget_items.description,  p_budget_items.created_by,  p_budget_items.entry_date,  p_budget_items.chart_account, user.Firstname,user.Lastname from p_budget_items"
                        . " join user on user.StaffID=p_budget_items.created_by "
                        . "  where p_budget_items.p_budget_items_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var item_name = $('#det_txt_item_name').val();
                        var description = $('#det_txt_description').val();
                        var created_by = $('#det_txt_created_by').val();
                        var entry_date = $('#det_txt_entry_date').val();
                        var chart_account = $('#det_txt_chart_account').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, item_name: item_name, description: description, created_by: created_by, entry_date: entry_date, chart_account: chart_account}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        items Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_item_name">item_name</label></td><td><input type="text" id="det_txt_item_name"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['item_name']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_description">description</label></td><td><input type="text" id="det_txt_description"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['description']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_created_by">User</label></td><td><input type="text" id="det_txt_created_by"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php
                                    echo $row['Firstname'] . ' ';
                                    echo $row['Lastname'];
                                    ?>" /> </td></tr>
                            <tr><td><label for="det_txt_entry_date">entry date</label></td><td><input type="text" disabled id="det_txt_entry_date"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['entry_date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_chart_account">chart_account</label></td><td><input type="text" id="det_txt_chart_account"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['chart_account']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));              ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="p_budget_items">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_p_request($id) {
            //This one gets the p_request details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select p_request.p_request_id,  p_request.item,  p_request.quantity,  p_request.unit_cost,  p_request.amount,  p_request.entry_date,  p_request.User,  p_request.measurement,  p_request.request_no,user.Firstname,user.Lastname ,p_budget_items.item_name as item , measurement.code as measurement from p_request "
                        . " join measurement on measurement_id=p_request.measurement"
                        . " join user on user.StaffiD=p_request.User"
                        . " join p_budget_items on p_budget_items.p_budget_items_id=p_request.item"
                        . " where p_request.p_request_id=:id ";
                $stmt = $db->prepare($sql);
                //$budget_id = $type;
                $stmt->execute(array(":id" => $id));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var item = $('#det_txt_item').val();
                        var quantity = $('#det_txt_quantity').val();
                        var unit_cost = $('#det_txt_unit_cost').val();
                        var amount = $('#det_txt_amount').val();
                        var entry_date = $('#det_txt_entry_date').val();
                        var User = $('#det_txt_User').val();
                        var measurement = $('#det_txt_measurement').val();
                        var request_no = $('#det_txt_request_no').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, item: item, quantity: quantity, unit_cost: unit_cost, amount: amount, entry_date: entry_date, User: User, measurement: measurement, request_no: request_no}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        request Details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box">            <table class="clickable_row_table ">
                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                <td>                       <tr><td><label for="det_txt_item">item</label></td><td><input type="text" id="det_txt_item"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['item']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_quantity">quantity</label></td><td><input type="text" id="det_txt_quantity"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['quantity']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_measurement">measurement</label></td><td><input type="text" id="det_txt_measurement"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['measurement']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_unit_cost">unit cost</label></td><td><input type="text" id="det_txt_unit_cost"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo number_format($row['unit_cost']); ?>" /> </td></tr>
                            <tr><td><label for="det_txt_amount">amount</label></td><td><input type="text" id="det_txt_amount"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo number_format($row['amount']); ?>" /> </td></tr>
                            <tr><td><label for="det_txt_entry_date">entry date</label></td><td><input type="text" id="det_txt_entry_date"  class="details_txt white_bg no_bg" style="min-width: 300px;" disabled  value="<?php echo $row['entry_date']; ?>" /> </td></tr>
                            <tr><td><label for="det_txt_User">User</label></td><td><input type="text" id="det_txt_User"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php
                                    echo $row['Firstname'] . ' ';
                                    echo $row['Lastname'];
                                    ?>" /> </td></tr>
                            <tr><td><label for="det_txt_request_no">request_no</label></td><td><input type="text" id="det_txt_request_no"  class="details_txt white_bg no_bg" style="min-width: 300px;"  value="<?php echo $row['request_no']; ?>" /> </td></tr>
                            </td>
                            </tr>
                        <?php } ?>
                        <tr class="off">
                            <td colspan="7">
                                <span class="push_right data_total">
                                    <?php //echo 'Total ' . number_format($this->get_total_budget_line($budget_id));            ?>
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <button class="details_send_update link_cursor push_right" data-bind="p_request">Save Changes</button>
                            </td>
                        </tr>            </table>
                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function det_by_click_rep_budget($min, $max) {
            //This one gets the p_request details
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                require_once '../web_db/fin_books_sum_views.php';
                $fin = new fin_books_sum_views();
                $sql = $fin->journal_based_query();
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min_date" => $min, ":max_date" => $max));
                ?>
                <!--this is js-->
                <script>
                    $('.data_details_pane_close_btn').click(function () {
                        $('.data_details_pane').fadeOut(10);
                        $('.data_details_pane').html('');
                    });
                    $('.details_txt').focus(function () {
                        $(this).css('border', '1px solid #000');
                    });
                    $('.details_txt').focusout(function () {
                        $(this).css('border', 'none');
                    });
                    $('.details_send_update').click(function () {
                        var update_activity_details = $(this).data('bind');
                        //here goes the fiels assignments
                        var item = $('#det_txt_item').val();
                        var quantity = $('#det_txt_quantity').val();
                        var unit_cost = $('#det_txt_unit_cost').val();
                        var amount = $('#det_txt_amount').val();
                        var entry_date = $('#det_txt_entry_date').val();
                        var User = $('#det_txt_User').val();
                        var measurement = $('#det_txt_measurement').val();
                        var request_no = $('#det_txt_request_no').val();


                        $.post('../admin/handler_update_details.php', {update_activity_details: update_activity_details, item: item, quantity: quantity, unit_cost: unit_cost, amount: amount, entry_date: entry_date, User: User, measurement: measurement, request_no: request_no}, function (data) {
                            alert('reached: ' + data);
                        }).complete(function () {
                            alert('finished');
                        });
                    });
                    $('.details_txt').keyup(function () {
                        $('.details_send_update').fadeIn(100);
                    });
                </script>
                <!--ending js-->
                <div class="parts full_center_two_h heit_free margin_free details_box">
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                        Budget details
                    </div>
                    <div class="parts no_paddin_shade_no_Border no_shade_noBorder push_right data_details_pane_close_btn"></div>
                    <div class="parts  no_shade_noBorder data_details_pane_load">

                    </div>
                </div>
                <div class="clickable_row_table_box" style="width:90%; margin-left: 0px;">   
                    <div class="parts full_center_two_h heit_free no_paddin_shade_no_Border">
                        <div class="parts no_paddin_shade_no_Border">Expenses</div>
                        <div class="parts no_paddin_shade_no_Border">
                            <table  class="margin_free export_table">
                                <td>
                                    <form action="../web_exports/excel_export.php" target="blank" method="post">
                                        <input type="hidden" name="account" value="a"/>
                                        <input type="hidden" name="account" value="a"/>
                                        <input type="submit" name="export" class="btn_export  btn_export_excel margin_free" value="Export"/>
                                    </form>
                                </td>
                                <td>
                                    <form action="../print_more_reports/print_book_section.php" target="blank" method="post">
                                        <input type="hidden" name="account" value="<?php echo $min; ?>"/>
                                        <input type="hidden" name="account" value="<?php echo $max; ?>"/>
                                        <input type="hidden" name="acc_classid" value="<?php echo $classid; ?>"/>
                                        <input type="hidden" name="acc_classname" value="<?php echo $class; ?>"/>
                                        <input type="submit" name="export" class="btn_export  btn_export_pdf margin_free" value="Export"/>
                                    </form>
                                </td>
                            </table>

                        </div>
                    </div>
                    <div class="parts no_paddin_shade_no_Border">
                        <table class="report_percentage_tableview margin_free" style="margin-left: 0px;  border: 1px solid #000;float: left;">
                            <tr>
                                <td colspan="5"></td>
                                <td colspan="2" style="background-color: #50b2b0;">PREPARATION</td>
                                <td colspan="2" style="background-color: #5076b2;">IMPLEMENTATION</td>
                                <td colspan="2">VARIANCE</td>
                                <td colspan="2">G.P.: PERCENTAGE</td>
                            </tr>
                            <tr>
                                <td colspan="5"></td><td>Revenue</td><td>Expense</td> <td>Revenue</td><td>Expense</td><td>Revenue</td><td>Expense</td><td>Percentage</td>
                            </tr>
                            <tr>
                                <td>S/N</td><td>Budget</td> <td>Project</td><td>Activity</td><td>Amount</td><td>Amount</td><td>Amount</td><td>Amount</td><td>Amount</td><td>Amount</td><td>Amount</td><td>Percentage</td>
                            </tr>
                            <?php
                            $sum = 0;
                            while ($row = $stmt->fetch()) {
                                $sum += $row['p_act_amount'];
                                $prep_rev = $this->get_tot_project_by_type_by_id($db, $fin, $row['p_budget_prep_id'], 'revenue');
                                $prep_exp = $this->get_tot_project_by_type_by_id($db, $fin, $row['p_budget_prep_id'], 'expense');
                                $impl_rev = $this->get_impl_exp_rev($db, $fin, $row['p_budget_prep_id'], 'income');
                                $impl_exp = $this->get_impl_exp_rev($db, $fin, $row['p_budget_prep_id'], 'expense');
                                ?>
                                <tr> 
                                    <td><?php echo $row['p_type_project_id']; ?></td>
                                    <td><?php echo $row['prj_type']; ?></td>
                                    <td><?php echo $row['p_budget_prep_id'] . '- ' . $row['proj_name']; ?></td>
                                    <td><?php echo $row['p_act_name']; ?></td> 
                                    <td><?php echo $row['p_act_name']; ?></td> 
                                    <td><?php echo number_format($prep_rev); ?></td>
                                    <td><?php echo number_format($prep_exp); ?></td>
                                    <td><?php echo number_format($impl_rev); ?></td> 
                                    <td><?php echo number_format($impl_exp); ?></td>
                                    <td><?php echo number_format($prep_rev - $impl_rev); ?></td>
                                    <td><?php echo number_format($prep_exp - $impl_exp); ?></td>
                                    <td><?php echo $impl_exp / $prep_rev * 100 . '%'; ?></td>
                                </tr>
                            <?php } ?>
                            <tr class="">
                                <td colspan="8">
                                    <span class="push_right data_total">
                                        <?php echo 'Total: ' . number_format($sum); ?>
                                    </span>
                                </td>
                            </tr>
                            <tr class="off">
                                <td colspan="2">
                                    <button class="details_send_update link_cursor push_right" data-bind="p_request">Save Changes</button>
                                </td>
                            </tr>         

                        </table></div>

                </div>            <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        //Additional
        function det_by_click_type_project_sub($type) {//This one will be called as sub query
            //This one gets the projects, activities
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_budget_prep.p_budget_prep_id,  p_budget_prep.project_type,  p_budget_prep.user,  p_budget_prep.entry_date,  p_budget_prep.budget_type,  p_budget_prep.activity_desc, p_activity.amount,   p_budget_prep.name, user.Firstname, user,Lastname, p_type_project.name as type
                                            from p_budget_prep
                                            join user on p_budget_prep.user = user.StaffID 
                                            join p_type_project on p_budget_prep.project_type=p_type_project.p_type_project_id
                                            join p_activity on p_activity.project=p_budget_prep.p_budget_prep_id
                                            where p_type_project_id=:type   ";
            $stmt = $db->prepare($sql);
            $budget_id = $type;
            $stmt->execute(array(":type" => $type));
            ?>
            <!--this is js-->
            <script>
                $('.data_details_pane_close_btn').click(function () {
                    $('.data_details_pane').fadeOut(10);
                    $('.data_details_pane').html('');
                });
            </script>
            <!--ending js-->

            <div class="parts full_center_two_h heit_free margin_free details_box">
                <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title_smaller">
                    Activities of the Project
                </div>

                <div class="parts  no_shade_noBorder data_details_pane_load">

                </div>
            </div>
            <div class="parts sub_datatable_box no_paddin_shade_no_Border">
                <table class="dataList_table clickable_row_table sub_datatable">
                    <thead><tr><td> S/N </td>
                            <td> project </td><td> user </td>
                            <td> entry date </td>
                            <td> Budget type </td>
                            <td> activity desc </td>
                            <td> amount </td>
                            <td> name </td>
                        </tr></thead>
                    <?php while ($row = $stmt->fetch()) { ?><tr> 
                            <td><?php echo $row['p_budget_prep_id']; ?> </td>
                            <td><?php echo $row['type']; ?> </td>
                            <td><?php
                                echo $row['Firstname'] . '  ';
                                echo $row['Lastname']
                                ?> </td>
                            <td>        <?php echo $row['entry_date']; ?> </td>
                            <td>        <?php echo $row['budget_type']; ?> </td>
                            <td>        <?php echo $row['activity_desc']; ?> </td>
                            <td>        <?php echo number_format($row['amount']); ?> </td>
                            <td>        <?php echo $row['name']; ?> </td>

                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="7">
                            <span class="push_right data_total">
                                <?php echo 'Total ' . number_format($this->get_total_budget_line($budget_id)); ?>
                            </span>
                        </td>
                    </tr>
                </table> </div>
            <?php
        }

        //Totals
        function get_total_budget_line($budget) {
            $con = new dbconnection();
            $sql = "select sum(amount) as amount from p_activity 
                join   p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id 
                join p_type_project on p_budget_prep.project_type=p_type_project.p_type_project_id
                 where p_type_project.p_type_project_id=:id";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute(array(":id" => $budget));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $amount = $row['amount'];
            return $amount;
        }

        function get_tot_project_by_type_by_id($con, $fin, $project, $type) {//This query gets the tot base on budget_prep_id and budget_prep budget type (revenue or expense)
            $sql = $fin->get_proj_exp_rev_id($project, $type);
            $stmt = $con->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $amount = $row['sum'];
            return $amount;
        }

        function get_impl_exp_rev($con, $fin, $project, $type) {
            $sql = $fin->get_impl_byrev_exp_proj($type);
            $stmt = $con->prepare($sql);
            $stmt->execute(array(":project" => $project));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $amount = $row['tot'];
            return $amount;
        }

    }
    