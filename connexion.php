<?php


include("inc/init.inc.php");

if (connecte()) {
    header("location:index.php");
}

if (isset($_COOKIE["pseudo"])) {
    $pseudo = lance_requete("SELECT pseudo FROM membre where id_membre='$_COOKIE[pseudo]'");
}

if (isset($_GET['action']) && $_GET['action'] == "deconnexion") {
    unset($_SESSION['utilisateur']);
}

if (isset($_POST['connexion'])) {
    securise();
    $membre = lance_requete("SELECT * FROM membre where pseudo ='$_POST[pseudo]'");
    if ($membre) {
        if ($_POST['pw_login'] == $membre['mdp']) {
            foreach ($membre as $indice => $valeur) {
                if ($indice != "mdp") {
                    $_SESSION['utilisateur'][$indice] = $valeur;
                }
            }
            if (isset($_POST['checkbox'])) {
                $un_an = 365 * 24 * 3600;
                setCookie("pseudo", $membre['id_membre'], time() + $un_an);
            } else {
                setCookie("pseudo", "", time() - 3600);
            }

            if (isset($_GET['id_produit'])) {
                header("location:panier.php?id_produit=" . $_GET['id_produit']);
            } elseif (isset($_GET['com'])) {
                header("location:details_reservation.php?id_produit=" . $_GET['com']);
            } elseif (isset($_GET['action']) && $_GET['action'] == 'newsletter') {
                header("location:register.php");
            } else {
                header("location:index.php");
            }
        } else {
            $msg .= '<div class="erreur"><p>Nom d\'utilisateur ou Mot de passe invalide</p></div>';
        }
    }
}


include("inc/haut_de_site.inc.php");
include("inc/menu.inc.php");
echo $msg;
?>

<hr style="color:#c4c4c4; width:400px;"><h3>CONNEXION</h3>
<hr style="color:#c4c4c4; width:400px;">
<div id="connect">
    <form method="POST" action="" enctype="multipart/form-data">
        <label for="pseudo">Pseudo:</label>
        <input type="text" name="pseudo" id="pseudo" value="<?php if (isset($_COOKIE["pseudo"])) {
            echo $pseudo["pseudo"];
        } ?>"/>
        <label for="pw_login">Mot de passe:</label>
        <input type="password" name="pw_login" id="pw_login"/>
        <p><a href="mdp_oubli.php">Mot de passe oublié ?</a></p>
        <p>Se souvenir de moi</p>
        <input type="checkbox" id="checkbox" name="checkbox" <?php if (isset($_COOKIE["pseudo"])) {
            echo "checked";
        } ?>>
        <input type="submit" name="connexion" value="Connexion">
    </form>
</div>
<div class="inscri_ici">
    <a href="inscription.php">Vous n'avez pas encore de compte? Inscrivez vous ici.*</a>
</div>

<?php

include("inc/footer.inc.php");

?>	
						
					
						
						
						
			
				
				
		


