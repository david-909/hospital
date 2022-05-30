<?php
header("Content-type: application/json");
include_once("functions.php");
startSession();
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        include_once("../data/connection.php");
        global $con;
        if (isset($_GET['idSubAccount']) and !empty($_GET['idSubAccount'])) {
            $idSubAccount = $_GET["idSubAccount"];
        } else {
            $idSubAccount = 0;
        }
        #var_dump($accId);
        $empolyeeId = +$_SESSION["id_employee"];
        #var_dump($empolyeeId);
        check1($idSubAccount, $empolyeeId);
        echo json_encode(1);
    } catch (PDOException $e) {
        http_response_code(500);
        $e->getMessage();
    }
} else {
    http_response_code(404);
}
