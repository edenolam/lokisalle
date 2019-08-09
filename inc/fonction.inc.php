
<?php
/**
 * @param $requete
 * @return bool|mysqli_result
 */
function execute_requete($requete)
{
    global $mysqli;

    $resultat = $mysqli->query($requete);

    if(!$resultat){
        die("Erreur de requete:  " . $mysqli->error . "<br /> Requete: " . $requete);
    }
    return $resultat;
}



/**
 * @param $requete
 * @return array
 */
function lance_requete($requete)
{
    global $mysqli;
    $resultat = $mysqli->query($requete);
    if(!$resultat){
        die("Erreur de requête:  " . $mysqli->error . "<br> Requête: " . $requete);
    }
    if(strstr($requete, "SELECT")){
        if($resultat->num_rows == 1){
            $array = $resultat->fetch_assoc();
            return $array;
        }
        elseif($resultat->num_rows > 1){
            while($array = $resultat->fetch_assoc()){
                $tableau[] = $array;
                return $tableau;
            }

        }
    }
}



/**
 * @param $argument
 */
function debug($argument)
{
    echo "<pre>"; print_r($argument); echo "</pre>";
    echo "<hr />";
    $trace = debug_backtrace();
    echo "print_r demandé dans le fichier<i>: " . $trace[0]['file'] . "</i><br /> Ligne:<i> " . $trace[0]['line'] ."</i>";
}


/**
 * @return bool
 */
function connecte()
{
    if(isset($_SESSION['utilisateur'])){
        return true;
    }
    else{
            return false;
        }
}




/**
 * @return bool
 */
function connecte_et_est_admin()
{
    if(connecte() && ($_SESSION['utilisateur']['statut'] ==1)){
            return true;
        }
        else{
            return false;
        }
}


//-----------------------------------------
/*function securise($tableau)
	{
		if(is_array($tableau)){
			foreach($tableau as $indice => $valeur)
				{
					$tableau[$indice] = htmlentities($valeur, ENT_QUOTES);
				}
		}
		else { htmlentities($tableau);
		}
		return $tableau;
	}*/
//-----------------------------------------


/**
 *
 *
 */
function securise()
{
    foreach ($_POST as $indice => $valeur){
        $_POST[$indice] = htmlentities($valeur, ENT_QUOTES);
    }
}



/**
 * @return bool
 */
function verification_extension_photo(){
	$extension = strrchr($_FILES['photo']['name'] , ".");
	$extension = strtolower(substr($extension , 1));
	$tab_extension_valide = array("jpeg", "jpg", "png" , "gif");
	$verif_extension = in_array($extension, $tab_extension_valide);
	return $verif_extension;
}


/**
 * @param $id
 * @param $chose
 * @return bool|mysqli_result
 */
function information_sur_une_chose($id, $chose)
{// $chose doit être le nom de la table
    $resultat = execute_requete("SELECT * FROM $chose WHERE id_$chose=$id");
    return $resultat;
}



/**
 * @param $id
 * @return bool|mysqli_result
 */
function information_sur_un_produit($id)
{
    $resultat = execute_requete("SELECT * FROM produit WHERE id_produit=$id");
    return $resultat;
}



/**
 * @param $id
 * @return bool|mysqli_result
 */
function information_sur_une_salle($id)
{
    $resultat = execute_requete("SELECT * FROM salle WHERE id_salle=$id");
    return $resultat;
}



/**
 * @param $id
 * @return bool|mysqli_result
 */
function information_sur_un_membre($id)
{
    $resultat = execute_requete("SELECT * FROM membre WHERE id_membre=$id");
    return $resultat;
}



/**
 * @param $long
 * @return bool|string
 */
function mdp_reinit($long)
{
    $caractere = "a,b,c,d,e,f,g,h,i,j,k,l,m,n,o,p,q,r,s,t,u,v,w,x,y,z,1,2,3,4,5,6,7,8,9,0";
    $array = explode(",",$caractere);
    shuffle($array);
    $chaine = implode($array,"");
    return substr($chaine, 0, $long);
}



