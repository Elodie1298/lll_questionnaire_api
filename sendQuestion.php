<?php

include_once 'connect.php';

if (isset($_POST["user_id"]) && !empty($_POST["user_id"]) && isset($_POST["question_id"]) && !empty($_POST["question_id"])
    && isset($_POST["answer_text"]) && !empty($_POST["answer_text"]) && isset($_POST["answer_time"]) && !empty($_POST["answer_time"])
    && isset($_POST["answer_extra_log"]) && !empty($_POST["answer_extra_log"]) && isset($_POST["answer_interface"]) && !empty($_POST["answer_interface"])
    && isset($_POST["answer_interface_template"]) && !empty($_POST["answer_interface_template"]) && isset($_POST["answer_template"]) && !empty($_POST["answer_template"])) {

    $uuid = htmlentities($_POST["user_id"]);
    $qid = htmlentities($_POST["question_id"]);
    $anstxt = htmlentities($_POST["answer_text"]);
    $anstime = htmlentities($_POST["answer_time"]);
    $anslog = htmlentities($_POST["answer_extra_log"]);
    $ansint = htmlentities($_POST["answer_interface"]);
    $ansinttemp = htmlentities($_POST["answer_interface_template"]);
    $anstemp = htmlentities($_POST["answer_template"]);

    $sql = 'INSERT INTO `answers` (`user_id`, `question_id`, `answer_text`, `answer_time`, `answer_extra_log`, `answer_interface`, `answer_interface_template`, `answer_template`) VALUES ("'.$uuid.'", '.$qid.', "'.$anstxt.'", '.$anstime.', "'.$anslog.'", "'.$ansint.'", "'.$ansinttemp.'", "'.$anstemp.'")';
    
    error_log("Writing in DB : ".$sql);
    
    mysqli_query($Connect,$sql);
    http_response_code(200);
} else {
    $uuid = htmlentities($_POST["user_id"]);
    $qid = htmlentities($_POST["question_id"]);
    $anstxt = htmlentities($_POST["answer_text"]);
    $anstime = htmlentities($_POST["answer_time"]);
    $anslog = htmlentities($_POST["answer_extra_log"]);
    $ansint = htmlentities($_POST["answer_interface"]);
    $ansinttemp = htmlentities($_POST["answer_interface_template"]);
    $anstemp = htmlentities($_POST["answer_template"]);

    error_log("Error with some values :)");
    error_log("user_id/question_id/answer_text/answer_time/answer_extra_log/answer_interface/answer_interface_template/answer_template");
    error_log($uuid."/".$qid."/".$anstxt."/".$anstime."/".$anslog."/".$ansint."/".$ansinttemp."/".$anstemp);

    http_response_code(400);
}

?>