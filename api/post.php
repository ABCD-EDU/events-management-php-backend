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
        case "CREATE_EVENT":
            createEvent();
            return;
    }

    function editEvent() {
        global $data;
        global $db;
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

    function createEvent() {
        global $data;
        global $db;
        try {
            $st1 = $db->prepare(
                "INSERT INTO EVENT(event_name, address, date_start, date_end, 
                description, event_status, category)
                VALUES(
                    :eventName,
                    :address,
                    :dateStart,
                    :dateEnd,
                    :description,
                    :eventStatus,
                    :category)");
            $st1->bindParam(":eventName", $data["name"]);
            $st1->bindParam(":address", $data["address"]);
            $st1->bindParam(":dateStart", $data["dateStart"]);
            $st1->bindParam(":dateEnd", $data["dateEnd"]);
            $st1->bindParam(":description", $data["description"]);
            $st1->bindParam(":eventStatus", $data["status"]);
            $st1->bindParam(":category", $data["category"]);

            $st1->execute();
            echo $st1->rowCount() . " records UPDATED successfully";

            // get id of added event
            $st2 = $db->prepare("SELECT event_id FROM event ORDER BY event_id DESC LIMIT 1 ");
            $st2->execute();
            $st2data = $st2->fetch();
            $eventID = (int)$st2data["event_id"];

            // insert relationship
            $st3 = $db->prepare("INSERT INTO admin_events (user_id, event_id)
                                VALUES (:userID, :eventID)");
            $st3->bindParam(":userID", $data["userID"]);
            $st3->bindParam(":eventID", $eventID);
                        
            $st3->execute();
            echo $st3->rowCount() . " records UPDATED successfully";

            echo true;
        }catch (PDOException $e) {
            echo json_encode($e->getMessage());
        }
    }
    
