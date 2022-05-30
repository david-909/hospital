<?php
if (isset($_GET['printSubAccount']) and !empty($_GET['printSubAccount'])) {
    $printSubId = $_GET["printSubAccount"];
    $printfull = file_get_contents("assets/img/printfull.svg");
    $printa4 = file_get_contents("assets/img/printa4.svg");
    $printa4customer = file_get_contents("assets/img/printa4.svg");
    $printslipper = file_get_contents("assets/img/printslipper.svg");
    $deletesvg = file_get_contents("assets/img/deletebtn.svg");
    $viewsvg = file_get_contents("assets/img/viewbtn.svg");
    $editsvg = file_get_contents("assets/img/editbtn.svg");
    $printsvg = file_get_contents("assets/img/printbtn.svg");
    $duplicatesvg = file_get_contents("assets/img/duplicatebtn.svg");
    $searchico = file_get_contents("assets/img/search.svg");
    $reloadsvg = file_get_contents("assets/img/reloadbtn.svg");
?>
    <div class="modal2">
        <div class="modal2-header fb">
            <p>FORMAT ŠTAMPE</p>
            <?php
            $url = $_SERVER["REQUEST_URI"];
            $arr = explode("/", $url);
            $br = count($arr) - 1;
            $lastOne = $arr[$br];
            $filename = explode("?", $arr[$br]);
            $filename = $filename[0];
            if ($filename == "nalog.php") {
            ?>
                <button class="close-button" id='exitAccount'><?php echo $deletesvg ?></button>
            <?php
            }
            if ($filename == "adminpanel.php") {

            ?>
                <button class="close-button" id='exitAccountAp'><?php echo $deletesvg ?></button>
            <?php
            } ?>
        </div>
        <div class="modal2-body fc">
            <div class="print mauto">
                <p>MT Editable A4</p>
                <a class='buttonForPrintFullAll fc' data-id="<?php echo $printId ?>"> <?php echo $printa4 ?></a>

            </div>
            <div class="print mr10 ml10">
                <p>Slipper Format</p>
                <a class='buttonForPrintSliperAll fc' onClick ="window.print();return false" data-id="<?php echo $printId ?>"><?php echo $printslipper ?></a>

            </div>
            



        </div>
    </div>
<?php
}
