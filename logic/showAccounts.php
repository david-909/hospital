<?php
header("Content-type: application/json");
include_once("functions.php");
startSession();
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $dateUnix = new DateTime('1970-01-01');
    $dateUnix = $dateUnix->format("Y-m-d");
    $deletesvg = file_get_contents("../assets/img/deletebtn.svg");
    $viewsvg = file_get_contents("../assets/img/viewbtn.svg");
    $editsvg = file_get_contents("../assets/img/editbtn.svg");
    $printsvg = file_get_contents("../assets/img/printbtn.svg");
    $duplicatesvg = file_get_contents("../assets/img/duplicatebtn.svg");
    $searchico = file_get_contents("../assets/img/search.svg");
    $reloadsvg = file_get_contents("../assets/img/reloadbtn.svg");
    try {
        if (isset($_GET["idAccount"]) and !empty($_GET["idAccount"])) {
            include_once("views/updateAccount.php");
        }
        if (isset($_GET["idSubAccount"]) and !empty($_GET["idSubAccount"])) {
            include_once("views/idSubAccount.php");
        }

        if (isset($_GET["dateStart"]) and !empty($_GET["dateStart"])) {
            $dateStart = $_GET["dateStart"];
        } else {
            $dateStart = $dateUnix;
        }
        if (isset($_GET["dateEnd"]) and !empty($_GET["dateEnd"])) {
            $dateEnd = $_GET["dateEnd"];
        } else {
            $dateEnd = date("Y-m-d", time() + 86400);
        }
        $dateStart = date("Y-m-d", strtotime($dateStart));
        $dateEnd = date("Y-m-d", strtotime($dateEnd));
        $limit = 14;
        $page;
        $start;
        if (!isset($_GET['page'])) {
            $page = 1;
        } else {
            $page = $_GET['page'];
        }
        if (isset($_GET["sort"]) and !empty($_GET["sort"])) {
            $sort = $_GET["sort"];
        } else {
            $sort = 0;
        }

        switch ($sort) {
            case 0:
                $sortQuery = 'accountId ASC';
                break;
            case 1:
                $sortQuery = 'patientName ASC';
                break;
            case 2:
                $sortQuery = 'patientName DESC';
                break;
            case 3:
                $sortQuery = 'datee ASC';
                break;
            case 4:
                $sortQuery = 'datee DESC';
                break;
            case 5:
                $sortQuery = 'total ASC';
                break;
            case 6:
                $sortQuery = 'total DESC';
                break;
            case 7:
                $sortQuery = "accountId ASC";
                break;
        }
        $output = "";
        include_once("../data/connection.php");
        global $con;
        $start = ($page - 1) * $limit;
        $query = "SELECT isShown,a.date as datee, totalPrice as total, a.id_account as accountId, p.name as patientName, p.surname as patientSurname, e.name as doctorName, e.surname as doctorSurname FROM accounts a INNER JOIN patients p ON p.id_patient = a.id_patient INNER JOIN employees e ON e.id_employee = a.id_employee WHERE isShown = 1 AND a.date >= '$dateStart' AND a.date <= '$dateEnd'";
        $query .= " ORDER BY $sortQuery";
        $result = $con->query($query);
        $rowCount = $result->rowCount();
        $query .= " LIMIT $start, $limit";
        $result = $con->query($query);
        $pageCount = ceil($rowCount / $limit);
        $result = $result->fetchAll();
        foreach ($result as $r) {
            $idAcc = $r->accountId;
            $query1 = "SELECT therapyPrice FROM accounts WHERE id_account = $r->accountId AND isShown = 1";
            $res1 = +$con->query($query1)->fetch()->therapyPrice;
            $class = $res1 > 0 ? "red" : "";
            $output .= "<div class='nalogGrid-body'>";
            
            $queryForGreen = "SELECT checked FROM checked WHERE id_account = $r->accountId";
            $resultForGreen = $con->query($queryForGreen)->fetchColumn();
            #var_dump($resultForGreen);
            if ($resultForGreen) {
                $output .= "<div class='$class no-vab head-tcss fc poppins wght600 green-check'>";
            } else {
                $output .= "<div class='$class no-vab head-tcss fc poppins wght600 orange-check'>";
            }
            $output .= "$r->accountId </div>
                <div class='namesur-vab head-tcss fl'>$r->patientName $r->patientSurname</div>
                <div class='doctor-vab head-tcss fl'>$r->doctorName $r->doctorSurname</div>";


            // DATE
            $queryDate = "SELECT date FROM accounts WHERE id_account = $r->accountId";
            $resultDate = $con->query($queryDate)->fetchAll();
            foreach ($resultDate as $rd) {
                $date2 = date("d.m.Y. H:i:s", strtotime($rd->date));
                $output .= "<div class='dateadded-vab head-tcss fc'>$date2</div>";
            }
            // TOTAL
            $queryTotal = "SELECT totalPrice FROM accounts WHERE id_account = $r->accountId";
            $resultTotalPrice = $con->query($queryTotal)->fetch();
            $output .= "<div class='fullprice-vab head-tcss fc'>$resultTotalPrice->totalPrice RSD</div>";
            // BUTTONI I CHECKED
            $output .= "<div class='options-vab head-tcss fc'><button class='viewButtonAcc crudbtns fc' data-id='$r->accountId'>$viewsvg</button>";
            $output .= "<button class='duplicateButtonAcc crudbtns fc' data-id='$r->accountId'>$duplicatesvg</button>";
            $output .= "<button class='printButtonAccAll crudbtns fc' data-id='$r->accountId'>$printsvg</button>";
            if (in_array($_SESSION["id_role"], [1, 3])) {
                $output .= "<button class='updateButtonAcc crudbtns fc' data-id='$r->accountId'>$editsvg</button>";
                $output .= "<button class='deleteButtonAcc crudbtns fc' data-id='$r->accountId'>$deletesvg</button>";
            }
            $output .= "</div>";
            // CHECKED
            $queryChecked = "SELECT c.checked, c.id_account as idAcc, e.name, e.surname, c.date FROM checked c INNER JOIN accounts a ON a.id_account=c.id_account INNER JOIN employees e ON e.id_employee = c.id_employee WHERE c.checked=1";
            $resultChecked = $con->query($queryChecked)->fetchAll();
            $output .= "<div class='checked-vab head-tcss fc'>";
            if (in_array($_SESSION["id_role"], [2, 5])) {
                $cek = "<form class='form'>
    
                        <div class='inputGroup'>
                            <input id='option1' data-id='$r->accountId' name='option1' type='checkbox' class='checkboxCheck' />
                            <label for='option1'>??ekiraj</label>
                        </div>
    
                    </form>";
                foreach ($resultChecked as $rc) {
                    if ($rc->idAcc == $r->accountId) {
                        $ourDate = date("d.m.Y. H:i:s", strtotime($rc->date));
                $cek = "<div class='accordion2 js-accordion3' id='accordion2'>
                            <div class='accordion2__item js-accordion3-item'>
                                <div class='accordion2-header js-accordion3-header'><span>$rc->name $rc->surname</span></div>
                                <div class='accordion2-body js-accordion3-body'>
                                    <div class='accordion2-body__contents fc'><span>$ourDate</span></div>
                                </div>
                            </div>
                        </div>";
                    }
                }
                $output .= $cek;
            } else {
                $cek = "";
                if ($resultForGreen) {
                    foreach ($resultChecked as $rc) {
                        if ($rc->idAcc == $r->accountId and $r->isShown == 1) {
                            $ourDate = date("d.m.Y. H:i:s", strtotime($rc->date));
                            $cek = "<div class='accordion2 js-accordion3' id='accordion2'>
                        <div class='accordion2__item js-accordion3-item'>
                            <div class='accordion2-header js-accordion3-header'><span>$rc->name $rc->surname </span></div>
                            <div class='accordion2-body js-accordion3-body'>
                                <div class='accordion2-body__contents fc'><span>$ourDate</span></div>
                            </div>
                        </div>
                    </div>";
                        }
                    }
                } else {
                    $cek = "<div class='unchecked-btn fc'>Ne ??ekirano</div>";
                }

                $output .= $cek;
            }
            
            $output .= "</div>";
            $output .= "</div>"; // OD CONTAINERA DIV
        }
        if ($pageCount > 1) {
            $output .= "<div class='paginationFront scroll2'><ul class='pagination fc' style='display:flex' class='paginationAccount'>";
            $output .= "<a data-page='1' class='btnAccountPagination btnPagination'>Prva</a>";
            for ($i = 1; $i <= $pageCount; $i++) {
                $class = $page == $i ? 'btn-activeAccountPagination' : "";
                $output .= "<li class='" . $class . "'><a data-page='$i' class='btnAccountPagination'>" . $i . "</a></li>";
            }
            $output .= "<a data-page='$pageCount' class='btnAccountPagination btnPagination'>Poslednja</a>";
            $output .= "</ul></div>";
        }
        echo json_encode($output);
    } catch (PDOException $e) {
        http_response_code(500);
        echo $e->getMessage();
    }
} else {
    http_response_code(404);
}
