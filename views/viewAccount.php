<?php
/* if (isset($_GET["idAccountDeleted"]) and !empty($_GET["idAccountDeleted"])) {
    echo "AHJFWOAKFIOWAFHJ";
} */
if (isset($_GET["viewAccount"]) and !empty($_GET["viewAccount"]) or isset($_GET["idAccountDeleted"]) and !empty($_GET["idAccountDeleted"])) {
    if (isset($_GET["viewAccount"]) and !empty($_GET["viewAccount"])) {
        $viewId = $_GET["viewAccount"];
    }
    if (isset($_GET["idAccountDeleted"]) and !empty($_GET["idAccountDeleted"])) {
        $viewId = (int)$_GET["idAccountDeleted"];
    }
    #var_dump($viewId);

?>
    <?php

    $arrowsvg = file_get_contents("./assets/img/arrow.svg");
    $deletesvg = file_get_contents("./assets/img/deletebtn.svg");
    $viewsvg = file_get_contents("./assets/img/viewbtn.svg");
    $editsvg = file_get_contents("./assets/img/editbtn.svg");
    $printsvg = file_get_contents("./assets/img/printbtn.svg");
    $duplicatesvg = file_get_contents("./assets/img/duplicatebtn.svg");
    $searchico = file_get_contents("./assets/img/search.svg");
    $reloadsvg = file_get_contents("./assets/img/reloadbtn.svg");
    ?>
    <div class="modal modal-w">
        <div class="modal-header" id="modal-header">
            <p>PREGLED NALOGA</p>
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
            if ($filename == 'adminpanel.php' and $_GET['view'] == 4) {
            ?>
                <button class='close-button' id='exitAccountAdminpanel'><?php echo $deletesvg ?></button>
            <?php
            }
            if ($filename == 'adminpanel.php' and $_GET['view'] == 5) {
            ?>
                <button class='close-button' id='exitDelAccountAdminpanel'><?php echo $deletesvg ?></button>
            <?php
            }
            ?>
        </div>
        <div class="modal-body scroll" id="modal-body">
            <div class="viewAccountGrid-head conm2">
                <div class="no-vah wght500">#</div>
                <div class="namesur-vah wght500">Ime i Prezime</div>
                <div class="doctor-vah wght500">Doktor</div>
                <div class="appointment-vah wght500">Pregled</div>
                <div class="therapy-vah wght500">Terapija</div>
                <div class="blood-vah wght500">Analize</div>
                <div class="fullprice-vah wght500">Ukupno</div>
                <div class="options-vah wght500">Opcije</div>

                <div class="checked-vah wght500">Čekirao</div>
                <div class="dateadded-vah wght500">Datum</div>
            </div>
            <?php
            include_once("data/connection.php");
            global $con;
            $query = "SELECT isShown,a.date as datee, totalPrice as total, a.id_account as accountId, p.name as patientName, p.surname as patientSurname, e.name as doctorName, e.surname as doctorSurname FROM accounts a INNER JOIN patients p ON p.id_patient = a.id_patient INNER JOIN employees e ON e.id_employee = a.id_employee WHERE isShown = 1 AND a.id_account=$viewId";
            $result = $con->query($query)->fetchAll();
            #var_dump($result);
            $outputmodal = "";
            foreach ($result as $r) {
                $outputmodal .= "<div class='viewAccountGrid-body'>";
                $queryForGreen = "SELECT checked FROM checked WHERE id_account = $r->accountId";
                $resultForGreen = $con->query($queryForGreen)->fetchColumn();
                #var_dump($resultForGreen);
                if ($resultForGreen) {
                    $outputmodal .= "<div class='no-vab head-tcss fc poppins wght600 green-check'>";
                } else {
                    $outputmodal .= "<div class='no-vab head-tcss fc poppins wght600 orange-check'>";
                }
                $outputmodal .= "$r->accountId</div><div class='namesur-vab fl head-tcss'>$r->patientName $r->patientSurname</div><div class='doctor-vab head-tcss fl'>$r->doctorName $r->doctorSurname</div>
                
                <div class='blood-vab head-tcss custom-select fc'><select class=''>";

                /*  OVO IDE GORE */

                $queryBloods = "SELECT analysis,b.id_blood AS bloodId FROM accounts a INNER JOIN accounts_bloods ab ON ab.id_account = a.id_account INNER JOIN bloods b ON b.id_blood = ab.id_blood WHERE a.id_account = $r->accountId";
                $resultBloods = $con->query($queryBloods);
                $bloodsRowCount = $resultBloods->rowCount();
                if ($bloodsRowCount > 0) {
                    $resultBloods = $resultBloods->fetchAll();
                    foreach ($resultBloods as $rb) {
                        $outputmodal .= "<option class='' value='$rb->bloodId'>$rb->analysis</option>";
                    }
                } else {
                    $outputmodal .= "<option>Nema krvi</option>";
                }
                $outputmodal .= "</select></div><div class='therapy-vab head-tcss fc'><ul>";
                $queryTherapies = "SELECT therapy,a.id_account as accountId FROM accounts a INNER JOIN accounts_therapies ath ON ath.id_account = a.id_account INNER JOIN therapies t ON t.id_therapy = ath.id_therapy WHERE a.id_account = $r->accountId";
                $resultTherapies = $con->query($queryTherapies);
                $therapiesRowCount = $resultTherapies->rowCount();
                $resultTherapies = $resultTherapies->fetch();
                /* var_dump($resultTherapies);
                var_dump($therapiesRowCount);  */
                if ($therapiesRowCount > 0) {
                    $outputmodal .= "<li><button class='btn-therapy btnTherapyAccount fc' data-idAccount='$resultTherapies->accountId'>$resultTherapies->therapy</button></li>";
                } else {
                    $outputmodal .= "<li>Nema terapije</li>";
                }
                $outputmodal .= "</ul></div><div class='appointment-vab head-tcss custom-select fc'><select class='' id='appointmentsSelectModal'>";
                // APPOINTMENT
                $queryAppointment = "SELECT aa.id_appointment as IDA, appointment FROM accounts_appointments aa INNER JOIN appointments a ON aa.id_appointment = a.id_appointment WHERE id_account = $r->accountId";
                $rowCountAppointment = $con->query($queryAppointment)->rowCount();
                $resultAppointment = $con->query($queryAppointment)->fetchAll();
                if ($rowCountAppointment > 0) {
                    foreach ($resultAppointment as $ra) {
                        $outputmodal .= "<option class='' value='$ra->IDA'>$ra->appointment</option>";
                    }
                } else {
                    $outputmodal .= "<option>Nema pregleda</option>";
                }
                $outputmodal .= "</select></div>";
                // DATE
                $queryDate = "SELECT date FROM accounts WHERE id_account = $r->accountId";
                $resultDate = $con->query($queryDate)->fetchAll();
                foreach ($resultDate as $rd) {
                    $date2 = date("d.m.Y. H:i:s", strtotime($rd->date));
                    $outputmodal .= "<div class='dateadded-vab head-tcss fc'>$date2</div>";
                }
                // TOTAL
                $queryTotal = "SELECT totalPrice FROM accounts WHERE id_account = $r->accountId";
                $resultTotalPrice = $con->query($queryTotal)->fetch();
                $outputmodal .= "<div class='fullprice-vab head-tcss fc'>$resultTotalPrice->totalPrice RSD</div>";
                // BUTTONI I CHECKED
                $outputmodal .= "<div class='options-vab head-tcss fc'>";
                $outputmodal .= "<button class='printButtonAcc crudbtns fc' data-id='$r->accountId'>$printsvg</button>";
                if (isset($_GET["viewAccount"]) and !empty($_GET["viewAccount"])) {
                    $outputmodal .= "<button class='duplicateButtonAcc crudbtns fc' data-id='$r->accountId'>$duplicatesvg</button>";
                    if (in_array($_SESSION["id_role"], [1, 3])) {
                        $outputmodal .= "<button class='updateButtonAcc crudbtns fc' data-id='$r->accountId'>$editsvg</button>";
                        $outputmodal .= "<button class='deleteButtonAcc crudbtns fc' data-id='$r->accountId'>$deletesvg</button>";
                    }
                }
                $outputmodal .= "</div>";
                // CHECKED
                $queryChecked = "SELECT c.checked, c.id_account as idAcc, e.name, e.surname, c.date FROM checked c INNER JOIN accounts a ON a.id_account=c.id_account INNER JOIN employees e ON e.id_employee = c.id_employee WHERE c.checked=1";
                $resultChecked = $con->query($queryChecked)->fetchAll();
                $outputmodal .= "<div class='checked-vab head-tcss fc'>";
                if (in_array($_SESSION["id_role"], [2, 5])) {
                    $cek = "<form class='form'>
                    <div class='inputGroup'>
                    <input data-id='$r->accountId' name='option1' type='checkbox' class='checkboxCheck option1' />
                    <label for='option1' class='checkLabel'>Čekiraj</label>
                </div>
                          
                          </form>";
                    foreach ($resultChecked as $rc) {
                        if ($rc->idAcc == $r->accountId) {
                            $ourDate = date("d.m.Y. H:i:s", strtotime($rc->date));
                            $cek = "<div class='accordion2 js-accordion3' id='accordion2'><div class='accordion2__item js-accordion3-item'><div class='accordion2-header js-accordion3-header'><span>$rc->name $rc->surname</span></div><div class='accordion2-body js-accordion3-body'><div class='accordion2-body__contents fc'><span>$ourDate</span></div></div></div></div>";
                        }
                    }
                    $outputmodal .= $cek;
                } else {
                    $cek = "";
                    if ($resultForGreen) {
                        foreach ($resultChecked as $rc) {
                            if ($rc->idAcc == $r->accountId and $r->isShown == 1) {
                                $ourDate = date("d.m.Y. H:i:s", strtotime($rc->date));
                                $cek = "<div class='accordion2 js-accordion3' id='accordion2'><div class='accordion2__item js-accordion3-item'><div class='accordion2-header js-accordion3-header'><span>$rc->name $rc->surname</span></div><div class='accordion2-body js-accordion3-body'><div class='accordion2-body__contents fc'><span>$ourDate</span></div></div></div></div>";
                            }
                        }
                    } else {
                        $cek = "<div class='unchecked-btn fc'>Ne čekirano</div>";
                    }

                    $outputmodal .= $cek;
                }
                $outputmodal .= "</div>";
                $outputmodal .= "</div>"; // OD CONTAINERA DIV
                echo $outputmodal;
            }
            ?>

            <p class="fl subbp mb0 pl15">PODNALOZI</p>



            <?php
            $query = "SELECT *,id_sub_account as subAccountId, a.date as datee, sa.totalPrice as total, a.id_account as accountId, p.name as patientName, p.surname as patientSurname, e.name as doctorName, e.surname as doctorSurname FROM sub_accounts sa INNER JOIN accounts a ON sa.id_account = a.id_account INNER JOIN patients p ON p.id_patient = a.id_patient INNER JOIN employees e ON e.id_employee = sa.id_employee WHERE sa.isShown = 1 AND a.id_account=$viewId";
            $result = $con->query($query)->fetchAll();
            #var_dump($result);
            $outputmodal = '<div class="viewAccountGrid-head conm2">
                    <div class="no-vah wght500">#</div>
                    <div class="namesur-vah wght500">Ime i Prezime</div>
                    <div class="doctor-vah wght500">Doktor</div>
                    <div class="appointment-vah wght500">Pregled</div>
                    <div class="therapy-vah wght500">Terapija</div>
                    <div class="blood-vah wght500">Analize</div>
                    <div class="fullprice-vah wght500">Ukupno</div>';

            $outputmodal .= '<div class="options-vah wght500">Opcije</div>';

            $outputmodal .= '<div class="checked-vah wght500">Čekirao</div>
                    <div class="dateadded-vah wght500">Datum</div>
                </div>';
            if ($result) {

                foreach ($result as $r) {
                    $subAccountId = $r->subAccountId;
                    $outputmodal .= "<div class='viewAccountGrid-body'>";
                    $queryForGreen = "SELECT checked FROM checkedSub WHERE id_sub_account = $subAccountId";
                    $resultForGreen = $con->query($queryForGreen)->fetchColumn();
                    #var_dump($resultForGreen);
                    if ($resultForGreen) {
                        $outputmodal .= "<div class='no-vab head-tcss fc poppins wght600 green-check'>";
                    } else {
                        $outputmodal .= "<div class='no-vab head-tcss fc poppins wght600 orange-check'>";
                    }
                    $outputmodal .= "$subAccountId</div><div class='namesur-vab fl head-tcss'>$r->patientName $r->patientSurname</div><div class='doctor-vab head-tcss fl'>$r->doctorName $r->doctorSurname</div>
                    <div class='blood-vab head-tcss custom-select fc'><select class='' id='bloodsSelectModal'>";

                    /*  OVO IDE GORE */

                    $queryBloods = "SELECT *,analysis,b.id_blood AS bloodId FROM sub_accounts sa INNER JOIN accounts a ON a.id_account=sa.id_account INNER JOIN sub_accounts_bloods sab ON sab.id_sub_account = sa.id_sub_account INNER JOIN bloods b ON b.id_blood = sab.id_blood WHERE a.id_account = $r->accountId AND sa.id_sub_account = $subAccountId ";
                    $resultBloods = $con->query($queryBloods);
                    $bloodsRowCount = $resultBloods->rowCount();
                    if ($bloodsRowCount > 0) {
                        $resultBloods = $resultBloods->fetchAll();
                        foreach ($resultBloods as $rb) {
                            $outputmodal .= "<option value='$rb->bloodId'>$rb->analysis</option>";
                        }
                    } else {
                        $outputmodal .= "<option>Nema krvi</option>";
                    }
                    $outputmodal .= "</select></div><div class='therapy-vab head-tcss fc'><ul>";
                    $queryTherapies = "SELECT therapy, sa.id_sub_account as accountId FROM sub_accounts sa INNER JOIN sub_accounts_therapies sat ON sa.id_sub_account = sat.id_sub_account INNER JOIN therapies t ON t.id_therapy = sat.id_therapy WHERE sa.id_sub_account = $subAccountId";
                    $resultTherapies = $con->query($queryTherapies);
                    $therapiesRowCount = $resultTherapies->rowCount();
                    $resultTherapies = $resultTherapies->fetch();
                    if ($therapiesRowCount > 0) {
                        $outputmodal .= "<li><button class='btn-therapy btnTherapySubAccount fc' data-idSubAccount='$resultTherapies->accountId'>$resultTherapies->therapy</button></li>";
                    } else {
                        $outputmodal .= "<li>Nema terapije</li>";
                    }
                    $outputmodal .= "</ul></div><div class='appointment-vab head-tcss custom-select fc'><select class='' id='appointmentsSelectModal'>";
                    // APPOINTMENT
                    $queryAppointment = "SELECT saa.id_appointment as IDA, appointment FROM sub_accounts sa INNER JOIN sub_accounts_appointments saa ON saa.id_sub_account = sa.id_sub_account INNER JOIN appointments a ON a.id_appointment = saa.id_appointment WHERE sa.id_sub_account = $subAccountId AND sa.id_account = $r->accountId";
                    $rowCountAppointment = $con->query($queryAppointment)->rowCount();
                    $resultAppointment = $con->query($queryAppointment)->fetchAll();
                    if ($rowCountAppointment > 0) {
                        foreach ($resultAppointment as $ra) {
                            $outputmodal .= "<option value='$ra->IDA'>$ra->appointment</option>";
                        }
                    } else {
                        $outputmodal .= "<option>Nema pregleda</option>";
                    }
                    $outputmodal .= "</select></div>";
                    // DATE
                    $queryDate = "SELECT date FROM sub_accounts WHERE id_sub_account = $subAccountId";
                    $resultDate = $con->query($queryDate)->fetchAll();
                    foreach ($resultDate as $rd) {
                        $date2 = date("d.m.Y. H:i:s", strtotime($rd->date));
                        $outputmodal .= "<div class='dateadded-vab head-tcss fc'>$date2</div>";
                    }
                    // TOTAL
                    $queryTotal = "SELECT totalPrice FROM sub_accounts WHERE id_sub_account = $subAccountId";
                    $resultTotalPrice = $con->query($queryTotal)->fetch();
                    $outputmodal .= "<div class='fullprice-vab head-tcss fc'>$resultTotalPrice->totalPrice RSD</div>";
                    // BUTTONI I CHECKED
                    $outputmodal .= "<div class='options-vab head-tcss fc'>";
                    $outputmodal .= "<button class='printButtonSubAcc crudbtns fc' data-id='$r->subAccountId'>$printsvg</button>";
                    if (isset($_GET["viewAccount"]) and !empty($_GET["viewAccount"])) {

                        if (in_array($_SESSION["id_role"], [1, 3])) {
                            $outputmodal .= "<button class='updateButtonAcc1 crudbtns fc' data-id='$r->subAccountId'>$editsvg</button>";
                            $outputmodal .= "<button class='deleteButtonAcc1 crudbtns fc' data-id='$r->subAccountId'>$deletesvg</button>";
                        }
                    }
                    $outputmodal .= "</div>";
                    // CHECKED
                    $queryChecked = "SELECT c.checked, c.id_sub_account as idSubAccount, e.name, e.surname, c.date FROM checkedSub c INNER JOIN sub_accounts a ON a.id_sub_account=c.id_sub_account INNER JOIN employees e ON e.id_employee = c.id_employee WHERE c.checked=1";
                    $resultChecked = $con->query($queryChecked)->fetchAll();
                    $outputmodal .= "<div class='checked-vab head-tcss fc'>";
                    if (in_array($_SESSION["id_role"], [2, 5])) {
                        $cek = "<form class='form'>
  
                            <div class='inputGroup'>
                              <input data-id='$subAccountId' name='option1' type='checkbox' class='option1 checkboxCheck1'/>
                              <label for='option1' class='checkLabel'>Čekiraj</label>
                            </div>
                          
                          </form>";
                        foreach ($resultChecked as $rc) {
                            if ($rc->idSubAccount == $subAccountId) {
                                $ourDate = date("d.m.Y. H:i:s", strtotime($rc->date));
                                $cek = "<div class='accordion2 js-accordion3' id='accordion2'><div class='accordion2__item js-accordion3-item'><div class='accordion2-header js-accordion3-header'><span>$rc->name $rc->surname</span></div><div class='accordion2-body js-accordion3-body'><div class='accordion2-body__contents fc'><span>$ourDate</span></div></div></div></div>";
                            }
                        }
                        $outputmodal .= $cek;
                    } else {
                        $cek = "";
                        if ($resultForGreen) {
                            echo "AAAAAAAAAAAAA";
                            foreach ($resultChecked as $rc) {
                                if ($rc->idSubAccount == $subAccountId and $r->isShown == 1) {
                                    $ourDate = date("d.m.Y. H:i:s", strtotime($rc->date));
                                    $cek = "<div class='accordion2 js-accordion3' id='accordion2'><div class='accordion2__item js-accordion3-item'><div class='accordion2-header js-accordion3-header'><span>$rc->name $rc->surname</span></div><div class='accordion2-body js-accordion3-body'><div class='accordion2-body__contents fc'><span>$ourDate</span></div></div></div></div>";
                                }
                            }
                        } else {
                            $cek = "<div class='unchecked-btn fc'>Ne čekirano</div>";
                        }

                        $outputmodal .= $cek;
                    }
                    $outputmodal .= "</div>";
                    $outputmodal .= "</div>"; // OD CONTAINERA DIV
                }
                echo $outputmodal;
            } else {
                echo "<div class='noaccount'>Nema podnaloga</div>";
            }
            ?>
        </div>
    </div>
<?php
}
