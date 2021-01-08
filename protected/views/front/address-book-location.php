
<div class="box-grey rounded section-address-book" style="margin-top:0;">

<?php
$do=isset($_GET['do'])?$_GET['do']:'';
?>

<?php if ( $do==="add" && $tabs==2 ) :?>

<form id="frm-addressbook" class="frm-addressbook" onsubmit="return false;">
<?php echo CHtml::hiddenField('action','addAddressBookLocation')?>
<?php echo CHtml::hiddenField('currentController','store')?>  
<?php if (isset($_GET['id'])):?>
<?php echo CHtml::hiddenField('id',$_GET['id'])?>
<?php else :?>
<?php endif;?>

<div class="row bottom10">
  <div class="col-md-6">
    <p class="text-small"><?php echo t("Street")?></p>
    <?php 
	  echo CHtml::textField('street',
      isset($data['street'])?$data['street']:''
      ,array(
       'class'=>'grey-fields full-width',
       'data-validation'=>"required"  
      ))?>	  
  </div>
  <div class="col-md-6">
    <p class="text-small"><?php echo t("State/Region")?></p>
    
  <?php    
	 echo CHtml::dropDownList('state_id',
	 isset($data['state_id'])?$data['state_id']:''
	 ,(array)$states,array(
	   'class'=>'grey-fields full-width',
	   'data-validation'=>"required"  
	 ))
    ?>	  
  </div>
</div> <!--row-->


<div class="row bottom10">
  <div class="col-md-6">
    <p class="text-small"><?php echo t("City")?></p>
    <?php 
	 echo CHtml::dropDownList('city_id',
	 isset($data['city_id'])?$data['city_id']:''
	 ,
	 (array)$citys,array(
	   'class'=>'grey-fields full-width',
	   'data-validation'=>"required"  
	 ))
    ?>	  
  </div>
  <div class="col-md-6">
    <p class="text-small"><?php echo t("Distric/Area/neighborhood")?></p>
	  <?php 
	  echo CHtml::dropDownList('area_id',
	  isset($data['area_id'])?$data['area_id']:''
	  ,
	  (array)$areas,array(
	   'class'=>'grey-fields full-width',
	   'data-validation'=>"required"  
	 ))
	  ?>
  </div>
</div> <!--row-->

<?php $merchant_default_country=Yii::app()->functions->getOptionAdmin('merchant_default_country'); ?>
<div class="row bottom10">
  <div class="col-md-6">
    <p class="text-small"><?php echo t("Location Name")?></p>
    <?php echo CHtml::textField('location_name',
          isset($data['location_name'])?$data['location_name']:''
          ,array(
           'class'=>'grey-fields full-width',           
          ))?>
  </div>
  <div class="col-md-6">
    <p class="text-small"><?php echo t("Country")?></p>
	 <?php 	 
      echo CHtml::dropDownList('country_id',
      isset($data['country_id'])?$data['country_id']:$country_id
      ,(array)FunctionsV3::countryList(),array(
        'class'=>'grey-fields full-width',
        'data-validation'=>"required"  
      ));
      ?>
  </div>
</div> <!--row-->

<div class="row bottom10">
<div class="col-md-6">
<?php 
      echo CHtml::checkBox('as_default',
      $data['as_default']==1?true:false
      ,array('class'=>"icheck",'value'=>1));
      echo " ".t("Default");
      ?>
</div>
</div>

<div class="row top10">
  <div class="col-md-2">
  <input type="submit" value="<?php echo t("Submit")?>" class="green-button medium inline">
  </div>
  <div class="col-md-5">
    <a class="green-text top10 block" href="<?php echo Yii::app()->createUrl('/store/profile/',array(
      'tab'=>2
    ))?>">
	<i class="ion-ios-arrow-thin-left"></i> <?php echo t("Back")?>
	</a>
  </div>
</div>

</form>

<?php else :?>

<div class="bottom10 top10">
<a class="green-button inline rounded" href="<?php echo Yii::app()->createUrl('store/profile',array(
  'tab'=>2,
  'do'=>'add'
))?>">
<?php echo t("Add New")?>
</a>
</div>

<form id="frm_table_list" method="POST" >
<input type="hidden" name="action" id="action" value="addressBookLocation">
<input type="hidden" name="tbl" id="tbl" value="temp">
<table id="table_list" class="table table-striped">
  <thead>
  <tr>
   <th width="40%" ><?php echo Yii::t("default","Address")?></th>
   <th ><?php echo Yii::t("default","Location Name")?></th>
   <th ><?php echo Yii::t("default","Default")?></th>   
  </tr>
  </thead>
</table>  
<div class="clear"></div>
</form>

<?php endif;?>

</div> <!--box-grey-->