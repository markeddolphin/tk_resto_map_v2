var my_notification = document.getElementById("my_notification");  
var notification_interval =  20000; //33000; //20000 //15000

function clear_elements(ele) {	
    $("#"+ele).find(':input').each(function() {						    	
        switch(this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
                $(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;            
            
        }
   });
   
   $(".preview").remove();
}

$.validate({ 	
	language : jsLanguageValidator,
    form : '#forms',    
    onError : function() {      
    },
    onSuccess : function() {     
      form_submit();
      return false;
    }  
});

$.validate({ 	
	language : jsLanguageValidator,
    form : '#frm-pop',    
    onError : function() {      
    },
    onSuccess : function() {     
      form_submit('frm-pop');
      return false;
    }  
});

$.validate({ 	
	language : jsLanguageValidator,
    form : '#mt-frm',    
    onError : function() {      
    },
    onSuccess : function() {     
      form_submit('mt-frm');
      return false;
    }  
});

$.validate({ 	
	language : jsLanguageValidator,
    form : '#mt-frm-activation',    
    onError : function() {      
    },
    onSuccess : function() {     
      form_submit('mt-frm-activation');
      return false;
    }  
});

$.validate({ 	
	language : jsLanguageValidator,
    form : '#frm-creditcard',    
    onError : function() {      
    },
    onSuccess : function() {           
      form_submit('frm-creditcard');
      return false;
    }  
});

$.validate({ 		
	language : jsLanguageValidator,
    form : '#frm-smsbroadcast',    
    onError : function() {      
    },
    onSuccess : function() {       
      var broadcast_type=$("#send_to:checked").val();
      //////console.debug(broadcast_type);
      if ( broadcast_type ==1 || broadcast_type == 2){
      	  var total=0;
      	  if ( broadcast_type ==1 ){
      	  	  total=$("#total_customer").val();
      	  } else if ( broadcast_type == 2 ){
      	  	 total=$("#total_customer_by_merchant").val();
      	  }
          var a=confirm(js_lang.trans_19+" ("+total+") "+js_lang.trans_20+"\n"+js_lang.trans_18);    
          if (a){
            form_submit('frm-smsbroadcast');
          }
      } else {
      	 form_submit('frm-smsbroadcast');
      }
      return false;
    }  
});

function busy(e)
{
    if (e) {
        $('body').css('cursor', 'wait');	
    } else $('body').css('cursor', 'auto');        
    
    if (e) {
    	$(".main-preloader").show(); 
    } else $(".main-preloader").hide(); 
    
}

function scroll(id){
   if( $('#'+id).is(':visible') ) {	
      $('html,body').animate({scrollTop: $("#"+id).offset().top-100},'slow');
   }
}

function toogle(id , bool , caption)
{
    $('#'+id).attr("disabled", bool );
    $("#"+id).val(caption);
}

function rm_notices()
{
	$(".uk-alert").remove();		    
}

function form_submit(formid)
{			
	
	dump("form_submit");
	
	rm_notices();
    //var form_id=$("form").attr("id");    
    if ( formid ) {
		var form_id=formid;
	} else {
		var form_id=$("form").attr("id");    
	}   
    
	var btn=$('#'+form_id+' input[type="submit"]');   	
    var btn_cap=btn.val();
    btn.attr("disabled", true );
    btn.val(js_lang.processing);
    busy(true);    
    
    var action=$('#'+form_id).find("#action").val(); 
    
	var params=$("#"+form_id).serialize();	
	
	params+="&currentController="+$("#currentController").val();
		
	params+= addValidationRequest();
	
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){ 
        	busy(false);  
        	btn.attr("disabled", false );
        	btn.val(btn_cap);        	
        	//scroll(form_id);
        	if (data.code==1){
        		//$("#"+form_id).before("<p class=\"uk-alert uk-alert-success\">"+data.msg+"</p>");
        		uk_msg_sucess(data.msg);
        		
        		if ( action=="switchMerchantAccount"){
        			window.location.href=data.details;
        		}
        		
        		if (action=="addMerchant"){
        			var old_status=$("#old_status").val();
        			var new_status=$("#status").val();        			
        			if ( old_status=="" || old_status!=new_status){
	        			var params="action=sendEmailMerchant&backend=true"+"&currentController="+
	        			$("#currentController").val()+"&id="+$("#id").val()+"&tbl=sendEmailMerchant";
	                    //open_fancy_box(params);
        			}
        		}
        		if (action=="sendEmailToMerchant"){
        			close_fb();
        		}
        		
        		if ( action=="merchantForgotPass" || action=="adminForgotPass"){
        			$(".mt-frm").hide();
        			$(".mt-frm-activation").show();
        			$("#email").val( $("#email_address").val() );
        			
        			if ( action=="adminForgotPass" ){
        				$(".uk-form").show();
        			}
        			return;
        		}
        		if ( action=="merchantChangePassword"){
        			$(".forms").show();
        			$(".mt-frm").hide();        			
        			$(".mt-frm-activation").hide();
        			return;
        		}
        		
        		if ( !empty(data.details)){
        			if ( !empty(data.details.redirect)){
	        			window.location.href=data.details.redirect;
	        			return;
        			}
        		}
        		
        		if ( $("#redirect").length>=1 ){
        			if (typeof data.details === "undefined" || data.details==null ) {        			    		
        			    window.location.replace(  $("#redirect").val() );
        			} else {
        				window.location.replace(  $("#redirect").val()+"/id/"+data.details );
        			}
        		}
        		        		
        		if ( action=="UpdateMerchant"){
        			if(!useMapbox()){
        			   load_map();
        			}        			
        		}        		
        		
        		if (action=="updateOrder"){
        			table_reload();
        			close_fb();
        			
        			// show sms message pop 
        			if (data.details.show_sms==2){
	        			/*var params="action=showSMS&tbl=showSMS&backend=true"+
	        			"&currentController="+$("#currentController").val()+
	        			"&order_id="+data.details.order_id;
	                    open_fancy_box(params);*/
        			}			
        		}
        		
        		if ( action=="sendUpdateOrderSMS" || action=="sendUpdateOrderEmail"){
        			close_fb();
        		}
        		
        		if (action=="updatePaymennt"){
        			table_reload();
        			close_fb();
        		}
        		
        		if (action=="updateMerchantStatus"){
        			table_reload();
        			close_fb();
        		}        		
        		        		
        		if (action=="initSelectPaymentProvider" || action=="initpaymentprovider" || action=="paymentPaypalVerification" ){
        			//window.location.replace( data.details);
        			window.location.href=data.details;
        			return;
        		}        		
        		
        		if ( action=="addCreditCardMerchant"){
        			loadCreditCardListMerchant();
        		}
        		
        		if ( action=="payCC"){
        			//window.location.replace(sites_url+"/merchant/smsReceipt/id/"+data.details);
        			window.location.href=sites_url+"/merchant/smsReceipt/id/"+data.details;
        			return;
        		}
        		if ( action=="PayPaypal"){
        			//window.location.replace(data.details);
        			window.location.href=data.details;
        			return;
        		}
        		
        		if (action=="FaxbankDepositVerification"){
        			clear_elements('forms');
        		}        	
        		
        		if (action=="updateOrderAdmin"){
        			table_reload();
        			if(!empty(epp_table3)){
        			   epp_table3.fnReloadAjax();
        			}
        			close_fb();        			        			
        		}
        		
        	} else {        		
        		
        		switch(action){
        			case "merchantLogin":
        			case "login":
        			  uk_msg(data.msg);        			
        			  if (typeof captcha_site_key === "undefined" || captcha_site_key==null || captcha_site_key=="" ) {         			      
        			  } else  {
        			  	 recaptchav3();  
        			  }        			          			 
        			break;
        			
        			default:
        			  uk_msg(data.msg);
        			break;
        		}
        		
        	}
        	        	        	
        }, 
        error: function(){	        	
        	btn.attr("disabled", false );
        	btn.val(btn_cap);
        	busy(false);        	
        	uk_msg(data.msg);        	
        }		
    });
}

var otable;

jQuery.fn.exists = function(){return this.length>0;}

