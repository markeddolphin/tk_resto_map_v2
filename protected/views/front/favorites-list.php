
<div class="box-grey rounded section-order-history" style="margin-top:0;">

<div class="bottom10">
<?php echo FunctionsV3::sectionHeader('Your favorite restaurant');?>
</div>

<table class="table table-striped  favorites_list">
  <?php if(is_array($data) && count($data)>=1):?>
  <?php foreach ($data as $val):?>
  <?php $ratings=Yii::app()->functions->getRatings($val['merchant_id']);  ?>
  
  <tr class="tr_fav_<?php echo $val['id']?>">
  <td>
   
    <div class="equal_table">
      <div class="col" style="width: 50%;">
         <a href="<?php echo  Yii::app()->createUrl('/')."/menu-".$val['restaurant_slug']?>">
          <h5><?php echo $val['restaurant_name']?></h5>
         </a>
      </div>
      <div class="col">
        <div class="rating-stars" data-score="<?php echo $ratings['ratings']?>"></div> 
      </div>
    </div>
            
  
   <p class="text-small"><?php echo Yii::t("default","Added [date]",array(
     '[date]'=>FunctionsV3::prettyDate($val['date_created'])." ".FunctionsV3::prettyTime($val['date_created'])
   ));?></p>
  </td>
  <td><a href="javascript:;" class="remove_fav" data-id="<?php echo $val['id']?>"><i class="ion-ios-trash-outline"></i></a></td>
  </tr>
  
 <?php endforeach;?> 
 <?php else :?>
  <tr>
   <td colspan="2"><?php echo t("No results")?></td>
  </tr>
 <?php endif;?>
    
</table>

</div>