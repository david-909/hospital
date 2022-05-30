
<?php
header("Content-type: application/json");
$return = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $array = [];
        $error;
        include_once("functions.php");
        include_once("../data/connection.php");
        global $con;
        $comment = $_POST["commentUpdate"];
        $idAccount = +checkVariable("idAccount");
        $idDoctor = +checkVariable("idDoctor");
        $totalPriceBloods = +checkVariable("totalPriceBloods");
        $totalPriceAppointments = +checkVariable("totalPriceAppointments");
        $totalPriceMedicines = +checkVariable("totalPriceMedicines");
        $totalPrice = +checkVariable("totalPrice");
        checkVariable("bloodsIds") ? $bloodsIds = array_map('intval', $_POST["bloodsIds"]) : $bloodsIds = 0;
        checkVariable("appointmentsIds") ? $appointmentsIds = array_map('intval', $_POST["appointmentsIds"]) : $appointmentsIds = 0;
        if (checkVariable("medicinesIds")) {
            $medicinesIds = array_map('intval', $_POST["medicinesIds"]);
            $medicinesQuantity = array_map('intval', $_POST["medicinesQuantity"]);
            $medicinesPrice = array_map('intval', $_POST["medicinesPrice"]);
        } else {
            $medicinesPrice = 0;
            $medicinesIds = 0;
            $medicinesQuantity = 0;
        }
        $query1 = "SELECT *,ath.id_therapy as therapyId FROM accounts a INNER JOIN accounts_therapies ath ON a.id_account = ath.id_account INNER JOIN therapies t ON ath.id_therapy = t.id_therapy WHERE a.id_account = $idAccount LIMIT 1";
        $fetch = $con->query($query1)->fetchColumn();
        if ($fetch) {
            $resultForTherapyType = $con->query($query1)->fetch();

            $idTherapy = (int)$resultForTherapyType->therapyId;
            $idTherapyType = (int)$resultForTherapyType->id_therapy_type;
        } else {
            $idTherapy = 0;
            $idTherapyType = 0;
        }

        $query = "SELECT *,a.comment as commentAccount,a.date as dateAccount,p.name as patientName, p.surname as patientSurname, e.name as doctorName, e.surname as doctorSurname, a.id_account as accId FROM accounts a INNER JOIN patients p ON a.id_patient = p.id_patient INNER JOIN employees e ON e.id_employee= a.id_employee WHERE id_account = $idAccount";
        $r = $con->query($query)->fetch();
        if ($r) {
            $filelog = fopen("../logs/log.txt", "a");
            $queryBloods = "SELECT * FROM bloodsview WHERE accountId = $idAccount";
            $resultBloods = $con->query($queryBloods)->fetchAll();
            $queryBloods1 = "SELECT bloodPriceTotal FROM bloodsview WHERE accountId = $idAccount LIMIT 1";
            fwrite($filelog, "-----------------------------------\n");
            fwrite($filelog, "ID Naloga: $r->accId\n");
            fwrite($filelog, "\tIme i prezime pacijenta: $r->patientName $r->patientSurname\n");
            fwrite($filelog, "\tIme i prezime doktora: $r->doctorName $r->doctorSurname\n");
            fwrite($filelog, "\tDatum naloga: $r->dateAccount\n");

            $fetchColumn = $con->query($queryBloods1)->fetchColumn();
            if ($fetchColumn) {
                $resBloods = $con->query($queryBloods1)->fetch();
                foreach ($resultBloods as $rb) {
                    fwrite($filelog, "\t\tAnaliza: $rb->analysis\t");
                    fwrite($filelog, "\t\tCena analize: $rb->bloodPrice\n");
                }
                fwrite($filelog, "\tUkupna cena analiza: $resBloods->bloodPriceTotal\n");
            } else {
                fwrite($filelog, "\t\tNema analiza\n");
            }
            $queryAppointment = "SELECT * FROM appointmentsview WHERE accountId = $idAccount";
            $resultAppointments = $con->query($queryAppointment)->fetchAll();
            $queryAppointment1 = "SELECT appointmentPriceTotal FROM appointmentsview WHERE accountId= $idAccount LIMIT 1";

            $fetchColumn = $con->query($queryAppointment1)->fetchColumn();
            if ($fetchColumn) {
                $resultAppointments1 = $con->query($queryAppointment1)->fetch();
                foreach ($resultAppointments as $ra) {
                    fwrite($filelog, "\t\tPregled: $ra->appointment\t");
                    fwrite($filelog, "\t\tCena pregleda: $ra->appointmentPrice\n");
                }
                fwrite($filelog, "\tUkupna cena pregleda: $resultAppointments1->appointmentPriceTotal\n");
            } else {
                fwrite($filelog, "\t\tNema Pregleda\n");
            }
            $queryMedicines = "SELECT * FROM medicineview WHERE accountId=$idAccount";
            $resultMedicines = $con->query($queryMedicines)->fetchAll();
            $queryMedicines1 = "SELECT therapyPriceTotal, therapy,totalPrice FROM medicineview WHERE accountId = $idAccount LIMIT 1";

            $fetchColumn = $con->query($queryMedicines1)->fetchColumn();
            if ($fetchColumn) {
                $resultMedicines1 = $con->query($queryMedicines1)->fetch();
                fwrite($filelog, "\tTerapija: $resultMedicines1->therapy\n");
                foreach ($resultMedicines as $rm) {
                    fwrite($filelog, "\t\tLek: $rm->medicine");
                    fwrite($filelog, "\t\tCena pojedinačnog leka: $rm->medicinePrice");
                    fwrite($filelog, "\t\tKoličina leka: $rm->tmQuantity\n");
                }
                fwrite($filelog, "\tUkupna cena lekova: $resultMedicines1->therapyPriceTotal\n");
            } else {
                fwrite($filelog, "\t\tNema terapije\n");
            }
            fwrite($filelog, "\tUkupna cena Naloga: $r->totalPrice\n");

            fwrite($filelog, "\tKomentar: $r->commentAccount\n");
        }
        if ($medicinesIds != 0) {
            for ($i = 0; $i < count($medicinesIds); $i++) {
                $error = checkMedicineQuantity($medicinesQuantity[$i], $medicinesIds[$i]);
                array_push($array, $error);
            }
            if (in_array(false, $array)) {
                echo json_encode(5);
            } else {
                $resultEditAccount = updateAccount($idDoctor, $comment, "", "", "", "", "", $totalPriceBloods, $totalPriceMedicines, $totalPriceAppointments, $totalPrice, $idAccount);
                if ($resultEditAccount) {
                    //                                     BLOODS 
                    $resultFromDeleting = deleteFromTable("accounts_bloods", "id_account", $idAccount);
                    if ($resultFromDeleting and $bloodsIds != 0) {
                        insertMultipleValuesIntoTable($bloodsIds, "accounts_bloods", $idAccount);
                    }
                    //-------------------------------------------------------------------------------------
                    //                                     APPOINTMENT
                    $resultFromDeleting = deleteFromTable("accounts_appointments", "id_account", $idAccount);
                    if ($resultFromDeleting and $appointmentsIds != 0) insertMultipleValuesIntoTable($appointmentsIds, "accounts_appointments", $idAccount);
                    //-------------------------------------------------------------------------------------
                    //                                     MEDICINES
                    // CHECK IF ITS PREDEFINED THERAPY 
                    // TODO OVDE IDE INSERT ZA LEKOVE
                    if ($idTherapyType != 0) {
                        if ($idTherapyType == 1) {
                            //----------------------------------------------
                            #$resultFromDeleting1 = deleteFromTable("therapy_medicine", "id_therapy", $idTherapy);
                            $resultFromDeleting2 = deleteFromTable("medicine_usage", "id_account", $idAccount);
                            //--------------------------------------------
                            if ($resultFromDeleting1 and $resultFromDeleting2 and $medicinesIds != 0) {
                                $lastInsertId = insertNewTherapy();
                                insertIntoTherapyMedicine($lastInsertId, $medicinesIds, $medicinesQuantity);
                                updateAccountsTherapies($idAccount, $lastInsertId);
                                for ($i = 0; $i < count($medicinesIds); $i++) {
                                    //TODO INSERT ZA MEDICINE_USAGE
                                    $total = (int)$medicinesQuantity[$i] * $medicinesPrice[$i];
                                    $medicineId = (int)$medicinesIds[$i];
                                    $quantity = (int)$medicinesQuantity[$i];
                                    insertMedicineUsageUpdate($medicineId, $idAccount, $quantity, $total);
                                }
                                updateMedicines($lastInsertId);
                            }
                        } else {
                            $resultFromDeleting1 = deleteFromTable("therapy_medicine", "id_therapy", $idTherapy);
                            $resultFromDeleting2 = deleteFromTable("medicine_usage", "id_account", $idAccount);
                            //--------------------------------------------
                            if ($resultFromDeleting1 and $resultFromDeleting2 and $medicinesIds != 0) {
                                insertIntoTherapyMedicine($idTherapy, $medicinesIds, $medicinesQuantity);
                                for ($i = 0; $i < count($medicinesIds); $i++) {
                                    //TODO INSERT ZA MEDICINE_USAGE
                                    $total = (int)$medicinesQuantity[$i] * $medicinesPrice[$i];
                                    $medicineId = (int)$medicinesIds[$i];
                                    $quantity = (int)$medicinesQuantity[$i];
                                    insertMedicineUsageUpdate($medicineId, $idAccount, $quantity, $total);
                                }
                                updateMedicines($idTherapy);
                            }
                        }
                        // CHECK IF MEDICINESIDS IS 0
                    } elseif ($idTherapyType == 0) {
                        if ($medicinesIds != 0) {
                            //--------------------------------------------
                            $lastInsertId = insertNewTherapy();
                            insertIntoTherapyMedicine($lastInsertId, $medicinesIds, $medicinesQuantity);
                            insertAccountsTherapies($idAccount, $lastInsertId);
                            for ($i = 0; $i < count($medicinesIds); $i++) {
                                //TODO INSERT ZA MEDICINE_USAGE
                                $total = (int)$medicinesQuantity[$i] * $medicinesPrice[$i];
                                $medicineId = (int)$medicinesIds[$i];
                                $quantity = (int)$medicinesQuantity[$i];
                                insertMedicineUsageUpdate($medicineId, $idAccount, $quantity, $total);
                            }
                            updateMedicines($lastInsertId);
                        }
                    }
                }
                $totalPriceAccount = $con->query("SELECT totalPrice FROM accounts where id_account = $idAccount")->fetch()->totalPrice;
                $newPrice = +$totalPrice + +$totalPriceAccount;
                $con->query("UPDATE accounts SET totalPrice = $newPrice WHERE id_account = $idAccount");
                echo json_encode(1);
            }
        } else {
            $resultEditAccount = updateAccount($idDoctor, $comment, "", "", "", "", "", $totalPriceBloods, $totalPriceMedicines, $totalPriceAppointments, $totalPrice, $idAccount);
            if ($resultEditAccount) {
                //                                     BLOODS 
                $resultFromDeleting = deleteFromTable("accounts_bloods", "id_account", $idAccount);
                if ($resultFromDeleting and $bloodsIds != 0) {
                    insertMultipleValuesIntoTable($bloodsIds, "accounts_bloods", $idAccount);
                }
                //-------------------------------------------------------------------------------------
                //                                     APPOINTMENT
                $resultFromDeleting = deleteFromTable("accounts_appointments", "id_account", $idAccount);
                if ($resultFromDeleting and $appointmentsIds != 0) insertMultipleValuesIntoTable($appointmentsIds, "accounts_appointments", $idAccount);
                //-------------------------------------------------------------------------------------
                //                                     MEDICINES
                // CHECK IF ITS PREDEFINED THERAPY 
                deleteFromTable("accounts_therapies", "id_account", $idAccount);
                deleteFromTable("medicine_usage", "id_account", $idAccount);
            }
            $totalPriceAccount = $con->query("SELECT totalPrice FROM accounts where id_account = $idAccount")->fetch()->totalPrice;
            $newPrice = +$totalPrice + +$totalPriceAccount;
            $con->query("UPDATE accounts SET totalPrice = $newPrice WHERE id_account = $idAccount");
            echo json_encode(1);
        }

        // UPDATE ACCOUNT UPDATE ACCOUNT UPDATE ACCOUNT UPDATE ACCOUNT UPDATE ACCOUNT UPDATE ACCOUNT UPDATE ACCOUNT

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
