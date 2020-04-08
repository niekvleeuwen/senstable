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

$name  = strtolower($json->name);
$limit = $json->limit;

$sql = "SELECT * FROM sensors";

if ($name != null) {
    $sql .= " WHERE LOWER(name) LIKE :name";
}

if ($limit != null) {
    $sql .= " LIMIT :limit";
}

$stmt = $pdo->prepare($sql);

if ($name != null) {
    $arg  = '%' . $name . '%';
    $stmt->bindValue(':name', $arg, PDO::PARAM_STR);
}

if ($limit != null) {
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
}

$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($stmt->rowCount() > 0) {
    $data = $result;
} else {
    $data = [];
}

echo json_encode($data);