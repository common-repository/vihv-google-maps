function TVihvGoogleMap(latitude, longitude, zoom, kml, containerId) {
    this.latitude = latitude;
    this.longitude = longitude;
    this.zoom = zoom;
    this.kml = kml;
    this.containerId = containerId;
    
    this.Init = function() {
	this.resizeContainer();
	var mapOptions = {
                            center: new google.maps.LatLng(
				this.latitude,
				this.longitude),
                            zoom: parseInt(this.zoom),
                            mapTypeId: google.maps.MapTypeId.ROADMAP
                            };
	var map = new google.maps.Map(document.getElementById(this.containerId), mapOptions);
	if(this.kml != '') {
	    this.loadKmlLayer(this.kml,map);
	}
    }
    
    this.resizeContainer = function() {
	jQuery('#'+this.containerId).height(jQuery('#'+this.containerId).width()*0.69);
    }
    
    this.loadKmlLayer = function(src, map) {
	var kmlLayer = new google.maps.KmlLayer(src, {
	    suppressInfoWindows: true,
	    preserveViewport: false,
	    map: map
	  });
       google.maps.event.addListener(kmlLayer, 'click', function(event) {
	    var content = event.featureData.infoWindowHtml;
	    var testimonial = document.getElementById('capture');
	    testimonial.innerHTML = content;
	  });
    }
    
    this.Init();
}


