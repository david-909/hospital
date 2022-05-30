<?php

include_once("data/connection.php");
include_once("logic/functions.php");
global $con;
startSession();
if (isset($_SESSION["id_employee"]) and in_array($_SESSION["id_role"], [3])) {
    include_once("views/head.php");
    include_once("views/nav.php");
    include_once("views/sidenav.php");
    include_once("views/acc-menu.php");
    $minus = file_get_contents("assets/img/minus.svg");
    $addplus = file_get_contents("assets/img/addplus.svg");
    if (isset($_GET["idAccount"]) and !empty($_GET["idAccount"])) {
        include_once("views/updateAccount.php");
    }
    if (isset($_GET["viewAccount"]) and !empty($_GET["viewAccount"])) {
        include_once("views/viewAccount1.php");
    }
    if (isset($_GET["idSubAccount"]) and !empty($_GET["idSubAccount"])) {
        include_once("views/viewSubAccount.php");
    }
    if (isset($_GET["idAccountDeleted"]) and !empty($_GET["idAccountDeleted"])) {
        include_once("views/viewAccount.php");
    }
    if (isset($_GET["subAccount"]) and !empty($_GET["subAccount"])) {

        include_once("views/subAccounts.php");
    }
    include_once("views/showTherapies.php");
    include_once("views/printSubAccount.php");
?>
    <div class="behind-nav"></div>
    <div id='wrapper'>

        <div class="admin-views fc mt20">
            <button <?php $v = +$_GET["view"];
                    if ($v == 1) {
                        echo "class='activeadminview'";
                    } ?> id='bank'>Banka</button>
            <button <?php $v = +$_GET["view"];
                    if ($v == 2) {
                        echo "class='activeadminview'";
                    } ?> id='addMedicineButton'>Аpoteka</button>
            <button <?php $v = +$_GET["view"];
                    if ($v == 3) {
                        echo "class='activeadminview'";
                    } ?> id='bills'>Izmena cena</button>
            <button <?php $v = +$_GET["view"];
                    if ($v == 4) {
                        echo "class='activeadminview'";
                    } ?> id='showEmployees'>Zaposleni</button>
        </div>

        <div class="container85" id="mainDiv">

            <?php
            if (isset($_GET["view"]) and $_GET["view"] == 1) {
                // TODO OVDE IDE BANKA
                $dateStartToday = date("Y-m-d 00:00:00", time());
                $dateStartWeek = date("Y-m-d 00:00:00", time() - 604800);
                $dateStartMonth = date("Y-m-d 00:00:00", time() - 86400 * 30);
                $dateEnd = date("Y-m-d 23:59:59", time());
                try {
                    // ?TODAY
                    +$totalIncomeTodayAccounts = $con->query("SELECT SUM(totalPrice) as priceTotal FROM accounts WHERE date BETWEEN '$dateStartToday' AND '$dateEnd'")->fetch()->priceTotal;
                    +$totalIncomeTodaySubAccounts = $con->query("SELECT SUM(totalPrice) as priceTotal FROM sub_accounts WHERE date BETWEEN '$dateStartToday' AND '$dateEnd'")->fetch()->priceTotal;
                    $totalIncomeToday = $totalIncomeTodayAccounts + $totalIncomeTodaySubAccounts;
                    +$totalExpensesTodayAccounts = $con->query("SELECT SUM(quantityXprice) as totalExpenses FROM medicine_usage WHERE date BETWEEN '$dateStartToday' AND '$dateEnd'")->fetch()->totalExpenses;
                    +$totalExpensesTodaySubAccounts = $con->query("SELECT SUM(quantityXprice) as totalExpenses FROM sub_accounts_medicine_usage WHERE date BETWEEN '$dateStartToday' AND '$dateEnd'")->fetch()->totalExpenses;
                    $totalExpensesToday = $totalExpensesTodayAccounts + $totalExpensesTodaySubAccounts;
                    $profitToday = $totalIncomeToday - $totalExpensesToday;
                    // ?WEEK
                    +$totalIncomeWeekAccounts = $con->query("SELECT SUM(totalPrice) as priceTotal FROM accounts WHERE date BETWEEN '$dateStartWeek' AND '$dateEnd'")->fetch()->priceTotal;
                    +$totalIncomeWeekSubAccounts = $con->query("SELECT SUM(totalPrice) as priceTotal FROM sub_accounts WHERE date BETWEEN '$dateStartWeek' AND '$dateEnd'")->fetch()->priceTotal;
                    $totalIncomeWeek = $totalIncomeWeekAccounts + $totalIncomeWeekSubAccounts;
                    +$totalExpensesWeekAccounts = $con->query("SELECT SUM(quantityXprice) as totalExpenses FROM medicine_usage WHERE date BETWEEN '$dateStartWeek' AND '$dateEnd'")->fetch()->totalExpenses;
                    +$totalExpensesWeekSubAccounts = $con->query("SELECT SUM(quantityXprice) as totalExpenses FROM sub_accounts_medicine_usage WHERE date BETWEEN '$dateStartWeek' AND '$dateEnd'")->fetch()->totalExpenses;
                    $totalExpensesWeek = $totalExpensesWeekAccounts + $totalExpensesWeekSubAccounts;
                    $profitWeek = $totalIncomeWeek - $totalExpensesWeek;
                    // ?MONTH
                    +$totalIncomeMonthAccounts = $con->query("SELECT SUM(totalPrice) as priceTotal FROM accounts WHERE date BETWEEN '$dateStartMonth' AND '$dateEnd'")->fetch()->priceTotal;
                    +$totalIncomeMonthSubAccounts = $con->query("SELECT SUM(totalPrice) as priceTotal FROM sub_accounts WHERE date BETWEEN '$dateStartMonth' AND '$dateEnd'")->fetch()->priceTotal;
                    $totalIncomeMonth = $totalIncomeMonthAccounts + $totalIncomeMonthSubAccounts;
                    +$totalExpensesMonthAccounts = $con->query("SELECT SUM(quantityXprice) as totalExpenses FROM medicine_usage WHERE date BETWEEN '$dateStartMonth' AND '$dateEnd'")->fetch()->totalExpenses;
                    +$totalExpensesMonthSubAccounts = $con->query("SELECT SUM(quantityXprice) as totalExpenses FROM sub_accounts_medicine_usage WHERE date BETWEEN '$dateStartMonth' AND '$dateEnd'")->fetch()->totalExpenses;
                    $totalExpensesMonth = $totalExpensesMonthAccounts + $totalExpensesMonthSubAccounts;
                    $profitMonth = $totalIncomeMonth - $totalExpensesMonth;
                    // !KOD PROFITA FALE I RACUNI I TROSKOVI LEKOVA PREMA FIRMI 


                    $output = "<p class='wght800 txtc fs24 gray mt20'>BANKA</p>";
                    $output .= "<div class='mt10 datebankalign fc' id='bankDiv'><div class='datebankalign2'><div class='bankcolumn'><label class='label-classic' for='bankDateStart'>Datum od:</label><input class='input-sec input-date poppins mr10' type='date' value='' id='bankDateStart'></div><div class='bankcolumn'><label class='label-classic' for='bankDateEnd'>Datum do:</label><input class='input-sec input-date poppins' type='date' value='' id='bankDateEnd'></div><button class='fc ml10' id='showIncomeButton'>Prikaži</button></div></div><div class='fdcolumn mt20' id='bankDateDiv'></div></div>";

                    $output .= "<div class='charts-admin'>";
                    $output .= "<div class='fdcolumnright2'><div class='chartweek fdcolumn'><p>NEDELJNA ZARADA</p><canvas id='myChart'></canvas></div>";
                    $output .= "<div class='chartday fdcolumn'><p>DNEVNA ZARADA</p><canvas id='myChart2'></canvas></div></div><div class='chartmonth fdcolumn2'><p>MESEČNA ZARADA</p><canvas id='myChart3'></canvas></div></div>";


                    echo $output;
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    http_response_code(500);
                }
            }
            if (isset($_GET["view"]) and $_GET["view"] == 2) {
                $limit = 30;
                if (!isset($_GET['page'])) {
                    $page = 1;
                } else {
                    $page = $_GET['page'];
                }
                try {
                    $output = "<div class='medicineGrid-head container85'>
                    <div class='medicine-vah'>Lek</div>
                    <div class='quantity-vah fc'>Trenutno stanje</div>
                    <div class='updatequantity-vah'>Izmeni</div>
                </div>";
                    $query = "SELECT * FROM medicines";
                    $rowCount = $con->query($query)->rowCount();
                    $pageCount = ceil($rowCount / $limit);
                    $start = ($page - 1) * $limit;
                    $query = "SELECT * FROM medicines ORDER BY CAST(medicine as unsigned) ASC LIMIT $start,$limit ";
                    $result = $con->query($query)->fetchAll();
                    foreach ($result as $r) {
                        $output .= "<div class='medicineGrid-body container85'><div class='medicine-vab wght500 gray fl'>$r->medicine</div><div class='quantity-vab fc wght600 gray'>$r->quantity</div><div class='updatequantity-vab fc'><div class='number-input'>
                        <button class='plus'></button><button class='minus'></button>
                        <input class='quantity' min='0' name='quantity' value='0' type='number'></div><button data-id='$r->id_medicine' class='quantityToAdd fend'>$addplus</button>&nbsp;<button data-id='$r->id_medicine' class='quantityToSubtract fc'>$minus</button></div></div>";
                    }
                    $output .= "<div class='paginationFront scroll2'><ul class='pagination fc mb30'>";
                    for ($i = 1; $i <= $pageCount; $i++) {
                        $class = $page == $i ? 'btn-active' : "";
                        $output .= "<li class='" . $class . "'><a class='btnPagePharmacy' href='adminpanel.php?view=2&page=" . $i . "'>" . $i . "</a></li>";
                    }
                    $output .= "</ul></div>";
                    echo $output;
                } catch (PDOException $e) {
                    echo $e->getMessage();
                    http_response_code(500);
                }
            }
            if (isset($_GET["view"]) and $_GET["view"] == 3) {
                // TODO ovde ide za update cena
            ?>

                <div id="cenovnik">
                    <div class="cenovnik-component fc mt20 br13" id="cenovnik">
                        <div class="pricecard-analize fdcolumn cenecene">
                            <button id="1" class="buttonForTables openprice">Analize</button>
                            <input type="text" class="blured searches" id="searchAnalysis" placeholder="Pretrazite krv">
                        </div>
                        <div class="pricecard-pregledi fdcolumn cenecene">
                            <button id="2" class="buttonForTables openprice">Pregledi</button>
                            <input type="text" class="blured searches" id="searchAppointments" placeholder="Pretrazite pregled">
                        </div>
                        <div class="pricecard-lekovi fdcolumn cenecene">
                            <button id="3" class="buttonForTables openprice">Lekovi</button>
                            <input type="text" class="blured searches" id="searchMedicineCenovnik" placeholder="Pretrazite lek">
                        </div>
                    </div>


                </div>

                <div id="priceCard">
                </div><?php
                    }
                    if (isset($_GET["view"]) and $_GET["view"] == 4) {
                        try {




                            $output = "<p class='txtc wght800 fs30 pt50 gray smaller'>IZDATI I ČEKIRANI NALOZI</p><div class='account-statistic pt20'><div class='outputDoctors' id='outputAccounts'>";
                            //! OVO JE ZA NALOGE
                            $output .= "<p class='pheadzap'>Doktori</p><div class='emp scroll2'>";
                            $queryForEmployees = "SELECT id_employee as employeeId, name,surname,(SELECT DISTINCT COUNT(id_employee) FROM accounts a  WHERE e.id_employee=a.id_employee AND isShown=1) as q FROM employees e WHERE id_role=1 ORDER BY q DESC";
                            $resultForEmployees = $con->query($queryForEmployees)->fetchAll();
                            foreach ($resultForEmployees as $rfe) {
                                if ($rfe->q != "0") {
                                    $output .= "<div class='seeAccounts fconly' data-id='$rfe->employeeId'>$rfe->name $rfe->surname<span class='accountsForChart'>$rfe->q</span></div>";
                                } else {

                                    $output .= "<div>$rfe->name $rfe->surname<span>0</span></div>";
                                }
                            }
                            $output .= "</div>";
                            $output .= "<button class='btn switchAccount' id='seeAccountsEmployee'>Nalozi</button><button class='btn switchAccount' id='seeSubAccountsEmployee'>Podnalozi</button></div>";

                            //! OVO JE ZA PODNALOGE
                            /* $queryForEmployeesSubAccount = "SELECT id_employee as employeeId, name,surname,(SELECT DISTINCT COUNT(id_employee) FROM sub_accounts sa  WHERE e.id_employee=sa.id_employee AND isShown=1) as q FROM employees e WHERE id_role=1 ORDER BY q DESC";
                    $rez = $con->query($queryForEmployeesSubAccount)->fetchAll();
                    
                    foreach ($rez as $rfe) {
                        if ($rfe->q != "0") {

                            $output .= "<div class='seeSubAccounts fconly' data-id='$rfe->employeeId'>$rfe->name $rfe->surname -  Broj dodatih podnaloga: <div class='accountsForChart'>$rfe->q</div></div>";
                        } else {

                            $output .= "<div>$rfe->name $rfe->surname -  Nema izdatih naloga</div>";
                        }
                    }
                    $output .= "</div>"; */



                            $output .= "<div class='outputTehnicians'><p class='pheadzap'>Tehničari</p><div class='emp scroll2'>";
                            $correctQuery = "SELECT * FROM checked c INNER JOIN accounts a ON c.id_account = a.id_account WHERE c.id_employee=19 AND isShown =1";
                            $queryForEmployees = "SELECT *,e.id_employee as employeeId,name,surname,(SELECT DISTINCT COUNT(id_employee) FROM checked c WHERE e.id_employee=c.id_employee) as q FROM employees e WHERE e.id_role=2 OR e.id_role=5";
                            $resultForEmployees = $con->query($queryForEmployees)->fetchAll();
                            foreach ($resultForEmployees as $rfe) {

                                if ($rfe->q != "0") {

                                    $output .= "<div class='seeChecked' data-id='$rfe->employeeId'>$rfe->name $rfe->surname<span class='accountsForChart'>$rfe->q</span></div>";
                                } else {

                                    $output .= "<div>$rfe->name $rfe->surname<span>0</span></div>";
                                }
                            }
                            $output .= "</div></div>";

                            $output .= "<div class='outputManagement'><p class='pheadzap'>Menadžeri</p><div class='emp scroll2'>";
                            $queryForEmployees = "SELECT *,id_employee as employeeId, name,surname,(SELECT DISTINCT COUNT(id_employee) FROM accounts a  WHERE e.id_employee=a.id_employee AND isShown=1) as q FROM employees e WHERE id_role=3";
                            $resultForEmployees = $con->query($queryForEmployees)->fetchAll();
                            foreach ($resultForEmployees as $rfe) {
                                if ($rfe->q != "0") {

                                    $output .= "<div class='seeAccounts' data-id='$rfe->employeeId'>$rfe->name $rfe->surname<span class='accountsForChart'>$rfe->q</span></div>";
                                } else {

                                    $output .= "<div>$rfe->name $rfe->surname<span>0</span></div>";
                                }
                            }
                            $output .= "</div></div>";

                            echo $output;
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                            http_response_code(500);
                        }
                    }
                    if (isset($_GET["view"]) and $_GET["view"] == 5) {

                        echo "<h1>Obrisani nalozi</h1>";
                        echo "<button class='btn' id='deletedAccounts'>Nalozi</button>";
                        echo "<button class='btn' id='deletedSubAccounts'>Podnalozi</button>";
                        echo "<div id='deletedContent'>";
                        include_once "views/showDeletedAccounts.php";
                        echo "</div>";
                    }
                    include_once("views/printAccountAll.php");
                        ?>

        </div>

        <div id="employeeAccounts" class='employeeAccounts'></div>
    </div>


<?php
    include_once("views/scripts.php");
    include_once("views/footer.php");
    include_once("views/chart.php");
} else {
    http_response_code(404);
}
