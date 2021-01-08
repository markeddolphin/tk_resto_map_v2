
<div style="padding:20px;">
<h3><?php echo t("Add City")?> - <?php echo $data['name']?></h3>

<form id="newforms" class="uk-form uk-form-horizontal" method="POST" onsubmit="return false;">
<?php echo CHtml::hiddenField('action','SaveCity')?>
<?php echo CHtml::hiddenField('state_id',$state_id)?>
<?php 
if (isset($data2['city_id'])){
	echo CHtml::hiddenField('id',$data2['city_id']);
}
?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Name")?></label>
  <?php 
  echo CHtml::textField('city_name',
  isset($data2['name'])?$data2['name']:''
  ,array(
    'class'=>"uk-form-width-large",
    'data-validation'=>"required"
  ))
  ?>  
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Postal Code/Zip code")?></label>
  <?php 
  echo CHtml::textField('postal_code',
  isset($data2['postal_code'])?$data2['postal_code']:''
  ,array(
    'class'=>"uk-form-width-large",
    //'data-validation'=>"required"
  ))
  ?>  
</div>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
<?php if (isset($data2['city_id'])):?> 
  <a href="javascript:;" class="uk-button uk-button-danger delete-city" data-id="<?php echo $data2['city_id']?>" >
  <?php echo t("Delete")?>
  </a>
<?php endif;?>
</div>

</form>
</div>

<script type="text/javascript">
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