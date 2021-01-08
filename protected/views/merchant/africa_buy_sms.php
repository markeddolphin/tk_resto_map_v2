
<div class="page-right-sidebar payment-option-page">
  <div class="main">  
  <?php if ( !empty($error)):?>
  <p class="uk-text-danger"><?php echo $error;?></p>  
  <?php else :?>
  
  
  <form id="frm_redirect_payment" class="frm_redirect_payment uk-form"  method="POST" action="https://payments.ipayafrica.com/v3/ke"  >
  
  <?php 
    $currency_code = adminCurrencyCode();
    $call_back = websiteUrl()."/ipay_africa_pay/?trans_type=sms&package_id=".$package_id ;
    $cst = "1";
    $crl = "0";    
    echo CHtml::hiddenField('live',$credentials['mode']);
    echo CHtml::hiddenField('oid',$order_id);
    echo CHtml::hiddenField('inv',$order_id);
    echo CHtml::hiddenField('ttl',$amount_to_pay);
    echo CHtml::hiddenField('tel',$telephone);
    echo CHtml::hiddenField('eml',$email_address);
    echo CHtml::hiddenField('vid',$credentials['vendor_id']);
    echo CHtml::hiddenField('curr',$currency_code);
    echo CHtml::hiddenField('cbk', $call_back);
    echo CHtml::hiddenField('cst', $cst );
    echo CHtml::hiddenField('crl', $crl);
    
    $datastring =  $credentials['mode'].$order_id.$order_id.$amount_to_pay.$telephone.$email_address.$credentials['vendor_id'].$currency_code.$call_back.$cst.$crl;
	$hashkey = $credentials['hashkey'];	
	$generated_hash = hash_hmac('sha1',$datastring , $hashkey);	
    echo CHtml::hiddenField('hsh',$generated_hash);
    ?> 
    
    <table class="uk-table" style="width:50%;">
     <tr>
       <td><?php echo t("Amount")?></td>
       <td><?php echo FunctionsV3::prettyPrice($amount_to_pay)?></td>
     </tr>
     <tr>
       <td><?php echo t("Email")?></td>
       <td>
         <?php 
	    echo CHtml::textField('email_address',$email_address,array(
	      'class'=>"form-control",
	      'data-validation'=>'required',
	      'disabled'=>true
	    ));
	    ?>
       </td>
     </tr>
     
      <tr>
       <td><?php echo t("Email")?></td>
       <td>
         <?php 
	     echo CHtml::textField('telephone',$telephone,array(
	      'class'=>"form-control",
	      'data-validation'=>'required',
	      'disabled'=>true
	    ));
	    ?>
       </td>
     </tr>
     
     <tr>
      <tr>
        <td></td>
        <td>
        <input type="submit" value="<?php echo Yii::t("default","Pay Now")?>" class="uk-button uk-form-width-medium uk-button-success">
        </td>
      </tr>
     </tr>
     
    </table>
    
  </form>
  
  <?php endif;?>      
  <div style="height:10px;"></div>
  <a href="<?php echo $back_url;?>"><?php echo Yii::t("default","Go back")?></a>
  
  </div>
</div>