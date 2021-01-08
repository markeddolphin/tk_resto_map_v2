var smap; /*global map variable*/
var otables;

jQuery(document).ready(function() {		
	
	if ( $(".tab-byaddress").exists()){
	    $(".tab-byaddress").fadeIn("slow");
	}
	
	/*DESKTOP MENU*/
	
	if ( $(".search-menu").exists() )
	{
		var selected=$(".search-menu li:first-child");
		var class_name=selected.find("a").attr("data-tab");
		$(".forms-search").hide();
		$("."+class_name).fadeIn("slow");	
	}
	
	$( document ).on( "click", ".search-menu a", function() {
		var tab = $(this).data("tab");		
		$(".search-menu a").removeClass("selected");
		$(this).addClass("selected");		
		
		$(".forms-search").hide();
		$("."+tab).fadeIn("slow");		
				
		switch (tab)
		{
			case "tab-byaddress":			
			$(".home-search-text").html( $("#find_restaurant_by_address").val() );
			break;
			
			case "tab-byname":
			$(".home-search-text").html( js_lang.find_restaurant_by_name );
			break;
			
			case "tab-bystreet":
			$(".home-search-text").html( js_lang.find_restaurant_by_streetname );
			break;
			
			case "tab-bycuisine":
			$(".home-search-text").html( js_lang.find_restaurant_by_cuisine );
			break;
			
			case "tab-byfood":
			$(".home-search-text").html( js_lang.find_restaurant_by_food );
			break;
		}
		
	});
	
	/*MOBILE MENU*/
	$( document ).on( "click", ".mobile-search-menu a", function() {
		var tab = $(this).data("tab");		

		$(".mobile-search-menu a").removeClass("selected");
		$(this).addClass("selected");		
		
		$(".forms-search").hide();
		$("."+tab).fadeIn("slow");		
		
		switch (tab)
		{
			case "tab-byaddress":			
			$(".home-search-text").html( $("#find_restaurant_by_address").val() );
			break;
			
			case "tab-byname":
			$(".home-search-text").html( js_lang.find_restaurant_by_name );
			break;
			
			case "tab-bystreet":
			$(".home-search-text").html( js_lang.find_restaurant_by_streetname );
			break;
			
			case "tab-bycuisine":
			$(".home-search-text").html( js_lang.find_restaurant_by_cuisine );
			break;
			
			case "tab-byfood":
			$(".home-search-text").html( js_lang.find_restaurant_by_food );
			break;
		}
		
	});
	
	
	/*RATING STARS*/
	if ( $(".rating-stars").exists() ){
	   initRating();
	}
	
	if ( $(".raty-stars").exists() ){
		$('.raty-stars').raty({ 
		   readOnly: false, 		
		   hints:'',
		   path: sites_url+'/assets/vendor/raty/images',
		   click: function (score, evt) {
		   	   $("#initial_review_rating").val(score);
		   }
        });    
	}

	/*FILTER BOX*/
	$( document ).on( "click", ".filter-box a", function() {
		var parent=$(this).parent();
		var t=$(this);
		var i=t.find("i");		
		if ( i.hasClass("ion-ios-arrow-thin-right")){
			i.removeClass("ion-ios-arrow-thin-right");
			i.addClass("ion-ios-arrow-thin-down");
		} else {
			i.addClass("ion-ios-arrow-thin-right");
			i.removeClass("ion-ios-arrow-thin-down");
		}
		var parent2 = parent.find("ul").slideToggle( "fast", function() {
			 parent2.removeClass("hide");
        });
	});
				
    if ( $(".infinite-container").exists()) { 	
		var infinite = new Waypoint.Infinite({
	       element: $('.infinite-container')[0],       
	       onBeforePageLoad : function() {
	       	  dump('onBeforePageLoad');
	       	  $(".search-result-loader").show();
	       },
	       onAfterPageLoad : function() {
	       	  dump('onAfterPageLoad');
	       	  $(".search-result-loader").hide();
	       	  initRating();
	       	  removeFreeDelivery();
	       	  if ( $("#restuarant-list").exists() ){
	    	       plotMap();
	          }           
	       }
	    }); 
   }
   
   $( document ).on( "click", ".display-type", function() {   	   
   	   $("#display_type").val( $(this).data("type") );   	   
   	   research_merchant(); 
   });    
        
   $('.filter_promo').on('ifChecked', function(event){      
      $(".non-free").fadeOut("fast");
   });
   
   $('.filter_promo').on('ifUnchecked', function(event){       
       $(".non-free").fadeIn("fast");
   });        

   /*SEARCH MAP TOOGLE*/  
   $( document ).on( "click", ".search-view-map, #mobile-viewmap-handle", function() {   	   
   	   if ( $(".search-map-results").hasClass("down") ){
   	   	 $(".search-map-results").slideUp( 'slow', function(){ 
   	      	   $(".search-map-results").removeClass("down")
   	      });
   	   } else {
   	      $(".search-map-results").slideDown( 'slow', function(){ 
   	      	   $(".search-map-results").addClass("down");
   	      	   dump('load map');   	 
   	      	   
   	      	   if(useMapbox()){
   	      	   	  mapbox_fullmap($("#clien_lat").val() , $("#clien_long").val() , 'search-map-results' );
   	      	   } else {
	   	      	   map = new GMaps({
						div: '.search-map-results',
						lat: $("#clien_lat").val(),
						lng: $("#clien_long").val(),
						scrollwheel: false ,
						styles: [ {stylers: [ { "saturation":-100 }, { "lightness": 0 }, { "gamma": 1 } ]}],
					    markerClusterer: function(map) {
	                        return new MarkerClusterer(map);
	                    }
				   });      	   
	   	      	   callAjax('loadAllRestoMap','');    	      	   
   	      	   }
   	      }); /*end slidedown*/
   	   }
   });
   

    /*TABS*/
    $("ul#tabs li").click(function(e){    	
    	if ( $(this).hasClass("noclick") ){
    		return;    		
    	}
        if (!$(this).hasClass("active")) {
            var tabNum = $(this).index();
            var nthChild = tabNum+1;
            dump(nthChild);
            $("ul#tabs li.active").removeClass("active");
            $(this).addClass("active");
            $("ul#tab li.active").removeClass("active");
            $("ul#tab li:nth-child("+nthChild+")").addClass("active");
        }
    });
   /*END TABS*/   

   /*SET MENU STICKY*/
   var disabled_cart_sticky=$("#disabled_cart_sticky").val();
   if ( $(".menu-right-content").exists() ){	   
   	   dump(disabled_cart_sticky);
   	   if (disabled_cart_sticky!=2){
		   jQuery('.menu-right-content, .category-list').theiaStickySidebar({      
		      additionalMarginTop: 8
		   }); 
   	   }
   }
   if ( $(".sticky-div").exists() ){	   	  
   	   if (disabled_cart_sticky!=2){ 
		   jQuery('.sticky-div').theiaStickySidebar({      
		      additionalMarginTop: 8
		   }); 
   	   }
   }  
    
   /*MENU 1*/  
   $( document ).on( "click", ".menu-1 .menu-cat a", function() {
		var parent=$(this).parent();		
		var t=$(this);
		var i=t.find("i");		
		if ( i.hasClass("ion-ios-arrow-thin-right")){
			i.removeClass("ion-ios-arrow-thin-right");
			i.addClass("ion-ios-arrow-thin-down");
		} else {
			i.addClass("ion-ios-arrow-thin-right");
			i.removeClass("ion-ios-arrow-thin-down");
		}

		var parent2 = parent.find(".items-row").slideToggle( "fast", function() {
			 parent2.removeClass("hide");
        });
	});
	
	/*READ MORE*/
	initReadMore();
				
	$( document ).on( "click", ".view-reviews", function() {	
		if ( $(".merchant-review-wrap").html()=="" ){
		    load_reviews();			
		    initReadMore();
		}
	});	
	
	$( document ).on( "click", ".write-review-new", function() {		
		$(".review-input-wrap").slideToggle("fast");
	});
	
	
	$( document ).on( "click", ".view-merchant-map", function() {	
		 	
		 $(".direction_output").css({"display":"none"});	
		 
		 var lat=$("#merchant_map_latitude").val();
		 var lng=$("#merchant_map_longtitude").val();	
		 
		 if (empty(lat)){
		 	 uk_msg(js_lang.trans_9);
		 	 $(".direction-action").hide();
		 	 return;
		 }
		 if (empty(lng)){
		 	 uk_msg(js_lang.trans_9);
		 	 $(".direction-action").hide();
		 	 return;
		 }		 		 		 
		 
		 $(".direction-action").show();

		 	 
		 if(useMapbox()){
		 	mapbox_merchantmap( lat,lng );
		 	return;
		 }		 	 
		 		 		 
		 smap = new GMaps({
			div: '#merchant-map',
			lat: lat,
			lng: lng,
			scrollwheel: false ,
			styles: [ {stylers: [ { "saturation":-100 }, { "lightness": 0 }, { "gamma": 1 } ]}]
		 });      	  		 
		 
		 var resto_info='';	
		 if ( !empty(merchant_information)){
			 resto_info+='<div class="marker-wrap">';
		   	   resto_info+='<div class="row">';
			   	   resto_info+='<div class="col-md-4 ">';
			   	   resto_info+='<img class="logo-small" src="'+merchant_information.merchant_logo+'" >';
			   	   resto_info+='</div>';
			   	   resto_info+='<div class="col-md-8 ">';
				   	   resto_info+='<h3 class="orange-text">'+merchant_information.restaurant_name+'</h3>'; 
				   	   resto_info+='<p class="small">'+merchant_information.merchant_address+'</p>';				   	   
				   resto_info+='</div>';
		   	   resto_info+='</div>';
	   	    resto_info+='<div>';  
		 } else {
		 	resto_info='';
		 }		 
		 smap.addMarker({
			lat: lat,
			lng: lng,
			title: $("#restaurant_name").val(),
			icon : map_marker ,
			infoWindow: {
			  content: resto_info
			}
		});			
	});
	
	/*MERCHANT PHOTOS*/	
	$( document ).on( "click", ".view-merchant-photos", function() {	
		if ( $("#photos").exists() ){
		   $("#photos").justifiedGallery();
		}
	});	
	
	if( $('.section-payment-option').exists()) {	
       load_cc_list();
    }
            
    $('.payment_option').on('ifChecked', function(event){   	      	
    	var seleted_payment=$(this).val();
    	dump(seleted_payment);
    	    	
    	if ( seleted_payment=="cod"){
    		if ( $("#cod_change_required").val()==2 ){
			   $("#order_change").attr("data-validation","required");
			} else {
				$("#order_change").removeAttr("data-validation");
			}
    	} else {
    		$("#order_change").removeAttr("data-validation");
    	}
    	
    	switch (seleted_payment)
    	{
    		case "ocr":    		
    		$(".credit_card_wrap").show();
    		$(".change_wrap").hide();
    		$(".payment-provider-wrap").hide();
    		break;
    		
    		case "cod":
    		$(".credit_card_wrap").hide();
    		$(".change_wrap").show();
    		$(".payment-provider-wrap").hide();
    		break;
    		
    		case "pyr":
    		$(".payment-provider-wrap").show();
    		$(".credit_card_wrap").hide();
    		$(".change_wrap").hide();
    		break;
    		
    		default:
    		$(".credit_card_wrap").hide();
    		$(".change_wrap").hide();
    		$(".payment-provider-wrap").hide();
    		break;
    	}
    });  


    if ($("#contact-map").exists()){    	
    	dump("contact_disabled_map=>"+contact_disabled_map);
    	if(contact_disabled_map==1){
    		return;
    	}
    	dump(website_location);    	
    	
    	if(useMapbox()){
    		mapbox_plot_contact( website_location );
    	} else {
	    	map = new GMaps({
				div: '#contact-map',
				lat: website_location.map_latitude ,
				lng: website_location.map_longitude ,
				scrollwheel: false ,
				disableDefaultUI: true,
				styles: [ {stylers: [ { "saturation":-100 }, { "lightness": 0 }, { "gamma": 1} ]}]
		    });      	    
		    map.addMarker({
				lat: website_location.map_latitude,
				lng: website_location.map_longitude ,			
				icon : map_marker			
			}); 	    	    
    	}
    }
    
    if ( $("#restuarant-list").exists() ){
    	plotMap();
    }
    
    if ( $(".section-merchant-payment").exists() ){
    	load_cc_list_merchant();
    }
    
    $( document ).on( "change", "#change_package", function() {	
		var package_id=$(this).val();
		window.location.href=$("#change_package_url").val()+package_id;
	});	
	
			
	if ( $(".section-address-book").exists() ){
		if ( $("#table_list_info").exists() ){
		} else {
			table();
		}
	}
				
	$( document ).on( "click", ".row_remove", function() {
		var ans=confirm(js_lang.deleteWarning);        
        if (ans){        	
        	var table = $(this).data("table");
		    var whereid = $(this).data("whereid");
		    var id = $(this).data("id");
		    rowRemove(id, table, whereid, $(this) );		
        }		
	});
		
	if ( $(".otable").exists() ){
		initOtable();
	}
	
	if( $('#uploadavatar').exists() ) {    	
       createUploader('uploadavatar','uploadAvatar');
    }    
      

    if ( $(".typhead_restaurant_name").exists() ){
    	loadTypeHead(1,'.typhead_restaurant_name','RestaurantName');
    	loadTypeHead(2,'.typhead_street_name','AutoStreetName');
    	loadTypeHead(3,'.typhead_cuisine','AutoCuisine');
    	loadTypeHead(4,'.typhead_foodname','AutoFoodName');
    }
    
    if ( $(".search-by-postcode").exists() ){    
    	iniRestoSearch('zipcode','AutoZipcode'); 
    }
    
    $( document ).on( "click", ".full-maps", function() {    	
    	dump(country_coordinates);  
    	if(useMapbox()){
    		mapbox_fullmap( country_coordinates.lat , country_coordinates.lng , 'full-map');
    	} else {
	    	map = new GMaps({
				div: '#full-map',
				lat: country_coordinates.lat ,
				lng: country_coordinates.lng ,
				scrollwheel: false ,
				styles: [ {stylers: [ { "saturation":-100 }, { "lightness": 0 }, { "gamma": 1 } ]}],
				zoom: 6,
			    markerClusterer: function(map) {
	                return new MarkerClusterer(map);
	            }
		    });      	       	
	    	callAjax("loadAllMerchantMap");
    	}
    });    
    
    $( document ).on( "click", ".view-full-map", function() {        		   
        $(".full-map-wrapper").toggleClass("full-map");
        if ( $(".full-map-wrapper").hasClass("full-map") ) {
        	$(this).html(js_lang.close_fullscreen);
        	$(".view-full-map").removeClass("green-button");
        	$(".view-full-map").addClass("orange-button");
        } else {
        	$(this).html(js_lang.view_fullscreen);     	
        	$(".view-full-map").addClass("green-button");
        	$(".view-full-map").removeClass("orange-button");
        }
    });    
    
    $( document ).on( "click", ".menu-nav-mobile a", function() { 
       $(".menu-top-menu").slideToggle("fast");
    });	
    
  
    $( document ).on( "click", "#mobile-filter-handle", function() { 
    	 
         toogleModalFilter("#mobile-search-filter");
         
         $('.filter_by').on('ifChecked', function(event){
            research_merchant();       
         });
         $('.filter_by').on('ifUnchecked', function(event){
            research_merchant();   
         }); 
         $('.filter_by_radio').on('ifChecked', function(event){  
	       $(".filter_minimum_clear").show();   
	       research_merchant();   
	     });
	     $('.filter_promo').on('ifChecked', function(event){      
		      $(".non-free").fadeOut("fast");
		 });
	     $('.filter_promo').on('ifUnchecked', function(event){       
	        $(".non-free").fadeIn("fast");
	     });    
    });
    
    /*$( document ).on( "click", ".cart-mobile-handle", function() { 
         toogleModalFilter("#menu-right-content");
    });*/
    
    /*MOBILE SINGLE PAGE FOR FOOD ITEM*/
    if ( $("#mobile-view-food-item").exists()){
    	var hide_foodprice=$("#hide_foodprice").val();	
		if ( hide_foodprice=="yes"){
			$(".hide-food-price").hide();
			$("span.price").hide();		
			$(".view-item-wrap").find(':input').each(function() {			
				$(this).hide();
			});
		}
		var price_cls=$(".price_cls:checked").length; 	
		if ( price_cls<=0){
			var x=0
			$( ".price_cls" ).each(function( index ) {
				if ( x==0){
					dump('set check');
					$(this).attr("checked",true);
				}
				x++;
			});
		}
    }
    
    $( document ).on( "change", ".language-options", function() {
    	if ( $(this).val() != ""){    		
    		window.location.href=$(this).val();
    	}
    });
    
   $( document ).on( "click", ".view-receipt-front", function() {    	       	 	
   	   var params="order_id="+ $(this).data("id")+"&post_type=get";
   	   params+="&lang="+lang;
       fancyBoxFront('viewReceipt',params);
   });	
   
   /*COOKIE LAW*/
   if ( $(".cookie-wrap").exists() ){   	   
   	   $.cookie.raw = true;
   	   var kr_cookie_law = $.cookie('kr_cookie_law');	
   	   dump("kr_cookie_law:"+kr_cookie_law);
   	   if (empty(kr_cookie_law)){
   	   	  $(".cookie-wrap").fadeIn("fast");
   	   }
   }
   $( document ).on( "click", ".accept-cookie", function() { 
   	   $(".cookie-wrap").fadeOut("slow");
   	   $.cookie('kr_cookie_law', '2', { expires: 500, path: '/' }); 
   });
   
   $( document ).on( "click", ".cookie-close", function() { 
   	   $(".cookie-wrap").fadeOut("slow");
   });
   
   $( document ).on( "click", ".resend-email-code", function() { 
        callAjax('resendEmailCode','client_id='+$("#client_id").val());
   });
   
                   
}); /*end docu*/

