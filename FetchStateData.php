<?php

include("dbconnection.php");
session_start();

$postcode = $_GET["postcode"];

$stateSelectSql = "SELECT * FROM state
                LEFT OUTER JOIN postcode ON postcode.state_code = state.state_code
                WHERE postcode = $postcode";

$stateResult = $connection->query($stateSelectSql); //perform query

if($stateResult){
    $row = mysqli_fetch_array($stateResult);
    echo json_encode($row);
}
    
?>