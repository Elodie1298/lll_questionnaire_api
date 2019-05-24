<?php

include_once 'connect.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

if (isset($_GET["age"]) && !empty($_GET["age"]) && isset($_GET["gender"]) && !empty($_GET["gender"]) && isset($_GET["activity"]) && !empty($_GET["activity"])) {
    $uuid = uniqid();
    $age = htmlspecialchars($_GET["age"]);
    $gender = htmlspecialchars($_GET["gender"]);
    $activity = htmlspecialchars($_GET["activity"]);

    $query = "INSERT INTO user (user_id,user_gender, user_age, user_activity) VALUES ('".$uuid."','".$gender."', '".$age."', '".$activity."')";
    $Connect->query($query);

    echo '{"userId":"'.$uuid.'"}';
} else {
    http_response_code(400);
}


?>