 <?php
include("inc/init.inc.php");



$ville = lance_requete("SELECT DISTINCT ville FROM salle ORDER BY ville");
if(isset($_POST['date_valide'])){
	$date = $_POST['date_rech'];
		$date = date("Y-m-d", strtotime($date));

	$liste = lance_requete("SELECT p.id_produit, DATE_FORMAT(p.date_arrivee,'%d %M %Y') as date_arrivee, DATE_FORMAT(p.date_depart,'%d %M %Y') as date_depart, p.prix, s.ville, s.titre, s.photo, s.capacite
						FROM produit p, salle s
						WHERE p.id_salle = s.id_salle
						AND p.etat = 0
						AND p.date_arrivee = '$date'
						ORDER BY id_produit
						");
			
						
}

if(isset($_POST['eval_rech']) && strlen($_POST['long_rech'])>2){
	$liste = lance_requete("SELECT p.id_produit, DATE_FORMAT(p.date_arrivee,'%d %M %Y') as date_arrivee, DATE_FORMAT(p.date_depart,'%d %M %Y') as date_depart, p.prix, s.ville, s.titre, s.photo, s.capacite
						FROM produit p, salle s
						WHERE p.id_salle = s.id_salle
						AND p.etat = 0
						AND p.date_arrivee >= NOW()
						AND (s.ville LIKE '%$_POST[long_rech]%'
						OR s.titre LIKE '%$_POST[long_rech]%'
						OR s.adresse LIKE '%$_POST[long_rech]%')
						ORDER BY id_produit
						");
}

include("inc/haut_de_site.inc.php");
include("inc/menu.inc.php");
?>
	

    <hr><h2>RECHERCHE</h2><hr>
		
<div class="recherche">
    <div class="recherchedate">

        <form  method="post" action="" enctype="">

            <label for="date_rech">date</label>
            <input type="text" name="date_rech" id="date_rech" placeholder=" jj-mm-AAAA " value="<?php if(isset($_POST['date_valide'])) echo $_POST['date_rech']; ?>">
            <input type="submit" id="date_valide" name="date_valide" value="rechercher">

        </form>
    </div>




    <div class="rechercheville">
        <form  method="post" action="" enctype="">
            <label for="eval_rech">ville</label>
            <input type="text"  name="long_rech" id="long_rech" placeholder="Paris, Lyon, Marseille." value="<?php if(isset($_POST['eval_rech'])){echo $_POST['long_rech'];} ?>">
            <input type="submit" id="eval_rech" name="eval_rech" value="rechercher">
        </form>
    </div>
</div>
	
		
			
				
			
		<div id="resultat">
			<?php include("inc/diffusion_produit.inc.php"); ?>
		</div>	
		
<?php

include("inc/footer.inc.php");

?>	
		

	


