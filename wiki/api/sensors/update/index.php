<?php
include_once './../../../config/connect.php';

header('Content-type:application/json;charset=utf-8');
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$json = file_get_contents('php://input');
$json = json_decode($json);

$token              = $json->token;
$id                 = $json->id;
$name               = $json->name;
$short_description  = $json->short_description;
$serial_number      = $json->serial_number;
$wiki               = $json->wiki;
$code               = $json->code;

$sql = "UPDATE sensors SET name=:name, short_description=:short_description, serial_number=:serial_number, wiki=:wiki, code=:code WHERE id=:id";

$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

$stmt = $pdo->prepare($sql);

$stmt->bindValue(':id', $id, PDO::PARAM_STR);
$stmt->bindValue(':name', $name, PDO::PARAM_STR);
$stmt->bindValue(':short_description', $short_description, PDO::PARAM_STR);
$stmt->bindValue(':serial_number', $serial_number, PDO::PARAM_STR);
$stmt->bindValue(':wiki', $wiki, PDO::PARAM_STR);
$stmt->bindValue(':code', $cdoe, PDO::PARAM_STR);

$result = $stmt->execute();

if ($result) {
    $data = [
        'result' => 'Sensor succesfully updated!',
    ];
} else {
    $data = [
        'error' => 'Updating failed!',
    ];
}

echo json_encode($data);