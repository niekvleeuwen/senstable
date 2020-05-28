<?php
include_once '../../api.php';

$api = new API();
$api->setHeaders();

$json = file_get_contents('php://input');
$json = json_decode($json);

$token              = $json->token;
$name               = $json->name;
$short_description  = $json->short_description;
$serial_number      = $json->serial_number;
$wiki               = $json->wiki;
$code               = $json->code;

// first check token
if ($api->authenticate($token)) {
    $sql = "INSERT INTO sensors (name, short_description, serial_number, wiki, code) VALUES (:name, :short_description, :serial_number, :wiki, :code)";
    $param = array (
        "name" => $name,
        "short_description" => $short_description,
        "serial_number" => $serial_number,
        "wiki" => $wiki,
        "code" => $code,
    );
    
    // check if the SQL query was succesful
    if ($api->sendQuery($sql, $param)) {
        $data = [
            'result' => 'Sensor succesvol opgeslagen!',
        ];
    } else {
        $data = [
            'error' => 'Opslaan is mislukt!',
        ];
    }
} else {
    $data = [
        'error' => 'U moet opnieuw inloggen!',
    ];
}

echo json_encode($data);