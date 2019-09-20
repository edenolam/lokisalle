<?php
include("../inc/init.inc.php");

if (!connecte_et_est_admin()) {
    header("location:../connexion.php");
    exit();
}


include("../inc/haut_de_site.inc.php");
include("../inc/menu.inc.php");
echo $msg;


// --------- Affichage des commandes --------------


echo "<div class='commande'><hr><h2>AFFICHAGE DES COMMANDES</h2><hr>";
$resultat = execute_requete("SELECT id_commande, id_membre, prix FROM commande");
echo "<p>TOTAL DES COMMANDES :" . $resultat->num_rows . "</p>";
echo "<table>";
echo "<tr>";
while ($colonne = $resultat->fetch_field()) {
    echo "<th>" . $colonne->name . "</th>";
}


echo "</tr>";

while ($ligne = $resultat->fetch_assoc()) {
    echo "<tr>";
    foreach ($ligne as $indice => $valeur) {
        if ($indice == 'id_commande') {
            echo "<td><a href='details_commandes.php?id=$valeur' >" . $valeur . "</a></td>";
        } else {
            echo "<td>" . $valeur . "</td>";
        }
    }
    echo "</tr>";
}
echo "</table>";
echo "</div>";

include("../inc/footer.inc.php");

