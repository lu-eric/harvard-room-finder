/* 
 * index.js
 *
 * Javascript for the index page.
 *
 * Loads the map and processes events
 *
 *
 */
 
 // Facebook code for the "like" button. Directly downloaded from Facebook website
 (function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
 
 
 // constants for loading our map
    var centreLat=0.0;
    var centreLon=0.0;
    var initialZoom=2;
    var imageWraps = true;
    var map; 
    var pic_customMap;
    var marker;
    var house = "none";
    var level = 1;
    var room;
    var lat;
    var lng;

	/*
	 * Generate Google Map
	 * 
	 * The code below is obtained from CASA GMapImgCutter
	 * http://www.casa.ucl.ac.uk
	 * 
	 * Some edits made by Jack Greenberg
	 * Obtained via http://www.eliot.harvard.edu/floorplans/
	 *
	 * My comments are included to explain the code
	 *
	 */
	 
	 // function below grabs custom image tiles
	 function customGetTileURL(a,b) {
      var c=Math.pow(2,b);

        var d=a.x;
        var e=a.y;
        var f="t";
        for(var g=0;g<b;g++){
            c=c/2;
            if(e<c){
                if(d<c){f+="q"}
                else{f+="r";d-=c}
            }
            else{
                if(d<c){f+="t";e-=c}
                else{f+="s";d-=c;e-=c}
            }
        }
        // edited by Eric Lu to grab tiles from the right folders and locations
        return "maps/" + house + "/" + level + "/" + f +".jpg";
    }
	
	// ensures the map is the correct height
    function getWindowHeight() {
        if (window.self&&self.innerHeight) {
            return self.innerHeight;
        }
        if (document.documentElement&&document.documentElement.clientHeight) {
            return document.documentElement.clientHeight;
        }
        return 0;
    }

	// prepares map DIV before generating map itself
    function resizeMapDiv() {
    	var d=document.getElementById("map");
        var offsetTop=0;
        for (var elem=d; elem!=null; elem=elem.offsetParent) {
            offsetTop+=elem.offsetTop;
        }
        var height=getWindowHeight()-offsetTop-16;
        if (height>=0) {
            d.style.height=height+"px";
        }
    }
    
    // loads map based on custom tiles, curtesy of CASA GMAPIMGCUTTER http://www.casa.ucl.ac.uk
    function load() {
    	// checks browser compatibility
      	if (GBrowserIsCompatible()) {
        	resizeMapDiv();
        
        // write credits message in the lower right corner
        var copyright = new GCopyright(1,
                              new GLatLngBounds(new GLatLng(-90, -180),
                                                new GLatLng(90, 180)),
                              0,
                              "<a href='http://www.casa.ucl.ac.uk' target='_blank'>Gmaps ImageCutter by UCL CASA</a> | <a href='index.php'>Eric Lu '14</a>");
        var copyrightCollection = new GCopyrightCollection("Credits:");
        copyrightCollection.addCopyright(copyright);
        
        // grabs picture tiles
        var pic_tileLayers = [ new GTileLayer(copyrightCollection , 0, 17)];
        
        // grabs picture names according to customGetTileURL function
        pic_tileLayers[0].getTileUrl = customGetTileURL;
        pic_tileLayers[0].isPng = function() { return false; };
        pic_tileLayers[0].getOpacity = function() { return 1.0; };
        var pic_customMap = new GMapType(pic_tileLayers, new GMercatorProjection(15), "Pic",
            {maxResolution:5, minResolution:0, errorMessage:"Data not available"});
        map = new GMap2(document.getElementById("map"),{mapTypes:[pic_customMap]});
        
        // enables various user-friendly features
        map.addControl(new GLargeMapControl());
        map.addControl(new GMapTypeControl());
		map.addControl(new GOverviewMapControl());
        map.enableDoubleClickZoom();
		map.enableContinuousZoom();
		map.enableScrollWheelZoom();
		
		// generates map centered at 0, 0
        map.setCenter(new GLatLng(centreLat, centreLon), initialZoom, pic_customMap);
        
        // Added by Jack Greenberg to prevent errors when over-zooming
        var minMapScale = 1;
        var maxMapScale = 5;
        var mapTypes = map.getMapTypes();
        for (var i=0; i<mapTypes.length; i++) {
        mapTypes[i].getMinimumResolution = function() {return minMapScale;}
        mapTypes[i].getMaximumResolution = function() {return maxMapScale;}
        }
        
        // Added by Eric Lu: initialize hidden marker in order to have only one marker on the map at a time
        marker = new GMarker(new GLatLng(0,0), {hide: true});
        map.addOverlay(marker);
        
      }
    }
    
    /*
     * My additions are below, to dynamically generate locations on the map
     */
     
    // Listen for change of house selection, then query server for possible entryways
 	$(document).ready(function() {
 		$("#house").change(function() {
 			if ($("#house").val() != "") 
 			{
 				$.ajax({
 					// query server for possible entryways
 					url: 'submit1.php', 
 					data: "house=" + $("#house").val(),
 					type: "POST",
					success: function(html) {
					
						// update entryway selction list by inserting returned html
						$("#entrywaySpan").html(html);
						
						// hide the room selection since we need to pick an entryway first
						$("#roomSpan").hide();
						
						// update the map by changing folder values
						house = $("#house").val();
						level = 1;
						map.setCenter(new GLatLng(centreLat, centreLon), initialZoom, pic_customMap);
						
						// remove any previous markers, i.e., generated by saved locations
						map.removeOverlay(marker);
					}
				});	
			}
		});
	 });
	 
	 // listen for selection of a saved location, then directly update map
	 $(document).ready(function() {
 		$("#saved").change(function() {
 			if ($("#saved").val() != "") {
 				// split up dynamically generated option
 				var result = $("#saved").val().split(",");
 				
 				// grab elements associated with the selected location
 				house = result[0];
				level = result[3];
				lat = result[4];
				lng = result[5];
				
				// create new center for map
				var center = new GLatLng(lat, lng);
				
				// remove previous marker
				map.removeOverlay(marker);
				
				// render map
				map.setCenter(center, 3, pic_customMap);

				// create new marker
        		marker = new GMarker(center, {title: result[2]});
        		map.addOverlay(marker);
        		
        		// add information about the location
        		var details = "<font style='font-family: arial, gerorgia; font-size: 10pt'><b>RoomFINDER has found:</b><br>" + result[0] + " " + result[1] + " " + result[2] + "<br>Floor " + result[3];
        		marker.openInfoWindow(details);
        		GEvent.addListener(marker, 'click', function() {
  					marker.openInfoWindow(details);
  				});
  			}
		});
	 });
	 
	// verification function for the login form
	$(document).ready(function() {  
		// wait for submission, then check conditions before submitting form
		$("#login").submit(function() {
			if ($("#user").val() == "") {
				$("#verify").text("Please fill out a  username!").show().fadeOut(5000);
      			return false;
      		}
      		else if ($("#pass").val() == "") {
      			$("#verify").text("Please fill out a password!").show().fadeOut(5000);
      			return false;
  			}
  			else return true;
		});
	});
