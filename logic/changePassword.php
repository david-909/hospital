<?php
    include_once("functions.php");
    startSession();
    header("Content-type: application/json");
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        try{
            $pass = md5($_POST["password"]);
            $id = $_SESSION["id_employee"];
            $passwordChange = md5($_POST["passwordChange"]);
            include_once("../data/connection.php");
            global $con;
            $query = "SELECT password FROM employees WHERE id_employee = $id";
            $trenutniPass = $con -> query($query)->fetchColumn();
            if($trenutniPass == $pass){
                $query = "UPDATE employees SET password = :password WHERE id_employee = :id";
                $prepared = $con -> prepare($query);
                $prepared -> bindParam(":password", $passwordChange);
                $prepared -> bindparam(":id", $id);
                $rez = $prepared -> execute();
                if($rez){
                    echo json_encode(1);
                }
                else{
                    echo json_encode(0);
                }
            }
            else{
                echo json_encode(2);
            }
        }
        catch(PDOException $e){
            http_response_code(500);
            echo $e->getMessage();
        }
    }
    else{
        http_response_code(404);
    }
?>