<?php
$this->renderPartial('/front/default-header',array(
   'h1'=>t("Verification"),
   'sub_text'=>''
));

$ref=isset($_GET['ref'])?$_GET['ref']:'';
?>
<div class="sections section-grey2 section-mobile-verification section-orangeform">
 <div class="container">
   <div class="row top30">
   
     <div class="inner">
         <h1><?php echo t("Bank Deposit verification")?></h1>
         <div class="box-grey rounded">	     	     	  
         
         <?php if ($res=Yii::app()->functions->getOrderInfo($ref)):?>
		     
		<p class="text-muted">
		<?php echo Yii::t("default","Please enter the details of your bank deposit payment below.")?><br/>
		<?php echo Yii::t("default","Failure to provide accurate information may cause delays in processing or invalidation of your payment.")?>
		</p>  
		
		 <form class="uk-form uk-form-horizontal forms" id="forms">
		 <?php echo CHtml::hiddenField('action','ItemBankDepositVerification')?>
		 <?php echo CHtml::hiddenField('ref',$ref)?>  
		
		 
		
		<div class="row top25">
		   <div class="col-md-4"><?php echo Yii::t("default","Branch Code")?></div>
		   <div class="col-md-7">
		   <?php echo CHtml::textField('branch_code','',array('data-validation'=>"required",
		   'class'=>"grey-fields"
		   ))?>
		   </div>
		 </div> 
		 
		 <div class="row top10">
		   <div class="col-md-4"><?php echo Yii::t("default","Date")?></div>
		   <div class="col-md-7">
		    <?php echo CHtml::hiddenField('date_of_deposit')?>
  <?php echo CHtml::textField('date_of_deposit1','',
  array('class'=>"j_date2 grey-fields",'data-validation'=>"required",
  'data-id'=>'date_of_deposit'
  ))?>
		   </div>
		 </div> 
		 
		 
        <div class="row top10">
		   <div class="col-md-4"><?php echo Yii::t("default","Time")?></div>
		   <div class="col-md-7"><?php echo CHtml::textField('time_of_deposit','',array('class'=>"timepick grey-fields",'data-validation'=>"required"))?></div>
		 </div> 
				
        <div class="row top10">
		   <div class="col-md-4"><?php echo Yii::t("default","Amount")?></div>
		   <div class="col-md-7"><?php echo CHtml::textField('amount','',array('data-validation'=>"required",'class'=>'numeric_only grey-fields'))?></div>
		 </div> 
		 
		  <br/>		  
		  <p class="text-muted"><?php echo Yii::t("default","or upload your scan bank deposit")?></p> 
		
		  <h3><?php echo t("Scan Bank deposit slip")?></h3>
		  
		<div class="row top10">
		   <div class="col-md-4"><?php echo Yii::t('default',"Scan Bank deposit slip")?></div>
		   <div class="col-md-7">
			
			<!--<div style="display:inline-table;margin-left:1px;" class="button orange-button" id="photo"><?php echo Yii::t('default',"Browse")?></div>	  
			<DIV  style="display:none;" class="photo_chart_status" >
			<div id="percent_bar" class="photo_percent_bar"></div>
			<div id="progress_bar" class="photo_progress_bar">
			<div id="status_bar" class="photo_status_bar"></div>
			</div>
			</DIV>		  -->
			
			 <a class="btn btn-info" href="javascript:;" id="upload_deposit" data-progress="upload_deposit_progress" data-preview="upload_deposit_preview" >
               <?php echo t("Browse")?>
             </a>       
             
             <div class="upload_deposit_preview preview" id="upload_deposit_preview"></div>
		   
		   </div>
		 </div> 
		
		<div class="row top30"> 
		<div class="input_block preview">
		<div class="image_preview"></div>
		</div>
		</div>		 
		
		
        <div class="top30"></div>
        <input type="submit" value="<?php echo t("Submit")?>" class="green-button inline">		  
  
		 </form>
         
         <?php else :?>
          <p class="text-danger"><?php echo t("Sorry but we cannot find what you are looking for.")?></p>
         <?php endif;?>
           
         </div>
     </div>
   
   </div>
 </div>
</div>