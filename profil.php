<?php
include("inc/init.inc.php");

if(!connecte()){
    header("location:connexion.php");
}
if(isset($_POST['modification_profil'])){
		securise();
		$identif_abonne = preg_match("#^[a-zA-Z0-9]+$#" , $_POST['abonne']);
		 if(empty($identif_abonne)){
             $msg .= "<div class='erreur'><p>pseudo obligatoire</p></div>";
         }
		
		if(!$identif_abonne){
            $msg .= "<div class='erreur'><p>pseudo accèpte les caractères suivants: A - Z et 0 - 9 </p></div>";
        }
		
		if(strlen($_POST['abonne']) > 12 || strlen($_POST['abonne']) < 4){
            $msg .= "<div class='erreur'><p>pseudo doit contenir 4 à 12 caractères</p></div>";
        }

		$evalu_pass = preg_match("#^[a-z0-9]+$#" , $_POST['mdp']);

		 if(empty($evalu_pass)){
             $msg .= "<div class='erreur'><p>mot de passe obligatoire</p></div>";
         }
		
		if(!$evalu_pass){
            $msg .= "<div class='erreur'><p>Mot de passe requiert:a-z et 0-9</p></div>";
        }
		
		if(strlen($_POST['mdp']) > 15 || strlen($_POST['mdp']) < 4){
            $msg .= "<div class='erreur'><p>4 a 16 caracteres accepté</p></div>";
        }

		if($_POST['mdp'] !== $_POST['verif_mdp']){
            $msg .= "<div class='erreur'><p>Mot de passe invalide</p></div>";
        }

		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) && !empty($_POST['email'])){
            $msg .= "<div class='erreur'><p>email invalide</p></div>";
        }

		if(strlen($_POST['cp']) != 5){
            $msg .= "<div class='erreur'><p>code postale invalide</p></div>";
        }
	
		if(empty($msg)){
            $verif_base = lance_requete("SELECT * FROM membre WHERE pseudo='$_POST[abonne]' AND id_membre != '$_POST[id]'");
				
				if($verif_base){
						$msg .= "<div class='erreur'><p>pseudo indisponible</p></div>";
                }
				else{
						
						lance_requete("UPDATE membre SET 

						pseudo = '$_POST[abonne]',
						mdp = '$_POST[mdp]',  
						nom = '$_POST[nom]', 
						prenom = '$_POST[prenom]',
						email = '$_POST[email]', 
						sexe = '$_POST[sexe]', 
						ville = '$_POST[ville]', 
						cp = '$_POST[cp]', 
						adresse = '$_POST[adresse]' WHERE id_membre= '$_POST[id]'");
							
					
				
						
						foreach($_POST as $indice=>$valeur){
								if($indice !== "mdp"){
                                    $_SESSION['utilisateur'][$indice] =$valeur;
                                }
                        }
                        $msg .= "<div class='erreur'><p></p></div>";
                        header("location:profil.php");
					}
			}
	}

include('inc/haut_de_site.inc.php');
include("inc/menu.inc.php");
echo $msg;
	
?>
	
