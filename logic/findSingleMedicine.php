<?php
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once("functions.php");
    try {
        $idMedicine = checkVariable("idMedicine");
        include_once("../data/connection.php");
        global $con;
        $query = "SELECT * FROM medicines m INNER JOIN medicine_prices mp ON m.id_medicine = mp.id_medicine WHERE m.id_medicine = $idMedicine";
        $result = $con->query($query)->fetch();
        if ($result) {
            echo json_encode($result);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
