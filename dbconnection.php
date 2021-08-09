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
        if (self::$init===TRUE)return;
        self::$init = TRUE;
        self::$conn = new mysqli("localhost", "root", "", "network_test1");
    }
}

?>
