<?php
$this->renderPartial('/front/banner-receipt',array(
   'h1'=>t("Driver Signup"),
   'sub_text'=>t("Become one of our drivers")
));

echo CHtml::hiddenField('mobile_country_code',Yii::app()->functions->getAdminCountrySet(true));
?>

<div class="sections section-grey">

  <div class="container">
     <div class="row">
       <div class="col-md-8">
         <div class="box-grey round top-line-green">
            
            <div class="section-label">
		    <a class="section-label-a">
		      <span class="bold">
		      <?php echo t("Sign up")?></span>
		      <b></b>
		    </a>     
		   </div>
		   
		   <form class="forms" id="forms" onsubmit="return false;">
		   <?php echo CHtml::hiddenField('action','driverSignup')?>
		   
		     <div class="row top10">
		        <div class="col-md-3 "><?php echo t("First Name")?></div>
		        <div class="col-md-8 ">
		             <?php echo CHtml::textField('first_name',
					  ''
					  ,array(
					  'class'=>'grey-fields full-width',
					  'data-validation'=>"required"
					  ))?>
		        </div>
		      </div>
		      
		      <div class="row top10">
		        <div class="col-md-3 "><?php echo t("Last Name")?></div>
		        <div class="col-md-8 ">
		             <?php echo CHtml::textField('last_name',
					  ''
					  ,array(
					  'class'=>'grey-fields full-width',
					  'data-validation'=>"required"
					  ))?>
		        </div>
		      </div>
		      
		      <div class="row top10">
		        <div class="col-md-3 "><?php echo t("Email address")?></div>
		        <div class="col-md-8 ">
		             <?php echo CHtml::textField('email',
					  ''
					  ,array(
					  'class'=>'grey-fields full-width',
					  'data-validation'=>"required"
					  ))?>
		        </div>
		      </div>
		      
		      <div class="row top10">
		        <div class="col-md-3 "><?php echo t("Phone")?></div>
		        <div class="col-md-8 ">
		             <?php echo CHtml::textField('phone',
					  ''
					  ,array(
					  'class'=>'grey-fields full-width mobile_inputs ',
					  'data-validation'=>"required"
					  ))?>
		        </div>
		      </div>
		      
		      <div class="row top10">
		        <div class="col-md-3 "><?php echo t("Username")?></div>
		        <div class="col-md-8 ">
		             <?php echo CHtml::textField('username',
					  ''
					  ,array(
					  'class'=>'grey-fields full-width',
					  'data-validation'=>"required"
					  ))?>
		        </div>
		      </div>
		      
		      <div class="row top10">
		        <div class="col-md-3 "><?php echo t("Password")?></div>
		        <div class="col-md-8 ">
		             <?php echo CHtml::passwordField('password',
					  ''
					  ,array(
					  'class'=>'grey-fields full-width',
					  'data-validation'=>"required"
					  ))?>
		        </div>
		      </div>
		      
		      <div class="row top10">
		        <div class="col-md-3 "><?php echo t("Confirm Password")?></div>
		        <div class="col-md-8 ">
		             <?php echo CHtml::passwordField('cpassword',
					  ''
					  ,array(
					  'class'=>'grey-fields full-width',
					  'data-validation'=>"required"
					  ))?>
		        </div>
		      </div>
		      
		      <div class="row top10">
		        <div class="col-md-3 "><?php echo t("Transportation")?></div>
		        <div class="col-md-8 ">
		             <?php 
		             echo CHtml::dropDownList('transport_type_id','',
		             (array)Driver::transportType()
		             ,array(
		               'class'=>'grey-fields full-width',
		               'data-validation'=>"required"
		             ));
		             ?>
		        </div>
		      </div>
		      
		      <div class="row top10">
		        <div class="col-md-3 "><?php echo t("Transport Description (Year,Model)")?></div>
		        <div class="col-md-8 ">
		             <?php echo CHtml::textField('transport_description',
					  ''
					  ,array(
					  'class'=>'grey-fields full-width',
					  'data-validation'=>"required"
					  ))?>
		        </div>
		      </div>
		      
		      <div class="row top10">
		        <div class="col-md-3 "><?php echo t("License Plate")?></div>
		        <div class="col-md-8 ">
		             <?php echo CHtml::textField('licence_plate',
					  ''
					  ,array(
					  'class'=>'grey-fields full-width',
					  'data-validation'=>"required"
					  ))?>
		        </div>
		      </div>
		      
		      <div class="row top10">
		        <div class="col-md-3 "><?php echo t("Color")?></div>
		        <div class="col-md-8 ">
		             <?php echo CHtml::textField('color',
					  ''
					  ,array(
					  'class'=>'grey-fields full-width',
					  'data-validation'=>"required"
					  ))?>
		        </div>
		      </div>
		      
		      <div class="row top10">
		        <div class="col-md-3"></div>
		        <div class="col-md-8">
		          <input type="submit" value="<?php echo t("Submit")?>" class="orange-button inline medium">
		        </div>
		      </div>
		   
		   </form>
            
         </div> <!--box-->
       </div> <!--col-->
     </div> <!--row-->
  </div> <!--container-->

</div> <!--grey-->