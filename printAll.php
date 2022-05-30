<?php
include_once("views/head.php");

include_once("logic/functions.php");
$hlogo = file_get_contents("assets/img/hlogo.svg");
$deletesvg = file_get_contents("assets/img/deletebtn.svg");
startSession();
?>
<div id="printTheDivisionValue"></div>
<div class='print-settings fdcolumn2l'>
    <p class="fs18 ptb5 gray txtc wght600">Uredi</p>
    <button class='customer-print'>Clean</button>
    <button class='therapy-print'>Terapija</button>
    <button class='onlymedicine-print'>Red Cena Lekova</button>
    <button class='appblprice-print'>Red Cena pregleda/analize </button>
    <button class='pregledi-print'>Cena Lekovi</button>
    <button class='th-print'>Pregledi/Analize</button>
    <button class='removeprice-bloodanalysis'>Ukupne Cena Pregelda/Analiza</button>
    <button class='remove-ukupno'>Ukupna cena</button>
    <button class='removecomment-print'>Komentar</button>
    <button class='drremovecomment-print'>Dr. Izveštaj</button>
    <button class='remove-date-print'>Datum </button>
    <button class='remove-id-print'>ID</button>
    <li class="wght600 fs16 gray" id="printMe">CTRL+P za štampanje</li>
</div>
<div class="info-msg fs13">
    <div class="fb msg-info">
        <p class="wght700 fs15 gray fc">ITIME info</p>
        <button class="exit-alert-box fc"><?php echo $deletesvg ?></button>
    </div>

    <ul>


        <li class="wght500">Želite gotov profil štampanja?</li>
        <li class="wght500">Ili još dodatnih alata? Kao npr:</li>
        <li>- Unos/upload slike</li>
        <li>- Brzo štampanje</li>
        <li>I mnogo toga još...</li>
    </ul>
