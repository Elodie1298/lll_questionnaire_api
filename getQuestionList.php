<?php
include_once 'connect.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$cat1 = array(1,2,3);
$cat2 = array(4,5,6);
$cat3 = array(7,8,9,10,11,12,13);
$cat4 = array(14,15,16,17,18,19);
$cat5 = array(20,21,22);

$qcat1 = array_rand($cat1,2);
$qcat2 = array_rand($cat2,2);
$qcat3 = array_rand($cat3,2);
$qcat4 = array_rand($cat4,2);
$qcat5 = array_rand($cat5,2);

$questions = array($cat1[$qcat1[0]],$cat1[$qcat1[1]],$cat2[$qcat2[0]],$cat2[$qcat2[1]],$cat3[$qcat3[0]],$cat3[$qcat3[1]],$cat4[$qcat4[0]],$cat4[$qcat4[1]],$cat5[$qcat5[0]],$cat5[$qcat5[1]]);
shuffle($questions);

$final = array();

foreach ($questions as $id) {
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
            if ($data[0] != $audioPath) array_push($spk1Audios,$data[0]);
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
            if ($data[0] != $audioPath) array_push($spk2Audios,$data[0]);
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
        $carac1 = $val;

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
                            if ($data[0] != $audioPath) array_push($carac1Audios,$data2[0]);
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
                            if ($data[0] != $audioPath) array_push($carac1Audios,$data2[0]);
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
                        if ($data[0] != $audioPath) array_push($carac1Audios,$data[0]);
                    }
                } else {
                    //Search Audios that are not in the same lang 
                    $query = 'SELECT audio_path FROM audio WHERE language <> "'.$val.'";';
                    $res = mysqli_query($Connect,$query);
                    $data = mysqli_fetch_row($res);
                    while ($data = mysqli_fetch_row($res)) {
                        if ($data[0] != $audioPath) array_push($carac1Audios,$data[0]);
                    }
                }
                break;
            case "Age":
                if ($val == "Enfant") {
                    $min = 0;
                    $max = 18;
                } else if ($val == "Adulte"){
                    $min = 18;
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
                            if ($data[0] != $audioPath) array_push($carac1Audios,$data2[0]);
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
                            if ($data[0] != $audioPath) array_push($carac1Audios,$data2[0]);
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
        $carac2 = $val;
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
                            if ($data[0] != $audioPath) array_push($carac2Audios,$data2[0]);
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
                            if ($data[0] != $audioPath) array_push($carac2Audios,$data2[0]);
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
                        if ($data[0] != $audioPath) array_push($carac2Audios,$data[0]);
                    }
                } else {
                    //Search Audios that are not in the same lang 
                    $query = 'SELECT audio_path FROM audio WHERE language <> "'.$val.'";';
                    $res = mysqli_query($Connect,$query);
                    $data = mysqli_fetch_row($res);
                    while ($data = mysqli_fetch_row($res)) {
                        if ($data[0] != $audioPath) array_push($carac2Audios,$data[0]);
                    }
                }
                break;
            case "Age":
                if ($val == "Enfant") {
                    $min = 0;
                    $max = 18;
                } else if ($val == "Adulte"){
                    $min = 18;
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
                            if ($data[0] != $audioPath) array_push($carac2Audios,$data2[0]);
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
                            if ($data[0] != $audioPath) array_push($carac2Audios,$data2[0]);
                        }
                    } 
                }
                break;
        }
    }

    $json = '{';
    $json .= '"questionId":'.$questionId.',';
    $json .= '"question":"'.$question.'",';
    $json .= '"audio": {';
    $json .= '"audioId": '.$audioID.',';
    $json .= '"audioPath": "'.$audioPath.'",';
    $json .= '"audioLang": "'.$audioLang.'",';
    $json .= '"speakerId": '.$speakerId;
    $json .= "},";
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
    $json .= ",";
    if ($carac1 != null){
        $json .= '"carac1": {';
        $json .= '"caracName": "'.$carac1.'",';
        $json .= '"audiosPath": '.json_encode($carac1Audios);
        $json .= '}';
    } else {
        $json .= '"carac1": null';
    }
    $json .= ",";
    if ($carac2 != null){
        $json .= '"carac2": {';
        $json .= '"caracName": "'.$carac2.'",';
        $json .= '"audiosPath": '.json_encode($carac2Audios);
        $json .= '}';
    } else {
        $json .= '"carac2": null';
    }
    $json .= "}";
	
    array_push($final,json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $json),true));
}
$res = json_encode($final);
echo '{"res":'.$res.'}';
?>