<?php
include_once("functions.php");
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        include_once("../data/connection.php");
        global $con;
        $input = $_POST["input"];
        if (isset($_POST["pageLoc"]) and !empty($_POST["pageLoc"])) {
            $pageLoc = $_POST["pageLoc"];
        }
        $query = "SELECT * FROM medicines m INNER JOIN medicine_prices mp ON mp.id_medicine = m.id_medicine WHERE medicine LIKE '%$input%' LIMIT 50";
        $result = $con->query($query)->fetchAll();
        if ($result) {
            if ($pageLoc == "adminpanel.php") {
                $output = "<div class='priceCard fdcolumn2 container85 scroll2 mt20 section'><table class='price-table'><tr class='firstTr'><th>Lek</th><th>Cena</th><th>Izmena</th>";
            } else {
                $output = "<div class='priceCard fdcolumn2 container85 scroll2 mt20 section'><table class='price-table'><tr class='firstTr'><th>Lek</th><th>Cena</th></tr>";
            }
            /* $output = "<div class='priceCard fdcolumn2 container85 scroll2 mt20 section'><table class='price-table'><tr class='firstTr'><th>Lek</th><th>Cena</th></tr>"; */
            foreach ($result as $r) {
                $output .= "<tr><td class='medicinePrice' data-price='$r->price'><button class='addprice-btn'>$r->medicine</button></td><td>$r->price</td></tr>";
            }
            $output .= "</table></div>";
        } else {
            $output = "<p class='fs20 gray wght700 txtc mt20'>Nema rezultata</p>";
        }
        echo json_encode($output);
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
