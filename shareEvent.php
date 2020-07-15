<?php
header("Content-Type: application/json");
ini_set("session.cooke_httponly", 1);
session_start();
require('database.php');
$global = array();
 // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:

// Use a prepared statement
$name = $json_obj['sharedEvent'];
$shareUser = $json_obj['userShare'];
$userLink = $_SESSION['username'];

$stmt = $mysqli->prepare("SELECT date from events where (name, userLink) = (?, ?)");
if(!$stmt){
    printf("Query Prep F: %s\n", $mysqli->error);
    exit;
}
$stmt->bind_param('ss', $name, $userLink);
$stmt->execute();
$stmt->bind_result($date);
while ($stmt->fetch()) {
    array_push($global, $date);
}

$statement = $mysqli->prepare("INSERT into events (name, date, userLink) values (?, ?, ?)");
if(!$statement){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$statement->bind_param('sss', $name, $date, $shareUser);
$statement->execute();
$statement->close();

echo json_encode(array(
    "success" => true,
    "response" => $shareUser,
    "date" => $global[0],
    "name" => $name
));
exit;
// else{
//    echo json_encode(array(
// 		"success" => false,
// 		"message" => "it broke"
// 	));
// 	exit;
// }

// Bind the results
?>