
<div class="search-wraps single-search search-by-postcode">

  <h1><?php echo $home_search_text;?></h1>
  <p><?php echo $home_search_subtext;?></p>
    
  <form method="GET" class="forms-search" id="forms-search" action="<?php echo Yii::app()->createUrl('store/searcharea')?>">
  <div class="search-input-wraps rounded30">
     <div class="row">
        <div class=" border col-sm-11 col-xs-10">
        <?php echo CHtml::textField('zipcode','',array(
         'placeholder'=>$placholder_search,
         'required'=>true,
         'class'=>"search-field zipcode"
        ))?>        
        </div>        
        <div class=" relative border col-sm-1 col-xs-2">
          <button type="submit"><i class="ion-ios-search"></i></button>         
        </div>
     </div>
  </div> <!--search-input-wrap-->
  </form>
  
</div> <!--search-wrapper-->