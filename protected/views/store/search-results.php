<?php 
$search_address=isset($_GET['s'])?$_GET['s']:'';
if (isset($_GET['st'])){
	$search_address=$_GET['st'];
}

$p = new CHtmlPurifier();
$search_address  = $p->purify($search_address);

/*dump($data);
die();
*/
/*SEARCH BY LOCATION*/
$location_data='';
if ($search_by_location){	
	if($location_data=FunctionsV3::getSearchByLocationData()){				
		switch ($location_data['location_type']) {
			case 1:
				$search_address= $location_data['location_city']." ".$location_data['location_area'];
				break;
		
			case 2:
			    $search_address = $location_data['city_name']." ".$location_data['state_name'];
			    break;
			    
			case 3:
				$search_address=$location_data['postal_code'];
				break;
					
			default:
				break;
		}	    
	}	
}
$this->renderPartial('/front/search-header',array(
   'search_address'=>$search_address,
   'total'=>$data['total']
));?>

<?php 
$this->renderPartial('/front/order-progress-bar',array(
   'step'=>2,
   'show_bar'=>true
));

echo CHtml::hiddenField('clien_lat',$data['client']['lat']);
echo CHtml::hiddenField('clien_long',$data['client']['long']);
?>

<div class="search-map-results" id="search-map-results">  
</div> <!--search-map-results-->

