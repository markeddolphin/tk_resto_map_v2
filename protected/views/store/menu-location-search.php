<!--SEARCH USING LOCATION-->
<img class="mobile-home-banner" src="<?php echo assetsURL()."/images/b6.jpg"?>">

<div id="parallax-wrap" class="parallax-container parallax-home" 
data-parallax="scroll" data-position="top" data-bleed="10" 
data-image-src="<?php echo assetsURL()."/images/b6.jpg"?>">

  <?php 
  $location_type=getOptionA("admin_zipcode_searchtype");  
  $this->renderPartial('/front/location-search-'.$location_type);
  ?>

</div> <!--parallax-container-->