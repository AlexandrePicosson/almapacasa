<?php
session_start();
	include_once('class/autoload.php');
	if(isset($_SESSION['id'])){
			$site = new page_base_securisee_admin('Création de rendez-vous');
	}
	else 
	{
		$site = new page_base('Création de rendez-vous');
	}
	$controleur = new controleur();
	$site-> left_sidebar=$controleur->optionAdmin();
	$site-> right_sidebar=$controleur->optionsRDVA();
	$site->affiche();
?>