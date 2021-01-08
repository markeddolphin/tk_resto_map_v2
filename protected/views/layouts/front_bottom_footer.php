
<?php if ( getOptionA('disabled_subscription') == ""):?>
<form method="POST" id="frm-subscribe" class="frm-subscribe" onsubmit="return false;">
<?php echo CHtml::hiddenField('action','subscribeNewsletter')?>
<div class="sections section-subcribe">
  <div class="container">
      <h2><?php echo t("Subscribe to our newsletter") ?></h2>
      <div class="subscribe-footer">
          <div class="row border">
             <div class="col-md-3 border col-md-offset-4 ">
               <?php echo CHtml::textField('subscriber_email','',array(
                 'placeholder'=>t("E-mail"),
                 'required'=>true,
                 'class'=>"email"
               ))?>
             </div>
             <div class="col-md-2 border">
               <button class="green-button rounded">
                <?php echo t("Subscribe")?>
               </button>               
             </div>
          </div>
      </div>
  </div>
  

<img src="<?php echo assetsURL()."/images/divider.png"?>" class="footer-divider">
  
</div> <!--section-browse-resto-->
</form>
<?php endif;?>


<div class="sections section-footer">
  <div class="container">
      <div class="row">
         <div class="col-md-4 ">
         <?php FunctionsV3::getFooterAddress();?>
         
        <?php         
        $enabled_lang=FunctionsV3::getEnabledLanguage();
        $lang_list_dropdown=array();
        $lang_list=FunctionsV3::getLanguageList(false);
        if(is_array($lang_list) && count($lang_list)>=1){
        	foreach ($lang_list as $lang_list_val) {
        		if (in_array($lang_list_val,(array)$enabled_lang)){
	        		$key=Yii::app()->createUrl('/store/setlanguage',array(
	        		  'lang'=>$lang_list_val
	        		));
	        		$lang_list_dropdown[$key]=t($lang_list_val);
        		}
        	}
        }                
        if ($show_language<>1){
        	if ( $theme_lang_pos=="bottom" || $theme_lang_pos==""){
		        echo CHtml::dropDownList('language-options',
		        Yii::app()->language
		        ,
		         (array)$lang_list_dropdown
		         ,array(
		         'class'=>"language-options selectpicker",
		         'title'=>t("Select language")
		        ));
        	}
        }
        ?>
         
         </div> <!--col-->
         
         
         <div class="col-md-3 border">
           <?php if ($theme_hide_footer_section1!=2):?>
           <h3><?php echo t("Menu")?></h3>
          
           <?php if (is_array($menu) && count($menu)>=1):?>
           <?php foreach ($menu as $val):
           $page_name_trans['page_name_trans'] =  isset($val['page_name_trans'])? json_decode($val['page_name_trans'],true) : '';	        	
	       $page_name = qTranslate($val['page_name'],'page_name',(array)$page_name_trans);
           ?>
           <li>
             <a 
               href="<?php echo FunctionsV3::customPageUrl($val)?>" <?php FunctionsV3::openAsNewTab($val)?> >
              <?php echo $page_name;?></a>
           </li>
           <?php endforeach;?>
           <?php endif;?>
           
           <?php endif;?>
         </div> <!--col-->
                  
         <div class="col-md-3 border">
         <?php if ($theme_hide_footer_section2!=2):?>
         <h3><?php echo t("Others")?></h3>
         
           <?php if (is_array($others_menu) && count($others_menu)>=1):?>
           <?php foreach ($others_menu as $val):         
           $page_name_trans['page_name_trans'] =  isset($val['page_name_trans'])? json_decode($val['page_name_trans'],true) : '';	        	
	       $page_name = qTranslate($val['page_name'],'page_name',(array)$page_name_trans);
           ?>
           <li>
             <a 
               href="<?php echo FunctionsV3::customPageUrl($val)?>" <?php FunctionsV3::openAsNewTab($val)?> >
              <?php echo $page_name;?></a>
           </li>
           <?php endforeach;?>
           <?php endif;?>
         
         <?php endif;?>  
         </div> <!--col-->
         
         <?php if ($social_flag<>1):?>
         <div class="col-md-2 border">
         <h3><?php echo t("Connect with us")?></h3>
         
         <div class="mytable social-wrap">
           <?php if (!empty($google_page)):?>
           <div class="mycol border">
             <a target="_blank" href="<?php echo FunctionsV3::prettyUrl($google_page)?>"><i class="ion-social-googleplus"></i></a>
           </div> <!--col-->
           <?php endif;?>
           
           <?php if (!empty($twitter_page)):?>
           <div class="mycol border">
             <a target="_blank" href="<?php echo FunctionsV3::prettyUrl($twitter_page)?>"><i class="ion-social-twitter"></i></a>
           </div> <!--col-->
           <?php endif;?>
           
           <?php if (!empty($fb_page)):?>
           <div class="mycol border">
            <a target="_blank" href="<?php echo FunctionsV3::prettyUrl($fb_page)?>"><i class="ion-social-facebook"></i></a>
           </div> <!--col-->
           <?php endif;?>
           
           
           <?php if (!empty($intagram_page)):?>
           <div class="mycol border">
            <a target="_blank" href="<?php echo FunctionsV3::prettyUrl($intagram_page)?>"><i class="ion-social-instagram"></i></a>
           </div> <!--col-->
           <?php endif;?>
           
           <?php if (!empty($youtube_url)):?>
           <div class="mycol border">
            <a target="_blank" href="<?php echo FunctionsV3::prettyUrl($youtube_url)?>"><i class="ion-social-youtube-outline"></i></a>
           </div> <!--col-->
           <?php endif;?>
           
         </div> <!--social wrap-->
         
         </div> <!--col-->
         <?php endif;?>
         
      </div> <!--row-->
  </div> <!--container-->
</div> <!--section-footer-->