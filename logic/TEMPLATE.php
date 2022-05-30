<?php
include_once("data/connection.php");
global $con;
$query = "SELECT * FROM patients";
$result = $con->query($query)->fetchAll();
return json_encode($result);
/*
! OVAKO SE POZIVA NA DRUGOJ .PHP STRANICI 
<div id="proba"><?php
                    $result = json_decode(include_once("logic/TEMPLATE.php"));
                    foreach ($result as $r) {
                        echo "$r->name $r->surname $r->phone";
                    }
                    ?>
</div> */