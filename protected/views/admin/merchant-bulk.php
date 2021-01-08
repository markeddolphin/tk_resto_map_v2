
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/merchantAdd" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/merchantAddBulk" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Upload Bulk CSV")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/merchant" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<div class="spacer"></div>

<p class="right uk-text-muted"><a href="<?php echo baseUrl()."/merchant-sample.csv"?>" target="_blank"><?php echo t("click here")?></a> <?php echo t("for sample csv format")?></p>
<div class="clear"></div>

<div class="csv-processing-wrap">
<?php
$db_ext=new DbExt;
$msg='';
$error='';
if (isset($_POST) && $_SERVER['REQUEST_METHOD']=='POST'){		
	$filename=$_FILES['file']['name'];	
	if (preg_match("/.csv/i",$filename)) {
		ini_set('auto_detect_line_endings',TRUE);
		$handle = fopen($_FILES['file']['tmp_name'], "r");
		$x=1;
		while (($data = @fgetcsv($handle)) !== FALSE){			    										    
			echo "<p class=\"non-indent uk-text-primary\">".t("Processing line")." ($x)<br/></p>";
		    if ( count($data) >= 11){
		    	$params=array(
		    	  'restaurant_name'=>$data[0],
		    	  'restaurant_phone'=>$data[1],
		    	  'contact_name'=>$data[2],
		    	  'contact_phone'=>$data[3],
		    	  'contact_email'=>$data[4],
		    	  'country_code'=>$data[5],
		    	  'street'=>$data[6],
		    	  'city'=>$data[7],
		    	  'state'=>$data[8],
		    	  'post_code'=>$data[9],
		    	  'status'=>$data[10],
		    	  'username'=>$data[4],
		    	  'password'=>md5(isset($data[11])?$data[11]:Yii::app()->functions->generateCode()),
		    	  'restaurant_slug'=>Yii::app()->functions->createSlug($data[0]),
		    	  'date_created'=>FunctionsV3::dateNow(),
		    	  'ip_address'=>$_SERVER['REMOTE_ADDR']
		    	);		    			    			    	
		    	echo "<p class=\"indent uk-text-primary\">".t("Saving merchant")."...</p>";
		    	if (!Yii::app()->functions->isMerchantExist($data[4]) ){
		    		if ( $db_ext->insertData("{{merchant}}",$params)){		    	
		    		   echo "<p class=\"indent uk-text-primary\">".t("Successful")."...</p>";
		    	    } else {
 		    		   echo "<p class=\"indent uk-text-danger\">".t("Failed")."...</p>";
		    	    }
		    	} else echo "<p class=\"indent uk-text-danger\">".t("Email address already exist")." <br/></p>";		    	
		    } else echo "<p class=\"indent uk-text-danger\">".t("Error on line"." ".$x)." <br/></p>";
		    $x++;
		}	
		ini_set('auto_detect_line_endings',FALSE);					
	} else $msg=t("Please upload a valid CSV file");
}
?>
</div>

<form class="uk-form uk-form-horizontal" method="post" enctype="multipart/form-data"  >


<?php if ( !empty($msg)):?>
<p class="uk-alert uk-alert-danger"><?php echo $msg;?></p>
<?php endif;?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","CSV")?></label>
  <input type="file" name="file" id="file" />
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Submit")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>


</form>