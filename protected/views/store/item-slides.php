<?php 
$item_found=false;
if (is_array($data) && count($data)>=1){
	$data=$data[0];
	$item_found=true;
} else $data['item_name']='';

$slug=isset($this_data['slug'])?$this_data['slug']:'';

$this->renderPartial('/front/mobile_header',array(
    'slug'=> $slug,
    'title'=>$data['item_name']
));
?>

<div class="container">

<?php if ($item_found==true):?>
<?php 
$row='';
$item_data='';
$price_select='';
$size_select='';
if (array_key_exists("row",(array)$this_data)){
	$row=$this_data['row'];	
	$item_data=$_SESSION['kr_item'][$row];
	//dump($item_data);
	$price=Yii::app()->functions->explodeData($item_data['price']);
	if (is_array($price) && count($price)>=1){
		$price_select=isset($price[0])?$price[0]:'';
		$size_select=isset($price[1])?$price[1]:'';
	}
	$row++;
}
$disabled_website_ordering=Yii::app()->functions->getOptionAdmin('disabled_website_ordering');
$hide_foodprice=Yii::app()->functions->getOptionAdmin('website_hide_foodprice');
echo CHtml::hiddenField('hide_foodprice',$hide_foodprice);
?>


<form class="frm-fooditem" id="frm-fooditem" method="POST" onsubmit="return false;">
<?php echo CHtml::hiddenField('action','addToCart')?>
<?php echo CHtml::hiddenField('item_id',$this_data['item_id'])?>
<?php echo CHtml::hiddenField('row',isset($row)?$row:"")?>
<?php echo CHtml::hiddenField('merchant_id',isset($data['merchant_id'])?$data['merchant_id']:'')?>
<?php echo CHtml::hiddenField('discount',isset($data['discount'])?$data['discount']:"" )?>
<?php echo CHtml::hiddenField('currentController','store')?>
<?php 
if ($data['two_flavors']==2){
	$data['prices'][0]=array(
	  'price'=>0,
	  'size'=>''
	);	
	echo CHtml::hiddenField('two_flavors',$data['two_flavors']);
}
?>


