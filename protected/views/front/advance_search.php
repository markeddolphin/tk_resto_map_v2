
<div class="search-wraps advance-search">

 <input type="hidden" name="find_restaurant_by_address" 
id="find_restaurant_by_address" value="<?php echo t($home_search_text)?>" >

  <ul class="search-menu">
  
   <?php if ($theme_search_merchant_address!=2):?> 
   <li><a href="javascript:;" class="selected byaddress" data-tab="tab-byaddress"><i></i></a></li>
   <?php endif;?>
   
   <?php if ($theme_search_merchant_name!=2):?>
   <li><a href="javascript:;" class="byname" data-tab="tab-byname"><i></i></a></li>
   <?php endif;?>
   
   <?php if ($theme_search_street_name!=2):?>
   <li><a href="javascript:;" class="bystreet" data-tab="tab-bystreet"><i></i></a></li>
   <?php endif;?>
   
   <?php if ($theme_search_cuisine!=2):?>
   <li><a href="javascript:;" class="bycuisine" data-tab="tab-bycuisine"><i></i></a></li>
   <?php endif;?>
   
   <?php if ($theme_search_foodname!=2):?>
   <li><a href="javascript:;" class="byfood" data-tab="tab-byfood"><i></i></a></li>   
   <?php endif;?>
  </ul>
  
  <div class="border mobile-search-menu mytable">    
    
     <?php if ($theme_search_merchant_address!=2):?> 
     <a href="javascript:;" class="mycol selected byaddress" data-tab="tab-byaddress" ><i class="ion-record"></i></a>
     <?php endif;?>
     
     <?php if ($theme_search_merchant_name!=2):?>
     <a href="javascript:;" class="mycol byname" data-tab="tab-byname" ><i class="ion-record"></i></a>
     <?php endif;?>
     
     <?php if ($theme_search_street_name!=2):?>
     <a href="javascript:;" class="mycol bystreet" data-tab="tab-bystreet" ><i class="ion-record"></i></a>
     <?php endif;?>
     
     <?php if ($theme_search_cuisine!=2):?>
     <a href="javascript:;" class="mycol bycuisine" data-tab="tab-bycuisine"><i class="ion-record"></i></a>
     <?php endif;?>
     
     <?php if ($theme_search_foodname!=2):?>
     <a href="javascript:;" class="mycol byfood" data-tab="tab-byfood"><i class="ion-record"></i></a>
     <?php endif;?>
  </div> <!--end row-->
  
  <h1 class="home-search-text"><?php echo t($home_search_text);?></h1>
  <p class="home-search-subtext"><?php echo t($home_search_subtext);?></p>  
       
  <form method="GET" class="tab-byaddress forms-search" id="forms-search" action="<?php echo Yii::app()->createUrl('store/searcharea')?>">
  <div class="search-input-wraps rounded30">
     <div class="row">
        <div class="col-sm-11 col-xs-10">
        
        <?php if($map_provider['provider']=="mapbox"):?>
            <div class="mapbox_s_goecoder" id="mapbox_s_goecoder"></div>
        <?php else:?>
        <?php echo CHtml::textField('s',$kr_search_adrress,array(
         'placeholder'=>$placholder_search,
         'required'=>true
        ))?>        
        <?php endif;?>
        
        </div>        
        <div class="col-sm-1 col-xs-2 relative">
          <button type="submit"><i class="ion-ios-search"></i></button>         
        </div>
     </div>
  </div> <!--search-input-wrap-->
  </form>
      
  <form method="GET" class="tab-byname forms-search" id="forms-search" action="<?php echo Yii::app()->createUrl('store/searcharea')?>">
  <?php echo CHtml::hiddenField('st',$kr_search_adrress,array('class'=>"st"));	?>
  <div class="search-input-wraps rounded30">
     <div class="row">
        <div class="col-sm-11 col-xs-10">
        
        <div class="typeahead__container">
         <div class="typeahead__field">
         <div class="typeahead__query">                
        <?php echo CHtml::textField('restaurant-name','',array(
         'placeholder'=>t("Restaurant name"),
         'required'=>true,
         'class'=>"search-field typhead_restaurant_name",
         'autocomplete'=>"off",
        ))?>        
        </div>
         </div>
         </div>
        
        </div>        
        <div class="col-sm-1 col-xs-2 relative">
          <button type="submit"><i class="ion-ios-search"></i></button>         
        </div>
     </div>
  </div> <!--search-input-wrap-->
  </form>  
  
  <form method="GET" class="tab-bystreet forms-search" id="forms-search" action="<?php echo Yii::app()->createUrl('store/searcharea')?>">
  <?php echo CHtml::hiddenField('st',$kr_search_adrress,array('class'=>"st"));	?>
  <div class="search-input-wraps rounded30">
     <div class="row">
        <div class="col-sm-11 col-xs-10">
        
        <div class="typeahead__container">
         <div class="typeahead__field">
         <div class="typeahead__query">                 
        <?php echo CHtml::textField('street-name','',array(
         'placeholder'=>t("Street name"),
         'required'=>true,
         'class'=>"search-field typhead_street_name",
         'autocomplete'=>"off",
        ))?>        
         </div>
         </div>
         </div>
        
        
        </div>        
        <div class="col-sm-1 col-xs-2 relative">
          <button type="submit"><i class="ion-ios-search"></i></button>         
        </div>
     </div>
  </div> <!--search-input-wrap-->
  </form>    
  
  <form method="GET" class="tab-bycuisine forms-search" id="forms-search" action="<?php echo Yii::app()->createUrl('store/searcharea')?>">
  <?php echo CHtml::hiddenField('st',$kr_search_adrress,array('class'=>"st"));	?>
  <div class="search-input-wraps rounded30">
     <div class="row">
        <div class="col-sm-11 col-xs-10">
        
        <div class="typeahead__container">
         <div class="typeahead__field">
         <div class="typeahead__query">                 
        <?php echo CHtml::textField('category','',array(
         'placeholder'=>t("Enter Cuisine"),
         'required'=>true,
         'class'=>"search-field typhead_cuisine",
         'autocomplete'=>"off",
        ))?>        
          </div>
         </div>
         </div>
        
        </div>        
        <div class="col-sm-1 col-xs-2 relative">
          <button type="submit"><i class="ion-ios-search"></i></button>         
        </div>
     </div>
  </div> <!--search-input-wrap-->
  </form>      
  
  <form method="GET" class="tab-byfood forms-search" id="forms-search" action="<?php echo Yii::app()->createUrl('store/searcharea')?>">
  <?php echo CHtml::hiddenField('st',$kr_search_adrress,array('class'=>"st"));	?>
  <div class="search-input-wraps rounded30">
     <div class="row">
        <div class="col-sm-11 col-xs-10">
        
        <div class="typeahead__container">
         <div class="typeahead__field">
         <div class="typeahead__query">                 
        <?php echo CHtml::textField('foodname','',array(
         'placeholder'=>t("Enter Food Name"),
         'required'=>true,
         'class'=>"search-field typhead_foodname",
         'autocomplete'=>"off",
        ))?>        
           </div>
         </div>
         </div>
        
        </div>        
        <div class="col-sm-1 col-xs-2 relative">
          <button type="submit"><i class="ion-ios-search"></i></button>         
        </div>
     </div>
  </div> <!--search-input-wrap-->
  </form>        
  
</div> <!--search-wrapper-->