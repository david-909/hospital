<!DOCTYPE html>
<html lang="sr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    include_once("logic/functions.php");
    startSession();
    if (!isset($_SESSION["id_employee"])) {
    ?>
        <title>Login | Hospital</title>
    <?php
    } else {
    ?>
        <title>Hospital</title>
    <?php
    }
    ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Assistant:wght@200;300;400;500;600;700;800&family=Be+Vietnam+Pro:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Be+Vietnam:ital,wght@0,100;0,300;0,400;0,500;0,600;0,700;0,800;1,100;1,300;1,400;1,500;1,600;1,700;1,800&family=Birthstone&family=Cabin:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Caveat:wght@400;500;600;700&family=Dancing+Script:wght@400;500;600;700&family=Fira+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Indie+Flower&family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Josefin+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&family=Kaushan+Script&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Mulish:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Nunito+Sans:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&family=Nunito:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Red+Hat+Display:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Red+Hat+Text:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Satisfy&family=Spartan:wght@100;200;300;400;500;600;700;800;900&family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&family=Work+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <?php
    $url = $_SERVER["REQUEST_URI"];
    $arr = explode("/", $url);
    $br = count($arr) - 1;
    $lastOne = $arr[$br];
    $filename = explode("?", $arr[$br]);
    $filename = $filename[0];
    if (in_array($filename, ["print.php", "printa4.php", "printAll.php", "printAllA4.php", "printAllSliper", "printsliper.php", "printSub.php", "printSubA4.php", "printSubSliper.php"])) {

    ?>
        
    <?php
    }
    ?>
    <link rel="stylesheet" media="screen" href="assets/css/print.css"/>
    <link rel="stylesheet" media="printslipper" href="assets/css/printslipper.css">
    <link rel="stylesheet" media="print" href="assets/css/print.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/toast.css">
    <!-- <link rel="stylesheet" type="text/css" href="assets/css/lv.css"> -->
    <link rel="stylesheet" type="text/css" href="assets/css/kartoni.css">
    <link rel="stylesheet" type="text/css" href="assets/css/nalog.css">
    <link rel="stylesheet" type="text/css" href="assets/css/main.css">
    <link rel="stylesheet" type="text/css" href="assets/css/responsive.css">

</head>

<body>