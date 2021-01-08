<?php
$show_delivery_info=false;
if($val['service']==1 || $val['service']==2  || $val['service']==4  || $val['service']==5 ){
	$show_delivery_info=true;
}
?>
<div id="search-listview" class="col-md-6 border infinite-item <?php echo $delivery_fee!=true?'free-wrap':'non-free'; ?>">
    <div class="inner">
        
        <?php if ( $val['is_sponsored']==2):?>
        <div class="ribbon"><span><?php echo t("Sponsored")?></span></div>
        <?php endif;?>
        
        <?php if ($offer=FunctionsV3::getOffersByMerchant($merchant_id)):?>
        <div class="ribbon-offer"><span><?php echo $offer;?></span></div>
        <?php endif;?>

        <!--<a href="<?php echo Yii::app()->createUrl('store/menu/merchant/'.$val['restaurant_slug'])?>" >-->
        <a href="<?php echo Yii::app()->createUrl("/menu/". trim($val['restaurant_slug']))?>">
        <img class="logo-medium"src="<?php echo FunctionsV3::getMerchantLogo($merchant_id);?>">
        </a>
        
        <h2 class="concat-text"><?php echo clearString($val['restaurant_name'])?></h2>
        <p class="merchant-address concat-text"><?php echo $val['merchant_address']?></p>
        
        <div class="mytable">
          <div class="mycol a">
             <div class="rating-stars" data-score="<?php echo $ratings['ratings']?>"></div>   
             <?php if(is_array($ratings) && count($ratings)>=1):?>
             <p><?php echo $ratings['votes']." ".t("Reviews")?></p>
             <?php endif;?>
          </div>
          <?php if($show_delivery_info):?>
          <div class="mycol b">
             <?php //echo FunctionsV3::prettyPrice($val['minimum_order'])?>
             <?php echo FunctionsV3::prettyPrice($min_fees)?>
             <p><?php echo t("Minimum Order")?></p>
          </div>
          <?php endif;?>
        </div> <!--mytable-->

        <div class="top25"></div>
        
        <div class="equal_table">
         <div class="col">
            <?php echo FunctionsV3::merchantOpenTag($merchant_id)?>
         </div>          
         <div class="col">
            <a href="javascript:;" data-id="<?php echo $val['merchant_id']?>"  title="<?php echo t("add to your favorite restaurant")?>" class="add_favorites <?php echo "fav_".$val['merchant_id']?>"><i class="ion-android-favorite-outline"></i></a>
         </div>          
        </div>
                
        
        <?php echo FunctionsV3::getFreeDeliveryTag($merchant_id)?>                        
        
        <p class="top15 cuisine concat-text">
        <?php echo FunctionsV3::displayCuisine($val['cuisine']);?>
        </p>                
                                 
        <p>
        <?php 
        if(!$search_by_location){
	        echo Yii::t("default","Distance : [distance]",array(
	          '[distance]'=>$distance
	        ));
        }
        ?>
        </p>
        
        <?php  if($show_delivery_info):?>
        <p><?php echo t("Delivery Est")?>: <?php echo !empty($val['delivery_estimation'])?$val['delivery_estimation']:t("not available")?></p>
        <?php endif;?>
        
        <p>
        <?php         
        if($show_delivery_info){
	        if($distance_covered>0){
	        	echo Yii::t("default","Delivery Distance : [distance]",array(
	        	  '[distance]'=>MapsWrapper::prettyDistance($distance_covered,$unit_pretty)
	        	));
	        } else echo  t("Delivery Distance").": ".t("not available");
        }
        ?>
        </p>
                                
        <p>
        <?php 
        //if($val['service']!=3){
        if($show_delivery_info){
	        if ($delivery_fee){
	             echo t("Delivery Fee").": ".FunctionsV3::prettyPrice($delivery_fee);
	        } else echo  t("Delivery Fee").": ".t("Free Delivery");
        }
        ?>
        </p>
        
        <?php if(method_exists('FunctionsV3','getOffersByMerchantNew')):?>
        <?php if ($offer=FunctionsV3::getOffersByMerchantNew($merchant_id)):?>
          <?php foreach ($offer as $offer_value):?>
            <p><?php echo $offer_value?></p>
          <?php endforeach;?>
        <?php endif;?>
        <?php endif;?>   
        
        <?php echo FunctionsV3::displayServicesList($val['service'])?>          
            
        <a href="<?php echo Yii::app()->createUrl("/menu/". trim($val['restaurant_slug']))?>" 
        class="orange-button rounded3 medium">
        <?php echo t("Order Now")?>
        </a>
        
        
    </div> <!--inner-->
 </div> <!--col-->          