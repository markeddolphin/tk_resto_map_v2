
<h3><?php echo t("Fax")." ".t("Purchase Credit Transactions")?></h3>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="faxPurchaseTransaction">
<input type="hidden" name="tbl" id="tbl" value="fax_package_trans">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="id">
<input type="hidden" name="slug" id="slug" value="faxpurchasetrans/Do/Add">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
  <caption>Merchant List</caption>
   <thead>
        <tr>
            <th><?php echo Yii::t('default',"Ref#")?></th>
            <th><?php echo Yii::t('default',"Payment Type")?></th>
            <th><?php echo Yii::t('default',"Package Name")?></th>            
            <th><?php echo Yii::t('default',"Package Price")?></th>
            <th><?php echo Yii::t('default',"Package Credits")?></th>
            <th><?php echo Yii::t('default',"Status")?></th>                        
            <th><?php echo Yii::t('default',"Date")?></th>
        </tr>
    </thead>
    <tbody> 
    </tbody>
</table>
<div class="clear"></div>
</form>