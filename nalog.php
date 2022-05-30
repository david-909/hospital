<?php
include_once("logic/functions.php");
startSession();
if (isset($_SESSION["id_employee"]) and in_array($_SESSION["id_role"], [1, 2, 3, 4, 5])) {
    include_once("views/head.php");
    include_once("views/nav.php");
    include_once("views/sidenav.php");
    include_once("views/acc-menu.php");
    $minus = file_get_contents("assets/img/minus.svg");
    $deletesvg = file_get_contents("assets/img/deletebtn.svg");
    $viewsvg = file_get_contents("assets/img/viewbtn.svg");
    $editsvg = file_get_contents("assets/img/editbtn.svg");
    $printsvg = file_get_contents("assets/img/printbtn.svg");
    $duplicatesvg = file_get_contents("assets/img/duplicatebtn.svg");
    $searchico = file_get_contents("assets/img/search.svg");
    $reloadsvg = file_get_contents("assets/img/reloadbtn.svg");
?>
    <div class="behind-nav"></div>
    <?php
    if (in_array($_SESSION["id_role"], [1, 2, 3, 4,5])) :
        include_once("views/showTherapies.php");
    ?>
        <div class="container100 responsiveh">
            <div id="drop-nalog" class="add-nalog section collapsible" data-collapsed="true" style="height: 0px;">
                <span class="close-button" id="add-account-collapse-button"><?php echo $deletesvg ?></span>
                <form id="nalog">

                    <div class="">

                        <div class="rcard-white brt0 bxs0 modal-s">

                            <div class="svg-box fc">
                                <svg class="svg-small" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" preserveAspectRatio="xMidYMid meet">
                                    <path d="M233 1c-49.4 4.8-95 23-135 53.8C78.5 69.8 53.2 98 38.6 121c-6.3 9.9-18.6 35.1-23 47.1-20.4 55.6-20.4 120.2-.2 175.4C20 355.9 32.2 381 38.6 391c5.9 9.2 16.6 23.8 22.2 30.3l4.1 4.7 1.6-5.7c7.1-25.7 18.7-49.3 34.4-69.8 6.2-8.2 23.1-25.5 31.6-32.4 14.5-11.8 32.3-22.4 50-29.6l9.2-3.9c.2-.1-2.6-2.3-6.1-4.9-59.7-43.9-65.3-130.4-11.7-181.9 9.8-9.5 17.8-15 30.6-21.3 16.3-8 32.4-11.6 51.5-11.6s35.2 3.6 51.5 11.6c12.8 6.3 20.8 11.8 30.6 21.3 53.2 51.1 48.3 136.5-10.4 180.8l-7 5.4c-.4.4 2.5 1.9 6.5 3.5 24.9 9.6 48.4 25.2 68 45.1 24.5 24.8 40.8 53.4 50.3 87.8l1.7 5.9 2.4-2.9c5.6-6.7 19.4-25.4 23.8-32.4 6.4-10 18.6-35.1 23.2-47.5 20.2-55.2 20.2-119.8-.2-175.4-4.4-12-16.7-37.2-23-47.1C453 88.9 423.1 59 391 38.6c-9.9-6.3-35.1-18.6-47.1-23-13.5-4.9-34.1-10.2-49.4-12.6C279.8.7 247-.4 233 1zm18 93.8c-47 2.6-84 41.6-84 88.5 0 25.7 9.4 47.4 28.3 65.3 34 32.4 87.4 32.4 121.4 0 18.9-17.9 28.3-39.6 28.3-65.3 0-50.8-43.2-91.4-94-88.5zm-7 209.9c-23.7 2.3-41.8 7.3-61.2 16.8-43.3 21.4-75.8 61.4-87.2 107.5-1.3 5.2-2.7 11.3-3 13.5l-1.2 6.6c-.4 2.3.6 3.3 8.8 9.6 12.3 9.3 24 16.8 36.8 23.6 88.7 46.8 194 37.7 274.8-23.6 10.6-8 9.9-5.3 6.1-24.1-11.4-55.8-54.5-103.6-110-122-20.3-6.8-44.9-9.8-63.9-7.9z" />
                                </svg>
                            </div>

                            <div class="fdcolumn2">
                                <label class="label-h1" for="searchNameSurname">PRETRAŽI PACIJENTA</label>

                                <div class="search-component fc">
                                    <span class="fc"><?php echo $searchico ?></span>
                                    <input class="search-input fc" type="text" id="searchNameSurname" name="searchNameSurname" placeholder="Ime, Prezime, JMBG...">
                                    <input type="hidden" id="id_employee" value='<?php echo $_SESSION["id_employee"]; ?>'>
                                </div>
                                <div class="search-output search-output3 scroll mb10" id="searchOutput"></div>


                            </div>
                            <?php 
                            $class = $_SESSION["id_role"] == "1" ? "doctorHide" : "";
                            ?>
                            <div class="select-doctor custom-select fdcolumn2 <?php echo $class ?>">
                                <?php
                                if (in_array($_SESSION["id_role"], [1,2,3,4,5])) {
                                        try {
                                                
                                                $var = $_SESSION["id_role"] == "1" ? "selected" : "";
                                           
                                                include_once("data/connection.php");
                                                global $con;
                                                $output = "<label class='label-h1 fc' for='chooseDoctor'>Doktor</label><select id='chooseDoctor' name='chooseDoctor'><option >Izaberi doktora</option>";
                                                $queryDr = "SELECT * FROM employees WHERE id_role = 1"; // SAMO DOKTORI 
                                                $resultDoctors = $con->query($queryDr)->fetchAll();
                                                foreach ($resultDoctors as $rd) {
                                                    $output .= "<option value='$rd->id_employee' $var>$rd->name $rd->surname</option>";
                                                }
                                                echo $output .= "</select>";
                                            
                                            
                                        } catch (PDOException $e) {
                                            echo $e->getMessage();
                                        }
                                    
                                }
                                ?>
                            </div>



                            <div class="build-nalog-body p20 ptb0">

                                <div class="pregled-krv fnoalign modal-w">



                                    <div class="accordion1 js-accordion3 mr10 mt10">

                                        <div class="accordion1__item js-accordion3-item">
                                            <div class="accordion1-header js-accordion3-header fb">
                                                <span>PREGLEDI LEKARA</span>
                                                <div class="price">
                                                    <span>Cena</span>
                                                    <span id='totalPriceAppointments'>0</span>&nbsp;RSD
                                                </div>

                                            </div>
                                            <div class="accordion1-body js-accordion3-body brbl16 brbr16">
                                                <div class="accordion1-body__contents ">



                                                    <div class="">
                                                        <div class="">

                                                        </div>
                                                        <div class="">
                                                            <div class="fc">
                                                                <select class="select-list1 appointment-list pr10 scroll2" name="appointment" id="appointment" multiple>
                                                                    <?php
                                                                    try {
                                                                        include_once("data/connection.php");
                                                                        global $con;
                                                                        $query = "SELECT * FROM appointments";
                                                                        $result = $con->query($query)->fetchAll();
                                                                        if ($result) {
                                                                            foreach ($result as $r) {
                                                                                echo ("<option class='chosenAppointment select-list1-item' data-id='$r->id_appointment' data-price='$r->price'>$r->appointment</option>");
                                                                            }
                                                                        }
                                                                    } catch (PDOException $e) {
                                                                        http_response_code(500);
                                                                        echo $e->getMessage();
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <select class="select-list1 removeapp-list pr10" name="finalAppointments" id="finalAppointments" multiple></select>
                                                            </div>
                                                            <div class="fc mauto">
                                                                <button class="select-list1-btn s-l1-btn1 btn brbl16" id="moveToFinalAppointments">+</button>
                                                                <button class="select-list1-btn s-l1-btn2 btn brbr16" id="moveFromFinalAppointments">-</button>
                                                            </div>
                                                        </div>
                                                    </div>






                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="accordion1 js-accordion3 ml10 mt10">
                                        <div class="accordion1__item js-accordion3-item">
                                            <div class="accordion1-header js-accordion3-header fb">
                                                <span>KRV</span>

                                                <div class="price">
                                                    <span>Cena</span>
                                                    <span id='totalPriceBloods'>0</span>&nbsp;RSD
                                                </div>
                                            </div>
                                            <div class="accordion1-body js-accordion3-body brbl16 brbr16">
                                                <div class="accordion1-body__contents">
                                                    <div class="">
                                                        <div class="fdcolumn mauto">
                                                            <input class="search-input txtc br0 bxs0 fs13 pt5 pb5" type="text" id="searchBlood" placeholder="Pretrazite krv...">
                                                            <div id="searchBloodResult"></div>
                                                        </div>

                                                        <div class="">
                                                            <div class="fc">
                                                                <select class="select-list1 scroll2 pr10" name="blood" id="blood" multiple>
                                                                    <?php
                                                                    try {
                                                                        include_once("data/connection.php");
                                                                        global $con;
                                                                        $query = "SELECT * FROM bloods LIMIT 100";
                                                                        $result = $con->query($query)->fetchAll();
                                                                        if ($result) {
                                                                            foreach ($result as $r) {
                                                                                echo ("<option class='chosenBlood select-list1-item' data-id='$r->id_blood' data-price='$r->price'>$r->analysis</option>");
                                                                            }
                                                                        }
                                                                    } catch (PDOException $e) {
                                                                        http_response_code(500);
                                                                        echo $e->getMessage();
                                                                    }
                                                                    ?>
                                                                </select>
                                                                <select class="select-list1 scroll2 pl10" name="finalBloods" id="finalBloods" multiple></select>
                                                            </div>
                                                            <div class="fc mauto">
                                                                <button class="select-list1-btn s-l1-btn1 btn brbl16" id="moveToFinalBloods">+</button>
                                                                <button class="select-list1-btn s-l1-btn2 btn brbr16" id="moveFromFinalBloods">-</button>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <div class="accordion1 js-accordion3 mt10 modal-w">
                                    <div class="accordion1__item js-accordion3-item">
                                        <div class="accordion1-header js-accordion3-header fb">
                                            <span>NAPRAVI TERAPIJU</span>

                                            <div class="price priceTherapy">
                                                <span>Cena</span>
                                                <span id='totalPriceMedicines'>0</span>&nbsp;RSD
                                            </div>
                                        </div>
                                        <div class="accordion1-body js-accordion3-body brbl16 brbr16">
                                            <div class="accordion1-body__contents">
                                                <div class="">
                                                    <div class="therapybox" name="therapybox">



                                                        <div class="make-therapy">
                                                            <div class="therapyCustomSize">
                                                                <div class="therapiesResponsive fc">
                                                                    <p class="label-h1 fc mr10 fs16">Profili kostura</p>

                                                                    <div class="fc therapiesButtons" id="therapies">

                                                                        <?php
                                                                        try {
                                                                            include_once("data/connection.php");
                                                                            global $con;
                                                                            $query = "SELECT * FROM therapies WHERE id_therapy_type = 1";
                                                                            $result = $con->query($query)->fetchAll();
                                                                            if ($result) {
                                                                                foreach ($result as $r) {
                                                                                    echo ("<button class='chosenTherapy btn' data-id='$r->id_therapy'>$r->therapy</button>");
                                                                                }
                                                                            }
                                                                        } catch (PDOException $e) {
                                                                            http_response_code(500);
                                                                            echo $e->getMessage();
                                                                        }
                                                                        ?>
                                                                        <button class='chosenTherapy btn' id="customTherapy">Napravi terapiju</button>
                                                                    </div><span class="fc addRemoveButton" id="addRemoveButton"></span>
                                                                </div>

                                                                <div class="">


                                                                    <div class="fc">

                                                                        <div class="mt10" id="medicines">

                                                                        </div>

                                                                    </div>


                                                                    <div class="fc">
                                                                        <div class="" id="topMedicines"></div>
                                                                    </div>





                                                                    <div class="" id="completedTherapy">
                                                                    </div>
                                                                </div>

                                                                <label class="fc wght600 gray mt15" for="commentAccount">Komentar</label>
                                                                <textarea class="commentTherpay fc mb15 scroll2" name="commentAccount" id="commentAccount" cols="30" rows="10" placeholder="Unesite vaš komentar... (Zaposleni)"></textarea>


                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="accordion1 js-accordion3 mt10 modal-w ">
                                    <div class="accordion1__item js-accordion3-item">
                                        <div class="accordion1-header js-accordion3-header fb drizacc">
                                            <span>LEKARSKI IZVEŠTAJ</span>
                                        </div>
                                        <div class="accordion1-body js-accordion3-body brbl16 brbr16">
                                            <div class="accordion1-body__contents">
                                                <div class="">
                                                    <div class="therapybox" name="therapybox">



                                                        <div class="make-therapy">
                                                            <div class="therapyCustomSize driz">
                                                                <div class="fnoalign drflex">
                                                                    <div class="">
                                                                        <label class="txtl wght600 gray mt15" for="anamneza">Anamneza</label>
                                                                        <textarea class="commentTherpay fc mb15 scroll2" name="anamneza" id="anamneza" cols="30" rows="10" placeholder="Anamneza... (Pacijent/Zaposleni)"></textarea>
                                                                    </div>
                                                                    <div class="">
                                                                        <label class="txtl wght600 gray mt15" for="nalaz">Nalaz</label>
                                                                        <textarea class="commentTherpay fc mb15 scroll2" name="nalaz" id="nalaz" cols="30" rows="10" placeholder="Nalaz... (Pacijent/Zaposleni)"></textarea>
                                                                    </div>

                                                                </div>
                                                                <div class="fnoalign drflex">
                                                                    <div class="">
                                                                        <label class="txtl wght600 gray mt15" for="uznalaz">UZ Nalaz</label>
                                                                        <textarea class="commentTherpay fc mb15 scroll2" name="uznalaz" id="uznalaz" cols="30" rows="10" placeholder="UZ Nalaz... (Pacijent/Zaposleni)"></textarea>
                                                                    </div>
                                                                    <div class="">
                                                                        <label class="txtl wght600 gray mt15" for="terapija">Terapija</label>
                                                                        <textarea class="commentTherpay fc mb15 scroll2" name="terapija" id="terapija" cols="30" rows="10" placeholder="Terapija... (Pacijent/Zaposleni)"></textarea>
                                                                    </div>
                                                                </div>

                                                                <label class="txtl wght600 gray mt15" for="zakljucak">Zaključak</label>
                                                                <textarea class="commentTherpay fc mb15 scroll2 zakljucak" name="zakljucak" id="zakljucak" cols="30" rows="10" placeholder="Zaključak... (Pacijent/Zaposleni)"></textarea>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="price-output mt20">
                            <div class="fs19 wght600 fc">
                                <span>Ukupna cena naloga: </span><span id='totalPriceAccount'>0</span><span>&nbsp;RSD</span>
                            </div>

                        </div>

                        <button class="final-btn btn fs19 wght600 txtc fc" id='finalButton'>DODAJ NALOG</button>
                    </div>

            </div>

            </form>

        </div>
    <?php
    endif;
    include_once("views/viewAccount.php");
    include_once("views/printAccount.php");
    include_once("views/printAccountAll.php");
    include_once("views/printSubAccount.php");
    include_once("views/subAccounts.php");
    include_once("views/idSubAccount.php");
    ?>
    <div class="louis"></div>
    <div class="container85 nalog section scale" id="nalog-window">
        <div class="nalog-head fc">
            <div class="fstart search-patient">
                <div class="lnalog-head fc">
                    <p class="txtl" id="doctor5">NALOZI
                    <div class="sortNalog fconly">
                        <?php
                        if (isset($_GET["sort"]) and !empty($_GET["sort"])) {
                            $sort = +$_GET["sort"];
                        } else {
                            $sort = 0;
                        }
                        ?>
                        <div class="select-doctor5 fc">

                            <select class="fc" id="sortNalog">
                                <option <?php if ($sort == 1) echo "selected='selected'" ?> value="1">Ime &#8593;</option>
                                <option <?php if ($sort == 2) echo "selected='selected'" ?> value="2">Ime &#8595;</option>
                                <option <?php if ($sort == 3) echo "selected='selected'" ?> value="3">Datum &#8593;</option>
                                <option <?php if ($sort == 4) echo "selected='selected'" ?> value="4">Datum &#8595;</option>
                                <option <?php if ($sort == 5) echo "selected='selected'" ?> value="5">Cena &#8593;</option>
                                <option <?php if ($sort == 6) echo "selected='selected'" ?> value="6">Cena &#8595;</option>
                            </select>

                        </div>

                    </div>
                    </p>

                </div>
                <div class="search-component sc3 fc ml20 bxs0">
                    <span class="fc"><svg id="search" xmlns="http://www.w3.org/2000/svg" width="682.667" height="682.667" viewBox="0 0 512 512" preserveAspectRatio="xMidYMid meet">
                            <path d="M289 .6c-40.4 3-82 19.2-114 44.5-14.9 11.7-34.4 33.2-44.8 49.4-32.3 49.9-41.3 107.7-25.8 165.9 3.3 12.5 9.4 27.7 16.7 41.4 3.5 6.8 5.9 12.6 5.9 14.5 0 2.8-6 9.2-56.8 60.2-47.2 47.2-57.6 58.2-61.1 64.1-8.2 14-10.7 26.5-7.7 38.3 2 7.7 4.4 11.9 10.9 18.8 15.9 17 36.1 18.7 59.1 5.2 5.9-3.5 16.9-13.9 64.1-61.1 51-50.8 57.4-56.8 60.2-56.8 1.9 0 7.7 2.4 14.5 5.9 29 15.3 56.6 22.7 88.3 23.8 44 1.5 82.3-9.1 119-32.9 16.2-10.4 37.7-29.9 49.4-44.8 23-29.1 37.6-63.7 43.2-102.5 1.9-12.7 1.6-44.3-.5-57.4-6.2-39.3-21.6-73.5-46.4-103.1C431.1 35.5 385.4 10.1 335 2.5 324.6.9 299-.1 289 .6zm40.5 55.9c23 3.6 46.3 13.4 65.7 27.6 10.5 7.8 26.6 24.1 34.2 34.7 38.3 53.8 37.3 128.2-2.4 181-30 39.9-79.3 63.2-128.4 60.9-40.2-1.9-74.8-17-102.6-44.7-60.3-60.4-59.9-157.9 1.1-217.7 35.3-34.6 82.6-49.5 132.4-41.8zm-30.8 27.6c-46.3 5.1-86.6 34-107.1 76.9-5.5 11.4-4.8 20.5 2.2 27.6 6.2 6.2 16.9 8.2 24 4.4 6.2-3.3 7.8-5.1 13.7-15.9 7.1-13.1 12.8-20.5 21.5-28 26.7-23 61.4-28.9 95.3-16.3 9.4 3.5 17 2.5 23.4-3.1 8.8-7.7 9.8-22.2 2-30.5-4.5-4.8-19.4-10.5-35.4-13.6-10.6-2-28.7-2.7-39.6-1.5z"></path>
                        </svg></span>
                    <input class="search-input fc" type="text" id="searchAccounts" list="lista" name="searchNameSurnameCardboard" placeholder="Ime, prezime, jmbg">
                </div>
            </div>




            <div class="filter-addnalog fend">

                <div class="filterNalog fconly">
                    <label class="label-classic" for="dateStart">Datum od:</label>
                    <input class="input-sec input-date poppins mr10" <?php if (isset($_GET["dateStart"]) and !empty($_GET["dateStart"])) {
                                                                            $dateS = $_GET["dateStart"];
                                                                            echo "value='$dateS'";
                                                                        } else {
                                                                            echo "value=''";
                                                                        }; ?> type="date" id="dateStart" name="dateStart">
                    <label class="label-classic" for="dateEnd">Datum do:</label>
                    <input class="input-sec input-date poppins" type="date" <?php if (isset($_GET["dateEnd"]) and !empty($_GET["dateEnd"])) {
                                                                                $dateE = $_GET["dateEnd"];
                                                                                echo "value='$dateE'";
                                                                            } else {
                                                                                echo "value=''";
                                                                            }; ?> id="dateEnd" name="dateEnd">

                    <button class="crudbtns" id='resetButton'><?php echo $reloadsvg ?></button>

                </div>




                <?php
                if (in_array($_SESSION["id_role"], [1, 3, 4, 5])) :
                ?>
                    <div class="rnalog-head fend">
                        <span id="add-account-expand-button">DODAJ NALOG</span>
                    </div>

                <?php endif; ?>
            </div>

        </div>

        <div class="nalogGrid-head">
            <div class="no-vah wght500">#</div>
            <div class="namesur-vah wght500">Ime i Prezime</div>
            <div class="doctor-vah wght500">Doktor</div>
            <div class="dateadded-vah wght500">Datum</div>
            <div class="fullprice-vah wght500">Ukupno</div>
            <div class="options-vah wght500">Opcije</div>
            <div class="checked-vah wght500">Čekirao</div>
        </div>
        <div id='showAccountsDiv'>
            <?php
            if (isset($_GET["input"]) and !empty($_GET["input"])) {
                include_once("views/showAccountsSearch.php");
            } else {
                $input = "";
                include_once("views/showAccounts.php");
            }
            ?>

        </div>
    </div>
    </div>
<?php
    include_once("views/scripts.php");
    include_once("views/footer.php");
} else {
    http_response_code(404);
}
?>