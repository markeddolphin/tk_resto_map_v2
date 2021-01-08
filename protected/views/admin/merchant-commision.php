<?php
$query='last15';
if (isset($_GET['query'])){
	$query=$_GET['query'];
}
if (isset($_GET['start_date'])){
	if (!empty($_GET['start_date'])){
		$query='period';
	}
}

$months=Yii::app()->functions->getLastTwoMonths();

$order_stats=Yii::app()->functions->orderStatusList2(false);    

$total_commission_status=Yii::app()->functions->getOptionAdmin('total_commission_status');
if (!empty($total_commission_status)){
	$total_commission_status=json_decode($total_commission_status);
} else {
	$total_commission_status=array('paid');
}
if (isset($_GET['merchant_id'])){	
	$total_commission_status=isset($_GET['stats_id'])?$_GET['stats_id']:'';
}
?>

<div class="uk-width-1">

<a href="<?php echo Yii::app()->createUrl('admin/merchantcommission',array('query'=>"all"))?>" class="uk-button selected_transaction_query all"><?php echo t("All")?></a>

<a href="<?php echo Yii::app()->createUrl('admin/merchantcommission',array('query'=>"last15"))?>" class="uk-button selected_transaction_query last15"><?php echo t("Last 15 days")?></a>

<a href="<?php echo Yii::app()->createUrl('admin/merchantcommission',array('query'=>"last30"))?>" class="uk-button selected_transaction_query last30"><?php echo t("Last 30 days")?></a>

<?php if (is_array($months) && count($months)>=1):?>
<?php foreach ($months as $key=>$months_val):?>
<a href="<?php echo Yii::app()->createUrl('admin/merchantcommission',array('query'=>"month","date"=>$key))?>" class="uk-button selected_transaction_query <?php echo "selected-".$key;?>"><?php echo Yii::app()->functions->translateDate($months_val)?></a>
<?php endforeach;?>
<?php endif;?>

</div>

<div style="height:25px;"></div>

<form id="frm_table_list" method="GET" class="report uk-form uk-form-horizontal" >
<?php echo CHtml::hiddenField('start_date',isset($_GET['start_date'])?$_GET['start_date']:"")?>
<?php echo CHtml::hiddenField('end_date',isset($_GET['end_date'])?$_GET['end_date']:"")?>

<div style="height:20px;"></div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Merchant Name")?></label>
  <?php 
  echo CHtml::dropDownList('merchant_id',
  isset($_GET['merchant_id'])?$_GET['merchant_id']:""
  ,
  (array)Yii::app()->functions->merchantList2(true)
  ,array(
    'class'=>'chosen uk-form-width-large'
  ))
  ?>
</div>

  
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Start Date")?></label>
  <?php echo CHtml::textField('start_date1',
  isset($_GET['start_date1'])?$_GET['start_date1']:""
  ,array(
  'class'=>'uk-form-width-large j_date',
  'data-id'=>'start_date'
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","End Date")?></label>
  <?php echo CHtml::textField('end_date1',
  isset($_GET['end_date1'])?$_GET['end_date1']:""
  ,array(
  'class'=>'uk-form-width-large j_date',
  'data-id'=>'end_date'
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Status")?></label>
  <?php echo CHtml::dropDownList('stats_id[]',$total_commission_status,(array)$order_stats,array(
  'class'=>"chosen uk-form-width-large",
  'multiple'=>true
  ))?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Payment Type")?></label>
  <?php 
  echo CHtml::dropDownList('payment_type',
  isset($_GET['payment_type'])?$_GET['payment_type']:1
  ,array(
    1=>t("All payment type"),
    2=>t("All offline payment"),
    3=>t("All Online payment")
  ),
  array(
    'class'=>"uk-form-width-large"
  ));
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label">&nbsp;</label>
  <!--<input type="button" class="uk-button uk-form-width-medium uk-button-success" value="Search" onclick="sales_summary_reload();">  -->
  <input type="submit" class="uk-button uk-form-width-medium uk-button-success" value="<?php echo t("Search")?>" >  
  <a href="javascript:;" rel="rptmerchantcommission" class="export_btn uk-button"><?php echo t("Export")?></a>
</div>  

<div style="height:20px;"></div>

<input type="hidden" name="action" id="action" value="merchantCommission">
<input type="hidden" name="tbl" id="tbl" value="merchant">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="merchant_id">
<input type="hidden" name="slug" id="slug" value="merchantAdd">

<?php 
echo CHtml::hiddenField('query',$query);
echo CHtml::hiddenField('query_date',isset($_GET['date'])?$_GET['date']:'' );
?>

<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
  <caption><?php echo Yii::t("default","Merchant List")?></caption>
   <thead>
        <tr>
            <th width="3%"><?php echo Yii::t("default","ID")?></th>
            <th width="7%"><?php echo Yii::t("default","Merchant Name")?></th>
            <th width="6%"><?php echo Yii::t("default","Total Price")?></th>
            <th width="5%"><?php echo Yii::t("default","Commission")?></th>            
            <th width="5%"></th>            
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>



<table class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
<thead>
        <tr>            
            <th width="5%"></th>
            <th width="5%"></th>                        
            <th width="5%"><?php echo t("Total Commission Price")?>:</th>            
            <th width="5%"><div class="total_commission"></div></th>  
            <th width="5%"></th>  
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>