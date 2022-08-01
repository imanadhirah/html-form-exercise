<?php

$servername="127.0.0.1";
$username="root";
$password="";
$database="dl_database";
$port = "3306";

$connection = new mysqli($servername, $username, $password, $database, $port);

if($connection -> connect_errno){
	echo "fail to connect to mysql: (" .$connection->connect_errno." ) " .$connection->connect_errno;
}else{}

?>