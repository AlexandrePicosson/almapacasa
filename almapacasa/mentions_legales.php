<?php
session_start();
include_once('class/autoload.php');

$site = new page_base('Mentions');

$site->affiche();
?>