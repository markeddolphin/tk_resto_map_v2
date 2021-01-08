
<form class="uk-form uk-form-horizontal forms" id="forms">

<h4><?php echo t("Tick when category is available")?></h4>



<div class="uk-form-row">
   <label class="uk-form-label"><?php echo t("Enabled scheduler")?></label>
  <?php  
  echo CHtml::checkBox('enabled_category_sked',
  getOption($merchant_id,'enabled_category_sked')
  ,array(
    'value'=>1
  ));
  ?>  
</div>    

<div class="uk-form-row">
   <label class="uk-form-label"><?php echo t("Check/Uncheck All")?></label>
  <?php  
  echo CHtml::checkBox('enabled_sked_all',
  false
  ,array(
    'class'=>"enabled_sked_all",
    'value'=>1
  ));
  ?>  
</div>    

<br/>

<?php echo CHtml::hiddenField('action','saveCategorySked')?>
<table id="sked_table"  class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
<thead>
 <tr>
  <th style="text-align: left;"><?php echo t("Category name")?></th>
  <th style="text-align: left;"><?php echo t("Monday")?></th>
  <th style="text-align: left;"><?php echo t("Tuesday")?></th>
  <th style="text-align: left;"><?php echo t("Wednesday")?></th>
  <th style="text-align: left;"><?php echo t("Thursday")?></th>
  <th style="text-align: left;"><?php echo t("Friday")?></th>
  <th style="text-align: left;"><?php echo t("Saturday")?></th>
  <th style="text-align: left;"><?php echo t("Sunday")?></th>
 </tr>
</thead>
<tbody>
 <?php if(is_array($data) && count($data)>=1):?>
 <?php foreach ($data as $val): ?>
  <tr>
   <td><?php echo clearString($val['category_name'])?></td>
   <td><?php echo CHtml::checkBox('category[monday]['.$val['cat_id'].']', $val['monday']==1?true:false  ,array('value'=>"1",'class'=>"cat_sked"))?></td>
   <td><?php echo CHtml::checkBox('category[tuesday]['.$val['cat_id'].']', $val['tuesday']==1?true:false  ,array('value'=>"1",'class'=>"cat_sked"))?></td>
   <td><?php echo CHtml::checkBox('category[wednesday]['.$val['cat_id'].']', $val['wednesday']==1?true:false ,array('value'=>"1",'class'=>"cat_sked"))?></td>
   <td><?php echo CHtml::checkBox('category[thursday]['.$val['cat_id'].']', $val['thursday']==1?true:false ,array('value'=>"1",'class'=>"cat_sked"))?></td>
   <td><?php echo CHtml::checkBox('category[friday]['.$val['cat_id'].']', $val['friday']==1?true:false ,array('value'=>"1",'class'=>"cat_sked"))?></td>
   <td><?php echo CHtml::checkBox('category[saturday]['.$val['cat_id'].']', $val['saturday']==1?true:false ,array('value'=>"1",'class'=>"cat_sked"))?></td>
   <td><?php echo CHtml::checkBox('category[sunday]['.$val['cat_id'].']', $val['sunday']==1?true:false ,array('value'=>"1",'class'=>"cat_sked"))?></td>   
  </tr>
 <?php endforeach;?>
 <?php endif;?>
</tbody>
</table>

<div class="uk-form-row" style="margin-top:20px;">
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>