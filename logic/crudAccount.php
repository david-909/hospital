<?php
include_once("functions.php");
startSession();
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        include_once("../data/connection.php");
        global $con;
        if (isset($_POST["idDoctor"]) and !empty($_POST["idDoctor"]) and in_array($_SESSION["id_role"], [3, 4,5])) {
            $idDoctor = $_POST["idDoctor"];
        } else {
            $idDoctor = 0;
        }
        $therapyPrice = checkVariable("therapyPrice") * 1;
        $bloodPrice = checkVariable("bloodPrice") * 1;
        $idTherapy = checkVariable("idTherapy") * 1;
        $idTherapyType = checkVariable("idTherapyType") * 1;
        $idEmployee = checkVariable("idEmployee") * 1;
        $idPatient = checkVariable("idPatient") * 1;
        $totalPriceMedicines = checkVariable("totalPriceMedicines") * 1;
        $bloodsTotalPrice = checkVariable("bloodsTotalPrice") * 1;
        $appointmentsTotalPrice = checkVariable("appointmentsTotalPrice") * 1;
        $selectedBloodsId = checkVariable("selectedBloodsId");
        $selectedAppointmentsIds = checkVariable("selectedAppointmentsIds");
        $comment = $_POST["comment"];
        $anamneza = $_POST["anamneza"];
        $nalaz = $_POST["nalaz"];
        $uznalaz = $_POST["uznalaz"];
        $terapija = $_POST["terapija"];
        $zakljucak = $_POST["zakljucak"];
        $medicines = checkVariable("medicines"); //CLASSIC ARRAY BUT EACH ELEMENT IS ASSOC ARRAY

        // var_dump($idTherapy);
        // PROVERA POJEDINACNOG LEKA SA STANJEM U MAGACINU
        // IZABRANA JE TERAPIJA
        if ($medicines != 0) {
            $array = [];
            $error;
            for ($i = 0; $i < count($medicines); $i++) {
                $error = checkMedicineQuantity((int)$medicines[$i]["quantity"], (int) $medicines[$i]["idMedicine"]);
                array_push($array, $error);
            }
            if (in_array(false, $array)) {
                echo json_encode(5);
            } else {
                if (in_array($_SESSION["id_role"], [1])) {
                    $result = insertAccount($idPatient, $idEmployee, $comment, $anamneza, $nalaz, $uznalaz, $terapija, $zakljucak, $bloodPrice, $therapyPrice, $appointmentsTotalPrice, $idEmployee);
                    $lastInsertedId = $con->lastInsertId();
                } else {
                    $result = insertAccount($idPatient, $idDoctor, $comment, $anamneza, $nalaz, $uznalaz, $terapija, $zakljucak, $bloodPrice, $therapyPrice, $appointmentsTotalPrice, $idEmployee);
                    $lastInsertedId = $con->lastInsertId();
                }
                // OPCIONO
                if ($selectedAppointmentsIds != 0) {
                    for ($i = 0; $i < count($selectedAppointmentsIds); $i++) {
                        insertAccountAppointments($lastInsertedId, $selectedAppointmentsIds[$i]);
                    }
                }
                if ($selectedBloodsId != 0) {
                    for ($i = 0; $i < count($selectedBloodsId); $i++) {

                        insertAccountBloods($lastInsertedId, $selectedBloodsId[$i]);
                    }
                }
                if ($idTherapyType == 2) { //CUSTOM
                    insertTherapy();
                    $idTherapyLast = $con->lastInsertId();
                    insertTherapyMedicines($medicines, $idTherapyLast);
                    insertAccountsTherapies($lastInsertedId, $idTherapyLast);
                    insertMedicineUsage($medicines, $lastInsertedId);
                    updateMedicines($idTherapyLast);
                } else if ($idTherapyType == 1) {
                    //PREDEFINISANA 
                    insertAccountsTherapies($lastInsertedId, $idTherapy);
                    insertMedicineUsage($medicines, $lastInsertedId);
                    updateMedicines($idTherapy);
                    /* var_dump("kurcina");
                    echo "kitaa"; */
                }
                echo json_encode(1);
            }
        } else {
            if (in_array($_SESSION["id_role"], [1])) {
                $result = insertAccount($idPatient, $idEmployee, $comment, $anamneza, $nalaz, $uznalaz, $terapija, $zakljucak, $bloodPrice, $therapyPrice, $appointmentsTotalPrice, $idEmployee);
                $lastInsertedId = $con->lastInsertId();
            } else {
                $result = insertAccount($idPatient, $idDoctor, $comment, $anamneza, $nalaz, $uznalaz, $terapija, $zakljucak, $bloodPrice, $therapyPrice, $appointmentsTotalPrice, $idEmployee);
                $lastInsertedId = $con->lastInsertId();
            }
            // OPCIONO
            if ($selectedAppointmentsIds != 0) {
                for ($i = 0; $i < count($selectedAppointmentsIds); $i++) {
                    insertAccountAppointments($lastInsertedId, $selectedAppointmentsIds[$i]);
                }
            }
            if ($selectedBloodsId != 0) {
                for ($i = 0; $i < count($selectedBloodsId); $i++) {

                    insertAccountBloods($lastInsertedId, $selectedBloodsId[$i]);
                }
            }
            echo json_encode(1);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
