<h2><?php echo t("Contact us")?></h2>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php 
echo CHtml::hiddenField('action','singleMerchantContact');
$contactus_fields = getOption($merchant_id,'singleapp_contactus_fields');
if(!empty($contactus_fields)){
	$contactus_fields= json_decode($contactus_fields,true);
}
?>



<div class="row">
  <div class="col-md-2">
  
   <div class="custom-control custom-checkbox">  
	 <?php 
	 echo CHtml::checkBox('singleapp_contactus_enabled',
	 getOption($merchant_id,'singleapp_contactus_enabled')==1?true:false
	 ,array(
	   'value'=>1
	 ));
	 ?>
	  <label class="custom-control-label">
	    <?php echo t("Enabled")?>
	  </label>
	</div>
  
  </div> <!--col-->  
</div>

<div class="height10"></div>  
<div class="height10"></div>  

<div class="row">
  <div class="col-md-5">
  
  <div class="form-group">
	    <label ><?php echo t("Send to")?></label>
	    <?php 
	    echo CHtml::textField('singleapp_contact_email',
	    getOption($merchant_id,'singleapp_contact_email')
	    ,array(
	      'class'=>'form-control',
	    ));
	    ?>
	    <span class="small"><?php echo t("Email address that will receive the email")?></span>
	</div> 
  
  </div> <!--col-->   
  
   <div class="col-md-5">
  
  <div class="form-group">
	    <label ><?php echo t("Subject")?></label>
	    <?php 
	    echo CHtml::textField('singleapp_contact_subject',
	    getOption($merchant_id,'singleapp_contact_subject')
	    ,array(
	      'class'=>'form-control',
	    ));
	    ?>	   
	</div> 
  
  </div> <!--col-->   
  
  
</div> <!--row-->



 <div class="form-group">
    <label ><?php echo t("Template")?></label>
    <?php 
    echo CHtml::textArea('singleapp_contact_tpl',
    getOption($merchant_id,'singleapp_contact_tpl')
    ,array(
      'class'=>'form-control', 
      'required'=>true,
      'style'=>"height:200px;"
    ));
    ?>
    <span><?php echo t("Tag available")?> : <b>[name] [email] [country] [phone] [message] [merchant_name]</b></span>
  </div>  

<div class="height10"></div>  
<p><b><?php echo t("Contact form fields")?></b></p>

<div class="row">
  <div class="col-md-2">
  
	 <div class="custom-control custom-checkbox">  
	 <?php 
	 echo CHtml::checkBox('singleapp_contactus_fields[1]',
	 in_array("name",(array)$contactus_fields)?true:false
	 ,array(
	   'value'=>'name',	   
	 ));
	 ?>
	  <label class="custom-control-label">
	    <?php echo t("Name")?>
	  </label>
	</div>
  
  </div> <!--col-->
  <div class="col-md-2">
   
   <div class="custom-control custom-checkbox">  
	 <?php 
	 echo CHtml::checkBox('singleapp_contactus_fields[2]',
	 in_array("email",(array)$contactus_fields)?true:false
	 ,array(
	   'value'=>'email',	   
	 ));
	 ?>
	  <label class="custom-control-label">
	    <?php echo t("Email")?>
	  </label>
	</div>
  
  </div> <!--col-->
  <div class="col-md-2">
  
   <div class="custom-control custom-checkbox">  
	 <?php 
	 echo CHtml::checkBox('singleapp_contactus_fields[3]',
	 in_array("phone",(array)$contactus_fields)?true:false
	 ,array(
	   'value'=>'phone',	   
	 ));
	 ?>
	  <label class="custom-control-label">
	    <?php echo t("Phone")?>
	  </label>
	</div>
  
  </div> <!--col-->
  <div class="col-md-2">  
  
   <div class="custom-control custom-checkbox">  
	 <?php 
	 echo CHtml::checkBox('singleapp_contactus_fields[4]',
	 in_array("country",(array)$contactus_fields)?true:false
	 ,array(
	   'value'=>'country',	   
	 ));
	 ?>
	  <label class="custom-control-label">
	    <?php echo t("Country")?>
	  </label>
	</div>
  
  </div> <!--col-->
  <div class="col-md-2">  
  
   <div class="custom-control custom-checkbox">  
	 <?php 
	 echo CHtml::checkBox('singleapp_contactus_fields[5]',
	 in_array("message",(array)$contactus_fields)?true:false
	 ,array(
	   'value'=>'message',	   
	 ));
	 ?>
	  <label class="custom-control-label">
	    <?php echo t("Message")?>
	  </label>
	</div>
  
  </div> <!--col-->
</div>


<div class="spacer"></div>

<button type="submit" class="btn btn-success">
<?php echo t("Save settings")?>
</button>

<?php echo CHtml::endForm(); ?>