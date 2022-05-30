<?php
header("Content-type: application/json");
include_once("functions.php");

startSession();
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        include_once("../data/connection.php");
        global $con;
        if (isset($_GET["idAccount"]) and !empty($_GET["idAccount"])) {
            $idAccount = (int)$_GET["idAccount"];
        }
        $idEmployee = +$_SESSION["id_employee"];
        $querySelect = "SELECT a.id_account as accountId, a.id_patient as patientId, a.id_employee as employeeId, a.date as datee, a.comment as commment FROM accounts a INNER JOIN patients p ON a.id_patient = p.id_patient INNER JOIN employees e ON e.id_employee = a.id_employee WHERE a.id_account = $idAccount";
        $result = $con->query($querySelect)->fetch();
        if ($result) {
            // TODO INSERT
            $queryInsert = "INSERT INTO sub_accounts VALUES (null,$idAccount, $result->employeeId, null,CURRENT_TIMESTAMP,1,0,0,0,0 ,$idEmployee)";
            $resultInsert = $con->query($queryInsert);
            $lastInsertId = $con->lastInsertId();
            if ($resultInsert) echo json_encode($lastInsertId);
            else echo json_encode(0);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