<div class="sections section-search-results">

  <div class="container">

   <div class="row">
   
     <div class="col-md-3 border search-left-content" id="mobile-search-filter">
       
        <?php if ( $enabled_search_map=="yes"):?>
        <a href="javascript:;" class="search-view-map green-button block center upper rounded">
        <?php echo t("View by map")?>
        </a>
        <?php endif;?>
        
        <div class="filter-wrap rounded2 <?php echo $enabled_search_map==""?"no-marin-top":""; ?>">
                
          <button type="button" class="close modal-close-btn" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>  
        
           <p class="bold"><?php echo t("Filters")?></p>
           
           
           <!--FILTER MERCHANT NAME-->       
           <?php if (!empty($restaurant_name)):?>                      
           <a href="<?php echo FunctionsV3::clearSearchParams('restaurant_name')?>">[<?php echo t("Clear")?>]</a>
           <?php endif;?>    
           <div class="filter-box">
	           <a href="javascript:;">	             
	             <span>
	             <i class="<?php echo $fc==2?"ion-ios-arrow-thin-down":'ion-ios-arrow-thin-right'?>"></i>
	             <?php echo t("Search by name")?>
	             </span>   
	             <b></b>
	           </a>
	           <ul class="<?php echo $fc==2?"hide":''?>">
	              <li>
	              <form method="POST" onsubmit="return research_merchant();">
		              <div class="search-input-wraps rounded30">
		              <div class="row">
				        <div class="col-md-10 col-xs-10">
				        <?php echo CHtml::textField('restaurant_name',$restaurant_name,array(
				          'required'=>true,
				          'placeholder'=>t("enter restaurant name")
				        ))?>
				        </div>        
				        <div class="col-md-2 relative col-xs-2 ">
				          <button type="submit"><i class="fa fa-search"></i></button>         
				        </div>
				     </div>
			     </div>
			     </form>
	              </li>
	           </ul>
           </div> <!--filter-box-->
           <!--END FILTER MERCHANT NAME-->           
           
           
           
           <!--FILTER DELIVERY FEE-->           
           <div class="filter-box">
	           <a href="javascript:;">	             
	             <span>
	             <i class="<?php echo $fc==2?"ion-ios-arrow-thin-down":'ion-ios-arrow-thin-right'?>"></i>
	             <?php echo t("Delivery Fee")?>
	             </span>   
	             <b></b>
	           </a>
	            <ul class="<?php echo $fc==2?"hide":''?>">
	              <li>
	              <?php 
		          echo CHtml::checkBox('filter_by[]',false,array(
		          'value'=>'free-delivery',
		          'class'=>"filter_promo icheck"
		          ));
		          ?>
	              <?php echo t("Free Delivery")?>
	              </li>
	           </ul>
           </div> <!--filter-box-->
           <!--END FILTER DELIVERY FEE-->
           
           <!--FILTER DELIVERY -->
           <?php if (!empty($filter_delivery_type)):?>                      
           <a href="<?php echo FunctionsV3::clearSearchParams('filter_delivery_type')?>">[<?php echo t("Clear")?>]</a>
           <?php endif;?>
           <?php if ( $services=Yii::app()->functions->Services() ):?>
           <div class="filter-box">
	           <a href="javascript:;">	             
	             <span>
	             <i class="<?php echo $fc==2?"ion-ios-arrow-thin-down":'ion-ios-arrow-thin-right'?>"></i>
	             <?php echo t("By Delivery")?>
	             </span>   
	             <b></b>
	           </a>
	           <ul class="<?php echo $fc==2?"hide":''?>">
	             <?php foreach ($services as $key=> $val):?>
	              <li>	           	              
	              <?php 
		           echo CHtml::radioButton('filter_delivery_type',
		           $filter_delivery_type==$key?true:false
		           ,array(
		          'value'=>$key,
		          'class'=>"filter_by filter_delivery_type icheck"
		          ));
		          ?>
		          <?php echo $val;?>   
	              </li>
	             <?php endforeach;?> 
	           </ul>
           </div> <!--filter-box-->
           <?php endif;?>
           <!--END FILTER DELIVERY -->
           
           <!--FILTER CUISINE-->
           <?php if (!empty($filter_cuisine)):?>                      
           <a href="<?php echo FunctionsV3::clearSearchParams('filter_cuisine')?>">[<?php echo t("Clear")?>]</a>
           <?php endif;?>
           <?php if ( $cuisine=Yii::app()->functions->Cuisine(false)):?>
           <div class="filter-box">
	           <a href="javascript:;">	             
	             <span>
	             <i class="<?php echo $fc==2?"ion-ios-arrow-thin-down":'ion-ios-arrow-thin-right'?>"></i>
	             <?php echo t("By Cuisines")?>
	             </span>   
	             <b></b>
	           </a>
	            <ul class="<?php echo $fc==2?"hide":''?>">
	             <?php foreach ($cuisine as $val): ?>
	              <li>
		           <?php 
		           $cuisine_json['cuisine_name_trans']=!empty($val['cuisine_name_trans'])?
	    		   json_decode($val['cuisine_name_trans'],true):'';
	    		   
		           echo CHtml::checkBox('filter_cuisine[]',
		           in_array($val['cuisine_id'],(array)$filter_cuisine)?true:false
		           ,array(
		           'value'=>$val['cuisine_id'],
		           'class'=>"filter_by icheck filter_cuisine"
		           ));
		          ?>
	              <?php echo qTranslate($val['cuisine_name'],'cuisine_name',$cuisine_json)?>
	              </li>
	             <?php endforeach;?> 
	           </ul>
           </div> <!--filter-box-->
           <?php endif;?>
           <!--END FILTER CUISINE-->
           
           
           <!--MINIUM DELIVERY FEE-->           
           <?php if (!empty($filter_minimum)):?>                      
           <a href="<?php echo FunctionsV3::clearSearchParams('filter_minimum')?>">[<?php echo t("Clear")?>]</a>
           <?php endif;?>
           <?php if ( $minimum_list=FunctionsV3::minimumDeliveryFee()):?>
           <div class="filter-box">
	           <a href="javascript:;">	             
	             <span>
	             <i class="<?php echo $fc==2?"ion-ios-arrow-thin-down":'ion-ios-arrow-thin-right'?>"></i>
	             <?php echo t("Minimum Delivery")?>
	             </span>   
	             <b></b>
	           </a>
	            <ul class="<?php echo $fc==2?"hide":''?>">
	             <?php foreach ($minimum_list as $key=>$val):?>
	              <li>
		           <?php 
		          echo CHtml::radioButton('filter_minimum[]',
		          $filter_minimum==$key?true:false
		          ,array(
		          'value'=>$key,
		          'class'=>"filter_by_radio filter_minimum icheck"
		          ));
		          ?>
	              <?php echo $val;?>
	              </li>
	             <?php endforeach;?> 
	           </ul>
           </div> <!--filter-box-->
           <?php endif;?>
           <!--END MINIUM DELIVERY FEE-->
           
        </div> <!--filter-wrap-->
        
     </div> <!--col search-left-content-->
     
     <div class="col-md-9 border search-right-content">
          
     <?php echo CHtml::hiddenField('sort_filter',$sort_filter)?>
     <?php echo CHtml::hiddenField('display_type',$display_type)?>     
     
         <div class="sort-wrap">
           <div class="row">           
              <div class="col-md-6 col-xs-6 border ">	           
	           <?php 
	           $filter_list=array(
	             'open_status'=>t("Open"),
	             'restaurant_name'=>t("Name"),
	             'ratings'=>t("Rating"),
	             'minimum_order'=>t("Minimum"),
	             'distance'=>t("Distance")
	           );
	           if (isset($_GET['st'])){
	           	   unset($filter_list['distance']);
	           }
	           if(FunctionsV3::isSearchByLocation()){
	           	  unset($filter_list['distance']);
	           }
	           echo CHtml::dropDownList('sort-results',$sort_filter,$filter_list,array(
	             'class'=>"sort-results selectpicker",
	             'title'=>t("Sort By")
	           ));
	           ?>
              </div> <!--col-->
              <div class="col-md-6 col-xs-6 border">                
               
                          
                <a href="<?php echo FunctionsV3::clearSearchParams('','display_type=listview')?>" 
	           class="display-type orange-button block center rounded 
	           <?php echo $display_type=="gridview"?'inactive':''?>" 
		          data-type="listview">
                <i class="fa fa-th-list"></i>
                </a>
                
                <a href="<?php echo FunctionsV3::clearSearchParams('','display_type=gridview')?>" 
		          class="display-type orange-button block center rounded mr10px 
	             <?php echo $display_type=="listview"?'inactive':''?>" 
		          data-type="gridview">
                <i class="fa fa-th-large"></i>
                </a>           
                
                <a href="javascript:;" id="mobile-filter-handle" class="orange-button block center rounded mr10px">
                  <i class="fa fa-filter"></i>
                </a>    
                
                <?php if ( $enabled_search_map=="yes"):?>
                <a href="javascript:;" id="mobile-viewmap-handle" class="orange-button block center rounded mr10px">
                  <i class="ion-ios-location"></i>
                </a>    
                <?php endif;?>
                
                <div class="clear"></div>
                
              </div>
           </div> <!--row-->
         </div>  <!--sort-wrap-->  
         
         
         <!--MERCHANT LIST -->
                  
         <div class="result-merchant">
             <div class="row infinite-container">
             
             
             <?php if ($data):?>                          
                 <?php 
                 $provider = array(); 
                 if(!$search_by_location){
                 	$provider = FunctionsV3::getMapProvider(); 
                 	MapsWrapper::init($provider);                 	
                 } 
                 ?>
                 
	             <?php foreach ($data['list'] as $val):?>
	             <?php	          
	             $distance=0; $delivery_fee=0; $ratings = array(); $unit_pretty = ''; $min_fees=0;
	             $merchant_id=$val['merchant_id'];   	    

	             $ratings = array(
	              'ratings'=>isset($val['ratings'])?(float)$val['ratings']:0,
	              'votes'=>isset($val['ratings_votes'])?(integer)$val['ratings_votes']:0,
	             );
	                        
	             if(!$search_by_location){
		             try {		         		             	
		             	$resp = CheckoutWrapper::getDeliveryDetails(array(
		             	  'merchant_id'=>$merchant_id,
		             	  'provider'=>$provider,	             	  
		             	  'from_lat'=>isset($val['latitude'])?$val['latitude']:0,
		             	  'from_lng'=>isset($val['lontitude'])?$val['lontitude']:0,
		             	  'to_lat'=>$data['client']['lat'],
		             	  'to_lng'=>$data['client']['long'],
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
	             	$unit_pretty = MapsWrapper::prettyUnit($val['distance_unit']);	             	
	             	$delivery_fee = FunctionsV3::getLocationDeliveryFee(
	             	   $merchant_id,
	             	   $val['delivery_charges'],
	             	   $location_data
	             	);
	             }      
	              
	             if ( $display_type=="listview"){
	             	 $this->renderPartial('/front/search-list-2',array(
					   'data'=>$data,
					   'val'=>$val,
					   'merchant_id'=>$merchant_id,
					   'ratings'=>$ratings,					  					   
					   'distance'=>$distance,
					   'distance_covered'=> (float) $val['delivery_distance_covered'],
					   'unit_pretty'=>$unit_pretty,
					   'delivery_fee'=> (float) $delivery_fee,
					   'search_by_location'=>$search_by_location,
					   'min_fees'=>(float) $min_fees
					 ));
	             } else {
		             $this->renderPartial('/front/search-list-1',array(
					  'data'=>$data,
					   'val'=>$val,
					   'merchant_id'=>$merchant_id,
					   'ratings'=>$ratings,					  					   
					   'distance'=>$distance,
					   'distance_covered'=> (float) $val['delivery_distance_covered'],
					   'unit_pretty'=>$unit_pretty,
					   'delivery_fee'=> (float) $delivery_fee,
					   'search_by_location'=>$search_by_location,
					   'min_fees'=>(float) $min_fees
					 ));
	             }
				 ?>
				              
	              <?php endforeach;?>     
              <?php else :?>     
              <p class="center top25 text-danger"><?php echo t("No results with your selected filters")?></p>
              <?php endif;?>
                                                   
             </div> <!--row-->                
             
             <div class="search-result-loader">
                <i></i>
                <p><?php echo t("Loading more restaurant...")?></p>
             </div> <!--search-result-loader-->
             
             <?php                         
             if (!isset($current_page_url)){
             	$current_page_url='';
             }
             if (!isset($current_page_link)){
             	$current_page_link='';
             }
             echo CHtml::hiddenField('current_page_url',$current_page_url);
             require_once('pagination.class.php'); 
             $attributes                 =   array();
			 $attributes['wrapper']      =   array('id'=>'pagination','class'=>'pagination');			 
			 $options                    =   array();
			 $options['attributes']      =   $attributes;
			 $options['items_per_page']  =   FunctionsV3::getPerPage();
			 $options['maxpages']        =   1;
			 $options['jumpers']=false;
			 $options['link_url']=$current_page_link.'&page=##ID##';			
			 $pagination =   new pagination( $data['total'] ,((isset($_GET['page'])) ? $_GET['page']:1),$options);		
			 $data   =   $pagination->render();
             ?>             
                    
         </div> <!--result-merchant-->
     
     </div> <!--col search-right-content-->
     
   </div> <!--row-->
  
  </div> <!--container-->
</div> <!--section-search-results-->