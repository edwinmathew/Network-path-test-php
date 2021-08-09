<?php
class Network_search {
	
	 function __construct() {
		
		}
		
		
	function get_user_input(){
		echo "Enter csv file name";
		$f = fopen( 'php://stdin', 'r' );
		$line = fgets( $f );
		$this->process_csv($line);
		while ($line!='QUIT') {
			$line = fgets( $f );
			echo $line;
		}
		fclose( $f );
	}
	
	function process_csv($line)
	{
		$row = 1;
		if (($handle = fopen(trim($line), "r")) !== FALSE) {
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
				 $csv_array[] = "(" . implode(', ', $rowValues) . ")";
				
			}
			fclose($handle);
		}
	}
}
$network = new Network_search();
echo $network->get_user_input();
?>