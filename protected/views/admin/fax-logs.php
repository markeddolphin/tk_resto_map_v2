
<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="adminFaxTransactionLogs">
<input type="hidden" name="tbl" id="tbl" value="fax_broadcast">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="id">
<input type="hidden" name="slug" id="slug" value="faxstats/Do/Add">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
  <caption>Merchant List</caption>
   <thead>
        <tr>            
            <th width="3%"><?php echo Yii::t('default',"ID")?></th>
            <th width="3%"><?php echo Yii::t('default',"Merchant Name")?></th>
            <th width="3%"><?php echo Yii::t('default',"Job ID")?></th>
            <th width="3%"><?php echo Yii::t('default',"Fax URL")?></th>
            <th width="3%"><?php echo Yii::t('default',"Status")?></th>            
            <th width="3%"><?php echo Yii::t('default',"API Response")?></th>            
            <th width="3%"><?php echo Yii::t('default',"Date")?></th>
        </tr>
    </thead>
    <tbody> 
    </tbody>
</table>
<div class="clear"></div>
</form>