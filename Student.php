<?php

include './connection.php';
require_once './emailController.php';
$con = dbconnection();
session_start();

class Student{
    public $errors = array();
    public $table="users";
    public $firstname = "";
    public $lastname = "";
    public $email = "";
    public $password = "";
    public $cPassword ="";

    public function register(){
        
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
    }
}

?>