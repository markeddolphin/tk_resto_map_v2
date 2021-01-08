<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/ManageCurrency/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/admin/ManageCurrency" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>

<?php 
if (isset($_GET['id'])){
	if (!$data=Yii::app()->functions->getCurrencyDetailsByID($_GET['id'])){
		echo "<div class=\"uk-alert uk-alert-danger\">".
		Yii::t("default","Sorry but we cannot find what your are looking for.")."</div>";
		return ;
	}
}
?>                                   

<div class="spacer"></div>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','addCurrency')?>
<?php echo CHtml::hiddenField('id',isset($_GET['id'])?$_GET['id']:"");?>
<?php if (!isset($_GET['id'])):?>
<?php echo CHtml::hiddenField("redirect",Yii::app()->request->baseUrl."/admin/ManageCurrency/")?>
<?php endif;?>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Currency Code")?></label>
  <?php 
  echo CHtml::textField('currency_code',
  isset($data['currency_code'])?$data['currency_code']:""
  ,array('class'=>"uk-form-width-large",
    'data-validation'=>"required",
    'maxlength'=>3
    ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Currency Symbol")?></label>
  <?php 
  echo CHtml::textField('currency_symbol',
  isset($data['currency_symbol'])?$data['currency_symbol']:"",
  array('class'=>"uk-form-width-large",'data-validation'=>"required"))
  ?>
</div>
<p class="uk-text-muted"><?php echo Yii::t("default","To get symbol refer to")?> <a target="_blank" href="http://www.xe.com/symbols.php">http://www.xe.com/symbols.php</a></p>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>