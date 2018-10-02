<?php
    require_once '../web_db/connection.php';
    /*
     * This Class wil hold all necessary reports
     */

    /**
     * Description of Reports
     *
     * SANGWA E. 
     */
    class Reports {

        function rprt_general_report() {
            try {
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = " select sales_receit_header.sales_receit_header_id,p_budget_items.item_name as item,  sales_receit_header.sales_invoice,  sum(sales_receit_header.quantity) as quantity,  sales_receit_header.unit_cost,  sum(sales_receit_header.amount) as amount,  sales_receit_header.entry_date,  sales_receit_header.User,  sales_receit_header.client,  sales_receit_header.budget_prep,  sales_receit_header.account, 
                            user.Firstname,user.Lastname,
                            party.name as client,
                            p_type_project.name as budget_line,
                            p_budget_prep.name as project, p_budget_prep.budget_type as bdgt_type,
                            p_activity.name as activity,p_activity.amount as budget_amount,
                            purchase_invoice_line.purchase_invoice_line_id as p_invoice,  purchase_invoice_line.entry_date,  purchase_invoice_line.User,  sum(purchase_invoice_line.quantity) as p_qty,  purchase_invoice_line.unit_cost as p_uc,  sum(purchase_invoice_line.amount) as p_amount,  purchase_invoice_line.purchase_order,   purchase_invoice_line.acc_debit,  purchase_invoice_line.supplier
                        from sales_receit_header
                        join sales_invoice_line on sales_receit_header.sales_invoice=sales_invoice_line.sales_invoice_line_id
                        join sales_order_line on sales_invoice_line.sales_order= sales_order_line.sales_order_line_id
                        join sales_quote_line on sales_order_line.quotationid=sales_quote_line.sales_quote_line_id 
                        join p_budget_items on sales_quote_line.item=p_budget_items.p_budget_items_id  
                        join p_activity on sales_invoice_line.budget_prep_id=p_activity.p_activity_id
                        join p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id
                        join p_type_project on p_budget_prep.project_type=p_type_project.p_type_project_id
                        join user on user.StaffID=sales_receit_header.User
                        join party on party.party_id=sales_receit_header.client 

                        join purchase_invoice_line on p_activity.p_activity_id=purchase_invoice_line.activity
                        join purchase_receit_line on purchase_invoice_line.purchase_invoice_line_id=purchase_receit_line.purchase_invoice
                        where party.party_type='customer'
                        group by p_type_project.p_type_project_id, p_activity.p_activity_id";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                ?>
                <table class="dataList_table">
                    <thead><tr>  <td colspan="3">BUDGETING</td>  <td colspan="2">EXPENSES</td><td>REVENUES</td><td colspan="4">NET</td></tr></thead>
                </thead>
                <thead>
                    <tr class="hilit_row" style="background-color: #22306b">
                        <td class="off"> S/N </td>
                        <td>Budget Line</td>
                        <td>Rev. Exp</td>
                        <td> Project </td>
                        <td> Activity </td>
                        <td> Amount </td>
                        <!--PURCHASE-->
                        <td class="off"> quantity </td>
                        <td class="off"> unit cost </td>
                        <td> amount </td>
                        <td class="off"> Invoice </td>
                        <!--sales-->

                        <td class="off"> quantity </td>
                        <td class="off"> unit cost </td>
                        <td> amount </td>
                        <td> entry date </td>

                        <!--NET-->
                        <td class="off"> User </td>
                        <td class="off"> client </td>

                        <td> Current Net </td>
                        <td>%</td>
                    </tr></thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    $prep = $row['budget_amount'];
                    $current_exp = $row['p_amount']; //current expenses
                    $percntg = ($current_exp / $prep) * 100;
                    ?> <tr class="clickable_row">
                        <td class="off">        <?php echo $row['sales_receit_header_id']; ?> </td>
                        <td>        <?php echo $row['budget_line']; ?> </td>
                        <td>        <?php echo $row['bdgt_type']; ?> </td>
                        <td>        <?php echo $row['project']; ?> </td>
                        <td>        <?php echo $row['activity']; ?> </td>
                        <td>        <?php echo number_format($row['budget_amount']); ?> </td>
                        <td class="p_color off">        <?php echo $row['p_qty']; ?> </td>
                        <td class="p_color off">        <?php echo $row['p_uc']; ?> </td>
                        <td class="p_color">        <?php echo number_format($row['p_amount']); ?> </td>
                        <td class="p_color off">        <?php echo $row['p_invoice']; ?> </td>
                        <td class="off">        <?php echo number_format($row['quantity']); ?> </td>
                        <td class="off">        <?php echo number_format($row['unit_cost']); ?> </td>
                        <td>        <?php echo number_format($row['amount']); ?> </td>
                        <td>        <?php echo $row['entry_date']; ?> </td>
                        <td class="off">        <?php
                            echo $row['Firstname'] . ' ';
                            echo $row['Lastname'];
                            ?> </td>
                        <td class="off">        <?php echo $row['client']; ?> </td>
                        </td>
                        <td class="net_color">        <?php echo $row['amount'] - $row['p_amount']; ?> </td>
                        <td><?php echo $percntg; ?></td>
                        <?php if (isset($_SESSION['shall_delete'])) { ?>
                            <td class="delete_cols">
                                <a href="#" class="account_delete_link" style="color: #000080;" data-id_delete="account_id"  data-table="
                                   <?php echo $row['account_id']; ?>">Delete</a>
                            </td>
                            <td class="update_cols">
                                <a href="#" class="account_update_link" style="color: #000080;" value="
                                   <?php echo $row['account_id']; ?>">Update</a>
                            </td>
                        <?php } ?></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
                <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function sales_receit() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select sales_receit_header.sales_receit_header_id,  sales_receit_header.sales_invoice,  sales_receit_header.quantity,  sales_receit_header.unit_cost,  sales_receit_header.amount,  sales_receit_header.entry_date,  sales_receit_header.User,  sales_receit_header.client,  sales_receit_header.sale_order,  sales_receit_header.budget_prep,  sales_receit_header.account
                        from sales_receit_header
                        join client on sales_receit_header.client = client.client_id 
                        join account on sales_receit_header.account = account.account_id 
                       []";
            ?>
            <table class="new_data_table">
                <thead>
                    <tr>
                        <td> S/N </td>
                        <td> sales invoice </td>
                        <td> quantity </td>
                        <td> unit_cost </td>
                        <td> amount </td>
                        <td> entry date </td>
                        <td> User </td>
                        <td> client </td>
                        <td> sale_order </td>
                        <td> budget prep </td>
                        <td> account </td>
                    </tr></thead>

                <?php foreach ($db->query($sql) as $row) { ?><tr> 
                        <td>        <?php echo $row['sales_receit_header_id']; ?> </td>
                        <td>        <?php echo $row['sales_invoice']; ?> </td>
                        <td>        <?php echo $row['quantity']; ?> </td>
                        <td>        <?php echo $row['unit_cost']; ?> </td>
                        <td>        <?php echo $row['amount']; ?> </td>
                        <td>        <?php echo $row['entry_date']; ?> </td>
                        <td>        <?php echo $row['User']; ?> </td>
                        <td>        <?php echo $row['client']; ?> </td>
                        <td>        <?php echo $row['sale_order']; ?> </td>
                        <td>        <?php echo $row['budget_prep']; ?> </td>
                        <td>        <?php echo $row['account']; ?> </td>

                    </tr>
                <?php } ?></table>
            <?php
        }

        function pinvoice() {
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select purchase_invoice_line.purchase_invoice_line_id,  purchase_invoice_line.entry_date,  purchase_invoice_line.User,  purchase_invoice_line.quantity,  purchase_invoice_line.unit_cost,  purchase_invoice_line.amount,  purchase_invoice_line.purchase_order,  purchase_invoice_line.activity,  purchase_invoice_line.account,  purchase_invoice_line.supplier,  purchase_invoice_line.pay_method
 from purchase_invoice_line

[]";
            ?>
            <table class="new_data_table">
                <thead>
                    <tr>
                        <td> SN </td>
                        <td> entry_date </td>
                        <td> User </td>
                        <td> quantity </td>
                        <td> unit_cost </td>
                        <td> amount </td>
                        <td> purchase order </td>
                        <td> activity </td>
                        <td> account </td>
                        <td> supplier </td>
                        <td> pay method </td>
                    </tr>
                </thead>

                <?php foreach ($db->query($sql) as $row) { ?><tr> 
                        <td>        <?php echo $row['purchase_invoice_line_id']; ?> </td>
                        <td>        <?php echo $row['entry_date']; ?> </td>
                        <td>        <?php echo $row['User']; ?> </td>
                        <td>        <?php echo $row['quantity']; ?> </td>
                        <td>        <?php echo $row['unit_cost']; ?> </td>
                        <td>        <?php echo $row['amount']; ?> </td>
                        <td>        <?php echo $row['purchase_order']; ?> </td>
                        <td>        <?php echo $row['activity']; ?> </td>
                        <td>        <?php echo $row['account']; ?> </td>
                        <td>        <?php echo $row['supplier']; ?> </td>
                        <td>        <?php echo $row['pay_method']; ?> </td>

                    </tr>
                <?php } ?></table>
            <?php
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

        function search_item_main_stock($item_name) {
            ?><div class="parts full_center_two_h heit_free no_shade_noBorder">
                Items from Main stock
            </div><?php
            try {
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = "select main_stock.main_stock_id,p_budget_items.item_name, p_budget_items.p_budget_items_id,  p_budget_items.description,  p_budget_items.created_by,  p_budget_items.entry_date,  p_budget_items.chart_account from main_stock
                        join p_budget_items on p_budget_items.p_budget_items_id=main_stock.item
                        where p_budget_items.item_name=:item 
                         ";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":item" => $item_name));
                ?>
                <table class="dataList_table">
                    <thead><tr>
                            <td> S/N </td>
                            <td> Item name </td>
                            <td> Description </td>
                            <td> Entry Date </td>
                            <?php if (isset($_SESSION['shall_delete'])) { ?> <td>Delete</td>
                                <td>Update</td><?php } ?>
                        </tr></thead>
                    <?php
                    $pages = 1;
                    while ($row = $stmt->fetch()) {
                        ?><tr> 
                            <td>
                                <?php echo $row['main_stock_id']; ?>
                            </td>
                            <td class="code_id_cols measurement " title="measurement" >
                                <?php echo $this->_e($row['item_name']); ?>
                            </td>
                            <td class="code_id_cols measurement " title="measurement" >
                                <?php echo $this->_e($row['entry_date']); ?>
                            </td>
                            <td>
                                <?php echo $this->_e($row['description']); ?>
                            </td><?php if (isset($_SESSION['shall_delete'])) { ?>
                                <td>
                                    <a href="#" class="measurement_delete_link" style="color: #000080;" data-id_delete="measurement_id"  data-table="
                                       <?php echo $row['created_by']; ?>">Delete</a>
                                </td>
                                <td>
                                    <a href="#" class="measurement_update_link" style="color: #000080;" value="
                                       <?php echo $row['measurement_id']; ?>">Update</a>
                                </td><?php } ?></tr>
                        <?php
                        $pages += 1;
                    }
                    ?></table>
                    <?php
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
            }

            function list_budget_types_by_dates($min, $max) {
                ?><div class="parts full_center_two_h heit_free no_paddin_shade_no_Border xx_titles blue_text">Budget Line prepared on <?php echo $min . ' -  ' . $max ?></div><?php
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "    select p_type_project.p_type_project_id,    p_type_project.name  from p_type_project "
                        . "  ";
                $stmt = $db->prepare($sql);
                $stmt->execute();
                ?>
            <script>
                $('.row_font_h1').hover(function () {
                    $('.no_padding_print_btn').css('opacity', '1');
                });
                $('.row_font_h1').mouseleave(function () {
                    $('.no_padding_print_btn').css('opacity', '0');
                });
            </script>
            <table class="white_report_table margin_free ">
                <thead><tr>
                        <td> Budget Line </td>                       
                    </tr>
                </thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr  class="row_font_h1"   data-bind="p_budget_prep"> 
                        <td class="project_type_id_cols p_budget_prep " title="p_budget_prep" >
                            <div class="parts no_paddin_shade_no_Border type_lbl"> <?php echo $this->_e('BUDGET LINE: ' . $row['name']); ?></div> 
                            <div class="parts no_paddin_shade_no_Border">
                                <table style="margin:0px;" class="margin_free sub_datatable_leftoff">
                                    <td class="no_paddin_shade_no_Border no_padding_print_btn">
                                        <form action="../web_exports/excel_export.php" method="post">
                                            <input type="hidden" name="account" value="a"/>
                                            <input type="hidden" name="account" value="a"/>
                                            <input type="submit" name="export" class="btn_export btn_export_excel margin_free" value="Export"/>
                                        </form>
                                    </td>
                                    <td class="no_paddin_shade_no_Border no_padding_print_btn">
                                        <form action="../print_more_reports/print_budget_line_bydates.php" target="blank" method="post">
                                            <input type="hidden" name="account" value="<?php echo $min; ?>"/>
                                            <input type="hidden" name="account" value="<?php echo $max; ?>"/>
                                            <input type="hidden" name="type" value="<?php echo $row['p_type_project_id']; ?>"/>
                                            <input type="hidden" name="proj_name" value="<?php echo $row['name']; ?>"/>
                                            <input type="submit" name="export" class="btn_export btn_export_pdf margin_free" value="Export"/>
                                        </form>
                                    </td>
                                </table>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <?php $this->list_p_budget_prep_by_type_project($min, $max, $row['p_type_project_id']) ?>
                        </td> 
                    </tr>

                    <tr class="leave_bottom_space">
                        <td></td>
                    </tr>

                    <?php
                    $pages += 1;
                }
                ?></table><?php
        }

        function list_p_budget_prep_by_type_project($min, $max, $type_project) {
            ?><div class="parts full_center_two_h heit_free no_paddin_shade_no_Border xx_titles blue_text off">Budget Line prepared on <?php echo $min . ' -  ' . $max ?></div><?php
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "    select   p_budget_prep.p_budget_prep_id,max(  p_budget_prep.project_type), max( p_budget_prep.user),max(  p_budget_prep.entry_date), max( p_budget_prep.budget_type),max(  p_budget_prep.activity_desc),sum( p_activity.amount),  max( p_budget_prep.name) as name, max(user.Firstname),max( user.Lastname),max( p_type_project.name) as type
                    from p_budget_prep
                    join user on p_budget_prep.user = user.StaffID 
                    join p_type_project on p_budget_prep.project_type=p_type_project.p_type_project_id
                    join p_activity on p_activity.project=p_budget_prep.p_budget_prep_id"
                        . " where  p_type_project.p_type_project_id=:project"
                        . " group by p_budget_prep.p_budget_prep_id ";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":project" => $type_project));
                ?>
            <table class="white_report_table sub_datatable_leftoff margin_free top_off_xx">
                <thead><tr class="off">
                        <td> Project </td>      
                        <td> Entry Date </td>
                    </tr>
                </thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr class="row_font_h2"    data-bind="p_budget_prep"  > 
                        <td class="project_type_id_cols p_budget_prep " title="p_budget_prep" >
                            <?php echo $this->_e('PROJECT: ' . $row['name']); ?>
                        </td>
                        <td class="project_type_id_cols p_budget_prep ">Revenues</td>
                        <td class="project_type_id_cols p_budget_prep ">Expenses</td>

                    </tr>
                    <tr>
                        <td> 
                            <?php $this->list_p_activity_by_project($min, $max, $row['p_budget_prep_id']); ?>
                        </td> 
                        <td> 
                            <?php $this->list_p_activity_revenues_by_project($min, $max, $row['p_budget_prep_id']) ?>
                        </td>
                        <td><?php $this->list_p_activity_expenses_by_project($min, $max, $row['p_budget_prep_id']) ?></td>
                    </tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
            <table class="margin_free off">
                <td>
                    <form action="../web_exports/excel_export.php" method="post">
                        <input type="hidden" name="account" value="a"/>
                        <input type="hidden" name="account" value="a"/>
                        <input type="submit" name="export" class="btn_export btn_export_excel" value="Export"/>
                    </form>
                </td>
                <td>
                    <form action="../print_more_reports/print_budget_line_bydates.php" target="blank" method="post">
                        <input type="hidden" name="account" value="<?php echo $min; ?>"/>
                        <input type="hidden" name="account" value="<?php echo $max; ?>"/>
                        <input type="submit" name="export" class="btn_export btn_export_pdf" value="Export"/>
                    </form>
                </td>
            </table>
            <?php
//            $this->paging($this->All_p_budget_prep());
        }

        function list_p_activity_by_project($min, $max, $project) {
            ?><div class="parts full_center_two_h heit_free no_paddin_shade_no_Border xx_titles blue_text off">Budget Line prepared on <?php echo $min . ' -  ' . $max ?></div><?php
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "    select p_type_project.name as type, p_budget_prep.p_budget_prep_id, p_activity.name,  p_activity.amount ,p_budget_prep.name as proj_name
                from p_activity
                        join p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id 
                        join p_type_project on p_budget_prep.project_type=p_type_project.p_type_project_id"
                    . " where  p_activity.project=:project and  p_budget_prep.entry_date>=:min and p_budget_prep.entry_date<=:max"
                    . " ";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $_SESSION['min_date'], ":max" => $_SESSION['max_date'], ":project" => $project));
            ?>
            <table class="white_report_table sub_datatable_leftoff margin_free fixed_wid_2x">
                <thead><tr class="off">
                        <td> Project </td>      
                        <td> Amount </td>      
                        <td> Entry Date </td>

                    </tr>
                </thead>
                <?php
                $pages = 1;
                $proj = '';
                $type = '';
                while ($row = $stmt->fetch()) {
                    $proj = $row['proj_name'];
                    $type = $row['type'];
                    ?><tr   data-bind="p_budget_prep"  > 

                        <td class="project_type_id_cols p_budget_prep " title="p_budget_prep" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>
                        <td class="project_type_id_cols p_budget_prep sec_td" title="p_budget_prep" >
                            <?php echo $this->_e(number_format($row['amount'])); ?>
                        </td>

                    </tr>
                    <?php
                    $pages += 1;
                }
                ?><tr class="row_bold">
                    <td>Total</td><td class="sec_td"><?php echo number_format($this->get_total_activities_by_project($project)); ?></td>
                </tr></table>
            <table style="" class="margin_free sub_datatable_leftoff">
                <td class="no_paddin_shade_no_Border smaller_print_btn">
                    <form action="../web_exports/excel_export.php" method="post">
                        <input type="hidden" name="account" value="a"/>
                        <input type="hidden" name="account" value="a"/>
                        <input type="submit" name="export" class="btn_export btn_export_excel margin_free" value="Export"/>
                    </form>
                </td>
                <td class="no_paddin_shade_no_Border smaller_print_btn">
                    <form action="../print_more_reports/print_activities_by_proj_dates.php" target="blank" method="post">
                        <input type="hidden" name="account" value="<?php echo $min; ?>"/>
                        <input type="hidden" name="account" value="<?php echo $max; ?>"/>
                        <input type="hidden" name="project" value="<?php echo $project; ?>"/>
                        <input type="hidden" name="project_name" value="<?php echo $proj; ?>"/>
                        <input type="hidden" name="type" value="<?php echo $type; ?>"/>
                        <input type="submit" name="export" class="btn_export btn_export_pdf margin_free" value="Export"/>
                    </form>
                </td>
            </table>
            <?php