function fancyBoxFront(action,params)
{  	  	  	  	
	var is_modal;
	switch (action)  	
	{
		case "ShowLocationFee":
		case "AgeRestriction":
		is_modal=true;
		break;
				
		default:
		is_modal=false;
		break;
	}
	
	params+="&lang="+lang;
	params+= addValidationRequest()+"&post_type=get";
	
	var URL=front_ajax+"/"+action+"/?"+params;
		$.fancybox({        
		maxWidth:800,
		closeBtn : false,          
		autoSize : true,
		padding :0,
		margin :2,
		modal:is_modal,
		type : 'ajax',
		href : URL,
		openEffect :'elastic',
		closeEffect :'elastic',
		helpers: {
		    overlay: {
		      locked: false
		    }
		 }
	});   
}

$('#mobile-search-filter').on('hidden.bs.modal', function (e) {
   $("#mobile-search-filter").removeClass("fade");
   $("#mobile-search-filter").removeClass("modal");
   $(".modal-close-btn").hide();
});


$('#menu-right-content').on('hidden.bs.modal', function (e) {
   $("#menu-right-content").removeClass("fade");
   $("#menu-right-content").removeClass("modal");
   $(".modal-close-btn").hide();
});

function toogleModalFilter(id)
{

   if ( id=="#menu-right-content"){
   	   $(id).css("overflow",'');
   	   $(id).css("position",'');
   }
	
   if ( $(id).hasClass("modal") ){
   	   $(id).removeClass("fade");
       $(id).removeClass("modal");
   	   $(id).modal('hide');
   	   $(".modal-close-btn").hide();
   } else {  	   
       $('.icheck').iCheck({
	       checkboxClass: 'icheckbox_minimal',
	       radioClass: 'iradio_flat'
	   });
   	   $(id).addClass("fade");
   	   $(id).addClass("modal");
   	   $(id).modal('show');
   	   $(".modal-close-btn").show();
   	   
   	   if ( id=="#menu-right-content"){
   	   	  load_item_cart();
   	   }
   	   
   }
}

