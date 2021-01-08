
<div class="uk-width-1">
<!--<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/customPage/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>-->
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/customerlist" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>

<a class="export_btn uk-button" rel="rptCustomerList" href="javascript:;"><?php echo t("Export to Mailchimp mailing list")?></a>

</div>

<div class="spacer"></div>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="customerList">
<input type="hidden" name="tbl" id="tbl" value="client">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="client_id">
<input type="hidden" name="slug" id="slug" value="customerlist">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr>
            <th width="2%"><?php echo Yii::t("default","ID")?></th>
            <th width="4%"><?php echo Yii::t("default","Name")?></th>
            <th width="7%"><?php echo Yii::t("default","Email")?></th>
            <th width="7%"><?php echo Yii::t("default","Contact Number")?></th>
            <th width="7%"><?php echo Yii::t("default","Address")?></th>
            <th width="7%"><?php echo Yii::t("default","Registered")?></th>
            <th width="3%"><?php echo Yii::t("default","Date Created")?></th>            
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>