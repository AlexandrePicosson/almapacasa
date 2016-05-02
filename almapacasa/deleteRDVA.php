<?php
session_start();
	include_once('class/autoload.php');
	if(isset($_SESSION['id'])){
			$site = new page_base_securisee_admin('Suppression d\'un rendez-vous');
			$site->js='jquery.tooltipster.min';
			$site->js='jquery.validate.min';
			$site->js='messages_fr';
			$site->css='tooltipster';
	}
	else 
	{
		$site = new page_base('Suppression d\'un rendez-vous');
	}
	$controleur = new controleur();
	$site-> left_sidebar=$controleur->optionAdmin();
	$site-> right_sidebar=$controleur->formRDV();
	if(isset($_POST["id"])){
			$site->right_sidebar=$controleur->retourne_formulaire_RDV('suppr', $_POST["id"]);
			$_SESSION['idRDV'] = $_POST['id'];
	}
	$site->affiche();
?>