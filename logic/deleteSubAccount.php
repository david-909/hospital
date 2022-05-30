<?php
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    try {
        include_once("../data/connection.php");
        global $con;
        if (isset($_GET["idSubAccount"]) and !empty($_GET["idSubAccount"])) {
            $idSubAccount = $_GET["idSubAccount"];
        }
        $queryForDeleteSubAccount = "DELETE FROM sub_accounts WHERE id_sub_account = $idSubAccount";
        $result = $con->query($queryForDeleteSubAccount);
        echo $result ?  json_encode(1) : json_encode(0);
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
