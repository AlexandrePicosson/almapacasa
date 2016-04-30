<?php
session_start();
	include_once('class/autoload.php');
	if(isset($_SESSION['id'])){
			$site = new page_base_securisee_admin('Patient');
			$site->js='jquery.tooltipster.min';
			$site->js='jquery.validate.min';
			$site->js='messages_fr';
			$site->css='tooltipster';
	}
	else 
	{
		$site = new page_base('Patient');
	}
	$controleur = new controleur();
	$site-> left_sidebar=$controleur->optionAdmin();
	$site-> right_sidebar=$controleur->formModifPersonneC();
	if(isset($_POST["id"])){
			$site->right_sidebar=$controleur->retourne_formulaire_personneC('suppr', $_POST["id"]);
			$_SESSION['idPersonneC'] = $_POST['id'];
	}
	$site->affiche();
?>