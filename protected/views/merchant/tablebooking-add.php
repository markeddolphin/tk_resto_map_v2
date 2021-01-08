<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/tablebooking/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/tablebooking" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/tablebooking/Do/settings" class="uk-button"><i class="fa fa-cog"></i> <?php echo Yii::t("default","Settings")?></a>
</div> 

<div class="spacer"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','bookATableMerchant')?>
<?php echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");?>
<?php if (!isset($_GET['id'])):?>
<?php echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/merchant/tablebooking/Do/Add")?>
<?php endif;?>

<?php 
$data='';
if (isset($_GET['id'])){
	$data=Yii::app()->functions->getBooking($_GET['id']);		
	if ($data['viewed']!=2){
		$db_ext=new DbExt;
	    $params=array('viewed'=>2);	    
	    $db_ext->updateData("{{bookingtable}}",$params,'booking_id',$data['booking_id']);
	}
}
?>
  
      <div class="uk-form-row">
		  <label class="uk-form-label"><?php echo Yii::t("default","Number Of Guests")?></label>
		  <?php echo CHtml::textField('number_guest',
		  isset($data['number_guest'])?$data['number_guest']:''
		  ,array(
		  'class'=>'numeric_only',
		  'data-validation'=>"required"
		  ))?>
	 </div>
	 
	 <div class="uk-form-row">
		  <label class="uk-form-label"><?php echo Yii::t("default","Date Of Booking")?></label>
		  <?php echo CHtml::hiddenField('date_booking',isset($data['date_booking'])?$data['date_booking']:'')?>
		  <?php echo CHtml::textField('date_booking1',
		  isset($data['date_booking'])?$data['date_booking']:''			 
		  ,array(
		  'class'=>'j_date',
		  'data-id'=>'date_booking',
		  'data-validation'=>"required"
		  ))?>
	 </div>
	 
	 <div class="uk-form-row">
		  <label class="uk-form-label"><?php echo Yii::t("default","Time")?></label>
		  <?php echo CHtml::textField('booking_time',
		  isset($data['booking_time'])?$data['booking_time']:''			 
		  ,array(
		  'class'=>'timepick',
		  'data-validation'=>"required"
		  ))?>
	 </div>
	 
	 <h3><?php echo Yii::t("default","Contact Information")?></h3>
	 
	 <div class="uk-form-row">
		  <label class="uk-form-label"><?php echo Yii::t("default","Name")?></label>
		  <?php echo CHtml::textField('booking_name',
		  isset($data['booking_name'])?$data['booking_name']:''			 
		  ,array(
		  'class'=>'uk-form-width-large',
		  'data-validation'=>"required"
		  ))?>
	 </div>
	 
	 <div class="uk-form-row">
		  <label class="uk-form-label"><?php echo Yii::t("default","Email")?></label>
		  <?php echo CHtml::textField('email',
		  isset($data['email'])?$data['email']:''			 		 
		  ,array(
		  'class'=>'uk-form-width-large',
		  'data-validation'=>"email"
		  ))?>
	 </div>
	 
	 <div class="uk-form-row">
		  <label class="uk-form-label"><?php echo Yii::t("default","Mobile")?></label>
		  <?php echo CHtml::textField('mobile',
		  isset($data['mobile'])?$data['mobile']:''			 		 		 
		  ,array(
		  'class'=>'uk-form-width-large',
		  'data-validation'=>"required"
		  ))?>
	 </div>
	 
	 <div class="uk-form-row">
		  <label class="uk-form-label"><?php echo Yii::t("default","Your Instructions")?></label>
		  <?php echo CHtml::textArea('booking_notes',
		  isset($data['booking_notes'])?$data['booking_notes']:''			 		 		 
		  ,array(
		  'class'=>'uk-form-width-large'			 
		  ))?>
	 </div>
	 
<!--<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Send Email Confirmation")?>?</label>
  <?php 
  echo CHtml::checkBox('send_email',
  true
  ,array('value'=>1,'class'=>"icheck"))
  ?>
</div>-->

<div class="uk-form-row">
    <label class="uk-form-label"><?php echo Yii::t("default","Message to client")?></label>
    <?php 
    echo CHtml::textArea('message','',array(
      'class'=>'uk-form-width-large'
    ));
    ?>
</div>
	 
	 
	 <div class="uk-form-row">
		  <label class="uk-form-label"><?php echo Yii::t("default","Satus")?></label>
		  <?php echo CHtml::dropDownList('status',isset($data['status'])?$data['status']:'',
		  (array)bookingStatus(),array(
		    'class'=>'booking_status uk-form-width-large'
		  ))
		  ?>
	 </div>
	 
	 
	 <div class="uk-form-row">
	 <label class="uk-form-label">&nbsp;</label>
     <input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
     </div>

  </form>