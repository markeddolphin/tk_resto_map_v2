<?php
class InstallHelper
{
		
	public static function addIndex($table='',$index_name='')
	{
		$DbExt=new DbExt;
		$prefix=Yii::app()->db->tablePrefix;		
		
		$table=$prefix.$table;
		
		$stmt="
		SHOW INDEX FROM $table
		";		
		$found=false;
		if ( $res=$DbExt->rst($stmt)){
			foreach ($res as $val) {				
				if ( $val['Key_name']==$index_name){
					$found=true;
					break;
				}
			}
		} 
		
		if ($found==false){
			echo "create index<br>";
			$stmt_index="ALTER TABLE $table ADD INDEX ( $index_name ) ";
			dump($stmt_index);
			$DbExt->qry($stmt_index);
			echo "Creating Index $index_name on $table <br/>";		
            echo "(Done)<br/>";		
		} else echo 'index exist<br>';
	}
	
	public static function alterTable($table='',$new_field='')
	{
		$DbExt=new DbExt;
		$prefix=Yii::app()->db->tablePrefix;		
		$existing_field='';
		if ( $res = self::checkTableStructure($table)){
			foreach ($res as $val) {								
				$existing_field[$val['Field']]=$val['Field'];
			}			
			foreach ($new_field as $key_new=>$val_new) {				
				if (!in_array($key_new,$existing_field)){
					echo "Creating field $key_new <br/>";
					$stmt_alter="ALTER TABLE ".$prefix."$table ADD $key_new ".$new_field[$key_new];
					dump($stmt_alter);
				    if ($DbExt->qry($stmt_alter)){
					   echo "(Done)<br/>";
				   } else echo "(Failed)<br/>";
				} else echo "Field $key_new already exist<br/>";
			}
		}
	}
	
    public static function checkTableStructure($table_name='')
    {
    	$db_ext=new DbExt;
    	$stmt=" SHOW COLUMNS FROM {{{$table_name}}}";	    	
    	if ($res=$db_ext->rst($stmt)){    		
    		return $res;
    	}
    	return false;    
    }      
    
    public static function isTableExist($table_name='')
    {
    	$db_ext=new DbExt;
    	$stmt="SHOW TABLE STATUS LIKE '{{{$table_name}}}'";	
    	if ($res=$db_ext->rst($stmt)){
    		return true;
    	}
    	return false;    
    }            
    
    public static function dump($data='')
    {
    	echo '<pre>';
    	print_r($data);
    	echo '</pre>';
    }
    
	public static function createFile($filename_path,$content='')
	{
		$myfile = fopen($filename_path, "w") or die("Unable to open file!".$filename_path);    
	    fwrite($myfile, $content);        
	    fclose($myfile);
	    return false;
	}    
	
	public static function UserAccessString()
	{
		return '["autologin","dashboard","merchant","sponsoredMerchantList","packages","Cuisine","dishes","OrderStatus","incomingorders","cancel_order","settings","themesettings","managelocation","commisionsettings","voucher","invoice","merchantcommission","withdrawal","incomingwithdrawal","withdrawalsettings","emailsettings","emailtpl","notisettings","emailogs","cronjobs","customPage","Ratings","ContactSettings","SocialSettings","ManageCurrency","ManageLanguage","Seo","addons","addonexport","mobileapp","pointsprogram","merchantapp","analytics","customerlist","subscriberlist","reviews","bankdeposit","paymentgatewaysettings","paymentgateway","paypalSettings","cardpaymentsettings","stripeSettings","mercadopagoSettings","sisowsettings","payumonenysettings","obdsettings","payserasettings","payondelivery","barclay","epaybg","authorize","braintree","razor","mollie","ipay88","moneris","sms","smsSettings","smsPackage","smstransaction","smslogs","fax","faxtransaction","faxpackage","faxlogs","faxsettings","reports","rptMerchantReg","rptMerchantPayment","rptMerchanteSales","rptmerchantsalesummary","rptbookingsummary","userList","voguepay","printermodule","paypal_v2","mercadopago","singlemerchant","mobileappv2","tags"]';
	}
	
	public static function CurrencyList()
	{
		return array(
		    'AUD'=>"AUD-&#36;",
		    'CAD'=>"CAD-&#36;",
		    'CNY'=>"CNY-&yen;",
		    'EUR'=>"EUR-&euro;",
		    'HKD'=>"HKD-&#36;",
		    'JPY'=>"JPY-&yen;",
		    'MXN'=>"MXN-&#36;",
		    'USD'=>"USD-&#36;",
		    'NZD'=>"NZD-&#36;"
	    );
	}
	
