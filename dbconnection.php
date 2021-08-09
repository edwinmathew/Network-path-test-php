<?php
class Database
{
    /** TRUE if static variables have been initialized. FALSE otherwise
    */
    private static $init = FALSE;
    /** The mysqli connection object
    */
    public static $conn;
    /** initializes the static class variables. Only runs initialization once.
    * does not return anything.
    */
	function __construct() 
	{
        $this->initialize();
    }
	
    public static function initialize()
    {
		/* Set your database details*/
		
		$host 			= "localhost"; 		// Your host
		$user_name 		= "root";			//User name
		$password 		= "";				//Your password
		$db_name 		= "network_test";	//Your database

        if (self::$init===TRUE)return;
        self::$init = TRUE;
        self::$conn = new mysqli($host,$user_name,$password,$db_name);
    }
}

?>
