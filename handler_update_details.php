<?php

    /*
     * To change this license header, choose License Headers in Project Properties.
     * To change this template file, choose Tools | Templates
     * and open the template in the editor.
     */

    /**
     * Description of Update_details
     *
     * Created by  SANGWA, June 13 2018
     */
    if (filter_has_var(INPUT_POST, 'update_activity_details')) {
        require_once '../web_db/updates.php';
        $obj = new updates();
        $unit = filter_input(INPUT_POST, 'update_activity_details');
        if ($unit == 'account') {
            $acc_type = filter_input(INPUT_POST, 'acc_type');
            $acc_class = filter_input(INPUT_POST, 'acc_class');
            $name = filter_input(INPUT_POST, 'name');
            $DrCrSide = filter_input(INPUT_POST, 'DrCrSide');
            $acc_code = filter_input(INPUT_POST, 'acc_code');
            $acc_desc = filter_input(INPUT_POST, 'acc_desc');
            $is_cash = filter_input(INPUT_POST, 'is_cash');
            $is_contra_acc = filter_input(INPUT_POST, 'is_contra_acc');
            $is_row_version = filter_input(INPUT_POST, 'is_row_version');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_account($acc_type, $acc_class, $name, $DrCrSide, $acc_code, $acc_desc, $is_cash, $is_contra_acc, $is_row_version, $id);
        }
        if ($unit == 'account_type') {
            $name = filter_input(INPUT_POST, 'name');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_account_type($name, $id);
        }
        if ($unit == 'ledger_settings') {
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_ledger_settings($id);
        }
        if ($unit == 'bank') {
            $account = filter_input(INPUT_POST, 'account');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_bank($account, $id);
        }
        if ($unit == 'account_class') {
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_account_class($id);
        }
        if ($unit == 'general_ledger_line') {
            $general_ledge_header = filter_input(INPUT_POST, 'general_ledge_header');
            $accountid = filter_input(INPUT_POST, 'accountid');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_general_ledger_line($general_ledge_header, $accountid, $id);
        }
        if ($unit == 'main_contra_account') {
            $main_contra_acc = filter_input(INPUT_POST, 'main_contra_acc');
            $related_contra_acc = filter_input(INPUT_POST, 'related_contra_acc');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_main_contra_account($main_contra_acc, $related_contra_acc, $id);
        }
        if ($unit == 'sales_receit_header') {
            $sales_invoice = filter_input(INPUT_POST, 'sales_invoice');
            $entry_date = filter_input(INPUT_POST, 'entry_date');
            $User = filter_input(INPUT_POST, 'User');
            $amount = filter_input(INPUT_POST, 'amount');
            $approved = filter_input(INPUT_POST, 'approved');
            $sales_invoice = filter_input(INPUT_POST, 'sales_invoice');
            $quantity = filter_input(INPUT_POST, 'quantity');
            $unit_cost = filter_input(INPUT_POST, 'unit_cost');
            $amount = filter_input(INPUT_POST, 'amount');
            $entry_date = filter_input(INPUT_POST, 'entry_date');
            $User = filter_input(INPUT_POST, 'User');
            $client = filter_input(INPUT_POST, 'client');
            $sale_order = filter_input(INPUT_POST, 'sale_order');
            $budget_prep = filter_input(INPUT_POST, 'budget_prep');
            $account = filter_input(INPUT_POST, 'account');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_sales_receit_header($sales_invoice, $entry_date, $User, $amount, $approved, $sales_invoice, $quantity, $unit_cost, $amount, $entry_date, $User, $client, $sale_order, $budget_prep, $account, $id);
        }
        if ($unit == 'measurement') {
            $code = filter_input(INPUT_POST, 'code');
            $description = filter_input(INPUT_POST, 'description');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_measurement($code, $description, $id);
        }
        if ($unit == 'journal_entry_line') {
            $accountid = filter_input(INPUT_POST, 'accountid');
            $dr_cr = filter_input(INPUT_POST, 'dr_cr');
            $amount = filter_input(INPUT_POST, 'amount');
            $memo = filter_input(INPUT_POST, 'memo');
            $journal_entry_header = filter_input(INPUT_POST, 'journal_entry_header');
            $entry_date = filter_input(INPUT_POST, 'entry_date');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_journal_entry_line($accountid, $dr_cr, $amount, $memo, $journal_entry_header, $entry_date, $id);
        }
        if ($unit == 'tax') {
            $sales_accid = filter_input(INPUT_POST, 'sales_accid');
            $purchase_accid = filter_input(INPUT_POST, 'purchase_accid');
            $tax_name = filter_input(INPUT_POST, 'tax_name');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_tax($sales_accid, $purchase_accid, $tax_name, $id);
        }
        if ($unit == 'vendor') {
            $venndor_number = filter_input(INPUT_POST, 'venndor_number');
            $party = filter_input(INPUT_POST, 'party');
            $payment_term = filter_input(INPUT_POST, 'payment_term');
            $tax_group = filter_input(INPUT_POST, 'tax_group');
            $purchase_acc = filter_input(INPUT_POST, 'purchase_acc');
            $pur_discount_accid = filter_input(INPUT_POST, 'pur_discount_accid');
            $primary_contact = filter_input(INPUT_POST, 'primary_contact');
            $acc_payable = filter_input(INPUT_POST, 'acc_payable');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_vendor($venndor_number, $party, $payment_term, $tax_group, $purchase_acc, $pur_discount_accid, $primary_contact, $acc_payable, $id);
        }
        if ($unit == 'general_ledger_header') {
            $date = filter_input(INPUT_POST, 'date');
            $doc_type = filter_input(INPUT_POST, 'doc_type');
            $desc = filter_input(INPUT_POST, 'desc');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_general_ledger_header($date, $doc_type, $desc, $id);
        }
        if ($unit == 'party') {
            $party_type = filter_input(INPUT_POST, 'party_type');
            $name = filter_input(INPUT_POST, 'name');
            $email = filter_input(INPUT_POST, 'email');
            $website = filter_input(INPUT_POST, 'website');
            $phone = filter_input(INPUT_POST, 'phone');
            $is_active = filter_input(INPUT_POST, 'is_active');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_party($party_type, $name, $email, $website, $phone, $is_active, $id);
        }
        if ($unit == 'contact') {
            $party = filter_input(INPUT_POST, 'party');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_contact($party, $id);
        }
        if ($unit == 'customer') {
            $party_id = filter_input(INPUT_POST, 'party_id');
            $contact = filter_input(INPUT_POST, 'contact');
            $number = filter_input(INPUT_POST, 'number');
            $tax_group = filter_input(INPUT_POST, 'tax_group');
            $payment_term = filter_input(INPUT_POST, 'payment_term');
            $sales_accid = filter_input(INPUT_POST, 'sales_accid');
            $acc_rec_accid = filter_input(INPUT_POST, 'acc_rec_accid');
            $promp_pyt_disc_accid = filter_input(INPUT_POST, 'promp_pyt_disc_accid');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_customer($party_id, $contact, $number, $tax_group, $payment_term, $sales_accid, $acc_rec_accid, $promp_pyt_disc_accid, $id);
        }
        if ($unit == 'taxgroup') {
            $description = filter_input(INPUT_POST, 'description');
            $tax_applied = filter_input(INPUT_POST, 'tax_applied');
            $is_active = filter_input(INPUT_POST, 'is_active');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_taxgroup($description, $tax_applied, $is_active, $id);
        }
        if ($unit == 'journal_entry_header') {
            $party = filter_input(INPUT_POST, 'party');
            $voucher_type = filter_input(INPUT_POST, 'voucher_type');
            $date = filter_input(INPUT_POST, 'date');
            $memo = filter_input(INPUT_POST, 'memo');
            $reference_number = filter_input(INPUT_POST, 'reference_number');
            $posted = filter_input(INPUT_POST, 'posted');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_journal_entry_header($party, $voucher_type, $date, $memo, $reference_number, $posted, $id);
        }
        if ($unit == 'Payment_term') {
            $description = filter_input(INPUT_POST, 'description');
            $payment_type = filter_input(INPUT_POST, 'payment_type');
            $due_after_days = filter_input(INPUT_POST, 'due_after_days');
            $is_active = filter_input(INPUT_POST, 'is_active');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_Payment_term($description, $payment_type, $due_after_days, $is_active, $id);
        }
        if ($unit == 'item') {
            $measurement = filter_input(INPUT_POST, 'measurement');
            $vendor = filter_input(INPUT_POST, 'vendor');
            $item_group = filter_input(INPUT_POST, 'item_group');
            $item_category = filter_input(INPUT_POST, 'item_category');
            $smallest_measurement = filter_input(INPUT_POST, 'smallest_measurement');
            $sale_measurement = filter_input(INPUT_POST, 'sale_measurement');
            $purchase_measurement = filter_input(INPUT_POST, 'purchase_measurement');
            $sales_account = filter_input(INPUT_POST, 'sales_account');
            $inventory_accid = filter_input(INPUT_POST, 'inventory_accid');
            $inventoty_adj_accid = filter_input(INPUT_POST, 'inventoty_adj_accid');
            $number = filter_input(INPUT_POST, 'number');
            $Code = filter_input(INPUT_POST, 'Code');
            $description = filter_input(INPUT_POST, 'description');
            $purchase_desc = filter_input(INPUT_POST, 'purchase_desc');
            $sale_desc = filter_input(INPUT_POST, 'sale_desc');
            $cost = filter_input(INPUT_POST, 'cost');
            $price = filter_input(INPUT_POST, 'price');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_item($measurement, $vendor, $item_group, $item_category, $smallest_measurement, $sale_measurement, $purchase_measurement, $sales_account, $inventory_accid, $inventoty_adj_accid, $number, $Code, $description, $purchase_desc, $sale_desc, $cost, $price, $id);
        }
        if ($unit == 'item_group') {
            $name = filter_input(INPUT_POST, 'name');
            $is_full_exempt = filter_input(INPUT_POST, 'is_full_exempt');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_item_group($name, $is_full_exempt, $id);
        }
        if ($unit == 'item_category') {
            $measurement = filter_input(INPUT_POST, 'measurement');
            $sales_accid = filter_input(INPUT_POST, 'sales_accid');
            $inventory_accid = filter_input(INPUT_POST, 'inventory_accid');
            $cost_good_sold_accid = filter_input(INPUT_POST, 'cost_good_sold_accid');
            $adjustment_accid = filter_input(INPUT_POST, 'adjustment_accid');
            $assembly_accid = filter_input(INPUT_POST, 'assembly_accid');
            $name = filter_input(INPUT_POST, 'name');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_item_category($measurement, $sales_accid, $inventory_accid, $cost_good_sold_accid, $adjustment_accid, $assembly_accid, $name, $id);
        }
        if ($unit == 'vendor_payment') {
            $vendor = filter_input(INPUT_POST, 'vendor');
            $gen_ledger_header = filter_input(INPUT_POST, 'gen_ledger_header');
            $pur_invoice_header = filter_input(INPUT_POST, 'pur_invoice_header');
            $number = filter_input(INPUT_POST, 'number');
            $date = filter_input(INPUT_POST, 'date');
            $amount = filter_input(INPUT_POST, 'amount');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_vendor_payment($vendor, $gen_ledger_header, $pur_invoice_header, $number, $date, $amount, $id);
        }
        if ($unit == 'sale_delivery_line') {
            $item = filter_input(INPUT_POST, 'item');
            $measurement = filter_input(INPUT_POST, 'measurement');
            $sales_delivery_header = filter_input(INPUT_POST, 'sales_delivery_header');
            $sales_invoice_line = filter_input(INPUT_POST, 'sales_invoice_line');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_sale_delivery_line($item, $measurement, $sales_delivery_header, $sales_invoice_line, $id);
        }
        if ($unit == 'sales_invoice_line') {
            $quantity = filter_input(INPUT_POST, 'quantity');
            $unit_cost = filter_input(INPUT_POST, 'unit_cost');
            $amount = filter_input(INPUT_POST, 'amount');
            $entry_date = filter_input(INPUT_POST, 'entry_date');
            $User = filter_input(INPUT_POST, 'User');
            $client = filter_input(INPUT_POST, 'client');
            $sales_order = filter_input(INPUT_POST, 'sales_order');
            $budget_prep_id = filter_input(INPUT_POST, 'budget_prep_id');
            $account = filter_input(INPUT_POST, 'account');
            $pay_method = filter_input(INPUT_POST, 'pay_method');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_sales_invoice_line($quantity, $unit_cost, $amount, $entry_date, $User, $client, $sales_order, $budget_prep_id, $account, $pay_method, $id);
        }
        if ($unit == 'sales_invoice_header') {
            $customer = filter_input(INPUT_POST, 'customer');
            $payment_term = filter_input(INPUT_POST, 'payment_term');
            $gen_ledger_header = filter_input(INPUT_POST, 'gen_ledger_header');
            $number = filter_input(INPUT_POST, 'number');
            $date = filter_input(INPUT_POST, 'date');
            $Shipping = filter_input(INPUT_POST, 'Shipping');
            $status = filter_input(INPUT_POST, 'status');
            $reference_no = filter_input(INPUT_POST, 'reference_no');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_sales_invoice_header($customer, $payment_term, $gen_ledger_header, $number, $date, $Shipping, $status, $reference_no, $id);
        }
        if ($unit == 'sales_order_line') {
            $quantity = filter_input(INPUT_POST, 'quantity');
            $unit_cost = filter_input(INPUT_POST, 'unit_cost');
            $amount = filter_input(INPUT_POST, 'amount');
            $entry_date = filter_input(INPUT_POST, 'entry_date');
            $User = filter_input(INPUT_POST, 'User');
            $quotationid = filter_input(INPUT_POST, 'quotationid');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_sales_order_line($quantity, $unit_cost, $amount, $entry_date, $User, $quotationid, $id);
        }
        if ($unit == 'sales_order_header') {
            $customer = filter_input(INPUT_POST, 'customer');
            $payment_term = filter_input(INPUT_POST, 'payment_term');
            $number = filter_input(INPUT_POST, 'number');
            $reference_number = filter_input(INPUT_POST, 'reference_number');
            $date = filter_input(INPUT_POST, 'date');
            $status = filter_input(INPUT_POST, 'status');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_sales_order_header($customer, $payment_term, $number, $reference_number, $date, $status, $id);
        }
        if ($unit == 'sales_quote_line') {
            $quantity = filter_input(INPUT_POST, 'quantity');
            $unit_cost = filter_input(INPUT_POST, 'unit_cost');
            $entry_date = filter_input(INPUT_POST, 'entry_date');
            $User = filter_input(INPUT_POST, 'User');
            $amount = filter_input(INPUT_POST, 'amount');
            $measurement = filter_input(INPUT_POST, 'measurement');
            $item = filter_input(INPUT_POST, 'item');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_sales_quote_line($quantity, $unit_cost, $entry_date, $User, $amount, $measurement, $item, $id);
        }
        if ($unit == 'sales_quote_header') {
            $customer = filter_input(INPUT_POST, 'customer');
            $date = filter_input(INPUT_POST, 'date');
            $payment_term = filter_input(INPUT_POST, 'payment_term');
            $reference_number = filter_input(INPUT_POST, 'reference_number');
            $number = filter_input(INPUT_POST, 'number');
            $status = filter_input(INPUT_POST, 'status');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_sales_quote_header($customer, $date, $payment_term, $reference_number, $number, $status, $id);
        }
        if ($unit == 'sales_receit_header') {
            $sales_invoice = filter_input(INPUT_POST, 'sales_invoice');
            $entry_date = filter_input(INPUT_POST, 'entry_date');
            $User = filter_input(INPUT_POST, 'User');
            $amount = filter_input(INPUT_POST, 'amount');
            $approved = filter_input(INPUT_POST, 'approved');
            $sales_invoice = filter_input(INPUT_POST, 'sales_invoice');
            $quantity = filter_input(INPUT_POST, 'quantity');
            $unit_cost = filter_input(INPUT_POST, 'unit_cost');
            $amount = filter_input(INPUT_POST, 'amount');
            $entry_date = filter_input(INPUT_POST, 'entry_date');
            $User = filter_input(INPUT_POST, 'User');
            $client = filter_input(INPUT_POST, 'client');
            $sale_order = filter_input(INPUT_POST, 'sale_order');
            $budget_prep = filter_input(INPUT_POST, 'budget_prep');
            $account = filter_input(INPUT_POST, 'account');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_sales_receit_header($sales_invoice, $entry_date, $User, $amount, $approved, $sales_invoice, $quantity, $unit_cost, $amount, $entry_date, $User, $client, $sale_order, $budget_prep, $account, $id);
        }
        if ($unit == 'purchase_invoice_header') {
            $inv_control_journal = filter_input(INPUT_POST, 'inv_control_journal');
            $item = filter_input(INPUT_POST, 'item');
            $measurement = filter_input(INPUT_POST, 'measurement');
            $quantity = filter_input(INPUT_POST, 'quantity');
            $receieved_qusntinty = filter_input(INPUT_POST, 'receieved_qusntinty');
            $cost = filter_input(INPUT_POST, 'cost');
            $discount = filter_input(INPUT_POST, 'discount');
            $purchase_order_line = filter_input(INPUT_POST, 'purchase_order_line');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_purchase_invoice_header($inv_control_journal, $item, $measurement, $quantity, $receieved_qusntinty, $cost, $discount, $purchase_order_line, $id);
        }
        if ($unit == 'purchase_invoice_line') {
            $entry_date = filter_input(INPUT_POST, 'entry_date');
            $User = filter_input(INPUT_POST, 'User');
            $quantity = filter_input(INPUT_POST, 'quantity');
            $unit_cost = filter_input(INPUT_POST, 'unit_cost');
            $amount = filter_input(INPUT_POST, 'amount');
            $purchase_order = filter_input(INPUT_POST, 'purchase_order');
            $activity = filter_input(INPUT_POST, 'activity');
            $account = filter_input(INPUT_POST, 'account');
            $supplier = filter_input(INPUT_POST, 'supplier');
            $pay_method = filter_input(INPUT_POST, 'pay_method');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_purchase_invoice_line($entry_date, $User, $quantity, $unit_cost, $amount, $purchase_order, $activity, $account, $supplier, $pay_method, $id);
        }
        if ($unit == 'purchase_order_header') {
            $vendor = filter_input(INPUT_POST, 'vendor');
            $gen_ledger_header = filter_input(INPUT_POST, 'gen_ledger_header');
            $date = filter_input(INPUT_POST, 'date');
            $number = filter_input(INPUT_POST, 'number');
            $vendor_invoice_number = filter_input(INPUT_POST, 'vendor_invoice_number');
            $description = filter_input(INPUT_POST, 'description');
            $payment_term = filter_input(INPUT_POST, 'payment_term');
            $reference_number = filter_input(INPUT_POST, 'reference_number');
            $status = filter_input(INPUT_POST, 'status');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_purchase_order_header($vendor, $gen_ledger_header, $date, $number, $vendor_invoice_number, $description, $payment_term, $reference_number, $status, $id);
        }
        if ($unit == 'purchase_order_line') {
            $entry_date = filter_input(INPUT_POST, 'entry_date');
            $User = filter_input(INPUT_POST, 'User');
            $quantity = filter_input(INPUT_POST, 'quantity');
            $unit_cost = filter_input(INPUT_POST, 'unit_cost');
            $amount = filter_input(INPUT_POST, 'amount');
            $request = filter_input(INPUT_POST, 'request');
            $measurement = filter_input(INPUT_POST, 'measurement');
            $supplier = filter_input(INPUT_POST, 'supplier');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_purchase_order_line($entry_date, $User, $quantity, $unit_cost, $amount, $request, $measurement, $supplier, $id);
        }
        if ($unit == 'purchase_receit_header') {
            $gen_ledger_header = filter_input(INPUT_POST, 'gen_ledger_header');
            $date = filter_input(INPUT_POST, 'date');
            $status = filter_input(INPUT_POST, 'status');
            $number = filter_input(INPUT_POST, 'number');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_purchase_receit_header($gen_ledger_header, $date, $status, $number, $id);
        }
        if ($unit == 'purchase_receit_line') {
            $entry_date = filter_input(INPUT_POST, 'entry_date');
            $User = filter_input(INPUT_POST, 'User');
            $quantity = filter_input(INPUT_POST, 'quantity');
            $unit_cost = filter_input(INPUT_POST, 'unit_cost');
            $amount = filter_input(INPUT_POST, 'amount');
            $purchase_invoice = filter_input(INPUT_POST, 'purchase_invoice');
            $activity = filter_input(INPUT_POST, 'activity');
            $account = filter_input(INPUT_POST, 'account');
            $suplier = filter_input(INPUT_POST, 'suplier');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_purchase_receit_line($entry_date, $User, $quantity, $unit_cost, $amount, $purchase_invoice, $activity, $account, $suplier, $id);
        }
        if ($unit == 'Inventory_control_journal') {
            $measurement = filter_input(INPUT_POST, 'measurement');
            $item = filter_input(INPUT_POST, 'item');
            $doc_type = filter_input(INPUT_POST, 'doc_type');
            $In_qty = filter_input(INPUT_POST, 'In_qty');
            $Out_qty = filter_input(INPUT_POST, 'Out_qty');
            $date = filter_input(INPUT_POST, 'date');
            $total_cost = filter_input(INPUT_POST, 'total_cost');
            $tot_amount = filter_input(INPUT_POST, 'tot_amount');
            $is_reverse = filter_input(INPUT_POST, 'is_reverse');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_Inventory_control_journal($measurement, $item, $doc_type, $In_qty, $Out_qty, $date, $total_cost, $tot_amount, $is_reverse, $id);
        }
        if ($unit == 'user') {
            $LastName = filter_input(INPUT_POST, 'LastName');
            $FirstName = filter_input(INPUT_POST, 'FirstName');
            $UserName = filter_input(INPUT_POST, 'UserName');
            $EmailAddress = filter_input(INPUT_POST, 'EmailAddress');
            $IsActive = filter_input(INPUT_POST, 'IsActive');
            $Password = filter_input(INPUT_POST, 'Password');
            $Roleid = filter_input(INPUT_POST, 'Roleid');
            $position_depart = filter_input(INPUT_POST, 'position_depart');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_user($LastName, $FirstName, $UserName, $EmailAddress, $IsActive, $Password, $Roleid, $position_depart, $id);
        }
        if ($unit == 'role') {
            $name = filter_input(INPUT_POST, 'name');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_role($name, $id);
        }
        if ($unit == 'staff_positions') {
            $name = filter_input(INPUT_POST, 'name');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_staff_positions($name, $id);
        }
        if ($unit == 'request') {
            $entry_date = filter_input(INPUT_POST, 'entry_date');
            $User = filter_input(INPUT_POST, 'User');
            $item = filter_input(INPUT_POST, 'item');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_request($entry_date, $User, $item, $id);
        }
        if ($unit == 'supplier') {
            $party = filter_input(INPUT_POST, 'party');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_supplier($party, $id);
        }
        if ($unit == 'client') {
            $party = filter_input(INPUT_POST, 'party');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_client($party, $id);
        }
        if ($unit == 'p_budget') {
            $entry_date = filter_input(INPUT_POST, 'entry_date');
            $User = filter_input(INPUT_POST, 'User');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_p_budget($entry_date, $User, $id);
        }
        if ($unit == 'p_activity') {
            $project = filter_input(INPUT_POST, 'project');
            $name = filter_input(INPUT_POST, 'name');
            $fisc_year = filter_input(INPUT_POST, 'fisc_year');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_p_activity($project, $name, $fisc_year, $id);
        }
        if ($unit == 'Main_Request') {
            $entry_date = filter_input(INPUT_POST, 'entry_date');
            $User = filter_input(INPUT_POST, 'User');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_Main_Request($entry_date, $User, $id);
        }
        if ($unit == 'p_budget_prep') {
            $project_type = filter_input(INPUT_POST, 'project_type');
            $user = filter_input(INPUT_POST, 'user');
            $entry_date = filter_input(INPUT_POST, 'entry_date');
            $budget_type = filter_input(INPUT_POST, 'budget_type');
            $activity_desc = filter_input(INPUT_POST, 'activity_desc');
            $name = filter_input(INPUT_POST, 'name');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_p_budget_prep($project_type, $user, $entry_date, $budget_type, $activity_desc, $name, $id);
        }
        if ($unit == 'payment_voucher') {
            $item = filter_input(INPUT_POST, 'item');
            $entry_date = filter_input(INPUT_POST, 'entry_date');
            $User = filter_input(INPUT_POST, 'User');
            $quantity = filter_input(INPUT_POST, 'quantity');
            $unit_cost = filter_input(INPUT_POST, 'unit_cost');
            $amount = filter_input(INPUT_POST, 'amount');
            $budget_prep = filter_input(INPUT_POST, 'budget_prep');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_payment_voucher($item, $entry_date, $User, $quantity, $unit_cost, $amount, $budget_prep, $id);
        }
        if ($unit == 'delete_update_permission') {
            $user = filter_input(INPUT_POST, 'user');
            $permission = filter_input(INPUT_POST, 'permission');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_delete_update_permission($user, $permission, $id);
        }
        if ($unit == 'stock_taking') {
            $item = filter_input(INPUT_POST, 'item');
            $quantity = filter_input(INPUT_POST, 'quantity');
            $entry_date = filter_input(INPUT_POST, 'entry_date');
            $User = filter_input(INPUT_POST, 'User');
            $available_quantity = filter_input(INPUT_POST, 'available_quantity');
            $in_or_out = filter_input(INPUT_POST, 'in_or_out');
            $measurement = filter_input(INPUT_POST, 'measurement');
            $id = filter_input(INPUT_POST, 'clicked_table_id');
            $obj->update_stock_taking($item, $quantity, $entry_date, $User, $available_quantity, $in_or_out, $measurement, $id);
        }
    }

    if (filter_has_var(INPUT_POST, 'view_request')) {
        require_once '../web_db/Details_by_click.php';
        $obj = new Details_by_click();
        $view_request = filter_input(INPUT_POST, 'view_request');
        echo $obj->get_request_details($view_request);
    }
    