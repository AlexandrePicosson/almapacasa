<?php
session_start();
	include_once('class/autoload.php');
	if(isset($_SESSION['id'])){
			$site = new page_base_securisee_admin('Gestion des patients');
	}
	else 
	{
		$site = new page_base('Gestion des patients');
	}
	$controleur = new controleur();
	$site-> left_sidebar=$controleur->optionAdmin();
	$site-> right_sidebar=$controleur->optionsModifsPatients();
	$site->affiche();
?>