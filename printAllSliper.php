<?php
include_once("logic/functions.php");
$hlogo = file_get_contents("assets/img/hlogo.svg");
startSession();

?>

<!DOCTYPE html>
<html lang="sr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    include_once("logic/functions.php");
    startSession();
    if (!isset($_SESSION["id_employee"])) {
    ?>
        <title>Login | MedicalTime System</title>
    <?php
    } else {
    ?>
        <title>Početna | MedicalTime System</title>
    <?php
    }
    ?>

    <style>
        @font-face {
            font-family: "Monaco";
            /* src: url("printr/Monaco Regular.eot"); */
            src:
                url("assets/printfont/Monaco Regular.ttf") format("truetype")
        }

        @page {
            margin: 0;
        }

        body {
            font-family: "Monaco";
            color: black;
            width: 8cm;
            height: 7.96cm;
            margin: 0 auto;
            font-size: 9pt;
            font-weight: 700;
        }
    </style>
    <link rel="stylesheet" media="print" href="assets/css/printslipper.css">
    <?php
    $url = $_SERVER["REQUEST_URI"];
    $arr = explode("/", $url);
    $br = count($arr) - 1;
    $lastOne = $arr[$br];
    $filename = explode("?", $arr[$br]);
    $filename = $filename[0];
    if (in_array($filename, ["print.php", "printa4.php", "printAll.php", "printAllA4.php", "printAllSliper", "printsliper.php", "printSub.php", "printSubA4.php", "printSubSliper.php"])) {

    ?>

    <?php
    }
    ?>
</head>

