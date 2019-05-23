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
        $audioLang = $data[3];

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

            shuffle($spk1Audios);

            $jsonSpk1 = "{";
            $jsonSpk1 .= '"speakerId":'.$spk1Id.',';
            $jsonSpk1 .= '"speakerAge":'.$speaker1Age.',';
            $jsonSpk1 .= '"speakerName":"'.$speaker1Name.'",';
            $jsonSpk1 .= '"speakerGender":"'.$speaker1Gender.'",';
            $jsonSpk1 .= '"speakerNativeLang":"'.$speaker1NativeLang.'",';
            $jsonSpk1 .= '"audiosPath":'.json_encode($spk1Audios);
            $jsonSpk1 .= "}";
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

            shuffle($spk2Audios);

            $jsonSpk2 = "{";
            $jsonSpk2 .= '"speakerId":'.$spk2Id.',';
            $jsonSpk2 .= '"speakerAge":'.$speaker2Age.',';
            $jsonSpk2 .= '"speakerName":"'.$speaker2Name.'",';
            $jsonSpk2 .= '"speakerGender":"'.$speaker2Gender.'",';
            $jsonSpk2 .= '"speakerNativeLang":"'.$speaker2NativeLang.'",';
            $jsonSpk2 .= '"audiosPath":'.json_encode($spk2Audios);
            $jsonSpk2 .= "}";
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
                        $query = 'SELECT speaker_id FROM speaker WHERE speaker_gender = "'.$val.'" AND speaker_id <> '.$speakerId.';';
                        $res = mysqli_query($Connect,$query);
                        while ($data = mysqli_fetch_row($res)) {
                            //Fetch Audios
                            $query = "SELECT audio_path FROM audio WHERE speaker_id = ".$data[0].";";
                            $res2 = mysqli_query($Connect,$query);
                            while ($data2 = mysqli_fetch_row($res2)) {
                                array_push($carac1Audios,$data2[0]);
                            }
                        }
                    } else {
                        //Search Speakers that are not the same gender 
                        $query = 'SELECT speaker_id FROM speaker WHERE speaker_gender <> "'.$val.'" AND speaker_id <> '.$speakerId.';';
                        $res = mysqli_query($Connect,$query);
                        while ($data = mysqli_fetch_row($res)) {
                            //Fetch Audios
                            $query = "SELECT audio_path FROM audio WHERE speaker_id = ".$data[0].";";
                            $res2 = mysqli_query($Connect,$query);
                            while ($data2 = mysqli_fetch_row($res2)) {
                                array_push($carac1Audios,$data2[0]);
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
                        $query = "SELECT speaker_id FROM speaker WHERE speaker_age > ".$min." AND speaker_age <= ".$max." AND speaker_id <> ".$speakerId.";";
                        $res = mysqli_query($Connect,$query);
                        while ($data = mysqli_fetch_row($res)) {
                            //Fetch Audios
                            $query = "SELECT audio_path FROM audio WHERE speaker_id = ".$data[0].";";
                            $res2 = mysqli_query($Connect,$query);
                            while ($data2 = mysqli_fetch_row($res2)) {
                                array_push($carac1Audios,$data2[0]);
                            }
                        } 
                    } else {
                        //Search Speakers that have not the same age
                        $query = "SELECT speaker_id FROM speaker WHERE speaker_age <= ".$min." OR speaker_age > ".$max." AND speaker_id <> ".$speakerId.";";
                        $res = mysqli_query($Connect,$query);
                        while ($data = mysqli_fetch_row($res)) {
                            //Fetch Audios
                            $query = "SELECT audio_path FROM audio WHERE speaker_id = ".$data[0].";";
                            $res2 = mysqli_query($Connect,$query);
                            while ($data2 = mysqli_fetch_row($res2)) {
                                array_push($carac1Audios,$data2[0]);
                            }
                        } 
                    }
                    break;
            }
        }
        
        if ($carac2 != null) {
            if (strpos($carac2, '=') !== false) {
                $logic = explode('=',$carac2);
                $operator = "=";
                $carac = $logic[0];
                $val = $logic[1];
            } else {
                $logic = explode('!',$carac2);
                $operator = "!";
                $carac = $logic[0];
                $val = $logic[1];
            }

            $carac2Audios = array();

            switch ($carac){
                case "Gender":
                    if($operator == "="){
                        //Search Speakers that are the same gender
                        $query = 'SELECT speaker_id FROM speaker WHERE speaker_gender = "'.$val.'" AND speaker_id <> '.$speakerId.';';
                        $res = mysqli_query($Connect,$query);
                        while ($data = mysqli_fetch_row($res)) {
                            //Fetch Audios
                            $query = "SELECT audio_path FROM audio WHERE speaker_id = ".$data[0].";";
                            $res2 = mysqli_query($Connect,$query);
                            while ($data2 = mysqli_fetch_row($res2)) {
                                array_push($carac2Audios,$data2[0]);
                            }
                        }
                    } else {
                        //Search Speakers that are not the same gender 
                        $query = 'SELECT speaker_id FROM speaker WHERE speaker_gender <> "'.$val.'" AND speaker_id <> '.$speakerId.';';
                        $res = mysqli_query($Connect,$query);
                        while ($data = mysqli_fetch_row($res)) {
                            //Fetch Audios
                            $query = "SELECT audio_path FROM audio WHERE speaker_id = ".$data[0].";";
                            $res2 = mysqli_query($Connect,$query);
                            while ($data2 = mysqli_fetch_row($res2)) {
                                array_push($carac2Audios,$data2[0]);
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
                            array_push($carac2Audios,$data[0]);
                        }
                    } else {
                        //Search Audios that are not in the same lang 
                        $query = 'SELECT audio_path FROM audio WHERE language <> "'.$val.'";';
                        $res = mysqli_query($Connect,$query);
                        $data = mysqli_fetch_row($res);
                        while ($data = mysqli_fetch_row($res)) {
                            array_push($carac2Audios,$data[0]);
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
                        $query = "SELECT speaker_id FROM speaker WHERE speaker_age > ".$min." AND speaker_age <= ".$max." AND speaker_id <> ".$speakerId.";";
                        $res = mysqli_query($Connect,$query);
                        while ($data = mysqli_fetch_row($res)) {
                            //Fetch Audios
                            $query = "SELECT audio_path FROM audio WHERE speaker_id = ".$data[0].";";
                            $res2 = mysqli_query($Connect,$query);
                            while ($data2 = mysqli_fetch_row($res2)) {
                                array_push($carac2Audios,$data2[0]);
                            }
                        } 
                    } else {
                        //Search Speakers that have not the same age
                        $query = "SELECT speaker_id FROM speaker WHERE speaker_age <= ".$min." OR speaker_age > ".$max." AND speaker_id <> ".$speakerId.";";
                        $res = mysqli_query($Connect,$query);
                        while ($data = mysqli_fetch_row($res)) {
                            //Fetch Audios
                            $query = "SELECT audio_path FROM audio WHERE speaker_id = ".$data[0].";";
                            $res2 = mysqli_query($Connect,$query);
                            while ($data2 = mysqli_fetch_row($res2)) {
                                array_push($carac2Audios,$data2[0]);
                            }
                        } 
                    }
                    break;
            }
        }

        $json = "{";
        $json .= '"questionId":'.$questionId.',';
        $json .= '"question":"'.$question.'",';
        $json .= '"audio": {';
        $json .= '"audioId": '.$audioID.',';
        $json .= '"audioPath": "'.$audioPath.'",';
        $json .= '"audioLang": "'.$audioLang.'",';
        $json .= '"speakerId": '.$speakerId;
        if ($spk1Id == null && $spk2Id == null && $carac1 == null && $carac2 == null){
            $json .= "}";
        } else {
            $json .= "},";
        }
        if ($spk1Id != null){
            $json .= '"speaker1": '.$jsonSpk1;
        } else {
            $json .= '"speaker1": null';
        }
        $json .= ",";
        if ($spk2Id != null){
            $json .= '"speaker2": '.$jsonSpk2;
        } else {
            $json .= '"speaker2": null';
        }
        $json .= "}";
        echo $json;
    } else {
        http_response_code(404);
    }
} else {
    http_response_code(400);
}

?>