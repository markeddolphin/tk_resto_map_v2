
<div class="uk-width-1">
<a href="<?php echo Yii::app()->createUrl('/merchant/invoice')?>" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="MerchantInvoiceList">
<input type="hidden" name="tbl" id="tbl" value="invoice">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="server_side" id="server_side" value="1">
<?php echo CHtml::hiddenField('merchant_id',$merchant_id)?>
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed"> 
   <thead>
        <tr>
            <th width="11%"><?php echo t("Invoice number")?>            
            <th width="15%"><?php echo t("Merchant")?></th>             
            <th width="15%"><?php echo t("Invoice terms")?></th>             
            <th width="20%"><?php echo t("Period")?></th> 
            <th width="12%"><?php echo t("Total")?></th> 
            <th width="12%"><?php echo t("Status")?></th> 
            <th width="12%"><?php echo t("PDF")?></th> 
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>