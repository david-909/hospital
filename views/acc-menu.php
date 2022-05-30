<div class="account" id="account">
    <div class="profile">
    <svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="682.667" height="682.667" viewBox="0 0 512 512"><path d="M238 .6c-1.9.2-7.8.9-13 1.5-43 4.8-87.2 22.7-123.8 50.1-11.9 8.9-33.9 30.2-43.7 42.3-23.7 29.2-43 68.5-50.9 104-4.9 21.8-6 32.5-6 57.5s1.1 35.7 6 57.5c7.9 35.5 27.2 74.9 50.9 104 9.2 11.4 27.4 29.4 38.7 38.4 28.7 22.9 67.5 41.7 102.3 49.5 20.9 4.7 33.4 6 57.5 6 42.5 0 75.1-7.5 112.5-25.9 41.7-20.4 75-49.6 101-88.5 15.3-22.8 30-56.9 35.9-83.5 4.9-21.8 6-32.5 6-57.5s-1.1-35.7-6-57.5c-7.9-35.5-27.2-74.9-50.9-104-9.2-11.4-27.4-29.4-38.7-38.4-34.8-27.8-77.7-46.5-122-53.2C281.2 1 246.7-.4 238 .6zm35.5 77.9c26.9 5.7 49.2 24.1 60.3 49.7 4.6 10.8 6.4 20.3 6.4 33.8-.1 23.6-8.8 43.7-26.1 60.2-9.2 8.9-19.1 15-30.4 18.9-51.5 17.7-106.1-17.1-111.7-71.3-4.4-42.7 24.2-82.2 66.1-91.3 8.5-1.8 26.7-1.8 35.4 0zm53 211.3c25.9 7.2 45.4 23.5 56.4 47.4 10 21.5 10.5 42.3 1.4 55.6-7.5 10.9-34.2 28.7-57.8 38.5-42.2 17.4-95.5 17.8-138.5 1-23.5-9.2-54-29.1-60.7-39.6-7.8-12.3-8.2-28.4-1.4-48.4 10.1-29.4 37.1-51.5 68.6-56.2 3.3-.5 32.6-.8 65-.7 57.6.2 59.2.2 67 2.4z"/></svg>
    </div>

    <div class="acc-menu pb20 br15">
        <div class="acc-info pt20 pl20">
            <input type="hidden" id="id_employee" value="<?php echo $_SESSION['id_employee']; ?>">
            <li><?php echo $_SESSION["name"];
                echo " ";
                echo $_SESSION["surname"]; ?></li>
            <li><?php if (isset($_SESSION["specialization"]) and !empty($_SESSION["specialization"])) echo $_SESSION["specialization"]; ?></li>
        </div>

        <?php
        if (isset($_SESSION["id_role"]) and $_SESSION["id_role"] == "3") {


        ?>
            <div class="adminpanel">
                <a class="adminp fc" href="adminpanel.php?view=1">ADMIN PANEL</a>
                <ul>
                    <li><a class="fc" href="adminpanel.php?view=1">Banka</a></li>
                    <li><a class="fc" href='adminpanel.php?view=2'>Apoteka</a></li>

                </ul>
                <ul>
                    <li><a class="leftpanel fc" href="adminpanel.php?view=3">Izmena cena</a></li>
                    <li><a class="rightpanel fc" href="adminpanel.php?view=4">Zaposleni</a></li>
                </ul>
            </div>
        <?php
        } ?>

        <div class="accordion3 js-accordion3 br13">
            <div class="accordion3__item js-accordion3-item">
                <div class="accordion3-header js-accordion3-header br13">Promeni šifru</div>
                <div class="accordion3-body js-accordion3-body ptb10">
                    <div class="accordion3-body__contents">

                        <div class="changepassword-front">
                            <form id="changePassword">
                                <li>Trenutna šifra</li>
                                <input class="input-secondary input-sec2 fc" type="password" autocomplete="off" id="passwordChangeNow" placeholder="Unesite vasu šifru...">
                                <li>Nova lozinka</li>
                                <input class="input-secondary input-sec2 fc" type="password" autocomplete="off" id="passwordChange" placeholder="Unesite novu šifru...">
                                <li>Ponovite novu lozinku</li>
                                <input class="input-secondary input-sec2 fc" type="password" autocomplete="off" id="passwordChangeConf" placeholder="Ponovite novu šifru...">

                            </form>

                        </div>

                    </div>
                    <button class="changepass fc mt10 wght500" id="passwordChangeButton">Sačuvaj</button>
                </div><!-- end of accordion3 body -->
            </div><!-- end of accordion3 item -->
        </div>
        <?php
        if (isset($_SESSION["id_employee"])) {
        ?>
            <div class="logout mt10">
                <button id="logoutButton">Izloguj se</button>
            </div>


            <?php
            if (in_array($_SESSION["id_role"], [3])) :
            ?>


            <?php
            endif;
            ?>
            </ul>
    </div>
<?php
        }
?>
</div>
</div>