/**
 * @return bool
 */
function creation_panier()
{

		if(!isset($_SESSION['panier'])){

				$_SESSION['panier'] = array();
				$_SESSION['panier']['produit'] = array();
				$_SESSION['panier']['titre'] = array();
				$_SESSION['panier']['photo'] = array();
				$_SESSION['panier']['ville'] = array();
				$_SESSION['panier']['capacite'] = array();
				$_SESSION['panier']['date_arrivee'] = array();
				$_SESSION['panier']['date_depart'] = array();
				$_SESSION['panier']['prix'] = array();
				$_SESSION['panier']['code_promo'] = array();
				$_SESSION['panier']['reduction'] = array();	

        }
		return true;	
	}




/**
 * @param $produit
 * @param $titre
 * @param $photo
 * @param $ville
 * @param $capacite
 * @param $date_arrivee
 * @param $date_depart
 * @param $prix
 * @param $code_promo
 */
function ajout_panier($produit, $titre, $photo, $ville, $capacite, $date_arrivee, $date_depart, $prix, $code_promo)
{

		$position_produit = array_search($produit, $_SESSION['panier']['produit']);		
		
		if($position_produit === false){
					
					$_SESSION['panier']['produit'][] = $produit;
					$_SESSION['panier']['titre'][] = $titre;
					$_SESSION['panier']['photo'][] = $photo;
					$_SESSION['panier']['ville'][]= $ville;
					$_SESSION['panier']['capacite'][] = $capacite;
					$_SESSION['panier']['date_arrivee'][] = $date_arrivee;
					$_SESSION['panier']['date_depart'][] = $date_depart;
					$_SESSION['panier']['prix'][] = $prix;	
					$_SESSION['panier']['code_promo'][] = $code_promo;	
					$_SESSION['panier']['reduction'][] = $prix;
					$_SESSION['panier']['statut'][] = 0 ;	
					
			}
	}
	


/**
 * @return float|int
 */
function prix_total_base()
{
	$total = 0;
	for($i = 0; $i < count($_SESSION['panier']['produit']); $i++){
			$total += $_SESSION['panier']['prix'][$i] * 1.2;
		}
	return $total;
}	



/**
 * @return float|int
 */
function prix_total()
{//final avec reduc

	$total = 0;
	for($i = 0; $i < count($_SESSION['panier']['produit']) ; $i++){

			$total += $_SESSION['panier']['reduction'][$i] * 1.2;
    }
	
	return $total;
}




/**
 * @param $id
 */
function retirer_produit_panier($id)
{

		$produit_existe = in_array($id, $_SESSION['panier']['produit']);

		if ($produit_existe){
			$position_produit = array_search($id,$_SESSION['panier']['produit']);
            array_splice($_SESSION['panier']['produit'], $position_produit , 1);
            array_splice($_SESSION['panier']['titre'], $position_produit , 1);
            array_splice($_SESSION['panier']['photo'], $position_produit , 1);
            array_splice($_SESSION['panier']['ville'], $position_produit , 1);
            array_splice($_SESSION['panier']['capacite'], $position_produit , 1);
            array_splice($_SESSION['panier']['date_arrivee'], $position_produit , 1);
            array_splice($_SESSION['panier']['date_depart'], $position_produit , 1);
            array_splice($_SESSION['panier']['prix'], $position_produit , 1);
            array_splice($_SESSION['panier']['code_promo'], $position_produit , 1);
            array_splice($_SESSION['panier']['reduction'], $position_produit , 1);
		
		}

		
}
	
	
	//--------------------------
/**
 * @param $prix
 * @param $pourcentage
 * @return float|int
 */
function code_reduc($prix, $pourcentage)
{
    return $prix * ( $pourcentage / 100 );
}
	




	
	

	
	
	
	
	



	
	
	
	
	
	
	
	
	



	