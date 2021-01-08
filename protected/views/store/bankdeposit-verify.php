<?php
$this->renderPartial('/front/default-header',array(
   'h1'=>t("Bank Deposit"),
   'sub_text'=>t("verify bank deposit")
));
$ref=isset($_GET['ref'])?$_GET['ref']:'';
?>

<div class="sections section-grey2 section-orangeform">
  <div class="container">  
  <div class="inner">
  
  <h1><?php echo t("Bank Deposit verification")?></h1>
  <div class="box-grey rounded">
  <?php if ($res=Yii::app()->functions->getMerchantByToken($ref)):?>
  
   <p class="text-muted">
  <?php echo Yii::t("default","Please enter the details of your bank deposit payment below.")?><br/>
  <?php echo Yii::t("default","Failure to provide accurate information may cause delays in processing or invalidation of your payment.")?>
  </p>
    
  <form class="forms-normal forms" id="forms">
  <?php echo CHtml::hiddenField('action','bankDepositVerification')?>
  <?php echo CHtml::hiddenField('ref',$ref)?>
  
  <div class="row top10">
	  <div class="col-md-3"><?php echo t("Branch Code")?></div>
	  <div class="col-md-8">
	    <?php
	    echo CHtml::textField('branch_code','',array('class'=>"grey-fields full-width",'data-validation'=>"required"))
	    ?>
	  </div>
  </div>
  
  <div class="row top10">
	  <div class="col-md-3"><?php echo t("Date")?></div>
	  <div class="col-md-8">
	   <?php echo CHtml::hiddenField('date_of_deposit')?>
		  <?php echo CHtml::textField('date_of_deposit1','',
		  array('class'=>"j_date2 grey-fields full-width",
		  'data-validation'=>"required",
		  'data-id'=>'date_of_deposit'
		  ));
		  ?>
	  </div>
  </div> 
  
   <div class="row top10">
	  <div class="col-md-3"><?php echo t("Time")?></div>
	  <div class="col-md-8">
	    <?php echo CHtml::textField('time_of_deposit','',array('class'=>"timepick grey-fields full-width",'data-validation'=>"required"))?>
	  </div>
  </div> 
  
 <div class="row top10">
	  <div class="col-md-3"><?php echo t("Amount")?></div>
	  <div class="col-md-8">
	      <?php echo CHtml::textField('amount','',array('data-validation'=>"required",'class'=>'numeric_only grey-fields full-width'))?>
	  </div>
  </div>  
  
 <p class="text-muted" style="margin-top:10px;"><?php echo Yii::t("default","or upload your scan bank deposit")?></p> 

 <div class="row top10">
	  <div class="col-md-3"><?php echo t("Scan Bank deposit slip")?></div>
	  <div class="col-md-8">
	  
	   <!--<div style="display:inline-table;margin-left:1px;" class="btn btn-default" id="photo"><?php echo Yii::t('default',"Browse")?></div>	  
  <DIV  style="display:none;" class="photo_chart_status" >
	<div id="percent_bar" class="photo_percent_bar"></div>
	<div id="progress_bar" class="photo_progress_bar">
	  <div id="status_bar" class="photo_status_bar"></div>
	</div>
  </DIV>	 -->
	   
	    <a class="btn btn-info" href="javascript:;" id="upload_deposit" data-progress="upload_deposit_progress" data-preview="upload_deposit_preview" data-clear="1" >
               <?php echo t("Browse")?>
             </a>       
             
             <div class="upload_deposit_preview preview" id="upload_deposit_preview"></div>
	  
	  </div>
  </div>  
  
  <div class="row top10">
	  <div class="col-md-3"></div>
	  <div class="col-md-8">
	      <input type="submit" value="<?php echo Yii::t("default","Submit")?>" class="btn btn-success">
	  </div>
  </div>  
 
  </form>
  
  <?php else :?>
  <p class="text-danger"><?php echo Yii::t("default","Sorry but we cannot find what you are looking for.")?></p>
  <?php endif;?>
  
  </div>
  </div>
  </div>
</div>  