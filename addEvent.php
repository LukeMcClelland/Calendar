<?php
require('database.php');
$json_str = file_get_contents('php://input');
$json_obj = json_decode($json_str, true);

$title = isset($_POST['title']);
$statement = $mysqli->prepare("INSERT INTO events (title) values (?)");
    if(!$statement){
        printf("Query Prep Failed: %s\n", $mysqli->error);
        exit;
        }
    $statement->bind_param('s'. $title);
    $statement->execute();
    $statement->close();

?>