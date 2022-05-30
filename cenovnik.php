<?php
include_once("logic/functions.php");
startSession();
if (isset($_SESSION["id_employee"])) {
    include_once("views/head.php");
    include_once("views/nav.php");
    include_once("views/acc-menu.php");
    include_once("views/sidenav.php");
    $deletesvg = file_get_contents("assets/img/deletebtn.svg");
?>
    <div class="behind-nav"></div>

    <div class="cenovnik-component fc mt20 br13" id="cenovnik">

        <div class="pricecard-analize fdcolumn cenecene">
            <button id='1' class="buttonForTables openprice">Analize</button>
            <input type="text" class='searches' id="searchAnalysis" placeholder="Pretrazite krv">
        </div>

        <div class="pricecard-pregledi fdcolumn cenecene">
            <button id='2' class='buttonForTables openprice'>Pregledi</button>
            <input type="text" class='searches' id="searchAppointments" placeholder="Pretrazite pregled">
        </div>

        <div class="pricecard-lekovi fdcolumn cenecene">
            <button id='3' class='buttonForTables openprice'>Lekovi</button>
            <input type="text" class='searches' id="searchMedicineCenovnik" placeholder="Pretrazite lek">
        </div>



    </div>


    <div class="container85">

        <div class="" id="priceCard">

        </div>

        <div class="fc mt20 gray fs13 txtc">
            Ukoliko primetite da neka određena cena nije tačna, Glavna sestra i Menadžeri mogu izmeniti cenu.
        </div>



        <div id="calculator" class="calculator mt20 section collapsible scroll2" data-collapsed="true" style="height:0px;">

            <div class="calculator-head">
                <div class="priceclear">
                    <span id='total'></span>RSD
                    <span id='clear'>Očisti</span>
                </div>

                <p>KALKULATOR</p>

                <div class="calculator-options">
                    <span id="add-account-expand-button">OTVORI</span>
                    <span class="close-button" id="add-account-collapse-button"><?php echo $deletesvg ?></span>
                </div>

            </div>






            <div class="calcInfo" id='calcInfo'></div>


        </div>
    </div>
<?php
    include_once("views/footer.php");
} else {
    http_response_code(404);
}
?>