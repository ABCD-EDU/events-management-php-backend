<?php
    require_once '../config/database.php';

    // $database = new Database();
    // $db = $database->connect();
    // $data = $db->prepare("SELECT * FROM user");
    // $data->execute();
    // $toReturn = $data->fetchAll();

    // $data = file_get_contents("php://input");
    $data = json_decode($_GET['data']);
    // header("Content-Type: application/json");
    echo json_encode($data);
