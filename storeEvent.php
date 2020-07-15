<?php
header("Content-Type: application/json");
ini_set("session.cooke_httponly", 1);
session_start();
require('database.php');
// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$name = $json_obj['evt-details'];
$userLink = $_SESSION['username'];
$date = $json_obj['selectDay'];

if($_SERVER["REQUEST_METHOD"] == "POST"){
    $statement = $mysqli->prepare("insert into events (name, date, userLink) values (?, ?, ?)");         //store values in database
    if(!$statement){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $statement->bind_param('sss', $name, $date, $userLink);
    $statement->execute();
    $statement->close();
    echo json_encode(array(
        "success" => true,
        "title" => $userLink,
    ));
    exit;
}

?>