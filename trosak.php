<?php
include_once("data/connection.php");
include_once("logic/functions.php");
global $con;
startSession();
if (isset($_GET["id"]) and !empty($_GET["id"])) {
    $id = $_GET["id"];
}
if (isset($_SESSION["id_employee"])) {
    include_once("views/head.php");
    include_once("views/nav.php");
    include_once("views/sidenav.php");
    include_once("views/acc-menu.php");
    $searchico = file_get_contents("assets/img/search.svg");
?>

    <div class="behind-nav"></div>

    <div class="container85 ptb60">

        <div class="component-1 p20 br20">
            <div class="svg-box fc mt30">
                <svg class="svg-small" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" preserveAspectRatio="xMidYMid meet">
                    <path d="M233 1c-49.4 4.8-95 23-135 53.8C78.5 69.8 53.2 98 38.6 121c-6.3 9.9-18.6 35.1-23 47.1-20.4 55.6-20.4 120.2-.2 175.4C20 355.9 32.2 381 38.6 391c5.9 9.2 16.6 23.8 22.2 30.3l4.1 4.7 1.6-5.7c7.1-25.7 18.7-49.3 34.4-69.8 6.2-8.2 23.1-25.5 31.6-32.4 14.5-11.8 32.3-22.4 50-29.6l9.2-3.9c.2-.1-2.6-2.3-6.1-4.9-59.7-43.9-65.3-130.4-11.7-181.9 9.8-9.5 17.8-15 30.6-21.3 16.3-8 32.4-11.6 51.5-11.6s35.2 3.6 51.5 11.6c12.8 6.3 20.8 11.8 30.6 21.3 53.2 51.1 48.3 136.5-10.4 180.8l-7 5.4c-.4.4 2.5 1.9 6.5 3.5 24.9 9.6 48.4 25.2 68 45.1 24.5 24.8 40.8 53.4 50.3 87.8l1.7 5.9 2.4-2.9c5.6-6.7 19.4-25.4 23.8-32.4 6.4-10 18.6-35.1 23.2-47.5 20.2-55.2 20.2-119.8-.2-175.4-4.4-12-16.7-37.2-23-47.1C453 88.9 423.1 59 391 38.6c-9.9-6.3-35.1-18.6-47.1-23-13.5-4.9-34.1-10.2-49.4-12.6C279.8.7 247-.4 233 1zm18 93.8c-47 2.6-84 41.6-84 88.5 0 25.7 9.4 47.4 28.3 65.3 34 32.4 87.4 32.4 121.4 0 18.9-17.9 28.3-39.6 28.3-65.3 0-50.8-43.2-91.4-94-88.5zm-7 209.9c-23.7 2.3-41.8 7.3-61.2 16.8-43.3 21.4-75.8 61.4-87.2 107.5-1.3 5.2-2.7 11.3-3 13.5l-1.2 6.6c-.4 2.3.6 3.3 8.8 9.6 12.3 9.3 24 16.8 36.8 23.6 88.7 46.8 194 37.7 274.8-23.6 10.6-8 9.9-5.3 6.1-24.1-11.4-55.8-54.5-103.6-110-122-20.3-6.8-44.9-9.8-63.9-7.9z" />
                </svg>
            </div>
            <div class="fcolumn2 pl20 pr20">
                <label class="label-sec mt10 mb5 fc" for='searchPatientExpense'>PRETRAŽI PACIJENTA</label>
                <div class="search-component fc">
                    <span class="fc"><?php echo $searchico ?></span>
                    <input class="search-input fc" type='text' id='searchPatientExpense' name='searchPatientExpense' placeholder="Ime, Prezime, JMBG...">
                </div>
                <div class="search-output" id='searchOutputExpense'></div>
            </div>


            <div class="addexpense fnoalign" id='addExpense'>
                <div class="fdcolumn w100">
                    <label class="label-classic mt20 mb5" for='expensesComment'>Unesite uslugu</label>
                    <div class="commentprice-div br13 fc">
                        <textarea class="scroll poppins br8 fc" name='expensesComment' id='expensesComment' placeholder="npr: Trebovanja, Majstor itd..."></textarea>
                    </div>
                </div>
                <div class="fdcolumn w100">
                    <label class="label-classic mt20 mb5 ml20" for='expensePrice'>Unesite cenu</label>
                    <input class="commentprice ml20 br33 poppins wght500 txtc fs18" type='text' name='expensePrice' id='expensePrice'>
                </div>
                <div id='errorPrice'></div>

            </div>
            <div class="txtc fc">
                <button class="btn-22 btn wght600 fs20 poppins mt40 mb40" id='insertExpense'>
                    Unesite trošak
                </button>
                
            </div>
            <button class="btn deleteexpenses" id='deleteAllExpenses'>Obriši sve troškove</button>
        </div>




    </div>
    <div class="container85 pb60">
        
            

            <div id='expensesBody'>
                <?php
                $deletesvg = file_get_contents("assets/img/deletebtn.svg");
                $query = "SELECT *,p.id_patient as patientId, e.date as datee, p.name as patientName, p.surname as patientSurname FROM patients p INNER JOIN expenses e ON p.id_patient = e.id_patient WHERE isShown = 1 ORDER BY datee DESC";
                $rowCount = $con->query($query)->rowCount();
                if ($rowCount > 0) {
                    $output = "<div class='trosakGrid-head'>
                    <div class='namesur-vah'>Ime i prezime</div>
                    <div class='options-vah'>Usluga</div>
                    <div class='dateadded-vah'>Datum</div>
                    <div class='fullprice-vah'>Cena</div>
                    </div>";
                    $result = $con->query($query)->fetchAll();
                    foreach ($result as $r) {
                        $date = date("d.m.Y. H:i:s", strtotime($r->date));
                        $output .= "<div class='trosakGrid-body'><div class='namesur-vab head-tcss wght600 fl' data-id='$r->patientId'>$r->patientName $r->patientSurname </div>";
                        $output .= "<div class='options-vab fl head-tcss'>$r->comment </div><div class='dateadded-vab head-tcss fc lastborder'>$date</div><div class='fullprice-vab head-tcss fb plr10 wght600 lastborder'>$r->price <button class='btn deleteExpense crudbtns fc ptb5' data-id='$r->id_expense'>$deletesvg</button></div></div>";
                    }
                    echo $output;
                }
                ?>
            
        </div>
    </div>






<?php
    include_once("views/footer.php");
} else {
    http_response_code(404);
}
