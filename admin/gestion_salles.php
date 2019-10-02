<?php
include("../inc/init.inc.php");
if (!connecte_et_est_admin()) {
    header("location:../connexion.php");
    exit();
}
// ----------- enregistrement des salles ----------------
$photo_bdd = "";

if ($_POST && isset($_GET['action']) && $_GET['action'] == "ajout") {
    $salle = execute_requete("SELECT * FROM salle WHERE id_salle = '$_POST[id_salle]'");
    if ($salle->num_rows > 0 && isset($_GET['action']) && $_GET['action'] == 'ajout') {
        $msg .= '<div class="erreur">la salle est déjà attribuée. Veuillez vérifier votre saisie. </div>';
    } else {
        if (isset($_GET["action"]) && $_GET["action"] == "enregistrement") {
            $photo_bdd = $_POST['photo_actuelle'];
        }

        if (!empty($_FILES['photo']['name'])) {
            if (verification_extension_photo()) {
                $nom_photo = $_POST['titre'] . "-" . $_FILES['photo']['name'];
                $photo_bdd .= "img/" . $nom_photo;
                $photo_dossier = RACINE_SERVEUR . RACINE_SITE . "img/" . $nom_photo;
                copy($_FILES['photo']['tmp_name'], $photo_dossier);
            } else {
                $msg .= "<div class='erreur'>format invalide</div>";
            }
        }
        if (empty($msg)) {
            $msg .= "<div class='validation'>Enregistrement validé</div>";
            execute_requete("INSERT INTO salle (id_salle, pays, ville, adresse, cp, latitude, longitude, titre, description, photo, capacite, categorie ) 
                                VALUES ('$_POST[id_salle]' ,'$_POST[pays]' , '$_POST[ville]' , '$_POST[adresse]' , '$_POST[cp]', '$_POST[latitude]', '$_POST[longitude]', '$_POST[titre]' , '$_POST[description]' , '$photo_bdd' , '$_POST[capacite]' , 'reunion')");

            $_GET['action'] = "affichage";
        }
    }
}


// ----------- modification des salles ----------------
// if(isset($_POST['valider']))
$photo_bdd = "";
if ($_POST && isset($_GET['action']) && $_GET['action'] == "modification") {
    $salle = execute_requete("SELECT * FROM salle WHERE id_salle = '$_POST[id_salle]'");
    if (isset($_GET["action"]) && $_GET["action"] == "modification") {
        $photo_bdd = $_POST['photo_actuelle'];
    }
    if (!empty($_FILES['photo']['name'])) {
        if (verification_extension_photo()) {
            $nom_photo = $_POST['titre'] . "-" . $_FILES['photo']['name'];
            $photo_bdd = "img/" . $nom_photo;
            $photo_dossier = RACINE_SERVEUR . RACINE_SITE . "img/" . $nom_photo;
            copy($_FILES['photo']['tmp_name'], $photo_dossier);
        } else {
            $msg .= "<div class='erreur'><p>format invalide</p></div>";
        }
    }
    if (empty($msg)) {
        $msg .= "<div class='validation'><p>modification effectué</p></div>";

        lance_requete("UPDATE salle SET pays = '$_POST[pays]',
            ville = '$_POST[ville]',  
            adresse = '$_POST[adresse]', 
            cp = '$_POST[cp]',
            latitude = '$_POST[latitude]', 
            longitude = '$_POST[longitude]', 
            titre = '$_POST[titre]', 
            description = '$_POST[description]', 
            photo = '$photo_bdd',
            capacite = '$_POST[capacite]'
            WHERE id_salle = '$_POST[id_salle]'");

        $_GET['action'] = "affichage";

    }

}
//------ Suppression des salles" --
if (isset($_GET['action']) && $_GET['action'] == "suppression") {
    lance_requete("UPDATE salle SET situation = 'inactif' WHERE id_salle ='$_GET[id]'");
    execute_requete("UPDATE produit SET etat= 2 WHERE etat = 0 AND id_salle='$_GET[id]'");
    $msg .= "<div class='validation'><p>Salle supprimé ainsi que reservations lié</p></div>";
    $_GET['action'] = "affichage";
}


include("../inc/haut_de_site.inc.php");
include("../inc/menu.inc.php");
echo $msg;


// --------- Affichage des salles --------------

if ((isset($_GET['action']) && $_GET['action'] == 'affichage') || !isset($_GET['action'])) {
    echo "<div class='choixadmin'>";
    echo "<button><a href='?action=ajout'>AJOUTER UNE SALLE<i class=\"fas fa-plus\"></i></a></button><br>";
    echo "</div>";

    echo "<hr><h2>AFFICHAGE DES SALLES <hr></h2>";
    echo "<div class='gestionsalle'>";

    $resultat = execute_requete("SELECT id_salle AS id, pays, ville, adresse, cp, latitude, longitude, titre, description, photo, capacite AS capacité FROM salle WHERE situation = 'actif'");

    echo "<p>NOMBRE DE SALLES : " . $resultat->num_rows . "</p>";

    echo "<table>";
    echo "<tr>";

    while ($colonne = $resultat->fetch_field()) {
        echo "<th>" . $colonne->name . "</th>";
    }
    echo "<th>modifier</th>";
    echo "<th>supprimer</th>";
    echo "</tr>";
    while ($ligne = $resultat->fetch_assoc()) {
        echo "<tr>";
        foreach ($ligne as $indice => $valeur) {
            if ($indice == "photo") {
                echo "<td><img src='../" . $valeur . "'/></td>";
            } else {
                echo "<td>" . $valeur . "</td>";
            }
        }

        echo "<td><a href='?action=modification&id=$ligne[id]'><img style='height:20px;width:20px;' src='../img/modif.png'/></a></td>";
        echo "<td><a href='?action=suppression&id=$ligne[id]' onClick=\"return(confirm('des reservations sont potentiellement lié a cette salle et seront supprimé, merci de valider la suppression'));\"><img src='../img/poubelle.png'/></a></td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";

}

///////////////////////formulaire///ajout et modif//////////////////////////////


if (isset($_GET['action']) && ($_GET['action'] == "ajout" || $_GET['action'] == "modification")) {
    echo "<div class='choixadmin'>";
    echo "<a href='?action=affichage'>AFFICHAGE DES SALLES</a>";
    echo "</div>";
    if (isset($_GET['id'])) {
        $resultat = information_sur_une_salle($_GET['id']);
        $salle_a_modifier = $resultat->fetch_assoc();
        $_POST = $salle_a_modifier;
        echo "<hr><h2>MODIFIER UNE SALLE</h2><hr>";
    } else echo "<hr><h2>AJOUTER UNE SALLE</h2><hr>";
    ?>
    <div id="ajout_salle">
        <form method="post" action="" enctype="multipart/form-data">
            <input type="hidden" name="id_salle"
                   value="<?php if (isset($_POST['id_salle'])) echo $_POST['id_salle']; ?>"/>

            <label for="pays">pays</label>
            <input type="text" name="pays" id="pays" value="<?php if (isset($_POST['pays'])) echo $_POST['pays']; ?>"/>


            <label for="ville">ville</label>
            <input type="text" name="ville" id="ville"
                   value="<?php if (isset($_POST['ville'])) echo $_POST['ville']; ?>"/>


            <label for="adresse">adresse</label>
            <input type="text" name="adresse" id="adresse"
                   value="<?php if (isset($_POST['adresse'])) echo $_POST['adresse']; ?>"/>


            <label for="cp">cp</label>
            <input type="text" name="cp" id="cp" value="<?php if (isset($_POST['cp'])) echo $_POST['cp']; ?>"/>


            <label for="latitude">latitude</label>
            <input type="text" name="latitude" id="latitude"
                   value="<?php if (isset($_POST['latitude'])) echo $_POST['latitude']; ?>"/>


            <label for="longitude">longitude</label>
            <input type="text" name="longitude" id="longitude"
                   value="<?php if (isset($_POST['longitude'])) echo $_POST['longitude']; ?>"/>


            <label for="titre">titre</label>
            <input type="text" name="titre" id="titre"
                   value="<?php if (isset($_POST['titre'])) echo $_POST['titre']; ?>"/>


            <label for="description">description</label>
            <textarea
                    name="description"><?php if (isset($_POST['description'])) echo $_POST['description']; ?></textarea>


            <label for="photo">Photo</label>
            <input type="file" name="photo" id="photo"/>
            <br/>

            <?php
            if (isset($_POST['photo'])) {
                echo 'Photo actuel: <img src="../' . $_POST['photo'] . '" height= 100 width=200>';
                echo '<input type="hidden" name="photo_actuelle" value="' . $_POST['photo'] . '">';
            }
            ?>
            <label for="capacite">Capacite</label>
            <input type="text" name="capacite" id="capacite"
                   value="<?php if (isset($_POST['capacite'])) echo $_POST['capacite']; ?>"/>
            <br/>


            <input type="submit" name="valider" value="<?php echo($_GET['action']); ?>"/>
            <a href="gestion_salles.php">Retour</a>
            <br/>
        </form>
    </div>
    <div class="clavier">
        <img src="<?php print RACINE_SITE; ?>/img/inscription-en-ligne-ouvertes-event.jpg"/>
    </div>

    <?php

}


include("../inc/footer.inc.php");
?>			
				


	