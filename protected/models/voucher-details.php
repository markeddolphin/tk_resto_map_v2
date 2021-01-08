<?php $func=new FunctionsK();?>
<?php if ( $res=$func->getUsedVoucher($_GET['voucher_name'])): //dump($res);?>

<table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
<thead>
<tr>
<th><?php echo Yii::t('default',"Voucher name")?></th> 
<th><?php echo Yii::t('default',"Customer Name")?></th> 
<th><?php echo Yii::t('default',"Transaction Type")?></th>
<th><?php echo Yii::t('default',"Order No.")?></th>
<!--<th><?php echo Yii::t('default',"Total Discount in amount")?></th>-->
<th><?php echo Yii::t('default',"Date Used")?></th>
</tr>
</thead>

<tbody>
<?php foreach ($res as $val):?>
<tr>
<td width="5%"><?php echo $val['voucher_code']?></td>
<td width="5%"><?php echo $val['client_name']?></td>
<td width="5%"><?php echo t($val['trans_type'])?></td>
<td width="5%">
  <a href="javascript:;" class="view-receipt" data-id="<?php echo $val['order_id']?>"><?php echo $val['order_id']?></a>
</td>
<!--<td width="5%"><?php echo displayPrice(getCurrencyCode(),standardPrettyFormat($val['voucher_amount']))?></td>-->
<td width="5%"><?php echo FormatDateTime($val['date_created']);?></td>
</tr>
<?php endforeach;?>
</tbody>
</table>

      
<?php else :?>
<p class="uk-text-danger"><?php echo t("No results")?></p>
<?php endif;?>
<?php
die();