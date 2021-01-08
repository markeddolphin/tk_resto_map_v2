<?php
/**
* On-the-fly CSS Compression
* Copyright (c) 2009 and onwards, Manas Tungare.
* Creative Commons Attribution, Share-Alike.
*
* In order to minimize the number and size of HTTP requests for CSS content,
* this script combines multiple CSS files into a single file and compresses
* it on-the-fly.
*
* To use this in your HTML, link to it in the usual way:
* <link rel="stylesheet" type="text/css" media="screen, print, projection" href="/css/compressed.css.php" />
*/

$assets_url=Yii::app()->request->baseUrl."/assets";
$assets_dir=Yii::getPathOfAlias('webroot')."/assets";
/*echo $assets_url;
echo '<br/>';
echo $assets_dir;
die();*/

/* Add your CSS files to this array (THESE ARE ONLY EXAMPLES) */
$cssFiles = array(
  $assets_dir."/vendor/jquery-ui-1.11.4/jquery-ui-modified.css",
  $assets_dir."/vendor/font-awesome/css/font-awesome.css",
  $assets_dir."/vendor/iCheck/skins/minimal/minimal-modified.css",
  $assets_dir."/vendor/iCheck/skins/flat/flat-modified.css",
  $assets_dir."/vendor/chosen/chosen-modified.css",
  $assets_dir."/vendor/fancybox/source/jquery.fancybox-modified.css",
  $assets_dir."/vendor/magnific-popup/magnific-popup.css",
  $assets_dir."/vendor/intel/build/css/intlTelInput-modified.css",
  $assets_dir."/vendor/rupee/rupyaINR-modified.css",
  $assets_dir."/vendor/bootstrap/css/bootstrap.css",
  $assets_dir."/vendor/raty/jquery.raty.css",
  $assets_dir."/vendor/ionicons-2.0.1/css/ionicons.min.css",
  $assets_dir."/vendor/compress/bootstrap-select.min.css",
  $assets_dir."/vendor/nprogress/nprogress.css",
  $assets_dir."/vendor/justified-gallery/css/justifiedGallery.min.css",
  $assets_dir."/vendor/EasyAutocomplete/easy-autocomplete.min.css",
  $assets_dir."/vendor/animate.min.css",
  $assets_dir."/css/store.css",
  $assets_dir."/css/store-v2.css",
  $assets_dir."/css/responsive.css",
);
 
/**
* Ideally, you wouldn't need to change any code beyond this point.
*/
$buffer = "";
foreach ($cssFiles as $cssFile) {
$buffer .= file_get_contents($cssFile);
}
 
// Remove comments
$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
 
// Remove space after colons
$buffer = str_replace(': ', ':', $buffer);
 
// Remove whitespace
//$buffer = str_replace(array("\r\n", "\r", "\n", "\t", ' ', ' ', ' '), '', $buffer);
$buffer = str_replace(array("\r\n", "\r", "\n", "\t"), '', $buffer);
//$buffer = ereg_replace(" {2,}", ' ',$buffer);

$buffer=str_replace("../images/",$assets_url."/images/",$buffer);
$buffer=str_replace("img/",$assets_url."/images/vendor/",$buffer);
$buffer=str_replace("../fonts/",$assets_url."/fonts/",$buffer);
 
// Enable GZip encoding.
//ob_start("ob_gzhandler");
 
// Enable caching
//header('Cache-Control: public');
 
// Expire in one day
//header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
 
// Set the correct MIME type, because Apache won't set it for us
//header("Content-type: text/css");
 
// Write everything out
//echo($buffer);
return $buffer;
?>