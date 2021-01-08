
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/zipcode/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/zipcode" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
<!--<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/zipcode/Do/Sort" class="uk-button"><i class="fa fa-sort-alpha-asc"></i> <?php echo Yii::t("default","Sort")?></a>-->
</div>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="ZipCodeList">
<input type="hidden" name="tbl" id="tbl" value="zipcode">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="zipcode_id">
<input type="hidden" name="slug" id="slug" value="zipcode">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr>
            <th width="3%"><?php echo t("ID")?></th>
            <th width="3%"><?php echo t("post code")?></th>
            <th width="5%"><?php echo t("Country")?></th>
            <th width="5%"><?php echo t("Street name")?></th>
            <th width="5%"><?php echo t("City")?></th>
            <th width="5%"><?php echo t("Area")?></th>
            <th width="5%"><?php echo t("Date")?></th>
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>