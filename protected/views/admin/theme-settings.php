<form class="uk-form uk-form-horizontal admin-settings-page forms" id="forms">
<?php 
echo CHtml::hiddenField('action','themeSettings');
FunctionsV3::addCsrfToken(false);
?>

<!--<h2><?php echo t("Website Compression")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")?></label>  
  <?php 
 echo CHtml::checkBox('theme_compression',getOptionA('theme_compression')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>
<p class="uk-text-muted"><?php echo t("this options will compress all your js and css and html for website fast loading")?></p>

<hr/>

-->
  
<!--
<h3><?php echo t("RTL")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled RTL")?></label>  
  <?php 
 echo CHtml::checkBox('theme_enabled_rtl',getOptionA('theme_enabled_rtl')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<hr/>-->

<h2><?php echo t("Home page")?></h2>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Hide website logo")?>?</label>  
  <?php 
 echo CHtml::checkBox('theme_hide_logo',getOptionA('theme_hide_logo')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Hide how it works section")?>?</label>  
  <?php 
 echo CHtml::checkBox('theme_hide_how_works',getOptionA('theme_hide_how_works')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Hide featured restaurant section")?>?</label>  
  <?php 
 echo CHtml::checkBox('disabled_featured_merchant',getOptionA('disabled_featured_merchant')=="yes"?true:false,array(
   'class'=>"icheck",
   'value'=>"yes"
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Hide browse by cuisine section")?>?</label>  
  <?php 
 echo CHtml::checkBox('theme_hide_cuisine',getOptionA('theme_hide_cuisine')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Hide subscription section")?>?</label>  
  <?php 
 echo CHtml::checkBox('disabled_subscription',getOptionA('disabled_subscription')=="yes"?true:false,array(
   'class'=>"icheck",
   'value'=>"yes"
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Hide connect with us section")?>?</label>  
  <?php 
 echo CHtml::checkBox('social_flag',getOptionA('social_flag')==1?true:false,array(
   'class'=>"icheck",
   'value'=>1
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Hide language bar")?>?</label>  
  <?php 
 echo CHtml::checkBox('show_language',getOptionA('show_language')==1?true:false,array(
   'class'=>"icheck",
   'value'=>1
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Custom footer")?></label>  
  <?php 
 echo CHtml::textArea('theme_custom_footer',getOptionA('theme_custom_footer'),array(
   'class'=>"big-text-area"
 ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Featured Restaurant Sort")?></label>  
  <?php 
  echo CHtml::dropDownList('featured_merchant_sort',
  getOptionA('featured_merchant_sort'),array(
    ''=>t("Select options"),
    1=>t("Random"),
    2=>t("Sort by name"),
    3=>t("Sort by ratings")
  ),array(
    'class'=>""
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Browse Page Sort by")?></label>  
  <?php 
  echo CHtml::dropDownList('browse_page_sort',
  getOptionA('browse_page_sort'),array(
    ''=>t("Select options"),
    1=>t("Random"),
    2=>t("Sort by name"),
    3=>t("Sort by ratings")
  ),array(
    'class'=>""
  ))
  ?> 
</div>

<hr/>

<h3><?php echo t("Advance search options")?></h3>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Disabled search by address")?></label>  
  <?php 
echo CHtml::checkBox('theme_search_merchant_address',getOptionA('theme_search_merchant_address')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Disabled search by restaurant name")?></label>  
  <?php 
echo CHtml::checkBox('theme_search_merchant_name',getOptionA('theme_search_merchant_name')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Disabled search by street name")?></label>  
  <?php 
echo CHtml::checkBox('theme_search_street_name',getOptionA('theme_search_street_name')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Disabled search by cuisine")?></label>  
  <?php 
echo CHtml::checkBox('theme_search_cuisine',getOptionA('theme_search_cuisine')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Disabled search by food name")?></label>  
  <?php 
echo CHtml::checkBox('theme_search_foodname',getOptionA('theme_search_foodname')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<hr/>

<h3><?php echo t("Menu")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Top menu")?></label>  
<?php 
$menus = array(
  'browse'=>t("Browse Restaurant"),
  'resto_signup'=>t("Restaurant Signup"),
  'contact'=>t("Contact"),
  'signup'=>t("Login & Signup"),
  'driver_signup'=>t("Driver Signup")
);
$act_menu=FunctionsV3::getTopMenuActivated();
if(!FunctionsV3::hasModuleAddon('driver')){
	unset($menus['driver_signup']);
}
echo CHtml::dropDownList('theme_top_menu[]',(array)FunctionsV3::getTopMenuActivated(),$menus
,array(
  'class'=>"chosen uk-form-width-large",
  "multiple"=>"multiple"
));
?>
</div>

<hr/>

<h3><?php echo t("Footer menu")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Hide Menu section")?></label>  
  <?php 
echo CHtml::checkBox('theme_hide_footer_section1',getOptionA('theme_hide_footer_section1')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Hide Others section")?></label>  
  <?php 
echo CHtml::checkBox('theme_hide_footer_section2',getOptionA('theme_hide_footer_section2')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<hr/>


<h3><?php echo t("Mobile App")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled mobile app section")?>?</label>  
  <?php 
 echo CHtml::checkBox('theme_show_app',getOptionA('theme_show_app')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Google Play Link")?></label>  
  <?php 
 echo CHtml::textField('theme_app_android',getOptionA('theme_app_android'),array(
   'class'=>"uk-form-width-large"
 ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("App Store Link")?></label>  
  <?php 
 echo CHtml::textField('theme_app_ios',getOptionA('theme_app_ios'),array(
   'class'=>"uk-form-width-large"
 ))
  ?> 
</div>

<!--<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Windows Phone Link")?></label>  
  <?php 
 echo CHtml::textField('theme_app_windows',getOptionA('theme_app_windows'),array(
   'class'=>"uk-form-width-large"
 ))
  ?> 
</div>-->
  
  <hr/>

<h3><?php echo t("Search Results")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Do not collapse all filters")?>?</label>  
  <?php 
 echo CHtml::checkBox('theme_filter_colapse',getOptionA('theme_filter_colapse')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled Maps")?>?</label>  
  <?php 
 echo CHtml::checkBox('enabled_search_map',getOptionA('enabled_search_map')=="yes"?true:false,array(
   'class'=>"icheck",
   'value'=>"yes"
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("List Style")?>?</label>  
  <?php 
  echo CHtml::dropDownList('theme_list_style',getOptionA('theme_list_style'),array(
     'gridview'=>t("Grid View"),
     'listview'=>t("List View"),
  ));
  ?> 
</div>

<hr/>

<h3><?php echo t("Food Menu")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Do not collapse menu")?>?</label>  
  <?php 
 echo CHtml::checkBox('theme_menu_colapse',getOptionA('theme_menu_colapse')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<hr/>

<h3><?php echo t("Restaurant menu")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Disabled opening hours tab")?></label>  
  <?php 
 echo CHtml::checkBox('theme_hours_tab',getOptionA('theme_hours_tab')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Disabled reviews tab")?></label>  
  <?php 
 echo CHtml::checkBox('theme_reviews_tab',getOptionA('theme_reviews_tab')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Disabled table booking tab")?></label>  
  <?php 
 echo CHtml::checkBox('merchant_tbl_book_disabled',getOptionA('merchant_tbl_book_disabled')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Disabled map tab")?></label>  
  <?php 
 echo CHtml::checkBox('theme_map_tab',getOptionA('theme_map_tab')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Disabled photos tab")?></label>  
  <?php 
 echo CHtml::checkBox('theme_photos_tab',getOptionA('theme_photos_tab')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Disabled information tab")?></label>  
  <?php 
 echo CHtml::checkBox('theme_info_tab',getOptionA('theme_info_tab')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Disabled promo tab")?></label>  
  <?php 
 echo CHtml::checkBox('theme_promo_tab',getOptionA('theme_promo_tab')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<hr/>

<h3><?php echo t("Cookie law")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled Cookie law")?></label>  
  <?php 
 echo CHtml::checkBox('cookie_law_enabled',getOptionA('cookie_law_enabled')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Accept cookies button text")?></label>  
  <?php 
 echo CHtml::textField('cookie_accept_text',getOptionA('cookie_accept_text'),array(
   'class'=>"uk-form-width-large"
 ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("What are cookies button text")?></label>  
  <?php 
 echo CHtml::textField('cookie_info_text',getOptionA('cookie_info_text'),array(
   'class'=>"uk-form-width-large"
 ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Cookie Privacy message")?></label>  
  <?php 
 echo CHtml::textArea('cookie_msg_text',getOptionA('cookie_msg_text'),array(
   'class'=>"uk-form-width-large"
 ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("What are cookies link")?></label>  
  <?php 
 echo CHtml::textField('cookie_info_link',getOptionA('cookie_info_link'),array(
   'class'=>"uk-form-width-large"
 ))
  ?> 
</div>

<hr/>
<h3><?php echo t("Age Restriction")?></h3>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled")?></label>  
  <?php 
  echo CHtml::checkBox('age_restriction',
  getOptionA('age_restriction')==1?true:false,array(
    'value'=>1
  ))
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Content")?></label>  
  <?php 
  echo CHtml::textArea('age_restriction_content',
  getOptionA('age_restriction_content'),array(
    'class'=>"uk-form-width-large"
  ));
  ?> 
</div>

<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Exit Link")?></label>  
  <?php 
  echo CHtml::textField('age_restriction_exit_link',
  getOptionA('age_restriction_exit_link'),array(
    'class'=>"uk-form-width-large"
  ));
  ?> 
</div>


<hr/>

<h3><?php echo t("Language bar position")?></h3>


<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Position")?></label>  
  <?php 
  echo CHtml::dropDownList('theme_lang_pos',
  getOptionA('theme_lang_pos')
  ,array(
    'bottom'=>t("bottom"),
    'top'=>t("top"),
  ))
  ?> 
</div>

<hr/>


<div style="display:none;">
<h3><?php echo t("Time Picker")?></h3>
<div class="uk-form-row">
  <label class="uk-form-label"><?php echo t("Enabled Time Picker UI")?></label>  
  <?php 
 echo CHtml::checkBox('theme_time_pick',getOptionA('theme_time_pick')==2?true:false,array(
   'class'=>"icheck",
   'value'=>2
 ));
  ?> 
</div>
<hr/>
</div>


<div class="uk-form-row">
<label class="uk-form-label"></label>
<input type="submit" value="<?php echo Yii::t("default","Save")?>" class="uk-button uk-form-width-medium uk-button-success">
</div>

</form>