<div class="container view-food-item-wrap" id="mobile-view-food-item">
   
  <!--ITEM NAME & DESCRIPTION-->
  <div class="row">
    <div class="col-md-3 ">              
       <img src="<?php echo FunctionsV3::getFoodDefaultImage($data['photo']);?>" alt="<?php echo $data['item_name']?>" title="<?php echo $data['item_name']?>" class="logo-small">
    </div> <!--col-->
    <div class="col-md-9 ">
       <p class="bold"><?php echo qTranslate($data['item_name'],'item_name',$data)?></p>
       <?php echo Widgets::displaySpicyIconNew($data['dish']);?> 
       <p><?php echo qTranslate($data['item_description'],'item_description',$data)?></p>
    </div> <!--col-->
  </div> <!--row-->
  <!--ITEM NAME & DESCRIPTION--
     
  <!--FOOD ITEM GALLERY-->
  <?php if (getOption($data['merchant_id'],'disabled_food_gallery')!=2):?>  
  <?php $gallery_photo=!empty($data['gallery_photo'])?json_decode($data['gallery_photo']):false; ?>
     <?php if (is_array($gallery_photo) && count($gallery_photo)>=1):?>
      <div class="section-label">
        <a class="section-label-a">
          <span class="bold">
          <?php echo t("Gallery")?></span>
          <b></b>
        </a>     
        <div class="food-gallery-wrap row ">
          <?php foreach ($gallery_photo as $gal_val):?>
          <div class="col-md-3 ">
            <a href="<?php echo websiteUrl()."/upload/$gal_val"?>">
              <div class="food-pic" style="background:url('<?php echo websiteUrl()."/upload/$gal_val"?>')"></div>
              <img style="display:none;" src="<?php echo websiteUrl()."/upload/$gal_val"?>" alt="" title="">
            </a>
          </div> <!--col-->         
          <?php endforeach;?>
        </div> <!--food-gallery-wrap-->   
      </div> <!--section-label-->
     <?php endif;?>
  <?php endif;?>
  <!--FOOD ITEM GALLERY-->
    
  
  <!--PRICE-->
  <div class="section-label">
    <a class="section-label-a">
      <span class="bold">
      <?php echo t("Price")?></span>
      <b></b>
    </a>     
    <div class="row">
    <?php if (is_array($data['prices']) && count($data['prices'])>=1):?>  
      <?php foreach ($data['prices'] as $price):?>
          <?php $price['price']=Yii::app()->functions->unPrettyPrice($price['price'])?>
          <div class="col-md-5 ">
             <?php if ( !empty($price['size'])):?>
                 <?php echo CHtml::radioButton('price',
		          $size_select==$price['size']?true:false
		          ,array(
		            'value'=>$price['price']."|".$price['size'],
		            'class'=>"price_cls item_price"
		          ))?>
		          <?php echo qTranslate($price['size'],'size',$price)?>
              <?php else :?>
                  <?php echo CHtml::radioButton('price',
		            count($price['price'])==1?true:false
		            ,array(
		            'value'=>$price['price'],
		            'class'=>"item_price"
		          ))?>
             <?php endif;?>
             
             <?php if (isset($price['price'])):?>  
                <?php if (is_numeric($data['discount'])):?>
                    <span class="line-tru"><?php echo FunctionsV3::prettyPrice($price['price'])?></span>
                    <span class="text-danger"><?php echo FunctionsV3::prettyPrice($price['price']-$data['discount'])?></span>
                <?php else :?>
                    <?php echo FunctionsV3::prettyPrice($price['price'])?>
                 <?php endif;?>
             <?php endif;?>
             
          </div> <!--col-->
      <?php endforeach;?>
    <?php endif;?>
    </div> <!--row-->
  </div>        
  <!--PRICE-->
  
  <!--QUANTITY-->
  <?php if (is_array($data['prices']) && count($data['prices'])>=1):?>
  <div class="section-label">
    <a class="section-label-a">
      <span class="bold">
      <?php echo t("Quantity")?></span>
      <b></b>
    </a>     
    <div class="row">
       <div class="col-md-1 col-xs-1 border into-row-2 text-to-right">
          <a href="javascript:;" class="green-button inline qty-minus" ><i class="ion-minus"></i></a>
       </div>
       <div class="col-md-2 col-xs-2 border into-row-2">
          <?php echo CHtml::textField('qty',
	      isset($item_data['qty'])?$item_data['qty']:1
	      ,array(
	      'class'=>"uk-form-width-mini numeric_only qty",      
	      ))?>
       </div>
       <div class="col-md-1 col-xs-1 border into-row-2 text-to-left">
         <a href="javascript:;" class="qty-plus green-button inline"><i class="ion-plus"></i></a>
       </div>
       <div class="col-md-8 col-xs-8 border into-row">
         <a href="javascript:;" class="special-instruction orange-button inline"><?php echo t("Special Instructions")?></a>
       </div>
    </div> <!--row-->
  </div> <!-- section-label--> 
  
  <div class="notes-wrap">
  <?php echo CHtml::textArea('notes',
  isset($item_data['notes'])?$item_data['notes']:""
  ,array(
   'class'=>'uk-width-1-1',
   'placeholder'=>Yii::t("default","Special Instructions")
  ))?>
  </div> <!--notes-wrap-->
  
  <?php else :?>
  <!--do nothing-->
  <?php endif;?>  
  <!--QUANTITY-->
    
  
  
  <!--COOKING REF-->
  <?php if (isset($data['cooking_ref'])):?>
  <?php if (is_array($data['cooking_ref']) && count($data['cooking_ref'])>=1):?>
  <div class="section-label">
  
    <div class="section-label-a collapse-parent" data-id="1" >
      <span class="bold">
      <?php echo t("Cooking Preference")?></span>
      <b></b>
      <a href="javascript:;" data-id="1" ><i class="ion-plus-circled"></i></a>
    </div>        
    
    <div class="row collapse-child collapse-child-1">
      <?php foreach ($data['cooking_ref'] as $val):?>
      <div class="col-md-5 ">
         <?php $item_data['cooking_ref']=isset($item_data['cooking_ref'])?$item_data['cooking_ref']:''; ?>
         <?php echo CHtml::radioButton('cooking_ref',
	       $item_data['cooking_ref']==$val?true:false
	       ,array(
	         'value'=>$val
	       ))?>&nbsp;             
	       <?php echo qTranslate($val,'cooking_ref',$data);?>
      </div> <!--col-->
      <?php endforeach;?>
    </div> <!--row-->
  </div>  <!--section-label--> 
  <?php endif;?>
  <?php endif;?>
  <!--COOKING REF-->  
  
  <!--Ingredients-->  
  <?php 
  if (!isset($item_data['ingredients'])){
  	  $item_data['ingredients']='';
  }
  ?>
  <?php if (isset($data['ingredients'])):?>  
  <?php if (is_array($data['ingredients']) && count($data['ingredients'])>=1):?>
  <div class="section-label">
  
    <div class="section-label-a collapse-parent">
      <span class="bold">
      <?php echo t("Ingredients")?></span>
      <b></b>
      <a href="javascript:;" data-id="2" ><i class="ion-plus-circled"></i></a>
    </div>             
    
     <div class="row collapse-child collapse-child-2">
         <?php foreach ($data['ingredients'] as $val):?>
         <?php $item_data['ingredients_id']=isset($item_data['ingredients_id'])?$item_data['ingredients_id']:''; ?>
         <div class="col-md-5 ">
           <?php echo CHtml::checkbox('ingredients[]',
	       in_array($val,(array)$item_data['ingredients'])?true:false
	       ,array(
	         'value'=>$val
	       ))?>&nbsp;             
	       <?php echo $val;?>
         </div>         
         <?php endforeach;?>
     </div>     
  </div>  
  <?php endif;?>
  <?php endif;?>
  <!--END Ingredients-->
  
  
  <!--FOOD ADDON-->
  <?php $add_counter=2;?>
  <div class="sub-item-rows">
  <?php if (isset($data['addon_item'])):?>
  <?php if (is_array($data['addon_item']) && count($data['addon_item'])>=1):?>
    <?php foreach ($data['addon_item'] as $val): //dump($val);?>
    <?php $add_counter++;?>
    
     <?php echo CHtml::hiddenField('require_addon_'.$val['subcat_id'],$val['require_addons'],array(
     'class'=>"require_addon require_addon_".$val['subcat_id'],
     'data-id'=>$val['subcat_id'],
     'data-name'=>strtoupper($val['subcat_name'])
    ))?>
    
	  <div class="section-label">
	  
	    <div class="section-label-a collapse-parent">
	      <span class="bold">
	      <?php echo qTranslate($val['subcat_name'],'subcat_name',$val)?>
	      </span>
	      <b></b>
	      <a href="javascript:;" data-id="<?php echo $add_counter;?>" ><i class="ion-plus-circled"></i></a>
	    </div>        
	    
	  </div>  
	  <?php if (is_array($val['sub_item']) && count($val['sub_item'])>=1):?>
	  <?php $x=0;?>
	  <?php foreach ($val['sub_item'] as $val_addon):?>    
	  <?php 
	  $subcat_id=$val['subcat_id'];
      $sub_item_id=$val_addon['sub_item_id'];
      $multi_option_val=$val['multi_option'];
      
       /** fixed select only one addon*/
        if ( $val['multi_option']=="custom" || $val['multi_option']=="multiple"){
        	$sub_item_name="sub_item[$subcat_id][$x]";
        } else $sub_item_name="sub_item[$subcat_id][]"; 
        
        $sub_addon_selected='';
        $sub_addon_selected_id='';
                    
        $item_data['sub_item']=isset($item_data['sub_item'])?$item_data['sub_item']:'';
        if (array_key_exists($subcat_id,(array)$item_data['sub_item'])){
        	$sub_addon_selected=$item_data['sub_item'][$subcat_id];
        	if (is_array($sub_addon_selected) && count($sub_addon_selected)>=1){
            	foreach ($sub_addon_selected as $val_addon_selected) {
            		$val_addon_selected=Yii::app()->functions->explodeData($val_addon_selected);
            		if (is_array($val_addon_selected)){
            		    $sub_addon_selected_id[]=$val_addon_selected[0];
            		}
            	}
        	}
        }
	  ?>	    
	    <div class="row top10 collapse-child collapse-child-<?php echo $add_counter?>">
	        <div class="col-md-5 col-xs-5 border into-row ">
	        <?php 
	         if ( $val['multi_option']=="custom" || $val['multi_option']=="multiple"): 
                            
	            echo CHtml::checkBox($sub_item_name,
	            in_array($sub_item_id,(array)$sub_addon_selected_id)?true:false
	            ,array(
	              'value'=>$val_addon['sub_item_id']."|".$val_addon['price']."|".$val_addon['sub_item_name']."|".$val['two_flavor_position'],
	              'data-id'=>$val['subcat_id'],
	              'data-option'=>$val['multi_option_val'],
	              'rel'=>$val['multi_option'],
	              'class'=>'sub_item_name sub_item_name_'.$val['subcat_id']
	            ));
            else :            	                            
	            echo CHtml::radioButton($sub_item_name,
	            in_array($sub_item_id,(array)$sub_addon_selected_id)?true:false
	            ,array(
	              'value'=>$val_addon['sub_item_id']."|".$val_addon['price']."|".$val_addon['sub_item_name']."|".$val['two_flavor_position'],	             
	              'class'=>'sub_item sub_item_name_'.$val['subcat_id']	             
	            ));
            endif;
            
            echo "&nbsp;".qTranslate($val_addon['sub_item_name'],'sub_item_name',$val_addon);
	        ?>
	         <span class="hide-food-price to-show">
	        <?php echo !empty($val_addon['price'])?displayPrice(getCurrencyCode(),$val_addon['price']):"-";?>
	        </span>
	        </div> <!--col-->
	        
	        <div class="col-md-4 col-xs-4 border into-row ">
	          <?php if ($val['multi_option']=="multiple"):?>
		      <?php             
	          $qty_selected=1;
	          if (!isset($item_data['addon_qty'])){
	           	 $item_data['addon_qty']='';
	          }
	          if (array_key_exists($subcat_id,(array)$item_data['addon_qty'])){            	            
	              $qty_selected=$item_data['addon_qty'][$subcat_id][$x];
	          }            
	          ?>
	          
	          <div class="row quantity-wrap-small">
	            <div class="col-md-3 col-xs-3 border ">
	              <a href="javascript:;" class="green-button inline qty-addon-minus"><i class="ion-minus"></i></a>
	            </div>
	            <div class="col-md-5 col-xs-5 border">
	              <?php echo CHtml::textField("addon_qty[$subcat_id][$x]",$qty_selected,array(
		          'class'=>"numeric_only left addon_qty",      
		          ))?>
	            </div>
	            <div class="col-md-3 col-xs-3 border ">
	              <a href="javascript:;" class="green-button inline qty-addon-plus"><i class="ion-plus"></i></a>
	            </div>
	          </div>
	          
	          <?php endif;?>
	        </div> <!--col-->
	        
	        <div class="col-md-3 col-xs-3 border text-right into-row">
	        <span class="hide-food-price to-hide">
	        <?php echo !empty($val_addon['price'])?displayPrice(getCurrencyCode(),$val_addon['price']):"-";?>
	        </span>
	        </div> <!--col-->
	    </div> <!--row-->	    
	    <?php $x++;?>
	  <?php endforeach;?>	  
	  <?php endif;?>
     <?php endforeach;?>
  <?php endif;?>
  <?php endif;?>
  </div><!-- .sub-item-rows-->
  <!--FOOD ADDON-->

<?php if ($disabled_website_ordering==""):?>
<div class="section-label top25">
<a class="section-label-a">
  <span class="bold">
  &nbsp;
  </span>
  <b></b>
</a>        
</div>  
<div class="row food-item-actions">
  <div class="col-md-6 col-xs-6 border">
       <a href="<?php echo Yii::app()->createUrl('/menu-'.$slug) ?>" 
     class="center upper-text green-button inline"><?php echo t("Back")?></a>
              
  </div>
  <div class="col-md-6 col-xs-6 border ">
 
  
  <input type="submit" value="<?php echo empty($row)?Yii::t("default","add to cart"):Yii::t("default","update cart");?>" 
     class="add_to_cart orange-button upper-text">
  
  </div>
</div>
<?php endif;?>
  
</div> <!--view-item-wrap-->

<?php else :?>
<p class="text-danger top25 center"><?php echo t("Sorry but we cannot find what you are looking for.")?></p>
<?php endif;?>

</div> <!--container-->