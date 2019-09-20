<ul class="gener_produit">
    <?php

    if (mysqli_num_rows($produits) === 0) {
        echo '<h3>pas de produits disponible</h3>';
    }
    ?>
    <?php foreach ($produits as $produit) : ?>
        <li>
            <h3><?php echo $produit['titre'] . ' - ' . $produit['ville']; ?></h3>

            <img src="<?php echo $produit['photo']; ?>" alt="<?php echo $produit['titre']; ?>">

            <div class="produit_info">
                <hr style="color: #C4C4C4; width: 150px;">
                <p>du <span><?php echo $produit['date_arrivee']; ?></span></p>
                <p>au <span><?php echo $produit['date_depart']; ?></span></p>
                <p>capacité: <span><?php echo $produit['capacite']; ?></span> personnes</p>
                <p class="prix"><span><?php echo $produit['prix']; ?> €</span></p>
                <hr style="color: #C4C4C4; width: 150px;">
            </div>
            <div class="lien">
                <a href="<?php echo RACINE_SITE; ?>details_reservation.php?id_produit=<?php echo $produit['id_produit']; ?>">Voir
                    la fiche détaillé</a> |
                <?php if (connecte()): ?>
                    <a href="<?php echo RACINE_SITE; ?>panier.php?id_produit=<?php echo $produit['id_produit']; ?>">Ajouter
                        au panier</a>
                <?php else: ?>
                    <a href="<?php echo RACINE_SITE; ?>connexion.php?id_produit=<?php echo $produit['id_produit']; ?>">Connectez
                        vous pour ajouter au panier</a>
                <?php endif; ?>
            <div>
            <br/>
            <br/>
        </li>
    <?php endforeach; ?>


</ul>