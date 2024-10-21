<?php
/*
PHP script that is used to insert data into a database table called "user_complains"
the script starts by including a file called "LOGdbconfig.php", 
which establishes a connection to the database
the html form below allows user to enter their details to be sumbitted
*/

include("LOGdbconfig.php");  // connecting to database

//line below checks whether the "b1" and "q1" values have been set in the POST request
if (isset($_POST['b1'])  && !empty($_POST['q1'])) {
  
  //insert the values of several other POST variables into the database table                   
    mysqli_query($db,
    "insert into user_complains set fname  = '".$_POST['q1']."'  , lname   = '".$_POST['q2']."',
      age   = '".$_POST['q3']."', address  = '".$_POST['q4']."'  ,
      gender    = '".$_POST['q5']."'  , incident_datetime  = '".$_POST['q6']."',
      register_datetime  = '".$_POST['q7']."', complaint   = '".$_POST['q8']."',
      category  = '".$_POST['q9']."'");           
      
      //if the insertion is successful, the code displays an alert message to the user
      if (isset($_POST['b1']) && !empty($_POST['q1'])){
        echo '<script>alert("Your Complaint has been successfully registered")</script>';
      }           
}
?>

<html lang="en">
<head>

<meta charset="utf-8">
<link rel="stylesheet" href="Login/css/firstat.css">
<meta name="viewport" content= "width=device-width, initial-scale=1, shrink-to-fit=no">

<link href= "https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity= "sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
      crossorigin="anonymous">

<title>Register your complaint</title>

<style type="text/css">
        .registerform{
            margin-top: 5%;
            width:50vw;
            max-width: 90vw;
        }
        .form-group{
            margin: 2vw;
        }       
</style>

</head>
<body>
<h1 align="center" style="color:black;">
  Register Your Complaint Here</h1>
 
  <center>
    <div class=" card registerform">
      <form name="frm" action="?" method="post" align="center">
         
      <div class="form-group">
        <label>First Name</label>
        <input type="text" class="form-control" placeholder="Enter first name" name="q1" id="q1" required>
      </div>

      <div class="form-group">
        <label>Last Name</label>
        <input type="text" class="form-control" placeholder="Enter last name" name="q2" id="q2" required>
      </div>
           
      <div class="form-group">
        <input type="number"class="form-control" placeholder="Enter Age" name="q3" id="q3" required min="0" max="100">
      </div>
           
      <div class="form-group">
        <label>Address</label>
        <textarea class="form-control" placeholder="Enter relevant address" name="q4" id="q4" required></textarea>
      </div>
           
      <div class="form-group">
        <label>Gender</label>
        <select class="form-control" name="q5" id="q5" required>
          <option>Male      </option>
          <option>Female    </option>
          <option>Other     </option>
          </select>
      </div> 
           
      <div class="form-group">
        <label>Date and Time of incident</label>
        <input type="datetime-local" class="form-control"
          placeholder="Enter date and time (YYYY-MM-DD hh:mm)" name="q6" id="q6" required
          pattern="[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}"
          min="2022-01-01T00:00"
          max="2022-12-31T23:59">
      </div>

      <div class="form-group">
        <label>Date and time of Registration</label>
        <input type="datetime-local" class="form-control"
          placeholder="Enter date and time" name="q7" id="q7" required
          pattern="[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}"
          min="2022-01-01T00:00"
          max="2022-12-31T23:59">
      </div>
           
      <div class="form-group">
        <label>Please write your complaint below</label>
        <textarea type="text" class="form-control"
          placeholder="Enter your complaint with as much details as possible"
          name="q8" id="q8" required></textarea>
      </div>
           
      <div class="form-group">
        <label>Select Complaint Type</label>
        <select class="form-control" name="q9" id="q9" required>
        <option>Lost and Found      </option>  
        <option>Attempt to Murder   </option>
        <option>Theft               </option>
        <option>Damages             </option>
        <option>Violence            </option>
        <option>Stolen Vehicle      </option>
        <option>Missing Person      </option>
        <option>Sexual Harrasment   </option>
        <option>Others              </option>
        </select>
      </div>   
                     
      <button class="btn btn-lg btn-warning" type="submit" id="b1" name="b1">Submit</button>
      <button class="btn btn-lg btn-warning" type="reset"  id="b2" name="b2">Reset</button>

</form>      
</div>
</center>
 </body>
</html>