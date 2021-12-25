<?php
function connex()
    {
     if (isset($_POST['nom']))
    {
    formidentvert("execonnec.php",$_POST["nom"],$_POST["motdepasse"]); 
    }
    else
      {formidentvert("execonnec.php"); }
    echo "<Center>".ancre("deconnec.php",'Déconnexion')."</Center>";
    echo "<P><Center>".ancre("mdpoubl.php",'Mot de passe oublié')."</Center></P>";
    }
function oublier()
    {
    session_start();
    if (!valchps($_POST))
	   {
	    echo "<H3><center>".'Des champs sont vides'."</center><H3>";
		formpwdoubl();
		exit;
	   }
	   else
	   if ($_POST["user"]!="")
	   {

	   if (!logknow($_POST["user"],$_POST["mail"] ))
	   {
	    echo "<H3><Center>"."Vous n'êtes pas enregistré comme utilisateur de la base"."</Center></H3>";
		exit;
	   }
	   else
	   {

		if (envpwd($_POST["mail"],$pwd ))
			echo "<H3><Center>".''."</Center></H3>";

	   }
	   }
	   else
    formpwdoubl();
    //session_write_close();
    }
function inscrip()
    {
	//session_start();
	//require ("pconnec.php");
     $bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
     echo "<center><H3>".$_POST['user']."</H3></center>";
	if (!valchps($_POST))
	   {
		echo "<center><H3>".'Des champs sont vides'."</H3></center>";
		forminscrip();
		exit;
	   }
	   else
	   if ($_POST["nom"]!="" &  $_POST["prenom"]!="")
	   {
		$reqnuser="select count(*)  from utilisateur where nom='".$_POST["nom"]."' and prenom='".$_POST["prenom"]."'";
		$resnuser =$bd-> execRequete ($reqnuser);
		if (!$resnuser)
			return FALSE;
		$cuser=$bd->ligTabSuivant($resnuser);
		if ($cuser >0)
		{
			echo "<center><H3>"."Il y a déjà une inscription sous ce nom et prénom"."</H3></center>";
			forminscrip();
			exit();
		}
		else
		{
			echo "<center><H3>"."Demande d'inscription effectuée"."</H3></center>";
			$reqfonc="select libfonc  from metier where idfonc='".$_POST["fonction"]."'";
			$resfonc = $bd->execRequete ($reqfonc);
			$ligne=$bd->ligTabSuivant($resfonc);
			envdinscrip($_POST["nom"],$_POST["prenom"],$_POST["service"],$_POST["portable"],$_POST["telephone"],$_POST["fax"],$_POST["email"],$ligne[0],$_POST["dotcdcn"]);
			exit();
		}
	   }
	   else
		forminscrip();
    }
function majuser()
    {
    session_start();
    //require ("pconnec.php");
    //$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);

    if ((isset($_POST["nom"]) && $_POST["nom"]=="") || (isset($_POST["email"]) && $_POST["email"]=="") || (isset($_POST["datdeb"]) && $_POST["datdeb"]=="") || (isset($_POST["motdepasse"]) && $_POST["motdepasse"]=="") || (isset($_POST["prenom"]) && $_POST["prenom"]==""))
	   {
	    echo "<H3><center>".'Des champs obligatoires sont vides'."</center><H3>";
		formmajuser();
		exit;
	   }
	   else
	   formmajuser();
	//session_write_close();
    }
