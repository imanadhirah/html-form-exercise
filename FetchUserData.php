<?php

include("dbconnection.php");
session_start();

$user_id = $_POST["user_id"];

$selectUserSql = "SELECT * FROM userData
WHERE user_id = $user_id";

$userResult = $connection->query($selectUserSql); //perform query

if($userResult){
    $row = mysqli_fetch_array($userResult);
    echo json_encode($row);
}

?>