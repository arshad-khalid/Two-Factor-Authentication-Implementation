<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title> Verify | Login with 2FA</title>
    
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
        height:100%;
        display:flex;
        flex-flow:column;
    }
</style>

</head>
<body>
<main>
    <nav class="navbar navbar-expand-lg navbar-light bg-warning bg-gradient" id="topNavBar">
    <div class="container">
        <a class="navbar-brand" href="#">Moodle - APU</a>
    </div>
    </nav>

    <div class="container py-3" id="page-container">
    <div class="row justify-content-center">
    <div class="col-lg-5 col-md-8 col-sm-12 col-xs-12">
    <div class="card shadow rounded-0">
    <div class="card-body py-4">
    <h1>Moodle verification </h1>
    <hr>
    <p>To ensure the security of your account, we require two-factor authentication.</p>
    
    <p>Please verify yourself with the authentication of your choice:</p>
    <hr>

    <div class="clear-fix mb-4"></div>
    <div class="text-end">
    <a href="./login_verification.php" class="btn btn btn-secondary bg-gradient rounded-0">Email OTP</a>
    <a href="./googlelogin.php" class="btn btn btn-secondary bg-gradient rounded-0"> Authenticator App</a>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
</main>
</body>
</html>