function formmajuser()
    {
    //require ("pconnec.php");
	$iduser=$_GET['iduser'];
    // Cr�ation du formulaire
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
    //$Form = new formulaire ("POST", "$bd");
	$reqsto = "SELECT  utilisateur.nom,utilisateur.prenom,utilisateur.service,utilisateur.portable,utilisateur.fax,utilisateur.telephone,utilisateur.email,date_format(utilisateur.datdeb,'%d/%m/%Y'),date_format(utilisateur.datfin,'%d/%m/%Y'),utilisateur.pwd,metier.idfonc,metier.fonction,utilisateur.dotcdcn FROM utilisateur,metier where utilisateur.idfonc=metier.idfonc and iduser='".$iduser."'";
		$ressto = $bd->execRequete ($reqsto);
		$lig=$bd->ligTabSuivant($ressto);
		$user["nom"]=$lig[0];
		$user['prenom']=$lig[1];
		$user['service']=$lig[2];
		$user['fonction']=$lig[11];
		$user['dotcdcn']=$lig[12];
		$user['etablissement']="";
		$user['portable']=$lig[3];
		$user['fax']=$lig[4];
		$user['telephone']=$lig[5];
		$user['email']=$lig[6];
		$user['datdeb']=$lig[7];
		$user['datfin']=$lig[8];
		$user["motdepasse"]=$lig[9];

	$Form = new formulaire ("POST", "");
    // Tableau en mode vertical, pour les champs simples
	$requete = "SELECT  distinct fonction,idfonc FROM metier order by fonction";
		$resultat =$bd-> execRequete ($requete);
		$nomb_ligne=$bd->nbLigne($resultat);
		$listMetier[0]=$lig[11];
	for ($j=0;$j<$nomb_ligne;$j++)
		{
		$ligne=$bd->objetSuivant($resultat);
		$listMetier[$ligne->idfonc]=$ligne->fonction;
		}
    // Tableau en mode vertical, pour les champs simples
    echo "<CENTER><H2>".'Modification utilisateur'."</H2></CENTER>\n";
	tblcellule(image('redbar.gif',"100%",2));
	$Form->debuttable();
    $Form->champTexte ("Nom :", "nom", $user['nom'], 30);
    $Form->champTexte ("Prénom :", "prenom", $user['prenom'], 30);
	$Form->champTexte ("Service :", "service", $user['service'], 30);
	$Form->champTexte ("DOTC DCN :", "dotcdcn", $user['dotcdcn'], 30);
	$Form->champTexte ("Etablissement :", "etablissement", $user['etablissement'], 30);
	$Form->champTexte ("Portable :", "portable", $user['portable'], 14);
	$Form->champTexte ("Fax :", "fax", $user['fax'], 14);
    $Form->champTexte ("Téléphone :", "telephone", $user['telephone'], 14);
	$Form->champTexte ("Email :", "email", $user['email'], 30);
	$Form->champTexte ("Date début :", "datdeb", $user['datdeb'], 10);
	$Form->champTexte ("Date fin :", "datfin", $user['datfin'], 10);
	$Form->champliste ("Fonction :", "fonction", "", 1, $listMetier);
	$Form->champTexte("Mot de passe : ","motdepasse",$user['motdepasse'],10);
    $Form->fintable();
    // Tableau en mode horizontal pour le metteur en sc�ne
    $Form->ajoutTexte ("<P></P>");
    $Form->champvalider ("Valider la saisie", "valider");
    //$Form->fin();
    echo $Form->formulaireHTML();
	}
function formcuser()
    {
    
    //require ("pconnec.php");
   //session_start(); 
        $bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
    if ((isset($_POST["nom"]) && $_POST["nom"]=="") || (isset($_POST["datdeb"]) && $_POST["datdeb"]=="") || (isset($_POST["motdepasse"]) && $_POST["motdepasse"]=="") || (isset($_POST["prenom"]) && $_POST["prenom"]==""))
	   {
	    echo "<H3><center>".'Des champs obligatoires sont vides'."</center><H3>";
		formcreauser();
		exit;
	   }
	   else
	   if (isset($_POST["nom"]))
			{
			$datmaj=date("Y/m/d");
			if ($_POST["datdeb"]!="")
			{
				$datret1=explode("/",$_POST["datdeb"]);
		    	$datdr= date("Y/m/d",mktime(0,0,0,$datret1[1],$datret1[0],$datret1[2]));
			}
			else
				{
					$datdr= ($_POST["datdeb"]);
				}
			//$reqnuser="insert into utilisateur (nom,prenom,idfonc,service,portable,fax,telephone,email,dotcdcn,datdeb,pwd,datemaj) values ('".$_POST["nom"]."','".$_POST["prenom"]."','".$_POST["fonction"]."','".$_POST["service"]."','".$_POST["portable"]."','".$_POST["fax"]."','".$_POST["telephone"]."','".$_POST["email"]."','".$_POST["dotcdcn"]."','".$datdr."','".md5($_POST["motdepasse"])."','".$datmaj."')";
			$reqnuser="insert into utilisateur (nom,prenom,idfonc,service,portable,fax,telephone,email,dotcdcn,datdeb,pwd,datemaj) values ('".$_POST["nom"]."','".$_POST["prenom"]."','".$_POST["fonction"]."','".$_POST["service"]."','".$_POST["portable"]."','".$_POST["fax"]."','".$_POST["telephone"]."','".$_POST["email"]."','".$_POST["dotcdcn"]."','".$datdr."','".$_POST["motdepasse"]."','".$datmaj."')";
			$resnuser = $bd->execRequete ($reqnuser);
			if (!$resnuser)
				return FALSE;
				else
				{
				echo "<H3><center>".'Création du nouvel utilisateur effectuée'."</center><H3>";
				ancre("creuser.php",'Créer un autre utilisateur');
				if (!$_POST["email"]&& $_POST["email"]=="")
				{
					envpwd($_POST["email"],$_POST["motdepasse"]);
				}
				return TRUE;
				}
			}
	   else
	   formcreauser();
    }
