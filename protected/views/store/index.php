<?php
$kr_search_adrress = FunctionsV3::getSessionAddress();

$home_search_text=Yii::app()->functions->getOptionAdmin('home_search_text');
if (empty($home_search_text)){
	$home_search_text=Yii::t("default","Find restaurants near you");
}

$home_search_subtext=Yii::app()->functions->getOptionAdmin('home_search_subtext');
if (empty($home_search_subtext)){
	$home_search_subtext=Yii::t("default","Order Delivery Food Online From Local Restaurants");
}

$home_search_mode=Yii::app()->functions->getOptionAdmin('home_search_mode');
$placholder_search=Yii::t("default","Street Address,City,State");
if ( $home_search_mode=="postcode" ){
	$placholder_search=Yii::t("default","Enter your postcode");
}
$placholder_search=Yii::t("default",$placholder_search);
?>

<?php if ( $home_search_mode=="address" || $home_search_mode=="") :?>
<img class="mobile-home-banner" src="<?php echo assetsURL()."/images/banner.jpg"?>">

<div id="parallax-wrap" class="parallax-container parallax-home" 
data-parallax="scroll" data-position="top" data-bleed="10" 
data-image-src="<?php echo assetsURL()."/images/banner.jpg"?>">
<?php 
if ( $enabled_advance_search=="yes"){
	$this->renderPartial('/front/advance_search',array(
	  'home_search_text'=>$home_search_text,
	  'kr_search_adrress'=>$kr_search_adrress,
	  'placholder_search'=>$placholder_search,
	  'home_search_subtext'=>$home_search_subtext,
	  'theme_search_merchant_name'=>getOptionA('theme_search_merchant_name'),
	  'theme_search_street_name'=>getOptionA('theme_search_street_name'),
	  'theme_search_cuisine'=>getOptionA('theme_search_cuisine'),
	  'theme_search_foodname'=>getOptionA('theme_search_foodname'),
	  'theme_search_merchant_address'=>getOptionA('theme_search_merchant_address'),
	  'map_provider'=>$map_provider
	));
} else $this->renderPartial('/front/single_search',array(
      'home_search_text'=>$home_search_text,
	  'kr_search_adrress'=>$kr_search_adrress,
	  'placholder_search'=>$placholder_search,
	  'home_search_subtext'=>$home_search_subtext,
	  'map_provider'=>$map_provider
));
?>
</div> <!--parallax-container-->
<?php else :?>

<!--SEARCH USING LOCATION-->
<img class="mobile-home-banner" src="<?php echo assetsURL()."/images/b6.jpg"?>">

<div id="parallax-wrap" class="parallax-container parallax-home" 
data-parallax="scroll" data-position="top" data-bleed="10" 
data-image-src="<?php echo assetsURL()."/images/b6.jpg"?>">

  <?php   
  $location_type=getOptionA("admin_zipcode_searchtype");  
  $this->renderPartial('/front/location-search-'.$location_type,array(
   'location_search_type'=>$location_type,
   'location_data'=>FunctionsV3::getSearchByLocationData()
  ));
  ?>

</div> <!--parallax-container-->

<?php endif;?>

<?php if ($theme_hide_how_works<>2):?>
<!--HOW IT WORKS SECTIONS-->
<div class="sections section-how-it-works">
<div class="container">
 <h2><?php echo t("How it works")?></h2>
 <p class="center"><?php echo t("Get your favourite food in 4 simple steps")?></p>
 
 <div class="row">
   <div class="col-md-3 col-sm-3 center">
      <div class="steps step1-icon">
        <img src="<?php echo assetsURL()."/images/step1.png"?>">
      </div>
      <h3><?php echo t("Search")?></h3>
      <p><?php echo t("Find all restaurants available near you")?></p>
   </div>
   <div class="col-md-3 col-sm-3 center">
      <div class="steps step2-icon">
         <img src="<?php echo assetsURL()."/images/step2.png"?>">
      </div>
      <h3><?php echo t("Choose")?></h3>
      <p><?php echo t("Browse hundreds of menus to find the food you like")?></p>
   </div>
   <div class="col-md-3 col-sm-3  center">
      <div class="steps step2-icon">
        <img src="<?php echo assetsURL()."/images/step3.png"?>">
      </div>
      <h3><?php echo t("Pay")?></h3>
      <p><?php echo t("It's quick, secure and easy")?></p>
   </div>
   <div class="col-md-3 col-sm-3  center">
     <div class="steps step2-icon">
       <img src="<?php echo assetsURL()."/images/step4.png"?>">
     </div>
      <h3><?php echo t("Enjoy")?></h3>
      <p><?php echo t("Food is prepared & delivered to your door")?></p>
   </div>   
 </div>

 </div> <!--container-->
</div> <!--section-how-it-works-->
<?php endif;?>


