<?php
include_once("views/head.php");
include_once("logic/functions.php");
startSession();
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        // name, surname, jmbg, docName, docSurname,
        include_once("data/connection.php");
        global $con;
        if (isset($_GET["idAcc"]) and !empty($_GET["idAcc"])) {
            $id = (int)$_GET["idAcc"];
        }
        #var_dump($id);
        $queryPatient = "SELECT p.name as patientName, p.surname as patientSurname, jmbg, id_account as accountId, a.comment as comment, e.name as drName, e.surname as drSurname, a.date as date, a.totalPrice as totalPrice FROM patients p INNER JOIN accounts a on a.id_patient=p.id_patient INNER JOIN employees e ON e.id_employee  = a.id_employee WHERE id_account = $id";
        $resultPatient = $con->query($queryPatient)->fetchAll();
        #var_dump($resultPatient);
        $output = "<div id='allInfo' class='allInfo'>";
        foreach ($resultPatient as $rp) {
            $output .= "<div id='printPatientName' class='printPatientName'>Ime: $rp->patientName </div><div id='printPatientSurname' class='printPatientSurname'>Prezime: $rp->patientSurname </div><div id='printPatientJmbg' class='printPatientJmbg'>JMBG: $rp->jmbg</div>";
            $output .= "<div id='printDrName' class='printDrName'>Ime doktora: $rp->drName</div><div id='drSurname' class='drSurname'>Prezime doktora: $rp->drSurname</div>";
            $output .= "<div id='printCommentAccount' class='printCommentAccount'>Komentar: $rp->comment</div>";
        }

        $queryAppointments = "SELECT * FROM appointmentsview WHERE accountId = $id";
        $resultAppointments = $con->query($queryAppointments)->fetchAll();
        if ($resultAppointments) {
            foreach ($resultAppointments as $ra) {
                $output .= "<div class='printAppointmentName'>Pregled: $ra->appointment</div><div class='printAppointmentPrice'>Cena: $ra->appointmentPrice RSD</div>";
            }
            $queryAppointmentsPriceTotal = "SELECT appointmentPriceTotal FROM appointmentsview WHERE accountId = $id LIMIT 1";
            $resultAppointmentsPriceTotal = $con->query($queryAppointmentsPriceTotal)->fetch();
            $output .= "<div id='printAppointmentPriceTotal' class='printAppointmentPriceTotal'>Pregled ukupno: $resultAppointmentsPriceTotal->appointmentPriceTotal RSD</div>";
        }

        $queryBloods = "SELECT * FROM bloodsview WHERE accountId = $id";
        $resultBloods = $con->query($queryBloods)->fetchAll();
        if ($resultBloods) {
            foreach ($resultBloods as $rb) {
                $output .= "<div class='printBloodName'>Analiza: $rb->analysis</div><div class='printBloodPrice'>Cena analize: $rb->bloodPrice</div>";
            }
            $queryBloodsPriceTotal = "SELECT bloodPriceTotal FROM bloodsview WHERE accountId = $id LIMIT 1";
            $resultBloodsPriceTotal = $con->query($queryBloodsPriceTotal)->fetch();
            $output .= "<div id='printBloodPriceTotal' class='printBloodPriceTotal'>Analize ukupno: $resultBloodsPriceTotal->bloodPriceTotal RSD</div>";
        }

        $queryMedicine = "SELECT DISTINCT * FROM medicineview WHERE accountId = $id";
        $resultMedicine = $con->query($queryMedicine)->fetchAll();
        if ($resultMedicine) {
            $queryMedicinePriceTotal = "SELECT therapy, totalPrice, date FROM medicineview WHERE accountId = $id LIMIT 1";
            $resultMedicinePriceTotal = $con->query($queryMedicinePriceTotal)->fetch();
            $output .= "<div id='printTherapy' class='printTherapy'>Terapija: $resultMedicinePriceTotal->therapy</div>";
            foreach ($resultMedicine as $rm) {
                $output .= "<div class='printMedicineName' class='printMedicineName'>Lek: $rm->medicine</div>";
                $output .= "<div class='printMedicineQuantity' class='printMedicineQuantity'> Koli??ina: $rm->tmQuantity x $rm->medicinePrice RSD = $rm->pricePerMedicine RSD</div>";
            }
        }
        foreach ($resultPatient as $rp) {

            $date = $rp->date;
            $date = date("d.m.Y. H:i:s", strtotime($date));
            $output .= "<div id='printDate' class='printDate'>$date</div><div id='printTotalPrice'>Ukupno: $rp->totalPrice RSD</div>";
        }

        $output .= "</div>";
        echo $output;
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
    include_once("views/footer.php");
} else {
    http_response_code(404);
}
