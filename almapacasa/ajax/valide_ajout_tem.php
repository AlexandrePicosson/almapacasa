<?php
session_start();

include_once("../class/autoload.php");

$errors = array();
$data = array();
$data['success'] = false;
$tab = array();
$mypdo = new mypdo();

$tab['id'] = $_SESSION['id'];
$tab['idPatient'] = $_POST['idPatient'];
$tab['idAdmin'] = $_POST['idAdmin'];
$tab['libelle'] = $_POST['libelle'];

$data = $mypdo->valide_tem($tab);

echo json_encode($data);

?>