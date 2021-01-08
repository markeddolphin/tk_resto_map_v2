<form id="forms" class="uk-form" method="POST" onsubmit="return false;">
<div class="template-modal">
 <div class="template-modal-header">
   
   <div class="mytable">
     <div class="col"><h4><?php echo $data['label']?></h4></div>
     <div class="col" style="text-align:right;">
       <?php echo t("Choose language")?> : 
       <?php echo CHtml::dropDownList('template_lang_selection',$lang,(array)$lang_list ,array(
         'class'=>"template_lang_selection"
       ))?>
     </div>
   </div><!-- mytable-->
   
 </div> <!--template-modal-header-->
 
 <div class="inner">
   
  
  <?php echo CHtml::hiddenField('key',$data['key'])?>
  <?php echo CHtml::hiddenField('modal_action','saveTemplate')?>
  <?php FunctionsV3::addCsrfToken(false);?>
  
  
  <table class="full-width">
   <tr>
    <td width="50%">
         
     <div class="box-rounded">
     
     <div class="mytable">
       <div class="col"><h5><?php echo t("Email")?></h5></div>
       <div class="col" style="text-align:right;">
       <?php echo CHtml::dropDownList('available_tags','', (array) $tag_email )?>
       </div>
     </div>
     
     <div class="uk-margin">
        <?php echo CHtml::textField("email_subject",
        getOptionA($key."_tpl_subject_$lang")
        ,array(
          'placeholder'=>t("Subject"),
          'disabled'=>$data['email']==false?true:false
        ))?>
     </div>
     
     <div class="uk-margin">
     <?php echo CHtml::textArea('email_content',
     getOptionA($key."_tpl_content_$lang")
     ,array(
       'class'=>"",
       'placeholder'=>t("Content"),
       'disabled'=>$data['email']==false?true:false
     ))?>
     </div>
          
     </div> <!--box-->
    
    </td>
    <td width="50%">
    
    <div class="box-rounded">
     
      <div class="mytable">
       <div class="col"><h5><?php echo t("SMS")?></h5></div>
       <div class="col" style="text-align:right;">
       <?php echo CHtml::dropDownList('available_tags','', (array) $tag_sms )?>
       </div>
      </div>
            
      <div class="uk-margin">
      <?php echo CHtml::textArea('sms_content',
      getOptionA($key."_sms_content_$lang")
      ,array(
       'class'=>"",
       'placeholder'=>t("Content"),
       'style'=>"height:225px",
       'disabled'=>$data['sms']==false?true:false
      ))?>
      </div>     
            
     </div> <!--box-->
    
    </td>
   </tr>
  </table>
  
  <?php if(!isset($data['push'])){
  	$data['push']='';
  }?>
  
  <?php if ($data['push']==1):?>
   <table class="full-width" style="width:50%;">
   <tr>
    <td width="50%">
    
    <div class="box-rounded">
     
      <div class="mytable">
       <div class="col"><h5><?php echo t("PUSH")?></h5></div>
       <div class="col" style="text-align:right;">
       <?php echo CHtml::dropDownList('available_tags','', (array) $tag_push )?>
       </div>
      </div>
      
      <div class="uk-margin">
        <?php echo CHtml::textField("push_title",
        getOptionA($key."_push_title_$lang")
        ,array(
          'placeholder'=>t("Push Title"),
          'disabled'=>$data['push']==false?true:false
        ))?>
     </div>
            
      <div class="uk-margin">
      <?php echo CHtml::textArea('push_content',
      getOptionA($key."_push_content_$lang")
      ,array(
       'class'=>"",
       'placeholder'=>t("Content"),
       'style'=>"height:120px;min-height:120px;",
       'disabled'=>$data['push']==false?true:false
      ))?>
      </div>     
            
     </div> <!--box-->
    
   </td>
   </tr>
  </table> 
  <?php endif;?>
  
  <table class="full-width">
   <tr>
    <td width="50%">
     <button style="margin-top: 10px" id="template-submit" type="submit" class="uk-button uk-form-width-medium uk-button-success">
	    <?php echo t("Save")?>
	    </button>
    </td>
    <td width="50%">
    <p class="uk-text-muted"><?php echo t("Example on how to use the available tags:")?><br/>
   <?php echo t("Hi [customername] your order no is [orderno]")?> </p>
    </td>
  </tr>
  </table>  
  
  
 
  
  
  
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