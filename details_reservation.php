<?php
include("inc/init.inc.php");

if(isset($_GET['id_produit'])){
	$id = intval($_GET['id_produit']);

	$detail = lance_requete("SELECT p.id_produit, DATE_FORMAT(p.date_arrivee,'%d %M %Y') as date_arrivee, DATE_FORMAT(p.date_depart,'%d %M %Y') as date_depart, p.prix, s.ville, s.titre, s.photo, s.capacite, s.longitude, s.latitude, s.description, s.adresse, s.cp, s.categorie,s.id_salle
							FROM produit p, salle s
							WHERE p.id_salle = s.id_salle 
							AND p.id_produit = '$id'
							");
					

	 $id_session = $_SESSION ['utilisateur'] ['id_membre']; 

	if(isset($_POST['avis_soumission'])){
		securise();
		$date_actuelle = date("Y-m-d H:i:s");
		lance_requete("INSERT INTO avis(commentaire, note, date, id_salle, id_membre) VALUES ('$_POST[avis_text]', '$_POST[avis_note]', '$date_actuelle', $detail[id_salle], $id_session)");
		header("location:details_reservation.php");
	}


	$liste_avis = lance_requete("SELECT DATE_FORMAT(a.date,'%d-%M-%Y-%H-%i') as date_avis, a.commentaire, a.note, m.prenom
							FROM avis a, membre m
							WHERE a.id_membre = m.id_membre
							AND a.id_salle = '$detail[id_salle]'
							");

	$avis_depose = lance_requete("SELECT id_membre
							FROM avis 
							WHERE id_salle = '$detail[id_salle]'
							AND id_membre = '$id_session'
							");

	$liste = lance_requete("SELECT p.id_produit, DATE_FORMAT(p.date_arrivee,'%d %M %Y') as date_arrivee, DATE_FORMAT(p.date_depart,'%d %M %Y') as date_depart, p.prix, s.ville, s.titre, s.photo, s.capacite
						FROM produit p, salle s
						WHERE p.id_salle = s.id_salle
						AND p.etat = 0
						AND p.date_arrivee >= NOW()
						AND s.ville = '$detail[ville]'
						AND p.id_produit != '$id'
						ORDER BY date_arrivee ASC
						LIMIT 0,5");
	
}
else{
	header("location:reservation.php");
}

include("inc/haut_de_site.inc.php");
include("inc/menu.inc.php");


?>




<div id="descript_detail_salle">
<div class="detail_salle">
		<h3><?php echo $detail['titre'];?></h3>
		<img style="width:300px; height:200px;" src="<?php echo $detail['photo'];?>" alt="<?php echo $detail['titre'];?>">
</div>


<div class="description_detail">
    <h3>Description</h3>
		<span><?php echo $detail['description'];?></span>
		<span>Capacité: <?php echo $detail['capacite'];?> personnes</span>
		<span>Catégorie: <?php echo $detail['categorie'];?></span>
		<?php if(connecte()): ?>
			<a href="panier.php?id_produit=<?php echo $detail['id_produit'];?>">Ajouter au panier</a>
		<?php else: ?>
			<p><a href="connexion.php?id_produit=<?php echo $detail['id_produit'];?>">Connectez vous</a> pour ajouter au panier</p>
		<?php endif; ?>
</div>
</div>

	
	<div class="info_detail">
			<h3>Informations</h3>
		<div class="text_detail">
			<p>Ville: <?php echo $detail['ville'];?></p>
			<p>Adresse: <?php echo $detail['adresse'];?></p>
			<p>Code Postal: <?php echo $detail['cp'];?></p>
			<p>Date d'arrivée: <?php echo $detail['date_arrivee'];?></p>
			<p>Date de départ: <?php echo $detail['date_depart'];?></p>
			<p>Tarif(hors taxes): <?php echo $detail['prix'];?>€</p>
		</div>

		
		<div class="map">
			<?php
				require("inc/GoogleMapAPIv3.class.php");
				$gmap = new GoogleMapAPI('ABQIAAAAz7Xbm_WTkGpNU7kyMc1gghS3lcuyex_8Fgp7wndALVTrLQXUHBSpiUS5eUwxq6wOiCz4YtdnlMuOvA');
				$gmap->setCenterLatLng($detail['latitude'],$detail['longitude']);
				$gmap->setEnableWindowZoom(false);
				$gmap->setEnableAutomaticCenterZoom(false);
				$gmap->setDisplayDirectionFields(true);
		/* 		$gmap->setSize(500,200); */
				$gmap->setZoom(16);
                $gmap->setDefaultHideMarker(false);
                $gmap->addMarkerByCoords($detail['latitude'],$detail['longitude'],$detail['titre']);
                $gmap->generate();
				echo $gmap->getGoogleMap();
			?>
		</div>
	</div>
	



	<div class="avis_cont">
		
		<h3>Avis</h3>
		
		<div class="avis_soumis">
			<?php if(isset($liste_avis[1])) :
					foreach ($liste_avis as $avis) :
					list($jour, $mois, $annee, $heure, $minute)= explode("-", $avis['date_avis']);?>
					
					<div class="avis">
						<p><span><?php echo ucfirst($avis['prenom']);?></span>, le <span><?php echo $jour." ".$mois." ".$annee." a ".$heure."h".$minute;?></span></p>
						
						<p><?php echo $avis['commentaire'];?></p><span class="note"><?php echo $avis['note'];?>/10</span>
							<hr color = #333333 width = 350 >
					</div>
				
					<?php endforeach ;
					elseif(!empty($liste_avis)) :
					list($jour, $mois, $annee, $heure, $minute)= explode("-", $liste_avis['date_avis']);?>
					
					<div class="avis">
						<p><span><?php echo ucfirst($liste_avis['prenom']);?></span>, le <span><?php echo $jour." ".$mois." ".$annee." a ".$heure."h".$minute;?></span></p>
						<p><?php echo $liste_avis['commentaire'];?></p><span class="note"><?php echo $liste_avis['note'];?>/10</span>
						<hr color = #333333 width = 350  >
					</div>
			<?php else :?>
			<p>Aucun avis deposé</p>
			<?php endif ;?>
		</div>
			
		<div class="depo_avis">
			<?php if(connecte() && !empty($avis_depose)) :?>
		<p>Merci pour votre contribution</p>
			<?php elseif(connecte()) :?>
		
			<form method="post" action="" enctype="">
					<label>Avis</label>
					
					<textarea id="avis_text" name="avis_text" placeholder="ajouter"></textarea>
					
					
					
					
					<label>Note</label>
					<select id="avis_note" name="avis_note">
						<option>0/10</option>
						<option>1/10</option>
						<option>2/10</option>
						<option>3/10</option>
						<option>4/10</option>
						<option>5/10</option>
						<option>6/10</option>
						<option>7/10</option>
						<option>8/10</option>
						<option>9/10</option>
						<option>10/10</option>
					</select>
					<input type="submit" id="avis_soumission" name="avis_soumission" value="Soumettre" >
				</form>
					
			
			<?php else: ?>
				<p><a href="connexion.php?com=<?php echo $id;?>">Connectez vous</a> pour donner votre avis</p>
			<?php endif; ?>
			

		</div>
			
	</div>
	<?php if(!empty($liste)) :?>	


		<div id="suggestion">
				<h3>Suggestions</h3>
			<?php include("inc/diffusion_produit.inc.php"); ?>
		</div>
		
	<?php endif; ?>


<?php

include("inc/footer.inc.php");

?>


