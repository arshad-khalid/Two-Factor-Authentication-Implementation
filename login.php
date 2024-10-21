<?php
/*
the following PHP code is a login script and contain functions related to authentication
*/
require_once('auth.php');
require_once('MainClass.php');
include_once 'LOGdbconfig.php';

//check if the request method is a post request
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    
    //decodes the json response from login() function
    $login = json_decode($class->login());

    //if status of the decoded json is success, it redirects the user to the auth-otp.php page
    if($login->status == 'success'){
        echo "<script>location.replace('./auth-otp.php');</script>";
    }
}
?>

<!DOCTYPE html>

<html lang="en">

<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Login with OTP</title>

<link rel="stylesheet" href="./Font-Awesome-master/css/all.min.css">
<link rel="stylesheet" href="./css/bootstrap.min.css">

<script src="./js/jquery-3.6.0.min.js"></script>
<script src="./js/popper.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./Font-Awesome-master/js/all.min.js"></script>

<style>
    html,body{
        height:100%;
        width:100%;
    }
    main{
        height:calc(100%);
        width:calc(100%);
        display:flex;
        flex-direction:column;
        align-items:center;
        justify-content:center;
    }
</style>

</head>
<body class="navbar navbar-expand-lg navbar-light bg-warning bg-gradient">

<main>
<div class="col-lg-7 col-md-9 col-sm-12 col-xs-12 mb-4">
<h1 class="text-light text-center">Moodle - APU Login</h1>
</div>
<div class="col-lg-3 col-md-8 col-sm-12 col-xs-12">
<div class="card shadow rounded-0">
<div class="card-header py-1"> <h4 class="card-title text-center">LOGIN</h4> </div>
<div class="card-body py-4">
<div class="container-fluid">
<form action="./login.php" method="POST">

<?php 
if(isset($_SESSION['flashdata'])):
?>

<div class="dynamic_alert alert alert-<?php echo $_SESSION['flashdata']['type'] ?> my-2 rounded-0">
<div class="d-flex align-items-center">
<div class="col-11"><?php echo $_SESSION['flashdata']['msg'] ?></div>
<div class="col-1 text-end">
<div class="float-end"><a href="javascript:void(0)" class="text-dark text-decoration-none" onclick="$(this).closest('.dynamic_alert').hide('slow').remove()">x</a>
</div>
</div>
</div>
</div>

<?php unset($_SESSION['flashdata']) ?>
<?php endif; ?>

<div class="form-group">

<label for="email" class="label-control">Email</label>
<input type="email" name="email" id="email" class="form-control rounded-0" value="<?= $emaillog=isset($_POST['email']) ? $_POST['email'] : '' ?>" autofocus required>
</div>
<div class="form-group">

<label for="password" class="label-control">Password</label>
<input type="password" name="password" id="password" class="form-control rounded-0" value="<?= isset($_POST['password']) ? $_POST['password'] : '' ?>" required>

</div>
<div class="clear-fix mb-4"></div>
<div class="form-group text-end">
<button class="btn btn-primary bg-gradient rounded-0">LOGIN</button>
</div>
                            
<?php

/*
PHP code for a user log script. it retrieves various pieces of information 
about the user and the server, such as the user's ip address, the referrer URL, 
and the user agent. it then inserts this information into a user_logs database table, 
along with the current time and a email value, which is provided by the user
*/

//check the protocol used by the server (HTTP/HTTPS) and constructs the current URL using this information
$protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$currentURL = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING']; 

//retrieves the user's ip address, referrer url, and user agent string from the $_SERVER superglobal array
$user_ip_address = $_SERVER['REMOTE_ADDR']; 
$referrer_url = !empty($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'/'; 
$user_agent = $_SERVER['HTTP_USER_AGENT']; 
                             
//insert user logs into user_log database 
$sql = "INSERT INTO user_logs (email, page_url, referrer_url, user_ip_address, user_agent, created) VALUES (?,?,?,?,?,NOW())"; 
$stmt = $db->prepare($sql); 
$stmt->bind_param("sssss", $emaillog, $currentURL, $referrer_url, $user_ip_address, $user_agent); 
$insert = $stmt->execute(); 
?>

</form>
</div>
</div>
</div>
</div>

</main>
</body>
</html>