function formsuivconnex()
    {
	//require ("pconnec.php");
	// Cr�ation du formulaire
        $bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	$reqnb ="select  concat(left(utilisateur.prenom,1),'. ',utilisateur.nom),date_format(joursession.debsess,'%d/%m/%Y %H:%i:%s'),date_format(joursession.finsess,'%d/%m/%Y %H:%i:%s'),if (joursession.finsess<>0,sec_to_time(unix_timestamp(joursession.finsess)-unix_timestamp(joursession.debsess)),'') from joursession,utilisateur where utilisateur.iduser = joursession.iduser order by joursession.debsess desc ";
	$resnb = $bd->execRequete ($reqnb);
	$reqbase= "select  concat(left(utilisateur.prenom,1),'. ',utilisateur.nom),date_format(joursession.debsess,'%d/%m/%Y %H:%i:%s'),date_format(joursession.finsess,'%d/%m/%Y %H:%i:%s'),if (joursession.finsess<>0,sec_to_time(unix_timestamp(joursession.finsess)-unix_timestamp(joursession.debsess)),'') from joursession,utilisateur where utilisateur.iduser = joursession.iduser order by joursession.debsess desc ";
	$affich=15;
	if (isset($_GET['requetc']))
	{
		$requete =stripslashes($_GET['requetc']);
		$resultat = $bd->execRequete ($requete);
	}
	else
	{
		$requete = "select  concat(left(utilisateur.prenom,1),'. ',utilisateur.nom),date_format(joursession.debsess,'%d/%m/%Y %H:%i:%s'),date_format(joursession.finsess,'%d/%m/%Y %H:%i:%s'),if (joursession.finsess<>0,sec_to_time(unix_timestamp(joursession.finsess)-unix_timestamp(joursession.debsess)),'') from joursession,utilisateur where utilisateur.iduser = joursession.iduser order by joursession.debsess desc limit 0,".$affich;
		$resultat = $bd->execRequete ($requete);
	}
	$nomb_ligtot=$bd->nbLigne($resnb);
	$nomb_ligne=$bd->nbLigne($resultat);
	$Form = new formulaire ("POST", "","suivconnex");
	$Form->debuttable();
	// Tableau en mode vertical, pour les champs simples
	echo "<CENTER><H3>".'Journal des connexions'."</H3></CENTER>\n";
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete("Utilisateur"));
	$Form->ajoutTexte (tblentete("Début"));
	$Form->ajoutTexte (tblentete("Fin"));
	$Form->ajoutTexte (tblentete("Durée"));
	$Form->ajoutTexte (tblfinligne());

	for ($j=0;$j<$nomb_ligne;$j++)
	{
		if ($j <= $affich)
		{
			$ligne=$bd->ligTabSuivant($resultat);
			tbldebutligne("A".($j%2));
			tblcellule($ligne[0]);
			tblcellule($ligne[1]);
			tblcellule($ligne[2]);
			tblcellule($ligne[3]);
			tblfinligne();
		}
	}
	$Form->ajoutTexte (tblfin());

	$Form->fintable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	multipage($nomb_ligtot,$affich,12,$reqbase,"stats.php");
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
    }
