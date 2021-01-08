

<?php if(is_array($menu) && count($menu)>=1):?>
<div class="menu-3 box-grey rounded" style="margin-top:0;">

<?php foreach ($menu as $val):?>
<div class="menu-3-cat bottom20">
<h2 class="text-left menu-cat cat-<?php echo $val['category_id']?>">
<?php echo qTranslate($val['category_name'],'category_name',$val)?>
</h2>

<?php if (is_array($val['item']) && count($val['item'])>=1):?>
<?php foreach ($val['item'] as $val_item):?>

<?php 
//dump($val_item);
$atts='';
if ( $val_item['single_item']==2){
	  $atts.='data-price="'.$val_item['single_details']['price'].'"';
	  $atts.=" ";
	  $atts.='data-size="'.$val_item['single_details']['size'].'"';
	  $atts.=" ";
	  if(isset($val_item['single_details']['size_id'])){
	     $atts.='data-size_id="'.$val_item['single_details']['size_id'].'"';
	  }
	  $atts.=" ";
	  $atts.='data-discount="'.$val_item['discount'].'"';
}
?> 

<?php if ( $disabled_addcart==""):?>
<a href="javascript:;" class="dsktop menu-item <?php echo $val_item['not_available']==2?"item_not_available":''?>"
rel="<?php echo $val_item['item_id']?>"
data-single="<?php echo $val_item['single_item']?>" 
<?php echo $atts;?>
data-category_id="<?php echo $val['category_id']?>"
>
<?php else :?>
<a href="javascript:;" class="menu-3-disabled-ordering">
<?php endif;?>

<div class="row">
  <div class="col-md-2">
    <img src="<?php echo FunctionsV3::getFoodDefaultImage($val_item['photo'],false)?>">
  </div> <!--col-->
  <div class="col-md-7">
    <p class="bold"><?php echo qTranslate($val_item['item_name'],'item_name',$val_item)?></p>  
    <p class="small food-description read-more">
    <?php echo qTranslate($val_item['item_description'],'item_description',$val_item)?>
    </p>
  </div> <!--col-->
  
  <div class="col-md-3 center">
  <p class="bold"><?php echo FunctionsV3::getItemFirstPrice($val_item['prices'],$val_item['discount'])?></p>
  </div> <!--col-->
  
</div> <!--row-->
</a>

<?php if ( $disabled_addcart==""):?>
<a href="javascript:;" class="mbile menu-item <?php echo $val_item['not_available']==2?"item_not_available":''?>"
rel="<?php echo $val_item['item_id']?>"
data-single="<?php echo $val_item['single_item']?>" 
<?php echo $atts;?>
data-category_id="<?php echo $val['category_id']?>"
>
<?php else :?>
<a href="javascript:;" class="mbile menu-3-disabled-ordering">
<?php endif;?>

<div class="row">
  <div class="col-md-2">
    <img src="<?php echo FunctionsV3::getFoodDefaultImage($val_item['photo'],false)?>">
  </div> <!--col-->
  <div class="col-md-7">
    <p class="bold"><?php echo qTranslate($val_item['item_name'],'item_name',$val_item)?></p>  
    <p class="small food-description read-more">
    <?php echo qTranslate($val_item['item_description'],'item_description',$val_item)?>
    </p>
  </div> <!--col-->
  
  <div class="col-md-3 center">
  <p class="bold"><?php echo FunctionsV3::getItemFirstPrice($val_item['prices'],$val_item['discount'])?></p>
  </div> <!--col-->
  
</div> <!--row-->
</a>

<?php endforeach;?>
<?php else :?>
<p class="small text-danger"><?php echo t("no item found on this category")?></p>
<?php endif;?>

</div><!-- menu-3-cat-->
<?php endforeach;?>

</div> <!--box-->
<?php else :?>
<p class="text-danger"><?php echo t("This restaurant has not published their menu yet.")?></p>
<?php endif;?>