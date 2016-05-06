<?php
session_start();

include_once("../class/autoload.php");

$errors = array();
$data = array();
$data['success'] = false;
$tab = array();
$mypdo = new mypdo();

$tab['id'] = $_SESSION['id'];
$tab['idVisite'] = $_POST['idVisite'];
$tab['idInfirmiere'] = $_POST['idInfirmiere'];
$tab['idPatient'] = $_POST['idPatient'];
$tab['idAdmin'] = $_POST['idAdmin'];
$tab['libelle'] = $_POST['libelle'];

$data = $mypdo->valide_com($tab);

echo json_encode($data);

?>