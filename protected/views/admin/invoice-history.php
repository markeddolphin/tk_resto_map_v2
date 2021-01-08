<form id="forms" class="uk-form" method="POST" onsubmit="return false;">
<div class="template-modal template-modal-small">
 <div class="template-modal-header"> 
 <h4><?php echo t("Invoice History").": ". $invoice_number?></h4>
 </div>
 
 <div class="inner">
  <?php if (is_array($data) && count($data)>=1):?>
  <table class="uk-table">
    <thead>
      <tr>
       <th><?php echo t("Payment status")?></th>
       <th><?php echo t("Remarks")?></th>
       <th><?php echo t("Date")?></th>
      </tr>
    </thead>
    <tbody>
    <?php foreach ($data as $val):?>
    <tr>
      <td><?php echo t($val['payment_status'])?></td>
      <td><?php echo $val['remarks']?></td>
      <td><?php 
      echo FunctionsV3::prettyDate($val['date_created'])." ".FunctionsV3::prettyTime($val['date_created']);
      ?></td>
    </tr>
    <?php endforeach;?>
    </tbody>
  </table>
  <?php else :?>
  <p><?php echo t("No result")?></p>
  <?php endif;?>
 
 </div> <!--inner-->
 
 
 </div> <!--inner-->
</div><!--template-modal-->
</form>
