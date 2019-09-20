<?php

include("../inc/init.inc.php");

if (!connecte_et_est_admin()) {
    header("location../connexion.php");
    exit();
}
// ----------- creation des produits//////////////////////////////////////////////////
if ($_POST && isset($_GET['action']) && $_GET['action'] == "ajout") {
    $originalDate = $_POST['date_arrivee'];
    $dateArrivee = date("Y-m-d", strtotime($originalDate));
    $originalDate = $_POST['date_depart'];
    $dateDepart = date("Y-m-d", strtotime($originalDate));
    $dateServer = date("Y-m-d");
    $postsalle = $_POST['salle'];
    $verif_date = lance_requete("SELECT DATE_FORMAT(date_arrivee,'%Y-%m-%d') as date_arrivee, DATE_FORMAT(date_depart,'%Y-%m-%d') as date_depart FROM produit WHERE id_salle = $postsalle AND etat = 0");
    if (isset($verif_date[1])) {
        foreach ($verif_date as $date) {
            /*echo $dateArrivee."-".$date['date_arrivee'] . "-" . $date['date_depart'] . "<br>";*/
            if ($dateArrivee >= $date['date_arrivee'] && $dateArrivee <= $date['date_depart']) {
                $msg = "<div class='erreur'><p>date arrivée non disponible</p></div>";
                $erreur = true;
            }

            if ($dateDepart >= $date['date_arrivee'] && $dateDepart <= $date['date_depart']) {
                $msg = "<div class='erreur'><p>date depart non disponible</p></div>";
                $erreur = true;
            }

            if ($dateArrivee <= $date['date_arrivee'] && $dateDepart >= $date['date_depart']) {
                $msg = "<div class='erreur'><p>date depart et date arrivee non disponible</p></div>";
                $erreur = true;
            }

        }
    } elseif (!empty($verif_date)) {

        if ($dateArrivee >= $verif_date['date_arrivee'] && $dateArrivee <= $verif_date['date_depart']) {
            $msg = "<div class='erreur'><p>date arrivée non disponible</p></div>";
            $erreur = true;
        }

        if ($dateDepart >= $verif_date['date_arrivee'] && $dateDepart <= $verif_date['date_depart']) {
            $msg = "<div class='erreur'><p>date depart non disponible</p></div>";
            $erreur = true;
        }

        if ($dateArrivee <= $verif_date['date_arrivee'] && $dateDepart >= $verif_date['date_depart']) {
            $msg = "<div class='erreur'><p>date de depart et arrivee non disponible</p></div>";
            $erreur = true;
        }
    }

    if ($dateDepart > $dateArrivee && $dateArrivee > $dateServer && !isset($erreur)) {
        $succes = execute_requete("INSERT INTO produit (date_arrivee, date_depart, prix, etat, id_salle, id_promotion)  VALUES ('$dateArrivee', '$dateDepart', '$_POST[prix]', 0, '$_POST[salle]', '$_POST[code_promo]') ");
        if ($succes) {
            $msg .= "<div class='validation'><p>produit crée avec succes</p></div>";
        }
        $_GET['action'] = "affichage";
    } else {
        $msg .= "<div class='erreur'><p>date invalide</p></div>";
    }
}

// ----------- modification des produits ---------------///////////////////////////////////////////////////////////////////////

