<?php
class InvoicecronController extends CController
{
	
	public function init()
	{		
		 $name=Yii::app()->functions->getOptionAdmin('website_title');
		 if (!empty($name)){		 	
		 	 Yii::app()->name = $name;
		 }	
		 		 
		 // set website timezone
		 $website_timezone=Yii::app()->functions->getOptionAdmin("website_timezone");		 		 
		 if (!empty($website_timezone)){		 	
		 	Yii::app()->timeZone=$website_timezone;
		 }		 				 
	}
	
	public function actionIndex()
	{
		echo 'cron is working';
	}
	
	public function actionGenerateInvoice()
	{
		$DbExt=new DbExt;
		$terms=isset($_GET['terms'])?$_GET['terms']:7;
		$and=''; $date_covered_start=''; $date_covered_end='';
		
		$and_status='';
		$commission_status=FunctionsV3::getCommissionStatusBased();
		if($commission_status){
			$and_status = " AND status in ($commission_status) ";
		}		
		
		switch ($terms) {
			case 1:							
			    $date_covered_start = date("Y-m-d");
			    $date_covered_end = date("Y-m-d");			    
			    $and = " AND date_created BETWEEN '$date_covered_start 00:00:00' AND '$date_covered_end 23:59:00' ";
				break;
				
			case 7:	
			   $date_covered_start = date("Y-m-d" , strtotime("-7 days") );
			   $date_covered_end = date("Y-m-d");			    
			   $and = " AND date_created BETWEEN '$date_covered_start 00:00:00' AND '$date_covered_end 23:59:00' ";
			   break;
			   
			case 15:	
			   $date_covered_start = date("Y-m-d" , strtotime("-15 days") );
			   $date_covered_end = date("Y-m-d");			    
			   $and = " AND date_created BETWEEN '$date_covered_start 00:00:00' AND '$date_covered_end 23:59:00' ";
			   break;   
			   
			case 30:	
			   $date_covered_start = date("Y-m-d" , strtotime("-1 month") );
			   $date_covered_end = date("Y-m-d");			    
			   $and = " AND date_created BETWEEN '$date_covered_start 00:00:00' AND '$date_covered_end 23:59:00' ";
			   break;      
		
			default:
				break;
		}				
		
		$stmt="
		SELECT 
		merchant_id,
		restaurant_name,
		contact_email,
		contact_phone
		
		FROM
		{{merchant}}
		WHERE
		merchant_type='3'
		AND
		invoice_terms=".FunctionsV3::q($terms)."	
		AND status = 'active'
		AND
		merchant_id NOT IN (
		   select merchant_id
		   from
		   {{invoice}}
		   where
		   date_from = ".FunctionsV3::q($date_covered_start)."
		   and
		   date_to = ".FunctionsV3::q($date_covered_end)."
		)		
		LIMIT 0,5
		";
		if (isset($_GET['debug'])){ dump($stmt); }		
		if ($res=$DbExt->rst($stmt)){
			foreach ($res as $val) {
				if (isset($_GET['debug'])){ dump($val); }
				$stmt2="
				SELECT SUM(total_commission) as total_commission
				FROM
				{{order}}
				WHERE
				merchant_id=".FunctionsV3::q($val['merchant_id'])."
				$and
				$and_status
				";
				if (isset($_GET['debug'])){ dump($stmt2); }
				if ($res2=$DbExt->rst($stmt2)){
					$res2=$res2[0];					
					$params=array(
					  'merchant_id'=>$val['merchant_id'],
					  'merchant_name'=>addslashes(stripslashes($val['restaurant_name'])),
					  'merchant_contact_email'=>$val['contact_email'],
					  'merchant_contact_phone'=>$val['contact_phone'],
					  'invoice_terms'=>$terms,
					  'invoice_total'=>$res2['total_commission']>0?$res2['total_commission']:0,
					  'date_from'=>$date_covered_start,
					  'date_to'=>$date_covered_end,
					  'date_created'=>FunctionsV3::dateNow(),
					  'ip_address'=>$_SERVER['REMOTE_ADDR']
					);
					if (isset($_GET['debug'])){ dump($params);}
					$DbExt->insertData("{{invoice}}",$params);
				}
			}
		} else {
			if (isset($_GET['debug'])){ echo "No results";}
		}
	}
	
