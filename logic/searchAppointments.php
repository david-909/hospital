<?php
/* include_once("functions.php");
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        include_once("../data/connection.php");
        global $con;
        $input = $_POST["input"];
        $query = "SELECT * FROM appointments WHERE appointment LIKE '%$input%' LIMIT 50";
        $result = $con->query($query)->fetchAll();
        
        if ($result) {
            foreach ($result as $r) {
                $output = "<table><tr><th>Pregled</th><th>Cena</th>";
                if ($pageLoc == "adminpanel.php") {
                    $output .= "<td><input type='text' class='updateAnalysisPrice'></td>";
                    $output .= "<td><button class='updateAnalysisButton' data-id='$r->anId'>Promeni</button></td>";
                }
                $output .= "</tr>";
                $output .= "<tr><td class='appointmentPrice' data-price='$r->price'>$r->appointment</td><td>$r->price</td></tr>";
            }
            $output .= "</table>";
        } else {
            $output = "<h2>Nema rezultata</h2>";
        }
        echo json_encode($output);
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
} */

include_once("functions.php");
startSession();
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        include_once("../data/connection.php");
        global $con;
        $input = $_POST["input"];
        # if (isset($_POST["pageLoc"]) and !empty($_POST["pageLoc"])) {
        $pageLoc = $_POST["pageLoc"];
        #}
        #var_dump($pageLoc);

        $query = "SELECT *,id_appointment as apId FROM appointments WHERE appointment LIKE '%$input%' LIMIT 50";
        $prepared = $con->prepare($query);
        $prepared->execute();
        $rez = $prepared->fetchAll();
        if ($pageLoc == "adminpanel.php") {
            $output = "<div class='priceCard fdcolumn2 container85 scroll2 mt20'><table class='price-table'><tr class='firstTr'><th>Pregled</th><th>Cena</th><th>Izmeni</th>";
        } else {
            $output = "<div class='priceCard fdcolumn2 container85 scroll2 mt20'><table class='price-table'><tr class='firstTr'><th>Pregled</th><th>Cena</th></tr>";
        }
        if ($rez) {
            foreach ($rez as $r) {
                $output .= "<tr><td class='appointmentPrice' data-price='$r->price'><button class='addprice-btn'>$r->appointment</button></td><td   data-price='$r->price'>$r->price</td>";
                if ($pageLoc == "adminpanel.php") {
                    $output .= "<td><input type='text' class='updateAppointmentPrice'><button class='updateAppointmentButton' data-id='$r->apId'>Izmeni</button></td>";
                    
                }
                $output .= "</tr>";
            } 
        }else {
            $output = "<p class='fs20 gray wght700 txtc mt20'>Nema rezultata</p>";
        }
        $output .= "</table></div>";
        echo json_encode($output);
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