function forminscrip()
    {
	//require ("pconnec.php");
        $bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	$Form = new formulaire ("POST", "","TRUE","Form","inscrip");
	$user= array();
	$requete = "SELECT  distinct metier.idfonc,metier.fonction FROM metier where metier.fonction <>'ADMINISTRATEUR' order by fonction ";
	$resultat =$bd-> execRequete ($requete);
	$nomb_ligne=$bd->nbLigne($resultat);
	for ($j=0;$j<$nomb_ligne;$j++)
	{
		$ligne=$bd->ligTabSuivant($resultat);
		$listMetier[$ligne->idfonc]=$ligne->fonction;
	}
	echo "<CENTER><H2>"."Demande d' inscription"."</H2></CENTER>\n";
	echo "\n";
	tblcellule(image('interface/redbar.jpg',"100%",1));
	$Form->debuttable();
	$Form->champTexte ("Nom :", "nom", $user["nom"], 25);
	//$Form->champTexte ("Pr�nom :", "prenom", $user['prenom'], 20);
	//$Form->champTexte ("DOTC DCN :", "dotcdcn", $user['dotcdcn'], 20);
	//$Form->champTexte ("Service :", "service", $user['service'], 30);
	//$Form->champTexte ("Portable :", "portable", $user['portable'], 10);
	//$Form->champTexte ("T�l�phone :", "telephone", $user['telephone'], 10);
	//$Form->champTexte ("Fax :", "fax", $user['fax'], 10);
	//$Form->champTexte ("Email :", "email", $user['email'], 30);
	$Form->champliste ("Fonction :", "fonction", "", 1, $listMetier);
	$Form->fintable();
	$Form->ajoutTexte ("<H3><Center>".'Votre mot de passe vous sera envoyé par mail'."</Center></H3>");
	$Form->ajoutTexte ("<P></P>");
	$Form->champvalider ("Valider la saisie", "valider");
	$Form->fintable();
	//$Form->fin();
    }
function formusers()
    {
	//require ("pconnec.php");
        $bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	$requete = "SELECT  distinct utilisateur.nom,utilisateur.prenom,date_format(utilisateur.datdeb,'%d/%m/%Y'),if(utilisateur.datfin<>0,date_format(utilisateur.datfin,'%d/%m/%Y'),'Autorisation valide'),metier.fonction,utilisateur.dotcdcn,utilisateur.iduser FROM utilisateur,metier where utilisateur.idfonc=metier.idfonc order by utilisateur.nom ";
	$resultat = $bd->execRequete ($requete);
	$nomb_ligne=$bd->nbLigne($resultat);
	$Form = new formulaire ("POST", "","users");
	// Tableau en mode vertical, pour les champs simples
	echo "<CENTER><H3>".'Liste des utilisateurs SCRIBE'."</H3></CENTER>\n";
	tblcellule(image('interface/redbar.jpg',"100%",1));
	//$nomb_pages=$nomb_ligne/10;
	//$Form->debuttable();
	tbldebut(1,"100%");
	tbldebutligne();
	tblentete("Utilisateur");
	tblentete("DOTC_DCN");
	tblentete("Fonction");
	tblentete("Debut autorisation");
	tblentete("Fin autorisation");
	tblentete("Modification");
	tblfinligne();
	for ($j=0;$j<$nomb_ligne;$j++)
		//for ($j=0;$j<10;$j++)
	{
		$ligne=$bd->ligTabSuivant($resultat);
		tbldebutligne("A".($j%2));
		tblcellule($ligne[0]." ".$ligne[1]);
		tblcellule($ligne[5]);
		tblcellule($ligne[4]);
		tblcellule($ligne[2]);
		tblcellule($ligne[3]);
		$user=urlencode($ligne[6]);
		tblcellule(ancre("muser.php?iduser=$user","Modification"));
		tblfinligne();
	}

	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	tbldebut(0,"100%");
	tblcellule(ancre("creuser.php",'Créer un utilisateur'));
	tblcellule(ancre("listuser.php","Importer une table d'utilisateurs"));
	tblfin();

	//$pages="";
	//for ($i=1;$i<$nomb_pages+1;$i++)
	//{
	//$pages=$pages."[".ancre("",$i)."]";
	//}
	//echo "<P><CENTER>".$pages."</CENTER></P>";
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
    }
