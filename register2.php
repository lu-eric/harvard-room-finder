<?
/*
 * Register2.php
 *
 * This is the linked page from the sent email
 * Inserts values into the database
 *
 */

    // require common code
    require("common.php"); 

    // escape username to avoid SQL injection attacks
    $username = mysql_real_escape_string($_POST["username"]);
    $password = mysql_real_escape_string($_POST["password"]); 
    $password2 = mysql_real_escape_string($_POST["password2"]); 

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysql_query($sql);
                
    // check for previously existing usernames
    if(mysql_num_rows($result) == 1) 
    	apologize('Sorry, that username is already taken!');
    else {
    // update database
    $hash = crypt($_POST["password"]);
    		
    $query = "INSERT INTO users (username, hash) VALUES ('$username', '$hash')";
    $result = mysql_query($query);

    // grab session ID
    $id = mysql_insert_id();
    $_SESSION["id"] = $id;
                            
    // redirect to portfolio
    redirect("index.php");
    
    };
             
?>
