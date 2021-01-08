
<div style="padding:20px;">
<?php if (isset($data['rate_id'])):?>
<h3><?php echo t("Update Rate")?></h3>
<?php else :?>
<h3><?php echo t("Add Rate")?></h3>
<?php endif;?>

<form id="newforms" class="uk-form uk-form-horizontal" method="POST" onsubmit="return false;">
<?php echo CHtml::hiddenField('action','SaveRate')?>
<?php 
if (isset($data['rate_id'])){
	echo CHtml::hiddenField('rate_id',$data['rate_id']);
}
?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Country")?></label>
  <?php 
  echo CHtml::dropDownList('rate_country_id',
  isset($data['country_id'])?$data['country_id']:$default_country_id
  ,
  (array)FunctionsV3::countryList()
  ,array(
   'class'=>"uk-form-width-large rate_country_id",
   'data-validation'=>"required"
  ));
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("State/Region")?></label>
  <?php 
  echo CHtml::dropDownList('rate_state_id',
  isset($data['state_id'])?$data['state_id']:''
  ,
  (array)$states
  ,array(
   'class'=>"uk-form-width-large rate_state_id",
   'data-validation'=>"required"
  ));
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("City")?></label>
  <?php 
  echo CHtml::dropDownList('rate_city_id',
  isset($data['city_id'])?$data['city_id']:''
  ,
  (array)$citys
  ,array(
   'class'=>"uk-form-width-large rate_city_id",
   'data-validation'=>"required"
  ));
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Distric/Area/neighborhood")?></label>
  <?php 
  echo CHtml::dropDownList('rate_area_id',
  isset($data['area_id'])?$data['area_id']:''
  ,
  (array)$areas
  ,array(
   'class'=>"uk-form-width-large rate_area_id",
   'data-validation'=>"required"
  ));
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Fee")?></label>
  <?php 
  echo CHtml::textField('fee',
  isset($data['fee'])?normalPrettyPrice($data['fee']):''
  ,array(
    'class'=>"numeric_only",
    'data-validation'=>"required"
  ));
  ?>  
</div>

<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
<?php if (isset($data['rate_id'])):?> 
  <a href="javascript:;" class="uk-button uk-button-danger location_delete" data-id="<?php echo $data['rate_id']?>" >
  <?php echo t("Delete")?>
  </a>
<?php endif;?>
</div>

</form>
</div>

<script type="text/javascript">
jQuery(document).ready(function() {	
		
	$('.numeric_only').keyup(function () {     
      this.value = this.value.replace(/[^0-9\.]/g,'');
    });	
    
	$( document ).delegate( ".rate_country_id", "change", function() {				
		loadStateList( $("#rate_country_id").val() );
	});
	
	$( document ).delegate( ".rate_state_id", "change", function() {				
		loadCityListx( $(".rate_state_id").val() );
	});
		
	$( document ).delegate( ".rate_city_id", "change", function() {				
		loadAreaList( $(".rate_city_id").val() );
	});
	
});
$.validate({ 	
	language : jsLanguageValidator,
    form : '#newforms',    
    onError : function() {      
    },
    onSuccess : function() {           
      var params=$("#newforms").serialize();	
      callAjax( $("#action").val(), params , '' ) ;
      return false;
    }  
});
</script>