</div>
<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {

        // name, surname, jmbg, docName, docSurname,
        include_once("data/connection.php");
        global $con;
        if (isset($_GET["idAcc"]) and !empty($_GET["idAcc"])) {
            $id = (int)$_GET["idAcc"];
        }
        #var_dump($id);
        $docName = "";
        $docSur = "";
        $queryPatient = "SELECT p.name as patientName, p.surname as patientSurname, a.anamneza as anamneza, a.nalaz as nalaz, a.uznalaz as uznalaz, a.terapija as terapija, a.zakljucak as zakljucak,jmbg, id_account as accountId, a.comment as comment, e.name as drName, e.surname as drSurname, a.date as date, a.totalPrice as totalPrice FROM patients p INNER JOIN accounts a on a.id_patient=p.id_patient INNER JOIN employees e ON e.id_employee  = a.id_employee WHERE id_account = $id";
        $resultPatient = $con->query($queryPatient)->fetchAll();
        /* var_dump($resultPatient); */
        $output = "<div id='allInfo' class='allInfo'>
        
        <div class='header-print-page fb2'> 
        
        
        
        <div class='logoprint logoa4'>$hlogo</div>";
        $output .= "<div class='naloginfo-print fdcolumn2r'><div class='dbid'>Nalog ID: $id</div>";
        foreach ($resultPatient as $rp) {
            $docName = $rp->drName;
            $docSur = $rp->drSurname;
            $date = $rp->date;
            $date = date("d.m.Y. H:i:s", strtotime($date));
            $output .= "<div class='date-print'><input class='txtr' value='$date'></div></div></div>"; // od class header-print 
            $output .= "
                      
            <div class='name-surname fnoalign'><div id='printPatientName' class='printPatientName'>$rp->patientName </div>&nbsp;<div id='printPatientSurname' class='printPatientSurname'>$rp->patientSurname </div></div><div id='printPatientJmbg ' class='printPatientJmbg fnoalign'>JMBG: $rp->jmbg</div>";
            
        }
        $output .= "<div class='print-body master-print'><div class='appblood-print'>";
        $queryAppointments = "SELECT * FROM appointmentsview WHERE accountId = $id";
        $resultAppointments = $con->query($queryAppointments)->fetchAll();
        if ($resultAppointments) {
            $output .= "<div class='header-print fb'><h3>Pregled</h3><h3 class='h3cena-print h3cena-print2'>Količina</h3></div>";
            foreach ($resultAppointments as $ra) {
                $output .= "<div class='tab-print fc'><div class='service-print fl'>$ra->appointment</div><div class='service2-print edit-print appbl-print'><input type='text' class='value-print fr txtr tab-printclass' value='$ra->appointmentPrice'><input class='currency-print  fc ml5 tab-printclass' type='text' value='RSD'></div></div>";
            }
            $queryAppointmentsPriceTotal = "SELECT appointmentPriceTotal FROM appointmentsview WHERE accountId = $id LIMIT 1";
            $resultAppointmentsPriceTotal = $con->query($queryAppointmentsPriceTotal)->fetch();
            $output .= "<div id='printAppointmentPriceTotal' class='price-print fc'><span class='ukupno2print'>Pregled ukupno:</span><input class='edit-service-price-print wght500 ukupno2print' value='$resultAppointmentsPriceTotal->appointmentPriceTotal'><input class='ml5 currency-service-print wght500 ukupno2print' value='RSD'></div>";
        }

        $queryBloods = "SELECT * FROM bloodsview WHERE accountId = $id";
        $resultBloods = $con->query($queryBloods)->fetchAll();
        if ($resultBloods) {
            $output .= "<div class='header-print fb mt15'><h3>Analiza</h3><h3 class='h3cena-print h3cena-print2'>Količina</h3></div>";
            foreach ($resultBloods as $rb) {
                $output .= "<div class='tab-print fc'><div class='service-print fl'>$rb->analysis</div><div class='service2-print edit-print appbl-print'><input type='text' class='value-print fr txtr tab-printclass' value='$rb->bloodPrice'><input class='currency-print  fc ml5 tab-printclass' type='text' value='RSD'></div></div>";
            }
            $queryBloodsPriceTotal = "SELECT bloodPriceTotal FROM bloodsview WHERE accountId = $id LIMIT 1";
            $resultBloodsPriceTotal = $con->query($queryBloodsPriceTotal)->fetch();
            $output .= "<div id='printAppointmentPriceTotal' class='price-print fc'><span class='ukupno2print'>Analize ukupno:</span><input class='edit-service-price-print wght500 ukupno2print' value='$resultBloodsPriceTotal->bloodPriceTotal'><input class='ml5 currency-service-print wght500 ukupno2print' value='RSD'></div>";
        }
        $output .= "</div>";
        $queryMedicine = "SELECT DISTINCT * FROM medicineview WHERE accountId = $id";
        $resultMedicine = $con->query($queryMedicine)->fetchAll();
        if ($resultMedicine) {
            $queryMedicinePriceTotal = "SELECT therapy, totalPrice, date FROM medicineview WHERE accountId = $id LIMIT 1";
            $resultMedicinePriceTotal = $con->query($queryMedicinePriceTotal)->fetch();
            $output .= "<div class='therapy-prices'><div id='printTherapy' class='printTherapy fb3'><div class='fdcolumn2 terapija-head'><span>$resultMedicinePriceTotal->therapy</span><h3>Terapija</h3></div><h3 class='h3cena-print h3therapy'>Količina</h3></div>";
            foreach ($resultMedicine as $rm) {
                $output .= "<div class='tab-print fc'><div class='service-print fl'>$rm->medicine</div>";
                $output .= "<div class='service2-print edit-medicine-print'><input class='  hideallq quantity-print' value='$rm->tmQuantity'>x<input class='value-print fr txtr       ' value='$rm->medicinePrice'><input class='      ' value='RSD'><span class=' '>=</span><input class='      ' value=' $rm->pricePerMedicine'><input class='      ' value='RSD'></div></div>";
            }
            foreach ($resultPatient as $rp) {



                $output .= "<div id='' class='fc wght600 totalPrice-print ukupnoprint mt15'>Ukupno: <input class='edit-service-price-print fs18 wght600 gray     ' value='$rp->totalPrice'><input class='fs18 wght600 gray ml5 currency-fullservice-print     ' value='RSD'></div>";
                $output .= "<h3 class='fs16 txtc mt25 drheadcomment wght800'>DOKTORSKI IZVEŠTAJ</h3>
                            <h3 class='fs14 ml10 mt5 drheadcomment'>Anamneza:</h3><div class='drprint'>$rp->anamneza</div>";
                $output .= "<h3 class='fs14 ml10 mt5 drheadcomment'>Nalaz:</h3><div class='drprint'>$rp->nalaz</div>";
                $output .= "<h3 class='fs14 ml10 mt5 drheadcomment'>UZ Nalaz:</h3><div class='drprint'>$rp->uznalaz</div>";
                $output .= "<h3 class='fs14 ml10 mt5 drheadcomment'>Terapija:</h3><div class='drprint'>$rp->terapija</div>";
                $output .= "<h3 class='fs14 ml10 mt5 drheadcomment'>Zaključak:</h3><div class='drprint'>$rp->zakljucak</div>";
                $output .= "<h3 class='fs14 ml10 mt5 headcomment'>Komentar:</h3><div id='printCommentAccount' class='printCommentAccount-edit-print' oninput='auto_grow(this)'>$rp->comment</div>";
                $output .= "<div class='drname-surname fnoalign'><div id='printDrName' class='printDrName'>Doktor: $docName</div><div id='drSurname' class='drSurname'>&nbsp;$docSur</div></div>";
            }
        }


        $output .= "</div><div class='underline'></div></div></div>";
        echo $output;


        // !OVDE KRECE PODNALOG

        $rq = "SELECT * FROM sub_accounts WHERE id_account = $id";
        $rrq = $con->query($rq)->fetchAll();
        #var_dump($rrq);

        foreach ($rrq as $r) {
            /* echo $r->id_sub_account;
            echo "<br>"; */

            $idSubAccount = $r->id_sub_account;
            $output = "<div id='allInfo' class='allInfo'>";
            $output .= "<div class='dbid fc fs20 dbidsub'>Podnalog ID: $idSubAccount</div>";


            $queryPatient = "SELECT sa.id_sub_account as subId, p.name as patientName, p.surname as patientSurname, jmbg, a.id_account as accountId, sa.comment as comment, e.name as drName, e.surname as drSurname, a.date as date, sa.totalPrice as totalPrice FROM patients p INNER JOIN accounts a on a.id_patient=p.id_patient INNER JOIN sub_accounts sa ON a.id_account = sa.id_account INNER JOIN employees e ON e.id_employee  = sa.id_employee WHERE sa.id_sub_account = $idSubAccount";
            $resultPatient = $con->query($queryPatient)->fetchAll();
            #var_dump($resultPatient);

            if ($resultPatient) {
                foreach ($resultPatient as $rp) {
                    $output .= "
                      
            <div class='name-surname fnoalign'><div id='printPatientName' class='printPatientName'>$rp->patientName </div>&nbsp;<div id='printPatientSurname' class='printPatientSurname'>$rp->patientSurname </div></div><div id='printPatientJmbg ' class='printPatientJmbg fnoalign'>JMBG: $rp->jmbg</div>";
                    $output .= "<div class='drname-surname fnoalign'><div id='printDrName' class='printDrName'>Doktor: $rp->drName</div><div id='drSurname' class='drSurname'>&nbsp;$rp->drSurname</div>";
                    $output .= "</div>";
                    $output .= "<div class='print-body slave-print'><div class='appblood-print'>";
                }
            }

            $queryAppointments = "SELECT * FROM subAppointmentsView WHERE idSubAccount = $idSubAccount";
            $resultAppointments = $con->query($queryAppointments)->fetchAll();
            if ($resultAppointments) {
                $output .= "<div class='header-print fb'><h3>Pregled</h3><h3 class='h3cena-print h3cena-print2'>Količina</h3></div>";
                foreach ($resultAppointments as $ra) {

                    $output .= "<div class='tab-print fc'><div class='service-print fl'>$ra->appointment</div><div class='service2-print edit-print appbl-print'><input type='text' class='value-print fr txtr     ' value='$ra->price'><input class='currency-print fc ml5      ' type='text' value='RSD'></div></div>";
                }
                $queryAppointmentsPriceTotal = "SELECT appointmentPriceTotal FROM subAppointmentsView WHERE idSubAccount = $idSubAccount LIMIT 1";
                $resultAppointmentsPriceTotal = $con->query($queryAppointmentsPriceTotal)->fetch();
                $output .= "<div id='printAppointmentPriceTotal' class='price-print fc'><span class='ukupno2print'>Pregled ukupno:</span><input class='edit-service-price-print wght500 ukupno2print' value='$resultAppointmentsPriceTotal->appointmentPriceTotal'><input class='ml5 currency-service-print wght500 ukupno2print' value='RSD'></div>";
            }

            $queryBloods = "SELECT * FROM subBloodsView WHERE idSubAccount = $idSubAccount";
            $resultBloods = $con->query($queryBloods)->fetchAll();
            if ($resultBloods) {
                $output .= "<div class='header-print fb mt15'><h3>Analiza</h3><h3 class='h3cena-print h3cena-print2'>Količina</h3></div>";
                foreach ($resultBloods as $rb) {
                    $output .= "<div class='tab-print fc'><div class='service-print fl'>$rb->blood</div><div class='service2-print edit-print appbl-print'><input type='text' class='value-print fr txtr     ' value='$rb->price'><input class='currency-print fc ml5      ' type='text' value='RSD'></div></div>";
                }
                $queryBloodsPriceTotal = "SELECT bloodPriceTotal FROM subBloodsView WHERE idSubAccount = $idSubAccount LIMIT 1";
                $resultBloodsPriceTotal = $con->query($queryBloodsPriceTotal)->fetch();
                $output .= "<div id='printAppointmentPriceTotal' class='price-print fc'><span class='ukupno2print'>Analize ukupno:</span><input class='edit-service-price-print wght500 ukupno2print' value='$resultBloodsPriceTotal->bloodPriceTotal'><input class='ml5 currency-service-print wght500 ukupno2print' value='RSD'></div>";
            }
            $output .= "</div>";

            $queryMedicine = "SELECT * FROM subMedicineView WHERE idSubAccount = $idSubAccount";
            $resultMedicine = $con->query($queryMedicine)->fetchAll();

            if ($resultMedicine) {
                $queryMedicinePriceTotal = "SELECT * FROM subMedicineView WHERE idSubAccount = $idSubAccount LIMIT 1";
                $resultMedicinePriceTotal = $con->query($queryMedicinePriceTotal)->fetch();
                $output .= "<div id='printTherapy' class='printTherapy fb2'><div class='fdcolumn2 terapija-head'><span>$resultMedicinePriceTotal->therapy</span><h3>Terapija</h3></div><h3 class='h3cena-print h3therapy'>Količina</h3></div>";
                foreach ($resultMedicine as $rm) {
                    $output .= "<div class='tab-print fc'><div class='service-print fl'>$rm->medicines</div>";
                    $output .= "<div class='service2-print edit-medicine-print'><input class='  hideallq quantity-print' value='$rm->tmQuantity'>x<input class='value-print fr txtr       ' value='$rm->medicinePrice'><input class='      ' value='RSD'><span class=' '>=</span><input class='      ' value=' $rm->medicinePrice'><input class='      ' value='RSD'></div></div>";
                }
            }
            $queryMedicine1 = "SELECT therapyPriceTotal FROM subMedicineView WHERE idSubAccount = $idSubAccount LIMIT 1";
            $resultMedicine1 = $con->query($queryMedicine1)->fetch();
            if ($resultMedicine1) {
                $output .= "<div class='price-print fc'><span class='h3ceona-print ukupno2print'>Terapija ukupno:</span><input class='edit-service-price-print wght500 ukupno2print'  value='$resultMedicine1->therapyPriceTotal'><input class='currency-fullservice-print ml5 wght500 ukupno2print' value='RSD'></div>";
            }
            #ar_dump($resultMedicine1);

            $output .= "<h3 class='fs14 ml10 mt5 headcomment'>Komentar:</h3><div id='printCommentAccount' class='printCommentAccount-edit-print'>$rp->comment</div>";
            $output .= "</div><div class='underline'></div></div></div>";
            echo $output;
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
    include_once("views/footer.php");
    include_once("views/scripts.php");
} else {
    http_response_code(404);
}
