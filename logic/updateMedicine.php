<?php
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once("functions.php");
    try {
        include_once("../data/connection.php");
        global $con;
        $idMedicine = +checkVariable("idMedicine");
        $quantityToAdd = +checkVariable("quantityToAdd");
        $query = "UPDATE medicines SET quantity = quantity+$quantityToAdd WHERE id_medicine = $idMedicine";
        $result = $con->query($query);
        if ($result) {
            echo json_encode(1);
        } else {
            echo json_encode(0);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
