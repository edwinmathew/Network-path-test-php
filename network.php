<?php
require("dbconnection.php");
class Network_search extends Database{
	
	 public $csv_array;
	 public $line;
	 function __construct() {
			parent::__construct();
		}
		
	//To get the csv file name
	
	function get_csv_file(){
		echo "Enter csv file name". PHP_EOL;
		$f = fopen( 'php://stdin', 'r' );
		$this->line = fgets( $f );
		$this->line =$this->clean_input_data($this->line);
		fclose( $f );
		$this->process_csv();
	}
	
	
	//To process the csv data
	
	function process_csv()
	{
		if(file_exists(trim($this->line))){
			$row = 1;
			if (($handle = fopen(trim($this->line), "r")) !== FALSE) {
				while (($data = fgetcsv($handle)) !== FALSE) {
					$num = count($data);
					$row++;
					$row_array=array();
					for ($c=0; $c < $num; $c++) {
						$data[$c] =$this->clean_input_data($data[$c]);
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
			$this->truncate_table();
			$this->insert_csv();
		}else{
			echo "Invalid file path". PHP_EOL;
			$this->get_csv_file();
		}
		
	} 
	
	// Delete existing data
	
	function truncate_table()
	{
		$sql = "TRUNCATE TABLE paths";
		
		$a=mysqli_query(self::$conn,$sql);

	}
	
	//Insert csv data into database
	
	function insert_csv()
	{
		$sql = "INSERT INTO paths (source, destination,signal_time) VALUES " . implode (', ',  $this->csv_array) . "";
		
		$a=mysqli_query(self::$conn,$sql);
		$this->get_user_input();
	}
	
	// Get the user input continuously
	
	function get_user_input()
	{
		
		$f = fopen( 'php://stdin', 'r' );
		$line = fgets($f);
		$this->line = $this->clean_input_data($line);
		
		while (trim($this->line)!='QUIT') {
			$this->find_path($line);
			$this->line = fgets($f);
			
		}
		fclose( $f );
		
		exit(0);
	}
	
	
	// To find the path
	
	function find_path()
	{
		$test_data  	= explode(' ',trim($this->line));
		$start_node		=  $this->clean_input_data($test_data['0']);
		$end_node		=  $this->clean_input_data($test_data['1']);
		$maximum_time	=  $this->clean_input_data($test_data['2']);
		$sql = "WITH RECURSIVE cte AS(SELECT p.destination,concat(p.source, '=>', p.destination) 	AS path,signal_time,path_id FROM paths p WHERE p.source = '".$start_node."' UNION ALL SELECT p.destination, concat(c.path, '=>', p.destination) AS path,p.signal_time + c.signal_time,p.path_id+c.path_id FROM cte c JOIN paths p ON p.source = c.destination) SELECT c.path,c.signal_time,c.path_id FROM cte c WHERE c.destination = '".$end_node."' AND c.signal_time <='".$maximum_time."' ORDER BY c.path_id LIMIT 1;";
		$result=mysqli_query(self::$conn,$sql);
		if (mysqli_num_rows($result) > 0) {
		  // output data of each row
		  while($row = $result->fetch_assoc()) {
			echo $row['path'].'=>'.$row['signal_time']. PHP_EOL;
		  }
		} else {
		 $this->find_path_reverse();
		}
	}
	
	// To find the path reverse order
	
	function find_path_reverse()
	{
		$test_data  	= explode(' ',trim($this->line));
		$start_node		=  $this->clean_input_data($test_data['0']);
		$end_node		=  $this->clean_input_data($test_data['1']);
		$maximum_time	=  $this->clean_input_data($test_data['2']);
        $sql = "WITH RECURSIVE cte AS(SELECT p.destination,concat(p.destination,'=>',p.source) 	AS path,signal_time,path_id FROM paths p WHERE p.source = '".$end_node."' UNION ALL SELECT p.destination, concat(p.destination,'=>',c.path) AS path,p.signal_time + c.signal_time ,p.path_id+c.path_id FROM cte c JOIN paths p ON p.source = c.destination) SELECT c.path,c.signal_time,c.path_id FROM cte c WHERE c.destination = '".$start_node."' AND c.signal_time <='".$maximum_time."' ORDER BY c.path_id LIMIT 1;";
		$result=mysqli_query(self::$conn,$sql);
		if (mysqli_num_rows($result) > 0) {
		
		  // output data of each row
		  while($row = $result->fetch_assoc()) {
			echo $row['path'].'=>'.$row['signal_time']. PHP_EOL;
		  }
		} else {
		  echo "No path found". PHP_EOL;
		}
	}
	
	//To clean user input data
	
	function clean_input_data($data)
	{
		$data = htmlspecialchars($data);
		$data = stripslashes($data);
		$data = trim($data);
		$data = mysqli_real_escape_string(self::$conn,$data);
		return $data;
	}
	
}
$network = new Network_search();
$network->get_csv_file();

?>