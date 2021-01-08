
<h4><?php echo t("General Template")?></h4>

<form id="newforms" class="email-template-table uk-form" method="POST" onsubmit="return false;">
<?php 
echo CHtml::hiddenField('action','saveTemplateSettings');
FunctionsV3::addCsrfToken(false);
?>
<table class="uk-table uk-table-hover">
  <thead>
   <tr>
    <th width="35%"><?php echo t("Triggers")?></th>
    <th width="20%"><?php echo t("Email")?></th>
    <th width="20%"><?php echo t("SMS")?></th>
    <th width="20%"><?php echo t("Actions")?></th>
   </tr>
  </thead>  
  <tbody>  
  <?php foreach ($general_template as $key => $val):?>
  <tr>
    <td><?php echo strtoupper(t($key))?></td>
    <td>
    <?php echo CHtml::checkBox($key."_email",
    getOptionA($key."_email")==1?true:false
    , array('disabled'=>$val['email']==false?true:false ))?>
    </td>
    
    <td>
    <?php echo CHtml::checkBox($key."_sms",
    getOptionA($key."_sms")==1?true:false
    , array('disabled'=>$val['sms']==false?true:false ))?>
    </td>
    <td>
    <a href="javascript:;" class="template_actions" 
    data-tag_email="<?php echo isset($val['email_tag'])?$val['email_tag']:''?>"
    data-tag_sms="<?php echo isset($val['sms_tag'])?$val['sms_tag']:''?>"
    data-key="<?php echo $key?>" 
    data-sms="<?php echo $val['sms']?>"
    data-email="<?php echo $val['email']?>"
    data-label="<?php echo strtoupper(t($key))?>"
    ><i class="fa fa-pencil"></i>
    </a>
    </td>
  </tr>  
  <?php endforeach;?>
  </tbody> 
</table>

<h4><?php echo t("Order Template")?></h4>

<table class="uk-table uk-table-hover">
  <thead>
   <tr>
    <th width="35%"><?php echo t("Triggers")?></th>
    <th width="20%"><?php echo t("Email")?></th>
    <th width="20%"><?php echo t("SMS")?></th>
    <th width="20%"><?php echo t("PUSH")?></th>
    <th width="20%"><?php echo t("Actions")?></th>
   </tr>
  </thead>  
  <tbody>  
  <?php if(is_array($order_template) && count($order_template)>=1):?>
  <?php foreach ($order_template as $key => $val): ?>
  <tr>
    <td><?php echo strtoupper(t($key))?></td>
    <td><?php echo CHtml::checkBox($key."_email",getOptionA($key."_email")==1?true:false)?></td>
    <td><?php echo CHtml::checkBox($key."_sms",getOptionA($key."_sms")==1?true:false, array('disabled'=>$val['sms']==false?true:false ))?>
    </td>
    
    <td>
    <?php echo CHtml::checkBox($key."_push",getOptionA($key."_push")==1?true:false, 
    array('disabled'=>$val['push']==false?true:false ))?>
    </td>
    
    <td>
    <a href="javascript:;" class="template_actions" 
    data-tag_email="<?php echo isset($val['email_tag'])?$val['email_tag']:''?>"
    data-tag_sms="<?php echo isset($val['sms_tag'])?$val['sms_tag']:''?>"
    data-key="<?php echo $key?>" 
    data-sms="<?php echo $val['sms']?>"
    data-email="<?php echo $val['email']?>"
    data-label="<?php echo strtoupper(t($key))?>"
    data-push="<?php echo $val['push']?>"
    data-tag_push="<?php echo isset($val['push_tag'])?$val['push_tag']:''?>"
    ><i class="fa fa-pencil"></i>
    </a>
    </td>
  </tr>  
  <?php endforeach;?>
  <?php endif;?>
  </tbody> 
</table>

<h4><?php echo t("Booking Template")?></h4>

