<?php
/*
this is a PHP script that is part of a user authentication and login process. 
it begins by including two required PHP files: auth.php and MainClass.php 
then, it retrieves the user data for the currently logged in user from the get_user_data() 
method of the MainClass object, and stores it in the $user_data variable. If the user
data is successfully retrieved, the script loops through each key-value pair in the 
data and creates a variable with the same name as the key, and assigns the corresponding value to it
*/

require_once('auth.php');
require_once('MainClass.php');

//assign the result of calling the get_user_data() method on an object called $class, 
//pass in the value of the otp_verify_user_id key in the $_SESSION as an argument 
//the value is then decoded from json and stored in the $user_data variable
$user_data = json_decode($class->get_user_data($_SESSION['otp_verify_user_id']));

//if the status of $user_data object is true, the code iterates over the data property
//of the $user_data object using a foreach loop
if($user_data->status){

    //for each key value pair in the data object, the code creates a new variable
    //with the name of the key and sets its value to the value of the key value pair
    foreach($user_data->data as $k => $v){
        $$k = $v;
    }
}

//check if the resend parameter of the $_GET is set to true. 
//if true, the code decodes the json data returned by the resend_otp() method of the $class object and stores it in the $resend variable
if(isset($_GET['resend']) && $_GET['resend'] == 'true'){
    $resend = json_decode($class->resend_otp($_SESSION['otp_verify_user_id']));
    
    //if status of the $resend object is set to success, the code redirects the user to the login_verification.php page
    if($resend->status == 'success'){
        echo "<script>location.replace('./login_verification.php')</script>";
    }
    //set the flashdata variables type and msg in the $_SESSION and logs an error message to the console
    else{
        $_SESSION['flashdata']['type']='danger';
        $_SESSION['flashdata']['msg']=' Resending OTP has failed.';
        echo "<script>console.error(".$resend.")</script>";
    }
}

//check if the request method used to access the script is post
if($_SERVER['REQUEST_METHOD'] == 'POST'){

    //call otp_verify() method of the $class object and decodes the json string that is returned
    $verify = json_decode($class->otp_verify());

    //if status of the $verify object is equal to the string 'success'
    if($verify->status == 'success'){

        //check if value of the $email is equal to the string 'arshadkhalid.15@icloud.com'
        if($email == 'arshadkhalid.15@icloud.com'){
            
            //redirect the user to index_admin.php
            header("Location: index_admin.php");}
        else{
            //redirect the user to index.php
            header("Location: index.php");
        }
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
    a.disabled {
        pointer-events: none;
        cursor: default;
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
<div class="card-header py-1">
<h4 class="card-title text-center">LOGIN - 2 Factor Authentication</h4>
</div>
<div class="card-body py-4">
<div class="container-fluid">

<form action="./login_verification.php" method="POST">

<input type="hidden" name="id" value="<?= isset($id) ? $id : '' ?>">

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

<div class="form-group"> <p class="">OTP has been sent to your registered email [<?= isset($email) ? $email : '' ?>].</p>
</div>
<div class="form-group">
<label for="otp" class="label-control">Please Enter the OTP</label>
<input type="otp" name="otp" id="otp" class="form-control rounded-0" value="" maxlength="6" pattern="{0-9}+" autofocus required>
</div>
<div class="clear-fix mb-4"></div>
<div class="form-group text-end">
<a class="btn btn-secondary bg-gradient rounded-0  <?= time() < strtotime($otp_expiration) ? 'disabled' : '' ?>" data-stat="<?= time() < strtotime($otp_expiration) ? 'countdown' : '' ?>" href="./login_verification.php?resend=true" id="resend"><?= time() < strtotime($otp_expiration) ? 'Resend in '.(strtotime($otp_expiration) - time()).'s' : 'Resend OTP' ?></a>

<button class="btn btn-primary bg-gradient rounded-0">Confirm</button>
</div>
</form>
</div>
</div>
</div>
</div>

</main>
</body>

<script>

//create a resend button via javascript for an OTP that counts down the time until the OTP expires, and the button will become clickable again
$(function(){

//create new variable and set it to true if an element with "resend" has a "data-stat" attribute with a value of "countdown", and false otherwise
var is_countdown_resend = $('#resend').attr('data-stat') == 'countdown';

//check if 'is_countdown_resend' is true. if so, the code inside the following block will be executed
if(is_countdown_resend){

    //create new variable 'sec' and initializes it to the number of seconds remaining until the otp_expiration time, 
    //or 0 if the current time is already past the 'otp_expiration' time
    var sec = '<?= time() < strtotime($otp_expiration) ? (strtotime($otp_expiration) - time()) : 0 ?>';

    //set a timer that runs every 1000 milliseconds using the 'setInterval' function 
    var countdown = setInterval(() => {
    if(sec > 0){
        //this updates the text of the element with the "resend" to show the number of seconds remaining in the countdown
        sec--;
        $('#resend').text("Resend in "+(sec)+'s')
        }else{
            //when the countdown reaches 0, the code removes the "disabled" class sets its text to "Resend OTP"
            $('#resend').attr('data-stat','')
            .removeClass('disabled').text('Resend OTP')

            //the timer is then cleared using the 'clearInterval' function
            clearInterval(countdown)
        }
    }, 1000);
}
})
</script>
</html>




