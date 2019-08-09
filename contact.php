<?php
include("inc/init.inc.php");


if(isset($_POST['contact_send'])){
 
	securise();
	$contact_admin = lance_requete("SELECT email
									FROM membre
									WHERE statut = 1
									");
							

	if(connecte()){
		$id_session = $_SESSION['utilisateur']['id_membre'];
		$membre = lance_requete("SELECT email
									FROM membre
									WHERE id_membre = $id_session
									");
				

		mail ("$contact_admin[email]", "$_POST[sujet]", "$_POST[message]", "$membre[email]");
	}
	else{
		mail ("$contact_admin[email]", "$_POST[sujet]", "$_POST[message]", "$_POST[expediteur]");
	}
}

include("inc/haut_de_site.inc.php");
include("inc/menu.inc.php");
?>
<hr style="color:#c4c4c4; width:400px;"><h3>CONTACT</h3><hr style="color:#c4c4c4; width:400px;">
<div id="contact">
			
			
			<form method="post" action="" enctype="">
				<?php if(!connecte()):?>
					<label>Expéditeur (mail)</label>
					<input type="text" id="expediteur" name="expediteur">
				<?php endif;?>

				<label>Sujet</label>
				<input type="text" id="sujet" name="sujet">
				<label>Message</label>
				<textarea id="message" name="message"></textarea>
				<input type="submit" id="contact_send" name="contact_send" value="Envoi">
			</form>
			</div>
		
				<div class="logo_contact">
				<img src="<?php print RACINE_SITE;?>/img/send-email.jpg">
				</div>
		
		
		
		
		

<?php

include("inc/footer.inc.php");

?>