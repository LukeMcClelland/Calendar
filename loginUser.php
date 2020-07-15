<?php
header("Content-Type: application/json");
session_start();
ini_set("session.cooke_httponly", 1);
require('database.php');
 // Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$username = $json_obj['username_log'];
$pwd_guess = $json_obj['password_log'];

// Use a prepared statement
$statement = $mysqli->prepare("SELECT * FROM users WHERE username = ?");
if(!$statement){
    printf("Query Prep Failed: %s\n", $mysqli->error);
    exit;
}
$statement->bind_param('s', $username);
$statement->execute();
// Bind the results
$statement->bind_result($username, $password, $hashed_password);
$statement->fetch();

//if(mysqli_num_rows($statement) == 1 ){

if ($password == $pwd_guess) {
		$_SESSION['username'] = $username;
	$_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32)); 

	echo json_encode(array(
		"success" => true,
		"username" => $username
	));
	exit;
}

//}
else
{
	echo json_encode(array(
		"success" => false,
		"message" => "Incorrect Username or Password"
	));
	exit;
}
?>