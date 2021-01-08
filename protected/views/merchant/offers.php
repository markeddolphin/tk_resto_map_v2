
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/offers/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/offers" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="merchantOffers">
<input type="hidden" name="tbl" id="tbl" value="offers">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="offers_id">
<input type="hidden" name="slug" id="slug" value="offers">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr>			
			 <th width="5%"><?php echo Yii::t('default',"ID")?></th>
			 <th width="10%"><?php echo Yii::t('default',"Offer Percentage")?></th>				 
			 <th width="5%"><?php echo Yii::t('default',"Orders Over")?></th>			 
			 <th width="5%"><?php echo Yii::t('default',"Valid From")?></th>
			 <th width="5%"><?php echo Yii::t('default',"Valid To")?></th>
			 <th width="5%"><?php echo Yii::t('default',"Applicable")?></th>
			 <th width="5%"><?php echo Yii::t('default',"Date Created")?></th>
        </tr>
    </thead>
    <tbody>   
    </tbody>
</table>
<div class="clear"></div>
</form>