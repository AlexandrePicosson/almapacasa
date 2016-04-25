<?php
	session_start();
	include_once('class/autoload.php');
	$controleur = new ControleurAndroid();
	
	$controleur->importData();
	
	