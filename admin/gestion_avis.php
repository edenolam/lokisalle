<?php
include("../inc/init.inc.php");
if (!connecte_et_est_admin()) {
    header("location:../connexion.php");
    exit();
}

//------ Suppression : url "action=suppression" --
if (isset($_GET['action']) && $_GET['action'] == "suppression") {
    $resultat = information_sur_un_produit($_GET['id']);
    $avis_a_supprimer = $resultat->fetch_assoc();
    execute_requete("DELETE FROM avis WHERE id_avis='$_GET[id]'");
    $msg .= "<div class='validation'><p>Avis supprimé.</p></div>";
    $_GET['action'] = "affichage";
}


include("../inc/haut_de_site.inc.php");
include("../inc/menu.inc.php");
echo $msg;


// --------- Affichage des avis --------------


echo "<div class='gestion_avis'><hr><h2>GESTION DES AVIS</h2><hr>";
$resultat = execute_requete("SELECT id_avis, id_membre, id_salle, commentaire, note, 
										DATE_FORMAT(date,'%d/%m/%Y %hh%m') as date
										FROM avis");
echo "<p>TOTAL DES AVIS :" . $resultat->num_rows . "</p>";
echo "<table>";
echo "<tr>";

while ($colonne = $resultat->fetch_field()) {
    echo "<th>" . $colonne->name . "</th>";
}

echo "<th>suppression</th>";
echo "</tr>";
while ($ligne = $resultat->fetch_assoc()) {
    echo "<tr>";
    foreach ($ligne as $indice => $valeur) {

        if ($indice == "photo") {
            echo "<td>" . $valeur . "</td>";
        } else {
            echo "<td>" . $valeur . "</td>";
        }
    }

    echo "<td><a href='?action=suppression&id=$ligne[id_avis]' onClick=\"return(confirm('valider la suppression?'));\"><img src='../img/poubelle.png'/></a></td>";
    echo "</tr>";
}
echo "</table>";
echo "</div>";


include("../inc/footer.inc.php");


