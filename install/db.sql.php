<?php
$objDatabase->dbQuery("CREATE TABLE IF NOT EXISTS `".$configArray->prefix."configuration` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `config_key` varchar(100) NOT NULL,
  `config_label` varchar(200) NOT NULL,
  `config_value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1") ;

$objDatabase->dbQuery("CREATE TABLE IF NOT EXISTS `".$configArray->prefix."countries` (
  `country_id` smallint(1) unsigned NOT NULL AUTO_INCREMENT,
  `country_name` char(64) DEFAULT NULL,
  `country_3_code` char(3) DEFAULT NULL,
  `country_2_code` char(2) DEFAULT NULL,
  PRIMARY KEY (`country_id`),
  KEY `idx_country_3_code` (`country_3_code`),
  KEY `idx_country_2_code` (`country_2_code`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='Country records' AUTO_INCREMENT=249");

$objDatabase->dbQuery("INSERT INTO `".$configArray->prefix."countries` (`country_id`, `country_name`, `country_3_code`, `country_2_code`) VALUES
(1, 'Afghanistan', 'AFG', 'AF'),
(2, 'Albania', 'ALB', 'AL'),
(3, 'Algeria', 'DZA', 'DZ'),
(4, 'American Samoa', 'ASM', 'AS'),
(5, 'Andorra', 'AND', 'AD'),
(6, 'Angola', 'AGO', 'AO'),
(7, 'Anguilla', 'AIA', 'AI'),
(8, 'Antarctica', 'ATA', 'AQ'),
(9, 'Antigua and Barbuda', 'ATG', 'AG'),
(10, 'Argentina', 'ARG', 'AR'),
(11, 'Armenia', 'ARM', 'AM'),
(12, 'Aruba', 'ABW', 'AW'),
(13, 'Australia', 'AUS', 'AU'),
(14, 'Austria', 'AUT', 'AT'),
(15, 'Azerbaijan', 'AZE', 'AZ'),
(16, 'Bahamas', 'BHS', 'BS'),
(17, 'Bahrain', 'BHR', 'BH'),
(18, 'Bangladesh', 'BGD', 'BD'),
(19, 'Barbados', 'BRB', 'BB'),
(20, 'Belarus', 'BLR', 'BY'),
(21, 'Belgium', 'BEL', 'BE'),
(22, 'Belize', 'BLZ', 'BZ'),
(23, 'Benin', 'BEN', 'BJ'),
(24, 'Bermuda', 'BMU', 'BM'),
(25, 'Bhutan', 'BTN', 'BT'),
(26, 'Bolivia', 'BOL', 'BO'),
(27, 'Bosnia and Herzegowina', 'BIH', 'BA'),
(28, 'Botswana', 'BWA', 'BW'),
(29, 'Bouvet Island', 'BVT', 'BV'),
(30, 'Brazil', 'BRA', 'BR'),
(31, 'British Indian Ocean Territory', 'IOT', 'IO'),
(32, 'Brunei Darussalam', 'BRN', 'BN'),
(33, 'Bulgaria', 'BGR', 'BG'),
(34, 'Burkina Faso', 'BFA', 'BF'),
(35, 'Burundi', 'BDI', 'BI'),
(36, 'Cambodia', 'KHM', 'KH'),
(37, 'Cameroon', 'CMR', 'CM'),
(38, 'Canada', 'CAN', 'CA'),
(39, 'Cape Verde', 'CPV', 'CV'),
(40, 'Cayman Islands', 'CYM', 'KY'),
(41, 'Central African Republic', 'CAF', 'CF'),
(42, 'Chad', 'TCD', 'TD'),
(43, 'Chile', 'CHL', 'CL'),
(44, 'China', 'CHN', 'CN'),
(45, 'Christmas Island', 'CXR', 'CX'),
(46, 'Cocos (Keeling) Islands', 'CCK', 'CC'),
(47, 'Colombia', 'COL', 'CO'),
(48, 'Comoros', 'COM', 'KM'),
(49, 'Congo', 'COG', 'CG'),
(50, 'Cook Islands', 'COK', 'CK'),
(51, 'Costa Rica', 'CRI', 'CR'),
(52, 'Cote D''Ivoire', 'CIV', 'CI'),
(53, 'Croatia', 'HRV', 'HR'),
(54, 'Cuba', 'CUB', 'CU'),
(55, 'Cyprus', 'CYP', 'CY'),
(56, 'Czech Republic', 'CZE', 'CZ'),
(57, 'Denmark', 'DNK', 'DK'),
(58, 'Djibouti', 'DJI', 'DJ'),
(59, 'Dominica', 'DMA', 'DM'),
(60, 'Dominican Republic', 'DOM', 'DO'),
(61, 'East Timor', 'TMP', 'TP'),
(62, 'Ecuador', 'ECU', 'EC'),
(63, 'Egypt', 'EGY', 'EG'),
(64, 'El Salvador', 'SLV', 'SV'),
(65, 'Equatorial Guinea', 'GNQ', 'GQ'),
(66, 'Eritrea', 'ERI', 'ER'),
(67, 'Estonia', 'EST', 'EE'),
(68, 'Ethiopia', 'ETH', 'ET'),
(69, 'Falkland Islands (Malvinas)', 'FLK', 'FK'),
(70, 'Faroe Islands', 'FRO', 'FO'),
(71, 'Fiji', 'FJI', 'FJ'),
(72, 'Finland', 'FIN', 'FI'),
(73, 'France', 'FRA', 'FR'),
(75, 'French Guiana', 'GUF', 'GF'),
(76, 'French Polynesia', 'PYF', 'PF'),
(77, 'French Southern Territories', 'ATF', 'TF'),
(78, 'Gabon', 'GAB', 'GA'),
(79, 'Gambia', 'GMB', 'GM'),
(80, 'Georgia', 'GEO', 'GE'),
(81, 'Germany', 'DEU', 'DE'),
(82, 'Ghana', 'GHA', 'GH'),
(83, 'Gibraltar', 'GIB', 'GI'),
(84, 'Greece', 'GRC', 'GR'),
(85, 'Greenland', 'GRL', 'GL'),
(86, 'Grenada', 'GRD', 'GD'),
(87, 'Guadeloupe', 'GLP', 'GP'),
(88, 'Guam', 'GUM', 'GU'),
(89, 'Guatemala', 'GTM', 'GT'),
(90, 'Guinea', 'GIN', 'GN'),
(91, 'Guinea-bissau', 'GNB', 'GW'),
(92, 'Guyana', 'GUY', 'GY'),
(93, 'Haiti', 'HTI', 'HT'),
(94, 'Heard and Mc Donald Islands', 'HMD', 'HM'),
(95, 'Honduras', 'HND', 'HN'),
(96, 'Hong Kong', 'HKG', 'HK'),
(97, 'Hungary', 'HUN', 'HU'),
(98, 'Iceland', 'ISL', 'IS'),
(99, 'India', 'IND', 'IN'),
(100, 'Indonesia', 'IDN', 'ID'),
(101, 'Iran (Islamic Republic of)', 'IRN', 'IR'),
(102, 'Iraq', 'IRQ', 'IQ'),
(103, 'Ireland', 'IRL', 'IE'),
(104, 'Israel', 'ISR', 'IL'),
(105, 'Italy', 'ITA', 'IT'),
(106, 'Jamaica', 'JAM', 'JM'),
(107, 'Japan', 'JPN', 'JP'),
(108, 'Jordan', 'JOR', 'JO'),
(109, 'Kazakhstan', 'KAZ', 'KZ'),
(110, 'Kenya', 'KEN', 'KE'),
(111, 'Kiribati', 'KIR', 'KI'),
(112, 'Korea, Democratic People''s Republic of', 'PRK', 'KP'),
(113, 'Korea, Republic of', 'KOR', 'KR'),
(114, 'Kuwait', 'KWT', 'KW'),
(115, 'Kyrgyzstan', 'KGZ', 'KG'),
(116, 'Lao People''s Democratic Republic', 'LAO', 'LA'),
(117, 'Latvia', 'LVA', 'LV'),
(118, 'Lebanon', 'LBN', 'LB'),
(119, 'Lesotho', 'LSO', 'LS'),
(120, 'Liberia', 'LBR', 'LR'),
(121, 'Libya', 'LBY', 'LY'),
(122, 'Liechtenstein', 'LIE', 'LI'),
(123, 'Lithuania', 'LTU', 'LT'),
(124, 'Luxembourg', 'LUX', 'LU'),
(125, 'Macau', 'MAC', 'MO'),
(126, 'Macedonia, The Former Yugoslav Republic of', 'MKD', 'MK'),
(127, 'Madagascar', 'MDG', 'MG'),
(128, 'Malawi', 'MWI', 'MW'),
(129, 'Malaysia', 'MYS', 'MY'),
(130, 'Maldives', 'MDV', 'MV'),
(131, 'Mali', 'MLI', 'ML'),
(132, 'Malta', 'MLT', 'MT'),
(133, 'Marshall Islands', 'MHL', 'MH'),
(134, 'Martinique', 'MTQ', 'MQ'),
(135, 'Mauritania', 'MRT', 'MR'),
(136, 'Mauritius', 'MUS', 'MU'),
(137, 'Mayotte', 'MYT', 'YT'),
(138, 'Mexico', 'MEX', 'MX'),
(139, 'Micronesia, Federated States of', 'FSM', 'FM'),
(140, 'Moldova, Republic of', 'MDA', 'MD'),
(141, 'Monaco', 'MCO', 'MC'),
(142, 'Mongolia', 'MNG', 'MN'),
(143, 'Montserrat', 'MSR', 'MS'),
(144, 'Morocco', 'MAR', 'MA'),
(145, 'Mozambique', 'MOZ', 'MZ'),
(146, 'Myanmar', 'MMR', 'MM'),
(147, 'Namibia', 'NAM', 'NA'),
(148, 'Nauru', 'NRU', 'NR'),
(149, 'Nepal', 'NPL', 'NP'),
(150, 'Netherlands', 'NLD', 'NL'),
(151, 'Netherlands Antilles', 'ANT', 'AN'),
(152, 'New Caledonia', 'NCL', 'NC'),
(153, 'New Zealand', 'NZL', 'NZ'),
(154, 'Nicaragua', 'NIC', 'NI'),
(155, 'Niger', 'NER', 'NE'),
(156, 'Nigeria', 'NGA', 'NG'),
(157, 'Niue', 'NIU', 'NU'),
(158, 'Norfolk Island', 'NFK', 'NF'),
(159, 'Northern Mariana Islands', 'MNP', 'MP'),
(160, 'Norway', 'NOR', 'NO'),
(161, 'Oman', 'OMN', 'OM'),
(162, 'Pakistan', 'PAK', 'PK'),
(163, 'Palau', 'PLW', 'PW'),
(164, 'Panama', 'PAN', 'PA'),
(165, 'Papua New Guinea', 'PNG', 'PG'),
(166, 'Paraguay', 'PRY', 'PY'),
(167, 'Peru', 'PER', 'PE'),
(168, 'Philippines', 'PHL', 'PH'),
(169, 'Pitcairn', 'PCN', 'PN'),
(170, 'Poland', 'POL', 'PL'),
(171, 'Portugal', 'PRT', 'PT'),
(172, 'Puerto Rico', 'PRI', 'PR'),
(173, 'Qatar', 'QAT', 'QA'),
(174, 'Reunion', 'REU', 'RE'),
(175, 'Romania', 'ROM', 'RO'),
(176, 'Russian Federation', 'RUS', 'RU'),
(177, 'Rwanda', 'RWA', 'RW'),
(178, 'Saint Kitts and Nevis', 'KNA', 'KN'),
(179, 'Saint Lucia', 'LCA', 'LC'),
(180, 'Saint Vincent and the Grenadines', 'VCT', 'VC'),
(181, 'Samoa', 'WSM', 'WS'),
(182, 'San Marino', 'SMR', 'SM'),
(183, 'Sao Tome and Principe', 'STP', 'ST'),
(184, 'Saudi Arabia', 'SAU', 'SA'),
(185, 'Senegal', 'SEN', 'SN'),
(186, 'Seychelles', 'SYC', 'SC'),
(187, 'Sierra Leone', 'SLE', 'SL'),
(188, 'Singapore', 'SGP', 'SG'),
(189, 'Slovakia', 'SVK', 'SK'),
(190, 'Slovenia', 'SVN', 'SI'),
(191, 'Solomon Islands', 'SLB', 'SB'),
(192, 'Somalia', 'SOM', 'SO'),
(193, 'South Africa', 'ZAF', 'ZA'),
(194, 'South Georgia and the South Sandwich Islands', 'SGS', 'GS'),
(195, 'Spain', 'ESP', 'ES'),
(196, 'Sri Lanka', 'LKA', 'LK'),
(197, 'St. Helena', 'SHN', 'SH'),
(198, 'St. Pierre and Miquelon', 'SPM', 'PM'),
(199, 'Sudan', 'SDN', 'SD'),
(200, 'Suriname', 'SUR', 'SR'),
(201, 'Svalbard and Jan Mayen Islands', 'SJM', 'SJ'),
(202, 'Swaziland', 'SWZ', 'SZ'),
(203, 'Sweden', 'SWE', 'SE'),
(204, 'Switzerland', 'CHE', 'CH'),
(205, 'Syrian Arab Republic', 'SYR', 'SY'),
(206, 'Taiwan', 'TWN', 'TW'),
(207, 'Tajikistan', 'TJK', 'TJ'),
(208, 'Tanzania, United Republic of', 'TZA', 'TZ'),
(209, 'Thailand', 'THA', 'TH'),
(210, 'Togo', 'TGO', 'TG'),
(211, 'Tokelau', 'TKL', 'TK'),
(212, 'Tonga', 'TON', 'TO'),
(213, 'Trinidad and Tobago', 'TTO', 'TT'),
(214, 'Tunisia', 'TUN', 'TN'),
(215, 'Turkey', 'TUR', 'TR'),
(216, 'Turkmenistan', 'TKM', 'TM'),
(217, 'Turks and Caicos Islands', 'TCA', 'TC'),
(218, 'Tuvalu', 'TUV', 'TV'),
(219, 'Uganda', 'UGA', 'UG'),
(220, 'Ukraine', 'UKR', 'UA'),
(221, 'United Arab Emirates', 'ARE', 'AE'),
(222, 'United Kingdom', 'GBR', 'GB'),
(223, 'United States', 'USA', 'US'),
(224, 'United States Minor Outlying Islands', 'UMI', 'UM'),
(225, 'Uruguay', 'URY', 'UY'),
(226, 'Uzbekistan', 'UZB', 'UZ'),
(227, 'Vanuatu', 'VUT', 'VU'),
(228, 'Vatican City State (Holy See)', 'VAT', 'VA'),
(229, 'Venezuela', 'VEN', 'VE'),
(230, 'Viet Nam', 'VNM', 'VN'),
(231, 'Virgin Islands (British)', 'VGB', 'VG'),
(232, 'Virgin Islands (U.S.)', 'VIR', 'VI'),
(233, 'Wallis and Futuna Islands', 'WLF', 'WF'),
(234, 'Western Sahara', 'ESH', 'EH'),
(235, 'Yemen', 'YEM', 'YE'),
(237, 'The Democratic Republic of Congo', 'DRC', 'DC'),
(238, 'Zambia', 'ZMB', 'ZM'),
(239, 'Zimbabwe', 'ZWE', 'ZW'),
(240, 'East Timor', 'XET', 'XE'),
(241, 'Jersey', 'JEY', 'JE'),
(242, 'St. Barthelemy', 'XSB', 'XB'),
(243, 'St. Eustatius', 'XSE', 'XU'),
(244, 'Canary Islands', 'XCA', 'XC'),
(245, 'Serbia', 'SRB', 'RS'),
(246, 'Sint Maarten (French Antilles)', 'MAF', 'MF'),
(247, 'Sint Maarten (Netherlands Antilles)', 'SXM', 'SX'),
(248, 'Palestinian Territory, occupied', 'PSE', 'PS')");

$objDatabase->dbQuery("CREATE TABLE IF NOT EXISTS `".$configArray->prefix."joblocation` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_listing` varchar(250) NOT NULL,
  `lat` float(10,6) NOT NULL,
  `lag` float(10,6) NOT NULL,
  `map_widget` text NOT NULL,
  `gallery` text NOT NULL,
  `location_address` varchar(1000) NOT NULL,
  `assigned_to` varchar(1000) NOT NULL,
  `phn_no` bigint(10) NOT NULL,
  `importent_notes` text NOT NULL,
  `location_id` int(11) NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `progress` smallint(1) NOT NULL DEFAULT '0',
  `enabled_reports` text NOT NULL,
  `priority_status` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `completion_date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

$objDatabase->dbQuery("CREATE TABLE IF NOT EXISTS `".$configArray->prefix."modules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` longtext NOT NULL,
  `module_dir` varchar(100) NOT NULL,
  `status` int(11) NOT NULL,
  `version` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `title` (`title`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2");

$objDatabase->dbQuery("INSERT INTO `".$configArray->prefix."modules` (`id`, `title`, `description`, `module_dir`, `status`, `version`) VALUES
(1, 'JobNotes&trade; Admin Pack', 'This plugin includes a set of standard administrative modules for your dashboard. Track your projects with the <b>Location Status Module</b>, see the members of your team and edit their status with the <b>Staff Module</b> and review your inventory with individual Inventory Modules that track usage of your inventory by staff in the field.', 'admin', 1, '1.03.11')");


$objDatabase->dbQuery("CREATE TABLE IF NOT EXISTS `".$configArray->prefix."pages` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_title` varchar(200) NOT NULL,
  `detailed_description` longtext NOT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `meta_title` varchar(200) NOT NULL,
  `meta_keywords` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3");

$objDatabase->dbQuery("INSERT INTO `".$configArray->prefix."pages` (`id`, `page_title`, `detailed_description`, `status`, `meta_title`, `meta_keywords`) VALUES
(1,	'About Us',	'Coming Soon',	1,	'About Us',	''),
(2,	'Upgrades',	'Coming Soon',	1,	'',	''),
(3,	'GPS Tracking',	'JobNotes focus is to help centralize your business needs.<br/>\r\nWe are able to supply API access to many current GPS systems you may already use or help set you up with one to meet your needs.',	1,	'',	''),
(4,	'Customer Management ',	'JobNotes focus is to help centralize your business needs.<br>\nWe are able to supply / create customer management to fit the demands of your particular business needs or integrate your current systems.\n',	1,	'',	'')");

$objDatabase->dbQuery("CREATE TABLE IF NOT EXISTS `".$configArray->prefix."reports` (
  `report_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `site_id` int(11) unsigned NOT NULL,
  `report_name` varchar(200) NOT NULL,
  `form_description` longtext NOT NULL,
  `form_body` longtext NOT NULL,
  `send_to` text NOT NULL,
  `mail_subject` varchar(200) NOT NULL,
  `submissions` int(11) NOT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

$objDatabase->dbQuery("CREATE TABLE IF NOT EXISTS `".$configArray->prefix."report_submission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `report_id` int(11) unsigned NOT NULL,
  `location_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `form_values` longtext NOT NULL,
  `submission_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `submitted_by` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `report_id` (`report_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

$objDatabase->dbQuery("CREATE TABLE IF NOT EXISTS `".$configArray->prefix."role_permission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `permission` varchar(200) NOT NULL,
  `module` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16");

$objDatabase->dbQuery("INSERT INTO `".$configArray->prefix."role_permission` (`id`, `permission`, `module`) VALUES
(1, 'View Reports', 'reports'),
(2, 'Add & Edit Report', 'reports'),
(3, 'View Service Categories', 'service'),
(4, 'Add & Edit Service Section', 'service'),
(5, 'View Properties', 'property'),
(6, 'Add & Edit Property', 'property'),
(7, 'View Staff', 'staff'),
(8, 'Add & Edit Staff User', 'staff'),
(9, 'Import Staff', 'staff'),
(10, 'Assign Permission', 'staff'),
(11, 'Authorize Devices', 'configuration'),
(12, 'Technical Support', 'configuration'),
(13, 'View License Information', 'configuration'),
(14, 'Upgrades', 'configuration'),
(15, 'Modify Settings', 'configuration')");

$objDatabase->dbQuery("CREATE TABLE IF NOT EXISTS `".$configArray->prefix."service` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `url_slug` varchar(250) NOT NULL,
  `image` varchar(200) NOT NULL,
  `state` varchar(200) NOT NULL,
  `country` varchar(100) NOT NULL,
  `territory` varchar(100) NOT NULL,
  `branch` varchar(100) NOT NULL,
  `comments` text NOT NULL,
  `priority` varchar(200) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` smallint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

$objDatabase->dbQuery("CREATE TABLE IF NOT EXISTS `".$configArray->prefix."sites` (
  `site_id` int(11) NOT NULL AUTO_INCREMENT,
  `site_title` varchar(200) NOT NULL,
  `module_id` int(11) NOT NULL,
  `icon_class` varchar(100) NOT NULL,
  PRIMARY KEY (`site_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2");

$objDatabase->dbQuery("INSERT INTO `".$configArray->prefix."sites` (`site_id`, `site_title`) VALUES
(1, 'JobNotes&trade;')");


$objDatabase->dbQuery("CREATE TABLE IF NOT EXISTS `".$configArray->prefix."staff` (
  `id` int(32) NOT NULL AUTO_INCREMENT,
  `site_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(200) NOT NULL,
  `f_name` varchar(200) NOT NULL,
  `l_name` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `phone` bigint(10) NOT NULL,
  `gender` varchar(200) NOT NULL,
  `password` varchar(32) NOT NULL,
  `user_type` int(11) NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` smallint(1) NOT NULL DEFAULT '1',
  `is_login` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");


$objDatabase->dbQuery("CREATE TABLE IF NOT EXISTS `".$configArray->prefix."staff_permissions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `role_permission_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_id` (`role_id`,`role_permission_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1");

$objDatabase->dbQuery("CREATE TABLE IF NOT EXISTS `".$configArray->prefix."staff_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6") ;

$objDatabase->dbQuery("INSERT INTO `".$configArray->prefix."staff_type` (`id`, `label`) VALUES
(1, 'Super Administrator'),
(2, 'Administrator'),
(3, 'Staff'),
(4, 'Contractor'),
(5, 'Vendor')");

$objDatabase->dbQuery("CREATE TABLE IF NOT EXISTS `".$configArray->prefix."state` (
  `state_id` smallint(1) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` smallint(1) unsigned NOT NULL DEFAULT '1',
  `state_name` char(64) DEFAULT NULL,
  `state_3_code` char(3) DEFAULT NULL,
  `state_2_code` char(2) DEFAULT NULL,
  PRIMARY KEY (`state_id`),
  UNIQUE KEY `idx_state_3_code` (`country_id`,`state_3_code`),
  UNIQUE KEY `idx_state_2_code` (`country_id`,`state_2_code`),
  KEY `i_virtuemart_country_id` (`country_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='States that are assigned to a country' AUTO_INCREMENT=730");

$objDatabase->dbQuery("INSERT INTO `".$configArray->prefix."state` (`state_id`, `country_id`, `state_name`, `state_3_code`, `state_2_code`) VALUES
(1, 223, 'Alabama', 'ALA', 'AL'),
(2, 223, 'Alaska', 'ALK', 'AK'),
(3, 223, 'Arizona', 'ARZ', 'AZ'),
(4, 223, 'Arkansas', 'ARK', 'AR'),
(5, 223, 'California', 'CAL', 'CA'),
(6, 223, 'Colorado', 'COL', 'CO'),
(7, 223, 'Connecticut', 'CCT', 'CT'),
(8, 223, 'Delaware', 'DEL', 'DE'),
(9, 223, 'District Of Columbia', 'DOC', 'DC'),
(10, 223, 'Florida', 'FLO', 'FL'),
(11, 223, 'Georgia', 'GEA', 'GA'),
(12, 223, 'Hawaii', 'HWI', 'HI'),
(13, 223, 'Idaho', 'IDA', 'ID'),
(14, 223, 'Illinois', 'ILL', 'IL'),
(15, 223, 'Indiana', 'IND', 'IN'),
(16, 223, 'Iowa', 'IOA', 'IA'),
(17, 223, 'Kansas', 'KAS', 'KS'),
(18, 223, 'Kentucky', 'KTY', 'KY'),
(19, 223, 'Louisiana', 'LOA', 'LA'),
(20, 223, 'Maine', 'MAI', 'ME'),
(21, 223, 'Maryland', 'MLD', 'MD'),
(22, 223, 'Massachusetts', 'MSA', 'MA'),
(23, 223, 'Michigan', 'MIC', 'MI'),
(24, 223, 'Minnesota', 'MIN', 'MN'),
(25, 223, 'Mississippi', 'MIS', 'MS'),
(26, 223, 'Missouri', 'MIO', 'MO'),
(27, 223, 'Montana', 'MOT', 'MT'),
(28, 223, 'Nebraska', 'NEB', 'NE'),
(29, 223, 'Nevada', 'NEV', 'NV'),
(30, 223, 'New Hampshire', 'NEH', 'NH'),
(31, 223, 'New Jersey', 'NEJ', 'NJ'),
(32, 223, 'New Mexico', 'NEM', 'NM'),
(33, 223, 'New York', 'NEY', 'NY'),
(34, 223, 'North Carolina', 'NOC', 'NC'),
(35, 223, 'North Dakota', 'NOD', 'ND'),
(36, 223, 'Ohio', 'OHI', 'OH'),
(37, 223, 'Oklahoma', 'OKL', 'OK'),
(38, 223, 'Oregon', 'ORN', 'OR'),
(39, 223, 'Pennsylvania', 'PEA', 'PA'),
(40, 223, 'Rhode Island', 'RHI', 'RI'),
(41, 223, 'South Carolina', 'SOC', 'SC'),
(42, 223, 'South Dakota', 'SOD', 'SD'),
(43, 223, 'Tennessee', 'TEN', 'TN'),
(44, 223, 'Texas', 'TXS', 'TX'),
(45, 223, 'Utah', 'UTA', 'UT'),
(46, 223, 'Vermont', 'VMT', 'VT'),
(47, 223, 'Virginia', 'VIA', 'VA'),
(48, 223, 'Washington', 'WAS', 'WA'),
(49, 223, 'West Virginia', 'WEV', 'WV'),
(50, 223, 'Wisconsin', 'WIS', 'WI'),
(51, 223, 'Wyoming', 'WYO', 'WY'),
(52, 38, 'Alberta', 'ALB', 'AB'),
(53, 38, 'British Columbia', 'BRC', 'BC'),
(54, 38, 'Manitoba', 'MAB', 'MB'),
(55, 38, 'New Brunswick', 'NEB', 'NB'),
(56, 38, 'Newfoundland and Labrador', 'NFL', 'NL'),
(57, 38, 'Northwest Territories', 'NWT', 'NT'),
(58, 38, 'Nova Scotia', 'NOS', 'NS'),
(59, 38, 'Nunavut', 'NUT', 'NU'),
(60, 38, 'Ontario', 'ONT', 'ON'),
(61, 38, 'Prince Edward Island', 'PEI', 'PE'),
(62, 38, 'Quebec', 'QEC', 'QC'),
(63, 38, 'Saskatchewan', 'SAK', 'SK'),
(64, 38, 'Yukon', 'YUT', 'YT'),
(65, 222, 'England', 'ENG', 'EN'),
(66, 222, 'Northern Ireland', 'NOI', 'NI'),
(67, 222, 'Scotland', 'SCO', 'SD'),
(68, 222, 'Wales', 'WLS', 'WS'),
(69, 13, 'Australian Capital Territory', 'ACT', 'AC'),
(70, 13, 'New South Wales', 'NSW', 'NS'),
(71, 13, 'Northern Territory', 'NOT', 'NT'),
(72, 13, 'Queensland', 'QLD', 'QL'),
(73, 13, 'South Australia', 'SOA', 'SA'),
(74, 13, 'Tasmania', 'TAS', 'TS'),
(75, 13, 'Victoria', 'VIC', 'VI'),
(76, 13, 'Western Australia', 'WEA', 'WA'),
(77, 138, 'Aguascalientes', 'AGS', 'AG'),
(78, 138, 'Baja California Norte', 'BCN', 'BN'),
(79, 138, 'Baja California Sur', 'BCS', 'BS'),
(80, 138, 'Campeche', 'CAM', 'CA'),
(81, 138, 'Chiapas', 'CHI', 'CS'),
(82, 138, 'Chihuahua', 'CHA', 'CH'),
(83, 138, 'Coahuila', 'COA', 'CO'),
(84, 138, 'Colima', 'COL', 'CM'),
(85, 138, 'Distrito Federal', 'DFM', 'DF'),
(86, 138, 'Durango', 'DGO', 'DO'),
(87, 138, 'Guanajuato', 'GTO', 'GO'),
(88, 138, 'Guerrero', 'GRO', 'GU'),
(89, 138, 'Hidalgo', 'HGO', 'HI'),
(90, 138, 'Jalisco', 'JAL', 'JA'),
(91, 138, 'M', 'EDM', 'EM'),
(92, 138, 'Michoac', 'MCN', 'MI'),
(93, 138, 'Morelos', 'MOR', 'MO'),
(94, 138, 'Nayarit', 'NAY', 'NY'),
(95, 138, 'Nuevo Le', 'NUL', 'NL'),
(96, 138, 'Oaxaca', 'OAX', 'OA'),
(97, 138, 'Puebla', 'PUE', 'PU'),
(98, 138, 'Quer', 'QRO', 'QU'),
(99, 138, 'Quintana Roo', 'QUR', 'QR'),
(100, 138, 'San Luis Potos', 'SLP', 'SP'),
(101, 138, 'Sinaloa', 'SIN', 'SI'),
(102, 138, 'Sonora', 'SON', 'SO'),
(103, 138, 'Tabasco', 'TAB', 'TA'),
(104, 138, 'Tamaulipas', 'TAM', 'TM'),
(105, 138, 'Tlaxcala', 'TLX', 'TX'),
(106, 138, 'Veracruz', 'VER', 'VZ'),
(107, 138, 'Yucat', 'YUC', 'YU'),
(108, 138, 'Zacatecas', 'ZAC', 'ZA'),
(109, 30, 'Acre', 'ACR', 'AC'),
(110, 30, 'Alagoas', 'ALG', 'AL'),
(111, 30, 'Amap�', 'AMP', 'AP'),
(112, 30, 'Amazonas', 'AMZ', 'AM'),
(113, 30, 'Bah�a', 'BAH', 'BA'),
(114, 30, 'Cear�', 'CEA', 'CE'),
(115, 30, 'Distrito Federal', 'DFB', 'DF'),
(116, 30, 'Esp�rito Santo', 'ESS', 'ES'),
(117, 30, 'Goi�s', 'GOI', 'GO'),
(118, 30, 'Maranh�o', 'MAR', 'MA'),
(119, 30, 'Mato Grosso', 'MAT', 'MT'),
(120, 30, 'Mato Grosso do Sul', 'MGS', 'MS'),
(121, 30, 'Minas Gerais', 'MIG', 'MG'),
(122, 30, 'Paran�', 'PAR', 'PR'),
(123, 30, 'Para�ba', 'PRB', 'PB'),
(124, 30, 'Par�', 'PAB', 'PA'),
(125, 30, 'Pernambuco', 'PER', 'PE'),
(126, 30, 'Piau�', 'PIA', 'PI'),
(127, 30, 'Rio Grande do Norte', 'RGN', 'RN'),
(128, 30, 'Rio Grande do Sul', 'RGS', 'RS'),
(129, 30, 'Rio de Janeiro', 'RDJ', 'RJ'),
(130, 30, 'Rond�nia', 'RON', 'RO'),
(131, 30, 'Roraima', 'ROR', 'RR'),
(132, 30, 'Santa Catarina', 'SAC', 'SC'),
(133, 30, 'Sergipe', 'SER', 'SE'),
(134, 30, 'S�o Paulo', 'SAP', 'SP'),
(135, 30, 'Tocantins', 'TOC', 'TO'),
(136, 44, 'Anhui', 'ANH', '34'),
(137, 44, 'Beijing', 'BEI', '11'),
(138, 44, 'Chongqing', 'CHO', '50'),
(139, 44, 'Fujian', 'FUJ', '35'),
(140, 44, 'Gansu', 'GAN', '62'),
(141, 44, 'Guangdong', 'GUA', '44'),
(142, 44, 'Guangxi Zhuang', 'GUZ', '45'),
(143, 44, 'Guizhou', 'GUI', '52'),
(144, 44, 'Hainan', 'HAI', '46'),
(145, 44, 'Hebei', 'HEB', '13'),
(146, 44, 'Heilongjiang', 'HEI', '23'),
(147, 44, 'Henan', 'HEN', '41'),
(148, 44, 'Hubei', 'HUB', '42'),
(149, 44, 'Hunan', 'HUN', '43'),
(150, 44, 'Jiangsu', 'JIA', '32'),
(151, 44, 'Jiangxi', 'JIX', '36'),
(152, 44, 'Jilin', 'JIL', '22'),
(153, 44, 'Liaoning', 'LIA', '21'),
(154, 44, 'Nei Mongol', 'NML', '15'),
(155, 44, 'Ningxia Hui', 'NIH', '64'),
(156, 44, 'Qinghai', 'QIN', '63'),
(157, 44, 'Shandong', 'SNG', '37'),
(158, 44, 'Shanghai', 'SHH', '31'),
(159, 44, 'Shaanxi', 'SHX', '61'),
(160, 44, 'Sichuan', 'SIC', '51'),
(161, 44, 'Tianjin', 'TIA', '12'),
(162, 44, 'Xinjiang Uygur', 'XIU', '65'),
(163, 44, 'Xizang', 'XIZ', '54'),
(164, 44, 'Yunnan', 'YUN', '53'),
(165, 44, 'Zhejiang', 'ZHE', '33'),
(166, 104, 'Israel', 'ISL', 'IL'),
(167, 104, 'Gaza Strip', 'GZS', 'GZ'),
(168, 104, 'West Bank', 'WBK', 'WB'),
(169, 151, 'St. Maarten', 'STM', 'SM'),
(170, 151, 'Bonaire', 'BNR', 'BN'),
(171, 151, 'Curacao', 'CUR', 'CR'),
(172, 175, 'Alba', 'ABA', 'AB'),
(173, 175, 'Arad', 'ARD', 'AR'),
(174, 175, 'Arges', 'ARG', 'AG'),
(175, 175, 'Bacau', 'BAC', 'BC'),
(176, 175, 'Bihor', 'BIH', 'BH'),
(177, 175, 'Bistrita-Nasaud', 'BIS', 'BN'),
(178, 175, 'Botosani', 'BOT', 'BT'),
(179, 175, 'Braila', 'BRL', 'BR'),
(180, 175, 'Brasov', 'BRA', 'BV'),
(181, 175, 'Bucuresti', 'BUC', 'B'),
(182, 175, 'Buzau', 'BUZ', 'BZ'),
(183, 175, 'Calarasi', 'CAL', 'CL'),
(184, 175, 'Caras Severin', 'CRS', 'CS'),
(185, 175, 'Cluj', 'CLJ', 'CJ'),
(186, 175, 'Constanta', 'CST', 'CT'),
(187, 175, 'Covasna', 'COV', 'CV'),
(188, 175, 'Dambovita', 'DAM', 'DB'),
(189, 175, 'Dolj', 'DLJ', 'DJ'),
(190, 175, 'Galati', 'GAL', 'GL'),
(191, 175, 'Giurgiu', 'GIU', 'GR'),
(192, 175, 'Gorj', 'GOR', 'GJ'),
(193, 175, 'Hargita', 'HRG', 'HR'),
(194, 175, 'Hunedoara', 'HUN', 'HD'),
(195, 175, 'Ialomita', 'IAL', 'IL'),
(196, 175, 'Iasi', 'IAS', 'IS'),
(197, 175, 'Ilfov', 'ILF', 'IF'),
(198, 175, 'Maramures', 'MAR', 'MM'),
(199, 175, 'Mehedinti', 'MEH', 'MH'),
(200, 175, 'Mures', 'MUR', 'MS'),
(201, 175, 'Neamt', 'NEM', 'NT'),
(202, 175, 'Olt', 'OLT', 'OT'),
(203, 175, 'Prahova', 'PRA', 'PH'),
(204, 175, 'Salaj', 'SAL', 'SJ'),
(205, 175, 'Satu Mare', 'SAT', 'SM'),
(206, 175, 'Sibiu', 'SIB', 'SB'),
(207, 175, 'Suceava', 'SUC', 'SV'),
(208, 175, 'Teleorman', 'TEL', 'TR'),
(209, 175, 'Timis', 'TIM', 'TM'),
(210, 175, 'Tulcea', 'TUL', 'TL'),
(211, 175, 'Valcea', 'VAL', 'VL'),
(212, 175, 'Vaslui', 'VAS', 'VS'),
(213, 175, 'Vrancea', 'VRA', 'VN'),
(214, 105, 'Agrigento', 'AGR', 'AG'),
(215, 105, 'Alessandria', 'ALE', 'AL'),
(216, 105, 'Ancona', 'ANC', 'AN'),
(217, 105, 'Aosta', 'AOS', 'AO'),
(218, 105, 'Arezzo', 'ARE', 'AR'),
(219, 105, 'Ascoli Piceno', 'API', 'AP'),
(220, 105, 'Asti', 'AST', 'AT'),
(221, 105, 'Avellino', 'AVE', 'AV'),
(222, 105, 'Bari', 'BAR', 'BA'),
(223, 105, 'Belluno', 'BEL', 'BL'),
(224, 105, 'Benevento', 'BEN', 'BN'),
(225, 105, 'Bergamo', 'BEG', 'BG'),
(226, 105, 'Biella', 'BIE', 'BI'),
(227, 105, 'Bologna', 'BOL', 'BO'),
(228, 105, 'Bolzano', 'BOZ', 'BZ'),
(229, 105, 'Brescia', 'BRE', 'BS'),
(230, 105, 'Brindisi', 'BRI', 'BR'),
(231, 105, 'Cagliari', 'CAG', 'CA'),
(232, 105, 'Caltanissetta', 'CAL', 'CL'),
(233, 105, 'Campobasso', 'CBO', 'CB'),
(234, 105, 'Carbonia-Iglesias', 'CAR', 'CI'),
(235, 105, 'Caserta', 'CAS', 'CE'),
(236, 105, 'Catania', 'CAT', 'CT'),
(237, 105, 'Catanzaro', 'CTZ', 'CZ'),
(238, 105, 'Chieti', 'CHI', 'CH'),
(239, 105, 'Como', 'COM', 'CO'),
(240, 105, 'Cosenza', 'COS', 'CS'),
(241, 105, 'Cremona', 'CRE', 'CR'),
(242, 105, 'Crotone', 'CRO', 'KR'),
(243, 105, 'Cuneo', 'CUN', 'CN'),
(244, 105, 'Enna', 'ENN', 'EN'),
(245, 105, 'Ferrara', 'FER', 'FE'),
(246, 105, 'Firenze', 'FIR', 'FI'),
(247, 105, 'Foggia', 'FOG', 'FG'),
(248, 105, 'Forli-Cesena', 'FOC', 'FC'),
(249, 105, 'Frosinone', 'FRO', 'FR'),
(250, 105, 'Genova', 'GEN', 'GE'),
(251, 105, 'Gorizia', 'GOR', 'GO'),
(252, 105, 'Grosseto', 'GRO', 'GR'),
(253, 105, 'Imperia', 'IMP', 'IM'),
(254, 105, 'Isernia', 'ISE', 'IS'),
(255, 105, 'L''Aquila', 'AQU', 'AQ'),
(256, 105, 'La Spezia', 'LAS', 'SP'),
(257, 105, 'Latina', 'LAT', 'LT'),
(258, 105, 'Lecce', 'LEC', 'LE'),
(259, 105, 'Lecco', 'LCC', 'LC'),
(260, 105, 'Livorno', 'LIV', 'LI'),
(261, 105, 'Lodi', 'LOD', 'LO'),
(262, 105, 'Lucca', 'LUC', 'LU'),
(263, 105, 'Macerata', 'MAC', 'MC'),
(264, 105, 'Mantova', 'MAN', 'MN'),
(265, 105, 'Massa-Carrara', 'MAS', 'MS'),
(266, 105, 'Matera', 'MAA', 'MT'),
(267, 105, 'Medio Campidano', 'MED', 'VS'),
(268, 105, 'Messina', 'MES', 'ME'),
(269, 105, 'Milano', 'MIL', 'MI'),
(270, 105, 'Modena', 'MOD', 'MO'),
(271, 105, 'Napoli', 'NAP', 'NA'),
(272, 105, 'Novara', 'NOV', 'NO'),
(273, 105, 'Nuoro', 'NUR', 'NU'),
(274, 105, 'Ogliastra', 'OGL', 'OG'),
(275, 105, 'Olbia-Tempio', 'OLB', 'OT'),
(276, 105, 'Oristano', 'ORI', 'OR'),
(277, 105, 'Padova', 'PDA', 'PD'),
(278, 105, 'Palermo', 'PAL', 'PA'),
(279, 105, 'Parma', 'PAA', 'PR'),
(280, 105, 'Pavia', 'PAV', 'PV'),
(281, 105, 'Perugia', 'PER', 'PG'),
(282, 105, 'Pesaro e Urbino', 'PES', 'PU'),
(283, 105, 'Pescara', 'PSC', 'PE'),
(284, 105, 'Piacenza', 'PIA', 'PC'),
(285, 105, 'Pisa', 'PIS', 'PI'),
(286, 105, 'Pistoia', 'PIT', 'PT'),
(287, 105, 'Pordenone', 'POR', 'PN'),
(288, 105, 'Potenza', 'PTZ', 'PZ'),
(289, 105, 'Prato', 'PRA', 'PO'),
(290, 105, 'Ragusa', 'RAG', 'RG'),
(291, 105, 'Ravenna', 'RAV', 'RA'),
(292, 105, 'Reggio Calabria', 'REG', 'RC'),
(293, 105, 'Reggio Emilia', 'REE', 'RE'),
(294, 105, 'Rieti', 'RIE', 'RI'),
(295, 105, 'Rimini', 'RIM', 'RN'),
(296, 105, 'Roma', 'ROM', 'RM'),
(297, 105, 'Rovigo', 'ROV', 'RO'),
(298, 105, 'Salerno', 'SAL', 'SA'),
(299, 105, 'Sassari', 'SAS', 'SS'),
(300, 105, 'Savona', 'SAV', 'SV'),
(301, 105, 'Siena', 'SIE', 'SI'),
(302, 105, 'Siracusa', 'SIR', 'SR'),
(303, 105, 'Sondrio', 'SOO', 'SO'),
(304, 105, 'Taranto', 'TAR', 'TA'),
(305, 105, 'Teramo', 'TER', 'TE'),
(306, 105, 'Terni', 'TRN', 'TR'),
(307, 105, 'Torino', 'TOR', 'TO'),
(308, 105, 'Trapani', 'TRA', 'TP'),
(309, 105, 'Trento', 'TRE', 'TN'),
(310, 105, 'Treviso', 'TRV', 'TV'),
(311, 105, 'Trieste', 'TRI', 'TS'),
(312, 105, 'Udine', 'UDI', 'UD'),
(313, 105, 'Varese', 'VAR', 'VA'),
(314, 105, 'Venezia', 'VEN', 'VE'),
(315, 105, 'Verbano Cusio Ossola', 'VCO', 'VB'),
(316, 105, 'Vercelli', 'VER', 'VC'),
(317, 105, 'Verona', 'VRN', 'VR'),
(318, 105, 'Vibo Valenzia', 'VIV', 'VV'),
(319, 105, 'Vicenza', 'VII', 'VI'),
(320, 105, 'Viterbo', 'VIT', 'VT'),
(321, 195, 'A Coru', 'ACO', '15'),
(322, 195, 'Alava', 'ALA', '01'),
(323, 195, 'Albacete', 'ALB', '02'),
(324, 195, 'Alicante', 'ALI', '03'),
(325, 195, 'Almeria', 'ALM', '04'),
(326, 195, 'Asturias', 'AST', '33'),
(327, 195, 'Avila', 'AVI', '05'),
(328, 195, 'Badajoz', 'BAD', '06'),
(329, 195, 'Baleares', 'BAL', '07'),
(330, 195, 'Barcelona', 'BAR', '08'),
(331, 195, 'Burgos', 'BUR', '09'),
(332, 195, 'Caceres', 'CAC', '10'),
(333, 195, 'Cadiz', 'CAD', '11'),
(334, 195, 'Cantabria', 'CAN', '39'),
(335, 195, 'Castellon', 'CAS', '12'),
(336, 195, 'Ceuta', 'CEU', '51'),
(337, 195, 'Ciudad Real', 'CIU', '13'),
(338, 195, 'Cordoba', 'COR', '14'),
(339, 195, 'Cuenca', 'CUE', '16'),
(340, 195, 'Girona', 'GIR', '17'),
(341, 195, 'Granada', 'GRA', '18'),
(342, 195, 'Guadalajara', 'GUA', '19'),
(343, 195, 'Guipuzcoa', 'GUI', '20'),
(344, 195, 'Huelva', 'HUL', '21'),
(345, 195, 'Huesca', 'HUS', '22'),
(346, 195, 'Jaen', 'JAE', '23'),
(347, 195, 'La Rioja', 'LRI', '26'),
(348, 195, 'Las Palmas', 'LPA', '35'),
(349, 195, 'Leon', 'LEO', '24'),
(350, 195, 'Lleida', 'LLE', '25'),
(351, 195, 'Lugo', 'LUG', '27'),
(352, 195, 'Madrid', 'MAD', '28'),
(353, 195, 'Malaga', 'MAL', '29'),
(354, 195, 'Melilla', 'MEL', '52'),
(355, 195, 'Murcia', 'MUR', '30'),
(356, 195, 'Navarra', 'NAV', '31'),
(357, 195, 'Ourense', 'OUR', '32'),
(358, 195, 'Palencia', 'PAL', '34'),
(359, 195, 'Pontevedra', 'PON', '36'),
(360, 195, 'Salamanca', 'SAL', '37'),
(361, 195, 'Santa Cruz de Tenerife', 'SCT', '38'),
(362, 195, 'Segovia', 'SEG', '40'),
(363, 195, 'Sevilla', 'SEV', '41'),
(364, 195, 'Soria', 'SOR', '42'),
(365, 195, 'Tarragona', 'TAR', '43'),
(366, 195, 'Teruel', 'TER', '44'),
(367, 195, 'Toledo', 'TOL', '45'),
(368, 195, 'Valencia', 'VAL', '46'),
(369, 195, 'Valladolid', 'VLL', '47'),
(370, 195, 'Vizcaya', 'VIZ', '48'),
(371, 195, 'Zamora', 'ZAM', '49'),
(372, 195, 'Zaragoza', 'ZAR', '50'),
(373, 10, 'Buenos Aires', 'BAS', 'BA'),
(374, 10, 'Ciudad Autonoma De Buenos Aires', 'CBA', 'CB'),
(375, 10, 'Catamarca', 'CAT', 'CA'),
(376, 10, 'Chaco', 'CHO', 'CH'),
(377, 10, 'Chubut', 'CTT', 'CT'),
(378, 10, 'Cordoba', 'COD', 'CO'),
(379, 10, 'Corrientes', 'CRI', 'CR'),
(380, 10, 'Entre Rios', 'ERS', 'ER'),
(381, 10, 'Formosa', 'FRM', 'FR'),
(382, 10, 'Jujuy', 'JUJ', 'JU'),
(383, 10, 'La Pampa', 'LPM', 'LP'),
(384, 10, 'La Rioja', 'LRI', 'LR'),
(385, 10, 'Mendoza', 'MED', 'ME'),
(386, 10, 'Misiones', 'MIS', 'MI'),
(387, 10, 'Neuquen', 'NQU', 'NQ'),
(388, 10, 'Rio Negro', 'RNG', 'RN'),
(389, 10, 'Salta', 'SAL', 'SA'),
(390, 10, 'San Juan', 'SJN', 'SJ'),
(391, 10, 'San Luis', 'SLU', 'SL'),
(392, 10, 'Santa Cruz', 'SCZ', 'SC'),
(393, 10, 'Santa Fe', 'SFE', 'SF'),
(394, 10, 'Santiago Del Estero', 'SEN', 'SE'),
(395, 10, 'Tierra Del Fuego', 'TFE', 'TF'),
(396, 10, 'Tucuman', 'TUC', 'TU'),
(397, 11, 'Aragatsotn', 'ARG', 'AG'),
(398, 11, 'Ararat', 'ARR', 'AR'),
(399, 11, 'Armavir', 'ARM', 'AV'),
(400, 11, 'Gegharkunik', 'GEG', 'GR'),
(401, 11, 'Kotayk', 'KOT', 'KT'),
(402, 11, 'Lori', 'LOR', 'LO'),
(403, 11, 'Shirak', 'SHI', 'SH'),
(404, 11, 'Syunik', 'SYU', 'SU'),
(405, 11, 'Tavush', 'TAV', 'TV'),
(406, 11, 'Vayots-Dzor', 'VAD', 'VD'),
(407, 11, 'Yerevan', 'YER', 'ER'),
(408, 99, 'Andaman & Nicobar Islands', 'ANI', 'AI'),
(409, 99, 'Andhra Pradesh', 'AND', 'AN'),
(410, 99, 'Arunachal Pradesh', 'ARU', 'AR'),
(411, 99, 'Assam', 'ASS', 'AS'),
(412, 99, 'Bihar', 'BIH', 'BI'),
(413, 99, 'Chandigarh', 'CHA', 'CA'),
(414, 99, 'Chhatisgarh', 'CHH', 'CH'),
(415, 99, 'Dadra & Nagar Haveli', 'DAD', 'DD'),
(416, 99, 'Daman & Diu', 'DAM', 'DA'),
(417, 99, 'Delhi', 'DEL', 'DE'),
(418, 99, 'Goa', 'GOA', 'GO'),
(419, 99, 'Gujarat', 'GUJ', 'GU'),
(420, 99, 'Haryana', 'HAR', 'HA'),
(421, 99, 'Himachal Pradesh', 'HIM', 'HI'),
(422, 99, 'Jammu & Kashmir', 'JAM', 'JA'),
(423, 99, 'Jharkhand', 'JHA', 'JH'),
(424, 99, 'Karnataka', 'KAR', 'KA'),
(425, 99, 'Kerala', 'KER', 'KE'),
(426, 99, 'Lakshadweep', 'LAK', 'LA'),
(427, 99, 'Madhya Pradesh', 'MAD', 'MD'),
(428, 99, 'Maharashtra', 'MAH', 'MH'),
(429, 99, 'Manipur', 'MAN', 'MN'),
(430, 99, 'Meghalaya', 'MEG', 'ME'),
(431, 99, 'Mizoram', 'MIZ', 'MI'),
(432, 99, 'Nagaland', 'NAG', 'NA'),
(433, 99, 'Orissa', 'ORI', 'OR'),
(434, 99, 'Pondicherry', 'PON', 'PO'),
(435, 99, 'Punjab', 'PUN', 'PU'),
(436, 99, 'Rajasthan', 'RAJ', 'RA'),
(437, 99, 'Sikkim', 'SIK', 'SI'),
(438, 99, 'Tamil Nadu', 'TAM', 'TA'),
(439, 99, 'Tripura', 'TRI', 'TR'),
(440, 99, 'Uttaranchal', 'UAR', 'UA'),
(441, 99, 'Uttar Pradesh', 'UTT', 'UT')");

$objDatabase->dbQuery("ALTER TABLE `".$configArray->prefix."report_submission`  ADD CONSTRAINT `Foreign Key` FOREIGN KEY (`report_id`) REFERENCES `".$configArray->prefix."reports` (`report_id`) ON DELETE CASCADE ON UPDATE CASCADE");
?>
