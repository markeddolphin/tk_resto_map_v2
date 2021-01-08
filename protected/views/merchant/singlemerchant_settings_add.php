<?php echo CHtml::beginForm('','post',array(
  'id'=>"forms",
  'class'=>"forms",
  'onsubmit'=>"return false;",  
)); 
?> 

<?php 
Yii::app()->setImport(array(			
  'application.modules.singlemerchant.components.*',
));
$data = array();
$page_id = isset($_GET['page_id'])?(integer)$_GET['page_id']:0;
if($page_id>0){
	$data = SingleAppClass::getPageByID($page_id);
}
echo CHtml::hiddenField('page_id',$page_id);
echo CHtml::hiddenField('merchant_id',$merchant_id);
echo CHtml::hiddenField('action','singleMerchantAddPage');
echo CHtml::hiddenField('redirect',Yii::app()->createUrl("/merchant/singlemerchant/settings_pages"));
?>

<div class="modal-body">

<?php if(Yii::app()->functions->multipleField()):?>

<ul class="nav nav-tabs" id="lang_tab" role="tablist">
    <li class="nav-item">
	 <a class="nav-link active"  data-toggle="tab" href="#tab_default"><?php echo t("default")?></a>
	</li>
	<?php if ( $fields=FunctionsV3::getLanguageList(false)):?>  
	  <?php foreach ($fields as $f_val): ?>
	     <li class="nav-item">
	      <a class="nav-link"  data-toggle="tab" href="#tab_<?php echo $f_val;?>"><?php echo $f_val;?></a>
	    </li>
	  <?php endforeach;?>
	<?php endif;?>
</ul> 

<div class="tab-content" id="lang_tab">
  <div class="tab-pane fade  active in" id="tab_default" >
  
  <div class="form-group">
	<label><?php echo t("Title")?></label>		
	<?php 
	echo CHtml::textField('title',
	isset($data['title'])?$data['title']:''
	,array('class'=>"form-control",'required'=>true ));
	?>			
   </div> 
   
   <div class="form-group">
	<label><?php echo t("Content")?></label>		
	<?php 
	echo CHtml::textArea('content',
	isset($data['content'])?$data['content']:''
	,array(
	  'class'=>"form-control text_area",
	  'required'=>true
	));
	?>			
   </div> 
  
  </div>
  <?php if(is_array($fields) && count($fields)>=1):?>
  <?php foreach ($fields as $lang_code):
        $data_title = "title_$lang_code";
        $data_content = "content_$lang_code";        
   ?>
     <div class="tab-pane fade " id="tab_<?php echo $lang_code;?>"  >
     
     <div class="form-group">
		<label><?php echo t("Title")?></label>		
		<?php 
		echo CHtml::textField('title_'.$lang_code,
		isset($data[$data_title])?$data[$data_title]:''
		,array('class'=>"form-control",'required'=>false ));
		?>			
	   </div> 
	   
	   <div class="form-group">
		<label><?php echo t("Content")?></label>		
		<?php 
		echo CHtml::textArea('content_'.$lang_code,
		isset($data[$data_content])?$data[$data_content]:''
		,array(
		  'class'=>"form-control",
		  'required'=>false
		));
		?>			
   </div>   
     
     </div>  
  <?php endforeach;?>
  <?php endif;?>
</div>

<div class="height10"></div>
<?php else :?>


  <div class="form-group">
	<label><?php echo t("Title")?></label>		
	<?php 
	echo CHtml::textField('title','',array('class'=>"form-control",'required'=>true ));
	?>			
   </div> 
   
   <div class="form-group">
	<label><?php echo t("Content")?></label>		
	<?php 
	echo CHtml::textArea('content','',array(
	  'class'=>"form-control",
	  'required'=>true
	));
	?>			
   </div>  

<?php endif;?>

<div class="custom-control custom-checkbox">  
  <?php 
  if(!isset($data['use_html'])){
  	 $data['use_html']=0;
  }
  echo CHtml::checkBox('use_html',
  $data['use_html']==1?true:false
  ,array(
    'id'=>'use_html',
    'class'=>"custom-control-input",
  ));
  ?>
  <label class="custom-control-label" for="use_html">
    <?php echo t("HTML Format")?>
  </label>
</div>

<div class="height10"></div>


<div class="form-group">
<label><?php echo t("Icon")?></label>		
<?php 
echo CHtml::textField('icon',
isset($data['icon'])?$data['icon']:''
,array('class'=>"form-control"));
?>
<small class="form-text text-muted">
  <?php echo t("icon class name")?> <a target="_blank" href="https://ionicons.com/v2/">https://ionicons.com/v2/</a>
</small>
</div> 

<div class="form-group">
<label><?php echo t("Sequence")?></label>		
<?php 
echo CHtml::textField('sequence',
isset($data['sequence'])?$data['sequence']:''
,array('class'=>"form-control"));
?>
</div> 

<div class="form-group">
<label><?php echo t("Status")?></label>		
<?php 
echo CHtml::dropDownList('status',
isset($data['status'])?$data['status']:'publish'
,statusList() ,array(
  'class'=>'form-control',      
  'required'=>true
));
?>
</div> 

</div> <!--modal body-->

<button type="submit" class="btn btn-success">
<?php echo t("Save settings")?>
</button>

<!--</form>-->
<?php echo CHtml::endForm() ; ?>