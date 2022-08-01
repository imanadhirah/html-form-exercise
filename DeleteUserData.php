<?php

include("dbconnection.php");
session_start();

$user_id = $_POST['user_id'];

//Delete job from job table
$deleteUserSql = "DELETE FROM userData
                 WHERE user_id = $user_id";

$userResult = $connection->query($deleteUserSql);	

?>