jQuery(document).ready(function() {
			
	//if( $('.chosen').is(':visible') ) {     
       //$(".chosen").chosen(); 
    if( $('.chosen').exists() ) {     
       $(".chosen").chosen({
       	  allow_single_deselect:true,
       	  no_results_text: js_lang.trans_33,
          placeholder_text_single: js_lang.trans_32, 
          placeholder_text_multiple: js_lang.trans_32
       }); 	
    } 
    
    //if( $('.icheck').is(':visible') ) { 
    if( $('.icheck').exists() ) { 
	     $('.icheck').iCheck({
	       checkboxClass: 'icheckbox_minimal',
	       radioClass: 'iradio_flat'
	     });
    }
    
    if( $('.merchant-add').is(':visible') ) { 
	     $('.icheck').iCheck({
	       checkboxClass: 'icheckbox_minimal',
	       radioClass: 'iradio_flat'
	     });
    }
    
    if( $('#table_list').is(':visible') ) {    	
    	table();    	
    } 
    
    if( $('#table_list2').is(':visible') ) {    	
    	table2();    	
    } 
    
    if( $('#table_list3').is(':visible') ) {    	
    	table3();    	
    } 
    
    $("#table_list").on({	
	   mouseenter: function(){    	    
	    $(this).find(".options").show();
	  },
	   mouseleave: function () {	   
	    $(this).find(".options").hide();
	}},'tbody tr');	    	
	
	$( document ).on( "click", ".row_del", function() {
        var ans=confirm(js_lang.deleteWarning);        
        if (ans){        	
        	row_delete( $(this).attr("rev"),$("#tbl").val(), $(this));        	        
        }
    });
    
    /*if( jQuery('#photo').is(':visible') ) {    	
       createUploader('photo','photo');
    }     
    if( jQuery('#photo2').is(':visible') ) {    	
       createUploader('photo2','photo2');
    }     
    if( jQuery('#files').is(':visible') ) {    	
       createUploader('files','files');
    }     
    
    if( jQuery('#gallery').is(':visible') ) {    	
       createUploader('gallery','gallery');
    }*/    
    
    $('.numeric_only').keyup(function () {     
      this.value = this.value.replace(/[^0-9\.]/g,'');
    });	
    
    $( document ).delegate( ".multi_option", "change", function() {	
    	var parent=$(this).parent().parent();
    	////////console.debug(parent);
    	if ( $(this).val() =="custom"){    	
    		parent.find(".multi_option_value").show();	
    	} else {    	
    		parent.find(".multi_option_value").val("");
    		parent.find(".multi_option_value").hide();
    	}        
    });	
    
    $( document ).on( "click", ".addnewprice", function() {
    	var html=$(".price_wrap").html();    	
    	//$(".price_wrap").after("<li class=\"\">"+html+"</li>");
    	$(".price_wrap_parent li:last").prev().after("<li class=\"\">"+html+"</li>");
    });
    
    $( document ).on( "click", ".removeprice", function() {
    	var price_wrap = $(".price_wrap_parent li").length;
    	dump(price_wrap); 
    	if ( price_wrap<=3){
    		return;
    	}   
    	var parent=$(this).parent().parent().parent();
    	parent.remove();
    });
    
    var website_date_picker_format="yy-mm-dd";
    if ( $("#website_date_picker_format").exists()){
    	website_date_picker_format= $("#website_date_picker_format").val();
    }
    dump(website_date_picker_format);
    
    jQuery(".j_date").datepicker( { 
       dateFormat: website_date_picker_format,        
       altFormat: "yy-mm-dd",       
       changeMonth: true, 
       changeYear: true ,      
	   yearRange: "-10:+10",
	   prevText: js_lang.datep_1,
		nextText: js_lang.datep_2,
		currentText: js_lang.datep_3,
		monthNames: [js_lang.January,js_lang.February,js_lang.March,js_lang.April,js_lang.May,js_lang.June,
		js_lang.July,js_lang.August,js_lang.September,js_lang.October,js_lang.November,js_lang.December],
		monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
		js_lang.Jul, js_lang.Aug, js_lang.Sep, js_lang.Oct, js_lang.Nov, js_lang.Dec],
		dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
		dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		dayNamesMin: [js_lang.Su,js_lang.Mo,js_lang.Tu,js_lang.We,js_lang.Th,js_lang.Fr,js_lang.Sa],						
		isRTL: false,
		onSelect : function( element, object ) {
			var original_id=$(this).data("id");
			dump(original_id);
			var altFormat = $(this).datepicker('option', 'altFormat');
			var currentDate = $(this).datepicker('getDate');
			var formatedDate = $.datepicker.formatDate(altFormat, currentDate);
			dump(formatedDate);
			$("#"+original_id).val(formatedDate);
		}
	});	  
	
	var show_period=false;
	if ( $("#website_time_picker_format").exists() ){		
		if ( $("#website_time_picker_format").val()=="12"){
			show_period=true;
		}
	}
	dump(show_period);
	
	jQuery('.timepick').timepicker({  
		showPeriod: show_period,      
        hourText: js_lang.Hour,       
		minuteText: js_lang.Minute,  
		amPmText: [js_lang.AM, js_lang.PM]
    });
    
    $( ".sortable_ul" ).sortable({
       	update: function( event, ui ) {       		
       		//sort_list( $(this) );
       	},
       	 change: function( event, ui ) {
       	 	////////console.debug('d2');
       	 }
    }); 
        
    $( document ).on( "click", ".view-map", function() {     	
       if(useMapbox()){
       	  var lat = $("#merchant_latitude").val();
	      var lng = $("#merchant_longtitude").val();       
       	  mapbox_init_map(lat, lng)
       } else {
          load_map();
       }
    });
    
    $( document ).on( "click", ".remove-merchant-logo", function() {    	
        var a=confirm(js_lang.trans_4);
        if (a){
        	remove_logo();
        }    
    });
    
    $( document ).on( "click", ".remove-merchant-bg", function() {    	
        var a=confirm(js_lang.trans_4);
        if (a){
        	remove_merchant_bg();
        }    
    });
    
    $( document ).on( "click", ".view-receipt", function() {    	       	 	
   	   var params="action=viewReceipt&tbl=viewReceipt&id="+ $(this).data("id")+"&backend=true"+"&currentController="+$("#currentController").val();
       open_fancy_box(params);
   });	
   
   $( document ).on( "click", ".print_element", function() {    	       	 	       
       $('.receipt-main-wrap').printElement();       
   });	
      
   $( document ).on( "click", ".edit-order", function() {    	       	 	      
       var params="action=editOrder&tbl=viewReceipt&id="+ $(this).data("id")+"&currentController="+$("#currentController").val();
       open_fancy_box(params);
   });	
      
   $( ".export_btn" ).click(function(){
      var params="action=export&rpt="+$(this).attr("rel")+"&tbl=export" + addValidationRequest();
      window.open(ajax_url+"?"+params);      
   });
   
   $( document ).on( "click", ".edit-payment", function() {    	       	 	      
       var params="action=editPayment&tbl=editPayment&id="+ $(this).data("id");
       open_fancy_box(params);
   });	
   
   
   $( document ).on( "click", ".edit-merchant-status", function() {    	       	 	      
       var params="action=editMerchantStatus&tbl=editMerchantStatus&id="+ $(this).data("id");
       open_fancy_box(params);
   });	

   
   
   if( $('.is_ready').is(':visible') ) {	
      get_merchant_status();
   }
     
   $('.is_ready').on('ifChecked', function(event){    	
    	merchant_set_ready(2);
   });
   $('.is_ready').on('ifUnchecked', function(event){    	
    	merchant_set_ready(1);
   });
      
       
    $( ".sortable-jquery" ).sortable();
    
    
    $( document ).on( "click", ".mt-fp-link", function() {    	    
    	$(".mt-frm").slideDown();
    	$(".forms").slideUp();
    	$(".mt-frm-activation").hide();
    });  
    $( document ).on( "click", ".mt-login-link", function() {    	    
    	//////console.debug('d2');    	
    	$(".forms").show();
    	$(".mt-frm").hide();
    	$(".mt-frm-activation").hide();
    });      
    
        
    if ( $("#alert_off").val()=="" ) {		    		    
	}   
	
	//if ( $("#alert_off").val()=="" ){	 	
	if ( $("#currentController").val()=="merchant" ){
	 	new_order_notify = setInterval(function(){get_new_order()}, notification_interval+1000 );
	}	
	//}
	
	if ( $("#booking_alert").exists() ){				
		if ( $("#booking_alert").val()=="" ){				
		 	new_order_notify = setInterval(function(){get_booking()}, notification_interval+2000);
		}
	}
	
	$('.payment_option').on('ifChecked', function(event){
       ////console.debug( $(this).val() );
   });
   
   $('.send_to').on('ifChecked', function(event){    	    	
    	if ( $(this).val() == 3){
    		$(".custom_wrap_mobile").slideDown();
    		$("#list_mobile_number").attr("data-validation","required");
    	} else  {
    		$(".custom_wrap_mobile").slideUp();
    		$("#list_mobile_number").removeAttr("data-validation");
    	}
   });
   
   if( $('#sms_alert_message').is(':visible') ) {           			
       $('#sms_alert_message').restrictLength($('#maxlength'));
   }
      
   if( $('#merchant').is(':visible') ) {    
   	   ////console.debug('d2');       			
       get_sell_limit_status();
   }
   
   $( document ).on( "click", ".select_all", function() {    	          	      	   
   	   //$(".access").attr('checked', true);   	      	   
   	   $('.access').iCheck('check');
   	   
   });
   $( document ).on( "click", ".unselect_all", function() {    	          	      	   
   	   //$(".access").attr('checked',false);   	   
   	   $('.access').iCheck('uncheck');
   });   
   
   $( document ).on( "click", ".view_vouchers", function() {    	          	      	   
   	   var id=$(this).data("id");   	   
   	   var params="action=voucherdetails&tbl=voucherdetails&id="+ $(this).data("id")+"&backend=true";
       open_fancy_box(params);
   });   
   
   $( document ).on( "click", ".get-coordinates", function() {
   	   var address = $("#street").val() + " "+ $("#city").val() + " "+ $("#state").val()  ;
   	   address+=" "+$("#country_code").val()
   	   //console.debug(address);
   	   //var a=confirm("Is this address correct? \n"+address);
   	   var a=confirm(js_lang.trans_50+"? \n"+address);
   	   if (a){   	   	   	  
   	   	  if(useMapbox()){	   	  
   	   	  	 callAjax("geocode","address="+address );
   	   	  } else {
   	   	  	 geocode_address(address);   	   	  
	   	   	 alert(js_lang.trans_49);
   	   	  }   	   
   	   }      	   
   });   	  
    
});/* END DOCUMENT*/

function debug(t)
{
	//console.debug(t);
}

function get_new_order()
{			
	var params="action=getNewOrder";
	params+= addValidationRequest();	
	
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){      
        	if (data.code==1){        		   
        		if ( $(".merchant-dashboard").exists() ) {
        		    table_reload();
        		}
        		if( $('.uk-notify').is(':visible') ) {           			
        		} else {              			
        			if ( $("#alert_off").val()=="" ){	          			   
        			   my_notification.play(); 
        			} else {        				
        			}
        			$.UIkit.notify({
		       	   	   message : data.msg+" "+js_lang.NewOrderStatsMsg+data.details
		       	    }); 	       	        
        		}
        	}
        }, 
        error: function(){        	
        }		
    });
}

function get_sell_limit_status()
{	
	var params="action=getLimitSellStatus&currentController=merchant";
	params+= addValidationRequest();	
	
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){   
        	if (data.code==2){
        		$.UIkit.notify({
	               message :data.msg,
	               pos:'bottom-right',
	               status:'danger',
	               timeout :8500,	  
	            });	    	   	   	  
        	}         	
        }, 
        error: function(){        	
        }		
    });
}

var epp_table;
var epp_table2;
var epp_table3;

function load_map()
{
	if ( $("#merchant_latitude").val() =="" && $("#merchant_longtitude").val()==""){
   	  $("#google_map_wrap").hide();
    } else {    
   	  $("#google_map_wrap").show();
      locations=[[$("#restaurant_name").val(),$("#merchant_latitude").val(),$("#merchant_longtitude").val(),16]];
      initializeMarker(locations);         	    
    }
}

function table()
{		    
	
	if ( $(".selected_transaction_query").exists() ){
		var query=$("#query").val();				
		switch(query){
			case "last30":
			$(".last30").addClass("uk-button-success");
			break;
			case "last15":
			$(".last15").addClass("uk-button-success");
			break;
			case "month":
			  var query_date="selected-"+$("#query_date").val();			  
			  $("."+query_date).addClass("uk-button-success");
			break;
			
			case "all":			  
			$(".all").addClass("uk-button-success");
			break;
		}
	}
	
	var action=$("#action").val();		
	var server_side=false;
	if (action=="merchantList" || action=="customerList"){
	    server_side=true;
	}
	if ( $("#server_side").exists() ){
		if (  $("#server_side").val()==1){
		    server_side=true;
		}
	}
	
	var sort_by ='desc';
	switch ( $("#action").val() )
	{
		case "CountryList":
		sort_by ='asc';
		break;
		
		default:
		break;
	}
		
	var params=$("#frm_table_list").serialize();
	params+="&currentController="+$("#currentController").val();	
	
	params+= addValidationRequest();
	
    epp_table = $('#table_list').dataTable({
    	   "iDisplayLength": 15,
	       "bProcessing": true, 
	       //"bServerSide": false,
	       "bServerSide": server_side,
	       "sAjaxSource": ajax_url+"?"+params,	       
	       "aaSorting": [[ 0, sort_by ]],
	       "oLanguage":{	       	 
	       	 "sProcessing": "<p>Processing.. <i class=\"fa fa-spinner fa-spin\"></i></p>"
	       },
	       "oLanguage": {
	       	  "sEmptyTable":    js_lang.tablet_1,
			    "sInfo":           js_lang.tablet_2,
			    "sInfoEmpty":      js_lang.tablet_3,
			    "sInfoFiltered":   js_lang.tablet_4,
			    "sInfoPostFix":    "",
			    "sInfoThousands":  ",",
			    "sLengthMenu":     js_lang.tablet_5,
			    "sLoadingRecords": js_lang.tablet_6,
			    "sProcessing":     js_lang.tablet_7,
			    "sSearch":         js_lang.tablet_8,
			    "sZeroRecords":    js_lang.tablet_9,
			    "oPaginate": {
			        "sFirst":    js_lang.tablet_10,
			        "sLast":     js_lang.tablet_11,
			        "sNext":     js_lang.tablet_12,
			        "sPrevious": js_lang.tablet_13
			    },
			    "oAria": {
			        "sSortAscending":  js_lang.tablet_14,
			        "sSortDescending": js_lang.tablet_15
			    }
	       },
	       "fnInitComplete": function(oSettings, json) {
	       	  var action=$("#action").val();	     	       	  
		      if ( action=="merchantCommissionDetails"){		      	  
		      	  $(".merchant_name").html(json.merchant_name);
		      	  $(".total_commission").html(json.total_commission);
		      }
		      if ( action=="merchantStatement"){		      	  
		      	  $(".total_amount").html(json.total_amount);		      	  
		      	  if ( $("#payment_type").val()==2){
		      	  	 $(".cash_tr").show();
		      	     $(".total_payable").html(json.total_payable);
		      	  }
		      }
		      
		      if (action=="merchantCommission"){
		      	  $(".total_commission").html(json.total_commission);
		      }
		      
		    }
    });		
}

