
<h3><?php echo t("Delivery Rates Table")?></h3>

<a href="javascript:;" class="uk-button uk-button-success add-new-rates">
<i class="fa fa-plus"></i>
<?php echo t("Add new")?>
</a>

<p class="uk-text-muted">
(<?php echo t("drag the list to sort")?>)
</p>

<table id="location_table_rates" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
<thead>
  <tr>
   <th width="6%"><?php echo t("Country")?></th>   
   <th width="10%"><?php echo t("State/Region")?></th>   
   <th width="10%"><?php echo t("City")?></th>
   <th width="10%"><?php echo t("Distric/Area/neighborhood")?></th>
   <th width="10%"><?php echo t("Postal Code/Zip Code")?></th>
   <th width="5%"><?php echo t("Fee")?></th>
  </tr>
</thead>
<tbody class="location_table_rates">
</tbody>
</table>