	public static function CuisineList()
	{
		$cuisine[]=array(
		  'cuisine_name'=>'American',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>"american"
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Deli',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>"deli"
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Indian',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>"indian"
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Mediterranean',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>strtolower("Mediterranean")
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Sandwiches',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>strtolower("Sandwiches")
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Barbeque',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>strtolower("Barbeque")
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Diner',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>strtolower("Diner")
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Italian',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>strtolower("Italian")
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Mexican',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>strtolower("Mexican")
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Sushi',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>strtolower("Sushi")
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Burgers',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>strtolower("Burgers")
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Greek',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>strtolower("Greek")
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Japanese',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>strtolower("Japanese")
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Middle Eastern',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>strtolower("Middle-Eastern")
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Thai',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>strtolower("Thai")
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Chinese',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>strtolower("Chinese")
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Healthy',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>strtolower("Healthy")
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Korean',		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>strtolower("Korean")
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Pizza',
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>strtolower("Pizza")
		);
		$cuisine[]=array(
		  'cuisine_name'=>'Vegetarian',
		  'ip_address'=>$_SERVER['REMOTE_ADDR'],
		  'slug'=>strtolower("Vegetarian")
		);
		
		return $cuisine;
	}
	
	public static function RatingList()
	{
		$rating[]=array(
		  'rating_start'=>"1.0",
		  'rating_end'=>"1.9",
		  'meaning'=>"poor",
		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		$rating[]=array(
		  'rating_start'=>"2.0",
		  'rating_end'=>"2.9",
		  'meaning'=>"good",
		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		$rating[]=array(
		  'rating_start'=>"3.0",
		  'rating_end'=>"4.0",
		  'meaning'=>"very good",
		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		$rating[]=array(
		  'rating_start'=>"4.1",
		  'rating_end'=>"5.0",
		  'meaning'=>"excellent",
		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);

		return $rating;
	}
	
	public static function OrderStatusList()
	{
		$order_stats[]=array(
		  'description'=>"pending",
		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		$order_stats[]=array(
		  'description'=>"cancelled",
		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		$order_stats[]=array(
		  'description'=>"delivered",
		  
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		$order_stats[]=array(
		  'description'=>"paid",		 
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
			
		$order_stats[]=array(
		  'description'=>"accepted",		 
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		$order_stats[]=array(
		  'description'=>"decline",		 
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		
		/*DRIVER STATUS*/
		$order_stats[]=array(
		  'description'=>"failed",		 
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		$order_stats[]=array(
		  'description'=>"declined",		 
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		$order_stats[]=array(
		  'description'=>"acknowledged",		 
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		$order_stats[]=array(
		  'description'=>"started",		 
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		$order_stats[]=array(
		  'description'=>"inprogress",		 
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		$order_stats[]=array(
		  'description'=>"successful",		 
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		
		$order_stats[]=array(
		  'description'=>"unassigned",		 
		  'ip_address'=>$_SERVER['REMOTE_ADDR']
		);
		
		return $order_stats;
	}
	
	public static function CountryList()
	{
		return "VALUES
(1, 'AF', 'Afghanistan', 93),
(2, 'AL', 'Albania', 355),
(3, 'DZ', 'Algeria', 213),
(4, 'AS', 'American Samoa', 1684),
(5, 'AD', 'Andorra', 376),
(6, 'AO', 'Angola', 244),
(7, 'AI', 'Anguilla', 1264),
(8, 'AQ', 'Antarctica', 0),
(9, 'AG', 'Antigua And Barbuda', 1268),
(10, 'AR', 'Argentina', 54),
(11, 'AM', 'Armenia', 374),
(12, 'AW', 'Aruba', 297),
(13, 'AU', 'Australia', 61),
(14, 'AT', 'Austria', 43),
(15, 'AZ', 'Azerbaijan', 994),
(16, 'BS', 'Bahamas The', 1242),
(17, 'BH', 'Bahrain', 973),
(18, 'BD', 'Bangladesh', 880),
(19, 'BB', 'Barbados', 1246),
(20, 'BY', 'Belarus', 375),
(21, 'BE', 'Belgium', 32),
(22, 'BZ', 'Belize', 501),
(23, 'BJ', 'Benin', 229),
(24, 'BM', 'Bermuda', 1441),
(25, 'BT', 'Bhutan', 975),
(26, 'BO', 'Bolivia', 591),
(27, 'BA', 'Bosnia and Herzegovina', 387),
(28, 'BW', 'Botswana', 267),
(29, 'BV', 'Bouvet Island', 0),
(30, 'BR', 'Brazil', 55),
(31, 'IO', 'British Indian Ocean Territory', 246),
(32, 'BN', 'Brunei', 673),
(33, 'BG', 'Bulgaria', 359),
(34, 'BF', 'Burkina Faso', 226),
(35, 'BI', 'Burundi', 257),
(36, 'KH', 'Cambodia', 855),
(37, 'CM', 'Cameroon', 237),
(38, 'CA', 'Canada', 1),
(39, 'CV', 'Cape Verde', 238),
(40, 'KY', 'Cayman Islands', 1345),
(41, 'CF', 'Central African Republic', 236),
(42, 'TD', 'Chad', 235),
(43, 'CL', 'Chile', 56),
(44, 'CN', 'China', 86),
(45, 'CX', 'Christmas Island', 61),
(46, 'CC', 'Cocos (Keeling) Islands', 672),
(47, 'CO', 'Colombia', 57),
(48, 'KM', 'Comoros', 269),
(49, 'CG', 'Congo', 242),
(50, 'CD', 'Congo The Democratic Republic Of The', 242),
(51, 'CK', 'Cook Islands', 682),
(52, 'CR', 'Costa Rica', 506),
(53, 'CI', 'Cote D''Ivoire (Ivory Coast)', 225),
(54, 'HR', 'Croatia (Hrvatska)', 385),
(55, 'CU', 'Cuba', 53),
(56, 'CY', 'Cyprus', 357),
(57, 'CZ', 'Czech Republic', 420),
(58, 'DK', 'Denmark', 45),
(59, 'DJ', 'Djibouti', 253),
(60, 'DM', 'Dominica', 1767),
(61, 'DO', 'Dominican Republic', 1809),
(62, 'TP', 'East Timor', 670),
(63, 'EC', 'Ecuador', 593),
(64, 'EG', 'Egypt', 20),
(65, 'SV', 'El Salvador', 503),
(66, 'GQ', 'Equatorial Guinea', 240),
(67, 'ER', 'Eritrea', 291),
(68, 'EE', 'Estonia', 372),
(69, 'ET', 'Ethiopia', 251),
(70, 'XA', 'External Territories of Australia', 61),
(71, 'FK', 'Falkland Islands', 500),
(72, 'FO', 'Faroe Islands', 298),
(73, 'FJ', 'Fiji Islands', 679),
(74, 'FI', 'Finland', 358),
(75, 'FR', 'France', 33),
(76, 'GF', 'French Guiana', 594),
(77, 'PF', 'French Polynesia', 689),
(78, 'TF', 'French Southern Territories', 0),
(79, 'GA', 'Gabon', 241),
(80, 'GM', 'Gambia The', 220),
(81, 'GE', 'Georgia', 995),
(82, 'DE', 'Germany', 49),
(83, 'GH', 'Ghana', 233),
(84, 'GI', 'Gibraltar', 350),
(85, 'GR', 'Greece', 30),
(86, 'GL', 'Greenland', 299),
(87, 'GD', 'Grenada', 1473),
(88, 'GP', 'Guadeloupe', 590),
(89, 'GU', 'Guam', 1671),
(90, 'GT', 'Guatemala', 502),
(91, 'XU', 'Guernsey and Alderney', 44),
(92, 'GN', 'Guinea', 224),
(93, 'GW', 'Guinea-Bissau', 245),
(94, 'GY', 'Guyana', 592),
(95, 'HT', 'Haiti', 509),
(96, 'HM', 'Heard and McDonald Islands', 0),
(97, 'HN', 'Honduras', 504),
(98, 'HK', 'Hong Kong S.A.R.', 852),
(99, 'HU', 'Hungary', 36),
(100, 'IS', 'Iceland', 354),
(101, 'IN', 'India', 91),
(102, 'ID', 'Indonesia', 62),
(103, 'IR', 'Iran', 98),
(104, 'IQ', 'Iraq', 964),
(105, 'IE', 'Ireland', 353),
(106, 'IL', 'Israel', 972),
(107, 'IT', 'Italy', 39),
(108, 'JM', 'Jamaica', 1876),
(109, 'JP', 'Japan', 81),
(110, 'XJ', 'Jersey', 44),
(111, 'JO', 'Jordan', 962),
(112, 'KZ', 'Kazakhstan', 7),
(113, 'KE', 'Kenya', 254),
(114, 'KI', 'Kiribati', 686),
(115, 'KP', 'Korea North', 850),
(116, 'KR', 'Korea South', 82),
(117, 'KW', 'Kuwait', 965),
(118, 'KG', 'Kyrgyzstan', 996),
(119, 'LA', 'Laos', 856),
(120, 'LV', 'Latvia', 371),
(121, 'LB', 'Lebanon', 961),
(122, 'LS', 'Lesotho', 266),
(123, 'LR', 'Liberia', 231),
(124, 'LY', 'Libya', 218),
(125, 'LI', 'Liechtenstein', 423),
(126, 'LT', 'Lithuania', 370),
(127, 'LU', 'Luxembourg', 352),
(128, 'MO', 'Macau S.A.R.', 853),
(129, 'MK', 'Macedonia', 389),
(130, 'MG', 'Madagascar', 261),
(131, 'MW', 'Malawi', 265),
(132, 'MY', 'Malaysia', 60),
(133, 'MV', 'Maldives', 960),
(134, 'ML', 'Mali', 223),
(135, 'MT', 'Malta', 356),
(136, 'XM', 'Man (Isle of)', 44),
(137, 'MH', 'Marshall Islands', 692),
(138, 'MQ', 'Martinique', 596),
(139, 'MR', 'Mauritania', 222),
(140, 'MU', 'Mauritius', 230),
(141, 'YT', 'Mayotte', 269),
(142, 'MX', 'Mexico', 52),
(143, 'FM', 'Micronesia', 691),
(144, 'MD', 'Moldova', 373),
(145, 'MC', 'Monaco', 377),
(146, 'MN', 'Mongolia', 976),
(147, 'MS', 'Montserrat', 1664),
(148, 'MA', 'Morocco', 212),
(149, 'MZ', 'Mozambique', 258),
(150, 'MM', 'Myanmar', 95),
(151, 'NA', 'Namibia', 264),
(152, 'NR', 'Nauru', 674),
(153, 'NP', 'Nepal', 977),
(154, 'AN', 'Netherlands Antilles', 599),
(155, 'NL', 'Netherlands The', 31),
(156, 'NC', 'New Caledonia', 687),
(157, 'NZ', 'New Zealand', 64),
(158, 'NI', 'Nicaragua', 505),
(159, 'NE', 'Niger', 227),
(160, 'NG', 'Nigeria', 234),
(161, 'NU', 'Niue', 683),
(162, 'NF', 'Norfolk Island', 672),
(163, 'MP', 'Northern Mariana Islands', 1670),
(164, 'NO', 'Norway', 47),
(165, 'OM', 'Oman', 968),
(166, 'PK', 'Pakistan', 92),
(167, 'PW', 'Palau', 680),
(168, 'PS', 'Palestinian Territory Occupied', 970),
(169, 'PA', 'Panama', 507),
(170, 'PG', 'Papua new Guinea', 675),
(171, 'PY', 'Paraguay', 595),
(172, 'PE', 'Peru', 51),
(173, 'PH', 'Philippines', 63),
(174, 'PN', 'Pitcairn Island', 0),
(175, 'PL', 'Poland', 48),
(176, 'PT', 'Portugal', 351),
(177, 'PR', 'Puerto Rico', 1787),
(178, 'QA', 'Qatar', 974),
(179, 'RE', 'Reunion', 262),
(180, 'RO', 'Romania', 40),
(181, 'RU', 'Russia', 70),
(182, 'RW', 'Rwanda', 250),
(183, 'SH', 'Saint Helena', 290),
(184, 'KN', 'Saint Kitts And Nevis', 1869),
(185, 'LC', 'Saint Lucia', 1758),
(186, 'PM', 'Saint Pierre and Miquelon', 508),
(187, 'VC', 'Saint Vincent And The Grenadines', 1784),
(188, 'WS', 'Samoa', 684),
(189, 'SM', 'San Marino', 378),
(190, 'ST', 'Sao Tome and Principe', 239),
(191, 'SA', 'Saudi Arabia', 966),
(192, 'SN', 'Senegal', 221),
(193, 'RS', 'Serbia', 381),
(194, 'SC', 'Seychelles', 248),
(195, 'SL', 'Sierra Leone', 232),
(196, 'SG', 'Singapore', 65),
(197, 'SK', 'Slovakia', 421),
(198, 'SI', 'Slovenia', 386),
(199, 'XG', 'Smaller Territories of the UK', 44),
(200, 'SB', 'Solomon Islands', 677),
(201, 'SO', 'Somalia', 252),
(202, 'ZA', 'South Africa', 27),
(203, 'GS', 'South Georgia', 0),
(204, 'SS', 'South Sudan', 211),
(205, 'ES', 'Spain', 34),
(206, 'LK', 'Sri Lanka', 94),
(207, 'SD', 'Sudan', 249),
(208, 'SR', 'Suriname', 597),
(209, 'SJ', 'Svalbard And Jan Mayen Islands', 47),
(210, 'SZ', 'Swaziland', 268),
(211, 'SE', 'Sweden', 46),
(212, 'CH', 'Switzerland', 41),
(213, 'SY', 'Syria', 963),
(214, 'TW', 'Taiwan', 886),
(215, 'TJ', 'Tajikistan', 992),
(216, 'TZ', 'Tanzania', 255),
(217, 'TH', 'Thailand', 66),
(218, 'TG', 'Togo', 228),
(219, 'TK', 'Tokelau', 690),
(220, 'TO', 'Tonga', 676),
(221, 'TT', 'Trinidad And Tobago', 1868),
(222, 'TN', 'Tunisia', 216),
(223, 'TR', 'Turkey', 90),
(224, 'TM', 'Turkmenistan', 7370),
(225, 'TC', 'Turks And Caicos Islands', 1649),
(226, 'TV', 'Tuvalu', 688),
(227, 'UG', 'Uganda', 256),
(228, 'UA', 'Ukraine', 380),
(229, 'AE', 'United Arab Emirates', 971),
(230, 'GB', 'United Kingdom', 44),
(231, 'US', 'United States', 1),
(232, 'UM', 'United States Minor Outlying Islands', 1),
(233, 'UY', 'Uruguay', 598),
(234, 'UZ', 'Uzbekistan', 998),
(235, 'VU', 'Vanuatu', 678),
(236, 'VA', 'Vatican City State (Holy See)', 39),
(237, 'VE', 'Venezuela', 58),
(238, 'VN', 'Vietnam', 84),
(239, 'VG', 'Virgin Islands (British)', 1284),
(240, 'VI', 'Virgin Islands (US)', 1340),
(241, 'WF', 'Wallis And Futuna Islands', 681),
(242, 'EH', 'Western Sahara', 212),
(243, 'YE', 'Yemen', 967),
(244, 'YU', 'Yugoslavia', 38),
(245, 'ZM', 'Zambia', 260),
(246, 'ZW', 'Zimbabwe', 263);";
	}
	
	public static function OptionNameExist($option_name='')
	{
		$DbExt=new DbExt;
		$stmt="
		SELECT option_name 
		FROM {{option}}
		WHERE
		option_name=".FunctionsV3::q($option_name)."
		LIMIT 0,1
		";
		if($res=$DbExt->rst($stmt)){			
		   unset($DbExt);
		   return $res;
		}
		unset($DbExt);
		return false;
	}
	
	public static function optionDefaultValue()
	{
		$option[]=array(
		  'option_name'=>'contact_us_tpl_subject_en',
		  'option_value'=>'contact us form'
		);
		$option[]=array(
		  'option_name'=>'contact_us_tpl_content_en',
		  'option_value'=>'hi admin 

new contact us
name : [name] 
email : [email]  
country : [country] 
phone : [phone] 
message : [message] 

Regards 
- [sitename]'
		);
			
        $option[]=array(
		  'option_name'=>'contact_us_email',
		  'option_value'=>'1'
		);		
		
		$option[]=array(
		  'option_name'=>'customer_welcome_email_tpl_subject_en',
		  'option_value'=>'Welcome [firstname]'
		);		
		
		$option[]=array(
		  'option_name'=>'customer_welcome_email_tpl_content_en',
		  'option_value'=>'hi [firstname]

thank you for signup 

Regards
- [sitename]'
		);		
		
		$option[]=array(
		  'option_name'=>'customer_welcome_email_email',
		  'option_value'=>'1'
		);		
		
		$option[]=array(
		  'option_name'=>'customer_forgot_password_tpl_subject_en',
		  'option_value'=>'Forgot password'
		);		
		
		$option[]=array(
		  'option_name'=>'customer_forgot_password_tpl_content_en',
		  'option_value'=>'Hi [firstname]

You have requested for your password
click in the link to change your password
[change_pass_link]

Regards
 - [sitename]'
		);		
		
		$option[]=array(
		  'option_name'=>'customer_forgot_password_email',
		  'option_value'=>'1'
		);		
		
		$option[]=array(
		  'option_name'=>'customer_verification_code_email_tpl_subject_en',
		  'option_value'=>'Signup Verification Code'
		);		
		
		$option[]=array(
		  'option_name'=>'customer_verification_code_email_tpl_content_en',
		  'option_value'=>'hi [firstname]

Your signup verification is [code]

Regards
- [sitename]'
		);		
		
		$option[]=array(
		  'option_name'=>'customer_verification_code_email_email',
		  'option_value'=>'1'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_verification_code_tpl_subject_en',
		  'option_value'=>'Your Activation Code'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_verification_code_tpl_content_en',
		  'option_value'=>'hi [restaurant_name]

Your activation code is [code]

Regards
  - [sitename]'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_verification_code_email',
		  'option_value'=>'1'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_forgot_password_tpl_subject_en',
		  'option_value'=>'Merchant Forgot Password'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_forgot_password_tpl_content_en',
		  'option_value'=>'hi [restaurant_name]

Your Your verification code is  [code]

Regards
 - [sitename]'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_forgot_password_email',
		  'option_value'=>'1'
		);		
		
		$option[]=array(
		  'option_name'=>'admin_forgot_password_tpl_subject_en',
		  'option_value'=>'Admin Change Password'
		);		
		
		$option[]=array(
		  'option_name'=>'admin_forgot_password_tpl_content_en',
		  'option_value'=>'hi admin

Your password has been reset to : [newpassword]

Regards
- [sitename]'
		);		
		
		$option[]=array(
		  'option_name'=>'admin_forgot_password_email',
		  'option_value'=>'1'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_new_signup_tpl_subject_en',
		  'option_value'=>'New Restaurant Signup - [restaurant_name]'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_new_signup_tpl_content_en',
		  'option_value'=>'hi admin

New restaurant signup 
name : [restaurant_name]
package : [package_name]
type : [merchant_type]

regard'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_new_signup_sms_content_en',
		  'option_value'=>'New Restaurant Signup name : [restaurant_name]
package : [package_name]
type : [merchant_type] '
		);		
			
		$option[]=array(
		  'option_name'=>'receipt_template_tpl_subject_en',
		  'option_value'=>'Your Receipt for Order ID : [order_id]'
		);		
		
		$option[]=array(
		  'option_name'=>'receipt_template_tpl_content_en',
		  'option_value'=>'Dear [customer_name],


Thank you for shopping at [sitename]. We hope you enjoy your new purchase! Your order number is [order_id]. We have included your order receipt and delivery details below:

[receipt]

Kind Regards'
		);		
		
		$option[]=array(
		  'option_name'=>'receipt_template_sms_content_en',
		  'option_value'=>'Hi [customer_name] 
We have receive your order,
Details:
Order ID : [order_id]
Restaurant : [restaurant_name]
Total Amount : [total_amount]
Order Details : 
[order_details]

Regards
[sitename]
'
		);				
		
		$option[]=array(
		  'option_name'=>'receipt_template_email',
		  'option_value'=>'1'
		);		
		
		$option[]=array(
		  'option_name'=>'receipt_template_sms',
		  'option_value'=>'1'
		);		
		
		$option[]=array(
		  'option_name'=>'receipt_send_to_merchant_tpl_subject_en',
		  'option_value'=>'New Order : [order_id] From [customer_name]'
		);				

		$option[]=array(
		  'option_name'=>'receipt_send_to_merchant_tpl_content_en',
		  'option_value'=>'hi [restaurant_name],

There is a new order with the reference number [order_id] from customer [customer_name]

[receipt]

Accept Order
[accept_link]

Decline Order
[decline_link]

Kind Regards'
		);		
		
		$option[]=array(
		  'option_name'=>'receipt_send_to_merchant_sms_content_en',
		  'option_value'=>'New Order : [order_id] From [customer_name]
total amout : [total_amount]
details : 
[order_details]
'
		);		
		
		$option[]=array(
		  'option_name'=>'receipt_send_to_merchant_email',
		  'option_value'=>'1'
		);				

		$option[]=array(
		  'option_name'=>'receipt_send_to_admin_email',
		  'option_value'=>'1'
		);		
		
		$option[]=array(
		  'option_name'=>'receipt_send_to_admin_tpl_subject_en',
		  'option_value'=>'New Order : [order_id] From [customer_name]'
		);		
		
		$option[]=array(
		  'option_name'=>'receipt_send_to_admin_tpl_content_en',
		  'option_value'=>'hi admin,

There is a new order to [restaurant_name]
with the reference number [order_id] from customer [customer_name]

[receipt]

Accept Order
[accept_link]

Decline Order
[decline_link]

Kind Regards'
		);							
		
		$option[]=array(
		  'option_name'=>'receipt_send_to_admin_sms_content_en',
		  'option_value'=>'New Order : [order_id] From [customer_name]
total amout : [total_amount]
details : [order_details]'
		);		
		
		$option[]=array(
		  'option_name'=>'offline_bank_deposit_tpl_subject_en',
		  'option_value'=>'Bank deposit instructions'
		);		
		
		$option[]=array(
		  'option_name'=>'offline_bank_deposit_tpl_content_en',
		  'option_value'=>'Hi [restaurant_name]

Deposit Instructions

Please deposit [amount] to :

Bank : Your bank name
Account Number : Your bank account number
Account Name : Your bank account name

When deposit is completed 
fill in your bank deposit information 
[verify_payment_link]


Kind Regards
  -[sitename]'
		);							

		$option[]=array(
		  'option_name'=>'offline_bank_deposit_signup_merchant_tpl_subject_en',
		  'option_value'=>'Bank deposit instructions'
		);		
		
		$option[]=array(
		  'option_name'=>'offline_bank_deposit_signup_merchant_tpl_content_en',
		  'option_value'=>'Hi [restaurant_name]

Deposit Instructions

Please deposit [amount] to :

Bank : Your bank name
Account Number : Your bank account number
Account Name : Your bank account name

When deposit is completed 
fill in your bank deposit information 
[verify_payment_link]


Kind Regards
  -[sitename]'
		);		
		
		$option[]=array(
		  'option_name'=>'offline_bank_deposit_signup_merchant_email',
		  'option_value'=>'1'
		);							
		
		$option[]=array(
		  'option_name'=>'offline_bank_deposit_purchase_email',
		  'option_value'=>'1'
		);		
		
		$option[]=array(
		  'option_name'=>'offline_bank_deposit_purchase_tpl_subject_en',
		  'option_value'=>'Bank deposit instructions for order id : [order_id]'
		);		
		
		$option[]=array(
		  'option_name'=>'offline_bank_deposit_purchase_tpl_content_en',
		  'option_value'=>'Hi [customer_name]

Deposit Instructions

Please deposit [amount] to :

Bank : Your bank name
Account Number : Your bank account number
Account Name : Your bank account name

When deposit is completed 
fill in your bank deposit information 
[verify_payment_link]


Kind Regards
  -[sitename]'
		);				
							
		$option[]=array(
		  'option_name'=>'merchant_near_expiration_email',
		  'option_value'=>'1'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_near_expiration_sms',
		  'option_value'=>'1'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_near_expiration_tpl_subject_en',
		  'option_value'=>'Your membership about to expired'
		);									
		
		$option[]=array(
		  'option_name'=>'merchant_near_expiration_tpl_content_en',
		  'option_value'=>'hi [restaurant_name]

 Your membership is about to expire in [expiration_date]

Regards
 - [sitename]'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_near_expiration_sms_content_en',
		  'option_value'=>'hi [restaurant_name]
 Your membership is about to expire in [expiration_date]
Regards
 - [sitename]'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_near_expiration_day',
		  'option_value'=>'5'
		);									

		$option[]=array(
		  'option_name'=>'merchant_change_status_email',
		  'option_value'=>'1'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_change_status_tpl_subject_en',
		  'option_value'=>'Account Status Updated'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_change_status_tpl_content_en',
		  'option_value'=>'hi [restaurant_name]

Your merchant records status has change to [status]

Regards
 - [sitename]'
		);									

		$option[]=array(
		  'option_name'=>'merchant_change_status_sms_content_en',
		  'option_value'=>'hi [restaurant_name]

Your merchant records status has change to [status]

Regards
 - [sitename]'
		);		
		
		$option[]=array(
		  'option_name'=>'customer_booked_email',
		  'option_value'=>'1'
		);		
		
		$option[]=array(
		  'option_name'=>'customer_booked_tpl_subject_en',
		  'option_value'=>'We have receive your Booking'
		);												
		
		$option[]=array(
		  'option_name'=>'customer_booked_tpl_content_en',
		  'option_value'=>'Hi [customer_name]

we have receive your booking 
for restaurant [restaurant_name]
with the following information

Number of guest : [number_guest]
Date of Booking : [date_booking]
Time : [time]
Email address : [email]
Mobile  : [mobile]
Special instruction : [instruction]

Your booking id : [booking_id]

Please wait for the merchant to accept your booking.
you will receive another email for confirmation from the merchant

Regards
 - [sitename]'
		);		
		
		$option[]=array(
		  'option_name'=>'booked_notify_admin_email',
		  'option_value'=>'1'
		);												

		$option[]=array(
		  'option_name'=>'booked_notify_admin_tpl_subject_en',
		  'option_value'=>'New booking from [restaurant_name]'
		);		
		
		$option[]=array(
		  'option_name'=>'booked_notify_admin_tpl_content_en',
		  'option_value'=>'hi admin

there is new booking from [restaurant_name]
with the following information

Booking ID : [booking_id]
Name : [customer_name]
Number of guest : [number_guest]
Date of Booking : [date_booking]
Time : [time]
Email address : [email]
Mobile  : [mobile]
Special instruction : [instruction]
'
		);												

		$option[]=array(
		  'option_name'=>'booked_notify_merchant_tpl_subject_en',
		  'option_value'=>'New booking from [customer_name]'
		);		
		
		$option[]=array(
		  'option_name'=>'booked_notify_merchant_tpl_content_en',
		  'option_value'=>'hi [restaurant_name]

there is new booking from [customer_name]
with the following information

Booking ID : [booking_id]
Name : [customer_name]
Number of guest : [number_guest]
Date of Booking : [date_booking]
Time : [time]
Email address : [email]
Mobile  : [mobile]
Special instruction : [instruction]

'
		);												

		$option[]=array(
		  'option_name'=>'booking_update_status_tpl_subject_en',
		  'option_value'=>'Update with your booking id [booking_id]'
		);		
		
		$option[]=array(
		  'option_name'=>'booking_update_status_tpl_content_en',
		  'option_value'=>'Hi [customer_name]

Your booking id [booking_id]
status updated to [status]

merchant remarks :
[merchant_remarks]

Regards
 - [sitename]'
		);															
		
		$option[]=array(
		  'option_name'=>'merchant_welcome_signup_email',
		  'option_value'=>'1'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_welcome_signup_tpl_subject_en',
		  'option_value'=>'Welcome [restaurant_name]'
		);												

		$option[]=array(
		  'option_name'=>'merchant_welcome_signup_tpl_content_en',
		  'option_value'=>'hi  [restaurant_name]

Thank you for joining us!

Login here
[login_url]

Regards
 - [sitename]'
		);		
		
		$option[]=array(
		  'option_name'=>'order_idle_to_merchant_tpl_subject_en',
		  'option_value'=>'Order ID [order_id] has been IDLE for [idle_time]'
		);															

		$option[]=array(
		  'option_name'=>'order_idle_to_merchant_tpl_content_en',
		  'option_value'=>'hi [restaurant_name]

Kindly take  action on Order ID : [order_Id]

Regards
- [sitename]'
		);		
		
		$option[]=array(
		  'option_name'=>'order_idle_to_merchant_sms_content_en',
		  'option_value'=>'hi [restaurant_name]

Kindly take action on Order ID : [order_Id]

Regards
- [sitename]'
		);												

		$option[]=array(
		  'option_name'=>'order_idle_to_admin_tpl_subject_en',
		  'option_value'=>'Order ID [order_id] has been IDLE for [idle_time]'
		);		
		
		$option[]=array(
		  'option_name'=>'order_idle_to_admin_tpl_content_en',
		  'option_value'=>'hi admin

Kindly take action on Order ID : [order_id]

Regards
- [sitename]'
		);															

		$option[]=array(
		  'option_name'=>'order_idle_to_admin_sms_content_en',
		  'option_value'=>'hi admin

Kindly take action on Order ID : [order_Id]

Regards
- [sitename]'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_invoice_email',
		  'option_value'=>'1'
		);												

		$option[]=array(
		  'option_name'=>'merchant_invoice_tpl_subject_en',
		  'option_value'=>'Your new Invoice : [invoice_number]'
		);		
		
		$option[]=array(
		  'option_name'=>'merchant_invoice_tpl_content_en',
		  'option_value'=>'hi [restaurant_name]

your invoice is now ready
Invoice number : [invoice_number]
Terms : [terms]
Period : [period]

You can donwload your invoice below
[invoice_link]

Regards
 - [sitename]'
		);															
				
        $option[]=array(
		  'option_name'=>'admin_decimal_place',
		  'option_value'=>'2'
		);		
		
		$option[]=array(
		  'option_name'=>'website_use_date_picker',
		  'option_value'=>2
		);		
		
		$option[]=array(
		  'option_name'=>'website_use_time_picker',
		  'option_value'=>3
		);	
		
		$option[]=array(
		  'option_name'=>'map_provider',
		  'option_value'=>'google.maps'
		);	
		
		$option[]=array(
		  'option_name'=>'map_distance_results',
		  'option_value'=>1
		);	
		
		$option[]=array(
		  'option_name'=>'website_review_type',
		  'option_value'=>2
		);	
		
		return $option;
	}	
    	
} /*end class*/