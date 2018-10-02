
--
-- Table structure for table `account`
--

CREATE TABLE IF NOT EXISTS `account` (
`account_id`     int(11) NOT NULL AUTO_INCREMENT 
, `acc_type`     int(11)   
, `acc_class`     int(11)   

,PRIMARY KEY (`account_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `account_type`
--

CREATE TABLE IF NOT EXISTS `account_type` (
`account_type_id`     int(11) NOT NULL AUTO_INCREMENT 

,PRIMARY KEY (`account_type_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `ledger_settings`
--

CREATE TABLE IF NOT EXISTS `ledger_settings` (
`ledger_settings_id`     int(11) NOT NULL AUTO_INCREMENT 

,PRIMARY KEY (`ledger_settings_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `bank`
--

CREATE TABLE IF NOT EXISTS `bank` (
`bank_id`     int(11) NOT NULL AUTO_INCREMENT 
, `account`     int(11)   

,PRIMARY KEY (`bank_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `account_class`
--

CREATE TABLE IF NOT EXISTS `account_class` (
`account_class_id`     int(11) NOT NULL AUTO_INCREMENT 

,PRIMARY KEY (`account_class_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `general_ledger_line`
--

CREATE TABLE IF NOT EXISTS `general_ledger_line` (
`general_ledger_line_id`     int(11) NOT NULL AUTO_INCREMENT 
, `general_ledger-header`     int(11)   
, `accountid`     int(11)   

,PRIMARY KEY (`general_ledger_line_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `main_contra_account`
--

CREATE TABLE IF NOT EXISTS `main_contra_account` (
`main_contra_account_id`     int(11) NOT NULL AUTO_INCREMENT 
, `main_contra_acc`     int(11)   
,`related_contra_acc`     VARCHAR(60) 

,PRIMARY KEY (`main_contra_account_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `sales_receit_header`
--

CREATE TABLE IF NOT EXISTS `sales_receit_header` (
`sales_receit_header_id`     int(11) NOT NULL AUTO_INCREMENT 
, `customerid`     int(11)   
, `general_ledger_header`     int(11)   
, `account`     int(11)   
,`number`     VARCHAR(60) 
,`number`     VARCHAR(60) 
,`date`     Date 
,`status`     VARCHAR(60) 
,`sales_receit_header_id`     int(11) 
, `customer`     int(11)   
, `gen_ledger_header`     int(11)   
, `account`     int(11)   
,`number`     VARCHAR(60) 
,`date`     Date 
, `amount`     int(11)   

,PRIMARY KEY (`sales_receit_header_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `measurement`
--

CREATE TABLE IF NOT EXISTS `measurement` (
`measurement_id`     int(11) NOT NULL AUTO_INCREMENT 
,`code`     VARCHAR(60) 
,`description`     VARCHAR(60) 

,PRIMARY KEY (`measurement_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `journal_entry_line`
--

CREATE TABLE IF NOT EXISTS `journal_entry_line` (
`journal_entry_line_id`     int(11) NOT NULL AUTO_INCREMENT 
, `accountid`     int(11)   
,`dr_cr`     VARCHAR(60) 
, `amount`     int(11)   
,`memo`     VARCHAR(60) 
, `journal_entry_header`     int(11)   

,PRIMARY KEY (`journal_entry_line_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `tax`
--

CREATE TABLE IF NOT EXISTS `tax` (
`tax_id`     int(11) NOT NULL AUTO_INCREMENT 
, `sales_accid`     int(11)   
, `purchase_accid`     int(11)   
,`tax_name`     VARCHAR(60) 

,PRIMARY KEY (`tax_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `vendor`
--

CREATE TABLE IF NOT EXISTS `vendor` (
`vendor_id`     int(11) NOT NULL AUTO_INCREMENT 
,`venndor_number`     VARCHAR(60) 
, `party`     int(11)   
, `payment_term`     int(11)   
, `tax_group`     int(11)   
,`purchase_acc`     VARCHAR(60) 
,`pur_discount_accid`     VARCHAR(60) 
,`primary_contact`     VARCHAR(60) 
,`acc_payble`     VARCHAR(60) 

,PRIMARY KEY (`vendor_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `general_ledger_header`
--

CREATE TABLE IF NOT EXISTS `general_ledger_header` (
`general_ledger_header_id`     int(11) NOT NULL AUTO_INCREMENT 
,`date`     Date 
,`doc_type`     VARCHAR(60) 
,`desc`     VARCHAR(60) 

,PRIMARY KEY (`general_ledger_header_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `party`
--

CREATE TABLE IF NOT EXISTS `party` (
`party_id`     int(11) NOT NULL AUTO_INCREMENT 
,`party_type`     VARCHAR(60) 
,`name`     VARCHAR(60) 
,`email`     VARCHAR(60) 
,`website`     VARCHAR(60) 
,`phone`     VARCHAR(60) 
,`is_active`     VARCHAR(60) 

,PRIMARY KEY (`party_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `contact`
--

CREATE TABLE IF NOT EXISTS `contact` (
`contact_id`     int(11) NOT NULL AUTO_INCREMENT 
, `party`     int(11)   

,PRIMARY KEY (`contact_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
`customer_id`     int(11) NOT NULL AUTO_INCREMENT 
, `party_id`     int(11)   
, `contact`     int(11)   
,`number`     VARCHAR(60) 
, `tax_group`     int(11)   
, `payment_term`     int(11)   
, `sales_accid`     int(11)   
, `acc_rec_accid`     int(11)   
,`promp_pyt_disc_accid`     VARCHAR(60) 

,PRIMARY KEY (`customer_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `taxgroup`
--

CREATE TABLE IF NOT EXISTS `taxgroup` (
`taxgroup_id`     int(11) NOT NULL AUTO_INCREMENT 
,`description`     VARCHAR(60) 
,`tax_applied`     VARCHAR(60) 
,`is_active`     VARCHAR(60) 

,PRIMARY KEY (`taxgroup_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `journal_entry_header`
--

CREATE TABLE IF NOT EXISTS `journal_entry_header` (
`journal_entry_header_id`     int(11) NOT NULL AUTO_INCREMENT 
, `party`     int(11)   
,`voucher_type`     VARCHAR(60) 
,`date`     Date 
,`memo`     VARCHAR(60) 
,`reference_number`     VARCHAR(60) 
,`posted`     VARCHAR(60) 

,PRIMARY KEY (`journal_entry_header_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `Payment_term`
--

CREATE TABLE IF NOT EXISTS `Payment_term` (
`Payment_term_id`     int(11) NOT NULL AUTO_INCREMENT 
,`description`     VARCHAR(60) 
,`payment_type`     VARCHAR(60) 
,`due_after-days`     VARCHAR(60) 
,`is_active`     VARCHAR(60) 

,PRIMARY KEY (`Payment_term_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `item`
--

CREATE TABLE IF NOT EXISTS `item` (
`item_id`     int(11) NOT NULL AUTO_INCREMENT 
, `measurement`     int(11)   
, `vendor`     int(11)   
, `item_group`     int(11)   
, `item_category`     int(11)   
,`smallest_measurement`     VARCHAR(60) 
,`sale_measurement`     VARCHAR(60) 
,`purchase_measurement`     VARCHAR(60) 
,`sales_account`     VARCHAR(60) 
,`inventory_accid`     VARCHAR(60) 
,`inventoty_adj_accid`     VARCHAR(60) 
,`number`     VARCHAR(60) 
,`Code`     VARCHAR(60) 
,`description`     VARCHAR(60) 
,`purchase_desc`     VARCHAR(60) 
,`sale_desc`     VARCHAR(60) 
, `cost`     int(11)   
, `price`     int(11)   

,PRIMARY KEY (`item_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `item_group`
--

CREATE TABLE IF NOT EXISTS `item_group` (
`item_group_id`     int(11) NOT NULL AUTO_INCREMENT 
,`name`     VARCHAR(60) 
,`is_full_exempt`     VARCHAR(60) 

,PRIMARY KEY (`item_group_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `item_category`
--

CREATE TABLE IF NOT EXISTS `item_category` (
`item_category_id`     int(11) NOT NULL AUTO_INCREMENT 
, `measurement`     int(11)   
, `sales-accid`     int(11)   
, `inventory_accid`     int(11)   
, `cost_good_sold_accid`     int(11)   
,`adjustment_accid`     VARCHAR(60) 
, `assembly_accid`     int(11)   
,`name`     VARCHAR(60) 

,PRIMARY KEY (`item_category_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `vendor_payment`
--

CREATE TABLE IF NOT EXISTS `vendor_payment` (
`vendor_payment_id`     int(11) NOT NULL AUTO_INCREMENT 
, `vendor`     int(11)   
, `gen_ledger_header`     int(11)   
, `pur_invoice_header`     int(11)   
,`number`     VARCHAR(60) 
,`date`     Date 
, `amount`     int(11)   

,PRIMARY KEY (`vendor_payment_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `sales_delivery_header`
--

CREATE TABLE IF NOT EXISTS `sales_delivery_header` (
`sales_delivery_header_id`     int(11) NOT NULL AUTO_INCREMENT 
, `customer`     int(11)   
, `gen_ledger_header`     int(11)   
, `payment_term`     int(11)   

,PRIMARY KEY (`sales_delivery_header_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `sale_delivery_line`
--

CREATE TABLE IF NOT EXISTS `sale_delivery_line` (
`sale_delivery_line_id`     int(11) NOT NULL AUTO_INCREMENT 
, `item`     int(11)   
, `measurement`     int(11)   
, `sales_delivery_header`     int(11)   
, `sales_invoice_line`     int(11)   

,PRIMARY KEY (`sale_delivery_line_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `sales_invoice_line`
--

CREATE TABLE IF NOT EXISTS `sales_invoice_line` (
`sales_invoice_line_id`     int(11) NOT NULL AUTO_INCREMENT 
, `item`     int(11)   
, `measurement`     int(11)   
, `sales_delivery_header`     int(11)   
, `sales_invoice_header`     int(11)   
, `sales_order_line`     int(11)   
, `quantity`     int(11)   
, `discount`     int(11)   

,PRIMARY KEY (`sales_invoice_line_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `sales_invoice_header`
--

CREATE TABLE IF NOT EXISTS `sales_invoice_header` (
`sales_invoice_header_id`     int(11) NOT NULL AUTO_INCREMENT 
, `customer`     int(11)   
, `payment_term`     int(11)   
, `gen_ledger_header`     int(11)   
,`number`     VARCHAR(60) 
,`date`     Date 
,`Shipping`     VARCHAR(60) 
,`status`     VARCHAR(60) 
,`reference_no`     VARCHAR(60) 

,PRIMARY KEY (`sales_invoice_header_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `sales_order_line`
--

CREATE TABLE IF NOT EXISTS `sales_order_line` (
`sales_order_line_id`     int(11) NOT NULL AUTO_INCREMENT 
, `sales_order_header`     int(11)   
, `item`     int(11)   
, `measurement`     int(11)   
, `quantity`     int(11)   
,`discount`     VARCHAR(60) 
, `amount`     int(11)   

,PRIMARY KEY (`sales_order_line_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `sales_order_header`
--

CREATE TABLE IF NOT EXISTS `sales_order_header` (
`sales_order_header_id`     int(11) NOT NULL AUTO_INCREMENT 
, `customer`     int(11)   
, `payment_term`     int(11)   
,`number`     VARCHAR(60) 
,`reference_number`     VARCHAR(60) 
,`date`     Date 
,`status`     VARCHAR(60) 

,PRIMARY KEY (`sales_order_header_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `sales_quote_line`
--

CREATE TABLE IF NOT EXISTS `sales_quote_line` (
`sales_quote_line_id`     int(11) NOT NULL AUTO_INCREMENT 
, `sales_quote_header`     int(11)   
, `item`     int(11)   
, `measurement`     int(11)   
, `quantity`     int(11)   
,`discount`     VARCHAR(60) 
, `amount`     int(11)   

,PRIMARY KEY (`sales_quote_line_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `sales_quote_header`
--

CREATE TABLE IF NOT EXISTS `sales_quote_header` (
`sales_quote_header_id`     int(11) NOT NULL AUTO_INCREMENT 
, `customer`     int(11)   
,`date`     Date 
, `payment_term`     int(11)   
,`reference_number`     VARCHAR(60) 
,`number`     VARCHAR(60) 
,`status`     VARCHAR(60) 

,PRIMARY KEY (`sales_quote_header_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `sales_receit_header`
--

CREATE TABLE IF NOT EXISTS `sales_receit_header` (
`sales_receit_header_id`     int(11) NOT NULL AUTO_INCREMENT 
, `customerid`     int(11)   
, `general_ledger_header`     int(11)   
, `account`     int(11)   
,`number`     VARCHAR(60) 
,`number`     VARCHAR(60) 
,`date`     Date 
,`status`     VARCHAR(60) 
,`sales_receit_header_id`     int(11) 
, `customer`     int(11)   
, `gen_ledger_header`     int(11)   
, `account`     int(11)   
,`number`     VARCHAR(60) 
,`date`     Date 
, `amount`     int(11)   

,PRIMARY KEY (`sales_receit_header_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `purchase_invoice_header`
--

CREATE TABLE IF NOT EXISTS `purchase_invoice_header` (
`purchase_invoice_header_id`     int(11) NOT NULL AUTO_INCREMENT 
, `inv_control_journal`     int(11)   
, `item`     int(11)   
, `measurement`     int(11)   
, `quantity`     int(11)   
, `receieved_qusntinty`     int(11)   
, `cost`     int(11)   
,`discount`     VARCHAR(60) 
, `purchase_order_line`     int(11)   

,PRIMARY KEY (`purchase_invoice_header_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `purchase_invoice_line`
--

CREATE TABLE IF NOT EXISTS `purchase_invoice_line` (
`purchase_invoice_line_id`     int(11) NOT NULL AUTO_INCREMENT 
, `item`     int(11)   
, `measurement`     int(11)   
, `pur_invoice_header`     int(11)   
, `cost`     int(11)   
,`discount`     VARCHAR(60) 
, `amount`     int(11)   
, `pur_order_line`     int(11)   
, `inventory_control_journal`     int(11)   
, `quantity`     int(11)   
, `received_quantity`     int(11)   

,PRIMARY KEY (`purchase_invoice_line_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `purchase_order_header`
--

CREATE TABLE IF NOT EXISTS `purchase_order_header` (
`purchase_order_header_id`     int(11) NOT NULL AUTO_INCREMENT 
, `vendor`     int(11)   
, `gen_ledger_header`     int(11)   
,`date`     Date 
,`number`     VARCHAR(60) 
,`vendor_invoice_number`     VARCHAR(60) 
,`description`     VARCHAR(60) 
, `payment_term`     int(11)   
,`reference_number`     VARCHAR(60) 
,`status`     VARCHAR(60) 

,PRIMARY KEY (`purchase_order_header_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `purchase_order_line`
--

CREATE TABLE IF NOT EXISTS `purchase_order_line` (
`purchase_order_line_id`     int(11) NOT NULL AUTO_INCREMENT 
, `pur_order_header`     int(11)   
, `item`     int(11)   
, `measurement`     int(11)   
, `quanitity`     int(11)   
, `cost`     int(11)   
,`discount`     VARCHAR(60) 
, `amount`     int(11)   

,PRIMARY KEY (`purchase_order_line_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `purchase_receit_header`
--

CREATE TABLE IF NOT EXISTS `purchase_receit_header` (
`purchase_receit_header_id`     int(11) NOT NULL AUTO_INCREMENT 
, `gen_ledger_header`     int(11)   
,`date`     Date 
,`status`     VARCHAR(60) 
,`number`     VARCHAR(60) 

,PRIMARY KEY (`purchase_receit_header_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `purchase_receit_line`
--

CREATE TABLE IF NOT EXISTS `purchase_receit_line` (
`purchase_receit_line_id`     int(11) NOT NULL AUTO_INCREMENT 
, `pur_recceit_header`     int(11)   
, `item`     int(11)   
, `Inventory control Journal`     int(11)   
, `measurement`     int(11)   
, `quantity`     int(11)   
, `received_qty`     int(11)   
, `cost`     int(11)   
,`discount`     VARCHAR(60) 
, `amount`     int(11)   

,PRIMARY KEY (`purchase_receit_line_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- Table structure for table `Inventory_control_journal`
--

CREATE TABLE IF NOT EXISTS `Inventory_control_journal` (
`Inventory_control_journal_id`     int(11) NOT NULL AUTO_INCREMENT 
, `measurement`     int(11)   
, `item`     int(11)   
,`doc_type`     VARCHAR(60) 
, `In_qty`     int(11)   
, `Out_qty`     int(11)   
,`date`     Date 
, `total_cost`     int(11)   
, `tot_amount`     int(11)   
,`is_reverse`     VARCHAR(60) 

,PRIMARY KEY (`Inventory_control_journal_id`)  ) 
ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