//            $this->paging($this->All_p_budget_prep());
        }

        function list_p_activity_revenues_by_project($min, $max, $project) {
            ?><div class="parts full_center_two_h heit_free no_paddin_shade_no_Border xx_titles blue_text off">Budget Line prepared on <?php echo $min . ' -  ' . $max ?></div><?php
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "    select p_activity.name,  p_activity.amount ,p_budget_prep.name as proj_name
                        from p_activity
                        join p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id 
                         oin project_expectations on project_expectations.project_expectations_id=p_activity.budget_type"
                    . " where  p_activity.project=:project and  p_budget_prep.entry_date>=:min and p_budget_prep.entry_date<=:max "
                    . " and project_expectations.name='revenue'"
                    . " ";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $_SESSION['min_date'], ":max" => $_SESSION['max_date'], ":project" => $project));
            ?>
            <table class="white_report_table sub_datatable_leftoff margin_free fixed_wid_2x">
                <thead><tr class="off">
                        <td colspan="2"> Revenues </td>      


                    </tr>
                </thead>
                <?php
                $pages = 1;
                $proj = '';
                while ($row = $stmt->fetch()) {
                    $proj = $row['proj_name'];
                    ?><tr     data-bind="p_budget_prep"  > 
                        <td class="project_type_id_cols p_budget_prep " title="p_budget_prep" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>
                        <td class="project_type_id_cols p_budget_prep sec_td" title="p_budget_prep" >
                            <?php echo $this->_e(number_format($row['amount'])); ?>
                        </td>


                    </tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
            <table style="" class="margin_free sub_datatable_leftoff">
                <td class="no_paddin_shade_no_Border smaller_print_btn">
                    <form action="../web_exports/excel_export.php" method="post">
                        <input type="hidden" name="account" value="a"/>
                        <input type="hidden" name="account" value="a"/>
                        <input type="submit" name="export" class="btn_export btn_export_excel margin_free" value="Export"/>
                    </form>
                </td>
                <td class="no_paddin_shade_no_Border smaller_print_btn">
                    <form action="../print_more_reports/print_activities_by_proj_dates.php" target="blank" method="post">
                        <input type="hidden" name="account" value="<?php echo $min; ?>"/>
                        <input type="hidden" name="account" value="<?php echo $max; ?>"/>
                        <input type="hidden" name="project" value="<?php echo $project; ?>"/>
                        <input type="hidden" name="project_name" value="<?php echo $proj; ?>"/>
                        <input type="submit" name="export" class="btn_export btn_export_pdf margin_free" value="Export"/>
                    </form>
                </td>
            </table>
            <?php
//            $this->paging($this->All_p_budget_prep());
        }

        function list_p_activity_expenses_by_project($min, $max, $project) {
            ?><div class="parts full_center_two_h heit_free no_paddin_shade_no_Border xx_titles blue_text off">Budget Line prepared on <?php echo $min . ' -  ' . $max ?></div><?php
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "    select p_activity.name,  p_activity.amount ,p_budget_prep.name as proj_name
                from p_activity
                        join p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id 
                         join project_expectations on project_expectations.project_expectations_id=p_activity.budget_type "
                    . " where  p_activity.project=:project and  p_budget_prep.entry_date>=:min and p_budget_prep.entry_date<=:max "
                    . " and project_expectations.name='expense'"
                    . " ";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $_SESSION['min_date'], ":max" => $_SESSION['max_date'], ":project" => $project));
            ?>
            <table class="white_report_table sub_datatable_leftoff margin_free fixed_wid_2x">
                <thead><tr class="off">
                        <td colspan="2"> Revenues </td>      
                    </tr>
                </thead>
                <?php
                $pages = 1;
                $proj = '';
                while ($row = $stmt->fetch()) {
                    $proj = $row['proj_name'];
                    ?><tr     data-bind="p_budget_prep"  > 
                        <td class="project_type_id_cols p_budget_prep " title="p_budget_prep" >
                            <?php echo $this->_e($row['name']); ?>
                        </td>
                        <td class="project_type_id_cols p_budget_prep sec_td" title="p_budget_prep" >
                            <?php echo $this->_e(number_format($row['amount'])); ?>
                        </td>

                    </tr>
                    <?php
                    $pages += 1;
                }
                ?></table>
            <table style="" class="margin_free sub_datatable_leftoff">
                <td class="no_paddin_shade_no_Border smaller_print_btn">
                    <form action="../web_exports/excel_export.php" method="post">
                        <input type="hidden" name="account" value="a"/>
                        <input type="hidden" name="account" value="a"/>
                        <input type="submit" name="export" class="btn_export btn_export_excel margin_free" value="Export"/>
                    </form>
                </td>
                <td class="no_paddin_shade_no_Border smaller_print_btn">
                    <form action="../print_more_reports/print_activities_by_proj_dates.php" target="blank" method="post">
                        <input type="hidden" name="account" value="<?php echo $min; ?>"/>
                        <input type="hidden" name="account" value="<?php echo $max; ?>"/>
                        <input type="hidden" name="project" value="<?php echo $project; ?>"/>
                        <input type="hidden" name="project_name" value="<?php echo $proj; ?>"/>
                        <input type="submit" name="export" class="btn_export btn_export_pdf margin_free" value="Export"/>
                    </form>
                </td>
            </table>
            <?php
