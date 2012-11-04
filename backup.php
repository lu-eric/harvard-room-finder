<?
/*
 * Index.php
 *
 * Loads primary map, login interface, and selection forms
 *
 *
 */

    // require common code
    require_once("common.php"); 
    
    // grab session ID, if there is one
    $id = $_SESSION["id"];
    
    // prepare SQL for getting user info
    $sql = "SELECT * FROM users WHERE id = $id";
    
    // prepare SQL for getting user rooms
    $query = "SELECT * FROM rooms WHERE id = $id";

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

    <script src="http://maps.google.com/maps?file=api&v=2"
            type="text/javascript"></script>
	<script src="jquery.js"></script>
	<style type="text/css">
	
	div.top span.left {
  		float: left;
  		font-family: arial, georgia;
  		font-size: 10pt;
  	}

	div.top span.right {
  		float: right;
  		font-family: arial, georgia;
  		font-size: 10pt;
  	}
    
    
    div.top
	{
		margin-top: 0px;
    	width: 100%;
    	height: 40px;
	}
	
    div#map
	{
    	height: 100%;
    	margin-left: 320px;
    	border: 3px solid black;
	}

	div#chart
	{
    	background-color: #ffffff;
    	width: 320px;
    	float: left;
    	font-family: arial, georgia;
    	font-size: 14px;
	}
	
	span#verify 
	{ 
		color:red;
		font-size: 10pt;
		font-family: arial, georgia;
	}
