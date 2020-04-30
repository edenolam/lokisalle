<?php
include("connexion_bdd.inc.php");
include("fonction.inc.php");
session_start();
define("RACINE_SITE", "/lokisalle/");
define("RACINE_SERVEUR", $_SERVER['DOCUMENT_ROOT']);
$msg = "";
