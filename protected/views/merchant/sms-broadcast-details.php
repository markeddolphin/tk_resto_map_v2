
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/smsBroadcast" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","Back")?></a>
</div>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="smsBroadcastListDetails">
<input type="hidden" name="tbl" id="tbl" value="sms_broadcast_details">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="id">
<input type="hidden" name="slug" id="slug" value="smsBroadcast/Do/view">
<input type="hidden" name="bid"  id="bid" value="<?php echo isset($_GET['bid'])?$_GET['bid']:'';?>">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
  <!--<caption>Merchant List</caption>-->
   <thead>
        <tr>
			<th width="2%"><input type="checkbox" id="chk_all" class="chk_all"></th>
			 <th width="3%"><?php echo Yii::t('default',"ID")?></th>
			 <th width="8%"><?php echo Yii::t('default',"Name")?></th>			 
			 <th width="5%"><?php echo Yii::t('default',"Mobile")?></th>
			 <th width="5%"><?php echo Yii::t('default',"SMS Message")?></th>
			 <th width="5%"><?php echo Yii::t('default',"Status")?></th>
			 <th width="5%"><?php echo Yii::t('default',"Date Created")?></th>	 
			 <th width="5%"><?php echo Yii::t('default',"Date Process")?></th>	 
        </tr>
    </thead>
    <tbody>   
    </tbody>
</table>
<div class="clear"></div>
</form>