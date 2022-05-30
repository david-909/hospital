<?php
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once("functions.php");
    try {
        include_once("../data/connection.php");
        global $con;
        $patientId = +checkVariable("patientId");
        $comment = checkVariable("commentExpense");
        if ($comment == 0) $comment = "";
        $expensePrice = (float) checkVariable("expensePrice");
        $query = "INSERT INTO expenses VALUES(null,?,?, CURRENT_TIMESTAMP,?,1)";
        $prepared = $con->prepare($query);
        $rez = $prepared->execute([$patientId, $expensePrice, $comment]);
        if ($rez) {
            echo json_encode(1);
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