$.validate({ 	
	language : jsLanguageValidator,
    form : '#frm-addressbook',    
    onError : function() {      
    },
    onSuccess : function() {     
      form_submit('frm-addressbook');
      return false;
    }  
});

$.validate({ 	
	language : jsLanguageValidator,
    form : '.krms-forms',    
    onError : function() {      
    },
    onSuccess : function() {     
      var params=$(".krms-forms").serialize();
      var action=$(".krms-forms").find("#action").val();
      callAjax(action,params, $(".krms-forms-btn") );
      return false;
    }  
});

function plotMap()
{
	dump('plotMap');	
	if(useMapbox()){
		mapbox_plot_browse_map();
	} else {
		$( ".browse-list-map.active" ).each(function( index ) {
								
			var lat=$(this).data("lat");
			var lng=$(this).data("long");
			
			map = new GMaps({
				div: this,
				lat: lat ,
				lng: lng ,
				scrollwheel: false ,			
				styles: [ {stylers: [ { "saturation":-100 }, { "lightness": 0 }, { "gamma": 1} ]}]
		    });      
	
		    map.addMarker({
				lat: lat,
				lng: lng ,			
				icon : map_marker			
			}); 	    
	
			$(this).removeClass("active"); 				     		
		});
	}
}

function initReadMore()
{
	if ( $(".read-more").exists() ){				
	    $('.read-more').readmore({
	    	moreLink:'<a class="small" href="javascript:;">'+js_lang.read_more+'</a>',
	    	lessLink:'<a class="small" href="javascript:;">'+js_lang.close+'</a>'
	    });
	}
}

function initRating()
{
	$('.rating-stars').raty({ 
		readOnly: true, 
		score: function() {
             return $(this).attr('data-score');
       },
		path: sites_url+'/assets/vendor/raty/images',
		hints:''
    });    
}

function removeFreeDelivery()
{
	var filter_promo=$(".filter_promo:checked").val();	
	if ( filter_promo=="free-delivery"){		
		$(".non-free").fadeOut("fast");
	}
}

/*mycall*/
var call_ajax_handle;

