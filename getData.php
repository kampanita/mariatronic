  <?php
   
    
    include('abre_conexion.php');
  
     $query = "SELECT 
                   DATE_FORMAT(fecha,'%d-%m-%Y') as fecha, avg(temp) as temp, avg(hum) as hum ,avg(co2ppm) as co2ppm, avg(higromet) as higromet, avg(luz) as luz
               FROM tempe 
      
      group by DATE_FORMAT(fecha,'%d-%m-%Y')
      order BY fecha asc,hora asc
      ";
  
     $query = "SELECT 
                   DATE_FORMAT(fecha,'%d-%m-%Y') as fecha, avg(temp) as temp, avg(hum) as hum 
               FROM tempe 
      
      group by DATE_FORMAT(fecha,'%d-%m-%Y')
      order BY fecha asc,hora asc
      ";
  
   //where date_format(fecha,'%Y%m%d') = date_format(now(),'%Y%m%d')
    $result = mysqli_query($conexion_db,$query);
   
   
   
// write your SQL query here (you may use parameters from $_GET or $_POST if you need them)

$table = array();
$table['cols'] = array(
	/* define your DataTable columns here
	 * each column gets its own array
	 * syntax of the arrays is:
	 * label => column label
	 * type => data type of column (string, number, date, datetime, boolean)
	 */
	// I assumed your first column is a "string" type
	// and your second column is a "number" type
	// but you can change them if they are not
	  
    array('label' => 'fecha', 'type' => 'string'),
   	array('label' => 'temp', 'type' => 'number'),
   	array('label' => 'hum', 'type' => 'number')
   	#,
   	#array('label' => 'co2ppm', 'type' => 'number'),
   	#array('label' => 'higromet', 'type' => 'number'),
   	#array('label' => 'luz', 'type' => 'number')
);

$rows = array();
while($r = mysqli_fetch_assoc($result)) {
    $temp = array();
	// each column needs to have data inserted via the $temp array
	
	$temp[] = array('v' => (string)$r['fecha']);
	$temp[] = array('v' =>  (float)$r['temp']); // typecast all numbers to the appropriate type (int or float) as needed - otherwise they are input as strings
	$temp[] = array('v' =>  (float)$r['hum']);
  #$temp[] = array('v' =>  (float)$r['co2ppm']); // typecast all numbers to the appropriate type (int or float) as needed - otherwise they are input as strings
	#$temp[] = array('v' =>  (float)$r['higromet']);
  #$temp[] = array('v' =>  (float)$r['luz']); // typecast all numbers to the appropriate type (int or float) as needed - otherwise they are input as strings

	// insert the temp array into $rows
    $rows[] = array('c' => $temp);
}

// populate the table with rows of data
$table['rows'] = $rows;

// encode the table as JSON
$jsonTable = json_encode($table,true);

// set up header; first two prevent IE from caching queries
//echo '<h3>'.$jsonTable.'</h3>'; 

// return the JSON data
//echo $table;
echo $jsonTable;
?>