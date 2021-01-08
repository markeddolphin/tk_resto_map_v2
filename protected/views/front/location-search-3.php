<form id="frm-location-search" class="frm-location-search">
<?php echo CHtml::hiddenField('location_action','SetLocationSearch')?>
<div class="search-wraps location-search-<?php echo $location_search_type?>">

  <h1><?php echo t("Order food online from 1000+ restaurants!");?></h1>
  <p><?php echo t("Food delivery service that's easy & convenient!")?></p>

  <div class="fields-location-wrap rounded3">
    <div class="inner row">
       <div class="col-sm-6 left-border rounded-corner typhead-city-wrap">
         <i class="ion-ios-arrow-down"></i>
         <?php 
         echo CHtml::hiddenField('postal_code');         
         echo CHtml::hiddenField('location_search_type',$location_search_type);
         ?>
         
         <div class="typeahead__container">
         <div class="typeahead__field">
         <div class="typeahead__query">        
         <?php echo CHtml::textField('location_postalcode','',array(
          'placeholder'=>t("Postal Code/Zip Code"),
          'class'=>"typhead_postalcode rounded-corner",
          'autocomplete'=>"off",
          'required'=>true
         ))?>
         </div>
         </div>
         </div>
         
       </div>       
       <div class="col-sm-6 right-border rounded-end">
         <button type="submit" class="location-search-submit"><?php echo t("SHOW RESTAURANTS")?></button>
       </div>
    </div> <!--inner-->
  </div> <!--fields-location-wrap-->
  
</div> <!--search-wraps-->
</form>