function callAjax(action,params,buttons)
{
	dump(action);
	dump(params);	
	var buttons_text='';	
	
	if (!empty(buttons)){
		buttons_text=buttons.html();
		buttons.html('<i class="fa fa-refresh fa-spin"></i>');
		buttons.css({ 'pointer-events' : 'none' });
	}
	
	if(!empty(lang)){
		params+="&lang="+lang;
	}
	
	params+= addValidationRequest();
	
    call_ajax_handle = $.ajax({    
    type: "POST",
    url: front_ajax+"/"+action,
    data: params,
    timeout: 20000,
    dataType: 'json',       
    beforeSend: function() {	 	
	 	if(call_ajax_handle != null) {
	 	   call_ajax_handle.abort();	 	   
	 	   busy(false);
	       showPreloader(false);
	 	} else {
	 	   busy(true);
	       showPreloader(true);
	 	}
	},
	complete: function(data) {					
		call_ajax_handle= (function () { return; })();		
		busy(false);
	    showPreloader(false);
	},
    success: function(data){     	
    	if (!empty(buttons)){
    		buttons.html(buttons_text);
		    buttons.css({ 'pointer-events' : 'auto' });
    	}
    	
    	if (data.code==1){
    		switch (action)
    		{
    			case "loadAllMerchantMap":
    			case "loadAllRestoMap": 
    			
    			   if(useMapbox()){
    			   	  mapbox_allmerchant(data.details);
    			   	  return;
    			   }
    			
    			   var last_lat='';
    			   var last_lng='';
    			   var bounds = [];
    			   
    			   $.each(data.details, function( index, val ) {
    			   	   
    			   	   resto_info='';
    			   	       			   	   
    			   	   resto_info+='<div class="marker-wrap">';
	    			   	   resto_info+='<div class="row">';
		    			   	   resto_info+='<div class="col-md-4 ">';
		    			   	   resto_info+='<img class="logo-small" src="'+val.logo+'" >';
		    			   	   resto_info+='</div>';
		    			   	   resto_info+='<div class="col-md-8 ">';
			    			   	   resto_info+='<h3 class="orange-text">'+val.restaurant_name+'</h3>'; 
			    			   	   resto_info+='<p class="small">'+val.merchant_address+'</p>'; 
			    			   	   resto_info+='<a class="orange-button" href="'+val.link+'">'+js_lang.trans_37+'</a>';
			    			   resto_info+='</div>';
	    			   	   resto_info+='</div>';
    			   	   resto_info+='<div>';    		
    			   	   
    			   	   last_lat=val.latitude;
    			   	   last_lng=val.lontitude;
    			   	  
    			   	   var latlng = new google.maps.LatLng( last_lat , last_lng );
    			   	   bounds.push(latlng);
    			   	    
    			   	    map.addMarker({
							lat: val.latitude,
							lng: val.lontitude,
							title: val.restaurant_name,
							icon : map_marker ,
							infoWindow: {
							  content: resto_info
							}
						});		     
						
    			   });    			   
    			
    			   if ( $("#full-map").exists() ){
    			   	   //map.setCenter(last_lat,last_lng);
    			   	   map.fitLatLngBounds(bounds);
    			   }
    			   
    			   if ( $(".search-map-results").exists() ){
    			   	   dump('fitLatLngBounds');
    			   	   map.fitLatLngBounds(bounds);
    			   }
    			   
    			break;
    			
    			case "SetLocationSearch":
    			   window.location.href=data.details;
    			break;
    			
    			case "CheckLocationData":
    			   if( !empty($("#merchant_id").val())){
    			     fancyBoxFront("ShowLocationFee", "merchant_id="+$("#merchant_id").val() );
    			   } else {
    			   	  window.location.href= sites_url;
    			   }
    			break;
    			
    			case "SetLocationFee":
    			  close_fb();
    			  window.location.reload();
    			break;

    			case "LoadState":
    			 $("#state_id").html(data.details);
    			break;
    			
    			case "LoadCityList":
    			 $("#city_id").html(data.details);
    			break;
    			
    			case "LoadArea":
    			 $("#area_id").html(data.details);
    			break;
    					    
    			case "LoadPostCodeByArea":
    			  $("#zipcode").val(data.details);
    			break;
    			
    			case "paymill_transaction":    			  
    			  window.location.href=data.details.redirect;
    			break;
    			
    			case "delAddressBookLocation":
    			  table_reload();
    			break;
    			
    			case "loadTimeList":
    			 $(".time_list").html(data.details);    			 
    			 loadSkedMenu( $("#delivery_date").val() );
    			break;
    			
    			case "loadMenu":
    			  $("#menu_left_content").html( data.details );
    			  
    			  load_item_cart();
    			  
				  var disabled_cart_sticky=$("#disabled_cart_sticky").val();
				  if ( $(".menu-right-content").exists() ){	   
				   	   dump("disabled_cart_sticky=>"+disabled_cart_sticky);
				   	   if (disabled_cart_sticky!=2){
						   jQuery('.menu-right-content, .category-list').theiaStickySidebar({      
						      additionalMarginTop: 8
						   }); 
				   	   }
				  }
    			  
    			break;
    			
    			case "mapbox_geocode":
    			  mapbox_direction( data.details );
    			break;
    			
    			case "geocode":    			  
    			  $("#s").val(data.details);
    			break;
    			
    			case "cancelOrder":    			
    			  uk_msg_sucess(data.msg);    			  
    			  $("."+ data.details.id).after( data.details.new_div );
    			  $("."+ data.details.id).remove();
    			break;
    			
    			case "loadAddressByLocation":    			  
    			  $("#street").val( data.details.street );
    			  $("#location_name").val( data.details.location_name );
    			  $("#state_id").val( data.details.state_id );
    			  $("#city_id").val( data.details.city_id );
    			  $("#area_id").val( data.details.area_id );
    			break;
    			
    			case "addToFavorite":
    			  if (data.details.added==1){
    			  	 $(".fav_"+ data.details.id).addClass("selected");
    			  } else {
    			  	 $(".fav_"+ data.details.id).removeClass("selected");
    			  }
    			break;
    			
    			case "removeFavorite":
    			  $(".tr_fav_"+ data.details).fadeOut( "slow", function() {				    
				  });
    			break;
    			
    			case "delete_addressbook":
    			  table_reload();
    			break;
    			
    			case "deleteClientCC":
    			 OtableReload();
    			break;
    			
    			case "requestCancelBooking":
    			  $(".booking_id_" +  data.details.booking_id ).after("<p class=\"text-muted\">"+ data.msg+"</p>");
    			  $(".booking_id_" +  data.details.booking_id ).remove();
    			break;
    			    			    						    	
    			default:
    			   uk_msg_sucess(data.msg);
    			   if (!empty(data.details)){
    			   	   if (!empty(data.details.redirect)){
    			   	   	   setTimeout( function(){ 
    			   	   	      window.location.href=data.details.redirect;
    			   	   	   }, 2501);		
    			   	   	   return;
    			   	   }
    			   }    			   
    			break;
    		}
    	} else { // my failed
    		
    		switch (action){
    			
    			case "geocode":
    			  $("#s").val('');
    			break;
    			
    			case "CheckLocationData":    			
    			//silent
    			break;
    			
    			case "LoadPostCodeByArea":
    			  $("#zipcode").val('');
    			break;
    			
    			case "paymill_transaction":
    			  uk_msg(data.msg);
    			  $('#paymill_submit').val( $("#label_paynow").val() );
                  $("#paymill_submit").removeAttr("disabled");
    			break;
    			
    			case "loadTimeList":
    			 $(".time_list").html('');
    			 loadSkedMenu( $("#delivery_date").val() );
    			break;
    			
    			case "loadMenu":
    			uk_msg(data.msg);
    			break;
    			
    			default: 
    			uk_msg(data.msg);
    			break;
    			   			
    		}    		    		
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    	showPreloader(false);
    	if (!empty(buttons)){
    		buttons.html(buttons_text);
		    buttons.css({ 'pointer-events' : 'auto' });
    	}
    }		
    });   	     	  
}

function initOtable()
{
	dump('otablesx');	
	otables = $('.otable').dataTable({
	"bProcessing": true, 
	"bServerSide": false,	    
	"bFilter":false,
	"bLengthChange":false,	
	"sAjaxSource": front_ajax+"/" + $("#otable_action").val() + "?" + addValidationRequest() + "&post_type=get" ,
	"oLanguage":{
		 "sInfo": js_lang.trans_13 ,
		 //"sEmptyTable": sEmptyTable,
		 "sInfoEmpty":  js_lang.tablet_3,
		 "sProcessing": "<p>"+js_lang.tablet_7+" <i class=\"fa fa-spinner fa-spin\"></i></p>",
		 "oPaginate": {
	        "sFirst":    js_lang.tablet_10,
	        "sLast":     js_lang.tablet_11,
	        "sNext":     js_lang.tablet_12,
	        "sPrevious": js_lang.tablet_13
	  }
	},
	"fnInitComplete": function (oSettings, json){ 	  
	}
	});		
}

function OtableReload()
{
	otables.fnReloadAjax(); 
}

 function rowRemove(id,tbl,whereid,object)
{			
	busy(true);
	var params="action=rowDelete&tbl="+tbl+"&row_id="+id+"&whereid="+whereid;	
	params+= addValidationRequest();
	
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){
        	busy(false);
        	if (data.code==1){               		
        		tr=object.closest("tr");
                tr.fadeOut("slow");
        	} else {      
        		uk_msg(data.msg);
        	}        	        	
        }, 
        error: function(){	        	        	
        	busy(false);
        	uk_msg(js_lang.trans_14);
        }		
    });
}

function uploadAvatar(data)
{
	dump(data);
	if ( data.code==1){
		$(".avatar-wrap").html( '<img src="'+upload_url+"/"+data.details.file +'" class="img-circle" />' );
		callAjax("saveAvatar",'filename='+data.details.file );
	} else {
		uk_msg(data.msg);
	}
}

function iniRestoSearch(fields,actions)
{
	var parent=$("."+fields).parent().parent();	
	var button=parent.find("i");
		
	var options = {
	  url: function(phrase) {
	    return home_url+"/"+actions;
	  },		
	  getValue: function(element) {
	    return element.name;
	  },		
	  ajaxSettings: {
	    dataType: "json",
	    method: "POST",
	    data: {
	      dataType: "json"
	    },
	    beforeSend: function(xhr, opts){	    	
	    	busy(true);
	    },
	    complete: function(data) {
	    	busy(false);
	    	dump(data);
	    },
	  },		
	  preparePostData: function(data) {
	    data.search = $("."+fields).val();
	    
	    if ( $("#merchant_id").exists() ){
	    	data.merchant_id = $("#merchant_id").val();
	    }
	    
	    data.yii_session_token=yii_session_token;
	    data.YII_CSRF_TOKEN=YII_CSRF_TOKEN;
	    
	    return data;
	  },
      requestDelay: 500
   };      
   $("."+fields).easyAutocomplete(options);
}


/*JQUERY BROWSER*/
var matched, browser;

jQuery.uaMatch = function( ua ) {
    ua = ua.toLowerCase();

    var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
        /(webkit)[ \/]([\w.]+)/.exec( ua ) ||
        /(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
        /(msie) ([\w.]+)/.exec( ua ) ||
        ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
        [];

    return {
        browser: match[ 1 ] || "",
        version: match[ 2 ] || "0"
    };
};

matched = jQuery.uaMatch( navigator.userAgent );
browser = {};

if ( matched.browser ) {
    browser[ matched.browser ] = true;
    browser.version = matched.version;
}

// Chrome is Webkit, but Webkit is also Safari.
if ( browser.chrome ) {
    browser.webkit = true;
} else if ( browser.webkit ) {
    browser.safari = true;
}

jQuery.browser = browser;
/*JQUERY BROWSER*/



jQuery(document).ready(function() {		
	
	$( document ).on( "click", ".language-selection a", function() {
		$(".language-selection-wrap").slideDown("fast");
	});
	$( document ).on( "click", ".language-selection-close", function() {
		$(".language-selection-wrap").fadeOut("slow");
	});
	$( document ).on( "click", ".lang-selector", function() {
		$(".lang-selector").removeClass("highlight");
		$(this).addClass("highlight");
		$(".change-language").attr("href", home_url+"/setlanguage/Id/"+ $(this).data("id")  );
	});
	
	$( document ).on( "click", ".goto-reviews-tab", function() {
	   $(".view-reviews").click();
	   scroll_class('view-reviews');
	});
	
	if ( $("#theme_time_pick").val() == "2"){	
		var is_twelve_period=false;
		if ( $("#website_time_picker_format").exists() ){		
			if ( $("#website_time_picker_format").val()=="12"){
				is_twelve_period=true;
			}
		}
		var time_format='hh:mm p';
		if (!is_twelve_period){
			dump('24 hour');
			time_format='H:mm';
		} 
		$('.timepick').timepicker({
			timeFormat: time_format,
			//interval:15
		});
		
		if ( $(".booking_time_desktop").exists() ){
		    $('#booking_time').timepicker({
		    	timeFormat: time_format
	            //timeFormat: 'H:mm'
	        });	
		}
	}
	
	$( document ).on( "click", ".back-map-address", function() {
		$(this).hide();
				
		$("#street").attr("data-validation","required");
  	    $("#city").attr("data-validation","required");
  	    $("#state").attr("data-validation","required");
   	          	  
		$(".address_book_wrap").show();		
		$("#map_address_toogle").val(1);
		$(".map-address-wrap-inner").hide();	
		$(".map-address").show();		
	});
	
	
	/*if ( $("#s").exists() ){
		$('#s').keypress(function (e) {
			if (e.which == 13) {				
				$("#forms-search").submit();
			}
		});
	}*/	
	
}); /*ready*/


jQuery(document).ready(function() {		
	
	if($("#menu-right-content").exists()){
		if($("#menu-right-content").hasClass("hide")){
			$(".cart-mobile-handle").hide();
		}
	}
		
	if( $('.cart-mobile-handle').is(':visible') ) {			
		showMobileCartNos();		
	}
	
}); /*ready*/

function showMobileCartNos()
{
	busy(true);
	var params="action=getCartCount&tbl=cart";	
	params+= addValidationRequest();
	
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){
        	busy(false);
        	if (data.code==1){    
        		$(".cart_count").html(data.details);       
        		$(".cart_count").show();
        	} else {      	        		
        		$(".cart_count").html(0);       
        		$(".cart_count").hide();
        	}        	        	
        }, 
        error: function(){	        	        	
        	busy(false);	        	
        }		
    });
}

