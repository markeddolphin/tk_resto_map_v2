
<div class="spacer"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','minTableRates')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Enabled Table")?>?</label>
  <?php
  echo CHtml::checkBox('min_tables_enabled',
  Yii::app()->functions->getOption("min_tables_enabled",$mtid)==1?true:false
  ,array(
  'class'=>"icheck",
  'value'=>1
  ));
  ?>
</div>

<h3><?php echo t("Delivery Minimum Purchase Rates")?></h3>

<div class="uk-panel uk-panel-box">
<table class="uk-table min-order-table">
<thead>
<tr>
 <th><?php echo t("Distance")?></th>
 <th><?php echo t("Units")?></th> 
 <th><?php echo t("Minimum Order")?></th> 
 <th><?php echo t("Action")?></th>
</tr>
</thead>

<tbody>

<?php if (is_array($data) && count($data)>=1):?>

<?php $x=1;?>	
 <?php foreach ($data as $val):?> 
 
	<tr class="min-table-row-<?php echo $x;?>">
	
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
	  <?php echo CHtml::textField('min_order[]',
	  $val['min_order']>=0.001?standardPrettyFormat($val['min_order']):''
	   ,array('class'=>"numeric_only"))?>
	  </td>
	    
	  <td>
	  <?php if ($x!=1):?>
	  <a href="javascript:;" class="min-order-table-remove" data-id="<?php echo $x?>" ><i class="fa fa-times"></i></a>
	  <?php endif;?>
	  </td>
	  
	</tr>
	 
	<?php $x++;?>	
 <?php endforeach;?>

<?php else :?>

<tr>

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
  <?php echo CHtml::textField('min_order[]',
   ''
   ,array('class'=>"numeric_only"))?>
  </td>
    
  <td></td>
  
</tr>
<?php endif;?>
</tbody>

</table>
<a class="uk-button add-table-min-order-row" href="javascript:;">+ <?php echo t("Add New Row")?></a>
</div>

<div class="spacer"></div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>