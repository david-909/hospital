<?php
include_once("functions.php");
startSession();
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        include_once("../data/connection.php");
        global $con;
        $id = checkVariable("id");
        $newPrice = checkVariable("newPrice");
        $query = "UPDATE medicine_prices SET price = $newPrice, date = CURRENT_TIMESTAMP WHERE id_medicine=$id";
        $prepared = $con->prepare($query);
        $result = $prepared->execute();

        if ($result) {
            $updated = $con->query("SELECT price FROM medicine_prices WHERE id_medicine = $id")->fetch();
            echo json_encode($updated);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
