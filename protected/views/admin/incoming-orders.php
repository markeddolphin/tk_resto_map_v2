
<h3><?php echo t("All Orders")?></h3>

<form id="frm_table_list" method="POST" class="report" >
<input type="hidden" name="action" id="action" value="AllOrders">
<input type="hidden" name="tbl" id="tbl" value="order">
<input type="hidden" name="server_side" id="server_side" value="1">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr>
            <th width="2%"><?php echo Yii::t('default',"Ref#")?></th>
            <th width="2%"><?php echo Yii::t('default',"Merchant Name")?></th>           
            <th width="6%"><?php echo Yii::t('default',"Name")?></th>
            <th width="3%"><?php echo Yii::t('default',"Item")?></th>            
            <th width="3%"><?php echo Yii::t('default',"TransType")?></th>
            <th width="3%"><?php echo Yii::t('default',"Payment Type")?></th>
            <th width="3%"><?php echo Yii::t('default',"Total")?></th>
            <th width="3%"><?php echo Yii::t('default',"Tax")?></th>
            <th width="3%"><?php echo Yii::t('default',"Total W/Tax")?></th>
            <th width="3%"><?php echo Yii::t('default',"Status")?></th>
            <th width="3%"><?php echo Yii::t('default',"Platform")?></th>
            <th width="3%"><?php echo Yii::t('default',"Date")?></th>            
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>