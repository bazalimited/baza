SET SESSION sql_mode="NO_AUTO_CREATE_USER";
SET FOREIGN_KEY_CHECKS=0;

--
-- Table structure for table `phppos_additional_item_numbers`
--




CREATE TABLE `phppos_additional_item_numbers` (
  `item_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`item_id`,`item_number`),
  UNIQUE KEY `item_number` (`item_number`),
  CONSTRAINT `phppos_additional_item_numbers_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*!50604 ALTER TABLE `phppos_additional_item_numbers` ADD FULLTEXT INDEX item_number_search (`item_number`)*/;		

--
-- Dumping data for table `phppos_additional_item_numbers`
--






--
-- Table structure for table `phppos_app_config`
--




CREATE TABLE `phppos_app_config` (
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_app_config`
--



INSERT INTO `phppos_app_config` VALUES ('additional_payment_types',''),('always_show_item_grid','0'),('announcement_special',''),('auto_focus_on_item_after_sale_and_receiving','0'),('automatically_email_receipt','0'),('automatically_print_duplicate_receipt_for_cc_transactions','0'),('automatically_show_comments_on_receipt','0'),('averaging_method','moving_average'),('barcode_price_include_tax','0'),('calculate_average_cost_price_from_receivings','0'),('calculate_profit_for_giftcard_when',''),('change_sale_date_for_new_sale','0'),('change_sale_date_when_completing_suspended_sale','0'),('change_sale_date_when_suspending','0'),('charge_tax_on_recv','0'),('commission_default_rate','0'),('company','PHP Point Of Sale, LLC'),('currency_symbol','$'),('customers_store_accounts','0'),('date_format','middle_endian'),('default_payment_type','Cash'),('default_sales_person','logged_in_employee'),('default_tax_1_name','Sales Tax'),('default_tax_1_rate',''),('default_tax_2_cumulative','0'),('default_tax_2_name','Sales Tax 2'),('default_tax_2_rate',''),('default_tax_3_name',''),('default_tax_3_rate',''),('default_tax_4_name',''),('default_tax_4_rate',''),('default_tax_5_name',''),('default_tax_5_rate',''),('default_tax_rate','8'),('default_type_for_grid','categories'),('disable_confirmation_sale','0'),('disable_giftcard_detection','0'),('disable_quick_complete_sale','0'),('disable_sale_notifications','0'),('do_not_allow_out_of_stock_items_to_be_sold','0'),('do_not_group_same_items','0'),('edit_item_price_if_zero_after_adding','0'),('enable_sounds','0'),('fast_user_switching','0'),('group_all_taxes_on_receipt','0'),('hide_barcode_on_sales_and_recv_receipt','0'),('hide_customer_recent_sales','0'),('hide_dashboard_statistics','0'),('hide_layaways_sales_in_reports','0'),('hide_signature','0'),('hide_store_account_balance_on_receipt','0'),('hide_store_account_payments_from_report_totals','0'),('hide_store_account_payments_in_reports','0'),('hide_suspended_recv_in_reports','0'),('id_to_show_on_barcode','id'),('id_to_show_on_sale_interface','number'),('keep_same_location_after_switching_employee','0'),('language','english'),('legacy_detailed_report_export','0'),('legacy_search_method','0'),('number_of_decimals',''),('number_of_items_in_grid','14'),('number_of_items_per_page','20'),('number_of_recent_sales','10'),('override_receipt_title',''),('prices_include_tax','0'),('print_after_receiving','0'),('print_after_sale','0'),('receipt_text_size','small'),('redirect_to_sale_or_recv_screen_after_printing_receipt','0'),('remove_customer_contact_info_from_receipt','0'),('report_sort_order','asc'),('require_customer_for_sale','0'),('require_employee_login_before_each_sale','0'),('return_policy','Change return policy'),('round_cash_on_sales','0'),('round_tier_prices_to_2_decimals','0'),('sale_prefix','POS'),('select_sales_person_during_sale','0'),('show_item_id_on_receipt','0'),('show_language_switcher','0'),('show_receipt_after_suspending_sale','0'),('speed_up_search_queries','0'),('spreadsheet_format','XLSX'),('thousands_separator',','),('decimal_point','.'),('time_format','12_hour'),('timeclock','0'),('track_cash','0'),('version','15.0'),('website',''),('hide_out_of_stock_grid','0'),('commission_percent_type','selling_price'),('highlight_low_inventory_items_in_items_module','0'),('deleted_payment_types', ''),('discount_percent_earned', '0'),('enable_customer_loyalty_system', '0'),('hide_sales_to_discount_on_receipt', '0'),('loyalty_option', ''),('number_of_sales_for_discount', ''),('point_value', ''),('show_clock_on_header', ''),('spend_to_point_ratio', ''),('hide_price_on_barcodes', '0'),('always_use_average_cost_method', '0'),('test_mode','0'),('hide_test_mode_home','0'),('require_customer_for_suspended_sale','0'),('default_new_items_to_service', '0'),('prompt_for_ccv_swipe','0'),('disable_store_account_when_over_credit_limit','0'),('mailing_labels_type', 'pdf'),('phppos_session_expiration','0'),('do_not_allow_below_cost', '0');



--
-- Table structure for table `phppos_app_files`
--




CREATE TABLE `phppos_app_files` (
  `file_id` int(10) NOT NULL AUTO_INCREMENT,
  `file_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_data` longblob NOT NULL,
  PRIMARY KEY (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_app_files`
--






--
-- Table structure for table `phppos_categories`
--




CREATE TABLE `phppos_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `hide_from_grid` int(1) NOT NULL DEFAULT '0',
  `parent_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `deleted` (`deleted`),
  KEY `parent_id_name_index` (`parent_id`,`name`),
  CONSTRAINT `phppos_categories_ibfk_1` FOREIGN KEY (`parent_id`) REFERENCES `phppos_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*!50604 ALTER TABLE `phppos_categories` ADD FULLTEXT INDEX name_search (`name`)*/;
--
-- Dumping data for table `phppos_categories`
--






--
-- Table structure for table `phppos_customers`
--




CREATE TABLE `phppos_customers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `person_id` int(10) NOT NULL,
  `account_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `override_default_tax` int(1) NOT NULL DEFAULT '0',
  `company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `balance` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `credit_limit` decimal(23,10) DEFAULT NULL,
  `points` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `current_spend_for_points` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `current_sales_for_discount` int(10) NOT NULL DEFAULT '0',
  `taxable` int(1) NOT NULL DEFAULT '1',
  `tax_certificate` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cc_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cc_preview` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_issuer` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `tier_id` int(10) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`),
  KEY `deleted` (`deleted`),
  KEY `cc_token` (`cc_token`),
  KEY `phppos_customers_ibfk_2` (`tier_id`),
  CONSTRAINT `phppos_customers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `phppos_people` (`person_id`),
  CONSTRAINT `phppos_customers_ibfk_2` FOREIGN KEY (`tier_id`) REFERENCES `phppos_price_tiers` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*!50604	ALTER TABLE `phppos_customers` ADD FULLTEXT INDEX full_search (`account_number`, `company_name`, `tax_certificate`)*/;	
/*!50604	ALTER TABLE `phppos_customers` ADD FULLTEXT INDEX account_number_search (`account_number`)*/;
/*!50604	ALTER TABLE `phppos_customers` ADD FULLTEXT INDEX company_name_search (`company_name`)*/;
/*!50604	ALTER TABLE `phppos_customers` ADD FULLTEXT INDEX tax_certificate_search(`tax_certificate`)*/;
--
-- Dumping data for table `phppos_customers`
--


CREATE TABLE `phppos_customers_taxes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tax` (`customer_id`,`name`,`percent`),
  CONSTRAINT `phppos_customers_taxes_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `phppos_customers` (`person_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;





--
-- Table structure for table `phppos_employees`
--




CREATE TABLE `phppos_employees` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `force_password_change` int(1) NOT NULL DEFAULT '0',
  `person_id` int(10) NOT NULL,
  `language` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `commission_percent` decimal(23,10) DEFAULT '0.0000000000',
  `commission_percent_type` VARCHAR(255) COLLATE utf8_unicode_ci DEFAULT '',
  `hourly_pay_rate` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `inactive` int(1) NOT NULL DEFAULT '0',
  `reason_inactive` text COLLATE utf8_unicode_ci,
  `hire_date` date DEFAULT NULL,
  `employee_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `termination_date` date DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `employee_number` (`employee_number`),
  KEY `person_id` (`person_id`),
  KEY `deleted` (`deleted`),
  CONSTRAINT `phppos_employees_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `phppos_people` (`person_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*!50604 ALTER TABLE `phppos_employees` ADD FULLTEXT INDEX username_search (`username`)*/;

--
-- Dumping data for table `phppos_employees`
--



INSERT INTO `phppos_employees` VALUES (1,'admin','439a6de57d475c1a0ba9bcb1c39f0af6',0,1,NULL,0.0000000000,'',0.0000000000,0,NULL,NULL,NULL,NULL,NULL,0);



--
-- Table structure for table `phppos_employees_locations`
--




CREATE TABLE `phppos_employees_locations` (
  `employee_id` int(10) NOT NULL,
  `location_id` int(10) NOT NULL,
  PRIMARY KEY (`employee_id`,`location_id`),
  KEY `phppos_employees_locations_ibfk_2` (`location_id`),
  CONSTRAINT `phppos_employees_locations_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`),
  CONSTRAINT `phppos_employees_locations_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_employees_locations`
--



INSERT INTO `phppos_employees_locations` VALUES (1,1);



--
-- Table structure for table `phppos_employees_reset_password`
--




CREATE TABLE `phppos_employees_reset_password` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `employee_id` int(11) NOT NULL,
  `expire` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `phppos_employees_reset_password_ibfk_1` (`employee_id`),
  CONSTRAINT `phppos_employees_reset_password_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_employees_reset_password`
--






--
-- Table structure for table `phppos_employees_time_clock`
--




CREATE TABLE `phppos_employees_time_clock` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `employee_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `clock_in` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `clock_out` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `clock_in_comment` text COLLATE utf8_unicode_ci NOT NULL,
  `clock_out_comment` text COLLATE utf8_unicode_ci NOT NULL,
  `hourly_pay_rate` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  PRIMARY KEY (`id`),
  KEY `phppos_employees_time_clock_ibfk_1` (`employee_id`),
  KEY `phppos_employees_time_clock_ibfk_2` (`location_id`),
  CONSTRAINT `phppos_employees_time_clock_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`),
  CONSTRAINT `phppos_employees_time_clock_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_employees_time_clock`
--






--
-- Table structure for table `phppos_expenses`
--




CREATE TABLE `phppos_expenses` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `location_id` int(10) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `expense_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expense_description` text COLLATE utf8_unicode_ci,
  `expense_reason` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `expense_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `expense_amount` decimal(23,10) NOT NULL,
  `expense_tax` decimal(23,10) NOT NULL,
  `expense_note` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `employee_id` int(10) NOT NULL,
  `approved_employee_id` int(10) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `location_id` (`location_id`),
  KEY `employee_id` (`employee_id`),
  KEY `approved_employee_id` (`approved_employee_id`),
  KEY `category_id` (`category_id`),
  KEY `deleted` (`deleted`),
  CONSTRAINT `phppos_expenses_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`),
  CONSTRAINT `phppos_expenses_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`),
  CONSTRAINT `phppos_expenses_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `phppos_categories` (`id`),
  CONSTRAINT `phppos_expenses_ibfk_4` FOREIGN KEY (`approved_employee_id`) REFERENCES `phppos_employees` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!50604	ALTER TABLE `phppos_expenses` ADD FULLTEXT INDEX `full_search` (`expense_type`,`expense_description`,`expense_reason`,`expense_note`)*/;	
/*!50604	ALTER TABLE `phppos_expenses` ADD FULLTEXT INDEX `expense_type_search` (`expense_type`)*/;
/*!50604	ALTER TABLE `phppos_expenses` ADD FULLTEXT INDEX `expense_description_search` (`expense_description`)*/;
/*!50604	ALTER TABLE `phppos_expenses` ADD FULLTEXT INDEX `expense_reason_search` (`expense_reason`)*/;
/*!50604	ALTER TABLE `phppos_expenses` ADD FULLTEXT INDEX `expense_note_search` (`expense_note`)*/;
--
-- Dumping data for table `phppos_expenses`
--






--
-- Table structure for table `phppos_giftcards`
--




CREATE TABLE `phppos_giftcards` (
  `giftcard_id` int(11) NOT NULL AUTO_INCREMENT,
  `giftcard_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `value` decimal(23,10) NOT NULL,
  `customer_id` int(10) DEFAULT NULL,
  `inactive` int(1) NOT NULL DEFAULT '0',
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`giftcard_id`),
  UNIQUE KEY `giftcard_number` (`giftcard_number`),
  KEY `deleted` (`deleted`),
  KEY `phppos_giftcards_ibfk_1` (`customer_id`),
  CONSTRAINT `phppos_giftcards_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `phppos_customers` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!50604	ALTER TABLE `phppos_giftcards` ADD FULLTEXT INDEX giftcard_number_search (`description`,`giftcard_number`)*/;

--
-- Dumping data for table `phppos_giftcards`
--






--
-- Table structure for table `phppos_giftcards_log`
--




CREATE TABLE `phppos_giftcards_log` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `log_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `giftcard_id` int(11) NOT NULL,
  `transaction_amount` decimal(23,10) NOT NULL,
  `log_message` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `phppos_giftcards_log_ibfk_1` (`giftcard_id`),
  CONSTRAINT `phppos_giftcards_log_ibfk_1` FOREIGN KEY (`giftcard_id`) REFERENCES `phppos_giftcards` (`giftcard_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_giftcards_log`
--






--
-- Table structure for table `phppos_inventory`
--




CREATE TABLE `phppos_inventory` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_items` int(11) NOT NULL DEFAULT '0',
  `trans_user` int(11) NOT NULL DEFAULT '0',
  `trans_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `trans_comment` text COLLATE utf8_unicode_ci NOT NULL,
  `trans_inventory` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `location_id` int(11) NOT NULL,
  PRIMARY KEY (`trans_id`),
  KEY `phppos_inventory_ibfk_1` (`trans_items`),
  KEY `phppos_inventory_ibfk_2` (`trans_user`),
  KEY `location_id` (`location_id`),
  CONSTRAINT `phppos_inventory_ibfk_1` FOREIGN KEY (`trans_items`) REFERENCES `phppos_items` (`item_id`),
  CONSTRAINT `phppos_inventory_ibfk_2` FOREIGN KEY (`trans_user`) REFERENCES `phppos_employees` (`person_id`),
  CONSTRAINT `phppos_inventory_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_inventory`
--






--
-- Table structure for table `phppos_inventory_counts`
--




CREATE TABLE `phppos_inventory_counts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `count_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `employee_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `phppos_inventory_counts_ibfk_1` (`employee_id`),
  KEY `phppos_inventory_counts_ibfk_2` (`location_id`),
  CONSTRAINT `phppos_inventory_counts_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`),
  CONSTRAINT `phppos_inventory_counts_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_inventory_counts`
--






--
-- Table structure for table `phppos_inventory_counts_items`
--




CREATE TABLE `phppos_inventory_counts_items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `inventory_counts_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `count` decimal(23,10) DEFAULT '0.0000000000',
  `actual_quantity` decimal(23,10) DEFAULT '0.0000000000',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `phppos_inventory_counts_items_ibfk_1` (`inventory_counts_id`),
  KEY `phppos_inventory_counts_items_ibfk_2` (`item_id`),
  CONSTRAINT `phppos_inventory_counts_items_ibfk_1` FOREIGN KEY (`inventory_counts_id`) REFERENCES `phppos_inventory_counts` (`id`),
  CONSTRAINT `phppos_inventory_counts_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_inventory_counts_items`
--






--
-- Table structure for table `phppos_item_kit_items`
--




CREATE TABLE `phppos_item_kit_items` (
  `item_kit_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` decimal(23,10) NOT NULL,
  PRIMARY KEY (`item_kit_id`,`item_id`,`quantity`),
  KEY `phppos_item_kit_items_ibfk_2` (`item_id`),
  CONSTRAINT `phppos_item_kit_items_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`) ON DELETE CASCADE,
  CONSTRAINT `phppos_item_kit_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_item_kit_items`
--






--
-- Table structure for table `phppos_item_kits`
--




CREATE TABLE `phppos_item_kits` (
  `item_kit_id` int(11) NOT NULL AUTO_INCREMENT,
  `item_kit_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tax_included` int(1) NOT NULL DEFAULT '0',
  `unit_price` decimal(23,10) DEFAULT NULL,
  `cost_price` decimal(23,10) DEFAULT NULL,
  `override_default_tax` int(1) NOT NULL DEFAULT '0',
  `commission_percent` decimal(23,10) DEFAULT '0.0000000000',
  `commission_percent_type` VARCHAR(255) COLLATE utf8_unicode_ci DEFAULT '',
  `commission_fixed` decimal(23,10) DEFAULT '0.0000000000',
  `change_cost_price` INT(1) NOT NULL DEFAULT '0',
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_kit_id`),
  UNIQUE KEY `item_kit_number` (`item_kit_number`),
  UNIQUE KEY `product_id` (`product_id`),
  KEY `name` (`name`),
  KEY `deleted` (`deleted`),
  KEY `phppos_item_kits_ibfk_1` (`category_id`),
  CONSTRAINT `phppos_item_kits_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `phppos_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!50604	ALTER TABLE `phppos_item_kits` DROP INDEX `name`*/;
/*!50604	ALTER TABLE `phppos_item_kits` ADD FULLTEXT INDEX full_search (`name`, `item_kit_number`, `product_id`, `description`)*/;	
/*!50604	ALTER TABLE `phppos_item_kits` ADD FULLTEXT INDEX name_search (`name`)*/;
/*!50604	ALTER TABLE `phppos_item_kits` ADD FULLTEXT INDEX item_kit_number_search (`item_kit_number`)*/;
/*!50604	ALTER TABLE `phppos_item_kits` ADD FULLTEXT INDEX product_id_search (`product_id`)*/;
/*!50604	ALTER TABLE `phppos_item_kits` ADD FULLTEXT INDEX description_search (`description`)*/;
--
-- Dumping data for table `phppos_item_kits`
--






--
-- Table structure for table `phppos_item_kits_tags`
--




CREATE TABLE `phppos_item_kits_tags` (
  `item_kit_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`item_kit_id`,`tag_id`),
  KEY `phppos_item_kits_tags_ibfk_2` (`tag_id`),
  CONSTRAINT `phppos_item_kits_tags_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`),
  CONSTRAINT `phppos_item_kits_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `phppos_tags` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_item_kits_tags`
--






--
-- Table structure for table `phppos_item_kits_taxes`
--




CREATE TABLE `phppos_item_kits_taxes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `item_kit_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tax` (`item_kit_id`,`name`,`percent`),
  CONSTRAINT `phppos_item_kits_taxes_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_item_kits_taxes`
--






--
-- Table structure for table `phppos_item_kits_tier_prices`
--




CREATE TABLE `phppos_item_kits_tier_prices` (
  `tier_id` int(10) NOT NULL,
  `item_kit_id` int(10) NOT NULL,
  `unit_price` decimal(23,10) DEFAULT '0.0000000000',
  `percent_off` decimal(15,3) DEFAULT NULL,
  PRIMARY KEY (`tier_id`,`item_kit_id`),
  KEY `phppos_item_kits_tier_prices_ibfk_2` (`item_kit_id`),
  CONSTRAINT `phppos_item_kits_tier_prices_ibfk_1` FOREIGN KEY (`tier_id`) REFERENCES `phppos_price_tiers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `phppos_item_kits_tier_prices_ibfk_2` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_item_kits_tier_prices`
--






--
-- Table structure for table `phppos_items`
--




CREATE TABLE `phppos_items` (
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `item_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `tax_included` int(1) NOT NULL DEFAULT '0',
  `cost_price` decimal(23,10) NOT NULL,
  `unit_price` decimal(23,10) NOT NULL,
  `promo_price` decimal(23,10) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `reorder_level` decimal(23,10) DEFAULT NULL,
  `expire_days` int(10) DEFAULT NULL,
  `item_id` int(10) NOT NULL AUTO_INCREMENT,
  `allow_alt_description` tinyint(1) NOT NULL,
  `is_serialized` tinyint(1) NOT NULL,
  `image_id` int(10) DEFAULT NULL,
  `override_default_tax` int(1) NOT NULL DEFAULT '0',
  `is_service` int(1) NOT NULL DEFAULT '0',
  `commission_percent` decimal(23,10) DEFAULT '0.0000000000',
  `commission_percent_type` VARCHAR(255) COLLATE utf8_unicode_ci DEFAULT '',
  `commission_fixed` decimal(23,10) DEFAULT '0.0000000000',
  `change_cost_price` INT(1) NOT NULL DEFAULT '0',
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `item_number` (`item_number`),
  UNIQUE KEY `product_id` (`product_id`),
  KEY `phppos_items_ibfk_1` (`supplier_id`),
  KEY `name` (`name`),
  KEY `deleted` (`deleted`),
  KEY `phppos_items_ibfk_2` (`image_id`),
  KEY `phppos_items_ibfk_3` (`category_id`),
  CONSTRAINT `phppos_items_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `phppos_suppliers` (`person_id`),
  CONSTRAINT `phppos_items_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `phppos_app_files` (`file_id`),
  CONSTRAINT `phppos_items_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `phppos_categories` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*!50604	ALTER TABLE `phppos_items` DROP INDEX `name`*/;
/*!50604	ALTER TABLE `phppos_items` ADD FULLTEXT INDEX full_search (`name`, `item_number`, `product_id`, `description`)*/;	
/*!50604	ALTER TABLE `phppos_items` ADD FULLTEXT INDEX name_search (`name`)*/;
/*!50604	ALTER TABLE `phppos_items` ADD FULLTEXT INDEX item_number_search (`item_number`)*/;
/*!50604	ALTER TABLE `phppos_items` ADD FULLTEXT INDEX product_id_search (`product_id`)*/;
/*!50604	ALTER TABLE `phppos_items` ADD FULLTEXT INDEX description_search (`description`)*/;
/*!50604	ALTER TABLE `phppos_items` ADD FULLTEXT INDEX size_search (`size`)*/;
--
-- Dumping data for table `phppos_items`
--






--
-- Table structure for table `phppos_items_tags`
--




CREATE TABLE `phppos_items_tags` (
  `item_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  PRIMARY KEY (`item_id`,`tag_id`),
  KEY `phppos_items_tags_ibfk_2` (`tag_id`),
  CONSTRAINT `phppos_items_tags_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`),
  CONSTRAINT `phppos_items_tags_ibfk_2` FOREIGN KEY (`tag_id`) REFERENCES `phppos_tags` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_items_tags`
--






--
-- Table structure for table `phppos_items_taxes`
--




CREATE TABLE `phppos_items_taxes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `item_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tax` (`item_id`,`name`,`percent`),
  CONSTRAINT `phppos_items_taxes_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_items_taxes`
