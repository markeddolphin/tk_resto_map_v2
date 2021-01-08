<div class="row">
 <div class="col-md-4 col-xs-4 border category-list">
	<div class="theiaStickySidebar">
	 <?php 
	 $this->renderPartial('/front/menu-category',array(
	  'merchant_id'=>$merchant_id,
	  'menu'=>$menu,
	  'show_image_category'=>getOption($merchant_id, 'merchant_show_category_image')
	 ));
	 ?>
	</div>
 </div> <!--col-->
 <div class="col-md-8 col-xs-8 border" id="menu-list-wrapper">
 
 <?php if($enabled_food_search_menu==1):?>
 <form method="GET" class="frm-search-food">			   
 
 <?php 
 if($is_preview==true){
	 if(isset($_GET['preview'])){
	 	echo CHtml::hiddenField('preview','true');
	 }
	 if(isset($_GET['token'])){
	 	echo CHtml::hiddenField('token',$_GET['token']);
	 }
 }
 ?>
 
 <div class="search-food-wrap">						   
   <?php echo CHtml::textField('sname',
   isset($_GET['sname'])?$_GET['sname']:''
   ,array(
     'placeholder'=>t("Search"),
     'class'=>"form-control search_foodname required"
   ))?>
   <button type="submit"><i class="ion-ios-search"></i></button>
 </div>
 <?php if (isset($_GET['sname'])):?> 
     <a href="<?php echo Yii::app()->createUrl('store/menu-'.$data['restaurant_slug'])?>">
     [<?php echo t("Clear")?>]
     </a>
     <div class="clear"></div>
   <?php endif;?>
 </form>
 <?php endif;?>
 
 <?php 
 $admin_activated_menu=getOptionA('admin_activated_menu');			 
 $admin_menu_allowed_merchant=getOptionA('admin_menu_allowed_merchant');
 if ($admin_menu_allowed_merchant==2){			 	 
 	 $temp_activated_menu=getOption($merchant_id,'merchant_activated_menu');			 	 
 	 if(!empty($temp_activated_menu)){
 	 	 $admin_activated_menu=$temp_activated_menu;
 	 }
 }			 
 
 $merchant_tax=getOption($merchant_id,'merchant_tax');
 if($merchant_tax>0){
    $merchant_tax=$merchant_tax/100;
 }
			 
 switch ($admin_activated_menu)
 {
 	case 1:
 		$this->renderPartial('/front/menu-merchant-2',array(
		  'merchant_id'=>$merchant_id,
		  'menu'=>$menu,
		  'disabled_addcart'=>$disabled_addcart
		));
 		break;
 		
 	case 2:			 		
 		$this->renderPartial('/front/menu-merchant-3',array(
		  'merchant_id'=>$merchant_id,
		  'menu'=>$menu,
		  'disabled_addcart'=>$disabled_addcart
		));
 		break;
 			
 	default:	
	 	$this->renderPartial('/front/menu-merchant-1',array(
		  'merchant_id'=>$merchant_id,
		  'menu'=>$menu,
		  'disabled_addcart'=>$disabled_addcart,
		  'tc'=>$tc,
		  'merchant_apply_tax'=>getOption($merchant_id,'merchant_apply_tax'),
		  'merchant_tax'=>$merchant_tax>0?$merchant_tax:0,
		));
    break;
 }			 
 ?>			
 </div> <!--col-->
</div> <!--row-->