</style>
	<script type="text/javascript">
	
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
        return "maps/" + house + "/" + level + "/" + f +".jpg";
    }

    function getWindowHeight() {
        if (window.self&&self.innerHeight) {
            return self.innerHeight;
        }
        if (document.documentElement&&document.documentElement.clientHeight) {
            return document.documentElement.clientHeight;
        }
        return 0;
    }

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
    
    function load() {
      if (GBrowserIsCompatible()) {
        resizeMapDiv();
        var copyright = new GCopyright(1,
                              new GLatLngBounds(new GLatLng(-90, -180),
                                                new GLatLng(90, 180)),
                              0,
                              "<a href=\"http://www.casa.ucl.ac.uk\">CASA</a>");
        var copyrightCollection = new GCopyrightCollection("GMapImgCutter");
        copyrightCollection.addCopyright(copyright);
        var pic_tileLayers = [ new GTileLayer(copyrightCollection , 0, 17)];
        pic_tileLayers[0].getTileUrl = customGetTileURL;
        pic_tileLayers[0].isPng = function() { return false; };
        pic_tileLayers[0].getOpacity = function() { return 1.0; };
        var pic_customMap = new GMapType(pic_tileLayers, new GMercatorProjection(15), "Pic",
            {maxResolution:5, minResolution:0, errorMessage:"Data not available"});
        map = new GMap2(document.getElementById("map"),{mapTypes:[pic_customMap]});
        map.addControl(new GLargeMapControl());
        map.addControl(new GMapTypeControl());
		map.addControl(new GOverviewMapControl());
        map.enableDoubleClickZoom();
		map.enableContinuousZoom();
		map.enableScrollWheelZoom();
        map.setCenter(new GLatLng(centreLat, centreLon), initialZoom, pic_customMap);
        var minMapScale = 1;
        var maxMapScale = 5;
        var mapTypes = map.getMapTypes();
        for (var i=0; i<mapTypes.length; i++) {
        mapTypes[i].getMinimumResolution = function() {return minMapScale;}
        mapTypes[i].getMaximumResolution = function() {return maxMapScale;}
        }
        
        marker = new GMarker(new GLatLng(0,0), {hide: true});
        map.addOverlay(marker);
        
      }
    }
    
    /*
     * My additions are below, to dynamically generate locations on the map
     */
     
 	$(document).ready(function() {
 		$("#house").change(function() {
 			if ($("#house").val() != "") 
 			{
 				$.ajax({
 					url: 'submit1.php', 
 					data: "house=" + $("#house").val(),
 					type: "POST",
					success: function(html) {
						$("#entrywaySpan").html(html);
						$("#roomSpan").hide();
						house = $("#house").val();
						level = 1;
						map.setCenter(new GLatLng(centreLat, centreLon), initialZoom, pic_customMap);
						map.removeOverlay(marker);
					}
				});	
			}
		});
	 });
	 
	 $(document).ready(function() {
 		$("#saved").change(function() {
 			if ($("#saved").val() != "") {
 				var result = $("#saved").val().split(",");
 				house = result[0];
				level = result[3];
				lat = result[4];
				lng = result[5];
				
				var center = new GLatLng(lat, lng);
				map.removeOverlay(marker);
				map.setCenter(center, 3, pic_customMap);

        		marker = new GMarker(center, {title: result[2]});
        		map.addOverlay(marker);
        		var details = "<font style='font-family: arial, gerorgia; font-size: 10pt'><b>RoomFINDER has found:</b><br>" + result[0] + " " + result[1] + " " + result[2] + "<br>Floor " + result[3];
        		marker.openInfoWindow(details);
        		GEvent.addListener(marker, 'click', function() {
  					marker.openInfoWindow(details);
  				});
  			}
		});
	 });
	 
	$(document).ready(function() {  
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

    </script>
  </head>
  <body onresize="resizeMapDiv()" onload="load()" onunload="GUnload()">
  <div id="fb-root"></div>
	<script>
	(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	
	<!-- create the header --!>
	<div class="top">
		<span class="left"><div class="fb-like" data-href="http://www.google.com" data-send="true" data-width="450" data-show-faces="false"></div></span>
		<span class="right">

		<? 
		
		if (isset($_SESSION["id"])) {
		
			$result = mysql_query($sql);
			$row = mysql_fetch_array($result);
			print("Welcome back, <b>" . $row['username'] . "</b>! &nbsp;&nbsp;&nbsp;<br>Not you? <a href='logout.php'>Logout</a>&nbsp;&nbsp;&nbsp;");
            }
            else {
            	print("  <form id='login' action='login.php' method='post'>
        <span id='verify'><font color='black'>Login to save locations: </font></span>&nbsp;&nbsp;&nbsp;
        <b>Username: <input id='user' name='username' type='text' size='8'> 
        Password: <input id='pass' name='password' type='password' size='8'>&nbsp; 
        <input type='submit' value='Log In'></b>&nbsp; or 
        <a href='register.php'>create an account</a>&nbsp;&nbsp;
      </form>"); }
            
            ?>
		</span>
			
	</div>

	<!-- create the select form --!>
      <div id="chart">
      <a href="index.php"><img src="harvardroomfinder.png"></a><br>
      &nbsp;&nbsp;&nbsp;Choose a House to begin:<center>
        <select id="house" name="house" style="height: 30px; width:300px; font-size:20px;">
          <option value="None" selected>Choose A House</option>
          <option value="Adams">Adams House</option>
          <option value="Cabot">Cabot House</option>
          <option value="Currier">Currier House</option>
          <option value="Dunster">Dunster House</option>
          <option value="Eliot">Eliot House</option>
          <option value="Kirkland">Kirkland House</option>
          <option value="Leverett">Leverett House</option>
          <option value="Lowell">Lowell House</option>
          <option value="Mather">Mather House</option>
          <option value="Pforzheimer">Pforzheimer House</option>
          <option value="Quincy">Quincy</option>
          <option value="Winthrop">Winthrop</option>
          </select></center><Br>
          <span id="entrywaySpan"></span>
          <span id="roomSpan"></span><br>
    
    <? 
    		if (isset($_SESSION["id"])): ?>
    		 &nbsp;&nbsp;&nbsp;Or, choose a saved location:<center>
        <form>
        <select id="saved" name="saved" style="height: 30px; width:300px; font-size:20px;">
          <option value="" selected>Choose A Saved Location</option>  
          

    
    <? 	/* dynamically generate saved locations */
          	$result = mysql_query($query);
          
          	while($row = mysql_fetch_array($result)):
          	
            
           ?>
          <option value="<?= $row['house'] ?>,<?= $row['entryway'] ?>,<?= $row['room'] ?>,<?= $row['level'] ?>,<?= $row['lat'] ?>,<?= $row['lng'] ?>"><?= $row["house"] . " " . $row["entryway"] . " " . $row["room"] ?></option>
        <? endwhile; ?>
          </select>    </center>
    </form><? endif; ?>
    </div>
    <div id="map"></div>

  </body>
</html>
