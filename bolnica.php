<?php
include_once("logic/functions.php");
startSession();
if (isset($_SESSION["id_employee"]) and in_array($_SESSION["id_role"], [1, 2, 3, 4, 5])) {
    include_once("views/head.php");
?>
    <div class="behind-nav"></div>


    <div class="itime-info fdcolumn">
        <ul>
            <h3>Niste pretplaćeni za ovu opciju...</h3>
            <h4>Vrati se nazad na<a href="index.php">Početna</a></h4>
            <h4>Vrati se nazad na "Nalozi" stranicu<a href="nalog.php?sort=4">Nalozi</a></h4>
        </ul>
    </div>





<?php
    include_once("views/scripts.php");
    include_once("views/footer.php");
} else {
    http_response_code(404);
}
?>