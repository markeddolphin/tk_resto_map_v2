<?php 
$selected='incoming';
if (isset($_GET['do'])){
	$selected=$_GET['do'];
}
?>
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/incomingwithdrawal" class="w-list incoming uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","Incoming withdrawal")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/incomingwithdrawal/?do=approved" class="w-list approved uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","Approved withdrawal")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/incomingwithdrawal/?do=paid" class="w-list paid uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","Paid withdrawal")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/incomingwithdrawal/?do=denied" class="w-list denied uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","Denied withdrawal")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/incomingwithdrawal/?do=failed" class="w-list failed uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","Failed withdrawal")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/incomingwithdrawal/?do=cancel" class="w-list cancel uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","Cancel withdrawal")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/incomingwithdrawal/?do=reversal" class="w-list reversal uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","Reversal withdrawal")?></a>

<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/incomingwithdrawal/?do=all" class="w-list all uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","All withdrawal")?></a>


</div>

<div class="spacer"></div>

<form id="frm_table_list" method="GET" class="uk-form uk-form-horizontal" >
<?php echo CHtml::hiddenField('start_date',isset($_GET['start_date'])?$_GET['start_date']:"")?>
<?php echo CHtml::hiddenField('end_date',isset($_GET['end_date'])?$_GET['end_date']:"")?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Merchant Name")?></label>
  <?php 
  echo CHtml::dropDownList('merchant_id',
  isset($_GET['merchant_id'])?$_GET['merchant_id']:''
  ,
  (array)Yii::app()->functions->merchantList3(true,true)
  ,array(
    'class'=>'uk-form-width-large'    
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Start Date")?></label>
  <?php echo CHtml::textField('start_date1',isset($_GET['start_date1'])?$_GET['start_date1']:''
  ,array(
  'class'=>'uk-form-width-large j_date' ,
  'data-id'=>'start_date'
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","End Date")?></label>
  <?php echo CHtml::textField('end_date1',isset($_GET['end_date1'])?$_GET['end_date1']:''
  ,array(
  'class'=>'uk-form-width-large j_date',
  'data-id'=>'end_date'
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label">&nbsp;</label>
  <input type="submit" class="uk-button uk-form-width-medium uk-button-success" value="<?php echo t("Search")?>">    
  <a  href="javascript:;" rel="rpt_incomingwithdrawal" class="export_btn uk-button"><?php echo t("Export")?></a>
</div>  

<div style="height:20px;"></div>

<input type="hidden" name="action" id="action" value="incomingWithdrawals">
<input type="hidden" name="tbl" id="tbl" value="withdrawal">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="withdrawal_id">
<input type="hidden" name="slug" id="slug" value="incomingWithdrawals">
<?php echo CHtml::hiddenField('w-list',$selected)?>
<?php echo CHtml::hiddenField('do',$selected)?>
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr>
            <th width="2%"><?php echo Yii::t("default","ID")?></th>
            <th width="5%"><?php echo Yii::t("default","Merchant Name")?></th>
            <th width="3%"><?php echo Yii::t("default","Payment Method")?></th>
            <th width="3%"><?php echo Yii::t("default","Amount")?></th>
            <th width="3%"><?php echo Yii::t("default","From Balance")?></th>
            <th width="3%"><?php echo Yii::t("default","Status")?></th>
            <th width="3%"><?php echo Yii::t("default","Date Of Request")?></th>
            <th width="3%"><?php echo Yii::t("default","Date to process")?></th>
            <th width="3%"><?php echo Yii::t("default","Action")?></th>
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>