<div class="location-fee-wrap">
<?php if (is_array($data) && count($data)>=1):?>
<h4><?php echo t("Select Your location on the list")?></h4>

<div  class="inner">
<table class="table table-condensed table-hover">
  <thead>
   <tr>     
     <th width="10%"><?php echo t("State/Region")?></th>
     <th width="10%"><?php echo t("City")?></th>
     <th width="10%"><?php echo t("Distric/Area/neighborhood")?></th>
     <th width="10%"><?php echo t("Postal Code/Zip Code")?></th>
     <th width="5%"><?php echo t("Fee")?></th>
     <th width="5%"></th>
   </tr>
  </thead>
  <tbody>
  <?php foreach ($data as $val):?>
  <tr>
    <td><?php echo $val['state_name']?></td>
    <td><?php echo $val['city_name']?></td>
    <td><?php echo $val['area_name']?></td>
    <td><?php echo $val['postal_code']?></td>
    <td><?php echo FunctionsV3::prettyPrice($val['fee'])?></td>
    <td>
    <?php echo CHtml::radioButton('location_fee',false,array(
     'value'=>$val['rate_id']
    ))?>
    </td>
  </tr>
  <?php endforeach;?>
  </tbody>
</table>

<script type="text/javascript">
jQuery(document).ready(function() {		
	 $( document ).on( "click", "#location_fee", function() {	 	
	 	callAjax("SetLocationFee", "rate_id="+$(this).val() );
	 });
});
</script>

<?php else :?>
<p><?php echo t("Delivery fee table is not available for this merchant")?></p>
<?php endif;?>

<a class="btn btn-primary" href="javascript:close_fb();"><?php echo t("Close")?></a>
</div>


</div>