function envpwd($usermail,$pwd)
    {
	$mess="Votre mot de passe pour accéder à l'application est : $pwd \r\n"."Changez le dés votre prochaine connexion. \r\n";
	$exped="From: sylvain.thouvenot@synapsat.com \r\n";
    if (mail($usermail,"login Saturn" ,$mess,$exped ))
    return TRUE;
	else
	return FALSE;
    }
function envdinscrip($nom,$prenom,$service,$portable,$telephone,$fax,$email,$fonction,$dotcdcn)
    {
	$mess="Demande d'inscription pour :\r\n"."Nom : $nom \r\n"."Pr�nom : $prenom \r\n"."Service : $service \r\n"."Portable : $portable \r\n"."T�l�phone : $telephone \r\n"."Fax : $fax \r\n"."Email : $email \r\n"."Fonction : $fonction \r\n"."DOTC DCN : $dotcdcn \r\n";	$exped="From: $email \r\n";
	$dest="sylvain_thouvenot@laposte.net";
	//$exped="From: sylvain.thouvenot@synapsat.com \r\n";
    if (mail($dest,"login SCRIBE" ,$mess,$exped ))
    return TRUE;
	else
	return FALSE;
    }
function sessval()
    {
    //session_start();
    //if (session_is_registered($_SESSION["userok"]))
    if (isset($_SESSION["userok"]))
    {

     }
    else
    {
     echo "Vous ne vous êtes pas identifiés à la connexion";

    exit;
    }
    //session_write_close();
    }
function deconnex()
    {
    session_start();
    //require ("pconnec.php");
    $bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
    if (isSet($_SESSION["userok"]))
    {
     //echo "<CENTER><H3>".$_SESSION["userok"].session_id()."</H3></CENTER>\n";
    $ancuser=$_SESSION["userok"];
    $ancsess=session_id();
    $anciduse=$_SESSION["useridok"];
    $ancdeb=$_SESSION["usersess"];
    $finsess=date("Y/m/d H:i:s");
    //$ressession=$_SESSION["userok"];
    //$ressession=$_SESSION["useprenok"];
    //$ressession=$_SESSION["useridok"];
    //$ressession=session_unregister($_SESSION["userok"]);
    //$ressession=session_unregister($_SESSION["useprenok"]);
    //$ressession=session_unregister($_SESSION["useridok"]);
    session_destroy();
    //unset($_SESSION[])
    echo "<CENTER><H3>".$ancuser.session_id()."</H3></CENTER>\n";
    if (isSet($ancuser))
        {
	   //if ($ressession)
	       //{
		  $reqsess="delete from session where idsession='".$ancsess."'";
		  $resulsess = $bd-> execRequete ($reqsess);
		  $reqjour="update joursession set finsess='".$finsess."' where iduser='".$anciduse."'and debsess='".$ancdeb."'";
		  $resuljour = $bd-> execRequete ($reqjour);
		  require ("leftprin.php");
		  if ( $resulsess == 1 and $resuljour == 1)
		      {
		      echo "<H5><center>".'Déconnecté'."</center><H5>";
		      return TRUE;
		      } 
		      
		      else
		          return FALSE;
            }
	   else
	       {
		  echo "<H5><center>"."Impossible de vous déconnecter"."</center></H5>";

	       }
        //}
        }
    else
        {
	   require ("leftprin.php");
	   echo "<H3><center>"."Vous n'étiez pas connecté, aussi il n'y pas eu de déconnexion"."</center></H3>";

        }
        //session_write_close();
    }