	public function actionGeneratePdf()
	{					
		define ('K_TCPDF_EXTERNAL_CONFIG', true);
		$upload_path=FunctionsV3::uploadPath();		
        $assets_path=Yii::getPathOfAlias('webroot')."/assets/images";

        if (!file_exists($upload_path."/invoice")){
	    	mkdir($upload_path."/invoice",0777);
	    }
	    
	    $website_logo=getOptionA('website_logo');
	    if(!empty($website_logo)){
	    	if (file_exists($upload_path.$website_logo)){
	    		define ('K_PATH_IMAGES', $upload_path."/");
	    		$logo=$website_logo;
	    	} else define ('K_PATH_IMAGES', $assets_path ."/" );
	    } else define ('K_PATH_IMAGES', $assets_path ."/");
				   	
		$DbExt=new DbExt;
		$html=''; 
		$terms=FunctionsV3::InvoiceTerms();
		$site_title=getOptionA("website_title");
		
		$bank_account_name=getOptionA('admin_bank_account_name');
		$bank_account_number=getOptionA('admin_bank_account_number');
		$bank_custom_tpl=getOptionA('admin_bank_custom_tpl');
		$bank_deposited_timeframe=getOptionA('admin_bank_deposited_timeframe');
		if(empty($bank_deposited_timeframe)){
			$bank_deposited_timeframe=0;
		}
		
		$and_status='';
		$commission_status=FunctionsV3::getCommissionStatusBased();
		if($commission_status){
			$and_status = " AND status in ($commission_status) ";
		}		
		
		$stmt="
		SELECT a.*,
		concat(b.street,' ',b.city) as merchant_address,
		concat(b.state,' ',b.post_code,' ',b.country_code) as merchant_address2
		
		FROM
		{{invoice}} a
		
		left join {{merchant}} b
		ON
		a.merchant_id = b.merchant_id

		WHERE
		a.status = 'pending'
		ORDER BY invoice_number ASC
		LIMIT 0,10
		";
		
		if (isset($_GET['debug'])){ dump($stmt); }
		
		$process_msg='';
		
		if ($res=$DbExt->rst($stmt)){			
			foreach ($res as $val) {		
								
				$merchant_id=$val['merchant_id'];
				$invoice_number=$val['invoice_number'];
				$invoice_token=FunctionsV3::generateInvoiceToken();
				$pdf_filename="$invoice_number-$invoice_token";
				
				$bank_deposited_timeframe_date = date("Y-m-d" , strtotime($val['date_created'] ." +$bank_deposited_timeframe days") );				
				$stmt2="
				SELECT * FROM
				{{order}}
				WHERE
				merchant_id=".FunctionsV3::q($val['merchant_id'])."
				AND
				date_created BETWEEN '".$val['date_from']." 00:00:00' AND '".$val['date_to']." 23:59:00'
				$and_status
				";				
				if (isset($_GET['debug'])){ dump($stmt2); }
				
				if ($res2=$DbExt->rst($stmt2)){
					$html=Yii::app()->controller->renderPartial('/admin/invoice-tpl',array(
					   'invoice_info'=>$val,
					   'data'=>$res2,
					   'terms'=>$terms,
					   'site_title'=>$site_title,
					   'bank_account_name'=>$bank_account_name,
					   'bank_account_number'=>$bank_account_number,
					   'bank_custom_tpl'=>$bank_custom_tpl,
					   'bank_deposited_timeframe_date'=>$bank_deposited_timeframe_date
					),true);
										
					$this->writePDF($html,$pdf_filename);					
					$process_msg="process";
					
				} else {
					$process_msg="process"; $pdf_filename='';
				}
				
				if(!empty($pdf_filename)){
					$pdf_filename=$pdf_filename.".pdf";
					
					/*SEND NOTIFICATION TO MERCHANT*/
					FunctionsV3::sendInvoiceNotification($merchant_id,$val,$pdf_filename);
					
				}
				
				$params=array(
				  'status'=>$process_msg,
				  'invoice_token'=>$invoice_token,
				  'pdf_filename'=>$pdf_filename,
				  'date_process'=>FunctionsV3::dateNow(),
				  'ip_address'=>$_SERVER['REMOTE_ADDR']
				);
				if (isset($_GET['debug'])){ dump($params); }
				$DbExt->updateData("{{invoice}}",$params,'invoice_number',$invoice_number);
			}
		} else {
			if (isset($_GET['debug'])){ echo "no results"; }
		}
	}
	
	public function writePDF($data='', $pdf_filename='')
	{
		Yii::app()->setImport(array(			
		  'application.vendor.TCPDF.*'
	    ));
	    require_once('tcpdf.php');
	    
	    $site_title=getOptionA("website_title");
	    $upload_path=FunctionsV3::uploadPath();		
	    $assets_path=Yii::getPathOfAlias('webroot')."/assets/images";
	    $logo="default-image-merchant.png";
	    
	    /*if (!file_exists($upload_path."/invoice")){
	    	mkdir($upload_path."/invoice",777);
	    }*/
	    
	    /*$website_logo=getOptionA('website_logo');
	    if(!empty($website_logo)){
	    	if (file_exists($upload_path.$website_logo)){
	    		define ('K_PATH_IMAGES', $upload_path."/");
	    		$logo=$website_logo;
	    	} else define ('K_PATH_IMAGES', $assets_path ."/" );
	    } else define ('K_PATH_IMAGES', $assets_path ."/");*/
	    
	    $site_addres=getOptionA("website_address");
	    if ($country_details=FunctionsV3::getCountryByShortCode(getOptionA('admin_country_set'))){
	    	$site_addres.=" ".$country_details['country_name'];
	    } else $site_addres.=" ".getOptionA('admin_country_set');	    
	    
	    $site_addres.="\n".getOptionA('website_contact_phone')." / ".getOptionA('website_contact_email');	    
	    	   
	   	    
	    $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
	    $pdf->SetCreator($site_title);
		$pdf->SetAuthor($site_title);
		$pdf->SetTitle('Invoice');
		$pdf->SetDefaultMonospacedFont("courier");				
				
		//dump($logo);die();
		$pdf->SetHeaderData( $logo , 15, $site_title, $site_addres);
		
		// set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', 9));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', 9));

        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, 25);	
        
        // set image scale factor
        $pdf->setImageScale(1.25);
        
        // set font
        $pdf->SetFont('helvetica', 'B', 20);
        
        // add a page
       $pdf->AddPage();
       
       $pdf->SetFont('helvetica', '', 8);
        
       
       //$pdf->writeHTML("<p style=\"color:red;\">test</p>", true, false, false, false, '');
       $pdf->writeHTML($data, true, false, false, false, '');
       
       //$pdf->Output('example_048.pdf', 'I');       
       $pdf->Output($upload_path."/invoice/$pdf_filename.pdf", 'F');
	}
	
} /*end class*/