//            $this->paging($this->All_p_budget_prep());
        }

        function list_journal_entry_line_by_2_dates($inc_exp, $min, $max) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select distinct journal_entry_line.journal_entry_line_id,  journal_entry_line.accountid,  journal_entry_line.dr_cr,  journal_entry_line.amount,  journal_entry_line.memo,  journal_entry_line.journal_entry_header,  account.name as account, party.name as party from journal_entry_line 
                   join account on journal_entry_line.accountid=account.account_id
                        join journal_entry_header on journal_entry_header.journal_entry_header_id=journal_entry_line.journal_entry_header
                        join account_type on account_type.account_type_id=account.acc_type
                        join party on journal_entry_header.party = party.party_id 
                        where  account_type.name=:acc_type and journal_entry_line.entry_date>=:min and journal_entry_line.entry_date<=:max";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":acc_type" => $inc_exp, ":min" => $min, ":max" => $max));
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
            <table class="margin_free">
                <td>
                    <form action="../web_exports/excel_export.php" method="post">
                        <input type="hidden" name="account" value="a"/>
                        <input type="hidden" name="account" value="a"/>
                        <input type="submit" name="export" class="btn_export btn_export_excel" value="Export"/>
                    </form>
                </td>
                <td>
                    <form action="../print_more_reports/print_income_stamt_bydates.php" target="blank" method="post">
                        <input type="hidden" name="account" value="<?php echo $min; ?>"/>
                        <input type="hidden" name="account" value="<?php echo $max; ?>"/>
                        <input type="submit" name="export" class="btn_export btn_export_pdf" value="Export"/>
                    </form>
                </td>
            </table>
            <?php
        }

        function list_assets_by_2_dates($min, $max) {// this function has one parameter but it check two parameters with or condition
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from account
                    right join journal_entry_line on journal_entry_line.accountid=account.account_id
                    left join journal_entry_header on journal_entry_header.journal_entry_header_id=journal_entry_line.journal_entry_header
                    join account_type on account.acc_type=account_type.account_type_id         
                    where account_type.name='other current asset'
                    or account_type.name='Fixed Assets'
                    or account_type.name='Other asset'
                     and journal_entry_line.entry_date>=:min and journal_entry_line.entry_date<=:max";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min, ":max" => $max));
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
                        </td></tr>
                    <?php
                    $pages += 1;
                }
                ?></table>  
            <table class="margin_free">
                <td>
                    <form action="../web_exports/excel_export.php" method="post">
                        <input type="hidden" name="account" value="a"/>
                        <input type="hidden" name="account" value="a"/>
                        <input type="submit" name="export" class="btn_export btn_export_excel" value="Export"/>
                    </form>
                </td>
                <td>
                    <form action="../print_more_reports/print_balancesheet_bydates.php" target="blank" method="post">
                        <input type="hidden" name="account" value="<?php echo $min; ?>"/>
                        <input type="hidden" name="account" value="<?php echo $max; ?>"/>
                        <input type="submit" name="export" class="btn_export btn_export_pdf" value="Export"/>
                    </form>
                </td>
            </table>
            <?php
        }

        function list_purchase_receit_line_by_2_dates($min, $max) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from purchase_receit_line "
                    . " join user on user.StaffID=purchase_receit_line.User"
                    . " where purchase_receit_line.entry_date>=:min and purchase_receit_line.entry_date<=:max";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min, ":max" => $max));
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
                ?></table> <table class="margin_free">
                <td>
                    <form action="../web_exports/excel_export.php" method="post">
                        <input type="hidden" name="account" value="a"/>
                        <input type="hidden" name="account" value="a"/>
                        <input type="submit" name="export" class="btn_export btn_export_excel" value="Export"/>
                    </form>
                </td>
                <td>
                    <form action="../print_more_reports/print_purchases_bydates.php" target="blank" method="post">
                        <input type="hidden" name="account" value="<?php echo $min; ?>"/>
                        <input type="hidden" name="account" value="<?php echo $max; ?>"/>
                        <input type="submit" name="export" class="btn_export btn_export_pdf" value="Export"/>
                    </form>
                </td>
            </table>
            <?php
        }

        function list_sales_receit_header($min, $max) {
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select sales_receit_header.sales_receit_header_id,  sales_receit_header.sales_invoice,  sales_receit_header.quantity,  sales_receit_header.unit_cost,  sales_receit_header.amount,  sales_receit_header.entry_date,  sales_receit_header.User,  sales_receit_header.client,    sales_receit_header.budget_prep,  sales_receit_header.account,p_budget_items.item_name as item "
                    . ",user.Firstname,user.Lastname   from sales_receit_header "
                    . " join user on user.StaffID= sales_receit_header.User "
                    . " join sales_invoice_line on sales_receit_header.sales_invoice=sales_invoice_line.sales_invoice_line_id
                            join sales_order_line on sales_invoice_line.sales_order= sales_order_line.sales_order_line_id
                            join sales_quote_line on sales_order_line.quotationid=sales_quote_line.sales_quote_line_id 
                            join p_budget_items on sales_quote_line.item=p_budget_items.p_budget_items_id  "
                    . " where sales_receit_header.entry_date>=:min and sales_receit_header.entry_date<=:max ";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":min" => $min, ":max" => $max));
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
                ?></table>  <table class="margin_free">
                <td>
                    <form action="../web_exports/excel_export.php" method="post">
                        <input type="hidden" name="account" value="a"/>
                        <input type="hidden" name="account" value="a"/>
                        <input type="submit" name="export" class="btn_export btn_export_excel" value="Export"/>
                    </form>
                </td>
                <td>
                    <form action="../print_more_reports/print_sales_bydates.php" target="blank" method="post">
                        <input type="hidden" name="account" value="<?php echo $min; ?>"/>
                        <input type="hidden" name="account" value="<?php echo $max; ?>"/>
                        <input type="submit" name="export" class="btn_export btn_export_pdf" value="Export"/>
                    </form>
                </td>
            </table>
            <?php
        }

        function list_p_requests_by_dates($min, $max) {
            ?><div class="parts full_center_two_h heit_free no_paddin_shade_no_Border xx_titles blue_text">Budget Line prepared on <?php echo $min . ' -  ' . $max ?></div><?php
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = " select main_request,main_request_id, main_request.entry_date,main_request.User "
                    . "  ";
            $stmt = $db->prepare($sql);
            $stmt->execute();
            ?>
            <script>
                $('.row_font_h1').hover(function () {
                    $('.no_padding_print_btn').css('opacity', '1');
                });
                $('.row_font_h1').mouseleave(function () {
                    $('.no_padding_print_btn').css('opacity', '0');
                });
            </script>
            <table class="white_report_table margin_free ">
                <thead><tr >
                        <td> Budget Line </td>                       

                        <?php if (!empty($_SESSION['shall_delete'])) { ?>
                            <td>Delete</td>
                            <td>Update</td><?php } ?>
                    </tr>
                </thead>
                <?php
                $pages = 1;
                while ($row = $stmt->fetch()) {
                    ?><tr  class="row_font_h1"   data-bind="p_budget_prep"> 
                        <td class="project_type_id_cols p_budget_prep " title="p_budget_prep" >
                            <div class="parts no_paddin_shade_no_Border type_lbl"> <?php echo $this->_e('BUDGET LINE: ' . $row['User']); ?></div> 
                            <div class="parts no_paddin_shade_no_Border">
                                <table style="margin:0px;" class="margin_free sub_datatable_leftoff">
                                    <td class="no_paddin_shade_no_Border no_padding_print_btn">
                                        <form action="../web_exports/excel_export.php" method="post">
                                            <input type="hidden" name="account" value="a"/>
                                            <input type="hidden" name="account" value="a"/>
                                            <input type="submit" name="export" class="btn_export btn_export_excel margin_free" value="Export"/>
                                        </form>
                                    </td>
                                    <td class="no_paddin_shade_no_Border no_padding_print_btn">
                                        <form action="../print_more_reports/print_budget_line_bydates.php" target="blank" method="post">
                                            <input type="hidden" name="account" value="<?php echo $min; ?>"/>
                                            <input type="hidden" name="account" value="<?php echo $max; ?>"/>
                                            <input type="hidden" name="type" value="<?php echo $row['p_type_project_id']; ?>"/>
                                            <input type="hidden" name="proj_name" value="<?php echo $row['name']; ?>"/>
                                            <input type="submit" name="export" class="btn_export btn_export_pdf margin_free" value="Export"/>
                                        </form>
                                    </td>
                                </table>
                            </div>
                        </td>
                        <?php if (!empty($_SESSION['shall_delete'])) { ?>
                            <td>
                                <a href="#" class="p_budget_prep_delete_link" style="color: #000080;" data-id_delete="p_budget_prep_id"  data-table="
                                   <?php echo $row['p_budget_prep_id']; ?>">Delete</a>
                            </td>
                            <td>
                                <a href="#" class="p_budget_prep_update_link" style="color: #000080;" value="
                                   <?php echo $row['p_budget_prep_id']; ?>">Update</a>
                            </td><?php } ?>
                    </tr>
                    <tr >
                        <td>
                            <?php $this->list_requests_by_main($min, $max, $row['p_type_project_id']) ?>
                        </td> 
                    </tr>

                    <tr class="leave_bottom_space">
                        <td></td>
                    </tr>

                    <?php
                    $pages += 1;
                }
                ?></table><?php
        }

        function list_p_type_project_admin($first, $last) {

            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select * from p_type_project limit " . $first . " , " . $last;
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
                ?> <div class="parts full_center_two_h heit_free no_paddin_shade_no_Border">
                    <div class="parts no_paddin_shade_no_Border off">  From  </div>
                    <div class="parts no_paddin_shade_no_Border off">  <input type="text" class="textbox date_picks" id="txt_min_date" autocomplete="off" name="txt_name" value="<?php echo date("Y") - 1; ?>"  />  </div>
                    <div class="parts no_paddin_shade_no_Border off">  To  </div>
                    <div class="parts no_paddin_shade_no_Border off">  <input type="text" class="textbox date_picks" id="txt_max_date" autocomplete="off" name="txt_name" value="<?php echo date("Y"); ?>"  />  </div>
                    <div class="parts no_paddin_shade_no_Border">  <input type="text" class="textbox txt_rep_val off" placeholder="Enter the value" autocomplete="off" name="txt_name"  />  </div>
                    <div class="parts full_center_two_h heit_free no_paddin_shade_no_Border xxx_titles">List of Budgets</div>

                </div>  
                <script>
                    $(document).ready(function () {
                        $('#txt_min_date, #txt_max_date').datepicker({
                            dateFormat: 'yy-mm-dd'
                        });
                    });</script>
                <?php
                while ($row = $stmt->fetch()) {
                    ?>
                    <div class="parts data_total admin_rep_panelevel1 link_cursor"data-proj_name="<?php echo $row['name']; ?>"   data-bind="<?php echo $row['p_type_project_id']; ?>"> <?php echo $this->_e($row['name']); ?>
                        <span class="push_right data_total full_center_two_h heit_free smaller_font">
                            <?php echo 'Total ' . number_format($this->get_total_budget_line($row['p_type_project_id'])); ?>
                        </span>
                    </div>     
                    <?php ?><tr class="clickable_row" data-table_id="<?php echo $row['p_type_project_id']; ?>" data_title="" data-bind="p_type_project"> 
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
                            <td>
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

        function list_p_type_project_admin_lv2($type) {

            //This one gets the projects, activities
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "select p_budget_prep.p_budget_prep_id,  p_budget_prep.project_type,  p_budget_prep.user,  p_budget_prep.entry_date,  p_budget_prep.budget_type,  p_budget_prep.activity_desc, sum(p_activity.amount) as amount,   p_budget_prep.name, user.Firstname, user.Lastname, p_type_project.name as type, p_field.name as field, p_field.p_field_id 
                    from p_budget_prep
                    join user on p_budget_prep.user = user.StaffID 
                    join p_type_project on p_budget_prep.project_type=p_type_project.p_type_project_id
                    join p_activity on p_activity.project=p_budget_prep.p_budget_prep_id
                    join p_field on p_field.p_field_id=p_activity.field
                    where p_type_project_id=:type   
                    group by field";
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
                <div class="parts  no_shade_noBorder">
                    <a href="admin_reports.php">Back</a>
                </div>
                <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                    Budget Line/Projects /<?php echo $_SESSION['proj_name']; ?>
                </div>

            </div>
            <div class="clickable_row_table_box">
                <?php while ($row = $stmt->fetch()) { ?>
                    <div class="parts no_paddin_shade_no_Border   admin_rep_databox_lv2">
                        <div class="parts admin_rep_loc_box margin_free link_cursor" data-loc_name="<?php echo $row['field']; ?>"   data-proj_name="<?php echo $row['type']; ?>" data-bind="<?php echo $row['p_field_id']; ?>">
                            <?php echo $row['field']; ?></div>
                        <div class="parts admin_rep_amount_box margin_free"><?php echo number_format($row['amount']); ?></div>
                    </div>

                <?php } ?>
                <tr>
                    <td colspan="7">
                        <span class="push_right data_total">
                            <?php echo 'Total ' . number_format($this->get_total_budget_line($budget_id)); ?>
                        </span>
                    </td>
                </tr>
            </table></div>
            <?php
        }

        function list_requests_by_main($min, $max, $request) {
            
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

        function get_total_budget_line($budget) {
            $con = new dbconnection();
            $sql = "select sum(amount) as amount,p_field.p_field_id from p_activity 
                join   p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id 
                join p_type_project on p_budget_prep.project_type=p_type_project.p_type_project_id
                 join p_field on p_field.p_field_id=p_activity.field
                 where p_type_project.p_type_project_id=:id 
                 ";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute(array(":id" => $budget));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $amount = $row['amount'];
            return $amount;
        }

        function get_total_activities_by_project($budget) {
            $con = new dbconnection();
            $sql = "select sum(amount) as amount,p_field.p_field_id from p_activity 
                join   p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id 
                join p_type_project on p_budget_prep.project_type=p_type_project.p_type_project_id
                 join p_field on p_field.p_field_id=p_activity.field
                 where p_budget_prep.p_budget_prep_id=:id 
                 ";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute(array(":id" => $budget));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $amount = $row['amount'];
            return $amount;
        }

        function get_total_budget_line_loc($budget, $field) {
            $con = new dbconnection();
            $sql = "select sum(amount) as amount from p_activity 
                join   p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id 
                join p_type_project on p_budget_prep.project_type=p_type_project.p_type_project_id
                 join p_field on p_field.p_field_id=p_activity.field
                 where p_type_project.p_type_project_id=:id and  p_field.p_field_id=:field";
            $stmt = $con->openconnection()->prepare($sql);
            $stmt->execute(array(":id" => $budget, ":field" => $field));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $amount = $row['amount'];
            return $amount;
        }

        function det_by_click_type_project_admin($type, $field) {
            //This one gets the projects, activities BY FIELD// level 3
            require_once('../web_db/connection.php');
            $database = new dbconnection();
            $db = $database->openConnection();
            $sql = "  select p_budget_prep.p_budget_prep_id,  p_budget_prep.project_type,   p_budget_prep.entry_date,  p_budget_prep.budget_type,  p_budget_prep.activity_desc, sum( p_activity.amount) as amount,   p_budget_prep.name as proj, p_type_project.p_type_project_id, p_type_project.name as type,  p_activity.name as activity,
                    sum(p_activity.amount) as p_amount 
                    from p_activity 
                    join   p_budget_prep on p_activity.project=p_budget_prep.p_budget_prep_id 
                    join p_type_project on p_budget_prep.project_type=p_type_project.p_type_project_id
                    where p_type_project.p_type_project_id=:type and  p_activity.field=:field";
            $stmt = $db->prepare($sql);
            $stmt->execute(array(":type" => $type, ":field" => $field));
            ?>
            <!--this is js-->
            <script>
                $('.data_details_pane_close_btn').click(function () {
                    $('.data_details_pane').fadeOut(10);
                    $('.data_details_pane').html('');
                });</script>
            <!--ending js-->

            <div class="parts full_center_two_h heit_free no_paddin_shade_no_Border margin_free details_box">
                <div class="parts no_paddin_shade_no_Border no_shade_noBorder data_details_pane_title">
                    Budget Line/Projects/<?php echo $_SESSION['proj_name'] . '/' . $_SESSION['field_name']; ?>
                </div>
                <div class="parts  no_shade_noBorder margin_free  full_center_two_h heit_free">
                    <a href="admin_reports_lv2.php" style="float: left;">       Back  </a>
                </div> 
            </div>
            <div class="parts  parts full_center_two_h heit_free margin_free">
                <table class="dataList_table clickable_row_table">
                    <thead><tr><td> S/N </td>
                            <td> Budget line </td>
                            <td> Project </td>
                            <td> activity </td>
                            <td class="off"> entry date </td>
                            <td> Budget type </td>
                            <td class="off"> activity desc </td>
                            <td> Budget </td>
                            <td> Implementation </td>
                            <td> Current </td>
                            <td> % </td>
                        </tr></thead>
                    <?php
                    while ($row = $stmt->fetch()) {
                        $bdgt = $row['amount'];
                        $impl = $row['p_amount'];
                        $net = $bdgt - $impl;
                        $net_pctg = round(($impl / $bdgt) / 100, 2);
                        $user_pctg = 100 - $net_pctg;
                        ?>
                        <tr> 
                            <td><?php echo $row['p_budget_prep_id']; ?> </td>
                            <td><?php echo $row['type']; ?> </td>
                            <td><?php echo $row['proj']; ?> </td>
                            <td><?php echo $row['activity']; ?></td>
                            <td class="off">        <?php echo $row['entry_date']; ?> </td>
                            <td>        <?php echo $row['budget_type']; ?> </td>
                            <td class="off">        <?php echo $row['activity_desc']; ?> </td>
                            <td>        <?php echo number_format($row['amount']); ?> </td>

                            <td><?php echo number_format($row['p_amount']); ?> </td>
                            <td><?php echo number_format($net); ?> </td>
                            <td><?php echo $user_pctg; ?> </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="7">
                            <span class="push_right data_total">
                                <?php echo 'Total ' . $row['p_type_project_id'] . number_format($this->get_total_budget_line_loc($_SESSION['report_budgetid'], $_SESSION['report_locationid'])); ?>
                            </span>
                        </td>
                    </tr>
                </table></div>
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

        // <editor-fold defaultstate="collapsed" desc="---- Financial books Special views (display)-------------">
        // Cost of goods sold
        function get_opening_stock($date1, $date2) {
            $db = new dbconnection();
            $sql = "select sum(total_amount) as total_amount from main_stock where entry_date>=:min_date  and entry_date<=:max_date order by main_stock_id desc limit 1";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(':min_date' => $date1, ':max_date' => $date2));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['total_amount'];
            return $field;
        }

        function get_opening_stock_by_item($date1, $date2, $item) {
            $db = new dbconnection();
            $sql = "select total_amount from main_stock where entry_date>=:min_date and entry_date<=:max_date and main_stock.item=:item order by main_stock_id asc limit 1";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(':min_date' => $date1, ':max_date' => $date2, ':item' => $item));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['total_amount'];
            return $field;
        }

        function get_closing_stock($date1, $date2) {
            $db = new dbconnection();
            $sql = "select sum(total_amount) as total_amount from main_stock where entry_date>=:min_date and entry_date<=:max_date   order by main_stock_id desc limit 1; ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(':min_date' => $date1, ':max_date' => $date2));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['total_amount'];
            return $field;
        }

        function get_closing_stock_by_item($date1, $date2, $item) {
            $db = new dbconnection();
            $sql = "select total_amount from main_stock where entry_date>=:min_date and entry_date<=max_date and main_stock.item=:item order by main_stock_id desc limit 1";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(':min_date' => $date1, ':max_date' => $date2, ':item' => $item));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['total_amount'];
            return $field;
        }

        function get_purchases_ofPeriod($date1, $date2) {
            $db = new dbconnection();
            $sql = " select amount from purchase_receit_line where entry_date>=:min_date and entry_date<=:max_date";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(':min_date' => $date1, ':max_date' => $date2));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['amount'];
            return $field;
        }

        function list_purchases_ofPeriod($date1, $date2) {
            $db = new dbconnection();
            $sql = " select amount ,account.name as account,purchase_invoice_line.entry_date "
                    . " from purchase_invoice_line"
                    . " join account on account.account_id= purchase_invoice_line.account "
                    . " join account_type on account_type.account_type_id=account.acc_type "
                    . " where account_type.name='expense'  "
                    . " purchase_invoice_line.entry_date>=:min_date and purchase_invoice_line.entry_date<=:max_date";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(':min_date' => $date1, ':max_date' => $date2));
            ?><table class="income_table2"><thead class="books_header"><tr>
                        <td>Account</td>
                        <td>Amount</td>
                        <!--<td>Memo</td>-->
                        <td>Entry date</td>
                    </tr></thead><?php
                while ($row = $stmt->fetch()) {
                    ?>
                    <tr class="inner_sub">
                        <td><?php echo $row['account']; ?></td>
                        <td><?php echo number_format($row['amount']); ?></td>
                        <td><?php echo $row['entry_date']; ?></td>
                    </tr>
                <?php }
                ?></table><?php
        }

        function get_openingstock_by_budget($date1, $date2) {// budget type is like: constriction, agriculture, etc
            $db = new dbconnection();
            $sql = "  select total_amount as amount from main_stock
                        join p_budget_items on p_budget_items.p_budget_items_id=main_stock.item
                        join p_type_project on p_type_project.p_type_project_id=p_budget_items.type
                        where p_type_project.name='construction' and main_stock.entry_date>=:min_date and main_stock.entry_date<=:max_date order by main_stock_id asc limit 1";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(':min_date' => $date1, ':max_date' => $date2));
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $field = $row['amount'];
            return $field;
        }

        function list_openingstock_by_date($date1, $date2) {// budget type is like: constriction, agriculture, etc
            $db = new dbconnection();
            $sql = "  select sum(total_amount) as amount,p_type_project.name  from main_stock
                        join p_budget_items on p_budget_items.p_budget_items_id=main_stock.item
                        join p_type_project on p_type_project.p_type_project_id=p_budget_items.type
                        where   main_stock.entry_date>=:min_date and main_stock.entry_date<=:max_date
                        group by  p_type_project.name
                        order by main_stock_id asc limit 1";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(':min_date' => $date1, ':max_date' => $date2));
            while ($row = $stmt->fetch()) {
                ?>
                <tr class="inner_sub">
                    <td><?php echo $row['name']; ?></td><td><?php echo number_format($row['amount']); ?></td>
                </tr>
                <?php
            }
        }

        function list_closingstock_by_date($date1, $date2) {// budget type is like: constriction, agriculture, etc
            ?> <table>
                <thead><tr>
                        <td> Name </td>
                        <td> Amount </td></tr>
                        </thead>

                        <?php
                        $db = new dbconnection();
                        $sql = "  select sum(total_amount) as amount,p_type_project.name  from main_stock
                        join p_budget_items on p_budget_items.p_budget_items_id=main_stock.item
                        join p_type_project on p_type_project.p_type_project_id=p_budget_items.type
                        where   main_stock.entry_date>=:min_date and main_stock.entry_date<=:max_date
                        group by  p_type_project.name
                        order by main_stock_id desc limit 1";
                        $stmt = $db->openConnection()->prepare($sql);
                        $stmt->execute(array(':min_date' => $date1, ':max_date' => $date2));
                        while ($row = $stmt->fetch()) {
                            ?><tr>
                            <td><?php echo $row['name']; ?></td><td><?php echo number_format($row['amount']); ?></td>
                        </tr><?php }
                        ?></table><?php
        }