function table2()
{		
	var params=$("#frm_table_list2").serialize();
	if(!empty(current_panel)){
	   params+="&current_panel="+current_panel;
	}
	params+= addValidationRequest();
	
    epp_table2 = $('#table_list2').dataTable({
	       "bProcessing": true, 
	       "bServerSide": false,
	       "sAjaxSource": ajax_url+"?"+params,	       
	       "aaSorting": [[ 0, "desc" ]],
	       "oLanguage":{	       	 
	       	 "sProcessing": "<p>Processing.. <i class=\"fa fa-spinner fa-spin\"></i></p>"
	       },
	       "oLanguage": {
	       	  "sEmptyTable":    js_lang.tablet_1,
			    "sInfo":           js_lang.tablet_2,
			    "sInfoEmpty":      js_lang.tablet_3,
			    "sInfoFiltered":   js_lang.tablet_4,
			    "sInfoPostFix":    "",
			    "sInfoThousands":  ",",
			    "sLengthMenu":     js_lang.tablet_5,
			    "sLoadingRecords": js_lang.tablet_6,
			    "sProcessing":     js_lang.tablet_7,
			    "sSearch":         js_lang.tablet_8,
			    "sZeroRecords":    js_lang.tablet_9,
			    "oPaginate": {
			        "sFirst":    js_lang.tablet_10,
			        "sLast":     js_lang.tablet_11,
			        "sNext":     js_lang.tablet_12,
			        "sPrevious": js_lang.tablet_13
			    },
			    "oAria": {
			        "sSortAscending":  js_lang.tablet_14,
			        "sSortDescending": js_lang.tablet_15
			    }
	       }	       
    });		
}

function table3()
{		
	var params=$("#frm_table_list3").serialize();
	params+= addValidationRequest();
	
    epp_table3 = $('#table_list3').dataTable({
	       "bProcessing": true, 
	       "bServerSide": false,
	       "sAjaxSource": ajax_url+"?"+params,	       
	       "aaSorting": [[ 0, "desc" ]],
	       "oLanguage":{	       	 
	       	 "sProcessing": "<p>Processing.. <i class=\"fa fa-spinner fa-spin\"></i></p>"
	       },
	       "oLanguage": {
	       	  "sEmptyTable":    js_lang.tablet_1,
			    "sInfo":           js_lang.tablet_2,
			    "sInfoEmpty":      js_lang.tablet_3,
			    "sInfoFiltered":   js_lang.tablet_4,
			    "sInfoPostFix":    "",
			    "sInfoThousands":  ",",
			    "sLengthMenu":     js_lang.tablet_5,
			    "sLoadingRecords": js_lang.tablet_6,
			    "sProcessing":     js_lang.tablet_7,
			    "sSearch":         js_lang.tablet_8,
			    "sZeroRecords":    js_lang.tablet_9,
			    "oPaginate": {
			        "sFirst":    js_lang.tablet_10,
			        "sLast":     js_lang.tablet_11,
			        "sNext":     js_lang.tablet_12,
			        "sPrevious": js_lang.tablet_13
			    },
			    "oAria": {
			        "sSortAscending":  js_lang.tablet_14,
			        "sSortDescending": js_lang.tablet_15
			    }
	       }	       
    });		
}

function table_reload()
{	
	epp_table.fnReloadAjax(); 
}

function sales_summary_reload()
{
	var params=$("#frm_table_list").serialize();
	params+= addValidationRequest();
	
	console.debug(params);
    /*epp_table = $('#table_list').dataTable({
	       "bProcessing": true, 
	       "bServerSide": false,
	       "sAjaxSource": ajax_url+"?"+params,	       
	       "aaSorting": [[ 0, "desc" ]]	       
    });		*/
	epp_table.fnReloadAjax(ajax_url+"?"+params); 
}


function row_delete(id,tbl,object)
{		
	var form_id=$("form").attr("id");
	rm_notices();	
	busy(true);
	var params="action=rowDelete&tbl="+tbl+"&row_id="+id+"&whereid="+$("#whereid").val()+"&yii_session_token="+yii_session_token;	
	params+='&current_panel='+ current_panel;
	
	params+= addValidationRequest();	
	
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){
        	busy(false);
        	if (data.code==1){       
        		$("#"+form_id).before("<div class=\"uk-alert uk-alert-success\">"+data.msg+"</div>");         		
        		tr=object.closest("tr");
                tr.fadeOut("slow");
        	} else {      
        		$("#"+form_id).before("<div class=\"uk-alert uk-alert-danger\">"+data.msg+"</div>");
        	}        	        	
        }, 
        error: function(){	        	        	
        	busy(false);
        	$("#"+form_id).before("<div class=\"uk-alert uk-alert-danger\">ERROR:</div>");
        }		
    });
}

function photo(data)
{
	var img='';
	//////console.debug(data);
	$(".preview").show();
	img+="<img src=\""+upload_url+"/"+data.details.file+"\" alt=\"\" title=\"\" class=\"uk-thumbnail uk-thumbnail-mini\" >";
	img+="<input type=\"hidden\" name=\"photo\" value=\""+data.details.file+"\" >";
	img+="<p><a href=\"javascript:rm_preview();\">"+js_lang.removeFeatureImage+"</a></p>";
	$(".image_preview").html(img);
}

function photo2(data)
{
	var img='';	
	$(".preview2").show();
	img+="<img src=\""+upload_url+"/"+data.details.file+"\" alt=\"\" title=\"\" class=\"uk-thumbnail uk-thumbnail-mini\" >";
	img+="<input type=\"hidden\" name=\"photo2\" value=\""+data.details.file+"\" >";
	img+="<p><a href=\"javascript:rm_preview2();\">"+js_lang.removeFeatureImage+"</a></p>";
	$(".image_preview2").html(img);
	$(".image_preview2").show();
}

function files(data)
{
	var img='';	
	$(".preview2").show();
	img+="<p>"+data.details.file+"</p>";
	img+="<input type=\"hidden\" name=\"language_file\" value=\""+data.details.file+"\" >";
	img+="<p><a href=\"javascript:rm_preview();\">"+js_lang.removeFiles+"</a></p>";
	$(".image_preview").html(img);
	$(".image_preview").show();
}

function rm_preview()
{
	$(".image_preview").html('');
}

function rm_preview2()
{
	$(".image_preview2").html('');
}

function remove_logo()
{
	var params="action=removeLogo";
	params+= addValidationRequest();	
	
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      
    	if (data.code==1){
    		if (data.code==1){
    			uk_msg_sucess(data.msg);
    			$(".image_preview").hide();
    			$(".image_preview input").val('');
    		}
    	} else {
    	   uk_msg(data.msg);
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });
}

function remove_merchant_bg()
{
	var params="action=removeMerchantBg";
	params+= addValidationRequest();	
	
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      
    	if (data.code==1){
    		if (data.code==1){
    			$(".image_preview2").hide();
    			$('input[name="photo2"]').remove();
    		}
    	} else {
    	   uk_msg(data.msg);
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });
}

function uk_msg(msg)
{
	$.UIkit.notify({
	   message :msg,
	   timeout :1500,	   
	});	    	   	   	  
}

function uk_msg_sucess(msg)
{
	$.UIkit.notify({
	   message :msg,
	   timeout :1500,	   
	   status:'success'
	});	    	   	   	  
}

function open_fancy_box(params)
{  	  	  	  	  	
  	params+= addValidationRequest();
  	
	var URL=ajax_url+"/?"+params;
	$.fancybox({        
	maxWidth:800,
	closeBtn : false,          
	autoSize : true,
	padding :0,
	margin :2,
	modal:false,
	type : 'ajax',
	href : URL,
	openEffect :'elastic',
	closeEffect :'elastic'	
	});   
}

function close_fb()
{
	$.fancybox.close(); 
}

/*=============================================================
START GOOGLE MAP MARKER
=============================================================*/
function initializeMarker(locations){	

    window.map = new google.maps.Map(document.getElementById('google_map_wrap'), {
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        scrollwheel: false
        //styles: [ {stylers: [ { "saturation":-100 }, { "lightness": 0 }, { "gamma": 0.5 }]}]
    });
        
    var infowindow = new google.maps.InfoWindow();

    var bounds = new google.maps.LatLngBounds();

    for (i = 0; i < locations.length; i++) {
        marker = new google.maps.Marker({
            position: new google.maps.LatLng(locations[i][1], locations[i][2]),
            map: map
            //icon: hl_template_url + '/images/google_mapicon.png'
        });

        bounds.extend(marker.position);

        google.maps.event.addListener(marker, 'click', (function (marker, i) {
            return function () {
                infowindow.setContent(locations[i][0]);
                infowindow.open(map, marker);
            }
        })(marker, i));
    }

    map.fitBounds(bounds);

    var listener = google.maps.event.addListener(map, "idle", function () {
        map.setZoom(18); /*16*/
        google.maps.event.removeListener(listener);
    });
}
/*=============================================================
END GOOGLE MAP MARKER
=============================================================*/

jQuery(document).ready(function() {	
	if( $('.chart').is(':visible') ) {	
	   load_totalsales_chart();	
	   load_total_sales_chart_by_item();
	}
}); /*END DOCU*/

function load_totalsales_chart()
{
	$.jqplot.config.enablePlugins = true;
	var ajaxDataRenderer = function(url, plot, options) {
    var ret = null;
    $.ajax({    
      async: false,
      url: url,
      dataType:"json",
      success: function(data) {
        ret = data;
      }
    });
    return ret;
    };
    
    var jsonurl = ajax_url+"/?action=chartTotalSales&tbl=chart&currentController="+$("#currentController").val();    
    
    jsonurl+= addValidationRequest();	
          
    var plot1 = $.jqplot('total_sales_chart', jsonurl,{
     animate: true,
     title: js_lang.lastTotalSales ,
     seriesDefaults:{
            renderer:$.jqplot.BarRenderer,
            pointLabels: { show: true },
            rendererOptions:{  varyBarColor: true }
     },     
       axesDefaults: {
        tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
        tickOptions: {
          angle: -30,
          fontSize: '10pt',
        }
    },
     grid:{
    		drawGridLines: false,
    		gridLineColor: '#cccccc',
    		backgroundColor: "#eee",
    		drawBorder: false,
    		borderColor: '#999999',   
    		borderWidth: 1.0,
    		shadow: false
     },
     axes: {
            xaxis: {
                renderer: $.jqplot.CategoryAxisRenderer,
                //ticks: ticks
            },
            yaxis: {
             tickOptions:{
               formatString: "%#.2f"
             }
           }
     },
     dataRenderer: ajaxDataRenderer,
     dataRendererOptions: {
        unusedOptionalUrl: jsonurl,
     }
   });
}

function load_total_sales_chart_by_item()
{
	$.jqplot.config.enablePlugins = true;
	var ajaxDataRenderer = function(url, plot, options) {
    var ret = null;
    $.ajax({    
      async: false,
      url: url,
      dataType:"json",
      success: function(data) {
        ret = data;
      }
    });
    return ret;
    };
    
    var jsonurl = ajax_url+"/?action=chartByItem&tbl=chart&currentController="+$("#currentController").val();
    jsonurl+= addValidationRequest();
          
    var plot2 = $.jqplot('total_sales_chart_by_item', jsonurl,{
     animate: true,
     title: js_lang.lastItemSales,
     seriesDefaults:{
            renderer:$.jqplot.BarRenderer,
            pointLabels: { show: true },
            rendererOptions:{  varyBarColor: true }
     },
       axesDefaults: {
        tickRenderer: $.jqplot.CanvasAxisTickRenderer ,
        tickOptions: {
          angle: -30,
          fontSize: '10pt',
        }
    },
     grid:{
    		drawGridLines: false,
    		gridLineColor: '#cccccc',
    		backgroundColor: "#eee",
    		drawBorder: false,
    		borderColor: '#999999',   
    		borderWidth: 1.0,
    		shadow: false
     },
     axes: {
            xaxis: {
                renderer: $.jqplot.CategoryAxisRenderer,
                //ticks: ticks
            },
            yaxis: {
             tickOptions:{
               formatString: "%#.2f"
             }
           }
     },
     dataRenderer: ajaxDataRenderer,
     dataRendererOptions: {
        unusedOptionalUrl: jsonurl,
     }
   });
}

function merchant_set_ready(status)
{			
	busy(true);
	var params="action=merchantSetReady&status="+status;
	
	params+= addValidationRequest();	
	
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){
        	busy(false);
        	uk_msg(data.msg);
        	if (data.code==1){    
        		$("#notice-merchant-stats").remove();             		
        	} else {              		
        	}        	        	
        }, 
        error: function(){	        	        	
        	busy(false);        	
        }		
    });
}

