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
    
    <!-- paying credit where credit is due --!>
    <meta name="author" content="Richard Milton, Centre for Advanced Spatial Analysis (CASA), University College London (UCL)">
    <meta name="description" content="Page automatically created by GMapImageCutter created by CASA">
    <meta name="keywords" content="Google, Maps, Image, Images, Tile, Cutter, GMapImageCutter, GMapCreator">

    <title>Harvard RoomFinder</title>
    <script src="http://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAAIGF_rnrCqxtTz9PaXs_f-RTDDDXRV8afaazDnDes2f8C3uXi9BR74yz0oqMkYbicZsFSgMiVIHQ_Ow"
            type="text/javascript"></script>
	<script src="jquery.js"></script>
    <link href="css/indexstyles.css" rel="stylesheet" type="text/css">
	<script src="js/index.js"></script>
  </head>
  
  <body onresize="resizeMapDiv()" onload="load()" onunload="GUnload()">
  
  
	<!-- create the header --!>
	<div class="top">
		<span class="left">  
        <!-- embed the facebook like button --!>
		<div id="fb-root"></div>
		<div class="fb-like" data-href="http://www.google.com" data-send="true" data-width="450" data-show-faces="false"></div>
		</span>
		
		<!-- build either the login form or the logout link --!>
		<span class="right">

		<? 
		
		// check if someone is logged in
		if (isset($_SESSION["id"])) {
		
			$result = mysql_query($sql);
			$row = mysql_fetch_array($result);
			
			// welcome them, and allow them to logout if necessary
			print("Welcome back, <b>" . $row['username'] . "</b>! &nbsp;&nbsp;&nbsp;<br>Not you? <a href='logout.php'>Logout</a>&nbsp;&nbsp;&nbsp;");
            }
            
            // otherwise, print out login form
            else {
            	print("<form id='login' action='login.php' method='post'>
        				<span id='verify'>
        					<font color='black'>Login to save locations: </font>
        				</span>
        					&nbsp;&nbsp;&nbsp;<b>Username: <input id='user' name='username' type='text' size='8'> 
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
          <span id="entrywaySpan"><!-- this is where the entryway selection will go --!></span>
          <span id="roomSpan"><!-- this is where the room selection will go --!></span><br>
    
    <? 
    		// if user is logged in, try to grabbed saved locations
    		if (isset($_SESSION["id"])): ?>
    		 &nbsp;&nbsp;&nbsp;Or, choose a saved location:<center>
        <form>
        <select id="saved" name="saved" style="height: 30px; width:300px; font-size:20px;">
          <option value="" selected>Choose A Saved Location</option>  
          

    
    <? 	// dynamically generate saved locations 
          	$result = mysql_query($query);
          
            // fetch saved rooms from database
          	while($row = mysql_fetch_array($result)):
          	
            
           ?>
          <option value="<?= $row['house'] ?>,<?= $row['entryway'] ?>,<?= $row['room'] ?>,<?= $row['level'] ?>,<?= $row['lat'] ?>,<?= $row['lng'] ?>"><?= $row["house"] . " " . $row["entryway"] . " " . $row["room"] ?></option>
        <? endwhile; ?>
          </select>
          </center>
    </form><? endif; ?>
    </div>
    <div id="map"></div>

  </body>
</html>
