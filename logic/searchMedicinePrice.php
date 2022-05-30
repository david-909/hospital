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

        $query = "SELECT *, m.id_medicine as mId FROM medicines m INNER JOIN  medicine_prices mp ON m.id_medicine=mp.id_medicine WHERE medicine LIKE '%$input%' LIMIT 20";
        $prepared = $con->prepare($query);
        $prepared->execute();
        $rez = $prepared->fetchAll();
        $output = "<table><tr><th>Analiza</th><th>Cena</th><th>Izmena</th></tr>";
        if ($rez) {
            foreach ($rez as $r) {
                $output .= "<tr><td class='medicinePrice' data-price='$r->price'>$r->medicine</td><td   data-price='$r->price'>$r->price</td>";
                if ($pageLoc == "adminpanel.php") {
                    $output .= "<td><input type='text' class='updateMedicinePrice'></td>";
                    $output .= "<td><button class='updateMedicineButton' data-id='$r->mId'>Promeni</button></td>";
                }
                $output .= "</tr>";
            }
        }

        echo json_encode($output);
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
