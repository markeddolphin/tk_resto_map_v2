<?php
$date_default = "datetime NOT NULL DEFAULT CURRENT_TIMESTAMP";
if($res=$DbExt->rst("SELECT VERSION() as mysql_version")){
	$res=$res[0];			
	$mysql_version = (float)$res['mysql_version'];
	//dump("MYSQL VERSION=>$mysql_version");
	if($mysql_version<=5.5){				
		$date_default="datetime NOT NULL DEFAULT '0000-00-00 00:00:00'";
	}
}
		
$tbl['address_book']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."address_book (
  `id` int(14) NOT NULL,
  `client_id` int(14) NOT NULL DEFAULT '0',
  `street` varchar(255) NOT NULL DEFAULT '',
  `city` varchar(255) NOT NULL DEFAULT '',
  `state` varchar(255) NOT NULL DEFAULT '',
  `zipcode` varchar(255) NOT NULL DEFAULT '',
  `location_name` varchar(255) NOT NULL DEFAULT '',
  `country_code` varchar(3) NOT NULL DEFAULT '',
  `as_default` int(1) NOT NULL DEFAULT '1',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(100) NOT NULL DEFAULT '',
  `latitude` varchar(255) NOT NULL DEFAULT '',
  `longitude` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."address_book
ADD PRIMARY KEY (`id`),
ADD KEY `client_id` (`client_id`),
ADD KEY `as_default` (`as_default`);

