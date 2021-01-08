<h2><?php echo t("Pages")?></h2>

<div class="row action_top_wrap desktop button_small_wrap">   

<a href="<?php echo Yii::app()->createUrl("/merchant/singlemerchant/settings_add");?>" class="btn btn-primary"  >
 <?php echo t("Add New")?>
</a> 

<button type="button" class="btn btn-raised refresh_datatables" onclick="table_reload();"  >
<?php echo t("Refresh")?> 
</button>
   
</div> <!--action_top_wrap-->


<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="singlemerchantPage">
<input type="hidden" name="tbl" id="tbl" value="singleapp_pages">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="page_id">
<input type="hidden" name="server_side"  id="server_side" value="1">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr>
            <th width="5%"><?php echo t("ID")?></th>
		    <th><?php echo t("Title")?></th>
		    <th><?php echo t("Content")?></th>
		    <th ><?php echo t("Icon")?></th>
		    <th><?php echo t("HTML format")?></th>    
		    <th><?php echo t("Sequence")?></th>
		    <th><?php echo t("Date")?></th>
		    <th><?php echo t("Actions")?></th>
        </tr>
    </thead>
    <tbody> 
    </tbody>
</table>
<div class="clear"></div>
</form>
