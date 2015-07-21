<?php
function appointgen_ustsappointment_install() {
	global $table_prefix, $wpdb;
  $table_name1 = $table_prefix.'appointgen_ustsappointments';
  $sql_sfe = "CREATE TABLE IF NOT EXISTS ".$table_name1." (
                `appointment_id` int(11) NOT NULL AUTO_INCREMENT,
                `schedule_id` int(11) DEFAULT NULL,
                `schedule` varchar(255) DEFAULT NULL,
                `date` date DEFAULT NULL,
                `start_time` varchar(255) DEFAULT NULL,
                `end_time` varchar(255) DEFAULT NULL,
                `timeshift` varchar(11) DEFAULT NULL,
                `first_name` varchar(255) DEFAULT NULL,
                `last_name` varchar(255) DEFAULT NULL,
                `email` varchar(255) DEFAULT NULL,
                `phone` varchar(20) DEFAULT NULL,
                `details` text,
                `appointment_by` varchar(255) DEFAULT NULL,
                `custom_price` decimal(10,2) DEFAULT NULL,
                `payment_method` varchar(255) DEFAULT NULL,
                `confirmed` int(1) DEFAULT '0',
                `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`appointment_id`)
              ) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
              ";
   $wpdb->query($sql_sfe);
	 //-------------------------------
	 $table_name2 = $table_prefix.'appointgen_ustsappointments_paymentmethods';
   $sql_scpm = "CREATE TABLE IF NOT EXISTS  $table_name2(
						  `id` int(11) NOT NULL AUTO_INCREMENT,
						  `payment_method` varchar(255) DEFAULT NULL,
							 PRIMARY KEY (`id`)
							) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;";

   $wpdb->query($sql_scpm);
   $tbl_schedules = $table_prefix.'appointgen_schedules';
   $sql_schedules = "CREATE TABLE IF NOT EXISTS $tbl_schedules(
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `schedule_name` varchar(256) DEFAULT NULL,
                    `timeslot` int(11) DEFAULT NULL,
                    `service` int(11) DEFAULT NULL,
                    `venue` int(11) DEFAULT NULL,
                    `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;";
   $wpdb->query($sql_schedules);
   
   $tbl_services = $table_prefix.'appointgen_services';
   $sql_services = "CREATE TABLE IF NOT EXISTS $tbl_services (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `provider_name` varchar(256) DEFAULT NULL,
                    `service_name` varchar(256) DEFAULT NULL,
                    `service_details` varchar(512) DEFAULT NULL,
                    `price` decimal(10,2) DEFAULT NULL,
                    `days` varchar(255) DEFAULT NULL,
                    PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;";
   $wpdb->query($sql_services);
   
   $tbl_timeslot = $table_prefix.'appointgen_timeslot';
   $sql_timeslot = "CREATE TABLE IF NOT EXISTS $tbl_timeslot (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `timeslot_name` varchar(255) DEFAULT NULL,
                    `mintime` varchar(11) DEFAULT NULL,
                    `maxtime` varchar(11) DEFAULT NULL,
                    `time_interval` varchar(11) DEFAULT NULL,
                    PRIMARY KEY (`id`)
                  ) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;";
   $wpdb->query($sql_timeslot);
   
   $tbl_venues = $table_prefix.'appointgen_venues';
   $sql_venues = "CREATE TABLE  IF NOT EXISTS $tbl_venues (
                  `id` int(11) NOT NULL AUTO_INCREMENT,
                  `venue_name` varchar(256) DEFAULT NULL,
                  `venue_address` mediumtext,
                  `description` text,
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;";
   $wpdb->query($sql_venues);
   //
   
	 $pm_count = $wpdb->get_results("SELECT COUNT(*) as pm_rowcount FROM ".$table_prefix."appointgen_ustsappointments_paymentmethods");
   if(!$pm_count[0]->pm_rowcount){
      $sql_pm_data = "INSERT INTO `".$table_prefix."appointgen_ustsappointments_paymentmethods` (`id`,`payment_method`) VALUES (2,'Bank'),(3,'Cash'),(4,'Paypal'),(5,'Credit Card'),(6,'Other');";
      $wpdb->query($sql_pm_data);
   }   
	 //===============================Currency Table==================================
  $table_currency = $wpdb->prefix ."usts_currency_list";
  $sql_currency = "CREATE TABLE IF NOT EXISTS $table_currency(
      `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
      `country` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
      `isocode` char(2) COLLATE utf8_unicode_ci DEFAULT '',
      `currency` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
      `symbol` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
      `symbol_html` varchar(10) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
      `code` char(3) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
      `has_regions` char(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
      `tax` varchar(8) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
      `continent` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
      `visible` varchar(1) COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
      PRIMARY KEY (`id`)
    ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=0 ;";
  $wpdb->query($sql_currency);
  
  $curr_count = $wpdb->get_results( "SELECT COUNT(*) as rowcount FROM ".$table_currency);
  if(!$curr_count[0]->rowcount){
    $currency_tbl_data = "INSERT INTO ".$table_currency." (id, country, isocode, currency, symbol, symbol_html, code, has_regions, tax, continent, visible) VALUES
    (1, 'Mauritania', 'MR', 'Mauritanian Ouguiya', '', '', 'MRO', '0', '0', 'africa', '1'),
    (2, 'Martinique (French)', 'MQ', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'southamerica', '1'),
    (3, 'Malta', 'MT', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (4, 'Marshall Islands', 'MH', 'US Dollar', '$', '&#036;', 'USD', '0', '0', 'asiapacific', '1'),
    (5, 'Mali', 'ML', 'CFA Franc BCEAO', '', '', 'XOF', '0', '0', 'africa', '1'),
    (6, 'Maldives', 'MV', 'Maldive Rufiyaa', '', '', 'MVR', '0', '0', 'asiapacific', '1'),
    (7, 'Malaysia', 'MY', 'Malaysian Ringgit', '', '', 'MYR', '0', '0', 'asiapacific', '1'),
    (8, 'Malawi', 'MW', 'Malawi Kwacha', '', '', 'MWK', '0', '0', 'africa', '1'),
    (9, 'Madagascar', 'MG', 'Malagasy Ariary', '', '', 'MGA', '0', '0', 'africa', '1'),
    (10, 'Macau', 'MO', 'Macau Pataca', '', '', 'MOP', '0', '0', 'asiapacific', '1'),
    (11, 'Macedonia', 'MK', 'Denar', '', '', 'MKD', '0', '0', 'europe', '1'),
    (12, 'Luxembourg', 'LU', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (13, 'Lithuania', 'LT', 'Lithuanian Litas', '', '', 'LTL', '0', '0', 'europe', '1'),
    (14, 'Liechtenstein', 'LI', 'Swiss Franc', '', '', 'CHF', '0', '0', 'europe', '1'),
    (15, 'Libya', 'LY', 'Libyan Dinar', '', '', 'LYD', '0', '0', 'africa', '1'),
    (16, 'Liberia', 'LR', 'Liberian Dollar', '$', '&#036;', 'LRD', '0', '0', 'africa', '1'),
    (17, 'Lesotho', 'LS', 'Lesotho Loti', '', '', 'LSL', '0', '0', 'africa', '1'),
    (18, 'Lebanon', 'LB', 'Lebanese Pound', '', '', 'LBP', '0', '0', 'asiapacific', '1'),
    (19, 'Latvia', 'LV', 'Latvian Lats', '', '', 'LVL', '0', '0', 'europe', '1'),
    (20, 'Laos', 'LA', 'Lao Kip', '', '', 'LAK', '0', '0', 'asiapacific', '1'),
    (21, 'Kyrgyzstan', 'KG', 'Som', '', '', 'KGS', '0', '0', 'asiapacific', '1'),
    (22, 'Kuwait', 'KW', 'Kuwaiti Dinar', '', '', 'KWD', '0', '0', 'asiapacific', '1'),
    (23, 'Korea, South', 'KR', 'Korean Won', '', '', 'KRW', '0', '0', 'asiapacific', '1'),
    (24, 'Korea, North', 'KP', 'North Korean Won', '', '', 'KPW', '0', '0', 'asiapacific', '1'),
    (25, 'Kiribati', 'KI', 'Australian Dollar', '$', '&#036;', 'AUD', '0', '0', 'asiapacific', '1'),
    (26, 'Kenya', 'KE', 'Kenyan Shilling', '', '', 'KES', '0', '0', 'africa', '1'),
    (27, 'Kazakhstan', 'KZ', 'Kazakhstan Tenge', '', '', 'KZT', '0', '0', 'asiapacific', '1'),
    (28, 'Jordan', 'JO', 'Jordanian Dinar', '', '', 'JOD', '0', '0', 'asiapacific', '1'),
    (29, 'Jersey', 'JE', 'Pound Sterling', '�', '&#163;', 'GBP', '0', '0', 'europe', '1'),
    (30, 'Japan', 'JP', 'Japanese Yen', '�', '&#165;', 'JPY', '0', '0', 'asiapacific', '1'),
    (31, 'Jamaica', 'JM', 'Jamaican Dollar', '$', '&#036;', 'JMD', '0', '0', 'southamerica', '1'),
    (32, 'Ivory Coast', 'CI', 'CFA Franc BCEAO', '', '', 'XOF', '0', '0', 'africa', '1'),
    (33, 'Italy', 'IT', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (34, 'Isle of Man', 'IM', 'Pound Sterling', '�', '&#163;', 'GBP', '0', '0', 'europe', '1'),
    (35, 'Israel', 'IL', 'Israeli New Shekel', '?', '&#8362;', 'ILS', '0', '0', 'asiapacific', '1'),
    (36, 'Ireland', 'IE', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (37, 'Iraq', 'IQ', 'Iraqi Dinar', '', '', 'IQD', '0', '0', 'asiapacific', '1'),
    (38, 'Indonesia', 'ID', 'Indonesian Rupiah', '', '', 'IDR', '0', '0', 'asiapacific', '1'),
    (39, 'Iran', 'IR', 'Iranian Rial', '', '', 'IRR', '0', '0', 'asiapacific', '1'),
    (40, 'India', 'IN', 'Indian Rupee', '', '', 'INR', '0', '0', 'asiapacific', '1'),
    (41, 'Iceland', 'IS', 'Iceland Krona', '', '', 'ISK', '0', '0', 'europe', '1'),
    (42, 'Hungary', 'HU', 'Hungarian Forint', '', '', 'HUF', '0', '0', 'europe', '1'),
    (43, 'Hong Kong', 'HK', 'Hong Kong Dollar', '$', '&#036;', 'HKD', '0', '0', 'asiapacific', '1'),
    (44, 'Honduras', 'HN', 'Honduran Lempira', '', '', 'HNL', '0', '0', 'southamerica', '1'),
    (45, 'Heard Island and McDonald Islands', 'HM', 'Australian Dollar', '$', '&#036;', 'AUD', '0', '0', 'asiapacific', '1'),
    (46, 'Haiti', 'HT', 'Haitian Gourde', '', '', 'HTG', '0', '0', 'southamerica', '1'),
    (47, 'Guyana', 'GY', 'Guyana Dollar', '$', '&#036;', 'GYD', '0', '0', 'southamerica', '1'),
    (48, 'Guinea Bissau', 'GW', 'CFA Franc BEAC', '', '', 'XAF', '0', '0', 'africa', '1'),
    (49, 'Guinea', 'GN', 'Guinea Franc', '', '', 'GNF', '0', '0', 'africa', '1'),
    (50, 'Guernsey', 'GG', 'Pound Sterling', '�', '&#163;', 'GBP', '0', '0', 'europe', '1'),
    (51, 'Guatemala', 'GT', 'Guatemalan Quetzal', '', '', 'GTQ', '0', '0', 'southamerica', '1'),
    (52, 'Guam (USA)', 'GU', 'US Dollar', '$', '&#036;', 'USD', '0', '0', 'asiapacific', '1'),
    (53, 'Grenada', 'GD', 'East Carribean Dollar', '$', '&#036;', 'XCD', '0', '0', 'africa', '1'),
    (54, 'Guadeloupe (French)', 'GP', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'southamerica', '1'),
    (55, 'Greenland', 'GL', 'Danish Krone', '', '', 'DKK', '0', '0', 'europe', '1'),
    (56, 'Greece', 'GR', 'Euro', '�', '&#8364;', 'EUR', '0', '19', 'europe', '1'),
    (57, 'Gibraltar', 'GI', 'Gibraltar Pound', '', '', 'GIP', '0', '0', 'europe', '1'),
    (58, 'Ghana', 'GH', 'Ghanaian Cedi', '', '', 'GHS', '0', '0', 'africa', '1'),
    (59, 'Germany', 'DE', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (60, 'Georgia', 'GE', 'Georgian Lari', '', '', 'GEL', '0', '0', 'europe', '1'),
    (61, 'Gambia', 'GM', 'Gambian Dalasi', '', '', 'GMD', '0', '0', 'africa', '1'),
    (62, 'Gabon', 'GA', 'CFA Franc BEAC', '', '', 'XAF', '0', '0', 'africa', '1'),
    (63, 'French Southern Territories', 'TF', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'africa', '1'),
    (64, 'France', 'FR', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (65, 'Finland', 'FI', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (66, 'Fiji', 'FJ', 'Fiji Dollar', '$', '&#036;', 'FJD', '0', '0', 'asiapacific', '1'),
    (67, 'Faroe Islands', 'FO', 'Danish Krone', '', '', 'DKK', '0', '0', 'europe', '1'),
    (68, 'Falkland Islands', 'FK', 'Falkland Islands Pound', '', '', 'FKP', '0', '0', 'southamerica', '1'),
    (69, 'Ethiopia', 'ET', 'Ethiopian Birr', '', '', 'ETB', '0', '0', 'africa', '1'),
    (70, 'Estonia', 'EE', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (71, 'Eritrea', 'ER', 'Eritrean Nakfa', '', '', 'ERN', '0', '0', 'africa', '1'),
    (72, 'Equatorial Guinea', 'GQ', 'CFA Franc BEAC', '', '', 'XAF', '0', '0', 'africa', '1'),
    (73, 'El Salvador', 'SV', 'US Dollar', '$', '&#036;', 'USD', '0', '0', 'southamerica', '1'),
    (74, 'Egypt', 'EG', 'Egyptian Pound', '', '', 'EGP', '0', '0', 'africa', '1'),
    (75, 'Ecuador', 'EC', 'Ecuador Sucre', '', '', 'ECS', '0', '0', 'southamerica', '1'),
    (76, 'Timor-Leste', 'TL', 'US Dollar', '$', '&#036;', 'USD', '0', '0', 'asiapacific', '1'),
    (77, 'Dominican Republic', 'DO', 'Dominican Peso', '', '', 'DOP', '0', '0', 'southamerica', '1'),
    (78, 'Dominica', 'DM', 'East Caribbean Dollar', '$', '&#036;', 'XCD', '0', '0', 'southamerica', '1'),
    (79, 'Djibouti', 'DJ', 'Djibouti Franc', '', '', 'DJF', '0', '0', 'africa', '1'),
    (80, 'Denmark', 'DK', 'Danish Krone', '', '', 'DKK', '0', '0', 'europe', '1'),
    (81, 'Democratic Republic of Congo', 'CD', 'Francs', '', '', 'CDF', '0', '0', 'africa', '1'),
    (82, 'Czech Rep.', 'CZ', 'Czech Koruna', '', '', 'CZK', '0', '0', 'europe', '1'),
    (83, 'Cyprus', 'CY', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (84, 'Cuba', 'CU', 'Cuban Peso', '', '', 'CUP', '0', '0', 'northamerica', '1'),
    (85, 'Croatia', 'HR', 'Croatian Kuna', '', '', 'HRK', '0', '0', 'europe', '1'),
    (86, 'Costa Rica', 'CR', 'Costa Rican Colon', '', '', 'CRC', '0', '0', 'southamerica', '1'),
    (87, 'Cook Islands', 'CK', 'New Zealand Dollar', '$', '&#036;', 'NZD', '0', '0', 'asiapacific', '1'),
    (88, 'Republic of the Congo', 'CG', 'CFA Franc BEAC', '', '', 'XAF', '0', '0', 'africa', '1'),
    (89, 'Comoros', 'KM', 'Comoros Franc', '', '', 'KMF', '0', '0', 'africa', '1'),
    (90, 'Colombia', 'CO', 'Colombian Peso', '$', '&#036;', 'COP', '0', '0', 'southamerica', '1'),
    (91, 'Cocos (Keeling) Islands', 'CC', 'Australian Dollar', '$', '&#036;', 'AUD', '0', '0', 'asiapacific', '1'),
    (92, 'Christmas Island', 'CX', 'Australian Dollar', '$', '&#036;', 'AUD', '0', '0', 'asiapacific', '1'),
    (93, 'Chile', 'CL', 'Chilean Peso', '', '', 'CLP', '0', '0', 'asiapacific', '1'),
    (94, 'China', 'CN', 'Yuan Renminbi', '', '', 'CNY', '0', '0', 'asiapacific', '1'),
    (95, 'Chad', 'TD', 'CFA Franc BEAC', '', '', 'XAF', '0', '0', 'africa', '1'),
    (96, 'Central African Republic', 'CF', 'CFA Franc BEAC', '', '', 'XAF', '0', '0', 'africa', '1'),
    (97, 'Cayman Islands', 'KY', 'Cayman Islands Dollar', '$', '&#036;', 'KYD', '0', '0', 'northamerica', '1'),
    (98, 'Cape Verde', 'CV', 'Cape Verde Escudo', '', '', 'CVE', '0', '0', 'africa', '1'),
    (99, 'Cameroon', 'CM', 'CFA Franc BEAC', '', '', 'XAF', '0', '0', 'africa', '1'),
    (100, 'Canada', 'CA', 'Canadian Dollar', '$', '&#036;', 'CAD', '1', '', 'northamerica', '1'),
    (101, 'Cambodia', 'KH', 'Kampuchean Riel', '', '', 'KHR', '0', '0', 'asiapacific', '1'),
    (102, 'Burundi', 'BI', 'Burundi Franc', '', '', 'BIF', '0', '0', 'africa', '1'),
    (103, 'Burkina Faso', 'BF', 'CFA Franc BCEAO', '', '', 'XOF', '0', '0', 'africa', '1'),
    (104, 'Bulgaria', 'BG', 'Bulgarian Lev', '', '', 'BGL', '0', '0', 'europe', '1'),
    (105, 'Brunei Darussalam', 'BN', 'Brunei Dollar', '$', '&#036;', 'BND', '0', '0', 'asiapacific', '1'),
    (106, 'British Indian Ocean Territory', 'IO', 'US Dollar', '$', '&#036;', 'USD', '0', '0', 'asiapacific', '1'),
    (107, 'Brazil', 'BR', 'Brazilian Real', '', '', 'BRL', '0', '0', 'southamerica', '1'),
    (108, 'Bouvet Island', 'BV', 'Norwegian Krone', '', '', 'NOK', '0', '0', 'africa', '1'),
    (109, 'Botswana', 'BW', 'Botswana Pula', '', '', 'BWP', '0', '0', 'africa', '1'),
    (110, 'Bosnia-Herzegovina', 'BA', 'Marka', '', '', 'BAM', '0', '0', 'europe', '1'),
    (111, 'Bolivia', 'BO', 'Boliviano', '', '', 'BOB', '0', '0', 'southamerica', '1'),
    (112, 'Bhutan', 'BT', 'Bhutan Ngultrum', '', '', 'BTN', '0', '0', 'asiapacific', '1'),
    (113, 'Bermuda', 'BM', 'Bermudian Dollar', '$', '&#036;', 'BMD', '0', '0', 'europe', '1'),
    (114, 'Benin', 'BJ', 'CFA Franc BCEAO', '', '', 'XOF', '0', '0', 'africa', '1'),
    (115, 'Belize', 'BZ', 'Belize Dollar', '$', '&#036;', 'BZD', '0', '0', 'northamerica', '1'),
    (116, 'Belgium', 'BE', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (117, 'Belarus', 'BY', 'Belarussian Ruble', '', '', 'BYR', '0', '0', 'europe', '1'),
    (118, 'Barbados', 'BB', 'Barbados Dollar', '$', '&#036;', 'BBD', '0', '0', 'southamerica', '1'),
    (119, 'Bangladesh', 'BD', 'Bangladeshi Taka', '', '', 'BDT', '0', '0', 'asiapacific', '1'),
    (120, 'Bahrain', 'BH', 'Bahraini Dinar', '', '', 'BHD', '0', '0', 'asiapacific', '1'),
    (121, 'Bahamas', 'BS', 'Bahamian Dollar', '$', '&#036;', 'BSD', '0', '0', 'northamerica', '1'),
    (122, 'Azerbaijan', 'AZ', 'Azerbaijani Manat', 'm', 'm', 'AZN', '0', '0', 'asiapacific', '1'),
    (123, 'Austria', 'AT', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (124, 'Aruba', 'AW', 'Aruban Florin', 'Afl.', 'Afl.', 'AWG', '0', '0', 'southamerica', '1'),
    (125, 'Armenia', 'AM', 'Armenian Dram', '', '', 'AMD', '0', '0', 'asiapacific', '1'),
    (126, 'Argentina', 'AR', 'Argentine Peso', '', '', 'ARS', '0', '0', 'southamerica', '1'),
    (127, 'Antigua and Barbuda', 'AG', 'East Caribbean Dollar', '$', '&#036;', 'XCD', '0', '0', 'africa', '1'),
    (128, 'Antarctica', 'AQ', 'Dollar', '$', '&#036;', 'ATA', '0', '0', 'antarctica', '1'),
    (129, 'Anguilla', 'AI', 'East Caribbean Dollar', '$', '&#036;', 'XCD', '0', '0', 'northamerica', '1'),
    (130, 'Angola', 'AO', 'Angolan Kwanza', 'Kz', 'Kz', 'AOA', '0', '0', 'africa', '1'),
    (131, 'Andorra', 'AD', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (132, 'American Samoa', 'AS', 'US Dollar', '$', '&#036;', 'USD', '0', '0', 'asiapacific', '1'),
    (133, 'Algeria', 'DZ', 'Algerian Dinar', '', '', 'DZD', '0', '0', 'africa', '1'),
    (134, 'Albania', 'AL', 'Albanian Lek', '', '', 'ALL', '0', '0', 'europe', '1'),
    (135, 'Afghanistan', 'AF', 'Afghanistan Afghani', '', '', 'AFA', '0', '0', 'asiapacific', '1'),
    (136, 'USA', 'US', 'US Dollar', '$', '&#036;', 'USD', '1', '', 'northamerica', '1'),
    (137, 'Australia', 'AU', 'Australian Dollar', '$', '&#036;', 'AUD', '0', '0', 'asiapacific', '1'),
    (139, 'Mauritius', 'MU', 'Mauritius Rupee', '', '', 'MUR', '0', '0', 'africa', '1'),
    (140, 'Mayotte', 'YT', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (141, 'Mexico', 'MX', 'Mexican Peso', '$', '&#036;', 'MXN', '1', '', 'northamerica', '1'),
    (142, 'Micronesia', 'FM', 'US Dollar', '$', '&#036;', 'USD', '0', '0', 'asiapacific', '1'),
    (143, 'Moldova', 'MD', 'Moldovan Leu', '', '', 'MDL', '0', '0', 'europe', '1'),
    (144, 'Monaco', 'MC', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (145, 'Mongolia', 'MN', 'Mongolian Tugrik', '', '', 'MNT', '0', '0', 'asiapacific', '1'),
    (146, 'Montserrat', 'MS', 'East Caribbean Dollar', '$', '&#036;', 'XCD', '0', '0', 'africa', '1'),
    (147, 'Morocco', 'MA', 'Moroccan Dirham', '', '', 'MAD', '0', '0', 'africa', '1'),
    (148, 'Mozambique', 'MZ', 'Mozambique Metical', '', '', 'MZN', '0', '0', 'africa', '1'),
    (149, 'Myanmar', 'MM', 'Myanmar Kyat', '', '', 'MMK', '0', '0', 'asiapacific', '1'),
    (150, 'Namibia', 'NA', 'Namibian Dollar', '$', '&#036;', 'NAD', '0', '0', 'africa', '1'),
    (151, 'Nauru', 'NR', 'Australian Dollar', '$', '&#036;', 'AUD', '0', '0', 'asiapacific', '1'),
    (152, 'Nepal', 'NP', 'Nepalese Rupee', '', '', 'NPR', '0', '0', 'asiapacific', '1'),
    (153, 'Netherlands', 'NL', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (154, 'Netherlands Antilles', 'AN', 'Netherlands Antillean Guilder', '�', '&#402;', 'ANG', '0', '0', 'africa', '1'),
    (155, 'New Caledonia (French)', 'NC', 'CFP Franc', '', '', 'XPF', '0', '0', 'asiapacific', '1'),
    (156, 'New Zealand', 'NZ', 'New Zealand Dollar', '$', '&#036;', 'NZD', '0', '12.5', 'asiapacific', '1'),
    (157, 'Nicaragua', 'NI', 'Nicaraguan Cordoba Oro', '', '', 'NIO', '0', '0', 'northamerica', '1'),
    (158, 'Niger', 'NE', 'CFA Franc BCEAO', '', '', 'XOF', '0', '0', 'africa', '1'),
    (159, 'Nigeria', 'NG', 'Nigerian Naira', '', '', 'NGN', '0', '0', 'africa', '1'),
    (160, 'Niue', 'NU', 'New Zealand Dollar', '$', '&#036;', 'NZD', '0', '0', 'asiapacific', '1'),
    (161, 'Norfolk Island', 'NF', 'Australian Dollar', '$', '&#036;', 'AUD', '0', '0', 'asiapacific', '1'),
    (162, 'Northern Mariana Islands', 'MP', 'US Dollar', '$', '&#036;', 'USD', '0', '0', 'asiapacific', '1'),
    (163, 'Norway', 'NO', 'Norwegian Krone', '', '', 'NOK', '0', '0', 'europe', '1'),
    (164, 'Oman', 'OM', 'Omani Rial', '', '', 'OMR', '0', '0', 'asiapacific', '1'),
    (165, 'Pakistan', 'PK', 'Pakistan Rupee', '', '', 'PKR', '0', '0', 'asiapacific', '1'),
    (166, 'Palau', 'PW', 'US Dollar', '$', '&#036;', 'USD', '0', '0', 'asiapacific', '1'),
    (167, 'Panama', 'PA', 'Panamanian Balboa', '', '', 'PAB', '0', '0', 'southamerica', '1'),
    (168, 'Papua New Guinea', 'PG', 'Papua New Guinea Kina', '', '', 'PGK', '0', '0', 'asiapacific', '1'),
    (169, 'Paraguay', 'PY', 'Paraguay Guarani', '', '', 'PYG', '0', '0', 'southamerica', '1'),
    (170, 'Peru', 'PE', 'Peruvian Nuevo Sol', '', '', 'PEN', '0', '0', 'southamerica', '1'),
    (171, 'Philippines', 'PH', 'Philippine Peso', '', '', 'PHP', '0', '0', 'asiapacific', '1'),
    (172, 'Pitcairn Island', 'PN', 'New Zealand Dollar', '$', '&#036;', 'NZD', '0', '0', 'asiapacific', '1'),
    (173, 'Poland', 'PL', 'Polish Zloty', '', '', 'PLN', '0', '0', 'europe', '1'),
    (174, 'Polynesia (French)', 'PF', 'CFP Franc', '', '', 'XPF', '0', '0', 'asiapacific', '1'),
    (175, 'Portugal', 'PT', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (176, 'Puerto Rico', 'PR', 'US Dollar', '$', '&#036;', 'USD', '0', '0', 'northamerica', '1'),
    (177, 'Qatar', 'QA', 'Qatari Rial', '', '', 'QAR', '0', '0', 'asiapacific', '1'),
    (178, 'Reunion (French)', 'RE', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (179, 'Romania', 'RO', 'Romanian New Leu', '', '', 'RON', '0', '0', 'europe', '1'),
    (180, 'Russia', 'RU', 'Russian Ruble', '', '', 'RUB', '0', '0', 'europe', '1'),
    (181, 'Rwanda', 'RW', 'Rwanda Franc', '', '', 'RWF', '0', '0', 'africa', '1'),
    (182, 'Saint Helena', 'SH', 'St. Helena Pound', '', '', 'SHP', '0', '0', 'africa', '1'),
    (183, 'Saint Kitts & Nevis Anguilla', 'KN', 'East Caribbean Dollar', '$', '&#036;', 'XCD', '0', '0', 'northamerica', '1'),
    (184, 'Saint Lucia', 'LC', 'East Caribbean Dollar', '$', '&#036;', 'XCD', '0', '0', 'northamerica', '1'),
    (185, 'Saint Pierre and Miquelon', 'PM', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'northamerica', '1'),
    (186, 'Saint Vincent & Grenadines', 'VC', 'East Caribbean Dollar', '$', '&#036;', 'XCD', '0', '0', 'northamerica', '1'),
    (187, 'Samoa', 'WS', 'Samoan Tala', '', '', 'WST', '0', '0', 'asiapacific', '1'),
    (188, 'San Marino', 'SM', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (189, 'Sao Tome and Principe', 'ST', 'Dobra', '', '', 'STD', '0', '0', 'africa', '1'),
    (190, 'Saudi Arabia', 'SA', 'Saudi Riyal', '', '', 'SAR', '0', '0', 'asiapacific', '1'),
    (191, 'Senegal', 'SN', 'CFA Franc BCEAO', '', '', 'XOF', '0', '0', 'africa', '1'),
    (192, 'Seychelles', 'SC', 'Seychelles Rupee', '', '', 'SCR', '0', '0', 'africa', '1'),
    (193, 'Sierra Leone', 'SL', 'Sierra Leone Leone', '', '', 'SLL', '0', '0', 'africa', '1'),
    (194, 'Singapore', 'SG', 'Singapore Dollar', '$', '&#036;', 'SGD', '0', '0', 'asiapacific', '1'),
    (195, 'Slovakia', 'SK', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (196, 'Slovenia', 'SI', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (197, 'Solomon Islands', 'SB', 'Solomon Islands Dollar', '$', '&#036;', 'SBD', '0', '0', 'asiapacific', '1'),
    (198, 'Somalia', 'SO', 'Somali Shilling', '', '', 'SOS', '0', '0', 'africa', '1'),
    (199, 'South Africa', 'ZA', 'South African Rand', '', '', 'ZAR', '0', '0', 'africa', '1'),
    (200, 'South Georgia & South Sandwich Islands', 'GS', 'Pound Sterling', '�', '&#163;', 'GBP', '0', '0', 'southamerica', '1'),
    (201, 'Spain', 'ES', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (202, 'Sri Lanka', 'LK', 'Sri Lanka Rupee', '', '', 'LKR', '0', '0', 'asiapacific', '1'),
    (203, 'Sudan', 'SD', 'Sudanese Pound', '', '', 'SDG', '0', '0', 'africa', '1'),
    (204, 'Suriname', 'SR', 'Surinamese Dollar', '', '', 'SRD', '0', '0', 'southamerica', '1'),
    (205, 'Svalbard and Jan Mayen Islands', 'SJ', 'Norwegian Krone', '', '', 'NOK', '0', '0', '', '1'),
    (206, 'Swaziland', 'SZ', 'Swaziland Lilangeni', '', '', 'SZL', '0', '0', 'africa', '1'),
    (207, 'Sweden', 'SE', 'Swedish Krona', '', '', 'SEK', '0', '0', 'europe', '1'),
    (208, 'Switzerland', 'CH', 'Swiss Franc', '', '', 'CHF', '0', '0', 'europe', '1'),
    (209, 'Syria', 'SY', 'Syrian Pound', '', '', 'SYP', '0', '0', 'europe', '1'),
    (210, 'Taiwan', 'TW', 'New Taiwan Dollar', '$', '&#036;', 'TWD', '0', '0', 'asiapacific', '1'),
    (211, 'Tajikistan', 'TJ', 'Tajik Somoni', '', '', 'TJS', '0', '0', 'asiapacific', '1'),
    (212, 'Tanzania', 'TZ', 'Tanzanian Shilling', '', '', 'TZS', '0', '0', 'africa', '1'),
    (213, 'Thailand', 'TH', 'Thai Baht', '', '', 'THB', '0', '0', 'asiapacific', '1'),
    (214, 'Togo', 'TG', 'CFA Franc BCEAO', '', '', 'XOF', '0', '0', 'africa', '1'),
    (215, 'Tokelau', 'TK', 'New Zealand Dollar', '$', '&#036;', 'NZD', '0', '0', 'asiapacific', '1'),
    (216, 'Tonga', 'TO', 'Tongan Pa&#699;anga', '', '', 'TOP', '0', '0', 'asiapacific', '1'),
    (217, 'Trinidad and Tobago', 'TT', 'Trinidad and Tobago Dollar', '$', '&#036;', 'TTD', '0', '0', 'africa', '1'),
    (218, 'Tunisia', 'TN', 'Tunisian Dinar', '$', '&#036;', 'TND', '0', '0', 'africa', '1'),
    (219, 'Turkey', 'TR', 'Turkish Lira', '', '', 'TRY', '0', '0', 'asiapacific', '1'),
    (220, 'Turkmenistan', 'TM', 'Manat', '', '', 'TMM', '0', '0', 'asiapacific', '1'),
    (221, 'Turks and Caicos Islands', 'TC', 'US Dollar', '$', '&#036;', 'USD', '0', '0', 'northamerica', '1'),
    (222, 'Tuvalu', 'TV', 'Australian Dollar', '$', '&#036;', 'AUD', '0', '0', 'asiapacific', '1'),
    (223, 'United Kingdom', 'GB', 'Pound Sterling', '�', '&#163;', 'GBP', '0', '17.5', 'europe', '1'),
    (224, 'Uganda', 'UG', 'Uganda Shilling', '', '', 'UGX', '0', '0', 'africa', '1'),
    (225, 'Ukraine', 'UA', 'Ukraine Hryvnia', '?', '&#8372;', 'UAH', '0', '0', 'europe', '1'),
    (226, 'United Arab Emirates', 'AE', 'Arab Emirates Dirham', '', '', 'AED', '0', '0', 'asiapacific', '1'),
    (227, 'Uruguay', 'UY', 'Uruguayan Peso', '', '', 'UYU', '0', '0', 'southamerica', '1'),
    (228, 'USA Minor Outlying Islands', 'UM', 'US Dollar', '$', '&#036;', 'USD', '0', '0', '', '1'),
    (229, 'Uzbekistan', 'UZ', 'Uzbekistan Sum', '', '', 'UZS', '0', '0', 'asiapacific', '1'),
    (230, 'Vanuatu', 'VU', 'Vanuatu Vatu', '', '', 'VUV', '0', '0', 'asiapacific', '1'),
    (231, 'Vatican', 'VA', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (232, 'Venezuela', 'VE', 'Venezuelan Bolivar Fuerte', '', '', 'VEF', '0', '0', 'asiapacific', '1'),
    (233, 'Vietnam', 'VN', 'Vietnamese Dong', '', '', 'VND', '0', '0', 'asiapacific', '1'),
    (234, 'Virgin Islands (British)', 'VG', 'US Dollar', '$', '&#036;', 'USD', '0', '0', 'northamerica', '1'),
    (235, 'Virgin Islands (USA)', 'VI', 'US Dollar', '$', '&#036;', 'USD', '0', '0', 'northamerica', '1'),
    (236, 'Wallis and Futuna Islands', 'WF', 'CFP Franc', '', '', 'XPF', '0', '0', 'asiapacific', '1'),
    (237, 'Western Sahara', 'EH', 'Moroccan Dirham', '', '', 'MAD', '0', '0', 'africa', '1'),
    (238, 'Yemen', 'YE', 'Yemeni Rial', '', '', 'YER', '0', '0', 'asiapacific', '1'),
    (240, 'Zambia', 'ZM', 'Zambian Kwacha', '', '', 'ZMK', '0', '0', 'africa', '1'),
    (241, 'Zimbabwe', 'ZW', 'US Dollar', '$', '&#036;', 'USD', '0', '0', 'africa', '1'),
    (242, 'South Sudan', 'SS', 'South Sudanese Pound', '', '', 'SSP', '0', '0', 'africa', '1'),
    (243, 'Serbia', 'RS', 'Serbian Dinar', '', '', 'RSD', '0', '0', 'europe', '1'),
    (244, 'Montenegro', 'ME', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (246, 'Aland Islands', 'AX', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (247, 'Saint Barthelemy', 'BL', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'europe', '1'),
    (248, 'Bonaire, Sint Eustatius and Saba', 'BQ', 'US Dollar', '$', '&#036;', 'USD', '0', '0', 'southamerica', '1'),
    (249, 'Curacao', 'CW', 'Netherlands Antillean Guilder', '�', '&#402;', 'ANG', '0', '0', 'southamerica', '1'),
    (250, 'Saint Martin (French Part)', 'MF', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'southamerica', '1'),
    (251, 'Palestinian Territories', 'PS', 'Israeli New Shekel', '?', '&#8362;', 'ILS', '0', '0', 'asiapacific', '1'),
    (252, 'Sint Maarten (Dutch Part)', 'SX', 'Netherlands Antillean Guilder', '�', '&#402;', 'ANG', '0', '0', 'africa', '1'),
    (253, 'French Guiana', 'GF', 'Euro', '�', '&#8364;', 'EUR', '0', '0', 'southamerica', '1');";
    $wpdb->query($currency_tbl_data);
   }
	 //===============================End Currency Table==============================
	 $ustsappointment_calendar_page_id = appointgen_programmatically_create_page('Appointment Calendar','usts-appointment-calendar','[appointgen_ustscalendar]','page');
}