ALTER TABLE  ".$table_prefix."address_book
MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['admin_user']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."admin_user (
  `admin_id` int(14) NOT NULL,
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `last_name` varchar(255) NOT NULL DEFAULT '',
  `role` varchar(100) NOT NULL DEFAULT '',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `user_lang` int(14) NOT NULL DEFAULT '0',
  `email_address` varchar(255) NOT NULL DEFAULT '',
  `lost_password_code` varchar(255) NOT NULL DEFAULT '',
  `session_token` varchar(255) NOT NULL DEFAULT '',
  `last_login` $date_default,
  `user_access` text,
  `status` varchar(100) NOT NULL DEFAULT 'active',
  `contact_number` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE  ".$table_prefix."admin_user
  ADD PRIMARY KEY (`admin_id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `username` (`username`),
  ADD KEY `password` (`password`),
  ADD KEY `lost_password_code` (`lost_password_code`),
  ADD KEY `session_token` (`session_token`);

ALTER TABLE  ".$table_prefix."admin_user
  MODIFY `admin_id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['bank_deposit']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."bank_deposit (
  `id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `branch_code` varchar(100) NOT NULL DEFAULT '',
  `date_of_deposit` date DEFAULT NULL,
  `time_of_deposit` varchar(50) NOT NULL DEFAULT '',
  `amount` float(14,4) NOT NULL DEFAULT '0.0000',
  `scanphoto` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `date_created` date DEFAULT NULL,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `transaction_type` varchar(255) NOT NULL DEFAULT 'merchant_signup',
  `client_id` int(14) NOT NULL DEFAULT '0',
  `order_id` int(14) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."bank_deposit
  ADD PRIMARY KEY (`id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `status` (`status`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `order_id` (`order_id`);

ALTER TABLE  ".$table_prefix."bank_deposit
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['barclay_trans']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."barclay_trans (
  `id` int(14) NOT NULL,
  `orderid` varchar(14) NOT NULL DEFAULT '',
  `token` varchar(255) NOT NULL DEFAULT '',
  `transaction_type` varchar(255) NOT NULL DEFAULT 'signup',
  `date_created` date DEFAULT NULL,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `param1` varchar(255) NOT NULL DEFAULT '',
  `param2` text ,
  `param3` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."barclay_trans
  ADD PRIMARY KEY (`id`),
  ADD KEY `orderid` (`orderid`),
  ADD KEY `token` (`token`),
  ADD KEY `transaction_type` (`transaction_type`);
  
ALTER TABLE  ".$table_prefix."barclay_trans
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['bookingtable']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."bookingtable (
  `booking_id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `number_guest` int(14) NOT NULL DEFAULT '0',
  `date_booking` date DEFAULT NULL,
  `booking_time` varchar(50) NOT NULL DEFAULT '',
  `booking_name` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `mobile` varchar(100) NOT NULL DEFAULT '',
  `booking_notes` text,
  `date_created` $date_default,
  `ip_address` varchar(100) NOT NULL DEFAULT '',
  `date_modified` $date_default,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `viewed` int(1) NOT NULL DEFAULT '1',
  `client_id` int(14) NOT NULL DEFAULT '0',
  `request_cancel` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."bookingtable
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `status` (`status`),
  ADD KEY `viewed` (`viewed`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `date_booking` (`date_booking`);
  
ALTER TABLE  ".$table_prefix."bookingtable
  MODIFY `booking_id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['bookingtable_history']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."bookingtable_history (
  `id` int(14) NOT NULL,
  `booking_id` int(14) NOT NULL DEFAULT '0',
  `status` varchar(255) NOT NULL DEFAULT '',
  `remarks` varchar(255) NOT NULL DEFAULT '',
  `date_created` $date_default
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE  ".$table_prefix."bookingtable_history
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `status` (`status`);

ALTER TABLE  ".$table_prefix."bookingtable_history
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['category']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."category (
  `cat_id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `category_name` varchar(255) NOT NULL DEFAULT '',
  `category_description` text,
  `photo` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(100) NOT NULL DEFAULT '',
  `sequence` int(14) NOT NULL DEFAULT '0',
  `date_created` varchar(50) NOT NULL DEFAULT '',
  `date_modified` varchar(50) DEFAULT '',
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `spicydish` int(2) NOT NULL DEFAULT '1',
  `spicydish_notes` text,
  `dish` text,
  `category_name_trans` text,
  `category_description_trans` text,
  `parent_cat_id` int(14) NOT NULL DEFAULT '0',
  `monday` int(1) NOT NULL DEFAULT '0',
  `tuesday` int(1) NOT NULL DEFAULT '0',
  `wednesday` int(1) NOT NULL DEFAULT '0',
  `thursday` int(1) NOT NULL DEFAULT '0',
  `friday` int(1) NOT NULL DEFAULT '0',
  `saturday` int(1) NOT NULL DEFAULT '0',
  `sunday` int(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE  ".$table_prefix."category
  ADD PRIMARY KEY (`cat_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `category_name` (`category_name`),
  ADD KEY `status` (`status`),
  ADD KEY `sequence` (`sequence`),
  ADD KEY `parent_cat_id` (`parent_cat_id`);
  
ALTER TABLE  ".$table_prefix."category
  MODIFY `cat_id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['client']="

CREATE TABLE IF NOT EXISTS  ".$table_prefix."client (
  `client_id` int(14) NOT NULL,
  `social_strategy` varchar(100) NOT NULL DEFAULT 'web',
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `last_name` varchar(255) NOT NULL DEFAULT '',
  `email_address` varchar(200) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `street` varchar(255) NOT NULL DEFAULT '',
  `city` varchar(255) NOT NULL DEFAULT '',
  `state` varchar(255) NOT NULL DEFAULT '',
  `zipcode` varchar(100) NOT NULL DEFAULT '',
  `country_code` varchar(3) NOT NULL DEFAULT '',
  `location_name` text,
  `contact_phone` varchar(20) NOT NULL DEFAULT '',
  `lost_password_token` varchar(255) NOT NULL DEFAULT '',
  `date_created` $date_default,
  `date_modified` $date_default,
  `last_login` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `status` varchar(100) NOT NULL DEFAULT 'active',
  `token` varchar(255) NOT NULL DEFAULT '',
  `mobile_verification_code` int(14) NOT NULL DEFAULT '0',
  `mobile_verification_date` $date_default,
  `custom_field1` varchar(255) NOT NULL DEFAULT '',
  `custom_field2` varchar(255) NOT NULL DEFAULT '',
  `avatar` varchar(255) NOT NULL DEFAULT '',
  `email_verification_code` varchar(14) NOT NULL DEFAULT '',
  `is_guest` int(1) NOT NULL DEFAULT '2',
  `payment_customer_id` varchar(255) NOT NULL DEFAULT '',
  `social_id` varchar(255) NOT NULL DEFAULT '',
  `verify_code_requested` $date_default,
  `single_app_merchant_id` int(14) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE  ".$table_prefix."client
  ADD PRIMARY KEY (`client_id`),
  ADD KEY `social_strategy` (`social_strategy`),
  ADD KEY `email_address` (`email_address`),
  ADD KEY `password` (`password`),
  ADD KEY `street` (`street`),
  ADD KEY `city` (`city`),
  ADD KEY `state` (`state`),
  ADD KEY `contact_phone` (`contact_phone`),
  ADD KEY `lost_password_token` (`lost_password_token`),
  ADD KEY `status` (`status`),
  ADD KEY `token` (`token`),
  ADD KEY `mobile_verification_code` (`mobile_verification_code`),
  ADD KEY `is_guest` (`is_guest`),
  ADD KEY `email_verification_code` (`email_verification_code`);
  
ALTER TABLE  ".$table_prefix."client
  MODIFY `client_id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['client_cc']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."client_cc (
  `cc_id` int(14) NOT NULL,
  `client_id` int(14) NOT NULL DEFAULT '0',
  `card_name` varchar(255) NOT NULL DEFAULT '',
  `credit_card_number` varchar(20) NOT NULL DEFAULT '',
  `expiration_month` varchar(5) NOT NULL DEFAULT '',
  `expiration_yr` varchar(5) NOT NULL DEFAULT '',
  `cvv` varchar(20) NOT NULL DEFAULT '',
  `billing_address` varchar(255) NOT NULL DEFAULT '',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `encrypted_card` binary(255) NOT NULL DEFAULT '\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."client_cc
  ADD PRIMARY KEY (`cc_id`),
  ADD KEY `client_id` (`client_id`);
  
ALTER TABLE  ".$table_prefix."client_cc
  MODIFY `cc_id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['cooking_ref']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."cooking_ref (
`cook_id` int(14) NOT NULL,
`merchant_id` int(14) NOT NULL DEFAULT '0',
`cooking_name` varchar(255) NOT NULL DEFAULT '',
`sequence` int(14) NOT NULL DEFAULT '0',
`date_created` $date_default,
`date_modified` $date_default,
`status` varchar(50) NOT NULL DEFAULT 'published',
`ip_address` varchar(50) NOT NULL DEFAULT '',
`cooking_name_trans` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE  ".$table_prefix."cooking_ref
ADD PRIMARY KEY (`cook_id`),
ADD KEY `merchant_id` (`merchant_id`),
ADD KEY `cooking_name` (`cooking_name`),
ADD KEY `sequence` (`sequence`),
ADD KEY `status` (`status`);

ALTER TABLE  ".$table_prefix."cooking_ref
MODIFY `cook_id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['cuisine']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."cuisine (
  `cuisine_id` int(14) NOT NULL DEFAULT '0',
  `cuisine_name` varchar(255) NOT NULL DEFAULT '',
  `sequence` int(14) NOT NULL DEFAULT '0',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `cuisine_name_trans` text,
  `status` varchar(100) NOT NULL DEFAULT 'publish',
  `featured_image` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."cuisine
  ADD PRIMARY KEY (`cuisine_id`),
  ADD KEY `cuisine_name` (`cuisine_name`),
  ADD KEY `sequence` (`sequence`);
  
ALTER TABLE  ".$table_prefix."cuisine
  MODIFY `cuisine_id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['currency']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."currency (
  `id` int(14) NOT NULL DEFAULT '0',
  `currency_code` varchar(3) NOT NULL DEFAULT '',
  `currency_symbol` varchar(100) NOT NULL DEFAULT '',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."currency
  ADD PRIMARY KEY (`id`),
  ADD KEY `currency_symbol` (`currency_symbol`),
  ADD KEY `currency_code` (`currency_code`);
  
ALTER TABLE  ".$table_prefix."currency
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;    
";

$tbl['custom_page']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."custom_page (
  `id` int(14) NOT NULL,
  `slug_name` varchar(255) NOT NULL DEFAULT '',
  `page_name` varchar(255) NOT NULL DEFAULT '',
  `content` text ,
  `seo_title` varchar(255) NOT NULL DEFAULT '',
  `meta_description` varchar(255) NOT NULL DEFAULT '',
  `meta_keywords` varchar(255) NOT NULL DEFAULT '',
  `icons` varchar(255) NOT NULL DEFAULT '',
  `assign_to` varchar(50) NOT NULL DEFAULT '',
  `sequence` int(14) NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL DEFAULT 'pending',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(100) NOT NULL DEFAULT '',
  `open_new_tab` int(11) NOT NULL DEFAULT '1',
  `is_custom_link` int(2) NOT NULL DEFAULT '1',
  `page_name_trans` text ,
  `content_trans` text ,
  `seo_title_trans` text ,
  `meta_description_trans` text ,
  `meta_keywords_trans` text 
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."custom_page
  ADD PRIMARY KEY (`id`),
  ADD KEY `slug_name` (`slug_name`);
  
ALTER TABLE  ".$table_prefix."custom_page
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['dishes']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."dishes (
  `dish_id` int(14) NOT NULL,
  `dish_name` varchar(255) NOT NULL DEFAULT '',
  `photo` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(100) NOT NULL DEFAULT '',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."dishes
  ADD PRIMARY KEY (`dish_id`),
  ADD KEY `dish_name` (`dish_name`);
  
  ALTER TABLE  ".$table_prefix."dishes
  MODIFY `dish_id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['email_logs']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."email_logs (
  `id` int(14) NOT NULL,
  `email_address` varchar(255) NOT NULL DEFAULT '',
  `sender` varchar(255) NOT NULL DEFAULT '',
  `subject` varchar(255) NOT NULL DEFAULT '',
  `content` text,
  `status` varchar(200) NOT NULL DEFAULT 'pending',
  `date_created` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `module_type` varchar(255) NOT NULL DEFAULT '',
  `user_type` varchar(100) NOT NULL DEFAULT '',
  `user_id` int(14) NOT NULL DEFAULT '0',
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `email_provider` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE  ".$table_prefix."email_logs
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `user_type` (`user_type`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `module_type` (`module_type`),
  ADD KEY `email_address` (`email_address`);

  
ALTER TABLE  ".$table_prefix."email_logs
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['fax_broadcast']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."fax_broadcast (
  `id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `faxno` varchar(50) NOT NULL DEFAULT '',
  `recipname` varchar(32) NOT NULL DEFAULT '',
  `faxurl` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `jobid` varchar(255) NOT NULL DEFAULT '',
  `api_raw_response` text,
  `date_created` $date_default,
  `date_process` $date_default,
  `date_postback` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE  ".$table_prefix."fax_broadcast
  ADD PRIMARY KEY (`id`),
  ADD KEY `merchant_id` (`merchant_id`);
  
ALTER TABLE  ".$table_prefix."fax_broadcast
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['fax_package']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."fax_package (
  `fax_package_id` int(14) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text ,
  `price` float(14,4) NOT NULL DEFAULT '0.0000',
  `promo_price` float(14,4) NOT NULL DEFAULT '0.0000',
  `fax_limit` int(14) NOT NULL DEFAULT '0',
  `sequence` int(14) NOT NULL DEFAULT '0',
  `status` varchar(100) NOT NULL DEFAULT '',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."fax_package
  ADD PRIMARY KEY (`fax_package_id`),
  ADD KEY `title` (`title`),
  ADD KEY `status` (`status`);
  
ALTER TABLE  ".$table_prefix."fax_package
  MODIFY `fax_package_id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['fax_package_trans']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."fax_package_trans (
  `id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `fax_package_id` int(14) NOT NULL DEFAULT '0',
  `payment_type` varchar(50) NOT NULL DEFAULT '',
  `package_price` float(14,4) NOT NULL DEFAULT '0.0000',
  `fax_limit` int(14) NOT NULL DEFAULT '0',
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `payment_reference` varchar(255) NOT NULL DEFAULT '',
  `payment_gateway_response` text,
  `date_created` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."fax_package_trans
  ADD PRIMARY KEY (`id`);

ALTER TABLE  ".$table_prefix."fax_package_trans
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['ingredients']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."ingredients (
  `ingredients_id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `ingredients_name` varchar(255) NOT NULL DEFAULT '',
  `sequence` int(14) NOT NULL DEFAULT '0',
  `date_created` $date_default,
  `date_modified` $date_default,
  `status` varchar(50) NOT NULL DEFAULT 'published',
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `ingredients_name_trans` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."ingredients
  ADD PRIMARY KEY (`ingredients_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `status` (`status`),
  ADD KEY `ingredients_name` (`ingredients_name`);

ALTER TABLE  ".$table_prefix."ingredients
  MODIFY `ingredients_id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['invoice']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."invoice (
  `invoice_number` int(14) NOT NULL,
  `invoice_token` varchar(100) NOT NULL DEFAULT '',
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `merchant_name` varchar(255) NOT NULL DEFAULT '',
  `merchant_contact_email` varchar(200) NOT NULL DEFAULT '',
  `merchant_contact_phone` varchar(50) NOT NULL DEFAULT '',
  `invoice_terms` int(14) NOT NULL DEFAULT '0',
  `invoice_total` float(14,4) NOT NULL DEFAULT '0.0000',
  `date_from` date DEFAULT NULL,
  `date_to` date DEFAULT NULL,
  `pdf_filename` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `viewed` varchar(2) NOT NULL DEFAULT '2',
  `date_created` $date_default,
  `date_process` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE ".$table_prefix."invoice
  ADD PRIMARY KEY (`invoice_number`),
  ADD KEY `invoice_token` (`invoice_token`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `status` (`status`),
  ADD KEY `date_from` (`date_from`),
  ADD KEY `date_to` (`date_to`),
  ADD KEY `invoice_total` (`invoice_total`);
  
ALTER TABLE  ".$table_prefix."invoice
  MODIFY `invoice_number` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['invoice_history']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."invoice_history (
  `id` int(14) NOT NULL,
  `invoice_number` varchar(14) NOT NULL DEFAULT '',
  `payment_status` varchar(100) NOT NULL DEFAULT '',
  `remarks` varchar(255) NOT NULL DEFAULT '',
  `date_created` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE  ".$table_prefix."invoice_history
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoice_number` (`invoice_number`),
  ADD KEY `payment_status` (`payment_status`);  
 
ALTER TABLE  ".$table_prefix."invoice_history
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['item']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."item (
  `item_id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `item_name` varchar(255) NOT NULL DEFAULT '',
  `item_description` text,
  `status` varchar(50) NOT NULL DEFAULT '',
  `category` text,
  `price` text,
  `addon_item` text,
  `cooking_ref` text,
  `discount` varchar(14) NOT NULL DEFAULT '',
  `multi_option` text,
  `multi_option_value` text,
  `photo` varchar(255) NOT NULL DEFAULT '',
  `sequence` int(14) NOT NULL DEFAULT '0',
  `is_featured` varchar(1) NOT NULL DEFAULT '',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `ingredients` text,
  `spicydish` int(2) NOT NULL DEFAULT '1',
  `two_flavors` int(2) NOT NULL,
  `two_flavors_position` text,
  `require_addon` text,
  `dish` text,
  `item_name_trans` text,
  `item_description_trans` text,
  `non_taxable` int(1) NOT NULL DEFAULT '1',
  `not_available` int(1) NOT NULL DEFAULT '1',
  `gallery_photo` text,
  `points_earned` int(14) NOT NULL DEFAULT '0',
  `points_disabled` int(1) NOT NULL DEFAULT '1',
  `packaging_fee` float(14,4) NOT NULL DEFAULT '0.0000',
  `packaging_incremental` int(1) NOT NULL DEFAULT '0',
  `item_token` varchar(50) NOT NULL DEFAULT '',
  `with_size` integer(1) NOT NULL DEFAULT '0',
  `track_stock` integer(1) NOT NULL DEFAULT '1',
  `supplier_id` integer(14) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."item
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `item_name` (`item_name`),
  ADD KEY `status` (`status`),
  ADD KEY `is_featured` (`is_featured`),
  ADD KEY `spicydish` (`spicydish`),
  ADD KEY `two_flavors` (`two_flavors`),
  ADD KEY `points_earned` (`points_earned`),
  ADD KEY `points_disabled` (`points_disabled`);

ALTER TABLE  ".$table_prefix."item
  MODIFY `item_id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['location_area']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."location_area (
  `area_id` int(14) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `city_id` int(14) NOT NULL DEFAULT '0',
  `sequence` int(14) NOT NULL DEFAULT '0',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."location_area
  ADD PRIMARY KEY (`area_id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `sequence` (`sequence`),
  ADD KEY `name` (`name`);

ALTER TABLE  ".$table_prefix."location_area
  MODIFY `area_id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['location_cities']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."location_cities (
  `city_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '',
  `postal_code` varchar(255) NOT NULL DEFAULT '',
  `state_id` int(11) NOT NULL,
  `sequence` int(14) NOT NULL DEFAULT '0',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE  ".$table_prefix."location_cities
  ADD PRIMARY KEY (`city_id`),
  ADD KEY `postal_code` (`postal_code`),
  ADD KEY `state_id` (`state_id`),
  ADD KEY `sequence` (`sequence`),
  ADD KEY `name` (`name`);

ALTER TABLE  ".$table_prefix."location_cities
  MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT;  
";

$tbl['location_countries']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."location_countries (
  `country_id` int(11) NOT NULL,
  `shortcode` varchar(3) NOT NULL DEFAULT '',
  `country_name` varchar(150) NOT NULL DEFAULT '',
  `phonecode` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."location_countries
  ADD PRIMARY KEY (`country_id`),
  ADD KEY `shortcode` (`shortcode`);


ALTER TABLE  ".$table_prefix."location_countries
  MODIFY `country_id` int(11) NOT NULL AUTO_INCREMENT;
";

$tbl['location_rate']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."location_rate (
  `rate_id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `country_id` int(14) NOT NULL DEFAULT '0',
  `state_id` int(14) NOT NULL DEFAULT '0',
  `city_id` int(14) DEFAULT '0',
  `area_id` int(14) NOT NULL DEFAULT '0',
  `fee` float(14,5) NOT NULL DEFAULT '0.00000',
  `sequence` int(14) NOT NULL DEFAULT '0',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."location_rate
  ADD PRIMARY KEY (`rate_id`);
  
ALTER TABLE  ".$table_prefix."location_rate
  MODIFY `rate_id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['location_states']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."location_states (
  `state_id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL DEFAULT '',
  `country_id` int(11) NOT NULL DEFAULT '1',
  `sequence` int(14) NOT NULL DEFAULT '0',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE  ".$table_prefix."location_states
  ADD PRIMARY KEY (`state_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `sequence` (`sequence`),
  ADD KEY `name` (`name`);
  
ALTER TABLE  ".$table_prefix."location_states
  MODIFY `state_id` int(11) NOT NULL AUTO_INCREMENT;
";

$tbl['merchant']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."merchant (
  `merchant_id` int(14) NOT NULL,
  `restaurant_slug` varchar(255) NOT NULL DEFAULT '',
  `restaurant_name` varchar(255) NOT NULL DEFAULT '',
  `restaurant_phone` varchar(100) NOT NULL DEFAULT '',
  `contact_name` varchar(255) NOT NULL DEFAULT '',
  `contact_phone` varchar(100) NOT NULL DEFAULT '',
  `contact_email` varchar(255) NOT NULL DEFAULT '',
  `country_code` varchar(3) NOT NULL DEFAULT '',
  `street` text,
  `city` varchar(255) NOT NULL DEFAULT '',
  `state` varchar(255) NOT NULL DEFAULT '',
  `post_code` varchar(100) NOT NULL DEFAULT '',
  `cuisine` text,
  `service` varchar(255) NOT NULL DEFAULT '',
  `free_delivery` int(1) NOT NULL DEFAULT '2',
  `delivery_estimation` varchar(100) NOT NULL DEFAULT '',
  `username` varchar(100) NOT NULL DEFAULT '',
  `password` varchar(100) NOT NULL DEFAULT '',
  `activation_key` varchar(50) NOT NULL DEFAULT '',
  `activation_token` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `date_created` $date_default,
  `date_modified` $date_default,
  `date_activated` $date_default,
  `last_login` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `package_id` int(14) NOT NULL DEFAULT '0',
  `package_price` float(14,5) NOT NULL DEFAULT '0.0000',
  `membership_expired` date DEFAULT NULL,
  `payment_steps` int(1) NOT NULL DEFAULT '1',
  `is_featured` int(1) NOT NULL DEFAULT '1',
  `is_ready` int(1) NOT NULL DEFAULT '1',
  `is_sponsored` int(2) NOT NULL DEFAULT '1',
  `sponsored_expiration` date DEFAULT NULL,
  `lost_password_code` varchar(50) NOT NULL DEFAULT '',
  `user_lang` int(14) NOT NULL DEFAULT '0',
  `membership_purchase_date` $date_default,
  `sort_featured` int(14) NOT NULL DEFAULT '0',
  `is_commission` int(1) NOT NULL DEFAULT '1',
  `percent_commision` float(14,5) NOT NULL DEFAULT '0.0000',
  `abn` varchar(255) NOT NULL DEFAULT '',
  `session_token` varchar(255) NOT NULL DEFAULT '',
  `commision_type` varchar(50) NOT NULL DEFAULT 'percentage',
  `mobile_session_token` varchar(255) NOT NULL DEFAULT '',
  `merchant_key` varchar(255) NOT NULL DEFAULT '',
  `latitude` varchar(50) NOT NULL DEFAULT '',
  `lontitude` varchar(50) NOT NULL DEFAULT '',
  `delivery_charges` float(14,5) NOT NULL DEFAULT '0.00000',
  `minimum_order` float(14,5) NOT NULL DEFAULT '0.00000',
  `delivery_minimum_order` float(14,5) NOT NULL DEFAULT '0.00000',
  `delivery_maximum_order` float(14,5) NOT NULL DEFAULT '0.00000',
  `pickup_minimum_order` float(14,5) NOT NULL DEFAULT '0.00000',
  `pickup_maximum_order` float(14,5) NOT NULL DEFAULT '0.00000',
  `country_name` varchar(255) NOT NULL DEFAULT '',
  `country_id` int(14) NOT NULL DEFAULT '0',
  `state_id` int(14) NOT NULL DEFAULT '0',
  `city_id` int(14) NOT NULL DEFAULT '0',
  `area_id` int(14) NOT NULL DEFAULT '0',
  `logo` varchar(255) NOT NULL DEFAULT '',
  `merchant_type` int(1) NOT NULL DEFAULT '1',
  `invoice_terms` int(14) NOT NULL DEFAULT '7',
  `payment_gateway_ref` varchar(255) NOT NULL DEFAULT '',
  `user_access` text,
  `distance_unit` varchar(20) NOT NULL DEFAULT 'mi',
  `delivery_distance_covered` float(14,2) NOT NULL DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE  ".$table_prefix."merchant
  ADD PRIMARY KEY (`merchant_id`),
  ADD KEY `restaurant_slug` (`restaurant_slug`),
  ADD KEY `restaurant_name` (`restaurant_name`),
  ADD KEY `post_code` (`post_code`),
  ADD KEY `service` (`service`),
  ADD KEY `username` (`username`),
  ADD KEY `password` (`password`),
  ADD KEY `status` (`status`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `payment_steps` (`payment_steps`),
  ADD KEY `is_featured` (`is_featured`),
  ADD KEY `is_ready` (`is_ready`),
  ADD KEY `is_sponsored` (`is_sponsored`),
  ADD KEY `is_commission` (`is_commission`),
  ADD KEY `percent_commision` (`percent_commision`),
  ADD KEY `session_token` (`session_token`),
  ADD KEY `commision_type` (`commision_type`),
  ADD KEY `delivery_charges` (`delivery_charges`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `country_id` (`country_id`),
  ADD KEY `state_id` (`state_id`),
  ADD KEY `city_id` (`city_id`),
  ADD KEY `area_id` (`area_id`);


ALTER TABLE  ".$table_prefix."merchant
  MODIFY `merchant_id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['merchant_cc']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."merchant_cc (
   mt_id int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `card_name` varchar(255) NOT NULL DEFAULT '',
  `credit_card_number` varchar(20) NOT NULL DEFAULT '',
  `expiration_month` varchar(5) NOT NULL DEFAULT '',
  `expiration_yr` varchar(5) NOT NULL DEFAULT '',
  `cvv` varchar(20) NOT NULL,
  `billing_address` varchar(255) NOT NULL DEFAULT '',
  `date_created` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `encrypted_card` binary(255) DEFAULT '\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0',
  `date_modified` $date_default
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."merchant_cc
  ADD PRIMARY KEY ( mt_id ),
  ADD KEY `merchant_id` (`merchant_id`);
  
ALTER TABLE  ".$table_prefix."merchant_cc
  MODIFY mt_id int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['merchant_user']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."merchant_user (
  `merchant_user_id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `last_name` varchar(255) NOT NULL DEFAULT '',
  `username` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `user_access` text,
  `date_created` $date_default,
  `date_modified` $date_default,
  `status` varchar(100) NOT NULL DEFAULT 'active',
  `last_login` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `contact_email` varchar(255) NOT NULL DEFAULT '',
  `session_token` varchar(255) NOT NULL DEFAULT '',
  `mobile_session_token` varchar(255) NOT NULL DEFAULT '',
  `lost_password_code` varchar(20) NOT NULL DEFAULT '',
  `contact_number` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."merchant_user
  ADD PRIMARY KEY (`merchant_user_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `username` (`username`),
  ADD KEY `password` (`password`),
  ADD KEY `status` (`status`),
  ADD KEY `session_token` (`session_token`),
  ADD KEY `mobile_session_token` (`mobile_session_token`),
  ADD KEY `lost_password_code` (`lost_password_code`);
  
ALTER TABLE  ".$table_prefix."merchant_user
  MODIFY `merchant_user_id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['minimum_table']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."minimum_table (
  `id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `distance_from` float(14,4) NOT NULL DEFAULT '0.0000',
  `distance_to` float(14,4) NOT NULL DEFAULT '0.0000',
  `shipping_units` varchar(5) NOT NULL DEFAULT '',
  `min_order` float(14,4) NOT NULL DEFAULT '0.0000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."minimum_table
  ADD PRIMARY KEY (`id`),
  ADD KEY `merchant_id` (`merchant_id`);

ALTER TABLE  ".$table_prefix."minimum_table
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['mobile_broadcast']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."mobile_broadcast (
  `broadcast_id` int(14) NOT NULL DEFAULT '0',
  `push_title` varchar(255) NOT NULL DEFAULT '',
  `push_message` varchar(255) NOT NULL DEFAULT '',
  `device_platform` int(14) NOT NULL DEFAULT '1',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `date_created` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."mobile_broadcast
  ADD PRIMARY KEY (`broadcast_id`);

ALTER TABLE  ".$table_prefix."mobile_broadcast
  MODIFY `broadcast_id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['newsletter']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."newsletter (
  `id` int(14) NOT NULL,
  `email_address` varchar(255) NOT NULL DEFAULT '',
  `date_created` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."newsletter
  ADD PRIMARY KEY (`id`),
  ADD KEY `email_address` (`email_address`);

ALTER TABLE  ".$table_prefix."newsletter
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['offers']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."offers (
  `offers_id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `offer_percentage` float(14,4) NOT NULL DEFAULT '0.0000',
  `offer_price` float(14,4) NOT NULL DEFAULT '0.0000',
  `valid_from` date DEFAULT NULL,
  `valid_to` date DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `applicable_to` varchar(100) NOT NULL DEFAULT 'all'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."offers
  ADD PRIMARY KEY (`offers_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `status` (`status`);
  

ALTER TABLE  ".$table_prefix."offers
  MODIFY `offers_id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['option']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."option (
  `id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `option_name` varchar(255) NOT NULL DEFAULT '',
  `option_value` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."option
  ADD PRIMARY KEY (`id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `option_name` (`option_name`);
  
ALTER TABLE  ".$table_prefix."option
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['order']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."order (
  `order_id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `client_id` int(14) NOT NULL DEFAULT '0',
  `json_details` text,
  `trans_type` varchar(100) NOT NULL DEFAULT '',
  `payment_type` varchar(100) NOT NULL DEFAULT '',
  `sub_total` float(14,4) NOT NULL DEFAULT '0.0000',
  `tax` float(14,4) NOT NULL DEFAULT '0.0000',
  `taxable_total` decimal(14,4) NOT NULL,
  `total_w_tax` float(14,4) NOT NULL DEFAULT '0.0000',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `stats_id` int(14) NOT NULL DEFAULT '0',
  `viewed` int(1) NOT NULL DEFAULT '1',
  `delivery_charge` float(14,4) NOT NULL DEFAULT '0.0000',
  `delivery_date` date DEFAULT NULL,
  `delivery_time` varchar(100) NOT NULL DEFAULT '',
  `delivery_asap` varchar(14) NOT NULL DEFAULT '',
  `delivery_instruction` varchar(255) NOT NULL DEFAULT '',
  `voucher_code` varchar(100) NOT NULL DEFAULT '',
  `voucher_amount` float(14,4) NOT NULL DEFAULT '0.0000',
  `voucher_type` varchar(100) NOT NULL DEFAULT '',
  `cc_id` int(14) NOT NULL DEFAULT '0',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `order_change` float(14,4) NOT NULL DEFAULT '0.0000',
  `payment_provider_name` varchar(255) NOT NULL DEFAULT '',
  `discounted_amount` float(14,5) NOT NULL DEFAULT '0.0000',
  `discount_percentage` float(14,5) NOT NULL DEFAULT '0.0000',
  `percent_commision` float(14,4) NOT NULL DEFAULT '0.0000',
  `total_commission` float(14,4) NOT NULL DEFAULT '0.0000',
  `commision_ontop` int(2) NOT NULL DEFAULT '2',
  `merchant_earnings` float(14,4) NOT NULL DEFAULT '0.0000',
  `packaging` float(14,4) NOT NULL DEFAULT '0.0000',
  `cart_tip_percentage` float(14,4) NOT NULL DEFAULT '0.0000',
  `cart_tip_value` float(14,4) NOT NULL DEFAULT '0.0000',
  `card_fee` float(14,4) NOT NULL DEFAULT '0.0000',
  `donot_apply_tax_delivery` int(1) NOT NULL DEFAULT '1',
  `order_locked` int(1) NOT NULL DEFAULT '1',
  `request_from` varchar(10) NOT NULL DEFAULT 'web',
  `mobile_cart_details` text,
  `points_discount` float(14,4) NOT NULL DEFAULT '0.0000',
  `apply_food_tax` int(1) NOT NULL DEFAULT '0',
  `order_id_token` varchar(100) NOT NULL DEFAULT '',
  `admin_viewed` int(1) NOT NULL DEFAULT '0',
  `merchantapp_viewed` int(1) NOT NULL DEFAULT '0',
  `dinein_number_of_guest` varchar(14) NOT NULL DEFAULT '',
  `dinein_special_instruction` varchar(255) NOT NULL DEFAULT '',
  `critical` int(1) NOT NULL DEFAULT '1',
  `commision_type` varchar(50) NOT NULL DEFAULT 'percentage',
  `calculation_method` int(1) NOT NULL DEFAULT '1',
  `request_cancel` int(1) NOT NULL DEFAULT '2',
  `request_cancel_viewed` int(1) NOT NULL DEFAULT '2',
  `request_cancel_status` varchar(255) NOT NULL DEFAULT 'pending',
  `sofort_trans_id` varchar(255) NOT NULL DEFAULT '',
  `dinein_table_number` varchar(50) NOT NULL DEFAULT '',
  `payment_gateway_ref` varchar(255) NOT NULL DEFAULT '',
  `distance` varchar(100) NOT NULL DEFAULT '',
  `cancel_reason` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."order
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `order_id_token` (`order_id_token`),
  ADD KEY `merchantapp_viewed` (`merchantapp_viewed`),
  ADD KEY `admin_viewed` (`admin_viewed`),
  ADD KEY `viewed` (`viewed`),
  ADD KEY `total_commission` (`total_commission`),
  ADD KEY `merchant_earnings` (`merchant_earnings`),
  ADD KEY `total_w_tax` (`total_w_tax`),
  ADD KEY `taxable_total` (`taxable_total`),
  ADD KEY `sub_total` (`sub_total`),
  ADD KEY `payment_type` (`payment_type`),
  ADD KEY `trans_type` (`trans_type`); 

ALTER TABLE  ".$table_prefix."order
  MODIFY `order_id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['order_delivery_address']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."order_delivery_address (
  `id` int(14) NOT NULL,
  `order_id` int(14) NOT NULL DEFAULT '0',
  `client_id` int(14) NOT NULL DEFAULT '0',
  `street` varchar(255) NOT NULL DEFAULT '',
  `city` varchar(255) NOT NULL DEFAULT '',
  `state` varchar(255) NOT NULL DEFAULT '',
  `zipcode` varchar(255) NOT NULL DEFAULT '',
  `location_name` varchar(255) NOT NULL DEFAULT '',
  `country` varchar(255) NOT NULL DEFAULT '',
  `date_created` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `contact_phone` varchar(100) NOT NULL DEFAULT '',
  `formatted_address` text,
  `google_lat` varchar(50) NOT NULL DEFAULT '',
  `google_lng` varchar(50) NOT NULL DEFAULT '',
  `area_name` varchar(255) NOT NULL DEFAULT '',
  `first_name` varchar(255) NOT NULL DEFAULT '',
  `last_name` varchar(255) NOT NULL DEFAULT '',
  `contact_email` varchar(255) NOT NULL DEFAULT '',
  `dinein_number_of_guest` varchar(14) NOT NULL DEFAULT '',
  `dinein_special_instruction` varchar(255) NOT NULL DEFAULT '',
  `dinein_table_number` varchar(50) NOT NULL DEFAULT '',
  `opt_contact_delivery` int(1) NOT NULL DEFAULT '0'  
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."order_delivery_address
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `google_lat` (`google_lat`),
  ADD KEY `google_lng` (`google_lng`);

  
ALTER TABLE  ".$table_prefix."order_delivery_address
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['order_details']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."order_details (
  `id` int(14) NOT NULL,
  `order_id` int(14) NOT NULL DEFAULT '0',
  `client_id` int(14) NOT NULL DEFAULT '0',
  `item_id` int(14) NOT NULL DEFAULT '0',
  `item_name` varchar(255) NOT NULL DEFAULT '',
  `order_notes` text,
  `normal_price` float(14,4) NOT NULL DEFAULT '0.0000',
  `discounted_price` float(14,4) NOT NULL DEFAULT '0.0000',
  `size` varchar(255) NOT NULL DEFAULT '',
  `qty` int(14) NOT NULL DEFAULT '0',
  `cooking_ref` varchar(255) NOT NULL DEFAULT '',
  `addon` text,
  `ingredients` text,
  `non_taxable` int(1) NOT NULL DEFAULT '1',
  `size_id` integer(14) NOT NULL DEFAULT '0',
  `cat_id` integer(14) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."order_details
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `item_id` (`item_id`);
  
ALTER TABLE  ".$table_prefix."order_details
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['order_history']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."order_history (
  `id` int(14) NOT NULL,
  `order_id` int(14) NOT NULL DEFAULT '0',
  `status` varchar(255) NOT NULL DEFAULT '',
  `remarks` text,
  `date_created` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `task_id` int(14) NOT NULL DEFAULT '0',
  `reason` text,
  `customer_signature` varchar(255) NOT NULL DEFAULT '',
  `notification_viewed` int(1) NOT NULL DEFAULT '2',
  `driver_id` int(14) NOT NULL DEFAULT '0',
  `driver_location_lat` varchar(50) NOT NULL DEFAULT '',
  `driver_location_lng` varchar(50) NOT NULL DEFAULT '',
  `remarks2` varchar(255) NOT NULL DEFAULT '',
  `remarks_args` varchar(255) NOT NULL DEFAULT '',
  `notes` varchar(255) NOT NULL DEFAULT '',
  `photo_task_id` int(14) NOT NULL DEFAULT '0',
  `receive_by` varchar(255) NOT NULL DEFAULT '',
  `signature_base30` text,
  `update_by_type` varchar(255) NOT NULL DEFAULT '',
  `update_by_id` integer(14) NOT NULL DEFAULT '0',
  `update_by_name` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."order_history
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `status` (`status`),
  ADD KEY `task_id` (`task_id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `driver_location_lat` (`driver_location_lat`),
  ADD KEY `driver_location_lng` (`driver_location_lng`);
  

ALTER TABLE  ".$table_prefix."order_history
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['order_sms']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."order_sms (
  `id` int(14) NOT NULL,
  `mobile` varchar(50) NOT NULL DEFAULT '',
  `code` int(4) NOT NULL,
  `session` varchar(255) NOT NULL DEFAULT '',
  `date_created` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."order_sms
  ADD PRIMARY KEY (`id`),
  ADD KEY `session` (`session`),
  ADD KEY `code` (`code`);
  
ALTER TABLE  ".$table_prefix."order_sms
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['order_status']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."order_status (
  `stats_id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `description` varchar(200) NOT NULL DEFAULT '',
  `date_created` date DEFAULT NULL,
  `date_modified` date DEFAULT NULL,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."order_status
  ADD PRIMARY KEY (`stats_id`),
  ADD KEY `merchant_id` (`merchant_id`);

  
ALTER TABLE  ".$table_prefix."order_status
  MODIFY `stats_id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['packages']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."packages (
  `package_id` int(14) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `price` float(14,4) NOT NULL DEFAULT '0.0000',
  `promo_price` float(14,4) NOT NULL DEFAULT '0.0000',
  `expiration` int(14) NOT NULL DEFAULT '0',
  `expiration_type` varchar(50) NOT NULL DEFAULT 'days',
  `unlimited_post` int(1) NOT NULL DEFAULT '2',
  `post_limit` int(14) NOT NULL DEFAULT '0',
  `sequence` int(14) NOT NULL DEFAULT '0',
  `status` varchar(100) NOT NULL DEFAULT '',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `sell_limit` int(14) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."packages
  ADD PRIMARY KEY (`package_id`),
  ADD KEY `title` (`title`),
  ADD KEY `status` (`status`);
  
ALTER TABLE  ".$table_prefix."packages
  MODIFY `package_id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['package_trans']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."package_trans (
  `id` int(14) NOT NULL,
  `package_id` int(14) NOT NULL DEFAULT '0',
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `price` float(14,4) NOT NULL DEFAULT '0.0000',
  `payment_type` varchar(100) NOT NULL DEFAULT '',
   mt_id int(14) NOT NULL DEFAULT '0',
  `TOKEN` varchar(255) NOT NULL DEFAULT '',
  `membership_expired` date DEFAULT NULL,
  `TRANSACTIONID` varchar(255) NOT NULL DEFAULT '',
  `TRANSACTIONTYPE` varchar(100) NOT NULL DEFAULT '',
  `PAYMENTSTATUS` varchar(100) NOT NULL DEFAULT '',
  `PAYPALFULLRESPONSE` text,
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `date_created` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `fee` float(14,4) NOT NULL DEFAULT '0.0000',
  `merchant_ref` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE  ".$table_prefix."package_trans
  ADD PRIMARY KEY (`id`),
  ADD KEY `package_id` (`package_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `TRANSACTIONID` (`TRANSACTIONID`),
  ADD KEY `status` (`status`);
  
ALTER TABLE  ".$table_prefix."package_trans
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['payment_order']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."payment_order (
  `id` int(14) NOT NULL,
  `payment_type` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `payment_reference` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `order_id` int(14) NOT NULL DEFAULT '0',
  `raw_response` text CHARACTER SET utf8 NOT NULL,
  `date_created` $date_default,
  `ip_address` varchar(100) CHARACTER SET utf8 NOT NULL,
  `merchant_ref` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE  ".$table_prefix."payment_order
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `payment_type` (`payment_type`),
  ADD KEY `payment_reference` (`payment_reference`);

  
ALTER TABLE  ".$table_prefix."payment_order
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['payment_provider']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."payment_provider (
  `id` int(14) NOT NULL,
  `payment_name` varchar(255) NOT NULL DEFAULT '',
  `payment_logo` varchar(255) NOT NULL DEFAULT '',
  `sequence` int(14) NOT NULL DEFAULT '0',
  `status` varchar(255) NOT NULL DEFAULT 'publish',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."payment_provider
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_name` (`payment_name`),
  ADD KEY `status` (`status`);
  
ALTER TABLE  ".$table_prefix."payment_provider
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['paypal_checkout']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."paypal_checkout (
  `id` int(14) NOT NULL,
  `order_id` int(14) NOT NULL DEFAULT '0',
  `token` varchar(255) NOT NULL DEFAULT '',
  `paypal_request` text,
  `paypal_response` text,
  `status` varchar(255) NOT NULL DEFAULT 'checkout',
  `date_created` $date_default,
  `ip_address` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."paypal_checkout
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `token` (`token`),
  ADD KEY `status` (`status`);
  
ALTER TABLE  ".$table_prefix."paypal_checkout
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['paypal_payment']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."paypal_payment (
  `id` int(14) NOT NULL,
  `order_id` int(14) NOT NULL DEFAULT '0',
  `TOKEN` varchar(255) NOT NULL DEFAULT '',
  `TRANSACTIONID` varchar(100) NOT NULL DEFAULT '',
  `TRANSACTIONTYPE` varchar(100) NOT NULL DEFAULT '',
  `PAYMENTTYPE` varchar(100) NOT NULL DEFAULT '',
  `ORDERTIME` varchar(100) NOT NULL DEFAULT '',
  `AMT` varchar(14) NOT NULL DEFAULT '',
  `FEEAMT` varchar(14) NOT NULL DEFAULT '',
  `TAXAMT` varchar(14) NOT NULL DEFAULT '',
  `CURRENCYCODE` varchar(3) NOT NULL DEFAULT '',
  `PAYMENTSTATUS` varchar(100) NOT NULL DEFAULT '',
  `CORRELATIONID` varchar(100) NOT NULL DEFAULT '',
  `TIMESTAMP` varchar(100) NOT NULL DEFAULT '',
  `json_details` text,
  `date_created` varchar(50) NOT NULL DEFAULT '',
  `ip_address` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."paypal_payment
  ADD PRIMARY KEY (`id`);

  
ALTER TABLE  ".$table_prefix."paypal_payment
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['rating']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."rating (
  `id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `ratings` float(14,1) NOT NULL DEFAULT '0.0',
  `client_id` int(14) NOT NULL DEFAULT '0',
  `date_created` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."rating
  ADD PRIMARY KEY (`id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `ratings` (`ratings`);
  
ALTER TABLE  ".$table_prefix."rating
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['rating_meaning']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."rating_meaning (
  `id` int(14) NOT NULL,
  `rating_start` float(14,1) NOT NULL DEFAULT '0.0',
  `rating_end` float(14,1) NOT NULL DEFAULT '0.0',
  `meaning` varchar(255) NOT NULL DEFAULT '',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."rating_meaning
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE  ".$table_prefix."rating_meaning
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['receive_post']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."receive_post (
  `id` int(14) NOT NULL,
  `payment_type` varchar(3) NOT NULL DEFAULT '',
  `receive_post` text,
  `status` text,
  `date_created` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."receive_post
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE  ".$table_prefix."receive_post
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['review']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."review (
  `id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `client_id` int(14) NOT NULL DEFAULT '0',
  `review` text,
  `rating` float(14,1) NOT NULL DEFAULT '0.0',
  `status` varchar(100) NOT NULL DEFAULT 'publish',
  `date_created` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `order_id` varchar(14) NOT NULL DEFAULT '',
  `parent_id` int(14) NOT NULL DEFAULT '0',
  `reply_from` varchar(255) NOT NULL DEFAULT '',
  `date_modified` $date_default
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."review
  ADD PRIMARY KEY (`id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `rating` (`rating`),
  ADD KEY `status` (`status`);
  
ALTER TABLE  ".$table_prefix."review
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['shipping_rate']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."shipping_rate (
  `id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `distance_from` float(14,2) NOT NULL DEFAULT '0.00',
  `distance_to` float(14,2) NOT NULL DEFAULT '0.00',
  `shipping_units` varchar(5) NOT NULL DEFAULT '',
  `distance_price` float(14,4) NOT NULL DEFAULT '0.0000'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE  ".$table_prefix."shipping_rate
  ADD PRIMARY KEY (`id`),
  ADD KEY `merchant_id` (`merchant_id`);

ALTER TABLE  ".$table_prefix."shipping_rate
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['size']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."size (
  `size_id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `size_name` varchar(255) NOT NULL DEFAULT '',
  `sequence` int(14) NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL DEFAULT 'published',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `size_name_trans` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."size
  ADD PRIMARY KEY (`size_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `size_name` (`size_name`),
  ADD KEY `status` (`status`);
  
ALTER TABLE  ".$table_prefix."size
  MODIFY `size_id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['sms_broadcast']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."sms_broadcast (
  `broadcast_id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `send_to` int(14) NOT NULL DEFAULT '0',
  `list_mobile_number` text CHARACTER SET utf8 NOT NULL,
  `sms_alert_message` varchar(255) CHARACTER SET utf8 NOT NULL,
  `status` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT 'pending',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."sms_broadcast
  ADD PRIMARY KEY (`broadcast_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `send_to` (`send_to`),
  ADD KEY `status` (`status`);
  
ALTER TABLE  ".$table_prefix."sms_broadcast
  MODIFY `broadcast_id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['sms_broadcast_details']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."sms_broadcast_details (
  `id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `broadcast_id` int(14) NOT NULL DEFAULT '0',
  `client_id` int(14) NOT NULL DEFAULT '0',
  `client_name` varchar(255) NOT NULL DEFAULT '',
  `contact_phone` varchar(50) NOT NULL DEFAULT '',
  `sms_message` text,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `gateway_response` text,
  `date_created` $date_default,
  `date_executed` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `gateway` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."sms_broadcast_details
  ADD PRIMARY KEY (`id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `broadcast_id` (`broadcast_id`),
  ADD KEY `client_id` (`client_id`),
  ADD KEY `status` (`status`),
  ADD KEY `gateway` (`gateway`);
  
ALTER TABLE  ".$table_prefix."sms_broadcast_details
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['sms_package']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."sms_package (
  `sms_package_id` int(14) NOT NULL,
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `price` float(14,4) NOT NULL DEFAULT '0.0000',
  `promo_price` float(14,4) NOT NULL DEFAULT '0.0000',
  `sms_limit` int(14) NOT NULL DEFAULT '0',
  `sequence` int(14) NOT NULL DEFAULT '0',
  `status` varchar(100) NOT NULL DEFAULT '',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(100) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."sms_package
  ADD PRIMARY KEY (`sms_package_id`);
  
ALTER TABLE  ".$table_prefix."sms_package
  MODIFY `sms_package_id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['sms_package_trans']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."sms_package_trans (
  `id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `sms_package_id` int(14) NOT NULL DEFAULT '0',
  `payment_type` varchar(50) NOT NULL DEFAULT '',
  `package_price` float(14,4) NOT NULL DEFAULT '0.0000',
  `sms_limit` int(14) NOT NULL DEFAULT '0',
  `status` varchar(100) NOT NULL DEFAULT 'pending',
  `payment_reference` varchar(255) NOT NULL DEFAULT '',
  `payment_gateway_response` text,
  `date_created` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `cc_id` int(14) NOT NULL DEFAULT '0',
  `merchant_ref` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."sms_package_trans
  ADD PRIMARY KEY (`id`);

ALTER TABLE  ".$table_prefix."sms_package_trans
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['stripe_logs']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."stripe_logs (
  `id` int(14) NOT NULL,
  `order_id` int(14) NOT NULL,
  `json_result` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE  ".$table_prefix."stripe_logs
  ADD PRIMARY KEY (`id`);

  
ALTER TABLE  ".$table_prefix."stripe_logs
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['subcategory']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."subcategory (
  `subcat_id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `subcategory_name` varchar(255) NOT NULL DEFAULT '',
  `subcategory_description` text,
  `discount` varchar(20) NOT NULL DEFAULT '',
  `sequence` int(14) NOT NULL DEFAULT '0',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `status` varchar(100) NOT NULL DEFAULT 'publish',
  `subcategory_name_trans` text,
  `subcategory_description_trans` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."subcategory
  ADD PRIMARY KEY (`subcat_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `subcategory_name` (`subcategory_name`),
  ADD KEY `status` (`status`);
  
ALTER TABLE  ".$table_prefix."subcategory
  MODIFY `subcat_id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['subcategory_item']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."subcategory_item (
  `sub_item_id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `sub_item_name` varchar(255) NOT NULL DEFAULT '',
  `item_description` text,
  `category` varchar(255) NOT NULL DEFAULT '',
  `price` varchar(15) NOT NULL DEFAULT '',
  `photo` varchar(255) NOT NULL DEFAULT '',
  `sequence` int(14) NOT NULL DEFAULT '0',
  `status` varchar(50) NOT NULL DEFAULT '',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `sub_item_name_trans` text,
  `item_description_trans` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."subcategory_item
  ADD PRIMARY KEY (`sub_item_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `sub_item_name` (`sub_item_name`),
  ADD KEY `status` (`status`);
  
ALTER TABLE  ".$table_prefix."subcategory_item
  MODIFY `sub_item_id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['voucher_new']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."voucher_new (
  `voucher_id` int(14) NOT NULL,
  `voucher_owner` varchar(255) NOT NULL DEFAULT 'merchant',
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `joining_merchant` text,
  `voucher_name` varchar(255) NOT NULL DEFAULT '',
  `voucher_type` varchar(255) NOT NULL DEFAULT '',
  `amount` float(14,4) NOT NULL DEFAULT '0.0000',
  `expiration` date DEFAULT NULL,
  `status` varchar(100) NOT NULL DEFAULT '',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(100) NOT NULL DEFAULT '',
  `used_once` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."voucher_new
  ADD PRIMARY KEY (`voucher_id`),
  ADD KEY `voucher_name` (`voucher_name`),
  ADD KEY `status` (`status`),
  ADD KEY `voucher_owner` (`voucher_owner`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `voucher_type` (`voucher_type`);
  
ALTER TABLE  ".$table_prefix."voucher_new
  MODIFY `voucher_id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['withdrawal']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."withdrawal (
  `withdrawal_id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `payment_type` varchar(100) NOT NULL DEFAULT '',
  `payment_method` varchar(100) NOT NULL DEFAULT '',
  `amount` float(14,4) NOT NULL DEFAULT '0.0000',
  `current_balance` float(14,4) NOT NULL DEFAULT '0.0000',
  `balance` float(14,4) NOT NULL DEFAULT '0.0000',
  `currency_code` varchar(3) NOT NULL DEFAULT '',
  `account` varchar(255) NOT NULL DEFAULT '',
  `account_name` varchar(255) NOT NULL DEFAULT '',
  `bank_account_number` varchar(255) NOT NULL DEFAULT '',
  `swift_code` varchar(100) NOT NULL DEFAULT '',
  `bank_name` varchar(255) NOT NULL DEFAULT '',
  `bank_branch` varchar(255) NOT NULL DEFAULT '',
  `bank_country` varchar(50) NOT NULL DEFAULT '',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `viewed` int(2) NOT NULL DEFAULT '1',
  `date_created` $date_default,
  `date_to_process` date DEFAULT NULL,
  `date_process` $date_default,
  `api_raw_response` text,
  `withdrawal_token` text,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `bank_type` varchar(255) NOT NULL DEFAULT 'default'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE  ".$table_prefix."withdrawal
  ADD PRIMARY KEY (`withdrawal_id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `payment_type` (`payment_type`),
  ADD KEY `payment_method` (`payment_method`),
  ADD KEY `status` (`status`),
  ADD KEY `viewed` (`viewed`);
  
  ALTER TABLE  ".$table_prefix."withdrawal
  MODIFY `withdrawal_id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['zipcode']="
CREATE TABLE IF NOT EXISTS  ".$table_prefix."zipcode (
  `zipcode_id` int(14) NOT NULL,
  `zipcode` varchar(255) NOT NULL DEFAULT '',
  `country_code` varchar(5) NOT NULL DEFAULT '',
  `city` varchar(255) NOT NULL DEFAULT '',
  `area` varchar(255) NOT NULL DEFAULT '',
  `stree_name` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE  ".$table_prefix."zipcode
  ADD PRIMARY KEY (`zipcode_id`),
  ADD KEY `country_code` (`country_code`),
  ADD KEY `area` (`area`),
  ADD KEY `stree_name` (`stree_name`);

  
ALTER TABLE  ".$table_prefix."zipcode
  MODIFY `zipcode_id` int(14) NOT NULL AUTO_INCREMENT;
";


$tbl['address_book_location']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."address_book_location (
  `id` int(11) NOT NULL,
  `client_id` int(14) NOT NULL DEFAULT '0',
  `street` varchar(255) NOT NULL DEFAULT '',
  `location_name` varchar(255) NOT NULL DEFAULT '',
  `country_id` int(14) NOT NULL DEFAULT '0',
  `state_id` int(14) NOT NULL DEFAULT '0',
  `city_id` int(14) NOT NULL DEFAULT '0',
  `area_id` int(14) NOT NULL DEFAULT '0',
  `as_default` int(1) NOT NULL DEFAULT '0',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT '',
  `latitude` varchar(255) NOT NULL DEFAULT '',
  `longitude` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE ".$table_prefix."address_book_location
  ADD PRIMARY KEY (`id`);
  
ALTER TABLE ".$table_prefix."address_book_location
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;  
";

$tbl['favorites']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."favorites (
  `id` int(14) NOT NULL,
  `fav_type` varchar(100) NOT NULL DEFAULT 'restaurant',
  `client_id` int(14) NOT NULL DEFAULT '0',
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

 ALTER TABLE ".$table_prefix."favorites
 ADD PRIMARY KEY (`id`);

 ALTER TABLE ".$table_prefix."favorites
 MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";


$tbl['sms_trans_logs']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."sms_trans_logs (
  `id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `sms_package_id` int(14) NOT NULL DEFAULT '0',
  `payment_type` varchar(100) NOT NULL DEFAULT '',
  `package_price` float(14,4) NOT NULL DEFAULT '0.0000',
  `sms_limit` varchar(14) NOT NULL DEFAULT '',
  `payment_reference` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `payment_gateway_ref` varchar(255) NOT NULL DEFAULT '',
  `gateway_response` text,
  `date_created` $date_default,
  `date_modified` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
		
ALTER TABLE ".$table_prefix."sms_trans_logs
ADD PRIMARY KEY (`id`);

ALTER TABLE ".$table_prefix."sms_trans_logs
MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

/*5.1*/

$tbl['stripe_logger']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."stripe_logger (
  `id` int(14) NOT NULL,
  `trans_type` varchar(255) NOT NULL DEFAULT '',
  `payment_intent` varchar(255) NOT NULL DEFAULT '',
  `post_receive` text,
  `webhooks_response` text,
  `date_created` $date_default,
  `post_receive_date` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE ".$table_prefix."stripe_logger
ADD PRIMARY KEY (`id`);
  
ALTER TABLE ".$table_prefix."stripe_logger
MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

/*5.4*/
$tbl['opening_hours']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."opening_hours (
  `id` int(14) NOT NULL,
  `merchant_id` int(14) NOT NULL DEFAULT '0',
  `day` varchar(20) NOT NULL DEFAULT '',
  `status` varchar(100) NOT NULL DEFAULT 'open',
  `start_time` varchar(14) NOT NULL DEFAULT '',
  `end_time` varchar(14) NOT NULL DEFAULT '',
  `start_time_pm` varchar(14) NOT NULL DEFAULT '',
  `end_time_pm` varchar(14) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE ".$table_prefix."opening_hours
  ADD PRIMARY KEY (`id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `day` (`day`),
  ADD KEY `status` (`status`),
  ADD KEY `start_time` (`start_time`),
  ADD KEY `end_time` (`end_time`),
  ADD KEY `start_time_pm` (`start_time_pm`),
  ADD KEY `end_time_pm` (`end_time_pm`);
  
ALTER TABLE ".$table_prefix."opening_hours
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;  
";

$tbl['cuisine_merchant']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."cuisine_merchant (
  `id` int(14) NOT NULL,
  `merchant_id` varchar(14) NOT NULL DEFAULT '0',
  `cuisine_id` varchar(14) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE ".$table_prefix."cuisine_merchant
  ADD PRIMARY KEY (`id`),
  ADD KEY `merchant_id` (`merchant_id`),
  ADD KEY `cuisine_id` (`cuisine_id`);
  
  ALTER TABLE ".$table_prefix."cuisine_merchant
  MODIFY `id` int(14) NOT NULL AUTO_INCREMENT;
";

$tbl['tags']="
CREATE TABLE IF NOT EXISTS ".$table_prefix."tags (
  `tag_id` bigint(20) NOT NULL,
  `tag_name` varchar(255) NOT NULL DEFAULT '',
  `slug` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `tag_name_trans` text,
  `description_trans` text,
  `date_created` $date_default,
  `ip_address` varchar(50) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE ".$table_prefix."tags
  ADD PRIMARY KEY (`tag_id`);
  
  ALTER TABLE ".$table_prefix."tags
  MODIFY `tag_id` bigint(20) NOT NULL AUTO_INCREMENT;
";

/*END 5.4*/


/*VIEW TABLES */

$tbl['view_ratings']="
create OR REPLACE VIEW ".$table_prefix."view_ratings as
select 
merchant_id,
SUM(rating)/COUNT(*) AS ratings
from
".$table_prefix."review
where
status in ('publish','published')
group by merchant_id
";

$tbl['view_merchant']="
create OR REPLACE VIEW ".$table_prefix."view_merchant as
select a.*,
f.ratings

from ".$table_prefix."merchant a

left join ".$table_prefix."view_ratings f
ON 
a.merchant_id = f.merchant_id 		
";

$tbl['view_order_details']="
create OR REPLACE VIEW ".$table_prefix."view_order_details as

select a.* ,
b.merchant_id,
b.status,
b.date_created

from ".$table_prefix."order_details a

left join ".$table_prefix."order b
on
a.order_id = b.order_id
";

$tbl['view_location_rate']="
CREATE OR REPLACE VIEW ".$table_prefix."view_location_rate AS
SELECT 
a.rate_id,
a.merchant_id,
a.country_id,
b.country_name,
a.state_id,
c.name as state_name,
a.city_id,
d.name as city_name,
d.postal_code,
a.area_id,
e.name as area_name,
a.fee,
a.sequence,
a.date_created,
a.date_modified,
a.ip_address

FROM
".$table_prefix."location_rate a

left join ".$table_prefix."location_countries b
on
a.country_id=b.country_id	   

left join ".$table_prefix."location_states c
on
a.state_id = c.state_id

left join ".$table_prefix."location_cities d
on
a.city_id = d.city_id

left join ".$table_prefix."location_area e
on
a.area_id = e.area_id
";

/*5.4 view*/

$tbl['view_cuisine_merchant']="
CREATE OR REPLACE VIEW ".$table_prefix."view_cuisine_merchant as
select 
a.merchant_id,
a.cuisine_id,
b.cuisine_name,
b.cuisine_name_trans,
b.status,
b.featured_image,
c.restaurant_name

from ".$table_prefix."cuisine_merchant a
left join ".$table_prefix."cuisine b
on
a.cuisine_id = b.cuisine_id	

left join ".$table_prefix."merchant c
on
a.merchant_id = c.merchant_id

where 
a.merchant_id = c.merchant_id
";

/*END 5.4 view*/