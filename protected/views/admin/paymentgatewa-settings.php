<form class="uk-form uk-form-horizontal admin-settings-page forms" id="forms">
<?php echo CHtml::hiddenField('action','paymentgatewaySettings')?>

<?php 
$paymentgateway=Yii::app()->functions->getMerchantListOfPaymentGateway();
$list=FunctionsV3::PaymentOptionList();
?>

<h4><?php echo t("list of enabled payment gateway on merchant")?></h4>
  
  <div class="uk-form-row">  
  <ul>
  
  <?php foreach ($list as $key=>$val):?>
   <li><?php 
   echo CHtml::checkBox('paymentgateway[]',
   in_array($key,(array)$paymentgateway)?true:false
   ,array(
    'class'=>"icheck",
    'value'=>$key
   ));
   echo "<span>".$val."</span>";
   ?>
   </li>
   <?php endforeach;?>
        
  </ul>
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>