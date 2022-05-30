<?php
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once("functions.php");
    try {
        include_once("../data/connection.php");
        global $con;
        $query = "SELECT * FROM medicines INNER JOIN medicine_prices ON medicines.id_medicine=medicine_prices.id_medicine WHERE priority = 1 ORDER BY CAST(medicines.medicine as unsigned) ASC";
        $rez = $con->query($query)->fetchAll();
        if ($rez) {
            echo json_encode($rez);
        }
    } catch (PDOException $e) {
        echo $e->getMessage();
        http_response_code(500);
    }
} else {
    http_response_code(404);
}