--






--
-- Table structure for table `phppos_items_tier_prices`
--




CREATE TABLE `phppos_items_tier_prices` (
  `tier_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `unit_price` decimal(23,10) DEFAULT '0.0000000000',
  `percent_off` decimal(15,3) DEFAULT NULL,
  PRIMARY KEY (`tier_id`,`item_id`),
  KEY `phppos_items_tier_prices_ibfk_2` (`item_id`),
  CONSTRAINT `phppos_items_tier_prices_ibfk_1` FOREIGN KEY (`tier_id`) REFERENCES `phppos_price_tiers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `phppos_items_tier_prices_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_items_tier_prices`
--






--
-- Table structure for table `phppos_location_item_kits`
--




CREATE TABLE `phppos_location_item_kits` (
  `location_id` int(11) NOT NULL,
  `item_kit_id` int(11) NOT NULL,
  `unit_price` decimal(23,10) DEFAULT NULL,
  `cost_price` decimal(23,10) DEFAULT NULL,
  `override_default_tax` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`location_id`,`item_kit_id`),
  KEY `phppos_location_item_kits_ibfk_2` (`item_kit_id`),
  CONSTRAINT `phppos_location_item_kits_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`),
  CONSTRAINT `phppos_location_item_kits_ibfk_2` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_location_item_kits`
--






--
-- Table structure for table `phppos_location_item_kits_taxes`
--




CREATE TABLE `phppos_location_item_kits_taxes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `item_kit_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(16,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tax` (`location_id`,`item_kit_id`,`name`,`percent`),
  KEY `phppos_location_item_kits_taxes_ibfk_2` (`item_kit_id`),
  CONSTRAINT `phppos_location_item_kits_taxes_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`) ON DELETE CASCADE,
  CONSTRAINT `phppos_location_item_kits_taxes_ibfk_2` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_location_item_kits_taxes`
--






--
-- Table structure for table `phppos_location_item_kits_tier_prices`
--




CREATE TABLE `phppos_location_item_kits_tier_prices` (
  `tier_id` int(10) NOT NULL,
  `item_kit_id` int(10) NOT NULL,
  `location_id` int(10) NOT NULL,
  `unit_price` decimal(23,10) DEFAULT '0.0000000000',
  `percent_off` decimal(15,3) DEFAULT NULL,
  PRIMARY KEY (`tier_id`,`item_kit_id`,`location_id`),
  KEY `phppos_location_item_kits_tier_prices_ibfk_2` (`location_id`),
  KEY `phppos_location_item_kits_tier_prices_ibfk_3` (`item_kit_id`),
  CONSTRAINT `phppos_location_item_kits_tier_prices_ibfk_1` FOREIGN KEY (`tier_id`) REFERENCES `phppos_price_tiers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `phppos_location_item_kits_tier_prices_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`),
  CONSTRAINT `phppos_location_item_kits_tier_prices_ibfk_3` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_location_item_kits_tier_prices`
--






--
-- Table structure for table `phppos_location_items`
--




CREATE TABLE `phppos_location_items` (
  `location_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `location` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `cost_price` decimal(23,10) DEFAULT NULL,
  `unit_price` decimal(23,10) DEFAULT NULL,
  `promo_price` decimal(23,10) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `quantity` decimal(23,10) DEFAULT '0.0000000000',
  `reorder_level` decimal(23,10) DEFAULT NULL,
  `override_default_tax` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`location_id`,`item_id`),
  KEY `phppos_location_items_ibfk_2` (`item_id`),
  CONSTRAINT `phppos_location_items_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`),
  CONSTRAINT `phppos_location_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_location_items`
