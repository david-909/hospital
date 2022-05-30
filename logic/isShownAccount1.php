<?php
header("Content-type: application/json");
include_once("functions.php");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        include_once("../data/connection.php");
        global $con;
        if (isset($_GET["id"]) and !empty($_GET["id"])) {
            $id = +$_GET["id"];
        }
        #var_dump($id);
        $query = "UPDATE sub_accounts SET isShown = false WHERE id_sub_account=$id";
        $result = $con->query($query);
        if ($result) {
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
