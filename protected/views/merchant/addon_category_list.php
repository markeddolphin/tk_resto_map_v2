
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/AddOnCategory/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/AddOnCategory" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/AddOnCategory/Do/Sort" class="uk-button"><i class="fa fa-sort-alpha-asc"></i> Sort</a>
</div>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="AddOnCategoryList">
<input type="hidden" name="tbl" id="tbl" value="subcategory">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="subcat_id">
<input type="hidden" name="slug" id="slug" value="AddOnCategory/Do/Add">
<input type="hidden" name="server_side" id="server_side" value="1">		
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr>
			<th><input type="checkbox" id="chk_all" class="chk_all"></th>
            <th><?php echo Yii::t('default',"AddOn Name")?></th>
            <th><?php echo Yii::t('default',"Description")?></th>
            <th><?php echo Yii::t('default',"Date")?></th>
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>