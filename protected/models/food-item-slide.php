<?php
$row='';
$item_data='';
$price_select='';
$size_select='';
if (array_key_exists("row",(array)$this->data)){
	$row=$this->data['row'];	
	$item_data=$_SESSION['kr_item'][$row];
	//dump($item_data);
	$price=Yii::app()->functions->explodeData($item_data['price']);
	if (is_array($price) && count($price)>=1){
		$price_select=isset($price[0])?$price[0]:'';
		$size_select=isset($price[1])?$price[1]:'';
	}
	$row++;
}


$data=Yii::app()->functions->getItemById($this->data['item_id']);
$disabled_website_ordering=Yii::app()->functions->getOptionAdmin('disabled_website_ordering');
$hide_foodprice=Yii::app()->functions->getOptionAdmin('website_hide_foodprice');
echo CHtml::hiddenField('hide_foodprice',$hide_foodprice);
?>

<?php if (is_array($data) && count($data)>=1):?>
<?php 
$data=$data[0];

//dump($data);
$mtid=$data['merchant_id'];
$apply_tax=getOption($mtid,'merchant_apply_tax');
$tax=FunctionsV3::getMerchantTax($mtid);
?>

<form class="frm-fooditem" id="frm-fooditem" method="POST" onsubmit="return false;">
<?php echo CHtml::hiddenField('action','addToCart')?>
<?php echo CHtml::hiddenField('item_id',$this->data['item_id'])?>
<?php echo CHtml::hiddenField('row',isset($row)?$row:"")?>
<?php echo CHtml::hiddenField('merchant_id',isset($data['merchant_id'])?$data['merchant_id']:'')?>


<?php echo CHtml::hiddenField('discount',isset($data['discount'])?$data['discount']:"" )?>
<?php echo CHtml::hiddenField('currentController','store')?>

<?php
 if (isset($item_data['category_id'])){
 	echo CHtml::hiddenField('category_id', isset($item_data['category_id'])?$item_data['category_id']:'' );
 } else echo CHtml::hiddenField('category_id', isset($this->data['category_id'])?$this->data['category_id']:'' );
 ?>

<?php 
//dump($data);
/** two flavores */
if ($data['two_flavors']==2){
	$data['prices'][0]=array(
	  'price'=>0,
	  'size'=>''
	);	
	echo CHtml::hiddenField('two_flavors',$data['two_flavors']);
}
//dump($data);
?>

