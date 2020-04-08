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

$id  = $json->id;
$limit = $json->limit;

$sql = "SELECT * FROM sensors";

if ($id != null) {
    $sql .= " WHERE id LIKE :id";
}

if ($limit != null) {
    $sql .= " LIMIT :limit";
}

$stmt = $pdo->prepare($sql);

if ($id != null) {
    $stmt->bindValue(':id', $id, PDO::PARAM_STR);
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