<?php if(is_array($menu) && count($menu)>=1):?>
<div class="category">
<?php foreach ($menu as $val):?>
 <a href="javascript:;" class="category-child relative goto-category" data-id="cat-<?php echo $val['category_id']?>" >
  
 <?php if($show_image_category==1):?>
	 <?php $cat_image = FunctionsV3::getImage($val['photo'], true);?> 
	 <?php if(!empty($cat_image)):?>
	 <img src="<?php echo $cat_image;?>" class="avatar" />
	 <?php endif;?>
 <?php endif;?>
 
  <?php echo qTranslate($val['category_name'],'category_name',$val)?>  
  <span>(<?php echo is_array($val['item'])?count($val['item']):'0';?>)</span>
  <i class="ion-ios-arrow-right"></i>
 </a>
<?php endforeach;?>
</div>
<?php endif;?>