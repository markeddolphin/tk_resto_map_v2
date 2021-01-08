<?php
$this->renderPartial('/front/default-header',array(
   'h1'=>t("Restaurant by cuisine"),
   'sub_text'=>t("choose from your favorite restaurant")
));
?>

<div class="sections section-grey2 section-browse" id="section-browse">

 <div class="container">
   <?php 
   
   $provider = array(); 
   if(!$search_by_location){
       $provider = FunctionsV3::getMapProvider(); 
       MapsWrapper::init($provider);                 	
   } 
   if (is_array($list['list']) && count($list['list'])>=1){
   	  $this->renderPartial('/front/browse-list',array(
		   'list'=>$list,
		   'cuisine_page'=>2,
		   'category'=>$category,
		   'provider'=>$provider,
		   'search_by_location'=>$search_by_location
	  ));
   } else echo '<p class="text-danger">'.t("No restaurant found").'</p>';
   ?>
 </div> <!--container--> 

</div><!-- sections-->