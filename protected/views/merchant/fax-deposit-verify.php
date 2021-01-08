<?php
$ref=isset($_GET['ref'])?$_GET['ref']:'';
$FunctionsK=new FunctionsK();
?>

<div class="page-right-sidebar payment-option-page">
  <div class="main">
  <div class="inner">
  
  <?php if ($res=$FunctionsK->getFaxTransactionByRef($ref)):?>
  
  <h2><?php echo Yii::t("default","Bank Deposit verification")?></h2>
    
  <p class="uk-text-muted">
  <?php echo Yii::t("default","Please enter the details of your bank deposit payment below.")?><br/>
  <?php echo Yii::t("default","Failure to provide accurate information may cause delays in processing or invalidation of your payment.")?>
  </p>
  
  <form class="uk-form uk-form-horizontal forms" id="forms">
  <?php echo CHtml::hiddenField('action','FaxbankDepositVerification')?>
  <?php echo CHtml::hiddenField('ref',$ref)?>
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Branch Code")?></label>
  <?php echo CHtml::textField('branch_code','',array('data-validation'=>""))?>
  </div>
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Date")?></label>
  <?php echo CHtml::textField('date_of_deposit','',array('class'=>"j_date"))?>
  </div>
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Time")?></label>
  <?php echo CHtml::textField('time_of_deposit','',array('class'=>"timepick"))?>
  </div>
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Amount")?></label>
  <?php echo CHtml::textField('amount','',array('data-validation'=>"",'class'=>'numeric_only'))?>
  </div>
    

  <p class="uk-text-muted"><?php echo Yii::t("default","or upload your scan bank deposit")?></p>
  
<div class="uk-form-row"> 
 <label class="uk-form-label"><?php echo Yii::t('default',"Scan Bank deposit slip")?></label>
  <div style="display:inline-table;margin-left:1px;" class="button uk-button" id="photo"><?php echo Yii::t('default',"Browse")?></div>	  
  <DIV  style="display:none;" class="photo_chart_status" >
	<div id="percent_bar" class="photo_percent_bar"></div>
	<div id="progress_bar" class="photo_progress_bar">
	  <div id="status_bar" class="photo_status_bar"></div>
	</div>
  </DIV>		  
</div>  

<div class="uk-form-row"> 
<div class="input_block preview">
<div class="image_preview"></div>
</div>
</div>
  
  <div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Submit")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>
  
  </form>
  
  <?php else :?>
  <p class="uk-text-danger"><?php echo Yii::t("default","Sorry but we cannot find what you are looking for.")?></p>
  <?php endif;?>
  
  </div>
</div>