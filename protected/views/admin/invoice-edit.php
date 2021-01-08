<form id="forms" class="uk-form" method="POST" onsubmit="return false;">
<div class="template-modal template-modal-small">
 <div class="template-modal-header"> 
 <?php echo CHtml::hiddenField('modal_action','SaveInvoice')?> 
 <?php echo CHtml::hiddenField('invoice_number',$data['invoice_number'])?> 
 <h4><?php echo t("Edit invoice")?></h4>
 </div>
 
 <div class="inner">

  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Status")?></label>
  <?php 
  echo CHtml::dropDownList('payment_status',$data['payment_status'],
  FunctionsV3::invoicePaymentStatusList()
  ,array(
    'class'=>"uk-form-width-medium"
  ));
  ?>
  </div>   
  
  <div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Remarks")?></label>
  <?php 
  echo CHtml::textArea('remarks',''
  ,array(
    'class'=>"uk-form-width-medium"
  ));
  ?>
  </div>   
  
  <div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>
 
 </div> <!--inner-->
 
 
 </div> <!--inner-->
</div><!--template-modal-->
</form>


<script type="text/javascript">

$.validate({ 	
	language : jsLanguageValidator,
    form : '#forms',    
    onError : function() {      
    },
    onSuccess : function() {           
      var params=$("#forms").serialize();	
      callAjax( $("#modal_action").val(), params , $("#template-submit") ) ;
      return false;
    }  
});

</script>