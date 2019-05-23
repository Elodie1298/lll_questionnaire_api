<?php

include_once 'connect.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

if (isset($_GET['id']) || !empty($_GET['id'])){
    $id = htmlentities($_GET['id']);
    if ($id > 0 && $id < 23) {
        //Fetching Question
        $query = "SELECT * FROM questions WHERE question_id = ".$id.";";
        $res = mysqli_query($Connect,$query);
        $data = mysqli_fetch_row ($res);

        //Question informations
        $questionId = $data[0];
        $question = $data[1];
        $audioID = $data[2];
        $spk1Id = $data[3];
        $spk2Id = $data[4];
        $carac1 = $data[5];
        $carac2 = $data[6];

        //Fetching Audio
        $query = "SELECT * FROM questions WHERE question_id = ".$id.";";
        $res = mysqli_query($Connect,$query);
        $data = mysqli_fetch_row($res);
        //Question informations
        $questionId = $data[0];
        $question = $data[1];
        $audioID = $data[2];
        $spk1Id = $data[3];
        $spk2Id = $data[4];
        $carac1 = $data[5];
        $carac2 = $data[6];

        //Fetching Audio
        $query = "SELECT * FROM audio WHERE audio_id = ".$audioID.";";
        $res = mysqli_query($Connect,$query);
        $data = mysqli_fetch_row($res);
        //Audio informations
        $audioPath = $data[1];
        $speakerId = $data[2];
        $speakerLang = $data[3];

        //Fetching Audio
        $query = "SELECT * FROM speaker WHERE speaker_id = ".$speakerId.";";
        $res = mysqli_query($Connect,$query);
        $data = mysqli_fetch_row($res);
        //Audio informations
        $speakerName = $data[1];
        $speakerGender = $data[2];
        $speakerAge = $data[3];
        $speakerNativeLang = $data[4];

        if ($spk1Id == null) {
            echo "Bobo le lapin";
        }
    } else {
        http_response_code(404);
    }
} else {
    http_response_code(400);
}

?>