<body>

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
            $queryPatient = "SELECT p.name as patientName, p.surname as patientSurname, jmbg, id_account as accountId, a.comment as comment, e.name as drName, e.surname as drSurname, a.date as date, a.totalPrice as totalPrice FROM patients p INNER JOIN accounts a on a.id_patient=p.id_patient INNER JOIN employees e ON e.id_employee  = a.id_employee WHERE id_account = $id";
            $resultPatient = $con->query($queryPatient)->fetchAll();
            #var_dump($resultPatient);
            $output = "<div class='slipper'><div class='slipperformat'>
        
        <div class='header-slipper fdcolumn'> 
        
        
        
        <div class='logo-slipper'>$hlogo</div>";
            $output .= "<div class='naloginfo-slipper'></div>";
            $output .= "<div class='dbid-slipper'>Nalog ID: $id</div>";
            foreach ($resultPatient as $rp) {
                $output .= "</div>"; // od class header-print 
                $output .= "
                      
            <div class='name-surname-slipper fnoalign'>
            
            <div class='printPatientName-slipper'>$rp->patientName </div>
            &nbsp;
            <div class='printPatientSurname-slipper'>$rp->patientSurname </div>
    
            </div>

            <div class='printPatientJmbg-slipper fnoalign'>JMBG: $rp->jmbg</div>";
                $output .= "<div class='drname-surname-slipper fnoalign'><div class='printDrName-slipper'>Doktor: $rp->drName</div><div class='drSurname-slipper'>&nbsp;$rp->drSurname</div>";
                $output .= "</div>";
            }
            $output .= "<div class='slipper-body'>";


            $queryBloods = "SELECT * FROM bloodsview WHERE accountId = $id";
            $resultBloods = $con->query($queryBloods)->fetchAll();
            if ($resultBloods) {
                $output .= "<div class='header-slipper fb'><h3>Analiza</h3><h3>Cena</h3></div>";
                foreach ($resultBloods as $rb) {
                    $output .= "<div class='tab-print-slipper fb'><div class='slipper2'>$rb->analysis</div><div class='slipper1 fc'><input type='text' class='value-print-slipper fr txtr' value='$rb->bloodPrice'><input class='currency-print-slipper' type='text' value='RSD'></div></div>";
                }
                $queryBloodsPriceTotal = "SELECT bloodPriceTotal FROM bloodsview WHERE accountId = $id LIMIT 1";
                $resultBloodsPriceTotal = $con->query($queryBloodsPriceTotal)->fetch();
                $output .= "<div class='price-printslipper fc'>Analize ukupno: <input class='edit-service-price-print-slipper' value='$resultBloodsPriceTotal->bloodPriceTotal'><input class=' currency-service-print-slipper' value='RSD'></div>";
            }

            $queryMedicine = "SELECT DISTINCT * FROM medicineview WHERE accountId = $id";
            $resultMedicine = $con->query($queryMedicine)->fetchAll();
            if ($resultMedicine) {
                $queryMedicinePriceTotal = "SELECT therapy, totalPrice, date FROM medicineview WHERE accountId = $id LIMIT 1";
                $resultMedicinePriceTotal = $con->query($queryMedicinePriceTotal)->fetch();
                $output .= "<div class='printTherapy-slipper'>
            
            <div class='fdcolumn2'>
            <span>$resultMedicinePriceTotal->therapy</span>
            <h3>Terapija</h3>
            </div>
            <div class='fb'>
            <h3>Lekovi</h3>
            <h3 class=''>Količina</h3></div>
            </div>
            ";
                foreach ($resultMedicine as $rm) {
                    $output .= "<div class='tab-print-slipper fb'><div class='slipper2'>$rm->medicine</div>";
                    $output .= "<div class='slipper1 fc'>x<input value='$rm->tmQuantity'></div></div>";
                }
            }

            foreach ($resultPatient as $rp) {

                $date = $rp->date;
                $date = date("d.m.Y. H:i:s", strtotime($date));
                $output .= "<h3 class=''>Komentar:</h3><div class='slipper-comment'>$rp->comment</div>";
            }

            $output .= "</div><p class='slipper-underline'></p>";
            echo $output;


            // !OVDE KRECE PODNALOG

            $rq = "SELECT * FROM sub_accounts WHERE id_account = $id";
            $rrq = $con->query($rq)->fetchAll();
            #var_dump($rrq);

            foreach ($rrq as $r) {
                /* echo $r->id_sub_account;
            echo "<br>"; */
                $idSubAccount = $r->id_sub_account;
                $output = "<div class='slipper'><div class='slipperformat'>";
                $output .= "<div class='dbid-slipper fc'>Podnalog ID: $idSubAccount</div>";
                $output .= "<div class='slipper-body'>";

                $queryPatient = "SELECT sa.id_sub_account as subId, p.name as patientName, p.surname as patientSurname, jmbg, a.id_account as accountId, sa.comment as comment, e.name as drName, e.surname as drSurname, a.date as date, sa.totalPrice as totalPrice FROM patients p INNER JOIN accounts a on a.id_patient=p.id_patient INNER JOIN sub_accounts sa ON a.id_account = sa.id_account INNER JOIN employees e ON e.id_employee  = sa.id_employee WHERE sa.id_sub_account = $idSubAccount";
                $resultPatient = $con->query($queryPatient)->fetchAll();
                #var_dump($resultPatient);

                if ($resultPatient) {
                    foreach ($resultPatient as $rp) {
                        $output .= "
                      
            <div class='name-surname-slipper fnoalign'><div class='printPatientName-slipper'>$rp->patientName </div>&nbsp;<div class='printPatientSurname-slipper'>$rp->patientSurname </div></div><div class='printPatientJmbg-slipper fnoalign'>JMBG: $rp->jmbg</div>";
                        $output .= "<div class='drname-surname-slipper fnoalign'><div class='printDrName-slipper'>Doktor: $rp->drName</div><div class='drSurname-slipper'>&nbsp;$rp->drSurname</div>";
                        $output .= "</div>";
                    }
                }



                $queryBloods = "SELECT * FROM subBloodsView WHERE idSubAccount = $idSubAccount";
                $resultBloods = $con->query($queryBloods)->fetchAll();
                if ($resultBloods) {
                    $output .= "<div class='header-slipper fb'><h3>Analiza</h3><h3>Cena</h3></div>";
                    foreach ($resultBloods as $rb) {
                        $output .= "<div class='tab-print-slipper fb'><div class='slipper2'>$rb->blood</div><div class='slipper1 fc'><input type='text' class='value-print-slipper fr txtr' value='$rb->price'><input class='currency-print-slipper fc ' type='text' value='RSD'></div></div>";
                    }
                    $queryBloodsPriceTotal = "SELECT bloodPriceTotal FROM subBloodsView WHERE idSubAccount = $idSubAccount LIMIT 1";
                    $resultBloodsPriceTotal = $con->query($queryBloodsPriceTotal)->fetch();
                    $output .= "<div class='price-print-slipper fc'>Analize ukupno: <input class='edit-service-price-print-slipper' value='$resultBloodsPriceTotal->bloodPriceTotal'><input class='currency-service-print-slipper' value='RSD'></div>";
                }

                $queryMedicine = "SELECT * FROM subMedicineView WHERE idSubAccount = $idSubAccount";
                $resultMedicine = $con->query($queryMedicine)->fetchAll();
                if ($resultMedicine) {
                    $queryMedicinePriceTotal = "SELECT * FROM subMedicineView WHERE idSubAccount = $idSubAccount LIMIT 1";
                    $resultMedicinePriceTotal = $con->query($queryMedicinePriceTotal)->fetch();
                    $output .= "<div class='printTherapy-slipper'>
                
                <div class='fdcolumn2'>
                <span>$resultMedicinePriceTotal->therapy</span>
                <div class=''></div><h3>Lekovi</h3></div><h3 class=''>Količina</h3>
                </div>";

                    foreach ($resultMedicine as $rm) {
                        $output .= "<div class='tab-print-slipper fb'><div class='slipper2'>$rm->medicines</div>";
                        $output .= "<div class='slipper1 fc'>x<input value='$rm->tmQuantity'></div></div>";
                    }
                }
                #ar_dump($resultMedicine1);
                foreach ($resultPatient as $rp) {

                    $date = $rp->date;
                    $date = date("d.m.Y. H:i:s", strtotime($date));

                    $output .= "<h3 class=''>Komentar:</h3><div class='slipper-comment'>$rp->comment</div>";
                }


                $output .= "</div></div></div></div></div>";
                echo $output;
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo $e->getMessage();
        }
        ?>
    <script>
        
        window.print();
        window.focus();
        window.onfocus=function(){ window.close();} 
        // window.close();
    </script>
    <?php
    } else {
        http_response_code(404);
    }

    ?>