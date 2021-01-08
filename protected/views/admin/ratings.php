
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/Ratings/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/Ratings" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<p class="uk-text-muted"><?php echo Yii::t("default","Note: Maximum rating is 5")?></p>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="ratingList">
<input type="hidden" name="tbl" id="tbl" value="rating_meaning">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="id">
<input type="hidden" name="slug" id="slug" value="Ratings">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
  <!--<caption>Merchant List</caption>-->
   <thead>
        <tr>
            <th width="6%"><?php echo Yii::t("default","Range 1")?></th>
            <th width="6%"><?php echo Yii::t("default","Range 2")?></th>
            <th width="6%"><?php echo Yii::t("default","Ratings")?></th>            
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>