<?php
		if(isset($_GET['action']) && $_GET['action'] == "modifier_profil"){

				echo '	<hr><h3>MODIFICATION PROFILE</h3><hr><div id="modif_profil">
			
				
				<form method="POST" action="" enctype="">
					
					
							<input type="hidden" name="id" id="id" value="' . $_SESSION['utilisateur']['id_membre'] .'">
							<label for="abonne">Pseudo:</label>
							<input type="text" name="abonne"  required id="abonne" value="' . $_SESSION['utilisateur']['pseudo'] .'">
					
							<label for="mdp">Mot de passe:</label>
							<input type="password" name="mdp" required id="mdp" >
					
							<label for="verif_mdp">Confirmer le mot de passe:</label>
							<input type="password" name="verif_mdp" required id="verif_mdp" >
					
							<label for="nom">Nom:</label>
							<input type="text" name="nom" required id="nom" value="' . $_SESSION['utilisateur']['nom'] .'" >
						
						
							<label for="prenom">Prénom:</label>
							<input type="text" name="prenom" required id="prenom" value="' . $_SESSION['utilisateur']['prenom'] .'" >
		
				
				
						
							<label for="email">Email:</label>
							<input type="email" name="email" required id="email" value="' . $_SESSION['utilisateur']['email'] .'" >
			
						
							<label for="sexe">Sexe:</label>
							<label>Homme</label>
							<input style="display: table-caption;" type="radio" name="sexe" id="sexe" value="m"';
							if($_SESSION['utilisateur']['sexe'] == "m"){ echo " checked";} echo'>
							<label>Femme</label>
							<input style="display: table-caption;" type="radio" name="sexe" id="sexe" value="f"'; 
							if($_SESSION['utilisateur']['sexe'] == "f"){ echo " checked";} echo'>
					
							<label for="ville">Ville:</label>
							<input type="text" name="ville" required id="ville" value="' . $_SESSION['utilisateur']['ville'] .'" >
					
							<label for="cp">Code Postal:</label>
							<input type="text" name="cp" required id="cp" value="' . $_SESSION['utilisateur']['cp'] .'" >
					
							<label for="adresse">Adresse:</label>
							<textarea style="height:50px;" name="adresse" required id="adresse">' . $_SESSION['utilisateur']['adresse'] .'</textarea>
						
				
						<input type="submit" id="modification_profil" name="modification_profil" value="modification">
						<a style="padding-top:20px;"href="profil.php">Retour</a>
				
				</form>
			
					</div>';?>	<div class="logomodifprofil">
			<img src="<?php print RACINE_SITE;?>/img/1409630604_Streamline-22-512.png">
				</div><?php

			}
		else{
				echo '<hr><h3>PROFIL</h3><hr>
				
						<div id="profil">
							<h3>Informations personnelles</h3><hr>
							<p>Pseudo :  '.$_SESSION['utilisateur']['pseudo'].'</p>
							<p>Nom :  '.$_SESSION['utilisateur']['nom'].'</p>
							<p>Prénom :  '.$_SESSION['utilisateur']['prenom'].'</p>
							<p>Email :  '.$_SESSION['utilisateur']['email'].'</p>
							<p>Sexe :  '.$_SESSION['utilisateur']['sexe'].'</p>
							<p>Ville :  '.$_SESSION['utilisateur']['ville'].'</p>
							<p>CP :  '.$_SESSION['utilisateur']['cp'].'</p>
							<p>Adresse :  '.$_SESSION['utilisateur']['adresse'].'</p>
							<a href="profil.php?action=modifier_profil"><h5 style="color:#333333;">Modifier profil</h5></a>
						</div>';
						
					
		
		
				echo '<div id="profil_commande">
					<h3>Dernières commandes</h3><hr>';			
							


				
				$id = $_SESSION['utilisateur']['id_membre'];
				$resultat = execute_requete("
                SELECT id_commande as commande, DATE_FORMAT(date,'%d %M %Y') as date_commande, prix 
                FROM commande 
                WHERE id_membre = '$id'
                ORDER BY date_commande
                LIMIT 0,5");

				echo "<table><tr>";

				while($colonne = $resultat->fetch_field()){
						echo "<th><h3>" . $colonne->name . "<h3></th>";
                }
				echo "</tr>";
                while($ligne = $resultat->fetch_assoc()){
                    echo "<tr>";
                    foreach($ligne as $indice => $valeur){
                        if($indice == 'id_commande'){
                            echo "<td style='padding-left:20px;'>" . $valeur. "</td>";
                        }
                        else{
                            echo "<td style='padding-left:20px;'>" . $valeur. "</td>";
                        }

                    }


                    echo "</tr>";
                }
		        echo '</table></div>';

			
}
include("inc/footer.inc.php");