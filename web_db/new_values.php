<?php

    class new_values {

        function new_account($acc_type, $acc_class, $name, $DrCrSide, $acc_code, $acc_desc, $is_cash, $is_contra_acc, $has_parent, $book_section) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into account values(:account_id, :acc_type,  :acc_class,  :name,  :DrCrSide,  :acc_code,  :acc_desc,  :is_cash,  :is_contra_acc,  :has_parent, :book_section)");
                $stm->execute(array(':account_id' => 0, ':acc_type' => $acc_type, ':acc_class' => $acc_class, ':name' => $name, ':DrCrSide' => $DrCrSide, ':acc_code' => $acc_code, ':acc_desc' => $acc_desc, ':is_cash' => $is_cash, ':is_contra_acc' => $is_contra_acc, ':has_parent' => $has_parent, ':book_section' => $book_section));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_account_type() {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into account_type values(:account_type_id,)");
                $stm->execute(array(':account_type_id' => 0,
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_ledger_settings() {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into ledger_settings values(:ledger_settings_id,)");
                $stm->execute(array(':ledger_settings_id' => 0,
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_bank($account, $name) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into bank values(:bank_id, :account,:name)");
                $stm->execute(array(':bank_id' => 0, ':account' => $account, ':name' => $name));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_account_class() {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into account_class values(:account_class_id,)");
                $stm->execute(array(':account_class_id' => 0,
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_general_ledger_line($general_ledge_header, $accountid) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into general_ledger_line values(:general_ledger_line_id, :general_ledge_header,  :accountid)");
                $stm->execute(array(':general_ledger_line_id' => 0, ':general_ledge_header' => $general_ledge_header, ':accountid' => $accountid
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_main_contra_account($main_contra_acc, $related_contra_acc, $order, $self_acc) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into main_contra_account values(:main_contra_account_id, :main_acc,  :sub_acc, :acc_order, :self_acc)");
                $stm->execute(array(':main_contra_account_id' => 0, ':main_acc' => $main_contra_acc, ':sub_acc' => $related_contra_acc, ":acc_order" => $order, ":self_acc" => $self_acc));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_sales_receit_header($sales_invoice, $entry_date, $User, $amount, $approved, $quantity, $unit_cost, $client, $budget_prep, $account) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into sales_receit_header values(:sales_receit_header_id, :sales_invoice,:entry_date,                  :User,            :amount,              :approved,                :quantity,                :unit_cost,                 :client,              :budget_prep,                                :account)");
                $stm->execute(array(':sales_receit_header_id' => 0, ':sales_invoice' => $sales_invoice, ':entry_date' => $entry_date, ':User' => $User, ':amount' => $amount, ':approved' => $approved, ':quantity' => $quantity, ':unit_cost' => $unit_cost, ':client' => $client, ':budget_prep' => $budget_prep, ':account' => $account));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_measurement($code, $description) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into measurement values(:measurement_id, :code,  :description)");
                $stm->execute(array(':measurement_id' => 0, ':code' => $code, ':description' => $description
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_journal_entry_line($accountid, $dr_cr, $amount, $memo, $journal_entry_header, $entry_date, $category, $status, $activity, $user) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into journal_entry_line values(:journal_entry_line_id, :accountid,  :dr_cr,  :amount,  :memo,  :journal_entry_header, :entry_date,:category, :status, :activity, :user)");
                $stm->execute(array(':journal_entry_line_id' => 0, ':accountid' => $accountid, ':dr_cr' => $dr_cr, ':amount' => $amount, ':memo' => $memo, ':journal_entry_header' => $journal_entry_header, ':entry_date' => $entry_date, 'category' => $category, ':status' => $status, ':activity' => $activity, ':user' => $user));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_journal_entry_line_by_acc($accountid, $dr_cr, $amount, $memo, $journal_entry_header, $entry_date, $category, $status) {


            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $sql_check = "";
                $stmt_check = $db->prepare($sql_check);
                $stmt_check->execute();

                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into journal_entry_line values(:journal_entry_line_id, :accountid,  :dr_cr,  :amount,  :memo,  :journal_entry_header, :entry_date,:category, :status)");
                $stm->execute(array(':journal_entry_line_id' => 0, ':accountid' => $accountid, ':dr_cr' => $dr_cr, ':amount' => $amount, ':memo' => $memo, ':journal_entry_header' => $journal_entry_header, ':entry_date' => $entry_date, 'category' => $category, ':status' => $status));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_tax($sales_accid, $purchase_accid, $tax_name, $entry_date, $User) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into tax values(:tax_id, :sales_accid,  :purchase_accid,  :tax_name,  :entry_date,  :User)");
                $stm->execute(array(':tax_id' => 0, ':sales_accid' => $sales_accid, ':purchase_accid' => $purchase_accid, ':tax_name' => $tax_name, ':entry_date' => $entry_date, ':User' => $User
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_vendor($venndor_number, $party, $payment_term, $tax_group, $purchase_acc, $pur_discount_accid, $primary_contact, $acc_payble) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into vendor values(:vendor_id, :venndor_number,  :party,  :payment_term,  :tax_group,  :purchase_acc,  :pur_discount_accid,  :primary_contact,  :acc_payble)");
                $stm->execute(array(':vendor_id' => 0, ':venndor_number' => $venndor_number, ':party' => $party, ':payment_term' => $payment_term, ':tax_group' => $tax_group, ':purchase_acc' => $purchase_acc, ':pur_discount_accid' => $pur_discount_accid, ':primary_contact' => $primary_contact, ':acc_payble' => $acc_payble
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_general_ledger_header($date, $doc_type, $desc) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into general_ledger_header values(:general_ledger_header_id, :date,  :doc_type,  :desc)");
                $stm->execute(array(':general_ledger_header_id' => 0, ':date' => $date, ':doc_type' => $doc_type, ':desc' => $desc
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_party($party_type, $name, $email, $website, $phone, $is_active) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into party values(:party_id, :party_type,  :name,  :email,  :website,  :phone,  :is_active)");
                $stm->execute(array(':party_id' => 0, ':party_type' => $party_type, ':name' => $name, ':email' => $email, ':website' => $website, ':phone' => $phone, ':is_active' => $is_active
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_contact($party) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into contact values(:contact_id, :party)");
                $stm->execute(array(':contact_id' => 0, ':party' => $party
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_customer($party_id, $contact, $number, $tax_group, $payment_term, $sales_accid, $acc_rec_accid, $promp_pyt_disc_accid) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into customer values(:customer_id, :party_id,  :contact,  :number,  :tax_group,  :payment_term,  :sales_accid,  :acc_rec_accid,  :promp_pyt_disc_accid)");
                $stm->execute(array(':customer_id' => 0, ':party_id' => $party_id, ':contact' => $contact, ':number' => $number, ':tax_group' => $tax_group, ':payment_term' => $payment_term, ':sales_accid' => $sales_accid, ':acc_rec_accid' => $acc_rec_accid, ':promp_pyt_disc_accid' => $promp_pyt_disc_accid
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_taxgroup($description, $tax_applied, $is_active, $pur_sale) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into taxgroup values(:taxgroup_id, :description,  :tax_applied,  :is_active, :pur_sale)");
                $stm->execute(array(':taxgroup_id' => 0, ':description' => $description, ':tax_applied' => $tax_applied, ':is_active' => $is_active, ':pur_sale' => $pur_sale
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_journal_entry_header($party, $voucher_type, $date, $memo, $reference_number, $posted) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into journal_entry_header values(:journal_entry_header_id, :party,  :voucher_type,  :date,  :memo,  :reference_number,  :posted)");
                $stm->execute(array(':journal_entry_header_id' => 0, ':party' => $party, ':voucher_type' => $voucher_type, ':date' => $date, ':memo' => $memo, ':reference_number' => $reference_number, ':posted' => $posted
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_Payment_term($description, $payment_type, $due_after_days, $is_active) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into Payment_term values(:Payment_term_id, :description,  :payment_type,  :due_after_days,  :is_active)");
                $stm->execute(array(':Payment_term_id' => 0, ':description' => $description, ':payment_type' => $payment_type, ':due_after_days' => $due_after_days, ':is_active' => $is_active
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_item($measurement, $vendor, $item_group, $item_category, $smallest_measurement, $sale_measurement, $purchase_measurement, $sales_account, $inventory_accid, $inventoty_adj_accid, $number, $Code, $description, $purchase_desc, $sale_desc, $cost, $price) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into item values(:item_id, :measurement,  :vendor,  :item_group,  :item_category,  :smallest_measurement,  :sale_measurement,  :purchase_measurement,  :sales_account,  :inventory_accid,  :inventoty_adj_accid,  :number,  :Code,  :description,  :purchase_desc,  :sale_desc,  :cost,  :price)");
                $stm->execute(array(':item_id' => 0, ':measurement' => $measurement, ':vendor' => $vendor, ':item_group' => $item_group, ':item_category' => $item_category, ':smallest_measurement' => $smallest_measurement, ':sale_measurement' => $sale_measurement, ':purchase_measurement' => $purchase_measurement, ':sales_account' => $sales_account, ':inventory_accid' => $inventory_accid, ':inventoty_adj_accid' => $inventoty_adj_accid, ':number' => $number, ':Code' => $Code, ':description' => $description, ':purchase_desc' => $purchase_desc, ':sale_desc' => $sale_desc, ':cost' => $cost, ':price' => $price
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_item_group($name, $is_full_exempt) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into item_group values(:item_group_id, :name,  :is_full_exempt)");
                $stm->execute(array(':item_group_id' => 0, ':name' => $name, ':is_full_exempt' => $is_full_exempt
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_item_category($measurement, $sales_accid, $inventory_accid, $cost_good_sold_accid, $adjustment_accid, $assembly_accid, $name) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into item_category values(:item_category_id, :measurement,  :sales_accid,  :inventory_accid,  :cost_good_sold_accid,  :adjustment_accid,  :assembly_accid,  :name)");
                $stm->execute(array(':item_category_id' => 0, ':measurement' => $measurement, ':sales_accid' => $sales_accid, ':inventory_accid' => $inventory_accid, ':cost_good_sold_accid' => $cost_good_sold_accid, ':adjustment_accid' => $adjustment_accid, ':assembly_accid' => $assembly_accid, ':name' => $name
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_vendor_payment($vendor, $gen_ledger_header, $pur_invoice_header, $number, $date, $amount) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into vendor_payment values(:vendor_payment_id, :vendor,  :gen_ledger_header,  :pur_invoice_header,  :number,  :date,  :amount)");
                $stm->execute(array(':vendor_payment_id' => 0, ':vendor' => $vendor, ':gen_ledger_header' => $gen_ledger_header, ':pur_invoice_header' => $pur_invoice_header, ':number' => $number, ':date' => $date, ':amount' => $amount
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_sales_delivery_header($customer, $gen_ledger_header, $payment_term) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into sales_delivery_header values(:sales_delivery_header_id, :customer,  :gen_ledger_header,  :payment_term)");
                $stm->execute(array(':sales_delivery_header_id' => 0, ':customer' => $customer, ':gen_ledger_header' => $gen_ledger_header, ':payment_term' => $payment_term
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_sale_delivery_line($item, $measurement, $sales_delivery_header, $sales_invoice_line) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into sale_delivery_line values(:sale_delivery_line_id, :item,  :measurement,  :sales_delivery_header,  :sales_invoice_line)");
                $stm->execute(array(':sale_delivery_line_id' => 0, ':item' => $item, ':measurement' => $measurement, ':sales_delivery_header' => $sales_delivery_header, ':sales_invoice_line' => $sales_invoice_line
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_sales_invoice_line($quantity, $unit_cost, $amount, $entry_date, $User, $client, $sales_order, $budget_prep_id, $account, $method, $tax_inclusive, $acc_credit) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into sales_invoice_line values(:sales_invoice_line_id, :quantity,  :unit_cost,  :amount,  :entry_date,  :User,  :client,  :sales_order,  :budget_prep_id,:acc_debit,:pay_method, :tax_inclusive,:acc_credit)");
                $stm->execute(array(':sales_invoice_line_id' => 0, ':quantity' => $quantity, ':unit_cost' => $unit_cost, ':amount' => $amount, ':entry_date' => $entry_date, ':User' => $User, ':client' => $client, ':sales_order' => $sales_order, ':budget_prep_id' => $budget_prep_id, ':acc_debit' => $account, ':pay_method' => $method, ':tax_inclusive' => $tax_inclusive, ':acc_credit' => $acc_credit));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e->getMessage();
            }
        }

        function new_sales_invoice_header($customer, $payment_term, $gen_ledger_header, $number, $date, $Shipping, $status, $reference_no) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into sales_invoice_header values(:sales_invoice_header_id, :customer,  :payment_term,  :gen_ledger_header,  :number,  :date,  :Shipping,  :status,  :reference_no)");
                $stm->execute(array(':sales_invoice_header_id' => 0, ':customer' => $customer, ':payment_term' => $payment_term, ':gen_ledger_header' => $gen_ledger_header, ':number' => $number, ':date' => $date, ':Shipping' => $Shipping, ':status' => $status, ':reference_no' => $reference_no
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_sales_order_line($sales_order_header, $item, $measurement, $quantity, $discount, $amount) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into sales_order_line values(:sales_order_line_id, :sales_order_header,  :item,  :measurement,  :quantity,  :discount,  :amount)");
                $stm->execute(array(':sales_order_line_id' => 0, ':sales_order_header' => $sales_order_header, ':item' => $item, ':measurement' => $measurement, ':quantity' => $quantity, ':discount' => $discount, ':amount' => $amount
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_sales_order_header($customer, $payment_term, $number, $reference_number, $date, $status) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into sales_order_header values(:sales_order_header_id, :customer,  :payment_term,  :number,  :reference_number,  :date,  :status)");
                $stm->execute(array(':sales_order_header_id' => 0, ':customer' => $customer, ':payment_term' => $payment_term, ':number' => $number, ':reference_number' => $reference_number, ':date' => $date, ':status' => $status
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_sales_quote_line($quantity, $unit_cost, $entry_date, $User, $amount, $measurement, $item) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into sales_quote_line values(:sales_quote_line_id, :quantity,  :unit_cost,  :entry_date,  :User,  :amount,  :measurement,  :item)");
                $stm->execute(array(':sales_quote_line_id' => 0, ':quantity' => $quantity, ':unit_cost' => $unit_cost, ':entry_date' => $entry_date, ':User' => $User, ':amount' => $amount, ':measurement' => $measurement, ':item' => $item
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_sales_quote_header($customer, $date, $payment_term, $reference_number, $number, $status) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into sales_quote_header values(:sales_quote_header_id, :customer,  :date,  :payment_term,  :reference_number,  :number,  :status)");
                $stm->execute(array(':sales_quote_header_id' => 0, ':customer' => $customer, ':date' => $date, ':payment_term' => $payment_term, ':reference_number' => $reference_number, ':number' => $number, ':status' => $status
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_purchase_invoice_header($inv_control_journal, $item, $measurement, $quantity, $receieved_qusntinty, $cost, $discount, $purchase_order_line) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into purchase_invoice_header values(:purchase_invoice_header_id, :inv_control_journal,  :item,  :measurement,  :quantity,  :receieved_qusntinty,  :cost,  :discount,  :purchase_order_line)");
                $stm->execute(array(':purchase_invoice_header_id' => 0, ':inv_control_journal' => $inv_control_journal, ':item' => $item, ':measurement' => $measurement, ':quantity' => $quantity, ':receieved_qusntinty' => $receieved_qusntinty, ':cost' => $cost, ':discount' => $discount, ':purchase_order_line' => $purchase_order_line
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_purchase_invoice_line($entry_date, $User, $quantity, $unit_cost, $amount, $purchase_order, $activity, $account, $suplier, $tax_inclusive, $acc_credit) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into purchase_invoice_line values(:purchase_invoice_line_id, :entry_date,  :User,  :quantity,  :unit_cost,  :amount,  :purchase_order,  :activity,:acc_debit,:supplier,:tax_inclusive,:acc_credit)");
                $stm->execute(array(':purchase_invoice_line_id' => 0, ':entry_date' => $entry_date, ':User' => $User, ':quantity' => $quantity, ':unit_cost' => $unit_cost, ':amount' => $amount, ':purchase_order' => $purchase_order, ':activity' => $activity, ':acc_debit' => $account, ':supplier' => $suplier, ':tax_inclusive' => $tax_inclusive, ':acc_credit' => $acc_credit));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_purchase_order_header($vendor, $gen_ledger_header, $date, $number, $vendor_invoice_number, $description, $payment_term, $reference_number, $status) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into purchase_order_header values(:purchase_order_header_id, :vendor,  :gen_ledger_header,  :date,  :number,  :vendor_invoice_number,  :description,  :payment_term,  :reference_number,  :status)");
                $stm->execute(array(':purchase_order_header_id' => 0, ':vendor' => $vendor, ':gen_ledger_header' => $gen_ledger_header, ':date' => $date, ':number' => $number, ':vendor_invoice_number' => $vendor_invoice_number, ':description' => $description, ':payment_term' => $payment_term, ':reference_number' => $reference_number, ':status' => $status
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_purchase_order_line($entry_date, $User, $quantity, $unit_cost, $amount, $request, $measurement, $supplier) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into purchase_order_line values(:purchase_order_line_id, :entry_date,  :User,  :quantity,  :unit_cost,  :amount,  :request,  :measurement,  :supplier)");
                $stm->execute(array(':purchase_order_line_id' => 0, ':entry_date' => $entry_date, ':User' => $User, ':quantity' => $quantity, ':unit_cost' => $unit_cost, ':amount' => $amount, ':request' => $request, ':measurement' => $measurement, ':supplier' => $supplier
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_purchase_receit_header($gen_ledger_header, $date, $status, $number) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into purchase_receit_header values(:purchase_receit_header_id, :gen_ledger_header,  :date,  :status,  :number)");
                $stm->execute(array(':purchase_receit_header_id' => 0, ':gen_ledger_header' => $gen_ledger_header, ':date' => $date, ':status' => $status, ':number' => $number
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_purchase_receit_line($entry_date, $User, $quantity, $unit_cost, $amount, $purchase_invoice, $activity, $account, $suplier) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into purchase_receit_line values(:purchase_receit_line_id, :entry_date,  :User,  :quantity,  :unit_cost,  :amount,  :purchase_invoice,  :activity,  :account,  :suplier)");
                $stm->execute(array(':purchase_receit_line_id' => 0, ':entry_date' => $entry_date, ':User' => $User, ':quantity' => $quantity, ':unit_cost' => $unit_cost, ':amount' => $amount, ':purchase_invoice' => $purchase_invoice, ':activity' => $activity, ':account' => $account, ':suplier' => $suplier));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e->getMessage();
            }
        }

        function new_Inventory_control_journal($measurement, $item, $doc_type, $In_qty, $Out_qty, $date, $total_cost, $tot_amount, $is_reverse) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into Inventory_control_journal values(:Inventory_control_journal_id, :measurement,  :item,  :doc_type,  :In_qty,  :Out_qty,  :date,  :total_cost,  :tot_amount,  :is_reverse)");
                $stm->execute(array(':Inventory_control_journal_id' => 0, ':measurement' => $measurement, ':item' => $item, ':doc_type' => $doc_type, ':In_qty' => $In_qty, ':Out_qty' => $Out_qty, ':date' => $date, ':total_cost' => $total_cost, ':tot_amount' => $tot_amount, ':is_reverse' => $is_reverse
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_delete_update_permission($user, $permission) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into delete_update_permission values(:delete_update_permission_id, :user,  :permission)");
                $stm->execute(array(':delete_update_permission_id' => 0, ':user' => $user, ':permission' => $permission
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        // <editor-fold defaultstate="collapsed" desc="------projects---------">
        // 

        function new_account_category($name) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into account_category values(:account_category_id, :name)");
                $stm->execute(array(':account_category_id' => 0, ':name' => $name
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_profile($image, $name, $surname) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into profile values(:profile_id, :image,  :name,  :surname )");
                $stm->execute(array(':profile_id' => 0, ':image' => $image, ':name' => $name, ':surname' => $surname
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_image($path) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into image values(:image_id, :path)");
                $stm->execute(array(':image_id' => 0, ':path' => $path
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_budget($description, $entry_date, $is_active, $status, $activity, $unit_cost, $qty, $measurement, $created_by, $item) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_budget values(:p_budget_id, :description,  :entry_date,  :is_active,  :status,  :activity,  :unit_cost,  :qty,  :measurement,  :created_by,  :item)");
                $stm->execute(array(':p_budget_id' => 0, ':description' => $description, ':entry_date' => $entry_date, ':is_active' => $is_active, ':status' => $status, ':activity' => $activity, ':unit_cost' => $unit_cost, ':qty' => $qty, ':measurement' => $measurement, ':created_by' => $created_by, ':item' => $item
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_budget_items($item_name, $description, $created_by, $entry_date, $chart_account, $type) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_budget_items values(:p_budget_items_id, :item_name,  :description,  :created_by,  :entry_date,  :chart_account, :type)");
                $stm->execute(array(':p_budget_items_id' => 0, ':item_name' => $item_name, ':description' => $description, ':created_by' => $created_by, ':entry_date' => $entry_date, ':chart_account' => $chart_account, ':type' => $type));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e->getMessage();
            }
        }

        function new_p_items_expenses($item, $description, $amount, $date, $finalized, $is_deleted, $account) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_items_expenses values(:p_items_expenses_id, :item,  :description,  :amount,  :date,  :finalized,  :is_deleted,  :account )");
                $stm->execute(array(':p_items_expenses_id' => 0, ':item' => $item, ':description' => $description, ':amount' => $amount, ':date' => $date, ':finalized' => $finalized, ':is_deleted' => $is_deleted, ':account' => $account));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_items_type() {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_items_type values(:p_items_type_id,)");
                $stm->execute(array(':p_items_type_id' => 0,
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_chart_account($acc_no, $name, $status, $created_by, $entry_date) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_chart_account values(:p_chart_account_id, :acc_no,  :name,  :status,  :created_by,  :entry_date)");
                $stm->execute(array(':p_chart_account_id' => 0, ':acc_no' => $acc_no, ':name' => $name, ':status' => $status, ':created_by' => $created_by, ':entry_date' => $entry_date
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_department($department_name, $description, $abbreviation, $contact_person, $contact_person_email) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_department values(:p_department_id, :department_name,  :description,  :abbreviation,  :contact_person,  :contact_person_email)");
                $stm->execute(array(':p_department_id' => 0, ':department_name' => $department_name, ':description' => $description, ':abbreviation' => $abbreviation, ':contact_person' => $contact_person, ':contact_person_email' => $contact_person_email
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_unit($department, $name, $Contact_person, $contact_person_email) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_unit values(:p_unit_id, :department,  :name,  :Contact_person,  :contact_person_email)");
                $stm->execute(array(':p_unit_id' => 0, ':department' => $department, ':name' => $name, ':Contact_person' => $Contact_person, ':contact_person_email' => $contact_person_email
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_staff($name, $last_name, $position, $email, $username, $Password, $unit_id, $is_active) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_staff values(:p_staff_id, :name,  :last_name,  :position,  :email,  :username,  :Password,  :unit_id,  :is_active)");
                $stm->execute(array(':p_staff_id' => 0, ':name' => $name, ':last_name' => $last_name, ':position' => $position, ':email' => $email, ':username' => $username, ':Password' => $Password, ':unit_id' => $unit_id, ':is_active' => $is_active
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_fund_request($staff, $expense, $request_date, $entry_date, $account, $request_status, $amount, $description) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_fund_request values(:p_fund_request_id, :staff,  :expense,  :request_date,  :entry_date,  :account,  :request_status,  :amount,  :description)");
                $stm->execute(array(':p_fund_request_id' => 0, ':staff' => $staff, ':expense' => $expense, ':request_date' => $request_date, ':entry_date' => $entry_date, ':account' => $account, ':request_status' => $request_status, ':amount' => $amount, ':description' => $description
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_approvals($request, $date, $account, $approval_type) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_approvals values(:p_approvals_id, :request,  :date,  :account,  :approval_type)");
                $stm->execute(array(':p_approvals_id' => 0, ':request' => $request, ':date' => $date, ':account' => $account, ':approval_type' => $approval_type
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_user_approvals($approval_type, $account) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_user_approvals values(:p_user_approvals_id, :approval-type,  :account)");
                $stm->execute(array(':p_user_approvals_id' => 0, ':approval_type' => $approval_type, ':account' => $account
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_project($name, $last_update_date, $project_contract_no, $project_spervisor, $account, $entry_date, $active, $status, $type_project, $field) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_project values(:p_project_id, :name,  :last_update_date,  :project_contract_no,  :project_spervisor,     :account,  :entry_date,  :active,  :status,  :type_project,:field)");
                $stm->execute(array(':p_project_id' => 0, ':name' => $name, ':last_update_date' => $last_update_date, ':project_contract_no' => $project_contract_no, ':project_spervisor' => $project_spervisor, ':account' => $account, ':entry_date' => $entry_date, ':active' => $active, ':status' => $status, ':type_project' => $type_project, ':field' => $field));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_approvals_type($name) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_approvals_type values(:p_approvals_type_id, :name)");
                $stm->execute(array(':p_approvals_type_id' => 0, ':name' => $name
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_other_expenses($item_name, $description, $amount, $date, $account, $project) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_other_expenses values(:p_other_expenses_id, :item_name,  :description,  :amount,  :date,  :account,  :project)");
                $stm->execute(array(':p_other_expenses_id' => 0, ':item_name' => $item_name, ':description' => $description, ':amount' => $amount, ':date' => $date, ':account' => $account, ':project' => $project
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_type_project($name) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_type_project values(:p_type_project_id, :name)");
                $stm->execute(array(':p_type_project_id' => 0, ':name' => $name
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_activity($project, $name, $fisc_year, $amount, $field, $budget_type) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_activity values(:p_activity_id, :project,     :name,:fisc_year,:amount, :field, :budget_type)");
                $stm->execute(array(':p_activity_id' => 0, ':project' => $project, ':name' => $name, ':fisc_year' => $fisc_year, ':amount' => $amount, ':field' => $field, ':budget_type' => $budget_type));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_field($name, $sector) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_field values(:p_field_id, :name,  :sector)");
                $stm->execute(array(':p_field_id' => 0, ':name' => $name, ':sector' => $sector
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_province($name) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_province values(:p_province_id, :name)");
                $stm->execute(array(':p_province_id' => 0, ':name' => $name
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_fiscal_year($fiscal_year_name, $start_date, $end_date, $entry_date, $account) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_fiscal_year values(:p_fiscal_year_id, :fiscal_year_name,  :start_date,  :end_date,  :entry_date,  :account)");
                $stm->execute(array(':p_fiscal_year_id' => 0, ':fiscal_year_name' => $fiscal_year_name, ':start_date' => $start_date, ':end_date' => $end_date, ':entry_date' => $entry_date, ':account' => $account
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_user($LastName, $FirstName, $UserName, $EmailAddress, $Roleid, $IsActive, $Password, $position_depart) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into user values(:user_id, :LastName,  :FirstName,  :UserName,  :EmailAddress ,:Roleid,  :IsActive,  :Password,    :position_depart)");
                $stm->execute(array(':user_id' => 0, ':LastName' => $LastName, ':FirstName' => $FirstName, ':UserName' => $UserName, ':EmailAddress' => $EmailAddress, ':Roleid' => $Roleid, ':IsActive' => $IsActive, ':Password' => $Password, ':position_depart' => $position_depart
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_roles($role_name) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_roles values(:p_roles_id, :role_name)");
                $stm->execute(array(':p_roles_id' => 0, ':role_name' => $role_name
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_role($name) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into role values(:role_id, :name)");
                $stm->execute(array(':role_id' => 0, ':name' => $name
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_staff_positions($name) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into staff_positions values(:staff_positions_id, :name)");
                $stm->execute(array(':staff_positions_id' => 0, ':name' => $name
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_request($item,$quantity,$unit_cost,$amount, $entry_date, $User, $measurement, $request_no, $main_req,$field,$status,$DAF,$DG) 
        {
            try 
            {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_request values(:p_request_id, :item,  :quantity,  :unit_cost,  :amount,  :entry_date,  :User,  :measurement,  :request_no,:main_req,  :field,:status, :DAF,:DG)");
                $stm->execute(array(':p_request_id' => 0, ':item' => $item, ':quantity' => $quantity, ':unit_cost' => $unit_cost, ':amount' => $amount, ':entry_date' => $entry_date, ':User' => $User, ':measurement' => $measurement, ':request_no' => $request_no, ":main_req" => $main_req,  ":field" => $field,":status" => $status,":DAF" =>$DAF,":DG" =>$DG));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_request_type($name) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_request_type values(:p_request_type_id, :name)");
                $stm->execute(array(':p_request_type_id' => 0, ':name' => $name
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_qty_request($request, $unit_cost, $quantity, $measurement, $requirement) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_qty_request values(:p_qty_request_id, :request,  :unit_cost,  :quantity,  :measurement,  :requirement)");
                $stm->execute(array(':p_qty_request_id' => 0, ':request' => $request, ':unit_cost' => $unit_cost, ':quantity' => $quantity, ':measurement' => $measurement, ':requirement' => $requirement
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_Currency($code, $description) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_Currency values(:p_Currency_id, :code,  :description)");
                $stm->execute(array(':p_Currency_id' => 0, ':code' => $code, ':description' => $description
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_budget_prep($project_type, $user, $entry_date, $budget_type, $activity_desc, $name) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_budget_prep values(:p_budget_prep_id, :project_type,  :user,  :entry_date,  :budget_type,  :activity_desc,   :name)");
                $stm->execute(array(':p_budget_prep_id' => 0, ':project_type' => $project_type, ':user' => $user, ':entry_date' => $entry_date, ':budget_type' => $budget_type, ':activity_desc' => $activity_desc, ':name' => $name));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e->getMessage();
            }
        }

        function new_p_bdgt_prep_expenses($name, $budget) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_bdgt_prep_expenses values(:p_bdgt_prep_expenses_id, :name,  :budget)");
                $stm->execute(array(':p_bdgt_prep_expenses_id' => 0, ':name' => $name, ':budget' => $budget
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_Main_Request($entry_date, $User) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into main_request values(:Main_Request_id, :entry_date,  :User)");
                $stm->execute(array(':Main_Request_id' => 0, ':entry_date' => $entry_date, ':User' => $User
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_payment_voucher($item, $entry_date, $User, $quantity, $unit_cost, $amount, $budget_prep) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into payment_voucher values(:payment_voucher_id, :item,  :entry_date,  :User,  :quantity,  :unit_cost,  :amount,  :activity)");
                $stm->execute(array(':payment_voucher_id' => 0, ':item' => $item, ':entry_date' => $entry_date, ':User' => $User, ':quantity' => $quantity, ':unit_cost' => $unit_cost, ':amount' => $amount, ':activity' => $budget_prep
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

// </editor-fold> 
        function new_main_stock($item, $quantity, $available_qty, $in_or_out, $measurement, $entry_date, $User, $unit_cost, $tot) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into main_stock values(:main_stock_id, :item,  :quantity,  :available_qty,  :in_or_out,  :measurement,  :entry_date,  :User, :unit_cost, :total_amount)");
                $stm->execute(array(':main_stock_id' => 0, ':item' => $item, ':quantity' => $quantity, ':available_qty' => $available_qty, ':in_or_out' => $in_or_out, ':measurement' => $measurement, ':entry_date' => $entry_date, ':User' => $User, ':unit_cost' => $unit_cost, ':total_amount' => $tot
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_cheque($bank, $address, $expense_items, $account, $amount, $memo, $party) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into cheque values(:cheque_id, :bank,  :address,  :expense_items,  :account,  :amount,  :memo,  :party)");
                $stm->execute(array(':cheque_id' => 0, ':bank' => $bank, ':address' => $address, ':expense_items' => $expense_items, ':account' => $account, ':amount' => $amount, ':memo' => $memo, ':party' => $party
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e->getMessage();
            }
        }

        function new_tax_calculations($self_id, $source_tax, $dest_tax, $sign, $is_self_existing, $group_type, $tax, $valued) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into tax_calculations values(:tax_calculations_id, :self_id,  :source_tax,  :dest_tax,  :sign,  :is_self_existing,  :group_type,  :tax,  :valued)");
                $stm->execute(array(':tax_calculations_id' => 0, ':self_id' => $self_id, ':source_tax' => $source_tax, ':dest_tax' => $dest_tax, ':sign' => $sign, ':is_self_existing' => $is_self_existing, ':group_type' => $group_type, ':tax' => $tax, ':valued' => $valued
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_tax_percentage($pur_sale, $percentage, $purid_saleid, $amount) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into tax_percentage values(:tax_percentage_id, :pur_sale,  :percentage,  :purid_saleid,:amount)");
                $stm->execute(array(':tax_percentage_id' => 0, ':pur_sale' => $pur_sale, ':percentage' => $percentage, ':purid_saleid' => $purid_saleid, ':amount' => $amount));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_vat($Total_val_of_Sup, $exempted_sales, $exports, $total_not_taxable, $taxable_sales_sub_vat, $vat_on_taxable_sales, $vat_reversecharge, $vat_payable, $vat_paid_on_imports, $vat_paidon_local_pur, $vat_rev_char_deduct, $vat_pay_cre_ref, $vat_with_hold_pub, $vat_due_cred_pay, $vat_refund, $vat_due) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into vat values(:vat_id, :Total_val_of_Sup,  :exempted_sales,  :exports,  :total_not_taxable,  :taxable_sales_sub_vat,  :vat_on_taxable_sales,  :vat_reversecharge,  :vat_payable,  :vat_paid_on_imports,  :vat_paidon_local_pur,  :vat_rev_char_deduct,  :vat_pay_cre_ref,  :vat_with_hold_pub,  :vat_due_cred_pay,  :vat_refund,  :vat_due)");
                $stm->execute(array(':vat_id' => 0, ':Total_val_of_Sup' => $Total_val_of_Sup, ':exempted_sales' => $exempted_sales, ':exports' => $exports, ':total_not_taxable' => $total_not_taxable, ':taxable_sales_sub_vat' => $taxable_sales_sub_vat, ':vat_on_taxable_sales' => $vat_on_taxable_sales, ':vat_reversecharge' => $vat_reversecharge, ':vat_payable' => $vat_payable, ':vat_paid_on_imports' => $vat_paid_on_imports, ':vat_paidon_local_pur' => $vat_paidon_local_pur, ':vat_rev_char_deduct' => $vat_rev_char_deduct, ':vat_pay_cre_ref' => $vat_pay_cre_ref, ':vat_with_hold_pub' => $vat_with_hold_pub, ':vat_due_cred_pay' => $vat_due_cred_pay, ':vat_refund' => $vat_refund, ':vat_due' => $vat_due
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_vat_calculation($purid_saleid, $vat_amount, $entry_date, $User, $vatid, $pur_sale) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into vat_calculation values(:vat_calculation_id, :purid_saleid,  :vat_amount,  :entry_date,  :User,  :vatid, :pur_sale)");
                $stm->execute(array(':vat_calculation_id' => 0, ':purid_saleid' => $purid_saleid, ':vat_amount' => $vat_amount, ':entry_date' => $entry_date, ':User' => $User, ':vatid' => $vatid, ':pur_sale' => $pur_sale));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_stock_into_main($item, $quantity, $entry_date, $User) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into stock_into_main values(:stock_into_main_id, :item,  :quantity,  :entry_date,  :User)");
                $stm->execute(array(':stock_into_main_id' => 0, ':item' => $item, ':quantity' => $quantity, ':entry_date' => $entry_date, ':User' => $User
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_district($name, $province) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_district values(:p_district_id, :name,  :province)");
                $stm->execute(array(':p_district_id' => 0, ':name' => $name, ':province' => $province
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_p_sector($name, $district) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into p_sector values(:p_sector_id, :name,  :district)");
                $stm->execute(array(':p_sector_id' => 0, ':name' => $name, ':district' => $district
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e;
            }
        }

        function new_tax_type($name, $percentage) {
            try {
                require_once('../web_db/connection.php');
                $database = new dbconnection();
                $db = $database->openConnection();
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $stm = $db->prepare("insert into tax_type values(:tax_type_id, :name,  :percentage)");
                $stm->execute(array(':tax_type_id' => 0, ':name' => $name, ':percentage' => $percentage
                ));
            } catch (PDOException $e) {
                echo 'Error .. ' . $e->getMessage();
            }
        }

    }
    