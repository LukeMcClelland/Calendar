<?php
ini_set("session.cooke_httponly", 1);
header("Content-Type: application/json"); 
session_start();
unset($_SESSION);
session_destroy();          //ends session
session_write_close();
echo json_encode(array(
    "success" => true
));
exit;
?>