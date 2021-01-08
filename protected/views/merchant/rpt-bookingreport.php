<form id="frm_table_list" method="GET" class="report uk-form uk-form-horizontal" >

<?php 
$order_stats=Yii::app()->functions->orderStatusList(false);    
?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Start Date")?></label>
  <?php echo CHtml::hiddenField('start_date',isset($_GET['start_date'])?$_GET['start_date']:'')?>
  <?php echo CHtml::textField('start_date1',
  isset($_GET['start_date'])?FormatDateTime($_GET['start_date'],false):''
  ,array(
  'class'=>'uk-form-width-large j_date' ,
  'data-id'=>'start_date',
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","End Date")?></label>
  <?php echo CHtml::hiddenField('end_date',isset($_GET['end_date'])?$_GET['end_date']:'')?>
  <?php echo CHtml::textField('end_date1',
  isset($_GET['end_date'])?FormatDateTime($_GET['end_date'],false):''
  ,array(
  'class'=>'uk-form-width-large j_date',
  'data-id'=>'end_date',
  ))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label">&nbsp;</label>
  <input type="submit" class="uk-button uk-form-width-medium uk-button-success" value="<?php echo t("Search")?>" >  
  <a href="javascript:;" rel="booking-summary-report" class="export_btn uk-button"><?php echo Yii::t("default","Export")?></a>
</div>  

<div style="height:20px;"></div>


<h3 style="text-align:center;"><?php echo t("Booking Summary Report")?></h3>
<?php if (isset($_GET['start_date']) || isset($_GET['end_date'])):?>
<p style="text-align:center;"><?php echo FormatDateTime($_GET['start_date'],false)." ".t("and")." ".FormatDateTime($_GET['end_date'],false)?></p>
<?php else :?>
<p style="text-align:center;"><?php echo t("As of")." ".FormatDateTime(date('F d Y'),false)?></p>
<?php endif;?>

<input type="hidden" name="action" id="action" value="bookingSummaryReport">
<input type="hidden" name="tbl" id="tbl" value="item">
<input type="hidden" name="server_side" id="server_side" value="1">	
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">  
   <thead>
        <tr>                         
           <th width="3%"><?php echo Yii::t('default',"Total Approved")?></th> 
           <th width="3%"><?php echo Yii::t('default',"Total Denied")?></th> 
           <th width="3%"><?php echo Yii::t('default',"Total Pending")?></th> 
        </tr>
    </thead>
    <tbody>    
    </tbody>
</table>
<div class="clear"></div>
</form>