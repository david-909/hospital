<?php

header("Content-type: application/json");

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    try {
        include_once("../data/connection.php");
        include_once("functions.php");
        global $con;
        if (checkVariable("isSelectedTherapy")) {
            #echo "selektovana je neka terapija";
            $medicineQuantity = checkVariable("medicineQuantity");
            $idMedicines = checkVariable("idMedicines");
            $idChosenTherapy = checkVariable("idChosenTherapy");
            $idTherapyType = checkVariable("idTherapyType");
        }

        $idSubAccount = checkVariable("subAccount");
        $idAccount = checkVariable("idAccount");
        $idDoctor = checkVariable("idDoctor");
        $idAppointments = checkVariable("idAppointments");
        $appointmentPrice = checkVariable("appointmentPrice");
        $bloodsPrice = checkVariable("bloodsPrice");
        $therapyPrice = checkVariable("therapyPrice");
        $totalPrice = checkVariable("totalPrice");
        $idBloods = checkVariable("idBloods");
        isset($_POST["commentAccount"]) and !empty($_POST["commentAccount"]) ? $commentAccount = $_POST["commentAccount"] : $commentAccount = "";
        settype($idChosenTherapy, "integer");
        settype($idTherapyType, "integer");
        // !CHECK VALUES
        if ($idTherapyType != 0) { // !THERAPY IS SELECTED
            for ($i = 0; $i < count($medicineQuantity); $i++) { // !CHECK QUANTITY OF MEDICINES
                $medicineId = $idMedicines[$i];
                $quanityMedicine = $medicineQuantity[$i];
                $queryForCheckMedicineQuantity = "SELECT quantity FROM medicines WHERE id_medicine = $medicineId";
                $currentQuantity = $con->query($queryForCheckMedicineQuantity)->fetch();
                if ($quanityMedicine > $currentQuantity->quantity) { // !NOT ENOGH MEDICINES
                    echo json_encode(0);
                    die;
                } else { // !UPDATE MEDICINE QUANTITY
                    $queryForUpdateMedicineQuantity = "UPDATE medicines SET quantity = quantity-$quanityMedicine WHERE id_medicine = $medicineId";
                    $con->query($queryForUpdateMedicineQuantity);
                }
            }
            if ($idTherapyType == 2) { //! CUSTOM
                // !INSERT INTO THERAPY
                $queryForInsertTherapy = "INSERT INTO therapies VALUES(null,'Terapija', 4)";
                $con->query($queryForInsertTherapy);
                $lastInsertTherapyId = +$con->lastInsertId();
                for ($i = 0; $i < count($idMedicines); $i++) {
                    settype($idMedicines[$i], "integer");
                    settype($medicineQuantity[$i], "integer");
                    $singleIdMedicine = $idMedicines[$i];
                    $singleQuantityMedicine = $medicineQuantity[$i];

                    $queryForTherapyMedicine = "INSERT INTO therapy_medicine VALUES (null,$lastInsertTherapyId,$singleIdMedicine,$singleQuantityMedicine)";
                    $con->query($queryForTherapyMedicine);
                    $queryForInsertSubAccountTherapy = "INSERT INTO sub_accounts_therapies VALUES(null,$idSubAccount,$lastInsertTherapyId)";
                    $con->query($queryForInsertSubAccountTherapy);
                    $p = $con->query("SELECT * FROM medicine_prices WHERE id_medicine = $singleIdMedicine")->fetch()->price;
                    $quantityXprice = $p * $singleQuantityMedicine;
                    $MU = "INSERT INTO sub_accounts_medicine_usage VALUES (null,$singleIdMedicine,$idSubAccount,$singleQuantityMedicine,$quantityXprice,CURRENT_TIMESTAMP)";
                    $con->query($MU);
                }
            } else { //! PREDEFINISANA TERAPIJA
                $queryForInsertSubAccountTherapy = "INSERT INTO sub_accounts_therapies VALUES(null,$idSubAccount,$idChosenTherapy)";
                $con->query($queryForInsertSubAccountTherapy);
                $result = $con->query("SELECT * FROM therapy_medicine WHERE id_therapy = $idChosenTherapy")->fetchAll();
                foreach ($result as $r) {
                    $idMedicine = $r->id_medicine;
                    $price = $con->query("SELECT * FROM medicine_prices WHERE id_medicine = $idMedicine")->fetch()->price;
                    $q = $r->quantity;
                    $qxp = $price * $q;
                    $con->query("INSERT INTO sub_accounts_medicine_usage VALUES(null,$idMedicine,$idSubAccount,$q,$qxp,CURRENT_TIMESTAMP)");
                }
            }
        }
        if ($idBloods != 0) {
            for ($i = 0; $i < count($idBloods); $i++) {
                $bloodId = $idBloods[$i];
                $queryForInsertBloods = "INSERT INTO sub_accounts_bloods VALUES (null,$idSubAccount,$bloodId)";
                $con->query($queryForInsertBloods);
            }
        }
        if ($idAppointments != 0) {
            for ($i = 0; $i < count($idAppointments); $i++) {
                $appointmentId = $idAppointments[$i];
                $queryForInsertAppointments = "INSERT INTO sub_accounts_appointments VALUES (null,$idSubAccount,$appointmentId)";
                $con->query($queryForInsertAppointments);
            }
        }
        // TODO UPDATE SUB_ACCOUNT
        $queryForUpdateSubAccount = "UPDATE sub_accounts SET id_employee = $idDoctor, comment = '$commentAccount',date = CURRENT_TIMESTAMP,bloodPrice = $bloodsPrice, therapyPrice = $therapyPrice, appointmentPrice = $appointmentPrice, totalPrice = $totalPrice WHERE id_sub_account = $idSubAccount";
        $con->query($queryForUpdateSubAccount);

        echo json_encode(1);
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