/*VERSION 4.0*/
jQuery(document).ready(function() {		
		
	 $( "#s" ).on( "keydown", function(event) {
	 	if(event.which == 13){
	 	   $("#forms-search").submit();
	 	}
	 });
	 
	 $( document ).on( "click", ".apply_tip", function() {
	 	 var tip_type = $(".tips.active").data("type");	 
	 	 dump(tip_type);	 	 
	 	 if ( tip_type=="tip"){
	 	 	 var tips = $(".tips.active").data("tip");	 	 
	 	 	 $("#cart_tip_percentage").val( tips );
	 	 } else {
	 	 	 $("#cart_tip_percentage").val( $("#cart_tip_cash_percentage").val() );
	 	 }	 	 
	 	 load_item_cart();
	 });
	 
	 if ( $(".search_foodname").exists() ){    	    	
    	loadTypeHead(1,'.search_foodname','autoFoodItem');
	 }	
	 
	 if ( $("#location_search_type").exists()){	 	 
	 	 switch ($("#location_search_type").val()){
	 	 	case "2":	
	 	 	case 2:
	 	 	  loadTypeHead(1,'.typhead_state','StateList');
	 	 	  loadTypeHead(2,'.typhead_city','CityList');
	 	 	break
	 	 	
	 	 	case "3":
	 	 	case 3:
	 	 	  loadTypeHead(4,'.typhead_postalcode','PostalCodeList');
	 	 	break
	 	 		 	 	
	 	 	default:	 	 	
	 	 	  loadTypeHead(2,'.typhead_city','CityList');
	 	 	  loadTypeHead(3,'.typhead_area','AreaList');
	 	 	break
	 	 }
	 }
	 
	 $.validate({ 	
		language : jsLanguageValidator,
	    form : '#frm-location-search',    
	    onError : function() {      
	    },
	    onSuccess : function() {     
	      var params=$(".frm-location-search").serialize();
	      callAjax( $("#location_action").val() , params , $(".location-search-submit") )
	      return false;
	    }  
	});
	
	if ( $("#search_by_location").exists() ){
		if ($("#search_by_location").val()==1){
			callAjax("CheckLocationData", "delivery_type="+$("#delivery_type").val() );
		}
	}

	$( document ).on( "click", ".change-location", function() {
		fancyBoxFront("ShowLocationFee", "merchant_id="+$("#merchant_id").val() );
	});

	$("#location_country_id").change(function() {
		if (!empty($(this).val())){
			clearFields(['#state_id','#city_id','#area_id']);	
			
			$("#state_id").html('');
		    $("#city_id").html('');
		    $("#area_id").html('');
		  		
		    callAjax("LoadState","country_id="+$(this).val());
		} 
	});
	
	$("#state_id").change(function() {		
		if (!empty($(this).val())){
		   $("#state").val( $("#state_id option:selected").text()  );
		   callAjax("LoadCityList","state_id="+$(this).val());
		} else {
		  $("#state").val('');
		  $("#city_id").html('');
		  $("#area_id").html('');
		}
	});
	$("#city_id").change(function() {		
		if (!empty($(this).val())){
		   $("#city").val( $("#city_id option:selected").text()  );
		   callAjax("LoadArea","city_id="+$(this).val());
		} else {
		   $("#area_id").html('');
		   $("#city").val('');
		}
	});
	$("#area_id").change(function() {		
		if (!empty($(this).val())){
			$("#area_name").val( $("#area_id option:selected").text()  );
		   callAjax("LoadPostCodeByArea","area_id="+$(this).val());
		} else {
			$("#area_name").val('');
			$("#zipcode").val('');
		}
	});
	
	/*AGE RESTRICION*/		
	if ( age_restriction==1 ){
		$.cookie.raw = true;
		var kr_cookie_age =$.cookie('kr_cookie_age');	
		dump("kr_cookie_age=>"+kr_cookie_age);
		if (empty(kr_cookie_age)){
		    fancyBoxFront("AgeRestriction","");
		}
	}
		
	$( document ).on( "click", ".age-exit", function() {
		close_fb();
		window.location.href=restriction_exit_link;		
	});
	
	$( document ).on( "click", ".age-accept", function() {
	    close_fb();
	    $.cookie('kr_cookie_age', '1', { expires: 500, path: '/' }); 
	});
	
	$( document ).on( "change", ".merchant_type_selection", function() {
		if ( $(this).val()==3){
			$(".invoice_terms_wrap").show();
		} else {
			$(".invoice_terms_wrap").hide();
		}
	});
	
}); /*end doc*/

function showPreloader(busy)
{
	if(busy){
	   $(".main-preloader").show(); 
	} else {
	   $(".main-preloader").hide(); 
	}
}

var my_typehead = [];

function loadTypeHead(id, target, action)
{			
	city_id = 0; state_id = 0; auto_merchant_id = 0;
	
	my_typehead[id] = $.typeahead({
		    input: target,
		    minLength: 0,
		    maxItem: 10,
		    order: "asc",
		    dynamic: true,
		    delay: 500,
		    hint: true,
			accent: true,
			searchOnFocus: true,	    
			cancelButton: false,
		    template: function (query, item) { 	 
		    	return '<span>{{name}}</span>';
		    },
		    emptyTemplate: ("no result for")+ " {{query}}",
		    source: {
		        user: {
		            display: "name",                        
		            ajax: function (query) {
		            	
		            	dump("before send "+ action);
		            	switch(action){
		            		case "StateList":		            		 
		            	     clearFields(['#state_id','#state_name','#city_id','#city_name','.typhead_city']);
		            		break;
		            		
		            		case "CityList":		            		
		            		clearFields( ['#area_id','.typhead_area'] );
		            		state_id = $("#state_id").val();
		            		break;
		            		
						    case "AreaList":						
						    city_id = $("#city_id").val();						
						    break;
						    
						    case "PostalCodeList":
						    clearFields( ['#postal_code'] );
						    break;
						    
						    case "autoFoodItem":
						      if (typeof merchant_information === "undefined" || merchant_information==null ) {						          
						      } else {
						      	 auto_merchant_id = merchant_information.merchant_id;
						      }
						    break;
						    
					    }			         
						            	   	
		                return {
		                    type: "POST",
		                    url: front_ajax+"/"+action,
		                    path: "data.item",
		                    data: {
		                        q: "{{query}}",
		                        YII_CSRF_TOKEN : YII_CSRF_TOKEN,
		                        'yii_session_token': yii_session_token,
		                        'city_id' : city_id,
		                        'state_id' : state_id,
		                        'auto_merchant_id' : auto_merchant_id
		                    },
		                    callback: {
		                        done: function (data) {	        	                        	
		                            return data;
		                        }
		                    }
		                }
		            }
		 
		        },       
		    },
		    callback: {
		        onClick: function (node, a, item, event) {             			        				        				        	
		            dump(item);
		            dump("onClick=>"+action);
		            switch(action){
		            	case "StateList":
		            	  $("#state_id").val( item.state_id);
		            	  $("#state_name").val( item.name);		            	  
		            	  $('.typhead_city').trigger('input.typeahead');
		            	  break;
		            	  
		            	case "CityList":		            	  
		            	  $("#city_id").val(item.id);
		            	  $("#city_name").val(item.name);
		            	  $('.typhead_area').trigger('input.typeahead');
		            	  break;
		            	
		            	case "AreaList":
		            	  $("#area_id").val(item.area_id);  
		            	  break;
		            	  
		            	case "PostalCodeList":  
		            	  $("#postal_code").val(item.name);  
		            	break;
		            }
		        },
		        onSendRequest: function (node, query) {
		            console.log('request is sent');
		        },
		        onReceiveRequest: function (node, query) {
		            console.log('request is received');
		        }
		    },
		    debug: true
		 });	
		
}

