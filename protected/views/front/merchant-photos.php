<div class="box-grey rounded " style="margin-top:0;">

 <?php if (is_array($gallery) && count($gallery)>=1):?>
   <div id="photos" class="merchant-gallery-wrap">
   <?php foreach ($gallery as $val):?>
    <a href="<?php echo uploadURL()."/".$val?>" title="">
	  <img src="<?php echo uploadURL()."/".$val?>">
    </a>
	<?php endforeach;?>	  
	</div>
 <?php else :?>
  <p class="text-danger"><?php echo t("gallery not available")?></p>
 <?php endif;?>
 
</div> <!--box-grey-->