<?php 
if ($header){
	$this->renderPartial('/front/default-header');
}
?>

<?php if ($header):?>
<div class="sections section-grey">
<div class="container">
<?php endif;?>

<div class="section-notfound">

  <div class="row">
	  <div class="col-md-7  text-center">
		  <h1><?php echo t("404")?></h1>
		  <h3><?php echo t("Sorry but we cannot find what you are looking for")?></h3>
		  
		  <p>
		  <?php echo t("Page doesn't exist or some other error occured. Go to our")?>
		  <a class="orange-text bold" href="<?php echo Yii::app()->createUrl('/store')?>"><?php echo t("home")?></a> <?php echo t("or go back to")?> 
		  <a href="javascript:window.history.back();" class="orange-text bold"> <?php echo t("previous page")?></a>
		  </p>
	  </div> <!--col-->
	  
	  <div class="col-md-5 ">
	  <img src="<?php echo assetsURL()."/images/404.png"?>" />
	  </div>
  
  </div><!-- row-->

</div> <!--section-notfound-->

<?php if ($header):?>
</div>
</div>
<?php endif;?>