function locationLoadArea( type_head, text_field , text_field2 , variable_1 , action )
{		 	 
	type_head.typeahead({
	 	autoSelect: true,	  	 
	 	delay:100,   
	 	minLength:2,
        source: function (query, process) {
            return ajaxArea(query,process,text_field2,variable_1 , action);
        }
    });
    type_head.change(function() {
	  var current = type_head.typeahead("getActive");  
	  if(current){	     
	     text_field.val( current.id );  
	     switch (action)
	     {
	     	case "CityList":
	     	$("#city_name").val( current.name );
	     	break;
	     }
	  }
	});
}

var ajax_area;

function ajaxArea(query,process,text_field2,variable_1 , action)
{	
	
	dump("ajaxArea");
	
	if(query==""){
		dump("empty query");
		return false;
	}
	var ajax_area = $.ajax({    
    type: "GET",
    //url: front_ajax+"/AreaList",
    url: front_ajax+"/"+action,
    data: "q="+query + "&"+variable_1+"=" + text_field2.val() + addValidationRequest() + "&post_type=get" ,
    timeout: 6000,		
    dataType: 'json',       
    beforeSend: function() {
    	if(ajax_area != null) {
    	   ajax_area.abort();
    	   locationLoader(false);
    	} else {
    	   locationLoader(true);
    	}    	
    },
    complete: function(data) {
    	locationLoader(false);
    },
    success: function(data){ 	      
	      return process(data);
	  }, 
    error: function(){	        	    	
    	locationLoader(false);
    }		
    });   	   
}

function locationLoader(busy)
{
	if(busy){
		$(".typhead_area").attr("disabled",true);
        $(".location-loader").show();
	} else {
		$(".typhead_area").attr("disabled",false);
        $(".location-loader").hide();
	}	
}

function locationLoadState()
{	
	$.get( front_ajax + "/StateList" + "/?"+ addValidationRequest() + "&post_type=get" , function(data){		
	   $(".typhead_state").typeahead({ 
	    	source:data,
	    	autoSelect: true,	  
	        showHintOnFocus:true
	   });
	},'json');
	
	$(".typhead_state").change(function() {
	  var current = $(".typhead_state").typeahead("getActive");  
	  if(current){	     
	     $("#state_id").val( current.id );  
	     $("#state_name").val( current.name );  
	     
	     $("#location_city").val('');
	     $("#city_id").val('');
	     $("#city_name").val('');
	  }
	});
	
	$( document ).on( "click", ".typhead_state", function() {	 
	 	 $(this).val("");
	});
	$( document ).on( "focusout", ".typhead_state", function() {	 
		 var state_name=$("#state_name").val();
	 	 $(this).val( state_name );
	}); 
}

function locationLoadPostalCode()
{	
	$.get( front_ajax + "/PostalCodeList" + "/?"+ addValidationRequest() + "&post_type=get", function(data){		
	   $(".typhead_postalcode").typeahead({ 
	    	source:data,
	    	autoSelect: true,	  
	        showHintOnFocus:true
	   });
	},'json');
	
	$(".typhead_postalcode").change(function() {
	  var current = $(".typhead_postalcode").typeahead("getActive");  
	  if(current){	     	     
	     $("#postal_code").val( current.name );  	     	    
	  }
	});
	
	$( document ).on( "click", ".typhead_postalcode", function() {	 
	 	 $(this).val("");
	});
	$( document ).on( "focusout", ".typhead_postalcode", function() {	 
		 var postal_code=$("#postal_code").val();
	 	 $(this).val( postal_code );
	}); 
}

/*VERSION 4.2*/

$.validate({ 	
	language : jsLanguageValidator,
    form : '#forms-review',    
    onError : function() {      
    },
    onSuccess : function() {           
      form_submit('forms-review');
      return true;
    }  
});

jQuery(document).ready(function() {
	
	$( document ).on( "click", ".collapse-parent a", function() {
		data=$(this).data('id');
		dump(data);
		$(this).toggleClass("active");
		if ( $(this).hasClass("active") ){
			$(this).html('<i class="ion-minus-circled"></i>');
		} else {
			$(this).html('<i class="ion-plus-circled"></i>');
		}
		$(".collapse-child-"+data).slideToggle();		
	});
	
}); /*end docu*/

/*END VERSION 4.2*/


/*START PAYMILL */
jQuery(document).ready(function() {
	
	$.validate({ 	
		language : jsLanguageValidator,
	    form : '#forms-paymill',    
	    onError : function() {     	    	 
	    },
	    onSuccess : function() {     
	       
	       $(".payment-errors").text('');
	       $('#paymill_submit').attr("disabled", "disabled");	       
	       $('#paymill_submit').val( $("#label_loading").val() );
	       //busy(true);

	       paymill.createToken({
		      number: $('#x_card_num').val(),  
		      exp_month: $('#expiration_month').val(), 
		      exp_year: $('#expiration_yr').val(),    
		      cvc: $('#cvv').val(),                  
		      amount_int: $('#x_amount').val(), 
		      currency: $('#x_currency_code').val(),    
		      cardholder: $('#x_card_holder_name').val() 
		    },  function responseHandler(error, result){		    	
		       	 //busy(false);
		       	 if (error){		       	 	
		       	 	$(".payment-errors").text(error.apierror);
	                $("#paymill_submit").removeAttr("disabled");	
	                
	                $('#paymill_submit').val( $("#label_paynow").val() );
	                                
		       	 } else {		       	 	
		       	 	
		       	 	$("#paymill_token").val(result.token);
		       	 	
		       	 	var form = $("#forms-paymill");
				    var params=$("#forms-paymill").serialize();
			        var action=$("#forms-paymill").find("#action").val();
			                  
			        callAjax(action,params, $("#paymill_submit") );
		       	 }
		    });		    
		    return false;	
	    }  
	});

}); /*end docu*/
/* END PAYMILL */

/*STRIPE IDEAL*/
$.validate({ 	
	language : jsLanguageValidator,
    form : '#forms_stripe_ideal',    
    onError : function() {     	    	 
    },
    onSuccess : function() {     
        
    	showPreloader(true);
    	
    	stripe.createSource({
		  type: 'ideal',
		  amount: stripe_amount,
		  currency: stripe_currency ,
		  statement_descriptor: stripe_descriptor ,
		  owner: {
		    name: stripe_client_name ,
		  },
		  redirect: {
		    return_url: stripe_return_url,
		  },
		}).then(function(result) {			  
			showPreloader(false);
		    dump(result);
		    if(!empty(result.error)){			    	
		    	$(".payment-errors").html( result.error.message );
		    } else {			    	
		    	$("#stripe_ideal_submit").css({ 'pointer-events' : 'none' });
		    	dump(result.source.redirect.url);
		    	window.location.href = result.source.redirect.url;
		    }
		});	    	
	    return false;	
    }  
});
	
/*IPAY AFRICA*/
$.validate({ 	
	language : jsLanguageValidator,
    form : '#forms_africa',    
    onError : function() {     	    	 
    },
    onSuccess : function() {     
        
    	showPreloader(true);
    	$("#africa_submit").css({ 'pointer-events' : 'none' });    	    		    	
	    return true;	
    }  
});


/*IPAY AFRICA*/
$.validate({ 	
	language : jsLanguageValidator,
    form : '#frm_redirect_payment',    
    onError : function() {     	    	 
    },
    onSuccess : function() {     
        
    	showPreloader(true);
    	$("#frm_redirect_payment input[submit]").css({ 'pointer-events' : 'none' });    	    		    	
	    return true;	
    }  
});


/*DIXI PAY*/
jQuery(document).ready(function() {
	
	if( $(".format_card_number").exists() ){
		onload = function() {
		  document.getElementById('x_card_num').oninput = function() {
		    this.value = CreditCardFormat(this.value)
		  }
		}
	}
	
	if ( $(".dixi_card_selection").exists() ){
		$(".card_information_wrap").hide();
		$("#tokenize_id").attr("data-validation","required");
		$("#x_card_num").removeAttr("data-validation");
		$("#cvv").removeAttr("data-validation");
		$("#x_first_name").removeAttr("data-validation");
	}
		
	$( document ).on( "click", ".dixi_show_card_wrap", function() {
		if (this.checked){
			$(".card_information_wrap").show();
			$("#tokenize_id").removeAttr("data-validation");			
						
			$("#x_card_num").attr("data-validation","required");
			$("#cvv").attr("data-validation","required");
			$("#x_first_name").attr("data-validation","required");
		} else {
			$(".card_information_wrap").hide();
			
			$("#tokenize_id").attr("data-validation","required");		
			
			$("#x_card_num").removeAttr("data-validation");
			$("#cvv").removeAttr("data-validation");
			$("#x_first_name").removeAttr("data-validation");
		}
	});
	
}); /*end docu*/


