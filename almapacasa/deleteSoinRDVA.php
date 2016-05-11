<?php
session_start();
	include_once('class/autoload.php');
	if(isset($_SESSION['id'])){
			$site = new page_base_securisee_admin('Ajout d\'un rendez-vous');
			$site->js='jquery.tooltipster.min';
			$site->js='jquery.validate.min';
			$site->js='messages_fr';
			$site->css='tooltipster';
	}
	else 
	{
		$site = new page_base('Ajout d\'un rendez-vous');
	}
	$controleur = new controleur();
$controleur = new controleur();
	$site-> left_sidebar=$controleur->optionAdmin();
	$site-> right_sidebar=$controleur->formsoin2RDV();
	if(isset($_POST["id"])){
			$site->right_sidebar=$controleur->retourne_formulaire_soinRDV('suppr', $_POST["id"]);
			$_SESSION['idSoinRDV'] = $_POST['id'];
	}
	
	$site->affiche();
?>