<?php
include_once("functions.php");
startSession();
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        include_once("../data/connection.php");
        global $con;
        $valuesToCheck = json_decode($_POST["valuesToCheck"], true);
        $query = "SELECT id_medicine,quantity FROM therapy_medicine WHERE id_therapy =1";
        $rez = $con->query($query)->fetchAll();
        if ($rez) {
            $newArray["idMedicine"] = [];
            $newArray["quantity"] = [];
            // var_dump($rez);
            for ($i = 0; $i < count($rez); $i++) {
                array_push($newArray["quantity"], $rez[$i]->quantity * 1);
                array_push($newArray["idMedicine"], $rez[$i]->id_medicine * 1);
                // PROVERITI VREDNOSTI DA LI SE PODUDARAJU SA VREDNOSTIMA IZ BAZE ZBOG THERAPY TYPA
            }
            // var_dump($newArray);
            $resultOfComparingIds = array_diff($valuesToCheck["idMedicine"], $newArray["idMedicine"]);
            $resultOfComparingQuantity = array_diff($valuesToCheck["quantity"], $newArray["quantity"]);
            if (count($resultOfComparingIds) == 0 and count($resultOfComparingQuantity) == 0) {
                echo json_encode(1);
            } else {
                echo json_encode(2);
            }
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
