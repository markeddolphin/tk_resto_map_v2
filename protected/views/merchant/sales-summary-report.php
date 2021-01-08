<form id="frm_table_list" method="POST" class="report uk-form uk-form-horizontal" >

<?php 
$order_stats=Yii::app()->functions->orderStatusList(false);    
?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Start Date")?></label>
  <?php echo CHtml::hiddenField('start_date')?>
  <?php echo CHtml::textField('start_date1',''  
  ,array(
  'class'=>'uk-form-width-large j_date',
  'data-id'=>'start_date',
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","End Date")?></label>
  <?php echo CHtml::hiddenField('end_date')?>
  <?php echo CHtml::textField('end_date1',''  
  ,array(
  'class'=>'uk-form-width-large j_date',
  'data-id'=>'end_date',
  ))?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Order Status")?></label>
  <?php echo CHtml::dropDownList('stats_id[]',array(4),(array)$order_stats,array(
  'class'=>"chosen uk-form-width-large",
  'multiple'=>true
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label">&nbsp;</label>
  <input type="button" class="uk-button uk-form-width-medium uk-button-success" value="<?php echo t("Search")?>" onclick="sales_summary_reload();">  
  <a href="javascript:;" rel="sales-summary-report" class="export_btn uk-button"><?php echo Yii::t("default","Export")?></a>
</div>  

<div style="height:20px;"></div>

<input type="hidden" name="action" id="action" value="salesSummaryReport">
<input type="hidden" name="tbl" id="tbl" value="item">
<input type="hidden" name="server_side" id="server_side" value="1">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr>                         
            <th width="3%"><?php echo Yii::t('default',"Item ID")?></th>        
            <th width="3%"><?php echo Yii::t('default',"Item")?></th>            
            <th width="3%"><?php echo Yii::t('default',"Size")?></th>
            <th width="3%"><?php echo Yii::t('default',"Price")?></th>
            <th width="3%"><?php echo Yii::t('default',"Total Qty")?></th>
            <th width="3%"><?php echo Yii::t('default',"Total Amount")?></th>            
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>