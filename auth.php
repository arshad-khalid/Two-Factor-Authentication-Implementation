<?php
/*
this PHP script checks if the user is logged in. 
If the user is not logged in and is not on the login or registration page, 
the script redirects the user to the login page. 
If the user is on the login verification page but has not requested an OTP for verification, 
the script also redirects the user to the login page. If the user is already logged 
in and is trying to access the login page, the script redirects the user to the home page.
*/

//code below checks if a session has been started by calling the session_status() function.
//if a session has not been started, the code inside the if block is executed, 
//which starts a new session by calling the session_start() function.
if(session_status() === PHP_SESSION_NONE)
session_start();

//get the current page URL and assigns it to the $link variable
$link = $_SERVER['PHP_SELF'];

//this line checks if the user is not on the login, login verification, or 
//registration page and is not logged in. If all of these conditions are true, the code inside the if block is executed.
if(!strpos($link,'login.php') && !strpos($link,'login_verification.php') && !strpos($link,'registration.php') && !isset($_SESSION['user_login'])){
    
    //redirect to the login page
    echo "<script>location.replace('./login.php');</script>";
}

//check if the user is on the login verification page and has not requested an OTP for verification 
//if both of these conditions are true, the code inside the if block is executed.
if(strpos($link,'login_verification.php') && !isset($_SESSION['otp_verify_user_id'])){
    
    echo "<script>location.replace('./login.php');</script>";
}

//This line checks if the user is trying to access the login page while already logged in
//if this condition is true, the code inside the if block is executed.
if(strpos($link,'login.php') > -1 && isset($_SESSION['user_login'])){
    
    echo "<script>location.replace('./');</script>";
}