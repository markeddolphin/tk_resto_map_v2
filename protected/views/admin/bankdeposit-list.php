

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="BankDepositList">
<input type="hidden" name="tbl" id="tbl" value="bank_deposit">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="id">
<input type="hidden" name="slug" id="slug" value="bankdeposit">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed"> 
   <thead>
        <tr>
            <th width="3%"><?php echo Yii::t("default","ID")?></th>
            <th width="7%"><?php echo Yii::t("default","TransType")?></th>
            <th width="7%"><?php echo Yii::t("default","Merchant Name")?></th>
            <th width="7%"><?php echo Yii::t("default","Branch Code")?></th>
            <th width="7%"><?php echo Yii::t("default","Date")?></th>
            <th width="7%"><?php echo Yii::t("default","Time")?></th>
            <th width="7%"><?php echo Yii::t("default","Amount")?></th>
            <th width="7%"><?php echo Yii::t("default","Scan Bank deposit slip")?></th>
            <th width="5%"><?php echo Yii::t("default","Date Created")?></th>            
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>