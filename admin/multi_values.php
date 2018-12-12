<?php
    require_once '../web_db/connection.php';

    class multi_values {

        function list_account($start, $end) {
            try {
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "  select min(main_contra_account.sub_account) as sub,   min(account.account_id) as account_id, min(account.name) as name,  max(account_type.name) as type, sum(journal_entry_line.amount) as amount "
                        . " from account "
                        . " join account_type on account.acc_type=account_type.account_type_id  "
                        . " left join journal_entry_line on journal_entry_line.accountid =account.account_id "
                        . " join main_contra_account on main_contra_account.self_acc=account.account_id "
                        . " where account.is_contra_acc='yes' and main_contra_account.sub_account=0 "
                        . " group by account.account_id"
                        . "  order by  sub  asc  limit " . $start . " ," . $end;
                $stmt = $db->prepare($sql);
                $stmt->execute();
                ?>
                <table class="dataList_table">
                    <thead><tr>
                            <td> Name </td> 
                            <td> account type </td>
                            <td> Balance </td>
                            <?php if (isset($_SESSION['shall_delete'])) { ?>  
                                <td class=" delete_cols">Delete</td>
                                <td class=" update_cols">Update</td>
                            <?php } ?>
                        </tr>
                    </thead>
                    <?php
                    $pages = 1;
                    while ($row = $stmt->fetch()) {
                        ?><tr class="clickable_row" data-table_id="<?php echo $row['account_id']; ?>"     data-bind="account" > 
                            <td style="padding-left: 10px">
                                <?php echo $row['name']; ?>
                            </td>
                            <td class="acc_type_id_cols account " title="account" >
                                <?php echo $this->_e($row['type']); ?>
                            </td>
                            <td>
                                <?php echo number_format($row['amount']); ?>
                            </td>
                            <?php if (isset($_SESSION['shall_delete'])) { ?>
                                <td class="delete_cols">
                                    <a href="#" class="account_delete_link" style="color: #000080;" data-id_delete="<?php echo $row['account_id']; ?>"  data-table="account">Delete</a>
                                </td>
                                <td class="update_cols">
                                    <a href="#" class="account_update_link" style="color: #000080;" value="<?php echo $row['account_id']; ?>">Update</a>
                                </td>
                            <?php } ?></tr>
                        <?php
                        if ($this->get_if_acc_has_sub($row['account_id']) != 0) {
                            echo 'yes sub found';
                            $this->list_sub_by_mainaccount($row['account_id']);
                        }
                        $pages += 1;
                    }
                    ?></table>
                    <?php
                    $this->paging($this->All_account());
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            }

            function list_sub_by_mainaccount($account) {
                try {
                    $database = new dbconnection();
                    $db = $database->openConnection();
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = " select main_contra_account.acc_order as acc_order,account.name as account,  account_type.name as type, journal_entry_line.amount as amount "
                            . " from main_contra_account "
                            . " join account on account.account_id=main_contra_account.self_acc"
                            . " join account_type on account.acc_type=account_type.account_type_id "
                            . " left join journal_entry_line on journal_entry_line.accountid =account.account_id "
                            . " where main_contra_account.sub_account=:acc  "
                            . "  ";
                    $stmt = $db->prepare($sql);
                    $stmt->execute(array(":acc" => $account));
                    $pages = 1;
                    while ($row = $stmt->fetch()) {
                        ?><tr>
                        <td style="padding-left: <?php echo $row['acc_order'] * 11 . 'px' ?>">
                            <?php echo $row['account']; ?>
                        </td>
                        <td>
                            <?php echo $row['type']; ?>
                        </td>
                        <td>
                            <?php echo number_format($row['amount']); ?>
                        </td>
                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td class="delete_cols">
                                <a href="#" class="account_delete_link" style="color: #000080;" data-id_delete="<?php echo $row['account_id']; ?>"  data-table="account">Delete</a>
                            </td>
                            <td class="update_cols">
                                <a href="#" class="account_update_link" style="color: #000080;" value="<?php echo $row['account_id']; ?>">Update</a>
                            </td>
                        <?php } ?>
                    </tr>
                    <?php
                    $pages += 1;
                }
                ?> 
                <?php
//                    $this->paging($this->All_account());
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function get_if_acc_has_sub($acc) {
            $con = new dbconnection();
            $sql = " select count(main_contra_account.acc_order) as acc "
                    . " from main_contra_account "
                    . " join account on account.account_id=main_contra_account.self_acc"
                    . " join account_type on account.acc_type=account_type.account_type_id "
                    . " left join journal_entry_line on journal_entry_line.accountid =account.account_id "
                    . " where main_contra_account.sub_account=:acc "
                    . " order by account_id  asc ";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute(array(":acc" => $acc));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['acc'];
            return $first_rec;
        }

//chosen individual field
        function get_chosen_account_acc_type($id) {

            $db = new dbconnection();
            $sql = "select account.acc_type from account 
                    join account_type on account.acc_type=account_type.account_type_id
                where account.account_id=:account_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':account_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['acc_type'];
            echo $field;
        }

        function get_chosen_account_acc_class($id) {

            $db = new dbconnection();
            $sql = "select   account.acc_class from account where account_id=:account_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':account_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['acc_class'];
            echo $field;
        }

        function All_account() {
            $c = 0;
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  account_id   from account";
            foreach ($db->query($sql) as $row) {
                $c += 1;
            }
            return $c;
        }

        function get_first_account() {
            $con = new dbconnection();
            $sql = "select account.account_id from account
                    order by account.account_id asc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['account_id'];
            return $first_rec;
        }

        function get_last_account() {
            $con = new dbconnection();
            $sql = "select account.account_id from account
                    order by account.account_id desc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['account_id'];
            return $first_rec;
        }

        function list_account_type($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from account_type";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>
                        <td>Delete</td>
                        <td>Update</td>
                    </tr>
                </thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['account_type_id']; ?>
                        </td>


                        <td>
                            <a href="#" class="account_type_delete_link" style="color: #000080;" value="
                               <?php echo $row['account_type_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="account_type_update_link" style="color: #000080;" value="
                               <?php echo $row['account_type_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function get_sales_order_in_combo() {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "    select sales_order_line.sales_order_line_id, user.Firstname,user.Lastname,p_budget_items.item_name as item "
                        . " from sales_order_line "
                        . " join user on user.StaffID=sales_order_line.User"
                        . " join sales_quote_line on sales_order_line.quotationid=sales_quote_line.sales_quote_line_id"
                        . " join p_budget_items on sales_quote_line.item=p_budget_items.p_budget_items_id  "
                        . " where sales_order_line.sales_order_line_id not in (select sales_order from sales_invoice_line)";
                ?>
            <select class="textbox cbo_sales_order"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['sales_order_line_id'] . ">" . $row['sales_order_line_id'] . " -- " . $row['Firstname'] . "  " . $row['Lastname'] . "(" . $row['item'] . ") </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_chosen_account_name($id) {

            $db = new dbconnection();
            $sql = "select   account.name from account where account_id=:account_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':account_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['name'];
            echo $field;
        }

//chosen individual field


        function All_account_type() {
            $c = 0;
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  account_type_id   from account_type";
            foreach ($db->query($sql) as $row) {
                $c += 1;
            }
            return $c;
        }

        function get_first_account_type() {
            $con = new dbconnection();
            $sql = "select account_type.account_type_id from account_type
                    order by account_type.account_type_id asc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['account_type_id'];
            return $first_rec;
        }

        function get_last_account_type() {
            $con = new dbconnection();
            $sql = "select account_type.account_type_id from account_type
                    order by account_type.account_type_id desc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['account_type_id'];
            return $first_rec;
        }

        function list_ledger_settings($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from ledger_settings";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>

                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['ledger_settings_id']; ?>
                        </td>


                        <td>
                            <a href="#" class="ledger_settings_delete_link" style="color: #000080;" value="
                               <?php echo $row['ledger_settings_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="ledger_settings_update_link" style="color: #000080;" value="
                               <?php echo $row['ledger_settings_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function list_journal_entry_line($first, $last, $min, $max) {
                ?>
            <table class="journal_entry_table_view">
                <thead><tr>
                        <td> Type </td>
                        <td> S/N </td>
                        <td> Memo </td>
                        <td> Name </td>
                        <td class="off"> Journal Entry Header </td>
                        <td> entry_date </td>
                        <td class="off">Delete</td>
                        <td class="off">Update</td> 
                        <td> Account </td>
                        <td> Debit   </td>
                        <td> Credit   </td>
                    </tr>
                </thead>
                <?php
                try {
                    $database = new dbconnection();
                    require_once '../web_db/other_fx.php';
                    $ot = new other_fx();
                    $db = $database->openConnection();
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql_1 = "select account.account_id,min(journal_entry_line.dr_cr) as dr_cr, min(journal_entry_line.accountid) as account_id, min(journal_entry_line.journal_entry_line_id) as journal_entry_line_id  from journal_entry_line "
                            . " join account on account.account_id=journal_entry_line.accountid  "
                            . " and journal_entry_line.entry_date>=:min 
                              and journal_entry_line.entry_date<=:max "
                            . " group by account.account_id "
                            . " order by journal_entry_line_id asc";
                    $stmt_1 = $db->prepare($sql_1);
                    $stmt_1->execute(array(":min" => $min, ":max" => $max));
                    while ($row_1 = $stmt_1->fetch()) {
                        $sql = "select  journal_entry_line.journal_entry_line_id,min(party.name) as party, min(journal_entry_line.category) as category,    min(journal_entry_line.accountid) as accountid, min( journal_entry_line.dr_cr) as dr_cr,min(journal_entry_line.amount)as amount ,  min(journal_entry_line.memo) as memo, min( journal_entry_line.journal_entry_header) as journal_entry_header , min( journal_entry_line.entry_date) as entry_date,min(account.name) as accountid from journal_entry_line  "
                                . " join account on account.account_id=journal_entry_line.accountid "
                                . "  join journal_entry_header on journal_entry_line.journal_entry_header= journal_entry_header.journal_entry_header_id
                                      join party on party.party_id=journal_entry_header.party "
                                . " where account.account_id=:id "
                                . " and journal_entry_line.entry_date>=:min 
                                  and journal_entry_line.entry_date<=:max "
                                . "group by journal_entry_line.journal_entry_line_id "
                                . " "
                                . " "
                                . "  "
                                . "  ";
                        $stmt = $db->prepare($sql);
                        $stmt->execute(array(":id" => $row_1['account_id'], ":min" => $min, ":max" => $max));
                        $pages = 1;
                        while ($row = $stmt->fetch()) {
                            ?><tr class="journal_entry_line_update_link" data-bind="journal_entry_line" data-table_id="<?php echo $row['journal_entry_line_id']; ?>"> 
                                <td>  <?php echo $row['category']; ?>   </td>
                                <td>   <?php echo $row['journal_entry_line_id']; ?>  </td>
                                <td>   <?php echo $this->_e($row['memo']); ?>   </td>
                                <td>   <?php echo $this->_e($row['party']); ?>  </td>
                                <td class="off">  <?php echo $this->_e($row['journal_entry_header']); ?>  </td>
                                <td>  <?php echo $this->_e($row['entry_date']); ?>  </td>
                                <td class="off">  <a href="#" class="journal_entry_line_delete_link" style="color: #000080;" data-id_delete="<?php echo $row['journal_entry_line_id']; ?>"  data-table="journal_entry_line">Delete</a>  </td>
                                <td class="off">  <a href="#" class="journal_entry_line_update_link" style="color: #000080;" value="<?php echo $row['journal_entry_line_id']; ?>">Update</a>  </td>   
                                <td class="accountid_id_cols journal_entry_line " title="journal_entry_line" ><?php echo $this->_e($row['accountid']); ?>  </td>
                                <td class="text_right">
                                    <?php
                                    if ($row['dr_cr'] == 'Debit') {
                                        echo number_format($row['amount']) . '.00';
                                    }
                                    ?>
                                </td>
                                <td class="text_right">
                                    <?php
                                    if ($row['dr_cr'] == 'Credit') {
                                        echo ($row['amount'] < 1) ? -1 * number_format($row['amount']) . '.00' : number_format($row['amount']) . '.00';
                                    }
                                    ?>
                                </td></tr>
                            <?php
                            $pages += 1;
                        }
                        ?><tr class="row_bold text_right row_double_underline"><td colspan="6">

                            </td>    
                            <td class="row_no_border">
                                <span class="parts push_right no_shade_noBorder single_underline no_padding">
                                    <?php echo number_format($ot->get_acc_sum('debit', $row_1['account_id'], $min, $max)); ?>
                                </span></td>
                            <td class="row_no_border">
                                <span class="parts push_right no_shade_noBorder single_underline no_padding">
                                    <?php echo number_format($ot->get_acc_sum('credit', $row_1['account_id'], $min, $max)); ?>
                                </span></td>
                        </tr>

                        <?php
                    }
                    ?>   <tr class="row_bold text_right row_double_underline journal_entry_table_view_last_row"><td colspan="6" class="row_no_border">

                        <td><span class="parts push_right no_shade_noBorder double_underline">
                                <?php echo number_format($ot->get_debit_sum_by_date('debit', $min, $max)); ?>
                            </span></td>
                        <td><span class="parts push_right no_shade_noBorder double_underline">
                                <?php echo number_format($ot->get_debit_sum_by_date('credit', $min, $max)); ?>
                            </span></td>

                    </tr>
                </table>


                <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

//chosen individual field


        function All_ledger_settings() {
            $c = 0;
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  ledger_settings_id   from ledger_settings";
            foreach ($db->query($sql) as $row) {
                $c += 1;
            }
            return $c;
        }

        function get_first_ledger_settings() {
            $con = new dbconnection();
            $sql = "select ledger_settings.ledger_settings_id from ledger_settings
                    order by ledger_settings.ledger_settings_id asc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['ledger_settings_id'];
            return $first_rec;
        }

        function get_last_ledger_settings() {
            $con = new dbconnection();
            $sql = "select ledger_settings.ledger_settings_id from ledger_settings
                    order by ledger_settings.ledger_settings_id desc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['ledger_settings_id'];
            return $first_rec;
        }

        function list_bank($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from bank";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>
                        <td> Name</td>  
                        <td> Account </td>
                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>Delete</td>
                            <td>Update</td><?php } ?></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['bank_id']; ?>
                        </td>
                        <td class="account_id_cols bank " title="bank" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>  <td class="account_id_cols bank " title="bank" >
                            <?php echo $this->_e($row['account']); ?>
                        </td>


                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>
                                <a href="#" class="bank_delete_link" style="color: #000080;" value="
                                   <?php echo $row['bank_id']; ?>">Delete</a>
                            </td>
                            <td>
                                <a href="#" class="bank_update_link" style="color: #000080;" value="
                                   <?php echo $row['bank_id']; ?>">Update</a>
                            </td><?php } ?></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_bank_account($id) {

                $db = new dbconnection();
                $sql = "select   bank.account from bank where bank_id=:bank_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':bank_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['account'];
                echo $field;
            }

            function All_bank() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  bank_id   from bank";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_bank() {
                $con = new dbconnection();
                $sql = "select bank.bank_id from bank
                    order by bank.bank_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['bank_id'];
                return $first_rec;
            }

            function get_last_bank() {
                $con = new dbconnection();
                $sql = "select bank.bank_id from bank
                    order by bank.bank_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['bank_id'];
                return $first_rec;
            }

            function list_account_class($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from account_class";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>

                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['account_class_id']; ?>
                        </td>


                        <td>
                            <a href="#" class="account_class_delete_link" style="color: #000080;" value="
                               <?php echo $row['account_class_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="account_class_update_link" style="color: #000080;" value="
                               <?php echo $row['account_class_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function All_account_class() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  account_class_id   from account_class";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_account_class() {
                $con = new dbconnection();
                $sql = "select account_class.account_class_id from account_class
                    order by account_class.account_class_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['account_class_id'];
                return $first_rec;
            }

            function get_last_account_class() {
                $con = new dbconnection();
                $sql = "select account_class.account_class_id from account_class
                    order by account_class.account_class_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['account_class_id'];
                return $first_rec;
            }

//chosen individual field
            function get_chosen_general_ledger_line_general_ledge_header($id) {

                $db = new dbconnection();
                $sql = "select   general_ledger_line.general_ledge_header from general_ledger_line where general_ledger_line_id=:general_ledger_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':general_ledger_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['general_ledge_header'];
                echo $field;
            }

            function get_chosen_general_ledger_line_accountid($id) {

                $db = new dbconnection();
                $sql = "select   general_ledger_line.accountid from general_ledger_line where general_ledger_line_id=:general_ledger_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':general_ledger_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['accountid'];
                echo $field;
            }

            function All_general_ledger_line() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  general_ledger_line_id   from general_ledger_line";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_general_ledger_line() {
                $con = new dbconnection();
                $sql = "select general_ledger_line.general_ledger_line_id from general_ledger_line
                    order by general_ledger_line.general_ledger_line_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['general_ledger_line_id'];
                return $first_rec;
            }

            function get_last_general_ledger_line() {
                $con = new dbconnection();
                $sql = "select general_ledger_line.general_ledger_line_id from general_ledger_line
                    order by general_ledger_line.general_ledger_line_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['general_ledger_line_id'];
                return $first_rec;
            }

            function list_main_contra_account($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from main_contra_account";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table" border="1">
                <thead><tr>

                        <td> S/N </td>
                        <td> Main Contra Account </td><td> Related Contra Account </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 
                        <td>
                            <?php echo $row['main_contra_account_id']; ?>
                        </td>
                        <td class="main_contra_acc_id_cols main_contra_account" style="padding-left: <?php echo 20 * $row['acc_order'] . 'px;'; ?>" title="main_contra_account" >
                            <?php echo $this->_e($row['main_account']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['sub_account']); ?>
                        </td>
                        <td>
                            <a href="#" class="main_contra_account_delete_link" style="color: #000080;" value="
                               <?php echo $row['main_contra_account_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="main_contra_account_update_link" style="color: #000080;" value="
                               <?php echo $row['main_contra_account_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_main_contra_account_main_contra_acc($id) {

                $db = new dbconnection();
                $sql = "select   main_contra_account.main_contra_acc from main_contra_account where main_contra_account_id=:main_contra_account_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':main_contra_account_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['main_contra_acc'];
                echo $field;
            }

            function get_chosen_main_contra_account_related_contra_acc($id) {

                $db = new dbconnection();
                $sql = "select   main_contra_account.related_contra_acc from main_contra_account where main_contra_account_id=:main_contra_account_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':main_contra_account_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['related_contra_acc'];
                echo $field;
            }

            function All_main_contra_account() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  main_contra_account_id   from main_contra_account";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_main_contra_account() {
                $con = new dbconnection();
                $sql = "select main_contra_account.main_contra_account_id from main_contra_account
                    order by main_contra_account.main_contra_account_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['main_contra_account_id'];
                return $first_rec;
            }

            function get_last_main_contra_account() {
                $con = new dbconnection();
                $sql = "select main_contra_account.main_contra_account_id from main_contra_account
                    order by main_contra_account.main_contra_account_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['main_contra_account_id'];
                return $first_rec;
            }

            function list_sales_receit_header($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select sales_receit_header.sales_receit_header_id,  sales_receit_header.sales_invoice,  sales_receit_header.quantity,  sales_receit_header.unit_cost,  sales_receit_header.amount,  sales_receit_header.entry_date,  sales_receit_header.User,  sales_receit_header.client,    sales_receit_header.budget_prep,  sales_receit_header.account,p_budget_items.item_name as item "
                        . ",user.Firstname,user.Lastname   from sales_receit_header "
                        . " join user on user.StaffID= sales_receit_header.User "
                        . " join sales_invoice_line on sales_receit_header.sales_invoice=sales_invoice_line.sales_invoice_line_id
                            join sales_order_line on sales_invoice_line.sales_order= sales_order_line.sales_order_line_id
                            join sales_quote_line on sales_order_line.quotationid=sales_quote_line.sales_quote_line_id 
                            join p_budget_items on sales_quote_line.item=p_budget_items.p_budget_items_id  "
                        . "";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>
                        <td> Item </td>
                        <td class="off"> Sales Invoice </td>
                        <td> Unit Cost </td>  <td> Quantity </td> 
                        <td> Amount </td>
                        <td class="off"> Sales Invoice </td>
                        <td> Entry Date </td> 
                        <td> User </td>
                        <?php if (isset($_SESSION['shall_delete'])) { ?> <td>Delete</td><td>Update</td><?php } ?></tr></thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr class="clickable_row" data-table_id="<?php echo $row['sales_receit_header_id']; ?>"     data-bind="sales_receit_header"> 

                        <td>
                            <?php echo $row['sales_receit_header_id']; ?>
                        </td>
                        <td>
                            <?php echo $row['item']; ?>
                        </td>
                        <td class="sales_invoice_id_cols sales_receit_header off" title="sales_receit_header" >
                            <?php echo $this->_e($row['sales_invoice']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['unit_cost']); ?>
                        </td>




                        <td>
                            <?php echo $this->_e($row['quantity']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['amount']); ?>
                        </td> 
                        <td>
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['sales_invoice']); ?>
                        </td>

                        <td>
                            <?php
                            echo $this->_e($row['Firstname']) . '  ';
                            echo $this->_e($row['Lastname']);
                            ?>
                        </td>

                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>
                                <a href="#" class="sales_receit_header_delete_link" style="color: #000080;" data-id_delete="sales_receit_header_id"  data-table="
                                   <?php echo $row['sales_receit_header_id']; ?>">Delete</a>
                            </td>
                            <td>
                                <a href="#" class="sales_receit_header_update_link" style="color: #000080;" value="
                                   <?php echo $row['sales_receit_header_id']; ?>">Update</a>
                            </td><?php } ?></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_sales_receit_header_customerid($id) {

                $db = new dbconnection();
                $sql = "select   sales_receit_header.customerid from sales_receit_header where sales_receit_header_id=:sales_receit_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_receit_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['customerid'];
                echo $field;
            }

            function get_chosen_sales_receit_header_general_ledger_header($id) {

                $db = new dbconnection();
                $sql = "select   sales_receit_header.general_ledger_header from sales_receit_header where sales_receit_header_id=:sales_receit_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_receit_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['general_ledger_header'];
                echo $field;
            }

            function get_chosen_sales_receit_header_status($id) {

                $db = new dbconnection();
                $sql = "select   sales_receit_header.status from sales_receit_header where sales_receit_header_id=:sales_receit_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_receit_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['status'];
                echo $field;
            }

            function get_chosen_sales_receit_header_customer($id) {

                $db = new dbconnection();
                $sql = "select   sales_receit_header.customer from sales_receit_header where sales_receit_header_id=:sales_receit_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_receit_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['customer'];
                echo $field;
            }

            function get_chosen_sales_receit_header_gen_ledger_header($id) {

                $db = new dbconnection();
                $sql = "select   sales_receit_header.gen_ledger_header from sales_receit_header where sales_receit_header_id=:sales_receit_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_receit_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['gen_ledger_header'];
                echo $field;
            }

            function get_chosen_sales_receit_header_account($id) {

                $db = new dbconnection();
                $sql = "select   sales_receit_header.account from sales_receit_header where sales_receit_header_id=:sales_receit_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_receit_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['account'];
                echo $field;
            }

            function get_chosen_sales_receit_header_date($id) {

                $db = new dbconnection();
                $sql = "select   sales_receit_header.date from sales_receit_header where sales_receit_header_id=:sales_receit_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_receit_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['date'];
                echo $field;
            }

            function All_sales_receit_header() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  sales_receit_header_id   from sales_receit_header";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_sales_receit_header() {
                $con = new dbconnection();
                $sql = "select sales_receit_header.sales_receit_header_id from sales_receit_header
                    order by sales_receit_header.sales_receit_header_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sales_receit_header_id'];
                return $first_rec;
            }

            function get_last_sales_receit_header() {
                $con = new dbconnection();
                $sql = "select sales_receit_header.sales_receit_header_id from sales_receit_header
                    order by sales_receit_header.sales_receit_header_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sales_receit_header_id'];
                return $first_rec;
            }

            function list_measurement($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from measurement";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Code </td><td> Description </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['measurement_id']; ?>
                        </td>
                        <td class="code_id_cols measurement " title="measurement" >
                            <?php echo $this->_e($row['code']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['description']); ?>
                        </td>


                        <td>
                            <a href="#" class="measurement_delete_link" style="color: #000080;" value="
                               <?php echo $row['measurement_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="measurement_update_link" style="color: #000080;" value="
                               <?php echo $row['measurement_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_measurement_code($id) {

                $db = new dbconnection();
                $sql = "select   measurement.code from measurement where measurement_id=:measurement_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':measurement_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['code'];
                echo $field;
            }

            function get_chosen_measurement_description($id) {

                $db = new dbconnection();
                $sql = "select   measurement.description from measurement where measurement_id=:measurement_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':measurement_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['description'];
                echo $field;
            }

            function All_measurement() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  measurement_id   from measurement";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_measurement() {
                $con = new dbconnection();
                $sql = "select measurement.measurement_id from measurement
                    order by measurement.measurement_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['measurement_id'];
                return $first_rec;
            }

            function get_last_measurement() {
                $con = new dbconnection();
                $sql = "select measurement.measurement_id from measurement
                    order by measurement.measurement_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['measurement_id'];
                return $first_rec;
            }

//chosen individual field
            function get_chosen_journal_entry_line_accountid($id) {

                $db = new dbconnection();
                $sql = "select   journal_entry_line.accountid from journal_entry_line where journal_entry_line_id=:journal_entry_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':journal_entry_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['accountid'];
                echo $field;
            }

            function get_chosen_journal_entry_line_dr_cr($id) {

                $db = new dbconnection();
                $sql = "select   journal_entry_line.dr_cr from journal_entry_line where journal_entry_line_id=:journal_entry_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':journal_entry_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['dr_cr'];
                echo $field;
            }

            function get_chosen_journal_entry_line_amount($id) {

                $db = new dbconnection();
                $sql = "select   journal_entry_line.amount from journal_entry_line where journal_entry_line_id=:journal_entry_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':journal_entry_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['amount'];
                echo $field;
            }

            function get_chosen_journal_entry_line_memo($id) {

                $db = new dbconnection();
                $sql = "select   journal_entry_line.memo from journal_entry_line where journal_entry_line_id=:journal_entry_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':journal_entry_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['memo'];
                echo $field;
            }

            function get_chosen_journal_entry_line_journal_entry_header($id) {

                $db = new dbconnection();
                $sql = "select   journal_entry_line.journal_entry_header from journal_entry_line where journal_entry_line_id=:journal_entry_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':journal_entry_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['journal_entry_header'];
                echo $field;
            }

            function All_journal_entry_line() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  journal_entry_line_id   from journal_entry_line";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_journal_entry_line() {
                $con = new dbconnection();
                $sql = "select journal_entry_line.journal_entry_line_id from journal_entry_line
                    order by journal_entry_line.journal_entry_line_id asc
                    limit 1 ";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['journal_entry_line_id'];
                return $first_rec;
            }

            function get_last_journal_entry_line() {
                $con = new dbconnection();
                $sql = "select journal_entry_line.journal_entry_line_id from journal_entry_line
                    order by journal_entry_line.journal_entry_line_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['journal_entry_line_id'];
                return $first_rec;
            }

            function list_tax($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from tax";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Sales Account  </td><td> Purchase Account </td><td> Tax Name </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['tax_id']; ?>
                        </td>
                        <td class="sales_accid_id_cols tax " title="tax" >
                            <?php echo $this->_e($row['sales_accid']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['purchase_accid']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['tax_name']); ?>
                        </td>


                        <td>
                            <a href="#" class="tax_delete_link" style="color: #000080;" value="
                               <?php echo $row['tax_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="tax_update_link" style="color: #000080;" value="
                               <?php echo $row['tax_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_tax_sales_accid($id) {

                $db = new dbconnection();
                $sql = "select   tax.sales_accid from tax where tax_id=:tax_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':tax_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['sales_accid'];
                echo $field;
            }

            function get_chosen_tax_purchase_accid($id) {

                $db = new dbconnection();
                $sql = "select   tax.purchase_accid from tax where tax_id=:tax_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':tax_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['purchase_accid'];
                echo $field;
            }

            function get_chosen_tax_tax_name($id) {

                $db = new dbconnection();
                $sql = "select   tax.tax_name from tax where tax_id=:tax_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':tax_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['tax_name'];
                echo $field;
            }

            function All_tax() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  tax_id   from tax";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_tax() {
                $con = new dbconnection();
                $sql = "select tax.tax_id from tax
                    order by tax.tax_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['tax_id'];
                return $first_rec;
            }

            function get_last_tax() {
                $con = new dbconnection();
                $sql = "select tax.tax_id from tax
                    order by tax.tax_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['tax_id'];
                return $first_rec;
            }

            function list_vendor($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select vendor.vendor_id,  party.name,  party.email,  party.website,  party.phone, vendor.primary_contact from vendor 
                    join party on vendor.party = party.party_id 
                    where party.party_type='supplier'";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead> <td> S/N </td>
                <td> name </td>
                <td> email </td>
                <td> website </td>
                <td> phone </td>
                <td> primary_contact </td>
                <?php if (isset($_SESSION['shall_delete'])) { ?>  <td> Delete </td>
                    <td> Update </td><?php } ?>
            </thead>
            <?php
            $pages = 1;
            while ($row = $stmt->fetch()) {
                ?><tr class="clickable_row" data-table_id="<?php echo $row['vendor_id']; ?>"     data-bind="vendor"> 
                    <td>        <?php echo $row['vendor_id']; ?> </td>
                    <td>        <?php echo $row['name']; ?> </td>
                    <td>        <?php echo $row['email']; ?> </td>
                    <td>        <?php echo $row['website']; ?> </td>
                    <td>        <?php echo $row['phone']; ?> </td>
                    <td>        <?php echo $row['primary_contact']; ?> </td>
                    <?php if (isset($_SESSION['shall_delete'])) { ?> 
                        <td>      <a href="#" class="dele_upd_link">Delete</a> </td>
                        <td>       <a href="#" class="dele_upd_link">Update</a> </td>
                    <?php } ?>
                </tr>
                <?php
                $pages += 1;
            }
            ?></table>
            <?php
            $this->paging($this->All_vendor());
        }

        //chosen individual field
        function get_chosen_vendor_venndor_number($id) {

            $db = new dbconnection();
            $sql = "select   vendor.venndor_number from vendor where vendor_id=:vendor_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':vendor_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['venndor_number'];
            echo $field;
        }

        function get_chosen_vendor_party($id) {

            $db = new dbconnection();
            $sql = "select   vendor.party from vendor where vendor_id=:vendor_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':vendor_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['party'];
            echo $field;
        }

        function get_chosen_vendor_payment_term($id) {

            $db = new dbconnection();
            $sql = "select   vendor.payment_term from vendor where vendor_id=:vendor_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':vendor_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['payment_term'];
            echo $field;
        }

        function get_chosen_vendor_tax_group($id) {

            $db = new dbconnection();
            $sql = "select   vendor.tax_group from vendor where vendor_id=:vendor_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':vendor_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['tax_group'];
            echo $field;
        }

        function get_chosen_vendor_purchase_acc($id) {

            $db = new dbconnection();
            $sql = "select   vendor.purchase_acc from vendor where vendor_id=:vendor_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':vendor_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['purchase_acc'];
            echo $field;
        }

        function get_chosen_vendor_pur_discount_accid($id) {

            $db = new dbconnection();
            $sql = "select   vendor.pur_discount_accid from vendor where vendor_id=:vendor_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':vendor_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['pur_discount_accid'];
            echo $field;
        }

        function get_chosen_vendor_primary_contact($id) {

            $db = new dbconnection();
            $sql = "select   vendor.primary_contact from vendor where vendor_id=:vendor_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':vendor_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['primary_contact'];
            echo $field;
        }

        function get_chosen_vendor_acc_payble($id) {

            $db = new dbconnection();
            $sql = "select   vendor.acc_payble from vendor where vendor_id=:vendor_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':vendor_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['acc_payble'];
            echo $field;
        }

        function All_vendor() {
            $c = 0;
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  vendor_id   from vendor";
            foreach ($db->query($sql) as $row) {
                $c += 1;
            }
            return $c;
        }

        function get_first_vendor() {
            $con = new dbconnection();
            $sql = "select vendor.vendor_id from vendor
                    order by vendor.vendor_id asc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['vendor_id'];
            return $first_rec;
        }

        function get_last_vendor() {
            $con = new dbconnection();
            $sql = "select vendor.vendor_id from vendor
                    order by vendor.vendor_id desc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['vendor_id'];
            return $first_rec;
        }

        function list_general_ledger_header($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from general_ledger_header";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Date </td><td> Document Type </td><td> Description </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['general_ledger_header_id']; ?>
                        </td>
                        <td class="date_id_cols general_ledger_header " title="general_ledger_header" >
                            <?php echo $this->_e($row['date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['doc_type']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['desc']); ?>
                        </td>


                        <td>
                            <a href="#" class="general_ledger_header_delete_link" style="color: #000080;" value="
                               <?php echo $row['general_ledger_header_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="general_ledger_header_update_link" style="color: #000080;" value="
                               <?php echo $row['general_ledger_header_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_general_ledger_header_date($id) {

                $db = new dbconnection();
                $sql = "select   general_ledger_header.date from general_ledger_header where general_ledger_header_id=:general_ledger_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':general_ledger_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['date'];
                echo $field;
            }

            function get_chosen_general_ledger_header_doc_type($id) {

                $db = new dbconnection();
                $sql = "select   general_ledger_header.doc_type from general_ledger_header where general_ledger_header_id=:general_ledger_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':general_ledger_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['doc_type'];
                echo $field;
            }

            function get_chosen_general_ledger_header_desc($id) {

                $db = new dbconnection();
                $sql = "select   general_ledger_header.desc from general_ledger_header where general_ledger_header_id=:general_ledger_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':general_ledger_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['desc'];
                echo $field;
            }

            function All_general_ledger_header() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  general_ledger_header_id   from general_ledger_header";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_general_ledger_header() {
                $con = new dbconnection();
                $sql = "select general_ledger_header.general_ledger_header_id from general_ledger_header
                    order by general_ledger_header.general_ledger_header_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['general_ledger_header_id'];
                return $first_rec;
            }

            function get_last_general_ledger_header() {
                $con = new dbconnection();
                $sql = "select general_ledger_header.general_ledger_header_id from general_ledger_header
                    order by general_ledger_header.general_ledger_header_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['general_ledger_header_id'];
                return $first_rec;
            }

            function list_party($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from party";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Party Type </td><td> Name </td><td> Email </td><td> website </td><td> Phone </td><td> Is Active </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['party_id']; ?>
                        </td>
                        <td class="party_type_id_cols party " title="party" >
                            <?php echo $this->_e($row['party_type']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['name']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['email']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['website']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['phone']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['is_active']); ?>
                        </td>


                        <td>
                            <a href="#" class="party_delete_link" style="color: #000080;" value="
                               <?php echo $row['party_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="party_update_link" style="color: #000080;" value="
                               <?php echo $row['party_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_party_party_type($id) {

                $db = new dbconnection();
                $sql = "select   party.party_type from party where party_id=:party_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':party_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['party_type'];
                echo $field;
            }

            function get_chosen_party_name($id) {

                $db = new dbconnection();
                $sql = "select   party.name from party where party_id=:party_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':party_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['name'];
                echo $field;
            }

            function get_chosen_party_email($id) {

                $db = new dbconnection();
                $sql = "select   party.email from party where party_id=:party_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':party_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['email'];
                echo $field;
            }

            function get_chosen_party_website($id) {

                $db = new dbconnection();
                $sql = "select   party.website from party where party_id=:party_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':party_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['website'];
                echo $field;
            }

            function get_chosen_party_phone($id) {

                $db = new dbconnection();
                $sql = "select   party.phone from party where party_id=:party_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':party_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['phone'];
                echo $field;
            }

            function get_chosen_party_is_active($id) {

                $db = new dbconnection();
                $sql = "select   party.is_active from party where party_id=:party_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':party_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['is_active'];
                echo $field;
            }

            function All_party() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  party_id   from party";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_party() {
                $con = new dbconnection();
                $sql = "select party.party_id from party
                    order by party.party_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['party_id'];
                return $first_rec;
            }

            function get_last_party() {
                $con = new dbconnection();
                $sql = "select party.party_id from party
                    order by party.party_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['party_id'];
                return $first_rec;
            }

            function list_contact($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from contact";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> party </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['contact_id']; ?>
                        </td>
                        <td class="party_id_cols contact " title="contact" >
                            <?php echo $this->_e($row['party']); ?>
                        </td>


                        <td>
                            <a href="#" class="contact_delete_link" style="color: #000080;" value="
                               <?php echo $row['contact_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="contact_update_link" style="color: #000080;" value="
                               <?php echo $row['contact_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_contact_party($id) {

                $db = new dbconnection();
                $sql = "select   contact.party from contact where contact_id=:contact_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':contact_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['party'];
                echo $field;
            }

            function All_contact() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  contact_id   from contact";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_contact() {
                $con = new dbconnection();
                $sql = "select contact.contact_id from contact
                    order by contact.contact_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['contact_id'];
                return $first_rec;
            }

            function get_last_contact() {
                $con = new dbconnection();
                $sql = "select contact.contact_id from contact
                    order by contact.contact_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['contact_id'];
                return $first_rec;
            }

            function list_customer($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select customer.customer_id,  customer.contact ,party.name,  party.email,  party.website,  party.phone"
                        . "     from customer  "
                        . " join party on party.party_id=customer.party_id "
                        . " where party.party_type='customer'";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Names </td>
                        <td> Phone </td>
                        <td> Email </td>
                        <td> Website </td>
                        <td class="off"> Payment Term </td>
                        <td class="off"> Sales Account </td>

                        <td class="off"> Prompt payment Disctount  </td>
                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>Delete</td>
                            <td>Update</td><?php } ?>
                    </tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr class="clickable_row" data-table_id="<?php echo $row['customer_id']; ?>"     data-bind="customer"> 

                        <td>
                            <?php echo $row['customer_id']; ?>
                        </td>
                        <td class="party_id_id_cols customer " title="customer" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>

                        <td>
                            <?php echo $this->_e($row['phone']); ?>
                        </td> 
                        <td class="party_id_id_cols customer " title="customer" >
                            <?php echo $this->_e($row['email']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['website']); ?>
                        </td>
                        <td class="off">
                            <?php // echo $this->_e($row['tax_group']);                          ?>
                        </td>
                        <td class="off">
                            <?php // echo $this->_e($row['payment_term']);                          ?>
                        </td>
                        <td class="off">
                            <?php // echo $this->_e($row['sales_accid']);                          ?>
                        </td>
                        <td class="off">
                            <?php //echo $this->_e($row['acc_rec_accid']);                          ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['promp_pyt_disc_accid']); ?>
                        </td>
                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>
                                <a href="#" class="customer_delete_link" style="color: #000080;" value="
                                   <?php echo $row['customer_id']; ?>">Delete</a>
                            </td>
                            <td>
                                <a href="#" class="customer_update_link" style="color: #000080;" value="
                                   <?php echo $row['customer_id']; ?>">Update</a>
                            </td><?php } ?>
                    </tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
            <?php
            $this->paging($this->All_customer());
        }

//chosen individual field
        function get_chosen_customer_party_id($id) {

            $db = new dbconnection();
            $sql = "select   customer.party_id from customer where customer_id=:customer_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':customer_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['party_id'];
            echo $field;
        }

        function get_chosen_customer_contact($id) {

            $db = new dbconnection();
            $sql = "select   customer.contact from customer where customer_id=:customer_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':customer_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['contact'];
            echo $field;
        }

        function get_chosen_customer_number($id) {

            $db = new dbconnection();
            $sql = "select   customer.number from customer where customer_id=:customer_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':customer_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['number'];
            echo $field;
        }

        function get_chosen_customer_tax_group($id) {

            $db = new dbconnection();
            $sql = "select   customer.tax_group from customer where customer_id=:customer_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':customer_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['tax_group'];
            echo $field;
        }

        function get_chosen_customer_payment_term($id) {

            $db = new dbconnection();
            $sql = "select   customer.payment_term from customer where customer_id=:customer_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':customer_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['payment_term'];
            echo $field;
        }

        function get_chosen_customer_sales_accid($id) {

            $db = new dbconnection();
            $sql = "select   customer.sales_accid from customer where customer_id=:customer_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':customer_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['sales_accid'];
            echo $field;
        }

        function get_chosen_customer_acc_rec_accid($id) {

            $db = new dbconnection();
            $sql = "select   customer.acc_rec_accid from customer where customer_id=:customer_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':customer_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['acc_rec_accid'];
            echo $field;
        }

        function get_chosen_customer_promp_pyt_disc_accid($id) {

            $db = new dbconnection();
            $sql = "select   customer.promp_pyt_disc_accid from customer where customer_id=:customer_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':customer_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['promp_pyt_disc_accid'];
            echo $field;
        }

        function All_customer() {
            $c = 0;
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  customer_id   from customer";
            foreach ($db->query($sql) as $row) {
                $c += 1;
            }
            return $c;
        }

        function get_first_customer() {
            $con = new dbconnection();
            $sql = "select customer.customer_id from customer
                    order by customer.customer_id asc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['customer_id'];
            return $first_rec;
        }

        function get_last_customer() {
            $con = new dbconnection();
            $sql = "select customer.customer_id from customer
                    order by customer.customer_id desc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['customer_id'];
            return $first_rec;
        }

        function list_taxgroup($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from taxgroup";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Description </td>
                        <td> Sales/Purchase </td>
                        <td class="off"> Tax Applied </td>
                        <td class="off"> Is Active </td>
                        <?php if (isset($_SESSION['shall_delete'])) { ?><td>Delete</td><td>Update</td><?php } ?></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['taxgroup_id']; ?>
                        </td>
                        <td class="description_id_cols taxgroup " title="taxgroup" >
                            <?php echo $this->_e($row['description']); ?>
                        </td>
                        <td  title="taxgroup" >
                            <?php echo $this->_e($row['pur_sale']); ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['tax_applied']); ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['is_active']); ?>
                        </td>

                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>
                                <a href="#" class="taxgroup_delete_link" style="color: #000080;" value="
                                   <?php echo $row['taxgroup_id']; ?>">Delete</a>
                            </td>
                            <td>
                                <a href="#" class="taxgroup_update_link" style="color: #000080;" value="
                                   <?php echo $row['taxgroup_id']; ?>">Update</a>
                            </td><?php } ?></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_taxgroup_description($id) {

                $db = new dbconnection();
                $sql = "select   taxgroup.description from taxgroup where taxgroup_id=:taxgroup_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':taxgroup_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['description'];
                echo $field;
            }

            function get_chosen_taxgroup_tax_applied($id) {

                $db = new dbconnection();
                $sql = "select   taxgroup.tax_applied from taxgroup where taxgroup_id=:taxgroup_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':taxgroup_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['tax_applied'];
                echo $field;
            }

            function get_chosen_taxgroup_is_active($id) {

                $db = new dbconnection();
                $sql = "select   taxgroup.is_active from taxgroup where taxgroup_id=:taxgroup_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':taxgroup_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['is_active'];
                echo $field;
            }

            function All_taxgroup() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  taxgroup_id   from taxgroup";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_taxgroup() {
                $con = new dbconnection();
                $sql = "select taxgroup.taxgroup_id from taxgroup
                    order by taxgroup.taxgroup_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['taxgroup_id'];
                return $first_rec;
            }

            function get_last_taxgroup() {
                $con = new dbconnection();
                $sql = "select taxgroup.taxgroup_id from taxgroup
                    order by taxgroup.taxgroup_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['taxgroup_id'];
                return $first_rec;
            }

            function list_journal_entry_header($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from journal_entry_header";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Party </td><td> Voucher Type </td><td> Date </td><td> Memo </td><td> Reference Number </td><td> Posted </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['journal_entry_header_id']; ?>
                        </td>
                        <td class="party_id_cols journal_entry_header " title="journal_entry_header" >
                            <?php echo $this->_e($row['party']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['voucher_type']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['memo']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['reference_number']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['posted']); ?>
                        </td>


                        <td>
                            <a href="#" class="journal_entry_header_delete_link" style="color: #000080;" value="
                               <?php echo $row['journal_entry_header_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="journal_entry_header_update_link" style="color: #000080;" value="
                               <?php echo $row['journal_entry_header_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_journal_entry_header_party($id) {

                $db = new dbconnection();
                $sql = "select   journal_entry_header.party from journal_entry_header where journal_entry_header_id=:journal_entry_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':journal_entry_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['party'];
                echo $field;
            }

            function get_chosen_journal_entry_header_voucher_type($id) {

                $db = new dbconnection();
                $sql = "select   journal_entry_header.voucher_type from journal_entry_header where journal_entry_header_id=:journal_entry_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':journal_entry_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['voucher_type'];
                echo $field;
            }

            function get_chosen_journal_entry_header_date($id) {

                $db = new dbconnection();
                $sql = "select   journal_entry_header.date from journal_entry_header where journal_entry_header_id=:journal_entry_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':journal_entry_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['date'];
                echo $field;
            }

            function get_chosen_journal_entry_header_memo($id) {

                $db = new dbconnection();
                $sql = "select   journal_entry_header.memo from journal_entry_header where journal_entry_header_id=:journal_entry_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':journal_entry_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['memo'];
                echo $field;
            }

            function get_chosen_journal_entry_header_reference_number($id) {

                $db = new dbconnection();
                $sql = "select   journal_entry_header.reference_number from journal_entry_header where journal_entry_header_id=:journal_entry_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':journal_entry_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['reference_number'];
                echo $field;
            }

            function get_chosen_journal_entry_header_posted($id) {

                $db = new dbconnection();
                $sql = "select   journal_entry_header.posted from journal_entry_header where journal_entry_header_id=:journal_entry_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':journal_entry_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['posted'];
                echo $field;
            }

            function All_journal_entry_header() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  journal_entry_header_id   from journal_entry_header";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_journal_entry_header() {
                $con = new dbconnection();
                $sql = "select journal_entry_header.journal_entry_header_id from journal_entry_header
                    order by journal_entry_header.journal_entry_header_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['journal_entry_header_id'];
                return $first_rec;
            }

            function get_last_journal_entry_header() {
                $con = new dbconnection();
                $sql = "select journal_entry_header.journal_entry_header_id from journal_entry_header
                    order by journal_entry_header.journal_entry_header_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['journal_entry_header_id'];
                return $first_rec;
            }

            function list_Payment_term($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from Payment_term";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>
                        <td> Description </td><td> Payment Type </td><td> DueAfterDays </td><td> Is Active </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['Payment_term_id']; ?>
                        </td>
                        <td class="description_id_cols Payment_term " title="Payment_term" >
                            <?php echo $this->_e($row['description']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['payment_type']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['due_after_days']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['is_active']); ?>
                        </td>


                        <td>
                            <a href="#" class="Payment_term_delete_link" style="color: #000080;" value="
                               <?php echo $row['Payment_term_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="Payment_term_update_link" style="color: #000080;" value="
                               <?php echo $row['Payment_term_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_Payment_term_description($id) {

                $db = new dbconnection();
                $sql = "select   Payment_term.description from Payment_term where Payment_term_id=:Payment_term_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':Payment_term_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['description'];
                echo $field;
            }

            function get_chosen_Payment_term_payment_type($id) {

                $db = new dbconnection();
                $sql = "select   Payment_term.payment_type from Payment_term where Payment_term_id=:Payment_term_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':Payment_term_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['payment_type'];
                echo $field;
            }

            function get_chosen_Payment_term_due_after_days($id) {

                $db = new dbconnection();
                $sql = "select   Payment_term.due_after_days from Payment_term where Payment_term_id=:Payment_term_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':Payment_term_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['due_after_days'];
                echo $field;
            }

            function get_chosen_Payment_term_is_active($id) {

                $db = new dbconnection();
                $sql = "select   Payment_term.is_active from Payment_term where Payment_term_id=:Payment_term_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':Payment_term_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['is_active'];
                echo $field;
            }

            function All_Payment_term() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  Payment_term_id   from Payment_term";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_Payment_term() {
                $con = new dbconnection();
                $sql = "select Payment_term.Payment_term_id from Payment_term
                    order by Payment_term.Payment_term_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['Payment_term_id'];
                return $first_rec;
            }

            function get_last_Payment_term() {
                $con = new dbconnection();
                $sql = "select Payment_term.Payment_term_id from Payment_term
                    order by Payment_term.Payment_term_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['Payment_term_id'];
                return $first_rec;
            }

            function list_item($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from item";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>
                        <td> Measurement </td><td> Vendor </td><td> Item Group </td><td> Item Category </td><td> Smallest Measurement </td><td> Sale Meausurement </td><td> Purchase Measurement </td><td> Sales Account </td><td> Inventory Account </td><td> Inventory Adjustment Account </td><td> Number </td><td> Code </td><td> Description </td><td> Purchase description </td><td> Sale Description </td><td> Cost </td><td> Price </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['item_id']; ?>
                        </td>
                        <td class="measurement_id_cols item " title="item" >
                            <?php echo $this->_e($row['measurement']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['vendor']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['item_group']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['item_category']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['smallest_measurement']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['sale_measurement']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['purchase_measurement']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['sales_account']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['inventory_accid']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['inventoty_adj_accid']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['number']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['Code']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['description']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['purchase_desc']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['sale_desc']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['cost']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['price']); ?>
                        </td>


                        <td>
                            <a href="#" class="item_delete_link" style="color: #000080;" value="
                               <?php echo $row['item_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="item_update_link" style="color: #000080;" value="
                               <?php echo $row['item_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_item_measurement($id) {

                $db = new dbconnection();
                $sql = "select   item.measurement from item where item_id=:item_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['measurement'];
                echo $field;
            }

            function get_chosen_item_vendor($id) {

                $db = new dbconnection();
                $sql = "select   item.vendor from item where item_id=:item_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['vendor'];
                echo $field;
            }

            function get_chosen_item_item_group($id) {

                $db = new dbconnection();
                $sql = "select   item.item_group from item where item_id=:item_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['item_group'];
                echo $field;
            }

            function get_chosen_item_item_category($id) {

                $db = new dbconnection();
                $sql = "select   item.item_category from item where item_id=:item_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['item_category'];
                echo $field;
            }

            function get_chosen_item_smallest_measurement($id) {

                $db = new dbconnection();
                $sql = "select   item.smallest_measurement from item where item_id=:item_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['smallest_measurement'];
                echo $field;
            }

            function get_chosen_item_sale_measurement($id) {

                $db = new dbconnection();
                $sql = "select   item.sale_measurement from item where item_id=:item_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['sale_measurement'];
                echo $field;
            }

            function get_chosen_item_purchase_measurement($id) {

                $db = new dbconnection();
                $sql = "select   item.purchase_measurement from item where item_id=:item_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['purchase_measurement'];
                echo $field;
            }

            function get_chosen_item_sales_account($id) {

                $db = new dbconnection();
                $sql = "select   item.sales_account from item where item_id=:item_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['sales_account'];
                echo $field;
            }

            function get_chosen_item_inventory_accid($id) {

                $db = new dbconnection();
                $sql = "select   item.inventory_accid from item where item_id=:item_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['inventory_accid'];
                echo $field;
            }

            function get_chosen_item_inventoty_adj_accid($id) {

                $db = new dbconnection();
                $sql = "select   item.inventoty_adj_accid from item where item_id=:item_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['inventoty_adj_accid'];
                echo $field;
            }

            function get_chosen_item_number($id) {

                $db = new dbconnection();
                $sql = "select   item.number from item where item_id=:item_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['number'];
                echo $field;
            }

            function get_chosen_item_Code($id) {

                $db = new dbconnection();
                $sql = "select   item.Code from item where item_id=:item_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['Code'];
                echo $field;
            }

            function get_chosen_item_description($id) {

                $db = new dbconnection();
                $sql = "select   item.description from item where item_id=:item_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['description'];
                echo $field;
            }

            function get_chosen_item_purchase_desc($id) {

                $db = new dbconnection();
                $sql = "select   item.purchase_desc from item where item_id=:item_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['purchase_desc'];
                echo $field;
            }

            function get_chosen_item_sale_desc($id) {

                $db = new dbconnection();
                $sql = "select   item.sale_desc from item where item_id=:item_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['sale_desc'];
                echo $field;
            }

            function get_chosen_item_cost($id) {

                $db = new dbconnection();
                $sql = "select   item.cost from item where item_id=:item_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['cost'];
                echo $field;
            }

            function get_chosen_item_price($id) {

                $db = new dbconnection();
                $sql = "select   item.price from item where item_id=:item_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['price'];
                echo $field;
            }

            function All_item() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  item_id   from item";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_item() {
                $con = new dbconnection();
                $sql = "select item.item_id from item
                    order by item.item_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['item_id'];
                return $first_rec;
            }

            function get_last_item() {
                $con = new dbconnection();
                $sql = "select item.item_id from item
                    order by item.item_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['item_id'];
                return $first_rec;
            }

            function list_item_group($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from item_group";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>
                        <td> Name </td><td> Full exempt </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['item_group_id']; ?>
                        </td>
                        <td class="name_id_cols item_group " title="item_group" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['is_full_exempt']); ?>
                        </td>


                        <td>
                            <a href="#" class="item_group_delete_link" style="color: #000080;" value="
                               <?php echo $row['item_group_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="item_group_update_link" style="color: #000080;" value="
                               <?php echo $row['item_group_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_item_group_name($id) {

                $db = new dbconnection();
                $sql = "select   item_group.name from item_group where item_group_id=:item_group_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_group_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['name'];
                echo $field;
            }

            function get_chosen_item_group_is_full_exempt($id) {

                $db = new dbconnection();
                $sql = "select   item_group.is_full_exempt from item_group where item_group_id=:item_group_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_group_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['is_full_exempt'];
                echo $field;
            }

            function All_item_group() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  item_group_id   from item_group";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_item_group() {
                $con = new dbconnection();
                $sql = "select item_group.item_group_id from item_group
                    order by item_group.item_group_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['item_group_id'];
                return $first_rec;
            }

            function get_last_item_group() {
                $con = new dbconnection();
                $sql = "select item_group.item_group_id from item_group
                    order by item_group.item_group_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['item_group_id'];
                return $first_rec;
            }

            function list_item_category($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from item_category";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>
                        <td> Measurement </td><td> Sales Account </td><td> Inventory Account </td><td> Cost Of Good Sold </td><td> Adjustment Account </td><td> Assembly_accid </td><td> Name </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['item_category_id']; ?>
                        </td>
                        <td class="measurement_id_cols item_category " title="item_category" >
                            <?php echo $this->_e($row['measurement']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['sales_accid']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['inventory_accid']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['cost_good_sold_accid']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['adjustment_accid']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['assembly_accid']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['name']); ?>
                        </td>


                        <td>
                            <a href="#" class="item_category_delete_link" style="color: #000080;" value="
                               <?php echo $row['item_category_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="item_category_update_link" style="color: #000080;" value="
                               <?php echo $row['item_category_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_item_category_measurement($id) {

                $db = new dbconnection();
                $sql = "select   item_category.measurement from item_category where item_category_id=:item_category_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_category_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['measurement'];
                echo $field;
            }

            function get_chosen_item_category_sales_accid($id) {

                $db = new dbconnection();
                $sql = "select   item_category.sales_accid from item_category where item_category_id=:item_category_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_category_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['sales_accid'];
                echo $field;
            }

            function get_chosen_item_category_inventory_accid($id) {

                $db = new dbconnection();
                $sql = "select   item_category.inventory_accid from item_category where item_category_id=:item_category_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_category_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['inventory_accid'];
                echo $field;
            }

            function get_chosen_item_category_cost_good_sold_accid($id) {

                $db = new dbconnection();
                $sql = "select   item_category.cost_good_sold_accid from item_category where item_category_id=:item_category_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_category_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['cost_good_sold_accid'];
                echo $field;
            }

            function get_chosen_item_category_adjustment_accid($id) {

                $db = new dbconnection();
                $sql = "select   item_category.adjustment_accid from item_category where item_category_id=:item_category_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_category_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['adjustment_accid'];
                echo $field;
            }

            function get_chosen_item_category_assembly_accid($id) {

                $db = new dbconnection();
                $sql = "select   item_category.assembly_accid from item_category where item_category_id=:item_category_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_category_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['assembly_accid'];
                echo $field;
            }

            function get_chosen_item_category_name($id) {

                $db = new dbconnection();
                $sql = "select   item_category.name from item_category where item_category_id=:item_category_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':item_category_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['name'];
                echo $field;
            }

            function All_item_category() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  item_category_id   from item_category";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_item_category() {
                $con = new dbconnection();
                $sql = "select item_category.item_category_id from item_category
                    order by item_category.item_category_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['item_category_id'];
                return $first_rec;
            }

            function get_last_item_category() {
                $con = new dbconnection();
                $sql = "select item_category.item_category_id from item_category
                    order by item_category.item_category_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['item_category_id'];
                return $first_rec;
            }

            function list_vendor_payment($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from vendor_payment";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>
                        <td> Vendor </td><td> General Ledger Header </td><td> Purchase Invoice Header </td><td> Number </td><td> Date </td><td> Amount </td>
                        <td>Delete</td><td>Update</td></tr></thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['vendor_payment_id']; ?>
                        </td>
                        <td class="vendor_id_cols vendor_payment " title="vendor_payment" >
                            <?php echo $this->_e($row['vendor']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['gen_ledger_header']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['pur_invoice_header']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['number']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['amount']); ?>
                        </td>


                        <td>
                            <a href="#" class="vendor_payment_delete_link" style="color: #000080;" value="
                               <?php echo $row['vendor_payment_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="vendor_payment_update_link" style="color: #000080;" value="
                               <?php echo $row['vendor_payment_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_vendor_payment_vendor($id) {

                $db = new dbconnection();
                $sql = "select   vendor_payment.vendor from vendor_payment where vendor_payment_id=:vendor_payment_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':vendor_payment_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['vendor'];
                echo $field;
            }

            function get_chosen_vendor_payment_gen_ledger_header($id) {

                $db = new dbconnection();
                $sql = "select   vendor_payment.gen_ledger_header from vendor_payment where vendor_payment_id=:vendor_payment_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':vendor_payment_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['gen_ledger_header'];
                echo $field;
            }

            function get_chosen_vendor_payment_pur_invoice_header($id) {

                $db = new dbconnection();
                $sql = "select   vendor_payment.pur_invoice_header from vendor_payment where vendor_payment_id=:vendor_payment_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':vendor_payment_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['pur_invoice_header'];
                echo $field;
            }

            function get_chosen_vendor_payment_number($id) {

                $db = new dbconnection();
                $sql = "select   vendor_payment.number from vendor_payment where vendor_payment_id=:vendor_payment_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':vendor_payment_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['number'];
                echo $field;
            }

            function get_chosen_vendor_payment_date($id) {

                $db = new dbconnection();
                $sql = "select   vendor_payment.date from vendor_payment where vendor_payment_id=:vendor_payment_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':vendor_payment_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['date'];
                echo $field;
            }

            function get_chosen_vendor_payment_amount($id) {

                $db = new dbconnection();
                $sql = "select   vendor_payment.amount from vendor_payment where vendor_payment_id=:vendor_payment_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':vendor_payment_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['amount'];
                echo $field;
            }

            function All_vendor_payment() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  vendor_payment_id   from vendor_payment";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_vendor_payment() {
                $con = new dbconnection();
                $sql = "select vendor_payment.vendor_payment_id from vendor_payment
                    order by vendor_payment.vendor_payment_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['vendor_payment_id'];
                return $first_rec;
            }

            function get_last_vendor_payment() {
                $con = new dbconnection();
                $sql = "select vendor_payment.vendor_payment_id from vendor_payment
                    order by vendor_payment.vendor_payment_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['vendor_payment_id'];
                return $first_rec;
            }

            function list_sales_delivery_header($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from sales_delivery_header";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Customer </td><td> General Ledger Header </td><td> Payment Term </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['sales_delivery_header_id']; ?>
                        </td>
                        <td class="customer_id_cols sales_delivery_header " title="sales_delivery_header" >
                            <?php echo $this->_e($row['customer']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['gen_ledger_header']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['payment_term']); ?>
                        </td>


                        <td>
                            <a href="#" class="sales_delivery_header_delete_link" style="color: #000080;" value="
                               <?php echo $row['sales_delivery_header_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="sales_delivery_header_update_link" style="color: #000080;" value="
                               <?php echo $row['sales_delivery_header_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_sales_delivery_header_customer($id) {

                $db = new dbconnection();
                $sql = "select   sales_delivery_header.customer from sales_delivery_header where sales_delivery_header_id=:sales_delivery_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_delivery_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['customer'];
                echo $field;
            }

            function get_chosen_sales_delivery_header_gen_ledger_header($id) {

                $db = new dbconnection();
                $sql = "select   sales_delivery_header.gen_ledger_header from sales_delivery_header where sales_delivery_header_id=:sales_delivery_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_delivery_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['gen_ledger_header'];
                echo $field;
            }

            function get_chosen_sales_delivery_header_payment_term($id) {

                $db = new dbconnection();
                $sql = "select   sales_delivery_header.payment_term from sales_delivery_header where sales_delivery_header_id=:sales_delivery_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_delivery_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['payment_term'];
                echo $field;
            }

            function All_sales_delivery_header() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  sales_delivery_header_id   from sales_delivery_header";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_sales_delivery_header() {
                $con = new dbconnection();
                $sql = "select sales_delivery_header.sales_delivery_header_id from sales_delivery_header
                    order by sales_delivery_header.sales_delivery_header_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sales_delivery_header_id'];
                return $first_rec;
            }

            function get_last_sales_delivery_header() {
                $con = new dbconnection();
                $sql = "select sales_delivery_header.sales_delivery_header_id from sales_delivery_header
                    order by sales_delivery_header.sales_delivery_header_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sales_delivery_header_id'];
                return $first_rec;
            }

            function list_sale_delivery_line($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from sale_delivery_line";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Item </td><td> Measurement </td><td> Sales Delivery Header </td><td> Sales Invoice Line </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['sale_delivery_line_id']; ?>
                        </td>
                        <td class="item_id_cols sale_delivery_line " title="sale_delivery_line" >
                            <?php echo $this->_e($row['item']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['measurement']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['sales_delivery_header']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['sales_invoice_line']); ?>
                        </td>


                        <td>
                            <a href="#" class="sale_delivery_line_delete_link" style="color: #000080;" value="
                               <?php echo $row['sale_delivery_line_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="sale_delivery_line_update_link" style="color: #000080;" value="
                               <?php echo $row['sale_delivery_line_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_sale_delivery_line_item($id) {

                $db = new dbconnection();
                $sql = "select   sale_delivery_line.item from sale_delivery_line where sale_delivery_line_id=:sale_delivery_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sale_delivery_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['item'];
                echo $field;
            }

            function get_chosen_sale_delivery_line_measurement($id) {

                $db = new dbconnection();
                $sql = "select   sale_delivery_line.measurement from sale_delivery_line where sale_delivery_line_id=:sale_delivery_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sale_delivery_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['measurement'];
                echo $field;
            }

            function get_chosen_sale_delivery_line_sales_delivery_header($id) {

                $db = new dbconnection();
                $sql = "select   sale_delivery_line.sales_delivery_header from sale_delivery_line where sale_delivery_line_id=:sale_delivery_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sale_delivery_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['sales_delivery_header'];
                echo $field;
            }

            function get_chosen_sale_delivery_line_sales_invoice_line($id) {

                $db = new dbconnection();
                $sql = "select   sale_delivery_line.sales_invoice_line from sale_delivery_line where sale_delivery_line_id=:sale_delivery_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sale_delivery_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['sales_invoice_line'];
                echo $field;
            }

            function All_sale_delivery_line() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  sale_delivery_line_id   from sale_delivery_line";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_sale_delivery_line() {
                $con = new dbconnection();
                $sql = "select sale_delivery_line.sale_delivery_line_id from sale_delivery_line
                    order by sale_delivery_line.sale_delivery_line_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sale_delivery_line_id'];
                return $first_rec;
            }

            function get_last_sale_delivery_line() {
                $con = new dbconnection();
                $sql = "select sale_delivery_line.sale_delivery_line_id from sale_delivery_line
                    order by sale_delivery_line.sale_delivery_line_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sale_delivery_line_id'];
                return $first_rec;
            }

            function list_sales_invoice_line($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select sales_invoice_line.sales_invoice_line_id,  sales_invoice_line.quantity,  sales_invoice_line.unit_cost,  sales_invoice_line.amount,  sales_invoice_line.entry_date,  sales_invoice_line.User,  sales_invoice_line.client,  sales_invoice_line.sales_order,  sales_invoice_line.budget_prep_id,  sales_invoice_line.acc_debit, "
                        . " user.Firstname, user.Lastname ,"
                        . " p_budget_items.item_name  as item, party.name as client"
                        . "   from sales_invoice_line "
                        . " join party on party.party_id =sales_invoice_line.client "
                        . " join user on user.StaffID=sales_invoice_line.User "
                        . " join sales_order_line on sales_order_line.sales_order_line_id=sales_invoice_line.sales_order
                            join sales_quote_line on sales_quote_line.sales_quote_line_id=sales_order_line.quotationid
                            join p_budget_items on p_budget_items.p_budget_items_id=sales_quote_line.item"
                        . "  ";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Item </td>
                        <td> Quantity </td><td> Unit Cost </td><td> Amount </td>
                        <td> Client </td><td> User </td> <td> Entry Date </td> 

                        <td class="off"> Sales order </td><td class="off"> Budget Reference </td>
                        <?php if (isset($_SESSION['shall_delete'])) { ?> <td>Delete</td><td>Update</td><?php } ?></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr class="clickable_row" data-table_id="<?php echo $row['sales_invoice_line_id']; ?>"     data-bind="sales_invoice_line"> 

                        <td>
                            <?php echo $row['sales_invoice_line_id']; ?>
                        </td>
                        <td>
                            <?php echo $row['item']; ?>
                        </td>
                        <td class="quantity_id_cols sales_invoice_line " title="sales_invoice_line" >
                            <?php echo $this->_e($row['quantity']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['unit_cost']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['amount']); ?>
                        </td>

                        <td>
                            <?php echo $this->_e($row['client']); ?>
                        </td>  <td>
                            <?php
                            echo $this->_e($row['Firstname']) . '  ';
                            echo $this->_e($row['Lastname']);
                            ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['sales_order']); ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['budget_prep_id']); ?>
                        </td>

                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>
                                <a href="#" class="sales_invoice_line_delete_link" style="color: #000080;" data-id_delete="sales_invoice_line_id"  data-table="
                                   <?php echo $row['sales_invoice_line_id']; ?>">Delete</a>
                            </td>
                            <td>
                                <a href="#" class="sales_invoice_line_update_link" style="color: #000080;" value="
                                   <?php echo $row['sales_invoice_line_id']; ?>">Update</a>
                            </td><?php } ?></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_sales_invoice_line_item($id) {

                $db = new dbconnection();
                $sql = "select   sales_invoice_line.item from sales_invoice_line where sales_invoice_line_id=:sales_invoice_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_invoice_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['item'];
                echo $field;
            }

            function get_chosen_sales_invoice_line_measurement($id) {

                $db = new dbconnection();
                $sql = "select   sales_invoice_line.measurement from sales_invoice_line where sales_invoice_line_id=:sales_invoice_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_invoice_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['measurement'];
                echo $field;
            }

            function get_chosen_sales_invoice_line_sales_delivery_header($id) {

                $db = new dbconnection();
                $sql = "select   sales_invoice_line.sales_delivery_header from sales_invoice_line where sales_invoice_line_id=:sales_invoice_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_invoice_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['sales_delivery_header'];
                echo $field;
            }

            function get_chosen_sales_invoice_line_sales_invoice_header($id) {

                $db = new dbconnection();
                $sql = "select   sales_invoice_line.sales_invoice_header from sales_invoice_line where sales_invoice_line_id=:sales_invoice_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_invoice_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['sales_invoice_header'];
                echo $field;
            }

            function get_chosen_sales_invoice_line_sales_order_line($id) {

                $db = new dbconnection();
                $sql = "select   sales_invoice_line.sales_order_line from sales_invoice_line where sales_invoice_line_id=:sales_invoice_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_invoice_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['sales_order_line'];
                echo $field;
            }

            function get_chosen_sales_invoice_line_quantity($id) {

                $db = new dbconnection();
                $sql = "select   sales_invoice_line.quantity from sales_invoice_line where sales_invoice_line_id=:sales_invoice_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_invoice_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['quantity'];
                echo $field;
            }

            function get_chosen_sales_invoice_line_discount($id) {

                $db = new dbconnection();
                $sql = "select   sales_invoice_line.discount from sales_invoice_line where sales_invoice_line_id=:sales_invoice_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_invoice_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['discount'];
                echo $field;
            }

            function All_sales_invoice_line() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  sales_invoice_line_id   from sales_invoice_line";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_sales_invoice_line() {
                $con = new dbconnection();
                $sql = "select sales_invoice_line.sales_invoice_line_id from sales_invoice_line
                    order by sales_invoice_line.sales_invoice_line_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sales_invoice_line_id'];
                return $first_rec;
            }

            function get_last_sales_invoice_line() {
                $con = new dbconnection();
                $sql = "select sales_invoice_line.sales_invoice_line_id from sales_invoice_line
                    order by sales_invoice_line.sales_invoice_line_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sales_invoice_line_id'];
                return $first_rec;
            }

            function list_sales_invoice_header($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from sales_invoice_header";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Customer </td><td> Payment Term </td><td> General Ledger Header </td><td> Number </td><td> Date </td><td> Shipping  Charge </td><td> Status </td><td> Reference Number </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['sales_invoice_header_id']; ?>
                        </td>
                        <td class="customer_id_cols sales_invoice_header " title="sales_invoice_header" >
                            <?php echo $this->_e($row['customer']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['payment_term']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['gen_ledger_header']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['number']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['Shipping']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['status']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['reference_no']); ?>
                        </td>


                        <td>
                            <a href="#" class="sales_invoice_header_delete_link" style="color: #000080;" value="
                               <?php echo $row['sales_invoice_header_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="sales_invoice_header_update_link" style="color: #000080;" value="
                               <?php echo $row['sales_invoice_header_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_sales_invoice_header_customer($id) {

                $db = new dbconnection();
                $sql = "select   sales_invoice_header.customer from sales_invoice_header where sales_invoice_header_id=:sales_invoice_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_invoice_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['customer'];
                echo $field;
            }

            function get_chosen_sales_invoice_header_payment_term($id) {

                $db = new dbconnection();
                $sql = "select   sales_invoice_header.payment_term from sales_invoice_header where sales_invoice_header_id=:sales_invoice_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_invoice_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['payment_term'];
                echo $field;
            }

            function get_chosen_sales_invoice_header_gen_ledger_header($id) {

                $db = new dbconnection();
                $sql = "select   sales_invoice_header.gen_ledger_header from sales_invoice_header where sales_invoice_header_id=:sales_invoice_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_invoice_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['gen_ledger_header'];
                echo $field;
            }

            function get_chosen_sales_invoice_header_number($id) {

                $db = new dbconnection();
                $sql = "select   sales_invoice_header.number from sales_invoice_header where sales_invoice_header_id=:sales_invoice_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_invoice_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['number'];
                echo $field;
            }

            function get_chosen_sales_invoice_header_date($id) {

                $db = new dbconnection();
                $sql = "select   sales_invoice_header.date from sales_invoice_header where sales_invoice_header_id=:sales_invoice_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_invoice_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['date'];
                echo $field;
            }

            function get_chosen_sales_invoice_header_Shipping($id) {

                $db = new dbconnection();
                $sql = "select   sales_invoice_header.Shipping from sales_invoice_header where sales_invoice_header_id=:sales_invoice_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_invoice_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['Shipping'];
                echo $field;
            }

            function get_chosen_sales_invoice_header_status($id) {

                $db = new dbconnection();
                $sql = "select   sales_invoice_header.status from sales_invoice_header where sales_invoice_header_id=:sales_invoice_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_invoice_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['status'];
                echo $field;
            }

            function get_chosen_sales_invoice_header_reference_no($id) {

                $db = new dbconnection();
                $sql = "select   sales_invoice_header.reference_no from sales_invoice_header where sales_invoice_header_id=:sales_invoice_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_invoice_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['reference_no'];
                echo $field;
            }

            function All_sales_invoice_header() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  sales_invoice_header_id   from sales_invoice_header";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_sales_invoice_header() {
                $con = new dbconnection();
                $sql = "select sales_invoice_header.sales_invoice_header_id from sales_invoice_header
                    order by sales_invoice_header.sales_invoice_header_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sales_invoice_header_id'];
                return $first_rec;
            }

            function get_last_sales_invoice_header() {
                $con = new dbconnection();
                $sql = "select sales_invoice_header.sales_invoice_header_id from sales_invoice_header
                    order by sales_invoice_header.sales_invoice_header_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sales_invoice_header_id'];
                return $first_rec;
            }

            function list_sales_order_line($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select sales_order_line.sales_order_line_id,  sales_order_line.quantity,  sales_order_line.unit_cost,  sales_order_line.amount,  sales_order_line.entry_date,  sales_order_line.User,  sales_order_line.quotationid, user.Firstname, user.Lastname, p_budget_items.item_name as item from sales_order_line "
                        . " join user on user.StaffID=sales_order_line.User "
                        . " join sales_quote_line on sales_quote_line.sales_quote_line_id=sales_order_line.quotationid "
                        . " join p_budget_items on p_budget_items.p_budget_items_id=sales_quote_line.item ";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td class="off"> S/N </td>
                        <td> Item</td>
                        <td> Quantity </td>
                        <td> Unit Cost </td> 
                        <td> Amount </td>
                        <td> Entry Date </td><td> User </td>


                        <?php if (isset($_SESSION['shall_delete'])) { ?> <td>Delete</td><td>Update</td><?php } ?></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr class="clickable_row" data-table_id="<?php echo $row['sales_order_line_id']; ?>"     data-bind="sales_order_line"> 
                        <td class="off">
                            <?php echo $row['S/N']; ?>
                        </td>
                        <td>
                            <?php echo $row['item']; ?>
                        </td>
                        <td class="quantity_id_cols sales_quote_line " title="sales_quote_line" >
                            <?php echo $this->_e($row['quantity']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e(number_format($row['unit_cost'])); ?>
                        </td> 
                        <td>
                            <?php echo $this->_e(number_format($row['amount'])); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>
                        <td>
                            <?php
                            echo $this->_e($row['Firstname']) . '   ';
                            echo $this->_e($row['Lastname']);
                            ?>
                        </td>


                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>
                                <a href="#" class="sales_order_line_delete_link" style="color: #000080;" value="
                                   <?php echo $row['sales_order_line_id']; ?>">Delete</a>
                            </td>
                            <td>
                                <a href="#" class="sales_order_line_update_link" style="color: #000080;" value="
                                   <?php echo $row['sales_order_line_id']; ?>">Update</a>
                            </td><?php } ?></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_sales_order_line_sales_order_header($id) {

                $db = new dbconnection();
                $sql = "select   sales_order_line.sales_order_header from sales_order_line where sales_order_line_id=:sales_order_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_order_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['sales_order_header'];
                echo $field;
            }

            function get_chosen_sales_order_line_item($id) {

                $db = new dbconnection();
                $sql = "select   sales_order_line.item from sales_order_line where sales_order_line_id=:sales_order_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_order_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['item'];
                echo $field;
            }

            function get_chosen_sales_order_line_measurement($id) {

                $db = new dbconnection();
                $sql = "select   sales_order_line.measurement from sales_order_line where sales_order_line_id=:sales_order_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_order_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['measurement'];
                echo $field;
            }

            function get_chosen_sales_order_line_quantity($id) {

                $db = new dbconnection();
                $sql = "select   sales_order_line.quantity from sales_order_line where sales_order_line_id=:sales_order_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_order_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['quantity'];
                echo $field;
            }

            function get_chosen_sales_order_line_discount($id) {

                $db = new dbconnection();
                $sql = "select   sales_order_line.discount from sales_order_line where sales_order_line_id=:sales_order_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_order_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['discount'];
                echo $field;
            }

            function get_chosen_sales_order_line_amount($id) {

                $db = new dbconnection();
                $sql = "select   sales_order_line.amount from sales_order_line where sales_order_line_id=:sales_order_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_order_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['amount'];
                echo $field;
            }

            function All_sales_order_line() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  sales_order_line_id   from sales_order_line";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_sales_order_line() {
                $con = new dbconnection();
                $sql = "select sales_order_line.sales_order_line_id from sales_order_line
                    order by sales_order_line.sales_order_line_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sales_order_line_id'];
                return $first_rec;
            }

            function get_last_sales_order_line() {
                $con = new dbconnection();
                $sql = "select sales_order_line.sales_order_line_id from sales_order_line
                    order by sales_order_line.sales_order_line_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sales_order_line_id'];
                return $first_rec;
            }

            function list_sales_order_header($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from sales_order_header";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Customer </td><td> Payment Term </td><td> Number </td><td> Reference Number </td><td> Date </td><td> Status </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['sales_order_header_id']; ?>
                        </td>
                        <td class="customer_id_cols sales_order_header " title="sales_order_header" >
                            <?php echo $this->_e($row['customer']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['payment_term']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['number']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['reference_number']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['status']); ?>
                        </td>

                        <td>
                            <?php echo $row['item']; ?>
                        </td>
                        <td>
                            <a href="#" class="sales_order_header_delete_link" style="color: #000080;" value="
                               <?php echo $row['sales_order_header_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="sales_order_header_update_link" style="color: #000080;" value="
                               <?php echo $row['sales_order_header_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_sales_order_header_customer($id) {

                $db = new dbconnection();
                $sql = "select   sales_order_header.customer from sales_order_header where sales_order_header_id=:sales_order_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_order_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['customer'];
                echo $field;
            }

            function get_chosen_sales_order_header_payment_term($id) {

                $db = new dbconnection();
                $sql = "select   sales_order_header.payment_term from sales_order_header where sales_order_header_id=:sales_order_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_order_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['payment_term'];
                echo $field;
            }

            function get_chosen_sales_order_header_number($id) {

                $db = new dbconnection();
                $sql = "select   sales_order_header.number from sales_order_header where sales_order_header_id=:sales_order_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_order_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['number'];
                echo $field;
            }

            function get_chosen_sales_order_header_reference_number($id) {
                $db = new dbconnection();
                $sql = "select   sales_order_header.reference_number from sales_order_header where sales_order_header_id=:sales_order_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_order_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['reference_number'];
                echo $field;
            }

            function get_chosen_sales_order_header_date($id) {

                $db = new dbconnection();
                $sql = "select   sales_order_header.date from sales_order_header where sales_order_header_id=:sales_order_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_order_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['date'];
                echo $field;
            }

            function get_chosen_sales_order_header_status($id) {

                $db = new dbconnection();
                $sql = "select   sales_order_header.status from sales_order_header where sales_order_header_id=:sales_order_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_order_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['status'];
                echo $field;
            }

            function All_sales_order_header() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  sales_order_header_id   from sales_order_header";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_sales_order_header() {
                $con = new dbconnection();
                $sql = "select sales_order_header.sales_order_header_id from sales_order_header
                    order by sales_order_header.sales_order_header_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sales_order_header_id'];
                return $first_rec;
            }

            function get_last_sales_order_header() {
                $con = new dbconnection();
                $sql = "select sales_order_header.sales_order_header_id from sales_order_header
                    order by sales_order_header.sales_order_header_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sales_order_header_id'];
                return $first_rec;
            }

            function list_sales_quote_line() {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  sales_quote_line.sales_quote_line_id,  sales_quote_line.quantity,  measurement.code as measurement_unit,
                            sales_quote_line.unit_cost,  sales_quote_line.entry_date, 
                            user.Firstname,user.Lastname,  sales_quote_line.amount, p_budget_items.item_name as item
                             from sales_quote_line
                            join measurement on sales_quote_line.measurement = measurement.measurement_id 
                            join user on user.StaffID=sales_quote_line.User 
                            join p_budget_items on p_budget_items.p_budget_items_id =sales_quote_line.item";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td><td> item </td>
                        <td> Quantity </td><td> Unit Cost </td><td> Amount </td><td> Entry Date </td>
                        <td> User </td><td> Measurement </td>

                        <?php if (isset($_SESSION['shall_delete'])) { ?>   <td>Delete</td><td>Update</td>
                        <?php } ?>
                    </tr></thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr class="clickable_row" data-table_id="<?php echo $row['sales_quote_line_id']; ?>"     data-bind="sales_quote_line"> 
                        <td>
                            <?php echo $row['sales_quote_line_id']; ?>
                        </td> 
                        <td>
                            <?php echo $this->_e($row['item']); ?>
                        </td>
                        <td class="quantity_id_cols sales_quote_line " title="sales_quote_line" >
                            <?php echo $this->_e($row['quantity']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['unit_cost']); ?>
                        </td>
                        <td>
                            <?php echo number_format($this->_e($row['amount'])); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['Firstname']) . '  ' . $this->_e($row['Lastname']); ?>
                        </td>

                        <td>
                            <?php echo $this->_e($row['measurement_unit']); ?>
                        </td>


                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>
                                <a href="#" class="sales_quote_line_delete_link" style="color: #000080;" data-id_delete="sales_quote_line_id"  data-table="
                                   <?php echo $row['sales_quote_line_id']; ?>">Delete</a>
                            </td>
                            <td>
                                <a href="#" class="sales_quote_line_update_link" style="color: #000080;" value="
                                   <?php echo $row['sales_quote_line_id']; ?>">Update</a>
                            </td><?php } ?></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
                $this->paging($this->All_sales_quote_line());
            }

//chosen individual field
            function get_chosen_sales_quote_line_sales_quote_header($id) {

                $db = new dbconnection();
                $sql = "select   sales_quote_line.sales_quote_header from sales_quote_line where sales_quote_line_id=:sales_quote_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_quote_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['sales_quote_header'];
                echo $field;
            }

            function get_chosen_sales_quote_line_item($id) {

                $db = new dbconnection();
                $sql = "select   sales_quote_line.item from sales_quote_line where sales_quote_line_id=:sales_quote_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_quote_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['item'];
                echo $field;
            }

            function get_chosen_sales_quote_line_measurement($id) {

                $db = new dbconnection();
                $sql = "select   sales_quote_line.measurement from sales_quote_line where sales_quote_line_id=:sales_quote_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_quote_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['measurement'];
                echo $field;
            }

            function get_chosen_sales_quote_line_quantity($id) {

                $db = new dbconnection();
                $sql = "select   sales_quote_line.quantity from sales_quote_line where sales_quote_line_id=:sales_quote_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_quote_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['quantity'];
                echo $field;
            }

            function get_chosen_sales_quote_line_discount($id) {

                $db = new dbconnection();
                $sql = "select   sales_quote_line.discount from sales_quote_line where sales_quote_line_id=:sales_quote_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_quote_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['discount'];
                echo $field;
            }

            function get_chosen_sales_quote_line_amount($id) {

                $db = new dbconnection();
                $sql = "select   sales_quote_line.amount from sales_quote_line where sales_quote_line_id=:sales_quote_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_quote_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['amount'];
                echo $field;
            }

            function All_sales_quote_line() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  sales_quote_line_id   from sales_quote_line";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_sales_quote_line() {
                $con = new dbconnection();
                $sql = "select sales_quote_line.sales_quote_line_id from sales_quote_line
                    order by sales_quote_line.sales_quote_line_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sales_quote_line_id'];
                return $first_rec;
            }

            function get_last_sales_quote_line() {
                $con = new dbconnection();
                $sql = "select sales_quote_line.sales_quote_line_id from sales_quote_line
                    order by sales_quote_line.sales_quote_line_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sales_quote_line_id'];
                return $first_rec;
            }

            function list_sales_quote_header($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from sales_quote_header";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Customer </td><td> Date </td><td> Payment Term </td><td> Reference Number </td><td> Number </td><td> Status </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['sales_quote_header_id']; ?>
                        </td>
                        <td class="customer_id_cols sales_quote_header " title="sales_quote_header" >
                            <?php echo $this->_e($row['customer']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['payment_term']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['reference_number']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['number']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['status']); ?>
                        </td>


                        <td>
                            <a href="#" class="sales_quote_header_delete_link" style="color: #000080;" value="
                               <?php echo $row['sales_quote_header_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="sales_quote_header_update_link" style="color: #000080;" value="
                               <?php echo $row['sales_quote_header_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_sales_quote_header_customer($id) {

                $db = new dbconnection();
                $sql = "select   sales_quote_header.customer from sales_quote_header where sales_quote_header_id=:sales_quote_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_quote_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['customer'];
                echo $field;
            }

            function get_chosen_sales_quote_header_date($id) {

                $db = new dbconnection();
                $sql = "select   sales_quote_header.date from sales_quote_header where sales_quote_header_id=:sales_quote_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_quote_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['date'];
                echo $field;
            }

            function get_chosen_sales_quote_header_payment_term($id) {

                $db = new dbconnection();
                $sql = "select   sales_quote_header.payment_term from sales_quote_header where sales_quote_header_id=:sales_quote_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_quote_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['payment_term'];
                echo $field;
            }

            function get_chosen_sales_quote_header_reference_number($id) {

                $db = new dbconnection();
                $sql = "select   sales_quote_header.reference_number from sales_quote_header where sales_quote_header_id=:sales_quote_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_quote_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['reference_number'];
                echo $field;
            }

            function get_chosen_sales_quote_header_number($id) {

                $db = new dbconnection();
                $sql = "select   sales_quote_header.number from sales_quote_header where sales_quote_header_id=:sales_quote_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_quote_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['number'];
                echo $field;
            }

            function get_chosen_sales_quote_header_status($id) {

                $db = new dbconnection();
                $sql = "select   sales_quote_header.status from sales_quote_header where sales_quote_header_id=:sales_quote_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_quote_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['status'];
                echo $field;
            }

            function All_sales_quote_header() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  sales_quote_header_id   from sales_quote_header";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_sales_quote_header() {
                $con = new dbconnection();
                $sql = "select sales_quote_header.sales_quote_header_id from sales_quote_header
                    order by sales_quote_header.sales_quote_header_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sales_quote_header_id'];
                return $first_rec;
            }

            function get_last_sales_quote_header() {
                $con = new dbconnection();
                $sql = "select sales_quote_header.sales_quote_header_id from sales_quote_header
                    order by sales_quote_header.sales_quote_header_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['sales_quote_header_id'];
                return $first_rec;
            }

//chosen individual field


            function get_chosen_sales_receit_header_number($id) {

                $db = new dbconnection();
                $sql = "select   sales_receit_header.number from sales_receit_header where sales_receit_header_id=:sales_receit_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_receit_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['number'];
                echo $field;
            }

            function get_chosen_sales_receit_header_amount($id) {

                $db = new dbconnection();
                $sql = "select   sales_receit_header.amount from sales_receit_header where sales_receit_header_id=:sales_receit_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':sales_receit_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['amount'];
                echo $field;
            }

            function list_purchase_invoice_header($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from purchase_invoice_header";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Inventory Control Journal </td><td> Item </td><td> Measurement </td><td> Quantity </td><td> Received Quantity </td><td> Cost </td><td> Discount </td><td> Purchase Order Line </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['purchase_invoice_header_id']; ?>
                        </td>
                        <td class="inv_control_journal_id_cols purchase_invoice_header " title="purchase_invoice_header" >
                            <?php echo $this->_e($row['inv_control_journal']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['item']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['measurement']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['quantity']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['receieved_qusntinty']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['cost']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['discount']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['purchase_order_line']); ?>
                        </td>


                        <td>
                            <a href="#" class="purchase_invoice_header_delete_link" style="color: #000080;" value="
                               <?php echo $row['purchase_invoice_header_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="purchase_invoice_header_update_link" style="color: #000080;" value="
                               <?php echo $row['purchase_invoice_header_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_purchase_invoice_header_inv_control_journal($id) {

                $db = new dbconnection();
                $sql = "select   purchase_invoice_header.inv_control_journal from purchase_invoice_header where purchase_invoice_header_id=:purchase_invoice_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_invoice_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['inv_control_journal'];
                echo $field;
            }

            function get_chosen_purchase_invoice_header_item($id) {

                $db = new dbconnection();
                $sql = "select   purchase_invoice_header.item from purchase_invoice_header where purchase_invoice_header_id=:purchase_invoice_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_invoice_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['item'];
                echo $field;
            }

            function get_chosen_purchase_invoice_header_measurement($id) {

                $db = new dbconnection();
                $sql = "select   purchase_invoice_header.measurement from purchase_invoice_header where purchase_invoice_header_id=:purchase_invoice_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_invoice_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['measurement'];
                echo $field;
            }

            function get_chosen_purchase_invoice_header_quantity($id) {

                $db = new dbconnection();
                $sql = "select   purchase_invoice_header.quantity from purchase_invoice_header where purchase_invoice_header_id=:purchase_invoice_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_invoice_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['quantity'];
                echo $field;
            }

            function get_chosen_purchase_invoice_header_receieved_qusntinty($id) {

                $db = new dbconnection();
                $sql = "select   purchase_invoice_header.receieved_qusntinty from purchase_invoice_header where purchase_invoice_header_id=:purchase_invoice_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_invoice_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['receieved_qusntinty'];
                echo $field;
            }

            function get_chosen_purchase_invoice_header_cost($id) {

                $db = new dbconnection();
                $sql = "select   purchase_invoice_header.cost from purchase_invoice_header where purchase_invoice_header_id=:purchase_invoice_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_invoice_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['cost'];
                echo $field;
            }

            function get_chosen_purchase_invoice_header_discount($id) {

                $db = new dbconnection();
                $sql = "select   purchase_invoice_header.discount from purchase_invoice_header where purchase_invoice_header_id=:purchase_invoice_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_invoice_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['discount'];
                echo $field;
            }

            function get_chosen_purchase_invoice_header_purchase_order_line($id) {

                $db = new dbconnection();
                $sql = "select   purchase_invoice_header.purchase_order_line from purchase_invoice_header where purchase_invoice_header_id=:purchase_invoice_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_invoice_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['purchase_order_line'];
                echo $field;
            }

            function All_purchase_invoice_header() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  purchase_invoice_header_id   from purchase_invoice_header";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_purchase_invoice_header() {
                $con = new dbconnection();
                $sql = "select purchase_invoice_header.purchase_invoice_header_id from purchase_invoice_header
                    order by purchase_invoice_header.purchase_invoice_header_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['purchase_invoice_header_id'];
                return $first_rec;
            }

            function get_last_purchase_invoice_header() {
                $con = new dbconnection();
                $sql = "select purchase_invoice_header.purchase_invoice_header_id from purchase_invoice_header
                    order by purchase_invoice_header.purchase_invoice_header_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['purchase_invoice_header_id'];
                return $first_rec;
            }

            function list_purchase_invoice_line($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  purchase_invoice_line.purchase_invoice_line_id,  purchase_invoice_line.entry_date,  purchase_invoice_line.User,  purchase_invoice_line.quantity,  purchase_invoice_line.unit_cost,  purchase_invoice_line.amount,  purchase_invoice_line.purchase_order,  purchase_invoice_line.activity,  purchase_invoice_line.acc_debit,  purchase_invoice_line.supplier,    purchase_invoice_line.tax_inclusive,p_budget_items.item_name as item, user.Firstname,  user.Lastname "
                        . " from purchase_invoice_line"
                        . " join purchase_order_line on purchase_order_line.purchase_order_line_id=purchase_invoice_line.purchase_order
                            join p_request on p_request.p_request_id=purchase_order_line.request
                            join p_budget_items on p_request.item=p_budget_items.p_budget_items_id  "
                        . " join user on user.StaffID=purchase_invoice_line.user                              
                            join p_activity on p_activity.p_activity_id=purchase_invoice_line.activity
                        join p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id
                        join p_type_project on p_budget_prep.project_type=p_type_project.p_type_project_id ";

                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Item </td>
                        <td> Quantity </td>
                        <td> Unit Cost </td>


                        <td> Amount </td> <td> User </td><td class="off"> Purchase Order </td>
                        <td> Entry Date </td>
                        <td class="off"> Activity </td>
                        <?php if (isset($_SESSION['shall_delete'])) { ?> <td>Delete</td><td>Update</td><?php } ?></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr class="clickable_row" data-table_id="<?php echo $row['purchase_invoice_line_id'];
                    ?>"     data-bind="purchase_invoice_line"> 

                        <td>
                            <?php echo $row['purchase_invoice_line_id']; ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['item']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['quantity']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e(number_format($row['unit_cost'])); ?>
                        </td>


                        <td>
                            <?php echo $this->_e(number_format($row['amount'])); ?>
                        </td> <td>
                            <?php
                            echo $this->_e($row['Firstname']) . '  ';
                            echo $this->_e($row['Lastname']);
                            ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['purchase_order']); ?>
                        </td><td class="entry_date_id_cols purchase_invoice_line " title="purchase_invoice_line" >
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['activity']); ?>
                        </td>
                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>
                                <a href="#" class="purchase_invoice_line_delete_link" style="color: #000080;" data-id_delete="purchase_invoice_line_id"  data-table="
                                   <?php echo $row['purchase_invoice_line_id']; ?>">Delete</a>
                            </td>
                            <td>
                                <a href="#" class="purchase_invoice_line_update_link" style="color: #000080;" value="
                                   <?php echo $row['purchase_invoice_line_id']; ?>">Update</a>
                            </td><?php } ?></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function list_trial_balance() {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "SELECT journal_entry_line_id, accountid,account.name as account_name, dr_cr, amount ,count(accountid) as n ,journal_entry_line.entry_date
                        FROM accounting_db.journal_entry_line
                        join account on account.account_id=journal_entry_line.accountid
                         group by  accountid
                        order by accountid;";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                ?>
            <table class="dataList_table">
                <thead><tr>
                        <td class="off"> S/N </td>    
                        <td> Name </td>     
                        <td>Balance</td>
                        <td>Date</td>

                    </tr></thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 
                        <td class="off">   <?php echo $row['journal_entry_line_id']; ?>                        </td>
                        <td class="entry_date_id_cols purchase_invoice_line "  >   <?php echo $this->_e($row['account_name']); ?>
                        </td>

                        <td><?php echo $this->list_accounts_sum_dr_cr('debit', $row['accountid']) - $this->list_accounts_sum_dr_cr('credit', $row['accountid']) ?></td>
                        </td>
                        <td><?php echo $row['entry_date']; ?></td>


                    </tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
            <?php
        }

        function list_accounts_sum_dr_cr($dr_cr, $account) {
            $con = new dbconnection();
            $sql = "select sum(amount) as amount from journal_entry_line where dr_cr=:dr_cr and accountid=:account";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute(array(":dr_cr" => $dr_cr, ":account" => $account));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['amount'];
            return $first_rec;
        }

//chosen individual field
        function get_chosen_purchase_invoice_line_item($id) {

            $db = new dbconnection();
            $sql = "select   purchase_invoice_line.item from purchase_invoice_line where purchase_invoice_line_id=:purchase_invoice_line_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':purchase_invoice_line_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['item'];
            echo $field;
        }

        function get_chosen_purchase_invoice_line_measurement($id) {

            $db = new dbconnection();
            $sql = "select   purchase_invoice_line.measurement from purchase_invoice_line where purchase_invoice_line_id=:purchase_invoice_line_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':purchase_invoice_line_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['measurement'];
            echo $field;
        }

        function get_chosen_purchase_invoice_line_pur_invoice_header($id) {

            $db = new dbconnection();
            $sql = "select   purchase_invoice_line.pur_invoice_header from purchase_invoice_line where purchase_invoice_line_id=:purchase_invoice_line_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':purchase_invoice_line_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['pur_invoice_header'];
            echo $field;
        }

        function get_chosen_purchase_invoice_line_cost($id) {

            $db = new dbconnection();
            $sql = "select   purchase_invoice_line.cost from purchase_invoice_line where purchase_invoice_line_id=:purchase_invoice_line_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':purchase_invoice_line_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['cost'];
            echo $field;
        }

        function get_chosen_purchase_invoice_line_discount($id) {

            $db = new dbconnection();
            $sql = "select   purchase_invoice_line.discount from purchase_invoice_line where purchase_invoice_line_id=:purchase_invoice_line_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':purchase_invoice_line_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['discount'];
            echo $field;
        }

        function get_chosen_purchase_invoice_line_amount($id) {

            $db = new dbconnection();
            $sql = "select   purchase_invoice_line.amount from purchase_invoice_line where purchase_invoice_line_id=:purchase_invoice_line_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':purchase_invoice_line_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['amount'];
            echo $field;
        }

        function get_chosen_purchase_invoice_line_pur_order_line($id) {

            $db = new dbconnection();
            $sql = "select   purchase_invoice_line.pur_order_line from purchase_invoice_line where purchase_invoice_line_id=:purchase_invoice_line_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':purchase_invoice_line_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['pur_order_line'];
            echo $field;
        }

        function get_chosen_purchase_invoice_line_inventory_control_journal($id) {

            $db = new dbconnection();
            $sql = "select   purchase_invoice_line.inventory_control_journal from purchase_invoice_line where purchase_invoice_line_id=:purchase_invoice_line_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':purchase_invoice_line_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['inventory_control_journal'];
            echo $field;
        }

        function get_chosen_purchase_invoice_line_quantity($id) {

            $db = new dbconnection();
            $sql = "select   purchase_invoice_line.quantity from purchase_invoice_line where purchase_invoice_line_id=:purchase_invoice_line_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':purchase_invoice_line_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['quantity'];
            echo $field;
        }

        function get_chosen_purchase_invoice_line_received_quantity($id) {

            $db = new dbconnection();
            $sql = "select   purchase_invoice_line.received_quantity from purchase_invoice_line where purchase_invoice_line_id=:purchase_invoice_line_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':purchase_invoice_line_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['received_quantity'];
            echo $field;
        }

        function All_purchase_invoice_line() {
            $c = 0;
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  purchase_invoice_line_id   from purchase_invoice_line";
            foreach ($db->query($sql) as $row) {
                $c += 1;
            }
            return $c;
        }

        function get_first_purchase_invoice_line() {
            $con = new dbconnection();
            $sql = "select purchase_invoice_line.purchase_invoice_line_id from purchase_invoice_line
                    order by purchase_invoice_line.purchase_invoice_line_id asc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['purchase_invoice_line_id'];
            return $first_rec;
        }

        function get_last_purchase_invoice_line() {
            $con = new dbconnection();
            $sql = "select purchase_invoice_line.purchase_invoice_line_id from purchase_invoice_line
                    order by purchase_invoice_line.purchase_invoice_line_id desc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['purchase_invoice_line_id'];
            return $first_rec;
        }

        function list_purchase_order_header($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from purchase_order_header";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Vendor </td><td> General ledger Line </td><td> Date </td><td> Number </td><td> Vendor Invoice Number </td><td> Description </td><td> Payment Term </td><td> Reference Number </td><td> Status </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['purchase_order_header_id']; ?>
                        </td>
                        <td class="vendor_id_cols purchase_order_header " title="purchase_order_header" >
                            <?php echo $this->_e($row['vendor']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['gen_ledger_header']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['number']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['vendor_invoice_number']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['description']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['payment_term']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['reference_number']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['status']); ?>
                        </td>


                        <td>
                            <a href="#" class="purchase_order_header_delete_link" style="color: #000080;" value="
                               <?php echo $row['purchase_order_header_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="purchase_order_header_update_link" style="color: #000080;" value="
                               <?php echo $row['purchase_order_header_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_purchase_order_header_vendor($id) {

                $db = new dbconnection();
                $sql = "select   purchase_order_header.vendor from purchase_order_header where purchase_order_header_id=:purchase_order_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_order_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['vendor'];
                echo $field;
            }

            function get_chosen_purchase_order_header_gen_ledger_header($id) {

                $db = new dbconnection();
                $sql = "select   purchase_order_header.gen_ledger_header from purchase_order_header where purchase_order_header_id=:purchase_order_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_order_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['gen_ledger_header'];
                echo $field;
            }

            function get_chosen_purchase_order_header_date($id) {

                $db = new dbconnection();
                $sql = "select   purchase_order_header.date from purchase_order_header where purchase_order_header_id=:purchase_order_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_order_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['date'];
                echo $field;
            }

            function get_chosen_purchase_order_header_number($id) {

                $db = new dbconnection();
                $sql = "select   purchase_order_header.number from purchase_order_header where purchase_order_header_id=:purchase_order_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_order_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['number'];
                echo $field;
            }

            function get_chosen_purchase_order_header_vendor_invoice_number($id) {

                $db = new dbconnection();
                $sql = "select   purchase_order_header.vendor_invoice_number from purchase_order_header where purchase_order_header_id=:purchase_order_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_order_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['vendor_invoice_number'];
                echo $field;
            }

            function get_chosen_purchase_order_header_description($id) {

                $db = new dbconnection();
                $sql = "select   purchase_order_header.description from purchase_order_header where purchase_order_header_id=:purchase_order_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_order_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['description'];
                echo $field;
            }

            function get_chosen_purchase_order_header_payment_term($id) {

                $db = new dbconnection();
                $sql = "select   purchase_order_header.payment_term from purchase_order_header where purchase_order_header_id=:purchase_order_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_order_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['payment_term'];
                echo $field;
            }

            function get_chosen_purchase_order_header_reference_number($id) {

                $db = new dbconnection();
                $sql = "select   purchase_order_header.reference_number from purchase_order_header where purchase_order_header_id=:purchase_order_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_order_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['reference_number'];
                echo $field;
            }

            function get_chosen_purchase_order_header_status($id) {

                $db = new dbconnection();
                $sql = "select   purchase_order_header.status from purchase_order_header where purchase_order_header_id=:purchase_order_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_order_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['status'];
                echo $field;
            }

            function All_purchase_order_header() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  purchase_order_header_id   from purchase_order_header";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_purchase_order_header() {
                $con = new dbconnection();
                $sql = "select purchase_order_header.purchase_order_header_id from purchase_order_header
                    order by purchase_order_header.purchase_order_header_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['purchase_order_header_id'];
                return $first_rec;
            }

            function get_last_purchase_order_header() {
                $con = new dbconnection();
                $sql = "select purchase_order_header.purchase_order_header_id from purchase_order_header
                    order by purchase_order_header.purchase_order_header_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['purchase_order_header_id'];
                return $first_rec;
            }

            function get_data_status($status, $user_cat, $link, $table, $id) {//This is the status of the request and purchase
                if ($user_cat == 'mates' || $user_cat == 'daf') {
                    if ($status == 0) {
                        ?>   <td>  <a href="#" class="<?php echo $link; ?>" style="color: #000080;"data-who="DF"  data-table="<?php echo $table; ?>"    data-table_id="<?php echo $id; ?>">View</a>   </td>  <?php
                    } else {
                        ?>  <td> <a href="#" class="<?php echo $link; ?>" data-table="<?php echo $table; ?>" data-table_id="<?php echo $id; ?>">View (Approved) </a></td>   <?php
                }
            } else if ($user_cat == 'admin' || $user_cat == 'mates') {
                if ($status == 0) {
                    ?>
                    <td> <a href="#"  class="<?php echo $link; ?>" style="color: #000080;" data-table="<?php echo $table; ?>" data-who="DG"   data-table_id="<?php echo $id; ?>"> Waiting for DAF Approval </a></td> <?php
                } else if ($status == 1) {
                    ?>
                    <td>
                        <a href="#"  class="<?php echo $link; ?>" style="color: #000080;" data-table="<?php echo $table; ?>" data-who="DG"   data-table_id="<?php echo $id; ?>">View</a>
                    </td>
                <?php } else if ($status == 2) {
                    ?>
                    <td> <a href="#" class="<?php echo $link; ?>" data-table="<?php echo $table; ?>" data-table_id="<?php echo $id; ?>">View (Approved)</a></td>
                    <?php
                }
            } else if ($user_cat != 'daf' && $user_cat != 'admin' || $user_cat == 'mates') {
                if ($status == 1) {
                    ?>
                    <td>
                        <a href="#" class="<?php echo $link; ?>" style="color: #000080;" data-table="<?php echo $table; ?>" data-who="DG"   data-table_id="<?php echo $id; ?>">View</a>
                    </td>
                    <?php
                } else if ($status == 0) {
                    ?>
                    <td>
                        <a href="#" class="<?php echo $link; ?>" style="color: #000080;" data-table="<?php echo $table; ?>" data-who="DG"   data-table_id="<?php echo $id; ?>">View</a>
                    </td>

                    <?php
                } else if ($status == 2) {
                    ?>   <td>
                        <a href="#" class="<?php echo $link; ?>" style="color: #000080;" data-table="<?php echo $table; ?>" data-who="DG"   data-table_id="<?php echo $id; ?>">View</a>
                    </td>   <?php
                }
            }
        }

        function list_purchase_order_line() {
            try {
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = " select min(purchase_order_line.purchase_order_line_id) as purchase_order_line_id,min(p_field.name) as field, main_request.main_request_id, count(p_budget_items.p_budget_items_id) as n,    min( p_budget_items.item_name) as item_name, min(purchase_order_line.entry_date) as entry_date,  min(purchase_order_line.User) as User,  sum(purchase_order_line.quantity) as quantity ,  sum(purchase_order_line.unit_cost) as unit_cost, sum(purchase_order_line.amount) as amount, min(purchase_order_line.request) as request, min( purchase_order_line.measurement) as measurement,  min(purchase_order_line.supplier) as supplier, min(user.Firstname) as Firstname,min(user.Lastname) as Lastname, min(party.name) as supplier,min(purchase_order_line.status) as status from purchase_order_line "
                        . " join user on user.StaffID=purchase_order_line.User"
                        . " join party on party.party_id=purchase_order_line.supplier "
                        . " join p_request on p_request.p_request_id=purchase_order_line.request"
                        . " join main_request on main_request.Main_request_id = p_request.main_req "
                        . " join p_budget_items on p_request.item=p_budget_items.p_budget_items_id  "
                        . " join p_field on p_field.p_field_id=p_request.field"
                        . " group by main_request.main_request_id   "
                        . "   "
                        . "order by main_request.main_request_id desc";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                ?>
                <table class="dataList_table">
                    <thead><tr> 
                            <td> S/N </td>
                            <td> Item </td>
                            <td> Field </td>
                            <td class="off"> Quantity </td>
                            <td class="off"> Unit Cost </td>
                            <td> Total Amount </td>
                            <td class="off"> Request </td><td> Supplier </td>
                            <td class="off"> Supplier </td> <td> Entry Date </td> 
                            <td> User </td>

                            <?php if (isset($_SESSION['shall_delete'])) { ?>   <td>Delete</td><td>Update</td><?php } ?> <td> Action </td></tr></thead>
                    <?php
                    $pages = 1;
                    while ($row = $stmt->fetch()) {
                        $n = ($row['n'] > 1 ? ' items' : ' item');
                        ?><tr class="" data-table_id="<?php echo $row['purchase_order_line_id']; ?>"     data-bind="purchase_order_line"> 
                            <td>
                                <?php echo $row['purchase_order_line_id']; ?>
                            </td>
                            <td>
                                <?php echo '<span style="color: red; background-color: #fff;">' . $row['n'] . '</span>' . $n . ' (' . $row['item_name'] . ',..)'; ?>
                            </td>
                            <td><?php echo $row['field']; ?></td>
                            <td class="off">
                                <?php echo $this->_e($row['quantity']); ?>
                            </td>
                            <td class="off">
                                <?php echo $this->_e(number_format($row['unit_cost'])); ?>
                            </td>
                            <td>
                                <?php echo $this->_e(number_format($row['amount'])); ?>
                            </td>
                            <td class="off">
                                <?php echo $this->_e($row['request']); ?>
                            </td>
                            <td class="off">
                                <?php echo $this->_e($row['supplier']); ?>
                            </td>
                            <td>
                                <?php echo $this->_e($row['supplier']); ?>
                            </td>  
                            <td class="entry_date_id_cols purchase_order_line " title="purchase_order_line" >
                                <?php echo $this->_e($row['entry_date']); ?>     </td> 
                            <td>
                                <?php
                                echo $this->_e($row['Firstname']) . '  ';
                                echo $this->_e($row['Lastname']);
                                ?>
                            </td>   
                            <!--<td>  <a href="#" class="data_details_link" style="color: #000080;" data-who="DF"  data-table="purchase_order"    data-id="<?php echo $row['main_request_id']; ?>">View</a>   </td>-->  
                            <?php if (isset($_SESSION['shall_delete'])) { ?>
                                <td>
                                    <a href="#" class="purchase_order_line_delete_link" style="color: #000080;" data-id_delete="purchase_order_line_id"  data-table="
                                       <?php echo $row['purchase_order_line_id']; ?>">Delete</a>
                                </td>
                                <td>
                                    <a href="#" class="purchase_order_line_update_link" style="color: #000080;" value="
                                       <?php echo $row['purchase_order_line_id']; ?>">Update</a>
                                </td><?php
                            }
                            $this->get_data_status($row['status'], $_SESSION['cat'], 'purchase_order_view_link', 'purchase_order', $row['main_request_id']);
                            ?>
                        </tr>
                        <?php
                        $pages += 1;
                    }
                    ?></table>  <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

//chosen individual field
        function get_chosen_purchase_order_line_pur_order_header($id) {

            $db = new dbconnection();
            $sql = "select   purchase_order_line.pur_order_header from purchase_order_line where purchase_order_line_id=:purchase_order_line_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':purchase_order_line_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['pur_order_header'];
            echo $field;
        }

        function get_chosen_purchase_order_line_item($id) {

            $db = new dbconnection();
            $sql = "select   purchase_order_line.item from purchase_order_line where purchase_order_line_id=:purchase_order_line_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':purchase_order_line_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['item'];
            echo $field;
        }

        function get_chosen_purchase_order_line_measurement($id) {

            $db = new dbconnection();
            $sql = "select   purchase_order_line.measurement from purchase_order_line where purchase_order_line_id=:purchase_order_line_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':purchase_order_line_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['measurement'];
            echo $field;
        }

        function get_chosen_purchase_order_line_quanitity($id) {

            $db = new dbconnection();
            $sql = "select   purchase_order_line.quanitity from purchase_order_line where purchase_order_line_id=:purchase_order_line_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':purchase_order_line_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['quanitity'];
            echo $field;
        }

        function get_chosen_purchase_order_line_cost($id) {

            $db = new dbconnection();
            $sql = "select   purchase_order_line.cost from purchase_order_line where purchase_order_line_id=:purchase_order_line_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':purchase_order_line_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['cost'];
            echo $field;
        }

        function get_chosen_purchase_order_line_discount($id) {

            $db = new dbconnection();
            $sql = "select   purchase_order_line.discount from purchase_order_line where purchase_order_line_id=:purchase_order_line_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':purchase_order_line_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['discount'];
            echo $field;
        }

        function get_chosen_purchase_order_line_amount($id) {

            $db = new dbconnection();
            $sql = "select   purchase_order_line.amount from purchase_order_line where purchase_order_line_id=:purchase_order_line_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':purchase_order_line_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['amount'];
            echo $field;
        }

        function All_purchase_order_line() {
            $c = 0;
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  purchase_order_line_id   from purchase_order_line";
            foreach ($db->query($sql) as $row) {
                $c += 1;
            }
            return $c;
        }

        function get_first_purchase_order_line() {
            $con = new dbconnection();
            $sql = "select purchase_order_line.purchase_order_line_id from purchase_order_line
                    order by purchase_order_line.purchase_order_line_id asc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['purchase_order_line_id'];
            return $first_rec;
        }

        function get_last_purchase_order_line() {
            $con = new dbconnection();
            $sql = "select purchase_order_line.purchase_order_line_id from purchase_order_line
                    order by purchase_order_line.purchase_order_line_id desc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['purchase_order_line_id'];
            return $first_rec;
        }

        function list_purchase_receit_header($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from purchase_receit_header";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> General Ledger Header </td><td> Date </td><td> Status </td><td> Number </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['purchase_receit_header_id']; ?>
                        </td>
                        <td class="gen_ledger_header_id_cols purchase_receit_header " title="purchase_receit_header" >
                            <?php echo $this->_e($row['gen_ledger_header']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['status']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['number']); ?>
                        </td>


                        <td>
                            <a href="#" class="purchase_receit_header_delete_link" style="color: #000080;" value="
                               <?php echo $row['purchase_receit_header_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="purchase_receit_header_update_link" style="color: #000080;" value="
                               <?php echo $row['purchase_receit_header_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_purchase_receit_header_gen_ledger_header($id) {

                $db = new dbconnection();
                $sql = "select   purchase_receit_header.gen_ledger_header from purchase_receit_header where purchase_receit_header_id=:purchase_receit_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_receit_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['gen_ledger_header'];
                echo $field;
            }

            function get_chosen_purchase_receit_header_date($id) {

                $db = new dbconnection();
                $sql = "select   purchase_receit_header.date from purchase_receit_header where purchase_receit_header_id=:purchase_receit_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_receit_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['date'];
                echo $field;
            }

            function get_chosen_purchase_receit_header_status($id) {

                $db = new dbconnection();
                $sql = "select   purchase_receit_header.status from purchase_receit_header where purchase_receit_header_id=:purchase_receit_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_receit_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['status'];
                echo $field;
            }

            function get_chosen_purchase_receit_header_number($id) {

                $db = new dbconnection();
                $sql = "select   purchase_receit_header.number from purchase_receit_header where purchase_receit_header_id=:purchase_receit_header_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_receit_header_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['number'];
                echo $field;
            }

            function All_purchase_receit_header() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  purchase_receit_header_id   from purchase_receit_header";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_purchase_receit_header() {
                $con = new dbconnection();
                $sql = "select purchase_receit_header.purchase_receit_header_id from purchase_receit_header
                    order by purchase_receit_header.purchase_receit_header_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['purchase_receit_header_id'];
                return $first_rec;
            }

            function get_last_purchase_receit_header() {
                $con = new dbconnection();
                $sql = "select purchase_receit_header.purchase_receit_header_id from purchase_receit_header
                    order by purchase_receit_header.purchase_receit_header_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['purchase_receit_header_id'];
                return $first_rec;
            }

            function list_purchase_receit_line($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from purchase_receit_line "
                        . " join user on user.StaffID=purchase_receit_line.User";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Amount </td><td class="off"> Purchase Invoice </td><td> Entry Date </td><td> User </td>
                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>Delete</td><td>Update</td><?php } ?></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr class="clickable_row" data-table_id="<?php echo $row['purchase_receit_line_id']; ?>"     data-bind="purchase_receit_line"> 

                        <td>
                            <?php echo $row['purchase_receit_line_id']; ?>
                        </td>
                        <td class="amount_id_cols purchase_receit_line " title="purchase_receit_line" >
                            <?php echo $this->_e($row['amount']); ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['purchase_invoice']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>
                        <td>
                            <?php
                            echo $this->_e($row['Firstname']) . '  ';
                            echo $this->_e($row['Lastname']);
                            ?>
                        </td>

                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>
                                <a href="#" class="purchase_receit_line_delete_link" style="color: #000080;" data-id_delete="purchase_receit_line_id"  data-table="
                                   <?php echo $row['purchase_receit_line_id']; ?>">Delete</a>
                            </td>
                            <td>
                                <a href="#" class="purchase_receit_line_update_link" style="color: #000080;" value="
                                   <?php echo $row['purchase_receit_line_id']; ?>">Update</a>
                            </td><?php } ?></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_purchase_receit_line_pur_recceit_header($id) {

                $db = new dbconnection();
                $sql = "select   purchase_receit_line.pur_recceit_header from purchase_receit_line where purchase_receit_line_id=:purchase_receit_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_receit_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['pur_recceit_header'];
                echo $field;
            }

            function get_chosen_purchase_receit_line_item($id) {

                $db = new dbconnection();
                $sql = "select   purchase_receit_line.item from purchase_receit_line where purchase_receit_line_id=:purchase_receit_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_receit_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['item'];
                echo $field;
            }

            function get_chosen_purchase_receit_line_Inventory_control_Journal($id) {

                $db = new dbconnection();
                $sql = "select   purchase_receit_line.Inventory_control_Journal from purchase_receit_line where purchase_receit_line_id=:purchase_receit_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_receit_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['Inventory_control_Journal'];
                echo $field;
            }

            function get_chosen_purchase_receit_line_measurement($id) {

                $db = new dbconnection();
                $sql = "select   purchase_receit_line.measurement from purchase_receit_line where purchase_receit_line_id=:purchase_receit_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_receit_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['measurement'];
                echo $field;
            }

            function get_chosen_purchase_receit_line_quantity($id) {

                $db = new dbconnection();
                $sql = "select   purchase_receit_line.quantity from purchase_receit_line where purchase_receit_line_id=:purchase_receit_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_receit_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['quantity'];
                echo $field;
            }

            function get_chosen_purchase_receit_line_received_qty($id) {

                $db = new dbconnection();
                $sql = "select   purchase_receit_line.received_qty from purchase_receit_line where purchase_receit_line_id=:purchase_receit_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_receit_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['received_qty'];
                echo $field;
            }

            function get_chosen_purchase_receit_line_cost($id) {

                $db = new dbconnection();
                $sql = "select   purchase_receit_line.cost from purchase_receit_line where purchase_receit_line_id=:purchase_receit_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_receit_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['cost'];
                echo $field;
            }

            function get_chosen_purchase_receit_line_discount($id) {

                $db = new dbconnection();
                $sql = "select   purchase_receit_line.discount from purchase_receit_line where purchase_receit_line_id=:purchase_receit_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_receit_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['discount'];
                echo $field;
            }

            function get_chosen_purchase_receit_line_amount($id) {

                $db = new dbconnection();
                $sql = "select   purchase_receit_line.amount from purchase_receit_line where purchase_receit_line_id=:purchase_receit_line_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':purchase_receit_line_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['amount'];
                echo $field;
            }

            function All_purchase_receit_line() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  purchase_receit_line_id   from purchase_receit_line";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_purchase_receit_line() {
                $con = new dbconnection();
                $sql = "select purchase_receit_line.purchase_receit_line_id from purchase_receit_line
                    order by purchase_receit_line.purchase_receit_line_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['purchase_receit_line_id'];
                return $first_rec;
            }

            function get_last_purchase_receit_line() {
                $con = new dbconnection();
                $sql = "select purchase_receit_line.purchase_receit_line_id from purchase_receit_line
                    order by purchase_receit_line.purchase_receit_line_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['purchase_receit_line_id'];
                return $first_rec;
            }

            function list_Inventory_control_journal($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from Inventory_control_journal";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Measurement </td><td> Item </td><td> Document Type </td><td> In Quantity </td><td> Out Quantity </td><td> Date </td><td> Total Cost </td><td> Total Amount </td><td> Is reverse </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['Inventory_control_journal_id']; ?>
                        </td>
                        <td class="measurement_id_cols Inventory_control_journal " title="Inventory_control_journal" >
                            <?php echo $this->_e($row['measurement']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['item']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['doc_type']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['In_qty']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['Out_qty']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['total_cost']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['tot_amount']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['is_reverse']); ?>
                        </td>


                        <td>
                            <a href="#" class="Inventory_control_journal_delete_link" style="color: #000080;" value="
                               <?php echo $row['Inventory_control_journal_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="Inventory_control_journal_update_link" style="color: #000080;" value="
                               <?php echo $row['Inventory_control_journal_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_Inventory_control_journal_measurement($id) {

                $db = new dbconnection();
                $sql = "select   Inventory_control_journal.measurement from Inventory_control_journal where Inventory_control_journal_id=:Inventory_control_journal_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':Inventory_control_journal_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['measurement'];
                echo $field;
            }

            function get_chosen_Inventory_control_journal_item($id) {

                $db = new dbconnection();
                $sql = "select   Inventory_control_journal.item from Inventory_control_journal where Inventory_control_journal_id=:Inventory_control_journal_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':Inventory_control_journal_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['item'];
                echo $field;
            }

            function get_chosen_Inventory_control_journal_doc_type($id) {

                $db = new dbconnection();
                $sql = "select   Inventory_control_journal.doc_type from Inventory_control_journal where Inventory_control_journal_id=:Inventory_control_journal_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':Inventory_control_journal_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['doc_type'];
                echo $field;
            }

            function get_chosen_Inventory_control_journal_In_qty($id) {

                $db = new dbconnection();
                $sql = "select   Inventory_control_journal.In_qty from Inventory_control_journal where Inventory_control_journal_id=:Inventory_control_journal_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':Inventory_control_journal_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['In_qty'];
                echo $field;
            }

            function get_chosen_Inventory_control_journal_Out_qty($id) {

                $db = new dbconnection();
                $sql = "select   Inventory_control_journal.Out_qty from Inventory_control_journal where Inventory_control_journal_id=:Inventory_control_journal_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':Inventory_control_journal_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['Out_qty'];
                echo $field;
            }

            function get_chosen_Inventory_control_journal_date($id) {

                $db = new dbconnection();
                $sql = "select   Inventory_control_journal.date from Inventory_control_journal where Inventory_control_journal_id=:Inventory_control_journal_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':Inventory_control_journal_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['date'];
                echo $field;
            }

            function get_chosen_Inventory_control_journal_total_cost($id) {

                $db = new dbconnection();
                $sql = "select   Inventory_control_journal.total_cost from Inventory_control_journal where Inventory_control_journal_id=:Inventory_control_journal_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':Inventory_control_journal_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['total_cost'];
                echo $field;
            }

            function get_chosen_Inventory_control_journal_tot_amount($id) {

                $db = new dbconnection();
                $sql = "select   Inventory_control_journal.tot_amount from Inventory_control_journal where Inventory_control_journal_id=:Inventory_control_journal_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':Inventory_control_journal_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['tot_amount'];
                echo $field;
            }

            function get_chosen_Inventory_control_journal_is_reverse($id) {

                $db = new dbconnection();
                $sql = "select   Inventory_control_journal.is_reverse from Inventory_control_journal where Inventory_control_journal_id=:Inventory_control_journal_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':Inventory_control_journal_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['is_reverse'];
                echo $field;
            }

            function All_Inventory_control_journal() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  Inventory_control_journal_id   from Inventory_control_journal";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_Inventory_control_journal() {
                $con = new dbconnection();
                $sql = "select Inventory_control_journal.Inventory_control_journal_id from Inventory_control_journal
                    order by Inventory_control_journal.Inventory_control_journal_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['Inventory_control_journal_id'];
                return $first_rec;
            }

            function get_last_Inventory_control_journal() {
                $con = new dbconnection();
                $sql = "select Inventory_control_journal.Inventory_control_journal_id from Inventory_control_journal
                    order by Inventory_control_journal.Inventory_control_journal_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['Inventory_control_journal_id'];
                return $first_rec;
            }

            function get_acc_type_in_combo() {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select acc_type.acc_type_id,   acc_type.name from acc_type";
                ?>
            <select class="textbox cbo_acc_type"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['acc_type_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_acc_class_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select acc_class.acc_class_id,   acc_class.name from acc_class";
            ?>
            <select class="textbox cbo_acc_class"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['acc_class_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_general_ledge_header_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select general_ledge_header.general_ledge_header_id,   general_ledge_header.name from general_ledge_header";
            ?>
            <select class="textbox cbo_general_ledge_header"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['general_ledge_header_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_main_contra_acc_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select main_contra_acc.main_contra_acc_id,   main_contra_acc.name from main_contra_acc";
            ?>
            <select class="textbox cbo_main_contra_acc"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['main_contra_acc_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_general_ledger_header_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select general_ledger_header.general_ledger_header_id,   general_ledger_header.name from general_ledger_header";
            ?>
            <select class="textbox cbo_general_ledger_header"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['general_ledger_header_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_account_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select account.account_id,   account.name,account_type.name as type "
                    . " from account"
                    . " join account_type on account.acc_type=account_type.account_type_id "
                    . " ";
            ?>
            <select name="acc_name_combo" disabled class="textbox cbo_account"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['account_id'] . ">" . $row['name'] . "(" . $row['type'] . ") </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_account_in_combo_enabled() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select account.account_id,   account.name, account_type.name as type from account "
                    . " join account_type on account.acc_type=account_type.account_type_id "
                    . " ";
            ?>
            <select name="acc_name_combo"   class="textbox cbo_account"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['account_id'] . ">" . $row['name'] . "  <b>(" . $row['type'] . ")</b> </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_account_in_combo_enabled_receivable() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select account.account_id,   account.name, account_type.name as type from account "
                    . " join account_type on account.acc_type=account_type.account_type_id "
                    . " where account_type.name='income'"
                    . " group by account.name";
            ?>
            <select name="acc_name_combo"   class="textbox cbo_account"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['account_id'] . ">" . $row['name'] . "  <b>(" . $row['type'] . ")</b> </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_account_in_combo_income_income_section_statement() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select account.account_id, account.name, account_type.name as type from account "
                    . " join account_type on account.acc_type=account_type.account_type_id "
                    . " where account_type.name='Income Statement' and account.is_contra_acc='no' and book_section='income'"
                    . "  ";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            ?> <select name="acc_name_combo"   class="textbox cbo_account"><option></option> <?php
            while ($row = $stmt->fetch()) {
                echo "<option value=" . $row['account_id'] . ">" . $row['name'] . "  <b>(" . strtoupper($row['type']) . ")</b> </option>";
            }
            ?>
            </select>
            <?php
        }

        function get_account_in_combo_income_expense_section_statement() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select account.account_id,   account.name, account_type.name as type from account "
                    . " join account_type on account.acc_type=account_type.account_type_id "
                    . "  where account_type.name='Income Statement' and account.is_contra_acc='no' and book_section='expense'"
                    . "  ";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            ?> <select name="acc_name_combo"   class="textbox cbo_account"><option></option> <?php
            while ($row = $stmt->fetch()) {
                echo "<option value=" . $row['account_id'] . ">" . $row['name'] . "  <b>(" . strtoupper($row['type']) . ")</b> </option>";
            }
            ?>
            </select>
            <?php
        }

        function get_expenses_sml_cbo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select account.account_id,   account.name, account_type.name as type from account "
                    . " join account_type on account.acc_type=account_type.account_type_id "
                    . "  where account_type.name='Balance sheet' and account.is_contra_acc='no'  or book_section='current asset' or book_section='fixed asset'"
                    . "  ";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            ?> <select name="expense_cbo" style="width:200px;"  class="textbox cbo_account "><option></option> <?php
            while ($row = $stmt->fetch()) {
                echo "<option value=" . $row['account_id'] . ">" . $row['name'] . "  <b>(" . strtoupper($row['type']) . ")</b> </option>";
            }
            ?>
            </select>
            <?php
        }

        function get_asset_sml_cbo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "   select account.account_id,   account.name, account_type.name as type from account "
                    . " join account_type on account.acc_type=account_type.account_type_id "
                    . " where account_type.name='Balance Sheet' or account_type.name='income statement'  and book_section='asset' or  book_section='income' "
                    . "and account.is_contra_acc='no' or book_section='current asset' or book_section='fixed asset' "
                    . "  ";
            ?>
            <select name="asset_cbo" style="width:200px;"  class="textbox push_right cbo_account cbo_aset"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['account_id'] . ">" . $row['name'] . "  <b>(" . strtoupper($row['type']) . ")</b> </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_account_in_combo_balance_liabilitysection_sheet() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "   select account.account_id,   account.name, account_type.name as type from account "
                    . " join account_type on account.acc_type=account_type.account_type_id "
                    . " where account_type.name='Balance Sheet' and book_section='liability' or book_section='equity'"
                    . "  ";
            ?>
            <select name="acc_name_combo"   class="textbox cbo_account full_center_two_h heit_free"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['account_id'] . ">" . $row['name'] . "  <b>(" . strtoupper($row['type']) . ")</b> </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_account_in_combo_balance_assetsection_sheet() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "   select account.account_id,   account.name, account_type.name as type from account "
                    . " join account_type on account.acc_type=account_type.account_type_id "
                    . " where account_type.name='Balance Sheet' and book_section='asset'"
                    . " and account.is_contra_acc='no' or book_section='current asset' or book_section='fixed asset' "
                    . "  ";
            ?>
            <select name="acc_name_combo"   class="textbox cbo_account cbo_aset"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['account_id'] . ">" . $row['name'] . "  <b>(" . strtoupper($row['type']) . ")</b> </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_account_in_combo_bal_assetsect_cash() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "   select account.account_id,   account.name, account_type.name as type from account "
                    . " join account_type on account.acc_type=account_type.account_type_id "
                    . " join account_class on account_class.account_class_id=account.acc_class "
                    . " where account_type.name='Balance Sheet' and book_section='asset' and account_class.name='Cash_hand_bank'"
                    . "and account.is_contra_acc='no' or book_section='current asset' or book_section='fixed asset' "
                    . "  ";
            ?>
            <select name="acc_name_combo"   class="textbox cbo_account"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['account_id'] . ">" . $row['name'] . "  <b>(" . strtoupper($row['type']) . ")</b> </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_account_in_combo_enabled_payable() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select account.account_id,   account.name, account_type.name as type from account "
                    . " join account_type on account.acc_type=account_type.account_type_id "
                    . " where account_type.name='expense'"
                    . " group by account.name";
            ?>
            <select name="acc_name_combo"   class="textbox cbo_account"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['account_id'] . ">" . $row['name'] . "  <b>(" . $row['type'] . ")</b> </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_customer_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select customer.customer_id, customer.name from customer";
            ?>
            <select class="textbox cbo_customer"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['customer_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_gen_ledger_header_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select gen_ledger_header.gen_ledger_header_id, gen_ledger_header.name from gen_ledger_header";
            ?>
            <select class="textbox cbo_gen_ledger_header"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['gen_ledger_header_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_accountid_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select account.accountid_id, account.name from account";
            ?>
            <select class="textbox cbo_accountid"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['accountid_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_journal_entry_header_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select journal_entry_header.journal_entry_header_id  from journal_entry_header";
            ?>
            <select class="textbox cbo_journal_entry_header"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['journal_entry_header_id'] . ">" . $row['journal_entry_header_id'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_sales_accid_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select sales_accid.sales_accid_id, sales_accid.name from sales_accid";
            ?>
            <select class="textbox cbo_sales_accid"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['sales_accid_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_purchase_accid_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select purchase_accid.purchase_accid_id, purchase_accid.name from purchase_accid";
            ?>
            <select class="textbox cbo_purchase_accid"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['purchase_accid_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_party_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select party.party_id, party.name from party";
            ?>
            <select class="textbox cbo_party"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['party_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_party_id_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select party_id.party_id_id, party_id.name from party_id";
            ?>
            <select class="textbox cbo_party_id"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['party_id_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_contact_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select contact.contact_id, contact.name from contact";
            ?>
            <select class="textbox cbo_contact"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['contact_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_tax_group_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select tax_group.tax_group_id, tax_group.name from tax_group";
            ?>
            <select class="textbox cbo_tax_group"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['tax_group_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_acc_rec_accid_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select account.account_id, account.name from account"
                    . " join account_type on account_type.account_type_id = account.acc_type
                    where account_type.name = 'accounts Receivable'";
            ?>
            <select class="textbox cbo_acc_rec_accid"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['account_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_item_group_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select item_group.item_group_id, item_group.name from item_group";
            ?>
            <select class="textbox cbo_item_group"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['item_group_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_item_category_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select item_category.item_category_id, item_category.name from item_category";
            ?>
            <select class="textbox cbo_item_category"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['item_category_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_inventory_accid_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select inventory_accid.inventory_accid_id, inventory_accid.name from inventory_accid";
            ?>
            <select class="textbox cbo_inventory_accid"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['inventory_accid_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_cost_good_sold_accid_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select cost_good_sold_accid.cost_good_sold_accid_id, cost_good_sold_accid.name from cost_good_sold_accid";
            ?>
            <select class="textbox cbo_cost_good_sold_accid"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['cost_good_sold_accid_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_assembly_accid_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select assembly_accid.assembly_accid_id, assembly_accid.name from assembly_accid";
            ?>
            <select class="textbox cbo_assembly_accid"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['assembly_accid_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_vendor_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select vendor.vendor_id, vendor.name from vendor";
            ?>
            <select class="textbox cbo_vendor"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['vendor_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_sales_invoice_line_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select sales_invoice_line.sales_invoice_line_id, sales_invoice_line.name from sales_invoice_line";
            ?>
            <select class="textbox cbo_sales_invoice_line"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['sales_invoice_line_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_measurement_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select measurement.measurement_id, measurement.code from measurement";
            ?>
            <select class="textbox bgt_txt_msrment cbo_measurement cbo_onfly_measurement_change">
                <option></option>
                <option value="fly_new_measurement">--Add new--</option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['measurement_id'] . ">" . $row['code'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_sales_delivery_header_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select sales_delivery_header.sales_delivery_header_id, sales_delivery_header.name from sales_delivery_header";
            ?>
            <select class="textbox cbo_sales_delivery_header"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['sales_delivery_header_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_sales_invoice_header_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select sales_invoice_header.sales_invoice_header_id, sales_invoice_header.name from sales_invoice_header";
            ?>
            <select class="textbox cbo_sales_invoice_header"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['sales_invoice_header_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_sales_order_line_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select sales_order_line.sales_order_line_id, sales_order_line.name from sales_order_line";
            ?>
            <select class="textbox cbo_sales_order_line"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['sales_order_line_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_payment_term_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select payment_term.payment_term_id, payment_term.name from payment_term";
            ?>
            <select class="textbox cbo_payment_term"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['payment_term_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_sales_order_header_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select sales_order_header.sales_order_header_id, sales_order_header.name from sales_order_header";
            ?>
            <select class="textbox cbo_sales_order_header"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['sales_order_header_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_sales_quote_header_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select sales_quote_header.sales_quote_header_id, sales_quote_header.name from sales_quote_header";
            ?>
            <select class="textbox cbo_sales_quote_header"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['sales_quote_header_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_customerid_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select customerid.customerid_id, customerid.name from customerid";
            ?>
            <select class="textbox cbo_customerid"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['customerid_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_inv_control_journal_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select inv_control_journal.inv_control_journal_id, inv_control_journal.name from inv_control_journal";
            ?>
            <select class="textbox cbo_inv_control_journal"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['inv_control_journal_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_purchase_order_line_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select purchase_order_line.purchase_order_line_id, purchase_order_line.name from purchase_order_line";
            ?>
            <select class="textbox cbo_purchase_order_line"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['purchase_order_line_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_pur_invoice_header_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select pur_invoice_header.pur_invoice_header_id, pur_invoice_header.name from pur_invoice_header";
            ?>
            <select class="textbox cbo_pur_invoice_header"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['pur_invoice_header_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_pur_order_line_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select pur_order_line.pur_order_line_id, pur_order_line.name from pur_order_line";
            ?>
            <select class="textbox cbo_pur_order_line"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['pur_order_line_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_inventory_control_journal_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select inventory_control_journal.inventory_control_journal_id, inventory_control_journal.name from inventory_control_journal";
            ?>
            <select class="textbox cbo_inventory_control_journal"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['inventory_control_journal_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_pur_order_header_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select pur_order_header.pur_order_header_id, pur_order_header.name from pur_order_header";
            ?>
            <select class="textbox cbo_pur_order_header"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['pur_order_header_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_pur_recceit_header_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select pur_recceit_header.pur_recceit_header_id, pur_recceit_header.name from pur_recceit_header";
            ?>
            <select class="textbox cbo_pur_recceit_header"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value = " . $row['pur_recceit_header_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function list_p_project($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_project.p_project_id, p_project.name as project, p_project.last_update_date, p_project.project_contract_no, p_project.project_spervisor, p_project.entry_date, p_project.active, p_type_project.name as type_project from p_project "
                    . " join p_type_project on p_type_project.p_type_project_id = p_project.type_project";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>
                        <td> Project </td>
                        <td class="off"> Last Update Date </td>
                        <td> Project Contract Number </td>
                        <td> Project Supervisor </td>


                        <td class="off"> Created By </td>
                        <td> Entry Date </td>
                        <td class="off"> Is Active </td>
                        <td class="off"> Status </td>
                        <td> Type of project </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 
                        <td>
                            <?php echo $row['p_project_id']; ?>
                        </td>
                        <td class="name_id_cols p_project " title="p_project" >
                            <?php echo $this->_e($row['project']); ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['last_update_date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['project_contract_no']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['project_spervisor']); ?>
                        </td>

                        <td class="off">
                            <?php echo $this->_e($row['account']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['active']); ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['status']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['type_project']); ?>
                        </td>

                        <td>
                            <a href="#" class="p_project_delete_link" style="color: #000080;" data-id_delete="p_project_id"  data-table="
                            <?php echo $row['p_project_id'];
                            ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_project_update_link" style="color: #000080;" value="
                               <?php echo $row['p_project_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function get_last_p_activity() {
                $con = new dbconnection();
                $sql = "select p_activity.p_activity_id from p_activity
                    order by p_activity.p_activity_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_activity_id'];
                return $first_rec;
            }

            function get_first_p_type_project() {
                $con = new dbconnection();
                $sql = "select p_type_project.p_type_project_id from p_type_project
                    order by p_type_project.p_type_project_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_type_project_id'];
                return $first_rec;
            }

            function get_last_p_type_project() {
                $con = new dbconnection();
                $sql = "select p_type_project.p_type_project_id from p_type_project
                    order by p_type_project.p_type_project_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_type_project_id'];
                return $first_rec;
            }

            function get_first_p_roles() {
                $con = new dbconnection();
                $sql = "select p_roles.p_roles_id from p_roles
                    order by p_roles.p_roles_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_roles_id'];
                return $first_rec;
            }

            function get_last_p_roles() {
                $con = new dbconnection();
                $sql = "select p_roles.p_roles_id from p_roles
                    order by p_roles.p_roles_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_roles_id'];
                return $first_rec;
            }

            function list_p_roles($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_roles";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> p_roles </td>
                        <td> Role Name </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_roles_id']; ?>
                        </td>
                        <td class="role_name_id_cols p_roles " title="p_roles" >
                            <?php echo $this->_e($row['role_name']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_roles_delete_link" style="color: #000080;" data-id_delete="p_roles_id"  data-table="
                               <?php echo $row['p_roles_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_roles_update_link" style="color: #000080;" value="
                               <?php echo $row['p_roles_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function get_first_role() {
                $con = new dbconnection();
                $sql = "select role.role_id from role
                    order by role.role_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['role_id'];
                return $first_rec;
            }

            function get_last_role() {
                $con = new dbconnection();
                $sql = "select role.role_id from role
                    order by role.role_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['role_id'];
                return $first_rec;
            }

            function list_role($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from role";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> role </td>
                        <td> Name </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['role_id']; ?>
                        </td>
                        <td class="name_id_cols role " title="role" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>


                        <td>
                            <a href="#" class="role_delete_link" style="color: #000080;" data-id_delete="role_id"  data-table="
                               <?php echo $row['role_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="role_update_link" style="color: #000080;" value="
                               <?php echo $row['role_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function get_Roleid_in_combo() {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select role.role_id,   role.name from role";
                ?>
            <select class="textbox cbo_Roleid"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['role_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function list_staff_positions($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from staff_positions";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> staff_positions </td>
                        <td> Name </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['staff_positions_id']; ?>
                        </td>
                        <td class="name_id_cols staff_positions " title="staff_positions" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>


                        <td>
                            <a href="#" class="staff_positions_delete_link" style="color: #000080;" data-id_delete="staff_positions_id"  data-table="
                               <?php echo $row['staff_positions_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="staff_positions_update_link" style="color: #000080;" value="
                               <?php echo $row['staff_positions_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function get_chosen_staff_positions_name($id) {

                $db = new dbconnection();
                $sql = "select   staff_positions.name from staff_positions where staff_positions_id=:staff_positions_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':staff_positions_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['name'];
                echo $field;
            }

            function All_staff_positions() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  staff_positions_id   from staff_positions";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_staff_positions() {
                $con = new dbconnection();
                $sql = "select staff_positions.staff_positions_id from staff_positions
                    order by staff_positions.staff_positions_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['staff_positions_id'];
                return $first_rec;
            }

            function get_last_staff_positions() {
                $con = new dbconnection();
                $sql = "select staff_positions.staff_positions_id from staff_positions
                    order by staff_positions.staff_positions_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['staff_positions_id'];
                return $first_rec;
            }

            function get_acc_payable_in_combo() {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select account.account_id, account.name from account"
                        . " join account_class on account_class.account_class_id=account.acc_class
                            where account_class.name='accounts payable' and is_contra_acc='yes'";
                ?>
            <select class="textbox cbo_acc_payable" name="txt_acc_payable_id"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['account_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function list_p_budget_prep($min, $max) {
            try {
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "";
                if ($_SESSION['cat'] == 'admin' || $_SESSION['cat'] == 'mates') {
                    $sql = "select  p_budget_prep.p_budget_prep_id,  p_budget_prep.project_type,p_budget_prep.activity_desc,  p_budget_prep.user,p_budget_prep.name as proj,  p_budget_prep.entry_date,  project_expectations.name as type,     p_type_project.name, p_activity.name as activity,user.Firstname,user.Lastname   from p_budget_prep "
                            . " join user on user.StaffID=p_budget_prep.user "
                            . " join p_type_project on p_type_project.p_type_project_id=p_budget_prep.project_type "
                            . "left join p_activity on p_activity.project=p_budget_prep.p_budget_prep_id "
                            . "left join project_expectations on project_expectations.project_expectations_id=p_activity.budget_type "
                            . " order by  p_type_project.name asc limit " . $min . " , " . $max;
                } else {
                    $sql = "select  p_budget_prep.p_budget_prep_id,  p_budget_prep.project_type,p_budget_prep.activity_desc,  p_budget_prep.user,p_budget_prep.name as proj,  p_budget_prep.entry_date,  project_expectations.name as type,     p_type_project.name, p_activity.name as activity,user.Firstname,user.Lastname   from p_budget_prep "
                            . " join user on user.StaffID=p_budget_prep.user "
                            . "  join p_type_project on p_type_project.p_type_project_id=p_budget_prep.project_type "
                            . "left join p_activity on p_activity.project=p_budget_prep.p_budget_prep_id "
                            . "left join project_expectations on project_expectations.project_expectations_id=p_activity.budget_type "
                            . "  where p_budget_prep.user='" . $_SESSION['userid'] . "' "
                            . " order by  p_type_project.name asc "
                            . " limit " . $min . " , " . $max;
                }

                $stmt = $db->prepare($sql);
                $stmt->execute();
                ?>
                <table class="dataList_table">
                    <thead><tr>
                            <td> S/N </td>
                            <td> Budget Line </td>
                            <td class="">Project Name</td>
                            <td>Activity</td>
                            <td> Entry Date </td>
                            <td> User </td>
                            <?php if (!empty($_SESSION['shall_delete'])) { ?>
                                <td>Delete</td>
                                <td>Update</td><?php } ?>
                        </tr>
                    </thead>
                    <?php
                    $pages = 1;
                    while ($row = $stmt->fetch()) {
                        ?><tr class="" data-table_id="<?php echo $row['p_budget_prep_id']; ?>"     data-bind="p_budget_prep"  > 
                            <td class="clickable_row" data-table_id="<?php echo $row['p_budget_prep_id']; ?>">
                                <?php echo $row['p_budget_prep_id']; ?>
                            </td>
                            <td class="project_type_id_cols p_budget_prep " title="p_budget_prep" >
                                <?php echo $this->_e($row['name']); ?>
                            </td>
                            <td class="project_type_id_cols p_budget_prep" title="p_budget_prep" >
                                <?php echo $this->_e($row['proj']); ?>
                            </td>
                            <td>
                                <?php echo $this->_e($row['activity']); ?>
                            </td>
                            <td> <?php echo $this->_e($row['entry_date']); ?></td>
                            <td> <?php echo $this->_e($row['Firstname'] . ' ' . $row['Lastname']); ?></td>
                            <?php if (!empty($_SESSION['shall_delete'])) { ?>
                                <td>
                                    <a href="#" class="delete_link" style="color: #000080;"               data-id_delete="<?php echo $row['p_budget_prep_id']; ?>"  data-table="p_budget_prep">Delete</a>
                                </td>
                                <td>
                                    <a href="#" class="p_budget_prep_update_link" style="color: #000080;" data-id_delete="<?php echo $row['p_budget_prep_id']; ?>"  data-table="p_budget_prep">Update</a>
                                </td><?php } ?>
                        </tr>
                        <?php
                        $pages += 1;
                    }
                    ?></table>
                <?php
                $this->paging($this->All_p_budget_prep());
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function get_chosen_p_budget_prep_project_type($id) {

            $db = new dbconnection();
            $sql = "select   p_budget_prep.project_type from p_budget_prep where p_budget_prep_id=:p_budget_prep_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_prep_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['project_type'];
            echo $field;
        }

        function get_chosen_fiscal_year($id) {

            $db = new dbconnection();
            $sql = "select p_budget_prep.fiscal_year from p_budget_prep where p_budget_prep.p_budget_prep_id=:p_budget_prep_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_prep_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['fiscal_year'];
            echo $field;
        }

        function get_chosen_p_budget_prep_entry_date($id) {

            $db = new dbconnection();
            $sql = "select   p_budget_prep.entry_date from p_budget_prep where p_budget_prep_id=:p_budget_prep_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_prep_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['entry_date'];
            echo $field;
        }

        function get_chosen_p_budget_prep_budget_type($id) {
            $db = new dbconnection();
            $sql = "select   p_budget_prep.project_type from p_budget_prep where p_budget_prep_id=:p_budget_prep_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_prep_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['project_type'];
            echo $field;
        }

        function get_chosen_p_budget_prep_activity_desc($id) {
            $db = new dbconnection();
            $sql = "select   p_budget_prep.activity_desc from p_budget_prep where p_budget_prep_id=:p_budget_prep_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_prep_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['activity_desc'];
            echo $field;
        }

        function get_chosen_p_budget_prep_name($id) {
            $db = new dbconnection();
            $sql = "select p_budget_prep.name  from p_budget_prep where p_budget_prep.p_budget_prep_id=:p_budget_prep_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_prep_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['name'];
            echo $field;
        }

        function get_chosen_p_budget_prep_amount($id) {

            $db = new dbconnection();
            $sql = "select   p_budget_prep.amount from p_budget_prep where p_budget_prep_id=:p_budget_prep_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_prep_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['amount'];
            echo $field;
        }

        function All_p_budget_prep() {
            $c = 0;
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  p_budget_prep_id   from p_budget_prep";
            foreach ($db->query($sql) as $row) {
                $c += 1;
            }
            return $c;
        }

        function get_first_p_budget_prep() {
            $con = new dbconnection();
            $sql = "select p_budget_prep.p_budget_prep_id from p_budget_prep
                    order by p_budget_prep.p_budget_prep_id asc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['p_budget_prep_id'];
            return $first_rec;
        }

        function get_last_p_budget_prep() {
            $con = new dbconnection();
            $sql = "select p_budget_prep.p_budget_prep_id from p_budget_prep
                    order by p_budget_prep.p_budget_prep_id desc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['p_budget_prep_id'];
            return $first_rec;
        }

        function get_project_type_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select project_type.project_type_id,   project_type.name from project_type";
            ?>
            <select class="textbox cbo_project_type"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['project_type_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_quotationid_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select sales_quote_line.sales_quote_line_id, p_budget_items.item_name,  sales_quote_line.item,user.Firstname, user.Lastname from sales_quote_line "
                    . "join user on  user.StaffID = sales_quote_line.user  "
                    . " join p_budget_items on sales_quote_line.item=p_budget_items.p_budget_items_id "
                    . " where sales_quote_line.sales_quote_line_id not in (select quotationid from sales_order_line)";
            ?>
            <select class="textbox cbo_quotationid"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['sales_quote_line_id'] . ">" . $row['sales_quote_line_id'] . " -- " . $row['item_name'] . "  (" . $row['Firstname'] . " " . $row['Lastname'] . ") </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_client_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  party.party_id ,party.name from party "
                    . "  ";
            ?>
            <select class="textbox cbo_client"    name="cbo_client"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['party_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_bank_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  bank.bank_id, bank.name from bank  "
                    . "  ";
            ?>
            <select class="textbox cbo_client"name="txt_bank"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['bank_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_budget_prep_id_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select budget_prep_id.budget_prep_id_id,   budget_prep_id.name from budget_prep_id";
            ?>
            <select class="textbox cbo_budget_prep_id"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['budget_prep_id_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_sales_invoice_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select sales_invoice_line.sales_invoice_line_id, user.Firstname,user.Lastname    from sales_invoice_line "
                    . " join user on user.StaffID=sales_invoice_line.user "
                    . " where sales_invoice_line.sales_invoice_line_id not in (select sales_invoice from sales_receit_header)";
            ?>
            <select class="textbox cbo_sales_invoice  cbo_sales_invoice_header"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['sales_invoice_line_id'] . ">" . $row['sales_invoice_line_id'] . " -- " . $row['Firstname'] . "  " . $row['Lastname'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function All_p_type_project() {
            $c = 0;
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  p_type_project_id   from p_type_project";
            foreach ($db->query($sql) as $row) {
                $c += 1;
            }
            return $c;
        }

        function list_payment_voucher() {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select payment_voucher.payment_voucher_id,  payment_voucher.item,  payment_voucher.entry_date,  payment_voucher.User,  payment_voucher.quantity,  payment_voucher.unit_cost,  payment_voucher.amount,  payment_voucher.activity,user.Firstname,user.Lastname, p_activity.name as budget_prep, p_budget_items.item_name as item  from payment_voucher "
                    . " join user on user.STaffID=payment_voucher.user 
                        join p_activity on p_activity.p_activity_id =payment_voucher.budget_prep
                        join p_budget_items on p_budget_items.p_budget_items_id=payment_voucher.item
";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>
                        <td> Item </td>

                        <td> Quantity </td><td> unit_cost </td>
                        <td> Amount </td><td> Budget prep </td> <td> User </td><td> Entry Date </td>
                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>Delete</td><td>Update</td></tr>
                    <?php } ?>
                </thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr class="clickable_row" data-table_id="<?php echo $row['payment_voucher_id']; ?>"     data-bind="payment_voucher"> 

                        <td>
                            <?php echo $row['payment_voucher_id']; ?>
                        </td>
                        <td class="item_id_cols payment_voucher " title="payment_voucher" >
                            <?php echo $this->_e($row['item']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['quantity']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['unit_cost']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['amount']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['activity']); ?>
                        </td> 
                        <td>
                            <?php
                            echo $this->_e($row['Firstname']) . '  ';
                            echo $this->_e($row['Lastname']);
                            ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>
                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>
                                <a href="#" class="payment_voucher_delete_link" style="color: #000080;" data-id_delete="payment_voucher_id"  data-table="
                                   <?php echo $row['payment_voucher_id']; ?>">Delete</a>
                            </td>
                            <td>
                                <a href="#" class="payment_voucher_update_link" style="color: #000080;" value="
                                   <?php echo $row['payment_voucher_id']; ?>">Update</a>
                            </td><?php } ?></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function list_cheque($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  "
                        . "cheque.cheque_id, cheque.address as address, cheque.amount as amount, cheque.memo  as memo,"
                        . " bank.name as bank, "
                        . " party.name as party,  party.email as email, "
                        . " account.name as account "
                        . "  from cheque  "
                        . " join bank on bank.bank_id=cheque.bank  "
                        . " join party on party.party_id=cheque.party "
                        . " join account on account.account_id=cheque.account ";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> cheque </td>
                        <td> Bank </td><td> Address </td> <td> Account </td><td> Amount </td><td> Memo </td><td> Customer </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 
                        <td>   <?php echo $row['cheque_id']; ?>   </td>
                        <td class="bank_id_cols cheque " title="cheque" >
                            <?php echo $this->_e($row['bank']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['address']); ?>
                        </td>

                        <td>
                            <?php echo $this->_e($row['account']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['amount']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['memo']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['party']); ?>
                        </td>


                        <td>
                            <a href="#" class="cheque_delete_link" style="color: #000080;" data-id_delete="cheque_id"  data-table="
                               <?php echo $row['cheque_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="cheque_update_link" style="color: #000080;" value="
                               <?php echo $row['cheque_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_cheque_bank($id) {

                $db = new dbconnection();
                $sql = "select   cheque.bank from cheque where cheque_id=:cheque_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':cheque_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['bank'];
                echo $field;
            }

            function get_chosen_cheque_address($id) {

                $db = new dbconnection();
                $sql = "select   cheque.address from cheque where cheque_id=:cheque_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':cheque_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['address'];
                echo $field;
            }

            function get_chosen_cheque_expense_items($id) {

                $db = new dbconnection();
                $sql = "select   cheque.expense_items from cheque where cheque_id=:cheque_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':cheque_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['expense_items'];
                echo $field;
            }

            function get_chosen_cheque_account($id) {

                $db = new dbconnection();
                $sql = "select   cheque.account from cheque where cheque_id=:cheque_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':cheque_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['account'];
                echo $field;
            }

            function get_chosen_cheque_amount($id) {

                $db = new dbconnection();
                $sql = "select   cheque.amount from cheque where cheque_id=:cheque_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':cheque_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['amount'];
                echo $field;
            }

            function get_chosen_cheque_memo($id) {

                $db = new dbconnection();
                $sql = "select   cheque.memo from cheque where cheque_id=:cheque_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':cheque_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['memo'];
                echo $field;
            }

            function get_chosen_cheque_party($id) {

                $db = new dbconnection();
                $sql = "select   cheque.party from cheque where cheque_id=:cheque_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':cheque_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['party'];
                echo $field;
            }

            function All_cheque() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  cheque_id   from cheque";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_cheque() {
                $con = new dbconnection();
                $sql = "select cheque.cheque_id from cheque
                    order by cheque.cheque_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['cheque_id'];
                return $first_rec;
            }

            function get_last_cheque() {
                $con = new dbconnection();
                $sql = "select cheque.cheque_id from cheque
                    order by cheque.cheque_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['cheque_id'];
                return $first_rec;
            }

            function list_tax_calculations($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from tax_calculations";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> selfid </td><td> Source_tax </td><td> Destination tac </td><td> Sign </td><td> Is self existing </td><td> Group type </td><td> Tax </td><td> Valued </td>
                        <?php if (isset($_SESSION['shall_delete'])) { ?>  <td>Delete</td><td>Update</td><?php } ?></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['tax_calculations_id']; ?>
                        </td>
                        <td class="self_id_id_cols tax_calculations " title="tax_calculations" >
                            <?php echo $this->_e($row['self_id']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['source_tax']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['dest_tax']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['sign']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['is_self_existing']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['group_type']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['tax']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['valued']); ?>
                        </td>
                        <?php if (isset($_SESSION['shall_delete'])) { ?><td>
                                <a href="#" class="tax_calculations_delete_link" style="color: #000080;" data-id_delete="tax_calculations_id"  data-table="
                                   <?php echo $row['tax_calculations_id']; ?>">Delete</a>
                            </td>
                            <td>
                                <a href="#" class="tax_calculations_update_link" style="color: #000080;" value="
                                   <?php echo $row['tax_calculations_id']; ?>">Update</a>
                            </td><?php } ?></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function get_chosen_tax_calculations_dest_tax($id) {

                $db = new dbconnection();
                $sql = "select   tax_calculations.dest_tax from tax_calculations where tax_calculations_id=:tax_calculations_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':tax_calculations_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['dest_tax'];
                echo $field;
            }

            function get_chosen_tax_calculations_sign($id) {

                $db = new dbconnection();
                $sql = "select   tax_calculations.sign from tax_calculations where tax_calculations_id=:tax_calculations_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':tax_calculations_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['sign'];
                echo $field;
            }

            function get_chosen_tax_calculations_is_self_existing($id) {

                $db = new dbconnection();
                $sql = "select   tax_calculations.is_self_existing from tax_calculations where tax_calculations_id=:tax_calculations_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':tax_calculations_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['is_self_existing'];
                echo $field;
            }

            function get_chosen_tax_calculations_group_type($id) {

                $db = new dbconnection();
                $sql = "select   tax_calculations.group_type from tax_calculations where tax_calculations_id=:tax_calculations_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':tax_calculations_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['group_type'];
                echo $field;
            }

            function get_chosen_tax_calculations_tax($id) {

                $db = new dbconnection();
                $sql = "select   tax_calculations.tax from tax_calculations where tax_calculations_id=:tax_calculations_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':tax_calculations_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['tax'];
                echo $field;
            }

            function get_chosen_tax_calculations_valued($id) {

                $db = new dbconnection();
                $sql = "select   tax_calculations.valued from tax_calculations where tax_calculations_id=:tax_calculations_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':tax_calculations_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['valued'];
                echo $field;
            }

            function All_tax_calculations() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  tax_calculations_id   from tax_calculations";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_tax_calculations() {
                $con = new dbconnection();
                $sql = "select tax_calculations.tax_calculations_id from tax_calculations
                    order by tax_calculations.tax_calculations_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['tax_calculations_id'];
                return $first_rec;
            }

            function get_last_tax_calculations() {
                $con = new dbconnection();
                $sql = "select tax_calculations.tax_calculations_id from tax_calculations
                    order by tax_calculations.tax_calculations_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['tax_calculations_id'];
                return $first_rec;
            }

            function get_district_in_combo() {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select p_district.p_district_id,   p_district.name from p_district";
                ?>
            <select class="textbox cbo_district"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['p_district_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function paging($tot) {
            $start = 0;
            if (isset($_SESSION['page'])) {
                if ($_SESSION['page'] <= 0) {
                    $start = 1;
                } else {
                    $start = $_SESSION['page'];
                }
            }
            $last = (($start + 30) <= (ceil($tot / 30))) ? ($start + 30) : ceil($tot / 30);
            ?><table class="paging_table">
                <tr class="no_paddin_shade_no_Border">
                    <td>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="no" value="1" />
                        </form>
                    <td>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="first" value="first" />
                            <input type="submit"  value="First" name="first"/>
                        </form>
                    </td><td>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="prev" value="1" />
                            <input type="submit"  value="<<" name="prev"/>
                        </form></td>
                    </td>   
                    <?php
                    for ($pages = $start; $pages <= $last; $pages++) {
                        ?> <td>
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <input type="hidden" name="paginated" value="<?php echo $pages; ?>"/>
                                <input type="submit" class="page_btn" style="float:left;margin-left: 4px; " value="<?php echo $pages ?>"/>

                            </form>
                        </td>
                        <?php
                    }
                    ?>

                    <td><form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <input type="hidden" name="page_level" value="1" />
                            <input type="submit" style="float:right;" name="level" value=">>"/>
                        </form>
                    </td>
                </tr>
            </table><?php
        }

        function list_vat_calculation() {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from vat_calculation "
                    . " join user on user.StaffID=vat_calculation.User";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>
                        <td> Purchase/Sale </td><td> VAT Amount </td><td> Entry Date </td><td> User </td><td> vat id </td>
                        <?php if (isset($_SESSION['shall_delete'])) { ?> <td>Delete</td><td>Update</td><?php } ?></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['vat_calculation_id']; ?>
                        </td>
                        <td class="purid_saleid_id_cols vat_calculation " title="vat_calculation" >
                            <?php echo $this->_e($row['purid_saleid']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e(number_format($row['vat_amount'])); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['Firstname']) . ' ' . $row['Lastname']; ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['vatid']); ?>
                        </td>

                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>
                                <a href="#" class="vat_calculation_delete_link" style="color: #000080;" data-id_delete="vat_calculation_id"  data-table="
                                   <?php echo $row['vat_calculation_id']; ?>">Delete</a>
                            </td>
                            <td>
                                <a href="#" class="vat_calculation_update_link" style="color: #000080;" value="
                                   <?php echo $row['vat_calculation_id']; ?>">Update</a>
                            </td><?php } ?></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function _e($string) {
                echo htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
            }

            // <editor-fold defaultstate="collapsed" desc="---Project---------">
            //chosen individual field 
            function get_chosen_account_account_category($id) {

                $db = new dbconnection();
                $sql = "select   account.account_category from account where account_id=:account_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':account_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['account_category'];
                echo $field;
            }

            function get_chosen_account_profile($id) {

                $db = new dbconnection();
                $sql = "select   account.profile from account where account_id=:account_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':account_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['profile'];
                echo $field;
            }

            function get_chosen_account_username($id) {

                $db = new dbconnection();
                $sql = "select   account.username from account where account_id=:account_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':account_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['username'];
                echo $field;
            }

            function get_chosen_account_password($id) {

                $db = new dbconnection();
                $sql = "select   account.password from account where account_id=:account_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':account_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['password'];
                echo $field;
            }

            function list_account_category($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from account_category";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Name </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['account_category_id']; ?>
                        </td>
                        <td class="name_id_cols account_category " title="account_category" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>


                        <td>
                            <a href="#" class="account_category_delete_link" style="color: #000080;" data-id_delete="account_category_id"  data-table="
                               <?php echo $row['account_category_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="account_category_update_link" style="color: #000080;" value="
                               <?php echo $row['account_category_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_account_category_name($id) {

                $db = new dbconnection();
                $sql = "select   account_category.name from account_category where account_category_id=:account_category_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':account_category_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['name'];
                echo $field;
            }

            function All_account_category() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  account_category_id   from account_category";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_account_category() {
                $con = new dbconnection();
                $sql = "select account_category.account_category_id from account_category
                    order by account_category.account_category_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['account_category_id'];
                return $first_rec;
            }

            function get_last_account_category() {
                $con = new dbconnection();
                $sql = "select account_category.account_category_id from account_category
                    order by account_category.account_category_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['account_category_id'];
                return $first_rec;
            }

            function list_profile($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from profile";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td>  </td><td> Name </td><td> Surname </td><td> image </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['profile_id']; ?>
                        </td>
                        <td class="image_id_cols profile " title="profile" >
                            <?php echo $this->_e($row['image']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['name']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['surname']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['image']); ?>
                        </td>


                        <td>
                            <a href="#" class="profile_delete_link" style="color: #000080;" data-id_delete="profile_id"  data-table="
                               <?php echo $row['profile_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="profile_update_link" style="color: #000080;" value="
                               <?php echo $row['profile_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field


            function get_chosen_profile_name($id) {

                $db = new dbconnection();
                $sql = "select   profile.name from profile where profile_id=:profile_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':profile_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['name'];
                echo $field;
            }

            function get_chosen_profile_surname($id) {

                $db = new dbconnection();
                $sql = "select   profile.surname from profile where profile_id=:profile_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':profile_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['surname'];
                echo $field;
            }

            function get_chosen_profile_image($id) {

                $db = new dbconnection();
                $sql = "select   profile.image from profile where profile_id=:profile_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':profile_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['image'];
                echo $field;
            }

            function All_profile() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  profile_id   from profile";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_profile() {
                $con = new dbconnection();
                $sql = "select profile.profile_id from profile
                    order by profile.profile_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['profile_id'];
                return $first_rec;
            }

            function get_last_profile() {
                $con = new dbconnection();
                $sql = "select profile.profile_id from profile
                    order by profile.profile_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['profile_id'];
                return $first_rec;
            }

            function list_image($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from image";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>
                        <td> path </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['image_id']; ?>
                        </td>
                        <td class="path_id_cols image " title="image" >
                            <?php echo $this->_e($row['path']); ?>
                        </td>


                        <td>
                            <a href="#" class="image_delete_link" style="color: #000080;" data-id_delete="image_id"  data-table="
                               <?php echo $row['image_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="image_update_link" style="color: #000080;" value="
                               <?php echo $row['image_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_image_path($id) {

                $db = new dbconnection();
                $sql = "select   image.path from image where image_id=:image_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':image_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['path'];
                echo $field;
            }

            function All_image() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  image_id   from image";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_image() {
                $con = new dbconnection();
                $sql = "select image.image_id from image
                    order by image.image_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['image_id'];
                return $first_rec;
            }

            function get_last_image() {
                $con = new dbconnection();
                $sql = "select image.image_id from image
                    order by image.image_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['image_id'];
                return $first_rec;
            }

            function list_p_budget($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_budget";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Entry Date </td><td> Is Active </td><td> Status </td><td> Activity </td><td> Unit Cost </td><td> Quantity </td><td> Measurement </td><td> Done By </td><td> Item </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_budget_id']; ?>
                        </td>                    <td>
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['is_active']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['status']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['activity']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['unit_cost']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['qty']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['measurement']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['created_by']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['item']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_budget_delete_link" style="color: #000080;" data-id_delete="p_budget_id"  data-table="
                               <?php echo $row['p_budget_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_budget_update_link" style="color: #000080;" value="
                               <?php echo $row['p_budget_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function list_p_selectaable_budget($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from type_project";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table selectable_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> budget_value </td>
                        <td> Decription </td>
                        <td class="off"> Created By </td>
                        <td> Entry Date </td>
                        <td> Is Active </td>
                        <td>Option</td>
                    </tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_budget_id']; ?>
                        </td>
                        <td class="budget_value_id_cols p_budget " title="p_budget" >
                            <?php echo $this->_e($row['budget_value']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['description']); ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['account']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['is_active']); ?>
                        </td>


                        <td>
                            <a data-bud_id="<?php echo $row['p_budget_id']; ?>" href="#" class="p_budget_select_link" style="color: #000080;" data-id_delete="p_budget_id"  data-table="
                               <?php echo $row['p_budget_id']; ?>">Select</a>
                        </td>
                    </tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
            <?php
        }

//chosen individual field
        function get_chosen_p_budget_budget_value($id) {

            $db = new dbconnection();
            $sql = "select   p_budget.budget_value from p_budget where p_budget_id=:p_budget_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['budget_value'];
            echo $field;
        }

        function get_chosen_p_budget_description($id) {

            $db = new dbconnection();
            $sql = "select   p_budget.description from p_budget where p_budget_id=:p_budget_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['description'];
            echo $field;
        }

        function get_chosen_p_budget_account($id) {

            $db = new dbconnection();
            $sql = "select   p_budget.account from p_budget where p_budget_id=:p_budget_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['account'];
            echo $field;
        }

        function get_chosen_p_budget_entry_date($id) {

            $db = new dbconnection();
            $sql = "select   p_budget.entry_date from p_budget where p_budget_id=:p_budget_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['entry_date'];
            echo $field;
        }

        function get_chosen_p_budget_is_active($id) {

            $db = new dbconnection();
            $sql = "select   p_budget.is_active from p_budget where p_budget_id=:p_budget_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['is_active'];
            echo $field;
        }

        function get_chosen_p_budget_status($id) {

            $db = new dbconnection();
            $sql = "select   p_budget.status from p_budget where p_budget_id=:p_budget_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['status'];
            echo $field;
        }

        function All_p_budget() {
            $c = 0;
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  p_budget_id   from p_budget";
            foreach ($db->query($sql) as $row) {
                $c += 1;
            }
            return $c;
        }

        function get_first_p_budget() {
            $con = new dbconnection();
            $sql = "select p_budget.p_budget_id from p_budget
                    order by p_budget.p_budget_id asc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['p_budget_id'];
            return $first_rec;
        }

        function get_last_p_budget() {
            $con = new dbconnection();
            $sql = "select p_budget.p_budget_id from p_budget
                    order by p_budget.p_budget_id desc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['p_budget_id'];
            return $first_rec;
        }

        function list_p_budget_items($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  p_budget_items.p_budget_items_id,  p_budget_items.item_name,  p_budget_items.description,  p_budget_items.created_by,  p_budget_items.entry_date,  p_budget_items.chart_account,account.name as account, user.Firstname,user.Lastname,p_type_project.name as type from p_budget_items "
                    . " join user on user.StaffID =p_budget_items.created_by "
                    . "left join account on account.account_id=p_budget_items.chart_account "
                    . "join p_type_project on p_type_project.p_type_project_id=p_budget_items.type "
                    . "  ";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>
                        <td> Name </td>      <td> Category </td>
                        <td> Description </td>
                        <td> Created By </td>
                        <td> Entry Date </td>
                        <td> Chart Account </td>

                        <?php if (isset($_SESSION['shall_delete'])) { ?>  
                            <td>Delete</td><td>Update</td>  <?php } ?> 
                    </tr></thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr class="clickable_row" data-table_id="<?php echo $row['p_budget_items_id']; ?>"     data-bind="p_budget_items"> 
                        <td>
                            <?php echo $row['p_budget_items_id']; ?>
                        </td>
                        <td class="item_name_id_cols p_budget_items " title="p_budget_items" >
                            <?php echo $this->_e($row['item_name']); ?>
                        </td>
                        <td class="item_name_id_cols p_budget_items " title="p_budget_items" >
                            <?php echo $this->_e($row['type']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['description']); ?>
                        </td>
                        <td>
                            <?php
                            echo $this->_e($row['Firstname']) . '  ';
                            echo $this->_e($row['Lastname'])
                            ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>

                        <td>
                            <?php echo $this->_e($row['account']); ?>
                        </td>

                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>
                                <a href="#" class="p_budget_items_delete_link" style="color: #000080;" data-id_delete="p_budget_items_id"  data-table="
                                   <?php echo $row['p_budget_items_id']; ?>">Delete</a>
                            </td>
                            <td>
                                <a href="#" class="p_budget_items_update_link" style="color: #000080;" value="
                                   <?php echo $row['p_budget_items_id']; ?>">Update</a>
                            </td><?php } ?></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function list_p_budget_items_selectable($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_budget_items";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table selectable_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Name </td><td> Description </td><td> Created By </td><td> Entry Date </td><td> Is Active </td><td> Chart Account </td>
                        <td>Delete</td> </tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_budget_items_id']; ?>
                        </td>
                        <td class="item_name_id_cols p_budget_items " title="p_budget_items" >
                            <?php echo $this->_e($row['item_name']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['description']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['created_by']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['is active']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['chart_account']); ?>
                        </td>


                        <td>
                            <a  href="#" data-item_id="<?php echo $row['p_budget_items_id']; ?>" class="p_budget_items_select_link" style="color: #000080;" data-id_delete="p_budget_items_id"  data-table="
                                <?php echo $row['p_budget_items_id']; ?>">Select</a>
                        </td>
                    </tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
            <?php
        }

//chosen individual field
        function get_chosen_p_budget_items_item_name($id) {

            $db = new dbconnection();
            $sql = "select   p_budget_items.item_name from p_budget_items where p_budget_items_id=:p_budget_items_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_items_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['item_name'];
            echo $field;
        }

        function get_chosen_p_budget_items_description($id) {

            $db = new dbconnection();
            $sql = "select   p_budget_items.description from p_budget_items where p_budget_items_id=:p_budget_items_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_items_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['description'];
            echo $field;
        }

        function get_chosen_p_budget_items_created_by($id) {

            $db = new dbconnection();
            $sql = "select   p_budget_items.created_by from p_budget_items where p_budget_items_id=:p_budget_items_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_items_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['created_by'];
            echo $field;
        }

        function get_chosen_p_budget_items_entry_date($id) {

            $db = new dbconnection();
            $sql = "select   p_budget_items.entry_date from p_budget_items where p_budget_items_id=:p_budget_items_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_items_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['entry_date'];
            echo $field;
        }

        function get_chosen_p_budget_items_is_active($id) {

            $db = new dbconnection();
            $sql = "select   p_budget_items.is active from p_budget_items where p_budget_items_id=:p_budget_items_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_items_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['is active'];
            echo $field;
        }

        function get_chosen_p_budget_items_chart_account($id) {

            $db = new dbconnection();
            $sql = "select   p_budget_items.chart_account from p_budget_items where p_budget_items_id=:p_budget_items_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_budget_items_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['chart_account'];
            echo $field;
        }

        function All_p_budget_items() {
            $c = 0;
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  p_budget_items_id   from p_budget_items";
            foreach ($db->query($sql) as $row) {
                $c += 1;
            }
            return $c;
        }

        function get_first_p_budget_items() {
            $con = new dbconnection();
            $sql = "select p_budget_items.p_budget_items_id from p_budget_items
                    order by p_budget_items.p_budget_items_id asc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['p_budget_items_id'];
            return $first_rec;
        }

        function get_last_p_budget_items() {
            $con = new dbconnection();
            $sql = "select p_budget_items.p_budget_items_id from p_budget_items
                    order by p_budget_items.p_budget_items_id desc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['p_budget_items_id'];
            return $first_rec;
        }

        function list_p_items_expenses($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from p_items_expenses";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Item </td><td> Description </td><td> Amount </td><td> Date </td><td> Finalized </td><td> Is Deleted </td><td> Created By </td><td> Entry Date </td><td> Item </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_items_expenses_id']; ?>
                        </td>
                        <td class="item_id_cols p_items_expenses " title="p_items_expenses" >
                            <?php echo $this->_e($row['item']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['description']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['amount']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['finalized']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['is_deleted']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['account']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['item']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_items_expenses_delete_link" style="color: #000080;" data-id_delete="p_items_expenses_id"  data-table="
                               <?php echo $row['p_items_expenses_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_items_expenses_update_link" style="color: #000080;" value="
                               <?php echo $row['p_items_expenses_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function list_p_proj_report($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = " select 
                    p_budget.p_budget_id,  p_budget.budget_value,
                   count( p_project.p_project_id) as p_project_id,  p_project.name as project,
                    count(p_budget_items.p_budget_items_id) as p_budget_items_id,  p_budget_items.item_name as item,
                    p_items_expenses.p_items_expenses_id,sum(p_items_expenses.amount) as tot_expenses
                    from p_budget 
                    join  p_project on p_budget.p_budget_id=p_project.budget
                    join  p_budget_items on p_budget_items.project=p_project.p_project_id
                    join  p_items_expenses on p_items_expenses.item=p_budget_items.p_budget_items_id
                    group by p_budget.p_budget_id, p_project.p_project_id;";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Budget </td>
                        <td> All Projects </td>

                        <td>All Activities </td>


                        <td> Expense Tot. Value </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 
                        <td>
                            <?php echo $row['p_budget_id']; ?>
                        </td>
                        <td class="item_id_cols p_items_expenses " title="p_items_expenses" >
                            <?php echo $this->_e(number_format($row['budget_value'])); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['p_project_id']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['p_budget_items_id']); ?>
                        </td>
                        <td> <?php echo $this->_e(number_format($row['tot_expenses'])); ?>                    </td>
                        <td>
                            <a href="#" class="p_items_expenses_delete_link" style="color: #000080;" data-id_delete="p_items_expenses_id"  data-table="
                               <?php echo $row['p_items_expenses_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_items_expenses_update_link" style="color: #000080;" value="
                               <?php echo $row['p_items_expenses_id']; ?>">Update</a>
                        </td>
                    </tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
            <?php
        }

//chosen individual field
        function get_chosen_p_items_expenses_item($id) {

            $db = new dbconnection();
            $sql = "select   p_items_expenses.item from p_items_expenses where p_items_expenses_id=:p_items_expenses_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_items_expenses_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['item'];
            echo $field;
        }

        function get_chosen_p_items_expenses_description($id) {

            $db = new dbconnection();
            $sql = "select   p_items_expenses.description from p_items_expenses where p_items_expenses_id=:p_items_expenses_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_items_expenses_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['description'];
            echo $field;
        }

        function get_chosen_p_items_expenses_amount($id) {

            $db = new dbconnection();
            $sql = "select   p_items_expenses.amount from p_items_expenses where p_items_expenses_id=:p_items_expenses_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_items_expenses_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['amount'];
            echo $field;
        }

        function get_chosen_p_items_expenses_date($id) {

            $db = new dbconnection();
            $sql = "select   p_items_expenses.date from p_items_expenses where p_items_expenses_id=:p_items_expenses_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_items_expenses_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['date'];
            echo $field;
        }

        function get_chosen_p_items_expenses_finalized($id) {

            $db = new dbconnection();
            $sql = "select   p_items_expenses.finalized from p_items_expenses where p_items_expenses_id=:p_items_expenses_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_items_expenses_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['finalized'];
            echo $field;
        }

        function get_chosen_p_items_expenses_is_deleted($id) {

            $db = new dbconnection();
            $sql = "select   p_items_expenses.is_deleted from p_items_expenses where p_items_expenses_id=:p_items_expenses_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_items_expenses_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['is_deleted'];
            echo $field;
        }

        function get_chosen_p_items_expenses_account($id) {

            $db = new dbconnection();
            $sql = "select   p_items_expenses.account from p_items_expenses where p_items_expenses_id=:p_items_expenses_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_items_expenses_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['account'];
            echo $field;
        }

        function All_p_items_expenses() {
            $c = 0;
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  p_items_expenses_id   from p_items_expenses";
            foreach ($db->query($sql) as $row) {
                $c += 1;
            }
            return $c;
        }

        function get_first_p_items_expenses() {
            $con = new dbconnection();
            $sql = "select p_items_expenses.p_items_expenses_id from p_items_expenses
                    order by p_items_expenses.p_items_expenses_id asc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['p_items_expenses_id'];
            return $first_rec;
        }

        function get_last_p_items_expenses() {
            $con = new dbconnection();
            $sql = "select p_items_expenses.p_items_expenses_id from p_items_expenses
                    order by p_items_expenses.p_items_expenses_id desc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['p_items_expenses_id'];
            return $first_rec;
        }

        function list_p_items_type($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from p_items_type";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_items_type_id']; ?>
                        </td>


                        <td>
                            <a href="#" class="p_items_type_delete_link" style="color: #000080;" data-id_delete="p_items_type_id"  data-table="
                               <?php echo $row['p_items_type_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_items_type_update_link" style="color: #000080;" value="
                               <?php echo $row['p_items_type_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field


            function All_p_items_type() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  p_items_type_id   from p_items_type";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_p_items_type() {
                $con = new dbconnection();
                $sql = "select p_items_type.p_items_type_id from p_items_type
                    order by p_items_type.p_items_type_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_items_type_id'];
                return $first_rec;
            }

            function get_last_p_items_type() {
                $con = new dbconnection();
                $sql = "select p_items_type.p_items_type_id from p_items_type
                    order by p_items_type.p_items_type_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_items_type_id'];
                return $first_rec;
            }

            function list_p_chart_account($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_chart_account";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Accounnt No </td><td> Account Name </td><td> Status </td><td> Created By </td><td> Entry Date </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_chart_account_id']; ?>
                        </td>
                        <td class="acc_no_id_cols p_chart_account " title="p_chart_account" >
                            <?php echo $this->_e($row['acc_no']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['name']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['status']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['created_by']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_chart_account_delete_link" style="color: #000080;" data-id_delete="p_chart_account_id"  data-table="
                               <?php echo $row['p_chart_account_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_chart_account_update_link" style="color: #000080;" value="
                               <?php echo $row['p_chart_account_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_p_chart_account_acc_no($id) {

                $db = new dbconnection();
                $sql = "select   p_chart_account.acc_no from p_chart_account where p_chart_account_id=:p_chart_account_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_chart_account_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['acc_no'];
                echo $field;
            }

            function get_chosen_p_chart_account_name($id) {

                $db = new dbconnection();
                $sql = "select   p_chart_account.name from p_chart_account where p_chart_account_id=:p_chart_account_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_chart_account_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['name'];
                echo $field;
            }

            function get_chosen_p_chart_account_status($id) {

                $db = new dbconnection();
                $sql = "select   p_chart_account.status from p_chart_account where p_chart_account_id=:p_chart_account_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_chart_account_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['status'];
                echo $field;
            }

            function get_chosen_p_chart_account_created_by($id) {

                $db = new dbconnection();
                $sql = "select   p_chart_account.created_by from p_chart_account where p_chart_account_id=:p_chart_account_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_chart_account_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['created_by'];
                echo $field;
            }

            function get_chosen_p_chart_account_entry_date($id) {

                $db = new dbconnection();
                $sql = "select   p_chart_account.entry_date from p_chart_account where p_chart_account_id=:p_chart_account_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_chart_account_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['entry_date'];
                echo $field;
            }

            function All_p_chart_account() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  p_chart_account_id   from p_chart_account";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_p_chart_account() {
                $con = new dbconnection();
                $sql = "select p_chart_account.p_chart_account_id from p_chart_account
                    order by p_chart_account.p_chart_account_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_chart_account_id'];
                return $first_rec;
            }

            function get_last_p_chart_account() {
                $con = new dbconnection();
                $sql = "select p_chart_account.p_chart_account_id from p_chart_account
                    order by p_chart_account.p_chart_account_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_chart_account_id'];
                return $first_rec;
            }

            function list_p_department($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_department";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Department Name </td><td> Description </td><td> Abbreviation </td><td> Contact Person </td><td> Contact Person Email </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_department_id']; ?>
                        </td>
                        <td class="department_name_id_cols p_department " title="p_department" >
                            <?php echo $this->_e($row['department_name']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['description']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['abbreviation']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['contact_person']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['contact_person_email']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_department_delete_link" style="color: #000080;" data-id_delete="p_department_id"  data-table="
                               <?php echo $row['p_department_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_department_update_link" style="color: #000080;" value="
                               <?php echo $row['p_department_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_p_department_department_name($id) {

                $db = new dbconnection();
                $sql = "select   p_department.department_name from p_department where p_department_id=:p_department_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_department_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['department_name'];
                echo $field;
            }

            function get_chosen_p_department_description($id) {

                $db = new dbconnection();
                $sql = "select   p_department.description from p_department where p_department_id=:p_department_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_department_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['description'];
                echo $field;
            }

            function get_chosen_p_department_abbreviation($id) {

                $db = new dbconnection();
                $sql = "select   p_department.abbreviation from p_department where p_department_id=:p_department_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_department_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['abbreviation'];
                echo $field;
            }

            function get_chosen_p_department_contact_person($id) {

                $db = new dbconnection();
                $sql = "select   p_department.contact_person from p_department where p_department_id=:p_department_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_department_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['contact_person'];
                echo $field;
            }

            function get_chosen_p_department_contact_person_email($id) {

                $db = new dbconnection();
                $sql = "select   p_department.contact_person_email from p_department where p_department_id=:p_department_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_department_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['contact_person_email'];
                echo $field;
            }

            function All_p_department() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  p_department_id   from p_department";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_p_department() {
                $con = new dbconnection();
                $sql = "select p_department.p_department_id from p_department
                    order by p_department.p_department_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_department_id'];
                return $first_rec;
            }

            function get_last_p_department() {
                $con = new dbconnection();
                $sql = "select p_department.p_department_id from p_department
                    order by p_department.p_department_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_department_id'];
                return $first_rec;
            }

            function list_p_unit($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_unit";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Department </td><td> Name </td><td> Contact Person </td><td> Contact Person Email </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_unit_id']; ?>
                        </td>
                        <td class="department_id_cols p_unit " title="p_unit" >
                            <?php echo $this->_e($row['department']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['name']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['Contact_person']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['contact_person_email']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_unit_delete_link" style="color: #000080;" data-id_delete="p_unit_id"  data-table="
                               <?php echo $row['p_unit_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_unit_update_link" style="color: #000080;" value="
                               <?php echo $row['p_unit_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_p_unit_department($id) {

                $db = new dbconnection();
                $sql = "select   p_unit.department from p_unit where p_unit_id=:p_unit_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_unit_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['department'];
                echo $field;
            }

            function get_chosen_p_unit_name($id) {

                $db = new dbconnection();
                $sql = "select   p_unit.name from p_unit where p_unit_id=:p_unit_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_unit_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['name'];
                echo $field;
            }

            function get_chosen_p_unit_Contact_person($id) {

                $db = new dbconnection();
                $sql = "select   p_unit.Contact_person from p_unit where p_unit_id=:p_unit_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_unit_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['Contact_person'];
                echo $field;
            }

            function get_chosen_p_unit_contact_person_email($id) {

                $db = new dbconnection();
                $sql = "select   p_unit.contact_person_email from p_unit where p_unit_id=:p_unit_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_unit_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['contact_person_email'];
                echo $field;
            }

            function All_p_unit() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  p_unit_id   from p_unit";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_p_unit() {
                $con = new dbconnection();
                $sql = "select p_unit.p_unit_id from p_unit
                    order by p_unit.p_unit_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_unit_id'];
                return $first_rec;
            }

            function get_last_p_unit() {
                $con = new dbconnection();
                $sql = "select p_unit.p_unit_id from p_unit
                    order by p_unit.p_unit_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_unit_id'];
                return $first_rec;
            }

            function list_p_staff($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_staff";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Name </td><td> Last Name </td><td> Position </td><td> Email </td><td> Username </td><td> Password </td><td> Unit </td><td> Is Active </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_staff_id']; ?>
                        </td>
                        <td class="name_id_cols p_staff " title="p_staff" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['last_name']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['position']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['email']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['username']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['Password']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['unit_id']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['is_active']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_staff_delete_link" style="color: #000080;" data-id_delete="p_staff_id"  data-table="
                               <?php echo $row['p_staff_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_staff_update_link" style="color: #000080;" value="
                               <?php echo $row['p_staff_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_p_staff_name($id) {

                $db = new dbconnection();
                $sql = "select   p_staff.name from p_staff where p_staff_id=:p_staff_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_staff_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['name'];
                echo $field;
            }

            function get_chosen_p_staff_last_name($id) {

                $db = new dbconnection();
                $sql = "select   p_staff.last_name from p_staff where p_staff_id=:p_staff_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_staff_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['last_name'];
                echo $field;
            }

            function get_chosen_p_staff_position($id) {

                $db = new dbconnection();
                $sql = "select   p_staff.position from p_staff where p_staff_id=:p_staff_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_staff_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['position'];
                echo $field;
            }

            function get_chosen_p_staff_email($id) {

                $db = new dbconnection();
                $sql = "select   p_staff.email from p_staff where p_staff_id=:p_staff_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_staff_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['email'];
                echo $field;
            }

            function get_chosen_p_staff_username($id) {

                $db = new dbconnection();
                $sql = "select   p_staff.username from p_staff where p_staff_id=:p_staff_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_staff_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['username'];
                echo $field;
            }

            function get_chosen_p_staff_Password($id) {

                $db = new dbconnection();
                $sql = "select   p_staff.Password from p_staff where p_staff_id=:p_staff_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_staff_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['Password'];
                echo $field;
            }

            function get_chosen_p_staff_unit_id($id) {

                $db = new dbconnection();
                $sql = "select   p_staff.unit_id from p_staff where p_staff_id=:p_staff_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_staff_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['unit_id'];
                echo $field;
            }

            function get_chosen_p_staff_is_active($id) {

                $db = new dbconnection();
                $sql = "select   p_staff.is_active from p_staff where p_staff_id=:p_staff_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_staff_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['is_active'];
                echo $field;
            }

            function All_p_staff() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  p_staff_id   from p_staff";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_p_staff() {
                $con = new dbconnection();
                $sql = "select p_staff.p_staff_id from p_staff
                    order by p_staff.p_staff_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_staff_id'];
                return $first_rec;
            }

            function get_last_p_staff() {
                $con = new dbconnection();
                $sql = "select p_staff.p_staff_id from p_staff
                    order by p_staff.p_staff_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_staff_id'];
                return $first_rec;
            }

            function list_p_fund_request($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_fund_request "
                        . " join user on user.StaffID=p_fund_request.User";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td class="off"> Staff </td>
                        <td class="off"> Expense </td>
                        <td class="off"> Request Date </td>
                        <td> Entry Date </td>
                        <td class="off"> Created By </td><td class="off"> Request Status </td><td> Amount </td><td> Description </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_fund_request_id']; ?>
                        </td>
                        <td class="staff_id_cols p_fund_request  off " title="p_fund_request" >
                            <?php echo $this->_e($row['staff']); ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['expense']); ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['request_date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['Firstname'] . '  ' . $row['Lastname']); ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['request_status']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['amount']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['description']); ?>
                        </td>
                        <td>
                            <a href="#" class="p_fund_request_delete_link" style="color: #000080;" data-id_delete="p_fund_request_id"  data-table="
                               <?php echo $row['p_fund_request_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_fund_request_update_link" style="color: #000080;" value="
                               <?php echo $row['p_fund_request_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_p_fund_request_staff($id) {

                $db = new dbconnection();
                $sql = "select   p_fund_request.staff from p_fund_request where p_fund_request_id=:p_fund_request_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_fund_request_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['staff'];
                echo $field;
            }

            function get_chosen_p_fund_request_expense($id) {

                $db = new dbconnection();
                $sql = "select   p_fund_request.expense from p_fund_request where p_fund_request_id=:p_fund_request_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_fund_request_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['expense'];
                echo $field;
            }

            function get_chosen_p_fund_request_request_date($id) {

                $db = new dbconnection();
                $sql = "select   p_fund_request.request_date from p_fund_request where p_fund_request_id=:p_fund_request_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_fund_request_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['request_date'];
                echo $field;
            }

            function get_chosen_p_fund_request_entry_date($id) {

                $db = new dbconnection();
                $sql = "select   p_fund_request.entry_date from p_fund_request where p_fund_request_id=:p_fund_request_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_fund_request_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['entry_date'];
                echo $field;
            }

            function get_chosen_p_fund_request_account($id) {

                $db = new dbconnection();
                $sql = "select   p_fund_request.account from p_fund_request where p_fund_request_id=:p_fund_request_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_fund_request_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['account'];
                echo $field;
            }

            function get_chosen_p_fund_request_request_status($id) {

                $db = new dbconnection();
                $sql = "select   p_fund_request.request_status from p_fund_request where p_fund_request_id=:p_fund_request_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_fund_request_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['request_status'];
                echo $field;
            }

            function get_chosen_p_fund_request_amount($id) {

                $db = new dbconnection();
                $sql = "select   p_fund_request.amount from p_fund_request where p_fund_request_id=:p_fund_request_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_fund_request_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['amount'];
                echo $field;
            }

            function get_chosen_p_fund_request_description($id) {

                $db = new dbconnection();
                $sql = "select   p_fund_request.description from p_fund_request where p_fund_request_id=:p_fund_request_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_fund_request_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['description'];
                echo $field;
            }

            function All_p_fund_request() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  p_fund_request_id   from p_fund_request";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_p_fund_request() {
                $con = new dbconnection();
                $sql = "select p_fund_request.p_fund_request_id from p_fund_request
                    order by p_fund_request.p_fund_request_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_fund_request_id'];
                return $first_rec;
            }

            function get_last_p_fund_request() {
                $con = new dbconnection();
                $sql = "select p_fund_request.p_fund_request_id from p_fund_request
                    order by p_fund_request.p_fund_request_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_fund_request_id'];
                return $first_rec;
            }

            function All_p_fund_usage() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  p_fund_usage_id   from p_fund_usage";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_p_fund_usage() {
                $con = new dbconnection();
                $sql = "select p_fund_usage.p_fund_usage_id from p_fund_usage
                    order by p_fund_usage.p_fund_usage_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_fund_usage_id'];
                return $first_rec;
            }

            function get_last_p_fund_usage() {
                $con = new dbconnection();
                $sql = "select p_fund_usage.p_fund_usage_id from p_fund_usage
                    order by p_fund_usage.p_fund_usage_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_fund_usage_id'];
                return $first_rec;
            }

            function list_p_approvals($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_approvals";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Request </td><td> Date </td><td> Approved By </td><td> Approval Type </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_approvals_id']; ?>
                        </td>
                        <td class="request_id_cols p_approvals " title="p_approvals" >
                            <?php echo $this->_e($row['request']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['account']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['approval_type']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_approvals_delete_link" style="color: #000080;" data-id_delete="p_approvals_id"  data-table="
                               <?php echo $row['p_approvals_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_approvals_update_link" style="color: #000080;" value="
                               <?php echo $row['p_approvals_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_p_approvals_request($id) {

                $db = new dbconnection();
                $sql = "select   p_approvals.request from p_approvals where p_approvals_id=:p_approvals_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_approvals_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['request'];
                echo $field;
            }

            function get_chosen_p_approvals_date($id) {

                $db = new dbconnection();
                $sql = "select   p_approvals.date from p_approvals where p_approvals_id=:p_approvals_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_approvals_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['date'];
                echo $field;
            }

            function get_chosen_p_approvals_account($id) {

                $db = new dbconnection();
                $sql = "select   p_approvals.account from p_approvals where p_approvals_id=:p_approvals_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_approvals_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['account'];
                echo $field;
            }

            function get_chosen_p_approvals_approval_type($id) {

                $db = new dbconnection();
                $sql = "select   p_approvals.approval_type from p_approvals where p_approvals_id=:p_approvals_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_approvals_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['approval_type'];
                echo $field;
            }

            function All_p_approvals() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  p_approvals_id   from p_approvals";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_p_approvals() {
                $con = new dbconnection();
                $sql = "select p_approvals.p_approvals_id from p_approvals
                    order by p_approvals.p_approvals_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_approvals_id'];
                return $first_rec;
            }

            function get_last_p_approvals() {
                $con = new dbconnection();
                $sql = "select p_approvals.p_approvals_id from p_approvals
                    order by p_approvals.p_approvals_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_approvals_id'];
                return $first_rec;
            }

            function list_p_user_approvals($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_user_approvals";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Approval Type </td><td> Staff </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_user_approvals_id']; ?>
                        </td>
                        <td class="approval-type_id_cols p_user_approvals " title="p_user_approvals" >
                            <?php echo $this->_e($row['approval-type']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['account']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_user_approvals_delete_link" style="color: #000080;" data-id_delete="p_user_approvals_id"  data-table="
                               <?php echo $row['p_user_approvals_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_user_approvals_update_link" style="color: #000080;" value="
                               <?php echo $row['p_user_approvals_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_p_user_approvals_approval_type($id) {

                $db = new dbconnection();
                $sql = "select   p_user_approvals.approval-type from p_user_approvals where p_user_approvals_id=:p_user_approvals_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_user_approvals_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['approval-type'];
                echo $field;
            }

            function get_chosen_p_user_approvals_account($id) {

                $db = new dbconnection();
                $sql = "select   p_user_approvals.account from p_user_approvals where p_user_approvals_id=:p_user_approvals_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_user_approvals_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['account'];
                echo $field;
            }

            function All_p_user_approvals() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  p_user_approvals_id   from p_user_approvals";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_p_user_approvals() {
                $con = new dbconnection();
                $sql = "select p_user_approvals.p_user_approvals_id from p_user_approvals
                    order by p_user_approvals.p_user_approvals_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_user_approvals_id'];
                return $first_rec;
            }

            function get_last_p_user_approvals() {
                $con = new dbconnection();
                $sql = "select p_user_approvals.p_user_approvals_id from p_user_approvals
                    order by p_user_approvals.p_user_approvals_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_user_approvals_id'];
                return $first_rec;
            }

            function list_p_project_selectable($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_project";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table selectable_table ">
                <thead><tr>

                        <td> S/N </td>
                        <td> Name </td>
                        <td> Last Update Date </td>
                        <td> Project Contract Number </td>
                        <td> Project Supervisor </td>
                        <td> Budget </td>
                        <td> Project Sector </td>

                        <td>Delete</td>

                    </tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_project_id']; ?>
                        </td>
                        <td class="name_id_cols p_project " title="p_project" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['last_update_date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['project_contract_no']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['project_spervisor']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['budget']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['prohect_sector']); ?>
                        </td>


                        <td>
                            <a href="#" data-proj_id="<?php echo $row['p_project_id']; ?>" class="p_project_select_link" style="color: #000080;" data-id_delete="p_project_id"  data-table="
                               <?php echo $row['p_project_id']; ?>">Select</a>
                        </td>

                    </tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
            <?php
        }

//chosen individual field
        function get_chosen_p_project_name($id) {

            $db = new dbconnection();
            $sql = "select   p_project.name from p_project where p_project_id=:p_project_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_project_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['name'];
            echo $field;
        }

        function get_chosen_p_project_last_update_date($id) {

            $db = new dbconnection();
            $sql = "select   p_project.last_update_date from p_project where p_project_id=:p_project_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_project_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['last_update_date'];
            echo $field;
        }

        function get_chosen_p_project_project_contract_no($id) {

            $db = new dbconnection();
            $sql = "select   p_project.project_contract_no from p_project where p_project_id=:p_project_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_project_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['project_contract_no'];
            echo $field;
        }

        function get_chosen_p_project_project_spervisor($id) {

            $db = new dbconnection();
            $sql = "select   p_project.project_spervisor from p_project where p_project_id=:p_project_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_project_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['project_spervisor'];
            echo $field;
        }

        function get_chosen_p_project_budget($id) {

            $db = new dbconnection();
            $sql = "select   p_project.budget from p_project where p_project_id=:p_project_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_project_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['budget'];
            echo $field;
        }

        function get_chosen_p_project_prohect_sector($id) {

            $db = new dbconnection();
            $sql = "select   p_project.prohect_sector from p_project where p_project_id=:p_project_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_project_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['prohect_sector'];
            echo $field;
        }

        function get_chosen_p_project_account($id) {

            $db = new dbconnection();
            $sql = "select   p_project.account from p_project where p_project_id=:p_project_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_project_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['account'];
            echo $field;
        }

        function get_chosen_p_project_entry_date($id) {

            $db = new dbconnection();
            $sql = "select   p_project.entry_date from p_project where p_project_id=:p_project_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_project_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['entry_date'];
            echo $field;
        }

        function get_chosen_p_project_active($id) {

            $db = new dbconnection();
            $sql = "select   p_project.active from p_project where p_project_id=:p_project_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_project_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['active'];
            echo $field;
        }

        function get_chosen_p_project_status($id) {

            $db = new dbconnection();
            $sql = "select   p_project.status from p_project where p_project_id=:p_project_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_project_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['status'];
            echo $field;
        }

        function All_p_project() {
            $c = 0;
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  p_project_id   from p_project";
            foreach ($db->query($sql) as $row) {
                $c += 1;
            }
            return $c;
        }

        function get_first_p_project() {
            $con = new dbconnection();
            $sql = "select p_project.p_project_id from p_project
                    order by p_project.p_project_id asc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['p_project_id'];
            return $first_rec;
        }

        function get_last_p_project() {
            $con = new dbconnection();
            $sql = "select p_project.p_project_id from p_project
                    order by p_project.p_project_id desc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['p_project_id'];
            return $first_rec;
        }

        function list_p_approvals_type($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from p_approvals_type";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Name </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_approvals_type_id']; ?>
                        </td>
                        <td class="name_id_cols p_approvals_type " title="p_approvals_type" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_approvals_type_delete_link" style="color: #000080;" data-id_delete="p_approvals_type_id"  data-table="
                               <?php echo $row['p_approvals_type_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_approvals_type_update_link" style="color: #000080;" value="
                               <?php echo $row['p_approvals_type_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_p_approvals_type_name($id) {

                $db = new dbconnection();
                $sql = "select   p_approvals_type.name from p_approvals_type where p_approvals_type_id=:p_approvals_type_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_approvals_type_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['name'];
                echo $field;
            }

            function All_p_approvals_type() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  p_approvals_type_id   from p_approvals_type";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_p_approvals_type() {
                $con = new dbconnection();
                $sql = "select p_approvals_type.p_approvals_type_id from p_approvals_type
                    order by p_approvals_type.p_approvals_type_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_approvals_type_id'];
                return $first_rec;
            }

            function get_last_p_approvals_type() {
                $con = new dbconnection();
                $sql = "select p_approvals_type.p_approvals_type_id from p_approvals_type
                    order by p_approvals_type.p_approvals_type_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_approvals_type_id'];
                return $first_rec;
            }

            function list_p_other_expenses($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_other_expenses";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> S/N </td>
                        <td> Item Name </td><td> description </td><td> Amount </td><td> Date </td><td> Created_by </td><td> Project </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_other_expenses_id']; ?>
                        </td>
                        <td class="item_name_id_cols p_other_expenses " title="p_other_expenses" >
                            <?php echo $this->_e($row['item_name']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['description']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['amount']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['account']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['project']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_other_expenses_delete_link" style="color: #000080;" data-id_delete="p_other_expenses_id"  data-table="
                               <?php echo $row['p_other_expenses_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_other_expenses_update_link" style="color: #000080;" value="
                               <?php echo $row['p_other_expenses_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_p_other_expenses_item_name($id) {

                $db = new dbconnection();
                $sql = "select   p_other_expenses.item_name from p_other_expenses where p_other_expenses_id=:p_other_expenses_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_other_expenses_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['item_name'];
                echo $field;
            }

            function get_chosen_p_other_expenses_description($id) {

                $db = new dbconnection();
                $sql = "select   p_other_expenses.description from p_other_expenses where p_other_expenses_id=:p_other_expenses_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_other_expenses_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['description'];
                echo $field;
            }

            function get_chosen_p_other_expenses_amount($id) {

                $db = new dbconnection();
                $sql = "select   p_other_expenses.amount from p_other_expenses where p_other_expenses_id=:p_other_expenses_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_other_expenses_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['amount'];
                echo $field;
            }

            function get_chosen_p_other_expenses_date($id) {

                $db = new dbconnection();
                $sql = "select   p_other_expenses.date from p_other_expenses where p_other_expenses_id=:p_other_expenses_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_other_expenses_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['date'];
                echo $field;
            }

            function get_chosen_p_other_expenses_account($id) {

                $db = new dbconnection();
                $sql = "select   p_other_expenses.account from p_other_expenses where p_other_expenses_id=:p_other_expenses_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_other_expenses_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['account'];
                echo $field;
            }

            function get_chosen_p_other_expenses_project($id) {

                $db = new dbconnection();
                $sql = "select   p_other_expenses.project from p_other_expenses where p_other_expenses_id=:p_other_expenses_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_other_expenses_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['project'];
                echo $field;
            }

            function All_p_other_expenses() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  p_other_expenses_id   from p_other_expenses";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_p_other_expenses() {
                $con = new dbconnection();
                $sql = "select p_other_expenses.p_other_expenses_id from p_other_expenses
                    order by p_other_expenses.p_other_expenses_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_other_expenses_id'];
                return $first_rec;
            }

            function get_last_p_other_expenses() {
                $con = new dbconnection();
                $sql = "select p_other_expenses.p_other_expenses_id from p_other_expenses
                    order by p_other_expenses.p_other_expenses_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_other_expenses_id'];
                return $first_rec;
            }

            function get_chosen_user_LastName($id) {

                $db = new dbconnection();
                $sql = "select   user.LastName from user where user_id=:user_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':user_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['LastName'];
                echo $field;
            }

            function get_chosen_user_FirstName($id) {

                $db = new dbconnection();
                $sql = "select   user.FirstName from user where user_id=:user_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':user_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['FirstName'];
                echo $field;
            }

            function get_chosen_user_UserName($id) {

                $db = new dbconnection();
                $sql = "select   user.UserName from user where user_id=:user_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':user_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['UserName'];
                echo $field;
            }

            function get_chosen_user_EmailAddress($id) {

                $db = new dbconnection();
                $sql = "select   user.EmailAddress from user where user_id=:user_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':user_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['EmailAddress'];
                echo $field;
            }

            function get_chosen_user_IsActive($id) {

                $db = new dbconnection();
                $sql = "select   user.IsActive from user where user_id=:user_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':user_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['IsActive'];
                echo $field;
            }

            function get_chosen_user_Password($id) {

                $db = new dbconnection();
                $sql = "select   user.Password from user where user_id=:user_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':user_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['Password'];
                echo $field;
            }

            function get_chosen_user_Roleid($id) {

                $db = new dbconnection();
                $sql = "select   user.Roleid from user where user_id=:user_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':user_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['Roleid'];
                echo $field;
            }

            function get_chosen_user_position_depart($id) {

                $db = new dbconnection();
                $sql = "select   user.position_depart from user where user_id=:user_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':user_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['position_depart'];
                echo $field;
            }

            function get_account_category_in_combo() {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select account_category.account_category_id,   account_category.name from account_category";
                ?>
            <select class="textbox cbo_account_category"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['account_category_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_profile_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select profile.profile_id,   profile.name from profile";
            ?>
            <select class="textbox cbo_profile"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['profile_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_image_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select image.image_id,   image.name from image";
            ?>
            <select class="textbox cbo_image"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['image_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_chart_account_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select chart_account.chart_account_id,   chart_account.name from chart_account";
            ?>
            <select class="textbox cbo_chart_account cbo_accountid"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['chart_account_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_chart_account_in_combo_enabled() {

            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  account.account_id,   account.name from account group by account.name";
            ?>
            <select class="textbox charts_acc cbo_account"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['account_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_item_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_budget_items.item_name,  p_budget_items.p_budget_items_id from p_budget_items";
            ?>
            <select class="textbox cbo_item name "><option></option><option value="fly_new_p_budget_items"></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['p_budget_items_id'] . ">" . $row['item_name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_department_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select department.department_id,   department.name from department";
            ?>
            <select class="textbox cbo_department"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['department_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_unit_id_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select unit_id.unit_id_id,   unit_id.name from unit_id";
            ?>
            <select class="textbox cbo_unit_id"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['unit_id_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_staff_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select staff.staff_id,   staff.name from staff";
            ?>
            <select class="textbox cbo_staff"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['staff_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_expense_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select expense.expense_id,   expense.name from expense";
            ?>
            <select class="textbox cbo_expense"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['expense_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_request_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_request.p_request_id,   user.Firstname,user.Lastname, p_budget_items.item_name from p_request "
                    . " join user on user.StaffID=p_request.user"
                    . " join purchase_order_line on p_request.p_request_id=purchase_order_line.request
                    join p_budget_items on p_budget_items.p_budget_items_id=p_request.item"
                    . ""
                    . "";
            ?>
            <select class="textbox cbo_request "><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['p_request_id'] . ">" . $row['p_request_id'] . " --- " . $row['item_name'] . "  (" . $row['Firstname'] . ") </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_p_fund_request_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = " select p_fund_request.p_fund_request_id, p_fund_request.entry_date,p_fund_request.amount, user.Firstname,user.Lastname  from p_fund_request "
                    . " join user on user.StaffID=p_fund_request.User"
                    . "";
            ?>
            <select class="textbox cbo_request "><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['p_fund_request_id'] . ">" . $row['entry_date'] . " --- " . $row['amount'] . "  (" . $row['Firstname'] . ") </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_approval_type_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select approval_type.approval-type_id,   approval-type.name from approval-type";
            ?>
            <select class="textbox cbo_approval-type"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['approval-type_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_budget_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select budget.budget_id,   budget.name from budget";
            ?>
            <select class="textbox cbo_budget"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['budget_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_project_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_project.p_project_id,   p_project.name from p_project group by name";
            ?>
            <select class="textbox cbo_project"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['p_project_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_project_in_combo_refill() {// this one refils another combobox
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select min(p_budget_prep.p_budget_prep_id)as p_project_id ,   min(p_budget_prep.name)as name from p_budget_prep group by name ";
            ?>
            <select class="textbox cbo_project cbo_proj_refill"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['p_project_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function list_p_type_project($first, $last) {

            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from p_type_project "
                    . "where p_type_project.name<>'common' limit " . $first . " , " . $last;
            $stmt = $db->prepare($sql);
            $stmt->execute();
            ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>
                        <td> Name </td>
                        <?php if (!empty($_SESSION['shall_delete'])) { ?>
                            <td>Delete</td><td>Update</td><?php } ?>
                    </tr></thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr class="clickable_row" data-table_id="<?php echo $row['p_type_project_id']; ?>" data_title="" data-bind="p_type_project"> 
                        <td>
                            <?php echo $row['p_type_project_id']; ?>
                        </td>
                        <td class="name_id_cols p_type_project " title="p_type_project" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>

                        <?php if (!empty($_SESSION['shall_delete'])) { ?>
                            <td>
                                <a href="#" class="p_type_project_delete_link" style="color: #000080;" data-id_delete="p_type_project_id"  data-table="
                                   <?php echo $row['p_type_project_id']; ?>">Delete</a>
                            </td>
                            <td >
                                <a href="#" class="p_type_project_update_link" style="color: #000080;" value="
                                   <?php echo $row['p_type_project_id']; ?>">Update</a>
                            </td>
                        <?php } ?>
                    </tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
            <?php
            $this->paging($this->All_p_type_project());
        }

        function get_first_p_activity() {
            $con = new dbconnection();
            $sql = "select p_activity.p_activity_id from p_activity
                    order by p_activity.p_activity_id asc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['p_activity_id'];
            return $first_rec;
        }

        function get_fisc_year_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_fiscal_year.p_fiscal_year_id,year(p_fiscal_year.start_date) as start_date,year(p_fiscal_year.end_date) as end_date ,  p_fiscal_year.fiscal_year_name from p_fiscal_year";
            ?>
            <select name="cbo_fisc_year" class="textbox cbo_fisc_year"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['p_fiscal_year_id'] . ">" . $row['start_date'] . "-" . $row['end_date'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function list_p_activity($min, $max) {
            $database = new dbconnection();
            $db = $database->openConnection();
            if ($_SESSION['cat'] == 'admin' || $_SESSION['cat'] == 'mates') {
                $sql = "select p_activity.p_activity_id,p_activity.name,p_type_project.name as type,project_expectations.name as btype, p_activity.amount,  p_budget_prep.name as project, user.Firstname,user.Lastname from  p_activity "
                        . " join p_budget_prep on p_budget_prep.p_budget_prep_id=p_activity.project "
                        . " join user on user.StaffID=p_budget_prep.user"
                        . "  join project_expectations on project_expectations.project_expectations_id=p_activity.budget_type "
                        . " join p_type_project on p_budget_prep.project_type=p_type_project.p_type_project_id  "
                        . " group by p_activity.p_activity_id "
                        . " limit " . $min . " , " . $max;
            } else {
                $sql = "select p_activity.p_activity_id,p_activity.name,p_type_project.name as type,project_expectations.name as btype, p_activity.amount,  p_budget_prep.name as project, user.Firstname,user.Lastname from  p_activity "
                        . " join p_budget_prep on p_budget_prep.p_budget_prep_id=p_activity.project "
                        . " join user on user.StaffID=p_budget_prep.user  "
                        . " join project_expectations on project_expectations.project_expectations_id=p_activity.budget_type "
                        . " join p_type_project on p_budget_prep.project_type=p_type_project.p_type_project_id  "
                        . " "
                        . " where p_budget_prep.user='" . $_SESSION['userid'] . "'"
                        . "  group by p_activity.p_activity_id "
                        . " limit " . $min . " , " . $max;
            }

            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>
                        <td>Budget Line</td>
                        <td>Budget Type</td>
                        <td> Project </td>
                        <td> Activity </td> 
                        <td> Amount </td> 
                        <td> User </td> 
                        <?php if (isset($_SESSION['shall_delete'])) { ?>    <td>Delete</td>
                            <td>Update</td><?php } ?>
                    </tr></thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr class="clickable_row" data-table_id="<?php echo $row['p_activity_id']; ?>"     data-bind="p_activity"> 
                        <td>
                            <?php echo $row['p_activity_id']; ?>
                        </td>
                        <td>
                            <?php echo $row['type']; ?>
                        </td>
                        <td class="project_id_cols p_activity " title="p_activity" >
                            <?php echo $this->_e($row['btype']); ?>
                        </td>
                        <td class="project_id_cols p_activity " title="p_activity" >
                            <?php echo $this->_e($row['project']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['name']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e(number_format($row['amount'])); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['Firstname'] . ' ' . $row['Lastname']); ?>
                        </td>
                        <?php if (isset($_SESSION['shall_delete'])) { ?>    <td>
                                <a href="#" class="p_activity_delete_link" style="color: #000080;" data-id_delete="p_activity_id"  data-table="
                                   <?php echo $row['p_activity_id']; ?>">Delete</a>
                            </td>
                            <td>
                                <a href="#" class="p_activity_update_link" style="color: #000080;" value="<?php echo $row['p_activity_id']; ?>">Update</a>
                            </td><?php } ?>
                    </tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
            <?php
            $this->paging($this->All_p_activity());
        }

        function All_p_activity() {
            $c = 0;
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  p_activity_id   from p_activity";
            foreach ($db->query($sql) as $row) {
                $c += 1;
            }
            return $c;
        }

        function list_p_type_project_selectable($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from p_type_project";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table selectable_table">
                <thead><tr>
                        <td> S/N </td>
                        <td> Name </td>
                        <td class="off">Delete</td>
                        <td >Option</td></tr></thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_type_project_id']; ?>
                        </td>
                        <td class="name_id_cols p_type_project " title="p_type_project" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>

                        <td>
                            <a href="#" data-type_id="<?php echo $row['p_type_project_id']; ?>" class="p_type_project_select_link" style="color: #000080;" value="
                               <?php echo $row['p_type_project_id']; ?>">Select</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function get_type_project_in_combo() {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select p_type_project.p_type_project_id,   p_type_project.name from p_type_project";
                ?>
            <select name="cbo_type_project" class="textbox cbo_type_project fly_new_p_type_project cbo_onfly_p_type_project_change"><option></option> <option value="fly_new_p_type_project">-- Add new --</option> 
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['p_type_project_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_type_project_in_sml_cbo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_type_project.p_type_project_id,   p_type_project.name from p_type_project";
            ?>
            <select name="cbo_type_project" style="width: 150px;" class="textbox cbo_type_project fly_new_p_type_project cbo_onfly_p_type_project_change"><option></option> <option value="fly_new_p_type_project">-- Add new --</option> 
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['p_type_project_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_last_p_field() {
            $con = new dbconnection();
            $sql = "select p_field.p_field_id from p_field
                    order by p_field.p_field_id desc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['p_field_id'];
            return $first_rec;
        }

        function get_first_p_field() {
            $con = new dbconnection();
            $sql = "select p_field.p_field_id from p_field
                    order by p_field.p_field_id asc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['p_field_id'];
            return $first_rec;
        }

        function list_p_field($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_field.p_field_id,  p_field.name , p_sector.p_sector_id,  p_sector.name as sector"
                    . "  from p_field join p_sector on p_sector.p_sector_id=p_field.sector";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> p_field </td>
                        <td> Name </td><td> Sector </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_field_id']; ?>
                        </td>
                        <td class="name_id_cols p_field " title="p_field" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['sector']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_field_delete_link" style="color: #000080;" data-id_delete="p_field_id"  data-table="
                               <?php echo $row['p_field_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_field_update_link" style="color: #000080;" value="
                               <?php echo $row['p_field_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function get_chosen_p_field_sector($id) {

                $db = new dbconnection();
                $sql = "select   p_field.sector from p_field where p_field_id=:p_field_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_field_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['sector'];
                echo $field;
            }

            function get_sector_in_combo() {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select p_sector.p_sector_id,   p_sector.name from p_sector";
                ?>
            <select class="textbox cbo_sector cbos last_cbo" id="sp_combo_sector" style="float: right;"><option>-- Sectors --</option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['p_sector_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function list_p_province($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from p_province";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> p_province </td>
                        <td> Name </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_province_id']; ?>
                        </td>
                        <td class="name_id_cols p_province " title="p_province" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_province_delete_link" style="color: #000080;" data-id_delete="p_province_id"  data-table="
                               <?php echo $row['p_province_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_province_update_link" style="color: #000080;" value="
                               <?php echo $row['p_province_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function get_first_p_province() {
                $con = new dbconnection();
                $sql = "select p_province.p_province_id from p_province
                    order by p_province.p_province_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_province_id'];
                return $first_rec;
            }

            function list_p_sector($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_sector";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>
                        <td> Name </td><td> District </td>
                        <td class="off">Delete</td><td class="off">Update</td></tr></thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_sector_id']; ?>
                        </td>
                        <td class="name_id_cols p_sector " title="p_sector" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['district']); ?>
                        </td>


                        <td class="off">
                            <a href="#" class="p_sector_delete_link" style="color: #000080;" data-id_delete="p_sector_id"  data-table="
                               <?php echo $row['p_sector_id']; ?>">Delete</a>
                        </td>
                        <td class="off">
                            <a href="#" class="p_sector_update_link" style="color: #000080;" value="
                               <?php echo $row['p_sector_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function get_first_p_sector() {
                $con = new dbconnection();
                $sql = "select p_sector.p_sector_id from p_sector
                    order by p_sector.p_sector_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_sector_id'];
                return $first_rec;
            }

            function get_last_p_sector() {
                $con = new dbconnection();
                $sql = "select p_sector.p_sector_id from p_sector
                    order by p_sector.p_sector_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_sector_id'];
                return $first_rec;
            }

            function list_p_district($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_district";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> p_district </td>
                        <td> Name </td><td> Province </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_district_id']; ?>
                        </td>
                        <td class="name_id_cols p_district " title="p_district" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['province']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_district_delete_link" style="color: #000080;" data-id_delete="p_district_id"  data-table="
                               <?php echo $row['p_district_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_district_update_link" style="color: #000080;" value="
                               <?php echo $row['p_district_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function get_province_in_combo() {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select p_province.p_province_id,   p_province.name from p_province";
                ?>
            <select class="textbox cbo_province cbos" id="sp_combo_prov"><option>-- Province --</option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['p_province_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_first_p_district() {
            $con = new dbconnection();
            $sql = "select p_district.p_district_id from p_district
                    order by p_district.p_district_id asc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['p_district_id'];
            return $first_rec;
        }

        function get_field_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_field.p_field_id,   p_field.name from p_field";
            ?>
            <select class="textbox cbo_field" name="cbo_field"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['p_field_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function specl_distr_by_prov($prov) {
            try {
                $res = '';
                $database = new dbconnection();
                $db = $database->openconnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select p_district.p_district_id, p_district.name from p_district join p_province on p_province.p_province_id = p_district.province   where  p_province.p_province_id = :id";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":id" => $prov));
                $data = array();
                while ($row = $stmt->fetch()) {
                    $data[] = array(
                        'id' => $row['p_district_id'],
                        'name' => $row['name']
                    );
                }
                return json_encode($data);
            } catch (PDOException $e) {
                echo $e;
            }
        }

        function specl_activities_by_proj($project) {
            try {
                $database = new dbconnection();
                $db = $database->openconnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select p_activity_id, p_activity.name from p_activity "
                        . " join p_project on p_project.p_project_id=p_activity.project "
                        . "  where  p_project.p_project_id=:name group by p_activity.name";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":name" => $project));
                $data = array();
                while ($row = $stmt->fetch()) {
                    $data[] = array(
                        'id' => $row['p_activity_id'],
                        'name' => $row['name']
                    );
                }
                return json_encode($data);
            } catch (PDOException $e) {
                echo $e;
            }
        }

        function specl_proj_by_fiscYr($fisc_year) {
            try {
                $database = new dbconnection();
                $db = $database->openconnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select p_fiscal_year.p_fiscal_year_id,  p_fiscal_year.fiscal_year_name from p_fiscal_year"
                        . " join ";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":name" => $fisc_year));
                $data = array();
                while ($row = $stmt->fetch()) {
                    $data[] = array(
                        'id' => $row['p_activity_id'],
                        'name' => $row['name']
                    );
                }
                return json_encode($data);
            } catch (PDOException $e) {
                echo $e;
            }
        }

        function specl_sectors_by_district($distr) {

            try {
                $res = '';
                $database = new dbconnection();
                $db = $database->openconnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select p_sector.p_sector_id, p_sector.name from p_sector join p_district on p_district.p_district_id=p_sector.district where  p_district.p_district_id =:id";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":id" => $distr));
                $data = array();
                while ($row = $stmt->fetch()) {
                    $data[] = array(
                        'id' => $row['p_sector_id'],
                        'name' => $row['name']
                    );
                }
                return json_encode($data);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function list_p_fiscal_year($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from p_fiscal_year";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> p_fiscal_year </td>
                        <td> Fiscal Year Name </td><td> Start Date </td><td> End Date </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_fiscal_year_id']; ?>
                        </td>
                        <td class="fiscal_year_name_id_cols p_fiscal_year " title="p_fiscal_year" >
                            <?php echo $this->_e($row['fiscal_year_name']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['start_date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['end_date']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_fiscal_year_delete_link" style="color: #000080;" data-id_delete="p_fiscal_year_id"  data-table="
                               <?php echo $row['p_fiscal_year_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_fiscal_year_update_link" style="color: #000080;" value="
                               <?php echo $row['p_fiscal_year_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function get_first_p_fiscal_year() {
                $con = new dbconnection();
                $sql = "select p_fiscal_year.p_fiscal_year_id from p_fiscal_year
                    order by p_fiscal_year.p_fiscal_year_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_fiscal_year_id'];
                return $first_rec;
            }

            function list_p_users($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_users";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> p_users </td>
                        <td> Username </td><td> Password </td><td> Position </td><td> Is Active </td><td> Role </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_users_id']; ?>
                        </td>
                        <td class="username_id_cols p_users " title="p_users" >
                            <?php echo $this->_e($row['username']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['password']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['position']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['is_active']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['role']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_users_delete_link" style="color: #000080;" data-id_delete="p_users_id"  data-table="
                               <?php echo $row['p_users_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_users_update_link" style="color: #000080;" value="
                               <?php echo $row['p_users_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function get_first_p_users() {
                $con = new dbconnection();
                $sql = "select p_users.p_users_id from p_users
                    order by p_users.p_users_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_users_id'];
                return $first_rec;
            }

            function list_user($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from user "
                        . " join role on role.role_id=user.Roleid "
                        . " where role.name <>'dev'";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> user </td>
                        <td> Last Name </td><td> First Name </td>
                        <td> Username </td>
                        <td> Email </td>
                        <td> Position </td>
                        <td> IsActive </td>

                        <?php if (isset($_SESSION['shall_delete'])) { ?> <td>Delete</td><td>Update</td><?php } ?>
                    </tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 
                        <td>
                            <?php echo $row['StaffID']; ?>
                        </td>
                        <td class="LastName_id_cols user " title="user" >
                            <?php echo $this->_e($row['Lastname']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['Firstname']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['UserName']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['EmailAddress']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['name']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['IsActive']); ?>
                        </td>


                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>
                                <a href="#" class="user_delete_link" style="color: #000080;" data-id_delete="user_id"  data-table="
                                   <?php echo $row['user_id']; ?>">Delete</a>
                            </td>
                            <td>
                                <a href="#" class="user_update_link" style="color: #000080;" value="
                                   <?php echo $row['user_id']; ?>">Update</a>
                            </td><?php } ?></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function get_chosen_p_users_username($id) {

                $db = new dbconnection();
                $sql = "select   p_users.username from p_users where p_users_id=:p_users_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_users_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['username'];
                echo $field;
            }

            function get_chosen_p_users_password($id) {

                $db = new dbconnection();
                $sql = "select   p_users.password from p_users where p_users_id=:p_users_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_users_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['password'];
                echo $field;
            }

            function get_chosen_p_users_position($id) {

                $db = new dbconnection();
                $sql = "select   p_users.position from p_users where p_users_id=:p_users_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_users_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['position'];
                echo $field;
            }

            function get_chosen_p_users_is_active($id) {

                $db = new dbconnection();
                $sql = "select   p_users.is_active from p_users where p_users_id=:p_users_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_users_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['is_active'];
                echo $field;
            }

            function get_chosen_p_users_role($id) {

                $db = new dbconnection();
                $sql = "select   p_users.role from p_users where p_users_id=:p_users_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_users_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['role'];
                echo $field;
            }

            function All_p_users() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  p_users_id   from p_users";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_user() {
                $con = new dbconnection();
                $sql = "select user.user_id from user
                    order by user.user_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['user_id'];
                return $first_rec;
            }

            function get_last_user() {
                $con = new dbconnection();
                $sql = "select user.staffID from user
                    order by user.staffID desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['staffID'];
                return $first_rec;
            }

            function get_role_in_combo() {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select role.role_id,   role.name from role";
                ?>
            <select class="textbox cbo_role"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['role_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_position_depart_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select staff_positions.staff_positions_id,   staff_positions.name from staff_positions";
            ?>
            <select class="textbox cbo_position_depart"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['staff_positions_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_activity_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select min(p_activity.p_activity_id) as p_activity_id,   min(p_activity.name) as name from p_activity group by p_activity.name";
            ?>
            <select class="textbox cbo_activity"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['p_activity_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function list_p_request($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select min(p_request.status)as status,p_request.main_req, min(p_request.p_request_id)as p_request_id,  count(p_request.item) as items,min(p_budget_items.item_name) as fitem, sum(p_request.quantity)as quantity ,  min(p_request.unit_cost) as unit_cost,  sum(p_request.amount) as amount,  min(p_request.entry_date) as entry_date,  min(p_request.User) as User,  min(p_request.measurement) as measurement,  min(p_request.request_no) as request_no, min(user.Firstname) as Firstname,min(user.Lastname) as Lastname ,min(p_budget_items.item_name) as item ,min(p_budget_items.description) as description, min(measurement.code) as measurement,min( p_field.name) as field"
                    . " from p_request "
                    . " join user on user.StaffiD=p_request.User "
                    . " join p_budget_items on p_budget_items.p_budget_items_id=p_request.item"
                    . " join measurement on measurement_id=p_request.measurement 
                        join p_field on p_field.p_field_id=p_request.field 
                        group by p_request.main_req  
                        order by p_request_id desc ";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>

                        <td> field </td>
                        <td> Items</td>
                        <td> Total Amount</td>

                        <td> Entry Date </td><td> User </td>
                        <td class="off"> Request Number </td>
                        <td>Status </td> 
                        <?php if (isset($_SESSION['shall_delete'])) { ?>  <td>Delete</td><td>Update</td>

                        <?php } ?> <td> Action </td> </tr></thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    $n = ($row['items'] > 1 ? ' items' : ' item');
                    $status = ($row['status'] == 0) ? 'Not Approved' : (($row['status'] == 1) ? 'Waiting at DG\'s office' : 'All approved');
                    ?><tr class="" data-table_id="<?php echo $row['main_req']; ?>"     data-bind="p_request"> 
                        <td>
                            <?php echo $row['main_req']; ?>
                        </td>
                        <td>        <?php echo $this->_e($row['field']); ?>   </td>
                        <td>        <?php echo '<span style="color: red; background-color: #fff;">' . $row['items'] . '</span>' . ' items (' . $row['fitem'] . ',...)'; ?>   </td>

                        <td>
                            <?php echo $this->_e(number_format($row['amount'])); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>
                        <td>
                            <?php
                            echo $this->_e($row['Firstname']) . ' ';
                            echo $this->_e($row['Lastname'])
                            ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['request_no']); ?>
                        </td>
                        <td class="status_td">
                            <?php echo $status; ?>
                        </td>

                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td>
                                <?php if ($row['status'] != 2 || $row['status'] == 1) {// if the request has been finished the user cannot delete it. the request finishes and get represented by 2 on the status        ?>
                                    <a href="#" class="p_request_delete_link" style="color: #000080;" data-table="main_request"  data-table_id="<?php echo $row['main_req']; ?>">Delete</a>
                                    <?php
                                } else {
                                    echo 'No action';
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($row['status'] != 2 || $row['status'] == 1) { ?>
                                    <a href = "#" class = "p_request_update_link" style = "color: #000080;" data-table = "p_request" data-upd_frm_type = "other" data-id_update = "<?php echo $row['main_req']; ?>">Update</a>
                                    <?php
                                } else {
                                    echo 'No action';
                                }
                                ?>


                            </td>
                            <?php
                        }
                        $this->get_data_status($row['status'], $_SESSION['cat'], 'p_request_view_link', 'p_request', $row['main_req']);
                        ?>

                    </tr>
                    <?php
                    $pages += 1;
                }
                ?>

            </table>

            <?php
        }

//chosen individual field
        function get_chosen_p_request_entry_date($id) {

            $db = new dbconnection();
            $sql = "select   p_request.entry_date from p_request where p_request_id=:p_request_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_request_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['entry_date'];
            echo $field;
        }

        function get_chosen_p_request_user($id) {

            $db = new dbconnection();
            $sql = "select   p_request.user from p_request where p_request_id=:p_request_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_request_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['user'];
            echo $field;
        }

        function get_chosen_p_request_req_type($id) {

            $db = new dbconnection();
            $sql = "select   p_request.req_type from p_request where p_request_id=:p_request_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_request_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['req_type'];
            echo $field;
        }

        function get_chosen_p_request_description($id) {

            $db = new dbconnection();
            $sql = "select   p_request.description from p_request where p_request_id=:p_request_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_request_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['description'];
            echo $field;
        }

        function get_chosen_p_request_fin_requirement($id) {

            $db = new dbconnection();
            $sql = "select   p_request.fin_requirement from p_request where p_request_id=:p_request_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_request_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['fin_requirement'];
            echo $field;
        }

        function get_chosen_p_request_currencyid($id) {

            $db = new dbconnection();
            $sql = "select   p_request.currencyid from p_request where p_request_id=:p_request_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':p_request_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['currencyid'];
            echo $field;
        }

        function All_p_request() {
            $c = 0;
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  p_request_id   from p_request";
            foreach ($db->query($sql) as $row) {
                $c += 1;
            }
            return $c;
        }

        function get_first_p_request() {
            $con = new dbconnection();
            $sql = "select p_request.p_request_id from p_request
                    order by p_request.p_request_id asc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['p_request_id'];
            return $first_rec;
        }

        function get_last_p_request() {
            $con = new dbconnection();
            $sql = "select p_request.p_request_id from p_request
                    order by p_request.p_request_id desc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['p_request_id'];
            return $first_rec;
        }

        function list_p_request_type($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from p_request_type";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> p_request_type </td>
                        <td> Name </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_request_type_id']; ?>
                        </td>
                        <td class="name_id_cols p_request_type " title="p_request_type" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_request_type_delete_link" style="color: #000080;" data-id_delete="p_request_type_id"  data-table="
                               <?php echo $row['p_request_type_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_request_type_update_link" style="color: #000080;" value="
                               <?php echo $row['p_request_type_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_p_request_type_name($id) {

                $db = new dbconnection();
                $sql = "select   p_request_type.name from p_request_type where p_request_type_id=:p_request_type_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_request_type_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['name'];
                echo $field;
            }

            function All_p_request_type() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  p_request_type_id   from p_request_type";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_p_request_type() {
                $con = new dbconnection();
                $sql = "select p_request_type.p_request_type_id from p_request_type
                    order by p_request_type.p_request_type_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_request_type_id'];
                return $first_rec;
            }

            function get_last_p_request_type() {
                $con = new dbconnection();
                $sql = "select p_request_type.p_request_type_id from p_request_type
                    order by p_request_type.p_request_type_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_request_type_id'];
                return $first_rec;
            }

            function list_p_qty_request($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_qty_request";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> p_qty_request </td>
                        <td> Request </td><td> Unit Cost </td><td> Quantity </td><td> Measurement </td><td> Request </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_qty_request_id']; ?>
                        </td>
                        <td class="request_id_cols p_qty_request " title="p_qty_request" >
                            <?php echo $this->_e($row['request']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['unit_cost']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['quantity']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['measurement']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['requirement']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_qty_request_delete_link" style="color: #000080;" data-id_delete="p_qty_request_id"  data-table="
                               <?php echo $row['p_qty_request_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_qty_request_update_link" style="color: #000080;" value="
                               <?php echo $row['p_qty_request_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_p_qty_request_request($id) {

                $db = new dbconnection();
                $sql = "select   p_qty_request.request from p_qty_request where p_qty_request_id=:p_qty_request_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_qty_request_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['request'];
                echo $field;
            }

            function get_chosen_p_qty_request_unit_cost($id) {

                $db = new dbconnection();
                $sql = "select   p_qty_request.unit_cost from p_qty_request where p_qty_request_id=:p_qty_request_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_qty_request_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['unit_cost'];
                echo $field;
            }

            function get_chosen_p_qty_request_quantity($id) {

                $db = new dbconnection();
                $sql = "select   p_qty_request.quantity from p_qty_request where p_qty_request_id=:p_qty_request_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_qty_request_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['quantity'];
                echo $field;
            }

            function get_chosen_p_qty_request_measurement($id) {

                $db = new dbconnection();
                $sql = "select   p_qty_request.measurement from p_qty_request where p_qty_request_id=:p_qty_request_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_qty_request_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['measurement'];
                echo $field;
            }

            function get_chosen_p_qty_request_requirement($id) {

                $db = new dbconnection();
                $sql = "select   p_qty_request.requirement from p_qty_request where p_qty_request_id=:p_qty_request_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_qty_request_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['requirement'];
                echo $field;
            }

            function All_p_qty_request() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  p_qty_request_id   from p_qty_request";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_p_qty_request() {
                $con = new dbconnection();
                $sql = "select p_qty_request.p_qty_request_id from p_qty_request
                    order by p_qty_request.p_qty_request_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_qty_request_id'];
                return $first_rec;
            }

            function get_last_p_qty_request() {
                $con = new dbconnection();
                $sql = "select p_qty_request.p_qty_request_id from p_qty_request
                    order by p_qty_request.p_qty_request_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_qty_request_id'];
                return $first_rec;
            }

            function list_p_Currency($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_Currency";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> p_Currency </td>
                        <td> Code </td><td> Description </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_Currency_id']; ?>
                        </td>
                        <td class="code_id_cols p_Currency " title="p_Currency" >
                            <?php echo $this->_e($row['code']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['description']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_Currency_delete_link" style="color: #000080;" data-id_delete="p_Currency_id"  data-table="
                               <?php echo $row['p_Currency_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_Currency_update_link" style="color: #000080;" value="
                               <?php echo $row['p_Currency_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

//chosen individual field
            function get_chosen_p_Currency_code($id) {

                $db = new dbconnection();
                $sql = "select   p_Currency.code from p_Currency where p_Currency_id=:p_Currency_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_Currency_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['code'];
                echo $field;
            }

            function get_chosen_p_Currency_description($id) {

                $db = new dbconnection();
                $sql = "select   p_Currency.description from p_Currency where p_Currency_id=:p_Currency_id ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':p_Currency_id', $id);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['description'];
                echo $field;
            }

            function All_p_Currency() {
                $c = 0;
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  p_Currency_id   from p_Currency";
                foreach ($db->query($sql) as $row) {
                    $c += 1;
                }
                return $c;
            }

            function get_first_p_Currency() {
                $con = new dbconnection();
                $sql = "select p_Currency.p_Currency_id from p_Currency
                    order by p_Currency.p_Currency_id asc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_Currency_id'];
                return $first_rec;
            }

            function get_last_p_Currency() {
                $con = new dbconnection();
                $sql = "select p_Currency.p_Currency_id from p_Currency
                    order by p_Currency.p_Currency_id desc
                    limit 1";
                $stmt = $con->openconnection()->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $first_rec = $row['p_Currency_id'];
                return $first_rec;
            }

            function get_req_type_in_combo() {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select p_request_type.p_request_type_id,   p_request_type.name from p_request_type";
                ?>
            <select class="textbox cbo_req_type"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['p_request_type_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_supplier_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select party.party_id ,party.name from party where party.party_type='supplier'";
            ?>
            <select name="cbo_client" class="textbox cbo_client"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['party_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_currencyid_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select currency.currencyid_id,   currency.code from currency";
            ?>
            <select class="textbox cbo_currencyid"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['currencyid_id'] . ">" . $row['code'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_last_Main_Request() {
            $con = new dbconnection();
            $sql = "select main_request.Main_request_id from main_request
                    order by main_request.Main_Request_id desc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['Main_request_id'];
            return $first_rec;
        }

        function get_main_req_in_combo() {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select main_request.Main_request_id, user.Firstname, user.Lastname,p_budget_items.item_name as item,main_request.entry_date as m_entry_date from main_request
                        join user on user.StaffID=main_request.User 
                        join p_request on p_request.main_req=main_request.Main_Request_id     
                        join p_budget_items on p_request.item=p_budget_items.p_budget_items_id
                         where p_request.p_request_id not in (select purchase_order_line.request from purchase_order_line)";
                ?>
                <select class="textbox cbo_main_contra_acc cbo_main_request"><option></option>
                    <?php
                    foreach ($db->query($sql) as $row) {
                        echo "<option value=" . $row['Main_request_id'] . ">" . $row['Main_Request_id'] . " (" . $row['name'] . " " . $row['Firstname'] . " " . $row['Lastname'] . ") on " . $row['m_entry_date'] . " =>" . $row['item'] . " </option>";
                    }
                    ?>
                </select>
                <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

//chosen individual field
        function get_chosen_tax_calculations_self_id($id) {

            $db = new dbconnection();
            $sql = "select   tax_calculations.self_id from tax_calculations where tax_calculations_id=:tax_calculations_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':tax_calculations_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['self_id'];
            echo $field;
        }

        function get_chosen_tax_calculations_source_tax($id) {

            $db = new dbconnection();
            $sql = "select   tax_calculations.source_tax from tax_calculations where tax_calculations_id=:tax_calculations_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':tax_calculations_id', $id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['source_tax'];
            echo $field;
        }

        function list_tax_percentage($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from tax_percentage";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> tax_percentage </td>
                        <td> Purchase or Sale </td><td> Percentage </td><td> Purchaseid Sale id </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['tax_percentage_id']; ?>
                        </td>
                        <td class="pur_sale_id_cols tax_percentage " title="tax_percentage" >
                            <?php echo $this->_e($row['pur_sale']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['percentage']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['purid_saleid']); ?>
                        </td>


                        <td>
                            <a href="#" class="tax_percentage_delete_link" style="color: #000080;" data-id_delete="tax_percentage_id"  data-table="
                               <?php echo $row['tax_percentage_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="tax_percentage_update_link" style="color: #000080;" value="
                               <?php echo $row['tax_percentage_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function list_p_fund_usage($min) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_fund_usage";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min" => $min));
                ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> p_fund_usage </td>
                        <td> Request </td><td> Amount </td><td> d_acount </td><td> c_account </td><td> Activity </td><td> Entry Date </td><td> User </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_fund_usage_id']; ?>
                        </td>
                        <td class="request_id_cols p_fund_usage " title="p_fund_usage" >
                            <?php echo $this->_e($row['request']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['amount']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['d_account']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['c_account']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['activity']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['User']); ?>
                        </td>


                        <td>
                            <a href="#" class="p_fund_usage_delete_link" style="color: #000080;" data-id_delete="p_fund_usage_id"  data-table="
                               <?php echo $row['p_fund_usage_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="p_fund_usage_update_link" style="color: #000080;" value="
                               <?php echo $row['p_fund_usage_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            }

            function get_purchase_invoice_in_combo() {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select purchase_invoice_line.purchase_invoice_line_id,  user.Firstname, user.Lastname 
                        from purchase_invoice_line
                        join user   on user.StaffID=purchase_invoice_line.User 
                         where purchase_invoice_line_id not in(select purchase_invoice from purchase_receit_line)";
                ?>
            <select class="textbox cbo_purchase_invoice"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['purchase_invoice_line_id'] . ">" . $row['purchase_invoice_line_id'] . " -(" . $row['Firstname'] . ' ' . $row['Lastname'] . ") </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_first_journal_transactions() {
            $con = new dbconnection();
            $sql = "select journal_transactions.journal_transactions_id from journal_transactions
                    order by journal_transactions.journal_transactions_id asc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['journal_transactions_id'];
            return $first_rec;
        }

        function get_last_journal_transactions() {
            $con = new dbconnection();
            $sql = "select journal_transactions.journal_transactions_id from journal_transactions
                    order by journal_transactions.journal_transactions_id desc
                    limit 1";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $first_rec = $row['journal_transactions_id'];
            return $first_rec;
        }

        function list_journal_transactions($min) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from journal_transactions";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> journal_transactions </td>
                        <td> transaction_date </td>
                        <td>Delete</td><td>Update</td></tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['journal_transactions_id']; ?>
                        </td>
                        <td class="transaction_date_id_cols journal_transactions " title="journal_transactions" >
                            <?php echo $this->_e($row['transaction_date']); ?>
                        </td>


                        <td>
                            <a href="#" class="journal_transactions_delete_link" style="color: #000080;" data-id_delete="journal_transactions_id"  data-table="
                               <?php echo $row['journal_transactions_id']; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="#" class="journal_transactions_update_link" style="color: #000080;" value="
                               <?php echo $row['journal_transactions_id']; ?>">Update</a>
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
            <?php
        }

        // </editor-fold>
    }
    