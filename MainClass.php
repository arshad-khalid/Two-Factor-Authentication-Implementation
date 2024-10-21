<?php
/*
PHP code with a class called 'MainClass' that contains methods for registering 
a user, logging in a user, verifying a user's otp, and sending an otp to a user's email
*/

//check if a session has already been started for the php script 
if(session_status() === PHP_SESSION_NONE)

//if session not started, the session_start function is called to start a new session
session_start();

Class MainClass{
    protected $db;

    //connect mysql database 'login_otp_db' using the mysqli interface
    function __construct(){

        //$db property of the object will be used to interact with the database
        $this->db = new mysqli('localhost','root','','login_otp_db');
        
        if(!$this->db){

            //display error message if the connection fails
            die("Database Connection Failed. Error: ".$this->db->error);
        }
    }

    function db_connect(){

        //return the $db property of the current object
        return $this->db;
    }

    //register function to register a new user in the database
    public function register(){

        //loop through the $_POST array and assigns each key to the variable $k and each value to the variable $v
        foreach($_POST as $k => $v){

            //escape $v value using the real_escape_string(), which is used to prevent sql injection attacks
            $$k = $this->db->real_escape_string($v);
        }

        //hash the $password using password_hash() with the default algorithm to securely store the password in the database
        $password = password_hash($password, PASSWORD_DEFAULT);

        //query to select all records from 'users' table where the email field matches the $email variable
        //it uses the num_rows to check if any records are found
        $check = $this->db->query("SELECT * FROM `users` where `email`= '$email}' ")->num_rows;

        //if at least one record with the provided email is found in the database
        if($check > 0){

            //set the status of the response to "failed" if email already exists in the database
            $resp['status'] = 'failed';

            //indicate and display that email exists
            $_SESSION['flashdata']['type']='danger';
            $_SESSION['flashdata']['msg'] = ' Email already exists.';

        }

        //if email does not exist in the database
        else{
            
            //construct sql 'insert' query using the user details and assign it to $sql
            $sql = "INSERT INTO `users` (firstname,middlename,lastname,email,`password`) VALUES ('$firstname','$middlename','$lastname','$email','$password')";
            $save = $this->db->query($sql);

            //if query is succesfull
            if($save){
                $resp['status'] = 'success';
            }
            
            //if query has failed
            else{
                $resp['status'] = 'failed';
                $resp['err'] = $this->db->error;
                $_SESSION['flashdata']['type']='danger';
                $_SESSION['flashdata']['msg'] = ' An error occurred.';
            }
        }

        //return $resp as a json encoded string (easily transmit data between this php script to any other client)
        return json_encode($resp);
    }
    public function login(){

        //extracts the email and password values from the post data
        extract($_POST);

        //query that selects all columns from the 'users' table where the 'email' column is equal to the 'email' variable
        $sql = "SELECT * FROM `users` where `email` = ? ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('s',$email);
        $stmt->execute();
        $result = $stmt->get_result();

        //check if the result query has at least one row of data. if the result is not empty, 
        //it means that the query found a user with the given password in the database
        if($result->num_rows > 0){

            //fetches the data from the first row of the query result and assigns it to the $data variable as an array
            $data = $result->fetch_array();

            //password_verify() function to check if the password entered by the user matches the hashed password in the database
            $pass_is_right = password_verify($password,$data['password']);
            
            //no otp has been generated yet
            $has_code = false;

            //check if password is correct, and if the user either doesn't have an otp OR has an otp that has expired
            //if conditions are true, the code block will be executed
            if($pass_is_right && (is_null($data['otp']) || (!is_null($data['otp']) && !is_null($data['otp_expiration']) && strtotime($data['otp_expiration']) < time()) ) ){
                
                //generate a random 6-digit otp
                $otp = sprintf("%'.06d",mt_rand(0,999999));

                //expiration time ofr the otp
                $expiration = date("Y-m-d H:i" ,strtotime(date('Y-m-d H:i')." +1 mins"));

                //update database to add the generated otp and expiration time
                $update_sql = "UPDATE `users` set otp_expiration = '{$expiration}', otp = '{$otp}' where id='{$data['id']}' ";
                $update_otp = $this->db->query($update_sql);
                
                //if otp has been updated and stored in database
                if($update_otp){
                    $has_code = true;
                    $resp['status'] = 'success';
                    $_SESSION['otp_verify_user_id'] = $data['id'];

                    //call the send_mail method and pass the user's email and the otp
                    $this->send_mail($data['email'],$otp);
                }
                
                //if otp has not been updated and stored in database, and displays an error
                else{
                    $resp['status'] = 'failed';

                    //'flashdata' to store temporary error messages that is displayed to the user on the page
                    $_SESSION['flashdata']['type'] = 'danger';
                    $_SESSION['flashdata']['msg'] = ' An error occurred while loggin in. Please try again later.';
                }
                
            }
            
            //if password is incorrect
            else if(!$pass_is_right){
               $resp['status'] = 'failed';
               $_SESSION['flashdata']['type'] = 'danger';
               $_SESSION['flashdata']['msg'] = ' Incorrect Password';
            }
        }
        
        //if email is incorrect
        else{
            $resp['status'] = 'failed';
            $_SESSION['flashdata']['type'] = 'danger';
            $_SESSION['flashdata']['msg'] = ' Email is not registered.';
        }
        
        //return the $resp array as a json encoded string
        return json_encode($resp);
    }
    
    //retrieve user data from database
    public function get_user_data($id){

        //import all variables from the $_POST array
        extract($_POST);
        $sql = "SELECT * FROM `users` where `id` = ? ";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        //create an empty array
        $dat=[];

        //if the query returned any rows, the code block will be executed
        if($result->num_rows > 0){
            $resp['status'] = 'success';

            //foreach loop iterating over the rows of data returned 
            //fetch_array() method is called on the $result object to retrieve the rows, and each row is assigned $k and $v
            foreach($result->fetch_array() as $k => $v){
                
                //check if the current row's key (in the $k variable) is not numeric
                //if not numeric, the code block will be executed
                if(!is_numeric($k)){

                    //add the current row's data (stored in the $v variable) to the $data array using the 
                    //current row's key (stored in the $k variable) as the key
                    $data[$k] = $v;
                }
            }
            
            //add the $data array to the $resp array with the key data
            $resp['data'] = $data;

        }else{
            $resp['status'] = 'false';
        }
        return json_encode($resp);
    }

    //function to resent otp code
    public function resend_otp($id){

        //generate a new 6 digit random number
        $otp = sprintf("%'.06d",mt_rand(0,999999));

        //generate a string representing the expiration time of the otp. expiration is set at 1 min
        $expiration = date("Y-m-d H:i" ,strtotime(date('Y-m-d H:i')." +1 mins"));

        //sql query to update the otp and expiry time of the code
        $update_sql = "UPDATE `users` set otp_expiration = '{$expiration}', otp = '{$otp}' where id = '{$id}' ";
        $update_otp = $this->db->query($update_sql);

        //if otp updates
        if($update_otp){
            $resp['status'] = 'success';
            $email = $this->db->query("SELECT email FROM `users` where id = '{$id}'")->fetch_array()[0];
            $this->send_mail($email,$otp);
        }
        
        //if otp fails to update
        else{
            $resp['status'] = 'failed';
            $resp['error'] = $this->db->error;
        }
        return json_encode($resp);
    }

    //function to verify the otp entered by user 
    public function otp_verify(){

        //extract the id and otp values from the $_POST array
        extract($_POST);
         $sql = "SELECT * FROM `users` where id = ? and otp = ?";
         $stmt = $this->db->prepare($sql);
         $stmt->bind_param('is',$id,$otp);
         $stmt->execute();
         $result = $stmt->get_result();

         //if the query returns a result, the code block is executed
         if($result->num_rows > 0){
             $resp['status'] = 'success';

             //update user record in the users table to remove the otp and its expiration date
             $this->db->query("UPDATE `users` set otp = NULL, otp_expiration = NULL where id = '{$id}'");
             $_SESSION['user_login'] = 1;
             foreach($result->fetch_array() as $k => $v){
                 if(!is_numeric($k))
                 $_SESSION[$k] = $v;
             }
         }
         
         //if the query does not return a result
         else{
             $resp['status'] = 'failed';
             $_SESSION['flashdata']['type'] = 'danger';
             $_SESSION['flashdata']['msg'] = ' Incorrect OTP.';
         }
         return json_encode($resp);
    }

    //function to send email OTP
    function send_mail($to="",$pin=""){

        //check if the $to argument is not empty. if so, the code block will be executed
        if(!empty($to)){
            try{
                //set the $email variable to the sender's email address
                $email = 'info@xyzapp.com';
                
                //create email headers, which include the sender's email address, 
                //the reply-to email address, and the version of PHP that is being used
                $headers = 'From:' .$email . '\r\n'. 'Reply-To:' .
                $email. "\r\n" .
                'X-Mailer: PHP/' . phpversion()."\r\n";
                $headers .= "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
                
                //the message to be sent in the email
                $msg = "
                <html>
                    <body>
                        <h2>You are Attempting to Login into Moodle</h2>
                        <p>Here is your OTP (One-Time PIN) to verify your Authenticity. Do not share this code with anyone.</p>
                        <h3><b>".$pin."</b></h3>
                    </body>
                </html>
                ";

                //send the email
                mail($to,"Requested OTP",$msg,$headers);

            }//catch exceptions in case email sending has an issue
            catch(Exception $e){
                $_SESSION['flashdata']['type']='danger';
                $_SESSION['flashdata']['msg'] = ' An error occurred while sending the OTP. Error: '.$e->getMessage();
            }
        }
    }
    
    //function to close the database connection
    function __destruct(){
         $this->db->close();
    }
}
$class = new MainClass();
$conn= $class->db_connect();