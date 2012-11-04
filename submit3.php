<? /*
	* submit3.php
	*
	* processes submitted data
	* generates link for each specific room
	* enables user to save a location to database
	*
	*/


    $id = $_SESSION["id"];
    
   	require_once("common.php");
    
    // get submitted values
    $house = $_POST["house"];
	$entryway = $_POST["entryway"];
	$room = $_POST["room"];
    
    $result = mysql_query("SELECT * FROM $house WHERE entryway = '$entryway' AND room = '$room'");
    $row = mysql_fetch_array($result);

	// prepare information for information popup box
	$details = "<font style='font-family: arial, gerorgia; font-size: 10pt'><b>RoomFINDER has found:</b><br>";
	
	// dynamically generate location
    $details .= $house . " ". $entryway. " " . "<br>" . $room . "<br>Floor " . $row['level'];
	$details .= "<br><br>";
	$details .= "Copy link to this location:<br>";
	
	// generate direct link to location
    $details .= "<input type='text' value='localhost/link.php?house=" . $house . "&entryway=" . $entryway . "&room=" . $room . "&level=" . $row['level'] . "&lat=" . $row['lat'] . "&lng=" . $row['lng'] . "'
  readonly='readonly' />";
  
  // create form to save the location
	$details .= "
	  <form action='save.php' method='post'><br>Or 
        <input type='hidden' name='id' value='" . $id . "'>
        <input type='hidden' name='house' value='" . $house . "'>
        <input type='hidden' name='entryway' value='" . $entryway . "'>
        <input type='hidden' name='level' value='" . $row['level'] . "'>
        <input type='hidden' name='room' value='" . $room . "'>
        <input type='hidden' name='lat' value='" . $row['lat'] . "'>
        <input type='hidden' name='lng' value='" . $row['lng'] . "'>
        <input type='submit' value='save to my locations'>
      </form>";
    
	
	// use json_encode to submit these values back to index.php
	$info = json_encode(array('lat' => $row['lat'], 'lng' => $row['lng'], 'level' => $row['level'], 'room' => $room, 'info' => $details));
	
	echo($info);
	
	
?>