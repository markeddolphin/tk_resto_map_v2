<?php 
$FunctionsK=new FunctionsK();
$merchant_id=Yii::app()->functions->getMerchantID();
?>
<div class="earnings-wrap">

<div class="table">
  <ul>
  
  <li>
   <div class="rounded-box rounded">
     <p><?php echo t("Total Send Fax")?>:</p>
     <h3><?php echo $FunctionsK->faxGetTotalSend($merchant_id)?></h3>     
   </div>
  </li>
  
  <li>
   <div class="rounded-box rounded">
     <p><?php echo t("Total Successful")?>:</p>
     <h3><?php echo $FunctionsK->faxGetTotalSuccesful($merchant_id)?></h3>     
   </div>
  </li>
  
  
  <li>
   <div class="rounded-box rounded">
     <p><?php echo t("Total Failed")?>:</p>
     <h3><?php echo $FunctionsK->faxGetTotalFailed($merchant_id)?></h3>     
   </div>
  </li>
  
  </ul>
  <div class="clear"></div>
</div> <!--table-->

</div> <!--earnings-wrap-->



<h3><?php echo t("Fax Transaction List")?></h3>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="faxTransactionLogs">
<input type="hidden" name="tbl" id="tbl" value="fax_broadcast">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="id">
<input type="hidden" name="slug" id="slug" value="faxstats/Do/Add">
<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
  <caption>Merchant List</caption>
   <thead>
        <tr>
            <th><?php echo Yii::t('default',"ID")?></th>
            <th><?php echo Yii::t('default',"Job ID")?></th>
            <th><?php echo Yii::t('default',"Fax URL")?></th>
            <th><?php echo Yii::t('default',"Status")?></th>            
            <th><?php echo Yii::t('default',"API Response")?></th>            
            <th><?php echo Yii::t('default',"Date")?></th>
        </tr>
    </thead>
    <tbody> 
    </tbody>
</table>
<div class="clear"></div>
</form>