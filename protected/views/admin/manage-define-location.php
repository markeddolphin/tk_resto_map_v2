
<div class="uk-width-1">
<a href="<?php echo Yii::app()->createUrl('admin/managelocation')?>" class="uk-button">
<i class="fa fa-list"></i> <?php echo Yii::t("default","List")?>
</a>
</div>

<div style="padding-top:30px;">

<div style="margin-bottom:5px;">
  <div style="float:left;width:50%;"><h3><?php echo $data['country_name']?></h3></div>
  <div style="float:left;width:50%;text-align:right;">
    <a href="javascript:;" class="collapse-state-list">
      <i class="fa fa-compress" aria-hidden="true"></i>
    </a>
  </div>
  <div class="clear"></div>
</div>

<?php echo CHtml::hiddenField('country_id',$data['country_id'])?>

<a href="javascript:;" class="button-border add_state" data-id="<?php echo $data['country_id']?>">
 <i class="fa fa-plus"></i> <?php echo t("ADD STATE / REGION")?>
</a>

<div class="location-list">
</div>


</div> <!--padding-top-->