function get_merchant_status()
{				
	busy(true);
	var params="action=merchantStatus&currentController="+$("#currentController").val();
	
	params+= addValidationRequest();	
	
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){
        	busy(false);        	
        	dump(data.details);
        	if (data.code==1){    
        		if (data.msg==2){        			
        			$('.is_ready').prop('checked', true);           			
        		} else {
        			$('.is_ready').prop('checked', false);   
$('.notice-wrap').before("<div id=\"notice-merchant-stats\" class=\"uk-badge uk-badge-danger\">"+js_lang.trans_17+"</div>");
        		}
        		$(".merchant-status").text(js_lang.Status+" "+data.details.display_status);
        		if (data.details.status=="expired"){
        			$(".merchant-status").addClass("uk-badge uk-badge-danger");
        			
        			$.UIkit.notify({
	                  message :js_lang.merchantStats,
	                  pos:'bottom-right',
	                  status:'danger',
	                  timeout :8500,	   
	                });	    	   	   	  
        			
        		} else if(data.details.status=="active"){
        			$(".merchant-status").addClass("uk-badge uk-badge-success");
        		} else{
        			$(".merchant-status").addClass("uk-badge uk-badge-notification");
        		}        
        		        		        		
        		if ( data.details.is_commission==2){
        			$(".merchant-status").attr("href","javascript:;");
        		}
        				
        	} else {              		
        		$('.is_ready').prop('checked', false);   
        	}        	        	
        	$('.is_ready').iCheck('update'); 
        }, 
        error: function(){	        	        	
        	$('.is_ready').prop('checked', false);   
        	busy(false);        	
        }		
    });
}

jQuery(document).ready(function() {	
	
	if( $('#frm-creditcard').is(':visible') ) {	
       loadCreditCardListMerchant();
	}
	
   $( document ).on( "click", ".cc-add", function() {    	  
   	   $(".cc-add-wrap").slideToggle("fast");
   });   
	
   jQuery.fn.exists = function(){return this.length>0;}
   
   if ( $("#payuForm").exists() ){
    	if ( $("#hash").val()=="" ){    		    	
    	} else {
    		$(".uk-button").attr("disabled",true);    		
    		$(".uk-button").css({ 'pointer-events' : 'none' });
    		$("#payuForm").submit();
    	}
   }
   if ( $("#payu_status").exists() ){
    	$(".uk-button").attr("disabled",true);    		
        $(".uk-button").css({ 'pointer-events' : 'none' });
   }    
   
   if ( $("#merchant").exists() ){
        getGoogleCordinateStatus();
   }
   
   if ( $(".big-textarea").exists() ){
   	    $(".big-textarea").jqte();
   }
      
   $( document ).on( "click", ".add_new_holiday", function() {    	  
       var t='';
       t+='<div class="holiday_row">';
       t+='<input type="text" name="merchant_holiday[]" class="j_date small_date" >';
       t+='<a href="javascript:;" class="remove_holiday"><i class="fa fa-minus-square"></i></a>';
       t+='</div>';
   	   $(".holiday_list").append(t);
   	   initDate();
   });   
      
   $( document ).on( "click", ".remove_holiday", function() {    	  
   	  var t=$(this).parent();
   	  t.remove();
   });   
   	
   /*$( document ).on( "click", ".view-details", function() {    	  
   	   var where=$(this).data("where");   	   
   	   var mtid=$(this).data("id");
   	   var and=$(this).data("and");
   	   dump(where);
   	   dump(and);
   	   dump(mtid);
   	   var url=admin_url+"/merchantcommissiondetails/mtid/"+mtid;
   	   url+="/where/"+encodeURIComponent(where);
   	   url+="/and/"+encodeURIComponent(and);
   	   dump(url);
   	   window.location.replace(url);
   });   */
   
   $( document ).on( "change", "#sms_package_id", function() {    	
       var selected=$(this).val();
       getPackageInformation(selected);
   });
   
}); /*END DOCU*/

function loadCreditCardListMerchant()
{
	var htm='';
	var params="action=loadCreditCardListMerchant&merchant_id="+$("#merchant_id").val();
	params+= addValidationRequest();	
	
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      	
    	if (data.code==1){    		    		    		    	
    		$.each(data.details, function( index, val ) {
    			$(".uk-list-cc li").remove(); 
    			htm+='<li>';
	              htm+='<div class="uk-grid">';
	                htm+='<div class="uk-width-1-2">'+val.credit_card_number+'</div>';
	                htm+='<div class="uk-width-1-2">&nbsp;<input class="icheck" type="radio" name="cc_id" class="cc_id" value="'+val.mt_id+'"></div>';
	              htm+='</div>';
	            htm+='</li>';
    		});
    		$(".uk-list-cc").append(htm);
    		$(".cc-add-wrap").hide();
    		
    		$('.icheck').iCheck({
	          checkboxClass: 'icheckbox_minimal',
	          radioClass: 'iradio_flat'
	       });
    		
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });
}

function geocode_address(address)
{		
	$("#google_map_wrap").show();
	var geocoder;
    var map;
    geocoder = new google.maps.Geocoder(); 
    var mapOptions = {
	   scrollwheel: false,	
	   zoom: 18,
	   //center: latlng,
	   mapTypeId: google.maps.MapTypeId.ROADMAP
	 }
	 map = new google.maps.Map(document.getElementById('google_map_wrap'), mapOptions);
	 
	 geocoder.geocode( { 'address': address}, function(results, status) {
	 	  //dump(results);
	 	  ////console.debug(status);
	 	  /*//console.debug(results[0].geometry.location.k);
	 	  //console.debug(results[0].geometry.location.B);*/
	 	  if (status == google.maps.GeocoderStatus.OK) {
	 	  	
	 	  	  var result_lat=results[0].geometry.location.lat();
	 	  	  var result_lng=results[0].geometry.location.lng();
	 	  	  
	 	  	  $("#merchant_latitude").val(result_lat);
	 	  	  $("#merchant_longtitude").val(result_lng);
	 	  	  
	 	  	  /*$("#merchant_latitude").val(results[0].geometry.location.k);
	 	  	  if (typeof results[0].geometry.location.B === "undefined" || results[0].geometry.location.B ) { 
	 	  	  	 $("#merchant_longtitude").val(results[0].geometry.location.D);
	 	  	  } else {
	 	  	     $("#merchant_longtitude").val(results[0].geometry.location.B);
	 	  	  }*/
		      map.setCenter(results[0].geometry.location);
			  var marker = new google.maps.Marker({    	
			     map: map,
			     position: results[0].geometry.location,
			     draggable:true
			  });			
			  
			   google.maps.event.addListener(marker,'drag',function(event) {			   	   
                   $("#merchant_latitude").val( event.latLng.lat() );                   
                   $("#merchant_longtitude").val( event.latLng.lng() );
               }); 
			    
	 	  } else {
	 	  	 uk_msg(status);
	 	  }
	 });
}

function getGoogleCordinateStatus()
{	
	var params="action=getGoogleCordinateStatus";
	params+= addValidationRequest();	
	
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    success: function(data){ 
    	busy(false);      	
    	if (data.code==2){  
    		$.UIkit.notify({
	         message :data.msg,
	         pos: 'bottom-right',
	         status:'warning',
	         timeout :5000,	   
	        });	    	   	   	      		    	
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });	
}

function dump(data)
{
	console.debug(data);
}

function get_booking()
{		
	var params="action=getNewBooking";
	params+= addValidationRequest();	
	
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){      
        	if (data.code==1){        		        	
        		if( $('.uk-notify').is(':visible') ) {           			
        		} else {      
        			if ( $("#alert_off").val()=="" ) {
        			    my_notification.play(); 
        			}
        			$.UIkit.notify({		       	   	   	       	   	  
		       	   	   message : data.msg
		       	    }); 	       	        
        		}
        	}
        }, 
        error: function(){        	
        }		
    });
}

function gallery(data)
{	
	var img='';
	$(".preview").show();
	img+='<li>';
	img+="<img src=\""+upload_url+"/"+data.details.file+"\" alt=\"\" title=\"\" class=\""+data.details.id+" uk-thumbnail uk-thumbnail-mini\" >";
	img+="<input type=\"hidden\" name=\"photo[]\" value=\""+data.details.file+"\" class=\""+data.details.id+"\" >";
	img+="<p class=\""+data.details.id+"\"><a href=\"javascript:rm_gallery('"+data.details.id+"');\">"+js_lang.removeFeatureImage+"</a></p>";
	img+='</li>';
	$(".image_preview").append(img);
}

function rm_gallery(id)
{	
	dump(id);
	$("."+id).remove();
}

function initDate()
{
	jQuery(".j_date").datepicker( { dateFormat: 'yy-mm-dd' , changeMonth: true, changeYear: true ,
	   yearRange: "-10:+10",
	   prevText: js_lang.datep_1,
		nextText: js_lang.datep_2,
		currentText: js_lang.datep_3,
		monthNames: [js_lang.January,js_lang.February,js_lang.March,js_lang.April,js_lang.May,js_lang.June,
		js_lang.July,js_lang.August,js_lang.September,js_lang.October,js_lang.November,js_lang.December],
		monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
		js_lang.Jul, js_lang.Aug, js_lang.Sep, js_lang.Oct, js_lang.Nov, js_lang.Dec],
		dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
		dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		dayNamesMin: [js_lang.Su,js_lang.Mo,js_lang.Tu,js_lang.We,js_lang.Th,js_lang.Fr,js_lang.Sa],						
		isRTL: false
	});	  
}

function getPackageInformation(package_id)
{
	busy(true);
	var params="action=getPackageInformation&package_id="+package_id;
	params+= addValidationRequest();	
	
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){     
        	busy(false); 
        	if (data.code==1){          		
        		$("#sms_limit").val(data.details);
        	} else if ( data.code==3){        		
        		$("#sms_limit").val('');
        	} else {
        		uk_msg(data.msg);
        	}
        }, 
        error: function(){        
        	busy(false);	
        }		
    });
}


