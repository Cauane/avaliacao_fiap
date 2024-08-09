<?php

use sistema\Login\LView;

if (!isset($_SESSION)) {
    session_start();
}

include_once 'sistema/Database/Database.php';
include_once 'pages/db.php';
include_once 'sistema/GlobalView.php';
include_once 'sistema/Login/LView.php';
include_once 'sistema/Login/LModel.php';

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="pt-br" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <title>Avaliação FIAP</title>

    <link rel="stylesheet" href="css/login.css" />
    <link rel="stylesheet" href="css/error.css" />
    <link rel="stylesheet" href="css/index.css" />
    <link rel="stylesheet" href="css/style.css" />

</head>

<body>

    <?php

        if (!isset($_SESSION["userId"])) {
            print mb_convert_encoding(LView::createHtml(), 'HTML-ENTITIES', 'UTF-8');
        } else {
            include 'home.php';
        }

    ?>

    <script src="js/login.js"></script>
    <script src="js/alunos.js"></script>
    <script src="js/turmas.js"></script>

</body>
</html>

