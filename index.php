﻿<?php
include("inc/init.inc.php");
include("inc/haut_de_site.inc.php");
include("inc/menu.inc.php");

if (isset($_GET['action']) && $_GET['action'] == "deconnexion") {
    unset($_SESSION['utilisateur']);
}


$produits = execute_requete("SELECT p.id_produit, DATE_FORMAT(p.date_arrivee,'%d %M %Y') AS date_arrivee, DATE_FORMAT(p.date_depart,'%d %M %Y') AS date_depart, p.prix, s.ville, s.titre, s.photo, s.capacite FROM produit p, salle s WHERE p.id_salle = s.id_salle AND p.etat = 0 AND p.date_arrivee >= NOW() ORDER BY id_produit LIMIT 0,3");
?>


<!-- Slideshow container -->
<div class="slideshow-container">

    <!-- Full-width images with number and caption text -->
    <div class="mySlides fade">
        <img src="img/hall1.jpg" style="width:100%;height:260px;">
        <h1 class="text">Donnez du style a vos reunions..</h1>
    </div>

    <div class="mySlides fade">
        <img src="img/salle21diapo.jpg" style="max-width:100%;height:auto;">
        <div class="text">Caption Two</div>
    </div>

    <div class="mySlides fade">
        <img src="img/salle9.jpg" style="max-width:100%;height:auto;">
        <div class="text">Caption Three</div>
    </div>

    <div class="mySlides fade">
        <img src="img/salle14diapo.jpg" style="max-width:100%;height:auto;">
        <div class="text">Caption four</div>
    </div>

    <div class="mySlides fade">
        <img src="img/sale17diap.jpg" style="max-width:100%;height:auto;">
        <div class="text">Caption five</div>
    </div>

    <!-- Next and previous buttons -->
    <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
    <a class="next" onclick="plusSlides(1)">&#10095;</a>
</div>
<br>

<!-- The dots/circles -->
<div style="text-align:center">
    <span class="dot" onclick="currentSlide(1)"></span>
    <span class="dot" onclick="currentSlide(2)"></span>
    <span class="dot" onclick="currentSlide(3)"></span>
    <span class="dot" onclick="currentSlide(4)"></span>
    <span class="dot" onclick="currentSlide(5)"></span>
</div>

<div class="index_gauche">
    <p>Lokisalle est le fournisseur mondial de solutions flexibles d'espaces de travail, de salles de réunions et de
        bureaux virtuels. Lokisalle vous accompagne et vous soutient dans vos activités en devenant une extension de
        votre business à travers la France.</p>
    <br/>
    <p>Lokisalle est le fournisseur mondial de solutions flexibles d'espaces de travail, de salles de réunions et de
        bureaux virtuels. Lokisalle vous accompagne et vous soutient dans vos activités en devenant une extension de
        votre business à travers la France.</p>
    <br/>
    <p>Lokisalle est le fournisseur mondial de solutions flexibles d'espaces de travail, de salles de réunions et de
        bureaux virtuels. Lokisalle vous accompagne et vous soutient dans vos activités en devenant une extension de
        votre business à travers la France.</p>
    <br/>
    <p>Lokisalle est le fournisseur mondial de solutions flexibles d'espaces de travail, de salles de réunions et de
        bureaux virtuels. Lokisalle vous accompagne et vous soutient dans vos activités en devenant une extension de
        votre business à travers la tsarfat.</p>
    <br/>
    <p>Que le prodigieux spectacle continue et que tu peux y apporter ta Rime.<br>(Walt whiteman)</p>

</div>
<div class="index_droit">
    <?php include("inc/diffusion_produit.inc.php"); ?>
</div>
<?php
include("inc/footer.inc.php");


	