jQuery(document).ready(function() {	
	
	if ( $(".commission_total_1").exists() ){
		getCommissionTotal();
	}
	
	if ( $(".merchant_total_balance").exists() ){
		getMerchantBalance();
	}
	
	$( document ).on( "click", ".remove_notice", function() {    	  
		 busy(true);
   	     var params="action=removeNotice";
   	     params+= addValidationRequest();	
		 $.ajax({    
	        type: "POST",
	        url: ajax_url,
	        data: params,
	        dataType: 'json',       
	        success: function(data){   	        
	        	busy(false);
	        	if (data.code==1){
	        		$(".merchant_notice").remove();
	        	}
	        }, 
	        error: function(){        	        	
	        	busy(false);
	        }		
	    });
    });   
	
    
    $( document ).on( "click", ".li-click", function() {    	      	
    	$(".li-click").removeClass("active");
    	$(this).addClass("active");
    	$("#payment_type").val(  $(this).data("id") );
    	dump( $(this).data("id") );
    	
    	if ( $(this).data("id") =="single"){
    		$("#amount").attr("data-validation","required");
    	} else {
    		$("#amount").removeAttr("data-validation");
    	}
    });   
    $( document ).on( "click", ".li-click2", function() {    	      	
    	$(".li-click2").removeClass("active");
    	$(this).addClass("active");    	    	
    	$("#payment_method").val(  $(this).data("id") );
    	
    	    	
    	$("#minimum_amount").val( $(this).data("minimum") );
    	
    	if ( $(this).data("id") == "paypal"){
    		$(".paypal-account-wrap").slideDown();
    		$("#account").attr("data-validation","required");
    		$("#account_confirm").attr("data-validation","required");
    		
    		
    		$(".bank-info-wrap").slideUp();
    		bankRequired(false);
    		
    	} else {
    		$(".paypal-account-wrap").slideUp();
    		$("#account").removeAttr("data-validation");
    		$("#account_confirm").removeAttr("data-validation");
    		
    		$(".bank-info-wrap").slideDown();    
    		bankRequired(true);		
    	}
    });   
    
    $( document ).on( "click", ".togle-withdrawal", function() {    	      	
        $(".withdrawal-info").slideToggle();
    });   
    
    
    $( document ).on( "click", ".payout_action", function() {    	      	
    	var status=$(this).data("status");
    	var withdrawal_id=$(this).data("id");
    	var ans=confirm(js_lang.trans_18);
    	if (ans){
    		payoutChangeStatus(status,withdrawal_id);
    	}
    });   
    
    if ( $("#w-list").exists() ){
    	var selected=$("#w-list").val();    	
    	$(".w-list."+selected).addClass("uk-button-primary");
    }
    
    if ( $("#wd_payout_alert").exists() ){
    	var wd_payout_alert=$("#wd_payout_alert").val();    	
    	if ( wd_payout_alert=="2"){    		
    		new_order_notify = setInterval(function(){wdPayoutNotification()}, notification_interval+3000);
    	}
    }    
        
    $( document ).on( "click", ".test-email", function() {    	      	
        var params="action=testEmail&backend=true"+"&currentController="+
		$("#currentController").val()+"&tbl=testEmail";
        open_fancy_box(params);
    });
    
    /*if( jQuery('#spicydish').is(':visible') ) {    	
       createUploader('spicydish','spicydish');
    }*/     
    
    $( document ).on( "click", ".view-bank-info", function() {    	      	
    	var withdrawal_id=$(this).data("id");
    	var params="action=viewBankInfo&backend=true"+"&currentController="+
		$("#currentController").val()+"&tbl=bankinfo&id="+withdrawal_id;
        open_fancy_box(params);
    });	
    
    
    $( document ).on( "click", ".add-table-rate", function() {    	      	
    	var count=$(".distance_from").length+1;
    	dump(count);
    	var html='';
    	html+="<tr class=\"shipping-row-"+count+"\">";
    	  html+="<td>";
    	  html+=$(".shipping-col-1").html();
    	  html+="</td>";
    	  html+="<td>";
    	  html+=$(".shipping-col-2").html();
    	  html+="</td>";
    	  html+="<td>";
    	  html+=$(".shipping-col-3").html();
    	  html+="</td>";
    	  
    	  html+="<td>";
    	  html+="<a href=\"javascript:;\" class=\"shipping-remove\" data-id=\""+count+"\"><i class=\"fa fa-times\"></i></a>";
    	  html+="</td>";
    	  
    	html+="</tr>";
    	$('.table-shipping-rates tr:last').after(html);
    });	
    
    
    $( document ).on( "click", ".shipping-remove", function() {    	      	
    	var id=$(this).data("id");    	
    	$(".shipping-row-"+id).remove();
    });	
    
    
	$( document ).on( "click", ".close-receipt", function() {
		close_fb();
	});
	
	if ( $(".mobile_inputs").exists()){
		 $(".mobile_inputs").intlTelInput({      
	        autoPlaceholder: false,
	        defaultCountry: $("#country_code").val(),    
	        autoHideDialCode:true,    
	        nationalMode:false,
	        autoFormat:false,
	        utilsScript: sites_url+"/assets/vendor/intel/lib/libphonenumber/build/utils.js"
	     });
	}
	
}); /*end docu*/

function getCommissionTotal()
{
	$(".commission_loader").html('<i class="fa fa-spinner fa-spin"></i>');
	var params="action=getCommissionTotal";
	
	params+= addValidationRequest();
	
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){   
        	$(".commission_loader").html('');          	
        	$(".commission_total_3").html(data.details.total_com);
        	$(".commission_total_2").html(data.details.total_today);
        	$(".commission_total_1").html(data.details.total_last);
        }, 
        error: function(){        
        	$(".commission_loader").html('error');
        }		
    });
}

function getMerchantBalance()
{
	$(".commission_loader").html('<i class="fa fa-spinner fa-spin"></i>');
	var params="action=getMerchantBalance";
	params+= addValidationRequest();	
	
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){   
        	$(".commission_loader").html('');          	
        	$(".merchant_total_balance").html(data.details);        	
        }, 
        error: function(){        
        	$(".commission_loader").html('error');
        }		
    });
}

function bankRequired(is_required)
{
	if ( is_required ){
		$("#account_name").attr("data-validation","required");
		$("#bank_account_number").attr("data-validation","required");
		$("#swift_code").attr("data-validation","required");
		$("#bank_name").attr("data-validation","required");
		$("#bank_country").attr("data-validation","required");		
	} else {
		$("#account_name").removeAttr("data-validation");
		$("#bank_account_number").removeAttr("data-validation");
		$("#swift_code").removeAttr("data-validation");
		$("#bank_name").removeAttr("data-validation");
		$("#bank_country").removeAttr("data-validation");
	}
}

function payoutChangeStatus(status,withdrawal_id)
{
	var params="action=payoutChangeStatus&status="+status+"&withdrawal_id="+withdrawal_id;
	params+= addValidationRequest();	
	
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){           	
        	if (data.code==1){
        		table_reload();
        	} else {
        		uk_msg_sucess(data.msg);
        	}
        }, 
        error: function(){        
        	uk_msg("Error");
        }		
    });
}

var ajaxwdPayoutNotification;

function wdPayoutNotification()
{
	if (ajaxwdPayoutNotification){
		ajaxwdPayoutNotification.abort();
	}
	
	var params="action=wdPayoutNotification";
	params+= addValidationRequest();	
	
	ajaxwdPayoutNotification = $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){           	        	
        	if (data.code==1){
        		uk_msg(data.msg);
        	}
        }, 
        error: function(){                	
        }		
    });
}

function spicydish(data)
{
	var img='';	
	$(".preview_spicydish").show();
	img+="<img src=\""+upload_url+"/"+data.details.file+"\" alt=\"\" title=\"\" class=\"uk-thumbnail\" >";
	img+="<input type=\"hidden\" name=\"spicydish\" value=\""+data.details.file+"\" >";
	img+="<p><a href=\"javascript:rm_spicydish_preview();\">"+js_lang.removeFeatureImage+"</a></p>";
	$(".image_preview_spicydish").html(img);
}

function rm_spicydish_preview()
{
	$(".image_preview_spicydish").html('');
}

/** two flavors options */
jQuery(document).ready(function() {	
		
	$( document ).on( "click", ".two_flavors", function() {
		var value=$(".two_flavors:checked").val();
		dump(value);		
		show_hide_flavors(value);
	});
	
	if ( $(".two_flavors").exists() ){
		var value=$(".two_flavors:checked").val();		
		show_hide_flavors(value);
	}
		
	$( document ).on( "change", ".two_flavors_position", function() {
		var value=$(this).val();		
		dump(value);
		var data_id=$(this).data("id");
		dump(data_id);
		if ( value=="right" || value=="left"){
			$("#multi_option_"+data_id).val("one");
		}
	});
		
    $( document ).on( "change", ".multi_option", function() {		
		var data_id=$(this).data("id");
		dump(data_id);
		var value=$(this).val();		
		dump(value);
		if ( $("#two_flavors_position_"+data_id).is(':visible')){
			var value2=$("#two_flavors_position_"+data_id).val();
			dump(value2);
			if ( value2=="right" || value2=="left"){
				if ( value=="multiple" || value=="custom"){
					$(this).val("one");
					$("#multi_option_value_"+data_id).hide();
				}
			}
		}
	});
	
	
	$( document ).on( "click", ".test-sms", function() {
		var params="action=testSms&backend=true"+"&currentController="+
		$("#currentController").val()+"&tbl=backend";
        open_fancy_box(params);
	});
	
}); /*end docu*/

function show_hide_flavors(value)
{
	if ( value==2){
		$(".two_flavors_position").show();
	} else {
		$(".two_flavors_position").hide();
	}
}

$.validate({ 	
	language : jsLanguageValidator,
    form : '#forms-normal',    
    onError : function() {      
    },
    onSuccess : function() {     
      return true;
    }  
});


/*START version 2.1.1*/

jQuery(document).ready(function() {	
	
	$( document ).on( "click", ".admin-select-access", function() {
		$('.admin-acess').iCheck('check');
	});
	
	$( document ).on( "click", ".admin-unselect-access", function() {
		$('.admin-acess').iCheck('uncheck');
	});
	
	/*if( jQuery('#rphoto').is(':visible') ) {    			
         createUploader('rphoto','rphoto');
    }*/     
	
    
    $( document ).on( "click", ".voucher-details", function() {
        var params="action=viewVoucherDetails&backend=true"+"&currentController="+
		$("#currentController").val()+"&voucher_name="+encodeURIComponent($(this).data("id"))+"&tbl=viewVoucherDetails";
        open_fancy_box(params);
    });	                    
    
}); /*end class*/


function rphoto(data)
{
	var img='';	
	$(".rc_preview").show();
	img+="<img src=\""+upload_url+"/"+data.details.file+"\" alt=\"\" title=\"\" class=\"uk-thumbnail uk-thumbnail-mini\" >";
	img+="<input type=\"hidden\" name=\"website_receipt_logo\" value=\""+data.details.file+"\" >";
	img+="<p><a href=\"javascript:rc_rm_preview();\">"+js_lang.removeFeatureImage+"</a></p>";
	$(".rc_image_preview").html(img);	
}

function rc_rm_preview()
{
	$(".rc_image_preview").html('');
}

jQuery(document).ready(function() {	
	if ( $(".j_date_normal").exists()){
	    jQuery(".j_date_normal").datepicker({ 
	    	dateFormat: 'yy-mm-dd' ,    	
	        altFormat: "yy-mm-dd",       
	    	changeMonth: true,
	    	changeYear: true ,	   
		    minDate: 0,
		    prevText: js_lang.datep_1,
			nextText: js_lang.datep_2,
			currentText: js_lang.datep_3,
			monthNames: [js_lang.January,js_lang.February,js_lang.March,js_lang.April,js_lang.May,js_lang.June,
			js_lang.July,js_lang.August,js_lang.September,js_lang.October,js_lang.November,js_lang.December],
			monthNamesShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
			js_lang.Jul, js_lang.Aug, js_lang.Sep, js_lang.Oct, js_lang.Nov, js_lang.Dec],
			dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
			dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
			dayNamesMin: [js_lang.Su,js_lang.Mo,js_lang.Tu,js_lang.We,js_lang.Th,js_lang.Fr,js_lang.Sa],
			isRTL: false		
		});	
	}
	
	if ( $("#disabled_voucher_code").exists()){		
		$( document ).on( "click", "#voucher_name", function() {
			$(this).blur();
		});
	}	
	
});		
/*END version 2.1.1*/


/*START version 2.4*/
jQuery(document).ready(function() {	
	
	$( document ).on( "click", ".not_available", function() {        
		 busy(true);   
		 var params="action=UpdateItemAvailable&item_id="+$(this).val()+"&checked="+$(this.checked).length;
		 params+= addValidationRequest();	
		 
		 $.ajax({    
	        type: "POST",
	        url: ajax_url,
	        data: params,
	        dataType: 'json',       
	        success: function(data){           	        	
	        	busy(false);   
	        	if (data.code==1){
	        		uk_msg_sucess(data.msg);
	        	} else {
	        		uk_msg(data.msg);
	        	}
	        }, 
	        error: function(){                	
	        	busy(false); 
	        }		
	    });
    });
	
});

$.validate({ 	
	language : jsLanguageValidator,
    form : '#forms2',    
    onError : function() {      
    },
    onSuccess : function() {           
      form_submit('forms2');
      return false;
    }  
});
/*END version 2.4*/

/** START ADDED CODE VERSION 2.5*/

jQuery(document).ready(function() {	
	
	$( document ).on( "click", ".view-order-history", function() {    	       	 	
	   	var params="action=viewOrderHistory&tbl=viewReceipt&id="+ $(this).data("id")+"&backend=true"+"&currentController="+$("#currentController").val();
	    open_fancy_box(params);                     
	});	
	
   /*if ( $("#foodgallery").exists()){   
       createUploader('foodgallery','foodGallery');
    }*/       	

});	 /*ready*/

