

<div class="parallax-search" 
data-parallax="scroll" data-position="top" data-bleed="0" 
data-image-src="<?php echo assetsURL()."/images/b-4.jpg"?>">

<div class="search-wraps">
	<h1><?php echo t("oops something went wrong")?></h1>	
	<p></p>
</div> <!--search-wraps-->

</div> <!--parallax-container-->


<div class="sections">
   <div class="container center">
      <h3 class="text-danger"><?php echo isset($message)?$message:''?></h3>
   </div>
</div>