<?php $mtid=Yii::app()->functions->getMerchantID();	 ?>
<div class="spacer"></div>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','shipppingRates')?>
<?php echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Free delivery above Sub Total Order")?></label>
  <?php
  echo CHtml::textField('free_delivery_above_price',
  Yii::app()->functions->getOption("free_delivery_above_price",$mtid)
  ,array('class'=>"numeric_only"));  
  ?>
  <span style="padding-left:8px;"><?php echo adminCurrencySymbol();?></span>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Table Rates")?>?</label>
  <?php
  echo CHtml::checkBox('shipping_enabled',
  Yii::app()->functions->getOption("shipping_enabled",$mtid)==2?true:false
  ,array(
  'class'=>"icheck",
  'value'=>2
  ));
  ?>
</div>

<h3><?php echo t("Table Rates")?></h3>

<div class="uk-panel uk-panel-box">
<table class="uk-table table-shipping-rates">
<thead>
<tr>
 <th><?php echo t("Distance")?></th>
 <th><?php echo t("Units")?></th>
 <th><?php echo t("Price")?></th>
 <!--<th><?php echo t("Minimum order")?></th>-->
 <th><?php echo t("Action")?></th>
</tr>
</thead>

<tbody>
 <tr>
 
 <?php if ( $resp=Yii::app()->functions->getShippingRates($mtid)):?>
 
 <?php $x=0?>
 <?php foreach ($resp as $val):?>
 <tr class="shipping-row-<?php echo $x?>">
 
   <td class="shipping-col-1">
   <?php echo CHtml::textField('distance_from[]',$val['distance_from'],
     array(
       'class'=>"numeric_only distance_from",
       "placeholder"=>t("From")
     ))?>
   <?php echo t("To")?>
   <?php echo CHtml::textField('distance_to[]',$val['distance_to'],
     array(
       'class'=>"numeric_only",
       "placeholder"=>t("To")
     ))?>
   </td>
   
   <td class="shipping-col-2">
   <?php echo CHtml::dropDownList('shipping_units[]',$val['shipping_units'],Yii::app()->functions->distanceOption())?>
   </td>
   
   <td class="shipping-col-3">
   <?php echo CHtml::textField('distance_price[]',
   standardPrettyFormat($val['distance_price'])
   ,array('class'=>"numeric_only"))?>
   </td>
      
   <!--<td class="shipping-col-2">
   <?php echo CHtml::textField('minimum_order[]',
   $val['minimum_order']>1?standardPrettyFormat($val['minimum_order']):''
   )?>
   </td>-->
   
   <td>
   <?php if ($x!=0):?>
   <a href="javascript:;" class="shipping-remove" data-id="<?php echo $x?>"><i class="fa fa-times"></i></a>
   <?php endif;?>
   </td>
 </tr>
 <?php $x++;?>
 <?php endforeach;?>
 
 <?php else :?>
 
  <td class="shipping-col-1">
   <?php echo CHtml::textField('distance_from[]','',
     array(
       'class'=>"numeric_only distance_from",
       "placeholder"=>t("From")
     ))?>
   <?php echo t("To")?>
   <?php echo CHtml::textField('distance_to[]','',
     array(
       'class'=>"numeric_only",
       "placeholder"=>t("To")
     ))?>
  </td>
  <td class="shipping-col-2">
    <?php echo CHtml::dropDownList('shipping_units[]','',Yii::app()->functions->distanceOption())?>
  </td>
  <td class="shipping-col-3">
    <?php echo CHtml::textField('distance_price[]','',array('class'=>"numeric_only"))?>
  </td>
  
  <td class="shipping-col-2">
   <!--<?php echo CHtml::textField('minimum_order[]','')?>-->
   </td>
  
  <td></td>
 </tr>
  
 <?php endif;?>
 
</tbody>

</table>
<a class="uk-button add-table-rate" href="javascript:;">+ <?php echo t("Add Table Rate")?></a>
</div>


<div class="spacer"></div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>