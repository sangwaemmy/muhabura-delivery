<?php
    /*
     * This is the class that allow the user to record data based on others data
     */

    /**
     * Description of Continuos_form
     *
     * SANGWA 
     */
    require_once '../web_db/connection.php';

    class Continuos_form {

        function get_on_salesorder_from_quototion($quotation) {
            try {
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $sql = " SELECT sales_quote_line.sales_quote_line_id,  sales_quote_line.quantity,  sales_quote_line.unit_cost,  sales_quote_line.entry_date,  sales_quote_line.User,  sales_quote_line.amount,  measurement.code as measurement,p_budget_items.item_name as item FROM sales_quote_line  "
                        . "  join p_budget_items on p_budget_items.p_budget_items_id=sales_quote_line.item"
                        . "  join measurement on measurement.measurement_id = sales_quote_line.measurement "
                        . " where sales_quote_line.sales_quote_line_id=:quotation";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":quotation" => $quotation));
                ?>
                <table class="new_data_table">
                    <?php
                    $pages = 1;
                    while ($row = $stmt->fetch()) {
                        ?> 
                        <tr><td>quantity</td><td><input type="text" class="textbox"autocomplete="off" name="txt_quantity" value=" <?php echo $row['quantity']; ?>" /> </td></tr>
                        <tr><td>unit cost</td><td><input type="text" class="textbox only_numbers"autocomplete="off" name="txt_unit_cost" value=" <?php echo number_format($row['unit_cost']); ?>" /> </td></tr>
                        <tr><td>measurement</td><td><input type="text" class="textbox" name="txt_measurement_id" value=" <?php echo $row['measurement']; ?>" /> </td></tr>
                        <tr><td>item</td><td><input type="text" class="textbox" name="" value=" <?php echo $row['item']; ?>" /> </td></tr>

                        <?php
                        $pages += 1;
                    }
                    ?> <tr><td colspan="2"> <input type="submit" class="confirm_buttons" name="send_sales_order_line" value="Save"/>  </td></tr></table>
                    <?php
                } catch (Exception $exc) {
                    echo $exc->getMessage();
                }
            }

            function get_on_many_invoices($invoices) {
                $db = new dbconnection();
                $sql = "select sales_invoice_line.sales_invoice_line_id,  sales_invoice_line.quantity,  sales_invoice_line.unit_cost,  sales_invoice_line.amount,  sales_invoice_line.entry_date,  sales_invoice_line.User,  sales_invoice_line.client,  sales_invoice_line.sales_order,  sales_invoice_line.budget_prep_id,  sales_invoice_line.account
                    from sales_invoice_line 
                    where sales_order=:saleorder ";
                $stmt = $db->openConnection()->prepare($sql);
                $stmt->bindValue(':saleorder', $invoices);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $field = $row['sales_invoice_line_id'];
                return $field;
            }

            function get_on_salesinvoice_from_saleorder($saleorder) {
                ?>
            <table class="new_data_table">

                <?php
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select sales_order_line.sales_order_line_id,  sales_order_line.quantity,  sales_order_line.unit_cost,  sales_order_line.amount,  sales_order_line.entry_date,  sales_order_line.User,  sales_order_line.quotationid
                        ,p_budget_items.item_name  as item from sales_order_line 
                        join user on user.StaffID=sales_order_line.User 
                        join sales_quote_line on sales_quote_line.sales_quote_line_id=sales_order_line.quotationid
                       left join p_budget_items on p_budget_items.p_budget_items_id=sales_quote_line.item
                        where sales_order_line.sales_order_line_id=:saleorder";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":saleorder" => $saleorder));
                ?>
                <table class="new_data_table">
                    <?php while ($row = $stmt->fetch()) { ?>
                        <tr><td>quantity</td> <td>        <input type="text"autocomplete="off" name="txt_quantity" class="textbox" value="<?php echo $row['quantity']; ?>"/> </td></tr>
                        <tr><td>unit cost</td> <td>        <input type="text"autocomplete="off" name="txt_unit_cost"  class="textbox only_numbers" value="<?php echo number_format($row['unit_cost']); ?>"/> </td></tr>
                    <?php } ?>

                </table>
                <?php
            }

            function get_on_salesreceit_fromsaleinvoice($invoice) {

                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select  sales_invoice_line.sales_invoice_line_id,  sales_invoice_line.quantity,  sales_invoice_line.unit_cost,  sales_invoice_line.amount,  sales_invoice_line.entry_date,  sales_invoice_line.User,  sales_invoice_line.client,  sales_invoice_line.sales_order,  sales_invoice_line.budget_prep_id,  sales_invoice_line.account
                            from sales_invoice_line 
                             where sales_invoice_line.sales_invoice_line_id=:invoice";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":invoice" => $invoice));
                ?>
                <table class="new_data_table">

                    <?php while ($row = $stmt->fetch()) { ?>
                        <tr><td>quantity</td> <td>        <input type="text" class="textbox"autocomplete="off" name="txt_quantity" value="<?php echo $row['quantity']; ?>"/> </td></tr>
                        <tr><td>unit cost</td> <td>        <input type="text" class="textbox only_numbers" autocomplete="off" name="txt_unit_cost" value="<?php echo number_format($row['unit_cost']); ?>"/> </td></tr>


                    <?php } ?></table>
                <?php
            }

            function get_on_purchaseorder_from_request($request) {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select purchase_order_line.purchase_order_line_id,  purchase_order_line.entry_date,  purchase_order_line.User,  purchase_order_line.quantity,  purchase_order_line.unit_cost,  purchase_order_line.amount,  purchase_order_line.request,  purchase_order_line.measurement,  purchase_order_line.supplier
               from purchase_order_line
               where request=:request ";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":request" => $request));
                ?>
                <table class="new_data_table">
                    <thead><tr><td> purchase_order_line_id </td><td> entry_date </td><td> User </td><td> quantity </td><td> unit_cost </td><td> amount </td><td> request </td><td> measurement </td><td> supplier </td>
                        </tr></thead>

                    <?php foreach ($db->query($sql) as $row) { ?><tr> 
                            <td>        <?php echo $row['purchase_order_line_id']; ?> </td>
                            <td>        <?php echo $row['entry_date']; ?> </td>
                            <td>        <?php echo $row['User']; ?> </td>
                            <td>        <?php echo $row['quantity']; ?> </td>
                            <td>        <?php echo $row['unit_cost']; ?> </td>
                            <td>        <?php echo $row['amount']; ?> </td>
                            <td>        <?php echo $row['request']; ?> </td>
                            <td>        <?php echo $row['measurement']; ?> </td>
                            <td>        <?php echo $row['supplier']; ?> </td>

                        </tr>
                    <?php } ?></table>
                <?php
            }

            function get_on_purchaseinvpoice_from_purchaseorder($porder) {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select purchase_order_line.purchase_order_line_id,  purchase_order_line.entry_date,  purchase_order_line.User,  purchase_order_line.quantity,  purchase_order_line.unit_cost,  purchase_order_line.amount,  purchase_order_line.request,  purchase_order_line.measurement,  purchase_order_line.supplier
                            from purchase_order_line 
                            where purchase_order_line_id=:porder";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":porder" => $porder));
                ?>
                <table class="new_data_table">


                    <?php while ($row = $stmt->fetch()) { ?> 
                        <tr><td>quantity</td><td>  <input type="text"autocomplete="off" class="textbox" name="txt_quantity"  value="<?php echo $row['quantity']; ?>"/> </td></tr>
                        <tr><td>unit_cost</td><td> <input type="text"autocomplete="off" class="textbox" name="txt_unit_cost"  value="<?php echo $row['unit_cost']; ?>"/> </td></tr>
                        <tr><td>measurement</td><td> <input type="text"autocomplete="off" class="textbox" name="txt_measurement"  value="<?php echo $row['measurement']; ?>"/> </td></tr>

                    <?php } ?></table>
                <?php
            }

            function get_on_purchasereceit_from_p_invoice($invoice) {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql = "select purchase_invoice_line.purchase_invoice_line_id,  purchase_invoice_line.entry_date,  purchase_invoice_line.User,  purchase_invoice_line.quantity,  purchase_invoice_line.unit_cost,  purchase_invoice_line.amount,  purchase_invoice_line.purchase_order,  purchase_invoice_line.activity,  purchase_invoice_line.account,  purchase_invoice_line.supplier
                        from purchase_invoice_line where purchase_invoice_line_id=:invoice";
                $stmt = $db->prepare($sql);
                $stmt->execute(array(":invoice" => $invoice));
                ?>
                <table class="new_data_table">


                    <?php while ($row = $stmt->fetch()) { ?><tr> 
                        <tr><td>quantity</td> <td> <input type="text" name="txt_quantity"autocomplete="off" class="textbox" name="txt_name"  value="<?php echo $row['quantity']; ?>"/> </td></tr>
                        <tr><td>unit cost</td> <td> <input type="text" name="txt_unit_cost"autocomplete="off" class="textbox" name="txt_name"  value="<?php echo $row['unit_cost']; ?>"/> </td></tr>

                        </tr>
                    <?php } ?></table>
                <?php
            }

        }
        