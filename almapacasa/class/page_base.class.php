<?php

class page_base {
	protected $right_sidebar;
	protected $left_sidebar;
	protected $all_sidebar;
	protected $titre;
	protected $js=array('jquery-2.1.4.min', 'bootstrap.min');
	protected $css=array('bootstrap.min','perso');
	protected $page;
	protected $metadescription="Bienvenue sur le site d'Almapacasa";
	protected $metakeyword=array('Accueil Almapacasa','Almapacasa' );

	public function __construct($p) {
				$this->titre = $p;
	}
	
	public function __get($prop) {
		switch ($prop)
		{
			case 'titre' : {
				return $this->titre;
				break;
			}
		}
		
	}

	public function __set($propriete, $valeur) {
		switch ($propriete) {
			case 'css' : {
				$this->css[count($this->css)+1] = $valeur;
				break;
			}
			case 'js' : {
				$this->js[count($this->js)+1] = $valeur;
				break;
			}
			case 'metakeyword' : {
				$this->metakeyword[count($this->metakeyword)+1] = $valeur;
				break;
			}
			case 'titre' : {
				$this->titre = $valeur;
				break;
			}
			case 'menu' : {
				$this->menu = $valeur;
				break;
			}
			case 'metadescription' : {
				$this->metadescription = $valeur;
				break;
			}
			case 'right_sidebar' : {
				$this->right_sidebar = $this->right_sidebar.$valeur;
				break;
			}
			case 'left_sidebar' : {
				$this->left_sidebar = $this->left_sidebar.$valeur;
				break;
			}
			case 'all_sidebar' : {
				$this->all_sidebar = $this->all_sidebar.$valeur;
			}

		}
	}
	/******************************Gestion des styles **********************************************/
	/* Insertion des feuilles de style */
	private function affiche_style() {
		foreach ($this->css as $s) {
			echo "<link rel='stylesheet'  href='css/".$s.".css' />\n";
		}

	}
	/******************************Gestion du javascript **********************************************/
	/* Insertion  js */
	private function affiche_javascript() {
		foreach ($this->js as $s) {
			echo "<script src='js/".$s.".js'></script>\n";
		}
	}
	/******************************affichage metakeyword **********************************************/

	private function affiche_keyword() {
		echo '<meta name="keywords" content="';
		foreach ($this->metakeyword as $s) {
			echo utf8_encode($s).',';
		}
		echo '" />';
	}	
	/****************************** Affichage de la partie entÃªte ***************************************/	
	protected function affiche_entete() {
		echo'
			<header class="page-header hidden-md hidden-lg">
				<div class="row">
				    <div class="col-xs-12">
						<h1>
							Kaliémie
						</h1>
						<h3>
							<!-- texte pour plus tard -->
						</h3>    
  					</div>
				</div>
            </header>	
		';
	}
	/****************************** Affichage du menu ***************************************/	
	
	protected function affiche_menu() {
		echo '
				<ul class="nav navbar-nav" onload="active();">
					<li class="home" ><a href="index.php">Accueil</a></li>
					<li class="equipe"><a href="equipe.php">Présentation de l\'équipe</a></li>
					<li class="temoignage"><a href="temoignage.php" >Témoignages</a></li>
					<li class="mentions"><a href="mentions_legales">Mentions légales</a></li>
				</ul>
				';
	}
	protected function affiche_menu_connexion() {
		
		if(!isset($_SESSION['id']))
		{	
			echo '
					<ul class="nav navbar-nav navbar-right">
						<li><a  href="connect.php">Connexion</a></li>
					</ul>';
		} 
		else
		{
			echo '
					<ul class="nav navbar-nav navbar-right">
				<li><a  href="deconnect.php">Déconnexion</a></li>
					</ul>';
		}
		
	}
	public function affiche_entete_menu() {
		echo '
				<div id="menu_horizontal">
					<nav class="navbar navbar-inverse">
						<div class="container-fluid">
				    		<div class="navbar-header">
      							<button type="button" class="navbar-toggle collapsed" data-toggle="collapse"  data-target="#monmenu" aria-expanded="false">
        							<span class="sr-only">Toggle navigation</span>
        							<span class="icon-bar"></span>
        							<span class="icon-bar"></span>
        							<span class="icon-bar"></span>
      							</button>
								<p class="visible-xs navbar-text"><mark>Menu</mark></p>
    						</div>
							<div class="collapse navbar-collapse" id="monmenu">
				';
						
	}
	public function affiche_footer_menu(){
		echo '
						
					
				</div>
			</nav>
		</div>';

	}

	
	/********************************************* Fonction permettant l'affichage de la page ****************/

	public function affiche() {
		
		
		?>
			<!DOCTYPE html>
			<html lang='fr'>
				<head>
					<title><?php echo $this->titre; ?></title>
					<meta http-equiv="content-type" content="text/html; charset=utf-8" />
					<meta name="description" content="<?php echo $this->metadescription; ?>" />
					<link rel="shortcut icon" href="images/logo.png">
					<meta http-equiv="X-UA-Compatible" content="IE=edge">
					<meta name="viewport" content="width=device-width, initial-scale=1">
					<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
					<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
					<!--[if lt IE 9]>
					<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
					<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
					<link href='https://fonts.googleapis.com/css?family=Lobster' rel='stylesheet' type='text/css'>
					<![endif]-->
					
					<?php $this->affiche_keyword(); ?>
					<?php $this->affiche_javascript(); ?>
					<?php $this->affiche_style(); ?>
				</head>
				<body>
				<div class="global container-fluid">
				
						<?php $this->affiche_entete(); ?>
						<?php $this->affiche_entete_menu(); ?>
						<?php $this->affiche_menu(); ?>
						<?php $this->affiche_menu_connexion(); ?>
						<?php $this->affiche_footer_menu(); ?>
						
  						<div class="content">
  							<div class="col-md-12">
						    	<?php echo $this->all_sidebar;?>
						    </div>
						    <div class="col-md-3">
						    	<?php echo $this->left_sidebar; ?>
						    </div>
						    <div class="col-md-9">
								<?php echo $this->right_sidebar;?>
						    </div>
						</div>
						
						
					</div>
				</body>
			</html>
		<?php
	}

}

?>
