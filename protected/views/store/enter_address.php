<?php
$this->renderPartial('/front/default-header',array(
   'h1'=>t("We need your location"),
   'sub_text'=>t("Please enter your location")
));

echo CHtml::hiddenField('google_auto_address',Yii::app()->functions->getOptionAdmin('google_auto_address'));
?>

<div class="sections section-grey2 white_bg center">
<div class="container">


<?php echo CHtml::beginForm(Yii::app()->createUrl($form_action),'GET',array(
  'id'=>"frm_address",	 
)); 
?> 	

  <h1><?php echo t("Find restaurants near you");?></h1>
  <p><?php echo t("Order Delivery Food Online From Local Restaurants");?></p>

<div class="search-input-wraps rounded30">
     <div class="row">
        <div class=" border col-sm-11 col-xs-10">
        
        <?php if($map_provider['provider']=="mapbox"):?>
            <div class="mapbox_s_goecoder" id="mapbox_s_goecoder"></div>
        <?php else:?>
	        <?php echo CHtml::textField('s','',array(
	         'placeholder'=>t("Street Address,City,State"),
	         'required'=>true
	        ))?>        
        <?php endif;?>
        
        </div>        
        <div class=" relative border col-sm-1 col-xs-2">
          <button type="submit"><i class="ion-ios-search"></i></button>         
        </div>
     </div>
  </div> <!--search-input-wrap-->
<?php echo CHtml::endForm(); ?>
  
</div>
</div>