<?php
    include_once 'connect.php';

    header("Access-Control-Allow-Origin: *");
    
    $age = htmlspecialchars($_GET["age"]);
    $gender = htmlspecialchars($_GET["gender"]);
    $activity = htmlspecialchars($_GET["activity"]);

    $query = "INSERT INTO user (user_gender, user_age, user_activity) VALUES ('".$gender."', '".$age."', '".$activity."')";
    $Connect->query($query);

	$query = "SELECT user_id FROM user ORDER BY user_id DESC LIMIT 1;";
	$_SESSION['user_id'] = $Connect->query($query);


	http_response_code(200);
?>