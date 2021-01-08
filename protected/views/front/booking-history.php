
<div class="box-grey rounded section-order-history" style="margin-top:0;">


<div class="bottom10">
<?php echo FunctionsV3::sectionHeader('Your Recent Booking');?>
</div>
<?php if (is_array($data) && count($data)>=1):?>

   <table class="table table-striped">
     <tbody>
     <?php foreach ($data as $val):?>     
      <tr class="tr_mobile">
        <td>
        <div class="mytable">
         <div class="mycol" style="width: 10%;"><i style="font-size: 30px;" class="ion-android-arrow-dropright"></i></div>
         <div class="mycol ">
           
           <a href="javascript:;" >
             <p><?php echo t("Booking ID")?># <?php echo $val['booking_id']?></p>
           </a>
           
           <a href="javascript:;"
           class=""  >
             <p><?php echo t("No of guest")?># <?php echo $val['number_guest']?></p>
           </a>
           
            <p><?php echo t("Book on");?> 
            <?php echo Yii::app()->functions->translateDate(prettyDate($val['date_created']))?></p>
         </div>
       </div>
        </td>
        
        <td>               
        <p><?php echo $val['booking_notes']?></p>            
        </td>
                
        
        <td>
          <a href="javascript:;" class="view-order-history" data-id="<?php echo $val['booking_id'];?>">
          <p class="green-text top10 "><?php echo isset($booking_stats[$val['status']])? $booking_stats[$val['status']] : t($val['status']) ?></p>
          </a>
          
          <?php if (FunctionsV3::canCancel($val['date_created'],$booking_cancel_days,$booking_cancel_hours,$booking_cancel_minutes)):?>
	          <?php if($val['request_cancel']<=0):?>

	          <?php if($val['status']=='pending'):?>          
	          <div style="margin-top:10px;">
	          <a href="javascript:;" class="orange-button request_cancel_booking booking_id_<?php echo $val['booking_id']?> " data-id="<?php echo $val['booking_id']?>" >
	          <?php echo t("Cancel booking")?>
	          </a>
	          </div>
	          <?php endif;?>
	          
	          <?php else :?>
	           <p class="text-muted"><?php echo t("Cancel booking request sent")?></p>
	          <?php endif;?>
	      <?php else :?>    
	         <?php if($val['request_cancel']>0):?>
	            <p class="text-muted"><?php echo t("Cancel booking request sent")?></p>
	         <?php endif;?>
          <?php endif;?>
          
        </td>
      </tr>      
            
      <tr class="order-order-history show-history-<?php echo $val['booking_id']?>"> 
        <td colspan="5">
         <?php if ( $resh=FunctionsV3::getBookingHistory($val['booking_id'])):?>     
         <table class="table table-striped" >
           <thead>
             <tr>
             <th><?php echo t("Date/Time")?></th>
             <th><?php echo t("Status")?></th>
             <th><?php echo t("Remarks")?></th>
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
         <p class="text-danger small-text"><?php echo t("No history found")?></p>
         <?php endif;?>
        </td>
      </tr>      
      
      <?php endforeach;?>
     </tbody>
   </table>
<?php else :?>
   <p class="text-danger"><?php echo t("No order history")?></p>
<?php endif;?>


</div> <!--box-grey-->