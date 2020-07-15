<?php
ini_set("session.cooke_httponly", 1);
header("Content-Type: application/json"); 
session_start();
if(!empty($_SESSION['username'])){
    $username = $_SESSION['username'];
    echo json_encode(array(
        "success" => true,
        "username" => $username
    ));
    exit;


}

?>