
<?php
function startSession()
{
    if (!isset($_SESSION)) {
        session_start();
    }
}
function stopSession()
{
    session_unset();
}
function setSession($id, $name, $surname, $id_role, $id_specialization)
{
    $_SESSION["id_employee"] = $id;
    $_SESSION["name"] = $name;
    $_SESSION["surname"] = $surname;
    $_SESSION["id_role"] = $id_role;
    if ($id_specialization != null) {
        $_SESSION["id_specialization"] = $id_specialization;
        switch ($_SESSION["id_specialization"]) {
            case 1:
                $_SESSION["specialization"] = "Pulmolog";
                break;
            case 2:
                $_SESSION["specialization"] = "Neurolog";
                break;
            case 3:
                $_SESSION["specialization"] = "Internista";
                break;
        }
    } else {
        $_SESSION["specialization"] = "";
    }

    switch ($_SESSION["id_role"]) {
        case 1:
            $_SESSION["role"] = "Doktor";
            break;
        case 2:
            $_SESSION["role"] = "Sestra";
            break;
        case 3:
            $_SESSION["role"] = "Menadzment";
            break;
        case 4:
            $_SESSION["role"] = "Pult";
            break;
        case 5:
            $_SESSION["role"] = "Glavna sestra";
            break;
    }
}
function checkCurrentPage($href)
{
    $href = explode("/", $href);
    $href = $href[count($href) - 1];
    $splited = explode("/", $_SERVER["PHP_SELF"]);
    $currentPage = $splited[count($splited) - 1];
    return $currentPage == $href ? true : false;
}
function insertAccount($idPatient, $idEmployee, $comment, $anamneza, $nalaz, $uznalaz, $terapija, $zakljucak, $bloodPrice, $therapyPrice, $appointmentPrice, $idInsertedBy)
{
    if ($comment == 0) $comment = "";
    $totalPrice = $therapyPrice + $bloodPrice + $appointmentPrice;
    global $con;
    $query = "INSERT INTO accounts VALUES(null, :idPatient, :idEmployee,:comment, :anamneza, :nalaz, :uznalaz,:terapija, :zakljucak,CURRENT_TIMESTAMP, true, $bloodPrice, $therapyPrice,$appointmentPrice, $totalPrice, $idInsertedBy)";
    $prepared = $con->prepare($query);
    $prepared->bindParam(":idPatient", $idPatient);
    $prepared->bindParam(":idEmployee", $idEmployee);
    $prepared->bindParam(":comment", $comment);
    $prepared->bindParam(":anamneza", $anamneza);
    $prepared->bindParam(":nalaz", $nalaz);
    $prepared->bindParam(":uznalaz", $uznalaz);
    $prepared->bindParam(":terapija", $terapija);
    $prepared->bindParam(":zakljucak", $zakljucak);
    $prepared->execute();
}
function insertAccountBloods($idAccount, $idBlood)
{
    global $con;
    $query = "INSERT INTO accounts_bloods VALUES(null, :idAccount,:idBlood)";
    $prepared = $con->prepare($query);
    $prepared->bindParam(":idAccount", $idAccount);
    $prepared->bindParam(":idBlood", $idBlood);
    $prepared->execute();
}
function insertTherapy()
{
    global $con;
    $query = "INSERT INTO therapies VALUES(null, 'Terapija', 2)";
    $prepared = $con->prepare($query);
    $prepared->execute();
}
function insertTherapyMedicines($medicines, $idTherapy)
{
    global $con;
    for ($i = 0; $i < count($medicines); $i++) {
        $query = "INSERT INTO therapy_medicine VALUES (null,:idTherapy,:idMedicine,:quantity)";
        $prepared = $con->prepare($query);
        $prepared->bindParam(":idTherapy", $idTherapy);
        $prepared->bindParam(":idMedicine", $medicines[$i]["idMedicine"]);
        $prepared->bindParam(":quantity", $medicines[$i]["quantity"]);
        $prepared->execute();
    }
}
function insertAccountsTherapies($idAccount, $idTherapy, $idSubAccount = 0)
{
    if ($idSubAccount == 0) {
        global $con;
        $query = "INSERT INTO accounts_therapies VALUES(null, :idAccount, :idTherapy)";
        $prepared = $con->prepare($query);
        $prepared->bindParam(":idAccount", $idAccount);
        $prepared->bindParam(":idTherapy", $idTherapy);
        $prepared->execute();
    } else {
        global $con;
        $query = "INSERT INTO sub_accounts_therapies VALUES(null, :idAccount, :idTherapy)";
        $prepared = $con->prepare($query);
        $prepared->bindParam(":idAccount", $idSubAccount);
        $prepared->bindParam(":idTherapy", $idTherapy);
        $prepared->execute();
    }
}
function checkVariable($post)
{
    if (isset($_POST[$post]) and !empty($_POST[$post])) {
        return $_POST[$post];
    } else {
        return 0;
    }
}
function updateMedicines($idTherapy)
{
    global $con;
    $query = "SELECT id_medicine, quantity FROM therapies t INNER JOIN therapy_medicine tm on t.id_therapy = tm.id_therapy WHERE t.id_therapy = $idTherapy";
    $result = $con->query($query)->fetchAll();
    foreach ($result as $r) {
        $query = "UPDATE medicines SET quantity = quantity - +$r->quantity WHERE id_medicine = +$r->id_medicine";
        $prepared = $con->prepare($query);
        $prepared->execute();
    }
}
function insertAccountAppointments($idAccount, $idAppointment)
{
    global $con;
    $query = "INSERT INTO accounts_appointments VALUES (null,$idAccount, $idAppointment)";
    $con->query($query);
}
function check($accId, $idEmployee)
{
    global $con;
    $query = "INSERT INTO checked VALUES(null, ?, 1, ?, CURRENT_TIMESTAMP)";
    $prepared = $con->prepare($query);
    $prepared->execute([$accId, $idEmployee]);
}
function check1($subAccountId, $idEmployee)
{
    global $con;
    $query = "INSERT INTO checkedSub VALUES(null, ?, 1, ?, CURRENT_TIMESTAMP)";
    $prepared = $con->prepare($query);
    $prepared->execute([$subAccountId, $idEmployee]);
}
function deleteFromTable($tableName, $columnName, $value)
{
    global $con;
    $query = "DELETE FROM $tableName WHERE $columnName = $value";
    return $con->query($query);
}
function insertMultipleValuesIntoTable($array, $tableName, $idAccount)
{
    global $con;
    foreach ($array as $a) {
        $query = "INSERT INTO $tableName VALUES (null,$idAccount, $a)";
        $con->query($query);
    }
}
function updateAccount($idEmployee, $comment, $anamneza, $nalaz, $uznalaz, $terapija, $zakljucak, $bloodPrice, $therapyPrice, $appointmentPrice, $totalPrice, $idAccount, $idSubAccount = 0)
{
    global $con;
    if ($idSubAccount == 0) {
        $query = "UPDATE accounts SET id_employee = $idEmployee, comment='$comment',anamneza='$anamneza',nalaz='$nalaz',uznalaz='$uznalaz',terapija='$terapija',zakljucak='$zakljucak', date = CURRENT_TIMESTAMP, bloodPrice = $bloodPrice, therapyPrice = $therapyPrice, appointmentPrice = $appointmentPrice, totalPrice = $totalPrice WHERE id_account = $idAccount";
        return $con->query($query);
    } else {
        $query = "UPDATE sub_accounts SET id_employee = $idEmployee, comment='$comment', date = CURRENT_TIMESTAMP, bloodPrice = $bloodPrice, therapyPrice = $therapyPrice, appointmentPrice = $appointmentPrice, totalPrice = $totalPrice WHERE id_sub_account = $idSubAccount";
        return $con->query($query);
    }
}

