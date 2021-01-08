<?php 
$search_address=isset($_GET['s'])?$_GET['s']:'';
if (isset($_GET['st'])){
	$search_address=$_GET['st'];
}

$p = new CHtmlPurifier();
$search_address  = $p->purify($search_address);

/*SEARCH BY LOCATION*/
$search_by_location=false; $location_data='';
if (FunctionsV3::isSearchByLocation()){
	if($location_data=FunctionsV3::getSearchByLocationData()){		
		$search_by_location=TRUE;		
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
   'total'=>0
));?>

<?php 
$this->renderPartial('/front/order-progress-bar',array(
   'step'=>2,
   'show_bar'=>true
));
echo CHtml::hiddenField('current_page_url',isset($current_page_url)?$current_page_url:'');
?>

<div class="sections section-search-results">
  <div class="container center">
     <h3><?php echo Yii::t("default","Oops. We're having trouble finding that address.")?></h3>
     <p><?php echo Yii::t("default","Please enter your address in one of the following formats and try again. Please do NOT enter your apartment or floor number here.")?></p>
    <p class="bold">- <?php echo Yii::t("default","Street address, city, state")?></p>
    <p class="bold">- <?php echo Yii::t("default","Street address, city")?></p>
    <p class="bold">- <?php echo Yii::t("default","Street address, zip code")?></p>
  </div> <!--container--> 
</div> <!--section-search-results-->   