if ($_POST && isset($_GET['action']) && $_GET['action'] == "modification") {
    $originalDate = $_POST['date_arrivee'];
    $dateArrivee = date("Y-m-d", strtotime($originalDate));
    $originalDate = $_POST['date_depart'];
    $dateDepart = date("Y-m-d", strtotime($originalDate));
    $dateServer = date("Y-m-d");
    $verif_date = lance_requete("
                        SELECT DATE_FORMAT(date_arrivee,'%Y-%m-%d') as date_arrivee, DATE_FORMAT(date_depart,'%Y-%m-%d') as date_depart 
                        FROM produit 
                        WHERE id_salle = '$_POST[salle]'
                        AND etat = 0
                        ");

    if (isset($verif_date[1])) {

        foreach ($verif_date as $date) {
            echo $dateArrivee . "-" . $date['date_arrivee'] . "-" . $date['date_depart'] . "<br>";

            if ($dateArrivee >= $date['date_arrivee'] && $dateArrivee <= $date['date_depart']) {
                $msg = "<div class='erreur'><p>date arrivée non disponible</p></div>";
                $erreur = true;
            }

            if ($dateDepart >= $date['date_arrivee'] && $dateDepart <= $date['date_depart']) {
                $msg = "<div class='erreur'><p>date depart non disponible</p></div>";
                $erreur = true;
            }

            if ($dateArrivee <= $date['date_arrivee'] && $dateDepart >= $date['date_depart']) {
                $msg = "<div class='erreur'><p>date de depart et arrivee non disponible</p></div>";
                $erreur = true;
            }
        }
    } elseif (!empty($verif_date)) {
        if ($dateArrivee >= $verif_date['date_arrivee'] && $dateArrivee <= $verif_date['date_depart']) {
            $msg = "<div class='erreur'><p>date arrivée non disponible</p></div>";
            $erreur = true;
        }

        if ($dateDepart >= $verif_date['date_arrivee'] && $dateDepart <= $verif_date['date_depart']) {
            $msg = "<div class='erreur'><p>date depart non disponible</p></div>";
            $erreur = true;
        }

        if ($dateArrivee <= $date['date_arrivee'] && $dateDepart >= $date['date_depart']) {
            $msg = "<div class='erreur'><p>date de depart et arrivee non disponible</p></div>";
            $erreur = true;
        }
    }


    if ($dateDepart > $dateArrivee && $dateArrivee > $dateServer && !isset($erreur)) {
        $success = execute_requete("
                                            UPDATE produit 
											SET id_salle = '$_POST[salle]', 
															date_arrivee = '" . $dateArrivee . "',
															date_depart = '" . $dateDepart . "',
															prix = '$_POST[prix]', 
															id_promotion = '$_POST[code_promo]'
						
											WHERE id_produit = '$_GET[id]'
											AND etat = 0");
        var_dump($success);


        if ($success) {
            $msg .= "<div class='validation'><p>modification effectué</p></div>";
        }
        $_GET['action'] = 'affichage';
    } else {
        $msg .= "<div class='erreur'><p>date invalide</p></div>";
    }

}

//------ Suppression : url "action=suppression" --
if (isset($_GET['action']) && $_GET['action'] == "suppression") {
    execute_requete("DELETE FROM produit WHERE id_produit='$_GET[id]'");
    $msg .= "<div class='validation'><p>Produit supprimé.</p></div>";
    $_GET['action'] = "affichage";
}

include("../inc/haut_de_site.inc.php");
include("../inc/menu.inc.php");
echo $msg;

// --------- Affichage des produits -------------

if ((isset($_GET['action']) && $_GET['action'] == 'affichage') || !isset($_GET['action'])) {
    echo "<div class='choixadmin'>";
    echo "<button><a href='?action=ajout'>AJOUTER UN PRODUIT <i class=\"fas fa-plus\"></i></a></button>";
    echo "</div>";
    echo "<div class='gestionproduit'><hr><h2>AFFICHAGE DES PRODUITS</h2><hr>";
    if (isset($_GET['order'])) {
        $order = $_GET['order'];
    } else {
        $order = "p.id_produit";
    }
    $resultat = execute_requete("SELECT p.id_produit, s.titre, DATE_FORMAT(p.date_arrivee,'%d %M %Y') as date_arrivee, DATE_FORMAT(p.date_depart,'%d %M %Y') as date_depart, p.prix, p.id_promotion, p.etat
							   FROM produit p LEFT JOIN  salle s
							   ON p.id_salle = s.id_salle
							   WHERE p.etat != 2
							
							   ORDER BY  $order");

    echo "<p>NOMBRE DE PRODUITS :" . $resultat->num_rows . "</p>";
    echo "<table>";
    echo "<tr>";
    while ($colonne = $resultat->fetch_field()) {
        echo "<th><a href='?order=p.id_produit ASC'><img src='../img/bas.png'/></a>" . $colonne->name . "<a href='?order=p.id_produit DESC'><img src='../img/haut.png'/></a></th>";
    }
    echo "<th>modification</th>";
    echo "<th>suppression</th>";
    echo "</tr>";
    while ($ligne = $resultat->fetch_assoc()) {
        echo "<tr>";
        foreach ($ligne as $indice => $valeur) {
            echo "<td>" . $valeur . "</td>";
        }
        if ($ligne['etat'] === 0) {
            echo "<td><a href='?action=modification&id=$ligne[id_produit]'><img style='height:20px;width:20px;' src='../img/modif.png'/></a></td>";
            echo "<td><a href='?action=suppression&id=$ligne[id_produit]' onClick=\"return(confirm('valider la suppression'));\"><img style='height:20px;width:20px;' src='../img/poubelle.png'/></a></td>";
        }
        if ($ligne['etat'] === 1) {
            echo "<td>produit vendu<br>non modifiable</td>";
            echo "<td>produit vendu<br>non supprimable</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
}


if (isset($_GET['action']) && ($_GET['action'] == "ajout" || $_GET['action'] == "modification")) {
    echo "<div class='choixadmin'>";
    echo "<a href='?action=affichage'>AFFICHAGE DES PRODUITS</a>";
    echo "</div>";
    if (isset($_GET['id'])) {
        $resultat = information_sur_un_produit($_GET['id']);
        $produit_a_modifier = $resultat->fetch_assoc();
        echo "<hr><h2>MODIFIER UN PRODUIT</h2><hr>";
    } else echo "<hr><h2>AJOUTER UN PRODUIT</h2><hr>";
    ?>
    <div id="ajout_produit">

        <form method="post" action="">

            <select name='salle'>

                <?php

                $resultat = execute_requete("SELECT id_salle, pays, ville, adresse, cp, titre, categorie  FROM salle WHERE situation = 'actif'  ");
                while ($ligne = $resultat->fetch_assoc()) {
                    $msg = '';
                    foreach ($ligne as $index => $value) {
                        $msg .= $value . " - ";
                    }
                    echo "<option style='padding:10px;' value='$ligne[id_salle]'>$msg</option>";
                }
                ?>
            </select>

            <label for="date_arrivee">arrivee</label>
            <input type="text" name="date_arrivee" id="date_arrivee"
                   value="<?php if (isset($_POST['date_arrivee'])) echo $_POST['date_arrivee']; ?>"
                   placeholder=" jj-mm-AAAA "/>

            <label for="date_depart">depart</label>
            <input type="text" name="date_depart" id="date_depart"
                   value="<?php if (isset($_POST['date_depart'])) echo $_POST['date_depart']; ?>"
                   placeholder="jj-mm-AAAA"/>

            <label for="prix">prix</label>
            <input type="text" name="prix" id="prix" value="<?php if (isset($_POST['prix'])) echo $_POST['prix']; ?>"
                   placeholder="ex: 1000€"/>

            <label for="promotion">attribution remise parmi le codes promo existant</label>

            <select name='code_promo'>
                <option value='NULL'>Aucun</option>

                <?php
                $resultat = execute_requete("SELECT * FROM promotion ");
                while ($ligne = $resultat->fetch_assoc()) {
                    $msg = '';
                    foreach ($ligne as $index => $value) {
                        $msg .= $value . " - ";
                    }
                    echo "<option value='$ligne[id_promotion]'>$msg</option>";
                }
                ?>
            </select>

            <input type="submit" name="valider" value="<?php echo($_GET['action']); ?>"/>
            <a href="gestion_produits.php">Retour</a>
        </form>
    </div>
    <div class="produit">
        <img src="<?php print RACINE_SITE; ?>/img/inscription-en-ligne-ouvertes-event.jpg"/>
    </div>

    <?php

}
include("../inc/footer.inc.php");
?>