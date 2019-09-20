<?php
include("../inc/init.inc.php");
if (!connecte_et_est_admin()) {
    header("location:../connexion.php");
    exit();
}

include("../inc/haut_de_site.inc.php");
include("../inc/menu.inc.php");


if (isset($_GET['id'])) {
    echo "<div class='detail'><hr><h2>DÉTAIL DE LA COMMANDE</h2><hr>";

    $id = $_GET['id'];
    $resultat = execute_requete(" SELECT dc.id_commande, p.prix, c.date, m.id_membre, m.pseudo, p.id_produit, s.id_salle, s.ville
                                                FROM details_commande dc
                                                JOIN commande c ON c.id_commande = dc.id_commande 
                                                JOIN membre m ON m.id_membre = c.id_membre 
                                                JOIN produit p ON p.id_produit = dc.id_produit 
                                                JOIN salle s ON s.id_salle = p.id_salle 
                                                WHERE dc.id_commande = $id ");


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
                echo "<td><a href='gestion_commandes.php?id=$valeur' >" . $valeur . "</a></td>";
            } else {
                echo "<td>" . $valeur . "</td>";
            }
        }
        echo "</tr>";
    }
    echo "</table>";

    echo "</div>";
} else {
    echo "<div class='erreur'>Erreur : Aucun identifiant indiqué</div>";
}


include("../inc/footer.inc.php");


		
		
		
		
		