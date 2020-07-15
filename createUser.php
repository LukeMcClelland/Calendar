<?php
header("Content-Type: application/json");
ini_set("session.cooke_httponly", 1);
require('database.php');


header("Content-Type: application/json"); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$username = $json_obj['username'];
$password = $json_obj['password'];
$hashed_password = password_hash($password, PASSWORD_DEFAULT);


if($_SERVER["REQUEST_METHOD"] == "POST"){
    $statement = $mysqli->prepare("insert into users (username, password, hashed) values (?, ?, ?)");         //store values in database
    if(!$statement){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $statement->bind_param('sss', $username, $password, $hashed_password);
    $statement->execute();
    $statement->close();
}

?> 