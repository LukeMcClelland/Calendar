<?php
header("Content-Type: application/json");
ini_set("session.cooke_httponly", 1);
session_start();
require('database.php'); // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$date = $json_obj['selectDay'];
$userLink = $_SESSION['username'];
// Use a prepared statement
$statement = $mysqli->prepare("DELETE FROM events WHERE (date, userLink) = (?, ?)");
    if(!$statement){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $statement->bind_param('ss', $date, $userLink);
    $statement->execute();
    $statement->close();
?>