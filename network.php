<?php
require("dbconnection.php");
class Network_search extends Database{
	
	 public $csv_array;
	 public $line;
	 function __construct() {
			parent::__construct();
		}
		
		
	function get_csv_file(){
		echo "Enter csv file name";
		$f = fopen( 'php://stdin', 'r' );
		$this->line = fgets( $f );
		fclose( $f );
	}
	
	function process_csv()
	{
		$row = 1;
		if (($handle = fopen(trim($this->line), "r")) !== FALSE) {
			while (($data = fgetcsv($handle)) !== FALSE) {
				$num = count($data);
				$row++;
				$row_array=array();
				for ($c=0; $c < $num; $c++) {
					if(!is_numeric($data[$c])){
						$rowValues[$c] ="'".$data[$c]."'";
					}else{
						$rowValues[$c] =$data[$c];
					}
					
				}
				 $this->csv_array[] = "(" . implode(', ', $rowValues) . ")";
				
			}
			fclose($handle);
		}
		
	} 
	
	
	function insert_csv()
	{
		$sql = "INSERT INTO paths (source, destination,signal_time) VALUES " . implode (', ',  $this->csv_array) . "";
		
		$a=mysqli_query(self::$conn,$sql);

	}
	
	
	function get_user_input()
	{
		$f = fopen( 'php://stdin', 'r' );
		$line = fgets($f);
		$this->process_csv($line);
		while (trim($line)!='QUIT') {
			$line = fgets( $f );
			echo $line;
		}
		fclose( $f );
		
		exit(0);
	}
	
	
	
	
}
$network = new Network_search();
$network->get_csv_file();
$network->process_csv();
$network->insert_csv();
$network->get_user_input()
?>