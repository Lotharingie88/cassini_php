<?php
session_start();
require_once ("util.php");
$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
$mdp=$_POST["motdepasse"];

//$mdp=md5($_POST["motdepasse"]);

enteteg("O","CASSINI");
?>
<div id="bandeau">
	<?php
	bandeau();
	?>
	<div id="logocourrier">
            <img id="img1" src="interface/logo_courrier.gif" alt="Retour Accueil" width="30" height="30" />
    </div>
	<div id="titresite">CASSINI</div>
	<div id="cartouche2"><a href="http://www.wac.courrier.intra.laposte.fr"><Img ID="img3" src="interface/logo_wac.gif" width="29" height="11" Alt="WAC" /></a> | <a href="http://www.i-poste.log.intra.laposte.fr"><Img ID="img4" src="interface/logo_iposte.gif" width="48" height="15" Alt="i-poste" /></a> | <a href="mailto:sylvain.thouvenot@laposte.fr">Nous &eacute;crire</a> | <a href="">Plan du site</a> | <a href="" target="_blank">Aide</a></div>
</div>

	<div id="menugaucheh">
	<?php
	menghaccs();
	confconn();
	?>
	</div>
	<div id="menugauchem">
	<?php
$requete = "SELECT  count(*) FROM utilisateur where nom='".$_POST["nom"]."' and pwd='".$mdp."' and pwd<>'PWD'";
  $resultat= $bd-> execRequete($requete);
//echo "<H3><center>".$mdp."</center><H3>";
if (!$resultat)
{
echo "Connexion impossible !";
exit;
}
$resn= $bd-> ligTabSuivant ($resultat);
 //echo "<CENTER><H3>".$_POST['nom'].$mdp. $resn[0]."</H3></CENTER>\n";

if ($resn[0] >0)
{    
    
    //echo "<CENTER><H3>".$_POST['nom']. $count[0].session_id()."</H3></CENTER>\n";
	 
    $reqsess = "SELECT count(*) FROM session where nom='".$_POST['nom']."' and idsession='".session_id()."'";
	$resusess = $bd->execRequete ($reqsess);
    $resn1=$bd-> ligTabSuivant ($resusess);
    
     //echo "<CENTER><H3>".$count[0].$ligsess.$_POST['nom']. $count[0].session_id()."</H3></CENTER>\n";
	if ($resn1[0]==0)
	{
	    $requete2 = "SELECT  utilisateur.nom,utilisateur.prenom,utilisateur.cprofil,utilisateur.iduser,utilisateur.pwd FROM utilisateur where nom='".$_POST['nom']."' and pwd='".$mdp."' and pwd<>'PWD' group by utilisateur.nom";
	    $resultat2 = $bd->execRequete ($requete2);
	    $ligne = $bd-> ligTabSuivant ($resultat2);
	    $_SESSION["userok"]=$ligne[0];
	    $_SESSION["useprenok"]=$ligne[1];
	    $_SESSION["usefonc"]=$ligne[2];
	    $_SESSION["useridok"]=$ligne[3];
	    $debsess=date("Y/m/d H:i:s");
	    $_SESSION["usersess"]=$debsess;
	    $_SESSION["pwd"]=$ligne[4];
	    sessval();
	    $requete3="insert session set nom='".$ligne[0]."',prenom='".$ligne[1]."',datdeb='".$debsess."',idsession='".session_id()."'";
	    $resultat3 =$bd-> execRequete ($requete3);
	    $requete4="insert joursession set iduser='".$ligne[3]."',debsess='".$debsess."',idsession='".session_id()."'";
	    $resultat4 =$bd-> execRequete ($requete4);
	    if ( $resultat3 )
	        return TRUE;
	        else
	            return FALSE;
	            if ( $resultat4 )
	                return TRUE;
	                else
	                    return FALSE;
	}
	if ($resn1[0]>0)
	{
	    sessval();
	    echo "<CENTER><H3>Vous êtes déjà connecté ! Déconnectez vous avant d'ouvrir une nouvelle session</H3></CENTER>\n";
	
		$requete2 = "SELECT  utilisateur.nom,utilisateur.prenom,utilisateur.cprofil,utilisateur.iduser,utilisateur.pwd FROM utilisateur where nom='".$_POST['nom']."' and pwd='".$mdp."' and pwd<>'PWD' group by utilisateur.nom";
		$resultat2 = $bd->execRequete ($requete2);
		$ligne=$bd-> ligTabSuivant($resultat2);
		$_SESSION["userok"]=$ligne[0];
		$_SESSION["useprenok"]=$ligne[1];
		$_SESSION["usefonc"]=$ligne[2];
		$_SESSION["useridok"]=$ligne[3];
		
		$_SESSION["pwd"]=$ligne[4];

	}
	

				switch ($_SESSION["usefonc"])
				{
					case 1:
						echo "<center><H2>".'VISION PAYS'."</H2></center>";
						echo "<UL>\n";
						echo "<LI>".ancre("glob.php",'Global')."</LI>\n";
						echo "<LI>".ancre("geogr.php",'Géographie')."</LI>\n";
						echo "<LI>".ancre("econo.php",'Economie')."</LI>\n";
						echo "<LI>".ancre("polit.php",'Politique')."</LI>\n";
						echo "</UL>\n";

						echo "<center><H2>".'VISION COMPARATIVE'."</H2></center>";
						echo "<UL>\n";
						echo "<LI>".ancre("compeco.php",'Economie')."</LI>\n";
						
						echo "</UL>\n";

						

						echo "<center><H2>".'ADMINISTRATION'."</H2></center>";
						echo "<UL>\n";
						echo "<LI>".ancre("gestut.php",'Gestion utilisateur')."</LI>\n";
						echo "<LI>".ancre("import.php",'Import données')."</LI>\n";
						echo "<LI>".ancre("export.php",'Export données')."</LI>\n";
						echo "<LI>".ancre("visulog.php",'Visualitionlog')."</LI>\n";
						echo "<LI>".ancre("stats.php",'Statistique')."</LI>\n";
						echo "</UL>\n";
						break;

					case 2:
						
					    echo "<center><H2>".'VISION PAYS'."</H2></center>";
					    echo "<UL>\n";
					    echo "<LI>".ancre("glob.php",'Global')."</LI>\n";
					    echo "<LI>".ancre("geogr.php",'Géographie')."</LI>\n";
					    echo "<LI>".ancre("econo.php",'Economie')."</LI>\n";
					    echo "<LI>".ancre("polit.php",'Politique')."</LI>\n";
					    echo "</UL>\n";
					    
					    echo "<center><H2>".'VISION COMPARATIVE'."</H2></center>";
					    echo "<UL>\n";
					    echo "<LI>".ancre("cudotc.php",'Economie')."</LI>\n";
					    
					    echo "</UL>\n";
						break;

					
					default:
						echo "<CENTER><H3>Type de fichier non determiné</H3></CENTER>\n";
						break;
				}
				
}
else
{
require_once ("util.php");
echo "<H3>"."Vous n'êtes pas utilisateur du service"."</H3>";
require ("leftprin.php");
//quitter();
}
	?>
</div>
<div id="logobas"></div>
<div id="cont_princ">
	<?php
	tblcellule(image('interface/scribe.jpg',"80%","100%"));
	?>
</div>
<?php
finpag();

?>