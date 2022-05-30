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
        $idSubAccount = +checkVariable("idSubAccount");
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
        $query1 = "SELECT *,ath.id_therapy as therapyId FROM sub_accounts sa INNER JOIN sub_accounts_therapies ath ON sa.id_sub_account = ath.id_sub_account INNER JOIN therapies t ON ath.id_therapy = t.id_therapy WHERE sa.id_sub_account = $idSubAccount LIMIT 1";
        $fetch = $con->query($query1)->fetchColumn();
        if ($fetch) {
            $resultForTherapyType = $con->query($query1)->fetch();

            $idTherapy = (int)$resultForTherapyType->therapyId;
            $idTherapyType = (int)$resultForTherapyType->id_therapy_type;
        } else {
            $idTherapy = 0;
            $idTherapyType = 0;
        }

        $query = "SELECT *,sa.totalPrice as totalPrice,sa.comment as commentAccount,sa.date as dateAccount,p.name as patientName, p.surname as patientSurname, e.name as doctorName, e.surname as doctorSurname, a.id_account as accId FROM sub_accounts sa INNER JOIN accounts a ON sa.id_account = a.id_account INNER JOIN patients p ON a.id_patient = p.id_patient INNER JOIN employees e ON e.id_employee= a.id_employee WHERE id_sub_account = $idSubAccount";
        $r = $con->query($query)->fetch();
        if ($r) {
            $filelog = fopen("../logs/logSubAccounts.txt", "a");
            $queryBloods = "SELECT * FROM subBloodsView WHERE idSubAccount = $idSubAccount";
            $resultBloods = $con->query($queryBloods)->fetchAll();
            $queryBloods1 = "SELECT bloodPriceTotal FROM subBloodsView WHERE idSubAccount = $idSubAccount LIMIT 1";
            fwrite($filelog, "-----------------------------------\n");
            fwrite($filelog, "ID Podnaloga: $r->accId\n");
            fwrite($filelog, "\tIme i prezime pacijenta: $r->patientName $r->patientSurname\n");
            fwrite($filelog, "\tIme i prezime doktora: $r->doctorName $r->doctorSurname\n");
            fwrite($filelog, "\tDatum naloga: $r->dateAccount\n");

            $fetchColumn = $con->query($queryBloods1)->fetchColumn();
            if ($fetchColumn) {
                $resBloods = $con->query($queryBloods1)->fetch();
                foreach ($resultBloods as $rb) {
                    fwrite($filelog, "\t\tAnaliza: $rb->blood\t");
                    fwrite($filelog, "\t\tCena analize: $rb->price\n");
                }
                fwrite($filelog, "\tUkupna cena analiza: $resBloods->bloodPriceTotal\n");
            } else {
                fwrite($filelog, "\t\tNema analiza\n");
            }
            $queryAppointment = "SELECT * FROM subAppointmentsView WHERE idSubAccount = $idSubAccount";
            $resultAppointments = $con->query($queryAppointment)->fetchAll();
            $queryAppointment1 = "SELECT appointmentPriceTotal FROM subAppointmentsView WHERE idSubAccount= $idSubAccount LIMIT 1";

            $fetchColumn = $con->query($queryAppointment1)->fetchColumn();
            if ($fetchColumn) {
                $resultAppointments1 = $con->query($queryAppointment1)->fetch();
                foreach ($resultAppointments as $ra) {
                    fwrite($filelog, "\t\tPregled: $ra->appointment\t");
                    fwrite($filelog, "\t\tCena pregleda: $ra->price\n");
                }
                fwrite($filelog, "\tUkupna cena pregleda: $resultAppointments1->appointmentPriceTotal\n");
            } else {
                fwrite($filelog, "\t\tNema Pregleda\n");
            }
            $queryMedicines = "SELECT * FROM subMedicineView WHERE idSubAccount=$idSubAccount";
            $resultMedicines = $con->query($queryMedicines)->fetchAll();
            $queryMedicines1 = "SELECT therapyPriceTotal, therapy,totalPrice FROM subMedicineView WHERE idSubAccount = $idSubAccount LIMIT 1";

            $fetchColumn = $con->query($queryMedicines1)->fetchColumn();
            if ($fetchColumn) {
                $resultMedicines1 = $con->query($queryMedicines1)->fetch();
                fwrite($filelog, "\tTerapija: $resultMedicines1->therapy\n");
                foreach ($resultMedicines as $rm) {
                    fwrite($filelog, "\t\tLek: $rm->medicines");
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
                $resultEditAccount = updateAccount($idDoctor, $comment, "", "", "", "", "", $totalPriceBloods, $totalPriceMedicines, $totalPriceAppointments, $totalPrice, $idAccount, $idSubAccount);
                if ($resultEditAccount) {
                    //                                     BLOODS 
                    $resultFromDeleting = deleteFromTable("sub_accounts_bloods", "id_sub_account", $idSubAccount);
                    if ($resultFromDeleting and $bloodsIds != 0) {
                        insertMultipleValuesIntoTable($bloodsIds, "sub_accounts_bloods", $idSubAccount);
                    }
                    //-------------------------------------------------------------------------------------
                    //                                     APPOINTMENT
                    $resultFromDeleting = deleteFromTable("sub_accounts_appointments", "id_sub_account", $idSubAccount);
                    if ($resultFromDeleting and $appointmentsIds != 0) insertMultipleValuesIntoTable($appointmentsIds, "sub_accounts_appointments", $idSubAccount);
                    //-------------------------------------------------------------------------------------
                    //                                     MEDICINES
                    // CHECK IF ITS PREDEFINED THERAPY 
                    // TODO OVDE IDE INSERT ZA LEKOVE
                    if ($idTherapyType != 0) {
                        if ($idTherapyType == 1) {
                            //----------------------------------------------
                            #$resultFromDeleting1 = deleteFromTable("therapy_medicine", "id_therapy", $idTherapy);
                            $resultFromDeleting2 = deleteFromTable("sub_accounts_medicine_usage", "id_sub_account", $idSubAccount);
                            //--------------------------------------------
                            if ($resultFromDeleting2 and $medicinesIds != 0) {
                                $lastInsertId = insertNewTherapy();
                                insertIntoTherapyMedicine($lastInsertId, $medicinesIds, $medicinesQuantity);
                                updateAccountsTherapies($idAccount, $lastInsertId, $idSubAccount);
                                for ($i = 0; $i < count($medicinesIds); $i++) {
                                    //TODO INSERT ZA MEDICINE_USAGE
                                    $total = (int)$medicinesQuantity[$i] * $medicinesPrice[$i];
                                    $medicineId = (int)$medicinesIds[$i];
                                    $quantity = (int)$medicinesQuantity[$i];
                                    insertMedicineUsageUpdate($medicineId, $idAccount, $quantity, $total, $idSubAccount);
                                }
                                updateMedicines($lastInsertId);
                            }
                        } else {
                            $resultFromDeleting1 = deleteFromTable("therapy_medicine", "id_therapy", $idTherapy);
                            $resultFromDeleting2 = deleteFromTable("sub_accounts_medicine_usage", "id_sub_account", $idSubAccount);
                            //--------------------------------------------
                            if ($resultFromDeleting1 and $resultFromDeleting2 and $medicinesIds != 0) {
                                insertIntoTherapyMedicine($idTherapy, $medicinesIds, $medicinesQuantity);
                                for ($i = 0; $i < count($medicinesIds); $i++) {
                                    //TODO INSERT ZA MEDICINE_USAGE
                                    $total = (int)$medicinesQuantity[$i] * $medicinesPrice[$i];
                                    $medicineId = (int)$medicinesIds[$i];
                                    $quantity = (int)$medicinesQuantity[$i];
                                    insertMedicineUsageUpdate($medicineId, $idAccount, $quantity, $total, $idSubAccount);
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
                            insertAccountsTherapies($idAccount, $lastInsertId, $idSubAccount);
                            for ($i = 0; $i < count($medicinesIds); $i++) {
                                //TODO INSERT ZA MEDICINE_USAGE
                                $total = (int)$medicinesQuantity[$i] * $medicinesPrice[$i];
                                $medicineId = (int)$medicinesIds[$i];
                                $quantity = (int)$medicinesQuantity[$i];
                                insertMedicineUsageUpdate($medicineId, $idAccount, $quantity, $total, $idSubAccount);
                            }
                            updateMedicines($lastInsertId);
                        }
                    }
                }
                echo json_encode(1);
            }
        } else {
            $resultEditAccount = updateAccount($idDoctor, $comment, "", "", "", "", "", $totalPriceBloods, $totalPriceMedicines, $totalPriceAppointments, $totalPrice, $idAccount, $idSubAccount);
            if ($resultEditAccount) {
                //                                     BLOODS 
                $resultFromDeleting = deleteFromTable("sub_accounts_bloods", "id_sub_account", $idSubAccount);
                if ($resultFromDeleting and $bloodsIds != 0) {
                    insertMultipleValuesIntoTable($bloodsIds, "sub_accounts_bloods", $idSubAccount);
                }
                //-------------------------------------------------------------------------------------
                //                                     APPOINTMENT
                $resultFromDeleting = deleteFromTable("sub_accounts_appointments", "id_sub_account", $idSubAccount);
                if ($resultFromDeleting and $appointmentsIds != 0) insertMultipleValuesIntoTable($appointmentsIds, "sub_accounts_appointments", $idSubAccount);
                //-------------------------------------------------------------------------------------
                //                                     MEDICINES
                // CHECK IF ITS PREDEFINED THERAPY 
                deleteFromTable("sub_accounts_therapies", "id_sub_account", $idSubAccount);
                deleteFromTable("sub_accounts_medicine_usage", "id_sub_account", $idSubAccount);
            }
            echo json_encode(1);
        }

        // UPDATE ACCOUNT UPDATE ACCOUNT UPDATE ACCOUNT UPDATE ACCOUNT UPDATE ACCOUNT UPDATE ACCOUNT UPDATE ACCOUNT

    } catch (PDOException $e) {
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
