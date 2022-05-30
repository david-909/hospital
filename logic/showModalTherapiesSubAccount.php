<?php
include_once("functions.php");
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        // name, surname, jmbg, docName, docSurname,
        include_once("../data/connection.php");
        global $con;
        if (isset($_GET["idSubAccount"]) and !empty($_GET["idSubAccount"])) {
            $id = (int)$_GET["idSubAccount"];
        }
        $queryPatient = "SELECT p.name as patientName, p.surname as patientSurname, jmbg, a.id_account as accountId, sa.comment as comment, e.name as drName, e.surname as drSurname, a.date as date, sa.totalPrice as totalPrice FROM patients p INNER JOIN accounts a on a.id_patient=p.id_patient INNER JOIN sub_accounts sa ON a.id_account = sa.id_account INNER JOIN employees e ON e.id_employee  = sa.id_employee WHERE id_sub_account = $id";
        $resultPatient = $con->query($queryPatient)->fetchAll();
        if ($resultPatient) {
            $output = "<div id='allInfo' class='allInfoscroll'>";
        foreach ($resultPatient as $rp) {
            $output .= "<div class='fc pacijentClass'><div class='namesurnameClass fdcolumn2'><div class='fc'><div id='printPatientName'>$rp->patientName</div><div id='printPatientSurname'>&nbsp;$rp->patientSurname</div></div><div id='printPatientJmbg' class='fc'>JMBG: $rp->jmbg</div></div>
            
            
            
            </div>";
            }

            $queryAppointments = "SELECT * FROM subAppointmentsView WHERE idSubAccount = $id";
            $resultAppointments = $con->query($queryAppointments)->fetchAll();
            if ($resultAppointments) {
                $output .= '<div class="fnoalign mt20 pregledterapijeflex">';
                $output .= '<div class="fdcolumn2"><p class="pb10 fs21 wght700 gray fc margina">PREGLEDI</p><div class="fdcolumn2 pregledterapije"><div class="appPrice scroll2">';
                foreach ($resultAppointments as $ra) {
                    $output .= "<div class='appTab fb'><div class='printAppointmentName scroll2'>Pregled: $ra->appointment</div><span class='fc'>$ra->price RSD</span></div>";
                }
                $queryAppointmentsPriceTotal = "SELECT appointmentPriceTotal FROM subAppointmentsView WHERE idSubAccount = $id LIMIT 1";
                $resultAppointmentsPriceTotal = $con->query($queryAppointmentsPriceTotal)->fetch();
                $output .= "</div>";
                $output .= "<div class='fc ukupnocena' id='printAppointmentPriceTotal'>Pregled ukupno: $resultAppointmentsPriceTotal->appointmentPriceTotal RSD</div></div></div>";
            }

            $queryBloods = "SELECT * FROM subBloodsView WHERE idSubAccount = $id";
            $resultBloods = $con->query($queryBloods)->fetchAll();
            if ($resultBloods) {
                $output .= '<div class="fdcolumn2"><p class="pb10 fs21 wght700 gray fc margina">ANALIZE</p><div class="fdcolumn2 pregledterapije"><div class="blPrice scroll2">';
                foreach ($resultBloods as $rb) {
                    $output .= "<div class='bloodTab fb'><div class='printBloodName scroll2'>$rb->blood</div><span class='fc printBloodPrice'>$rb->price RSD</span></div>";
                }
                $queryBloodsPriceTotal = "SELECT bloodPriceTotal FROM subBloodsView WHERE idSubAccount = $id LIMIT 1";
                $resultBloodsPriceTotal = $con->query($queryBloodsPriceTotal)->fetch();
                $output .= '</div>';
                $output .= "<div id='printBloodPriceTotal' class='fc ukupnocena printBloodPriceTotal'>Analize ukupno: $resultBloodsPriceTotal->bloodPriceTotal RSD</div></div></div>";
            }

            $queryMedicine = "SELECT * FROM subMedicineView WHERE idSubAccount = $id";
            $resultMedicine = $con->query($queryMedicine)->fetchAll();
            if ($resultMedicine) {
                $queryMedicinePriceTotal = "SELECT therapy, totalPrice, date FROM subMedicineView WHERE idSubAccount = $id LIMIT 1";
                $resultMedicinePriceTotal = $con->query($queryMedicinePriceTotal)->fetch();
                $output .= '<div class="fdcolumn2"><p class="pb10 fs21 wght700 gray fc margina">TERAPIJA</p><div class="fdcolumn2 pregledterapije"><div class="thePrice scroll2">';
                $output .= "<div id='printTherapy' class='txtc wght700 gray fs18 pb5'>$resultMedicinePriceTotal->therapy</div>";
                foreach ($resultMedicine as $rm) {

                    $output .= "<div class='fb therapyTab'><div class='printMedicineName scroll2'>$rm->tmQuantity x $rm->medicines</div>";
                    $output .= "<span class='printMedicineQuantity fc'>$rm->medicinePrice RSD</span></div>";
                }
                
                
    
                
            }
            $queryMedicine1 = "SELECT therapyPriceTotal FROM subMedicineView WHERE idSubAccount = $id LIMIT 1";
            $resultMedicine1 = $con->query($queryMedicine1)->fetch();
            if ($resultMedicine1) {
                $output .= "</div><div id='printTherapyPriceTotal' class='printTherapyPriceTotal ukupnocena fc'>Terapija ukupno: $resultMedicine1->therapyPriceTotal</div>";
                $output .= "</div>";

            }
            #ar_dump($resultMedicine1);
            foreach ($resultPatient as $rp) {

                $date = $rp->date;
                $date = date("d.m.Y. H:i:s", strtotime($date));
                $output .= "</div>";
                $output .= "</div>";
                $output .= "<div class='fdcolumn2 drandcomment mt20'><div class='fc drborder'><div id='printDrName'>$rp->drName</div><div id='drSurname'>&nbsp;$rp->drSurname</div></div><div class=''><p class='ml20 wght600 txtc mb5'>Komentar:</p><div class='scroll2 modal-s' id='printCommentAccount'>$rp->comment</div></div></div>";
                $output .= "<div class='fdcolumn totalPriceAccountClass'><div id='printDate' class='printDate fc'>$date</div><div id='printTotalPrice' class='printTotalPrice fc'>Ukupno: $rp->totalPrice RSD</div></div>";
                
            }
            

             
            echo json_encode($output);
        } else {
            http_response_code(404);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
