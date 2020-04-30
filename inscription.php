﻿<?php
include("inc/init.inc.php");

if (isset($_POST['valider'])) {
    $verif_caractere = preg_match("#^[a-zA-Z0-9_-]+$#", $_POST['pseudo']);
    if (!$verif_caractere && !empty($_POST['pseudo'])) {
        $msg .= "<div class='erreur'><p>Caractère accepté : A - Z et 0 - 9</p></div>";
    }
    if (strlen($_POST['pseudo']) > 10 || strlen($_POST['pseudo']) < 4) {
        $msg .= "<div class='erreur'><p>Le pseudo doit avoir  4 a 10 caractères</p></div>";
    }
    if (strlen($_POST['mdp']) > 10 || strlen($_POST['mdp']) < 4) {
        $msg .= "<div class='erreur'><p>Le mot de passe doit avoir  4 a 10 caractères</p></div>";
    }
    if (empty($msg)) {
        $membre = execute_requete("SELECT * FROM membre WHERE pseudo='$_POST[pseudo]'");
        if ($membre->num_rows > 0) {
            $msg .= "<div class='erreur'><p>Pseudo indisponible</p></div>";
        } else {
            foreach ($_POST as $indice => $valeur) {
                $_POST[$indice] = htmlentities($valeur);
            }

            execute_requete("INSERT INTO membre (pseudo, mdp, nom, prenom, email, sexe, ville, cp, adresse,statut) 
									 VALUES ('$_POST[pseudo]', '$_POST[mdp]', '$_POST[nom]', '$_POST[prenom]','$_POST[email]', 
									 '$_POST[sexe]', '$_POST[ville]', '$_POST[cp]', '$_POST[adresse]', 0)");
            $msg .= "<div class='validation'><p>Inscription validée</p></div>";
            header("location:connexion.php");
        }
    }
}
include("inc/haut_de_site.inc.php");
include("inc/menu.inc.php");
echo $msg;
?>
<hr style="color:#c4c4c4; width:400px;"><h3>INSCRIPTION</h3>
<hr style="color:#c4c4c4; width:400px;">
<div id="inscription">
    <form method="POST" action="connexion.php" enctype="multipart/form-data">

        <label for="pseudo">Pseudo</label>
        <input type="text" name="pseudo" id="pseudo"
               value="<?php if (isset($_POST['pseudo'])) echo $_POST['pseudo']; ?>"/>

        <label for="Password">password</label>
        <input type="password" name="mdp" id="mdp" value="<?php if (isset($_POST['mdp'])) echo $_POST['mdp']; ?>"/>


        <label for="prenom">Prénom</label>
        <input type="text" name="prenom" id="prenom"
               value="<?php if (isset($_POST['prenom'])) echo $_POST['prenom']; ?>"/>

        <label for="nom">Nom</label>
        <input type="text" name="nom" id="nom" value="<?php if (isset($_POST['nom'])) echo $_POST['nom']; ?>"/>


        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"/>
        <label>Sexe</label>
        <label>homme</label>
        <input style="display: inline;" type="radio" name="sexe" value="m"
            <?php if (isset($_POST['sexe']) && $_POST['sexe'] == "m") echo "checked";
            elseif (!isset($_POST['sexe'])) echo "checked"; ?> />
        <label>femme</label>
        <input style="display: inline;" type="radio" name="sexe" value="f"
            <?php if (isset($_POST['sexe']) && $_POST['sexe'] == "f") echo "checked"; ?>/>


        <label for="ville">Ville</label>
        <input type="text" name="ville" id="ville" value="<?php if (isset($_POST['ville'])) echo $_POST['ville']; ?>"/>

        <label for="cp">Cp </label>
        <input type="text" name="cp" id="cp" value="<?php if (isset($_POST['cp'])) echo $_POST['cp']; ?>"/>

        <label for="adresse">Adresse</label>
        <textarea style="max-height:50px;" type="text" name="adresse"
                  id="adresse"><?php if (isset($_POST['adresse'])) echo $_POST['adresse']; ?>
			</textarea>

        <input type="submit" value="valider" name="valider"/>
        <p>En cliquant sur Inscription, vous acceptez nos <a href="cgv.php">Conditions</a> et indiquez que vous avez lu
            notre <a href="mention_legales.php"> Politique d’utilisation</a> de données</p>
    </form>
</div>
<div class="clavier">
    <img src="<?= RACINE_SITE; ?>/img/inscription-en-ligne-ouvertes-event.jpg"/>
</div>


<?php

include("inc/footer.inc.php");
?>