--






--
-- Table structure for table `phppos_location_items_taxes`
--




CREATE TABLE `phppos_location_items_taxes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `item_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(16,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tax` (`location_id`,`item_id`,`name`,`percent`),
  KEY `phppos_location_items_taxes_ibfk_2` (`item_id`),
  CONSTRAINT `phppos_location_items_taxes_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`) ON DELETE CASCADE,
  CONSTRAINT `phppos_location_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_location_items_taxes`
--






--
-- Table structure for table `phppos_location_items_tier_prices`
--




CREATE TABLE `phppos_location_items_tier_prices` (
  `tier_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `location_id` int(10) NOT NULL,
  `unit_price` decimal(23,10) DEFAULT '0.0000000000',
  `percent_off` decimal(15,3) DEFAULT NULL,
  PRIMARY KEY (`tier_id`,`item_id`,`location_id`),
  KEY `phppos_location_items_tier_prices_ibfk_2` (`location_id`),
  KEY `phppos_location_items_tier_prices_ibfk_3` (`item_id`),
  CONSTRAINT `phppos_location_items_tier_prices_ibfk_1` FOREIGN KEY (`tier_id`) REFERENCES `phppos_price_tiers` (`id`) ON DELETE CASCADE,
  CONSTRAINT `phppos_location_items_tier_prices_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`),
  CONSTRAINT `phppos_location_items_tier_prices_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_location_items_tier_prices`
