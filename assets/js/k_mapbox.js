var mapbox_handle;
var mapbox_marker;

mapbox_get_marker = function()
{
	var default_icon;	
	if(!empty(map_marker)){
		default_icon = L.icon({
		    iconUrl: map_marker,	    	   
		});
	}
	return default_icon;
}

mapbox_plot_browse_map = function()
{
	
	var default_icon = mapbox_get_marker();

	$( ".browse-list-map.active" ).each(function( index ) {
		try {
			
			var lat=$(this).data("lat");
			var lng=$(this).data("long");
			
			dump(lat+", "+lng );
			
			mapbox = L.map(this,{
				scrollWheelZoom:false
			}).setView([lat,lng], mapbox_default_zoom );
								   
			L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token='+mapbox_access_token, {		    
			    maxZoom: 18,
			    id: 'mapbox/streets-v11',	    
			}).addTo(mapbox);
					
			if(!empty(default_icon)){
				marker = L.marker([lat,lng], { icon : default_icon } ).addTo(mapbox);
			} else {
				var marker = L.marker([lat,lng]).addTo(mapbox);
			}		
			
			$(this).removeClass("active"); 	
		
		} catch(err) {
		   $(this).removeClass("active");
	       dump("error : =>"+err.message);
	   } 
		
	});
}


mapbox_plot_contact = function(data){
	
	
	lat = data.map_latitude;
	lng = data.map_longitude;
		
	var default_icon = mapbox_get_marker();
	
	mapbox = L.map("contact-map",{ 
		scrollWheelZoom:false,
		zoomControl:false,
	 }).setView([lat,lng], mapbox_default_zoom );
		
	L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token='+mapbox_access_token, {	
	    maxZoom: 18,
	    id: 'mapbox/streets-v11',
	}).addTo(mapbox);
	
	marker = L.marker([lat,lng], { icon : default_icon } ).addTo(mapbox);
}

mapbox_fullmap = function(lat , lng , div){
	
	if (mapbox_handle != undefined) {
		mapbox_handle.remove();
	}	
	
	//mapbox_handle = L.map("full-map",{ 
	mapbox_handle = L.map(div,{ 		
	 }).setView([lat,lng], 6 );
		
	var url = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token='+mapbox_access_token;
	 
	L.tileLayer(url, {		    
	    maxZoom: 18,
	    id: 'mapbox/streets-v11',	    
	}).addTo(mapbox_handle);
	
	setTimeout(function(){ 
	 	callAjax("loadAllMerchantMap");
	}, 10);
}

mapbox_allmerchant = function(data){
	
	var default_icon = mapbox_get_marker();
	marker = [];
	bounds = [];
	
	$.each(data, function( index, val ) {
		
		try {
					
			lat = val.latitude;
			lng =  val.lontitude;
			
			dump(lat+","+lng);
			
			if(!empty(default_icon)){
				marker[index] = L.marker([lat,lng], { icon : default_icon } ).addTo(mapbox_handle);
			} else {
				marker[index] = L.marker([lat,lng]).addTo(mapbox_handle);
			}		
			
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
			
			marker[index].bindPopup(resto_info).openPopup();
					
			latlng = [lat,lng];
			bounds.push( latlng );
			
	   } catch(err) {	   	
	       dump("error : =>"+err.message);
	   } 
		
	});
		
	mapbox_handle.fitBounds(bounds, {padding: [30, 30]}); 
}

mapbox_merchantmap = function(lat, lng){
		
	try {
		
		var default_icon = mapbox_get_marker();
		
		mapbox_handle = L.map("merchant-map",{ 
			scrollWheelZoom:false,
			//zoomControl:false,
		 }).setView([lat,lng], mapbox_default_zoom );
				
		L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token='+mapbox_access_token, {	    
		    maxZoom: 18,
		    id: 'mapbox/streets-v11',
		}).addTo(mapbox_handle);
		
		mapbox_marker = L.marker([lat,lng], { icon : default_icon } ).addTo(mapbox_handle);
		
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
		  
		 mapbox_marker.bindPopup(resto_info).openPopup();
	 
	 } catch(err) {	   	
	    dump("error : =>"+err.message);
	 } 
}

mapbox_direction = function(data){
		
	
	mapbox_handle.eachLayer(function (layer) {
	    mapbox_handle.removeLayer(layer);
	});
	
	mapbox_handle.off();
		
	L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token='+mapbox_access_token, {	
	    maxZoom: 18,
	    id: 'mapbox/streets-v11',
	}).addTo(mapbox_handle);

	bounds = [];
	
	var origin = L.latLng({
		lat: data.lat,
		lng: data.lng
	});
	
	latlng = [data.lat,data.lng];
	bounds.push( latlng );
	
	//http://www.liedman.net/leaflet-routing-machine/tutorials/geocoders/
	//https://github.com/perliedman/leaflet-control-geocoder
	
	if (mapbox_marker != undefined) {
        mapbox_handle.removeLayer(mapbox_marker);
    };
	
	$(".direction_output").html('');
		
	//var destination_location= $("#merchant_map_latitude").val()+","+$("#merchant_map_longtitude").val();
	
	var destination_location = L.latLng({
		lat:  $("#merchant_map_latitude").val(),
		lng: $("#merchant_map_longtitude").val()
	});
	
	latlng = [$("#merchant_map_latitude").val(),$("#merchant_map_longtitude").val()];
	bounds.push( latlng );
	
	dump(origin);
    dump(destination_location);
    
     
	/*L.Routing.control({	  
	  waypoints: [
	    L.latLng(14.560642335840027, 121.04132463170777),
	    L.latLng(14.5609954,121.0464101)
	  ],
	  router: L.Routing.mapbox(mapbox_access_token)
	}).addTo(mapbox_handle);*/
	
	var control = L.Routing.control({
	/*waypoints: [
	        L.latLng(14.560642335840027, 121.04132463170777),
	        L.latLng(14.5609954,121.0464101)
	    ],
	    router: L.Routing.mapbox(mapbox_access_token)	    
	});*/
	
	waypoints: [
	        origin,
	        destination_location
	    ],
	    router: L.Routing.mapbox(mapbox_access_token)	    
	});
	
	var routeBlock = control.onAdd(mapbox_handle);    
	document.getElementById('direction_output').appendChild(routeBlock);
	$(".direction_output").show();
	
	mapbox_handle.fitBounds(bounds, {padding: [30, 30]}); 
}

