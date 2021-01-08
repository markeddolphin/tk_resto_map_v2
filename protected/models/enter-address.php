
<div class="container enter-address-wrap">

<div class="section-label">
    <a class="section-label-a">
      <span class="bold">
      <?php echo t("Enter your address below")?></span>
      <b></b>
    </a>     
</div>  

<form id="frm-modal-enter-address" class="frm-modal-enter-address" method="POST" onsubmit="return false;" >
<?php echo CHtml::hiddenField('action','setAddress');?> 
<?php echo CHtml::hiddenField('web_session_id',
isset($this->data['web_session_id'])?$this->data['web_session_id']:''
);?>

<div class="row">
  <div class="col-md-12 ">
    <?php echo CHtml::textField('client_address',
	 isset($_SESSION['kr_search_address'])?$_SESSION['kr_search_address']:''
	 ,array(
	 'class'=>"grey-inputs",
	 'data-validation'=>"required"
	 ))?>
  </div> 
  
  <div class="geocoder_address" id="geocoder_address"></div>
  
</div> <!--row-->

<div class="row food-item-actions top10">
  <div class="col-md-5 "></div>
  <div class="col-md-3 ">
  <a href="javascript:$.fancybox.close();" class="orange-button inline center">
  <?php echo t("Close")?>
  </a>
  </div>
  <div class="col-md-3 ">
     <input type="submit" class="green-button inline" value="<?php echo t("Submit")?>">
  </div>
</div>

 </form>

</div> <!--container-->

<script type="text/javascript">
$.validate({ 	
	language : jsLanguageValidator,
	language : jsLanguageValidator,
    form : '#frm-modal-enter-address',    
    onError : function() {      
    },
    onSuccess : function() {     
      form_submit('frm-modal-enter-address');
      return false;
    }  
})

jQuery(document).ready(function() {
	var google_auto_address= $("#google_auto_address").val();	
	if ( google_auto_address =="yes") {		
	} else {
		if(useMapbox()){
			mapbox_client_address();
		} else {
			$("#client_address").geocomplete({
			    country: $("#admin_country_set").val()
			});	
		}
	}
});

jQuery(document).ready(function() {		
	 $( "#client_address" ).on( "keydown", function(event) {
	 	if(event.which == 13){
	 	   $("#frm-modal-enter-address").submit();
	 	}
	 });
});


function mapbox_client_address()
{
	$("#client_address").remove();
	
	var country_code = $("#admin_country_set").val();
	if(empty(country_code)){
		country_code = "";
	} else {
		if ( $("#google_default_country").val()!="yes" ){
			country_code = '';
		}
	}
		
	var geocoder = new MapboxGeocoder({
	    accessToken: mapbox_access_token ,
	    country: country_code ,
	    flyTo : false
	});
	
	document.getElementById('geocoder_address').appendChild(geocoder.onAdd());
	
	$(".geocoder_address input").attr("name","client_address");
	$(".geocoder_address input").attr("id","client_address");
	$(".geocoder_address input").attr("placeholder", js_lang.search_placeholder );	
	$(".geocoder_address input").attr("autocomplete","off");
	
}

</script>
<?php
die();