
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

$commission_stats = yii::app()->functions->getCommissionOrderStats();
$merchant_read_notice=Yii::app()->functions->getOption("merchant_read_notice",Yii::app()->functions->getMerchantID());

if ( !isset($_GET['payment_type'])){
	$_GET['payment_type']=3;
}

//dump($commission_stats);
?>

<?php if (!empty($commission_stats) && $merchant_read_notice==""):?>
<div class="uk-alert merchant_notice">
<?php echo t("Notice: Merchant should change the order status to")." <b>".t($commission_stats)?></b>
<?php echo t("Once the order has been fully satisfied, in order to be counted as earnings")?>.
<a href="javascript:;" class="uk-button remove_notice"><?php echo t("Ok got it")?>!</a>
</div>
<?php endif;?>

<div class="uk-width-1">

<a href="<?php echo Yii::app()->createUrl('merchant/statement',array('query'=>"last15"))?>" class="uk-button selected_transaction_query last15"><?php echo t("Last 15 days")?></a>

<a href="<?php echo Yii::app()->createUrl('merchant/statement',array('query'=>"last30"))?>" class="uk-button selected_transaction_query last30"><?php echo t("Last 30 days")?></a>

<?php if (is_array($months) && count($months)>=1):?>
<?php foreach ($months as $key=>$months_val):?>
<a href="<?php echo Yii::app()->createUrl('merchant/statement',array('query'=>"month","date"=>$key))?>" class="uk-button selected_transaction_query <?php echo "selected-".$key;?>"><?php echo Yii::app()->functions->translateDate($months_val)?></a>
<?php endforeach;?>
<?php endif;?>

</div>

<div style="height:25px;"></div>

<form id="frm_table_list" method="GET" class="report uk-form uk-form-horizontal" >

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Start Date")?></label>
  
  
  <?php 
  echo CHtml::hiddenField('start_date',isset($_GET['start_date'])?$_GET['start_date']:'' );
  echo CHtml::textField('start_date1',isset($_GET['start_date1'])?$_GET['start_date1']:'' 
  ,array(
  'class'=>'uk-form-width-large j_date',
  'data-id'=>'start_date'
  ))?>
  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","End Date")?></label>
  
  <?php 
  echo CHtml::hiddenField('end_date',isset($_GET['end_date'])?$_GET['end_date']:'' );
  echo CHtml::textField('end_date1',isset($_GET['end_date1'])?$_GET['end_date1']:'' 
  ,array(
  'class'=>'uk-form-width-large j_date',
  'data-id'=>'end_date'
  ))?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Payment Type")?></label>
  <?php 
  echo CHtml::dropDownList('payment_type',
  isset($_GET['payment_type'])?$_GET['payment_type']:1
  ,array(
    1=>t("All payment type"),
    2=>t("Cash"),
    3=>t("Card")
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
  <a href="javascript:;" rel="rptmerchantstatement" class="export_btn uk-button"><?php echo t("Export")?></a>
</div>  

<div style="height:20px;"></div>

<input type="hidden" name="action" id="action" value="merchantStatement">
<input type="hidden" name="tbl" id="tbl" value="item">
<?php 
echo CHtml::hiddenField('query',$query);
echo CHtml::hiddenField('query_date',isset($_GET['date'])?$_GET['date']:'' );
?>

<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr>
            <th width="3%"><?php echo Yii::t("default","Reference #")?></th>
            <th width="5%"><?php echo Yii::t("default","Payment Type")?></th>                        
            <th width="5%"><?php echo Yii::t("default","Total Price")?></th>                                    
            <th width="5%"><?php echo Yii::t("default","Commission")?> (%)</th>                        
            <!--<th width="5%"><?php echo Yii::t("default","Commission price")?></th>  -->
            <th width="5%"><?php echo Yii::t("default","Commission")." (".adminCurrencySymbol().")";?></th>            
            <th width="5%"><?php echo Yii::t("default","Net Amount")?></th>  
            <th width="5%"><?php echo Yii::t("default","Date")?></th>  
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
            <th width="5%"><?php echo t("Total Amount")?>:</th>            
            <th width="5%"><div class="total_amount"></div></th>  
            <th width="5%"></th>  
        </tr>
         <tr class="cash_tr">            
            <th width="5%"></th>
            <th width="5%"></th>                        
            <th width="5%"><?php echo t("Total Payable to")." ".ucwords(Yii::app()->functions->getOptionAdmin('website_title'))?>:</th>            
            <th width="5%"><div class="total_payable"></div></th>  
            <th width="5%"></th>  
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>