<?php
if (isset($_GET["idSubAccount"]) and !empty($_GET["idSubAccount"])) {
    $idSubAccount = $_GET["idSubAccount"];
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
    <div class="modal modal-s" id="flyingContainterUpdate">
        <div class="modal-header fb">
            <p>IZMENA PODNALOGA</p>

            <?php
            $url = $_SERVER["REQUEST_URI"];
            $arr = explode("/", $url);
            $br = count($arr) - 1;
            $lastOne = $arr[$br];
            $filename = explode("?", $arr[$br]);
            $filename = $filename[0];
            if ($filename == 'nalog.php') {
            ?>
                <button class='exitexit close-button'><?php echo $deletesvg ?></button>
            <?php
            }
            if ($filename == 'adminpanel.php') {
            ?>
                <button class='exitexitexit close-button'><?php echo $deletesvg ?></button>
            <?php
            }
            ?>
        </div>

        <div class="modal-body scroll">
            <p class="idaccountheader">
                <?php
                try {
                    include_once("data/connection.php");
                    global $con;
                    $query = "SELECT name,surname,jmbg FROM accounts a INNER JOIN patients p ON a.id_patient = p.id_patient WHERE id_account = $idAccount";
                    $result = $con->query($query)->fetch();
                    echo "$result->name $result->surname $result->jmbg";
                } catch (PDOException $e) {
                    echo $e->getMessage();
                }
                ?>
            </p>
            <?php
            $queryForDoctor = "SELECT name,surname, sa.id_employee as employeeId FROM sub_accounts sa INNER JOIN accounts a ON a.id_account = sa.id_account INNER JOIN employees e ON sa.id_employee = e.id_employee WHERE sa.id_sub_account = $idSubAccount";
            $resultForDoctor = $con->query($queryForDoctor)->fetch();
            $output = "<div class='select-doctor fdcolumn2 custom-select fc'><label class='label-h1 fc txtc' for='chooseDoctorUpdate'>Doktor</label><select id='chooseDoctorUpdate' name='chooseDoctorUpdate'><option value='$resultForDoctor->employeeId'>$resultForDoctor->name $resultForDoctor->surname</option>";
            $queryDr = "SELECT * FROM employees WHERE id_role = 1"; // SAMO DOKTORI 
            $resultDoctors = $con->query($queryDr)->fetchAll();
            foreach ($resultDoctors as $rd) {
                if ($resultForDoctor->employeeId != $rd->id_employee) {

                    $output .= "<option value='$rd->id_employee'>$rd->name $rd->surname</option>";
                }
            }
            $output .= "</select></div>";
            echo $output;
            ?>



            <div class="build-nalog-body container85 mt20">
                <div class="pregled-krv fnoalign mb10">




                    <div class="accordion1 js-accordion3 mr5">
                        <div class="accordion1__item js-accordion3-item">
                            <div class="accordion1-header js-accordion3-header bluedrop fb">
                                <span>Pregledi</span>
                                <div class="price">
                                    <span>Cena</span>
                                    <span id='totalPriceAppointmentsUpdate'><?php
                                                                            $result = $con->query("SELECT appointmentPrice FROM sub_accounts WHERE id_sub_account = $idSubAccount")->fetch();
                                                                            echo "$result->appointmentPrice";
                                                                            ?></span><span>RSD</span>
                                </div>
                            </div>
                            <div class="accordion1-body js-accordion3-body brbl16 brbr16">
                                <div class="accordion1-body__contents">
                                    <div class="">

                                        <div class="">
                                            <div class="fconly">

                                                <select class="select-list1 pr10 scroll2" name="appointmentUpdate" id="appointmentUpdate" size="5" multiple>
                                                    <?php
                                                    try {
                                                        $query = "SELECT * FROM appointments";
                                                        $result = $con->query($query)->fetchAll();
                                                        if ($result) {
                                                            foreach ($result as $r) {
                                                                echo ("<option class='chosenAppointmentUpdate select-list1-item' data-id='$r->id_appointment' data-price='$r->price'>$r->appointment</option>");
                                                            }
                                                        }
                                                    } catch (PDOException $e) {
                                                        http_response_code(500);
                                                        echo $e->getMessage();
                                                    }
                                                    ?>
                                                </select>

                                                <select class="select-list1 pr10 scroll2" name="finalAppointmentsUpdate" id="finalAppointmentsUpdate" size="5" multiple>
                                                    <?php
                                                    try {
                                                        $query = "SELECT DISTINCT * FROM subAppointmentsView INNER JOIN appointments on appointments.appointment = subAppointmentsView.appointment WHERE idSubAccount= $idSubAccount";
                                                        $output = "";
                                                        $result = $con->query($query)->fetchAll();
                                                        foreach ($result as $r) {
                                                            $output .= "<option class='select-list1-item' data-price='$r->price' data-id='$r->id_appointment'>$r->appointment</option>";
                                                        }
                                                        echo $output;
                                                    } catch (PDOException $e) {
                                                        echo $e->getMessage();
                                                    }
                                                    ?>
                                                </select>

                                            </div>
                                            <div class="fc matuo">
                                                <button class="select-list1-btn s-l1-btn1 btn brbl16" id="moveToFinalAppointmentsUpdate">+</button>
                                                <button class="select-list1-btn s-l1-btn2 btn brbr16" id="moveFromFinalAppointmentsUpdate">-</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>




                    <div class="accordion1 js-accordion3 ml5">
                        <div class="accordion1__item js-accordion3-item">
                            <div class="accordion1-header js-accordion3-header bluedrop fb">
                                <span>Krv</span>
                                <div class="price">
                                    <span>Cena</span>
                                    <span id='totalPriceBloodsUpdate'><?php
                                                                        $query = "SELECT bloodPrice FROM sub_accounts WHERE id_sub_account = $idSubAccount";
                                                                        $result = $con->query($query)->fetch();
                                                                        echo "$result->bloodPrice";
                                                                        ?></span><span>&nbsp;RSD</span>

                                </div>
                            </div>
                            <div class="accordion1-body js-accordion3-body brbl16 brbr16">
                                <div class="accordion1-body__contents">
                                    <div class="">

                                        <div class='search-component fc'><span class='fc'><svg id='search' xmlns='http://www.w3.org/2000/svg' width='682.667' height='682.667' viewBox='0 0 512 512' preserveAspectRatio='xMidYMid meet'>
                                                    <path d='M289 .6c-40.4 3-82 19.2-114 44.5-14.9 11.7-34.4 33.2-44.8 49.4-32.3 49.9-41.3 107.7-25.8 165.9 3.3 12.5 9.4 27.7 16.7 41.4 3.5 6.8 5.9 12.6 5.9 14.5 0 2.8-6 9.2-56.8 60.2-47.2 47.2-57.6 58.2-61.1 64.1-8.2 14-10.7 26.5-7.7 38.3 2 7.7 4.4 11.9 10.9 18.8 15.9 17 36.1 18.7 59.1 5.2 5.9-3.5 16.9-13.9 64.1-61.1 51-50.8 57.4-56.8 60.2-56.8 1.9 0 7.7 2.4 14.5 5.9 29 15.3 56.6 22.7 88.3 23.8 44 1.5 82.3-9.1 119-32.9 16.2-10.4 37.7-29.9 49.4-44.8 23-29.1 37.6-63.7 43.2-102.5 1.9-12.7 1.6-44.3-.5-57.4-6.2-39.3-21.6-73.5-46.4-103.1C431.1 35.5 385.4 10.1 335 2.5 324.6.9 299-.1 289 .6zm40.5 55.9c23 3.6 46.3 13.4 65.7 27.6 10.5 7.8 26.6 24.1 34.2 34.7 38.3 53.8 37.3 128.2-2.4 181-30 39.9-79.3 63.2-128.4 60.9-40.2-1.9-74.8-17-102.6-44.7-60.3-60.4-59.9-157.9 1.1-217.7 35.3-34.6 82.6-49.5 132.4-41.8zm-30.8 27.6c-46.3 5.1-86.6 34-107.1 76.9-5.5 11.4-4.8 20.5 2.2 27.6 6.2 6.2 16.9 8.2 24 4.4 6.2-3.3 7.8-5.1 13.7-15.9 7.1-13.1 12.8-20.5 21.5-28 26.7-23 61.4-28.9 95.3-16.3 9.4 3.5 17 2.5 23.4-3.1 8.8-7.7 9.8-22.2 2-30.5-4.5-4.8-19.4-10.5-35.4-13.6-10.6-2-28.7-2.7-39.6-1.5z'></path>
                                                </svg></span>
                                            <input class='search-input fc br0' type='text' id="searchBloodUpdate" placeholder='Unesite naziv analize...'>
                                            <div id="searchBloodResultUpdate"></div>
                                        </div>

                                        <div class="">
                                            <div class="fc">



                                                <select name="bloodUpdate" class="select-list1 pr10 scroll2" id="bloodUpdate" size="11" multiple>
                                                    <?php
                                                    try {
                                                        include_once("data/connection.php");
                                                        global $con;
                                                        $query = "SELECT * FROM bloods LIMIT 100";
                                                        $result = $con->query($query)->fetchAll();
                                                        if ($result) {
                                                            foreach ($result as $r) {
                                                                echo ("<option class='chosenBloodUpdate select-list1-item' data-id='$r->id_blood' data-price='$r->price'>$r->analysis</option>");
                                                            }
                                                        }
                                                    } catch (PDOException $e) {
                                                        http_response_code(500);
                                                        echo $e->getMessage();
                                                    }
                                                    ?>
                                                </select>

                                                <select name="finalBloodsUpdate" class="select-list1 pr10 scroll2" id="finalBloodsUpdate" size="11" multiple>
                                                    <?php
                                                    try {
                                                        $query = "SELECT DISTINCT * FROM subBloodsView INNER JOIN bloods on subBloodsView.blood = bloods.analysis WHERE idSubAccount = $idSubAccount";
                                                        $output = "";
                                                        $result = $con->query($query)->fetchAll();
                                                        foreach ($result as $r) {
                                                            $output .= "<option class='chosenBloodUpdate select-list1-item' data-price='$r->price' data-id='$r->id_blood'>$r->analysis</option>";
                                                        }
                                                        echo $output;
                                                    } catch (PDOException $e) {
                                                        echo $e->getMessage();
                                                    }

                                                    ?>
                                                </select>

                                            </div>
                                            <div class="fc matuo">
                                                <button class="select-list1-btn s-l1-btn1 btn brbl16" id="moveToFinalBloodsUpdate">+</button>
                                                <button class="select-list1-btn s-l1-btn2 btn brbr16" id="moveFromFinalBloodsUpdate">
                                                    -</button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>




                </div>




                <div class="accordion1 js-accordion3 mr40">
                    <div class="accordion1__item js-accordion3-item">
                        <div class="accordion1-header js-accordion3-header bluedrop fb">
                            <span>Terapija</span>
                            <div class="price">
                                <span>Cena</span>
                                <span id='totalMedicinesPriceUpdate'><?php
                                                                        $resultMedicines = $con->query("SELECT therapyPrice FROM sub_accounts WHERE id_sub_account = $idSubAccount")->fetch();
                                                                        echo "$resultMedicines->therapyPrice";
                                                                        ?></span><span> RSD</span>
                            </div>
                        </div>
                        <div class="accordion1-body js-accordion3-body brbl16 brbr16">
                            <div class="accordion1-body__contents">
                                <div id="therapies">

                                    <?php
                                    try {
                                        include_once("data/connection.php");
                                        global $con;
                                        // $query = "SELECT * FROM accounts a INNER JOIN accounts_therapies ath ON a.id_account = ath.id_account INNER JOIN therapies t ON ath.id_therapy = t.id_therapy WHERE a.id_account = $idAccount";

                                        $query = "SELECT *, sat.id_therapy as therapyId FROM sub_accounts sa INNER JOIN sub_accounts_therapies sat ON sat.id_sub_account = sa.id_sub_account INNER JOIN therapies t ON sat.id_therapy = t.id_therapy WHERE sa.id_sub_account = $idSubAccount";
                                        $result = $con->query($query)->fetch();
                                        if ($result) {
                                            $output = "<p class='fs16 wght600 txtc gray2'>Pojedinačno dodavanje lekova</p>

                                                        <div class='mt5 container85 fdcolumn'><p class='fs20 wght700 txtc gray'>PRETRAŽI APOTEKU</p>
                                        
                                                        <div class='search-component relative fc mt5'><span class='fc'><svg id='search' xmlns='http://www.w3.org/2000/svg' width='682.667' height='682.667' viewBox='0 0 512 512' preserveAspectRatio='xMidYMid meet'><path d='M289 .6c-40.4 3-82 19.2-114 44.5-14.9 11.7-34.4 33.2-44.8 49.4-32.3 49.9-41.3 107.7-25.8 165.9 3.3 12.5 9.4 27.7 16.7 41.4 3.5 6.8 5.9 12.6 5.9 14.5 0 2.8-6 9.2-56.8 60.2-47.2 47.2-57.6 58.2-61.1 64.1-8.2 14-10.7 26.5-7.7 38.3 2 7.7 4.4 11.9 10.9 18.8 15.9 17 36.1 18.7 59.1 5.2 5.9-3.5 16.9-13.9 64.1-61.1 51-50.8 57.4-56.8 60.2-56.8 1.9 0 7.7 2.4 14.5 5.9 29 15.3 56.6 22.7 88.3 23.8 44 1.5 82.3-9.1 119-32.9 16.2-10.4 37.7-29.9 49.4-44.8 23-29.1 37.6-63.7 43.2-102.5 1.9-12.7 1.6-44.3-.5-57.4-6.2-39.3-21.6-73.5-46.4-103.1C431.1 35.5 385.4 10.1 335 2.5 324.6.9 299-.1 289 .6zm40.5 55.9c23 3.6 46.3 13.4 65.7 27.6 10.5 7.8 26.6 24.1 34.2 34.7 38.3 53.8 37.3 128.2-2.4 181-30 39.9-79.3 63.2-128.4 60.9-40.2-1.9-74.8-17-102.6-44.7-60.3-60.4-59.9-157.9 1.1-217.7 35.3-34.6 82.6-49.5 132.4-41.8zm-30.8 27.6c-46.3 5.1-86.6 34-107.1 76.9-5.5 11.4-4.8 20.5 2.2 27.6 6.2 6.2 16.9 8.2 24 4.4 6.2-3.3 7.8-5.1 13.7-15.9 7.1-13.1 12.8-20.5 21.5-28 26.7-23 61.4-28.9 95.3-16.3 9.4 3.5 17 2.5 23.4-3.1 8.8-7.7 9.8-22.2 2-30.5-4.5-4.8-19.4-10.5-35.4-13.6-10.6-2-28.7-2.7-39.6-1.5z'></path></svg></span><input class='search-input fc' type='text' id='searchMedicineUpdate' placeholder='Pretražite apoteku...'><div id='searchedMedicines' class='medicineOutputClassFront medicineOutputClassFront2 txtl scroll2'>
                                                        </div>
                                                        </div>
                                                        <p class='fs18 gray wght700'>Trenutni lekovi na ovom nalogu</p><p>Dodaj/Oduzmi lek</p>
                                                        
                                                        <div id='medicinesUpdate' class='fav-medicines scroll2 mb6'></div>
                                                        <div id='medicinesUpdate'>";
                                            // ! ovo ne znam sta je ... <span id='totalMedicinesPriceUpdate'>0</span>
                                            // foreach ($result as $r) {

                                            $queryMedicines = "SELECT *,tm.quantity as tmQuantity, m.id_medicine as medicineId FROM therapies t INNER JOIN  therapy_medicine tm ON t.id_therapy = tm.id_therapy INNER JOIN medicines m ON tm.id_medicine=m.id_medicine INNER JOIN medicine_prices mp ON m.id_medicine = mp.id_medicine WHERE t.id_therapy = $result->therapyId";
                                            $totalMedicinesPriceUpdate = 0;
                                            $resultMedicines = $con->query($queryMedicines)->fetchAll();
                                            foreach ($resultMedicines as $rm) {
                                                $singeMedicinePriceUpdate = $rm->tmQuantity * $rm->price;
                                                $output .= "<div class='singleMedicine'><label for='medicineInput' class='fl'>$rm->medicine</label>
                                                <input type='number' name='medicineInput' class='medicineQuantityUpdate' data-id='$rm->medicineId' data-price='$rm->price' value='$rm->tmQuantity' min='1'>
                                                <span class='singleMedicinePriceUpdate fc'>$singeMedicinePriceUpdate</span>
                                                <button class='deleteMedicine fc'>
                                                <svg id='deletebtn' viewBox='0 0 30 30'><path d='M13.81.25c-.13.01-.47.06-.76.1C7.2 1.13 2.31 5.37.75 11.03c-.4 1.49-.52 2.31-.52 3.95-.01 1.64.08 2.32.46 3.79.63 2.45 1.87 4.65 3.63 6.48 2.31 2.41 5.13 3.87 8.52 4.42 1 .17 3.27.16 4.29-.01 2.22-.36 4.26-1.13 6-2.29 3.53-2.35 5.85-5.99 6.5-10.24.16-1.05.16-3.18 0-4.23-.52-3.34-2-6.22-4.41-8.55-2.19-2.11-4.78-3.41-7.9-3.94-.74-.14-2.91-.24-3.51-.16zm6.2 9.34c.35.18.55.51.55.93-.01.18-.04.39-.08.48s-.96 1.02-2.04 2.08l-1.96 1.94 2.03 2c1.78 1.75 2.04 2.04 2.07 2.25.07.38-.03.69-.28.98-.29.32-.63.43-1.02.32-.24-.07-.55-.34-2.29-2.07l-2-2-1.96 2c-1.73 1.76-2 2-2.24 2.07-.75.19-1.45-.47-1.31-1.23.05-.28.21-.46 2.74-3.01l1.29-1.31-1.96-1.96c-2.11-2.13-2.11-2.12-2-2.71.06-.32.46-.72.78-.78.59-.11.58-.11 2.71 2L15 13.53l1.97-1.96c1.08-1.08 2.04-2 2.13-2.03.26-.12.64-.09.91.05z'/></svg></button>
                                                </div>";
                                                $totalMedicinesPriceUpdate += $singeMedicinePriceUpdate;
                                            }
                                            // }
                                            $query = "SELECT totalPrice,comment from sub_accounts WHERE id_sub_account = $idSubAccount";
                                            $resultQuery = $con->query($query)->fetch();
                                            $totalAccountPriceUpdate = $resultQuery->totalPrice;
                                            $output .= "</div></div></div></div></div></div>
                                            
                                                <p class='mt10 wght600 txtc mb5'>Komentar</p>

                                                <textarea name='commentUpdate' id='commentUpdate' class='commentTherpay fc mb15 scroll2' cols='30' rows='10' placeholder='Unesite vaš komentar u podnalog pacijenta... (Zaposleni)'>$resultQuery->comment</textarea>
                                                
                                                <div class='fdcolumn totalPriceAccountClass'>
                                                <span class='updatePriceResult fc' id='totalAccountPriceUpdate'>Ukupna cena podnaloga: $totalAccountPriceUpdate RSD</span>

                                                <button class='ukupnocena fc' data-idAccount='$idAccount'
                                                data-idSubAccount = '$idSubAccount' id='updateButton1'>Izmeni</button>                      
                                                
                                                </div></div>";

                                            echo $output;

                                    ?>
                                    <?php } else {
                                            $output = "
                                            
                                                        <p class='fs16 wght600 txtc gray2'>Pojedinačno dodavanje lekova</p>

                                                        <div class='mt5 container85 fdcolumn'><p class='fs20 wght700 txtc gray'>PRETRAŽI APOTEKU</p>

                                                        <div class='search-component relative fc mt5'><span class='fc'><svg id='search' xmlns='http://www.w3.org/2000/svg' width='682.667' height='682.667' viewBox='0 0 512 512' preserveAspectRatio='xMidYMid meet'><path d='M289 .6c-40.4 3-82 19.2-114 44.5-14.9 11.7-34.4 33.2-44.8 49.4-32.3 49.9-41.3 107.7-25.8 165.9 3.3 12.5 9.4 27.7 16.7 41.4 3.5 6.8 5.9 12.6 5.9 14.5 0 2.8-6 9.2-56.8 60.2-47.2 47.2-57.6 58.2-61.1 64.1-8.2 14-10.7 26.5-7.7 38.3 2 7.7 4.4 11.9 10.9 18.8 15.9 17 36.1 18.7 59.1 5.2 5.9-3.5 16.9-13.9 64.1-61.1 51-50.8 57.4-56.8 60.2-56.8 1.9 0 7.7 2.4 14.5 5.9 29 15.3 56.6 22.7 88.3 23.8 44 1.5 82.3-9.1 119-32.9 16.2-10.4 37.7-29.9 49.4-44.8 23-29.1 37.6-63.7 43.2-102.5 1.9-12.7 1.6-44.3-.5-57.4-6.2-39.3-21.6-73.5-46.4-103.1C431.1 35.5 385.4 10.1 335 2.5 324.6.9 299-.1 289 .6zm40.5 55.9c23 3.6 46.3 13.4 65.7 27.6 10.5 7.8 26.6 24.1 34.2 34.7 38.3 53.8 37.3 128.2-2.4 181-30 39.9-79.3 63.2-128.4 60.9-40.2-1.9-74.8-17-102.6-44.7-60.3-60.4-59.9-157.9 1.1-217.7 35.3-34.6 82.6-49.5 132.4-41.8zm-30.8 27.6c-46.3 5.1-86.6 34-107.1 76.9-5.5 11.4-4.8 20.5 2.2 27.6 6.2 6.2 16.9 8.2 24 4.4 6.2-3.3 7.8-5.1 13.7-15.9 7.1-13.1 12.8-20.5 21.5-28 26.7-23 61.4-28.9 95.3-16.3 9.4 3.5 17 2.5 23.4-3.1 8.8-7.7 9.8-22.2 2-30.5-4.5-4.8-19.4-10.5-35.4-13.6-10.6-2-28.7-2.7-39.6-1.5z'></path></svg></span><input class='search-input fc' type='text' id='searchMedicineUpdate' placeholder='Pretražite apoteku...'>
                                                        <div id='searchedMedicines' class='medicineOutputClassFront medicineOutputClassFront2 scroll2'></div>
                                                        </div>
                                                        <div id='medicinesUpdate' class='fav-medicines scroll2 mb10'></div>
                                                        ";
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
                                            $output .= "</div></div></div></div></div></div>
                                            
                                                
                                                
                                                
                                                
                                            <p class='mt10 wght600 txtc mb5'>Komentar</p>
                                                <textarea class='commentTherpay fc mb15 scroll2' name='commentUpdate' id='commentUpdate' cols='30' rows='10' placeholder='Unesite vaš komentar u podnalog pacijenta... (Zaposleni)'>$resultQuery->comment</textarea>
                                                
                                                <div class='fdcolumn totalPriceAccountClass'>

                                                <span class='updatePriceResult fc' id='totalAccountPriceUpdate'>Ukupna cena podnaloga:
                                                 $totalAccountPriceUpdate RSD</span>
                                                 
                                                <button class='ukupnocena fc' data-idAccount='$idAccount'
                                                data-idSubAccount='$idSubAccount' id='updateButton1'>Izmeni</button>
                                                
                                                </div>";
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
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php

}