// </editor-fold>
        // <editor-fold defaultstate="collapsed" desc="------------Full books -------------------">
        function list_full_income_stmt_by_date($min_date, $max_date) {
            ?><table class="income_table2">
                <thead class="books_header">
                    <tr>
                        <td>Account</td>
                        <td>Amount</td> <td>Memo</td> <td>Entry date</td>
                    </tr>
                </thead>

                <?php ?><tr class="row_bold"><td colspan="3">SALES REVENUE</td></tr><?php
                $this->full_inc_sales_by_date($min_date, $max_date);
                ?><tr class="row_bold"><td colspan="3">COST OF GOOD SOLD</td></tr><?php
                $this->full_inc_cogs_by_date($min_date, $max_date);
                ?><tr class="row_bold"><td colspan="3">RESEARCH AND DEVELOPMENT</td></tr><?php
                $this->full_inc_research_dev_by_date($min_date, $max_date);
                ?><tr class="row_bold"><td colspan="3">INTEREST INCOME</td></tr><BR/><?php ?><tr class="row_bold"><td colspan="3">INCOME TAX</td></tr><?php ?>

            </table><?php
        }

        function list_full_balancesheet_by_date($min_date, $max_date) {
            ?><table class="income_table2">
                <thead class="books_header">
                    <tr>
                        <td>Account</td>
                        <td>Amount</td> <td>Memo</td> <td>Entry date</td>
                    </tr>
                </thead>
                <?php ?><tr class="row_bold"><td colspan="3">ASSETS</td></tr><?php
                $this->full_balnc_cash_by_date($min_date, $max_date);
                ?><tr class="row_bold"><td colspan="3">ACCOUNT RECEIVABLE</td></tr><?php
                $this->full_balnc_account_receivable($min_date, $max_date);
                ?><tr class="row_bold"><td colspan="3">INVENTORY</td></tr><?php
                $this->full_balnc_inventory($min_date, $max_date);
                ?><tr class="row_bold"><td colspan="3">REPAID EXPENSE</td></tr><?php
                $this->full_balnc_prepaid_expenses($min_date, $max_date);
                ?><tr class="row_bold"><td colspan="3">OTHER ASSETS</td></tr><?php
                $this->full_balnc_other_asset($min_date, $max_date);
                ?><tr class="row_bold"><td colspan="3">FIXED ASSETS</td></tr><?php
                $this->full_balnc_fixed_assets($min_date, $max_date);
                ?></table><?php
        }

        function list_full_cashflow_by_date($param) {
            
        }