<!--FEATURED RESTAURANT SECIONS-->
<?php if ($disabled_featured_merchant==""):?>
<?php if ( getOptionA('disabled_featured_merchant')!="yes"):?>
<?php if ($res=FunctionsV3::getFeatureMerchant()):?>
<div class="sections section-feature-resto">
<div class="container">

  <?php $cuisine_list=Yii::app()->functions->Cuisine(true);?>

  <h2><?php echo t("Featured Restaurants")?></h2>
  
  <div class="row">
  <?php foreach ($res as $val): //dump($val);?>
  <?php $address= $val['street']." ".$val['city'];
        $address.=" ".$val['state']." ".$val['post_code'];
        
        $ratings=Yii::app()->functions->getRatings($val['merchant_id']);
  ?>   
      
    
    <div class="col-md-5 border-light ">
    
        <div class="col-md-3 col-sm-3">
           <a href="<?php echo Yii::app()->createUrl("/menu/". trim($val['restaurant_slug']))?>">
           <img class="logo-small" src="<?php echo FunctionsV3::getMerchantLogo($val['merchant_id'],$val['logo']);?>">
           </a>
        </div> <!--col-->
        
        <div class="col-md-9 col-sm-9">
        
          <div class="row">
              <div class="col-sm-5">
		          <div class="rating-stars" data-score="<?php echo $ratings['ratings']?>"></div>   		          
	          </div>
	          <div class="col-sm-3 merchantopentag">
	          <?php echo FunctionsV3::merchantOpenTag($val['merchant_id'])?>   	          
	          </div>
	          
	          <div class="col-sm-2 merchantopentag">
	          <a href="javascript:;" data-id="<?php echo $val['merchant_id']?>"  title="<?php echo t("add to your favorite restaurant")?>" class="add_favorites <?php echo "fav_".$val['merchant_id']?>"><i class="ion-android-favorite-outline"></i></a>
	          </div>
	          
          </div>
          
          <a href="<?php echo Yii::app()->createUrl("/menu/". trim($val['restaurant_slug']))?>">
          <h4 class="concat-text"><?php echo clearString($val['restaurant_name'])?></h4>
          </a>
          
          <p class="concat-text">          
          <?php echo FunctionsV3::displayCuisine($val['cuisine'],$cuisine_list);?>
          </p>
          <p class="concat-text"><?php echo $address?></p>                             
          <?php echo FunctionsV3::displayServicesList($val['service'])?>          
        </div> <!--col-->
        
    </div> <!--col-6-->
    
    <div class="col-md-1"></div>
      
  <?php endforeach;?>
  </div> <!--end row-->

  
</div> <!--container-->
</div>
<?php endif;?>
<?php endif;?>
<?php endif;?>
<!--END FEATURED RESTAURANT SECIONS-->


<?php if ($theme_hide_cuisine<>2):?>
<!--CUISINE SECTIONS-->
<?php if ( $list=FunctionsV3::getCuisine() ): ?>
<div class="sections section-cuisine">
<div class="container  nopad">

<div class="col-md-3 nopad">
<img src="<?php echo assetsURL()."/images/cuisine.png"?>" class="img-cuisine">
</div>

<div class="col-md-9  nopad">

  <h2><?php echo t("Browse by cuisine")?></h2>
  <p class="sub-text center"><?php echo t("choose from your favorite cuisine")?></p>
  
  <div class="row">
    <?php $x=1;?>
    <?php foreach ($list as $val): 
    $slug = isset($val['slug'])?$val['slug']:'';
    ?>
    <div class="col-md-4 col-sm-4 indent-5percent nopad">
      <a href="<?php echo Yii::app()->createUrl('/cuisine/'.$slug)?>" 
     class="<?php echo ($x%2)?"even":'odd'?>">
      <?php 
      $cuisine_json['cuisine_name_trans']=!empty($val['cuisine_name_trans'])?json_decode($val['cuisine_name_trans'],true):'';	 
      echo qTranslate($val['cuisine_name'],'cuisine_name',$cuisine_json);
      if($val['total']>0){
      	echo "<span>(".$val['total'].")</span>";
      }
      ?>
      </a>
    </div>   
    <?php $x++;?>
    <?php endforeach;?>
  </div> 

</div>
  
</div> <!--container-->
</div> <!--section-cuisine-->
<?php endif;?>
<?php endif;?>


<?php if ($theme_show_app==2):?>
<!--MOBILE APP SECTION-->
<div id="mobile-app-sections" class="container">
<div class="container-medium">
  <div class="row">
     <div class="col-xs-5 into-row border app-image-wrap">
       <img class="app-phone" src="<?php echo assetsURL()."/images/getapp-2.jpg"?>">
     </div> <!--col-->
     <div class="col-xs-7 into-row border">
       <h2><?php echo getOptionA('website_title')." ".t("in your mobile")?>! </h2>
       <h3 class="green-text"><?php echo t("Get our app, it's the fastest way to order food on the go")?>.</h3>
       
       <div class="row border" id="getapp-wrap">
       <?php if(!empty($theme_app_ios) && $theme_app_ios!="http://"):?>
         <div class="col-xs-4 border">                      
           <a href="<?php echo $theme_app_ios?>" target="_blank">
           <img class="get-app" src="<?php echo assetsURL()."/images/get-app-store.png"?>">
           </a>           
         </div>
         <?php endif;?>
         
         <?php if(!empty($theme_app_android) && $theme_app_android!="http://"):?>
         <div class="col-xs-4 border">
           <a href="<?php echo $theme_app_android?>" target="_blank">
             <img class="get-app" src="<?php echo assetsURL()."/images/get-google-play.png"?>">
           </a>
         </div>
         <?php endif;?>
         
       </div> <!--row-->
       
     </div> <!--col-->
  </div> <!--row-->
  </div> <!--container-medium-->
  
  <div class="mytable border" id="getapp-wrap2">
     <?php if(!empty($theme_app_ios) && $theme_app_ios!="http://"):?>
     <div class="mycol border">
           <a href="<?php echo $theme_app_ios?>" target="_blank">
           <img class="get-app" src="<?php echo assetsURL()."/images/get-app-store.png"?>">
           </a>
     </div> <!--col-->
     <?php endif;?>
     <?php if(!empty($theme_app_android) && $theme_app_android!="http://"):?>
     <div class="mycol border">
          <a href="<?php echo $theme_app_android?>" target="_blank">
             <img class="get-app" src="<?php echo assetsURL()."/images/get-google-play.png"?>">
           </a>
     </div> <!--col-->
     <?php endif;?>
  </div> <!--mytable-->
  
  
</div> <!--container-->
<!--END MOBILE APP SECTION-->
<?php endif;?>


 