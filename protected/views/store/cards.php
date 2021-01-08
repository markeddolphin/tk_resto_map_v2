
<div class="page-right-sidebar">
  <div class="main cc_page">
  <div class="inner">
  
<div class="uk-width-1">
<a href="<?php echo Yii::app()->request->baseUrl; ?>/store/Cards/Do/Add" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo Yii::app()->request->baseUrl; ?>/store/Cards" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>  

  <?php $client_id=Yii::app()->functions->getClientId();?>
  <?php if (is_numeric($client_id)):?>
  
  <form id="frm_table_list" method="POST" >
  <input type="hidden" name="action" id="action" value="ClientCCList">
  <?php echo CHtml::hiddenField('currentController','store')?>

<input type="hidden" name="tbl" id="tbl" value="client_cc">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="cc_id">
<input type="hidden" name="slug" id="slug" value="store/Cards">
  
  <table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
  <thead>
  <tr>
   <th><?php echo Yii::t("default","Card name")?></th>
   <th><?php echo Yii::t("default","Card number")?></th>
   <th><?php echo Yii::t("default","Expiration")?></th>
  </tr>
  </thead>
  </table>
  
  <?php else :?>
  <p class="uk-text-danger"><?php echo Yii::t("default","Profile not available")?></p>
  <?php endif;?>

  </div>
  </div> <!--main-->
</div> <!--page-right-sidebar--> 