<?php
	session_start();
	include_once ('class/autoload.php');
	$controleur = new ControleurAndroid();
	$identifiant = $_POST['id'];
	$mdp = $_POST['mdp'];
	$identifiant = "ninette94200";
	$mdp = "lanctot";
	$controleur->login($identifiant, $mdp);