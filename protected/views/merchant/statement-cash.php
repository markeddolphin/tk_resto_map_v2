
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
?>

<div class="uk-width-1">

<a href="<?php echo Yii::app()->createUrl('merchant/cashstatement',array('query'=>"last15"))?>" class="uk-button selected_transaction_query last15"><?php echo t("Last 15 days")?></a>

<a href="<?php echo Yii::app()->createUrl('merchant/cashstatement',array('query'=>"last30"))?>" class="uk-button selected_transaction_query last30"><?php echo t("Last 30 days")?></a>

<?php if (is_array($months) && count($months)>=1):?>
<?php foreach ($months as $key=>$months_val):?>
<a href="<?php echo Yii::app()->createUrl('merchant/cashstatement',array('query'=>"month","date"=>$key))?>" class="uk-button selected_transaction_query <?php echo "selected-".$key;?>"><?php echo $months_val?></a>
<?php endforeach;?>
<?php endif;?>

</div>

<div style="height:25px;"></div>

<form id="frm_table_list" method="GET" class="report uk-form uk-form-horizontal" >

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Start Date")?></label>
  <?php echo CHtml::textField('start_date',isset($_GET['start_date'])?$_GET['start_date']:'' 
  ,array(
  'class'=>'uk-form-width-large j_date' 
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","End Date")?></label>
  <?php echo CHtml::textField('end_date',isset($_GET['end_date'])?$_GET['end_date']:'' 
  ,array(
  'class'=>'uk-form-width-large j_date' 
  ))?>
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
echo CHtml::hiddenField('cash_statement',2);
?>

<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr>
            <th width="3%"><?php echo Yii::t("default","Reference #")?></th>
            <th width="5%"><?php echo Yii::t("default","Payment Type")?></th>                                           
            <th width="5%"><?php echo Yii::t("default","Total Price")?></th>                 
            <th width="5%"><?php echo Yii::t("default","Commission (%)")?></th>                        
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
        <tr>            
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