<div class="container  view-food-item-wrap">
   
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
             
             <?php 
             /*if ($apply_tax==1 && $tax>0){
             	$price['price']=$price['price'] + ($price['price']*$tax);
             }*/
             ?>
                          
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
       <div class="col-md-1 col-xs-1 border into-row">
          <a href="javascript:;" class="green-button inline qty-minus" ><i class="ion-minus"></i></a>
       </div>
       <div class="col-md-2 col-xs-2 border into-row">
          <?php echo CHtml::textField('qty',
	      isset($item_data['qty'])?$item_data['qty']:1
	      ,array(
	      'class'=>"uk-form-width-mini numeric_only qty", 
	      'maxlength'=>5     
	      ))?>
       </div>
       <div class="col-md-1 col-xs-1 border into-row">
         <a href="javascript:;" class="qty-plus green-button inline"><i class="ion-plus"></i></a>
       </div>
       <div class="col-md-6 col-xs-6 border into-row">
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
  
    <div class="section-label-a collapse-parent" data-id="1">
      <span class="bold">
      <?php echo t("Cooking Preference")?></span>
      <b></b>
       <a href="javascript:;" data-id="1" ><i class="ion-plus-circled"></i></a>
    </div>        
    
    <div class="row collapse-child collapse-child-1">    
      <?php foreach ($data['cooking_ref'] as $cooking_ref_id=>$val):?>
      
      <div class="col-md-5 ">
         <?php $item_data['cooking_ref']=isset($item_data['cooking_ref'])?$item_data['cooking_ref']:''; ?>
         <?php echo CHtml::radioButton('cooking_ref',
	       $item_data['cooking_ref']==$val?true:false
	       ,array(
	         'value'=>$val
	       ))?>&nbsp;             
	       <?php 
	       $cooking_ref_trans=Yii::app()->functions->getCookingTranslation($val,$data['merchant_id']);
	       echo qTranslate($val,'cooking_name',$cooking_ref_trans);
	       ?>
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
         <?php foreach ($data['ingredients'] as $ingredients_id =>  $val):
         $ingredients_name_trans='';
         $_ingredienst=Yii::app()->functions->getIngredients($ingredients_id);
         if ($_ingredienst){
         	$ingredients_name_trans['ingredients_name_trans']=!empty($_ingredienst['ingredients_name_trans'])?json_decode($_ingredienst['ingredients_name_trans'],true):'';
         }         
         ?>
         <?php $item_data['ingredients_id']=isset($item_data['ingredients_id'])?$item_data['ingredients_id']:''; ?>
         <div class="col-md-5 ">
           <?php echo CHtml::checkbox('ingredients[]',
	       in_array($val,(array)$item_data['ingredients'])?true:false
	       ,array(
	         'value'=>$val
	       ))?>&nbsp;             
	       <?php echo qTranslate($val,'ingredients_name',$ingredients_name_trans);?>
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
	  <?php 	  
	  $enabled_select_addon=False;
	  $multi_option_selected=$val['multi_option'];
	  $addon_data='';	  	 

	  if (!empty($val['two_flavor_position'])){
	  	 $enabled_select_addon=false;
	  }
	  ?>
	  
	  <?php if ($multi_option_selected=="one" && $enabled_select_addon==TRUE) :?>
	     <?php 	     	     
	     $sub_addon_selected_id='';
	     $subcat_id=$val['subcat_id'];
	     $item_data['sub_item']=isset($item_data['sub_item'])?$item_data['sub_item']:'';
	     if (array_key_exists($subcat_id,(array)$item_data['sub_item'])){
	         $sub_addon_selected=$item_data['sub_item'][$subcat_id];	         
	         if(is_array($sub_addon_selected) && count($sub_addon_selected)>=1){
	         	$sub_addon_selected_id = $sub_addon_selected[0];	  	         	
	         }	         
	     }
	     
	     $addon_data[]=t("Choose one")."...";
	     $subcat_id=$val['subcat_id'];
         //$sub_item_id=$val_addon['sub_item_id'];
         $multi_option_val=$val['multi_option'];
         $sub_item_name="sub_item[$subcat_id][]";          
	     //dump($sub_item_name);	  
	     if (is_array($val['sub_item']) && count($val['sub_item'])>=1){
	     	foreach ($val['sub_item'] as $val_addon){	     	
	     		$key=$val_addon['sub_item_id']."|".$val_addon['price']."|".$val_addon['sub_item_name']."|".$val['two_flavor_position'];
	     		if ($val_addon['price']>0){
	     			$addon_data[$key]=$val_addon['sub_item_name']." (".prettyFormat($val_addon['price']).")";	
	     		} else $addon_data[$key]=$val_addon['sub_item_name'];		     		
	     	}	     		     	
	     	?>
	     	<div class="row top10">
	     	  <div class="col-md-12 col-xs-12 border into-row">
	     	   <?php 
	     	   echo CHtml::dropDownList($sub_item_name, $sub_addon_selected_id ,(array)$addon_data,array(
	     	     'class'=>'select_sub_item sub_item_name sub_item_name_'.$val['subcat_id'],
	     	     'data-type'=>"select"
	     	   ));
	     	   ?>
	     	  </div>
	     	</div>
	     	<?php	     	
	     }
	     ?>
	     
	  <?php else :?>
	  
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
	        <div class="col-md-5 col-xs-5 border into-row">
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
	              'class'=>'sub_item sub_item_name_'.$val['subcat_id'],
	              'data-type'=>"radio"	             
	            ));
            endif;
                        
            echo "&nbsp;".qTranslate($val_addon['sub_item_name'],'sub_item_name',$val_addon);
            echo "<p>".qTranslate($val_addon['item_description'],'item_description',$val_addon)."</p>";
	        ?>
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
		          'maxlength'=>5
		          ))?>
	            </div>
	            <div class="col-md-3 col-xs-3 border ">
	              <a href="javascript:;" class="green-button inline qty-addon-plus"><i class="ion-plus"></i></a>
	            </div>
	          </div>
	          
	          <?php endif;?>
	        </div> <!--col-->
	        
	        <?php 
	        /*if ($apply_tax==1 && $tax>0){
	        	$val_addon['price']=$val_addon['price']+($val_addon['price']*$tax);
	        }*/
	        ?>
	        <div class="col-md-3 col-xs-3 border text-right into-row">
	        <span class="hide-food-price">
	        <?php echo !empty($val_addon['price'])? FunctionsV3::prettyPrice($val_addon['price']) :"-";?>
	        </span>
	        </div> <!--col-->
	    </div> <!--row-->	    
	    <?php $x++;?>
	  <?php endforeach;?>	  
	  <?php endif;?>  <!--endif sub_item-->	  
	  <?php endif;?> 
	  	  
     <?php endforeach;?> <!--endforeach val-->
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
  <div class="col-md-4 col-xs-4 border into-row "></div>
  <div class="col-md-4 col-xs-4 border into-row">
     <input type="submit" value="<?php echo empty($row)?Yii::t("default","add to cart"):Yii::t("default","update cart");?>" 
     class="add_to_cart orange-button upper-text">
  </div>
  <div class="col-md-4 col-xs-4 border into-row">
  <a href="javascript:close_fb();" class="center upper-text green-button inline"><?php echo t("Close")?></a>
  </div>
</div>
<?php endif;?>
  
</div> <!--view-item-wrap-->
</form>
<?php else :?>
<p class="text-danger"><?php echo Yii::t("default","Sorry but we cannot find what you are looking for.")?></p>
<?php endif;?>
<script type="text/javascript">
jQuery(document).ready(function() {	
	var hide_foodprice=$("#hide_foodprice").val();	
	if ( hide_foodprice=="yes"){
		$(".hide-food-price").hide();
		$("span.price").hide();		
		$(".view-item-wrap").find(':input').each(function() {			
			$(this).hide();
		});
	}
	

	var price_cls=$(".price_cls:checked").length; 	
	if ( price_cls<=0){
		var x=0
		$( ".price_cls" ).each(function( index ) {
			if ( x==0){
				dump('set check');
				$(this).attr("checked",true);
			}
			x++;
		});
	}
		

if ( $(".food-gallery-wrap").exists()){
	  $('.food-gallery-wrap').magnificPopup({
      delegate: 'a',
      type: 'image',
      closeOnContentClick: false,
      closeBtnInside: false,
      mainClass: 'mfp-with-zoom mfp-img-mobile',
      image: {
        verticalFit: true,
        titleSrc: function(item) {
          return '';
        }
      },
      gallery: {
        enabled: true
      },
      zoom: {
        enabled: true,
        duration: 300, // don't foget to change the duration also in CSS
        opener: function(element) {
          return element.find('img');
        }
      }      
    });
    	  
}

   $( document ).on( "change", ".qty", function() {	
	 	var value = parseInt($(this).val());
	 	if ( value<=0){
	 		$(this).val(1);
	 	}
   });
   	
});	 /*END READY*/
</script>