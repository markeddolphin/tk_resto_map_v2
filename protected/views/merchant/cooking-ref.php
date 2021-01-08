
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/CookingRef/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/CookingRef" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/CookingRef/Do/Sort" class="uk-button"><i class="fa fa-sort-alpha-asc"></i> <?php echo Yii::t("default","Sort")?></a>
</div>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="CookingRefList">
<input type="hidden" name="tbl" id="tbl" value="cooking_ref">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="cook_id">
<input type="hidden" name="slug" id="slug" value="CookingRef/Do/Add">
<input type="hidden" name="server_side" id="server_side" value="1">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
   <thead>
        <tr>
			 <th width="1%"><input type="checkbox" id="chk_all" class="chk_all"></th>
			 <th width="5%"><?php echo Yii::t('default',"Name")?></th>			 
			 <th width="5%"><?php echo Yii::t('default',"Date")?></th>
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>