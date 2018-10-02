<?php
    require_once '../web_db/connection.php';

    class other_fx {

        function get_account_exist($account) {
            $db = new dbconnection();
            $sql = "select account.name from account where  account.name =:name ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':name', $account);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['name'];
            return $field;
        }

        function get_accountid_by_name($account) {
            $db = new dbconnection();
            $sql = "select account.account_id from account where account.name=:acc ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':acc', $account);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['account_id'];
            return $field;
        }

        function get_accountclassname_by_accid($account) {
            $db = new dbconnection();
            $sql = "select account_class.account_class_id from account "
                    . " join account_class on account_class.account_class_id=account.acc_class "
                    . " where account.account_id=:acc ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->bindValue(':acc', $account);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['account_class_id'];
            return $field;
        }

        function get_exitst_common_budtline() {//this is p_type_project
            $db = new dbconnection();
            $sql = "select p_type_project.p_type_project_id  from p_type_project where p_type_project.name='common'";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['p_type_project_id'];
            return $field;
        }

        function get_account_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "    select account.account_id,  account.name from account "
                    . " join account_type on account.acc_type=account_type.account_type_id "
                    . " where account.is_contra_acc='yes' order by account_type.account_type_id asc ";
            ?>
            <select name="sub_acc_name_combo"  class="textbox cbo_account"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['account_id'] . ">" . $row['name'] . "      (<b>" . strtoupper($row['type']) . "</b>) </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_sub_account_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "   select account.account_id , main_contra_account.main_contra_account_id, max( account.name) as name,max(account_type.name) as type "
                    . "    from  main_contra_account "
                    . " join account on account.account_id=main_contra_account.self_acc"
                    . " join account_type on account.acc_type=account.acc_type "
                    . " group by account.account_id ,main_contra_account.main_contra_account_id";
            ?>
            <select name="sub_acc_name_combo"   class="textbox cbo_sub_acc cbo_account"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['account_id'] . ">" . $row['name'] . "      (<b>" . strtoupper($row['type']) . "</b>) </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_account_in_combo_array() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select account.account_id,account.book_section,   account.name,account_type.name as type "
                    . " from account"
                    . " join account_type on account.acc_type=account_type.account_type_id "
                    . " where account.is_contra_acc='yes' order by account.name  asc";
            ?>
            <select name="acc_name_com[]"  class="textbox cbo_account_arr">
                <option </option>
                <option value="new_item">-- Add New --</option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['account_id'] . ">" . $row['name'] . "      (<b>" . strtoupper($row['book_section'] . " - " . $row['type']) . "</b>) </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_vendors_suppliers_in_combo_array() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select party_id, name, party_type as type from party group by party_id";
            ?>            
            <select name="acc_party_in_combo[]"  class="textbox cbo_account_arr">
                <option </option>
                <!--<option value="new_item">-- Add New --</option>-->
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['party_id'] . ">" . strtoupper($row['name']) . "  (" . $row['type'] . ") </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_tax_group_combo_array() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select  tax_type.tax_type_id,  tax_type.name,  tax_type.percentage from tax_type ";
            ?>            
            <select name="acc_tax_in_combo[]" style="width: 100px;"  class="textbox cbo_tax_arr">
                <option </option>
                <!--<option value="new_item">-- Add New --</option>-->
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['tax_type_id'] . ">" . strtoupper($row['name']) . "  (" . $row['percentage'] . "%) </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_purchase_invoice_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select purchase_invoice_line.purchase_invoice_line_id,   purchase_invoice_line.User from  purchase_invoice_line";
            ?>
            <select class="textbox cbo_purchase_invoice"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['purchase_invoice_line_id'] . ">" . $row['User'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_purchase_order_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select purchase_order_line.purchase_order_line_id,   purchase_order_line.User, user.Firstname,user.Lastname,p_budget_items.item_name as item from purchase_order_line"
                    . " join user on user.StaffID=purchase_order_line.User "
                    . " join p_request on p_request.p_request_id=purchase_order_line.request "
                    . "  "
                    . " join p_budget_items on p_request.item=p_budget_items.p_budget_items_id"
                    . " where purchase_order_line.purchase_order_line_id not in (select purchase_order from purchase_invoice_line) "
                    . " group by purchase_order_line.purchase_order_line_id ";
            ?>
            <select class="textbox cbo_purchase_order"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['purchase_order_line_id'] . ">" . $row['purchase_order_line_id'] . " (" . $row['Firstname'] . " " . $row['Lastname'] . ") -- " . $row['item'] . "</option>";
                }
                ?>
            </select>
            <?php
        }

        function get_request_notIn_P_orders_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_request.p_request_id,   user.Firstname,user.Lastname, p_budget_items.item_name from p_request "
                    . " join user on user.StaffID=p_request.user"
                    . "  join purchase_order_line on p_request.p_request_id=purchase_order_line.request
                    join p_budget_items on p_budget_items.p_budget_items_id=p_request.item"
                    . ""
                    . "";
            ?>
            <select class="textbox cbo_request"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['p_request_id'] . ">" . $row['p_request_id'] . " --- " . $row['item_name'] . "  (" . $row['Firstname'] . ") </option>";
                }
                ?>
            </select>
            <?php
        }

        // <editor-fold defaultstate="collapsed" desc="--Budget implementation report--">
        // 
        function list_p_smart_budget($min) {//this is the report
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from p_budget";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min));
            ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> p_budget </td>
                        <td> Entry Date </td><td> Is Active </td><td> Status </td><td> Activity </td><td> Unit Cost </td><td> Quantity </td><td> Measurement </td><td> Done By </td><td> Item </td>
                        <td>Delete</td><td>Update</td></tr></thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 

                        <td>
                            <?php echo $row['p_budget_id']; ?>
                        </td>                   
                        <td>
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

            function get_budget_by_activity($activity) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select * from p_budget where activity=:activity";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":activity" => $activity));
                ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> p_budget </td>
                        <td> Entry Date </td>
                        <td>  Item </td>
                        <td>Delete</td><td>Update</td></tr></thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 


                    </tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
            <?php
        }

        function get_activities_by_project($Project) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_activity.p_activity_id,p_activity.name,p_project.name as project from  p_activity "
                    . " join p_project on p_project.p_project_id=p_activity.project"
                    . " where p_project.p_project_id=:project";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":project" => $Project));
            if ($stmt->rowCount() > 1) {
                ?>
                <table class="dataList_table full_center_two_h heit_free">
                    <thead><tr>
                            <td> S/N </td>
                            <td> Project </td>
                            <td> Activity </td> 
                            <td class="off">Delete</td>
                            <td class="off">Update</td>
                        </tr></thead>
                    <?php
                    $pages = 1;
                    while ($row = $stmt->fetch()) {
                        ?><tr> 

                            <td>
                                <?php echo $row['p_activity_id']; ?>
                            </td>
                            <td class="project_id_cols p_activity " title="p_activity" >
                                <?php echo $this->_e($row['project']); ?>
                            </td>

                            <td>
                                <?php echo $this->_e($row['name']); ?>
                            </td>
                            <td class="off">
                                <a href="#" class="p_activity_delete_link" style="color: #000080;" data-id_delete="p_activity_id"  data-table="
                                   <?php echo $row['p_activity_id']; ?>">Delete</a>
                            </td>
                            <td class="off">
                                <a href="#" class="p_activity_update_link" style="color: #000080;" value="
                                   <?php echo $row['p_activity_id']; ?>">Update</a>
                            </td></tr>
                        <?php
                        $pages += 1;
                    }
                    ?></table>
                    <?php
                }
            }

            function get_activities_by_projectid($Project) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select p_activity.p_activity_id, p_activity.name from p_activity where p_activity.project=:project ";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":project" => $Project));
                $data = array();
                while ($row = $stmt->fetch()) {
                    $data[] = array(
                        'id' => $row['p_activity_id'],
                        'name' => $row['name']
                    );
                }
                return json_encode($data);
            }

            function list_p_project() {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select p_type_project.name, p_project.name.p_project_id,p_project.name as idproject , p_project.last_update_date,  p_project.project_contract_no,  p_project.project_spervisor,   p_project.entry_date,  p_project.active ,p_type_project.name as type_project  "
                        . "from p_project  "
                        . "join p_budget_prep "
                        . " join p_type_project on p_type_project.p_type_project_id=p_project.type_project"
                        . " ";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                ?>
            <table class="dataList_table">
                <thead><tr>
                        <td> S/N </td>
                        <td> Project Name </td>

                    </tr></thead>

                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?>
                    <tr class="main_data_txt">
                        <td  colspan="2"> <?php echo $row['idproject']; ?></td>
                    </tr>
                    <tr>
                        <td colspan="2"> <?php $this->get_activities_by_project($row['p_project_id']) ?></td>
                    </tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
            <?php
        }

        function get_items_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_budget_items.p_budget_items_id,  p_budget_items.item_name from p_budget_items";
            ?>
            <select name="acc_item_combo[]"  class="textbox cbo_items">
                <option></option> 
                <option style="display: none;" value="fly_new_p_budget_items">--Add new--</option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['p_budget_items_id'] . ">" . $row['item_name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

        function get_measurement_in_combo_arr() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select measurement.measurement_id,   measurement.code from measurement";
            ?>
            <select name="measurement_array[]"  class="textbox bgt_txt_msrment  cbo_measurement cbo_onfly_measurement_change">
                <option></option>
                <option style="display: none;" value="fly_new_measurement">--Add new--</option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['measurement_id'] . ">" . $row['code'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

// </editor-fold>
        // <editor-fold defaultstate="collapsed" desc="---Budget preparation report---">
        function list_p_budget_prep() {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_type_project.p_type_project_id, p_type_project.name, p_budget_prep.p_budget_prep_id,  sum(p_activity.amount) as sum from p_budget_prep "
                    . " join p_type_project  on p_type_project.p_type_project_id=p_budget_prep.project_type "
                    . " join p_activity on p_activity.project=p_budget_prep.p_budget_prep_id"
                    . " group by p_budget_prep.project_type ";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            ?><table class="dataList_table">
            <?php
            $pages = 1;
            while ($row = $stmt->fetch()) {
                ?>
                    <tr><td></td><td>Expenses</td><td>Revenue</td><td>Profit</td><td>Status (Current expenses)</td><td>Expenses %</td><td>Revenues %</td> </tr>
                    <tr class="big_title">
                        <td>   <?php echo $row['name']; ?>  </td>
                        <td><?php echo number_format($this->sum_expenses($row['p_type_project_id'])); ?></td>
                        <td><?php echo number_format($this->sum_revenues($row['p_type_project_id'])); ?></td>
                        <td><?php echo number_format(($this->sum_revenues($row['p_type_project_id']) - $this->sum_expenses($row['p_type_project_id']))); ?></td>
                        <td><?php echo number_format($this->current_impl($row['p_type_project_id'])) ?></td>

                        <?php
                        $exp = ($this->sum_expenses($row['p_type_project_id']) > 1) ? $this->sum_expenses($row['p_type_project_id']) : 1;
                        $inc = ($this->sum_expenses($row['p_type_project_id']) > 1) ? $this->sum_expenses($row['p_type_project_id']) : 1;
                        $curr = $this->current_impl($row['p_type_project_id']);


                        $curr_inc = $this->current_impl($row['p_type_project_id']);
                        $perc_inc = $curr_inc / $inc * 100;
                        $percg = ($curr / $exp) * 100; //This is on the side of the expenses
                        ?>
                        <td><?php echo number_format($percg) ?></td>
                        <td><?php echo number_format($perc_inc) ?></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <a href="#" style="color: #000080; text-decoration: none;">
                                <span class="details link_cursor"><div class="parts no_paddin_shade_no_Border full_center_two_h heit_free no_bg"> Details</div>
                                    <span class="off hidable full_center_two_h heit_free">
                                        <?php $this->get_prep_names($row['p_type_project_id']); ?>

                                    </span>
                                </span>
                            </a>
                        </td>
                    </tr>
                    <?php
                    $pages += 1;
                }
                ?>
            </table>
            <?php
        }

        function get_prep_names($budget) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_budget_prep.p_budget_prep_id, p_budget_prep.name as n,  p_budget_prep.project_type,  p_budget_prep.user,  p_budget_prep.entry_date,  p_budget_prep.budget_type as type,  p_activity.amount,  p_type_project.name from p_budget_prep "
                    . " join p_type_project "
                    . " on p_type_project.p_type_project_id=p_budget_prep.project_type "
                    . "join p_activity on p_activity.project=p_budget_prep.p_budget_prep_id "
                    . " where p_type_project_id=:prep";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":prep" => $budget));
            ?>
            <table class="dataList_table full_center_two_h heit_free">
                <thead><tr>
                        <td> S/N </td>
                        <td> Project Type </td><td class="off"> User </td>
                        <td> Name</td> <td> Budget Type </td>

                        <td> Amount </td><td> Entry Date </td>
                    </tr>
                </thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr> 
                        <td>
                            <?php echo $row['p_budget_prep_id']; ?>
                        </td>
                        <td class="project_type_id_cols p_budget_prep " title="p_budget_prep" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>
                        <td class="off">
                            <?php echo $this->_e($row['user']); ?>
                        </td>

                        <td>
                            <?php echo $this->_e($row['n']); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['type']); ?>
                        </td>

                        <td>
                            <?php echo $this->_e(number_format($row['amount'])); ?>
                        </td>
                        <td>
                            <?php echo $this->_e($row['entry_date']); ?>
                        </td>
                    </tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
            <?php
        }

        function sum_expenses($budget) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_budget_prep.p_budget_prep_id,  p_activity.amount as sum from p_budget_prep "
                    . " join p_type_project on p_type_project.p_type_project_id=p_budget_prep.project_type "
                    . " join p_activity on p_activity.project=p_budget_prep.p_budget_prep_id "
                    . "    where p_type_project.p_type_project_id=:budget  and p_budget_prep.budget_type='Expense'";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":budget" => $budget));
            $s = 0;
            while ($row = $stmt->fetch()) {
                $s += $row['sum'];
            }
            return $s;
        }

        function sum_revenues($budget) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_budget_prep_id,  p_activity.amount as sum from p_budget_prep "
                    . " join p_type_project on p_type_project.p_type_project_id=p_budget_prep.project_type "
                    . " join p_activity on p_activity.project=p_budget_prep.p_budget_prep_id "
                    . "    where p_type_project.p_type_project_id=:budget  and p_budget_prep.budget_type='revenue'";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":budget" => $budget));
            $s = 0;
            while ($row = $stmt->fetch()) {
                $s += $row['sum'];
            }
            return $s;
        }

        function current_impl($type) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select purchase_invoice_line.purchase_invoice_line_id,sum( p_activity.amount)as sum  from purchase_invoice_line
                    join purchase_order_line on purchase_order_line.purchase_order_line_id=purchase_invoice_line.purchase_order
                    join p_request on p_request.p_request_id=purchase_order_line.request
                    join p_activity on p_activity.p_activity_id=purchase_invoice_line.activity
                    join p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id
                    join p_type_project on p_budget_prep.project_type=p_type_project.p_type_project_id 
                    where p_type_project.p_type_project_id=:type";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":type" => $type));
            $s = 0;
            while ($row = $stmt->fetch()) {
                $s = $row['sum'];
            }
            return $s;
        }

        function current_revenues($type) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "sales_invoice_line.sales_invoice_line_id,sum(p_activity.amount) as sum
            from sales_invoice_line  
            join sales_order_line on sales_invoice_line.sales_order= sales_order_line.sales_order_line_id
            join sales_quote_line on sales_order_line.quotationid=sales_quote_line.sales_quote_line_id 
            join p_budget_items on sales_quote_line.item=p_budget_items.p_budget_items_id 
            join p_activity on sales_invoice_line.budget_prep_id=p_activity.p_activity_id                    
                    join p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id
                    join p_type_project on p_budget_prep.project_type=p_type_project.p_type_project_id 
                    where p_type_project.p_type_project_id=:type";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":type" => $type));
            $s = 0;
            while ($row = $stmt->fetch()) {
                $s = $row['sum'];
            }
            return $s;
        }

