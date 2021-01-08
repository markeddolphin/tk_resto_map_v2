<?php
$merchant_id=Yii::app()->functions->getMerchantID();
$facebook_page=Yii::app()->functions->getOption("facebook_page",$merchant_id);
$twitter_page=Yii::app()->functions->getOption("twitter_page",$merchant_id);
$google_page=Yii::app()->functions->getOption("google_page",$merchant_id);
?>

<form class="uk-form uk-form-horizontal forms" id="forms">
<?php echo CHtml::hiddenField('action','socialSettings')?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Facebook Page")?></label>
  <?php   
  echo CHtml::textField('facebook_page',$facebook_page,array(
       'class'=>'uk-form-width-large',
  ))
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Twitter Page")?></label>
  <?php   
  echo CHtml::textField('twitter_page',$twitter_page,array(
       'class'=>'uk-form-width-large',
  ))
  ?>
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Google Page")?></label>
  <?php   
  echo CHtml::textField('google_page',$google_page,array(
       'class'=>'uk-form-width-large',
  ))
  ?>
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>