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

?>


<div class="sections section-grey2">

  <div class="container">
  
  <div class="row">  
  <div class="col-md-8 ">
    <div class="box-grey round top-line-green">
                  
     
       <form class="forms" id="forms" onsubmit="return false;">
	  <?php echo CHtml::hiddenField('action','merchantSignUp2')?>
	  <?php echo CHtml::hiddenField('currentController','store')?>	 
	  <?php FunctionsV3::addCsrfToken();?>
      
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
		  'class'=>'grey-fields full-width',
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
		  <?php echo CHtml::dropDownList('cuisine[]',
		  isset($data['cuisine'])?(array)json_decode($data['cuisine']):"",
		  (array)Yii::app()->functions->Cuisine(true),          
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
      <?php FunctionsV3::sectionHeader('Commission Type');?>
      </div>
      
      <div class="row top10">
        <div class="col-md-3"><?php echo t("Type")?></div>
        <div class="col-md-8">
		  <?php
		  echo CHtml::dropDownList('merchant_type','',
		  (array)$commission_type,array(
		    'class'=>'grey-fields full-width merchant_type_selection',
		    'data-validation'=>"required"
		  ))
		  ?>           
        </div>
      </div>
      
       <div class="row top10 invoice_terms_wrap">
        <div class="col-md-3"><?php echo t("Invoice Terms")?></div>
        <div class="col-md-8">
		  <?php
		  echo CHtml::dropDownList('invoice_terms','',
		  (array)FunctionsV3::InvoiceTerms(),array(
		    'class'=>'grey-fields full-width',
		    'data-validation'=>"required"
		  ))
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
      
     
      <?php if ($kapcha_enabled==2):?>      
      <div class="recaptcha_v3"><?php GoogleCaptchaV3::init();?></div> 
      <?php endif;?>
      
      <div class="row top10">
        <div class="col-md-3"></div>
        <div class="col-md-8">
          <input type="submit" value="<?php echo t("Next")?>" class="orange-button inline medium">
        </div>
      </div>
      
      </form>
                   
    </div> <!--box-grey-->
    
   </div> <!--col-->
    
   </div> <!--row--> 
  </div> <!--container-->  
</div> <!--sections-->