// </editor-fold>
        // <editor-fold defaultstate="collapsed" desc="--------------------sales---">
        function get_customers_in_combo() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select customer.customer_id,  party.name from customer"
                    . " join party on party.party_id=customer.party_id";
            ?>
            <select name="acc_name_combo"  class="textbox cbo_account"><option></option>
                <?php
                foreach ($db->query($sql) as $row) {
                    echo "<option value=" . $row['customer_id'] . ">" . $row['name'] . " </option>";
                }
                ?>
            </select>
            <?php
        }

// </editor-fold>
        // <editor-fold defaultstate="collapsed" desc="---Financial books (Normal views)---">

        function list_income_or_expenses($inc_exp) {
            $db = new dbconnection();
            $sql = "select account.name as account,  account_type.name,   journal_entry_line.amount, journal_entry_header.date, journal_entry_line.dr_cr  from account
                join journal_entry_line on journal_entry_line.accountid=account.account_id
                join journal_entry_header on journal_entry_header.journal_entry_header_id=journal_entry_line.journal_entry_header
                join account_type on account.acc_type=account_type.account_type_id where account_type.name=:name 
                  group by account_type.account_type_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(":name" => $inc_exp));
            ?>
            <table class="dataList_table">
                <thead><tr>

                        <td> Type </td>
                        <td> Date </td> 
                        <td> Amount </td>
                        <td> Balance </td>

                    </tr>
                </thead>

                <?php
                while ($row = $stmt->fetch()) {
                    ?><tr> 
                        <td><?php echo $row['account']; ?></td>
                        <td>  <?php echo $row['date']; ?> </td> 
                        <td>  <?php echo $row['amount']; ?> </td> 
                        <td>  <?php echo $row['dr_cr']; ?> </td> 
                    </tr><?php
                }
                $field = $row['name'];
                return $field;
            }

            function list_journal_entry_line($inc_exp) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select distinct journal_entry_line.journal_entry_line_id,  journal_entry_line.accountid,  journal_entry_line.dr_cr,  journal_entry_line.amount,  journal_entry_line.memo,  journal_entry_line.journal_entry_header,  account.name as account, party.name as party from journal_entry_line 
                   join account on journal_entry_line.accountid=account.account_id
                        join journal_entry_header on journal_entry_header.journal_entry_header_id=journal_entry_line.journal_entry_header
                        join account_type on account_type.account_type_id=account.acc_type
                        join party on journal_entry_header.party = party.party_id 
                        where  account_type.name=:acc_type  
                        group by account_type.account_type_id";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":acc_type" => $inc_exp));
                ?>
                <table class="dataList_table">
                    <thead><tr>
                            <td> S/N </td>
                            <td> Account </td><td class="off"> Debit or Credit </td>
                            <td> Amount </td><td> Memo </td>
                            <td> Party </td>
                            <td class="delete_cols off">Delete</td><td class="update_cols off">Update</td></tr></thead>
                    <?php
                    $pages = 1;
                    while ($row = $stmt->fetch()) {
                        ?><tr> 
                            <td>
                                <?php echo $row['journal_entry_line_id']; ?>
                            </td>
                            <td class="accountid_id_cols journal_entry_line " title="journal_entry_line" >
                                <?php echo $this->_e($row['account']); ?>
                            </td>
                            <td class="off">
                                <?php echo $this->_e($row['dr_cr']); ?>
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
                            <td class="delete_cols off">
                                <a href="#" class="journal_entry_line_delete_link" style="color: #000080;" value="
                                   <?php echo $row['journal_entry_line_id']; ?>">Delete</a>
                            </td>
                            <td class="update_cols off">
                                <a href="#" class="journal_entry_line_update_link" style="color: #000080;" value="
                                   <?php echo $row['journal_entry_line_id']; ?>">Update</a>
                            </td>
                        </tr>
                        <?php
                        $pages += 1;
                    }
                    ?></table>
                <?php
            }

            function list_liabilities() {// this function has one parameter but it check two parameters with or condition
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select account.account_id,  account.acc_type,  account.acc_class,  account.name,  account.DrCrSide,  account.acc_code,  account.acc_desc,  account.is_cash,  account.is_contra_acc,  account.is_row_version , journal_entry_line.journal_entry_line_id,  journal_entry_line.accountid,  journal_entry_line.dr_cr,  sum(journal_entry_line.amount)as amount,  journal_entry_line.memo,  journal_entry_line.journal_entry_header,  journal_entry_line.entry_date, journal_entry_header.journal_entry_header_id,  journal_entry_header.party,  journal_entry_header.voucher_type,  journal_entry_header.date,  journal_entry_header.memo,  journal_entry_header.reference_number,  journal_entry_header.posted,account_type.name from account
                   right join journal_entry_line on journal_entry_line.accountid=account.account_id
                    left join journal_entry_header on journal_entry_header.journal_entry_header_id=journal_entry_line.journal_entry_header
                    join account_type on account.acc_type=account_type.account_type_id       
                    where account_type.name='other current liability'
                    or account_type.name='Long Term Liability' 
                    group by account_type.name";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                ?>
                <table class="dataList_table">
                    <thead><tr>
                            <td> S/N </td>
                            <td> Account </td><td class="off"> Debit or Credit </td>
                            <td> Amount </td><td> Memo </td>
                            <td> Party </td>
                            <td class="delete_cols off">Delete</td><td class="update_cols off">Update</td></tr></thead>
                    <?php
                    $pages = 1;
                    while ($row = $stmt->fetch()) {
                        ?><tr> 

                            <td>
                                <?php echo $row['journal_entry_line_id']; ?>
                            </td>
                            <td class="accountid_id_cols journal_entry_line " title="journal_entry_line" >
                                <?php echo $this->_e($row['name']); ?>
                            </td>
                            <td class="off">
                                <?php echo $this->_e($row['dr_cr']); ?>
                            </td>
                            <td>
                                <?php echo $this->_e(number_format($row['amount'])); ?>
                            </td>
                            <td>
                                <?php echo $this->_e($row['memo']); ?>
                            </td>
                            <td>
                                <?php echo $this->_e($row['party']); ?>
                            </td>
                            <td class="delete_cols off">
                                <a href="#" class="journal_entry_line_delete_link" style="color: #000080;" value="
                                   <?php echo $row['journal_entry_line_id']; ?>">Delete</a>
                            </td>
                            <td class="update_cols off">
                                <a href="#" class="journal_entry_line_update_link" style="color: #000080;" value="
                                   <?php echo $row['journal_entry_line_id']; ?>">Update</a>
                            </td></tr>
                        <?php
                        $pages += 1;
                    }
                    ?></table>
                    <?php
                }

                function list_cash_by_date($min_date, $max_date) {// this function has one parameter but it check two parameters with or condition
                    $database = new dbconnection();
                    $db = $database->openConnection();
                    $sql = "select account.account_id,  account.acc_type,journal_entry_line.entry_date,journal_entry_line.memo,  account.acc_class,  account.name,  account.DrCrSide,  account.acc_code,  account.acc_desc,  account.is_cash,  account.is_contra_acc,  account.is_row_version , journal_entry_line.journal_entry_line_id,  journal_entry_line.accountid,  journal_entry_line.dr_cr,  sum(journal_entry_line.amount)as amount,  journal_entry_line.memo,  journal_entry_line.journal_entry_header,  journal_entry_line.entry_date, journal_entry_header.journal_entry_header_id,  journal_entry_header.party,  journal_entry_header.voucher_type,  journal_entry_header.date,  journal_entry_header.memo,  journal_entry_header.reference_number,  journal_entry_header.posted,account_type.name from account
                    right join journal_entry_line on journal_entry_line.accountid=account.account_id
                    left join journal_entry_header on journal_entry_header.journal_entry_header_id=journal_entry_line.journal_entry_header
                    join account_type on account.acc_type=account_type.account_type_id       
                    where account_type.name='bank' and journal_entry_line.entry_date>=:min_date and journal_entry_line.entry_date<=:max_date
                    group by account.account_id";
                    $stmt = $db->prepare($sql);
                    $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                    ?>

                <?php
                $pages = 1;
                ?><table class="income_table2">
                    <thead  class="books_header"> <tr>
                            <td>Account</td>
                            <td>Amount</td>
                            <td>Memo</td>
                            <td>Entry date</td>
                        </tr></thead>
                    <?php
                    while ($row = $stmt->fetch()) {
                        ?><tr class=""> 
                            <td class="accountid_id_cols journal_entry_line " title="journal_entry_line" >
                                <?php echo $this->_e($row['name']); ?>
                            </td>
                            <td>
                                <?php echo $this->_e(number_format($row['amount'])); ?>
                            </td>
                            <td>
                                <?php echo $this->_e($row['memo']); ?>
                            </td>
                            <td>
                                <?php echo $this->_e($row['entry_date']); ?>
                            </td>
                            <td class="delete_cols off">
                                <a href="#" class="journal_entry_line_delete_link" style="color: #000080;" value="
                                   <?php echo $row['journal_entry_line_id']; ?>">Delete</a>
                            </td>
                            <td class="update_cols off">
                                <a href="#" class="journal_entry_line_update_link" style="color: #000080;" value="
                                   <?php echo $row['journal_entry_line_id']; ?>">Update</a>
                            </td></tr>
                        <?php
                        $pages += 1;
                    }
                    ?></table><?php
                    ?> 
                    <?php
                }

                function list_assets() {// this function has one parameter but it check two parameters with or condition
                    $database = new dbconnection();
                    $db = $database->openConnection();
                    $sql = "select account.account_id,  account.acc_type,  account.acc_class,  account.name as account,  account.DrCrSide,  account.acc_code,  account.acc_desc,  account.is_cash,  account.is_contra_acc,  account.is_row_version , journal_entry_line.journal_entry_line_id,  journal_entry_line.accountid,  journal_entry_line.dr_cr,  sum(journal_entry_line.amount)as amount,  journal_entry_line.memo,  journal_entry_line.journal_entry_header,  journal_entry_line.entry_date, journal_entry_header.journal_entry_header_id,  journal_entry_header.party,  journal_entry_header.voucher_type,  journal_entry_header.date,  journal_entry_header.memo,  journal_entry_header.reference_number,  journal_entry_header.posted,account_type.name from account
                    right join journal_entry_line on journal_entry_line.accountid=account.account_id
                    left join journal_entry_header on journal_entry_header.journal_entry_header_id=journal_entry_line.journal_entry_header
                    join account_type on account.acc_type=account_type.account_type_id         
                    where account_type.name='other current asset'
                    or account_type.name='Fixed Assets'
                    or account_type.name='Other asset' 
                    group by account_type.name";
                    $stmt = $db->prepare($sql);
                    $stmt->execute();
                    ?>
                <table class="dataList_table">
                    <thead><tr>
                            <td> S/N </td>
                            <td> Account </td>
                            <td class="off"> Debit or Credit </td>
                            <td> Amount </td><td> Memo </td>
                            <td> Party </td>
                            <td class="delete_cols off">Delete</td><td class="update_cols off">Update</td></tr></thead>
                    <?php
                    $pages = 1;
                    while ($row = $stmt->fetch()) {
                        ?><tr> 

                            <td>
                                <?php echo $row['journal_entry_line_id']; ?>
                            </td>
                            <td class="accountid_id_cols journal_entry_line " title="journal_entry_line" >
                                <?php echo $this->_e($row['name']); ?>
                            </td>
                            <td class="off">
                                <?php echo $this->_e($row['dr_cr']); ?>
                            </td>
                            <td>
                                <?php echo $this->_e(number_format($row['amount'])); ?>
                            </td>
                            <td>
                                <?php echo $this->_e($row['memo']); ?>
                            </td>
                            <td>
                                <?php echo $this->_e($row['party']); ?>
                            </td>
                            <td class="delete_cols off">
                                <a href="#" class="journal_entry_line_delete_link" style="color: #000080;" value="
                                   <?php echo $row['journal_entry_line_id']; ?>">Delete</a>
                            </td>
                            <td class="update_cols off">
                                <a href="#" class="journal_entry_line_update_link" style="color: #000080;" value="
                                   <?php echo $row['journal_entry_line_id']; ?>">Update</a>
                            </td></tr>
                        <?php
                        $pages += 1;
                    }
                    ?></table>
                    <?php
                }

                function list_general_ledger_line($date1, $date2) {
                    $database = new dbconnection();
                    $db = $database->openConnection();
                    $sql = "select  journal_entry_line.journal_entry_line_id,  journal_entry_line.accountid,  journal_entry_line.dr_cr,  sum(journal_entry_line.amount) as amount,  journal_entry_line.memo,  journal_entry_line.journal_entry_header,  journal_entry_line.entry_date,
                            account.account_id,  account.acc_type,  account.acc_class,  account.name,  account.DrCrSide,  account.acc_code,  account.acc_desc,  account.is_cash,  account.is_contra_acc,  account.is_row_version,
                            account.name as account,
                            account_type.name as type
                            from journal_entry_line  
                            join account on account.account_id=journal_entry_line.accountid
                            join account_type on account_type.account_type_id=account.acc_type
                            where journal_entry_line.entry_date >=:min_date and journal_entry_line.entry_date <=:max_date
                            group by account.account_id";
                    $stmt = $db->prepare($sql);
                    $stmt->execute(array(":min_date" => $date1, ":max_date" => $date2));
                    ?>
                <table class="less_color_table padding_sides">
                    <thead><tr>
                            <td> S/N </td><td> account </td> <td class="off"> dr_cr </td>
                            <td> amount </td><td > memo </td>
                            <td class="off"> journal_entry_header </td>
                            <td> entry_date </td> 

                            <td> acc_type </td>
                            <td class="off"> acc_class </td><td> name </td>
                            <td class="off"> DrCrSide </td>  
                            <td class="off"> acc_code </td>
                            <td class="off"> acc_desc </td>
                            <td class="off"> is_cash </td>
                            <td class="off"> is_contra_acc </td>
                            <td class="off"> is_row_version </td>

                        </tr>
                    </thead>
                    <?php
                    while ($row = $stmt->fetch()) {
                        ?><tr class="clickable_row" data-bind="<?php echo $row['journal_entry_line_id']; ?>"> 
                            <td>        <?php echo $row['journal_entry_line_id']; ?> </td>
                            <td>        <?php echo $row['account']; ?> </td>

                            <td>        <?php echo number_format($row['amount']); ?> </td>
                            <td>        <?php echo $row['memo']; ?> </td>
                            <td class="off">        <?php echo $row['journal_entry_header']; ?> </td>
                            <td>        <?php echo $row['entry_date']; ?> </td>
                            <td>        <?php echo $row['type']; ?> </td>
                            <td class="off">        <?php echo $row['acc_class']; ?> </td>
                            <td>        <?php echo $row['name']; ?> </td>
                            <td class="off">        <?php echo $row['DrCrSide']; ?> </td>
                            <td class="off">        <?php echo $row['acc_code']; ?> </td>
                            <td class="off">        <?php echo $row['acc_desc']; ?> </td>
                            <td class="off">        <?php echo $row['is_cash']; ?> </td>
                            <td class="off">        <?php echo $row['is_contra_acc']; ?> </td>
                            <td class="off">        <?php echo $row['is_row_version']; ?> </td>
                            <?php // $this->is_debt_crdt($row['dr_cr']);       ?>
                        </tr>
                    <?php }
                    ?></table>
                <?php
            }

            function is_debt_crdt($account) {
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select journal_entry_line.dr_cr ,journal_entry_line.amount,journal_entry_line.accountid from journal_entry_line where dr_cr=:dt_crt group by journal_entry_line.accountid";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":dt_crt" => $account));
                while ($row = $stmt->fetch()) {
                    echo ($row['dr_cr'] = 'Debit') ? ' <td> ' . $row['amount'] . '</td> <td></td>' : '<td></td> <td>' . $row['amount'] . '</td>';
                }
            }

            function get_account_balance($account, $min, $max) {
                require_once 'fin_books_sum_views.php';
                $sums = new fin_books_sum_views();
                $db = new dbconnection();
                $d_sql = $sums->get_sums();
                $c_sql = $sums->get_sums();

                $d_stmt = $db->openConnection()->prepare($d_sql);
                $c_stmt = $db->openConnection()->prepare($c_sql);

                $d_stmt->execute(array(":d_c" => 'debit', ":class" => $account, ":min" => $min, ":max" => $max));
                $c_stmt->execute(array(":d_c" => 'credit', ":class" => $account, ":min" => $min, ":max" => $max));
                $d_res = $d_stmt->fetch(PDO::FETCH_ASSOC);
                $c_res = $c_stmt->fetch(PDO::FETCH_ASSOC);
                $sale_revenue = $d_res['amount'] - $c_res['amount'];
                return $sale_revenue;
            }

            function get_account_details($min, $max, $class) {
                try {
                    require_once 'fin_books_sum_views.php';
                    $sums = new fin_books_sum_views();
                    $db = new dbconnection();
                    $db->openconnection()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $sql = $sums->journal_based_query_acc_class($class);
                    $stmt = $db->openConnection()->prepare($sql);
                    $stmt->execute(array(":min_date" => $min, ":max_date" => $max));
                    //Get class id by name
                    $classid = $this->get_acc_classid_byname($class);
                    ?>
                    <div class="parts full_center_two_h heit_free no_paddin_shade_no_Border">
                        <div class="parts no_paddin_shade_no_Border"> <?php echo $min . ' - ' . $max; ?></div>
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
                    <table class="book_part_details diff_td">
                        <thead class="books_header">
                            <tr>
                                <td>Budget Line</td>
                                <td>Project</td>
                                <td>Activity</td>
                                <td>Fin. Account</td>
                                <td>Amount</td>
                                <td>Memo</td>
                                <td>Entry date</td>
                                <td>User</td>
                            </tr>
                        </thead>
                        <?php
                        $sum = 0;
                        $amount = 0;
                        while ($row = $stmt->fetch()) {
                            ?>
                            <tr>
                                <td><?php echo $row['budget']; ?></td> 
                                <td><?php echo $row['project']; ?></td> 
                                <td><?php echo $row['activity']; ?></td> 
                                <td><?php echo $row['accountname'] ?></td>
                                <td><?php
                                    $amount = ($row['amount'] >= 1) ? $row['amount'] : -1 * $row['amount'];

                                    echo number_format(($row['amount'] > 1) ? $row['amount'] : -1 * $row['amount'])
                                    ?></td>
                                <td><?php echo $row['memo'] ?></td>
                                <td><?php echo $row['entry_date'] ?></td>
                                <td><?php echo $row['Firstname'] . ' ' . $row['Lastname'] ?></td>
                            </tr>    
                            <?php
                            $sum += $amount;
                        }
                        ?>
                        <tr class="row_bold big_title">
                            <td colspan="5" class="text_right">
                                <?php echo number_format($sum); ?>
                            </td>
                        </tr>

                    </table><?php
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            }

            function get_acc_sum($dr_cr, $acc, $min, $max) {
                require_once 'fin_books_sum_views.php';
                $sums = new fin_books_sum_views();
                $db = new dbconnection();
                $sql = $sums->get_ledger_by_accname(); //filters: acc, date and debit cr
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->execute(array(":d_c" => $dr_cr, ":account" => $acc, ":min" => $min, ":max" => $max));
                $res = $stmt->fetch(PDO::FETCH_ASSOC);
                return $res['amount'];
            }

            function get_debit_sum_by_date($dr_cr, $min, $max) {
                require_once 'fin_books_sum_views.php';
                $sums = new fin_books_sum_views();
                $db = new dbconnection();
                $sql = $sums->get_by_accname_no_acc(); //filters: acc, date and debit cr
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->execute(array(":d_c" => $dr_cr, ":min" => $min, ":max" => $max));
                $res = $stmt->fetch(PDO::FETCH_ASSOC);
                return $res['amount'];
            }

            function list_account_for_ledger($min, $max) {
                try {
                    require_once 'fin_books_sum_views.php';
                    $sums = new fin_books_sum_views();
                    $database = new dbconnection();
                    $db = $database->openConnection();
                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $acc_sql = $sums->list_account_for_ledger();

                    $acc_stmt = $db->prepare($acc_sql);
                    $acc_stmt->execute(array(":min_date" => $min, ":max_date" => $max));
                    $pages = 0;
                    while ($row = $acc_stmt->fetch()) {
                        ?><div class="parts no_paddin_shade_no_Border"> 
                            <table class="tab_ledger margin_free" cellspacing="0" border="1">
                                <tr class="row_center_txt">
                                    <td colspan="2">
                                        <?php echo $row['name']; ?>
                                    </td>
                                </tr>
                                <?php echo $this->get_ledger_format($row['account_id'], $min, $max) ?></td>

                            </table>
                        </div>
                        <?php
                        $pages += 1;
                    }
                } catch (PDOException $e) {
                    echo 'Is there an error? ' . $e->getMessage();
                }
            }

            function get_ledger_format($account, $min, $max) {
                require_once 'fin_books_sum_views.php';
                $sums = new fin_books_sum_views();
                $db = new dbconnection();
                $d_sql = $sums->get_ledger_sum_by_accname();
                $c_sql = $sums->get_ledger_sum_by_accname();


                $d_stmt = $db->openConnection()->prepare($d_sql);
                $c_stmt = $db->openConnection()->prepare($c_sql);

                $d_nogrp_sql = $sums->get_ledger_by_accname();
                $c_nogrp_sql = $sums->get_ledger_by_accname();
                $d_nogrp_stmt = $db->openConnection()->prepare($d_nogrp_sql);
                $c_nogrp_stmt = $db->openConnection()->prepare($c_nogrp_sql);
                $d_tb_rows = '';
                $c_tb_rows = '';

                $d_nogrp_stmt->execute(array(":d_c" => 'debit', ":account" => $account, ":min" => $min, ":max" => $max));
                $c_nogrp_stmt->execute(array(":d_c" => 'credit', ":account" => $account, ":min" => $min, ":max" => $max));
                while ($dnogrp_row = $d_nogrp_stmt->fetch()) {
                    $d_tb_rows .= '' . number_format($dnogrp_row['amount']) . '<br/>';
                }
                while ($cnogrp_row = $c_nogrp_stmt->fetch()) {
                    $c_tb_rows .= '' . number_format($cnogrp_row['amount']) . '<br/>';
                }


                $d_stmt->execute(array(":d_c" => 'debit', ":account" => $account, ":min" => $min, ":max" => $max));
                $c_stmt->execute(array(":d_c" => 'credit', ":account" => $account, ":min" => $min, ":max" => $max));
                $d_res = $d_stmt->fetch(PDO::FETCH_ASSOC);
                $c_res = $c_stmt->fetch(PDO::FETCH_ASSOC);
                //is db>cr, if yes 
                return '<tr><td class="no_paddin_shade_no_Border">' . $d_tb_rows . '</td><td>' . $c_tb_rows . '</td></tr>'
                        . '<tr class="dc_totals"><td class="db_cr_cells ">' . number_format($d_res['amount']) . '</td><td class="db_cr_cells">' . number_format($c_res['amount']) . '</td></tr>'
                        . '';
            }

            function list_sum_income_or_expenses($inc_exp) {
                $db = new dbconnection();
                $sql = "select account.name as account, account.account_id, journal_entry_line.amount as amount, journal_entry_line.entry_date, journal_entry_line.memo from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = :name ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->execute(array(":name" => $inc_exp));
                ?><table class="income_table2">
                    <thead class="books_header">
                        <tr>
                            <td>Account</td>
                            <td>Amount</td>
                            <td>Memo</td>
                            <td>Amout</td>
                        </tr>
                    </thead> 
                    <?php
                    while ($row = $stmt->fetch()) {
                        ?>
                        <tr class="inner_sub">
                            <td><?php echo $row['account']; ?></td>
                            <td><?php echo $row['amount']; ?></td>
                            <td><?php echo $row['memo']; ?></td>
                            <td><?php echo $row['entry_date']; ?></td>
                        </tr> 
                    <?php }
                    ?><table><?php
//                $sale_revenue = $row['amount'];
                    }

                    function list_other_income($inc_exp) {
                        $db = new dbconnection();
                        $sql = "select account.account_id, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name''name
                group by account_type.account_type_id";
                        $stmt = $db->openConnection()->prepare($sql);
                        $stmt->execute(array(":name" => $inc_exp));
                        ?>
                        <table>
                            <tr>
                                <td>Name</td> <td>Amount </td>
                            </tr>
                        </table><?php
                        while ($row = $stmt->fetch()) {
                            ?>
                            <tr>
                                <td><?php echo $row['account_id']; ?></td>
                                <td><?php echo $row['amount']; ?></td>
                            </tr> 
                            <?php
                        }

//                $sale_revenue = $row['amount'];
                    }

                    function get_sum_liability() {
                        $db = new dbconnection();
                        $sql = "select sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'Other Current liability'
                or account_type.name = 'Long Term Liability'
                group by account_type.account_type_id";
                        $stmt = $db->openConnection()->prepare($sql);
                        $stmt->execute();

                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $field = $row['amount'];
                        return $field;
                    }

                    function get_sum_inventory($min_date, $max_date) {
                        $db = new dbconnection();
                        $sql = "select sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'Other Current asset'
                or account_type.name = 'Other Assets'
                or account_type.name = 'Fixed Assets' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id";
                        $stmt = $db->openConnection()->prepare($sql);
                        $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));

                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $field = $row['amount'];
                        return $field;
                    }

                    function get_sum_assets() {
                        $db = new dbconnection();
                        $sql = "select sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'Other Current asset'
                or account_type.name = 'Other Assets'
                or account_type.name = 'Fixed Assets'
                group by account_type.account_type_id";
                        $stmt = $db->openConnection()->prepare($sql);
                        $stmt->execute();

                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $field = $row['amount'];
                        return $field;
                    }

                    function get_sum_cash_by_date($min_date, $max_date) {// This function is implemented in cash flow. on 30th July
                        try {
                            $database = new dbconnection();
                            $db = $database->openConnection();
                            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $sql = " select max(account.name) as account, sum(journal_entry_line.amount)as amount from account
                right join journal_entry_line on journal_entry_line.accountid = account.account_id
                left join journal_entry_header on journal_entry_header.journal_entry_header_id = journal_entry_line.journal_entry_header
                join account_type on account.acc_type = account_type.account_type_id

                where account.book_section = 'asset' and journal_entry_line.dr_cr = 'debit' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account.name";
                            $stmt = $db->prepare($sql);
                            $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                            $row1 = $stmt->fetch(PDO::FETCH_ASSOC);
                            $debit = $row1['amount'];

                            $sql2 = " select max(account.name) as account, sum(journal_entry_line.amount)as amount from account
                right join journal_entry_line on journal_entry_line.accountid = account.account_id
                left join journal_entry_header on journal_entry_header.journal_entry_header_id = journal_entry_line.journal_entry_header
                join account_type on account.acc_type = account_type.account_type_id
                where account.book_section = 'asset' and journal_entry_line.dr_cr = 'credit' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account.name";
                            $stmt2 = $db->prepare($sql2);
                            $stmt2->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                            $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                            $credit = $row2['amount'];

                            $field = $debit - $credit;
                            return $field;
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }
                    }

                    function get_sum_cash_receipt_by_date($min_date, $max_date) {// In the database this is the purchase receipt
                        $database = new dbconnection();
                        $db = $database->openConnection();
                        $sql = " select sum(sales_receit_header.amount) as amount from sales_receit_header
                join account on account.account_id = sales_receit_header.account
                where sales_receit_header.entry_date >= :min_date and sales_receit_header.entry_date <= :max_date ";
                        $stmt = $db->prepare($sql);
                        $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $field = $row['amount'];
                        return $field;
                    }

                    function list_cash_receipt_by_date($min_date, $max_date) {
                        $database = new dbconnection();
                        $db = $database->openConnection();
                        $sql = " select sum(sales_receit_header.amount) as amount, account.name as account, sales_receit_header.entry_date from sales_receit_header
                join account on account.account_id = sales_receit_header.account
                where sales_receit_header.entry_date >= :min_date and sales_receit_header.entry_date <= :max_date
                ";
                        $stmt = $db->prepare($sql);
                        $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                        ?><table class="income_table2"><tr class="books_header">
                                <td>Account</td>
                                <td>Amount</td>
                                <td>Entry date</td>
                            </tr><?php
                            while ($row = $stmt->fetch()) {
                                ?>
                                <tr class="inner_sub">
                                    <td><?php echo $row['account']; ?></td>
                                    <td><?php echo $row['amount']; ?></td>
                                    <td><?php echo $row['entry_date']; ?></td>
                                </tr> 
                            <?php }
                            ?></table><?php
                    }

                    function get_sum_disbursement_receipt_by_date($min_date, $max_date) {// In the database this is the purchase receipt
                        $database = new dbconnection();
                        $db = $database->openConnection();
                        $sql = " select sum(amount) as amount from purchase_receit_line
                where purchase_receit_line.entry_date >= :min_date and purchase_receit_line.entry_date <= :max_date
                ";
                        $stmt = $db->prepare($sql);
                        $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));

                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $field = $row['amount'];
                        return $field;
                    }

                    function list_disbursement_receipt_by_date($min_date, $max_date) {
                        $database = new dbconnection();
                        $db = $database->openConnection();
                        $sql = " select sum(purchase_receit_line.amount) as amount, account.name as account, purchase_receit_line.entry_date from purchase_receit_line
                join account on account.account_id = purchase_receit_line.account
                where purchase_receit_line.entry_date >= :min_date and purchase_receit_line.entry_date <= :max_date
                ";
                        $stmt = $db->prepare($sql);
                        $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                        ?><table class="income_table2"><tr class="books_header">
                                <td>Account</td>
                                <td>Amount</td>
                                <td>Entry date</td>
                            </tr><?php
                            while ($row = $stmt->fetch()) {
                                ?>
                                <tr class="inner_sub">
                                    <td><?php echo $row['account']; ?></td>
                                    <td><?php echo $row['amount']; ?></td>
                                    <td><?php echo $row['entry_date']; ?></td>
                                </tr> 
                            <?php }
                            ?></table><?php
                    }

                    function list_sum_cash_by_date($min_date, $max_date) {// this function has one parameter but it check two parameters with or condition
                        $database = new dbconnection();
                        $db = $database->openConnection();
                        $sql = " select account.account_id, account.acc_type, account.acc_class, account.name, account.DrCrSide, account.acc_code, account.acc_desc, account.is_cash, account.is_contra_acc, account.is_row_version, journal_entry_line.journal_entry_line_id, journal_entry_line.accountid, journal_entry_line.dr_cr, sum(journal_entry_line.amount)as amount, journal_entry_line.memo, journal_entry_line.journal_entry_header, journal_entry_line.entry_date, journal_entry_header.journal_entry_header_id, journal_entry_header.party, journal_entry_header.voucher_type, journal_entry_header.date, journal_entry_header.memo, journal_entry_header.reference_number, journal_entry_header.posted, account_type.name from account
                right join journal_entry_line on journal_entry_line.accountid = account.account_id
                left join journal_entry_header on journal_entry_header.journal_entry_header_id = journal_entry_line.journal_entry_header
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'bank' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.name";
                        $stmt = $db->prepare($sql);
                        $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));

                        while ($row = $stmt->fetch()) {
                            ?>
                            <tr>
                                <td><?php echo $row['account'] ?></td><td><?php echo $row['amount']; ?></td>
                            </tr>    
                            <?php
                        }
                    }

                    function list_current_assets_by_date($min_date, $max_date) {
                        $db = new dbconnection();
                        $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'Other Current asset' or account_type.name = 'other asset' and account_type.name = 'other current asset' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id";
                        $stmt = $db->openConnection()->prepare($sql);
                        $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                        while ($row = $stmt->fetch()) {
                            ?><tr>
                                <td><?php echo $row['account'] ?></td>
                                <td><?php echo $row['amount'] ?></td>
                            </tr><?php
                        }
                    }

                    function get_sum_sales_stock_by_date($min_date, $max_date) {
                        $db = new dbconnection();
                        $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account.name = 'sales of stock' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                        $stmt = $db->openConnection()->prepare($sql);
                        $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $field = $row['amount'];
                        return $field;
                    }

                    function list_sales_stock_by_date($min_date, $max_date) {
                        $db = new dbconnection();
                        $sql = "select account.name as account, journal_entry_line.entry_date, journal_entry_line.memo, journal_entry_line.amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account.name = 'sales of stock' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                ";
                        $stmt = $db->openConnection()->prepare($sql);
                        $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                        while ($row = $stmt->fetch()) {
                            ?><tr>
                                <td><?php echo $row['account'] ?></td>
                                <td><?php echo $row['amount'] ?></td>
                                <td><?php echo $row['memo'] ?></td>
                                <td><?php echo $row['entry_date'] ?></td>
                            </tr><?php
                        }
                    }

                    function get_research_dev_by_date($min_date, $max_date) {
                        $db = new dbconnection();
                        $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account.name = 'Research and development' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                        $stmt = $db->openConnection()->prepare($sql);
                        $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $field = $row['amount'];
                        return $field;
                    }

                    function get_sum_cogs_by_date($min_date, $max_date) {
                        try {
                            $db = new dbconnection();
                            $con = $db->openconnection();
                            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                join main_contra_account on main_contra_account.main_account = account.account_id
                where account.name = 'Cost Of Good Sold' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                            $stmt = $db->openConnection()->prepare($sql);
                            $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            $field = $row['amount'];
                            return $field;
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }
                    }

                    function get_other_income_by_date($min_date, $max_date) {
                        try {
                            $db = new dbconnection();
                            $con = $db->openconnection();
                            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account.name = 'Other income' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                            $stmt = $db->openConnection()->prepare($sql);
                            $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            $field = $row['amount'];
                            return $field;
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }
                    }

                    function get_interestincome_by_date($min_date, $max_date) {
                        try {
                            $db = new dbconnection();
                            $con = $db->openconnection();
                            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $sql = "select max(account.name) as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account.name = 'Interest Income' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                            $stmt = $db->openConnection()->prepare($sql);
                            $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            $field = $row['amount'];
                            return $field;
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }
                    }

                    function get_incometax_by_date($min_date, $max_date) {
                        try {
                            $db = new dbconnection();
                            $con = $db->openconnection();
                            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $sql = "select max(account.name) as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account.name = 'Income tax' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                            $stmt = $db->openConnection()->prepare($sql);
                            $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            $field = $row['amount'];
                            return $field;
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }
                    }

                    function list_cogs_by_date($min_date, $max_date) {
                        $db = new dbconnection();
                        $sql = "select account.name as account, journal_entry_line.memo, journal_entry_line.entry_date, sum(journal_entry_line.amount) as amount from account
                                join journal_entry_line on journal_entry_line.accountid = account.account_id
                                join account_type on account.acc_type = account_type.account_type_id
                                where account_type.name = 'Cost Of Good Sold' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                                group by account_type.account_type_id ";
                        $stmt = $db->openConnection()->prepare($sql);
                        $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                        ?><table class="income_table2 book_part_details full_center_two_h heit_free">
                            <thead class="books_header">
                                <tr>
                                    <td>Account</td>
                                    <td>Amount</td>
                                    <td>Memo</td>
                                    <td>Entry date</td>
                                </tr>
                            </thead><?php
                            while ($row = $stmt->fetch()) {
                                ?><tr>
                                    <td><?php echo $row['account'] ?></td>
                                    <td><?php echo number_format($row['amount']) ?></td>
                                    <td><?php echo $row['memo'] ?></td>
                                    <td><?php echo $row['entry_date'] ?></td>
                                </tr><?php
                            }
                            ?></table><?php
                    }

                    function list_research_dev_by_date($min_date, $max_date) {
                        $db = new dbconnection();
                        $sql = "select account.name as account, journal_entry_line.amount, journal_entry_line.memo, journal_entry_line.entry_date from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'Other Expense' or account.name = 'Research and development' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date ";
                        $stmt = $db->openConnection()->prepare($sql);
                        $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                        ?><table class="income_table2"><thead class="books_header">
                                <tr>
                                    <td>Account</td>
                                    <td>Amount</td>
                                    <td>Memo</td>
                                    <td>Entry date</td>
                                </tr>
                            </thead><?php
                            while ($row = $stmt->fetch()) {
                                ?><tr>
                                    <td><?php echo $row['account'] ?></td>
                                    <td><?php echo number_format($row['amount']) ?></td>
                                    <td><?php echo $row['memo'] ?></td>
                                    <td><?php echo $row['entry_date'] ?></td>
                                </tr><?php
                            }
                            ?></table><?php
                    }

                    function get_sum_gen_expe_by_date($min_date, $max_date) {
                        $db = new dbconnection();
                        $sql = "select max(account.name) as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account.book_section = 'expense' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                        $stmt = $db->openConnection()->prepare($sql);
                        $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                        $field = $row['amount'];
                        return $field;
                    }

                    function list_gen_expe_by_date($min_date, $max_date) {
                        $db = new dbconnection();
                        $sql = "select account.name as account, journal_entry_line.entry_date, journal_entry_line.memo, journal_entry_line.amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'expense' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                ";
                        $stmt = $db->openConnection()->prepare($sql);
                        $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                        ?><table class="income_table2">
                            <tr class="books_header">
                                <td>Account</td>
                                <td>Amount</td>
                                <td>Memo</td>
                                <td>Entry date</td>
                            </tr>
                            <?php
                            while ($row = $stmt->fetch()) {
                                ?><tr>
                                    <td><?php echo $row['account'] ?></td>
                                    <td><?php echo number_format($row['amount']) ?></td>
                                    <td><?php echo $row['memo'] ?></td>
                                    <td><?php echo $row['entry_date'] ?></td>
                                </tr><?php }
                            ?><table><?php
                            }

                            function get_sum_current_assets_by_date($min_date, $max_date) {
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'Other Current asset' or account_type.name = 'Other Current Assets' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function get_sum_acc_rec_by_date($min_date, $max_date) {
                                $db = new dbconnection();
                                $sql = "select max(account.name) as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account .name = 'Accounts Receivable' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function get_sum_prep_exp_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account.name = 'Prepaid expenses' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function list_prep_exp_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, journal_entry_line.entry_date, journal_entry_line.memo, journal_entry_line.amount as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account.name = 'Prepaid expenses' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                ?><table class="income_table2 out_border"><tr>
                                    <thead class="books_header"> 
                                    <td> Account </td>
                                    <td> Name </td> 
                                    <td> Memo </td> 
                                    <td> Entry date </td> 
                                    <tr>
                                        </thead><?php
                                        while ($row = $stmt->fetch()) {
                                            ?><tr>
                                            <td><?php echo $row['account'] ?></td>
                                            <td><?php echo number_format($row['amount']) ?></td>
                                            <td><?php echo $row['memo'] ?></td>
                                            <td><?php echo $row['entry_date'] ?></td>
                                        </tr><?php }
                                        ?></table><?php
                            }

                            //
                            function list_other_current_asset_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, journal_entry_line.entry_date, journal_entry_line.memo, journal_entry_line.amount as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'Other Current asset' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                ?><table class="income_table2 out_border"><tr>
                                    <thead class="books_header"> 
                                    <td> Account </td>
                                    <td> Name </td> 
                                    <td> Memo </td> 
                                    <td> Entry date </td> 
                                    <tr>
                                        </thead><?php
                                        while ($row = $stmt->fetch()) {
                                            ?><tr>
                                            <td><?php echo $row['account'] ?></td>
                                            <td><?php echo number_format($row['amount']) ?></td>
                                            <td><?php echo $row['memo'] ?></td>
                                            <td><?php echo $row['entry_date'] ?></td>
                                        </tr><?php }
                                        ?></table><?php
                            }

                            function get_sum_acc_depreciation_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, journal_entry_line.memo, journal_entry_line.entry_date, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account.name = 'Prepaid expenses' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function list_acc_depreciation_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, journal_entry_line.memo, journal_entry_line.entry_date, journal_entry_line.memo, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'expense' and account.name = 'accumulated depreciation' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                ?><table class="income_table2 out_border"><tr>
                                    <thead class="books_header"> 
                                    <td> Account </td>
                                    <td> Name </td> 
                                    <td> Memo </td> 
                                    <td> Entry date </td> 
                                    <tr>
                                        </thead>
                                        <?php
                                        while ($row = $stmt->fetch()) {
                                            ?><tr>
                                            <td><?php echo $row['account'] ?></td>
                                            <td><?php echo number_format($row['amount']) ?></td>
                                            <td><?php echo $row['memo'] ?></td>
                                            <td><?php echo $row['entry_date'] ?></td>
                                        </tr><?php }
                                        ?></table><?php
                            }

                            function get_sum_acc_pay_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'account payable' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function list_acc_pay_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'Account Payable' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                ?><table class="income_table2 out_border">
                                    <thead class="books_header"> 
                                        <tr>
                                            <td>Account </td>
                                            <td>Amount </td>
                                            <td>Amount </td>
                                        </tr> 
                                    </thead> <?php
                                    while ($row = $stmt->fetch()) {
                                        ?><tr>
                                            <td><?php echo $row['account'] ?></td>
                                            <td><?php echo number_format($row['amount']) ?></td>
                                        </tr><?php
                                    }
                                    ?></table><?php
                            }

                            function get_sum_acrued_exp_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'expense' and account.name = 'accrued expense' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function list_acrued_exp_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'expense' and account.name = 'accrued expense' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                ?><table class="income_table2 out_border">
                                    <thead class="books_header"> 
                                        <tr>
                                            <td>Account </td>
                                            <td>Amount </td>
                                            <td>memo </td>
                                            <td>date</td>
                                        </tr> 
                                    </thead> <?php
                                    while ($row = $stmt->fetch()) {
                                        ?><tr>
                                            <td><?php echo $row['account'] ?></td>
                                            <td><?php echo number_format($row['amount']) ?></td>
                                            <td><?php echo $row['memo'] ?></td>
                                            <td><?php echo $row['entry_date'] ?></td>
                                        </tr><?php
                                    }
                                    ?></table><?php
                            }

                            function get_sum_currentportion_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'other current liability' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function list_currentportion_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, journal_entry_line.entry_date, journal_entry_line.memo, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'other current liability' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                ?><table class="income_table2 out_border">
                                    <thead class="books_header"> 
                                        <tr>
                                            <td>Account </td>
                                            <td>Amount </td>
                                            <td>memo </td>
                                            <td>date </td>
                                        </tr> 
                                    </thead> <?php
                                    while ($row = $stmt->fetch()) {
                                        ?><tr>
                                            <td><?php echo $row['account'] ?></td>
                                            <td><?php echo number_format($row['amount']) ?></td>
                                            <td><?php echo $row['memo'] ?></td>
                                            <td><?php echo $row['entry_date'] ?></td>
                                        </tr><?php
                                    }
                                    ?></table><?php
                            }

                            function get_sum_incometx_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'expense' and account.name = 'income tax' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function list_incometx_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'expense' and account.name = 'income tax' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                ?><table class="income_table2 out_border">
                                    <thead class="books_header"> 
                                        <tr>
                                            <td>Account </td>
                                            <td>Amount </td>
                                            <td>Entry date </td>
                                        </tr> 
                                    </thead> <?php
                                    while ($row = $stmt->fetch()) {
                                        ?><tr>
                                            <td><?php echo $row['account'] ?></td>
                                            <td><?php echo number_format($row['amount']) ?></td>
                                            <td><?php echo $row['entry_date'] ?></td>
                                        </tr><?php
                                    }
                                    ?></table><?php
                            }

                            function get_sum_acrud_exp_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account.name = 'accrued expenses' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function list_acrud_exp_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account.name = 'accrued expenses' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                ?><table class="income_table2"><tr>
                                        <td colspan="2"> </td><tr><?php
                                        while ($row = $stmt->fetch()) {
                                            ?><tr>
                                            <td><?php echo $row['account'] ?></td>
                                            <td><?php echo number_format($row['amount']) ?></td>
                                        </tr><?php }
                                        ?></table><?php
                            }

                            function get_sum_current_debt_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'other current liability' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function list_current_debt_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'other current liability' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                ?><table class="income_table2"><tr>
                                        <td colspan="2"> </td><tr><?php
                                        while ($row = $stmt->fetch()) {
                                            ?><tr>
                                            <td><?php echo $row['account'] ?></td>
                                            <td><?php echo number_format($row['amount']) ?></td>
                                        </tr><?php }
                                        ?></table><?php
                            }

                            function get_sum_longterm_debt_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'long term liability' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function get_sum_capital_stock_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'equity' and account.name = 'share capital' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function list_longterm_debt_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, journal_entry_line.entry_date, journal_entry_line.memo, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'long term liability' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                ?><table class="income_table2 out_border">
                                    <thead class="books_header"><tr>
                                            <td>Account </td>
                                            <td>Amount </td>
                                            <td>Memo </td>
                                            <td>Entry date </td>
                                        <tr></thead><?php
                                    while ($row = $stmt->fetch()) {
                                        ?><tr>
                                            <td><?php echo $row['account'] ?></td>
                                            <td><?php echo number_format($row['amount']) ?></td>
                                            <td><?php echo $row['memo'] ?></td>
                                            <td><?php echo $row['entry_date'] ?></td>
                                        </tr><?php }
                                    ?></table><?php
                            }

                            function get_capital_stock_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account.name = 'capital stock' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function list_capital_stock_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, journal_entry_line.entry_date, journal_entry_line.memo, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account.name = 'capital stock' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                ?><table class="income_table2 out_border">
                                    <thead class="books_header"><tr>
                                            <td>Account </td>
                                            <td>Amount </td>
                                            <td>Memo </td>
                                            <td>Entry date </td>
                                        <tr></thead><?php
                                    while ($row = $stmt->fetch()) {
                                        ?><tr>
                                            <td><?php echo $row['account'] ?></td>
                                            <td><?php echo number_format($row['amount']) ?></td>
                                            <td><?php echo $row['memo'] ?></td>
                                            <td><?php echo $row['entry_date'] ?></td>
                                        </tr><?php }
                                    ?></table><?php
                            }

                            function get_sum_retained_earn_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account.book_section = 'liability' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function list_retained_earn_by_date($min_date, $max_date) {// these are the prepaid expenses
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account.name = 'retained earnings' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                ?><table class="income_table2"><tr>
                                        <td colspan="2"> </td><tr><?php
                                        while ($row = $stmt->fetch()) {
                                            ?><tr>
                                            <td><?php echo $row['account'] ?></td>
                                            <td><?php echo number_format($row['amount']) ?></td>
                                        </tr><?php }
                                        ?></table><?php
                            }

                            function list_acc_rec_by_date($min_date, $max_date) {
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'account receivable' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                ?><table class="income_table2"><tr>
                                        <td colspan="2"> </td><tr><?php
                                        while ($row = $stmt->fetch()) {
                                            ?><tr>
                                            <td><?php echo $row['account'] ?></td>
                                            <td><?php echo number_format($row['amount']) ?></td>
                                        </tr><?php }
                                        ?></table><?php
                            }

                            function get_sum_inventory_by_date($min_date, $max_date) {
                                $db = new dbconnection();
                                $sql = " select max(account.name) as account, sum(journal_entry_line.amount)as amount from account
                right join journal_entry_line on journal_entry_line.accountid = account.account_id
                left join journal_entry_header on journal_entry_header.journal_entry_header_id = journal_entry_line.journal_entry_header
                join account_type on account.acc_type = account_type.account_type_id
                where account.name = 'inventory' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account.name ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function list_inventory_by_date($min_date, $max_date) {
                                $db = new dbconnection();
                                $sql = "select purchase_invoice_line.account, account.name as acc, stock_into_main.entry_date, stock_into_main.item, sum(stock_into_main.quantity-distriibution.taken_qty) as quantity_out,
                sum(purchase_invoice_line.amount) as amount, purchase_invoice_line.unit_cost from stock_into_main
                join purchase_invoice_line on purchase_invoice_line.purchase_invoice_line_id = stock_into_main.purchaseid
                join distriibution on distriibution.item = stock_into_main.item
                join account on account.account_id = purchase_invoice_line.account
                where stock_into_main.entry_date >= :min_date and stock_into_main.entry_date <= :max_date
                group by stock_into_main.item ";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                ?><table class="income_table2 out_border"><tr>
                                    <thead class="books_header"> 
                                    <td>Account    </td>
                                    <td>  Amount  </td>
                                    <!--<td>  Memo  </td>-->
                                    <td>  Entry date  </td>
                                    <tr></thead><?php
                                        while ($row = $stmt->fetch()) {
                                            ?><tr>
                                            <td><?php echo $row['acc'] ?></td>
                                            <td><?php echo number_format($row['amount']) ?></td>
                                            <!--<td><?php echo number_format($row['memo']) ?></td>-->
                                            <td><?php echo $row['entry_date'] ?></td>
                                        </tr><?php }
                                        ?></table><?php
                            }

                            function list_fixed_assets_by_date($min_date, $max_date) {
                                $db = new dbconnection();
                                $sql = "select account.name as account, journal_entry_line.memo, journal_entry_line.entry_date, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name<>'Bank' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                ?><table class="income_table2 out_border">
                                    <thead class="books_header"> 
                                        <tr><td>Account</td>
                                            <td>Amount</td>
                                            <td>Memo</td>
                                            <td>Entry date</td>
                                        <tr></thead><?php
                                    while ($row = $stmt->fetch()) {
                                        ?><tr>
                                            <td><?php echo $row['account'] ?></td>
                                            <td><?php echo number_format($row['amount']) ?></td>
                                            <td><?php echo $row['memo'] ?></td>
                                            <td><?php echo $row['entry_date'] ?></td>
                                        </tr><?php }
                                    ?></table><?php
                            }

                            function get_sum_fixed_assets_by_date($min_date, $max_date) {
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'fixed asset' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function get_sum_liability_by_date($min_date, $max_date) {
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'liability' or account_type.name = 'other current liability' or account_type.name = 'long term liability' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function list_liability_by_date($min_date, $max_date) {
                                $db = new dbconnection();
                                $sql = "select account.name as account, sum(journal_entry_line.amount) as amount from account
                join journal_entry_line on journal_entry_line.accountid = account.account_id
                join account_type on account.acc_type = account_type.account_type_id
                where account_type.name = 'liability' or account_type.name = 'other current liability' or account_type.name = 'long term liability' and journal_entry_line.entry_date >= :min_date and journal_entry_line.entry_date <= :max_date
                group by account_type.account_type_id";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                while ($row = $stmt->fetch()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $row['account'] ?></td>
                                        <td><?php echo number_format($row['amount']) ?></td>
                                    </tr>                      
                                    <?php
                                }
                            }

                            function get_project_by_project_line($type) {
                                try {
                                    $database = new dbconnection();
                                    $db = $database->openconnection();
                                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql = "select p_budget_prep.p_budget_prep_id, p_budget_prep.name, p_budget_prep.budget_type from p_budget_prep join p_type_project on p_type_project.p_type_project_id = p_budget_prep.project_type where p_type_project.p_type_project_id = :type";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute(array(":type" => $type));
                                    $data = array();
                                    while ($row = $stmt->fetch()) {
                                        $data[] = array(
                                            'id' => $row['p_budget_prep_id'],
                                            'name' => $row['name'] . ' - (' . $row['budget_type'] . ' )'
                                        );
                                    }
                                    return json_encode($data);
                                } catch (PDOException $e) {
                                    echo $e;
                                }
                            }

                            function get_actiovity_by_project($type) {
                                try {
                                    $database = new dbconnection();
                                    $db = $database->openconnection();
                                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql = " select p_activity.p_activity_id, p_activity.name from p_activity join p_budget_prep on p_budget_prep.p_budget_prep_id = p_activity.project where p_budget_prep.p_budget_prep_id = :id";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute(array(":id" => $type));
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

                            function get_sum_income__exp_project($inc_expense) {//this function retreives the income or expense from sales receit
                                $db = new dbconnection();
                                $sql = "select sales_receit_header.sales_receit_header_id, sum(sales_invoice_line.amount) as amount from sales_receit_header
                join sales_invoice_line on sales_receit_header.sales_invoice = sales_invoice_line.sales_invoice_line_id
                join account on account.account_id = sales_receit_header.account
                join account_type on account.acc_type = account_type.account_type_id
                where account_type = :name";
                                $stmt = $db->openConnection()->prepare($sql);
                                $stmt->execute(array(":name" => $inc_expense));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

// </editor-fold>
                            // <editor-fold defaultstate="collapsed" desc=" --Combo refilll on the fly --">
                            function get_cbo_refilled_p_type_project() {
                                try {
                                    $database = new dbconnection();
                                    $db = $database->openconnection();
                                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql = "select p_type_project_id, name from p_type_project";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    $data = array();
                                    while ($row = $stmt->fetch()) {
                                        $data[] = array(
                                            'id' => $row['p_type_project_id'],
                                            'name' => $row['name']
                                        );
                                    }
                                    return json_encode($data);
                                } catch (PDOException $e) {
                                    echo $e;
                                }
                            }

                            function get_cbo_refilled_p_fiscal_year() {
                                try {
                                    $database = new dbconnection();
                                    $db = $database->openconnection();
                                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql = "select p_fiscal_year_id, fiscal_year_name from p_fiscal_year";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    $data = array();
                                    while ($row = $stmt->fetch()) {
                                        $data[] = array(
                                            'id' => $row['p_fiscal_year_id'],
                                            'name' => $row['fiscal_year_name']
                                        );
                                    }
                                    return json_encode($data);
                                } catch (PDOException $e) {
                                    echo $e;
                                }
                            }

                            function get_cbo_refilled_p_budget_items() {
                                try {
                                    $database = new dbconnection();
                                    $db = $database->openconnection();
                                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql = "select p_budget_items_id, item_name from p_budget_items";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    $data = array();
                                    while ($row = $stmt->fetch()) {
                                        $data[] = array(
                                            'id' => $row['p_budget_items_id'],
                                            'name' => $row['item_name']
                                        );
                                    }
                                    return json_encode($data);
                                } catch (PDOException $e) {
                                    echo $e;
                                }
                            }

                            function get_cbo_refilled_account() {
                                try {
                                    $database = new dbconnection();
                                    $db = $database->openconnection();
                                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql = "select account_id, name from account";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    $data = array();
                                    while ($row = $stmt->fetch()) {
                                        $data[] = array(
                                            'id' => $row['account_id'],
                                            'name' => $row['name']
                                        );
                                    }
                                    return json_encode($data);
                                } catch (PDOException $e) {
                                    echo $e;
                                }
                            }

                            function get_cbo_refilled_supplier() {
                                try {
                                    $database = new dbconnection();
                                    $db = $database->openconnection();
                                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql = "select supplier.supplier_id, supplier.name, party.name as party from supplier "
                                            . " join party on party.party_id = supplier.party";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    $data = array();
                                    while ($row = $stmt->fetch()) {
                                        $data[] = array(
                                            'id' => $row['supplier_id'],
                                            'name' => $row['party']
                                        );
                                    }
                                    return json_encode($data);
                                } catch (PDOException $e) {
                                    echo $e;
                                }
                            }

                            function get_cbo_refilled_customer() {
                                try {
                                    $database = new dbconnection();
                                    $db = $database->openconnection();
                                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql = "select customer_id, party.name as party from customer "
                                            . " join party on party.party_id = customer.party_id";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    $data = array();
                                    while ($row = $stmt->fetch()) {
                                        $data[] = array(
                                            'id' => $row['customer_id'],
                                            'name' => $row['party']
                                        );
                                    }
                                    return json_encode($data);
                                } catch (PDOException $e) {
                                    echo $e;
                                }
                            }

                            function get_cbo_refilled_measurement() {
                                try {
                                    $database = new dbconnection();
                                    $db = $database->openconnection();
                                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql = "select measurement_id, code from measurement ";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    $data = array();
                                    while ($row = $stmt->fetch()) {
                                        $data[] = array(
                                            'id' => $row['measurement_id'],
                                            'name' => $row['code']
                                        );
                                    }
                                    return json_encode($data);
                                } catch (PDOException $e) {
                                    echo $e;
                                }
                            }

                            function get_cbo_refilled_p_activity() {
                                try {
                                    $database = new dbconnection();
                                    $db = $database->openconnection();
                                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql = "select p_activity_id, name from p_activity";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
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

                            function get_cbo_refilled_staff_positions() {
                                try {
                                    $database = new dbconnection();
                                    $db = $database->openconnection();
                                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql = "select staff_positions_id, name from staff_positions";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    $data = array();
                                    while ($row = $stmt->fetch()) {
                                        $data[] = array(
                                            'id' => $row['staff_positions_id'],
                                            'name' => $row['name']
                                        );
                                    }
                                    return json_encode($data);
                                } catch (PDOException $e) {
                                    echo $e;
                                }
                            }

                            function get_cbo_refilled_p_budget_prep() {
                                try {
                                    $database = new dbconnection();
                                    $db = $database->openconnection();
                                    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                    $sql = "select p_budget_prep_id, name from p_budget_prep";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    $data = array();
                                    while ($row = $stmt->fetch()) {
                                        $data[] = array(
                                            'id' => $row['p_budget_prep_id'],
                                            'name' => $row['name']
                                        );
                                    }
                                    return json_encode($data);
                                } catch (PDOException $e) {
                                    echo $e;
                                }
                            }

// </editor-fold>

                            function get_items_by_request($mainrequest) {
                                $database = new dbconnection();
                                $db = $database->openConnection();
                                $sql = "select p_request.p_request_id, p_request.item, p_request.quantity, p_request.unit_cost, p_request.amount, p_request.entry_date, p_request.User, p_request.measurement, p_request.request_no, p_budget_items.item_name"
                                        . " from p_request "
                                        . " join p_budget_items on p_request.item = p_budget_items.p_budget_items_id "
                                        . " join main_request on main_request.Main_Request_id = p_request.main_req"
                                        . " where p_request.main_req = :activity";
                                $stmt = $db->prepare($sql);
                                $stmt->execute(array(":activity" => $mainrequest));
                                ?>
                                <script>
                                    $(document).ready(function () {
                                        $('.only_numbers').keyup(function (event) {
                                            if (event.which >= 37 && event.which <= 40)
                                                return;
                                            // format number
                                            $(this).val(function (index, value) {
                                                return value.replace(/\D/g, "").replace(/\B(?=(\d{3})+(?!\d))/g, ", ");
                                            });

                                        });
                                        $('.only_numbers').keydown(function (e) {
                                            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                                                    // Allow: Ctrl+A, Command+A
                                                            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                                                            // Allow: home, end, left, right, down, up
                                                                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                                                        // let it happen, don't do anything
                                                        return;
                                                    }
                                                    // Ensure that it is a number and stop the keypress
                                                    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                                                        e.preventDefault();
                                                    }
                                                });
                                    });
                                </script>
                                <form method="post" action="new_purchase_order_line.php">
                                    <table class="res_table_no_styles">
                                        <thead><tr>
                                                <td>  Item </td>
                                                <td>  Quantity </td>
                                                <td>  Unit cost </td>
                                                <td class="off">  Amount     </td>
                                                <td>  Supplier     </td>
                                            </tr></thead>
                                        <?php
                                        $pages = 1;
                                        while ($row = $stmt->fetch()) {
                                            ?><tr> 
                                                <td><?php echo '<input class="only_numbers" name="txt_item" disabled type="text" value="' . $row['item_name'] . '">'; ?>
                                                    <?php echo '<input class="only_numbers" name="txt_itemid[]" type="hidden" value="' . $row['item'] . '">'; ?>
                                                    <?php echo '<input class="only_numbers" name="txt_requestid[]" type="hidden" value="' . $row['p_request_id'] . '">'; ?>

                                                </td>
                                                <td><?php echo '<input class="only_numbers" name="txt_quantity[]" type="text" value="' . number_format($row['quantity']) . '">'; ?></td>
                                                <td><?php echo '<input class="only_numbers" name="txt_unit_cost[]" type="text" value="' . number_format($row['unit_cost']) . '">' ?></td>
                                                <td><?php $this->get_supplier_sml_combo(); ?></td>

                                                <td class="off"><?php echo '<input class="only_numbers"  type="text" value="' . number_format($row['amount']) . '">"'; ?></td>
                                            </tr>
                                            <?php
                                            $pages += 1;
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="3"><input type="submit" class="confirm_buttons push_right btn_save_po" name="send_po" value="Save" style="float: right; margin-right: 0px;" /> </td>
                                        </tr>
                                    </table></form>
                                <?php
                            }

                            function list_p_type_project_excel() {
                                $database = new dbconnection();
                                $db = $database->openConnection();
                                $sql = "select * from p_type_project";
                                $stmt = $db->prepare($sql);
                                $stmt->execute();
                                $output = '';
                                $output .= '
                <table class = "dataList_table">
                <thead><tr>
                <td> S/N </td>
                <td> Name </td>
                </tr></thead>
                ';

                                while ($row = $stmt->fetch()) {
                                    $output .= '<tr>

                <td>
                ' . $row['p_type_project_id'] . '
                </td>
                <td class = "name_id_cols p_type_project " title = "p_type_project" >
                ' . $row['name'] . '
                </td>

                </tr>';
                                }

                                $output .= '</table>';
                                header("Content-Type: vnd.ms-excel");
                                header("Content-Disposition:attachment; filename=excel_out.xls");
                                header("Pragma: no-cache");
                                header("Expires: 0");
                                echo $output;
                            }

                            function get_accounts_rec_pay($rec_pay) {
                                $database = new dbconnection();
                                $db = $database->openConnection();
                                $sql = "select account.name as account,account_type.name ,sum(journal_entry_line.amount) as amount from journal_entry_line join account on account.account_id=journal_entry_line.accountid
                            join account_type on account.acc_type=account_type.account_type_id
                            where account_type.name=:type
                            group by account_type.name";
                                $stmt = $db->prepare($sql);
                                $stmt->execute(array(":type" => $rec_pay));
                                ?>
                                <table class="dataList_table">
                                    <thead><tr>
                                            <td> Account </td>

                                            <td>  Amount     </td>
                                        </tr>

                                    </thead>
                                    <?php
                                    $pages = 1;
                                    while ($row = $stmt->fetch()) {
                                        ?><tr> 
                                            <td>
                                                <?php echo $row['account']; ?>
                                            </td>
                                            <td class="quantity_id_cols sales_invoice_line " title="sales_invoice_line" >
                                                <?php echo number_format($this->_e(number_format($row['amount']))); ?>
                                            </td>
                                        </tr>
                                        <?php
                                        $pages += 1;
                                    }
                                    ?></table>
                                <?php
                            }

                            function get_sum_net_borrowing_by_date($min_date, $max_date) {
                                $database = new dbconnection();
                                $db = $database->openConnection();
                                $sql = "select account.name as account,account_type.name ,sum(journal_entry_line.amount) as amount from journal_entry_line
                                        join account on account.account_id=journal_entry_line.accountid
                                        join account_type on account.acc_type=account_type.account_type_id
                                        where account_type.name='account payable' or account_type.name='long term liability' or account_type.name='other current liability' and journal_entry_line.entry_date>=:min_date and journal_entry_line.entry_date<=:max_date
                                            ";
                                $stmt = $db->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                $field = $row['amount'];
                                return $field;
                            }

                            function list_sum_net_borrowing_by_date($min_date, $max_date) {
                                $database = new dbconnection();
                                $db = $database->openConnection();
                                $sql = "select account.name as account, account_type.name, journal_entry_line.entry_date,  journal_entry_line.memo,  journal_entry_line.amount  from journal_entry_line
                                    join account on account.account_id=journal_entry_line.accountid
                                    join account_type on account.acc_type=account_type.account_type_id
                                    where account_type.name='account payable' or account_type.name='long term liability' or account_type.name='other current liability' and journal_entry_line.entry_date>=:min_date and journal_entry_line.entry_date<=:max_date
                                     ";
                                $stmt = $db->prepare($sql);
                                $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
                                ?><table class="income_table2">
                                    <thead class="books_header">
                                        <tr>
                                            <td>Account</td>
                                            <td>Amount</td>
                                            <td>Memo</td>
                                            <td>Entry date</td>
                                        </tr>
                                    </thead><?php
                                    while ($row = $stmt->fetch()) {
                                        ?><tr>
                                            <td><?php echo $row['account'] ?></td>
                                            <td><?php echo number_format($row['amount']); ?></td>
                                            <td><?php echo $row['memo']; ?></td>
                                            <td><?php echo $row['entry_date']; ?></td>
                                        </tr><?php
                                    }
                                }

                                function get_accounts_cash($rec_pay) {
                                    $database = new dbconnection();
                                    $db = $database->openConnection();
                                    $sql = "select account.name as account,account_type.name ,sum(journal_entry_line.amount) as amount from journal_entry_line join account on account.account_id=journal_entry_line.accountid
                            join account_type on account.acc_type=account_type.account_type_id
                            where account_type.name='bank'
                            group by account_type.name";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute(array(":type" => $rec_pay));
                                    ?>
                                    <table class="dataList_table">
                                        <thead><tr>
                                                <td> Account </td>

                                                <td>  Amount     </td>
                                            </tr></thead>
                                        <?php
                                        $pages = 1;
                                        while ($row = $stmt->fetch()) {
                                            ?><tr> 

                                                <td>
                                                    <?php echo $row['account']; ?>
                                                </td>
                                                <td class="quantity_id_cols sales_invoice_line " title="sales_invoice_line" >
                                                    <?php echo number_format($this->_e($row['amount'])); ?>
                                                </td>

                                            </tr>

                                            <?php
                                            $pages += 1;
                                        }
                                        ?></table>
                                    <?php
                                }

                                function get_on_sales_from_quotation($quotation) {
                                    require_once('../web_db/connection.php');
                                    $database = new dbconnection();
                                    $db = $database->openConnection();
                                    $sql = "select sales_quote_line.sales_quote_line_id,  sales_quote_line.quantity,  sales_quote_line.unit_cost,  sales_quote_line.entry_date,  sales_quote_line.User,  sales_quote_line.amount,  sales_quote_line.measurement,  sales_quote_line.item
                            from sales_quote_line  where sales_quote_line_id=:quotation ";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute(array(":quotation" => $quotation));
                                    ?>
                                    <table class="new_data_table">
                                        <thead><tr><td> sales_quote_line_id </td><td> quantity </td><td> unit_cost </td><td> entry_date </td><td> User </td><td> amount </td><td> measurement </td><td> item </td>
                                            </tr></thead>

                                        <?php while ($row = $stmt->fetch()) { ?><tr> 
                                                <td>        <?php echo $row['sales_quote_line_id']; ?> </td>
                                                <td>        <?php echo $row['quantity']; ?> </td>
                                                <td>        <?php echo $row['unit_cost']; ?> </td>
                                                <td>        <?php echo $row['entry_date']; ?> </td>
                                                <td>        <?php echo $row['User']; ?> </td>
                                                <td>        <?php echo $row['amount']; ?> </td>
                                                <td>        <?php echo $row['measurement']; ?> </td>
                                                <td>        <?php echo $row['item']; ?> </td>

                                            </tr>
                                        <?php } ?></table>
                                    <?php
                                }

                                function get_supplier_sml_combo() {
                                    require_once('../web_db/connection.php');
                                    $database = new dbconnection();
                                    $db = $database->openConnection();
                                    $sql = "select party.party_id ,party.name from party where party.party_type='supplier'";
                                    ?>
                                    <select class="sml_cbo" name="suppliers_cbo[]"><option></option>
                                        <?php
                                        foreach ($db->query($sql) as $row) {
                                            echo "<option value=" . $row['party_id'] . ">" . $row['name'] . " </option>";
                                        }
                                        ?>
                                    </select>
                                    <?php
                                }

                                function get_item_by_pur_invoice($invoice) {
                                    $db = new dbconnection();
                                    $sql = "select purchase_invoice_line.purchase_invoice_line_id from purchase_invoice_line
                            join purchase_order_line on purchase_order_line.purchase_order_line_id=purchase_invoice_line.purchase_order
                            join p_request on   p_request.p_request_id=purchase_order_line.request
                             join p_budget_items on p_budget_items.p_budget_items_id=p_request.item
                             where purchase_invoice_line.purchase_invoice_line_id=:invoice ";
                                    $stmt = $db->openConnection()->prepare($sql);
                                    $stmt->bindValue(':invoice', $invoice);
                                    $stmt->execute();
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $field = $row['purchase_invoice_line_id'];
                                    return $field;
                                }

                                // <editor-fold defaultstate="collapsed" desc="--------Alerts unfinished processes -----------">
                                function pur_ntfinished_request_p_order() {
                                    $db = new dbconnection();
                                    $sql = "select count(p_request.p_request_id) as p_request_id from p_request where p_request_id not in (select request from purchase_order_line)";
                                    $stmt = $db->openConnection()->prepare($sql);
                                    $stmt->execute();
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $field = $row['p_request_id'];
                                    return $field;
                                }

                                function pur_ntfinished_p_order_p_invoice() {
                                    $db = new dbconnection();
                                    $sql = "select count(purchase_order_line_id) as purchase_order_line_id from purchase_order_line where purchase_order_line.purchase_order_line_id not in (select purchase_order from purchase_invoice_line)";
                                    $stmt = $db->openConnection()->prepare($sql);
                                    $stmt->execute();
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $field = $row['purchase_order_line_id'];
                                    return $field;
                                }

                                function pur_ntfinished_p_invoice_p_receit() {
                                    $db = new dbconnection();
                                    $sql = "select count( purchase_invoice_line_id) as purchase_invoice_line_id from purchase_invoice_line where purchase_invoice_line_id not in (select purchase_invoice from purchase_receit_line)";
                                    $stmt = $db->openConnection()->prepare($sql);
                                    $stmt->execute();
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $field = $row['purchase_invoice_line_id'];
                                    return $field;
                                }

                                function sale_ntfinished_quote_salesorder() {
                                    $db = new dbconnection();
                                    $sql = "select count(sales_quote_line_id) as sales_quote_line_id from sales_quote_line where sales_quote_line_id not in (select quotationid from sales_order_line)";
                                    $stmt = $db->openConnection()->prepare($sql);
                                    $stmt->execute();
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $field = $row['sales_quote_line_id'];
                                    return $field;
                                }

                                function sale_ntfinished_salesorder_salesinvoice() {
                                    $db = new dbconnection();
                                    $sql = "select count(sales_order_line_id) as sales_order_line_id from sales_order_line where sales_order_line_id not in (select sales_order from sales_invoice_line)";
                                    $stmt = $db->openConnection()->prepare($sql);
                                    $stmt->execute();
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $field = $row['sales_order_line_id'];
                                    return $field;
                                }

                                function sale_ntfinished_salesinvoice_salesreceipt() {
                                    $db = new dbconnection();
                                    $sql = "select count(sales_receit_header_id) as sales_receit_header_id from sales_receit_header where sales_invoice not in(select sales_invoice from sales_receit_header)";
                                    $stmt = $db->openConnection()->prepare($sql);
                                    $stmt->execute();
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $field = $row['sales_receit_header_id'];
                                    return $field;
                                }

// </editor-fold>

                                function get_this_year_start_date() {
                                    $start_year = date('Y');
                                    $start_month = '0' . 1;
                                    $start_date = '0' . 1;
                                    if (isset($_SESSION['rep_min_date'])) {
                                        return $_SESSION['rep_min_date'];
                                    } else {
                                        return $start_year . '-' . $start_month . '-' . $start_date;
                                    }
                                }

                                function get_this_year_end_date() {
                                    //End this year
                                    if (isset($_SESSION['rep_max_date'])) {
                                        return $_SESSION['rep_max_date'];
                                    } else {
                                        return date('Y-m-d h:m:s');
                                    }
                                }

                                function get_type_project_and_common_in_combo() {
                                    require_once('../web_db/connection.php');
                                    $database = new dbconnection();
                                    $db = $database->openConnection();
                                    $sql = "select p_type_project.p_type_project_id,   p_type_project.name from p_type_project";
                                    ?>
                                    <select name="type" class="textbox cbo_type_project fly_new_p_type_project cbo_onfly_p_type_project_change"><option></option> <option value="fly_new_p_type_project">-- Add new --</option> 
                                        <?php
                                        foreach ($db->query($sql) as $row) {
                                            echo "<option value=" . $row['p_type_project_id'] . ">" . $row['name'] . " </option>";
                                        }
                                        ?>
                                    </select>
                                    <?php
                                }

                                function get_mainstock_items_in_combo() {
                                    require_once('../web_db/connection.php');
                                    $database = new dbconnection();
                                    $db = $database->openConnection();
                                    $sql = "select main_stock.item,p_budget_items.item_name from main_stock "
                                            . " join p_budget_items on p_budget_items.p_budget_items_id=main_stock.item "
                                            . "group by p_budget_items.p_budget_items_id";
                                    ?>
                                    <select name="acc_item_combo[]"  class="textbox cbo_also_rep cbo_items"><option></option> <option valuealso_rep="fly_new_p_budget_items">--Add new--</option>
                                        <?php
                                        foreach ($db->query($sql) as $row) {
                                            echo "<option value=" . $row['item'] . ">" . $row['item_name'] . " </option>";
                                        }
                                        ?>
                                    </select>
                                    <?php
                                }

                                function list_taxgroup_toenable_disable() {
                                    $database = new dbconnection();
                                    $db = $database->openConnection();
                                    $sql = "select * from taxgroup";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    ?>
                                    <?php
                                    $pages = 1;
                                    while ($row = $stmt->fetch()) {
                                        ?><tr> 
                                            <td class="description_id_cols taxgroup " title="taxgroup" >
                                                <?php echo $this->_e($row['description']); ?>
                                            </td>

                                            <td class="">
                                                <input type="checkbox" name="enable" checked="" class="enable_tax">
                                            </td>
                                        </tr>
                                        <?php
                                        $pages += 1;
                                    }
                                    ?>
                                    <?php
                                }

                                function list_account_toenable_disable() {
                                    $database = new dbconnection();
                                    $db = $database->openConnection();
                                    $sql = "select account_id, account.name, account_type.name as type, sum(journal_entry_line.amount) as amount from account "
                                            . " join account_type on account.acc_type=account_type.account_type_id "
                                            . " left join journal_entry_line on journal_entry_line.accountid =account.account_id "
                                            . " group by account.name order by amount desc ";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    ?>

                                    <?php
                                    $c = 0;
                                    while ($row = $stmt->fetch()) {
                                        $c += 1;
                                        ?><tr class="clickable_row" data-table_id="<?php echo $row['account_id']; ?>"     data-bind="account" > 
                                            <td>    <label for="<?php echo 'acc' . $c ?>">  <?php echo $row['name']; ?></label>   </td>
                                            <td>     <input type="checkbox" checked="" id="<?php echo 'acc' . $c ?>" name="enable" class="enable_account"> </td>
                                        </tr>
                                        <?php
                                    }
                                    ?> 
                                    <?php
                                }

                                function list_users_toenable_disable() {
                                    $database = new dbconnection();
                                    $db = $database->openConnection();
                                    $sql = "select account_id, account.name, account_type.name as type, sum(journal_entry_line.amount) as amount from account "
                                            . " join account_type on account.acc_type=account_type.account_type_id "
                                            . " left join journal_entry_line on journal_entry_line.accountid =account.account_id "
                                            . " group by account.name order by amount desc ";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    ?>

                                    <?php
                                    $c = 0;
                                    while ($row = $stmt->fetch()) {
                                        $c += 1;
                                        ?><tr class="clickable_row" data-table_id="<?php echo $row['account_id']; ?>"     data-bind="account" > 
                                            <td>    <label for="<?php echo 'acc' . $c ?>">  <?php echo $row['name']; ?></label>   </td>
                                            <td>     <input type="checkbox" checked="" id="<?php echo 'acc' . $c ?>" name="enable" class="enable_account"> </td>
                                        </tr>
                                        <?php
                                    }
                                    ?> 
                                    <?php
                                }

                                function list_taxgroup_to_make_calculations() {
                                    $database = new dbconnection();
                                    $db = $database->openConnection();
                                    $sql = "select * from taxgroup";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute();
                                    ?>
                                    <table class="journal_entry_table tax_table" style="width: auto;">
                                        <thead><tr>
                                                <td> S/N </td>
                                                <td> Description </td>
                                                <td> Sales/Purchase </td>
                                                <td> Value </td>
                                                <td> Confirm </td>
                                                <td class="off"> Tax Applied </td>
                                                <td class="off"> Is Active </td>
                                            </tr></thead>

                                        <?php
                                        $pages = 1;
                                        while ($row = $stmt->fetch()) {
                                            ?><tr> 
                                                <td>
                                                    <?php echo $row['taxgroup_id']; ?>
                                                </td>
                                                <td class="description_id_cols taxgroup tax_label_col" data-bind="<?php echo $row['taxgroup_id']; ?>" title="taxgroup"  >
                                                    <?php echo $this->_e($row['description']); ?>
                                                </td>
                                                <td title="taxgroup" >
                                                    <?php echo $this->_e($row['pur_sale']); ?>
                                                </td>
                                                <td class="value_td" value="400">
                                                    <input type="text" class="textbox" />
                                                </td>
                                                <td>
                                                    <input type="button" class="confirm_buttons conf_tax" data-bind="<?php echo $row['taxgroup_id']; ?>" value="confirm"  style="margin-top: 0px;background-image: none;"/>
                                                </td>
                                                <td class="off">
                                                    <?php echo $this->_e($row['tax_applied']); ?>
                                                </td>
                                                <td class="off">
                                                    <?php echo $this->_e($row['is_active']); ?>
                                                </td>
                                            </tr>
                                            <?php
                                            $pages += 1;
                                        }
                                        ?></table>
                                    <?php
                                }

                                function get_tax_formula_exits($tax) {
                                    try {
                                        $con = new dbconnection();
                                        $db = $con->openconnection();
                                        $sql = "select tax_calculations.tax_calculations_id from tax_calculations"
                                                . " where tax_calculations.tax=:tax ";
                                        $stmt = $db->prepare($sql);
                                        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                        $stmt->execute(array(":tax" => $tax));
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $field = $row['tax_calculations_id'];
                                        return $field;
                                    } catch (PDOException $e) {
                                        echo $e->getMessage();
                                    }
                                }

                                function get_total_tax_on_pur_sale_by_date($date1, $date2, $pur_sale) {// this function calculates the total tax on purchase or on sale
                                    try {
                                        $con = new dbconnection();
                                        $db = $con->openconnection();
                                        $sql = "select sum(tax_percentage.amount) as tot_tax from tax_percentage
                                join purchase_invoice_line on purchase_invoice_line.purchase_invoice_line_id=tax_percentage.purid_saleid
                                where tax_percentage.pur_sale=:pur_sale and purchase_invoice_line.entry_date>=:min_date and purchase_invoice_line.entry_date<=:max_date ";
                                        $stmt = $db->prepare($sql);
                                        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                        $stmt->execute(array(":min_date" => $date1, ':max_date' => $date2, ':pur_sale' => $pur_sale));
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $field = $row['tot_tax'];
                                        return $field;
                                    } catch (PDOException $e) {
                                        echo $e->getMessage();
                                    }
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

                                                <td> S/N </td>
                                                <td> Purchase or Sale </td>
                                                <td> Percentage </td><td> Purchaseid Sale id </td>
                                                <?php if (isset($_SESSION['shall_delete'])) { ?> <td>Delete</td><td>Update</td><?php } ?></tr></thead>

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
                                                    <?php echo $this->_e($row['percentage']) . '%'; ?>
                                                </td>
                                                <td>
                                                    <?php echo $this->_e($row['purid_saleid']); ?>
                                                </td>

                                                <?php if (isset($_SESSION['shall_delete'])) { ?>   <td>
                                                        <a href="#" class="tax_percentage_delete_link" style="color: #000080;" data-id_delete="tax_percentage_id"  data-table="
                                                           <?php echo $row['tax_percentage_id']; ?>">Delete</a>
                                                    </td>
                                                    <td>
                                                        <a href="#" class="tax_percentage_update_link" style="color: #000080;" value="
                                                           <?php echo $row['tax_percentage_id']; ?>">Update</a>
                                                    </td><?php } ?></tr>
                                            <?php
                                            $pages += 1;
                                        }
                                        ?></table>
                                        <?php
                                    }

//chosen individual field
                                    function get_chosen_tax_percentage_pur_sale($id) {

                                        $db = new dbconnection();
                                        $sql = "select   tax_percentage.pur_sale from tax_percentage where tax_percentage_id=:tax_percentage_id ";
                                        $stmt = $db->openConnection()->prepare($sql);
                                        $stmt->bindValue(':tax_percentage_id', $id);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $field = $row['pur_sale'];
                                        echo $field;
                                    }

                                    function get_chosen_tax_percentage_percentage($id) {

                                        $db = new dbconnection();
                                        $sql = "select   tax_percentage.percentage from tax_percentage where tax_percentage_id=:tax_percentage_id ";
                                        $stmt = $db->openConnection()->prepare($sql);
                                        $stmt->bindValue(':tax_percentage_id', $id);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $field = $row['percentage'];
                                        echo $field;
                                    }

                                    function get_chosen_tax_percentage_purid_saleid($id) {

                                        $db = new dbconnection();
                                        $sql = "select   tax_percentage.purid_saleid from tax_percentage where tax_percentage_id=:tax_percentage_id ";
                                        $stmt = $db->openConnection()->prepare($sql);
                                        $stmt->bindValue(':tax_percentage_id', $id);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $field = $row['purid_saleid'];
                                        echo $field;
                                    }

                                    function All_tax_percentage() {
                                        $c = 0;
                                        $database = new dbconnection();
                                        $db = $database->openConnection();
                                        $sql = "select  tax_percentage_id   from tax_percentage";
                                        foreach ($db->query($sql) as $row) {
                                            $c += 1;
                                        }
                                        return $c;
                                    }

                                    function get_first_tax_percentage() {
                                        $con = new dbconnection();
                                        $sql = "select tax_percentage.tax_percentage_id from tax_percentage
                    order by tax_percentage.tax_percentage_id asc
                    limit 1";
                                        $stmt = $con->openconnection()->prepare($sql);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $first_rec = $row['tax_percentage_id'];
                                        return $first_rec;
                                    }

                                    function get_last_tax_percentage() {
                                        $con = new dbconnection();
                                        $sql = "select tax_percentage.tax_percentage_id from tax_percentage
                    order by tax_percentage.tax_percentage_id desc
                    limit 1";
                                        $stmt = $con->openconnection()->prepare($sql);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $first_rec = $row['tax_percentage_id'];
                                        return $first_rec;
                                    }

                                    function get_vatid_in_combo() {
                                        require_once('../web_db/connection.php');
                                        $database = new dbconnection();
                                        $db = $database->openConnection();
                                        $sql = "select vatid.vatid_id,   vatid.name from vatid";
                                        ?>
                                    <select class="textbox cbo_vatid"><option></option>
                                        <?php
                                        foreach ($db->query($sql) as $row) {
                                            echo "<option value=" . $row['vatid_id'] . ">" . $row['name'] . " </option>";
                                        }
                                        ?>
                                    </select>
                                    <?php
                                }

                                function get_account_order_by_account($account) {
                                    $con = new dbconnection();
                                    $sql = "  select account.account_id,main_contra_account.acc_order from main_contra_account "
                                            . "join account on main_contra_account.self_acc=account.account_id"
                                            . " where account.account_id=:acc  ";
                                    $stmt = $con->openconnection()->prepare($sql);
                                    $stmt->execute(array(":acc" => $account));
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $first_rec = $row['acc_order'];
                                    return $first_rec;
                                }

                                function get_acc_class_byaccount($account) {
                                    $con = new dbconnection();
                                    $sql = "  select account_class.account_class_id from account_class where account_class.name=:acc  ";
                                    $stmt = $con->openconnection()->prepare($sql);
                                    $stmt->execute(array(":acc" => $account));
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $first_rec = $row['account_class_id'];
                                    return $first_rec;
                                }

                                function get_income_stmt_net() {
                                    $sum_income = $this->get_account_balance('Sales revenue', $this->get_this_year_start_date(), $this->get_this_year_end_date());
                                    $tot_cogs = $this->get_account_balance('Cost Of Good Sold', $this->get_this_year_start_date(), $this->get_this_year_end_date());
                                    $sum_research_dev = $this->get_account_balance('Research and Development', $this->get_this_year_start_date(), $this->get_this_year_end_date());
                                    $gen_expenses = $this->get_account_balance('General & administrative', $this->get_this_year_start_date(), $this->get_this_year_end_date());
                                    $gen_other_expense = $this->get_account_balance('Other expense', $this->get_this_year_start_date(), $this->get_this_year_end_date());
                                    $sum_sales_marketing = $this->get_account_balance('Sales And Marketing', $this->get_this_year_start_date(), $this->get_this_year_end_date());
                                    $other_income = $this->get_account_balance('Other income', $this->get_this_year_start_date(), $this->get_this_year_end_date());

                                    $gross_profit = $sum_income + $other_income - $tot_cogs;
                                    $tot_op_expense = $sum_research_dev + $gen_expenses + $gen_other_expense + $sum_sales_marketing; // these are the operating exp.

                                    $sum_income_ope = $gross_profit - $tot_op_expense;
                                    $sum_interest_income = $this->get_account_balance('Interest Income', $this->get_this_year_start_date(), $this->get_this_year_end_date()); //This is calculated by accountant manually
                                    $sum_income_tax = $this->get_account_balance('Income Tax', $this->get_this_year_start_date(), $this->get_this_year_end_date()); //This is what calculated by accountant manually

                                    return $sum_income_ope + $sum_interest_income - $sum_income_tax;
                                }

                                function get_sign($account, $db_drdt) {
                                    require_once 'fin_books_sum_views.php';
                                    $sign = new fin_books_sum_views();
                                    $db = new dbconnection();
                                    $sql = $sign->get_account_sign();
                                    $stmt = $db->openConnection()->prepare($sql);
                                    $stmt->execute(array(":acc" => $account));
                                    $res = $stmt->fetch(PDO::FETCH_ASSOC);
                                    return ($res['name'] == 'Sales revenue' && $db_drdt == 'Credit') ? -1 : 1;
                                }

                                function list_tax_type($min) {
                                    $database = new dbconnection();
                                    $db = $database->openConnection();
                                    $sql = "select * from tax_type";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute(array(":min" => $min));
                                    ?>
                                    <table class="dataList_table">
                                        <thead><tr>

                                                <td> Type </td>
                                                <td> Name </td><td> percentage </td>
                                                <td>Delete</td><td>Update</td></tr></thead>

                                        <?php
                                        $pages = 1;
                                        while ($row = $stmt->fetch()) {
                                            ?><tr> 

                                                <td>
                                                    <?php echo $row['tax_type_id']; ?>
                                                </td>
                                                <td class="name_id_cols tax_type " title="tax_type" >
                                                    <?php echo $this->_e($row['name']); ?>
                                                </td>
                                                <td>
                                                    <?php echo $this->_e($row['percentage']); ?>
                                                </td>


                                                <td>
                                                    <a href="#" class="tax_type_delete_link" style="color: #000080;" data-id_delete="tax_type_id"  data-table="
                                                       <?php echo $row['tax_type_id']; ?>">Delete</a>
                                                </td>
                                                <td>
                                                    <a href="#" class="tax_type_update_link" style="color: #000080;" value="
                                                       <?php echo $row['tax_type_id']; ?>">Update</a>
                                                </td></tr>
                                            <?php
                                            $pages += 1;
                                        }
                                        ?></table>
                                        <?php
                                    }

                                    function get_chosen_tax_type_name($id) {

                                        $db = new dbconnection();
                                        $sql = "select   tax_type.name from tax_type where tax_type_id=:tax_type_id ";
                                        $stmt = $db->openConnection()->prepare($sql);
                                        $stmt->bindValue(':tax_type_id', $id);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $field = $row['name'];
                                        echo $field;
                                    }

                                    function get_chosen_tax_type_percentage($id) {

                                        $db = new dbconnection();
                                        $sql = "select   tax_type.percentage from tax_type where tax_type_id=:tax_type_id ";
                                        $stmt = $db->openConnection()->prepare($sql);
                                        $stmt->bindValue(':tax_type_id', $id);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $field = $row['percentage'];
                                        echo $field;
                                    }

                                    function All_tax_type() {
                                        $c = 0;
                                        $database = new dbconnection();
                                        $db = $database->openConnection();
                                        $sql = "select  tax_type_id   from tax_type";
                                        foreach ($db->query($sql) as $row) {
                                            $c += 1;
                                        }
                                        return $c;
                                    }

                                    function get_first_tax_type() {
                                        $con = new dbconnection();
                                        $sql = "select tax_type.tax_type_id from tax_type
                    order by tax_type.tax_type_id asc
                    limit 1";
                                        $stmt = $con->openconnection()->prepare($sql);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $first_rec = $row['tax_type_id'];
                                        return $first_rec;
                                    }

                                    function get_last_tax_type() {
                                        $con = new dbconnection();
                                        $sql = "select tax_type.tax_type_id from tax_type
                    order by tax_type.tax_type_id desc
                    limit 1";
                                        $stmt = $con->openconnection()->prepare($sql);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $first_rec = $row['tax_type_id'];
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

                                function get_tax_percentage_by_id($id) {

                                    $con = new dbconnection();
                                    $sql = "select tax_type.percentage from tax_type where tax_type_id=:id";
                                    $stmt = $con->openconnection()->prepare($sql);
                                    $stmt->execute(array(":id" => $id));
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $tax = $row['percentage'];
                                    return $tax;
                                }

                                function list_reconcilition($min) {
                                    $database = new dbconnection();
                                    $db = $database->openConnection();
                                    $sql = "select * from reconcilition";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute(array(":min" => $min));
                                    ?>
                                    <table class="dataList_table">
                                        <thead><tr>

                                                <td> reconcilition </td>
                                                <td> Entry Date </td><td> User </td><td> Transaction </td><td> Amount due </td><td> Amount done </td><td> Remaining amount </td><td> comments </td>
                                                <td>Delete</td><td>Update</td></tr></thead>

                                        <?php
                                        $pages = 1;
                                        while ($row = $stmt->fetch()) {
                                            ?><tr> 

                                                <td>
                                                    <?php echo $row['reconcilition_id']; ?>
                                                </td>
                                                <td class="entry_date_id_cols reconcilition " title="reconcilition" >
                                                    <?php echo $this->_e($row['entry_date']); ?>
                                                </td>
                                                <td>
                                                    <?php echo $this->_e($row['User']); ?>
                                                </td>
                                                <td>
                                                    <?php echo $this->_e($row['transaction']); ?>
                                                </td>
                                                <td>
                                                    <?php echo $this->_e($row['amount_due']); ?>
                                                </td>
                                                <td>
                                                    <?php echo $this->_e($row['amount_done']); ?>
                                                </td>
                                                <td>
                                                    <?php echo $this->_e($row['remaining_amount']); ?>
                                                </td>
                                                <td>
                                                    <?php echo $this->_e($row['comments']); ?>
                                                </td>


                                                <td>
                                                    <a href="#" class="reconcilition_delete_link" style="color: #000080;" data-id_delete="reconcilition_id"  data-table="
                                                       <?php echo $row['reconcilition_id']; ?>">Delete</a>
                                                </td>
                                                <td>
                                                    <a href="#" class="reconcilition_update_link" style="color: #000080;" value="
                                                       <?php echo $row['reconcilition_id']; ?>">Update</a>
                                                </td></tr>
                                            <?php
                                            $pages += 1;
                                        }
                                        ?></table>
                                        <?php
                                    }

//chosen individual field
                                    function get_chosen_reconcilition_entry_date($id) {

                                        $db = new dbconnection();
                                        $sql = "select   reconcilition.entry_date from reconcilition where reconcilition_id=:reconcilition_id ";
                                        $stmt = $db->openConnection()->prepare($sql);
                                        $stmt->bindValue(':reconcilition_id', $id);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $field = $row['entry_date'];
                                        echo $field;
                                    }

                                    function get_chosen_reconcilition_User($id) {

                                        $db = new dbconnection();
                                        $sql = "select   reconcilition.User from reconcilition where reconcilition_id=:reconcilition_id ";
                                        $stmt = $db->openConnection()->prepare($sql);
                                        $stmt->bindValue(':reconcilition_id', $id);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $field = $row['User'];
                                        echo $field;
                                    }

                                    function get_chosen_reconcilition_transaction($id) {

                                        $db = new dbconnection();
                                        $sql = "select   reconcilition.transaction from reconcilition where reconcilition_id=:reconcilition_id ";
                                        $stmt = $db->openConnection()->prepare($sql);
                                        $stmt->bindValue(':reconcilition_id', $id);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $field = $row['transaction'];
                                        echo $field;
                                    }

                                    function get_chosen_reconcilition_amount_due($id) {

                                        $db = new dbconnection();
                                        $sql = "select   reconcilition.amount_due from reconcilition where reconcilition_id=:reconcilition_id ";
                                        $stmt = $db->openConnection()->prepare($sql);
                                        $stmt->bindValue(':reconcilition_id', $id);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $field = $row['amount_due'];
                                        echo $field;
                                    }

                                    function get_chosen_reconcilition_amount_done($id) {

                                        $db = new dbconnection();
                                        $sql = "select   reconcilition.amount_done from reconcilition where reconcilition_id=:reconcilition_id ";
                                        $stmt = $db->openConnection()->prepare($sql);
                                        $stmt->bindValue(':reconcilition_id', $id);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $field = $row['amount_done'];
                                        echo $field;
                                    }

                                    function get_chosen_reconcilition_remaining_amount($id) {

                                        $db = new dbconnection();
                                        $sql = "select   reconcilition.remaining_amount from reconcilition where reconcilition_id=:reconcilition_id ";
                                        $stmt = $db->openConnection()->prepare($sql);
                                        $stmt->bindValue(':reconcilition_id', $id);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $field = $row['remaining_amount'];
                                        echo $field;
                                    }

                                    function get_chosen_reconcilition_comments($id) {

                                        $db = new dbconnection();
                                        $sql = "select   reconcilition.comments from reconcilition where reconcilition_id=:reconcilition_id ";
                                        $stmt = $db->openConnection()->prepare($sql);
                                        $stmt->bindValue(':reconcilition_id', $id);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $field = $row['comments'];
                                        echo $field;
                                    }

                                    function All_reconcilition() {
                                        $c = 0;
                                        $database = new dbconnection();
                                        $db = $database->openConnection();
                                        $sql = "select  reconcilition_id   from reconcilition";
                                        foreach ($db->query($sql) as $row) {
                                            $c += 1;
                                        }
                                        return $c;
                                    }

                                    function get_first_reconcilition() {
                                        $con = new dbconnection();
                                        $sql = "select reconcilition.reconcilition_id from reconcilition
                    order by reconcilition.reconcilition_id asc
                    limit 1";
                                        $stmt = $con->openconnection()->prepare($sql);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $first_rec = $row['reconcilition_id'];
                                        return $first_rec;
                                    }

                                    function get_last_reconcilition() {
                                        $con = new dbconnection();
                                        $sql = "select reconcilition.reconcilition_id from reconcilition
                    order by reconcilition.reconcilition_id desc
                    limit 1";
                                        $stmt = $con->openconnection()->prepare($sql);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $first_rec = $row['reconcilition_id'];
                                        return $first_rec;
                                    }

                                    function get_transaction_in_combo() {
                                        require_once('../web_db/connection.php');
                                        $database = new dbconnection();
                                        $db = $database->openConnection();
                                        $sql = "select  journal_entry_line.journal_entry_line_id,  min(journal_entry_line.accountid) as accountid, min( journal_entry_line.dr_cr) as dr_cr,min(journal_entry_line.amount)as amount ,  min(journal_entry_line.memo) as memo, min( journal_entry_line.journal_entry_header) as journal_entry_header , min( journal_entry_line.entry_date) as entry_date,min(account.name) as accountid from journal_entry_line";
                                        ?>
                                    <select class="textbox cbo_transaction"><option></option>
                                        <?php
                                        foreach ($db->query($sql) as $row) {
                                            echo "<option value=" . $row['journal_entry_line_id'] . ">" . $row['accountid'] . " </option>";
                                        }
                                        ?>
                                    </select>
                                    <?php
                                }

                                function list_journal_entry_for_rec($first, $last, $status, $dc, $category) {//for reconciliation
                                    ?>
                                    <table class="dataList_table">
                                        <thead><tr>
                                                <td> <input type="checkbox"> </td>
                                                <td> Account </td>
                                                <td> Type </td>
                                                <td> Status   </td>

                                                <td> Memo </td>
                                                <td class="off"> Journal Entry Header </td>
                                                <td> entry_date </td>

                                            </tr>
                                        </thead>
                                        <?php
                                        try {
                                            $database = new dbconnection();
                                            $db = $database->openConnection();
                                            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                                            $sql2 = "select  journal_entry_line.journal_entry_line_id, min(journal_entry_line.category) as category, min(journal_entry_line.accountid) as accountid, min( journal_entry_line.dr_cr) as dr_cr,min(journal_entry_line.amount)as amount ,  min(journal_entry_line.memo) as memo, min( journal_entry_line.journal_entry_header) as journal_entry_header , min( journal_entry_line.entry_date) as entry_date,min(account.name) as account from journal_entry_line  "
                                                    . " join account on account.account_id=journal_entry_line.accountid "
                                                    . " where   journal_entry_line.status=:status "
                                                    . " and journal_entry_line.dr_cr=:dc and journal_entry_line.category=:category"
                                                    . "  "
                                                    . " group by journal_entry_line.journal_entry_line_id "
                                                    . " order by journal_entry_line.dr_cr desc"
                                                    . " limit " . $first . " , " . $last . " "
                                                    . "  ";
                                            $stmt = $db->prepare($sql2);
                                            $stmt->execute(array(":status" => $status, ":dc" => $dc, ":category" => $category));

                                            while ($row = $stmt->fetch()) {
                                                ?><tr> 
                                                    <td>
                                                        <input type="checkbox">
                                                    </td>
                                                    <td class="accountid_id_cols journal_entry_line " title="journal_entry_line" >
                                                        <?php echo $this->_e($row['accountid']); ?>
                                                    </td>
                                                    <td class="accountid_id_cols journal_entry_line " title="journal_entry_line" >
                                                        <?php echo $this->_e($row['category']); ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        echo number_format($row['amount']);
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $this->_e($row['memo']); ?>
                                                    </td>
                                                    <td class="off">
                                                        <?php echo $this->_e($row['journal_entry_header']); ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $this->_e($row['entry_date']); ?>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                            ?></table>
                                        <?php
                                    } catch (PDOException $e) {
                                        echo $e->getMessage();
                                    }
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

                                function paging($tot) {
                                    $start = 0;

                                    if (isset($_SESSION['page'])) {
                                        if ($_SESSION['page'] <= 0) {
                                            $start = 1;
                                        } else {
                                            $start = $_SESSION['page'];
                                        }
                                    }
                                    $last = (($start + 15) <= (ceil($tot / 15))) ? ($start + 15) : ceil($tot / 15);
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
                                                        <input type="submit" style="float:left;margin-left: 4px; " value="<?php echo $pages ?>"/>
                                                    </form>
                                                </td>
                                                <?php
                                            }
                                            ?>
                                            <td><form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                                    <input type="hidden" name="page_level" value="1" />
                                                    <input type="submit" style="float:right;" name="level" value=">>"/>
                                                </form></td>
                                        </tr>
                                    </table><?php
                                }

                                function startdate_enddate_layout() {
                                    echo '<div class="parts full_center_two_h heit_free margin_free no_paddin_shade_no_Border row_underline">
                <div class="parts ninenty_centered heit_free no_paddin_shade_no_Border ">
                    <table  class="full_center_two_h heit_free margin_free no_paddin_shade_no_Border">
                        <td><select class="cbo_rep_period_options">
                                <option>All</option>
                                <option>Today</option>
                                <option>This week</option>
                                <option>This week-to-date</option>
                                <option>This month</option>
                                <option>This month-to-date</option>
                                <option>This fiscal quarter</option>
                                <option>This fiscal quarter-to-date</option>
                                <option>This fiscal year</option>
                                <option>This fiscal year-to-Last Month</option>
                                <option>This fiscal year-to-date</option>
                                <option>Yesterday</option>
                                <option>Last week</option>
                                <option>Last week-to-date</option>
                                <option>Last month</option>
                                <option>Last month-to-date</option>
                                <option>Last fiscal quarter</option>
                                <option>Last fiscal quarter-to-date</option>
                                <option>Last Year</option>
                                <option>Last Year-to-date</option>
                            </select>
                        </td> <td>Start date</td><td><input type="text" id="date1" value="' . $this->get_this_year_start_date() . '" class="textbox dates"></td>
                        <td>End date</td><td><input  type="text" id="date2" value="' . $this->get_this_year_end_date() . '" class="textbox dates"></td>
                        <td>
                            <select>
                                <option>Sort by</option>
                                <option>Type</option>
                                <option>Date</option>
                                <option>Num</option>
                                <option>Name</option>
                                <option>Memo</option>
                                <option>Amount</option>
                            </select> </td><td></td>
                        <td><input type="button"  class="confirm_buttons btn_books_date" data-bind="blns" value="Search" style= "background-image: none;margin-top: 0px;"/> </td>
                    </table>
                </div>
            </div>';
                                }

                                function get_acc_classid_byname($class) {
                                    $con = new dbconnection();
                                    $sql = " select account_class_id from account_class   where account_class.name=:class";
                                    $stmt = $con->openconnection()->prepare($sql);
                                    $stmt->execute(array(":class" => $class));
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $first_rec = $row['account_class_id'];
                                    return $first_rec;
                                }

                                function get_acc_name_byid($class) {
                                    $con = new dbconnection();
                                    $sql = "  select account_class.name from account_class  where account_class.account_class_id=:class";
                                    $stmt = $con->openconnection()->prepare($sql);
                                    $stmt->execute(array(":class" => $class));
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $first_rec = $row['name'];
                                    return $first_rec;
                                }

                                function list_project_expectations($min) {
                                    $database = new dbconnection();
                                    $db = $database->openConnection();
                                    $sql = "select * from project_expectations";
                                    $stmt = $db->prepare($sql);
                                    $stmt->execute(array(":min" => $min));
                                    ?>
                                    <table class="dataList_table">
                                        <thead><tr>

                                                <td> project_expectations </td>
                                                <td> Name </td>
                                                <td>Delete</td><td>Update</td></tr></thead>

                                        <?php
                                        $pages = 1;
                                        while ($row = $stmt->fetch()) {
                                            ?><tr> 

                                                <td>
                                                    <?php echo $row['project_expectations_id']; ?>
                                                </td>
                                                <td class="name_id_cols project_expectations " title="project_expectations" >
                                                    <?php echo $this->_e($row['name']); ?>
                                                </td>


                                                <td>
                                                    <a href="#" class="project_expectations_delete_link" style="color: #000080;" data-id_delete="project_expectations_id"  data-table="
                                                       <?php echo $row['project_expectations_id']; ?>">Delete</a>
                                                </td>
                                                <td>
                                                    <a href="#" class="project_expectations_update_link" style="color: #000080;" value="
                                                       <?php echo $row['project_expectations_id']; ?>">Update</a>
                                                </td></tr>
                                            <?php
                                            $pages += 1;
                                        }
                                        ?></table>
                                        <?php
                                    }

//chosen individual field
                                    function get_chosen_project_expectations_name($id) {

                                        $db = new dbconnection();
                                        $sql = "select   project_expectations.name from project_expectations where project_expectations_id=:project_expectations_id ";
                                        $stmt = $db->openConnection()->prepare($sql);
                                        $stmt->bindValue(':project_expectations_id', $id);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $field = $row['name'];
                                        echo $field;
                                    }

                                    function All_project_expectations() {
                                        $c = 0;
                                        $database = new dbconnection();
                                        $db = $database->openConnection();
                                        $sql = "select  project_expectations_id   from project_expectations";
                                        foreach ($db->query($sql) as $row) {
                                            $c += 1;
                                        }
                                        return $c;
                                    }

                                    function get_first_project_expectations() {
                                        $con = new dbconnection();
                                        $sql = "select project_expectations.project_expectations_id from project_expectations
                    order by project_expectations.project_expectations_id asc
                    limit 1";
                                        $stmt = $con->openconnection()->prepare($sql);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $first_rec = $row['project_expectations_id'];
                                        return $first_rec;
                                    }

                                    function get_last_project_expectations() {
                                        $con = new dbconnection();
                                        $sql = "select project_expectations.project_expectations_id from project_expectations
                    order by project_expectations.project_expectations_id desc
                    limit 1";
                                        $stmt = $con->openconnection()->prepare($sql);
                                        $stmt->execute();
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $first_rec = $row['project_expectations_id'];
                                        return $first_rec;
                                    }

                                    function page_sub_menus($allowed) {
                                        if ($allowed = '') {
                                            ?>
                                        <div class="parts no_paddin_shade_no_Border">

                                        </div>
                                        <?php
                                    }
                                }

                                function get_project_idby_name($name) {
                                    $con = new dbconnection();
                                    $sql = " select project_expectations.project_expectations_id from project_expectations where project_expectations.name=:name";
                                    $stmt = $con->openconnection()->prepare($sql);
                                    $stmt->execute(array(":name" => $name));
                                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                    $first_rec = $row['project_expectations_id'];
                                    return $first_rec;
                                }

                                function _e($string) {
                                    echo htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
                                }

                            }
                            