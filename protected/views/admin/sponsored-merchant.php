
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/sponsoredMerchantList/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/sponsoredMerchantList" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<P class="uk-badge"><?php echo Yii::t("default","List of merchant that will put on top of the search.")?></P>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="sponsoredMerchantList">
<input type="hidden" name="tbl" id="tbl" value="merchantSponsoredList">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="merchant_id">
<input type="hidden" name="slug" id="slug" value="sponsoredMerchantList">
<input type="hidden" name="server_side" id="server_side" value="1">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
  <caption><?php echo Yii::t("default","Merchant List")?></caption>
   <thead>
        <tr>
            <th width="3%"><?php echo Yii::t("default","MerchantID")?></th>
            <th width="7%"><?php echo Yii::t("default","Merchant Name")?></th>
            <th width="3%"><?php echo Yii::t("default","Expiration Date")?></th>            
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>
<div class="clear"></div>
</form>