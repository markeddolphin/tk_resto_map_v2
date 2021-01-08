
<div class="result-merchant infinite-container" id="restuarant-list">
<?php foreach ($list['list'] as $val):?>
<?php
$merchant_id=$val['merchant_id'];
$distance_covered = isset($val['delivery_distance_covered'])?$val['delivery_distance_covered']:0;
$unit_pretty='';
$distance=''; $delivery_fee = 0; $min_fees=0;

$ratings = array(
  'ratings'=>isset($val['ratings'])?(float)$val['ratings']:0,
  'votes'=>isset($val['ratings_votes'])?(integer)$val['ratings_votes']:0,
 );

$show_delivery_info=false;
if($val['service']==1 || $val['service']==2  || $val['service']==4  || $val['service']==5 ){
	$show_delivery_info=true;
}


if(!$search_by_location){
	try {
		
		$customer_lat = isset($_SESSION['client_location']['lat'])?$_SESSION['client_location']['lat']:0;
		$customer_lng = isset($_SESSION['client_location']['long'])?$_SESSION['client_location']['long']:0;
		
	 	$resp = CheckoutWrapper::getDeliveryDetails(array(
	 	  'merchant_id'=>$merchant_id, 	   	
	 	  'provider'=>$provider,	     
	 	  'from_lat'=>isset($val['latitude'])?$val['latitude']:0,
	 	  'from_lng'=>isset($val['lontitude'])?$val['lontitude']:0,
	 	  'to_lat'=>$customer_lat,
	 	  'to_lng'=>$customer_lng,
	 	  'delivery_charges'=>isset($val['delivery_charges'])?$val['delivery_charges']:0,
	 	  'unit'=>isset($val['distance_unit'])?$val['distance_unit']:'',
	 	  'delivery_distance_covered'=>isset($val['delivery_distance_covered'])?$val['delivery_distance_covered']:0,
	 	  'order_subtotal'=>0,
	 	  'minimum_order'=>isset($val['minimum_order'])?$val['minimum_order']:0
	 	));	  	  	  
	 	$unit_pretty = $resp['pretty_unit'];
	 	$distance = $resp['pretty_distance'];
	 	$delivery_fee = $resp['delivery_fee'];
	 	$min_fees = $resp['min_order'];
	 } catch (Exception $e) {
	 	$distance = $e->getMessage();
	 	$delivery_fee = 0;
	 }	       
} else {
	if($location_data=FunctionsV3::getSearchByLocationData()){
		$delivery_fee = FunctionsV3::getLocationDeliveryFee(
     	   $merchant_id,
     	   $val['delivery_charges'],
     	   $location_data
     	);
	}
}
?>
<div class="infinite-item">
   <div class="inner">
   
   <?php if ( $val['is_sponsored']==2):?>
       <div class="ribbon"><span><?php echo t("Sponsored")?></span></div>
    <?php endif;?>
    
    <?php if ($offer=FunctionsV3::getOffersByMerchant($merchant_id)):?>
       <div class="ribbon-offer"><span><?php echo $offer;?></span></div>
    <?php endif;?>
   
     <div class="row"> 
        <div class="col-md-6 borderx">
        
         <div class="row borderx" style="padding: 10px;padding-bottom:0;">
             <div class="col-md-3 borderx ">		       
		       <a href="<?php echo Yii::app()->createUrl("/menu/". trim($val['restaurant_slug']))?>">
		        <img class="logo-small"src="<?php echo FunctionsV3::getMerchantLogo($merchant_id);?>">
		       </a>
		       <div class="top10"><?php echo FunctionsV3::displayServicesList($val['service'])?></div>		               
		       
		       <div class="top10">
		         <?php FunctionsV3::displayCashAvailable($merchant_id,true,$val['service'])?>
		       </div>
		       
		     </div> <!--col-->
		     <div class="col-md-9 borderx">
		     
		     <div class="mytable">
		         <div class="mycol">
		            <div class="rating-stars" data-score="<?php echo isset($ratings['ratings'])?$ratings['ratings']:0?>"></div>   
		         </div>
		         <div class="mycol">
		            <?php if(is_array($ratings) && count($ratings)>=1):?>
		            <p><?php echo $ratings['votes']." ".t("Reviews")?></p>
		            <?php endif;?>
		         </div>
		         <div class="mycol">
		            <?php echo FunctionsV3::merchantOpenTag($merchant_id)?>                
		         </div>		         		         		         
		         
		         <div class="mycol">
		            <a href="javascript:;" data-id="<?php echo $val['merchant_id']?>"  title="<?php echo t("add to your favorite restaurant")?>" class="add_favorites <?php echo "fav_".$val['merchant_id']?>"><i class="ion-android-favorite-outline"></i></a>
		         </div>		         		         		         
		         
		      </div> <!--mytable-->
	       
		      <h2><?php echo clearString($val['restaurant_name'])?></h2>
	          <p class="merchant-address concat-text"><?php echo $val['merchant_address']?></p>   
	          
	          <p class="cuisine bold">
              <?php echo FunctionsV3::displayCuisine($val['cuisine']);?>
              </p>                
              
             <p>
	        <?php 	        
	        if($show_delivery_info){	
	        	if(!empty($distance)){
			        echo Yii::t("default","Distance : [distance]",array(
			          '[distance]'=>$distance
			        ));
	        	}
	        }
	        ?>
	        </p> 
		     
             <?php if($show_delivery_info):?>
	         <p><?php echo t("Delivery Est")?>: <?php echo !empty($val['delivery_estimation'])?$val['delivery_estimation']:t("not available");?></p>
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
	        if($show_delivery_info){
		        if ($delivery_fee>0){
		             echo t("Delivery Fee").": ".FunctionsV3::prettyPrice($delivery_fee);
		        } else echo  t("Delivery Fee").": ".t("Free Delivery");
	        }
	        ?>
	        </p>
	        
	        <?php if($show_delivery_info):?>	         
	          <p><?php echo t("Minimum Order").": ".FunctionsV3::prettyPrice($min_fees)?></p>	         
	        <?php endif;?>
                                         		      
		       <?php if(method_exists('FunctionsV3','getOffersByMerchantNew')):?>
		        <?php if ($offer=FunctionsV3::getOffersByMerchantNew($merchant_id)):?>
		          <?php foreach ($offer as $offer_value):?>
		            <p><?php echo $offer_value?></p>
		          <?php endforeach;?>
		        <?php endif;?>
		        <?php endif;?>
		       		       
		       <?php if($val['service']!=3):?>
		        <p class="top15"><?php echo FunctionsV3::getFreeDeliveryTag($merchant_id)?></p>
		       <?php endif;?>
		        
		        <a href="<?php echo Yii::app()->createUrl("/menu/". trim($val['restaurant_slug']))?>" 
		        class="orange-button rounded3 medium bottom10 inline-block">
		        <?php echo t("View menu")?>
		        </a>
		                      
		     </div> <!--col-->
         </div> <!--row-->         
         
        </div> <!--col-->
        
        <!--MAP-->
        <div class="col-md-6 with-padleft" style="padding-left:0; border-left:1px solid #C9C7C7;" >
          <div class="browse-list-map active" 
		        data-lat="<?php echo $val['latitude']?>" data-long="<?php echo $val['lontitude']?>">
             
          </div> <!--browse-list-map-->
        </div> <!--col-->
        
     </div> <!--row-->
   </div> <!--inner-->
</div> <!--infinite-item-->
<?php endforeach;?>
</div> <!--result-merchant-->

<div class="search-result-loader">
    <i></i>
    <p><?php echo t("Loading more restaurant...")?></p>
 </div> <!--search-result-loader-->

<?php             
if (isset($cuisine_page)){		
	$slug = isset($_GET['slug'])?$_GET['slug']:'';
	$page_link=Yii::app()->createUrl('/cuisine/'.$slug);
} else $page_link=Yii::app()->createUrl('store/browse/?tab='.$tabs);

 echo CHtml::hiddenField('current_page_url',$page_link);
 require_once('pagination.class.php'); 
 $attributes                 =   array();
 $attributes['wrapper']      =   array('id'=>'pagination','class'=>'pagination');			 
 $options                    =   array();
 $options['attributes']      =   $attributes;
 $options['items_per_page']  =   FunctionsV3::getPerPage();
 $options['maxpages']        =   1;
 $options['jumpers']=false;
 $options['link_url']=$page_link.'&page=##ID##';			
 $pagination =   new pagination( $list['total'] ,((isset($_GET['page'])) ? $_GET['page']:1),$options);		
 $data   =   $pagination->render();
 ?>             