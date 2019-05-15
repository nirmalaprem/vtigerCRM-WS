<?php
$servername = "test";
$username = "test";
$password = "test";
$dbname="test";

// Create connection
$usapp_conn = new mysqli($servername, $username, $password,$dbname);

// Check connection
if ($usapp_conn->connect_error) {
    die("Connection failed: " . $usapp_conn->connect_error);
}else{
    //echo "EEWRWEREWR";
}


?>
