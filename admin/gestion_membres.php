 <?php
include("../inc/init.inc.php");
if(isset($_POST['valider'])){
		$verif_caractere = preg_match("#^[a-zA-Z0-9_-]+$#" , $_POST['pseudo']);
		if(!$verif_caractere && !empty($_POST['pseudo'])){
            $msg .= "<div class='erreur'><p>Caractère accepté : A - Z et 0 - 9.</p></div>";
        }
		if(strlen($_POST['pseudo']) > 15 || strlen($_POST['pseudo']) < 5){
            $msg .= "<div class='erreur'><p>Le pseudo doit avoir 5 a 16 caractères. </p></div>";
        }
		if(strlen($_POST['mdp']) > 10 || strlen($_POST['mdp']) < 5){
            $msg .= "<div class='erreur'><p>Le mot de passe doit avoir 5 a 10 caractères.</p></div>";
        }
	    if(empty($msg)){
			$membre = execute_requete("SELECT * FROM membre WHERE pseudo='$_POST[pseudo]'");
			if($membre -> num_rows > 0){
					$msg .= "<div class='erreur'><p>Pseudo Indisponible.</p></div>";   
            }
			else{
                foreach($_POST as $indice => $valeur){
                        $_POST[$indice] = htmlentities($valeur);
                }
                execute_requete("INSERT INTO membre (pseudo, mdp, nom, prenom, email, sexe, ville, cp, adresse, statut )  
                                         VALUES ('$_POST[pseudo]', '$_POST[mdp]', '$_POST[nom]', '$_POST[prenom]','$_POST[email]', '$_POST[sexe]', '$_POST[ville]', '$_POST[cp]', '$_POST[adresse]',1)");
                $msg .= "<div class='validation'><p>Inscription validé</p></div>";
                header("location:".RACINE_SITE."/admin/gestion_membres.php");
            }
		}
}
if(!connecte_et_est_admin()){
    header("location:../connexion.php");
    exit();
}


include("../inc/haut_de_site.inc.php");	
include("../inc/menu.inc.php");
echo $msg;
	

if(isset($_GET['action']) && ($_GET['action'] == "ajout" || $_GET['action'] == "modification")){
	    echo "<div class='choixadmin'>";
		echo "<a href='?action=affichage'>AFFICHAGE DES MEMBRES</a><br />";
        echo "</div>";
	    if(isset($_GET['id'])){
			$resultat = information_sur_un_produit($_GET['id']);
			$admin_a_modifier = $resultat->fetch_assoc();
			$_POST = $admin_a_modifier;
            echo "<hr><h2>MODIFIER D'UN COMPTE ADMINISTRATEUR</h2><hr>";
			
		}
		else echo "<hr><h2>CREATION D'UN COMPTE ADMINISTRATEUR</h2><hr>";
		
?>
<div id="creadmin">
	<form method="post" action="">

		<label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" id="pseudo" value="<?php if(isset($_POST['pseudo'])) echo $_POST['pseudo']; ?>"/><br />

		<label for="mdp">Mdp</label>
        <input type="password" name="mdp" id="mdp" value="<?php if(isset($_POST['mdp'])) echo $_POST['mdp']; ?>"/><br />

		<label for="nom">Nom</label>
		<input type="text" name="nom" id="nom" value="<?php if(isset($_POST['nom'])) echo $_POST['nom']; ?>"/><br />

		<label for="prenom">Prénom</label>
        <input type="text" name="prenom" id="prenom" value="<?php if(isset($_POST['prenom'])) echo $_POST['prenom']; ?>"/><br />
		
		<label for="email">Email</label>
        <input type="text" name="email" id="email" value="<?php if(isset($_POST['email'])) echo $_POST['email']; ?>"/><br />

		<label>Sexe</label>

		<label for="sexe">Homme</label>
        <input type="radio" name="sexe" value="m" <?php if(isset($_POST['sexe']) && $_POST['sexe'] == "m") echo "checked";
        elseif(!isset($_POST['sexe'])) echo "checked"; ?> />

        <label for="sexe">Femme</label>
        <input type="radio" name="sexe" value="f" <?php if(isset($_POST['sexe']) && $_POST['sexe'] == "f") echo "checked";?>/>
        <br />
			
		
		<label for="ville">Ville</label>
        <input type="text" name="ville" id="ville" value="<?php if(isset($_POST['ville'])) echo $_POST['ville']; ?>"/><br />
			
	
		<label for="cp">Cp</label>
        <input type="text" name="cp" id="cp" value="<?php if(isset($_POST['cp'])) echo $_POST['cp']; ?>" /><br />
			
		
		<label for="adresse">Adresse</label>
        <textarea name="adresse" id="adresse"><?php if(isset($_POST['adresse'])) echo $_POST['adresse']; ?></textarea><br />
			
		
		<input type="submit" name="valider" value="Valider" />
		<a href="gestion_membres.php">Retour</a>

	</form>
	</div>
	<div class="imagecrea">
	<img src="<?php print RACINE_SITE;?>/img/inscription-en-ligne-ouvertes-event.jpg" />
	</div>

<?php
}

//------ Suppression : url "action=suppression" --
if(isset($_GET['action']) && $_GET['action'] == "suppression"){
    execute_requete("UPDATE membre SET statut = 2 WHERE id_membre ='$_GET[id]'");
    $msg .= "<div class='validation'><p>Membre supprimé.</p></div>";
    $_GET['action'] = "affichage";
}
	

// --------- Affichage des membre --------------

if((isset($_GET['action']) && $_GET['action'] == 'affichage')|| !isset($_GET['action'])){
	echo "<div class='choixadmin'>";
	echo "<a href='?action=ajout'>CREATION D'UN COMPTE ADMINISTRATEUR</a><br />";
	echo "</div>";	
	echo "<div class='gestionmembre'><hr><h2>AFFICHAGE DES MEMBRES</h2>

	<hr>";
    $resultat = execute_requete("SELECT id_membre,pseudo,mdp,nom,prenom as prénom,email,sexe,ville,cp,adresse,statut FROM membre WHERE id_membre != 15 AND statut != 2 ");
    echo "<p>NOMBRE DE MEMBRES : " . $resultat->num_rows . "</p>";
    echo "<table>";
    echo "<tr>";
    while($colonne = $resultat->fetch_field()){
        echo "<th>" . $colonne->name . "</th>";
    }
	
    echo "<th>modification</th>";
    echo "<th>suppression</th>";
    echo "</tr>";
    while($ligne = $resultat->fetch_assoc()){
        echo "<tr>";
            foreach($ligne as $indice => $valeur){
                    echo "<td>" . $valeur . "</td>";
            }

            if ($ligne['statut'] == 1){
                echo "<td><a href='?action=modification&id=$ligne[id_membre]'><img style='height:20px;width:20px;' src='../img/modif.png'/></a></td>";
                echo "<td><a href='?action=suppression&id=$ligne[id_membre]' onClick=\"return(confirm('valider la suppression'));\"><img style='height:20px;width:20px;' src='../img/poubelle.png'/></a></td>";
            }
            if ($ligne['statut'] == 0){
                echo "<td><p>membre non modifiable</p></td>";
                echo "<td><a href='?action=suppression&id=$ligne[id_membre]' onClick=\"return(confirm('valider la suppression'));\"><img style='height:20px;width:20px;' src='../img/poubelle.png'/></a></td>";
            }

        echo "</tr>";
    }
    echo "</table>";
	echo "</div>";

}

include("../inc/footer.inc.php");
?>

			
			
			
		
		
	