// </editor-fold>


        function full_inc_sales_by_date($min_date, $max_date) {
            $db = new dbconnection();
            $sql = "select account.name as account,  account.account_id, journal_entry_line.amount as amount ,journal_entry_line.entry_date, journal_entry_line.memo  from account
join journal_entry_line on journal_entry_line.accountid=account.account_id
join account_type on account.acc_type=account_type.account_type_id 
where account_type.name='income' and journal_entry_line.entry_date>=:min_date and journal_entry_line.entry_date<=:max_date ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
            while ($row = $stmt->fetch()) {
                ?>
                <tr class="inner_sub">
                    <td><?php echo $row['account']; ?></td>
                    <td><?php echo $row['amount']; ?></td>
                    <td><?php echo $row['memo']; ?></td>
                    <td><?php echo $row['entry_date']; ?></td>
                </tr> 
                <?php
            }
        }

        function full_inc_cogs_by_date($min_date, $max_date) {
            $db = new dbconnection();
            $sql = "select account.name as account,  account.account_id, journal_entry_line.amount as amount ,journal_entry_line.entry_date, journal_entry_line.memo  from account
                            join journal_entry_line on journal_entry_line.accountid=account.account_id
                            join account_type on account.acc_type=account_type.account_type_id 
                            where account_type.name='expense' and journal_entry_line.entry_date>=:min_date and journal_entry_line.entry_date<=:max_date";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
            while ($row = $stmt->fetch()) {
                ?>
                <tr class="inner_sub">
                    <td><?php echo $row['account']; ?></td>
                    <td><?php echo $row['amount']; ?></td>
                    <td><?php echo $row['memo']; ?></td>
                    <td><?php echo $row['entry_date']; ?></td>
                </tr> 
                <?php
            }
        }

        function full_inc_research_dev_by_date($min_date, $max_date) {
            $db = new dbconnection();
            $sql = "select account.name as account,  journal_entry_line.amount , journal_entry_line.memo, journal_entry_line.entry_date from account               
                                join journal_entry_line on journal_entry_line.accountid=account.account_id
                                join account_type on account.acc_type=account_type.account_type_id   
                                where account.name='Research and development'  and journal_entry_line.entry_date>=:min_date and journal_entry_line.entry_date<=:max_date ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
            while ($row = $stmt->fetch()) {
                ?><tr class="inner_sub">
                    <td><?php echo $row['account'] ?></td>
                    <td><?php echo number_format($row['amount']) ?></td>
                    <td><?php echo $row['memo'] ?></td>
                    <td><?php echo $row['entry_date'] ?></td>
                </tr><?php
            }
            ?></table><?php
        }

        function full_inc_gen_expense_by_date($min_date, $max_date) {
            $db = new dbconnection();
            $sql = "select account.name as account,journal_entry_line.entry_date,  journal_entry_line.memo,  journal_entry_line.amount from account               
                    join journal_entry_line on journal_entry_line.accountid=account.account_id
                    join account_type on account.acc_type=account_type.account_type_id   
                    where account_type.name='expense' and account.name<>'Research and development'  and journal_entry_line.entry_date>=:min_date and journal_entry_line.entry_date<=:max_date
                    ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
            while ($row = $stmt->fetch()) {
                ?><tr>
                    <td><?php echo $row['account'] ?></td>
                    <td><?php echo number_format($row['amount']) ?></td>
                    <td><?php echo $row['memo'] ?></td>
                    <td><?php echo $row['entry_date'] ?></td>
                </tr><?php
            }
        }

        function full_inc_interestincome($min_date, $max_date) {
//Calculated by accountant
        }

