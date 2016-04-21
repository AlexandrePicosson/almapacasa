<?php
session_start();
	include_once('class/autoload.php');
	if(isset($_SESSION['id'])){
			$site = new page_base_securisee_admin('Mentions');
	}
	else 
	{
		$site = new page_base('Mentions');
	}
	$controleur = new controleur();
	$site-> all_sidebar=$controleur->mentionsLegales();
	$site->affiche();
	
?>