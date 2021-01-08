<div class="page-right-sidebar">
  <div class="main">
  <div class="inner">
  <div class="left-content left">
   <h3><?php echo Yii::t("default","Your Recent Order")?></h3>
   
   <?php if ( Yii::app()->functions->isClientLogin()):?>
   <?php $client_id=Yii::app()->functions->getClientId();?>
   <?php if ( $res=Yii::app()->functions->clientHistyOrder($client_id)):?>
   <ul class="uk-list uk-list-striped order-history">
    <?php foreach ($res as $val):?>    
    <li>
      <div>
        <a class="add-to-cart" href="javascript:;" data-id="<?php echo $val['order_id']?>" >
          <i class="fa fa-calendar-o"></i> 
          <?php echo ucwords($val['merchant_name'])?> - 
          <?php echo Yii::app()->functions->translateDate(prettyDate($val['date_created']))?>          
        </a>
        <a href="javascript:;" class="view-receipt" data-id="<?php echo $val['order_id']?>" >
         <i class="fa fa-file-text-o"></i> <?php echo Yii::t("default","View Receipt")?>
        </a>
        <span style="padding-left:10px;">
           <a href="javascript:;" class="view-order-history" data-id="<?php echo $val['order_id'];?>">
            <?php echo t("View status history")?>
          </a>
        </span>
        <span><i class="fa fa-square-o"></i> <?php echo t($val['status'])?></span>        
      </div>                    
      <?php if ( $order_details=Yii::app()->functions->clientHistyOrderDetails($val['order_id']) ):?>
      <ul>      
       <?php foreach ($order_details as $val_sub):?>
       <li class="uk-text-muted"><i class="fa fa-angle-right"></i> <?php echo $val_sub['item_name']?></li>
       <?php endforeach;?>
       
          
          <?php if ( $resh=FunctionsK::orderHistory($val['order_id'])):?>                    
               <table class="uk-table uk-table-hover order-order-history show-history-<?php echo $val['order_id']?>">
                 <thead>
                   <tr>
                    <th class="uk-text-muted"><?php echo t("Date/Time")?></th>
                    <th class="uk-text-muted"><?php echo t("Status")?></th>
                    <th class="uk-text-muted"><?php echo t("Remarks")?></th>
                   </tr>
                 </thead>
                 <tbody>
                   <?php foreach ($resh as $valh):?>
                   <tr style="font-size:12px;">
                     <td><?php                       
                      echo FormatDateTime($valh['date_created'],true);
                      ?></td>
                     <td><?php echo t($valh['status'])?></td>
                     <td><?php echo $valh['remarks']?></td>
                   </tr>
                   <?php endforeach;?>
                 </tbody>
               </table> 
          <?php else :?>                
            <p class="uk-text-danger order-order-history show-history-<?php echo $val['order_id']?>">
              <?php echo t("No history found")?>
            </p>
          <?php endif;?>
       
      </ul>
      <?php endif;?>
    </li>
    <?php endforeach;?>
   </ul>
   <?php else :?>
     <p class="uk-text-danger"><?php echo Yii::t("default","No order history")?></p>
   <?php endif;?>
   <?php else :?>
   <p class="uk-text-danger"><?php echo Yii::t("default","You need to login first.")?></p>
   <?php endif;?>
   
  </div>
  
  <div class="right-content right">
  
  </div>
  <div class="clear"></div>
  
  </div>
  </div> <!--main-->
</div>  <!--page-right-sidebar-->