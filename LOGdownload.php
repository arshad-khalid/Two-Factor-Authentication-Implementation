<?php 
 
//load the database configuration file 
include_once 'LOGdbconfig.php'; 
 
//execute query that selects all records from the user_logs table, ordered by the id field in ascending order 
$query = $db->query("SELECT * FROM user_logs ORDER BY id ASC"); 

//if there are any records in the result set, the code in the following block will be executed
if($query->num_rows > 0){

    //sets the delimiter used to separate values in the CSV file to a comma 
    $delimiter = ",";
    
    //set the filename of the csv file to be generated, using the current date in the format yyyy-mm-dd
    $filename = "user-logs_" . date('Y-m-d') . ".csv"; 
     
    //creates a file pointer $f that points to a temporary memory location where the csv file will be written 
    $f = fopen('php://memory', 'w'); 
     
    //create array containing the column headers for the csv file
    $fields = array('ID', 'EMAIL', 'URL', 'IP ADDRESS', 'BROWSER', 'LOGIN ATTEMPT'); 
    
    //write the field names of the data to the file as a csv row
    fputcsv($f, $fields, $delimiter); 
     
    //iterates over the rows of data returned by a database query, formats each row as a csv row, and writes it to the file 
    while($row = $query->fetch_assoc()){ 
        $lineData = array($row['id'], $row['email'], $row['page_url'], $row['user_ip_address'], $row['user_agent'], $row['created']); 
        fputcsv($f, $lineData, $delimiter); 
    } 
     
    //move back to beginning of file 
    fseek($f, 0); 
     
    //after the data has been written to the file, the script moves the file pointer back to the beginning of the file, 
    //sets the http response headers to force the browser to download the file
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
     
    //output all remaining data on a file pointer 
    fpassthru($f); 
} 
exit; 
 
?>