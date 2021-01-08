
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/smstransaction/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/smstransaction" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="smsTransactionList">
<input type="hidden" name="tbl" id="tbl" value="sms_package_trans">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="id">
<input type="hidden" name="slug" id="slug" value="smstransaction">
<input type="hidden" name="server_side"  id="server_side" value="1">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
  <caption><?php echo Yii::t("default","Merchant List")?></caption>
   <thead>
        <tr>
            <th width="3%"><?php echo Yii::t("default","ID")?></th>
            <th width="7%"><?php echo Yii::t("default","Merchant Name")?></th>
            <th width="7%"><?php echo Yii::t("default","SMS Package")?></th>
            <th width="7%"><?php echo Yii::t("default","Price")?></th>
            <th width="7%"><?php echo Yii::t("default","Credits")?></th>
            <th width="7%"><?php echo Yii::t("default","Payment Type")?></th>
            <th width="5%"><?php echo Yii::t("default","Date Created")?></th>            
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>