<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Driver Signup"),
   'sub_text'=>t("Thank You for signing up")
));
?>

<div class="sections section-grey">

  <div class="container">
  <div class="inner">    
   
  <div class="box-grey rounded">	  
   <p><?php echo isset($message)?$message:''?></p>
   </div>
  
   </div>
  </div>
  
</div>  