jQuery.fn.exists = function(){return this.length>0;}

if ( $("#sau_merchant_upload_file").exists() ){						
	sau_merchant_progress = $("#sau_merchant_upload_file").data("progress");
	sau_merchant_progress = $("."+sau_merchant_progress);
	sau_merchant_preview = $("#sau_merchant_upload_file").data("preview");
	
	sau_merchant_field = $("#sau_merchant_upload_file").data("field");
					
	var uploader = new ss.SimpleUpload({
		 button: 'sau_merchant_upload_file',
		 url: ajax_admin + "/uploadFile/?"+ addValidationRequest()+"&method=get&field="+sau_merchant_field + "&preview=" + sau_merchant_preview  ,
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
		 	this.setProgressBar(sau_merchant_progress);
		 },
		 onComplete: function(filename, response) {			
		 	 busy(false); 	 
		 	 if (!response) {
                uk_msg( js_lang.upload_failed  );
                return false;            
             } else {	        	             	
             	dump(response);	             	
             	if( response.code==1){
             		$("."+sau_merchant_preview).html( response.details.preview_html );
             	} else {
             		uk_msg( response.msg );
             	}
             }	            
		 }
	});
}


/*MULTIPLE UPLOAD*/

if ( $("#multiple_upload").exists() ){						
	multiple_progress = $("#multiple_upload").data("progress");
	multiple_progress = $("."+multiple_progress);
	multiple_preview = $("#multiple_upload").data("preview");
	
	multiple_field = $("#multiple_upload").data("field");
						
	var uploader = new ss.SimpleUpload({
		 button: 'multiple_upload',
		 url: ajax_admin + "/MultipleUploadFile/?"+ addValidationRequest()+"&method=get&field="+multiple_field + "&preview=" + multiple_preview  ,
		 name: 'uploadfile',		
		 multipleSelect: true, 	
		  multipart: true,
          multiple: true,
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
		 },
		 onComplete: function(filename, response) {			 	 
		 	 busy(false);
		 	 if (!response) {
                uk_msg( js_lang.upload_failed  );
                return false;            
             } else {	        	             	
             	dump(response);	             	
             	if( response.code==1){
             		$("."+multiple_preview).append( response.details.preview_html );
             	} else {
             		uk_msg( response.msg );
             	}
             }	            
		 }
	});
}


if ( $("#single_uploadfile").exists() ){						
	single_uploadfile_progress = $("#single_uploadfile").data("progress");
	single_uploadfile_progress = $("."+single_uploadfile_progress);
	single_uploadfile_preview = $("#single_uploadfile").data("preview");
	
	single_uploadfile_field = $("#single_uploadfile").data("field");
					
	var uploader = new ss.SimpleUpload({
		 button: 'single_uploadfile',
		 url: ajax_admin + "/uploadFile/?"+ addValidationRequest()+"&method=get&field="+single_uploadfile_field + "&preview=" + single_uploadfile_preview  ,
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
                uk_msg( js_lang.upload_failed  );
                return false;            
             } else {	        	             	
             	dump(response);	             	
             	if( response.code==1){
             		$("."+single_uploadfile_preview).html( response.details.preview_html );
             	} else {
             		uk_msg( response.msg );
             	}
             }	            
		 }
	});
}

cance_order_handle='';

jQuery(document).ready(function() {
	
	$( document ).on( "click", ".multiple_remove_image,.single_uploadfile_remove", function() {
		$(this).parent().html('');
	});		
	
	if ( current_panel =="merchant" ){
	 	cance_order_handle = setInterval(function(){getNewCancelOrder()}, 15000);
	}	
	
});/* END DOCU*/

var handle_cancelorder;

function getNewCancelOrder()
{			
	var params='';
	params+= addValidationRequest();	
	
	handle_cancelorder = $.ajax({    
        type: "POST",
        url: ajax_admin+"/getNewCancelOrder",
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
        		if ( $(".merchant-dashboard").exists() ) {
        		    epp_table2.fnReloadAjax(); 	
        		    table_reload();
        		}
        		if( $('.uk-notify').is(':visible') ) {           			
        		} else {              			
        			if ( $("#alert_off").val()=="" ){	        				
        			   my_notification.play(); 			        			
        			} else {        				
        			}
        			$.UIkit.notify({
		       	   	   message : data.msg
		       	    }); 	       	        
        		}
        	} else {
        		count = $("#table_list2").find(".uk-badge").length;        		
        		if(count>0){
        			epp_table2.fnReloadAjax(); 
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