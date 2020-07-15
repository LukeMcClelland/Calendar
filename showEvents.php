<?php
ini_set("session.cooke_httponly", 1);
header("Content-Type: application/json"); 
$global = array();
$global2 = array();
$global3 = array();

session_start();
require('database.php');
// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$userLink = $json_obj['user'];

// Use a prepared statement
$statement = $mysqli->prepare("SELECT name FROM events where userLink = ?");
if(!$statement){
    printf("Query Prep F: %s\n", $mysqli->error);
    exit;
}
$statement->bind_param('s', $userLink);
$statement->execute();
$statement->bind_result($title);
while ($statement->fetch()) {
    array_push($global, $title);
}

for($i = 0; $i < count($global); $i++ ){
    $query = $mysqli->prepare("SELECT date FROM events where userLink = ?");
    
    $query->bind_param('s', $userLink);
    $query->execute();
    if(!$query){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
    }
    $query->bind_result($date);
    while($query->fetch()){
    } 
    array_push($global2, $global[$i], $date);
    array_push($global3, $global2);
    $global2 = array();
}
echo json_encode($global3);
//}
?>