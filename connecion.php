<?php
//includeing credentials of db
include './config.php';

function dbconnection(){
    $conn = mysqli_connect(DBHOST,DBUSER, DBPASS , DBNAME);
    
    if(!conn){
        //exit from function if cannot get connected
        die("Connction Failed". mysqli_connect_error());
    }
    
    return $conn;
}