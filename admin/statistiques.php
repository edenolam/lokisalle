<?php
include("../inc/init.inc.php");

if (!connecte_et_est_admin()) {
    header("location:" . RACINE_SITE . "/index.php");
    exit();
}

if (isset($_GET['action']) && $_GET['action'] == "afficher") {
    if ($_GET['filtre'] == "note") {
        $stats = lance_requete("SELECT round(AVG(a.note)) AS valeur, s.titre AS nom
							  FROM avis a, salle s
							  WHERE s.id_salle = a.id_salle
							  GROUP BY a.id_salle
							  ORDER BY a.note DESC
							  OFFSET 0,5
							");
        $slang = "/10";
    }

    if ($_GET['filtre'] == "vendu") {
        $stats = lance_requete("SELECT  COUNT(*) AS valeur, s.titre AS nom
							  FROM produit p, salle s, details_commande dc
							  WHERE s.id_salle = p.id_salle
							  AND p.id_produit = dc.id_produit
							  GROUP BY s.id_salle
							  ORDER BY COUNT(*) DESC
							  OFFSET 0,5
							");
        $slang = " #";
    }

    if ($_GET['filtre'] == "quantite") {
        $stats = lance_requete("SELECT  COUNT(*) AS valeur, m.pseudo AS nom
							  FROM membre m, details_commande dc, commande c
							  WHERE c.id_membre = m.id_membre
							  AND c.id_commande = dc.id_commande
							  GROUP BY m.id_membre
							  ORDER BY COUNT(*) DESC
							  LIMIT 0,5
							");
        $slang = " achat";
    }

    if ($_GET['filtre'] == "prix") {
        $stats = lance_requete("SELECT  ROUND(SUM(c.prix),2) AS valeur, m.pseudo AS nom
							  FROM membre m, commande c
							  WHERE c.id_membre = m.id_membre
							  GROUP BY c.id_membre
							  ORDER BY ROUND(SUM(c.prix),2) DESC
							  LIMIT 0,5
							");
        $slang = " €";
    }
}

include("../inc/haut_de_site.inc.php");
include("../inc/menu.inc.php");
echo $msg;
?>

<hr><h2>STATISTIQUES</h2>
<hr>
<div class="stat">
    <p><a href="?action=afficher&filtre=note">*TOP 5 DES SALLES LES MIEUX NOTÉ*</a></p>
    <p><a href="?action=afficher&filtre=vendu">*TOP 5 DES SALLES LES PLUS VENDU*</a></p>
    <p><a href="?action=afficher&filtre=quantite">*TOP 5 DES MEMBRES QUI ACHÈTENT LE PLUS DE PRODUITS*</a></p>
    <p><a href="?action=afficher&filtre=prix">*TOP 5 DES MEMBRES QUI ACHÈTENT LE PLUS CHER*</a></p>
</div>
<div class="top">
    <ul>
        <?php if (isset($stats[1])):
            foreach ($stats as $top): ?>
                <li><p>*<?php print $top['nom'] . " - " . $top['valeur'] . $slang; ?></p></li>
            <?php endforeach;
        elseif (!empty($stats)):?>
            <li><p>*<?php print $stats['nom'] . " - " . $stats['valeur'] . $slang; ?></p></li>
        <?php endif; ?>
    </ul>
</div>

<?php

include("../inc/footer.inc.php");

?>
