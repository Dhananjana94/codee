<?php

include './connection.php';
$con = dbconnection();
$table="users";

function validate($inputdata) {
    htmlspecialchars($inputdata);
    trim($inputdata);
    stripslashes($inputdata);

    return $inputdata;
}

function insertData($table, array $data) {

    global $con;
    // impolde function is used to seperate array elemts by s sacial character(,/.)

    $sql = "INSERT INTO $table(" . implode(" ,", array_keys($data)) . " )VALUES('" . implode("', '", array_values($data)) . "')";

    if (mysqli_query($con, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($con);
    }

    //send email
    if($sql){
        $to = "sandaruimal99@gmail.com ";
        $subject = "Email verification";
        $message = "<a href='http://localhost/registration/verify.php?token=123'>";
        $headers = "From sandaru@gmail.com";
        $headers .= "MIME-Version:1.0". "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

        mail($to,$subject,$message,$headers);
        header('location:thankyou.php');
    }
}


?>