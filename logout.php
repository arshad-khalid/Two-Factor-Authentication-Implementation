<?php
/*
PHP code to logout
*/

//check if a session has been started using session_status(), if not, it starts a new session
if(session_status() === PHP_SESSION_NONE)
session_start();

//destroy the current session using session_destroy() & will remove all session data and prevent the user from being logged in
session_destroy();

//loop to unset all session variables, removing them from the $_SESSION array
foreach($_SESSION as $k =>$v){
    unset($_SESSION[$k]);
}

//redirect to the login.php page
echo "<script>location.replace('./login.php');</script>";