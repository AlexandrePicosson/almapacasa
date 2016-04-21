<?php
session_start();
	include_once('class/autoload.php');
	if(isset($_SESSION['id'])){
			$site = new page_base_securisee_admin('Equipe');
	}
	else 
	{
		$site = new page_base('Equipe');
	}
	$controleur = new controleur();
	$site-> all_sidebar=$controleur->returnPageEquipe();
	$site->affiche();
	
?>