function CreditCardFormat(value) {
  var v = value.replace(/\s+/g, '').replace(/[^0-9]/gi, '')
  var matches = v.match(/\d{4,16}/g);
  var match = matches && matches[0] || ''
  var parts = []
  for (i=0, len=match.length; i<len; i+=4) {
    parts.push(match.substring(i, i+4))
  }
  if (parts.length) {
    return parts.join(' ')
  } else {
    return value
  }
}


/*PAYULATAM*/
jQuery(document).ready(function() {
	
	if( $(".has_card_saved").exists() ){
		$(".enter_card_div").hide();
		removeRequired('card_name,credit_card_number,expiration_month,expiration_yr,cvv')
	}
	
	$( document ).on( "click", ".enter_card_manually", function() {
		$(".select_card_div").remove();
		$(".enter_card_div").show();		
		removeRequired('card_id');
		required('card_name,credit_card_number,expiration_month,expiration_yr,cvv')
	});
		
	$( document ).on( "click", ".request_cancel_order", function() {
		order_id_token = $(this).data("id");
		ans = confirm(js_lang.trans_4);
		if(ans){
			callAjax("cancelOrder",'id='+order_id_token,'','');
		}
	});
	
});

function required(id)
{
	ids = id.split(",");	
	$.each(ids, function( index, val ) {
		$("#"+val).attr("required",true);
	});
}

function removeRequired(id)
{
	ids = id.split(",");
	$.each(ids, function( index, val ) {
		$("#"+val).removeAttr("required");
	});
}

jQuery(document).ready(function() {	
	
	if ( $("#single_uploadfile").exists() ){						
		single_uploadfile_progress = $("#single_uploadfile").data("progress");
		single_uploadfile_progress = $("."+single_uploadfile_progress);
		single_uploadfile_preview = $("#single_uploadfile").data("preview");
								
		var uploader = new ss.SimpleUpload({
			 button: 'single_uploadfile',
			 url: front_ajax + "/UploadProfile/?"+ addValidationRequest()+"&post_type=get&preview=" + single_uploadfile_preview  ,
			 name: 'uploadfile',			 	
			 responseType: 'json',			 
			 allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],			 
			 maxSize: image_limit_size,
			 onExtError: function(filename,extension ){
			   uk_msg(js_lang.invalid_file_ext,"warning");
		     },
		     onSizeError: function (filename,fileSize){ 
			   uk_msg( js_lang.invalid_file_size,"warning");  
		     },    
			 onSubmit: function(filename, extension) {			 	
			 	busy(true);	
			 	this.setProgressBar(single_uploadfile_progress);
			 },
			 onComplete: function(filename, response) {			 	 
			 	 busy(false);	 
			 	 if (!response) {
	                uk_msg(filename + 'upload failed');
	                return false;            
	             } else {	        	             	
	             	dump(response);	             	
	             	if( response.code==1){
	             		$("."+single_uploadfile_preview).html( response.details.preview_html );
	             		callAjax("saveAvatar",'filename='+response.details.new_filename );
	             	} else {
	             		uk_msg( response.msg );
	             	}
	             }	            
			 }
		});
	}
	
	$( document ).on( "click", ".del_addresslocation", function() {
		id = $(this).data('id');
		ans = confirm(js_lang.trans_4);
		if(ans){
			callAjax("delAddressBookLocation","id="+id);
		}				
	});	
	
	if ( $("#address_book_id_location").exists() ){
		$(".address-block").hide();			
		$(".saved_address_block").hide();	
				
		$("#state_id").removeAttr("data-validation");
  	    $("#city_id").removeAttr("data-validation");
  	    $("#area_id").removeAttr("data-validation");		
	}
	
	if( $("#is_search_by_location").exists() ){
		if( !empty($("#state_id").val()) ){
		   $("#state").val( $("#state_id option:selected").text()  );
		}
		if( !empty($("#city_id").val()) ){
		  $("#city").val( $("#city_id option:selected").text()  );	
		}
		if( !empty($("#area_id").val()) ){
  		   $("#area_name").val( $("#area_id option:selected").text()  );	
		}
	}
		
	$( document ).on( "change", ".date_list", function() {
		 date_selected = $(this).val();
		 if ( $(".time_list").exists() ){
		    callAjax("loadTimeList", "date_selected="+ date_selected + "&merchant_id=" + merchant_information.merchant_id );
		 } else {
		 	loadSkedMenu( $("#delivery_date").val() );
		 }
	});

	
	if ( $(".mapbox_s_goecoder").exists() ){
		mapbox_search_autocomplete();
	}

});/* END DOCU*/

function loadSkedMenu(date_selected)
{
	dump('loadSkedMenu =>' + date_selected);	
	if( !empty(website_use_date_picker) ){
		if (enabled_category_sked==1){
			callAjax("loadMenu",'merchant_id=' + merchant_information.merchant_id + "&date_selected="+date_selected);
		} 
	} 
}

useMapbox = function(){
	if(map_provider=="mapbox"){
		return true;
	}
	return false;
}

/*4.8*/
jQuery(document).ready(function() {
	
	if ( $("#address_book_id_location").exists() ){	
		setTimeout( function(){
			callAjax('loadAddressByLocation','id=' + $("#address_book_id_location").val() );
		}, 300);		
		
		$( document ).on( "change", "#address_book_id_location", function() {
			callAjax('loadAddressByLocation','id=' + $("#address_book_id_location option:selected").val() );
		});			
	}
	
	
	if ( $("#upload_deposit").exists() ){						
		upload_deposit_progress = $("#upload_deposit").data("progress");
		upload_deposit_progress = $("."+upload_deposit_progress);
		upload_deposit_preview = $("#upload_deposit").data("preview");
		
		upload_deposit_clear = $("#upload_deposit").data("clear");
		
							
		var uploader = new ss.SimpleUpload({
			 button: 'upload_deposit',
			 url: front_ajax + "/UploadDeposit/?"+ addValidationRequest()+"&post_type=get&preview=" + upload_deposit_preview +"&method=get" ,
			 name: 'uploadfile',			 	
			 responseType: 'json',			 
			 allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],			 
			 maxSize: image_limit_size,
			 onExtError: function(filename,extension ){
			   uk_msg(js_lang.invalid_file_ext,"warning");
		     },
		     onSizeError: function (filename,fileSize){ 
			   uk_msg( js_lang.invalid_file_size,"warning");  
		     },    
			 onSubmit: function(filename, extension) {			 	
			 	busy(true);	
			 	this.setProgressBar(upload_deposit_progress);
			 },
			 onComplete: function(filename, response) {			 	 
			 	 busy(false);	 
			 	 if (!response) {
	                uk_msg(filename + 'upload failed');
	                return false;            
	             } else {	        	             	
	             	dump(response);	             	
	             	if( response.code==1){
	             		$("."+upload_deposit_preview).html( response.details.preview_html );	
	             		$("."+upload_deposit_preview).after( '<input type="hidden" name="photo" value="'+ response.details.new_filename+'" >')
	             		
	             		if(upload_deposit_clear==1){
		             		$("#branch_code").removeAttr("data-validation");
		             		$("#date_of_deposit1").removeAttr("data-validation");
		             		$("#time_of_deposit").removeAttr("data-validation");
		             		$("#amount").removeAttr("data-validation");
	             		}
	             		
	             	} else {
	             		uk_msg( response.msg );
	             	}
	             }	            
			 }
		});
	}
			
	$( document ).on( "click", ".add_favorites", function() {
		id = $(this).data("id");
		callAjax("addToFavorite","id="+ id);
	});
	
	if ( $(".add_favorites").exists() ){
		loadFavorites();
	}
	
	$( document ).on( "click", ".remove_fav", function() {
		id = $(this).data("id");
		var ans=confirm(js_lang.trans_4);
		if(ans){
			callAjax("removeFavorite","id="+ id);
		}
	});
	
	if( $(".delivery_map_accuracy").exists() ){
		if(useMapbox()){
			mapbox_delivery_location('delivery_map_accuracy');
		} else {
			googleMapsDeliveryLocation();
		}
	}
	
}); /*end doc*/


var ajax_fav;
var dl_map;
var dl_marker;

