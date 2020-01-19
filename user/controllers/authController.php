<?php

include 'connection.php';
require_once 'user/controllers/emailController.php';
session_start();
$con = dbconnection();

$errors = array();
$table="users";
$firstname = "";
$lastname = "";
$email = "";
$password = "";
$cPassword ="";

 

//if user click on te signup button
if(isset($_POST['signup-btn'])){
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cPassword = $_POST['cPassword'];
    
    //validation
    if(empty($_POST['firstname'])){
        $errors['firstname'] = "Firstname required";
    }

    if(empty($_POST['lastname'])){
        $errors['lastname'] = "Lastname required";
    }

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['$email'] = "Email address is invalid";
    }

    if(empty($_POST['email'])){
        $errors['$email'] = "Email required";
    }

    if(empty($_POST['password'])){
        $errors['password'] = "Password required";
    }

    if($password !== $cPassword){
        $errors['password'] = "Password confirmation did not match";
    }

    $token = bin2hex(random_bytes(50));
    $password = password_hash($password, PASSWORD_DEFAULT);

    $data = array(
        "firstname" => $firstname,
        "lastname" => $lastname,
        "email" => $email,
        "password" => $password,
        "token" => $token,
        
    );

    if(!$errors) {
        $sql="select * from $table where (email='$email');";
        $res=mysqli_query($con,$sql);
        
        if (mysqli_num_rows($res) > 0) {
        // output data of each row
        $row = mysqli_fetch_assoc($res);

            if ($email==$row['email']){
                $errors = "email already exists";
            }
        }else{ 
            //here you need to add else condition
            if(count($errors) == 0){
                $password = password_hash($password, PASSWORD_DEFAULT);
                $isEmailConfirmed = false;
        
                $sql = "INSERT INTO $table(" . implode(" ,", array_keys($data)) . " )VALUES('" . implode("', '", array_values($data)) . "')";
        
                    if (mysqli_query($con, $sql)) {
                        echo "New record created successfully";
                        $user_id = $con->insert_id;
                        $_SESSION['firstname'] = $firstname;
                        $_SESSION['lastname'] = $lastname;
                        $_SESSION['emal'] = $email;
                        $_SESSION['isEmailConfirmed'] = $isEmailConfirmed;
                        $__SESSION['token'] = $token;

                        sendVerificationEmail($email, $token);
        
                        $_SESSION['message'] = "You are now logged in";
                        $_SESSION['alert-class'] = "alert-sucess";

                        header('location: index.php');
                        exit();
        
                    } else {
                        echo "Error: " . $sql . "<br>" . mysqli_error($con);
                        $errors['db_error'] = "Database error: failed to register";
                        
                    }
            }
        }
    }
}


//code for login button


if(isset($_POST['login-btn'])){
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    //validation
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $errors['$email'] = "Email address is invalid";
    }

    if(empty($_POST['email'])){
        $errors['$email'] = "Email required";
    }

    if(empty($_POST['password'])){
        $errors['password'] = "Password required";
    }

    if(count($errors) === 0) {

    $sqlSelect="select * from $table where (email='$email');";
        $res=mysqli_query($con,$sqlSelect);
        $user= mysqli_fetch_assoc($res);

        if(password_verify($password, $user['password'])){
            //login success
            $user_id = $user['id'];
            $_SESSION['firstname'] = $user['firstname'];
            $_SESSION['emal'] = $user['email'];
            $_SESSION['isEmailConfirmed'] = $user['isEmailConfirmed'];
            
            $_SESSION['message'] = "You are now logged in";
            $_SESSION['alert-class'] = "alert-sucess";

            header('location: index.php');
            exit();

        }else{
            $errors['login_fail'] = "Wrong credentials";
        }

    }    
    
     
}

//logout functionality

if(isset($_GET['logout'])){
    session_destroy();
    unset($_SESSION['id']);
    unset($_SESSION['email']);
    unset($_SESSION['password']);
    unset($_SESSION['isEmailConfirmed']);

    header('location: login.php');
    exit();
}

//verify user by function
function verifyUser($token){
    print_r($token);
// $token="d036f62cae98ce798d08";
  global $con;
  $sql2="SELECT * FROM users where token='$token' LIMIT 1";
  $res=mysqli_query($con,$sql2);

  if(mysqli_num_rows($res) > 0){
    $user = mysqli_fetch_assoc($res);
    $updateQuery = "UPDATE users SET isEmailConfirmed=1 WHERE token='$token'";

    if(mysqli_query($con, $updateQuery)){
        //login user in
        $user_id = $user['id'];
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['emal'] = $user['email'];
        $_SESSION['isEmailConfirmed'] = 1;
        
        $_SESSION['message'] = "Your email has been verified";
        $_SESSION['alert-class'] = "alert-sucess";
        header('location: index.php');
        exit();
    }
    
  }else{
      echo 'User not found';
      error_reporting(-1);
  }

}
 
?>