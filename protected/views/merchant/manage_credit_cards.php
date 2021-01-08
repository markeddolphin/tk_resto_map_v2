
<div class="uk-width-1">
<a href="<?php echo Yii::app()->createUrl('/merchant/manage_credit_cards',array('do'=>"add"))?>" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->createUrl('/merchant/manage_credit_cards')?>" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="merchantCreditCardList">
<input type="hidden" name="tbl" id="tbl" value="merchant_cc">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="mt_id">
<input type="hidden" name="server_side" id="server_side" value="1">
<?php echo CHtml::hiddenField('merchant_id',$mtid)?>
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed"> 
   <thead>
        <tr>
            <th width="11%"><?php echo t("ID")?>            
            <th width="15%"><?php echo t("Name")?></th>             
            <th width="15%"><?php echo t("Card number")?></th>
            <th width="15%"><?php echo t("Date Created")?></th>            
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>