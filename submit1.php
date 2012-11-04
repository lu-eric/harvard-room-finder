<? /*
	* submit1.php
	*
	* processes submitted house data
	* updates selection list for entryways 
	* prepares for selection of entryways
	* updates map accordingly
	*
	*/
	
	require_once("common.php");
    
    // process house selection
	$house = $_POST["house"];
    
    // prepare script for the submission of the entryway form (generated below)
    $html = "<script type='text/javascript'>
	 $(document).ready(function() {
 		$('#entryway').change(function() {
 			if ($('#entryway').val() != '') {
 				$.ajax({
 				
 					// create AJAX command
 					url: 'submit2.php',
 					data: 'house=' + $('#house').val() + '&entryway=' + $('#entryway').val(),
 					type: 'POST',
					success: function(html) {
					
						// update html, inserting selection menu for room
						$('#roomSpan').html(html);
						$('#roomSpan').show();
					}
				});
			}
		});
	 });
	 </script>";
	
	$html .= "&nbsp;&nbsp;&nbsp;Now, choose a general area:<center>";
    $html .= "<select id='entryway' name='entryway' style='height: 30px; width:300px; font-size:20px;'>";
    $html .= "<option value = ''>Choose an area</option>";
    
    // id in this case represents the first entry of each entryway, so it only shows up on the select list once
    $result = mysql_query("SELECT * FROM $house WHERE id = 1");
    while($row = mysql_fetch_array($result))
    {
    	// dynamically generate option values based on database
    	$html .= "<option value='";
    	$html .= $row["entryway"];
    	$html .="'>";
    	$html .= $row["entryway"];
    	$html .= "</option>";
    };
    
    $html .= "</select><br><br></center>";
    
    // print out the html for AJAX
	echo($html);
	
	
?>