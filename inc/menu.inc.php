<nav class="topnav" id="myTopnav">


    <?php
    if (connecte_et_est_admin()) // si admin est connecté
    {
        echo "<a href='" . RACINE_SITE . "index.php'>ACCUEIL</a>";
        echo "<a href='" . RACINE_SITE . "reservation.php'>RESERVATION</a>";
        echo "<a href='" . RACINE_SITE . "recherche.php'>RECHERCHE</a>";
        echo "<a href='" . RACINE_SITE . "connexion.php?action=deconnexion'>DECONNEXION</a>";
        echo "<a href='" . RACINE_SITE . "profil.php'>VOIR VOTRE PROFIL</a>";
        echo "<a href='" . RACINE_SITE . "panier.php'>VOIR VOTRE PANIER</a>";
        echo "<a href='" . RACINE_SITE . "admin/gestion_salles.php'>GESTION DES SALLES</a>";
        echo "<a href='" . RACINE_SITE . "admin/gestion_produits.php'>GESTION DES PRODUITS</a>";
        echo "<a href='" . RACINE_SITE . "admin/gestion_membres.php'>GESTION DES MEMBRES</a>";
        echo "<a href='" . RACINE_SITE . "admin/gestion_commandes.php'>GESTION DES COMMANDES</a>";
        echo "<a href='" . RACINE_SITE . "admin/gestion_avis.php'>GESTION DES AVIS</a>";
        echo "<a href='" . RACINE_SITE . "admin/gestion_promos.php'>GESTION CODES PROMO</a>";
        echo "<a href='" . RACINE_SITE . "admin/statistiques.php'>STATISTIQUES</a>";
        echo "<a href='" . RACINE_SITE . "admin/envoi_newsletter.php'>ENVOYER LA NEWSLETTER</a>";
    } elseif (connecte())// membres et admin connecté
    {
        echo "<a href='" . RACINE_SITE . "index.php'>ACCUEIL</a>";
        echo "<a href='" . RACINE_SITE . "reservation.php'>RESERVATION</a>";
        echo "<a href='" . RACINE_SITE . "recherche.php'>RECHERCHE</a>";
        echo "<a href='" . RACINE_SITE . "connexion.php?action=deconnexion'>DECONNEXION</a>";
        echo "<a href='" . RACINE_SITE . "profil.php'>VOIR VOTRE PROFIL</a>";
        echo "<a href='" . RACINE_SITE . "panier.php'>VOIR VOTRE PANIER</a>";

    } else // visiteurs
    {
        echo "<a href='" . RACINE_SITE . "index.php'>ACCUEIL</a>";
        echo "<a href='" . RACINE_SITE . "reservation.php'>RESERVATION</a>";
        echo "<a href='" . RACINE_SITE . "recherche.php'>RECHERCHE</a>";
        echo "<a href='" . RACINE_SITE . "connexion.php'>SE CONNECTER</a>";
        echo "<a href='" . RACINE_SITE . "inscription.php'>CRÉER UN NOUVEAU COMPTE</a>";
    }
    ?>
</nav>

<section>