function formidentification($nomscript, $puserdef="")
    {
    //demande d'identification
        $Form = new formulaire ("POST", "$nomscript");
        $Form->debuttable();
        $Form->champtexte("Votre nom : ","nom","$puserdef",30);
        echo "<P>";
        $Form->champmotdepasse("Mot de passe : ","motdepasse","",30);
        echo "<P>";
        $Form->champvalider("Valider","Identification");
        //$Form->fin();
    }
function formidentvert($nomscript, $puserdef="",$ppwd="")
    {
    //demande d'identification vertical
    $Form = new Formulaire("POST", $nomscript);
    $Form->debuttable(Formulaire::HORIZONTAL,array(),1);
    //echo "<CENTER>";
    $Form->champtexte("Login : ","nom","",20);
    $Form->fintable();
    $Form->debuttable(Formulaire::HORIZONTAL,array(),1);
    $Form->champmotdepasse("Mot de passe : ","motdepasse","",10);
    $Form->fintable();
    $Form->debuttable(Formulaire::HORIZONTAL,array(),1);
    //echo "<CENTER>";
    $Form->champvalider("Valider","Identification");
    $Form->fintable();
    echo $Form->formulaireHTML();
    //$Form->fin();
    }
function logval($userid,$pwd)
    {
	   //require ("pconnec.php");
	   if ($userid!='' & $pwd!='')
	   {
	   //$mdp=md5($pwd);
	   $mdp=$pwd;
	   $bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
    	$reqpw = "SELECT  utilisateur.pwd FROM utilisateur where iduser='".$userid."' and pwd='".$mdp."'";
	   $respw = $bd->execRequete ($reqpw);
	   if (!$respw)
		return 0;
	   if ($bd->nbLigne($respw)>0)
		  return 1;
		else
		return 0;
	   }

    }
function logknow($user,$mail)
    {
	   //require ("pconnec.php");
	   if ($user!='' & $mail!='')
	   {
	       $bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	   $reqpw = "SELECT  count(*) FROM utilisateur where nom='".$user."' and email='".$mail."'";
	   $respw =$bd->execRequete ($reqpw);
	   if (!$respw)
		return 0;
	   if ($bd->nbLigne($respw)>0)
		return 1;
		else
		return 0;
	   }

    }
function chgpwd()
    {
	   // Gestion des changements de mots de passe
	   //require ("pconnec.php");
        $bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	   $userid=$_SESSION["useridok"];
	   //echo "<H3><center>".$userid."</center><H3>";
	   if (!valchps($_POST))
	   {
	    echo "<H3><center>".'Des champs sont vides'."</center><H3>";
		formchgpwd();
		exit;
	   }
	   else
		{
		if (!valpwd($_POST["Nmotdepasse"],$_POST["Cmotdepasse"]))
		{
	    	echo "<H3><center>".'La confirmation est diffèrente du nouveau mot de passe'."</center><H3>";
			formchgpwd();
			exit;
		}
		else
			{
			if ($_POST["Amotdepasse"]!='')
			 {

			if (!logval($userid,$_POST["Amotdepasse"]))
			{
				echo "<H3><center>".'Le mot de passe saisi n est pas votre mot de passe'."</center><H3>";
				formchgpwd();
				exit;
			}
			else
			{
			$reqchpw="update utilisateur set pwd='".md5($_POST["Nmotdepasse"])."' where iduser='".$userid."'";
			$reschpw =$bd->execRequete ($reqchpw);
			if (!$reschpw)
				return FALSE;
				else
				{
				echo "<H3><center>".'Changement de mot de passe effectué'."</center><H3>";
				return TRUE;
				}
			}
			}
			else
			formchgpwd();
			}
			}
	}
function formchgpwd()
	{
	$Form = new formulaire ("POST", "chgpwd.php");
    // Tableau en mode vertical, pour les champs simples
    echo "<CENTER><H2>".'Changement de mot de passe'."</H2></CENTER>\n";
	tblcellule(image('redbar.gif',"100%",2));
	$Form->debuttable();
	$Form->champmotdepasse("Ancien mot de passe : ","Amotdepasse","",30);
	$Form->champmotdepasse("Nouveau mot de passe : ","Nmotdepasse","",30);
	$Form->champmotdepasse("Confirmation nouveau mot de passe : ","Cmotdepasse","",30);
    $Form->fintable();
    // Tableau en mode horizontal pour le metteur en sc�ne
    $Form->ajoutTexte ("<P></P>");
    $Form->champvalider ("Valider", "valider");
    echo $Form->formulaireHTML();
    //$Form->fin();
	}
