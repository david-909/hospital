<?php
include_once("functions.php");
startSession();
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_SESSION["id_employee"])) {
        stopSession();
        echo json_encode(1);
    } else {
        echo json_encode(0);
    }
} else {
    http_response_code(404);
}
