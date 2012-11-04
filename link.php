<?
/*
 * Link.php
 *
 * Processes a linked location and displays it on the map
 *
 */

    // require common code
    require_once("common.php"); 

    // escape username to avoid SQL injection attacks
    $house = mysql_real_escape_string($_GET["house"]);
    $entryway = mysql_real_escape_string($_GET["entryway"]); 
    $room = mysql_real_escape_string($_GET["room"]);
    $level = mysql_real_escape_string($_GET["level"]);
    $lat = mysql_real_escape_string($_GET["lat"]);
    $lng = mysql_real_escape_string($_GET["lng"]);
    
    // check validity of link
    if (empty($house) || empty($entryway) || empty($room) || empty($level) || empty($lat) || empty($lng))
    	redirect("index.php");
    
    
    
?>



<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml">
  <head>
    <meta http-equiv="content-type" content="text/html/xml; charset=utf-8"/>
    <meta name="author" content="Richard Milton, Centre for Advanced Spatial Analysis (CASA), University College London (UCL)">
    <meta name="description" content="Page automatically created by GMapImageCutter created by CASA">
    <meta name="keywords" content="Google, Maps, Image, Images, Tile, Cutter, GMapImageCutter, GMapCreator">

    <title>Harvard RoomFinder</title>
<script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAIGF_rnrCqxtTz9PaXs_f-RTDDDXRV8afaazDnDes2f8C3uXi9BR74yz0oqMkYbicZsFSgMiVIHQ_Ow"
            type="text/javascript"></script>
	<script src="jquery.js"></script>

    <link href="css/indexstyles.css" rel="stylesheet" type="text/css">
	<script type="text/javascript">
	
	/*
	 * Loads map similarly to js/index.js but loads the map according to dynamically generated initial values
	 *
	 *
	 */
	
    var centreLat=0.0;
    var centreLon=0.0;
    var initialZoom=2;
    var imageWraps=true;
    var map; 
    var pic_customMap;
    var marker; 
    
    // initialize values to the values from the link
    var house = "<?= $house ?>";
    var entryway = "<?= $entryway ?>";
	var level = <?= $level ?>;
	var lat = <?= $lat ?>;
	var lng = <?= $lng ?>;
	var room = "<?= $room ?>";
	
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
        
    
	/*
	 * Create the information box for the linked location
	 *
	 */
	 
	var center = new GLatLng(lat, lng);
	map.setCenter(center, 3, pic_customMap);
    marker = new GMarker(center, {title: room});
  	map.addOverlay(marker);
  	
  	var details = "<font style='font-family: arial, gerorgia; font-size: 10pt'><b>RoomFinder has found:</b><br>";
    details += house + " " + entryway + " " + "<br>" + room + "<br>Floor " + level;
    details += "</font>";
  	
    marker.openInfoWindowHtml(details);
    GEvent.addListener(marker, 'click', function() {
  			marker.openInfoWindow(details);
  	});

      }
    }
    
    </script>
  </head>
  
  <body onresize="resizeMapDiv()" onload="load()" onunload="GUnload()">
  <div id="fb-root"></div>
	<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<div class="top">
		<span class="left"><div class="fb-like" data-href="http://cloud.cs50.net/~elu/harvardroomfinder/" data-send="true" data-width="450" data-show-faces="false"></div></span>
		<span class="right">
		</span>
			
	</div>


      <div id="chart">
      <center><a href="index.php"><img src="harvardroomfinder2.png"></a><br>
      <!-- Generate information in the sidebar as well --!>
      <font style='font-family: arial, gerorgia; font-size: 12pt'><b>RoomFINDER has found:</b><br>
    	<?= $house . " " . $entryway . " " . "<br>" . $room . "<br>Floor " . $level ?>
    	
   </font>
      
      
   </center>

    </div>   
    <div id="map"></div>

  </body>
</html>