mapbox_autocomplete = function(){		
	
	$("#origin").hide();
	
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
	
	document.getElementById('geocoder').appendChild(geocoder.onAdd(mapbox_handle));
}

mapbox_search_autocomplete = function(){
	
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
	
	document.getElementById('mapbox_s_goecoder').appendChild(geocoder.onAdd(mapbox_handle));
	
	$(".mapbox_s_goecoder input").attr("name","s");
	$(".mapbox_s_goecoder input").attr("id","s");
	$(".mapbox_s_goecoder input").attr("placeholder", js_lang.search_placeholder );	
	$(".mapbox_s_goecoder input").attr("autocomplete","off");
	
	//if(!empty(mapbox_search_address)){
	if (typeof mapbox_search_address === "undefined" || mapbox_search_address==null || mapbox_search_address=="" || mapbox_search_address=="null" ) {	
		//
	} else {
		$(".mapbox_s_goecoder input").val( mapbox_search_address );
	}
}

mapbox_geo = function(position){
	dump(position);
	var lat=position.coords.latitude;
	var lng=position.coords.longitude;
	dump(lat+","+lng);
	callAjax("geocode","lat="+  lat + "&lng="+ lng);
}

//https://www.mapbox.com/mapbox-gl-js/example/locate-user/
//mapAddress();
mapbox_select_address = function(){

	lat  = temporary_address_lat;
	lng  = temporary_address_lng;
	
	if (mapbox_handle != undefined) {
		mapbox_handle.remove();
	}	
	
	mapbox_handle = L.map("map_address",{ 
		scrollWheelZoom:false,		
	 }).setView([lat,lng], mapbox_default_zoom );
	
	var url  = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token='+mapbox_access_token;
	 
	L.tileLayer(url, {		    
	    maxZoom: 18,
	    id: 'mapbox/streets-v11',    
	}).addTo(mapbox_handle);
	
	mapbox_marker = L.marker([lat,lng], { draggable : true } ).addTo(mapbox_handle);
	
	mapbox_marker.on('dragend', function (e) {
	    /*document.getElementById('map_address_lat').value = mapbox_marker.getLatLng().lat;
	    document.getElementById('map_address_lng').value = mapbox_marker.getLatLng().lng;*/
	    $("#map_address_lat").val( mapbox_marker.getLatLng().lat ) ;
	    $("#map_address_lng").val( mapbox_marker.getLatLng().lng ) ;
	});
}

/*4.8*/
var mapbox_delivery_accuracy;
var mapbox_delivery_accuracy_marker;

mapbox_delivery_location = function(div){
	
	// http://{s}.tile.osm.org/{z}/{x}/{y}.png
    // http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png

	lat  = temporary_address_lat;
	lng  = temporary_address_lng;		
	
	if (mapbox_delivery_accuracy != undefined) {
		mapbox_delivery_accuracy.remove();
	}	
	
	mapbox_delivery_accuracy = L.map(div,{ 
		scrollWheelZoom:false,		
	 }).setView([lat,lng], mapbox_default_zoom );
		
	var url  = 'https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token='+mapbox_access_token;
	 
	L.tileLayer(url, {		    
	    maxZoom: 18,
	    id: 'mapbox/streets-v11',
	}).addTo(mapbox_delivery_accuracy);
	
	mapbox_delivery_accuracy_marker = L.marker([lat,lng], { draggable : true } ).addTo(mapbox_delivery_accuracy);
	
	mapbox_delivery_accuracy_marker.on('dragend', function (e) {
		dump(mapbox_delivery_accuracy_marker.getLatLng().lat);
	    $("#map_accurate_address_lat").val( mapbox_delivery_accuracy_marker.getLatLng().lat ) ;
	    $("#map_accurate_address_lng").val( mapbox_delivery_accuracy_marker.getLatLng().lng ) ;
	});
	
	
	if (typeof address_list === "undefined" || address_list==null ) {
		//
	} else {
		try {
			address_book_id =  $("#address_book_id").val();
			lat = address_list[address_book_id].lat;
			lng = address_list[address_book_id].lng;					
			mapbox_delivery_accuracy_marker.setLatLng([lat, lng]).update();
			mapbox_delivery_accuracy.setView([lat, lng], 14);
			return;		
		} catch(err) {
		  //
		}
	}
	
	mapbox_delivery_accuracy.locate({setView: true, maxZoom: 15});
	
	mapbox_delivery_accuracy.on('locationfound', onLocationFound);
	mapbox_delivery_accuracy.on('locationerror', onLocationError);
	
};

onLocationFound = function(e){	
	var newLatLng = new L.LatLng(e.latlng.lat, e.latlng.lng);
    mapbox_delivery_accuracy_marker.setLatLng(newLatLng); 
    
    $("#map_accurate_address_lat").val( e.latlng.lat );
	$("#map_accurate_address_lng").val( e.latlng.lng );	    
};

onLocationError = function(){
	uk_msg(e.message);
};