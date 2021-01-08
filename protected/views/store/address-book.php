
<div class="page-right-sidebar">
  <div class="main cc_page">
  <div class="inner">
  
<div class="uk-width-1">
<a href="<?php echo createUrl("store/addressbook/do/add")?>" class="uk-button"><i class="fa fa-plus"></i> <?php echo Yii::t("default","Add New")?></a>
<a href="<?php echo createUrl("store/addressbook")?>" class="uk-button"><i class="fa fa-list"></i> <?php echo Yii::t("default","List")?></a>
</div>  

  <?php $client_id=Yii::app()->functions->getClientId();?>
  <?php if (is_numeric($client_id)):?>
  
  <form id="frm_table_list" method="POST" >
  <input type="hidden" name="action" id="action" value="addressBook">
  <?php echo CHtml::hiddenField('currentController','store')?>

<input type="hidden" name="tbl" id="tbl" value="address_book">
<input type="hidden" name="clear_tbl"  id="clear_tbl" value="clear_tbl">
<input type="hidden" name="whereid"  id="whereid" value="id">
<input type="hidden" name="slug" id="slug" value="store/addressbook">
  
  <table id="table_list" class="uk-table uk-table-hover uk-table-striped uk-table-condensed">
  <thead>
  <tr>
   <th width="50%"><?php echo Yii::t("default","Address")?></th>
   <th width="20%"><?php echo Yii::t("default","Location Name")?></th>
   <th  width="10%"><?php echo Yii::t("default","Default")?></th>   
  </tr>
  </thead>
  </table>
  
  <?php else :?>
  <p class="uk-text-danger"><?php echo Yii::t("default","Profile not available")?></p>
  <?php endif;?>

  </div>
  </div> <!--main-->
</div> <!--page-right-sidebar--> 