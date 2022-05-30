<?php
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        include_once("../data/connection.php");
        global $con;
        $output = "";
        $query = "SELECT * FROM bloods LIMIT 100";
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
