<?php
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once("functions.php");
    startSession();
    try {
        include_once("../data/connection.php");
        global $con;
        $idEmployee = +$_SESSION["id_employee"];
        $name = checkVariable("name");
        $surname = checkVariable("surname");
        $parentName = checkVariable("parentName");
        $dateOfBirth = checkVariable("dateOfBirth");
        $idNumber = checkVariable("identificationNumber");
        $email = $_POST["email"];
        $phone = checkVariable("phone");
        $address = checkVariable("address");
        // $commentPatient = checkVariable("commentPatient");
        isset($_POST["commentPatient"]) and !empty($_POST["commentPatient"]) ? $commentPatient = $_POST["commentPatient"] : $commentPatient = "";
        $jmbg = checkVariable("jmbg");
        $dateOfInsert = date('Y-m-d H:i:s');
        $queryForJMBG = "SELECT jmbg FROM patients WHERE jmbg = $jmbg";
        $fetch = $con->query($queryForJMBG)->fetchColumn();
        if ($fetch) {
            echo json_encode(2);
        } else {
            if ($email == "") {
                $query = "INSERT INTO patients VALUES(null, ?, ?, ?, ?, ?, ?, null, ?, ?, ?, ?,$idEmployee)";
                $prepared = $con->prepare($query);
                $result = $prepared->execute([$name, $surname, $parentName, $dateOfBirth, $jmbg, $idNumber, $phone, $address, $commentPatient, $dateOfInsert]);
            } else {
                $query = "INSERT INTO patients VALUES(null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,$idEmployee)";
                $prepared = $con->prepare($query);
                $result = $prepared->execute([$name, $surname, $parentName, $dateOfBirth, $jmbg, $idNumber, $email, $phone, $address, $commentPatient, $dateOfInsert]);
            }
            if ($result) {
                echo json_encode(1);
            } else {
                echo json_encode(0);
            }
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
