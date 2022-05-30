<?php
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    include_once("functions.php");
    try {
        include_once("../data/connection.php");
        global $con;
        $output = "<p class='subAccoutnHead pheadzap'>Doktori</p><div class='emp scroll2yellow'>";
        $queryForEmployees = "SELECT id_employee as employeeId, name,surname,(SELECT DISTINCT COUNT(id_employee) FROM sub_accounts sa  WHERE e.id_employee=sa.id_employee AND isShown=1) as q FROM employees e WHERE id_role=1 ORDER BY q DESC";
        $resultForEmployees = $con->query($queryForEmployees)->fetchAll();
        foreach ($resultForEmployees as $rfe) {
            if ($rfe->q != "0") {

                $output .= "<div class='seeSubAccounts fconly' data-id='$rfe->employeeId'>$rfe->name $rfe->surname<span class='accountsForChart accountsForChartYellow'>$rfe->q</span></div>";
            } else {

                $output .= "<div>$rfe->name $rfe->surname<span class='accountsForChartYellow'>0</span></div>";
            }
        }
        $output .= "</div>";
        $output .= "<button class='btn switchAccount' id='seeAccountsEmployee'>Nalozi</button><button class='btn switchAccount subButton' id='seeSubAccountsEmployee'>Podnalozi</button></div>";
        echo json_encode($output);
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
