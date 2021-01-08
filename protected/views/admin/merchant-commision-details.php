
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/merchantcommission" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","Back")?></a>
</div>

<form id="frm_table_list" method="POST" class="report uk-form uk-form-horizontal" >

<input type="hidden" name="action" id="action" value="merchantCommissionDetails">
<input type="hidden" name="tbl" id="tbl" value="merchant">

<?php echo CHtml::hiddenField('start_date',isset($_GET['start_date'])?$_GET['start_date']:"")?>
<?php echo CHtml::hiddenField('end_date',isset($_GET['end_date'])?$_GET['end_date']:"")?>

<?php echo CHtml::hiddenField('mtid',
isset($_GET['mtid'])?$_GET['mtid']:''
);?>
<?php echo CHtml::hiddenField('where',
isset($_GET['where'])?$_GET['where']:''
);?>
<?php 
echo CHtml::hiddenField('and',
isset($_GET['and'])?$_GET['and']:''
);

echo CHtml::hiddenField('payment_type_in',
isset($_GET['payment_type_in'])?$_GET['payment_type_in']:''
);
echo CHtml::hiddenField('payment_type_not_in',
isset($_GET['payment_type_not_in'])?$_GET['payment_type_not_in']:''
);
?>

<?php 
/*$commission=0;
if (isset($_GET['mtid'])){
	echo $_GET['mtid'];	
	if ( $commission=Yii::app()->functions->getMerchantCommission($_GET['mtid'])){		
		$commission=standardPrettyFormat($commission);
	}
}*/
?>

<div style="height:20px;"></div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Start Date")?></label>
  <?php echo CHtml::textField('start_date1',''  
  ,array(
  'class'=>'uk-form-width-large j_date',
  'data-id'=>'start_date'
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","End Date")?></label>
  <?php echo CHtml::textField('end_date1',''  
  ,array(
  'class'=>'uk-form-width-large j_date' ,
  'data-id'=>'end_date'
  ))?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label">&nbsp;</label>
  <input type="button" class="uk-button uk-form-width-medium uk-button-success" value="<?php echo t("Search")?>" onclick="sales_summary_reload();">  
  <a href="javascript:;" rel="rptmerchantcommissiondetails" class="export_btn uk-button"><?php echo t("Export")?></a>
</div>  

<div style="height:20px;"></div>


<h3><?php echo t("Merchant Name")?>: <span  class="merchant_name"></span></h3>

<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
   <thead>
        <tr>            
            <th width="5%"><?php echo Yii::t("default","Reference #")?></th>
            <th width="5%"><?php echo Yii::t("default","Payment Type")?></th>                        
            <th width="5%"><?php echo Yii::t("default","Total Price")?></th>                        
            <th width="5%"><?php echo Yii::t("default","Commission (%)")?></th>            
            <th width="5%"><?php echo Yii::t("default","Commission price")?></th>  
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