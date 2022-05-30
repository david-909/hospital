<?php
include_once("functions.php");
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        include_once("../data/connection.php");
        global $con;
        $input = $_POST["input"];
        $query = "SELECT * FROM appointments WHERE appointment LIKE '%$input%' LIMIT 50";
        $result = $con->query($query)->fetchAll();

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
