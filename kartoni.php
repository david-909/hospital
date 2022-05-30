<?php
include_once("logic/functions.php");
startSession();
if (isset($_GET["id"]) and !empty($_GET["id"])) {
    $id = $_GET["id"];
}
if (isset($_SESSION["id_employee"])) {
    include_once("views/head.php");
    include_once("views/nav.php");
    include_once("views/sidenav.php");
    include_once("views/acc-menu.php");
    $searchico = file_get_contents("assets/img/search.svg");
    $deletesvg = file_get_contents("assets/img/deletebtn.svg");
    $editsvg = file_get_contents("assets/img/editbtn.svg");
?>
    <div class="behind-nav"></div>

    <div class="modal-t">




        <div class="rcard-white mt20 mb20">
            <div class="svg-box fc">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" preserveAspectRatio="xMidYMid meet">
                    <path d="M233 1c-49.4 4.8-95 23-135 53.8C78.5 69.8 53.2 98 38.6 121c-6.3 9.9-18.6 35.1-23 47.1-20.4 55.6-20.4 120.2-.2 175.4C20 355.9 32.2 381 38.6 391c5.9 9.2 16.6 23.8 22.2 30.3l4.1 4.7 1.6-5.7c7.1-25.7 18.7-49.3 34.4-69.8 6.2-8.2 23.1-25.5 31.6-32.4 14.5-11.8 32.3-22.4 50-29.6l9.2-3.9c.2-.1-2.6-2.3-6.1-4.9-59.7-43.9-65.3-130.4-11.7-181.9 9.8-9.5 17.8-15 30.6-21.3 16.3-8 32.4-11.6 51.5-11.6s35.2 3.6 51.5 11.6c12.8 6.3 20.8 11.8 30.6 21.3 53.2 51.1 48.3 136.5-10.4 180.8l-7 5.4c-.4.4 2.5 1.9 6.5 3.5 24.9 9.6 48.4 25.2 68 45.1 24.5 24.8 40.8 53.4 50.3 87.8l1.7 5.9 2.4-2.9c5.6-6.7 19.4-25.4 23.8-32.4 6.4-10 18.6-35.1 23.2-47.5 20.2-55.2 20.2-119.8-.2-175.4-4.4-12-16.7-37.2-23-47.1C453 88.9 423.1 59 391 38.6c-9.9-6.3-35.1-18.6-47.1-23-13.5-4.9-34.1-10.2-49.4-12.6C279.8.7 247-.4 233 1zm18 93.8c-47 2.6-84 41.6-84 88.5 0 25.7 9.4 47.4 28.3 65.3 34 32.4 87.4 32.4 121.4 0 18.9-17.9 28.3-39.6 28.3-65.3 0-50.8-43.2-91.4-94-88.5zm-7 209.9c-23.7 2.3-41.8 7.3-61.2 16.8-43.3 21.4-75.8 61.4-87.2 107.5-1.3 5.2-2.7 11.3-3 13.5l-1.2 6.6c-.4 2.3.6 3.3 8.8 9.6 12.3 9.3 24 16.8 36.8 23.6 88.7 46.8 194 37.7 274.8-23.6 10.6-8 9.9-5.3 6.1-24.1-11.4-55.8-54.5-103.6-110-122-20.3-6.8-44.9-9.8-63.9-7.9z" />
                </svg>
            </div>
            <form class="fdcolumn2" id="searchPatient">
                <label class="label-h1" for="searchNameSurnameCardboard">PRETRAŽI PACIJENTA</label>
                <div class="search-component fc">
                    <span class="fc"><?php echo $searchico ?></span>
                    <input class="search-input fc" type="text" id="searchNameSurnameCardboard" list="lista" name="searchNameSurnameCardboard" placeholder="Ime, prezime, jmbg">
                </div>

                <input type="hidden" id="id_employee" value='<?php echo $_SESSION["id_employee"]; ?>'>
                <div class="search-output" id="searchOutputCardboard"></div>
            </form>
        </div>

        <div class="rcard-white mb20">

            <div class="svg-box fc">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 730.7 736">
                    <circle cx="366.7" cy="367.5" r="357" fill="#00eb89" />
                    <path d="M183.79 134.99c-9.87 2.61-17.42 6.99-24.87 14.44-7.55 7.55-11.83 15-14.44 25.25-1.86 6.99-1.86 10.9-1.86 167.12l1.86 167.12c1.02 4.01 3.17 9.78 4.84 12.95 4.38 8.2 14.53 18.07 22.92 22.26 12.86 6.43 12.11 6.43 89.34 6.15 68.1-.28 69.12-.28 71.64-2.24 4.94-3.63 6.43-6.61 6.43-12.48s-1.49-8.85-6.43-12.48c-2.52-1.96-3.63-1.96-72.57-2.42-67.26-.47-70.24-.56-73.78-2.33-4.66-2.33-9.78-7.45-12.11-12.11-1.86-3.63-1.86-6.52-1.86-164.42l1.86-164.42c2.33-4.66 7.45-9.78 12.11-12.11 3.63-1.86 6.24-1.86 124.83-1.86h121.1l3.73 2.05c4.84 2.61 10.15 8.2 12.3 12.86 1.58 3.45 1.68 8.76 2.14 69.87.47 61.2.56 66.42 2.14 68.75 7.27 11.09 25.25 7.17 27.2-6.06.28-2.24.47-32.98.28-68.28-.37-70.8-.09-67.26-6.33-79.74-6.15-12.02-18.54-22.73-31.49-27.11l-6.24-2.14-122.97-.19c-118.68-.2-123.25-.11-129.77 1.57zm28.41 78.62c-4.56 1.68-5.96 2.98-8.29 7.45-3.45 6.8-1.21 14.25 5.78 18.91l3.07 2.14h49.09 49.09l3.07-2.14c4.47-2.98 6.71-6.43 7.17-11.09.65-5.4-2.14-10.99-6.89-13.88l-3.54-2.24-47.51-.19c-38.09-.17-48.15.01-51.04 1.04zm-2.51 80.86c-4.47 3.07-6.71 6.52-7.17 11.18-.65 5.4 2.14 10.99 6.89 13.88l3.54 2.24 95.49.28 99.49-.28c5.31-.75 10.99-5.78 12.3-10.81 1.77-6.43-1.58-14.07-7.45-17.14-2.33-1.21-16.12-1.4-101.35-1.4h-98.65l-3.09 2.05zm259.9 58.87c-56.55 10.06-99.21 54.03-107.22 110.39-1.4 9.87-.65 33.63 1.4 42.95 5.4 24.69 16.68 45.83 34.19 63.81 18.72 19.28 40.15 31.11 66.79 36.98 13.51 2.98 37.91 2.98 51.33 0 26.55-5.96 46.76-16.95 65.4-35.49 18.54-18.63 29.53-38.85 35.49-65.4 2.98-13.41 2.98-37.82 0-51.33-4.01-18.45-11.55-35.49-22.26-50.21-6.61-9.22-22.73-24.97-31.77-30.93-13.79-9.22-29.9-16.12-46.3-19.75-9.04-2.04-37.73-2.69-47.05-1.02zm38.48 29.91c34.75 6.06 64.28 31.3 76.02 65.12 10.9 31.02 5.31 66.05-14.63 92.41-31.39 41.55-89.99 51.7-133.59 23.01-22.45-14.72-38.57-39.22-43.13-65.21-9.41-53.84 25.52-104.61 79.28-115.23 8.66-1.78 26.36-1.78 36.05-.1zm-25.43 30.46c-1.77 1.21-4.19 4.01-5.31 6.33-2.05 3.91-2.14 5.22-2.14 24.87v20.78h-20.77c-19.66 0-20.96.09-24.87 2.05-6.15 3.17-8.66 7.36-8.2 13.88.47 6.06 2.98 10.15 7.64 12.39 2.61 1.21 6.8 1.49 24.59 1.49h21.52l.28 22.08c.28 21.43.37 22.26 2.52 25.71 5.68 9.22 17.98 9.41 24.69.28 1.86-2.33 1.96-4.19 2.42-25.06l.47-22.54 22.52-.47c20.87-.47 22.73-.56 25.06-2.42 9.13-6.71 8.94-19-.28-24.69-3.45-2.14-4.29-2.24-25.62-2.52l-22.17-.28v-21.52c0-23.75-.47-25.99-5.87-29.81-4.37-3.16-12.29-3.34-16.48-.55zm-271.56-40.8c-6.24 2.7-9.78 9.87-8.48 16.86.84 4.38 5.5 9.5 9.69 10.71 2.33.75 22.36.93 61.39.75 57.11-.28 57.94-.28 60.55-2.24 9.13-6.8 9.13-18.17 0-24.97-2.61-1.96-3.45-1.96-61.48-2.14-47.51-.18-59.43 0-61.67 1.03z" fill="#fff" />
                </svg>
            </div>

            <label class="label-h1 fc" for="addPatient">OTVORI KARTON</label>




            <form class="open-cardboard" id="addPatient">

                <div class="open-cardboard-flex container100 fconly">
                    <div class="container100">
                        <div class="cardboard-flex flex">
                            <div class="container100 fdcolumn mauto mr10">
                                <label class="label-classic" for="name">Ime</label>
                                <input class="input-sec" type="text" id="name" name="name"><span class="error-msg" id="nameError"></span>
                            </div>
                            <div class="container100 fdcolumn mauto mr10">
                                <label class="label-classic" for="parentName">Ime roditelja</label>
                                <input class="input-sec" type="text" id="parentName" name="parentName"><span class="error-msg" id="parentNameError"></span>
                            </div>
                            <div class="container100 fdcolumn mauto mr10">
                                <label class="label-classic" for="surname">Prezime</label>
                                <input class="input-sec" type="text" id="surname" name="surname"><span class="error-msg" id="surnameError"></span>
                            </div>
                        </div>

                        <div class="cardboard-flex flex">
                            <div class="container100 fdcolumn mauto mr10">
                                <label class="label-classic" for="dateOfBirth">Datum rodjenja</label>
                                <input class="input-sec" type="date" class="dateofbirth" id="dateOfBirth" name="dateOfBirth"><span class='error-msg' id='dateError'></span>
                            </div>
                            <div class="container100 fdcolumn mauto mr10">
                                <label class="label-classic" for="jmbg">JMBG</label>
                                <input class="input-sec" type="text" id="jmbg" name="jmbg"><span class="error-msg" id="jmbgError"></span>
                            </div>
                            <div class="container100 fdcolumn mauto mr10">
                                <label class="label-classic" for="identificationNumber">Broj licne karte</label>
                                <input class="input-sec" type="text" id="identificationNumber" name="identificationNumber"><span class="error-msg" id="identificationNumberError"></span>
                            </div>
                        </div>

                        <div class="cardboard-flex flex">
                            <div class="container100 fdcolumn mauto mr10">
                                <label class="label-classic" for="email">Email</label>
                                <input class="input-sec" type="text" id="email" name="email"><span class="error-msg" id="emailError"></span>
                            </div>
                            <div class="container100 fdcolumn mauto mr10">
                                <label class="label-classic" for="phone">Telefon</label>
                                <input class="input-sec" type="text" id="phone" name="phone"><span class="error-msg" id="phoneError"></span>
                            </div>
                            <div class="container100 fdcolumn mauto mr10">
                                <label class="label-classic" for="address">Adresa</label>
                                <input class="input-sec" type="text" id="address" name="address"><span class="error-msg" id="addressError"></span>
                            </div>
                        </div>
                    </div>

                    <div class="container100 comment fdcolumn2">
                        <label class="label-classic" for="commentPatient">Komentar</label>
                        <textarea class="comment-area scroll" id="commentPatient" name="commentPatient" placeholder="Unesite vaš komentar u karton pacijenta... (Zaposleni)"></textarea>
                    </div>

                </div>

            </form>
            <div class="button-pacijent fc mt20">
                <input type="button" class="btn-22 btn wght600 fs20 poppins" id="addPatientButton" value="DODAJ PACIJENTA">
            </div>


        </div>





    </div>

    <?php
    if (isset($_GET["id"]) and !empty($_GET["id"])) {
        try {
            //ID = 4124124 Id= fafasfasfasf
            include_once("data/connection.php");
            global $con;
            $id = $_GET["id"];
            $query1 = "SELECT * FROM patients WHERE id_patient = +$id";
            $rowCount = $con->query($query1)->rowCount();
        } catch (PDOException $e) {
    ?>
            <script>
                window.location.replace("404");
            </script>
        <?php
            echo $e->getMessage();
        }
        ?>
        <div class='overlayblack'>
            <div class="modal modal-s2" id="modal1">
                <div class="modal-header fb" id="modal1-header">
                    <div class="fb">
                    <p>
                        <?php
                        try {
                            include_once("data/connection.php");
                            global $con;
                            $id = +$id;
                            $query = "SELECT name,surname,jmbg FROM patients WHERE id_patient = $id";
                            $result = $con->query($query)->fetch();
                            if ($result) {

                                echo "$result->name $result->surname | $result->jmbg";
                            }
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }
                        ?>
                    </p>


                    <button type="button" class="showUpdatePatient fc" data-id=<?php echo $id ?>><?php echo $editsvg ?></button>
                    </div>
                    

                    <button class="close-button crudbtns" id='exitAccount2'><?php echo $deletesvg ?></button>
                </div>

                <div class="modal-body scroll">

                    <div class="updatePatientDivClass" id='updatePatientDiv'></div>
                    <!-- UPIT I SELECT -->
                    <?php
                    $output = "";
                    try {
                        include_once("data/connection.php");
                        include_once("logic/functions.php");
                        global $con;
                        $query = "SELECT * FROM accounts WHERE id_patient = $id";
                        $resultAccount = $con->query($query);
                        $rowCountAccount = $resultAccount->rowCount();
                        $resultAccount = $resultAccount->fetchAll();

                        if ($rowCountAccount == 0) {
                            $output .= "<p class='txtc wght500 gray mt10 fs18'>Prazan karton...</p>";
                        } else {
                    ?>
                            <div class="cardboardGrid-head">
                                <div class="no-vah wght500">#</div>
                                <!-- <div class="namesur wght500">Ime i Prezime</div> -->
                                <div class="doctor-vah wght500">Doktor</div>
                                <div class="appointment-vah wght500">Pregled</div>
                                <div class="therapy-vah wght500">Terapija</div>
                                <div class="blood-vah wght500">Analize</div>
                                <div class="dateadded-vah wght500">Datum</div>
                                <div class="fullprice-vah wght500">Ukupno</div>
                                <!-- <div class="options wght500">Opcije</div>
                                <div class="checked wght500">Status</div> -->
                            </div>
                    <?php

                            foreach ($resultAccount as $ra) {
                                $output .= "<div class='cardboardGrid-body'><div class='no-vab fc'>$ra->id_account</div><div class='doctor-vab fl head-tcss tcss'>";
                                $queryDoctor = "SELECT DISTINCT name,surname, e.id_employee as employeeId FROM accounts a INNER JOIN employees e ON a.id_employee = e.id_employee WHERE id_patient = $id";
                                $resultDoctor = $con->query($queryDoctor)->fetchAll();
                                foreach ($resultDoctor as $rd) {
                                    if ($ra->id_employee == $rd->employeeId)
                                        $output .= "$rd->name $rd->surname";
                                }
                                $output .= "</div>";
                                //APPOINTMENT
                                $queryAppointment = "SELECT *,aa.id_appointment as IDA,appointment FROM accounts a INNER JOIN accounts_appointments aa ON a.id_account=aa.id_account INNER JOIN appointments ap ON ap.id_appointment = aa.id_appointment WHERE id_patient = $id AND aa.id_account = $ra->id_account";
                                $output .= "<div class='appointment-vab head-tcss custom-select fc'><select name='appointment'>";
                                $rowCountAppointment = $con->query($queryAppointment)->rowCount(); //2  
                                $resultAppointment = $con->query($queryAppointment)->fetchAll();
                                if ($rowCountAppointment > 0) {
                                    foreach ($resultAppointment as $rap) {
                                        $output .= "<option value='$rap->IDA'>$rap->appointment</option>";
                                    }
                                } else {
                                    $output .= "<option>Nema pregleda</option>";
                                }
                                $output .= "</select></div>";
                                //THERAPY
                                $queryTherapy = "SELECT * FROM accounts a INNER JOIN accounts_therapies ath ON ath.id_account = a.id_account INNER JOIN therapies t ON t.id_therapy = ath.id_therapy WHERE a.id_account = $ra->id_account AND a.id_patient = $id";
                                $output .= "<div class='therapy-vab head-tcss tcss fl'>";
                                $rowCountTherapy = $con->query($queryTherapy)->rowCount();
                                $resultTherapy = $con->query($queryTherapy)->fetch();
                                if ($rowCountTherapy > 0) {
                                    $output .= "<span class='therapySpan' id='$resultTherapy->id_therapy'>$resultTherapy->therapy</span>";
                                } else {
                                    $output .= "<span class='therapySpanX'>Nema Terapije</span>";
                                }
                                $output .= "</div>";
                                //BLOODS
                                $queryBloods = "SELECT ab.id_blood as bloodId, analysis FROM bloods b INNER JOIN accounts_bloods ab ON ab.id_blood = b.id_blood INNER JOIN accounts a ON a.id_account = ab.id_account WHERE id_patient = $id AND ab.id_account = $ra->id_account";
                                $output .= "<div class='blood-vab head-tcss custom-select fc'><select name='blood'>";
                                $rowCountBloods = $con->query($queryBloods)->rowCount(); //2  
                                $resultBloods = $con->query($queryBloods)->fetchAll();
                                if ($rowCountBloods > 0) {
                                    foreach ($resultBloods as $rb) {
                                        $output .= "<option value='$rb->bloodId'>$rb->analysis</option>";
                                    }
                                } else {
                                    $output .= "<option>Nema pregleda</option>";
                                }
                                $output .= "</select></div>";
                                // DATE
                                $queryDate = "SELECT date FROM accounts WHERE id_patient = $id AND id_account = $ra->id_account";
                                $resultDate = $con->query($queryDate)->fetch();
                                $date2 = date("d.m.Y. H:i:s", strtotime($resultDate->date));
                                $output .= "<div class='dateadded-vab head-tcss tcss fc'>$date2</div>";
                                // TOTAL PRICE
                                $queryTotal = "SELECT totalPrice FROM accounts WHERE id_account = $ra->id_account AND id_patient=$id";
                                $resultTotalPrice = $con->query($queryTotal)->fetch();
                                $output .= "<div class='fullprice-vab head-tcss fc wght500'>$resultTotalPrice->totalPrice RSD</div>";
                                $output .= "</div>";
                            }
                        }
                        echo $output;
                    } catch (PDOException $e) {
                        echo $e->getMessage();
                    }
                    ?>

                </div>
            </div>
        </div>
        <!-- QUERY I TU ISPIS IDE ID PACIENT -->


    <?php
    }
    ?>
<?php
    include_once("views/footer.php");
    include_once("views/scripts.php");
} else {
    http_response_code(404);
}
?>