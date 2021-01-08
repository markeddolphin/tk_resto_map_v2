
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/tablebooking/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/tablebooking" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/tablebooking/Do/settings" class="uk-button"><i class="fa fa-cog"></i> <?php echo Yii::t("default","Settings")?></a>
</div>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="tableBookingList">
<input type="hidden" name="tbl" id="tbl" value="bookingtable">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="booking_id">
<input type="hidden" name="slug" id="slug" value="tablebooking/Do/Add">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr>
            <th width="1%"><?php Yii::t("default","BookingID")?></th>
            <th width="7%"><?php echo Yii::t('default',"Guest Name")?></th>
            <th width="5%"><?php echo Yii::t('default',"Date Booking")?></th>
            <th width="4%"align="center"><?php echo Yii::t('default',"No. Of guest")?></th>            
            <th width="5%"><?php echo Yii::t('default',"Mobile")?></th>
            <th width="5%"><?php echo Yii::t('default',"Notes")?></th>
            <th width="5%"><?php echo Yii::t('default',"Date")?></th>
        </tr>
    </thead>
    <tbody> 
    </tbody>
</table>
<div class="clear"></div>
</form>