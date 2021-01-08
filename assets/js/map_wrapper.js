var $map;
var $map_marker = [];
var $map_bounds = [];
var $infoWindow;
var $map_provider = '';
var $set_data = [];

initMap = function(provider,id, lat, lng, zoom){
	dump("provider=>"+ provider);
	dump("id=>"+ id);
	dump("location=>"+ lat+"=>"+lng);
	$map_provider = provider;
	
	switch(provider){			
		case "google.maps":				
			 if (typeof zoom === "undefined" || zoom==null ) {
				zoom = 10;
			 }				 
			 		
			 options = {
			  div: id ,
			  lat: lat,
			  lng: lng		  
			};
			
			var latlng = new google.maps.LatLng( lat, lng);
		    $map_bounds.push(latlng);
		    $map = new GMaps(options);	
		    $map.setZoom( parseInt(zoom) );
		    return true;
		break;
		
		case "mapbox":
		
			 if (typeof zoom === "undefined" || zoom==null ) {
				zoom = 15;
			 }		
			 
			 id = id.replace("#", "");			    			   
			 $map = L.map(id,{ 
			   scrollWheelZoom:true,
			   zoomControl:false,
		     }).setView([lat,lng], zoom );  
		    
		     L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token='+map_apikey, {	
		    	attribution: 'Mapbox',
			    maxZoom: zoom ,
			    id: 'mapbox/streets-v11',		    
			 }).addTo($map);
			return true;
		break;
		
		default:
		  alert("Invalid provider");
		  return false;
		break;
	}
};

map_addMarker = function(index, lat, lng){
		
	switch($map_provider){
		case "google.maps":	
		  var options = {
		       lat: lat,
			   lng: lng,
			   draggable:true,
			   dragend: function(event) {
			   	  var $lat = event.latLng.lat();
                  var $lng = event.latLng.lng();
                  
                  if (typeof $set_data === "undefined" || $set_data==null ) {
                  } else {
	                  $($set_data.latitude).val( $lat );
	                  $($set_data.longitude).val( $lng );
                  }
			   }
		    };
		  if(!empty($map_marker[index])){
		  	   map_moveMarker( index, lat, lng );
		  } else {
		  	   $map_marker[index] = $map.addMarker( options );
		  }
		  
		  var latlng = new google.maps.LatLng( lat , lng );
	      $map_bounds.push(latlng);		  
		  
		break;
		
		case "mapbox":
			var options = {};
			options.draggable = true;
			if(!empty($map_marker[index])){
				map_moveMarker( index, lat, lng );
			} else {
				$map_marker[index] = L.marker([ lat , lng ], options ).addTo($map);  
			}
			
			if (typeof $set_data === "undefined" || $set_data==null ) {
				//
			} else {
				$map_marker[index].on('dragend', function (e) {
					 $lat = $map_marker[index].getLatLng().lat;
			  	 	 $lng = $map_marker[index].getLatLng().lng;		  	 	 
			  	 	 $($set_data.latitude).val( $lat );
		             $($set_data.longitude).val( $lng );
				});
			}
			
			latlng = [lat,lng];
			$map_bounds.push( latlng );				  			
		break;
	}
	
};

fill_map = function(provider,id, lat, lng, zoom, set_data){
	$set_data = set_data;
	if( initMap(provider,id,lat,lng,zoom) ){
		setTimeout(function() {					
			if(!empty(lat)){
			   map_addMarker(0,lat,lng);
			} else {				
				locate_location(0);
			}
		}, 1000); 
	}
};

locate_location = function(index){
	
	switch($map_provider){
		case "google.maps":	
		
		 GMaps.geolocate({
		  success: function(position) {	  		  	 
		  	 map_addMarker(index,position.coords.latitude , position.coords.longitude);
		  	 setTimeout(function() {
		  	 	$($set_data.latitude).val( position.coords.latitude );
			    $($set_data.longitude).val( position.coords.longitude );
		  	    map_setCenter(position.coords.latitude, position.coords.longitude);
		  	 }, 100); 
		  },
		  error: function(error) {
		    uk_msg(  js_lang.geolocation_failed + ': '+error.message);
		  },
		  not_supported: function() {
		    uk_msg( js_lang.browser_not_supported );
		  },
		  always: function() {	    
		  	//
		  }
		});
		
		break;
		
		case "mapbox":			 
		 $map.locate({setView: true, maxZoom: 15});	
	     $map.on('locationfound', function(e){	     	
	     	$lat = e.latlng.lat; 
	     	$lng = e.latlng.lng;
	     	map_addMarker(index,e.latlng.lat, e.latlng.lng);
	     	$($set_data.latitude).val( $lat );
		    $($set_data.longitude).val( $lng );
	     });
	     $map.on('locationerror', function(e){
	     	uk_msg(e.message);
	     });
		break;
	}	
};

map_fit = function(){
	switch($map_provider){
	   case "google.maps":	
	     $map.fitLatLngBounds($map_bounds);
	   break;
	   
	   case "mapbox":	
	     $map.fitBounds($map_bounds, {padding: [30, 30]}); 
	   break;
	}
};

map_setCenter = function(lat, lng){
	switch($map_provider){
	   case "google.maps":	
	     $map.setCenter(lat, lng);	
	     $map.setZoom(15);
	   break;
	   
	   case "mapbox":	
	     map.setView([lat, lng], 15);
	   break;
	}
};

map_moveMarker = function(index, lat , lng){	
	try {		
		switch($map_provider){
			case "google.maps":	
			  map_marker[index].setPosition( new google.maps.LatLng( lat , lng ) );			   
			break;
			
			case "mapbox":	
			   map_marker[index].setLatLng([lat, lng]).update(); 
			break;
		}				
	} catch(err) {		
		dump(err);	    
	}  
};
