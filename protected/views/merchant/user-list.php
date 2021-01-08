
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/user/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/user" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="MerchantUserList">
<input type="hidden" name="tbl" id="tbl" value="merchant_user">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="merchant_user_id">
<input type="hidden" name="slug" id="slug" value="user/Do/Add">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
  <!--<caption>Merchant List</caption>-->
   <thead>
        <tr>
			<th width="5%"><input type="checkbox" id="chk_all" class="chk_all"></th>
			 <th width="10%"><?php echo Yii::t('default',"Name")?></th>
			 <!--<th><?php echo Yii::t('default',"Access Role")?></th>				 -->
			 <th width="5%"><?php echo Yii::t('default',"Status")?></th>
			 <th width="5%"><?php echo Yii::t('default',"Last Login")?></th>			 
			 <th width="5%"><?php echo Yii::t('default',"Date Created")?></th>
        </tr>
    </thead>
    <tbody>   
    </tbody>
</table>
<div class="clear"></div>
</form>