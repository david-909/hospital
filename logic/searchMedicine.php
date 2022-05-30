<?php
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once("functions.php");
    try {
        include_once("../data/connection.php");
        global $con;
        $searchMedicine = $_POST["searchMedicine"];
        $query = "SELECT * FROM medicines m INNER JOIN  medicine_prices mp ON m.id_medicine=mp.id_medicine WHERE medicine LIKE '%$searchMedicine%' ORDER BY CAST(m.medicine as unsigned) ASC LIMIT 100";
        $rez = $con->query($query)->fetchAll();
        if ($rez) {
            echo json_encode($rez);
        } else {
            echo json_encode(0);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
