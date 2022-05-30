<?php
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include_once("functions.php");
    try {
        include_once("../data/connection.php");
        global $con;
        $search = $_POST["search"];
        $query = "SELECT * FROM patients WHERE name LIKE '%$search%' OR surname LIKE '%$search%' OR jmbg LIKE '%$search%' OR CONCAT(name,' ',surname) LIKE '%$search%' OR CONCAT(surname,' ',name) LIKE '%$search%' ORDER BY name DESC LIMIT 5";
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