loadFavorites = function(){	
	
	action='loadFavorites';
	params='';
	
	if(!empty(lang)){
		params+="&lang="+lang;
	}
	
	params+= addValidationRequest();
	
	ajax_fav = $.ajax({
	  url: front_ajax+"/"+action,
	  method: "POST",
	  data: params ,
	  dataType: "json",
	  timeout: 30000,	  
	  beforeSend: function( xhr ) {
         dump("before send ajax");     
          if(ajax_fav != null) {
          	 ajax_fav.abort();
          } 
      }
    });
    
    ajax_fav.done(function( data ) {    	
    	if(data.code==1){    		
    		$.each(data.details.data, function( index, val ) {
    			$(".fav_"+ val.merchant_id).addClass("selected");
    		});
    	}
    });
	
    ajax_fav.always(function() {
        dump("ajax always");
        ajax_fav=null;         
    });
    
};

googleMapsDeliveryLocation = function(){
	
	dl_map = new GMaps({
	    div: '.delivery_map_accuracy',
	    lat: temporary_address_lat,
	    lng: temporary_address_lng,	    
	    scrollwheel: false ,
	    styles: [ {stylers: [ { "saturation":-100 }, { "lightness": 0 }, { "gamma": 1 } ]}]
	});
	
	dl_marker = dl_map.addMarker({
		lat: temporary_address_lat,
		lng: temporary_address_lng,	
		draggable: true,
	});			
	
	dl_marker.addListener('dragend',function(event) {
		$("#map_accurate_address_lat").val( event.latLng.lat() );
	    $("#map_accurate_address_lng").val( event.latLng.lng() );
	});
	
	
	if (typeof address_list === "undefined" || address_list==null ) {
		//
	} else {
		try {
			address_book_id =  $("#address_book_id").val();
			lat = address_list[address_book_id].lat;
			lng = address_list[address_book_id].lng;		
			dl_map.setCenter(lat,lng);	    
		    dl_marker.setPosition( new google.maps.LatLng( lat,lng ) );	    
		    $("#map_accurate_address_lat").val( lat );
		    $("#map_accurate_address_lng").val( lng );	    
			return;		
		} catch(err) {
		  //
		}
	}
	
	GMaps.geolocate({
	  success: function(position) {	  	
	    dl_map.setCenter(position.coords.latitude, position.coords.longitude);	    
	    dl_marker.setPosition( new google.maps.LatLng( position.coords.latitude , position.coords.longitude ) );	    
	    $("#map_accurate_address_lat").val( position.coords.latitude );
	    $("#map_accurate_address_lng").val( position.coords.longitude );	    
	  },
	  error: function(error) {
	    uk_msg(  js_lang.geolocation_failed + ': '+error.message);
	  },
	  not_supported: function() {
	    uk_msg( js_lang.browser_not_supported );
	  },
	  always: function() {
	    //alert("Done!");
	  }
	});
	
};

/*4.9*/
jQuery(document).ready(function() {
		
	if( $(".badge_review_count").exists() ){		
		getRemainingReview();
	}
	
	if( $(".format_as_card_number").exists() ){
		
		 $('.format_as_card_number').validateCreditCard(function(result) {             
             removeClasses('format_as_card_number','valid,visa,visa_electron,mastercard,maestro,discover');
             if(result.card_type  != null){
                $(this).addClass(result.card_type.name);
             }
        });
        
		$.formUtils.addValidator({
		  name : 'full_name',
		  validatorFunction : function(value, $el, config, language, $form) {
		  	  dump(value);		     
		  	  res  = value.split(" "); 
		  	  dump(res);
		  	  if(res.length>=2){
		  	  	 return true;
		  	  } else {
		  	     return false;
		  	  }
		  },
		  errorMessage : js_lang.enter_full_name,
		  errorMessageKey: 'badEvenNumber'
		});	
		$.validate();
	}
	
	$.validate({ 	
		language : jsLanguageValidator,
	    form : '#forms_validate_card',    
	    onError : function() {      
	    },
	    onSuccess : function() {
	    	card_number = $(".format_as_card_number").val();
	    	clean_card = card_number.replace(/ /g,'');
	    	$(".format_as_card_number").val(clean_card);
	        return true;
	    }  
	});
	
	if( $(".cc_number").exists() ){
		onload = function() {
		  document.getElementById('cc_number').oninput = function() {
		    this.value = CreditCardFormat(this.value)
		  }
		}
	}
		
	$( document ).on( "click", ".delete_addressbook", function() {
		id = $(this).data('id');
		var ans=confirm(js_lang.deleteWarning);
		if(ans){
		   callAjax("delete_addressbook","id="+id);
		}
	});
		
	if ( $(".cc_expiration").exists() ){
		$('input.cc_expiration').formance('format_credit_card_expiry');
	}
	
	$( document ).on( "click", "#select_type_of_cc", function() {
		selected  = $(this).val();
		if (selected==1){
			$(".select_cc_wrap").show();
			$(".enter_cc_wrap").hide();
		} else {
			$(".select_cc_wrap").hide();
			$(".enter_cc_wrap").show();
		}
	});
		
	$( document ).on( "click", ".delete_client_cc", function() {
		id = $(this).data('id');
		var ans=confirm(js_lang.deleteWarning);
		if(ans){
		   callAjax("deleteClientCC","id="+id);
		}
	});
	
});/* end docu*/


var ajax_remaining_review;

getRemainingReview = function(){
	
	action='getRemainingReview';
	
	params='mtid='+ merchant_information.merchant_id;
	
	if(!empty(lang)){
		params+="&lang="+lang;
	}
		
	params+= addValidationRequest();
	
	ajax_remaining_review = $.ajax({
	  url: front_ajax+"/"+action,
	  method: "POST",
	  data: params ,
	  dataType: "json",
	  timeout: 30000,	  
	  beforeSend: function( xhr ) {
         dump("before send ajax");     
          if(ajax_remaining_review != null) {
          	 ajax_remaining_review.abort();
          } 
      }
    });
    
    ajax_remaining_review.done(function( data ) {    	
    	if(data.code==1 && data.details.remaining>0){    	    		
    		$(".badge_review_count").html( data.details.remaining  );
    		$(".review_notification").html( data.details.count_msg  );
    		$(".write-review-new").show();
    	} else {
    		$(".badge_review_count").html( ''  );
    		$(".review_notification").html( '' );
    		$(".write-review-new").hide();
    	}
    });
	
    ajax_remaining_review.always(function() {
        dump("ajax always");
        ajax_remaining_review=null;         
    });
    
};

removeClasses = function(field_class_name, classes){
	clas = classes.split(",");
	$.each(clas, function( index, val ) {
		$("."+field_class_name).removeClass(val);
	});
};


jQuery(document).ready(function() {	
	$( document ).on( "click", ".paynow_stripe", function() {		
		stripe.redirectToCheckout({		  
		  sessionId: stripe_session,
		}).then(function (result) {
		    uk_msg(result.error.message);
		});		
	});
	
	$( document ).on( "click", ".request_cancel_booking", function() {
		booking_id = $(this).data("id");
		ans = confirm(js_lang.trans_4);
		if(ans){
			callAjax("requestCancelBooking",'id='+booking_id,'','');
		}
	});
	
	$( document ).on( "change", "#address_book_id", function() {
		if (typeof address_list === "undefined" || address_list==null ) {
			//
		} else {
			try {
				address_book_id = $(this).val();
				lat = address_list[address_book_id].lat;
			    lng = address_list[address_book_id].lng;
				if(map_provider=="mapbox"){
					mapbox_delivery_accuracy_marker.setLatLng([lat, lng]).update();
					mapbox_delivery_accuracy.setView([lat, lng], 14);
				} else {					
			        dl_map.setCenter(lat,lng);	    
				    dl_marker.setPosition( new google.maps.LatLng( lat,lng ) );	    
				}
			    $("#map_accurate_address_lat").val( lat );
			    $("#map_accurate_address_lng").val( lng );	    
		    } catch(err) {
		    	//
		    }
		}
	});
		
	$( document ).on( "click", ".forgot_selection", function() {
		$(".forgot_form_email").hide(); $(".forgot_form_sms").hide();
		var selection = $(this).data("id");
		$(".forgot_form_"+selection).show();
		$("#forgot_pass_action").val(selection);
		switch(selection){
			case "email":
			  $("#username-email").attr("required",true);
			  $("#forgot_phone_number").removeAttr("required");
			break;
			
			case "sms":
			  $("#forgot_phone_number").attr("required",true);
			  $("#username-email").removeAttr("required");
			break;
		}
	});
	
	
	$.validate({ 	
		language : jsLanguageValidator,
	    form : '#frm_ajax',    
	    onError : function() {      
	    },
	    onSuccess : function() {     
	      var params=$("#frm_ajax").serialize();	      
	      callAjax(ajax_action,params, $("#frm_ajax button") );
	      return false;
	    }  
	});
		
	
});
/*end ready*/

function clearFields(data)
{
	$.each(data, function( index, fields ) {
		$(fields).val('');
	});
}