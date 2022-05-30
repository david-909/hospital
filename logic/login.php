<?php
include_once("functions.php");
startSession();
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        include_once("../data/connection.php");
        global $con;
        $username = checkVariable("username");
        $password = md5(checkVariable("password"));
        $query = "SELECT * FROM employees WHERE username = ? AND password = ?";
        $prepared = $con->prepare($query);
        $prepared->execute([$username, $password]);
        $result = $prepared->fetch();
        if ($result) {
            setSession($result->id_employee, $result->name, $result->surname, $result->id_role, $result->id_specialization);
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
