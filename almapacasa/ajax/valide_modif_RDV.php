<?php
 
session_start();

include_once('../class/autoload.php');

$errors = array();
$data = array();
$data['success'] = false;

$tab = array();
$mypdo = new mypdo();

$tab['id'] = $_SESSION['id'];
$tab['idPatient'] = $_POST['idPatient'];
$tab['idInfirmiere'] = $_POST['idInfirmiere'];
$tab['dateVisite'] = $_POST['dateVisite'];
$tab['heureDeb'] = $_POST['heureDeb'];
$tab['heureFin'] = $_POST['heureFin'];

$data = $mypdo->update_RDV_admin($tab);

echo json_encode($data);

?>