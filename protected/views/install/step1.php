
<h3>Checking requirements..</h3>

<?php
$failed=0;
$_SESSION['kr_install']=2;
$path=Yii::getPathOfAlias('webroot');
?>


<?php echo CHtml::beginForm( Yii::app()->createUrl('index.php/install/step2'),
   'post', 
   array('onsubmit'=>'return formSubmit()') 
);
?>

<p>
<?php
try {
    echo 'Connecting to database...<br/>';
    $connection = Yii::app()->db;  // (*)
    echo ($connection ? 'Database Successful [OK]' : 'Database Failed');
}
catch(Exception $ex) {
    echo $ex->getMessage();
    $failed++;
}
?>
</p>

<p>
<?php 
if (!defined('PDO::ATTR_DRIVER_NAME')){    	
	echo "PDO is not installed";
	$failed++;
} else echo "PDO installed [OK]";
?>
</p>

<p>
<?php 
$_SESSION['test']='test';
if (!empty($_SESSION['test'])){
	echo "Session [OK]";
} else {
	echo "Session not supported";
	$failed++;
}
?>
</p>

<p>
<?php 
if ( !function_exists( 'mail' ) ) { 
	echo "mail() has been disabled";
	$failed++;
} else echo "mail() is available [OK]"	;
?>
</p>

<p>
<?php 
if ( function_exists('curl_version') ){
	echo "CURL is enabled [OK]";
} else {
	echo "CURL is disabled";
	$failed++;
}
?>
</p>

<p>
<?php 
if ( @file_get_contents(__FILE__) ){
	echo "file_get_contents is enabled [OK]";
} else {
	echo "file_get_contents is disabled";
	$failed++;
}
?>
</p>


<p>
<?php 
$t_path=explode("/",$path);        
$host=dirname($_SERVER['REQUEST_URI']);
$host=$host=="/"?"":$host;
$current_dir_folder=$host;
$ht_file=$path."/.htaccess";    
$current_dir_folder=str_replace("/index.php",'',$current_dir_folder);

$windows_config='';
$win_file=$path."/web.config";    

if(file_exists($ht_file)){
	/*REMOVE ANY EXISTING HTACCESS*/
	@unlink($ht_file);
	@unlink($win_file);	
}

if(!file_exists($ht_file)){
	echo 'Creating .htaccess file<br/>';    
	if ( $host=="htdocs" || $host=="public_html" || $host==""){
$htaccess="<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . /index.php [L]
</IfModule>";  	

$windows_config='
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
	<system.webServer>
		<rewrite>
 <rules>
     <rule name="Main Rule" stopProcessing="true">
         <match url=".*" />
         <conditions logicalGrouping="MatchAll">
             <add input="{REQUEST_FILENAME}" matchType="IsFile" negate="true" />
             <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />
         </conditions>
         <action type="Rewrite" url="index.php/{R:0}" />
     </rule>
 </rules>
</rewrite>
	</system.webServer>
</configuration>
';

} else {  
	$current_dir_folder2=$current_dir_folder."/index.php";
$htaccess="<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase $current_dir_folder/
RewriteRule ^index\.php$ - [L]
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule . $current_dir_folder2 [L]
</IfModule>";

$windows_config='
<?xml version="1.0" encoding="UTF-8"?>
<configuration>
	<system.webServer>
		<rewrite>
 <rules>
     <rule name="Main Rule" stopProcessing="true">
         <match url=".*" />
         <conditions logicalGrouping="MatchAll">
             <add input="{REQUEST_FILENAME}" matchType="IsFile" negate="true" />
             <add input="{REQUEST_FILENAME}" matchType="IsDirectory" negate="true" />
         </conditions>
         <action type="Rewrite" url="'.$current_dir_folder.'/index.php/{R:0}" />
     </rule>
 </rules>
</rewrite>
	</system.webServer>
</configuration>
';

}
	InstallHelper::dump($htaccess);	
	InstallHelper::createFile($ht_file,$htaccess);
			
	if(!file_exists($win_file)){
		InstallHelper::createFile($win_file,$windows_config);
	}
		
}


$path_to_upload=Yii::getPathOfAlias('webroot')."/upload";

echo 'Creating folder upload<br/>';    
if(!file_exists($path_to_upload)) {	
  if (!@mkdir($path_to_upload,0777)){
  	  echo "Cannot create upload folder please create a folder manually"." $path_to_upload<br/>";
  	  $failed++;
  } else {
  	  $upload_ht_file = $path_to_upload."/.htaccess"; 
$upload_ht_content='<Files *>
SetHandler None
</Files>

<Files *.php>
deny from all
</Files>

<Files *.html>
deny from all
</Files>

<Files *.js>
deny from all
</Files>

<Files *.cgi>
deny from all
</Files>
  	  ';
  	  InstallHelper::createFile($upload_ht_file,$upload_ht_content);
  	  
  }		    
}

$path_helper=Yii::getPathOfAlias('webroot')."/cronHelper";

echo 'Creating folder cronHelper<br/>';    
if(!file_exists($path_helper)) {	
  if (!@mkdir($path_helper,0777)){
  	  echo "Cannot create upload folder please create a folder manually"." $path_helper<br/>";
  	  $failed++;
  }		    
}

echo 'Creating folder runtime<br/>';   
$runtime_path=Yii::getPathOfAlias('webroot')."/protected/runtime";
if(!file_exists($runtime_path)) {	
  if (!@mkdir($runtime_path,0777)){
  	  echo "Cannot create runtime folder please create a folder manually"." $runtime_path<br/>";
  	  $failed++;
  }		    
}
?>    
</p>

<p>
<?php 
if ( $failed<=0){
	$_SESSION['kr_install']=1;
	echo '<h5>Everything seems to be ok. Proceed to next steps</h5>';
} else echo '<h5 style="color:red;font-size:16px;">There seems to be error in checking your server. Please fixed the following issue and try again.</h5>';
?>
</p>

<?php if($_SESSION['kr_install']==1):?>
<div class="panel panel-default">
<div class="panel-body">
 <button class="btn btn-success" type="submit" name="action">
   Next
 </button>
</div> 
</div>
<?php endif;?>


<?php echo CHtml::endForm() ; ?>

<script type="text/javascript">
function formSubmit()
{
	$(".btn").attr("enabled",false);
	return true;
}
</script>