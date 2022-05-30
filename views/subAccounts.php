<?php
if (isset($_GET['subAccount']) and !empty($_GET["subAccount"])) {
    $deletesvg = file_get_contents("./assets/img/deletebtn.svg");
    $addplus = file_get_contents("./assets/img/addplus.svg");
    $send = file_get_contents("./assets/img/send.svg");
    include_once("data/connection.php");
    global $con;
    $subAccount = $_GET["subAccount"];
    $rowCount = $con->query("SELECT id_sub_account FROM sub_accounts where id_sub_account = $subAccount")->rowCount();
    if ($rowCount == 0) {
        http_response_code(404);
    } else {
        $a = "";

?>
        <div class="modal modal-t">
            <div class="modal-header" id="modal-header">
                <p>NOVI NALOG ZA ISTOG PACIJENTA</p>
                <?php
                $url = $_SERVER["REQUEST_URI"];
                $arr = explode("/", $url);
                $br = count($arr) - 1;
                $lastOne = $arr[$br];
                $filename = explode("?", $arr[$br]);
                $filename = $filename[0];
                if ($filename == 'nalog.php') {
                ?>
                    <button class="close-button crudbtns" id='exitAccount'><?php echo $deletesvg ?></button>
                <?php
                } elseif ($filename == "adminpanel.php") {
                ?>
                    <button class="close-button crudbtns" id='exitAccountAp'><?php echo $deletesvg ?></button>
                <?php
                }
                ?>
            </div>
            <div class="modal-body scroll dupmodal" id="modal-body">
                <div class="modalheight scroll">
                    <div class="duplicateViewGrid-head">
                        <div class="no-vah wght500">#</div>
                        <div class="namesur-vah wght500">Ime i Prezime</div>
                        <?php
                        if (in_array($_SESSION['id_role'], [3, 4])) {
                        ?>
                            <div class="doctor-vah wght500">Doktor</div>
                        <?php
                        }
                        ?>
                        <div class="appointment-vah wght500">Pregled</div>
                        <div class="therapy-vah wght500">Terapija</div>
                        <div class="blood-vah wght500">Analize</div>
                        <div class="fullprice-vah wght500">Ukupno</div>
                    </div>
                    <?php
                    try {
                        $output = "";
                        $queryForSubAccount = "SELECT a.id_account as accountId, p.name as patientName, p.surname as patientSurname FROM sub_accounts sa INNER JOIN accounts a ON a.id_account = sa.id_account INNER JOIN patients p ON p.id_patient = a.id_patient WHERE id_sub_account = $subAccount";
                        $resultForSubAccount = $con->query($queryForSubAccount)->fetch();
                        $idAccount = $resultForSubAccount->accountId;
                        // TODO FALI CHECKED 
                        $output .= "  <div class='duplicateViewGrid-body'><div class='no-vab head-tcss fc poppins wght600'><span id='accountId'>$resultForSubAccount->accountId</span></div><div class='namesur-vab head-tcss fl'>$resultForSubAccount->patientName $resultForSubAccount->patientSurname</div>";
                        if (in_array($_SESSION['id_role'], [3, 4])) {
                            $output .= "<div class='doctor-vab head-tcss custom-select fc'><select class='' id='selectDoctors'>";
                            $queryForDoctor = "SELECT a.id_employee as employeeId, name, surname FROM employees e INNER JOIN accounts a ON e.id_employee = a.id_employee WHERE a.id_account = $idAccount";
                            $res = $con->query($queryForDoctor)->fetch();
                            $resultForDoctors = $con->query("SELECT * FROM employees WHERE id_role=1")->fetchAll();

                            $output .= "<option value='$res->employeeId'>$res->name $res->surname</option>";
                            foreach ($resultForDoctors as $rd) {
                                if ($rd->id_employee != $res->employeeId) {
                                    $output .= "<option value='$rd->id_employee'>$rd->name $rd->surname</option>";
                                }
                            }
                            $url = $_SERVER["REQUEST_URI"];
                            $arr = explode("/", $url);
                            $br = count($arr) - 1;
                            $lastOne = $arr[$br];
                            $filename = explode("?", $arr[$br]);
                            $filename = $filename[0];

                            if ($filename == "nalog.php") {
                                $a = "finalButton1";
                            } elseif ($filename == "adminpanel.php") {
                                $a = "finalButtonAp";
                            }
                            $output .= "</option></select></div>";
                        }
                        $output .= "<div class='appointment-vab head-tcss fc'><button data-id='$subAccount' class='addAppointmentSubAccount crudbtns fc'>$addplus</button><span class='fc txtc' id='totalPriceAppointmentsSubAccount'>0</span></div>";
                        $output .= "<div class='therapy-vab head-tcss fc'><button data-id='$subAccount' class='addTherapySubAccount crudbtns fc'>$addplus</button><span id='totalPriceTherapySubAccount'>0</span></div>";
                        $output .= "<div class='blood-vab head-tcss fc'><button data-id='$subAccount' class='addBloodSubAccount crudbtns fc'>$addplus</button><span id='totalPriceBloodsSubAccount'>0</span></div>";
                        $output .= "<div class='fullprice-vab head-tcss fc'><span id='totalPriceSubAccount'>0</span></div>";
                        $output .= "</div>";


                        $output .= "<div id='showAppointments' class='modal-s'></div><div id='showTherapy'></div><div id='showBloods' class='modal-s'></div></div>
                
                    </div><div class='send-btn fc'><button class='crudbtns fc' id='" . $a . "'>$send <p class='wght700 fs15'>POŠALJI</p></button></div>";
                        echo $output;
                    } catch (PDOException $e) {
                        echo $e->getMessage();
                        http_response_code(500);
                    }
                    ?>

                    <div id='showSomething'>
                    </div>
                </div>


        <?php
    }
}
