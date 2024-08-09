<?php

use Sistema\Database\Database;

$dbase = Database::getInstance();

$host = "localhost";
$db = "avaliacao_fiap";
$user = "root";
$passdb = "";

$dbase->connect($host, $user, $passdb, $db);
