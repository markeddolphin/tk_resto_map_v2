<?php 
$counrty_list=require_once 'CountryCode.php';
?>

<form class="uk-form uk-form-horizontal forms" id="forms" style="padding:10px;min-width:400px;">

<h3><?php echo t("Bank Information")?></h3>

<?php if ( $res=Yii::app()->functions->getWithdrawalInformation($this->data['id'])):?>

<?php if ( $res['bank_type']=="au"):?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","BSB")?><span class="required">*</span></label>
  <?php echo $res['swift_code'];?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Account Number")?><span class="required">*</span></label>
  <?php
  echo $res['bank_account_number'];
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Account Name")?><span class="required">*</span></label>
  <?php
  echo $res['account_name'];
  ?>
</div>


<?php else :?>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Bank Account Holder's Name")?><span class="required">*</span></label>
  <?php echo $res['account_name'] ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Bank Account Number/IBAN")?><span class="required">*</span></label>
  <?php
  echo $res['bank_account_number'];
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","SWIFT Code")?><span class="required">*</span></label>
  <?php 
  echo $res['swift_code'];
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Bank Name in Full")?><span class="required">*</span></label>
  <?php 
  echo $res['bank_name'];
  ?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Bank Branch City")?></label>
  <?php echo $res['bank_branch']?>
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo Yii::t("default","Bank Branch Country")?><span class="required">*</span></label>
  <?php 
  echo $counrty_list[$res['bank_country']];
  ?>
</div>

<?php endif;?>

<?php else :?>
<?php echo t("Sorry but we cannot find your information.")?>
<?php endif;?>

</form>