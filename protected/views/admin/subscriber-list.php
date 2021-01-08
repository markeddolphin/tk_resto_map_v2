
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/subscriberlist" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>

<a href="javascript:;" rel="rptSubriberList" class="export_btn uk-button"><?php echo t("Export")?></a>

</div>

<div class="spacer"></div>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="subscriberList">
<input type="hidden" name="tbl" id="tbl" value="newsletter">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="id">
<input type="hidden" name="slug" id="slug" value="subscriberlist">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr>
            <th width="2%"><?php echo Yii::t("default","ID")?></th>
            <th width="4%"><?php echo Yii::t("default","Email address")?></th>                        
            <th width="3%"><?php echo Yii::t("default","Date Created")?></th>            
            <th width="4%"><?php echo Yii::t("default","I.P Address")?></th>            
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>