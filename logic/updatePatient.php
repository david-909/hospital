<?php
include_once("functions.php");
startSession();
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        include_once("../data/connection.php");
        global $con;
        $id = +checkVariable("id");
        $name = checkVariable("name");
        $surname = checkVariable("surname");
        $parentName = checkVariable("parentName");
        $email = checkVariable("email");
        $phone = checkVariable("phone");
        $jmbg = checkVariable("jmbg");
        $identificationNumber = checkVariable("identificationNumber");
        $address = checkVariable("address");
        $dateOfBirth = checkVariable("dateOfBirth");
        $commentPatient = checkVariable("commentPatient");
        $date = date("d.m.Y H:i:s");


        $nameDr = $_SESSION["name"];
        $surnameDr = $_SESSION["surname"];

        #echo $name . ' ' . $surname;
        #var_dump($id, $name, $surname, $parentName, $email, $phone, $jmbg, $identificationNumber, $address, $dateOfBirth, $commentPatient);
        $querySelect = "SELECT * FROM patients WHERE id_patient = $id";
        $resultSelect = $con->query($querySelect)->fetch();
        #var_dump($resultSelect);
        if ($resultSelect) {
            $filelog = fopen("../logs/logPatients.txt", "a");

            fwrite($filelog, "-----------------------------------\n");
            fwrite($filelog, "ID Pacijenta: $resultSelect->id_patient\n");
            fwrite($filelog, "\tIme i prezime pacijenta: $resultSelect->name $resultSelect->surname\n");
            fwrite($filelog, "\tIme roditelja: $resultSelect->parent_name\n");
            fwrite($filelog, "\tEmail: $resultSelect->email\n");
            fwrite($filelog, "\tTelefon: $resultSelect->phone\n");
            fwrite($filelog, "\tJMBG: $resultSelect->jmbg\n");
            fwrite($filelog, "\tBroj lične karte: $resultSelect->identificaton_number\n");
            fwrite($filelog, "\tAdresa: $resultSelect->address\n");
            fwrite($filelog, "\tDatum rođenja: $resultSelect->date_of_birth\n");
            fwrite($filelog, "\tKomentar: $resultSelect->comment\n");
            fwrite($filelog, "\tDatum izmene: $date\n");
            fwrite($filelog, "\tIzmenio: $nameDr $surnameDr\n");
        }

        $queryUpdate = "UPDATE patients SET name='$name', surname='$surname', parent_name='$parentName', date_of_birth='$dateOfBirth', jmbg=$jmbg, identificaton_number='$identificationNumber', email='$email', phone='$phone', address='$address', comment='$commentPatient' WHERE id_patient=$id";
        $resultQueryUpdate = $con->query($queryUpdate);
        if ($resultQueryUpdate) {
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
