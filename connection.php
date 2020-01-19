<?php
//includeing credentials of db
include('./config.php');

function dbconnection(){
    $con = mysqli_connect(DBHOST,DBUSER, DBPASS , DBNAME);
    
    if(!$con){
        //exit from function if cannot get connected
        die("Connction Failed". mysqli_connect_error());
    }
	
    return $con;
    echo "Connection has established";
	
}