function foodGallery(data)
{
	var img='';
	$(".preview").show();
	img+='<li>';
	img+="<img src=\""+upload_url+"/"+data.details.file+"\" alt=\"\" title=\"\" class=\""+data.details.id+" uk-thumbnail uk-thumbnail-mini\" >";
	img+="<input type=\"hidden\" name=\"gallery_photo[]\" value=\""+data.details.file+"\" class=\""+data.details.id+"\" >";
	img+="<p class=\""+data.details.id+"\"><a href=\"javascript:rm_foodGallery('"+data.details.id+"');\">"+js_lang.removeFeatureImage+"</a></p>";
	img+='</li>';
	$(".foodgallery_preview").append(img);
}

function rm_foodGallery(id)
{	
	$("."+id).remove();
}

/** END ADDED CODE VERSION 2.5*/


/* ADDED CODE VERSION 3.0 */

jQuery(document).ready(function() {
	
	/*if ( $("#mobilelogo").exists() ) {   	
       createUploader('mobilelogo','mobileLogo');
    }*/
    
    $('.website_enabled_mobile_verification').on('ifChecked', function(event){
    	$('.theme_enabled_email_verification').iCheck('uncheck');
    });
    $('.theme_enabled_email_verification').on('ifChecked', function(event){
    	$('.website_enabled_mobile_verification').iCheck('uncheck');
    });
	
}); /*end doc*/

function mobileLogo(data)
{
	var img='';
	img+="<img id=\"logo-small\" src=\""+upload_url+"/"+data.details.file+"\" alt=\"\" title=\"\" class=\"uk-thumbnail\" >";
	img+="<input type=\"hidden\" name=\"mobilelogo\" value=\""+data.details.file+"\" >";
	img+="<p><a href=\"javascript:rmMobileLogo();\">"+js_lang.removeFeatureImage+"</a></p>";
	$(".MobileLogoPreview").html(img);
}

function rmMobileLogo()
{
	$(".MobileLogoPreview").html('');
}

/* END ADDED CODE VERSION 3.0 */

jQuery(document).ready(function() {	
		
	$( document ).on( "click", ".show-cc-details", function() {
		var id=$(this).data("id");
		var params="action=showCCDetails&tbl=showCCDetails&backend=true"+
		"&currentController="+$("#currentController").val()+
		"&id="+id;
        open_fancy_box(params);       
	});
	
}); /*END DOCU*/


var admin_neworder;


jQuery(document).ready(function() {	
	
	if ( $("#currentController").val()=="admin" ){
		if (order_notification!=1){
			 new_order_notify = setInterval(function(){getAdminNewOrder()}, notification_interval+4000);					 			 
		}		
	}
	
	if ( $(".min-order-table").exists() ){
		$( document ).on( "click", ".add-table-min-order-row", function() {    	      	
    	var count=$(".distance_from").length+1;
	    	dump(count);
	    	var html='';
	    	html+="<tr class=\"min-table-row-"+count+"\">";
	    	  html+="<td>";
	    	  html+=$(".shipping-col-1").html();
	    	  html+="</td>";
	    	  html+="<td>";
	    	  html+=$(".shipping-col-2").html();
	    	  html+="</td>";
	    	  html+="<td>";
	    	  html+=$(".shipping-col-3").html();
	    	  html+="</td>";
	    	  
	    	  html+="<td>";
	    	  html+="<a href=\"javascript:;\" class=\"min-order-table-remove\" data-id=\""+count+"\"><i class=\"fa fa-times\"></i></a>";
	    	  html+="</td>";
	    	  
	    	html+="</tr>";
	    	$('.min-order-table tr:last').after(html);
	    });	
	    
	    $( document ).on( "click", ".min-order-table-remove", function() {
	    	var id = $(this).data("id");
	    	dump(id);
	    	$(".min-table-row-"+id).remove();
	    });	    
	}	
	
}); /*END DOCU*/

function getAdminNewOrder()
{
	var params="action=getAdminNewOrder";
	params+= addValidationRequest();	
	
	 $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        success: function(data){      
        	if (data.code==1){        		   
        		if ( $(".admin-neworders").exists() ) {
        		    epp_table3.fnReloadAjax(); 
        		}
        		if( $('.uk-notify').is(':visible') ) {           			
        		} else {              			
        			
        			if (order_notification_sounds!=1){        			           			   
        			   my_notification.play(); 
        			}
        			
        			$.UIkit.notify({
		       	   	   message : data.msg+" "+js_lang.NewOrderStatsMsg+data.details
		       	    }); 	       	        
        		}
        	}
        }, 
        error: function(){        	
        }		
    });
}


function numberFormat(number, decimals, dec_point, thousands_sep) 
{
  number = (number + '')
    .replace(/[^0-9+\-Ee.]/g, '');
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
      var k = Math.pow(10, prec);
      return '' + (Math.round(n * k) / k)
        .toFixed(prec);
    };
  // Fix for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.');
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1)
      .join('0');
  }
  return s.join(dec);
}


var printing_window;

jQuery(document).ready(function() {	
		
    $( document ).on( "keyup", ".food_price", function() {		
		var merchant_tax= parseFloat($("#merchant_tax").val());
		var price=  parseFloat($(this).val());
		var parent=$(this).parent().parent();		
		var div = parent.find(".price_with_tax");
		var total_price = parseFloat(price) + ( price * merchant_tax );
		dump(total_price);
		dump(div);
		div.html(  numberFormat(total_price,price_decimal_place,price_decimal_separator,price_thousand_separator) );
	});
	
	$( document ).on( "keyup", ".addon_price", function() {		
		var price =  parseFloat($(this).val());
		var merchant_tax= parseFloat($("#merchant_tax").val());
		var price=  parseFloat($(this).val());
		var total_price = parseFloat(price) + ( price * merchant_tax );
		$(".total_addon_with_tax").html( numberFormat(total_price,price_decimal_place,price_decimal_separator,price_thousand_separator) );
	});
		
	$( document ).on( "click", ".print_thermal", function() {
		var id = $("#printing_order_id").val();		
		var h = window.outerHeight/1.5;				
		var current_panel = $("#currentController").val();
		var receipt_width = 250;
		printing_window = popupwindow( sites_url+"/"+current_panel+"/print?id=" + id +"&backend=true" ,'', receipt_width , h );
		var isChrome = /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
		
		if ( isChrome ){			
		 	printing_window.onload = function() { printing_window.print();}		
		} else {			
			printing_window.onload = function() { printing_window.print(); printing_window.close(); }		
		}
	});
	
	$( document ).on( "click", ".template_actions", function() {
		var params='key=' + $(this).data("key") +"&label=" +  encodeURIComponent($(this).data("label"));
		params+="&tag_email=" + $(this).data("tag_email");
		params+="&tag_sms=" + $(this).data("tag_sms");
		params+="&sms=" + $(this).data("sms");
		
		if(!empty($(this).data("push"))){
		   params+="&push=" + $(this).data("push");
		} 
		if(!empty($(this).data("tag_push"))){
		   params+="&tag_push=" + $(this).data("tag_push");
		} 
		
		params+="&email=" + $(this).data("email");
		params+="&method=get";
		params+="&lang="+lang;		
		openFancyboxWindow('template', params );
	});
			
	$( document ).delegate( "#template_lang_selection", "change", function() {
		if ( $(this).val() !=0){						
			callAjax("loadETemplateByLang", "lang="+ $(this).val() + "&key="+ $("#key").val() , $("#template-submit") );
		}
	});
		
	
}); /*END DOCU*/

function popupwindow(url, title, w, h) {
    var y = window.outerHeight / 2 + window.screenY - ( h / 2)
    var x = window.outerWidth / 2 + window.screenX - ( w / 2)
    return window.open(url, title, 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=no, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + y + ', left=' + x);
} 

function openFancyboxWindow(action, params)
  { 

  	params+= addValidationRequest(); 	
  	params+="&method=get";
	var URL=ajax_admin + "/" + action + "/?"+params;	
	
	$.fancybox({        
		maxWidth:1000,
		closeBtn : false,          
		autoSize : true,
		padding :0,
		margin :2,
		modal:false,
		type : 'ajax',
		href : URL,
		openEffect :'elastic',
		closeEffect :'elastic',		
	});   
}

function empty(data)
{
	if (typeof data === "undefined" || data==null || data=="" ) { 
		return true;
	}
	return false;
}


$.validate({ 	
	language : jsLanguageValidator,
    form : '#newforms',    
    onError : function() {      
    },
    onSuccess : function() {           
      var params=$("#newforms").serialize();	
      callAjax( $("#action").val(), params , $("#newforms button") ) ;
      return false;
    }  
});

var ajax_request;

