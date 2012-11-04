<? /*
	* submit2.php
	*
	* processes submitted house and entryway data
	* updates selection list for rooms 
	* prepares for selection of room
	* updates map accordingly
	*
	*/

    
    require_once("common.php");
    
    // get house and entryway data
    $house = $_POST["house"];
	$entryway = $_POST["entryway"];
    
    // prepare for submission of the room, then update the map accordingly
    $html = "<script type='text/javascript'>
	 $(document).ready(function() {
 		$('#room').change(function() {
 			if ($('#room').val() != '') {
 				$.ajax({
 				
 					// submit information for processing
 					url: 'submit3.php',
 					data: 'house=' + $('#house').val() + '&entryway=' + $('#entryway').val() + '&room=' + $('#room').val(),
 					type: 'POST',
 					dataType: 'json',
					success: function(data) {
					
						// break up encoded array for interpretation
						level = parseInt(data.level);
						lat = data.lat;
						lng = data.lng;
						
						// create new center for map
						var center = new GLatLng(lat, lng);
						
						// remove any previous markers
						map.removeOverlay(marker);
						
						// update map
						map.setCenter(center, 3, pic_customMap);
        				marker = new GMarker(center, {title: data.room});
        				map.addOverlay(marker);
        				
        				// open window corresponding with the marker
        				marker.openInfoWindowHtml(data.info);
        				GEvent.addListener(marker, 'click', function() {
  							marker.openInfoWindow(data.info);
  						});
  					}

				});
			}
			});
		});
	 
	 </script>";
	 
	// dynamically generate the selection list for rooms
	$html .= "&nbsp;&nbsp;&nbsp;Finally, choose a room or location:<center>"; 
    $html .= "<select id='room' name='room' style='height: 30px; width:300px; font-size:20px;'>";
    $html .= "<option value = ''>Choose an room</option>";
    
    
    $result = mysql_query("SELECT * FROM $house WHERE entryway = '$entryway'");
    while($row = mysql_fetch_array($result))
    {
    	$html .= "<option value='";
    	$html .= $row["room"];
    	$html .="'>";
    	$html .= $row["room"];
    	$html .= "</option>";
    };
    
    $html .= "</select>";
    
    // print this out 
	echo($html);
	
	
?>