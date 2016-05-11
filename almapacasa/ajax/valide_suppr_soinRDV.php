<?php
session_start();

include_once("../class/autoload.php");

$errors = array();
$data = array();
$data['success'] = false;
$tab = array();
$mypdo = new mypdo();

$tab['idVisiteSoin'] = $_SESSION['idSoinRDV'];
$tab['idVisite'] = $_POST['idVisite'];
$tab['idSoin'] = $_POST['idSoin'];

$data = $mypdo->suppr_soinRDV($tab);

echo json_encode($data);

?>