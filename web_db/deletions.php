<?php

    require_once 'connection.php';

    class deletions {

        function deleteFrom_account($account_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM account where account_id =:account_id");
            $smt->bindValue(':account_id', $account_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_account_type($account_type_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM account_type where account_type_id =:account_type_id");
            $smt->bindValue(':account_type_id', $account_type_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_ledger_settings($ledger_settings_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM ledger_settings where ledger_settings_id =:ledger_settings_id");
            $smt->bindValue(':ledger_settings_id', $ledger_settings_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_bank($bank_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM bank where bank_id =:bank_id");
            $smt->bindValue(':bank_id', $bank_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_account_class($account_class_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM account_class where account_class_id =:account_class_id");
            $smt->bindValue(':account_class_id', $account_class_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_general_ledger_line($general_ledger_line_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM general_ledger_line where general_ledger_line_id =:general_ledger_line_id");
            $smt->bindValue(':general_ledger_line_id', $general_ledger_line_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_main_contra_account($main_contra_account_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM main_contra_account where main_contra_account_id =:main_contra_account_id");
            $smt->bindValue(':main_contra_account_id', $main_contra_account_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_measurement($measurement_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM measurement where measurement_id =:measurement_id");
            $smt->bindValue(':measurement_id', $measurement_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_journal_entry_line($journal_entry_line_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM journal_entry_line where journal_entry_line_id =:journal_entry_line_id");
            $smt->bindValue(':journal_entry_line_id', $journal_entry_line_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_tax($tax_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM tax where tax_id =:tax_id");
            $smt->bindValue(':tax_id', $tax_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_vendor($vendor_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM vendor where vendor_id =:vendor_id");
            $smt->bindValue(':vendor_id', $vendor_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_general_ledger_header($general_ledger_header_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM general_ledger_header where general_ledger_header_id =:general_ledger_header_id");
            $smt->bindValue(':general_ledger_header_id', $general_ledger_header_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_party($party_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM party where party_id =:party_id");
            $smt->bindValue(':party_id', $party_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_contact($contact_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM contact where contact_id =:contact_id");
            $smt->bindValue(':contact_id', $contact_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_customer($customer_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM customer where customer_id =:customer_id");
            $smt->bindValue(':customer_id', $customer_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_taxgroup($taxgroup_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM taxgroup where taxgroup_id =:taxgroup_id");
            $smt->bindValue(':taxgroup_id', $taxgroup_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_journal_entry_header($journal_entry_header_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM journal_entry_header where journal_entry_header_id =:journal_entry_header_id");
            $smt->bindValue(':journal_entry_header_id', $journal_entry_header_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_Payment_term($Payment_term_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM Payment_term where Payment_term_id =:Payment_term_id");
            $smt->bindValue(':Payment_term_id', $Payment_term_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_item($item_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM item where item_id =:item_id");
            $smt->bindValue(':item_id', $item_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_item_group($item_group_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM item_group where item_group_id =:item_group_id");
            $smt->bindValue(':item_group_id', $item_group_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_item_category($item_category_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM item_category where item_category_id =:item_category_id");
            $smt->bindValue(':item_category_id', $item_category_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_vendor_payment($vendor_payment_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM vendor_payment where vendor_payment_id =:vendor_payment_id");
            $smt->bindValue(':vendor_payment_id', $vendor_payment_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_sales_delivery_header($sales_delivery_header_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM sales_delivery_header where sales_delivery_header_id =:sales_delivery_header_id");
            $smt->bindValue(':sales_delivery_header_id', $sales_delivery_header_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_sale_delivery_line($sale_delivery_line_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM sale_delivery_line where sale_delivery_line_id =:sale_delivery_line_id");
            $smt->bindValue(':sale_delivery_line_id', $sale_delivery_line_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_sales_invoice_line($sales_invoice_line_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM sales_invoice_line where sales_invoice_line_id =:sales_invoice_line_id");
            $smt->bindValue(':sales_invoice_line_id', $sales_invoice_line_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_sales_invoice_header($sales_invoice_header_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM sales_invoice_header where sales_invoice_header_id =:sales_invoice_header_id");
            $smt->bindValue(':sales_invoice_header_id', $sales_invoice_header_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_sales_order_line($sales_order_line_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM sales_order_line where sales_order_line_id =:sales_order_line_id");
            $smt->bindValue(':sales_order_line_id', $sales_order_line_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_sales_order_header($sales_order_header_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM sales_order_header where sales_order_header_id =:sales_order_header_id");
            $smt->bindValue(':sales_order_header_id', $sales_order_header_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_sales_quote_line($sales_quote_line_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM sales_quote_line where sales_quote_line_id =:sales_quote_line_id");
            $smt->bindValue(':sales_quote_line_id', $sales_quote_line_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_sales_quote_header($sales_quote_header_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM sales_quote_header where sales_quote_header_id =:sales_quote_header_id");
            $smt->bindValue(':sales_quote_header_id', $sales_quote_header_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_sales_receit_header($sales_receit_header_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM sales_receit_header where sales_receit_header_id =:sales_receit_header_id");
            $smt->bindValue(':sales_receit_header_id', $sales_receit_header_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_purchase_invoice_header($purchase_invoice_header_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM purchase_invoice_header where purchase_invoice_header_id =:purchase_invoice_header_id");
            $smt->bindValue(':purchase_invoice_header_id', $purchase_invoice_header_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_purchase_invoice_line($purchase_invoice_line_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM purchase_invoice_line where purchase_invoice_line_id =:purchase_invoice_line_id");
            $smt->bindValue(':purchase_invoice_line_id', $purchase_invoice_line_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_purchase_order_header($purchase_order_header_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM purchase_order_header where purchase_order_header_id =:purchase_order_header_id");
            $smt->bindValue(':purchase_order_header_id', $purchase_order_header_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_purchase_order_line($purchase_order_line_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM purchase_order_line where purchase_order_line_id =:purchase_order_line_id");
            $smt->bindValue(':purchase_order_line_id', $purchase_order_line_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_purchase_receit_header($purchase_receit_header_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM purchase_receit_header where purchase_receit_header_id =:purchase_receit_header_id");
            $smt->bindValue(':purchase_receit_header_id', $purchase_receit_header_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_purchase_receit_line($purchase_receit_line_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM purchase_receit_line where purchase_receit_line_id =:purchase_receit_line_id");
            $smt->bindValue(':purchase_receit_line_id', $purchase_receit_line_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_Inventory_control_journal($Inventory_control_journal_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM Inventory_control_journal where Inventory_control_journal_id =:Inventory_control_journal_id");
            $smt->bindValue(':Inventory_control_journal_id', $Inventory_control_journal_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

        function deleteFrom_cheque($cheque_id) {
            $db = new dbconnection();
            $con = $db->openconnection();
            $smt = $con->prepare(" DELETE FROM cheque where cheque_id =:cheque_id");
            $smt->bindValue(':cheque_id', $cheque_id, PDO::PARAM_STR);
            $smt->execute();
            echo 'Record removed succefully';
        }

    }
    