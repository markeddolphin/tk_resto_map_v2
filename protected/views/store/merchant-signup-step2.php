<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Restaurant Signup"),
   'sub_text'=>t("step 2 of 4")
));

/*PROGRESS ORDER BAR*/
$this->renderPartial('/front/progress-merchantsignup',array(
   'step'=>2,
   'show_bar'=>true
));

echo CHtml::hiddenField('mobile_country_code',Yii::app()->functions->getAdminCountrySet(true));
?>


<div class="sections section-grey2">

  <div class="container">
  
  <div class="row">  
  <div class="col-md-8 border">
    <div class="box-grey round top-line-green">
             
     <?php if (is_array($data) && count($data)>=1):?>
     
       <form class="forms" id="forms" onsubmit="return false;">
	  <?php echo CHtml::hiddenField('action','merchantSignUp')?>
	  <?php echo CHtml::hiddenField('currentController','store')?>
	  <?php echo CHtml::hiddenField('package_id',$data['package_id'])?>
	  <?php FunctionsV3::addCsrfToken();?>
 
      <div class="row top10">
        <div class="col-md-3 "><?php echo t("Selected Package")?></div>
        <div class="col-md-8  bold"><?php echo $data['title']?></div>
      </div>
      
      <div class="row top10">
        <div class="col-md-3 "><?php echo t("Price")?></div>
        <div class="col-md-8  bold">
        <?php if ( $data['promo_price']>=1):?>
           <span class="strike-price"><?php echo FunctionsV3::prettyPrice($data['price'])?></span>
           <?php echo FunctionsV3::prettyPrice($data['promo_price'])?> 
        <?php else :?>
           <?php echo FunctionsV3::prettyPrice($data['price'])?> 
        <?php endif;?>
        </div>
      </div>
      
      <div class="row top10">
        <div class="col-md-3 "><?php echo t("Membership Limit")?></div>
        <div class="col-md-8  bold">
          <?php if ( $data['expiration_type']=="year"):?>
             <?php echo $data['expiration']/365?> <?php echo Yii::t("default",ucwords($data['expiration_type']))?>
          <?php else :?>
             <?php echo $data['expiration']?> <?php echo Yii::t("default",ucwords($data['expiration_type']))?>
          <?php endif;?>
        </div>
      </div>
      
     <div class="row top10">
        <div class="col-md-3 "><?php echo t("Usage")?></div>
        <div class="col-md-8  bold">
           <?php if ( $data['unlimited_post']==2):?>
              <?php echo $limit_post[$data['unlimited_post']]?>
           <?php else :?>
              <?php echo $limit_post[$data['unlimited_post']] . " (".$data['post_limit']." ".t("item")." )"?>
           <?php endif;?>
        </div>
      </div> 
      
      <div class="row top10">
        <div class="col-md-3 "><?php echo t("Restaurant name")?></div>
        <div class="col-md-8 ">
             <?php echo CHtml::textField('restaurant_name',
			  isset($data['restaurant_name'])?$data['restaurant_name']:""
			  ,array(
			  'class'=>'grey-fields full-width',
			  'data-validation'=>"required"
			  ))?>
        </div>
      </div>
      
     <?php if ( getOptionA('merchant_reg_abn')=="yes"):?>
     <div class="row top10">
        <div class="col-md-3 "><?php echo t("ABN")?></div>
        <div class="col-md-8 ">
              <?php echo CHtml::textField('abn',
			  isset($data['restaurant_name'])?$data['abn']:""
			  ,array(
			  'class'=>'grey-fields full-width',
			  'data-validation'=>"required"
			  ))?>
        </div>
      </div>
     <?php endif;?>
      
     <div class="row top10">
        <div class="col-md-3"><?php echo t("Restaurant phone")?></div>
        <div class="col-md-8">
         <?php echo CHtml::textField('restaurant_phone',
		  isset($data['restaurant_phone'])?$data['restaurant_phone']:""
		  ,array(
		  'class'=>'grey-fields full-width',
		  ))?>    
        </div>
      </div>
      
      <div class="row top10">
        <div class="col-md-3"><?php echo t("Contact name")?></div>
        <div class="col-md-8">
		  <?php echo CHtml::textField('contact_name',
		  isset($data['contact_name'])?$data['contact_name']:""
		  ,array(
		  'class'=>'grey-fields full-width',
		  'data-validation'=>"required"
		  ))?>           
        </div>
      </div>
      
      <div class="row top10">
        <div class="col-md-3"><?php echo t("Contact phone")?></div>
        <div class="col-md-8">
		  <?php echo CHtml::textField('contact_phone',
		  isset($data['contact_phone'])?$data['contact_phone']:""
		  ,array(
		  'class'=>'grey-fields full-width mobile_inputs',
		  'data-validation'=>"required"
		  ))?>           
        </div>
      </div>
      
      <div class="row top10">
        <div class="col-md-3"><?php echo t("Contact email")?></div>
        <div class="col-md-8">
		  <?php echo CHtml::textField('contact_email',
		  isset($data['contact_email'])?$data['contact_email']:""
		  ,array(
		  'class'=>'grey-fields full-width',
		  'data-validation'=>"email"
		  ))?>           
        </div>
      </div> 
      
      <div class="row top10">
        <div class="col-md-3"></div>
        <div class="col-md-8">
        <p class="text-muted text-small"><?php echo t("Important: Please enter your correct email. we will sent an activation code to your email")?></p>
        </div>
      </div>   
      
      
      <div class="row top10">
        <div class="col-md-3"><?php echo t("Street address")?></div>
        <div class="col-md-8">
		  <?php echo CHtml::textField('street',
		  isset($data['street'])?$data['street']:""
		  ,array(
		  'class'=>'grey-fields full-width',
		  'data-validation'=>"required"
		  ))?>           
        </div>
      </div>
      
      <div class="row top10">
        <div class="col-md-3"><?php echo t("City")?></div>
        <div class="col-md-8">
		  <?php echo CHtml::textField('city',
		  isset($data['city'])?$data['city']:""
		  ,array(
		  'class'=>'grey-fields full-width',
		  'data-validation'=>"required"
		  ))?>           
        </div>
      </div>
      
      <div class="row top10">
        <div class="col-md-3"><?php echo t("Post code/Zip code")?></div>
        <div class="col-md-8">
		  <?php echo CHtml::textField('post_code',
		  isset($data['post_code'])?$data['post_code']:""
		  ,array(
		  'class'=>'grey-fields full-width',
		  'data-validation'=>"required"
		  ))?>           
        </div>
      </div>
      
      <div class="row top10">
        <div class="col-md-3"><?php echo t("Country")?></div>
        <div class="col-md-8">
		  <?php echo CHtml::dropDownList('country_code',
		  getOptionA('merchant_default_country'),
		  (array)Yii::app()->functions->CountryListMerchant(),          
		  array(
		  'class'=>'grey-fields full-width',
		  'data-validation'=>"required"
		  ))?>           
        </div>
      </div>
      
      <div class="row top10">
        <div class="col-md-3"><?php echo t("State/Region")?></div>
        <div class="col-md-8">
		  <?php echo CHtml::textField('state',
		  isset($data['state'])?$data['state']:""
		  ,array(
		  'class'=>'grey-fields full-width',
		  'data-validation'=>"required"
		  ))?>           
        </div>
      </div>
      
      <div class="row top10">
        <div class="col-md-3"><?php echo t("Cuisine")?></div>
        <div class="col-md-8">
		  <?php 
		  $cuisine_list=Yii::app()->functions->Cuisine(true);
		  $cuisine_1=array();
		  if ( Yii::app()->functions->multipleField()==2){
		   	  foreach ($cuisine_list as $cuisine_id=>$val) {
		   	  	   $cuisine_info=Yii::app()->functions->GetCuisine($cuisine_id);
		   	  	   $cuisine_json['cuisine_name_trans']=!empty($cuisine_info['cuisine_name_trans'])?
	    		   json_decode($cuisine_info['cuisine_name_trans'],true):'';
	    		   $cuisine_1[$cuisine_id]=qTranslate($val,'cuisine_name',$cuisine_json);
		   	  }
		   	  $cuisine_list=$cuisine_1;
		  }
		  echo CHtml::dropDownList('cuisine[]',
		  isset($data['cuisine'])?(array)json_decode($data['cuisine']):"",
		  (array)$cuisine_list,          
		  array(
		  'class'=>'full-width chosen',
		  'multiple'=>true,
		  'data-validation'=>"required"  
		  ))?>           
        </div>
      </div>
      
      <div class="row top10">
        <div class="col-md-3"><?php echo t("Services Pick Up or Delivery?")?></div>
        <div class="col-md-8">
		  <?php echo CHtml::dropDownList('service',
		  isset($data['service'])?$data['service']:"",
		  (array)Yii::app()->functions->Services(),          
		  array(
		  'class'=>'grey-fields full-width',
		  'data-validation'=>"required"
		  ))?>           
        </div>
      </div>
      
      <div class="row top10">
        <div class="col-md-3"><?php echo t("Delivery Distance Covered")?></div>
        <div class="col-md-3">
		  <?php 
		  echo CHtml::textField('delivery_distance_covered',
		  ''
		  ,array(
		  'placeholder'=>"",
		  "class"=>"numeric_only form-control",
		  'data-validation'=>"required"
		  ));
		  ?>           
        </div>
        <div class="col-md-3">
         <?php 
		  echo CHtml::dropDownList('distance_unit',
		  'mi',
		  Yii::app()->functions->distanceOption(),array(
		    "class"=>"form-control",
		  ));
		  ?>
        </div>
      </div>
      
            
      <div class="top15">
      <?php FunctionsV3::sectionHeader('Login Information');?>
      </div>
      
      <div class="row top10">
        <div class="col-md-3"><?php echo t("Username")?></div>
        <div class="col-md-8">
		<?php echo CHtml::textField('username',
		  ''
		  ,array(
		  'class'=>'grey-fields full-width',
		  'data-validation'=>"required"
		  ))?>
        </div>
      </div>
      
      <div class="row top10">
        <div class="col-md-3"><?php echo t("Password")?></div>
        <div class="col-md-8">
		  <?php echo CHtml::passwordField('password',
		  ''
		  ,array(
		  'class'=>'grey-fields full-width',
		  'data-validation'=>"required"
		  ))?>           
        </div>
      </div>
      
      <div class="row top10">
        <div class="col-md-3"><?php echo t("Confirm Password")?></div>
        <div class="col-md-8">
		  <?php echo CHtml::passwordField('cpassword',
		  ''
		  ,array(
		  'class'=>'grey-fields full-width',
		  'data-validation'=>"required"
		  ))?>           
        </div>
      </div>
      
      <?php if ($kapcha_enabled==2):?>      
         <div class="recaptcha_v3"><?php GoogleCaptchaV3::init();?></div> 
      <?php endif;?>
      
      <?php if ( $terms_merchant=="yes"):?>
      <?php $terms_link=Yii::app()->functions->prettyLink($terms_merchant_url);?>
      <div class="row top10">
        <div class="col-md-3"></div>
        <div class="col-md-8">
          <?php 
		  echo CHtml::checkBox('terms_n_condition',false,array(
		   'value'=>2,
		   'class'=>"",
		   'data-validation'=>"required"
		  ));
		  echo " ". t("I Agree To")." <a href=\"$terms_link\" target=\"_blank\">".t("The Terms & Conditions")."</a>";
		  ?>  
        </div>
      </div>
      <?php endif;?>
      
      <div class="row top10">
        <div class="col-md-3"></div>
        <div class="col-md-8">
          <input type="submit" value="<?php echo t("Next")?>" class="orange-button inline medium">
        </div>
      </div>
      
      </form>
      
      <?php else :?>
      <p class="text-danger"><?php echo t("Sorry but we cannot find what you are looking for.")?></p>
      <?php endif;?>
       
    </div> <!--box-grey-->
    
   </div> <!--col-->
    
   <div class="col-md-4 border sticky-div">
       <div class="box-grey round" id="change-package-wrap">
           
          <?php 
          $p_list=array();
          if (is_array($package_list) && count($package_list)>=1){
          	  foreach ($package_list as $val) {
          	  	  $p_list[$val['package_id']]=$val['title'];
          	  }
          }    
          echo CHtml::hiddenField('change_package_url',
             Yii::app()->createUrl('/store/merchantsignup?do=step2&package_id=')
          ) ;
          ?>
          <?php FunctionsV3::sectionHeader('Change Package');?>
          
          <div class="top10">
            <?php 
            echo CHtml::dropDownList('change_package',
            isset($_GET['package_id'])?$_GET['package_id']:''
            ,(array)$p_list,array(
              'class'=>'grey-fields full-width',
            ));
            ?>          
          </div>
           
           <div class="top25">
           <a href="<?php echo Yii::app()->createUrl('/store/merchantsignup')?>" class="black-button inline medium">
           <i class="ion-ios-arrow-thin-left"></i> <?php echo t("Back")?>
           </a>
           </div>
           
       </div> <!--box-->
   </div> <!--col-->
   
   </div> <!--row--> 
  </div> <!--container-->  
</div> <!--sections-->