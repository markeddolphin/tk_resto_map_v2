
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/customPage/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/customPage/Do/AddCustom" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New Custom Link")?></a>


<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/customPage" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/customPage/Do/Assign" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","Assign Page")?></a>
</div>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="customPageList">
<input type="hidden" name="tbl" id="tbl" value="custom_page">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="id">
<input type="hidden" name="slug" id="slug" value="customPage">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr>
            <th width="2%"><?php echo Yii::t("default","ID")?></th>
            <th width="4%"><?php echo Yii::t("default","Slug")?></th>
            <th width="7%"><?php echo Yii::t("default","Page Title")?></th>
            <th width="7%"><?php echo Yii::t("default","Content")?></th>
            <th width="3%"><?php echo Yii::t("default","Date Created")?></th>            
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>