//Balance sheet
        function full_balnc_cash_by_date($min_date, $max_date) {
            $db = new dbconnection();
            $sql = "select account.name as account,journal_entry_line.memo,journal_entry_line.entry_date,  sum(journal_entry_line.amount) as amount from account               
                                join journal_entry_line on journal_entry_line.accountid=account.account_id
                                join account_type on account.acc_type=account_type.account_type_id   
                                where account_type.name<>'Other Current asset'    and journal_entry_line.entry_date>=:min_date and journal_entry_line.entry_date<=:max_date
                                group by account_type.account_type_id";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));

            while ($row = $stmt->fetch()) {
                ?><tr>
                    <td><?php echo $row['account'] ?></td>
                    <td><?php echo number_format($row['amount']) ?></td>
                    <td><?php echo $row['memo'] ?></td>
                    <td><?php echo $row['entry_date'] ?></td>
                </tr><?php
            }
        }

        function full_balnc_account_receivable($min_date, $max_date) {
            $db = new dbconnection();
            $sql = "select account.name as account,  sum(journal_entry_line.amount) as amount from account               
                                join journal_entry_line on journal_entry_line.accountid=account.account_id
                                join account_type on account.acc_type=account_type.account_type_id   
                                where account_type.name='account receivable'  and journal_entry_line.entry_date>=:min_date and journal_entry_line.entry_date<=:max_date
                                group by account_type.account_type_id ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));

            while ($row = $stmt->fetch()) {
                ?><tr>
                    <td><?php echo $row['account'] ?></td>
                    <td><?php echo number_format($row['amount']) ?></td>
                </tr><?php
            }
        }

        function full_balnc_inventory($min_date, $max_date) {
            $db = new dbconnection();
            $sql = "select purchase_invoice_line.account,account.name as acc,stock_into_main.entry_date, stock_into_main.item, sum(stock_into_main.quantity-distriibution.taken_qty) as quantity_out,
                                    sum(purchase_invoice_line.amount) as amount, purchase_invoice_line.unit_cost from stock_into_main 
                                    join purchase_invoice_line on purchase_invoice_line.purchase_invoice_line_id=stock_into_main.purchaseid
                                    join distriibution on distriibution.item=stock_into_main.item
                                    join account on account.account_id=purchase_invoice_line.account
                                    where stock_into_main.entry_date>=:min_date and stock_into_main.entry_date<=:max_date
                                    group by stock_into_main.item ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));
            while ($row = $stmt->fetch()) {
                ?><tr class="inner_sub">
                    <td><?php echo $row['acc'] ?></td>
                    <td><?php echo number_format($row['amount']) ?></td>
                    <!--<td><?php echo number_format($row['memo']) ?></td>-->
                    <td><?php echo $row['entry_date'] ?></td>
                </tr><?php
            }
        }

        function full_balnc_prepaid_expenses($min_date, $max_date) {
            $db = new dbconnection();
            $sql = "select account.name as account,journal_entry_line.entry_date,journal_entry_line.memo,  journal_entry_line.amount as amount from account               
                                join journal_entry_line on journal_entry_line.accountid=account.account_id
                                join account_type on account.acc_type=account_type.account_type_id   
                                where account.name='Prepaid expenses'  and journal_entry_line.entry_date>=:min_date and journal_entry_line.entry_date<=:max_date
                                ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));

            while ($row = $stmt->fetch()) {
                ?><tr class="inner_sub">
                    <td><?php echo $row['account'] ?></td>
                    <td><?php echo number_format($row['amount']) ?></td>
                    <td><?php echo $row['memo'] ?></td>
                    <td><?php echo $row['entry_date'] ?></td>
                </tr><?php
            }
        }

        function full_balnc_other_asset($min_date, $max_date) {
            
        }

        function full_balnc_fixed_assets($min_date, $max_date) {
            $db = new dbconnection();
            $sql = "select account.name as account,journal_entry_line.memo,journal_entry_line.entry_date,  sum(journal_entry_line.amount) as amount from account               
                                join journal_entry_line on journal_entry_line.accountid=account.account_id
                                join account_type on account.acc_type=account_type.account_type_id   
                                where account_type.name<>'Other Current asset'    and journal_entry_line.entry_date>=:min_date and journal_entry_line.entry_date<=:max_date
                                group by account_type.account_type_id";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute(array(":min_date" => $min_date, ":max_date" => $max_date));

            while ($row = $stmt->fetch()) {
                ?><tr>
                    <td><?php echo $row['account'] ?></td>
                    <td><?php echo number_format($row['amount']) ?></td>
                    <td><?php echo $row['memo'] ?></td>
                    <td><?php echo $row['entry_date'] ?></td>
                </tr><?php
            }
        }

        function get_types($min, $max) {
            try {
                require_once '../web_db/fin_books_sum_views.php';
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $fin = new fin_books_sum_views();
                $sql = $fin->get_budgets();
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min_date" => $min, ":max_date" => $max));
                ?> <table class="report_percentage_tableview">
                    <tr>
                        <td>S/N</td>
                        <td>PROJECT</td>
                        <td>REVENUE PREPARATION</td>
                        <td class="">REVENUE IMPL.</td>
                        <td>eXPENSES</td>
                        <td>GROSS PROFIT</td>
                        <td>G.P PERCENTAGE</td>
                    </tr><?php
                    while ($row = $stmt->fetch()) {
//                    echo $row['prj_type'] . '<br/><hr/>';
                        $sql2 = $fin->get_tot_rev_exp('revenue');
                        $sql3 = $fin->get_tot_rev_exp('expense');

                        $sql4 = $fin->get_implementation_rev_exp('income');
                        $sql5 = $fin->get_implementation_rev_exp('expense');
                        $stmt2 = $db->prepare($sql2);
                        $stmt3 = $db->prepare($sql3);
                        $stmt4 = $db->prepare($sql4);
                        $stmt5 = $db->prepare($sql5);
                        $stmt2->execute(array(":project" => $row['p_type_project_id']));
                        $stmt3->execute(array(":project" => $row['p_type_project_id']));
                        $stmt4->execute(array(":project" => $row['p_type_project_id']));
                        $stmt5->execute(array(":project" => $row['p_type_project_id']));
                        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                        $row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
                        $row4 = $stmt4->fetch(PDO::FETCH_ASSOC);
                        $row5 = $stmt5->fetch(PDO::FETCH_ASSOC);
                        $tot1 = ($row2['tot'] < 1) ? 1 : $row2['tot'];
                        $exp1 = ($row3['tot'] < 1) ? 1 : $row3['tot'];
//                        $curr_rev = ($row4['tot'] < 1) ? 1 : $row4['tot'];
                        $curr_rev = $row4['tot'];
                        if ($curr_rev < 1 && $curr_rev != 0) {
                            $curr_rev *= -1;
                        }
//                    echo '(' . $row['prj_type'] . ') Revenues: ' . number_format($row2['tot']) . ' // expenses: ' . number_format($row3['tot']) . '<br/>'
//                    . 'Impl: Revenues: ' . number_format($row4['tot']) . '  // expenses: ' . number_format($row5['tot']) . ' <br/>'
//                    . 'percentage revenues: ' . $row2['tot'] / $tot1 * 100 . '% <br/>'
//                    . 'Percentage expenses: ' . $row5['tot'] / $exp1 * 100 . '%'
//                    . '<hr/>';
                        ?>
                        <tr class="clickable_row" data-bind="budget_for_details" data-prjtype="<?php echo $row['p_type_project_id']; ?>">
                            <td><?php echo $row['p_type_project_id']; ?></td>
                            <td><?php echo strtoupper($row['prj_type']); ?></td>
                            <td><?php echo number_format($row2['tot']); ?></td>
                            <td class=""><?php echo number_format($curr_rev); ?></td>
                            <td><?php echo number_format($row5['tot']); ?></td>
                            <td><?php echo $perc = number_format($curr_rev - $row5['tot']) ?></td>
                            <?php if ($perc < 50) { ?>
                                <td class="warn_msg"><?php echo number_format(($row4['tot'] - $row5['tot']) / $tot1 * 100) . '%' ?></td>
                            <?php } else { ?>
                                <td><?php echo number_format(($curr_rev - $row5['tot']) / $curr_rev * 100) . '%' ?></td>
                            <?php } ?>
                        </tr>

                    <?php }
                    ?> <tr>
                        <td colspan="7">
                            <form action="../prints/print_p_type_project.php"target="blank" method="post">
                                <input type="submit" name="export" class="btn_export btn_export_pdf" value="Print"/>
                            </form>
                        </td>
                    </tr> </table><?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function get_types2($min, $max) {
            try {
                require_once '../web_db/fin_books_sum_views.php';
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $fin = new fin_books_sum_views();
                $sql = $fin->get_budgets();
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":min_date" => $min, ":max_date" => $max));

                while ($row = $stmt->fetch()) {
//                    echo $row['prj_type'] . '<br/><hr/>';
                    $sql2 = $fin->get_tot_rev_exp('revenue');
                    $sql3 = $fin->get_tot_rev_exp('expense');

                    $sql4 = $fin->get_implementation_rev_exp('income');
                    $sql5 = $fin->get_implementation_rev_exp('expense');
                    $stmt2 = $db->prepare($sql2);
                    $stmt3 = $db->prepare($sql3);
                    $stmt4 = $db->prepare($sql4);
                    $stmt5 = $db->prepare($sql5);
                    $stmt2->execute(array(":project" => $row['p_type_project_id']));
                    $stmt3->execute(array(":project" => $row['p_type_project_id']));
                    $stmt4->execute(array(":project" => $row['p_type_project_id']));
                    $stmt5->execute(array(":project" => $row['p_type_project_id']));
                    $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                    $row3 = $stmt3->fetch(PDO::FETCH_ASSOC);
                    $row4 = $stmt4->fetch(PDO::FETCH_ASSOC);
                    $row5 = $stmt5->fetch(PDO::FETCH_ASSOC);
                    $tot1 = ($row2['tot'] < 1) ? 1 : $row2['tot'];
                    $exp1 = ($row3['tot'] < 1) ? 1 : $row3['tot'];
//                        $curr_rev = ($row4['tot'] < 1) ? 1 : $row4['tot'];
                    $curr_rev = $row4['tot'];
                    if ($curr_rev < 1 && $curr_rev != 0) {
                        $curr_rev *= -1;
                    }
//                    echo '(' . $row['prj_type'] . ') Revenues: ' . number_format($row2['tot']) . ' // expenses: ' . number_format($row3['tot']) . '<br/>'
//                    . 'Impl: Revenues: ' . number_format($row4['tot']) . '  // expenses: ' . number_format($row5['tot']) . ' <br/>'
//                    . 'percentage revenues: ' . $row2['tot'] / $tot1 * 100 . '% <br/>'
//                    . 'Percentage expenses: ' . $row5['tot'] / $exp1 * 100 . '%'
//                    . '<hr/>';
                    ?>
                    <table  class="report_percentage_tableview">
                        <tr class="row_bold big_title">
                            <td colspan="4"><?php echo $row['prj_type']; ?></td>
                        </tr>
                        <tr>
                            <td colspan="2">Preparation</td>  <td colspan="2">Implementation</td>
                        </tr>
                        <tr>
                            <td><?php echo 'Revenues: ' . number_format($row2['tot']); ?></td> 
                            <td><?php echo 'Expenses: ' . number_format($row3['tot']); ?></td>
                            <td><?php echo 'Revenues: ' . number_format($row4['tot']) ?></td> 
                            <td><?php echo 'Expenses: ' . number_format($row5['tot']); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2"><?php echo 'Reveues percentage: ' . $row2['tot'] / $tot1 * 100 . '%'; ?></td>                            
                            <td colspan="2"><?php echo 'Expenses percentage: ' . $row5['tot'] / $exp1 * 100 . '%'; ?></td>                        
                        </tr>
                    </table>

                <?php }
                ?> <?php
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }

        function _e($string) {
            echo htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        }

         function get_budget_Amount($type,$line)
        {
            $total=0 ;
            $db = new dbconnection();
            $sql = "SELECT  SUM (p_activity.amount) as amount from p_activity 
                    join 
                        p_budget_prep on p_budget_prep.p_budget_prep_id = p_activity.project 
                    join 
                    p_type_project on p_type_project.p_type_project_id = p_budget_prep.project_type 
                    join 
                       
                      project_expectations on   project_expectations.project_expectations_id=p_activity.budget_type

                    where   
                         p_type_project.p_type_project_id='$line' 
                         
                         and 
                         
                         project_expectations.name='$type' 
                          ";
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute();
            while ($row = $stmt->fetch())
            {
               $total =$row['amount'];
            }
            return $total; 
        }


         function get_budget_report ()
        {
        
           ?>  <table>
                <thead>
                    <tr>
                         <td> Budget name  </td>
                          <td> Revenues  </td>
                           <td> Expenses  </td>
                        <td> Profit  </td>
                        <td> G.P Parcentage </td>
                    </tr>
                </thead>

            <?php
            $db = new dbconnection();
            $sql = "SELECT  * from p_type_project   " ;
            $stmt = $db->openConnection()->prepare($sql);
            $stmt->execute();
            while ($row = $stmt->fetch())
            {
              $expense=$this->get_budget_Amount('expense',$row['p_type_project_id']);
              $revenue=$this->get_budget_Amount('revenue',$row['p_type_project_id']);
              $balance =$revenue-$expense;
              $rate;
              if ($revenue!=0)
              {
                $rate=$balance/$revenue;
              }
              else $rate=$balance;


            ?>      <tr>
                        <td><?php echo $row['name']; ?></td>
                        <td><?php echo number_format($expense); ?></td>
                        <td><?php echo number_format($revenue); ?></td>
                        <td><?php echo number_format($balance); ?></td>
                        <td><?php echo number_format($rate).'  %'; ?></td>
                    </tr>
            <?php 
            }
            ?>
                </table>
            <?php
            
            }
            
        



    }
    