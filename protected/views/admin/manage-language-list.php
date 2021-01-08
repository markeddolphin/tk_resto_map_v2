
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/ManageLanguage/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/ManageLanguage" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/ManageLanguage/Do/Settings" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","Settings")?></a>
</div>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="languageList">
<input type="hidden" name="tbl" id="tbl" value="languages">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="lang_id">
<input type="hidden" name="slug" id="slug" value="ManageLanguage">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
  <caption><?php echo Yii::t("default","Language List")?></caption>
   <thead>
        <tr>
            <th width="7%"><?php echo Yii::t("default","Country")?></th>
            <th width="7%"><?php echo Yii::t("default","Language")?></th>
            <th width="7%"><?php echo Yii::t("default","Date Created")?></th>            
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>