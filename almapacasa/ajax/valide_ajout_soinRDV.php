<?php
session_start();

include_once("../class/autoload.php");

$errors = array();
$data = array();
$data['success'] = false;
$tab = array();
$mypdo = new mypdo();


$tab['idVisite'] = $_POST['idVisite'];
$tab['idSoin'] = $_POST['idSoin'];

$data = $mypdo->insert_soinRDV($tab);

echo json_encode($data);

?>