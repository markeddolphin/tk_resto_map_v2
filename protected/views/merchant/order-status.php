<?php 
$merchant_id=Yii::app()->functions->getMerchantID();
$status_list=Yii::app()->functions->orderStatusList();
$default_order_status=Yii::app()->functions->getOption("default_order_status",$merchant_id);
?>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','orderStatusSettings')?>

<h3><?php echo Yii::t("default","Set Default Order Status:")?></h3>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Default Status")?></label>
  <?php 
  echo CHtml::dropDownList('default_order_status',$default_order_status  
  ,$status_list,array(
    'class'=>"uk-form-width-large"
  ))
  ?>
  <p class="uk-text-muted"><?php echo Yii::t("default","The default order status when the client place order on front end.")?></p>
</div>
  

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>


<div style="height:30px;"></div>

<?php if ( getOptionA('merchant_status_disabled')!=2):?>
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/orderStatus/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php 
echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/orderStatus" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="OrderStatusListMerchant">
<input type="hidden" name="tbl" id="tbl" value="order_status">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="stats_id">
<input type="hidden" name="slug" id="slug" value="orderStatus/Do/Add">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
  <!--<caption>Merchant List</caption>-->
   <thead>
        <tr>            
            <th width="5%"><?php echo Yii::t('default',"ID")?></th>
            <th width="4%"><?php echo Yii::t('default',"Status")?></th>            
            <th width="4%"><?php echo Yii::t('default',"Date")?></th>
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>
<?php endif;?>