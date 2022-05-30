<?php
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once("functions.php");
    try {
        $idTherapy = +$_POST["idTherapy"];
        #var_dump($idTherapy);
        include_once("../data/connection.php");
        global $con;
        $query = "SELECT *,tm.quantity AS tmQuantity,m.id_medicine as medicineId FROM therapies t INNER JOIN therapy_medicine tm ON t.id_therapy=tm.id_therapy INNER JOIN medicines m ON tm.id_medicine = m.id_medicine INNER JOIN medicine_prices mp ON mp.id_medicine = m.id_medicine WHERE tm.id_therapy = $idTherapy ORDER BY CAST(m.medicine as unsigned) ASC";
        $result = $con->query($query)->fetchAll();
        // var_dump($result);
        if ($result) {
            echo json_encode($result);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
