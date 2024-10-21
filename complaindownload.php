<?php 
/* PHP script for exporting data from a database table as a csv file. The script first loads a database configuration file, 
LOGdbconfig.php, which contains the database information.
*/

//load the database configuration file 
include_once 'LOGdbconfig.php'; 
 
//select query to retrieve all records from the user_complains table in the database, sorted by the serial_no column in ascending order
$query = $db->query("SELECT * FROM user_complains ORDER BY serial_no ASC"); 

//if there are any records in the user_complains table, the script then creates a csv file and sets the column headers for the file. 
if($query->num_rows > 0){ 
    $delimiter = ",";
    
    //set the filename for the csv file
    $filename = "user-complains_" . date('Y-m-d') . ".csv"; 
     
    //create a file pointer 
    $f = fopen('php://memory', 'w'); 
     
    //set column headers 
    $fields = array('ID', 'First Name', 'Last Name', 'Age', 'Address', 'Gender', 'Incident Date/Time', 'Registration Date/Time', 'Complaint', 'Category'); 
    fputcsv($f, $fields, $delimiter); 
     
    //iterate through the records, formatting each row of data and writing it to the CSV file.
    while($row = $query->fetch_assoc()){  
        $lineData = array($row['serial_no'], $row['fname'], $row['lname'], $row['age'], $row['address'], $row['gender'], $row['incident_datetime'], $row['register_datetime'], $row['complaint'], $row['category']); 
        fputcsv($f, $lineData, $delimiter); 
    } 
     
    //move back to beginning of file 
    fseek($f, 0); 
     
    //set the appropriate headers and outputs the contents of the csv file to the user, allowing them to download the file 
    header('Content-Type: text/csv'); 
    header('Content-Disposition: attachment; filename="' . $filename . '";'); 
     
    //output all remaining data on a file pointer 
    fpassthru($f); 
} 
exit; 
 
?>