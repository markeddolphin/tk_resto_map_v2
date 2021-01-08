
<div id="error-message-wrapper"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','MerchantPointsSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Disabled Points System")?></label>
  <?php 
  echo CHtml::checkBox('mt_disabled_pts',
  getOption($mtid,'mt_disabled_pts')==2?true:false
  ,array(
    'value'=>2,
    'class'=>"icheck"
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Earn points by order status")?></label> 
   <?php 
    unset($status_list[0]);    
    $points_status = getOption($mtid,'mt_pts_earn_points_status');
    if(!empty($points_status)){
    	$points_status = json_decode($points_status);
    }
    echo CHtml::dropDownList('mt_pts_earn_points_status[]',
    (array)$points_status,
    (array)$status_list,array(
    'class'=>'chosen',
    'multiple'=>true,
    'style'=>"width:400px;"
  ));
  ?>
</div>

<hr/>

<h4><?php echo t("Earning Points Settings")?></h4>



<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Based points earnings")?></label>  
   <?php echo CHtml::dropDownList('mt_points_based_earn',getOption($mtid,'mt_points_based_earn'),array(
     0=>t("Please select..."),
     1=>t("Food item (default)"),
     2=>t("Order Sub total"),
   ),array(
     'class'=>"form-control",
     'style'=>"width:300px;"
   ))?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Earning Point")?></label>
   <?php 
    echo CHtml::textField('mt_pts_earning_points',
       getOption($mtid,'mt_pts_earning_points'),array(
      'class'=>'numeric_only amount_value form-control'      
    ));
    ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Earning Point Value in")." ".getCurrencyCode()?></label>
   <?php 
    echo CHtml::textField('mt_pts_earning_points_value',
       getOption($mtid,'mt_pts_earning_points_value'),array(
      'class'=>'numeric_only amount_value form-control'      
    ));
    ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Earn points above order")?></label>  
    <?php 
    echo CHtml::textField('mt_pts_earn_above_amount',getOption($mtid,'mt_pts_earn_above_amount'),array(
      'class'=>'numeric_only amount_value form-control'      
    ));
    ?>  
</div>


<hr/>

<h4><?php echo t("Redeeming Points Settings")?></h4>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Disabled Redeeming")?></label>
     <?php 
	  echo CHtml::checkBox('mt_pts_disabled_redeem',
	  getOption($mtid,'mt_pts_disabled_redeem')==1?true:false
	  ,array(
	    'class'=>"icheck",
	    'value'=>1
	  ));
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Redeeming Point")?></label>
    <?php 
    echo CHtml::textField('mt_pts_redeeming_point',
       getOption($mtid,'mt_pts_redeeming_point'),array(
      'class'=>'numeric_only amount_value form-control'      
    ));
    ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Redeeming Point Value in")." ".getCurrencyCode()?></label>
    <?php 
    echo CHtml::textField('mt_pts_redeeming_point_value',
       getOption($mtid,'mt_pts_redeeming_point_value'),array(
      'class'=>'numeric_only amount_value form-control'      
    ));
    ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Redeem points above orders")?></label>
    <?php 
    echo CHtml::textField('mt_points_apply_order_amt',
       getOption($mtid,'mt_points_apply_order_amt'),array(
      'class'=>'numeric_only amount_value form-control'      
    ));
    ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Minimum points can be used")?></label>
    <?php 
    echo CHtml::textField('mt_points_minimum',
       getOption($mtid,'mt_points_minimum'),array(
      'class'=>'numeric_only amount_value form-control'      
    ));
    ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Maximum points can be used")?></label>
    <?php 
    echo CHtml::textField('mt_points_max',
       getOption($mtid,'mt_points_max'),array(
      'class'=>'numeric_only amount_value form-control'      
    ));
    ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled customer can apply voucher even they have point discount")?></label>  
  <?php 
  echo CHtml::checkBox('mt_pts_enabled_add_voucher',
  getOption($mtid,'mt_pts_enabled_add_voucher')==1?true:false
  ,array(
    'class'=>"",
    'value'=>1
  ));
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Customer can have offers+points discount")?></label>  
  <?php 
  echo CHtml::checkBox('mt_pts_enabled_offers_discount',
  getOption($mtid,'mt_pts_enabled_offers_discount')==1?true:false
  ,array(
    'class'=>"",
    'value'=>1
  ));
  ?>
</div>

<hr/>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>