<?php
include("inc/init.inc.php");

if(connecte()){
    header("location:index.php");
}

if(isset($_POST['reinitialiser'])){
    securise();
    $mail=lance_requete("SELECT * FROM membre where email='$_POST[back_mail]'");
    if($mail){
        $nouveau_mdp = mdp_reinit(5);
        lance_requete("UPDATE membre SET mdp = '$nouveau_mdp' WHERE email = '$_POST[back_mail]'");
        mail("$_POST[back_mail]", "Réinitialisation de votre mot de passe", "voici votre mot de passe temporaire:". $nouveau_mdp . "", "From: lokisalle.fr");
        $msg.= '<div class="validation">votre nouveau mot de passe vient de vous etre envoyé, veuillez verifier votre boite mail.</div>';
    }
    else{
            $msg.= '<div class="erreur">Email invalide</div>';
        }
}
	

include("inc/haut_de_site.inc.php");
include("inc/menu.inc.php");
echo $msg;
?>

		<div id="pw_new">
			<form class="back_mdp" method="post" action="" enctype="">
                <h2>Mot de passe oublié</h2>
                <p>Veuillez entrer votre email, afin de réinitialiser votre mot de passe.</p>
                <div>
                    <label>Email</label>
                    <input type="email" name="back_mail" id="back_mail" >
                </div>
                <div class="sub">
                    <input type="submit" id="reinitialiser" name="reinitialiser" value="Réinitialiser" >
                    <a href="connexion.php" rel="connexion" class="direct">Connexion</a>
                </div>
			</form>
						
			<div class="inscri_ici">
			<a href="inscription.php" >Vous n'avez pas encore de compte? Inscrivez vous ici.</a>
			</div>
		</div>
					
				

<?php

include("inc/footer.inc.php");

?>