//mycall
function callAjax(action,params,button)
{
	
	dump("MYCALL");
	dump(action);
	dump(params);
	dump(button);
	
	if ( !empty(button) ){
		button.css({ 'pointer-events' : 'none' });
	}
	
	
	switch (action){
		case "loadETemplateByLang":
		break;
		
		default:
		params+="&lang="+lang;
		break;
	}
	
	params+= addValidationRequest();
		
	ajax_request = $.ajax({
		url: ajax_admin+"/"+action, 
		data: params,
		type: 'post',           		
		dataType: 'json',
		timeout: 7000,		
	 beforeSend: function() {
	 	dump("before=>");
	 	dump( ajax_request );
	 	if(ajax_request != null) {
	 	   ajax_request.abort();	 	   
	 	   busy(false);	 	   
	 	   if ( !empty(button) ){
	 	      button.css({ 'pointer-events' : 'auto' });
	 	   }
	 	} else {
	 	   switch (action){	
		 	  	case "getNotification":
		 	  	//
		 	  	break;
		 	  	
		 	  	default:
		 	  	busy(true);	 	  
		 	  	break;
	 	    }	 	     
	 	}
	 },
	 complete: function(data) {					
		ajax_request= (function () { return; })();
		dump( 'Completed');
		dump(ajax_request);
		busy(false);	
		if ( !empty(button) ){
		   button.css({ 'pointer-events' : 'auto' });
		}
	 },
	 success: function (data) {	  
	 	
	 	 dump(data);
	 	 if (data.code==1){
	 	 	 switch (action){
	 	 	 	case "loadETemplateByLang":
	 	 	 	$("#email_subject").val( data.details.subject );
	 	 	 	$("#email_content").val( data.details.content );
	 	 	 	$("#sms_content").val( data.details.sms );
	 	 	 	$("#push_content").val( data.details.push );
	 	 	 	$("#push_title").val( data.details.push_title );
	 	 	 	break;
	 	 	 	
	 	 	 	case "loadCountryDetails":
	 	 	 	 $(".location-list").html(data.details);
	 	 	 	 	 	 	 	 
                 $(".area-list").sortable({
	 			  	 update: function( event, ui ) {	 			  	  	  
	 			  	  	  var ids='';
	 			  	  	  $.each( $(this).find("li") , function() { 	 			  	  
	 			  	  	  	  ids+= $(this).data("area_id") + ",";
	 			  	  	  });
	 			  	  	  callAjax("SortArea","ids="+ids);
	 			  	  }
	 			  });
	 			  
	 			  $(".state-ul").sortable({
	 			  	 update: function( event, ui ) {	 			  	  	  
	 			  	  	  var ids='';
	 			  	  	  $.each( $(this).find("li.state-li") , function() { 	 			  	  
	 			  	  	  	  ids+= $(this).data("state_id") + ",";
	 			  	  	  });
	 			  	  	  callAjax("SortState","ids="+ids);
	 			  	  }
	 			  });
	 	 	 	 
	 	 	 	break;
	 	 	 	
	 	 	 	case "SaveCity":
	 	 	 	case "DeleteCity":
	 	 	 	case "SaveState":
	 	 	 	case "DeleteState":
	 	 	 	case "SaveArea":
	 	 	 	case "DeleteArea":
	 	 	 	close_fb();
	 	 	 	loadCountryDetails();
	 	 	 	break;
	 	 	 	
	 	 	 	case "SortArea":
	 	 	 	case "SortState":
	 	 	 	case "SortCity":
	 	 	 	break;
	 	 	 	
	 	 	 	case "LoadStateList":
	 	 	 	$(".rate_state_id").html(data.details);
	 	 	 	break;
	 	 	 	
	 	 	 	case "LoadCityList":
	 	 	 	$(".rate_city_id").html(data.details);
	 	 	 	break;
	 	 	 	
	 	 	 	case "LoadArea":
	 	 	 	$(".rate_area_id").html(data.details);
	 	 	 	break;
	 	 	 	
	 	 	 	case "SaveRate":
	 	 	 	case "DeleteLocationRates":
	 	 	 	close_fb();
	 	 	 	loadTableRates();
	 	 	 	break;
	 	 	 	
	 	 	 	case "SaveInvoice":
	 	 	 	close_fb();
	 	 	 	table_reload();
	 	 	 	break;
	 	 	 	
	 	 	 	case "LoadTableRates":
	 	 	 	$(".location_table_rates").html(data.details);
	 	 	 	
	 	 	 	 $(".location_table_rates").sortable({
	 			  	 update: function( event, ui ) {	 			  	  	  
	 			  	  	  var ids='';
	 			  	  	  $.each( $(this).find("tr") , function() { 	 			  	  
	 			  	  	  	  ids+= $(this).data("rateid") + ",";
	 			  	  	  });
	 			  	  	  callAjax("SortTableRates","ids="+ids);
	 			  	  }
	 			  });
	 	 	 	
	 	 	 	break;
	 	 	 	
	 	 	 	case "SortTableRates":
	 	 	 	break;
	 	 	 	
	 	 	 	case "LoadReviewComment":
	 	 	 	   $(".comment-review-details-"+data.details.parent_id).html( data.details.html );
	 	 	 	   $(".rcom-"+data.details.parent_id).hide();
	 	 	 	break;
	 	 	 	
	 	 	 	case "DeleteReviewReply":
	 	 	 	 $(".replies-list-"+data.details.id).fadeOut();
	 	 	 	break;
	 	 	 	
	 	 	 	case "requestOrderApproved":
	 	 	 	case "requestOrderDecline":
	 	 	 	  close_fb();	 	 	 	 
	 	 	 	  epp_table2.fnReloadAjax(); 	 	 	 	  	 	 	 	  
	 	 	 	  if (current_panel=="merchant"){
	 	 	 	  	  table_reload();
	 	 	 	  }	 	 	 	  
	 	 	 	break;
	 	 	 	
	 	 	 	case "geocode":
	 	 	 	  $("#merchant_latitude").val( data.details.lat );
	 	 	 	  $("#merchant_longtitude").val( data.details.lng );	 	 	 	  
	 	 	 	  mapbox_init_map( data.details.lat, data.details.lng );
	 	 	 	  alert(js_lang.trans_49);
	 	 	 	break;
	 	 	 	
	 	 	 	case "getNotification":
	 	 	 	  $(".system_notification").addClass("uk-badge-danger");
	 	 	 	  $(".system_notification").html( data.details.count);
	 	 	 	  html='';
	 	 	 	  $.each(data.details.error, function( index_error, val_error ) {
	 	 	 	  	html+='<li><a href="javascript:;">'+val_error+'</a></li>';
	 	 	 	  });
	 	 	 	  $(".system_notification_list").html(html);
	 	 	 	break;
	 	 	 	
	 	 	 	default:
	 	 	 	uk_msg_sucess(data.msg);
	 	 	 	break;
	 	 	 }	 	 	 
	 	 } else { // failed
	 	 	switch (action){
	 	 		case "loadETemplateByLang":
	 	 	 	$("#email_subject").val( '' );
	 	 	 	$("#email_content").val( '' );
	 	 	 	$("#sms_content").val( '' );
	 	 	 	$("#push_content").val( '' );
	 	 	 	$("#push_title").val( '' );
	 	 	 	break;
	 	 	 	
	 	 	 	case "LoadStateList":
	 	 	 	$(".rate_state_id").html('');
	 	 	 	break;
	 	 	 	
	 	 	 	case "LoadCityList":
	 	 	 	$(".rate_city_id").html('');
	 	 	 	break;
	 	 	 	
	 	 	 	case "LoadArea":
	 	 	 	$(".rate_area_id").html('');
	 	 	 	break;
	 	 	 	
	 	 	 	case "LoadTableRates":
	 	 	 	$(".location_table_rates").html('');
	 	 	 	break;	 	 	 		 	 	 	
	 	 	 	
	 	 	 	case "getNotification":
	 	 	 	  $(".system_notification").removeClass("uk-badge-danger");
	 	 	 	  $(".system_notification").html( '');
	 	 	 	  html='';	 	 	 	  
	 	 	 	  html+='<li><a href="javascript:;">'+data.msg+'</a></li>';	 	 	 	  
	 	 	 	  $(".system_notification_list").html(html);
	 	 	 	break;
	 	 	 	
	 	 		default:
	 	 	 	uk_msg(data.msg);
	 	 	 	break;
	 	 	}
	 	 }
	 	
	  },
	 error: function (request,error) {	    
	 	busy(false);
	  }
    });    
}

jQuery(document).ready(function() {	
	
	if ( $(".location-list").exists() ){
		loadCountryDetails();
	}
		
	$( document ).on( "click", ".add_state", function() {
		var params="&country_id=" + $(this).data("id");
		params+="&method=get";
		params+="&lang="+lang;		
		openFancyboxWindow('AddState', params );
	});
	$( document ).on( "click", ".edit_state", function() {
		var params="&country_id=" + $(this).data("countryid");
		params+="&state_id="+ $(this).data("stateid");
		params+="&method=get";
		params+="&lang="+lang;		
		openFancyboxWindow('AddState', params );
	});	
		
	$( document ).on( "click", ".add_city", function() {		
		var params="&state_id=" + $(this).data("id");
		params+="&method=get";
		params+="&lang="+lang;		
		openFancyboxWindow('addCity', params );
	});
	$( document ).on( "click", ".edit_city", function() {		
		var params="&id=" + $(this).data("id");		
		params+="&state_id=" + $(this).data("stateid");
		params+="&method=get";
		params+="&lang="+lang;		
		openFancyboxWindow('addCity', params );
	});
	
	$( document ).on( "click", ".delete-city", function() {
		var ans=confirm(js_lang.deleteWarning);        
        if (ans){        	
        	callAjax('DeleteCity', "id="+$(this).data('id') );
        }	   
	});
	$( document ).on( "click", ".delete-state", function() {
		var ans=confirm(js_lang.deleteWarning);        
        if (ans){        	
        	callAjax('DeleteState', "id="+$(this).data('id') );
        }	   
	});
		
	$( document ).on( "click", ".add_area", function() {		
		var params="&city_id=" + $(this).data("cityid");				
		params+="&method=get";
		params+="&lang="+lang;		
		openFancyboxWindow('AddArea', params );
	});
	$( document ).on( "click", ".edit_area", function() {		
		var params="&city_id=" + $(this).data("cityid");				
		params+="&area_id=" + $(this).data("areaid");
		params+="&method=get";
		params+="&lang="+lang;		
		openFancyboxWindow('AddArea', params );
	});	
	$( document ).on( "click", ".delete-area", function() {
		var ans=confirm(js_lang.deleteWarning);        
        if (ans){        	
        	callAjax('DeleteArea', "id="+$(this).data('id') );
        }	   
	});	
	$( document ).on( "click", ".collapse-state-list", function() {
		$(".state-list-li").slideToggle("fast");		
	});
	$( document ).on( "click", ".collapse-state", function() {
	   var parent=$(this).parent().parent();
	   dump(parent);
	   var li=parent.find(".state-list-li");
	   li.slideToggle("fast");
	});
		
	$( document ).on( "click", ".add-new-rates", function() {
		var params='';
		params+="&method=get";
		params+="&lang="+lang;		
		openFancyboxWindow('addNewRates', params );
	});
		
	$( document ).on( "click", ".location_edit", function() {
		var params='rate_id='+$(this).data("id");
		params+="&method=get";
		params+="&lang="+lang;		
		openFancyboxWindow('addNewRates', params );
	});
	
	if ( $(".location_table_rates").exists() ){
		loadTableRates();
	}
	
	$("#location_table_rates").on({	
	   mouseenter: function(){    	    
	    $(this).find(".options").show();
	  },
	   mouseleave: function () {	   
	    $(this).find(".options").hide();
	}},'tbody tr');	    	
			
   $( document ).on( "click", ".location_delete", function() {
   	    var rate_id = $(this).data("id");   	    
   	    var ans=confirm(js_lang.deleteWarning);        
        if (ans){        	
        	callAjax("DeleteLocationRates","rate_id="+rate_id );
        }
   });
      
   if ($("#merchant_type").exists()){   	   
   	   switchMerchantTypDiv( $("#merchant_type").val() );
   	   
   	   $( document ).on( "change", "#merchant_type", function() {   	   
   	   	   switchMerchantTypDiv( $(this).val() );
   	   });
   }
   
   $( document ).on( "click", ".edit_invoice", function() {
   	   openFancyboxWindow("EditInvoice","id="+$(this).data("id"));
   });
      
   $( document ).on( "click", ".invoice_view_history", function() {
   	   openFancyboxWindow("InvoiceHistory","id="+$(this).data("id"));
   });	
   
      
});/* end docu*/

function switchMerchantTypDiv(merchant_type)
{
	if (merchant_type==1){
   	   $(".membership_type_1").show();
   	   $(".membership_type_2").hide();
   	} else {   	   
   	   $(".membership_type_2").show();
   	   $(".membership_type_1").hide();
   	   if (merchant_type==2){
   	   	   $(".invoice_terms_wrap").hide();
   	   } else {
   	   	   $(".invoice_terms_wrap").show();
   	   }
   	}
}

function loadCountryDetails()
{
	callAjax("loadCountryDetails","country_id="+ $("#country_id").val(), '' );
}

function loadStateList(country_id)
{
	if (country_id>0){
	    callAjax('LoadStateList', "country_id="+ country_id );
	} else {
		$(".rate_state_id").html('');
	}
}

function loadCityListx(state_id)
{	
	if (state_id>0){
	    callAjax('LoadCityList', "state_id="+ state_id );
	} else {
		$(".rate_city_id").html('');
	}
}

function loadAreaList(city_id)
{	
	if (city_id>0){
	    callAjax('LoadArea', "city_id="+ city_id );
	} else {
		$(".rate_area_id").html('');
	}
}

function loadTableRates()
{
	callAjax('LoadTableRates');
}


jQuery(document).ready(function() {
	
	$( document ).on( "click", ".review-comment", function() {
		var parent_id=$(this).data("id");
		callAjax('LoadReviewComment', "parent_id="+ parent_id );
	});
		
	$( document ).on( "click", ".delete-reply", function() {
		var id=$(this).data("id");
		var ans=confirm(js_lang.deleteWarning);
        if (ans){
		   callAjax('DeleteReviewReply', "id="+ id );
        }
	});
	
	$( document ).on( "click", ".view-cc-details-sms", function() {
		params="action=viewccdetailsms&id="+ $(this).data("id") + "&tbl=true"+"&currentController="+$("#currentController").val();
		dump(params);
		open_fancy_box(params);
	});
	
}); /*end docu*/


