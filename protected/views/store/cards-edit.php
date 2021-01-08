<div class="page-right-sidebar">
  <div class="main">
  
  <form class="uk-form uk-form-horizontal forms" id="forms" onsubmit="return false;">
  <?php echo CHtml::hiddenField('action','updateCreditCard')?>
  <?php echo CHtml::hiddenField('cc_id',$_GET['id'])?>
  <?php echo CHtml::hiddenField('currentController','store')?>

  <h2><?php echo Yii::t("default","Update Cards")?></h2>
  
  <a class="uk-button right" href="<?php echo Yii::app()->request->baseUrl; ?>/store/Cards/"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
  <div class="clear"></div>
  
  <div class="spacer"></div>
      
  <?php if ($data=Yii::app()->functions->getCreditCardInfo($_GET['id']) ):?>  
  
    <div class="uk-form-row">                  
          <?php echo CHtml::textField('card_name',$data['card_name'],array(
           'class'=>'uk-width-1-1',
           'placeholder'=>Yii::t("default","Card name"),
           'data-validation'=>"required"  
          ))?>
       </div>             
       
       <div class="uk-form-row">                  
          <?php echo CHtml::textField('credit_card_number',$data['credit_card_number'],array(
           'class'=>'uk-width-1-1 numeric_only',
           'placeholder'=>Yii::t("default","Credit Card Number"),
           'data-validation'=>"required",
           'maxlength'=>20
          ))?>
       </div>             
       
       <div class="uk-form-row">                  
          <?php echo CHtml::dropDownList('expiration_month',$data['expiration_month'],
          Yii::app()->functions->ccExpirationMonth()
          ,array(
           'class'=>'uk-width-1-1',
           'placeholder'=>Yii::t("default","Exp. month"),
           'data-validation'=>"required"  
          ))?>
       </div>             
       
       <div class="uk-form-row">                  
          <?php echo CHtml::dropDownList('expiration_yr',$data['expiration_yr'],
          Yii::app()->functions->ccExpirationYear()
          ,array(
           'class'=>'uk-width-1-1',
           'placeholder'=>Yii::t("default","Exp. year"),
           'data-validation'=>"required"  
          ))?>
       </div>             
       
       <div class="uk-form-row">                  
          <?php echo CHtml::textField('cvv',$data['cvv'],array(
           'class'=>'uk-width-1-1',
           'placeholder'=>Yii::t("default","CVV"),
           'data-validation'=>"required",
           'maxlength'=>4
          ))?>
       </div>             
       
       <div class="uk-form-row">                  
          <?php echo CHtml::textField('billing_address',$data['billing_address'],array(
           'class'=>'uk-width-1-1',
           'placeholder'=>Yii::t("default","Billing Address") ,
           'data-validation'=>"required"  
          ))?>
       </div>             
       
       <div class="uk-form-row">   
          <input type="submit" value="<?php echo Yii::t("default","Update Cards")?>" class="uk-button uk-button-success uk-width-1-4">
       </div>
     </div> 
  
  <?php else :?>
  <p class="uk-text-danger"><?php echo Yii::t("default","Card not available")?></p>
  <?php endif;?>  
  </form>
    
  </div> <!--main-->
</div> <!--page-right-sidebar--> 