<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html lang="en">
<head>

<!-- IE6-8 support of HTML5 elements --> 
<!--[if lt IE 9]>
<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!--[if IE]>
<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
<link rel="shortcut icon" href="<?php echo  Yii::app()->request->baseUrl; ?>/favicon.ico?ver=1.1" />
</head>
<body>

<div class="container">
  <div class="section-process">
   <div class="row">
      <div class="col-md-2 col-md-offset-2">
        <span class="<?php echo $this->steps==1?"active":"";?>">Checking Prerequisite</span>
      </div>
      <div class="col-md-2">
        <span class="<?php echo $this->steps==2?"active":"";?>">
        Creating Tables
        </span>
      </div>
      <div class="col-md-2">
        <span class="<?php echo $this->steps==3?"active":"";?>">
        Information
        </span>
      </div>
      <div class="col-md-2">
        <span class="<?php echo $this->steps==4?"active":"";?>">
        Finish
        </span>
      </div>
   </div>
  </div> <!--section-process-->
</div>

<div class="orange-header">
   <div class="container">
     <img src="<?php echo Yii::app()->request->baseUrl."/assets/images/logo-desktop.png"?>">
   </div> 
</div> <!--orange-header-->

<div class="container">
<div class="row">
   <div class="col-md-7 col-md-offset-2">
    <?php echo $content?>
   </div>
</div>
</div> <!--container-->

<div class="footer">
</div>

</body>
</html>