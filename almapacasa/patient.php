<?php
session_start();
include_once('class/autoload.php');
if(isset($_SESSION['id'])){
	$site = new page_base_securisee_patient('Administration');
}
else
{
	$site = new page_base('Administration');
}
$controleur = new controleur();
$site-> all_sidebar=$controleur->optionPatient();
$site->affiche();
?>