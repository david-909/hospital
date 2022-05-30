<?php
include_once("functions.php");
startSession();
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        include_once("../data/connection.php");
        global $con;
        $input = $_POST["input"];
        if (isset($_POST["pageLoc"]) and !empty($_POST["pageLoc"])) {
            $pageLoc = $_POST["pageLoc"];
        }
        #var_dump($pageLoc);
        $query = "SELECT *, id_blood as anId FROM bloods WHERE analysis LIKE '%$input%' LIMIT 50";
        $prepared = $con->prepare($query);
        $prepared->execute();
        $rez = $prepared->fetchAll();
        if ($pageLoc == "adminpanel.php") {
            $output = "<div class='priceCard fdcolumn2 container85 scroll2 mt20 section'><table class='price-table'><tr class='firstTr'><th>Analiza</th><th>Cena</th><th>Izmena</th>";
        } else {
            $output = "<div class='priceCard fdcolumn2 container85 scroll2 mt20 section'><table class='price-table'><tr class='firstTr'><th>Analiza</th><th>Cena</th></tr>";
        }
        if ($rez) {
            foreach ($rez as $r) {
                $output .= "<tr><td class='analysisPrice' data-price='$r->price'><button class='addprice-btn'>$r->analysis</button></td><td   data-price='$r->price'>$r->price</td>";
                if ($pageLoc == "adminpanel.php") {
                    
                    $output .= "<td><input type='text' class='updateAnalysisPrice'><button class='updateAnalysisButton' data-id='$r->anId'>Izmeni</button><td>";
                    
                }
                $output .= "</tr>";
            }
        }else {
            $output = "<p class='fs20 gray wght700 txtc mt20'>Nema rezultata</p>";
        }
        $output .= "</div>";
        echo json_encode($output);
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}