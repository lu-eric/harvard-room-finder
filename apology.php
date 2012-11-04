<?
/*
 * Apology.php
 *
 * Template for apology form
 *
 */
 ?>

<!DOCTYPE html>

<html>

  <head>
    <link href="css/styles.css" rel="stylesheet" type="text/css">
    <title>Harvard Room Locator: Error</title>
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

        <center><span id="apology"><?= $message ?></span></center>
    </div>
 <br>
    <div id="bottom">
      <a href="javascript:history.go(-1);">Back</a>
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
