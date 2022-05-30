<?php
if (isset($_GET["idSubAccount"]) and !empty($_GET["idSubAccount"])) {
    $idSubAccount = $_GET["idSubAccount"];
    $arrowsvg = file_get_contents("./assets/img/arrow.svg");
    $deletesvg = file_get_contents("./assets/img/deletebtn.svg");
    $viewsvg = file_get_contents("./assets/img/viewbtn.svg");
    $editsvg = file_get_contents("./assets/img/editbtn.svg");
    $printsvg = file_get_contents("./assets/img/printbtn.svg");
    $duplicatesvg = file_get_contents("./assets/img/duplicatebtn.svg");
    $searchico = file_get_contents("./assets/img/search.svg");
    $reloadsvg = file_get_contents("./assets/img/reloadbtn.svg");
    try {
        include_once("data/connection.php");
        global $con;
        $idAccount = +$con->query("SELECT id_account FROM sub_accounts where id_sub_account = $idSubAccount")->fetch()->id_account;
        $query = "SELECT * FROM sub_accounts WHERE id_account = $idAccount";
        $rowCount = +$con->query($query)->rowCount();
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
    if ($rowCount == 0) {
?>
        <script>
            window.location.replace("404");
        </script>
    <?php
    }

    ?>
    <div class="modal modal-s2" id="modal10">
        <div class="modal-header" id="modal10-header">
            <p>PREGLED NALOGA</p>
            <button class="exitexitexit close-button"><?php echo $deletesvg ?></button>
        </div>
        <div class="modal-body scroll" id="modal10-body">

            <?php
            $queryForDoctor = "SELECT name,surname, sa.id_employee as employeeId FROM sub_accounts sa INNER JOIN accounts a ON a.id_account = sa.id_account INNER JOIN employees e ON sa.id_employee = e.id_employee WHERE sa.id_sub_account = $idSubAccount";
            $resultForDoctor = $con->query($queryForDoctor)->fetch();
            $output = "<label for='chooseDoctorUpdate'>Doktor</label><select id='chooseDoctorUpdate' name='chooseDoctorUpdate'><option value='$resultForDoctor->employeeId'>$resultForDoctor->name $resultForDoctor->surname</option>";
            $queryDr = "SELECT * FROM employees WHERE id_role = 1"; // SAMO DOKTORI 
            $resultDoctors = $con->query($queryDr)->fetchAll();
            foreach ($resultDoctors as $rd) {
                if ($resultForDoctor->employeeId != $rd->id_employee) {

                    $output .= "<option value='$rd->id_employee'>$rd->name $rd->surname</option>";
                }
            }
            $output .= "</select>";
            echo $output;
            ?>
            <label for="bloodUpdate">Krv</label>
            <input type="text" id="searchBloodUpdate" placeholder="Pretrazite krv">
            <div id="searchBloodResultUpdate"></div>
            <select name="bloodUpdate" id="bloodUpdate" size="11" multiple>
                <?php
                try {
                    include_once("data/connection.php");
                    global $con;
                    $query = "SELECT * FROM bloods LIMIT 100";
                    $result = $con->query($query)->fetchAll();
                    if ($result) {
                        foreach ($result as $r) {
                            echo ("<option class='chosenBloodUpdate' data-id='$r->id_blood' data-price='$r->price'>$r->analysis</option>");
                        }
                    }
                } catch (PDOException $e) {
                    http_response_code(500);
                    echo $e->getMessage();
                }
                ?>
            </select>
            <button id="moveToFinalBloodsUpdate">>>></button>
            <button id="moveFromFinalBloodsUpdate">
                <<< </button>
                    <select name="finalBloodsUpdate" id="finalBloodsUpdate" size="11" multiple>
                        <?php
                        try {
                            $query = "SELECT DISTINCT * FROM subBloodsView INNER JOIN bloods on subBloodsView.blood = bloods.analysis WHERE idSubAccount = $idSubAccount";
                            $output = "";
                            $result = $con->query($query)->fetchAll();
                            foreach ($result as $r) {
                                $output .= "<option class='chosenBloodUpdate' data-price='$r->price' data-id='$r->id_blood'>$r->analysis</option>";
                            }
                            echo $output;
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }

                        ?>
                    </select>Cena krvi:
                    <span id='totalPriceBloodsUpdate'><?php
                                                        $query = "SELECT bloodPrice FROM sub_accounts WHERE id_sub_account = $idSubAccount";
                                                        $result = $con->query($query)->fetch();
                                                        echo "$result->bloodPrice";
                                                        ?></span>
                    <label for="appointmentUpdate">Pregledi</label>
                    <select name="appointmentUpdate" id="appointmentUpdate" size="5" multiple>
                        <?php
                        try {
                            $query = "SELECT * FROM appointments";
                            $result = $con->query($query)->fetchAll();
                            if ($result) {
                                foreach ($result as $r) {
                                    echo ("<option class='chosenAppointmentUpdate' data-id='$r->id_appointment' data-price='$r->price'>$r->appointment</option>");
                                }
                            }
                        } catch (PDOException $e) {
                            http_response_code(500);
                            echo $e->getMessage();
                        }
                        ?>
                    </select>
                    <button id="moveToFinalAppointmentsUpdate">>>></button>
                    <button id="moveFromFinalAppointmentsUpdate">
                        <<< </button>
                            <select name="finalAppointmentsUpdate" id="finalAppointmentsUpdate" size="5" multiple>
                                <?php
                                try {
                                    $query = "SELECT DISTINCT * FROM subAppointmentsView INNER JOIN appointments on appointments.appointment = subAppointmentsView.appointment WHERE idSubAccount= $idSubAccount";
                                    $output = "";
                                    $result = $con->query($query)->fetchAll();
                                    foreach ($result as $r) {
                                        $output .= "<option data-price='$r->price' data-id='$r->id_appointment'>$r->appointment</option>";
                                    }
                                    echo $output;
                                } catch (PDOException $e) {
                                    echo $e->getMessage();
                                }
                                ?>
                            </select>Cena pregleda:
                            <span id='totalPriceAppointmentsUpdate'><?php
                                                                    $result = $con->query("SELECT appointmentPrice FROM sub_accounts WHERE id_sub_account = $idSubAccount")->fetch();
                                                                    echo "$result->appointmentPrice";
                                                                    ?></span>
                            <label for="therapies">Lekovi</label>
                            <div id="therapies">
                                <?php
                                try {
                                    include_once("data/connection.php");
                                    global $con;
                                    // $query = "SELECT * FROM accounts a INNER JOIN accounts_therapies ath ON a.id_account = ath.id_account INNER JOIN therapies t ON ath.id_therapy = t.id_therapy WHERE a.id_account = $idAccount";

                                    $query = "SELECT *, sat.id_therapy as therapyId FROM sub_accounts sa INNER JOIN sub_accounts_therapies sat ON sat.id_sub_account = sa.id_sub_account INNER JOIN therapies t ON sat.id_therapy = t.id_therapy WHERE sa.id_sub_account = $idSubAccount";
                                    $result = $con->query($query)->fetch();
                                    if ($result) {
                                        $output = "<label for='searchMedicineUpdate'>Pretraži lek</label><input type='text' id='searchMedicineUpdate' name='searchMedicineUpdate'/><div id='medicinesUpdate'>";
                                        // foreach ($result as $r) {

                                        $queryMedicines = "SELECT *,tm.quantity as tmQuantity, m.id_medicine as medicineId FROM therapies t INNER JOIN  therapy_medicine tm ON t.id_therapy = tm.id_therapy INNER JOIN medicines m ON tm.id_medicine=m.id_medicine INNER JOIN medicine_prices mp ON m.id_medicine = mp.id_medicine WHERE t.id_therapy = $result->therapyId";
                                        $totalMedicinesPriceUpdate = 0;
                                        $resultMedicines = $con->query($queryMedicines)->fetchAll();
                                        foreach ($resultMedicines as $rm) {
                                            $singeMedicinePriceUpdate = $rm->tmQuantity * $rm->price;
                                            $output .= "<div class='singleMedicine'><label for='medicineInput'>$rm->medicine</label><input type='number' name='medicineInput' class='medicineQuantityUpdate' data-id='$rm->medicineId' data-price='$rm->price' value='$rm->tmQuantity' min='1'><button class='deleteMedicine'>X</button><span class='singleMedicinePriceUpdate fc'>$singeMedicinePriceUpdate</span></div>";
                                            $totalMedicinesPriceUpdate += $singeMedicinePriceUpdate;
                                        }
                                        // }
                                        $query = "SELECT totalPrice,comment from sub_accounts WHERE id_sub_account = $idSubAccount";
                                        $resultQuery = $con->query($query)->fetch();
                                        $totalAccountPriceUpdate = $resultQuery->totalPrice;
                                        $output .= "</div><div id='searchedMedicines'></div>Cena lekova: <span id='totalMedicinesPriceUpdate'>$totalMedicinesPriceUpdate</span><label for='commentUpdate'>Komentar</label>
                                                <textarea name='commentUpdate' id='commentUpdate' cols='30' rows='10'>
                                                $resultQuery->comment
                                                </textarea><hr>Ukupna cena naloga: <span id='totalAccountPriceUpdate'>$totalAccountPriceUpdate</span><button data-idAccount='$idAccount'
                                                data-idSubAccount = '$idSubAccount' id='updateButton1'>Izmeni</button>";
                                        echo $output;
                                    } else {
                                        $output = "<label for='searchMedicineUpdate'>Pretraži lek</label><input type='text' id='searchMedicineUpdate' name='searchMedicineUpdate'/><div id='medicinesUpdate'>";
                                        if ($result) {
                                            foreach ($result as $r) {

                                                $queryMedicines = "SELECT *,tm.quantity as tmQuantity, m.id_medicine as medicineId FROM therapies t INNER JOIN  therapy_medicine tm ON t.id_therapy = tm.id_therapy INNER JOIN medicines m ON tm.id_medicine=m.id_medicine INNER JOIN medicine_prices mp ON m.id_medicine = mp.id_medicine WHERE t.id_therapy = $r->therapyId";
                                                $totalMedicinesPriceUpdate = 0;
                                                $resultMedicines = $con->query($queryMedicines)->fetchAll();
                                                foreach ($resultMedicines as $rm) {
                                                    $singeMedicinePriceUpdate = $rm->tmQuantity * $rm->price;
                                                    $output .= "<div class='singleMedicine'><label for='medicineInput'>$rm->medicine</label><input type='number' name='medicineInput' class='medicineQuantityUpdate' data-id='$rm->medicineId' data-price='$rm->price' value='$rm->tmQuantity' min='1'><button class='deleteMedicine'>X</button><span class='singleMedicinePriceUpdate fc'>$singeMedicinePriceUpdate</span></div>";
                                                    //$totalMedicinesPriceUpdate += $singeMedicinePriceUpdate;
                                                }
                                            }
                                        }
                                        $query = "SELECT totalPrice,comment from sub_accounts WHERE id_sub_account = $idSubAccount";
                                        $resultQuery = $con->query($query)->fetch();
                                        $totalAccountPriceUpdate = $resultQuery->totalPrice;
                                        $output .= "</div><div id='searchedMedicines'></div>Cena lekova: <span id='totalMedicinesPriceUpdate'>0</span><label for='commentUpdate'>Komentar</label>
                                                <textarea name='commentUpdate' id='commentUpdate' cols='30' rows='10'>
                                                $resultQuery->comment
                                                </textarea><hr>Ukupna cena naloga: <span id='totalAccountPriceUpdate'>$totalAccountPriceUpdate</span><button data-idAccount='$idAccount'
                                                data-idSubAccount='$idSubAccount' id='updateButton1'>Izmeni</button>";
                                        echo $output;
                                    }
                                } catch (PDOException $e) {
                                    http_response_code(500);
                                    echo $e->getMessage();
                                }
                                ?>
                            </div>
        </div>
    </div>
<?php
}
?>