jQuery(document).ready(function() {
	
	$( document ).on( "click", ".extra_charge_add_new_row", function() {
		
		var extra_charge_rows = $(".time_applicable_rows").length;
		
		dump(extra_charge_rows);
		
		html='';
		
		html+='<div class="uk-form-row time_applicable_rows">';
		  html+='<label class="uk-form-label">Time Applicable</label>';
		  html+='<input style="width:100px" id="timepick'+extra_charge_rows+'"  placeholder="Start" value="" name="extra_charge_start_time[]" id="extra_charge_start_time" type="text">  <span>To</span>';
		  
		  html+='<input style="width:100px" id="timepick2'+extra_charge_rows+'" placeholder="End" value="" name="extra_charge_end_time[]" id="extra_charge_end_time" type="text">';
		  
		  html+='<input style="width:100px" class="numeric_only" placeholder="Fee" value="" name="extra_charge_fee[]" id="extra_charge_fee" type="text"> ';
		
		  html+='<a href="javascript:;" class="remove_charge_row">';
		  html+='<i class="fa fa-minus-square"></i>';
		  html+='</a>';
		  
		html+='</div>';
				
		$(".more_charge_row").append(html);
		
		var show_period=false;
		if ( $("#website_time_picker_format").exists() ){		
			if ( $("#website_time_picker_format").val()=="12"){
				show_period=true;
			}
		}		
		jQuery('#timepick'+extra_charge_rows).timepicker({  
				showPeriod: show_period,      
		        hourText: js_lang.Hour,       
				minuteText: js_lang.Minute,  
				amPmText: [js_lang.AM, js_lang.PM]
	    });
	    jQuery('#timepick2'+extra_charge_rows).timepicker({  
				showPeriod: show_period,      
		        hourText: js_lang.Hour,       
				minuteText: js_lang.Minute,  
				amPmText: [js_lang.AM, js_lang.PM]
	    });
		
	});
		
	$( document ).on( "click", ".remove_charge_row", function() {
		$(this).parent().remove();
	});
	
	$( document ).on( "click", ".check_all", function() {
		var addon_id = $(this).val();		
		if($(this).is(':checked')){			
			$(".check_all_"+addon_id).prop( "checked", true );
		} else {
			$(".check_all_"+addon_id).prop( "checked", false );
		}
	});
	
	$( document ).on( "click", ".order_cancel_review", function() {
		order_id = $(this).data('id');
		var params="action=reviewCancelOrder&tbl=order&order_id="+order_id;
        open_fancy_box(params);
	});
	
	
	if ( $("#sau_upload_file").exists() ){						
		sau_progress = $("#sau_upload_file").data("progress");
		sau_progress = $("."+sau_progress);
		sau_preview = $("#sau_upload_file").data("preview");
		
		sau_field = $("#sau_upload_file").data("field");
		
					
		var uploader = new ss.SimpleUpload({
			 button: 'sau_upload_file',
			 url: ajax_admin + "/uploadFile/?"+ addValidationRequest()+"&method=get&field="+sau_field + "&preview=" + sau_preview  ,
			 progressUrl: 'uploadProgress.php',
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
			 	this.setProgressBar(sau_progress);
			 	busy(true);
			 },
			 onComplete: function(filename, response) {			 	 
			 	 busy(false);
			 	 if (!response) {
	                uk_msg( js_lang.upload_failed  );
	                return false;            
	             } else {	        	             	
	             	dump(response);	             	
	             	if( response.code==1){
	             		$("."+sau_preview).html( response.details.preview_html );
	             	} else {
	             		uk_msg( response.msg );
	             	}
	             }	            
			 }
		});
	}
		
	
	if ( $("#saup_upload_file").exists() ){						
		saup_progress = $("#saup_upload_file").data("progress");
		saup_progress = $("."+saup_progress);
		saup_preview = $("#saup_upload_file").data("preview");
		
		saup_field = $("#saup_upload_file").data("field");
						
		var uploader = new ss.SimpleUpload({
			 button: 'saup_upload_file',
			 url: ajax_admin + "/uploadFile/?"+ addValidationRequest()+"&method=get&field="+saup_field + "&preview=" + saup_preview  ,
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
			 	this.setProgressBar(saup_progress);			 	
			 },
			 onComplete: function(filename, response) {			 	 
			 	 busy(false);
			 	 if (!response) {
	                uk_msg( js_lang.upload_failed  );
	                return false;            
	             } else {	        	             	
	             	dump(response);	             	
	             	if( response.code==1){
	             		$("."+saup_preview).html( response.details.preview_html );
	             	} else {
	             		uk_msg( response.msg );
	             	}
	             }	            
			 }
		});
	}

	if ( $("#file_upload").exists() ){						
		file_upload_progress = $("#file_upload").data("progress");
		file_upload_progress = $("."+file_upload_progress);
		file_upload_preview = $("#file_upload").data("preview");
		
		file_upload_field = $("#file_upload").data("field");
						
		var uploader = new ss.SimpleUpload({
			 button: 'file_upload',
			 url: ajax_admin + "/uploadFile/?"+ addValidationRequest()+"&method=get&field="+file_upload_field + "&preview=" + file_upload_preview  ,
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
			 	this.setProgressBar(file_upload_progress);
			 },
			 onComplete: function(filename, response) {			 	 
			 	 busy(false);
			 	 if (!response) {
	                uk_msg( js_lang.upload_failed  );
	                return false;            
	             } else {	        	             	
	             	dump(response);	             	
	             	if( response.code==1){
	             		$("."+file_upload_preview).html( response.details.preview_html );
	             	} else {
	             		uk_msg( response.msg );
	             	}
	             }	            
			 }
		});
	}	
	
	
	if ( $("#file_upload2").exists() ){						
		file_upload_progress2 = $("#file_upload2").data("progress");
		file_upload_progress2 = $("."+file_upload_progress2);
		file_upload_preview2 = $("#file_upload2").data("preview");
		
		file_upload_field2 = $("#file_upload2").data("field");
						
		var uploader = new ss.SimpleUpload({
			 button: 'file_upload2',
			 url: ajax_admin + "/uploadFile/?"+ addValidationRequest()+"&method=get&field="+file_upload_field2 + "&preview=" + file_upload_preview2  ,
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
			 	this.setProgressBar(file_upload_progress);
			 },
			 onComplete: function(filename, response) {			 	 
			 	 busy(false);
			 	 if (!response) {
	                uk_msg( js_lang.upload_failed  );
	                return false;            
	             } else {	        	             	
	             	dump(response);	             	
	             	if( response.code==1){
	             		$("."+file_upload_preview2).html( response.details.preview_html );
	             	} else {
	             		uk_msg( response.msg );
	             	}
	             }	            
			 }
		});
	}		
	
	$( document ).on( "click", ".sau_remove_file", function() {
	    preview = $(this).data("preview");			 
	    $("."+preview).html('');
	});		
	
	$( document ).on( "click", ".request_cancel_approved", function() {	
		 callAjax('requestOrderApproved','order_id=' + $(this).data('id') );
	});
	
	$( document ).on( "click", ".request_cancel_decline", function() {	
		callAjax('requestOrderDecline','order_id=' +  $(this).data('id') );
	});
	
	$( document ).on( "click", ".enabled_sked_all", function() {
		if($(this).is(':checked')){			
			$(".cat_sked").prop( "checked", true );
		} else {
			$(".cat_sked").prop( "checked", false );
		}
	});	
		
});/* end docu*/


$.validate({ 	
	language : jsLanguageValidator,
    form : '#frm_redirect_payment',    
    onError : function() {     	    	 
    },
    onSuccess : function() {     
        
    	busy(true);
    	$("#frm_redirect_payment button").css({ 'pointer-events' : 'none' });    	    		    	
	    return true;	
    }  
});


function addValidationRequest()
{
	var params='';	
	params+="&yii_session_token="+yii_session_token;
	params+="&YII_CSRF_TOKEN="+YII_CSRF_TOKEN;
	return params;
}

admin_cancel_order_handle='';

jQuery(document).ready(function() {
		
	if ( current_panel =="admin" ){
		if(!empty(has_session)){
			if(has_session==1){
		 	   admin_cancel_order_handle = setInterval(function(){getNewCancelOrderAdmin()}, notification_interval+5000);
			}
		}
	}	
	
});/* END DOCU*/

var handle_cancelorder;

function getNewCancelOrderAdmin()
{
	var params='';
	params+= addValidationRequest();	
	
	handle_cancelorder = $.ajax({    
        type: "POST",
        url: ajax_admin+"/getNewCancelOrderAdmin",
        data: params,
        dataType: 'json',       
        beforeSend: function() {
	 	   dump("before=>");
	 	   if(handle_cancelorder != null) {
	 	   	  handle_cancelorder.abort();
	 	   }
	    },
        success: function(data){      
        	if (data.code==1){        		           		
        		if( $('.uk-notify').is(':visible') ) {           			
        		} else {            
        			my_notification.play(); 
        			$.UIkit.notify({
		       	   	   message : data.msg
		       	    }); 	       	        
        		}
        	}
        }, 
        error: function(){        	
        }		
    });
    
    handle_cancelorder.always(function() {        
        handle_cancelorder= (function () { return; })();
        handle_cancelorder=null;          
    });
    
}

var mapbox_handle;
var mapbox_marker;

useMapbox = function(){
	if(map_provider=="mapbox"){
		return true;
	}
	return false;
}

mapbox_init_map = function(lat, lng){
	if(!empty(lat) && !empty(lng)){
				
		$("#google_map_wrap").show();
		
		if(empty(mapbox_handle)){
			mapbox_handle = L.map("google_map_wrap",{ 
				/*scrollWheelZoom:false,
				zoomControl:false,*/
			 }).setView([lat,lng], mapbox_default_zoom );
						
			L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token='+map_apikey, {		    	
			    maxZoom: 18,
			    id: 'mapbox/streets-v11',    
			}).addTo(mapbox_handle);
			
			mapbox_marker = L.marker([lat,lng], { draggable : true } ).addTo(mapbox_handle);
		} else {
			var newLatLng = new L.LatLng(lat, lng);
			mapbox_marker.setLatLng(newLatLng);
			mapbox_handle.setView(new L.LatLng(lat, lng), 15);
		}
						
		mapbox_marker.on('dragend', function (e) {
		    document.getElementById('merchant_latitude').value = mapbox_marker.getLatLng().lat;
		    document.getElementById('merchant_longtitude').value = mapbox_marker.getLatLng().lng;
		});
	} else {
		$("#google_map_wrap").hide();
	}
}


/*DELIVERY TABLE RATE*/
jQuery(document).ready(function() {
		
	$( document ).on( "click", ".add_fee_row,.add_price_range_row", function() {				
		var html = $(".table-shipping-rates .first_row").html();		
		$('.table-shipping-rates tr:last').after('<tr>'+html+'</tr>');
	});
		
	$( document ).on( "click", ".remove_rate,.remove_price_range", function() {		
		var count = $('.table-shipping-rates tbody tr').length;
		if(count<=1){
			return;
		}
		parent = $(this).parent().parent();				
		parent.remove();
	});
	
});/* end docu*/


jQuery(document).ready(function() {
		
	
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
		
	$( document ).on( "click", ".print_receipt_thermal", function() {
		ans = confirm("this will print receipt using your printer addon.\npress ok to continue");
		if(ans){			
			callAjax("printerThermalReceipt","order_id="+ $(this).data("id") + "&panel="+ current_panel );
		}
	});
	
});/* end docu*/

removeClasses = function(field_class_name, classes){
	clas = classes.split(",");
	$.each(clas, function( index, val ) {
		$("."+field_class_name).removeClass(val);
	});
};


/*cygnuspay payment*/

$.validate({ 	
	language : jsLanguageValidator,
    form : '#forms_cygnus',    
    onError : function() {      
    },
    onSuccess : function() {               
      CollectJS.startPaymentRequest();  
      return false;
    }  
});

showPreloader = function(e){
	if (e) {
        $('body').css('cursor', 'wait');	
    } else $('body').css('cursor', 'auto');        
    
    if (e) {
    	$(".main-preloader").show(); 
    } else $(".main-preloader").hide(); 
};

function onLoad(loading, loaded) {
    if(document.readyState === 'complete'){
        return loaded();
    }
    loading();
    if (window.addEventListener) {
        window.addEventListener('load', loaded, false);
    }
    else if (window.attachEvent) {
        window.attachEvent('onload', loaded);
    }
};

if ( $("#forms_cygnus").exists() ){
	onLoad(function(){
	   console.log('I am waiting for the page to be loaded');
	   busy(true);
	},
	function(){
	    console.log('The page is loaded');
	    busy(false);
	});
}

jQuery(document).ready(function() {	
	$( document ).on( "click", ".paynow_stripe", function() {		
		stripe.redirectToCheckout({		  
		  sessionId: stripe_session,
		}).then(function (result) {
		    uk_msg(result.error.message);
		});		
	});
	
		
	if( $(".system_notification").exists()){
		setTimeout(function(){ 
		   callAjax("getNotification","");
		}, 1000);		
	}
	
});
/*end ready*/
