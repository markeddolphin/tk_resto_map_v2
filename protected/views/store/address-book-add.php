<div class="page-right-sidebar">
  <div class="main">
  <div class="inner">
  
  <div class="uk-width-1">
<a href="<?php echo createUrl("store/addressbook/do/add")?>" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo createUrl("store/addressbook")?>" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>  
  
  <form class="uk-form uk-form-horizontal forms" id="forms" onsubmit="return false;">
  <?php echo CHtml::hiddenField('action','addAddressBook')?>
  <?php echo CHtml::hiddenField('currentController','store')?>  
  <?php if (isset($_GET['id'])):?>
  <?php echo CHtml::hiddenField('id',$_GET['id'])?>
  <?php else :?>
  <?php echo CHtml::hiddenField('redirect',createUrl("store/addressbook/do/add"))?>
  <?php endif;?>
  
  
  <div style="height:20px;"></div>
        
    <?php if (Yii::app()->functions->isClientLogin()):?>
    
      <div class="uk-form-row">                  
          <?php echo CHtml::textField('street',
          isset($data['street'])?$data['street']:''
          ,array(
           'class'=>'uk-width-1-1',
           'placeholder'=>Yii::t("default","Address"),
           'data-validation'=>"required"  
          ))?>
       </div>             
              
       <div class="uk-form-row">                  
          <?php echo CHtml::textField('city',
          isset($data['city'])?$data['city']:''
          ,array(
           'class'=>'uk-width-1-1',
           'placeholder'=>Yii::t("default","City"),
           'data-validation'=>"required"  
          ))?>
       </div>             

       <div class="uk-form-row">                  
          <?php echo CHtml::textField('state',
          isset($data['state'])?$data['state']:''
          ,array(
           'class'=>'uk-width-1-1',
           'placeholder'=>Yii::t("default","State"),
           'data-validation'=>"required"  
          ))?>
       </div>             
       
       <div class="uk-form-row">                  
          <?php echo CHtml::textField('zipcode',
          isset($data['state'])?$data['zipcode']:''
          ,array(
           'class'=>'uk-width-1-1',
           'placeholder'=>Yii::t("default","Zip code"),
           'data-validation'=>"required"  
          ))?>
       </div>             
       
       <div class="uk-form-row">                  
          <?php echo CHtml::textField('location_name',
          isset($data['location_name'])?$data['location_name']:''
          ,array(
           'class'=>'uk-width-1-1',
           'placeholder'=>Yii::t("default","Location Name")           
          ))?>
       </div>             
       
       <?php $merchant_default_country=Yii::app()->functions->getOptionAdmin('merchant_default_country'); ?>
       <div class="uk-form-row">                  
          <?php 
          echo CHtml::dropDownList('country_code',
          isset($data['country_code'])?$data['country_code']:$merchant_default_country
          ,(array)Yii::app()->functions->CountryListMerchant(),array(
            'class'=>'uk-width-1-1',
            'data-validation'=>"required"  
          ));
          ?>
       </div>             
       
       <div class="uk-form-row">                  
          <?php 
          echo CHtml::checkBox('as_default',
          $data['as_default']==2?true:false
          ,array('class'=>"icheck",'value'=>2));
          echo " ".t("Default");
          ?>
       </div>             
   
       <div class="uk-form-row">   
      <input type="submit" value="<?php echo Yii::t("default","Submit")?>" class="uk-button uk-button-success uk-width-1-4">
       </div>
     </div> 
    <?php else :?> 
    <p class="uk-text-danger"><?php echo Yii::t("default","Sorry but you need to login first.")?></p>
    <?php endif;?>
    
  </form>
    </div>
  </div> <!--main-->
</div> <!--page-right-sidebar--> 