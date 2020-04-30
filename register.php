<?php
include("inc/init.inc.php");

if (connecte()) {
    $id_session = $_SESSION['utilisateur']['id_membre'];

    if (isset($_POST['inscription'])) {
        if (isset($_POST['checkbox'])) {
            lance_requete("INSERT INTO newsletter(id_membre) VALUES ($id_session)");
            $msg .= '<div class="validation"><p>votre iscription a la newsletter a ete pris en compte</p></div>';
        }
    }


    if (isset($_POST['desinscription'])) {
        if (isset($_POST['checkbox_2'])) {
            lance_requete("DELETE FROM newsletter WHERE id_membre = $id_session");
            $msg .= '<div class="validation"><p>votre resiliation a ete pris en compte</p></div>';
        }
    }

    $register_newsletter = lance_requete("SELECT id_membre
									FROM newsletter
									WHERE id_membre = $id_session
								");
}


include("inc/haut_de_site.inc.php");
include("inc/menu.inc.php");
echo $msg;

?>
<div id="abo_news">
    <hr style="background-color: #c4c4c4; width: 400px;">
    <h2>NEWSLETTER</h2>
    <hr style="background-color: #c4c4c4; width: 400px;">
    <?php if (connecte() && empty($register_newsletter)) : ?>
        <form method="post" action="" enctype="">
            <p>Je souhaite m'abonner à la newsletter</p>
            <input type="checkbox" id="checkbox" name="checkbox" required>
            <input type="submit" id="inscription" name="inscription" value="M'inscrire">
        </form>
    <?php elseif (connecte() && !empty($register_newsletter)) : ?>
        <form method="post" action="" enctype="multipart/form-data">
            <p>Je souhaite me desinscrire de la newsletter</p>
            <input type="checkbox" id="checkbox_2" name="checkbox_2" required>
            <input type="submit" id="desinscription" name="desinscription" value="Me désinscrire">
        </form>
    <?php else : ?>
        <div class="connect_news">
            <p>Vous devez vous <a style="color:red;" href="connexion.php?action=register">connecter</a> ou <a
                        style="color:red;" href="inscription.php?action=register">créer un compte</a> afin de vous
                abonné à la newsletter</p>
        </div>
    <?php endif; ?>
</div>

<?php

include("inc/footer.inc.php");

?>
