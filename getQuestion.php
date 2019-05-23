<?php

include_once 'connect.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

if (isset($_GET['id']) || !empty($_GET['id'])){
    $id = htmlentities($_GET['id']);
    if ($id > 0 && $id < 23) {
        $query = "SELECT * FROM questions WHERE question_id = ".$id.";";
        $res = mysqli_query($Connect,$query);
        $data = mysqli_fetch_row ($res);

        echo $data["audio_id"];
    } else {
        http_response_code(404);
    }
} else {
    http_response_code(400);
}

?>