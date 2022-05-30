<?php
header("Content-type: application/json");
include_once("functions.php");
startSession();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        include_once("../data/connection.php");
        global $con;
        $accId = +checkVariable("accId");
        #var_dump($accId);
        $empolyeeId = +$_SESSION["id_employee"];
        #var_dump($empolyeeId);
        check($accId, $empolyeeId);
        echo json_encode(1);
    } catch (PDOException $e) {
        http_response_code(500);
        $e->getMessage();
    }
} else {
    http_response_code(404);
}
