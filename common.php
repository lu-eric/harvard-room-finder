<?

    /***********************************************************************
     * common.php
     *
     * Eric Lu
     * Harvard RoomFINDER
     *
     * Code common to (i.e., required by) most pages.
     **********************************************************************/

    // enable sessions
    session_start();

	 // your database's name (i.e., username_pset7)
    define("DB_NAME", "maps");

    // your database's password
    define("DB_PASSWORD", "password");

    // your database's server
    define("DB_SERVER", "localhost");

    // your database's username
    define("DB_USERNAME", "root");
    
    // connect to database server
    if (($connection = @mysql_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD)) === false)
        apologize("Could not connect to database server.");

    // select database
    if (@mysql_select_db(DB_NAME, $connection) === false)
        apologize("Could not select database (" . DB_NAME . ").");
    
    // enable redirection functionality, adapted directly from pset7
    function redirect($destination)
    {
        // handle URL
        if (preg_match("/^http:\/\//", $destination))
            header("Location: " . $destination);

        // handle absolute path
        else if (preg_match("/^\//", $destination))
        {
            $protocol = (@$_SERVER["HTTPS"]) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            header("Location: $protocol://$host$destination");
        }

        // handle relative path
        else
        {
            // adapted from http://www.php.net/header
            $protocol = (@$_SERVER["HTTPS"]) ? "https" : "http";
            $host = $_SERVER["HTTP_HOST"];
            $path = rtrim(dirname($_SERVER["PHP_SELF"]), "/\\");
            header("Location: $protocol://$host$path/$destination");
        }

        // exit immediately since we're redirecting anyway
        exit;
    }
    
    // log user out of session
	function logout()
    {
        // unset any session variables
        $_SESSION = array();

        // expire cookie
        if (isset($_COOKIE[session_name()]))
        {
            if (preg_match("{^(/~[^/]+/pset7/)}", $_SERVER["REQUEST_URI"], $matches))
                setcookie(session_name(), "", time() - 42000, $matches[1]);
            else
                setcookie(session_name(), "", time() - 42000);
        }

        // destroy session
        session_destroy();
        redirect('index.php');
    }

    // in case of error, apologize
    function apologize($message)
    {
        // require template
        require("apology.php");

        // exit immediately since we're apologizing
        exit;
    }


?>
