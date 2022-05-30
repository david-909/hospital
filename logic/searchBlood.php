<?php
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once("functions.php");
    try {
        include_once("../data/connection.php");
        global $con;
        $searchBlood = $_POST["searchBlood"];
        $query = "SELECT * FROM bloods WHERE analysis LIKE '%$searchBlood%' LIMIT 100";
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
