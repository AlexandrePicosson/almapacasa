<?php
session_start();

include_once("../class/autoload.php");

$errors = array();
$data = array();
$data['success'] = false;
$tab = array();
$mypdo = new mypdo();

$tab['id'] = $_SESSION['id'];
$tab['libelleTem'] = $_POST['libelleTem'];

$data = $mypdo->valide_ajout_tem($tab);

echo json_encode($data);

?>