--






--
-- Table structure for table `phppos_locations`
--




CREATE TABLE `phppos_locations` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` text COLLATE utf8_unicode_ci,
  `address` text COLLATE utf8_unicode_ci,
  `phone` text COLLATE utf8_unicode_ci,
  `fax` text COLLATE utf8_unicode_ci,
  `email` text COLLATE utf8_unicode_ci,
  `color` text COLLATE utf8_unicode_ci,
  `return_policy` text COLLATE utf8_unicode_ci,
  `receive_stock_alert` text COLLATE utf8_unicode_ci,
  `stock_alert_email` text COLLATE utf8_unicode_ci,
  `timezone` text COLLATE utf8_unicode_ci,
  `mailchimp_api_key` text COLLATE utf8_unicode_ci,
  `enable_credit_card_processing` text COLLATE utf8_unicode_ci,
  `credit_card_processor` text COLLATE utf8_unicode_ci,
  `hosted_checkout_merchant_id` text COLLATE utf8_unicode_ci,
  `hosted_checkout_merchant_password` text COLLATE utf8_unicode_ci,
  `emv_merchant_id` text COLLATE utf8_unicode_ci,
  `listener_port` text COLLATE utf8_unicode_ci,
  `com_port` text COLLATE utf8_unicode_ci,
  `stripe_public` text COLLATE utf8_unicode_ci,
  `stripe_private` text COLLATE utf8_unicode_ci,
  `stripe_currency_code` text COLLATE utf8_unicode_ci,
  `braintree_merchant_id` text COLLATE utf8_unicode_ci,
  `braintree_public_key` text COLLATE utf8_unicode_ci,
  `braintree_private_key` text COLLATE utf8_unicode_ci,
  `default_tax_1_rate` text COLLATE utf8_unicode_ci,
  `default_tax_1_name` text COLLATE utf8_unicode_ci,
  `default_tax_2_rate` text COLLATE utf8_unicode_ci,
  `default_tax_2_name` text COLLATE utf8_unicode_ci,
  `default_tax_2_cumulative` text COLLATE utf8_unicode_ci,
  `default_tax_3_rate` text COLLATE utf8_unicode_ci,
  `default_tax_3_name` text COLLATE utf8_unicode_ci,
  `default_tax_4_rate` text COLLATE utf8_unicode_ci,
  `default_tax_4_name` text COLLATE utf8_unicode_ci,
  `default_tax_5_rate` text COLLATE utf8_unicode_ci,
  `default_tax_5_name` text COLLATE utf8_unicode_ci,
  `deleted` int(1) DEFAULT '0',
  PRIMARY KEY (`location_id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!50604	ALTER TABLE `phppos_locations` ADD FULLTEXT INDEX full_search (`name`, `address`, `phone`, `email`)*/;	
