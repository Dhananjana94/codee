<?php

include './functions.php';

$table="users";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST['firstname'])) {
        $err = "First name is reqired";

    } else {
        $firstname = validate($_POST['firstname']);
    }

    if (empty($_POST['lastname'])) {
        $err = "Lastname name is reqired";

    } else {
        $lastname = validate($_POST['lastname']);
    }

    if (empty($_POST['email'])) {
        $err = "Emal is reqired";
        echo $err;
    } else {
        $email = validate($_POST['email']);
    }

    if (empty($_POST['password'])) {
        $err = "Password is reqired";
        echo $err;
    } else {
        $password = validate($_POST['password']);
    }

}

$token = md5(time().$firstname);

$data = array(
    "firstname" => $firstname,
    "lastname" => $lastname,
    "email" => $email,
    "password" => $password,
    "token" => $token,
);

var_dump($data);

//insertData("register", $data);
insertData($table, $data);






