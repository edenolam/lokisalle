<?php
//$mysqli = @new Mysqli("mysql51-122.perso", "edenolambdd", "papa17111952", "edenolambdd");

$mysqli = @new Mysqli("localhost", "root", "root", "lokisalle");
$mysqli->query("SET lc_time_names = 'fr_FR'");
$mysqli->query("SET NAMES 'utf8'");

if ($mysqli->connect_error) {
    die("Un probème est survenu lors de la connexion a la base de donnes: " . $mysqli->connect_error);
}
