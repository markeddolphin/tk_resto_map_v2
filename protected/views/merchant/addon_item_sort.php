<?php 
$merchant_id=Yii::app()->functions->getMerchantID();
$list=Yii::app()->functions->getAddonItemListByMerchant($merchant_id);
?>


<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/AddOnItem/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/AddOnItem" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/merchant/AddOnItem/Do/Sort" class="uk-button"><i class="fa fa-sort-alpha-asc"></i> <?php echo Yii::t("default","Sort")?></a>
</div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','sortItem')?>
<?php echo CHtml::hiddenField('table','subcategory_item')?>
<?php echo CHtml::hiddenField('whereid','sub_item_id')?>

<h3 class="uk=h3"><?php echo Yii::t("default","Sort")?></h3>
<p class="uk-text-muted"><?php echo Yii::t("default","Drag the item below to sort")?></p>
<?php if (is_array($list) && count($list)>=1):?>
   <ul class="uk-sortable" data-uk-sortable>
  <?php foreach ($list as $val):?>
   <li class="uk-panel uk-panel-box" style="list-style:none;margin-bottom:5px;">
    <?php echo CHtml::hiddenField('sort_field[]',$val['sub_item_id'])?>
    <i class="fa fa-arrows-alt"></i>
    <?php echo $val['sub_item_name']?>
   </li>
  <?php endforeach;?>
  </ul>
<?php else :?>
<p class=""><?php echo Yii::t("default","No results")?></p>
<?php endif;?>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>
</form>