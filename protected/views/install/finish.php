
<?php if($code==1):?>
<h2>Installation done...</h2>

<?php $base=Yii::app()->getBaseUrl(true);?>
<p>Admin link</p>
<a href="<?php echo $base?>/admin" target="_blank"><?php echo $base?>/admin</a>

<p style="margin-top:10px">Front link</p>
<a href="<?php echo $base?>/" target="_blank"><?php echo $base?>/</a>

<p style="margin-top:10px">Merchant link</p>
<a href="<?php echo $base?>/merchant" target="_blank"><?php echo $base?>/merchant</a>

<p style="color:red;margin-top:20px;">
Important : For security purposes you can delete or rename the file controllers/InstallController.php
</p>

<?php else :?>
<h3>Installation has failed.</h3>
<p style="color:red;"><?php echo $msg;?></p>
<p style="margin-top:50px;"><a href="<?php echo Yii::app()->createUrl('index.php/install')?>">CLick here</a> to install again</p>
<?php endif;?>
	  