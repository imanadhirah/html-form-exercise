<?php

session_start();
include("dbconnection.php");

$name = $_POST['name'];
$dob = $_POST['dob'];
$address = $_POST['address'];
$postcode = $_POST['postcode'];
$state = $_POST['state'];
$user_id = $_POST['user_id'];

if($user_id == "") //if no user_id means new user
{ 
    $insertUserSQL = "INSERT INTO userData (name, dob, address, postcode, state)
    VALUES ('$name', '$dob', '$address', '$postcode', '$state') ";

    $dataResult = $connection->query($insertUserSQL); //perform query

    if($dataResult){
        // reload page
        echo " <script>window.location.href='MainPage.php'; </script>";
    } 
}else //if existing user, update data
{
    $updateUserSql = "UPDATE userData 
                    SET name = '$name',
                    dob = '$dob',
                    address = '$address',
                    postcode = '$postcode',
                    state = '$state'
                    WHERE user_id = $user_id";

    $dataResult = $connection->query($updateUserSql);

    if($dataResult){
        // reload page
        echo " <script>window.location.href='MainPage.php'; </script>";
    } 
}  
?>