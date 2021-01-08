<?php
$this->renderPartial('/front/default-header',array(
   'h1'=>t("Password change"),   
));
?>
<div class="sections section-grey2 ">
 <div class="container">
  <div class="row">
    <div class="col-md-6 white_bg" style="margin-left:25%;padding:20px;">
       <p class="text-success"><?php echo t("You have successfully change your password")?></p>
       <a href="<?php echo Yii::app()->createUrl("store/signup")?>"><?php echo t("Click here to login")?></a>
    </div>
  </div>
 </div>
</div>