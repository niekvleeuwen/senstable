<?php
include_once '../../api.php';

$api = new API();
$api->setHeaders();

$json = file_get_contents('php://input');
$json = json_decode($json);

$token              = $json->token;
$id                 = $json->id;
$name               = $json->name;
$short_description  = $json->short_description;
$serial_number      = $json->serial_number;
$wiki               = $json->wiki;
$code               = $json->code;

// first check token
if ($api->authenticate($token)) {
    $sql = "UPDATE sensors SET name=:name, short_description=:short_description, serial_number=:serial_number, wiki=:wiki, code=:code WHERE id=:id";
    $param = array (
        "id" => $id,
        "name" => $name,
        "short_description" => $short_description,
        "serial_number" => $serial_number,
        "wiki" => $wiki,
        "code" => $code,
    );

    // check if the SQL query was succesful
    if ($api->sendQuery($sql, $param)) {
        $data = [
            'result' => 'Sensor succesvol bijgewerkt!',
        ];
    } else {
        $data = [
            'error' => 'Updaten is mislukt!',
        ];
    }
} else {
    $data = [
        'error' => 'U moet opnieuw inloggen!',
    ];
}

echo json_encode($data);