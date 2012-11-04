<?
/*
 * Register.php
 *
 * Displays registration form, submits form to register2.php
 * 
 *
 */
    // require common code
    require_once("common.php");

?>

<!DOCTYPE html>

<html>

  <head>
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <title>Harvard Room Locator: Register</title>
    <script src="jquery.js"></script>
    <script type="text/javascript">
    
    // listen for form submission, then check validity of forms without submitting the form
	$(document).ready(function() {  
		$("#register").submit(function() {
			if ($("#user").val() == "") {
				$("#verify").text("Please fill out a  username!").show().fadeOut(3000);
      			return false;
      		}
      		else if ($("#pass").val() == "") {
      			$("#verify").text("Please fill out a password!").show().fadeOut(3000);
      			return false;
  			}
  			else if ($("#pass").val() != $("#pass2").val()) {
      			$("#verify").text("Passwords do not match!").show().fadeOut(3000);
      			return false;
  			}
  			else return true;
		});
	});
	
	</script>
  </head>

  <body>
   <br><Br> <br><Br>
  <div class="test">
  <div>
  <!-- Rounded Corners implementation courtesy of http://www.spiffycorners.com/ --!>
  <b class="login">
  <b class="login1"><b></b></b>
  <b class="login2"><b></b></b>
  <b class="login3"></b>
  <b class="login4"></b>
  <b class="login5"></b></b>

  <div class="loginfg">
 <div id="top"> <br><Br>
      <a href="index.php"><img alt="Harvard RoomFINDER" src="harvardroomfinder3.png"></a>
    </div>


    <div id="middle">
      <form id="register" action="register2.php" method="post">
        <table>
          <tr>
            <td>Username:</td>
            <td><input id="user" name="username" type="text"></td>
          </tr>
          <tr>
            <td>Password:</td>
            <td><input id="pass" name="password" type="password"></td>
          </tr>
          <tr>
            <td>Password (again):</td>
            <td><input id="pass2" name="password2" type="password"></td>
          </tr>
          <tr>
          <td></td>
            <td colspan="2"><input type="submit" value="Register"></td>
          </tr>
        </table><br>
        <center><span id="verify">&nbsp;</span></center>
      </form>
    </div>
 <br>
    <div id="bottom">
      or <a href="index.php">log in</a> if you already have an account
    </div><br>
  </div>

  <b class="login">
  <b class="login5"></b>
  <b class="login4"></b>
  <b class="login3"></b>
  <b class="login2"><b></b></b>
  <b class="login1"><b></b></b></b>
</div>
</div>
</div>
  </body>

</html>
