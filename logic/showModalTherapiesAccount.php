<?php
header("Content-type: application/json");
include_once("functions.php");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        include_once("../data/connection.php");
        global $con;
        if (isset($_GET["idAccount"]) and !empty($_GET["idAccount"])) {
            $id = +$_GET["idAccount"];
        }
        #var_dump($id);
        $queryPatient = "SELECT p.name as patientName, p.surname as patientSurname, jmbg, id_account as accountId, a.comment as comment, e.name as drName, e.surname as drSurname, a.date as date, a.totalPrice as totalPrice FROM patients p INNER JOIN accounts a on a.id_patient=p.id_patient INNER JOIN employees e ON e.id_employee  = a.id_employee WHERE id_account = $id";
        $resultPatient = $con->query($queryPatient)->fetchAll();
        #var_dump($resultPatient);
        $output = "<div id='allInfo' class='allInfoscroll'>";
        foreach ($resultPatient as $rp) {
            $output .= "<div class='fc pacijentClass'><div class='namesurnameClass fdcolumn2'><div class='fc'><div id='printPatientName'>$rp->patientName</div><div id='printPatientSurname'>&nbsp;$rp->patientSurname</div></div><div id='printPatientJmbg' class='fc'>JMBG: $rp->jmbg</div></div>
            
            
            
            </div>";
        }

        $queryAppointments = "SELECT * FROM appointmentsview WHERE accountId = $id";
        $resultAppointments = $con->query($queryAppointments)->fetchAll();
        if ($resultAppointments) {
            $output .= '<div class="fnoalign mt20 pregledterapijeflex">';
            $output .= '<div class="fdcolumn2"><p class="pb10 fs21 wght700 gray fc margina">PREGLEDI</p><div class="fdcolumn2 pregledterapije"><div class="appPrice scroll2">';
            foreach ($resultAppointments as $ra) {

                $output .= "<div class='appTab fb'><div class='printAppointmentName scroll2'>$ra->appointment</div><span class='fc'> $ra->appointmentPrice RSD</span></div>";
            }
            $queryAppointmentsPriceTotal = "SELECT appointmentPriceTotal FROM appointmentsview WHERE accountId = $id LIMIT 1";
            $resultAppointmentsPriceTotal = $con->query($queryAppointmentsPriceTotal)->fetch();
            $output .= "</div>";
            $output .= "<div class='fc ukupnocena' id='printAppointmentPriceTotal'>Pregled ukupno: $resultAppointmentsPriceTotal->appointmentPriceTotal RSD</div></div></div>";
        }

        $queryBloods = "SELECT * FROM bloodsview WHERE accountId = $id";
        $resultBloods = $con->query($queryBloods)->fetchAll();
        if ($resultBloods) {
            $output .= '<div class="fdcolumn2"><p class="pb10 fs21 wght700 gray fc margina">ANALIZE</p><div class="fdcolumn2 pregledterapije"><div class="blPrice scroll2">';
            foreach ($resultBloods as $rb) {
                $output .= "<div class='bloodTab fb'><div class='printBloodName scroll2'>$rb->analysis</div><span class='fc'> $rb->bloodPrice RSD</span></div>";
            }
            $queryBloodsPriceTotal = "SELECT bloodPriceTotal FROM bloodsview WHERE accountId = $id LIMIT 1";
            $resultBloodsPriceTotal = $con->query($queryBloodsPriceTotal)->fetch();
            $output .= '</div>';
            $output .= "<div class='fc ukupnocena' id='printBloodPriceTotal'>Analize ukupno: $resultBloodsPriceTotal->bloodPriceTotal RSD</div></div></div>";
        }

        $queryMedicine = "SELECT DISTINCT * FROM medicineview  WHERE accountId = $id";
        $queryComments = "SELECT * FROM accounts WHERE id_account = $id";
        $comment = $con->query($queryComments)->fetch();
        $resultMedicine = $con->query($queryMedicine)->fetchAll();
        if ($resultMedicine) {
            $queryMedicinePriceTotal = "SELECT DISTINCT therapy, totalPrice, therapyPriceTotal, date FROM medicineview WHERE accountId = $id LIMIT 1";
            $resultMedicinePriceTotal = $con->query($queryMedicinePriceTotal)->fetch();
            $output .= '<div class="fdcolumn2"><p class="pb10 fs21 wght700 gray fc margina">TERAPIJA</p><div class="fdcolumn2 pregledterapije"><div class="thePrice scroll2">';
            $output .= "<div id='printTherapy' class='txtc wght700 gray fs18 pb5'>$resultMedicinePriceTotal->therapy</div>";
            $output .= "<div class='theMedicine'>";
            foreach ($resultMedicine as $rm) {

                $output .= "<div class='fb therapyTab'><div class='printMedicineName scroll2'>$rm->tmQuantity x $rm->medicine</div>";
                $output .= "<span class='printMedicineQuantity fc'>$rm->pricePerMedicine RSD</span></div>";
            }

            $output .= "</div>";
            $output .= "</div><span class='fc ukupnocena'>Terapija ukupno: $resultMedicinePriceTotal->therapyPriceTotal RSD</span></div></div>";
            $output .= "</div><div class='fdcolumn2 drandcomment mt20'><div class='fc drborder'><div id='printDrName'>$rp->drName</div><div id='drSurname'>&nbsp;$rp->drSurname</div></div><div class=''><p class='ml20 wght600 txtc mb5'>Komentar:</p><div class='scroll2 modal-s' id='printCommentAccount'>$rp->comment</div><p class='ml20 wght600 txtc mb5'>Anamneza:</p><div class='scroll2 modal-s' id='printCommentAccount'>$comment->anamneza</div><p class='ml20 wght600 txtc mb5'>Nalaz:</p><div class='scroll2 modal-s' id='printCommentAccount'>$comment->nalaz</div><p class='ml20 wght600 txtc mb5'>UZ Nalaz:</p><div class='scroll2 modal-s' id='printCommentAccount'>$comment->uznalaz</div><p class='ml20 wght600 txtc mb5'>Terapija:</p><div class='scroll2 modal-s' id='printCommentAccount'>$comment->terapija</div><p class='ml20 wght600 txtc mb5'>Zaključak:</p><div class='scroll2 modal-s' id='printCommentAccount'>$comment->zakljucak</div></div></div>";
        }
        foreach ($resultPatient as $rp) {

            $date = $rp->date;
            $date = date("d.m.Y. H:i:s", strtotime($date));
            $output .= "<div class='fdcolumn totalPriceAccountClass'><div id='printDate' class='fc'>$date</div><div class='fc' id='printTotalPrice'>Ukupno: $rp->totalPrice RSD</div></div>";
        }


        echo json_encode($output);
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
