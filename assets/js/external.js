
var host_name="http://"+window.location.hostname;
var ajax_url=host_name+'/restomulti/External';

function getScripts(scripts, callback) {
    var progress = 0;
    var internalCallback = function () {
        if (++progress == scripts.length) { callback(); }
    };

    scripts.forEach(function(script) { $.getScript(script, internalCallback); });
};

function finishLoadingScript()
{
	dump("finish loading scrript");
}

jQuery(document).ready(function() {		
	jQuery.fn.exists = function(){return this.length>0;}	
	
	
	getScripts([host_name+"/restomulti/assets/vendor/fancybox/source/jquery.fancybox.js", host_name+"/restomulti/assets/vendor/jquery.ui.timepicker-0.0.8.js"], function () { finishLoadingScript(); });
		            
	$('<link>')
    .appendTo('head')
    .attr({type : 'text/css', rel : 'stylesheet'})
    .attr('href', host_name+'/restomulti/assets/css/external.css?ver=1');
    
    $('<link>')
    .appendTo('head')
    .attr({type : 'text/css', rel : 'stylesheet'})
    .attr('href', host_name+'/restomulti/assets/vendor/uikit/css/uikit.almost-flat.min.css');
    
    $('<link>')
    .appendTo('head')
    .attr({type : 'text/css', rel : 'stylesheet'})
    .attr('href', host_name+'/restomulti/assets/vendor/fancybox/source/jquery.fancybox.css');
    
    $('<link>')
    .appendTo('head')
    .attr({type : 'text/css', rel : 'stylesheet'})
    .attr('href', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css');
    
	if ( !$("#kr-merchant-id").exists() ){	
		alert("Merchant ID not found");
		return;
	}
	
	if ( $(".kr-menu").exists() ){
		$( ".kr-menu" ).each(function( index ) {
			var cat_id=$(this).data("id");			
			fillCategory(cat_id,$(this));
		});
	}
		
	if ( $(".kr-cart").exists() ){		
		getCart( $(".kr-cart") );
	} else {
		alert("kr-cart not found.");
	}
	
	$( document ).on( "click", ".kr-item", function() {
		 var item_id=$(this).data("id");
		 dump(item_id);
		 var params="action=viewFoodItem&currentController=store&item_id="+item_id+"&tbl=viewFoodItem";
    	open_fancy_box(params);
	});
	
	$( document ).on( "click", ".edit_item", function() {
    	var id=$(this).attr("rel");
    	var row=$(this).data("row");
    	var params="action=viewFoodItem&currentController=store&item_id="+id+"&tbl=viewFoodItem&row="+row;
    	open_fancy_box(params);
    });
    
    $( document ).on( "click", ".delete_item", function() { 
   	   var ans=confirm('Are you sure?'); 
   	   if (ans){      
   	       var row=$(this).data("row");   	   
   	       delete_item(row);
   	   }
   });
	
	$( document ).on( "keyup", ".numeric_only", function() {
      this.value = this.value.replace(/[^0-9\.]/g,'');
   });	 
   
    $( document ).on( "click", ".qty-plus", function() {
   	  qty=parseFloat( $("#qty").val())+1;    	
    	if (isNaN(qty)){
    	    qty=1;
    	}
    	$("#qty").val( qty );
   });
   
   $( document ).on( "click", ".qty-minus", function() {
   	    var qty=$("#qty").val()-1;
    	if (qty<=0){
    		qty=1;
    	}
    	$("#qty").val(  qty );
   });
   
   $( document ).on( "click", ".special-instruction", function() {
   	  $(".notes-wrap").slideToggle("fast");
   });
   
   $( document ).on( "click", ".add_to_cart", function() {   	   
   	   var price=$("#price:checked").length;   	   
   	   if (price<=0){
   	   	   alert('Please select price');
   	   	   $("#price_wrap").focus();
   	   	   return;
   	   }  
   	   form_submit('frm-fooditem');
   });
   
   $( document ).on( "click", ".qty-addon-plus", function() {
   	   var parent=$(this).parent();   	   
   	   var child=parent.find(".addon_qty");   	   
   	   var qty=parseFloat(child.val())+1;   	   
   	   if (isNaN(qty)){
    	    qty=1;
       }
       child.val( qty );
   });
   
   $( document ).on( "click", ".qty-addon-minus", function() {
   	   var parent=$(this).parent();   	   
   	   var child=parent.find(".addon_qty");   	      
   	   var qty=parseFloat(child.val())-1;
       if (qty<=0){
    		qty=1;
       }
       child.val( qty );
   });
            
}); /*END DOCO*/

function dump(data)
{
	console.debug(data);
}

function fillCategory(cat_id,object)
{
	object.html("loading...");
	var params="action=fillCategory&cat_id="+cat_id;
    $.ajax({    
    type: "get",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    async: true,
    crossDomain: true,
    success: function(data){     	
    	console.debug(data);
    	if (data.code==1){    		
    		var list=menuFormater(data.details,data.msg);    		
    		object.html(list);
    	} else  {
    		object.html(data.msg);
    	}
    }, 
    error: function(){	        	    	    	
    }		
    });	
}

function jdate()
{
   jQuery(".j_date").datepicker({ 
    	dateFormat: 'yy-mm-dd' ,
    	changeMonth: true,
    	changeYear: true ,	   
	    minDate: 0	   
   });	
}

function timepick()
{
	jQuery('.timepick').timepicker({       
		/*hourText: js_lang.Hour,       
		minuteText: js_lang.Minute,  
		amPmText: [js_lang.AM, js_lang.PM],*/
    });	      
}

function getCart(object)
{
	var params="action=getCart&mtid="+$("#kr-merchant-id").val();
    $.ajax({    
    type: "get",
    url: ajax_url,
    data: params,
    dataType: 'html',
    async: true,
    crossDomain: true,       
    success: function(data){     	    
    	object.html(data);
    	load_item_cart();
    	jdate();    	
    	timepick();
    }, 
    error: function(){	        	    	    	
    }		
    });	
}

function load_item_cart()
{
	var params="action=loadItemCart&currentController=store&merchant_id="+$("#kr-merchant-id").val();
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',       
    async: true,
    crossDomain: true,
    success: function(data){ 
    	busy(false);      	
    	if (data.code==1){
    		$(".item-order-wrap").html(data.details.html);
    		$(".checkout").attr("disabled",false);    		
    		$(".checkout").css({ 'pointer-events' : 'auto' });
    		$(".checkout").addClass("uk-button-success");
    		$(".voucher_wrap").show();
    	} else {
    		$(".item-order-wrap").html('<div class="center">'+data.msg+'</div>');
    		$(".checkout").attr("disabled",true);
    		$(".checkout").css({ 'pointer-events' : 'none' });
    		$(".checkout").removeClass("uk-button-success");
    		$(".voucher_wrap").hide();
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });
}

function menuFormater(data,currency)
{	
	var html='<ul>';
	html+='<h3>'+currency.category_name+'</h3>';
    $.each(data, function( index, val ) {	    
	    html+='<li>';
	    html+='<a href="javascript:;" class="kr-item" data-id="'+val.item_id+'" >';
	    html+="<item>"+val.item_name+"</item>";
	    html+="<price>"+currency.currency+" "+val.prices[0].price+"</price>";
	    html+='</a>';
	    html+='</li>';
    });
    html+='</ul>';
    return html;
}

function open_fancy_box(params)
  {  	  	  	  	
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

function open_fancy_box2(url_link)
  {  	  	  	  	
	var URL=url_link;
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

function busy(e)
{
    if (e) {
        $('body').css('cursor', 'wait');	
    } else $('body').css('cursor', 'auto');        
    
    if (e) {
    	$("body").before("<div class=\"preloader\"></div>");
    } else $(".preloader").remove();
    
}

function rm_notices()
{
	$(".uk-alert").remove();		    
}

function form_submit(formid)
{		
	rm_notices();
	if ( formid ) {
		var form_id=formid;
	} else {
		var form_id=$("form").attr("id");    
	}    	
    
	var btn=$('#'+form_id+' input[type="submit"]');   	
    var btn_cap=btn.val();
    btn.attr("disabled", true );
    btn.val("processing");
    busy(true);    
    
    var params=$("#"+form_id).serialize();	
    
    var action=$('#'+form_id).find("#action").val(); 
    
     $.ajax({    
        type: "POST",
        url: ajax_url,
        data: params,
        dataType: 'json',       
        async: true,
        crossDomain: true,
        success: function(data){ 
        	busy(false);  
        	btn.attr("disabled", false );
        	btn.val(btn_cap);        	        	
        	if (data.code==1){        		
        		if ( action=="addToCart" ){
        			close_fb();        	
        			load_item_cart();		
        		}        		     
        		if (action=="clientLogin"){
        			var params="action=PaymentOption";
    	            open_fancy_box(params);
        		}
        		
        		if (action=="placeOrder"){
        			var params="action=receipt&id="+data.details.order_id;
    	            open_fancy_box(params);
        		}
        	} else {
        		alert(data.msg);
        	}     	
        }, 
        error: function(){	        	
        	btn.attr("disabled", false );
        	btn.val(btn_cap);
        	busy(false);        	        	
        	$("#"+form_id).before("<p class=\"uk-alert uk-alert-danger\">ERROR</p>");    		
        }		
    });
}    


jQuery(document).ready(function() {		
	
	$( document ).on( "click", ".checkout", function() {    	  
   	  if ( $("#minimum_order").length>=1){   	  	  
   	  	  var minimum= parseFloat($("#minimum_order").val());
   	  	  var subtotal= parseFloat($("#subtotal_order").val());
   	  	  if (isNaN(subtotal)){
   	  	  	  subtotal=0;
   	  	  }   	     	  	  
   	  	  if (isNaN(minimum)){
   	  	  	  minimum=0;
   	  	  }   	     	  	  
   	  	  if ( minimum>subtotal){   	  	  	
              alert('Sorry but Minimum order is'+" "+ $("#minimum_order_pretty").val());
   	  	  	  return;
   	  	  }      	  	  
   	  	  
          if ( $("#merchant_maximum_order").exists() ){
    	      console.debug("max");
    	      var merchant_maximum_order= parseFloat($("#merchant_maximum_order").val());    	      
    	      if ( subtotal>merchant_maximum_order){
    	      	  alert('Sorry but Maximum order is'+" "+ $("#merchant_maximum_order_pretty").val());
   	  	  	      return;
    	      }              	      
          }   	  	  
   	  	  
   	  }  
   	  
   	  var params="delivery_type="+$("#delivery_type").val()+"&delivery_date="+$("#delivery_date").val();
   	  params+="&delivery_time="+$("#delivery_time").val();
   	  params+="&delivery_asap="+$("#delivery_asap:checked").val();
   	  params+="&mtid="+$("#kr-merchant-id").val();
   	  dump(params);
   	  
   	    busy(true);
	    $.ajax({    
	    type: "POST",
	    url: ajax_url,
	    data: "action=setDeliveryOptions&currentController=store&tbl=setDeliveryOptions&"+params,
	    dataType: 'json',      
	    async: true,
        crossDomain: true, 
	    success: function(data){ 
	    	busy(false);      	
	    	if (data.code==1){
	    		if (data.details==2){
	    		   var params="action=PaymentOption&mtid="+$("#kr-merchant-id").val();    	           
	    		} else {
	    		   var params="action=checkOut&mtid="+$("#kr-merchant-id").val();    	           
	    		}
	    		open_fancy_box(params);
	    	} else {
	    		alert(data.msg);
	    	}
	    }, 
	    error: function(){	        	    	
	    	busy(false); 
	    }		
	    });   	     	
   	  
	}); 
	
	
   $( document ).on( "click", ".place_order", function() {    	  
   	
   	   if ( $("#minimum_order").length>=1){   	  	  
   	  	  var minimum= parseFloat($("#minimum_order").val());
   	  	  var subtotal= parseFloat($("#subtotal_order").val());
   	  	  if (isNaN(subtotal)){
   	  	  	  subtotal=0;
   	  	  }   	     	  	  
   	  	  if (isNaN(minimum)){
   	  	  	  minimum=0;
   	  	  }   	     	  	  
   	  	  if ( minimum>subtotal){   	  	  	
              alert('Sorry but Minimum order is'+" "+ $("#minimum_order_pretty").val());
   	  	  	  return;
   	  	  }      	  	  
   	   }  
   	
   	   var payment_type=$(".payment_option:checked").val();
   	   
   	   if ( $(".payment_option:checked").length<=0 ){
   	   	   alert('Please select payment method');
   	   	   return;
   	   }
   	   
   	   if ( $("#contact_phone").val()=="" ){
   	   	   alert('Mobile number is required');
   	   	   $("#contact_phone").focus();
   	   	   return;
   	   }   
   	   
   	   if ( payment_type =="ccr"){   	   	   
   	   	   if ( $(".cc_id:checked").length<=0 ){
   	   	   	   alert('Please select your credit card');   	   	  
   	   	   	   return;
   	   	   }   	   
   	   }      	   
   	   form_submit('frm-delivery');   	   
   });
  	
	
});	 /*END doc*/


function delete_item(row)
{
	var params="action=deleteItem&row="+row;
	busy(true);
    $.ajax({    
    type: "POST",
    url: ajax_url,
    data: params,
    dataType: 'json',     
    async: true,
    crossDomain: true,  
    success: function(data){ 
    	busy(false);      	
    	if (data.code==1){    		
    		load_item_cart();
    	}
    }, 
    error: function(){	        	    	
    	busy(false); 
    }		
    });
}