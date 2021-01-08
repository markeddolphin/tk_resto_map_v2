<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/sponsoredMerchantList/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/sponsoredMerchantList" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<?php 
if (isset($_GET['id'])){
	if (!$data=Yii::app()->functions->getMerchant($_GET['id'])){
		echo "<div class=\"uk-alert uk-alert-danger\">".
		Yii::t("default","Sorry but we cannot find what your are looking for.")."</div>";
		return ;
	}
}
?>                                   

<div class="spacer"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','sponsoreMerchantAdd')?>
<?php echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");?>
<?php if (!isset($_GET['id'])):?>
<?php echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/admin/sponsoredMerchantList/")?>
<?php endif;?>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Merchant")?></label>
  <?php echo CHtml::dropDownList('merchant_id',
  isset($data['merchant_id'])?$data['merchant_id']:''
  ,(array)Yii::app()->functions->merchantList(),
  array('class'=>"uk-form-width-large"))?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Expiration Date")?></label>
  <?php echo CHtml::hiddenField('expiration',isset($data['sponsored_expiration'])?$data['sponsored_expiration']:"")?>
  <?php echo CHtml::textField('expiration1',
  isset($data['sponsored_expiration'])?$data['sponsored_expiration']:""
  ,array(
  'class'=>'uk-form-width-medium j_date',
  'data-validation'=>"required",
  'data-id'=>"expiration"
  ))?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>