function updateAccountsTherapies($idAccount, $idTherapy, $idSubAccount = 0)
{
    if ($idSubAccount == 0) {
        global $con;
        $query = "UPDATE accounts_therapies SET id_therapy = $idTherapy WHERE id_account = $idAccount";
        $con->query($query);
    } else {
        global $con;
        $query = "UPDATE sub_accounts_therapies SET id_therapy = $idTherapy WHERE id_sub_account = $idSubAccount";
        $con->query($query);
    }
}
function insertNewTherapy()
{
    global $con;
    $query = "INSERT INTO therapies VALUES(null,'Terapija',2)";
    $con->query($query);
    return $con->lastInsertId();
}
function insertIntoAccountsTherapies($idAccount, $idTherapy)
{
    global $con;
    $query = "INSERT INTO accounts_therapies VALUES(null,$idAccount, $idTherapy)";
    $con->query($query);
}
function insertIntoTherapyMedicine($idTherapy, $arrayId, $arrayQuantity)
{
    global $con;
    for ($i = 0; $i < count($arrayId); $i++) {
        $query = "INSERT INTO therapy_medicine VALUES(null,$idTherapy,$arrayId[$i], $arrayQuantity[$i])";
        $con->query($query);
    }
}
function checkMedicineQuantity($inputQuantity, $idMedicine)
{
    global $con;
    $currentQuantity = 0;
    $query = "SELECT * FROM medicines WHERE id_medicine = $idMedicine";
    $result = $con->query($query)->fetch();
    $currentQuantity = $result->quantity;
    return $inputQuantity > $currentQuantity ? false :  true;
}
function insertMedicineUsage($medicines, $idAccount)
{
    global $con;
    for ($i = 0; $i < count($medicines); $i++) {
        $idMed = (int) $medicines[$i]["idMedicine"];
        $quantity = (int) $medicines[$i]["quantity"];
        $price = (int) $medicines[$i]["price"];
        $total = $quantity * $price;
        $query = "INSERT INTO medicine_usage VALUES(null,$idMed,$idAccount,$quantity,$total,CURRENT_TIMESTAMP)";
        $con->query($query);
    }
}
function insertMedicineUsageUpdate($idMedicine, $idAccount, $quantityUsed, $total, $idSubAccount = 0)
{
    if ($idSubAccount == 0) {
        global $con;
        $query = "INSERT INTO medicine_usage VALUES(null,$idMedicine,$idAccount,$quantityUsed,$total,CURRENT_TIMESTAMP)";
        $con->query($query);
    } else {
        global $con;
        $query = "INSERT INTO sub_accounts_medicine_usage VALUES(null,$idMedicine,$idSubAccount,$quantityUsed,$total,CURRENT_TIMESTAMP)";
        $con->query($query);
    }
}
