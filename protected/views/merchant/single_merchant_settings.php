<div class="row">
  <div class="col-md-4 col-sm-4">
  
  <div class="card card_medium" id="box_wrap">
	<div class="card-body">
	
	<?php 
	 /*$this->renderPartial(APP_FOLDER.'.views.index.settings_menu',array(
	  'settings_title'=>$settings_title,
	  'menu'=>SingleAppClass::settingsMenu($merchant_id),
	  'merchant_id'=>$merchant_id
	 ));*/
	?>
		
	<ul class="list-group pt-4">	  	
	<a href="<?php echo Yii::app()->createUrl("/merchant/singlemerchant/banner")?>" class="list-group-item
	  <?php echo $action_id=="banner"?"active":'';?>">
	  <?php echo t("Home banner")?>
	</a> 
	<a href="<?php echo Yii::app()->createUrl("/merchant/singlemerchant/settings_android")?>" class="list-group-item
	  <?php echo $action_id=="settings_android"?"active":'';?>">
	  <?php echo t("Android Settings")?>
	</a>  		 		
	<a href="<?php echo Yii::app()->createUrl("/merchant/singlemerchant/settings_pages")?>" class="list-group-item
	  <?php echo $action_id=="settings_pages"?"active":'';?>">
	  <?php echo t("Pages")?>
	</a>  		 		
	<a href="<?php echo Yii::app()->createUrl("/merchant/singlemerchant/settings_contactus")?>" class="list-group-item
	  <?php echo $action_id=="settings_contactus"?"active":'';?>">
	  <?php echo t("Contact us")?>
	</a>  		 		
	</ul>
	
	</div> <!--card body-->
  </div> <!--card-->
  
  </div> <!--col-->
  <div class="col-md-8 col-sm-8">
  
  <div class="card card_medium" id="box_wrap">
	<div class="card-body">
		 
	 <div class="pt-2">
	 <?php $this->renderPartial('/merchant/singlemerchant_'.$action_id,$data);?>
	 </div>
	
	</div> <!--card body-->
  </div> <!--card-->
  
  </div> <!--col-->
</div> <!--row-->