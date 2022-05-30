<?php
$navlogo = file_get_contents("assets/img/navlogo.svg"); ?>
<nav class="fc">
    <div class="logo fc br33">

        <a class="" href="index.php">HOSPITAL</a>

    </div>
    <ul class="fc">
        <li><a href="index.php" <?php
                                if (checkCurrentPage("index.php")) {
                                    echo "class='activetab'";
                                }
                                ?>>Početna</a></li>
        <li><a href="kartoni.php" <?php
                                    if (checkCurrentPage("kartoni.php")) {
                                        echo "class='activetab'";
                                    }
                                    ?>>Kartoni</a></li>

        <li class="nolink">Nalozi

            <div class="accordion4">
                <div class="accordion4-body fcolumn">

                    <div class="accordion4-item fc"><a href="nalog.php?sort=4" <?php
                                                                                if (checkCurrentPage("nalog.php?sort=4")) {
                                                                                    echo "class=''";
                                                                                }
                                                                                ?>>Ambulanta</a></div>
                    <div class="accordion4-item fc"><a href="bolnica.php" <?php
                                                                            if (checkCurrentPage("bolnica.php")) {
                                                                                echo "class=''";
                                                                            }
                                                                            ?>>Bolnica</a></div>

                </div>
            </div>




        </li>
        <?php
        if (in_array($_SESSION["id_role"], [1, 3, 4])) :
        ?>
            <li><a href="trosak.php" <?php
                                        if (checkCurrentPage("trosak.php")) {
                                            echo "class='activetab'";
                                        }
                                        ?>>Trošak</a></li>
        <?php
        endif;
        ?>
        <li><a href="cenovnik.php" <?php
                                    if (checkCurrentPage("cenovnik.php")) {
                                        echo "class='activetab'";
                                    }
                                    ?>>Cenovnici</a></li>
    </ul>
    <div id="main">
        <svg id="menu" style="cursor:pointer" onclick="openSideNav()" viewBox="0 0 500 500">
            <path d="M467.4 450.2H32.6C14.3 450.2.8 432.8 0 412.3c-.8-20.4 15.5-37.9 32.6-37.9h434.8c18.3 0 31.8 17.4 32.6 37.9s-15.6 37.9-32.6 37.9zm0-162.3H32.6C14.4 287.9.8 270.5 0 250c-.8-20.4 15.5-37.9 32.6-37.9h434.8c18.3 0 31.8 17.4 32.6 37.9.8 20.4-15.6 37.9-32.6 37.9zm0-162.3H32.6C14.3 125.6.8 108.2 0 87.7-.8 67.3 15.5 49.8 32.6 49.8h434.8c18.3 0 31.8 17.4 32.6 37.9.8 20.4-15.6 37.9-32.6 37.9z" />
        </svg>
    </div>
</nav>
<div class="space"></div>