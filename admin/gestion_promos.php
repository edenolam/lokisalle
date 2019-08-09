 <?php
include("../inc/init.inc.php");	
if(!connecte_et_est_admin()){
    header("location:../connexion.php");
    exit();
}


// ----------- ajout des codes promo ----------------
if( !empty($_GET["action"]) && connecte_et_est_admin()){

	//------ Modification : url "action=modification" --	
		if($_GET["action"] == "modification"){
			if($_POST && !empty($_POST['code_promo']) && !empty($_POST['reduction'])){
				$msg .= "<div class='validation'><p>Enregistrement du code promo</p></div>";
 				execute_requete("
					UPDATE promotion
					SET code_promo = '$_POST[code_promo]', reduction = '$_POST[reduction]'
					WHERE id_promotion = $_GET[id]
					");
				$_GET['action'] = "affichage";
			}
		}
		elseif($_GET['action'] == "ajout"){
		
			if($_POST){
				$promotion = execute_requete ("SELECT * FROM promotion WHERE code_promo = '$_POST[code_promo]'  ");
				if($promotion->num_rows == 0){
                    $msg .= "<div class='validation'><p>Enregistrement du code promo</p></div>";
                    execute_requete("INSERT INTO promotion (code_promo, reduction) VALUES ( '$_POST[code_promo]' , '$_POST[reduction]')");
                    $_GET['action'] = "affichage";
				}
				if(empty($msg)){
                    $msg .= "<div class='erreur'><p>Le code promo est déjà attribué. Veuillez vérifier votre saisie.</p></div>";
                }
            }
        }
		
		//------ Suppression : url "action=suppression" --
		else if($_GET['action'] == "suppression"){
            execute_requete("DELETE FROM promotion WHERE id_promotion='$_GET[id]'");
            $msg .= "<div class='validation'><p>Code promo supprimé.</p></div>";
            $_GET['action'] = "affichage";
        }
}



include("../inc/haut_de_site.inc.php");	
include("../inc/menu.inc.php");
echo $msg;


// --------- Affichage des promo --------------

if((isset($_GET['action']) && $_GET['action'] == 'affichage')|| !isset($_GET['action'])){
	echo "<div class='choixadmin'>";	
    echo "<button><a href='?action=ajout'>AJOUTER UN CODE PROMO<i class=\"fas fa-plus\"></i></a></button>";
    echo "</div>";
	echo "<div class='gestionpromo'><hr><h2>AFFICHAGE DES CODES PROMO</h2><hr>";
	$resultat = execute_requete("SELECT id_promotion,code_promo,reduction  FROM promotion");
    echo "<p>TOTAL DES CODES PROMOS :" . $resultat->num_rows . "</p>";
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
				if($indice == "code"){
					echo "<td>" . $valeur . "</td>";
					}
				else{
                        echo "<td>" . $valeur. "</td>";
				}
        }
        echo "<td><a href='?action=modification&id=$ligne[id_promotion]'><img style='height:20px;width:20px;' src='../img/modif.png'/></a></td>";
        echo "<td><a href='?action=suppression&id=$ligne[id_promotion]' onClick=\"return(confirm('valider la suppression'));\"><img style='height:20px;width:20px;' src='../img/poubelle.png'/></a></td>";
	    echo "</tr>";
    }
    echo "</table>";
	echo "</div>";
}
	

if(isset($_GET['action']) && ($_GET['action'] == "ajout" || $_GET['action'] == "modification")){
	echo "<div class='choixadmin'>";	
	echo "<a href='?action=affichage'>AFFICHAGE DES CODES PROMOS</a><br />";
	echo "</div>";
	if(isset($_GET['id'])) {
        $resultat = information_sur_un_produit($_GET['id']);
        $produit_a_modifier = $resultat->fetch_assoc();
        $_POST = $produit_a_modifier;
        echo "<hr><h2>MODIFIER UN CODE PROMO</h2><hr>";
    }
    else {
        echo "<hr><h2>AJOUTER UN CODE PROMO</h2><hr>";
    }
    ?>
    <div id="gestionpromotion">
    <form method="post" action="" enctype="multipart/form-data">
        <label for="code_promo">code promo</label>
        <input type="text" name="code_promo" id="code_promo" value="<?php if(isset($_POST['code_promo'])) echo $_POST['code_promo']; ?>" required/><br />
        <label for="reduction">reduction -%</label>
        <input type="text" name="reduction" id="reduction" value="<?php if(isset($_POST['reduction'])) echo $_POST['reduction']; ?>" required/><br />
        <input type="submit" name="valider" value="<?php echo ($_GET['action']) ;?>" />
        <a href="gestion_promos.php">Retour</a>
    </form>

    </div>
    <div class="imgpromo">
        <img src="<?php print RACINE_SITE;?>/img/inscription-en-ligne-ouvertes-event.jpg" />
    </div>
    <?php
}
include("../inc/footer.inc.php");
?>

		
	
		
			