function formpwdoubl()
	{
	$Form = new formulaire ("POST", "mdpoubl.php");
    // Tableau en mode vertical, pour les champs simples
    echo "<CENTER><H2>".'Mot de passe oublié'."</H2></CENTER>\n";
	tblcellule(image('redbar.gif',"100%",2));
	$Form->debuttable();
	$Form->champtexte("Utilisateur : ","user","",30);
	$Form->champtexte("Mail : ","mail","",30);
    $Form->fintable();
    // Tableau en mode horizontal pour le metteur en sc�ne
    $Form->ajoutTexte ("<P></P>");
	echo "<H3><Center>".'Votre nouveau mot de passe vous sera envoyé par mail'."</Center></H3>";
    $Form->champvalider ("Valider", "valider");
    //$Form->fin();
	}
function formcreauser()
    {
    //require ("pconnec.php");
    //session_start();
    sessval();
    // Cr�ation du formulaire
    $bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
    //$Form = new formulaire ("POST", "$bd");
	$Form = new Formulaire("POST", "");
    $cuser=array("nom"=>"","prenom"=>"","service"=>"","dotcdcn"=>"","etablissement"=>"","portable"=>"","fax"=>"","telephone"=>"","email"=>"","datdeb"=>"");
    // Tableau en mode vertical, pour les champs simples
	$requete = "SELECT  distinct fonction,idfonc FROM metier order by fonction";
		$resultat = $bd->execRequete ($requete);
		$nomb_ligne=$bd->nbLigne($resultat);
	for ($j=0;$j<$nomb_ligne;$j++)
		{
		$ligne=$bd->objetSuivant($resultat);
		$listMetier[$ligne->idfonc]=$ligne->fonction;
		}
    // Tableau en mode vertical, pour les champs simples
    echo "<CENTER><H2>".'Nouvel utilisateur'."</H2></CENTER>\n";
	tblcellule(image('redbar.gif',"100%",2));
	$Form->debuttable();
    $Form->champTexte ("Nom :", "nom", $cuser['nom'], 30);
    $Form->champTexte ("Prénom :", "prenom", $cuser['prenom'], 30);
	$Form->champTexte ("Service :", "service", $cuser['service'], 30);
	$Form->champTexte ("DOTC DCN :", "dotcdcn", $cuser['dotcdcn'], 30);
	$Form->champTexte ("Etablissement :", "etablissement", $cuser['etablissement'], 30);
	$Form->champTexte ("Portable :", "portable", $cuser['portable'], 14);
	$Form->champTexte ("Fax :", "fax", $cuser['fax'], 14);
    $Form->champTexte ("Téléphone :", "telephone", $cuser['telephone'], 14);
	$Form->champTexte ("Email :", "email", $cuser['email'], 30);
	$Form->champTexte ("Date début :", "datdeb", $cuser['datdeb'], 10);
	$Form->champliste ("Fonction :", "fonction", "", 1, $listMetier);
	$Form->champmotdepasse("Mot de passe : ","motdepasse","",10);
    $Form->fintable();
    // Tableau en mode horizontal pour le metteur en sc�ne
    $Form->ajoutTexte ("<P></P>");
    $Form->champvalider ("Valider la saisie", "valider");
    //$Form->fin();
    echo $Form->formulaireHTML();
	}
function importfic()
    {
	if ($_FILES["impfich"]!="")
	{
		$fp=fopen($_FILES["impfich"]);
		$taille=filesize($_FILES["impfich"]);
		echo "<center><H3>"."Taille fichier :".$taille."</H3></center>";
		forminscrip();
	}
	else
		//echo "<center><H3>"."Erreur"."</H3></center>";
		forminscrip();

    }

?>