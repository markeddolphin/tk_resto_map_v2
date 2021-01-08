

<div class="box-grey rounded section-credit-card" style="margin-top:0;">

<div class="bottom10 top10">
<a class="green-button inline rounded" href="<?php echo Yii::app()->createUrl('/store/profile',array(
'tab'=>4,
'do'=>"add"
))?>">
<?php echo t("Add New")?>
</a>
</div>

<form method="POST" id="frm-otable" class="frm-otable"> 
<?php echo CHtml::hiddenField('otable_action','ClientCCList')?> 
<?php echo CHtml::hiddenField('tbl','client_cc')?>
  
<table class="otable table table-striped">
 <thead>
  <tr>
   <th><?php echo Yii::t("default","Card name")?></th>
   <th><?php echo Yii::t("default","Card number")?></th>
   <th><?php echo Yii::t("default","Expiration")?></th>
  </tr>
 </thead>
</table>

</form>
<div class="clear"></div>

</div>