<?php
ini_set("session.cooke_httponly", 1);
header("Content-Type: application/json"); 
$name_array= array();
$date_array= array();
session_start();
require('database.php');
// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:

// Use a prepared statement
$userLink = $_SESSION['username'];
$statement = $mysqli->prepare("SELECT name, date FROM events where userLink = ?");
if(!$statement){
    printf("Query Prep F: %s\n", $mysqli->error);
    exit;
}
$statement->bind_param('s', $userLink);
if($statement->execute()){
    $statement->bind_result($eventTitles, $eventDate);
    while($statement->fetch()){
        array_push($name_array,$eventTitles);
        array_push($date_array, $eventDate);
    }
    $statement->close();
    echo json_encode(array(
        "success" => true,
        "username" => $userLink,
        "name_array" => $name_array,
        "date_array" => $date_array
    ));
    exit;
}




?>