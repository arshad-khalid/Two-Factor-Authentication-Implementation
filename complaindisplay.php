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

<title>Complaints</title>

</head>

<div class="form-group text-cneter">
    <input type="button" value="Back" onclick="location='index_admin.php'" />
    <input type="button" value="Download All Complaints (.csv)" onclick="location='complaindownload.php'" />
    <hr>
</div>
<body>
      
<?php
/*This PHP script connects to a MySQL database specified in the LOGdbconfig.php file 
and retrieves records from a table named user_complains. It then prints the records in an HTML table. 
If there are no records in the table or if there is an error connecting to the database, it prints an error message.
*/

//line below includes the LOGdbconfig.php file, which contains the database connection configuration
include_once 'LOGdbconfig.php';

//line below defines a SQL query that selects all columns (*) from the user_complains table.
$sql = "SELECT * FROM user_complains";

/*code below checks if the query executed successfully by calling the mysqli_query() function. 
If the query was successful, the $result variable will contain the query result*/
if($result = mysqli_query($db, $sql)){

    //Tcode below checks if the query returned any rows by calling the mysqli_num_rows() function. 
    //If the query returned at least one row, the code inside the if block is executed.
    if(mysqli_num_rows($result) > 0){
        
        //create a table
        echo "<table>";
        echo "<tr>"; //'tr' new row, 'th' new table header 
        echo "<th>ID</th>";
        echo "<th>First Name</th>";
        echo "<th>Last Name</th>";
        echo "<th>Age</th>";
        echo "<th>Address</th>";
        echo "<th>Gender </th>";
        echo "<th>Incident Time/Date </th>";
        echo "<th>Registration Time/Date </th>";
        echo "<th>Complaint </th>";
        echo "<th>Category </th>";
        echo "</tr>";
        
        while($row = mysqli_fetch_array($result)){
            echo "<tr>";
            //prints table data the information value of the current row in the query result
            echo "<td>" . $row['serial_no'] . "</td>";
            echo "<td>" . $row['fname'] . "</td>";
            echo "<td>" . $row['lname'] . "</td>";
            echo "<td>" . $row['age'] . "</td>";
            echo "<td>" . $row['address'] . "</td>";
            echo "<td>" . $row['gender'] . "</td>";
            echo "<td>" . $row['incident_datetime'] . "</td>";
            echo "<td>" . $row['register_datetime'] . "</td>";
            echo "<td>" . $row['complaint'] . "</td>";
            echo "<td>" . $row['category'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        
        //code below frees the memory used by the query result by calling the mysqli_free_result() function
        mysqli_free_result($result);

    } else{
        echo "No records matching your query were found.";
    }
    
} else{
    //prints an error message if there was an error executing the query. 
    //the mysqli_error() function is used to retrieve the error message from the database connection
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($db);
}
?>
</tbody>
</table>
</body>
</html>