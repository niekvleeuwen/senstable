<?php
include_once './../../../config/connect.php';

header('Content-type:application/json;charset=utf-8');
header('Access-Control-Allow-Origin: *');

$json = file_get_contents('php://input');
$json = json_decode($json);

$token              = $json->token;
$name               = $json->name;
$short_description  = $json->short_description;
$serial_number      = $json->serial_number;
$wiki               = $json->wiki;
$code               = $json->code;

$sql = "INSERT INTO sensors (name, short_description, serial_number, wiki, code) VALUES (:name, :short_description, :serial_number, :wiki, :code)";

$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

$stmt = $pdo->prepare($sql);

$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':short_description', $short_description, PDO::PARAM_STR);
$stmt->bindValue(':serial_number', $serial_number, PDO::PARAM_STR);
$stmt->bindValue(':wiki', $wiki, PDO::PARAM_STR);
$stmt->bindValue(':code', $cdoe, PDO::PARAM_STR);

$result = $stmt->execute();

if ($result) {
    $data = [
        'result' => 'Sensor succesfully stored!',
    ];
} else {
    $data = [
        'error' => 'Storing failed!',
    ];
}

echo json_encode($data);