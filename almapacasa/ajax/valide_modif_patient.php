<?php
session_start();

include_once("../class/autoload.php");

$errors = array();
$data = array();
$data['success'] = false;
$tab = array();
$mypdo = new mypdo();

$tab['id'] = $_SESSION['idPatient'];
$tab['nom'] = $_POST['nom'];
$tab['prenom'] = $_POST['prenom'];
$tab['rue'] = $_POST['rue'];
$tab['telephone'] = $_POST['telephone'];
$tab['cp'] = $_POST['cp'];
$tab['ville'] = $_POST['ville'];
$tab['anNaiss'] = $_POST['anNaiss'];
$tab['sexe'] = $_POST['sexe'];

$data = $mypdo->update_patient_admin($tab);
echo json_encode($data);

?>