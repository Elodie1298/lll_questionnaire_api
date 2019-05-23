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
                array_push($spk2Audios,$data[0]);
            }
        }

        if ($carac1 != null) {
            if (strpos($carac1, '=') !== false) {
                $logic = explode('=',$carac1);
                $operator = "=";
                $carac = $logic[0];
                $val = $logic[1];
            } else {
                $logic = explode('!',$carac1);
                $operator = "!";
                $carac = $logic[0];
                $val = $logic[1];
            }

            $carac1Audios = array();

            switch ($carac){
                case "Gender":
                    if($operator == "="){
                        //Search Speakers that are the same gender
                        $query = 'SELECT speaker_id FROM speaker WHERE speaker_gender = "'.$val.'";';
                        $res = mysqli_query($Connect,$query);
                        while ($data = mysqli_fetch_row($res)) {
                            //Fetch Audios
                            $query = "SELECT audio_path FROM audio WHERE speaker_id = ".$data[0].";";
                            $res = mysqli_query($Connect,$query);
                            $data = mysqli_fetch_row($res);
                            while ($data = mysqli_fetch_row($res)) {
                                array_push($carac1Audios,$data[0]);
                            }
                        }
                    } else {
                        //Search Speakers that are not the same gender 
                        $query = 'SELECT speaker_id FROM speaker WHERE speaker_gender <> "'.$val.'";';
                        $res = mysqli_query($Connect,$query);
                        while ($data = mysqli_fetch_row($res)) {
                            //Fetch Audios
                            $query = "SELECT audio_path FROM audio WHERE speaker_id = ".$data[0].";";
                            $res = mysqli_query($Connect,$query);
                            $data = mysqli_fetch_row($res);
                            while ($data = mysqli_fetch_row($res)) {
                                array_push($carac1Audios,$data[0]);
                            }
                        }
                    }
                    break;
                case "Lang":
                    if($operator == "="){
                        //Search Audios that are in the same lang 
                        $query = 'SELECT audio_path FROM audio WHERE language = "'.$val.'";';
                        $res = mysqli_query($Connect,$query);
                        $data = mysqli_fetch_row($res);
                        while ($data = mysqli_fetch_row($res)) {
                            array_push($carac1Audios,$data[0]);
                        }
                    } else {
                        //Search Audios that are not in the same lang 
                        $query = 'SELECT audio_path FROM audio WHERE language <> "'.$val.'";';
                        $res = mysqli_query($Connect,$query);
                        $data = mysqli_fetch_row($res);
                        while ($data = mysqli_fetch_row($res)) {
                            array_push($carac1Audios,$data[0]);
                        }
                    }
                    break;
                case "Age":
                    if ($val == "Enfant") {
                        $min = 0;
                        $max = 20;
                    } else if ($val == "Adulte"){
                        $min = 20;
                        $max = 60;
                    } else {
                        $min = 60;
                        $max = 150;
                    }
                    if($operator == "="){
                        //Search Speakers that have the same age
                        $query = "SELECT speaker_id FROM speaker WHERE speaker_age > ".$min." AND speaker_age <= ".$max.";";
                        $res = mysqli_query($Connect,$query);
                        while ($data = mysqli_fetch_row($res)) {
                            //Fetch Audios
                            $query = "SELECT audio_path FROM audio WHERE speaker_id = ".$data[0].";";
                            $res = mysqli_query($Connect,$query);
                            $data = mysqli_fetch_row($res);
                            while ($data = mysqli_fetch_row($res)) {
                                array_push($carac1Audios,$data[0]);
                            }
                        } 
                    } else {
                        //Search Speakers that have not the same age
                        $query = "SELECT speaker_id FROM speaker WHERE speaker_age <= ".$min." OR speaker_age > ".$max.";";
                        $res = mysqli_query($Connect,$query);
                        while ($data = mysqli_fetch_row($res)) {
                            //Fetch Audios
                            $query = "SELECT audio_path FROM audio WHERE speaker_id = ".$data[0].";";
                            $res = mysqli_query($Connect,$query);
                            $data = mysqli_fetch_row($res);
                            while ($data = mysqli_fetch_row($res)) {
                                array_push($carac1Audios,$data[0]);
                            }
                        } 
                    }
                    break;
            }
        }
        print_r($carac1Audios);
    } else {
        http_response_code(404);
    }
} else {
    http_response_code(400);
}

?>