<table class="uk-table uk-table-hover">
  <thead>
   <tr>
    <th width="35%"><?php echo t("Triggers")?></th>
    <th width="20%"><?php echo t("Email")?></th>
    <th width="20%"><?php echo t("SMS")?></th>
    <th width="20%"><?php echo t("PUSH")?></th>
    <th width="20%"><?php echo t("Actions")?></th>
   </tr>
  </thead>  
  <tbody>  
  <?php if(is_array($booking_template) && count($booking_template)>=1):?>
  <?php foreach ($booking_template as $key => $val): ?>
  <tr>
    <td><?php echo strtoupper(t($key))?></td>
    <td><?php echo CHtml::checkBox($key."_email",getOptionA($key."_email")==1?true:false)?></td>
    <td><?php echo CHtml::checkBox($key."_sms",getOptionA($key."_sms")==1?true:false, array('disabled'=>$val['sms']==false?true:false ))?>
    </td>
    
    <td>
    <?php echo CHtml::checkBox($key."_push",getOptionA($key."_push")==1?true:false, 
    array('disabled'=>$val['push']==false?true:false ))?>
    </td>
    
    <td>
    <a href="javascript:;" class="template_actions" 
    data-tag_email="<?php echo isset($val['email_tag'])?$val['email_tag']:''?>"
    data-tag_sms="<?php echo isset($val['sms_tag'])?$val['sms_tag']:''?>"
    data-key="<?php echo $key?>" 
    data-sms="<?php echo $val['sms']?>"
    data-email="<?php echo $val['email']?>"
    data-label="<?php echo strtoupper(t($key))?>"
    data-push="<?php echo $val['push']?>"
    data-tag_push="<?php echo isset($val['push_tag'])?$val['push_tag']:''?>"
    ><i class="fa fa-pencil"></i>
    </a>
    </td>
  </tr>  
  <?php endforeach;?>
  <?php endif;?>
  </tbody> 
</table>

<h4><?php echo t("Payment Template")?></h4>

<table class="uk-table uk-table-hover">
  <thead>
   <tr>
    <th width="35%"><?php echo t("Triggers")?></th>
    <th width="20%"><?php echo t("Email")?></th>
    <th width="20%"><?php echo t("SMS")?></th>
    <th width="20%"><?php echo t("Actions")?></th>
   </tr>
  </thead>  
  <tbody>  
  <?php if(is_array($payment_template) && count($payment_template)>=1):?>
  <?php foreach ($payment_template as $key => $val): ?>
  <tr>
    <td><?php echo strtoupper(t($key))?></td>
    <td><?php echo CHtml::checkBox($key."_email",getOptionA($key."_email")==1?true:false)?></td>
    <td><?php echo CHtml::checkBox($key."_sms",getOptionA($key."_sms")==1?true:false, array('disabled'=>$val['sms']==false?true:false ))?>
    </td>
    <td>
    <a href="javascript:;" class="template_actions" 
    data-tag_email="<?php echo isset($val['email_tag'])?$val['email_tag']:''?>"
    data-tag_sms="<?php echo isset($val['sms_tag'])?$val['sms_tag']:''?>"
    data-key="<?php echo $key?>" 
    data-sms="<?php echo $val['sms']?>"
    data-email="<?php echo $val['email']?>"
    data-label="<?php echo strtoupper(t($key))?>"
    ><i class="fa fa-pencil"></i>
    </a>
    </td>
  </tr>  
  <?php endforeach;?>
  <?php endif;?>
  </tbody> 
</table>


<h4><?php echo t("Order Status Template")?></h4>

<table class="uk-table uk-table-hover">
  <thead>
   <tr>
    <th width="35%"><?php echo t("Triggers")?></th>
    <th width="20%"><?php echo t("Email")?></th>
    <th width="20%"><?php echo t("SMS")?></th>
    <th width="20%"><?php echo t("PUSH")?></th>
    <th width="20%"><?php echo t("Actions")?></th>
   </tr>
  </thead>  
  <tbody>  
  <?php if(is_array($order_status_template) && count($order_status_template)>=1):?>
  <?php foreach ($order_status_template as $key => $val): ?>
  <tr>
    <td><?php echo strtoupper(t($key))?></td>
    <td><?php echo CHtml::checkBox($key."_email",getOptionA($key."_email")==1?true:false)?></td>
    <td><?php echo CHtml::checkBox($key."_sms",getOptionA($key."_sms")==1?true:false, array('disabled'=>$val['sms']==false?true:false ))?>
    </td>
    
    <td>
    <?php echo CHtml::checkBox($key."_push",getOptionA($key."_push")==1?true:false, 
    array('disabled'=>$val['push']==false?true:false ))?>
    </td>
    
    <td>
    <a href="javascript:;" class="template_actions" 
    data-tag_email="<?php echo isset($val['email_tag'])?$val['email_tag']:''?>"
    data-tag_sms="<?php echo isset($val['sms_tag'])?$val['sms_tag']:''?>"
    data-tag_push="<?php echo isset($val['push_tag'])?$val['push_tag']:''?>"
    data-key="<?php echo $key?>" 
    data-sms="<?php echo $val['sms']?>"
    data-push="<?php echo $val['push']?>"
    data-email="<?php echo $val['email']?>"
    data-label="<?php echo strtoupper(t($key))?>"
    ><i class="fa fa-pencil"></i>
    </a>
    </td>
  </tr>  
  <?php endforeach;?>
  <?php endif;?>
  </tbody> 
</table>


  <div class="uk-margin" style="padding-left:8px;">
    <button id="" type="submit" class="uk-button uk-form-width-medium uk-button-success">
    <?php echo t("Save Settings")?>
    </button>
  </div>
  
 </form>