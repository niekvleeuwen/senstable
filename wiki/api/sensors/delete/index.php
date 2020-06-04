<?php
ini_set('display_errors', 'On');
error_reporting(E_ALL);
include_once '../../api.php';

$api = new API();
$api->setHeaders();

$json = file_get_contents('php://input');
$json = json_decode($json);

$token = $json->token;
$id = $json->id;

// first check token
if ($api->authenticate($token)) {
    $sql = "DELETE FROM sensors WHERE id=:id";
    $param = array (
        "id" => $id,
    );
    if ($api->sendQuery($sql, $param)) {
        $data = [
            'result' => 'Sensor succesvol verwijderd!',
        ];
    } else {
        $data = [
            'error' => 'De sensor is niet gewist :(',
        ];
    }
} else {
    $data = [
        'error' => 'U moet opnieuw inloggen!',
    ];
}

echo json_encode($data);