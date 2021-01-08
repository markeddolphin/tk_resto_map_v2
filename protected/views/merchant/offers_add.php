
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/offers/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/offers" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<div class="spacer"></div>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','addOffers')?>
<?php echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");?>
<?php if (!isset($_GET['id'])):?>
<?php echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/merchant/offers/Do/Add")?>
<?php endif;?>

<?php 
if (isset($_GET['id'])){
	if (!$data=Yii::app()->functions->getOffers($_GET['id'])){
		echo "<div class=\"uk-alert uk-alert-danger\">".
		Yii::t("default","Sorry but we cannot find what your are looking for.")."</div>";
		return ;
	}	
}
?>                                 

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Offer Percentage")?></label>
  <?php echo CHtml::textField('offer_percentage',
  isset($data['offer_percentage'])?number_format($data['offer_percentage'],0):""
  ,array(
  'class'=>'numeric_only',
  'data-validation'=>"required"
  ))?> %
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Orders Over")?></label>
  <?php echo CHtml::textField('offer_price',
  isset($data['offer_price'])?standardPrettyFormat($data['offer_price']):""
  ,array(
  'class'=>'numeric_only',
  'data-validation'=>"required"
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Valid From")?></label>
  <?php echo CHtml::hiddenField('valid_from',isset($data['valid_from'])?$data['valid_from']:"")?>
  <?php echo CHtml::textField('valid_from2',
  isset($data['valid_from'])?$data['valid_from']:""
  ,array(
  'class'=>'j_date',
  'data-validation'=>"required",
  'data-id'=>"valid_from"
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Valid To")?></label>
  <?php echo CHtml::hiddenField('valid_to',isset($data['valid_to'])?$data['valid_to']:"")?>
  <?php echo CHtml::textField('valid_to2',
  isset($data['valid_to'])?$data['valid_to']:""
  ,array(
  'class'=>'j_date',
  'data-validation'=>"required",
  'data-id'=>"valid_to"
  ))?>
</div>

<?php 
$applicable_to=array();
if (isset($data['applicable_to'])){
	$applicable_to=json_decode($data['applicable_to'],true);
	//dump($applicable_to);
}
?>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Applicable to")?></label>
  <?php 
  echo CHtml::checkBox('applicable_to[]',
  in_array('delivery',(array)$applicable_to)?true:false
  ,array('value'=>'delivery'));
  echo "&nbsp;".t("Delivery");
  
  echo "&nbsp;";
  
  echo CHtml::checkBox('applicable_to[]',
  in_array('pickup',(array)$applicable_to)?true:false
  ,array('value'=>'pickup'));
  echo "&nbsp;".t("Pickup");
  
  echo "&nbsp;";
  
  echo CHtml::checkBox('applicable_to[]',
  in_array('dinein',(array)$applicable_to)?true:false
  ,array('value'=>'dinein'));
  echo "&nbsp;".t("Dinein");
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Status")?></label>
  <?php echo CHtml::dropDownList('status',
  isset($data['status'])?$data['status']:"",
  (array)statusList(),          
  array(
  'class'=>'',
  'data-validation'=>"required"
  ))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>