/*!50604	ALTER TABLE `phppos_locations` ADD FULLTEXT INDEX name_search (`name`)*/;
/*!50604	ALTER TABLE `phppos_locations` ADD FULLTEXT INDEX address_search (`address`)*/;
/*!50604	ALTER TABLE `phppos_locations` ADD FULLTEXT INDEX phone_search (`phone`)*/;
/*!50604	ALTER TABLE `phppos_locations` ADD FULLTEXT INDEX email_search (`email`)*/;
--
-- Dumping data for table `phppos_locations`
--



INSERT INTO `phppos_locations` VALUES (1,'Default','123 Nowhere street','555-555-5555','','no-reply@bluhub.rw',NULL,NULL,'0','','America/New_York','','0',NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,'Sales Tax',NULL,'Sales Tax 2','0',NULL,'',NULL,'',NULL,'',0);



--
-- Table structure for table `phppos_message_receiver`
--




CREATE TABLE `phppos_message_receiver` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message_read` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `phppos_message_receiver_ibfk_2` (`receiver_id`),
  KEY `phppos_message_receiver_key_1` (`message_id`,`receiver_id`),
  CONSTRAINT `phppos_message_receiver_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `phppos_messages` (`id`),
  CONSTRAINT `phppos_message_receiver_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `phppos_employees` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_message_receiver`
--






--
-- Table structure for table `phppos_messages`
--




CREATE TABLE `phppos_messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `sender_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `phppos_messages_ibfk_1` (`sender_id`),
  KEY `phppos_messages_key_1` (`deleted`,`created_at`,`id`),
  CONSTRAINT `phppos_messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `phppos_employees` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_messages`
--






--
-- Table structure for table `phppos_modules`
--




