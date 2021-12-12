<?php
    require_once '../config/database.php';
    require_once '../util/auth.php';

    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization,X-Requested-With');

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    $headers = apache_request_headers();
    if (!checkKey($headers)) {
        echo "Invalid API key";
        return;
    }

    $received = json_decode(file_get_contents("php://input"), true);
    $data = $received["data"];
    
    switch ($received["action"]) {
        case "EDIT_EVENT":
            editEvent();
            return;
    }

    function editEvent() {
        global $data;
        global $db;
        $toReturn = "abcd";
        $eventID = $data["eventID"];
        $toEditArray = $data["toEditArray"];
        try {
            foreach ($toEditArray as $e) {
                // update sql here
                $attr = $e["attr"];
                $statement = $db->prepare("UPDATE event SET " . $attr . "=:val WHERE event_id=:id");
                $statement->bindParam(":val", $e["val"]);
                $id = (int)$eventID;
                $statement->bindValue(":id", $id, PDO::PARAM_INT);
                $statement->execute();
            }
            echo $statement->rowCount() . " records UPDATED successfully";
        }catch (PDOException $e) {
            echo json_encode($e->getMessage());
        }
    }
    
