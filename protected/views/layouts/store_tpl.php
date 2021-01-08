<!--HEADER-->
<?php $this->renderPartial('/layouts/front_header');?>

<!--COOKIE LAW-->
<?php if (getOptionA('cookie_law_enabled')==2):?>
<?php $this->renderPartial('/front/cookie-law',array(
  'cookie_accept_text'=>getOptionA('cookie_accept_text'),
  'cookie_info_text'=>getOptionA('cookie_info_text'),
  'cookie_msg_text'=>getOptionA('cookie_msg_text'),
  'cookie_info_link'=>getOptionA('cookie_info_link')
));?>
<?php endif;?>
<!--COOKIE LAW-->

<!--LANGUAGE SELECTION TOP-->
<?php if (getOptionA('theme_lang_pos')=="top"):?>
<?php $this->renderPartial('/front/language-selection');?>
<?php endif;?>
<!--LANGUAGE SELECTION TOP-->

<!--TOP MENU-->
<?php $this->renderPartial('/layouts/front_top_menu',array(
  'action'=>isset(Yii::app()->controller->action->id)?Yii::app()->controller->action->id:'',
  'theme_hide_logo'=>getOptionA('theme_hide_logo')
));?>

<!--MAIN CONTENT-->
<?php echo $content;?>

<!--BOOTOM FOOTER-->
<?php $this->renderPartial('/layouts/front_bottom_footer',array(
  'fb_page'=>getOptionA('admin_fb_page'),
  'twitter_page'=>getOptionA('admin_twitter_page'),
  'google_page'=>getOptionA('admin_google_page'),
  'menu'=>Yii::app()->functions->customPagePosition("bottom"),
  'others_menu'=>Yii::app()->functions->customPagePosition("bottom2"),
  'social_flag'=>getOptionA('social_flag'),
  'show_language'=>getOptionA('show_language'),
  'theme_lang_pos'=>getOptionA('theme_lang_pos'),
  'intagram_page'=>getOptionA('admin_intagram_page'),
  'youtube_url'=>getOptionA('admin_youtube_url'),
  'theme_hide_footer_section1'=>getOptionA('theme_hide_footer_section1'),
  'theme_hide_footer_section2'=>getOptionA('theme_hide_footer_section2'),
));?>

<!--PRELOADER-->
<div class="main-preloader">
   <div class="inner">
   <div class="ploader"></div>
   </div>
</div> 
<!--PRELOADER-->

<!--FOOTER-->
<?php $this->renderPartial('/layouts/front_footer');?>