<?php

?>

<?php if ($show_bar):?>
<div class="order-progress-bar">
  <div class="container">
      <div class="row">
      
        <div class="col-md-3 col-xs-3 ">
          <a class="active" href="<?php echo Yii::app()->createUrl('/store/merchantsignup')?>">
          <?php echo t("Select Package")?>
          </a>  
        </div>
        
        <div class="col-md-3 col-xs-3">
           <a class="<?php echo $step>=2?"active":"inactive"; echo $step==2?" current":"";?>" 
           href="javascript:;">
           <?php echo t("Merchant information")?></a>
        </div>
        
        <div class="col-md-3 col-xs-3">
        <a class="<?php echo $step>=3?"active":"inactive"; echo $step==3?" current":"";?> "
         href="javascript:;">
        <?php echo t("Payment Information")?></a>
        </div>
        
        <div class="col-md-3 col-xs-3">
        <a class="<?php echo $step>=4?"active":"inactive"; echo $step==4?" current":"";?> "
         href="javascript:;"><?php echo t("Activation")?></a>
        </div>
        
      </div>
  </div> <!--container-->
  
   <div class="border progress-dot mytable">    
     <a href="<?php echo Yii::app()->createUrl('/store/merchantsignup')?>" class="mycol selected" >
     <i class="ion-record"></i>
     </a>
     
     <a href="javascript:;" class="mycol 
     <?php echo $step>=2?"selected":'';?>" ><i class="ion-record"></i>
     </a>
     
     <a href="javascript:;" class="mycol <?php echo $step>=3?"selected":'';?>" >
     <i class="ion-record"></i>
     </a>
     
     <a href="javascript:;" class="mycol <?php echo $step>=4?"selected":'';?>">
     <i class="ion-record"></i>
     </a>
     
  </div> <!--end progress-dot-->
  
</div> <!--order-progress-bar-->
<?php endif;?>