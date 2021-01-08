<form id="frm-location-search" class="frm-location-search">
<?php echo CHtml::hiddenField('location_action','SetLocationSearch')?>
<div class="search-wraps location-search-1">

  <h1><?php echo t("Order food online from 1000+ restaurants!");?></h1>
  <p><?php echo t("Food delivery service that's easy & convenient!")?></p>

  <div class="fields-location-wrap rounded3">
    <div class="inner row">
       <div class="col-sm-4 left-border rounded-corner typhead-city-wrap">
         <i class="ion-ios-arrow-down"></i>
         <?php 
         $city_id = isset($location_data['city_id'] )?(integer)$location_data['city_id']:0;
         $area_id = isset($location_data['area_id'] )?(integer)$location_data['area_id']:0;
         $city_name = isset($location_data['city_name'] )?(string)$location_data['city_name']:'';
         $area_name = isset($location_data['location_area'] )?(string)$location_data['location_area']:'';         
         echo CHtml::hiddenField('city_id',$city_id);
         echo CHtml::hiddenField('city_name',$city_name);
         echo CHtml::hiddenField('area_id',$area_id);
         echo CHtml::hiddenField('location_search_type',$location_search_type);         
         ?>
         
        <div class="typeahead__container">
        <div class="typeahead__field">
        <div class="typeahead__query">                
         <?php echo CHtml::textField('location_city',$city_name,array(
          'placeholder'=>t("City"),
          'class'=>"typhead_city rounded-corner",
          'autocomplete'=>"off",
          'required'=>true
         ))?>
       </div>
       </div>
       </div>  
         
       </div>
       <div class="col-sm-4 left-border with-location-loader">
         <div class="location-loader"></div>
         
         <div class="typeahead__container">
         <div class="typeahead__field">
         <div class="typeahead__query">
         <?php echo CHtml::textField('location_area',$area_name,array(
           'placeholder'=>t("District / Area"),
           'class'=>"typhead_area",
           'autocomplete'=>"off",
           //'required'=>true
         ))?>
         </div>
         </div>
         </div>  
         
       </div>
       <div class="col-sm-4 right-border rounded-end">
         <button type="submit" class="location-search-submit"><?php echo t("SHOW RESTAURANTS")?></button>
       </div>
    </div> <!--inner-->
  </div> <!--fields-location-wrap-->
  
</div> <!--search-wraps-->
</form>