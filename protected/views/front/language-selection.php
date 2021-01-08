<?php if ( $lang_list=FunctionsV3::getLanguageList(false) ):
$enabled_lang=FunctionsV3::getEnabledLanguage();
?>
<div class="language-selection-wrap">
  <a href="javascript:;" class="language-selection-close"><i class="ion-ios-close-empty"></i></a>
  <div class="container-medium">
  
   <div class="col-sm-6 border">
    
   </div> <!--col-->
   <div class="col-sm-6 border">
       <div class="lang-list">
       
         <div class="row head bottom10">
           <div class="col-xs-8 border"><?php echo t("Language")?></div>
           <!--<div class="col-xs-4 border"><?php echo t("Language")?></div>-->
           <div class="col-xs-4 border"></div>
         </div>  <!--row-->
         
          <div class="row body">
           <div class="col-xs-8 border">
             <ul>              
              <?php foreach ($lang_list as $val):?>
              <?php if (in_array($val,(array)$enabled_lang)):?>
              <li>
              <a href="<?php echo Yii::app()->createUrl('/store/setlanguage',array(
                'lang'=>$val
              ))?>">
              <img src="<?php echo FunctionsV3::getLanguageFlag($val)?>">
              <span style="padding-left:5px;text-transform:none;"><?php echo t($val)?></span>
              </a>
              </li>
              <?php endif;?>
              <?php endforeach;?>
             </ul>
           </div>
           <!--<div class="col-xs-4 border"><div class="highlight">Espanol</div></div>-->
           <div class="col-xs-4 border">
             <!--<a href="<?php echo Yii::app()->createUrl('/store/setlanguage/',array(
               'Id'=>"-9999"
             ))?>" 
class="change-language orange-button rounded"><?php echo t("Change sites")?></a>-->
           </div>
         </div>  <!--row-->
       
       </div> <!--lang-list-->
   </div> <!--col-->
   
  </div> <!--container-medium-->
</div> <!--language-selection-wrap-->
<?php endif;?>