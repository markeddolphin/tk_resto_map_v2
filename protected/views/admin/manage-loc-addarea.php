
<div style="padding:20px;">
<h3><?php echo t("Add District / Area")?> - <?php echo $data['name']?></h3>

<form id="newforms" class="uk-form uk-form-horizontal" method="POST" onsubmit="return false;">
<?php echo CHtml::hiddenField('action','SaveArea')?>
<?php echo CHtml::hiddenField('city_id',$data['city_id'])?>
<?php 
if (isset($data2['area_id'])){
	echo CHtml::hiddenField('id',$data2['area_id']);
}
?>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Name")?></label>
  <?php 
  echo CHtml::textField('name',
  isset($data2['name'])?$data2['name']:''
  ,array(
    'class'=>"uk-form-width-large area_name",
    'data-validation'=>"required"
  ))
  ?>  
</div>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
<?php if (isset($data2['area_id'])):?> 
  <a href="javascript:;" class="uk-button uk-button-danger delete-area" data-id="<?php echo $data2['area_id']?>" >
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