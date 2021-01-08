
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/packagesAdd" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/packages" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/packages/Do/Sort" class="uk-button"><i class="fa fa-sort-alpha-asc"></i> <?php echo Yii::t("default","Sort")?></a>
</div>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="packagesList">
<input type="hidden" name="tbl" id="tbl" value="packages">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="package_id">
<input type="hidden" name="slug" id="slug" value="packagesAdd">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
  <!--<caption>Merchant List</caption>-->
   <thead>
        <tr>
            <th width="3%"><?php echo Yii::t("default","ID")?></th>
            <th width="7%"><?php echo Yii::t("default","Title")?></th>
            <th width="7%"><?php echo Yii::t("default","Description")?></th>
            <th width="5%"><?php echo Yii::t("default","Price")?></th>
            <th width="5%"><?php echo Yii::t("default","Promo Price")?></th>
            <th width="5%"><?php echo Yii::t("default","Expiration time(day)")?></th>
            <th width="5%"><?php echo Yii::t("default","Usage")?></th>
            <th width="5%"><?php echo Yii::t("default","Limit merchant by sell")?></th>
            <th width="5%"><?php echo Yii::t("default","Status")?></th>            
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>