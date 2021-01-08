
<div class="spacer"></div>

<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','deliveryTableRate')?>

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

<h3><?php echo t("Services Charges to Your Customer")?></h3>

<div class="uk-panel uk-panel-box">
<table class="uk-table table-shipping-rates">
<thead>
<tr>
 <th><?php echo t("Radius")?></th>
 <?php if(is_array($range) && count($range)>=1):?>
 <?php foreach ($range as $val):?>
 <th><?php echo FunctionsV3::prettyPrice($val['range_from'])."&nbsp;-&nbsp;".FunctionsV3::prettyPrice($val['range_to'])?></th>
 <?php endforeach;?>
 <?php endif;?>
 <th><?php echo t("Action")?></th>
</tr>
</thead>

<tbody>

<?php if(is_array($data) && count($data)>=1):?>
<?php foreach ($data as $data_val):?>
 <tr class="first_row">
  <td>
    <div class="mytable">
      <div class="col">
         <?php echo CHtml::textField('from[]',
         isset($data_val['distance_from'])?$data_val['distance_from']:''
         ,array('class'=>'numeric_only',
         'style'=>'width:80px;',
         'placeholder'=>t("from")
         ))?>
      </div>
      <div class="col">
         <?php echo CHtml::textField('to[]',
         isset($data_val['distance_to'])?$data_val['distance_to']:''
         ,array('class'=>'numeric_only',
          'style'=>'width:80px;',
          'placeholder'=>t("to")
         ))?>
      </div>
      
      <div class="col">
       <?php echo CHtml::dropDownList('unit[]',
       isset($data_val['unit'])?$data_val['unit']:''
       ,Yii::app()->functions->distanceOption(),array(
         'style'=>'width:80px;',
       ))?>
      </div>
      
    </div>
  </td>
  
  <?php if(is_array($range) && count($range)>=1):?>
  <?php foreach ($range as $key=>$val):?>
   <th>
    <?php echo CHtml::textField("fee[$val[id]][]",
    normalPrettyPrice($data_val[ $val['id']."_fee" ])
    ,array(
      'class'=>'numeric_only',
      'style'=>"width:80px;",
      'placeholder'=>t("fee")
    ))?>
   </th>
  <?php endforeach;?>
  <?php endif;?>
 
  <td>
    <a href="javascript:;" class="remove_rate" data-id="1"><i class="fa fa-times"></i></a>
  </td>
  
</tr> 
<?php endforeach;?>
<?php else :?>

<tr class="first_row">
  <td>
    <div class="mytable">
      <div class="col">
         <?php echo CHtml::textField('from[]',
         ''
         ,array('class'=>'numeric_only',
         'style'=>'width:80px;',
         'placeholder'=>t("from")
         ))?>
      </div>
      <div class="col">
         <?php echo CHtml::textField('to[]',
         ''
         ,array('class'=>'numeric_only',
          'style'=>'width:80px;',
          'placeholder'=>t("to")
         ))?>
      </div>
      
      <div class="col">
       <?php echo CHtml::dropDownList('unit[]',
       ''
       ,Yii::app()->functions->distanceOption(),array(
         'style'=>'width:80px;',
       ))?>
      </div>
      
    </div>
  </td>
  
  <?php if(is_array($range) && count($range)>=1):?>
  <?php foreach ($range as $key=>$val):?>
   <th>
    <?php echo CHtml::textField("fee[$val[id]][]",
     ''
    ,array(
      'class'=>'numeric_only',
      'style'=>"width:80px;",
      'placeholder'=>t("fee")
    ))?>
   </th>
  <?php endforeach;?>
  <?php endif;?>
 
  <td>
    <a href="javascript:;" class="remove_rate" data-id="1"><i class="fa fa-times"></i></a>
  </td>
  
</tr> 

<?php endif;?>

</tbody>

</table>
<a class="uk-button add_fee_row" href="javascript:;">+ <?php echo t("Add new row")?></a>
</div>


<div class="spacer"></div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>