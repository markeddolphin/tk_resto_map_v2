<?php 
$merchant_id=Yii::app()->functions->getMerchantID();
$list=Yii::app()->functions->getPaymentProviderListActive(); 
$list_check=Yii::app()->functions->getOption('payment_provider',$merchant_id);

$enabled=Yii::app()->functions->getOption('merchant_payondeliver_enabled',$merchant_id);
if (!empty($list_check)){
	$list_check=json_decode($list_check);
}
?>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','payOnDeliveryMerchant')?>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled")?>?</label>
  <?php 
  echo CHtml::checkBox('merchant_payondeliver_enabled',
  $enabled=="yes"?true:false
  ,array(
    'value'=>"yes",
    'class'=>"icheck"
  ))
  ?> 
</div>



<div class="uk-form-row payment-provider-list">
  <label class="uk-form-label"><?php echo Yii::t("default","Mode")?></label>
  <?php if (is_array($list) && count($list)>=1):?>
  <?php foreach ($list as $val):?>
  <?php 
  echo CHtml::checkBox('payment_provider[]',
  in_array($val['id'],(array)$list_check)?true:false
  ,array('class'=>"icheck",'value'=>$val['id']));
  echo "<span>".ucwords($val['payment_name'])."</span>";
  echo "<img src=\"".uploadURL()."/".$val['payment_logo']."\" >";  
  ?>
  <?php endforeach;?>
  <?php endif;?>
</div>

<div class="spacer"></div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>