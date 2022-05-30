<?php

include_once("functions.php");
startSession();
$limit = 50;
$page;
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        $pageLoc = "";
        include_once("../data/connection.php");
        global $con;
        $output = "<table>";
        if (!isset($_GET["id"])) $id = 1;
        else {
            $id = $_GET["id"];
        }
        if (isset($_GET["pageLocation"]) and !empty($_GET["pageLocation"])) {
            $pageLoc = $_GET["pageLocation"];
        }

        if ($id == 1) {
            if (!isset($_GET['page'])) {
                $page = 1;
            } else {
                $page = $_GET['page'];
            }
            $output = "<div class='priceCard fdcolumn2 container85 scroll2 mt20 section resizmena'><table class='price-table'><tr class='firstTr'><th>Analiza</th><th>Cena</th>";
            if ($pageLoc == "adminpanel.php") {
                $output .= "<th>Izmena</th>";
                
            }
            $output .= "</tr>";
            $query = "SELECT * FROM bloods";
            $prepared = $con->prepare($query);
            $prepared->execute();
            $rowCount = $prepared->rowCount();
            $rez = $prepared->fetchAll();
            $pageCount = ceil($rowCount / $limit);
            $start = ($page - 1) * $limit;
            $query = "SELECT *, id_blood as anId FROM bloods LIMIT $start, $limit";
            $prepared = $con->prepare($query);
            $prepared->execute();
            $rez = $prepared->fetchAll();
            if ($rez) {
                foreach ($rez as $r) {
                    $output .= "<tr><td class='analysisPrice' data-price='$r->price'><button class='addprice-btn'>$r->analysis</button></td><td data-price='$r->price'>$r->price</td>";
                    if ($pageLoc == "adminpanel.php") {
                        $output .= "<td class='fc'><input type='text' class='updateAnalysisPrice'><button class='updateAnalysisButton' data-id='$r->anId'>Izmeni</button></td>";
                    }
                    $output .= "</tr>";
                }
            }
            $output .= "</table></div><div class='paginationFront scroll2'><ul class='pagination fc'>" ?>
            <?php
            for ($i = 1; $i <= $pageCount; $i++) {
                $class = $page == $i ? 'btn-active' : "";
                $output .= "<li class='" . $class . "'><a class='btnPage' href='?page=" . $i . "'>" . $i . "</a></li>";
            }
            ?>
            <?php $output .= "</ul></div>";
            ?>
            <?php
        }
        if ($id == 2) {
            // $page = 1;
            $output = "<div class='priceCard fdcolumn2 container85 scroll2 mt20 section' id='priceCard'><table class='price-table'><tr class='firstTr'><th>Pregled</th><th>Cena</th>";
            if ($pageLoc == "adminpanel.php") {
                $output .= "<th>Izmena</th>";
            }
            $output .= "</tr>";
            // TODO OVDE ZAVRSI UPDATE
            $query = "SELECT *,id_appointment as appId FROM appointments";
            $rez = $con->query($query)->fetchAll();
            if ($rez) {
                foreach ($rez as $r) {
                    $output .= "<tr><td class='appointmentPrice' data-price='$r->price'><button class='addprice-btn'>$r->appointment</button></td><td>$r->price</td>";
                    if ($pageLoc == "adminpanel.php") {
                        $output .= "<td class='fc'><input type='text' class='updateAppointmentPrice'><button class='updateAppointmentButton' data-id='$r->appId'>Izmeni</button></td>";
                    }
                    $output .= "</tr>";
                }
            }
            $output .= "</table></div>";
        }
        if ($id == 3) {

            // $page = 1;
            $output = "<div class='priceCard fdcolumn2 container85 scroll2 mt20 section' id='priceCard'><table class='price-table'><tr class='firstTr'><th>Lek</th><th>Cena</th>";
            if ($pageLoc == "adminpanel.php") {
                $output .= "<th>Izmena</th>";
            }
            $output .= "</tr>";
            $query = "SELECT *, m.id_medicine as medId FROM medicines m INNER JOIN medicine_prices mp ON mp.id_medicine = m.id_medicine ORDER BY CAST(medicine as unsigned) ASC";
            $rez = $con->query($query)->fetchAll();
            if ($rez) {
                foreach ($rez as $r) {
                    $output .= "<tr><td class='medicinePrice' data-price='$r->price'><button class='addprice-btn'>$r->medicine</button></td><td>$r->price</td>";
                    if ($pageLoc == "adminpanel.php") {
                        $output .= "<td class='fc'><input type='text' class='updateMedicinePrice'><button class='updateMedicineButton' data-id='$r->medId'>Izmeni</button></td>";

                    }
                    $output .= "</tr>";
                }
            }
            $output .= "</table></div>";
        }
         
        echo json_encode($output);
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }

   
} else {
    http_response_code(404);
    
}
?>
