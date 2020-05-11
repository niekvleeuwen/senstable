<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);

include_once './../../../config/connect.php';

header('Content-type:application/json;charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$json = file_get_contents('php://input');
$json = json_decode($json);

$id     = $json->id;
$value  = $json->value;

if($id == null || $value == null){
    $data = [
        'error' => 'Storing failed!',
    ];
}else{
    // get the content of the file and decode it into JSON
    $jsonString = file_get_contents('sensData.json');
    $data = json_decode($jsonString, true);

    if(count($data["amounts"]) >= 10){
        // shift the first value of the json array off, shortening the array by one element and moving everything down
        array_shift($data["amounts"]);
    }

    // make an array with the new values
    $timestamp = date("H:i:s");
    $newValue = array($timestamp, $value);

    // update the json array
    array_push($data["amounts"], $newValue);

    // write the contents to the file using the LOCK_EX flag to prevent anyone else writing to the file at the same time
    $newJsonString = json_encode($data);
    $result = file_put_contents('sensData.json', $newJsonString, LOCK_EX);

    if ($result !== false) {
        $data = [
            'result' => 'Data succesfully stored!',
        ];
    } else {
        $data = [
            'error' => 'Storing failed!',
        ];
    }
}

echo json_encode($data);