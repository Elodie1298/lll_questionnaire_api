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

        //Fetching Speaker
        $query = "SELECT * FROM speaker WHERE speaker_id = ".$speakerId.";";
        $res = mysqli_query($Connect,$query);
        $data = mysqli_fetch_row($res);
        //Speaker informations
        $speakerName = $data[1];
        $speakerGender = $data[2];
        $speakerAge = $data[3];
        $speakerNativeLang = $data[4];

        if ($spk1Id != null) {
            //Fetching Speaker
            $query = "SELECT * FROM speaker WHERE speaker_id = ".$spk1Id.";";
            $res = mysqli_query($Connect,$query);
            $data = mysqli_fetch_row($res);
            //Speaker informations
            $speaker1Name = $data[1];
            $speaker1Gender = $data[2];
            $speaker1Age = $data[3];
            $speaker1NativeLang = $data[4];

            $spk1Audios = array();

            //Fetching Audios
            $query = "SELECT audio_path FROM audio WHERE speaker_id = ".$spk1Id.";";
            $res = mysqli_query($Connect,$query);
            $data = mysqli_fetch_row($res);
            while ($data = mysqli_fetch_row($res)) {
                array_push($spk1Audios,$data[0]);
            }
        }

        if ($spk2Id != null) {
            //Fetching Speaker
            $query = "SELECT * FROM speaker WHERE speaker_id = ".$spk2Id.";";
            $res = mysqli_query($Connect,$query);
            $data = mysqli_fetch_row($res);
            //Speaker informations
            $speaker2Name = $data[1];
            $speaker2Gender = $data[2];
            $speaker2Age = $data[3];
            $speaker2NativeLang = $data[4];

            $spk2Audios = array();

            //Fetching Audios
            $query = "SELECT audio_path FROM audio WHERE speaker_id = ".$spk2Id.";";
            $res = mysqli_query($Connect,$query);
            $data = mysqli_fetch_row($res);
            while ($data = mysqli_fetch_row($res)) {
                array_push($spk1Audios,$data[0]);
            }
        }

        print_r($spk2Audios);
    } else {
        http_response_code(404);
    }
} else {
    http_response_code(400);
}

?>