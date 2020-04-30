<?php
include("inc/init.inc.php");
include("inc/haut_de_site.inc.php");
include("inc/menu.inc.php");

creation_panier();

// ------------ POST: ajout panier ------------------
if (isset($_GET['id_produit'])) {
    $id = (int)$_GET['id_produit'];
    $resultat = execute_requete("SELECT id_produit, produit.id_salle, titre, photo, 
                                             DATE_FORMAT(date_arrivee,'%d %M %Y')
                                             AS date_arrivee,DATE_FORMAT(date_depart,'%d %M %Y') 
                                             AS date_depart, ville, prix, capacite, id_promotion 
                                             FROM produit 
                                             JOIN salle 
                                             ON produit.id_salle = salle.id_salle 
                                             WHERE id_produit = '$id' 
                                             AND etat = 0
                                              ");


    $produit = $resultat->fetch_assoc();

    if ($produit) {
        ajout_panier(
            $produit['id_produit'],
            $produit['titre'],
            $produit['photo'],
            $produit['ville'],
            $produit['capacite'],
            $produit['date_arrivee'],
            $produit['date_depart'],
            $produit['prix'],
            $produit['id_promotion']);
        header("location: panier.php");

    }


}

// ------------- Action: Vider panier ------------
if (isset($_GET['action']) && $_GET['action'] == "vider") {
    unset($_SESSION['panier']);
    header("location: panier.php");
}

// ------ Action: retirer produit du panier --------
if (isset($_GET['action']) && $_GET['action'] == "retirer") {
    retirer_produit_panier($_GET["id"]);
}


// ------------- POST: code promo --------------------
if (isset($_POST['appliquer']) && !empty($_POST['promo'])) {
    securise();
    $code = lance_requete("SELECT * FROM promotion WHERE code_promo = '$_POST[promo]'");

    if (empty($code)) {
        $msg .= "<div class='erreur'><p>code promo invalide.</p></div>";
    } else {
        $count = count($_SESSION['panier']['produit']);
        for ($i = 0; $i < $count; $i++) {
            if ($code['id_promotion'] == $_SESSION['panier']['code_promo'][$i] && $_SESSION['panier']['prix'][$i] == $_SESSION['panier']['reduction'][$i]) {
                $_SESSION['panier']['reduction'][$i] = code_reduc($_SESSION['panier']['prix'][$i], $code['reduction']);
            }
        }
    }

}

// ------------- POST: payer --------------------

if (isset($_POST['payer'])) {
    securise();
    if (isset($_POST['cgv'])) {
        for ($i = 0; $i < count($_SESSION['panier']['produit']); $i++) {
            $resultat = information_sur_un_produit($_SESSION['panier']['produit'][$i]);
            $produit = $resultat->fetch_assoc();
            if ($produit['etat'] == 1) {
                echo "<div class='erreur'><p>Votre produit n'est plus disponible.</p></div>";
                retirer_article_panier($_SESSION['panier']['produit'][$i]);
                $i--;
            }
        }
    } else {
        $msg .= "<div class='erreur'><p>veuillez accepter les conditions general de vente afin de valider votre panier</p></div>";
        $erreur = true;
    }

    if (!isset($erreur)) {
        execute_requete("INSERT INTO commande (id_membre, prix, date) VALUES (" . $_SESSION['utilisateur']['id_membre'] . " ,'" . prix_total() . "', now())");
        $id_commande = $mysqli->insert_id;
        $count = count($_SESSION['panier']['produit']);
        for ($i = 0; $i < $count; $i++) {
            $id = $_SESSION['panier']['produit'][$i];
            $prix = lance_requete("SELECT prix FROM produit WHERE  id_produit = $id");
            $prix = $prix['prix'];
            execute_requete("INSERT INTO details_commande (id_commande, id_produit) VALUES ( $id_commande ,$id)");
            execute_requete("UPDATE produit SET etat=1 WHERE id_produit= $id ");
        }

        unset($_SESSION['panier']);

        echo "<div class='validation'><p>Merci pour votre commande. Votre n° de suivi est le: $id_commande </p></div>";

        mail($_SESSION['utilisateur']['email'], "confirmation de commande", "Merci pour votre commande. Votre n° de suivi est le: $id_commande", "From: admin@lokisalle.fr");

    }

}
// ------------- Affichage du panier ------------


echo $msg;

echo "<div class='panier'>";

if (empty($_SESSION['panier']['produit'])) {
    echo "<hr><h2>PANIER</h2><hr>";
    echo "<table><tr><td colspan=10>Votre Panier est vide</td></tr></table>";
} else {
    echo "<table border=1>";
    echo "<hr><h2>PANIER</h2><hr>";
    echo "<tr>
		<th>produit</th>
		<th>salle</th>
		<th>photo</th>
		<th>ville</th>
		<th>capacitée</th>
		<th>arrivée</th>
		<th>depart</th>
		<th>retirer</th>
		<th>prix HT</th>
		<th>TVA</th>
		<th>TTC</th>
	
		</tr>";


    for ($i = 0; $i < count($_SESSION['panier']['produit']); $i++) {

        $url = $_SESSION['panier']['photo'][$i];

        echo "<tr>";
        echo "<td>" . $_SESSION['panier']['produit'][$i] . "</td>";
        echo "<td>" . $_SESSION['panier']['titre'][$i] . "</td>";
        echo "<td><img src=' $url' width='100px'></td>";
        echo "<td>" . $_SESSION['panier']['ville'][$i] . "</td>";
        echo "<td>" . $_SESSION['panier']['capacite'][$i] . " personnes</td>";
        echo "<td>" . $_SESSION['panier']['date_arrivee'][$i] . "</td>";
        echo "<td>" . $_SESSION['panier']['date_depart'][$i] . "</td>";
        echo "<td><a href='?action=retirer&id=" . $_SESSION['panier']['produit'][$i] . "'><img style='height:20px;width:20px;' src='img/poubelle.png'/></a></td>";
        echo "<td>" . $_SESSION['panier']['prix'][$i] . " €</td>";
        echo "<td>  20 %</td>";
        echo "<td>" . $_SESSION['panier']['prix'][$i] * 1.2 . " €</td>";


        echo "</tr>";
    }
    $reduc = prix_total_base() - prix_total();


    echo "<tr><td style='text-align:justify; padding-left:10px;' colspan=10>prix total TTC :</td><td colspan=1>" . prix_total_base() . " €</td></tr>";
    if (prix_total() != prix_total_base()) {
        echo "<tr><td style='text-align:justify; padding-left:10px;' colspan=10>code reduction inclus:</td><td colspan=1>-" . prix_total() . " €</td></tr>";
        echo "<tr><td style='text-align:justify; padding-left:10px;' colspan=10>reste a payer:</td><td colspan=1>" . $reduc . " €</td></tr>";
    }


    echo "</table>";
    if (connecte()) {
        ?>
        <div id="panierform">
            <form method='post'>
                <a href='?action=vider'>*vider mon panier
                    <hr style="color: #c4c4c4; width: 400px; float: left;">
                </a>


                <br>
                <label for='promo'>utiliser un code promo : </label>
                <input type='text' id='promo' name='promo' value=''/>
                <input type='submit' name='appliquer' value='appliquer'/>
            </form>
        </div>
        <div id="panierform">
            <form method='post'>
                <label for='cgv'>j accepte les conditions general de vente(<a href='cgv.php'>voir</a>)</label>
                <input style="display:table-caption;" type='checkbox' id='cgv' name='cgv' value='cgv'/>


                <input type='submit' name='payer' value='Payer'/>
            </form>
        </div>


        <?php
    } else {
        echo '<table><tr><td colspan=10>Veuillez vous <a href="connexion.php">connecter</a> ou vous 
			<a href="inscription.php">inscrire</a> pour acceder au paiment.</td></tr></table>';
    }
}
echo "</div>";
echo "<div class='modalite'>";
echo "<p>Tous nos articles sont calculés avec un taux de TVA à 20%</p> ";
echo "<p>Paiment : Par chèque uniquement</p>";
echo "<p>Réglement à l'adresse suivante : LOKISALLE - 1 rue Boswellia, 75000 Paris, FRANCE<hr color = c4c4c4 width = 400 ></p>";
echo "<p></p>";


if (connecte()) {
    echo '<p>Votre adresse de facturation : ' . $_SESSION['utilisateur']['adresse'] . " - " . $_SESSION['utilisateur']['cp'] . " - " . $_SESSION['utilisateur']['ville'];
    '</p>';
}
echo "</div>";

include("inc/footer.inc.php");
?>

	
