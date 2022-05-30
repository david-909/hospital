<?php
include_once("functions.php");
startSession();
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        include_once("../data/connection.php");
        global $con;
        $idPatient = checkVariable("idPatient");
        #var_dump($idPatient);
        $query = "SELECT * FROM patients WHERE id_patient = $idPatient";
        $result = $con->query($query)->fetch();
        if ($result) {
            echo json_encode($result);
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
