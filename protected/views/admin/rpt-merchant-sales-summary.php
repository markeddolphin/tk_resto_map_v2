<form id="frm_table_list" method="GET" class="report uk-form uk-form-horizontal" >

<?php echo CHtml::hiddenField('start_date',isset($_GET['start_date'])?$_GET['start_date']:"")?>
<?php echo CHtml::hiddenField('end_date',isset($_GET['end_date'])?$_GET['end_date']:"")?>

<?php 
$order_stats=Yii::app()->functions->orderStatusList2(false);    
?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Merchant Name")?></label>
  <?php 
  echo CHtml::dropDownList('merchant_id',
  isset($_GET['merchant_id'])?$_GET['merchant_id']:''
  ,
  (array)Yii::app()->functions->merchantList3(true,true)
  ,array(
    'class'=>'uk-form-width-large'
  ))
  ?>
</div>
  
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Start Date")?></label>
  <?php echo CHtml::textField('start_date1',
  isset($_GET['start_date1'])?$_GET['start_date1']:''
  ,array(
  'class'=>'uk-form-width-large j_date',
  'data-id'=>'start_date'
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","End Date")?></label>
  <?php echo CHtml::textField('end_date1',
  isset($_GET['end_date1'])?$_GET['end_date1']:''
  ,array(
  'class'=>'uk-form-width-large j_date',
  'data-id'=>'end_date'
  ))?>
</div>

<?php 

$default=Yii::app()->functions->getCommissionOrderStatsArray();

if (isset($_GET['merchant_id'])){	
	$default=isset($_GET['stats_id'])?$_GET['stats_id']:'';
}
?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Status")?></label>
  <?php echo CHtml::dropDownList('stats_id[]',$default,(array)$order_stats,array(
  'class'=>"chosen uk-form-width-large",
  'multiple'=>true
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label">&nbsp;</label>  
  <input type="submit" value="<?php echo t("Search")?>" class="uk-button uk-form-width-medium uk-button-success">
  <a href="javascript:;" rel="rptmerchantsalesummary" class="export_btn uk-button"><?php echo t("Export")?></a>
</div>  

<div style="height:20px;"></div>


<h3 style="text-align:center;"><?php echo t("Merchant Sales Summary Report")?></h3>
<?php if (isset($_GET['start_date']) || isset($_GET['end_date'])):?>
<p style="text-align:center;"><?php echo prettyDate($_GET['start_date'])." ".t("and")." ".prettyDate($_GET['end_date'])?></p>
<?php else :?>
<p style="text-align:center;"><?php echo t("As Of")?> <?php echo date("F d Y")?></p>
<?php endif;?>


<input type="hidden" name="action" id="action" value="rptMerchantSalesSummaryReport">
<input type="hidden" name="tbl" id="tbl" value="item">
<input type="hidden" name="server_side" id="server_side" value="1">	
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr> 
            <th width="3%"><?php echo Yii::t('default',"Merchant Name")?></th>            
            <th width="3%"><?php echo Yii::t('default',"Total Sales")?></th>                                    
            <th width="3%"><?php echo Yii::t('default',"Total Commission")?></th>
            <th width="3%"><?php echo Yii::t('default',"Merchant Earnings")?></th>            
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>