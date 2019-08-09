<!DOCTYPE html>
<html lang="fr">
	<head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width , initial-scale=1" />
        <title>LOKISALLE</title>
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <![endif]-->
        <link rel="stylesheet" type="text/css" href="<?php echo RACINE_SITE; ?>css/style.css">
        <link rel="stylesheet" type="text/css" href="<?php echo RACINE_SITE; ?>css/slide.css">
        <link rel="stylesheet" type="text/css" href="<?php echo RACINE_SITE; ?>css/flip.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
        <link href='http://fonts.googleapis.com/css?family=Oswald:400,300,700' rel='stylesheet' type='text/css'>
        <link rel="icon" type="image.png" href="<?php echo RACINE_SITE?>ls.png" />


	</head>
	
	<body>
		<div class="container">

			<header class="header">
                <div class="haut_gauche">
                        <div class="flip-container" ontouchstart="this.classList.toggle('hover');">
                                <div class="flipper">
                                        <div class="front">
                                            <a href="<?php echo RACINE_SITE; ?>index.php"><img src="<?php print RACINE_SITE;?>img/lokilogo1.jpg" alt="" style="width:100%; height: 100%;"></a>

                                        </div>
                                        <div class="back">
                                            <a href="<?php echo RACINE_SITE; ?>index.php"><img src="<?php print RACINE_SITE;?>img/lokilogo2.jpg" alt="" style="width:100%; height: 100%;"></a>

                                        </div>
                                </div>
                        </div>
                </div>
                <div class="haut_droit">
                    	<hr />

						<span>
						<a href="<?php echo RACINE_SITE; ?>contact.php">NOUS ECRIRE </a>
                            <i class="fas fa-envelope-open-text"></i>
						</span>

						<span>
						<a href="<?php print RACINE_SITE;?>panier.php">MON PANIER </a>
						<i class="fas fa-shopping-basket"></i>
						</span>

						<hr />
					</div>
			</header>	