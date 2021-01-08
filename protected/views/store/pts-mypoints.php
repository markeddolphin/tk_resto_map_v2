<?php
$this->renderPartial('/front/default-header',array(
   'h1'=>t("Loyalty points"),
   'sub_text'=>''
));
?>

<div class="sections section-grey2">
   <div class="container">
   <div class="box-grey rounded" style="padding-bottom:40px;" >
   
   <h3><?php echo t("My Points")?></h3>
   
   <ul id="simpletabs">
	<li class="active"><?php echo t("Income Points")?></li>
	<li><?php echo t("Expenses Points")?></li>
	<li><?php echo t("Expired Points")?></li>	
	<li><?php echo t("Points By Merchant")?></li>
   </ul>
   
   <div class="points-total-wrap">
	  <div class="mytable">
	    <div class="col">
	       <p><?php echo t("Available Points")?></p>
	       <h4><?php echo $earn_points>0?$earn_points:0;?></h4>
	    </div>
	    <div class="col last">
	       <p><?php echo t("Points Expiring Soon(This year)")?></p>
	       <h4><?php echo $points_expirint>0?$points_expirint:0?></h4>
	       <p class="small">
	       <?php 
	       $date_expiring="December"." 31 ".date("Y")." 11:59 PM";
	       echo Yii::app()->functions->translateDate($date_expiring);
	       ?>
	       </p>
	    </div>
	  </div>
	</div> <!-- points-total-wrap-->
	
    <ul id="tab">   
		<li class="active">	
		 <form class="frm_pts_income pts_frm">
		  <table id="pts-income-tbl" class="table table-hover">
		     <thead>
		     <tr>
		      <th width="25%"><?php echo t("Date")?></th>
		      <th width="50%"><?php echo t("Transaction")?></th>
		      <th width="20%"><?php echo t("Amount")?></th>
		     </tr>
		     </thead>
		     <tbody>		     
		     </tbody>
		  </table> 
		 </form>
		</li>
		
		<li>
		<form class="frm_pts_expenses pts_frm">
		  <table id="pts-expenses-tbl" class="table table-hover">
		     <thead>
		     <tr>
		      <th width="25%"><?php echo t("Date")?></th>
		      <th width="50%" ><?php echo t("Transaction")?></th>
		      <th width="20%"><?php echo t("Amount")?></th>
		     </tr>
		     </thead>
		     <tbody>		     
		     </tbody>
		  </table> 
		 </form> 
		</li>
		
		<li>
		 <form class="frm_pts_expired pts_frm">
		  <table id="pts-expired-tbl" class="table table-hover">
		     <thead>
		     <tr>
		      <th width="25%"><?php echo t("Date")?></th>
		      <th width="50%"><?php echo t("Transaction")?></th>
		      <th width="20%"><?php echo t("Amount")?></th>
		     </tr>
		     </thead>
		     <tbody>		     
		     </tbody>
		  </table> 
		 </form> 
		</li>
		
		<li>
		 <form class="frm_pts_by_merchant pts_frm">
		  <table id="pts-merchant-tbl" class="table table-hover">
		     <thead>
		     <tr>
		      <th width="25%"><?php echo t("Merchant")?></th>
		      <th width="50%"><?php echo t("Total Points")?></th>		      
		     </tr>
		     </thead>
		     <tbody>		     
		     </tbody>
		  </table> 
		 </form> 
		</li>
		
    </ul> <!--tab-->	
   
    </div> <!--box-grey-->
   </div> <!--container-->
</div> <!--sections-->