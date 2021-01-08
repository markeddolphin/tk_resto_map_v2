<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Profile"),   
   'sub_text'=>t("Manage your profile,address book, credit card and more")
));
echo CHtml::hiddenField('mobile_country_code',Yii::app()->functions->getAdminCountrySet(true));
?>

<div class="sections section-grey2 section-profile">
  <div class="container">

  <div class="row">
  <div class="col-md-8 ">
  
  <div class="tabs-wrapper">
     <ul id="tabs">
       <li class="<?php echo $tabs==""?"active":''?>">
       <i class="ion-android-contact"></i> <span><?php echo t("Profile")?></span>
       </li> 
             
       <li class="address-book <?php echo $tabs==2?"active":''?>" >
         <i class="ion-ios-location-outline"></i> <span><?php echo t("Address Book")?></span>
       </li>
       
       <li ><i class="ion-ios-book-outline"></i> 
       <span><?php echo t("Order History")?></span>
       </li>
       
       <?php if($booking_disabled!=2):?>
       <li ><i class="ion-ios-bookmarks-outline <?php echo $tabs==3?"active":''?>"></i> 
       <span><?php echo t("Booking History")?></span>
       </li>
       <?php endif;?>
       
       <li ><i class="ion-android-favorite-outline"></i> 
       <span><?php echo t("Favorites")?></span>
       </li>
       
       <?php if ( $disabled_cc != "yes"):?>
       <li class="<?php echo $tabs==4?"active":''?>" >
       <i class="ion-card"></i> <span><?php echo t("Credit Cards")?></span>
       </li>
       <?php endif;?>
       
      <?php if (FunctionsV3::hasModuleAddon("pointsprogram")) :?>
      <?php PointsProgram::frontMenu(true);?>
      <?php endif;?>
       
     </ul>
     
     <ul id="tab">
       <li class="<?php echo $tabs==""?"active":''?>">
          <?php $this->renderPartial('/front/profile',array(
            'data'=>$info           
          ));?>
       </li>
       <li class="<?php echo $tabs==2?"active":''?>">
         <?php 
         
         if (FunctionsV3::isSearchByLocation()){
         	
         	$country_id=getOptionA('location_default_country');				
         	$citys='';$areas='';
         	if ($datas = FunctionsV3::getAddressByLocation( isset($_GET['id'])?(integer)$_GET['id']:'' )){         		
         		$citys=FunctionsV3::ListCityList($datas['state_id']);
         		$areas=FunctionsV3::AreaList($datas['city_id']);
         		$country_id = $datas['country_id'];
         	}
         	
         	$this->renderPartial('/front/address-book-location',array(
	           'client_id'=>Yii::app()->functions->getClientId(),
	           'data'=>$datas,
	           'tabs'=>$tabs,
	           'states'=>FunctionsV3::ListLocationState($country_id),
	           'country_id'=>$country_id,
	           'citys'=>$citys,
	           'areas'=>$areas
	         ));
         } else {
         	 $resp_address = Yii::app()->functions->getAddressBookByID( isset($_GET['id'])?(integer)$_GET['id']:'' );
         	 if($resp_address){
         	    $resp_address = Yii::app()->request->stripSlashes($resp_address);
         	 }
	         $this->renderPartial('/front/address-book',array(
	           'client_id'=>Yii::app()->functions->getClientId(),
	           'data'=>$resp_address,
	           'tabs'=>$tabs,	           
	         ));
         }
         
         ?>
       </li>
       <li class="<?php echo $tabs==3?"active":''?>">
         <?php

         if (method_exists('Functionsv3','clientHistyOrder')){
            $this->renderPartial('/front/order-history',array(           
	           'data'=>FunctionsV3::clientHistyOrder( Yii::app()->functions->getClientId() )
	         ));
         } else {
	         $this->renderPartial('/front/order-history',array(           
	           'data'=>Yii::app()->functions->clientHistyOrder( Yii::app()->functions->getClientId() )
	         ));
         }
         
         ?>
                
       </li>
       
       
       <?php if($booking_disabled!=2):?>
       <li>
        <?php $this->renderPartial('/front/booking-history',array(           
           'data'=>FunctionsV3::getBooking( Yii::app()->functions->getClientId() ),
           'booking_cancel_days'=>getOptionA('booking_cancel_days'),
           'booking_cancel_hours'=>getOptionA('booking_cancel_hours'),
           'booking_cancel_minutes'=>getOptionA('booking_cancel_minutes'),
           'booking_stats'=>bookingStatus()
         ));?>
       </li>
       <?php endif;?>
       
       <li>
        <?php $this->renderPartial('/front/favorites-list',array(           
           'data'=>FunctionsV3::getCustomerFavoritesList( Yii::app()->functions->getClientId() )
         ));?>
       </li>
       
       <?php if ( $disabled_cc != "yes"):?>
       <li class="<?php echo $tabs==4?"active":''?>" >
       <?php 
         if (isset($_GET['do']) && $tabs == 4){
         	 $id = isset($_GET['id'])?$_GET['id']:'';
         	 if($id>=1){
         	 	if($data=FunctionsV3::getCreditCardInfo(Yii::app()->functions->getClientId(),$id)){
         	 		$this->renderPartial('/front/manage-credit-card-add',array(         	   
         	 		   'data'=>$data,
	         	       'tabs'=>$tabs,	         	       
	         	    ));
         	 	} else {
         	 		$this->renderPartial('/store/error',array(
				       'message'=>''
				     ));
         	 	}
         	 } else {
         	 	$this->renderPartial('/front/manage-credit-card-add',array(         	   
         	       'tabs'=>$tabs
         	    ));
         	 }
         } else {
		     $this->renderPartial('/front/manage-credit-card',array(
		       'tabs'=>$tabs
		     ));
         }
		 ?>     
       </li>
       <?php endif;?>
       
     </ul>
  </div> <!--tabs-wrapper--> 
  
      
    </div> <!--col-->
    
    <div class="col-md-4 avatar-section">
       <div class="box-grey rounded" style="margin-top:0;">
                        
          <div class="avatar-wrap">
            <img src="<?php echo $avatar;?>" class="img-circle">
          </div> <!--center-->
          
          <!--<div class="center top10">
          <a href="javascript:;" id="uploadavatar"><?php echo t("Browse")?></a>          
          </div>
          <DIV  style="display:none;" class="uploadavatar_chart_status" >
			<div id="percent_bar" class="uploadavatar_percent_bar"></div>
			<div id="progress_bar" class="uploadavatar_progress_bar">
			  <div id="status_bar" class="uploadavatar_status_bar"></div>
			</div>
		  </DIV>-->		  
          
          <div class="center top10">
             <a href="javascript:;" id="single_uploadfile" data-progress="single_uploadfile_progress" data-preview="avatar-wrap" >
               <?php echo t("Browse")?>
             </a>          
          </div>
          
          <div class="single_uploadfile_progress"></div>
          
          <div class="line-top line-bottom center">
          <?php echo t("Update your profile picture")?>
          </div>
          
          <?php if ( $info['social_strategy']=="web"):?>
          <div class="connected-wrap">
            <div class="mytable web">
              <div class="mycol  col-1 center">
              <i class="ion-social-dribbble i-big"></i>
              </div> <!--col-->
              <div class="mycol  col-2">
                <span class="small"><?php echo t("Connected as")?></span><br/>
                <span class="bold"><?php echo $info['first_name']." ".$info['last_name']?></span>
              </div> <!--col-->
            </div>
          </div> <!--connected-wrap-->
          <?php endif;?>
          
          <?php if ( $info['social_strategy']=="fb"):?>
          <div class="connected-wrap">
            <div class="mytable fb">
              <div class="mycol  col-1 center">
              <i class="ion-social-facebook-outline i-big"></i>
              </div> <!--col-->
              <div class="mycol  col-2">
                <span class="small"><?php echo t("Connected as")?></span><br/>
                <span class="bold"><?php echo $info['first_name']." ".$info['last_name']?></span>
              </div> <!--col-->
            </div>
          </div> <!--connected-wrap-->
          <?php endif;?>
          
          <?php if ( $info['social_strategy']=="google"):?>
          <div class="connected-wrap">
            <div class="mytable google">
              <div class="mycol  col-1 center">
              <i class="ion-social-googleplus-outline i-big"></i>
              </div> <!--col-->
              <div class="mycol  col-2">
                <span class="small"><?php echo t("Connected as")?></span><br/>
                <span class="bold"><?php echo $info['first_name']." ".$info['last_name']?></span>
              </div> <!--col-->
            </div>
          </div> <!--connected-wrap-->
          <?php endif;?>
       
       </div>
    </div> <!--col-->
    
  </div> <!--row-->
  </div> <!--container-->  
</div> <!--sections-->
