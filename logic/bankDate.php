<?php
header("Content-type: application/json");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        include_once("../data/connection.php");
        global $con;
        if (isset($_POST["startDate"]) and !empty($_POST["startDate"])) {
            $bankStartDate = date("Y-m-d", strtotime($_POST["startDate"]));
        }
        if (isset($_POST["endDate"]) and !empty($_POST["endDate"])) {
            $endDate = date("Y-m-d", strtotime($_POST["endDate"]));
        }
        +$totalIncomeTodayAccounts = $con->query("SELECT SUM(totalPrice) as priceTotal FROM accounts WHERE date BETWEEN '$bankStartDate' AND '$endDate'")->fetch()->priceTotal;
        +$totalIncomeTodaySubAccounts = $con->query("SELECT SUM(totalPrice) as priceTotal FROM sub_accounts WHERE date BETWEEN '$bankStartDate' AND '$endDate'")->fetch()->priceTotal;
        $totalIncomeToday = $totalIncomeTodayAccounts + $totalIncomeTodaySubAccounts;
        +$totalExpensesTodayAccounts = $con->query("SELECT SUM(quantityXprice) as totalExpenses FROM medicine_usage WHERE date BETWEEN '$bankStartDate' AND '$endDate'")->fetch()->totalExpenses;
        +$totalExpensesTodaySubAccounts = $con->query("SELECT SUM(quantityXprice) as totalExpenses FROM sub_accounts_medicine_usage WHERE date BETWEEN '$bankStartDate' AND '$endDate'")->fetch()->totalExpenses;
        $totalExpensesToday = $totalExpensesTodayAccounts + $totalExpensesTodaySubAccounts;
        $profitToday = $totalIncomeToday - $totalExpensesToday;
        $DATUMOD = date("d.m.Y", strtotime($bankStartDate));
        $DATUMDO = date("d.m.Y", strtotime($endDate));
        $output = "<h4>Zarada od $DATUMOD do $DATUMDO</h4>";
        $output .= "Zarada: $totalIncomeToday RSD";
        $output .= "<br>Trošak: $totalExpensesToday RSD";
        $output .= "<br>Profit: $profitToday RSD";



        echo json_encode($output);
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
