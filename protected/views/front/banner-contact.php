
<div class="mobile-banner-wrap relative">
 <div class="layer"></div>
 <img class="mobile-banner" src="<?php echo assetsURL()."/images/banner-5-mobile.jpg"?>">
</div>

<div  id="parallax-wrap" class="parallax-search" 
data-parallax="scroll" data-position="top" data-bleed="10" 
data-image-src="<?php echo assetsURL()."/images/b-2.jpg"?>">

<div class="search-wraps contact-banner">
	<h1><?php echo $h1?></h1>
	
	<?php if (!empty($sub_text)):?>
	<p><i class="ion-ios-location"></i> <?php echo $sub_text?></p>
	<?php endif;?>
	
	<?php if (!empty($contact_phone)):?>
	<p class="text-small"> 
	  <span class="relative"><i class="ion-iphone"></i></span>
	  <?php echo $contact_phone?>
	</p>
	<?php endif;?>
	
	<?php if (!empty($contact_email)):?>
	<p class="text-small">
	  <span class="relative"><i class="ion-email"></i></span>
	  <?php echo $contact_email?>
	</p>
	<?php endif;?>
	
</div> <!--search-wraps-->

</div> <!--parallax-container-->