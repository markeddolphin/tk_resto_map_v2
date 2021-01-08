
<h3>Site information</h3>

<?php echo CHtml::beginForm( Yii::app()->createUrl('index.php/install/finish') ,
   'post', 
   array('onsubmit'=>'return checkform();') 
);
?>

<div class="form-group">
   <label>Website name</label>
   <?php echo CHtml::textField('website_title','',array(
    'class'=>"form-control",
    'required'=>true
   ))?>
</div>

<div class="form-group">
   <label>Address</label>
   <?php echo CHtml::textField('website_address','',array(
    'class'=>"form-control",
    'required'=>true
   ))?>
</div>

<div class="form-group">
   <label>Country</label>
   <?php echo CHtml::dropDownList('admin_country_set','US',
   (array)$country_list
   ,array(
    'class'=>"form-control",
    'required'=>true
   ))?>
</div>

<div class="form-group">
   <label>Phone</label>
   <?php echo CHtml::textField('website_contact_phone','',array(
    'class'=>"form-control",
    'required'=>true
   ))?>
</div>

<div class="form-group">
   <label>Email</label>
   <?php echo CHtml::textField('website_contact_email','',array(
    'class'=>"form-control",
    'required'=>true
   ))?>
</div>

<div class="form-group">
   <label>Currency</label>
   <?php echo CHtml::dropDownList('admin_currency_set','',
   (array)$currency_list
   ,array(
    'class'=>"form-control",
    'required'=>true
   ))?>
</div>

<h3 style="margin-top:20px;margin-bottom:20px;">Admin user</h3>

<div class="row">
 <div class="col-md-6">
 
 <div class="form-group">
   <label>First name</label>
   <?php echo CHtml::textField('first_name','',array(
    'class'=>"form-control",
    'required'=>true
   ))?>
</div>

 </div> <!--col-->
 <div class="col-md-6">
 
 <div class="form-group">
   <label>Last name</label>
   <?php echo CHtml::textField('last_name','',array(
    'class'=>"form-control",
    'required'=>true
   ))?>
</div>
 
 </div> <!--col-->
</div> <!--row-->

<div class="form-group">
   <label>Username</label>
   <?php echo CHtml::textField('username','',array(
    'class'=>"form-control",
    'required'=>true
   ))?>
</div>
<div class="form-group">
   <label>Password</label>
   <?php echo CHtml::passwordField('password','',array(
    'class'=>"form-control",
    'required'=>true
   ))?>
</div>
<div class="form-group">
   <label>Confirm Password</label>
   <?php echo CHtml::passwordField('cpassword','',array(
    'class'=>"form-control",
    'required'=>true
   ))?>
</div>

<div class="panel panel-default">
<div class="panel-body">
 <button class="btn btn-success" type="submit" name="action">
   Next
 </button>
</div> 
</div>

<?php echo CHtml::endForm() ; ?>
