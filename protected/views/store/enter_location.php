<?php
$this->renderPartial('/front/default-header',array(
   'h1'=>t("We need your location"),
   'sub_text'=>t("Please enter your location")
));
?>

<div class="sections section-grey2 white_bg center">
<div class="container">


<?php echo CHtml::beginForm(Yii::app()->createUrl($form_action),'GET',array(
  'id'=>"frm_address",	 
)); 
?> 	

<h1><?php echo t("Find restaurants near you");?></h1>
<p><?php echo t("Order Delivery Food Online From Local Restaurants");?></p>

<?php 
echo CHtml::hiddenField('location_search_type',$search_type);  
$search_type = (integer)$search_type;
if($search_type<0){
	$search_type=1;
}

switch ($search_type) {
	case 2:	
		echo CHtml::hiddenField('state_id');
		echo CHtml::hiddenField('state_name');
		echo CHtml::hiddenField('city_id');
		echo CHtml::hiddenField('city_name');
		break;

	case 3:
		echo CHtml::hiddenField('postal_code');         
		break;
		
	default:
		echo CHtml::hiddenField('city_id','');
        echo CHtml::hiddenField('city_name','');
        echo CHtml::hiddenField('area_id','');
		break;
}

$this->renderPartial("/store/enter_location_$search_type");
?>

<?php echo CHtml::endForm(); ?>
  
</div>
</div>