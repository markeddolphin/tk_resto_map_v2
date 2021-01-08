<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>$data['page_name']
));
?>

<div class="sections section-grey2 section-custom-page" id="section-custom-page">  
 <div class="container">   
   <p><?php echo $data['content']?></p>
 </div> <!--container-->
</div> <!--sections-->