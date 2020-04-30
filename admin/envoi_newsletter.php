<?php
include("../inc/init.inc.php");

if (!connecte_et_est_admin()) {

    header("location:" . RACINE_SITE . "/index.php");

    exit();
}

$envoi_news = lance_requete("SELECT m.email
							FROM membre m, newsletter n
							WHERE m.id_membre = n.id_membre
							");

if (isset($_POST['envoi_news'])) {
    securise();
    if (isset($envoi_news[1])) {

        foreach ($envoi_news as $membre) {
            mail("$membre[email]", "$_POST[sujet]", "$_POST[message]", "$_POST[newsletter]", "From: " . $_POST['expediteur']);
        }
    } elseif (!empty($envoi_news)) {
        mail("$envoi_news[email]", "$_POST[sujet]", "$_POST[message]", "$_POST[newsletter]", "From: " . $_POST['expediteur']);
    }
    $msg .= "<div class='validation'><p>Newsletter envoyé</p></div>";
}

include("../inc/haut_de_site.inc.php");
include("../inc/menu.inc.php");
echo $msg;
?>

    <hr><h2>ENVOYER LA NEWSLETTER</h2>
    <hr>
    <div class="formnews">
        <p>TOTAL DES MEMBRES ABONNÉ : <?php print count($envoi_news); ?></p>
        <div id="newl">
            <form method="post" action="" enctype="multipart/form-data">
                <label>Expéditeur</label>
                <input type="text" id="expediteur" name="expediteur" required placeholder="e-mail">

                <label>Sujet</label>
                <input type="text" id="sujet" name="sujet" required placeholder="sujet">

                <label>Message</label>
                <textarea id="message" name="message" required placeholder="ajouter"></textarea>

                <label>newsletter</label>
                <input type="file" id="newsletter" name="newsletter" required placeholder="newsletter">

                <input type="submit" id="envoi_news" name="envoi_news" value="envoyer"
                       onClick="return(confirm('confirmation d\'envoi'));">
            </form>
        </div>

        <div class="logonews">
            <img src="<?php print RACINE_SITE; ?>/img/newsletter1.jpg">
        </div>

    </div>


<?php

include("../inc/footer.inc.php");

?>
