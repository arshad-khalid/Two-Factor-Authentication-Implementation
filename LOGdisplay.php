<!doctype html>

<html lang="en">

<head>
<style>
    table {
        border-collapse: collapse;
        width: 100%;}
    th, td {
        border: 1px solid black;
        padding: 8px;
        text-align: left;}
    th {
        background-color: #F2AB40;
        color: white;}
    tr:nth-child(even) {
        background-color: #f2f2f2;}
</style>

<meta charset="UTF-8">

<title>Database Logs</title>

</head>
<div class="form-group text-cneter">
<input type="button" value="Back" onclick="location='index_admin.php'" />
<input type="button" value="Download Logs (.csv)" onclick="location='LOGdownload.php'" />
<hr>
</div>
<body>

<?php
/* This PHP script connects to a MySQL database specified in the LOGdbconfig.php file and 
retrieves records from a table named user_Logs. It then prints the records in an HTML table
if there are no records in the table or if there is an error connecting to the database, 
it prints an error message. */

// code below includes the LOGdbconfig.php file, which contains the database connection configuration
include_once 'LOGdbconfig.php';

//query that selects all columns (*) from the user_Logs table
$sql = "SELECT * FROM user_Logs";

//below line checks if the query executed successfully by calling the mysqli_query() function 
//if the query was successful, the $result variable will contain the query result.
if($result = mysqli_query($db, $sql)){

    //checks if the query returned any rows by calling the mysqli_num_rows() function
    //if the query returned at least one row, the code inside the if block is executed
    if(mysqli_num_rows($result) > 0){
        
        //create table and insert table head
        echo "<table>";
            echo "<tr>";
                echo "<th>USER ID</th>";
                echo "<th>Email</th>";
                echo "<th>URL</th>";
                echo "<th>IP Address</th>";
                echo "<th>Browser</th>";
                echo "<th>Login Attempt </th>";
            echo "</tr>";
            
        //insert the contents of each data in corresponding column
        while($row = mysqli_fetch_array($result)){
            echo "<tr>";
                echo "<td>" . $row['id'] . "</td>";
                echo "<td>" . $row['email'] . "</td>";
                echo "<td>" . $row['page_url'] . "</td>";
                echo "<td>" . $row['user_ip_address'] . "</td>";
                echo "<td>" . $row['user_agent'] . "</td>";
                echo "<td>" . $row['created'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        //free the memory used by the query result by calling the mysqli_free_result() function
        mysqli_free_result($result);

    }//if no records found 
    else{
        echo "No records matching your query were found.";
    }
} else{

    //print an error message if there was an error executing the query 
    //the mysqli_error() function is used to retrieve the error message from the database connection
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
}
?>
</tbody>
</table>
</body>
</html>