<?
/*
 * Save.php
 *
 * Receives form submission from submit3.php
 * Saves location to database if user is logged in
 *
 */

    // require common code
    require_once("common.php"); 
    
	// get session id
    if (isset($_SESSION["id"])) {
		$id = $_SESSION["id"];

    // add cash to users portfolio
    $house = $_POST["house"];
    $entryway = $_POST["entryway"];
    $level = $_POST["level"];
    $room = $_POST["room"];
    $lat = $_POST["lat"];
    $lng = $_POST["lng"];
    
    // insert transaction into history
    $result = mysql_query("INSERT INTO rooms (id, house, level, entryway, room, lat, lng) VALUES
                                ($id, '$house', $level, '$entryway', '$room', $lat, $lng)");
	
	redirect('index.php');
	}
	else apologize("Sorry, you must be logged in to save a location!");

?>