CREATE TABLE `phppos_modules` (
  `name_lang_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `desc_lang_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(10) NOT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `module_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`module_id`),
  UNIQUE KEY `desc_lang_key` (`desc_lang_key`),
  UNIQUE KEY `name_lang_key` (`name_lang_key`),
  KEY `all_w_sort_first` (`sort`,`module_id`,`name_lang_key`,`desc_lang_key`,`icon`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_modules`
--



INSERT INTO `phppos_modules` VALUES ('module_customers','module_customers_desc',10,'user','customers'),('module_items','module_items_desc',20,'harddrive','items'),('module_item_kits','module_item_kits_desc',30,'harddrives','item_kits'),('module_suppliers','module_suppliers_desc',40,'download','suppliers'),('module_reports','module_reports_desc',50,'bar-chart','reports'),('module_receivings','module_receivings_desc',60,'cloud-down','receivings'),('module_sales','module_sales_desc',70,'shopping-cart','sales'),('module_expenses','module_expenses_desc',75,'money','expenses'),('module_employees','module_employees_desc',80,'id-badge','employees'),('module_giftcards','module_giftcards_desc',90,'credit-card','giftcards'),('module_config','module_config_desc',100,'settings','config'),('module_locations','module_locations_desc',110,'home','locations'),('module_messages','module_messages_desc',120,'email','messages');



--
-- Table structure for table `phppos_modules_actions`
--




CREATE TABLE `phppos_modules_actions` (
  `action_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `module_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `action_name_key` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`action_id`,`module_id`),
  KEY `phppos_modules_actions_ibfk_1` (`module_id`),
  CONSTRAINT `phppos_modules_actions_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `phppos_modules` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_modules_actions`
--



INSERT INTO `phppos_modules_actions` VALUES ('add_update','customers','module_action_add_update',1),('add_update','employees','module_action_add_update',130),('add_update','expenses','module_expenses_add_update',315),('add_update','giftcards','module_action_add_update',200),('add_update','item_kits','module_action_add_update',70),('add_update','items','module_action_add_update',40),('add_update','locations','module_action_add_update',240),('add_update','suppliers','module_action_add_update',100),('assign_all_locations','employees','module_action_assign_all_locations',151),('count_inventory','items','items_count_inventory',65),('delete','customers','module_action_delete',20),('delete','employees','module_action_delete',140),('delete','expenses','module_expenses_delete',330),('delete','giftcards','module_action_delete',210),('delete','item_kits','module_action_delete',80),('delete','items','module_action_delete',50),('delete','locations','module_action_delete',250),('delete','suppliers','module_action_delete',110),('delete_receiving','receivings','module_action_delete_receiving',306),('delete_sale','sales','module_action_delete_sale',230),('delete_suspended_sale','sales','module_action_delete_suspended_sale',181),('delete_taxes','receivings','module_action_delete_taxes',300),('delete_taxes','sales','module_action_delete_taxes',182),('edit_customer_points','customers','module_edit_customer_points',35),('edit_giftcard_value','giftcards','module_edit_giftcard_value',205),('edit_quantity','items','items_edit_quantity',62),('edit_receiving','receivings','module_action_edit_receiving',303),('edit_sale','sales','module_edit_sale',190),('edit_sale_cost_price','sales','module_edit_sale_cost_price',175),('edit_sale_price','sales','module_edit_sale_price',170),('edit_store_account_balance','customers','customers_edit_store_account_balance',31),('give_discount','sales','module_give_discount',180),('manage_categories','items','items_manage_categories',70),('manage_tags','items','items_manage_tags',75),('search','customers','module_action_search_customers',30),('search','employees','module_action_search_employees',150),('search','expenses','module_expenses_search',310),('search','giftcards','module_action_search_giftcards',220),('search','item_kits','module_action_search_item_kits',90),('search','items','module_action_search_items',60),('search','locations','module_action_search_locations',260),('search','suppliers','module_action_search_suppliers',120),('see_cost_price','item_kits','module_see_cost_price',91),('see_cost_price','items','module_see_cost_price',61),('send_message','messages','employees_send_message',350),('show_cost_price','reports','reports_show_cost_price',290),('show_profit','reports','reports_show_profit',280),('view_all_employee_commissions','reports','reports_view_all_employee_commissions',107),('view_categories','reports','reports_categories',100),('view_closeout','reports','reports_closeout',105),('view_commissions','reports','reports_commission',106),('view_customers','reports','reports_customers',120),('view_deleted_sales','reports','reports_deleted_sales',130),('view_discounts','reports','reports_discounts',140),('view_employees','reports','reports_employees',150),('view_expenses','reports','module_expenses_report',155),('view_giftcards','reports','reports_giftcards',160),('view_inventory_at_all_locations','reports','reports_view_inventory_at_all_locations',300),('view_inventory_reports','reports','reports_inventory_reports',170),('view_item_kits','reports','module_item_kits',180),('view_items','reports','reports_items',190),('view_payments','reports','reports_payments',200),('view_profit_and_loss','reports','reports_profit_and_loss',210),('view_receivings','reports','reports_receivings',220),('view_register_log','reports','reports_register_log_title',230),('view_sales','reports','reports_sales',240),('view_sales_generator','reports','reports_sales_generator',110),('view_store_account','reports','reports_store_account',250),('view_suppliers','reports','reports_suppliers',260),('view_suspended_sales','reports','reports_suspended_sales',261),('view_tags','reports','common_tags',264),('view_taxes','reports','reports_taxes',270),('view_tiers','reports','reports_tiers',275),('view_timeclock','reports','employees_timeclock',280);



--
-- Table structure for table `phppos_people`
--




CREATE TABLE `phppos_people` (
  `first_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone_number` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_1` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `address_2` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `state` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `zip` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comments` text COLLATE utf8_unicode_ci NOT NULL,
  `image_id` int(10) DEFAULT NULL,
  `person_id` int(10) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`person_id`),
  KEY `first_name` (`first_name`),
  KEY `last_name` (`last_name`),
  KEY `email` (`email`),
  KEY `phppos_people_ibfk_1` (`image_id`),
  CONSTRAINT `phppos_people_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `phppos_app_files` (`file_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*!50604	ALTER TABLE `phppos_people` DROP INDEX `first_name`*/;
/*!50604	ALTER TABLE `phppos_people` DROP INDEX `last_name`*/;
/*!50604	ALTER TABLE `phppos_people` DROP INDEX `email`*/;
/*!50604	ALTER TABLE `phppos_people` ADD FULLTEXT INDEX full_search (`first_name`, `last_name`, `email`, `phone_number`)*/;	
/*!50604	ALTER TABLE `phppos_people` ADD FULLTEXT INDEX first_name_search (`first_name`)*/;
/*!50604	ALTER TABLE `phppos_people` ADD FULLTEXT INDEX last_name_search (`last_name`)*/;
/*!50604	ALTER TABLE `phppos_people` ADD FULLTEXT INDEX full_name_search (`first_name`, `last_name`)*/;
/*!50604	ALTER TABLE `phppos_people` ADD FULLTEXT INDEX email_search (`email`)*/;
/*!50604	ALTER TABLE `phppos_people` ADD FULLTEXT INDEX phone_number_search (`phone_number`)*/;
--
-- Dumping data for table `phppos_people`
--



INSERT INTO `phppos_people` VALUES ('John','Doe','555-555-5555','no-reply@bluhub.rw','Address 1','','','','','','',NULL,1);



--
-- Table structure for table `phppos_permissions`
--




CREATE TABLE `phppos_permissions` (
  `module_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `person_id` int(10) NOT NULL,
  PRIMARY KEY (`module_id`,`person_id`),
  KEY `person_id` (`person_id`),
  CONSTRAINT `phppos_permissions_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `phppos_employees` (`person_id`),
  CONSTRAINT `phppos_permissions_ibfk_2` FOREIGN KEY (`module_id`) REFERENCES `phppos_modules` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_permissions`
--



INSERT INTO `phppos_permissions` VALUES ('config',1),('customers',1),('employees',1),('expenses',1),('giftcards',1),('item_kits',1),('items',1),('locations',1),('messages',1),('receivings',1),('reports',1),('sales',1),('suppliers',1);



--
-- Table structure for table `phppos_permissions_actions`
--




CREATE TABLE `phppos_permissions_actions` (
  `module_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `person_id` int(11) NOT NULL,
  `action_id` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`module_id`,`person_id`,`action_id`),
  KEY `phppos_permissions_actions_ibfk_2` (`person_id`),
  KEY `phppos_permissions_actions_ibfk_3` (`action_id`),
  CONSTRAINT `phppos_permissions_actions_ibfk_1` FOREIGN KEY (`module_id`) REFERENCES `phppos_modules` (`module_id`),
  CONSTRAINT `phppos_permissions_actions_ibfk_2` FOREIGN KEY (`person_id`) REFERENCES `phppos_employees` (`person_id`),
  CONSTRAINT `phppos_permissions_actions_ibfk_3` FOREIGN KEY (`action_id`) REFERENCES `phppos_modules_actions` (`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_permissions_actions`
--



INSERT INTO `phppos_permissions_actions` VALUES ('customers',1,'add_update'),('customers',1,'delete'),('customers',1,'edit_customer_points'),('customers',1,'edit_store_account_balance'),('customers',1,'search'),('employees',1,'add_update'),('employees',1,'assign_all_locations'),('employees',1,'delete'),('employees',1,'search'),('expenses',1,'add_update'),('expenses',1,'delete'),('expenses',1,'search'),('giftcards',1,'add_update'),('giftcards',1,'delete'),('giftcards',1,'edit_giftcard_value'),('giftcards',1,'search'),('item_kits',1,'add_update'),('item_kits',1,'delete'),('item_kits',1,'search'),('item_kits',1,'see_cost_price'),('items',1,'add_update'),('items',1,'count_inventory'),('items',1,'delete'),('items',1,'edit_quantity'),('items',1,'manage_categories'),('items',1,'manage_tags'),('items',1,'search'),('items',1,'see_cost_price'),('locations',1,'add_update'),('locations',1,'delete'),('locations',1,'search'),('messages',1,'send_message'),('receivings',1,'delete_receiving'),('receivings',1,'delete_taxes'),('receivings',1,'edit_receiving'),('reports',1,'show_cost_price'),('reports',1,'show_profit'),('reports',1,'view_all_employee_commissions'),('reports',1,'view_categories'),('reports',1,'view_closeout'),('reports',1,'view_commissions'),('reports',1,'view_customers'),('reports',1,'view_deleted_sales'),('reports',1,'view_discounts'),('reports',1,'view_employees'),('reports',1,'view_expenses'),('reports',1,'view_giftcards'),('reports',1,'view_inventory_at_all_locations'),('reports',1,'view_inventory_reports'),('reports',1,'view_item_kits'),('reports',1,'view_items'),('reports',1,'view_payments'),('reports',1,'view_profit_and_loss'),('reports',1,'view_receivings'),('reports',1,'view_register_log'),('reports',1,'view_sales'),('reports',1,'view_sales_generator'),('reports',1,'view_store_account'),('reports',1,'view_suppliers'),('reports',1,'view_suspended_sales'),('reports',1,'view_tags'),('reports',1,'view_taxes'),('reports',1,'view_tiers'),('reports',1,'view_timeclock'),('sales',1,'delete_sale'),('sales',1,'delete_suspended_sale'),('sales',1,'delete_taxes'),('sales',1,'edit_sale'),('sales',1,'edit_sale_cost_price'),('sales',1,'edit_sale_price'),('sales',1,'give_discount'),('suppliers',1,'add_update'),('suppliers',1,'delete'),('suppliers',1,'search');



--
-- Table structure for table `phppos_price_tiers`
--




CREATE TABLE `phppos_price_tiers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `order` INT(10) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_price_tiers`
--






--
-- Table structure for table `phppos_receivings`
--




CREATE TABLE `phppos_receivings` (
  `receiving_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `supplier_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `receiving_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `deleted_by` int(10) DEFAULT NULL,
  `suspended` int(1) NOT NULL DEFAULT '0',
  `location_id` int(11) NOT NULL,
  `transfer_to_location_id` int(11) DEFAULT NULL,
  `deleted_taxes` text COLLATE utf8_unicode_ci,
  `is_po` INT(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`receiving_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `employee_id` (`employee_id`),
  KEY `deleted` (`deleted`),
  KEY `location_id` (`location_id`),
  KEY `transfer_to_location_id` (`transfer_to_location_id`),
  CONSTRAINT `phppos_receivings_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`),
  CONSTRAINT `phppos_receivings_ibfk_2` FOREIGN KEY (`supplier_id`) REFERENCES `phppos_suppliers` (`person_id`),
  CONSTRAINT `phppos_receivings_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`),
  CONSTRAINT `phppos_receivings_ibfk_4` FOREIGN KEY (`transfer_to_location_id`) REFERENCES `phppos_locations` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_receivings`
--






--
-- Table structure for table `phppos_receivings_items`
--




CREATE TABLE `phppos_receivings_items` (
  `receiving_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serialnumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(11) NOT NULL DEFAULT '0',
  `quantity_purchased` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `quantity_received` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `item_cost_price` decimal(23,10) NOT NULL,
  `item_unit_price` decimal(23,10) NOT NULL,
  `discount_percent` decimal(15,3) NOT NULL DEFAULT '0.000',
  `expire_date` date DEFAULT NULL,
  PRIMARY KEY (`receiving_id`,`item_id`,`line`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `phppos_receivings_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`),
  CONSTRAINT `phppos_receivings_items_ibfk_2` FOREIGN KEY (`receiving_id`) REFERENCES `phppos_receivings` (`receiving_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_receivings_items`
--






--
-- Table structure for table `phppos_receivings_items_taxes`
--




CREATE TABLE `phppos_receivings_items_taxes` (
  `receiving_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`receiving_id`,`item_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `phppos_receivings_items_taxes_ibfk_1` FOREIGN KEY (`receiving_id`) REFERENCES `phppos_receivings` (`receiving_id`),
  CONSTRAINT `phppos_receivings_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_receivings_items_taxes`
--






--
-- Table structure for table `phppos_register_currency_denominations`
--




CREATE TABLE `phppos_register_currency_denominations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `value` decimal(23,10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_register_currency_denominations`
--



INSERT INTO `phppos_register_currency_denominations` (`id`, `name`, `value`) VALUES
(1, '100''s', 100.0000000000),
(2, '50''s', 50.0000000000),
(3, '20''s', 20.0000000000),
(4, '10''s', 10.0000000000),
(5, '5''s', 5.0000000000),
(6, '2''s', 2.0000000000),
(7, '1''s', 1.0000000000),
(8, 'Half Dollars', 0.5000000000),
(9, 'Quarters', 0.2500000000),
(10, 'Dimes', 0.1000000000),
(11, 'Nickels', 0.0500000000),
(12, 'Pennies', 0.0100000000);

--
-- Table structure for table `phppos_register_log`
--




CREATE TABLE `phppos_register_log` (
  `register_log_id` int(10) NOT NULL AUTO_INCREMENT,
  `employee_id_open` int(10) NOT NULL,
  `employee_id_close` int(11) DEFAULT NULL,
  `register_id` int(11) DEFAULT NULL,
  `shift_start` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `shift_end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `open_amount` decimal(23,10) NOT NULL,
  `close_amount` decimal(23,10) NOT NULL,
  `cash_sales_amount` decimal(23,10) NOT NULL,
  `total_cash_additions` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `total_cash_subtractions` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `notes` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`register_log_id`),
  KEY `phppos_register_log_ibfk_1` (`employee_id_open`),
  KEY `phppos_register_log_ibfk_2` (`register_id`),
  KEY `phppos_register_log_ibfk_3` (`employee_id_close`),
  CONSTRAINT `phppos_register_log_ibfk_1` FOREIGN KEY (`employee_id_open`) REFERENCES `phppos_employees` (`person_id`),
  CONSTRAINT `phppos_register_log_ibfk_2` FOREIGN KEY (`register_id`) REFERENCES `phppos_registers` (`register_id`),
  CONSTRAINT `phppos_register_log_ibfk_3` FOREIGN KEY (`employee_id_close`) REFERENCES `phppos_employees` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_register_log`
--






--
-- Table structure for table `phppos_register_log_audit`
--




CREATE TABLE `phppos_register_log_audit` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `register_log_id` int(10) NOT NULL,
  `employee_id` int(10) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `amount` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `note` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `register_log_audit_ibfk_1` (`register_log_id`),
  KEY `register_log_audit_ibfk_2` (`employee_id`),
  CONSTRAINT `register_log_audit_ibfk_1` FOREIGN KEY (`register_log_id`) REFERENCES `phppos_register_log` (`register_log_id`),
  CONSTRAINT `register_log_audit_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_register_log_audit`
--






--
-- Table structure for table `phppos_registers`
--




CREATE TABLE `phppos_registers` (
  `register_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`register_id`),
  KEY `deleted` (`deleted`),
  KEY `phppos_registers_ibfk_1` (`location_id`),
  CONSTRAINT `phppos_registers_ibfk_1` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_registers`
--



INSERT INTO `phppos_registers` VALUES (1,1,'Default',0);



--
-- Table structure for table `phppos_sales`
--




CREATE TABLE `phppos_sales` (
  `sale_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT '0',
  `sold_by_employee_id` int(10) DEFAULT NULL,
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  `show_comment_on_receipt` int(1) NOT NULL DEFAULT '0',
  `sale_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` TEXT CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `cc_ref_no` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `auth_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `deleted_by` int(10) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `suspended` int(1) NOT NULL DEFAULT '0',
  `store_account_payment` int(1) NOT NULL DEFAULT '0',
  `was_layaway` int(1) NOT NULL DEFAULT '0',
  `was_estimate` int(1) NOT NULL DEFAULT '0',
  `location_id` int(11) NOT NULL,
  `register_id` int(11) DEFAULT NULL,
  `tier_id` int(10) DEFAULT NULL,
  `points_used` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `points_gained` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `did_redeem_discount` int(1) NOT NULL DEFAULT '0',
  `signature_image_id` int(10) DEFAULT NULL,
  `deleted_taxes` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`sale_id`),
  KEY `customer_id` (`customer_id`),
  KEY `employee_id` (`employee_id`),
  KEY `deleted` (`deleted`),
  KEY `location_id` (`location_id`),
  KEY `phppos_sales_ibfk_4` (`deleted_by`),
  KEY `sales_search` (`location_id`,`store_account_payment`,`sale_time`,`sale_id`),
  KEY `phppos_sales_ibfk_5` (`tier_id`),
  KEY `phppos_sales_ibfk_7` (`register_id`),
  KEY `phppos_sales_ibfk_6` (`sold_by_employee_id`),
  KEY `phppos_sales_ibfk_8` (`signature_image_id`),
  KEY `was_layaway` (`was_layaway`),
  KEY `was_estimate` (`was_estimate`),
  CONSTRAINT `phppos_sales_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `phppos_employees` (`person_id`),
  CONSTRAINT `phppos_sales_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `phppos_customers` (`person_id`),
  CONSTRAINT `phppos_sales_ibfk_3` FOREIGN KEY (`location_id`) REFERENCES `phppos_locations` (`location_id`),
  CONSTRAINT `phppos_sales_ibfk_4` FOREIGN KEY (`deleted_by`) REFERENCES `phppos_employees` (`person_id`),
  CONSTRAINT `phppos_sales_ibfk_5` FOREIGN KEY (`tier_id`) REFERENCES `phppos_price_tiers` (`id`),
  CONSTRAINT `phppos_sales_ibfk_6` FOREIGN KEY (`sold_by_employee_id`) REFERENCES `phppos_employees` (`person_id`),
  CONSTRAINT `phppos_sales_ibfk_7` FOREIGN KEY (`register_id`) REFERENCES `phppos_registers` (`register_id`),
  CONSTRAINT `phppos_sales_ibfk_8` FOREIGN KEY (`signature_image_id`) REFERENCES `phppos_app_files` (`file_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_sales`
--






--
-- Table structure for table `phppos_sales_item_kits`
--




CREATE TABLE `phppos_sales_item_kits` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_kit_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(11) NOT NULL DEFAULT '0',
  `quantity_purchased` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `item_kit_cost_price` decimal(23,10) NOT NULL,
  `item_kit_unit_price` decimal(23,10) NOT NULL,
  `discount_percent` decimal(15,3) NOT NULL DEFAULT '0.000',
  `commission` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  PRIMARY KEY (`sale_id`,`item_kit_id`,`line`),
  KEY `item_kit_id` (`item_kit_id`),
  CONSTRAINT `phppos_sales_item_kits_ibfk_1` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`),
  CONSTRAINT `phppos_sales_item_kits_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_sales_item_kits`
--






--
-- Table structure for table `phppos_sales_item_kits_taxes`
--




CREATE TABLE `phppos_sales_item_kits_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_kit_id` int(10) NOT NULL,
  `line` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`item_kit_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_kit_id`),
  CONSTRAINT `phppos_sales_item_kits_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales_item_kits` (`sale_id`),
  CONSTRAINT `phppos_sales_item_kits_taxes_ibfk_2` FOREIGN KEY (`item_kit_id`) REFERENCES `phppos_item_kits` (`item_kit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_sales_item_kits_taxes`
--






--
-- Table structure for table `phppos_sales_items`
--




CREATE TABLE `phppos_sales_items` (
  `sale_id` int(10) NOT NULL DEFAULT '0',
  `item_id` int(10) NOT NULL DEFAULT '0',
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `serialnumber` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `line` int(11) NOT NULL DEFAULT '0',
  `quantity_purchased` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `item_cost_price` decimal(23,10) NOT NULL,
  `item_unit_price` decimal(23,10) NOT NULL,
  `discount_percent` decimal(15,3) NOT NULL DEFAULT '0.000',
  `commission` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  PRIMARY KEY (`sale_id`,`item_id`,`line`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `phppos_sales_items_ibfk_1` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`),
  CONSTRAINT `phppos_sales_items_ibfk_2` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_sales_items`
--






--
-- Table structure for table `phppos_sales_items_taxes`
--




CREATE TABLE `phppos_sales_items_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`sale_id`,`item_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_id`),
  CONSTRAINT `phppos_sales_items_taxes_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales_items` (`sale_id`),
  CONSTRAINT `phppos_sales_items_taxes_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `phppos_items` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_sales_items_taxes`
--






--
-- Table structure for table `phppos_sales_payments`
--




CREATE TABLE `phppos_sales_payments` (
  `payment_id` int(10) NOT NULL AUTO_INCREMENT,
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_amount` decimal(23,10) NOT NULL,
  `auth_code` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `ref_no` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `cc_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `acq_ref_data` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `process_data` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',  
  `entry_method` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',    
  `aid` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',    
  `tvr` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',   
  `iad` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',    
  `tsi` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',    
  `arc` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',    
  `cvm` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `tran_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',    
  `application_label` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',   
  `truncated_card` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `card_issuer` varchar(255) COLLATE utf8_unicode_ci DEFAULT '',
  `payment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`payment_id`),
  KEY `sale_id` (`sale_id`),
  CONSTRAINT `phppos_sales_payments_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales` (`sale_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_sales_payments`
--






--
-- Table structure for table `phppos_sessions`
--




CREATE TABLE `phppos_sessions` (
  `id` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8_unicode_ci NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT '0',
  `data` LONGBLOB NOT NULL,
  PRIMARY KEY (`id`),
  KEY `phppos_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_sessions`
--






--
-- Table structure for table `phppos_store_accounts`
--




CREATE TABLE `phppos_store_accounts` (
  `sno` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `sale_id` int(11) DEFAULT NULL,
  `transaction_amount` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `balance` decimal(23,10) NOT NULL DEFAULT '0.0000000000',
  `comment` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`sno`),
  KEY `phppos_store_accounts_ibfk_1` (`sale_id`),
  KEY `phppos_store_accounts_ibfk_2` (`customer_id`),
  CONSTRAINT `phppos_store_accounts_ibfk_1` FOREIGN KEY (`sale_id`) REFERENCES `phppos_sales` (`sale_id`),
  CONSTRAINT `phppos_store_accounts_ibfk_2` FOREIGN KEY (`customer_id`) REFERENCES `phppos_customers` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


--
-- Dumping data for table `phppos_store_accounts`
--






--
-- Table structure for table `phppos_suppliers`
--




CREATE TABLE `phppos_suppliers` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `person_id` int(10) NOT NULL,
  `company_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `account_number` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `override_default_tax` int(1) NOT NULL DEFAULT '0',
  `deleted` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`),
  KEY `deleted` (`deleted`),
  CONSTRAINT `phppos_suppliers_ibfk_1` FOREIGN KEY (`person_id`) REFERENCES `phppos_people` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!50604	ALTER TABLE `phppos_suppliers` ADD FULLTEXT INDEX full_search (`account_number`, `company_name`)*/;	
/*!50604	ALTER TABLE `phppos_suppliers` ADD FULLTEXT INDEX company_name_search (`company_name`)*/;
/*!50604	ALTER TABLE `phppos_suppliers` ADD FULLTEXT INDEX account_number_search (`account_number`)*/;
--
-- Dumping data for table `phppos_suppliers`
--


CREATE TABLE `phppos_suppliers_taxes` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(10) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  `cumulative` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_tax` (`supplier_id`,`name`,`percent`),
  CONSTRAINT `phppos_suppliers_taxes_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `phppos_suppliers` (`person_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;




--
-- Table structure for table `phppos_tags`
--




CREATE TABLE `phppos_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `deleted` int(1) NOT NULL DEFAULT '0',
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tag_name` (`name`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*!50604	ALTER TABLE `phppos_tags` ADD FULLTEXT INDEX name_search (`name`)*/;		
--
-- Dumping data for table `phppos_tags`
--

CREATE TABLE `phppos_registers_cart` ( 
	`id` INT(10) NOT NULL AUTO_INCREMENT , 
	`register_id` INT NOT NULL , 
	`data` LONGBLOB NOT NULL,
   UNIQUE KEY `register_id` (register_id),
   CONSTRAINT `phppos_registers_cart_ibfk_1` FOREIGN KEY (`register_id`) REFERENCES `phppos_registers` (`register_id`),
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


/*!50604 REPLACE INTO `phppos_app_config` (`key`, `value`) VALUES ('supports_full_text', '1')*/;




SET FOREIGN_KEY_CHECKS=1;