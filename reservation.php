<?php
include("inc/init.inc.php");

$produits = execute_requete("SELECT p.id_produit, DATE_FORMAT(p.date_arrivee,'%d %M %Y') 
                                as date_arrivee, DATE_FORMAT(p.date_depart,'%d %M %Y') 
                                as date_depart, p.prix, s.ville, s.titre, s.photo, s.capacite
                                FROM produit p, salle s
                                WHERE p.id_salle = s.id_salle
                                AND p.etat=0
                                AND p.date_arrivee >= NOW()
                                ");


include("inc/haut_de_site.inc.php");
include("inc/menu.inc.php");
?>

    <div id="reservation">
        <hr>
        <h2>TOUTES NOS OFFRES</h2>
        <hr>
        <?php include("inc/diffusion_produit.inc.php"); ?>
    </div>


<?php

include("inc/footer.inc.php");

?>
