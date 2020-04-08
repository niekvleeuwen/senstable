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

$token = $json->token;
$id = $json->id;

$sql = "DELETE FROM sensors WHERE id=:id";

$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING );

$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $id, PDO::PARAM_STR);
$result = $stmt->execute();

if ($result) {
    $data = [
        'result' => 'Sensor succesfully deleted!',
    ];
} else {
    $data = [
        'error' => 'Failed to delete the sensor.',
    ];
}

echo json_encode($data);