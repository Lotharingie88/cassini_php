<?php
function rec()
{
	session_start();
	//require ("pconnec.php");
	$iduserconn=$_SESSION["useridok"];
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	//if (!valchps($_POST))
	//{
	  //  echo "<H3><center>".'Des champs sont vides'."</center><H3>";
	//	formrec();
		//exit;
	//}
	if ($_POST['client']=="0" && $_POST['obj']!="")
	{
	    echo "<center><H3>".'Il faut s�lectionner un client'."</H3></center>";
		formrec();
		exit;
	}
	else
	if ($_POST["client"]!="" && $_POST["obj"]!="")
	{
		$datmaj= date("Y/m/d");
		if ($_POST["datfin"]!="")
		{
			$datret1=explode("/",$_POST["datfin"]);
		    $datfr= date("Y/m/d",mktime(0,0,0,$datret1[1],$datret1[0],$datret1[2]));
		}
			else
			{
			$datfr= ($_POST["datfin"]);
			}
		if ($_POST["datrec"]!="")
		{
			$datret2=explode("/",$_POST["datrec"]);
		    $datr= date("Y/m/d",mktime(0,0,0,$datret2[1],$datret2[0],$datret2[2]));
		}
			else
			{
			$datr= date("Y/m/d");
			}
			$reqcrdv="insert into reclamation (idclient,contact,motif,rep,corec,auteur,datmaj,ouvert,clot) values ('".$_POST["client"]."','".$_POST["contact"]."','".$_POST["obj"]."','".$_POST["rep"]."','".$_POST["typrec"]."','".$iduserconn."','".$datmaj."','".$datr."','".$datfr."')";
			$rescrdv= $bd->execRequete($reqcrdv);
				if (!$rescrdv)
					{
					echo "<center><H3>"."Anomalie! la r�clamation n'a pas �t� cr��"."</H3></center>";
					formrec();
					return FALSE;

					}
				else
				{
				echo "<center><H3>"."Nouvelle r�clamation cr��e"."</H3></center>";
				formerec();
				exit();
				}

	}
	else

	formrec();
}
function mrec()
{
	session_start();
	//require ("pconnec.php");
	$iduserconn=$_SESSION["useridok"];
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);

	//if (isset($_GET['action']))
		//{
	  formmajrec();


		//}
		//else
		//{
		//$datmaj= date("Y/m/d");
		//if ($_POST["datfin"]!="")
		//{
			//$datret1=explode("/",$_POST["datfin"]);
		    //$datfr= date("Y/m/d",mktime(0,0,0,$datret1[1],$datret1[0],$datret1[2]));
		//}
			//else
			//{
			//$datfr= ($_POST["datfin"]);
			//}
			//$num= $_GET["num"];
		//if ($_GET['action']=="M")
			//{
				//$reqmrdv="update reclamation set motif='".$_POST["obj"]."',rep='".$_POST["rep"]."',contact='".$_POST["cont"]."',corec='".$_POST["typrec"]."',clot='".$datfr."',datmaj='".$datmaj."' where idrec='".$_get["num"]."'";
				//$reqmrdv="update reclamation set motif='".$_POST["obj"]."',rep='".$_POST["rep"]."',contact='".$_POST["contact"]."' where idrec='".$num."'";
				//$reqcrdv="insert into reclamation (idclient,contact,motif,rep,corec,auteur,datmaj,ouvert,clot) values ('".$_POST["client"]."','".$_POST["contact"]."','".$_POST["obj"]."','".$_POST["rep"]."','".$_POST["typrec"]."','".$iduserconn."','".$datmaj."','".$datr."','".$datfr."')";
				//$resmrdv= $bd->execRequete ($reqmrdv);
				//if (!$resmrdv)
					//{
					//echo "<center><H3>"."Anomalie! la r�clamation n'a pas �t� modifi�e"."</H3></center>";
					//formajrec();
					//return FALSE;

					//}
				//else
				//{
				//echo "<center><H3>"."R�clamation modifi�e"."</H3></center>";
				//formerec();
				//exit();
				//}
			//}


}
function majrec()
{
	session_start();
	//require ("pconnec.php");
	//$iduserconn=$_SESSION["useridok"];
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
		$datmaj= date("Y/m/d");
		if ($_POST["datfin"]!="")
		{
			$datret1=explode("/",$_POST["datfin"]);
		    $datfr= date("Y/m/d",mktime(0,0,0,$datret1[1],$datret1[0],$datret1[2]));
		}
			else
			{
			$datfr= "0000/00/00";
			}
		$num= $_POST["recla"];
		//if ($_GET['action']=="M")
			//{
				$reqmrec="update reclamation set motif='".$_POST["obj"]."',rep='".$_POST["rep"]."',contact='".$_POST["contact"]."',corec='".$_POST["typrec"]."',clot='".$datfr."',datmaj='".$datmaj."' where idrec=".$num;
				//$reqmrec="update reclamation set motif='".$_POST["obj"]."',rep='".$_POST["rep"]."',contact='".$_POST["contact"]."' where idrec='".$num."'";
				//$reqcrdv="insert into reclamation (idclient,contact,motif,rep,corec,auteur,datmaj,ouvert,clot) values ('".$_POST["client"]."','".$_POST["contact"]."','".$_POST["obj"]."','".$_POST["rep"]."','".$_POST["typrec"]."','".$iduserconn."','".$datmaj."','".$datr."','".$datfr."')";
		$resmrec= $bd->execRequete ($reqmrec);
		if (!$resmrec)
			{
			echo "<center><H3>"."Anomalie! la r�clamation n'a pas �t� modifi�e"."</H3></center>";
			//formmajrec();
			formmajrec();
			return FALSE;
			}
			else
			{
			echo "<center><H3>"."R�clamation modifi�e"."</H3></center>";
			formerec();
			exit();
			}
}
function crdv()
{
	session_start();
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	if (!valchps($_POST))
	{
	    echo "<center><H3>".'Des champs sont vides'."</H3></center>";
		formcrdv();
		exit;
	}
	//if ($_POST["cli"]=="" or $_POST["dat"]=="" or $_POST["heu"]=="" or $_POST["resp"]=="" or $_POST["moti"]=="")
	//{
	    //echo "<H3><center>".'Des champs obligatoires sont vides'."</center><H3>";
		//formcrdv();
		//exit;
	//}
	else
	if ($_POST["cli"]!="0" and $_POST["dat"]!="")
			{
			$reqrdv="select count(*)  from rdv where datrdv='".$_POST["dat"]."' and rdv.idclient='".$_POST["cli"]."'";
			$resrdv = $bd->execRequete ($reqrdv);
			if (!$resrdv)
				return FALSE;
			$crdv=mysql_result($resrdv,0,0);
			if ($crdv >0)
				{
				echo "<center><H3>"."Il y a d�j� un RDV avec ce client � cette date"."</H3></center>";
				formcrdv();
				exit();
				}
			else
				{
				$datmaj=date("Y/m/d");
			$reqcrdv="insert into rdv (idclient,idmot,datrdv,hrdv,iduser,datmaj) values ('".$_POST["cli"]."','".$_POST["moti"]."','".$_POST["dat"]."','".$_POST["heu"]."','".$_POST["resp"]."','".$datmaj."')";
			$rescrdv= $bd->execRequete ($reqcrdv);
				if (!$rescrdv)
					{
					echo "<center><H3>"."Anomalie! le rdv n'a pas �t� cr��"."</H3></center>";
					formcrdv();
					return FALSE;

					}
				else
				{
				echo "<center><H3>"."Nouveau RDV cr��"."</H3></center>";
				formrdv();
				exit();
				}
				}
			}
		else
	formcrdv();
	//session_write_close();
}
function crnumi()
{
	session_start();
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	if (!valchps($_POST))
	{
	    echo "<center><H3>".'Des champs sont vides'."</H3></center>";
		formcrnumi();
		exit;
	}
	else
	if ($_POST["nom"]!="")
			{
			$reqnmonn="select count(*)  from monnaie where monnaie='".$_POST["nom"]."'";
			$resnmonn = $bd->execRequete ($reqnmonn);
			if (!$resnmonn)
				return FALSE;
			$cmonn=mysql_result($resnmonn,0,0);
			if ($cmonn >0)
				{
				echo "<center><H3>"."Il y a d�j� une monnaie portant ce nom"."</H3></center>";
				formcrnumi();
				exit();
				}
			else
				{
				$datmaj=date("Y/m/d");
			$reqnmonn="insert into monnaie (monnaie,lbcmo,datemaj) values ('".$_POST["nom"]."','".$_POST["symb"]."','".$_POST["datdeb"]."')";
			$resnmonn= $bd->execRequete ($reqnmonn);
			$reqidmonn="select idmonnaie from monnaie where monnaie='".$_POST["nom"]."'";
			$residmonn= $bd->execRequete ($reqidmonn);
			$lig=$bd->ligTabSuivant($residmonn[0]);
			$reqcrsmonn="insert into courmonnaie (idmonnaie,coureuro,date,datmaj) values ('".$lig."','".$_POST["crs"]."','".$_POST["datdeb"]."','".$datmaj."')";
			$rescrsmonn= $bd->execRequete ($reqcrsmonn);
				if (!$resnmonn)
					return FALSE;
				else
				{
				echo "<center><H3>"."Nouvelle monnaie cr��e"."</H3></center>";
				formmnumi();
				exit();
				}
				}
			}
		else
	formcrnumi();
	//session_write_close();
}
function gestcomm()
{
	session_start();
	//require ("pconnec.php");
	//$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	if (!valchps($_POST))
	{
	    echo "<center><H3>".'Des champs sont vides'."</H3></center>";
		formcbdc();
		exit;
	}
	else
	if ($_POST["gam"]!="")
			{
				echo "<center><H3>".$_POST["gam"]."</H3></center>";
		formcbdc();
		exit;
			}
		else
	formcbdc();
	//session_write_close();
}
function ddevis()
{
	//session_start();
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);

	//if (isset($_GET['infcli']))
	//	{
		//	$client['rs']=$_GET[$client['rs']];
		  //  $reqcli = "SELECT  distinct client.idclient,client.rs,client.siret FROM client where concat(rs,'-',idclient)='".$client['rs']."'";
		//	$rescli = $bd->execRequete ($reqcli);
			//$nomb_ligcli=mysql_num_rows($rescli);
			//if ($nomb_ligcli>0)
			//{
				//$ligcli=mysql_fetch_object($rescli);
				//$client['siret']=$ligcli[2];
		//	}
		//}

	if (isset($_POST["client"]) && $_POST["client"]="" && isset($_POST['infcli']))
	//if ( isset($_POST['infcli']))
	{
	    echo "<center><H3>".'Il faut s�lectionner un client'."</H3></center>";
		formddevis();
		exit;
	}
	else
	if (isset($_POST["client"]) && $_POST["client"]!="")
			{
			$datmaj=date("Y/m/d");

			$client=$_POST["client"];
		  	$reqcli = "SELECT  distinct client.idclient,client.rs,client.siret FROM client where idclient='".$client."'";
			$rescli = $bd->execRequete ($reqcli);
			$nomb_ligcli=mysql_num_rows($rescli);
			if ($nomb_ligcli>0)
			{
				$ligcli=mysql_fetch_row($rescli);
				$client['siret']=$ligcli[2];
			}



			$reqprod="select idproduit  from produit where libproduit='".$_POST["prod"]."'";
			$resprod = $bd->execRequete ($reqprod);
			$lig=mysql_fetch_row($resprod);
			$reqcatprod="select idproduit,idunit,initarif  from tarifcatal where idproduit='".$lig[0]."'";
			$rescatprod = $bd->execRequete ($reqcatprod);
			$ligcat=mysql_fetch_row($rescatprod);

			$reqclotarif="update tarifcatal set datfin='".$_POST["datdeb"]."',datmaj='".$datmaj."' where idproduit='".$lig[0]."'and datfin='0000-00-00'";
			$resclotarif = $bd->execRequete ($reqclotarif);
			$reqnoutar="insert into tarifcatal (idproduit,idunit,tarunit,datdeb,datmaj,initarif) values ('".$ligcat[0]."','".$ligcat[1]."','".$_POST["ntar"]."','".$_POST["ndatdeb"]."','".$datmaj."','".$ligcat[2]."')";
			$resnoutar = $bd->execRequete ($reqnoutar);

			if (!$rescatprod)
				return FALSE;
				else
			formddevis();
			exit;
			}
	else

	formddevis();
}
function mstock()
{
	//session_start();
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	if (!valchps($_POST))
	{
	    echo "<center><H3>".'Des champs sont vides'."</H3></center>";
		formmajstock();
		exit;
	}
	else
	if ($_POST["ref"]!="" & $_POST["unit"]!="")
			{
			$datmaj=date("Y/m/d");
			$reqsto="select produit.idproduit,unite.idunit,stock.datdeb  from produit,unite,stock where codprod='".$_POST["ref"]."' and produit.idproduit=stock.idproduit and stock.idunit=unite.idunit";
			$ressto = $bd->execRequete($reqsto);
			$lig=$bd->ligTabSuivant($ressto);
			//$requnistoc="update stock set idunit='".$_POST["unit"]."' where idproduit='".$lig[0]."'";
			$reqstoc="insert into stock (idproduit,idunit,datdeb,qte,ctunit,datmaj) values ('".$lig[0]."','".$lig[1]."','".date("Y/m/d",mktime($lig[2]))."','".$_POST["sto"]."','".$_POST["ct"]."','".$datmaj."')";
			$resstoc = $bd->execRequete ($reqstoc);
			if (!$ressto)
				return FALSE;
				else
			formmstock();
			exit;
			}
	else
	formmajstock();
}
function mrdv()
{
	//session_start();
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	if (!valchps($_POST))
{
	    echo "<center><H3>".'Des champs sont vides'."</H3></center>";
		formmajrdv();
		exit;
	}
	//if ($_POST["idcli"]=="" or $_POST["dat"]==""or $_POST["heu"]==""or $_POST["resp"]=="")
	//{
	    //echo "<H3><center>".'Des champs obligatoires sont vides'."</center><H3>";
		//formmajrdv();
		//exit;
	//}
	else
	if ($_POST["idcli"]!="" & $_POST["concl"]!="")
			{
			$datmaj=date("Y/m/d");
			//$reqrdv="select idrdv  from rdv where idclient='".$_POST["idcli"]."' and datrdv='".$_POST["dat"]."'";
			//$resrdv = $bd->execRequete ($reqrdv);
			//$lig=mysql_fetch_row($resrdv);
			$reqmrdv="update rdv set conclusion='".$_POST["concl"]."',datrdv='".$_POST["dat"]."',hrdv='".$_POST["heu"]."',datmaj='".$datmaj."' where idrdv='".$_GET["rdv"]."'";
			$resmrdv =$bd-> execRequete($reqmrdv);
			if (!$resmrdv)
				return FALSE;
				else
			formrdv();
			exit;
			}
	else
	formmajrdv();
}
function mnumi()
{
	//session_start();
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	if (!valchps($_POST))
	{
	    echo "<center><H3>".'Des champs sont vides'."</H3></center>";
		formmajnumi();
		exit;
	}
	else
	if ($_POST["nom"]!="" & $_POST["ncou"]!="")
			{
			$dat=convdat($_POST["datdeb"]);
			$datmaj=date("Y/m/d");
			$reqnumi="select idmonnaie  from monnaie where monnaie='".$_POST["nom"]."'";
			$resnumi = $bd-> execRequete ($reqnumi);
			$lig=$bd->ligTabSuivant($resnumi);
			$reqacour="update courmonnaie set etcour='C',datmaj='".$datmaj."' where idmonnaie='".$lig[0]."'and coureuro='".$_POST[acou]."'";
			$resacour = $bd-> execRequete ($reqacour);
			$reqncour="insert into courmonnaie (idmonnaie,coureuro,date,datmaj) values ('".$lig[0]."','".$_POST[ncou]."','".$_POST["datdeb"]."','".$datmaj."')";
			$resncour = $bd-> execRequete ($reqncour);
			if (!$resnumi)
				return FALSE;
				else
			formmnumi();
			exit;
			}
	else
	formmajnumi();
	}
function mtarif()
{
	//session_start();
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	if (!valchps($_POST))
	{
	    echo "<center><H3>".'Des champs sont vides'."</H3></center>";
		formmajtarif();
		exit;
	}
	else
	if ($_POST["prod"]!="" & $_POST["ntar"]!="")
			{
			$datmaj=date("Y/m/d");
			if ($_POST["datdeb"]!="")
		{
			$datclo1=explode("/",$_POST["datdeb"]);
		    $datclo= date("Y/m/d",mktime(0,0,0,$datclo1[1],$datclo1[0],$datclo1[2]));
		}
			else
			{
			$datclo= ($_POST["datdeb"]);
			}

			$reqprod="select idproduit  from produit where libproduit='".$_POST["prod"]."'";
			$resprod = $bd-> execRequete ($reqprod);
			$lig=$bd->ligTabSuivant($resprod);
			$reqcatprod="select idproduit,idunit,initarif  from tarifcatal where idproduit='".$lig[0]."'";
			$rescatprod = $bd-> execRequete ($reqcatprod);
			$ligcat=$bd->ligTabSuivant($rescatprod);

			$reqclotarif="update tarifcatal set datfin='".$datclo."',datmaj='".$datmaj."' where idproduit='".$lig[0]."'and datfin='0000-00-00'";
			$resclotarif = $bd-> execRequete ($reqclotarif);
			$reqnoutar="insert into tarifcatal (idproduit,idunit,tarunit,datdeb,datmaj,initarif) values ('".$ligcat[0]."','".$ligcat[1]."','".$_POST["ntar"]."','".$datclo."','".$datmaj."','".$ligcat[2]."')";
			$resnoutar = $bd-> execRequete ($reqnoutar);

			if (!$rescatprod)
				return FALSE;
				else
			formmtarif();
			exit;
			}
	else

	formmajtarif();
}
function ntarif()
{
	//session_start();
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	if (!valchps($_POST))
	{
	    echo "<center><H3>".'Des champs sont vides'."</H3></center>";
		formntarif();
		exit;
	}
	else
	if ($_POST["prod"]!="" & $_POST["tar"]!="")
			{
			$datmaj=date("Y/m/d");
			$reqprod="select idproduit  from produit where libproduit='".$_POST["prod"]."'";
			$resprod = $bd-> execRequete ($reqprod);
			$lig=$bd->ligTabSuivant($resprod);
			$requntarif="update tarifcatal set tarunit='".$_POST["tar"]."', datdeb='".$_POST["datdeb"]."',datmaj='".$datmaj."',idunit='".$_POST["unit"]."',initarif='O' where idproduit='".$lig[0]."' and datfin='0000-00-00'";
			$resuntarif = $bd-> execRequete ($requntarif);
			$requnistoc="update stock set idunit='".$_POST["unit"]."' where idproduit='".$lig[0]."'";
			$resunistoc = $bd-> execRequete ($requnistoc);
			if (!$resuntarif)
				return FALSE;
				else
			formmtarif();
			exit;
			}
	else
	formntarif();
}
function gamm()
{
	session_start();
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	if (!valchps($_POST))
	{
	    echo "<center><H3>".'Des champs sont vides'."</H3></center>";
		formgamm();
		exit;
	}
	else
	if ($_POST["lblgam"]!="")
			{
			$reqngam="select count(*)  from gamme where gamme='".$_POST["lblgam"]."'";
			$resngam = $bd-> execRequete ($reqngam);

			if (!$resngam)
				return FALSE;
				$cgam=$bd->ligTabSuivant($resngam);
			if ($cgam[0] >0)
				{
				echo "<center><H3>"."Il y a d�j� une gamme portant ce nom"."</H3></center>";
				formgamm();
				exit();
				}
			else
				{

				//$datmaj=date("Y/m/d");
			$reqngam="insert into gamme (gamme,libcgam,datcreate) values ('".$_POST["lblgam"]."','".$_POST["lbcgam"]."','".$_POST["datdeb"]."')";
			$resngam= $bd-> execRequete ($reqngam);
				if (!$resngam)
					return FALSE;
				else
				{
				echo "<center><H3>"."Nouvelle gamme cr��e"."</H3></center>";
				//$reqfonc="select libfonc  from metier where idfonc='".$_POST["fonction"]."'";
				//$resfonc = $bd->execRequete ($reqfonc);
				//$ligne=mysql_fetch_row($resfonc);
				//return TRUE;
				formmproduit();
				exit();
				}
				}
			}
	formgamm();
	//session_write_close();
}
function famm()
{
	session_start();
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	if (!valchps($_POST))
	{
	    echo "<center><H3>".'Des champs sont vides'."</H3></center>";
		formfamm();
		exit;
	}
	else
	if ($_POST["lblfam"]!="")
			{
			$reqnfam="select count(*)  from famille where lbfam='".$_POST["lblfam"]."'";
			$resnfam = $bd-> execRequete ($reqnfam);

			if (!$resnfam)
				return FALSE;
				$cfam=$bd->ligTabSuivant($resnfam);
			if ($cfam[0] >0)
				{
				echo "<center><H3>"."Il y a d�j� une famille portant ce nom"."</H3></center>";
				formfamm();
				exit();
				}
			else
				{
				//$datmaj=date("Y/m/d");
				//$reqgam="select idgamme  from gamme where idgamme='".$_POST["idgamme"]."'";
				//$resgam = $bd->execRequete ($reqgam);
				//$igam=mysql_fetch_row($resgam);
			$reqnfam="insert into famille (lbfam,libcfam,idgamme,datcreat) values ('".$_POST["lblfam"]."','".$_POST["lbcfam"]."','".$_POST["gamm"]."','".$_POST["datdeb"]."')";
			$resnfam= $bd-> execRequete ($reqnfam);
				if (!$resnfam)
					return FALSE;
				else
				{
				echo "<center><H3>"."Nouvelle famille cr��e"."</H3></center>";
				//$reqfonc="select libfonc  from metier where idfonc='".$_POST["fonction"]."'";
				//$resfonc = $bd->execRequete ($reqfonc);
				//$ligne=mysql_fetch_row($resfonc);
				//return TRUE;
				formmproduit();
				//require ("mproduct.php");
				exit();
				}
				}
			}
	formfamm();
	//session_write_close();
}
function cproduit()
{
	session_start();
	//require ("pconnec.php");

	enteteg("O","Synapsat Saturn FDV");

	echo "<div id=\"bandeau\">";

	bandeau();

	echo "</div>";
	echo "<div id=\"menugauche\">";
	echo "<div id=\"menugaucheh\">";

		menghaccs();


	echo "</div>";
	echo "<div id=\"menugauchem\">";

		confconn();


	echo "</div>";
	echo "<div id=\"menugaucheb\">";

	echo "<center><H3>".'ESPACE LIBRE'."</H3></center>";
	echo "<UL>";
	echo"<LI>".ancre("left2.php",'R�clamations')."</LI>";
	echo"<LI>".ancre("left4.php",'Suivi clients')."</LI>";
	echo"<LI>".ancre("left5.php",'Facturation-Vente')."</LI>";
	echo"<LI>".ancre("left6.php",'Informations')."</LI>";
	echo"<LI>".ancre("left7.php",'Statistique')."</LI>";
	echo"<LI>".ancre("left8.php",'Administration')."</LI>";
		echo "<UL>";
				echo"<LI>".ancre("creuser.php",'Cr�ation d utilisateurs')."</LI>";
				echo"<LI>".ancre("suali.php",'Suivi alimentations')."</LI>";
				echo"<LI>".ancre("suconnex.php",'Suivi connexions')."</LI>";
				echo"<LI>".ancre("impofile.php",'Importation de donn�es')."</LI>";
				echo"<LI>".ancre("expofile.php",'Exportation de donn�es')."</LI>";
				echo"<LI>".ancre("mufdv.php",'MAJ Utilisateurs et FDV')."</LI>";
				echo"<LI>".ancre("mcts.php",'MAJ Co�ts internes')."</LI>";
				echo"<LI>".ancre("mmonn.php",'MAJ Monnaie')."</LI>";
				echo"<LI>".ancre("mland.php",'MAJ Pays')."</LI>";
				echo"<LI>".ancre("mogeo.php",'MAJ Organisation territoriale')."</LI>";
				echo"<LI>".ancre("mproduct.php",'MAJ Produits')."</LI>";
				echo"<LI>".ancre("mbarem.php",'MAJ Tarifs')."</LI>";
				echo"<LI>".ancre("mstock.php",'Gestion stocks')."</LI>";
				echo"<LI>".ancre("mparm.php",'MAJ Param�tres')."</LI>";
		echo "</UL>";
	echo "</UL>";
	pieddepage("master");

	echo "</div>";

	echo "</div>";
	echo "<div id=\"cont_princ\">";

	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	if (!valchps($_POST))
	{
	    echo "<center><H3>".'Des champs sont vides'."</H3></center>";
		formcproduit();
		exit;
	}
	else
	if ($_POST["lblproduit"]!="")
			{
			$reqnpro="select count(*)  from produit where libproduit='".$_POST["lblproduit"]."'";
			$resnpro =$bd-> execRequete ($reqnpro);

			if (!$resnpro)
				return FALSE;
				$cpro=$bd->ligTabSuivant($resnpro);
			if ($cpro[0] >0)
				{
				echo "<center><H3>"."Il y a d�j� un produit portant ce nom"."</H3></center>";
				formcproduit();
				exit();
				}
			else
				{

				$datmaj=date("Y/m/d");
				if ($_POST["datdeb"]!="")
				{
					$datouv1=explode("/",$_POST["datdeb"]);
		    		$datouv= date("Y/m/d",mktime(0,0,0,$datouv1[1],$datouv1[0],$datouv1[2]));
				}
				else
				{
				$datouv= ($_POST["datdeb"]);
				}
				//$reqgam="select idgamme  from gamme where idgamme='".$_POST["idgamme"]."'";
				//$resgam = $bd->execRequete ($reqgam);
				//$igam=mysql_fetch_row($resgam);
			$reqnpro="insert into produit (libproduit,libcpro,idfamille,datdeb,codprod,datemaj) values ('".$_POST["lblproduit"]."','".$_POST["lbcproduit"]."','".$_POST["fam"]."','".$datouv."','".$_POST["ref"]."','".$datmaj."')";
			$resnpro= $bd-> execRequete ($reqnpro);
			$reqidpro="select idproduit from produit where libproduit='".$_POST["lblproduit"]."'";
			$residpro=$bd-> execRequete ($reqidpro);
			$lig=$bd->ligTabSuivant($residpro);
			$reqtarpro="insert into tarifcatal (idproduit,datdeb,initarif) values ('".$lig[0]."','".$datouv."','N')";
			$restarpro= $bd-> execRequete ($reqtarpro);
			if (!$restarpro)
			    return FALSE;
			    else
			        return TRUE;
			$reqstopro="insert into stock (idproduit,idunit,datdeb,qte,datmaj) values ('".$lig[0]."','".$_POST["uvent"]."','".$datouv."','0',$datmaj)";
			$resstopro= $bd-> execRequete ($reqstopro);
			if (!$resstopro)
			    return FALSE;
			    else
			        return TRUE;
				if (!$resnpro)
					return FALSE;
				else
				{
				echo "<center><H3>"."Nouveau produit cr��"."</H3></center>";
				//$reqfonc="select libfonc  from metier where idfonc='".$_POST["fonction"]."'";
				//$resfonc = $bd->execRequete ($reqfonc);
				//$ligne=mysql_fetch_row($resfonc);
				//return TRUE;
				formmproduit();
				exit();
				}
				}
			}
	formcproduit();



	echo "</div>";

	finpag();

	//session_write_close();
}
//--------------------------------------------------------------Gestion des Importations--------------------------------------------------------------------------
function gesimp()
    {
	//require ("pconnec.php");
	sessval();
    //session_start();
	//$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	if (isset($_POST["typfichier"]))
	   $tfich=$_POST["typfichier"];
	   else $tfich="";
	if (isset($_POST["niveau"]))
	   $niv=$_POST["niveau"];
	   else $niv="";
		if($niv==1 and ($tfich==1 or $tfich==2 or $tfich==12 or $tfich==14 or $tfich==15))
		   {
			//$fich=$_FILES["impfich"];
			$fichs=$_FILES["impfich"]['size'];
			$fichn=$_FILES["impfich"]['name'];
			formimport2($fichn,$fichs,$tfich,$niv);
			exit;
		   }
	       else
	          {
	            if (isset($_FILES ["impfich"]) && $_FILES["impfich"]=="")
                	{
		                echo "<center><H3>".'Il faut sélectionner un fichier'."</H3></center>";
		                formimport();
	                 	exit;
	                }
	                else
	                {
	                  if (isset($_FILES["impfich"]))
                 	    {
		                  //$fich=$_FILES["impfich"];
		                  $fichs=$_FILES["impfich"]['size'];
		                  $fichn=$_FILES["impfich"]['name'];
	                      formimport1($fichn,$fichs,$tfich);
	                    }
	                     else
	                         {
	               	           formimport("1");
	                         }
	               }
	       }
    }
//--------------------------------------------------------------Gestions des Utilisateurs-------------------------------------------------------------------------
function listuser()
    {
	//require ("pconnec.php");

	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	//if (!valchps($_POST))
	//{
	//  echo "<H3><center>".'Des champs sont vides'."</center><H3>";
	//	formrec();
	//exit;
	//}
	if (isset($_FILES ["impfich"]) && $_FILES["impfich"]=="")
	{
		echo "<center><H3>".'Il faut sélectionner un fichier'."</H3></center>";
		formlistuser();
		exit;
	}
	else
	if (isset($_FILES["impfich"]))
	{
		$fich=$_FILES["impfich"];
		$fichs=$_FILES["impfich"]['size'];
		$fichn=$_FILES["impfich"]['name'];

		//$tfich2=$_POST["listfichier2"];
		//$reqjoueur="insert into t_joueurs (nomjoueur,prenjoueur,an_naissance,sais_joueur,telephone,adresse,licence,arbitre,dirigeant,benjamin) values ('".$_POST["nomj"]."','".$_POST["prenj"]."','".$_POST["naiss"]."','".$_POST["saisj"]."','".$tel."','".$adr."','".$lic."','".$arb."','".$dir."','".$benj."')";
		//$resjoueur= $bd->execRequete ($reqjoueur);


		//if (!$resjoueur)
		//{
		//	echo "<center><H3>"."Anomalie! le nouveau joueur n'a pas �t� cr��"."</H3></center>";
		//	formimport();
		//	return FALSE;

		//}
		//else
		//{
		//	echo "<center><H3>"."Nouveau joueur cr��"."</H3></center>";
		formlistuser1($fichn,$fichs);
		//	exit();
		//}

	}
	else

		formlistuser();
    }
    function impUser()
    {
        echo "<center><H3>"."TEST"."</H3></center>";
    }
//-------------------------------------------------------------------Vision Nationale-----------------------------------------------------------------------------
function majcunat()
    {
	$_SESSION['anc']="";
	$_SESSION['perc']="";
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	if (isset($_POST["ann"]) and isset($_POST["perio"]) and (($_POST["ann"]==0) or ($_POST["perio"]==0)))
	{
		echo "<CENTER><H6>".'Attention, vous devez séélectionner une année et une pèriode.'."</H6></CENTER>\n";

		formcunat();
		exit;
	}
	else
	{
		if (isset($_POST["ann"]) and isset($_POST["perio"]) and ($_POST["ann"]<>0) and ($_POST["perio"]<>0))
		{
			echo "<CENTER><H2>".$_POST["ann"]." et ".$_POST["perio"]."</H2></CENTER>\n";
			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=$bd->ligTabSuivant($rescodan);
			$_POST['libann']=$codannee[0];
			$_SESSION['anc']=$codannee[0];

			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=$bd->ligTabSuivant($rescodper);
			$_POST['libperio']=$codperiode[0];
			$_SESSION['perc']=$codperiode[0];
            echo "<CENTER><H2>".$_POST["ann"]." et ".$_POST["perio"].",".$_POST["libann"]." et ".$_POST["libperio"]."</H2></CENTER>\n";

			//echo "<CENTER><H6>".$ancour." , ".$percour."</H6></CENTER>\n";
			formcunat($_POST["libann"],$_POST['libperio']);
		}
		else
		{
			formcunat();
		}
	}
    }
function majcharge()
    {
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	if (isset($_POST["ann"]) and isset($_POST["perio"]) and (($_POST["ann"]==0) or ($_POST["perio"]==0)))
	{
		echo "<CENTER><H6>".'Attention, vous devez sélectionner une année et une pèriode.'."</H6></CENTER>\n";

		formcharge();
		exit;
	}
	else
	{
		if (isset($_POST["ann"]) and isset($_POST["perio"]) and ($_POST["ann"]<>0) and ($_POST["perio"]<>0))
		{
			echo "<CENTER><H6>".'OK3'."</H6></CENTER>\n";
			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee= $bd->ligTabSuivant($rescodan[0]);
			$_POST['ann']=$codannee;
			$_SESSION['anc']=$codannee;

			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode= $bd->ligTabSuivant($rescodper[0]);
			$_POST['perio']=$codperiode;
			$_SESSION['perc']=$codperiode;

			echo "<CENTER><H6>".$codperiode." , ".$codannee."</H6></CENTER>\n";
			formcharge($codannee,$codperiode);
		}
		else
		{
			formcharge();
		}
	}
    }
function majtraf()
    {
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	if (isset($_POST["ann"]) and isset($_POST["perio"]) and (($_POST["ann"]==0) or ($_POST["perio"]==0)))
	{
		echo "<CENTER><H6>".'Attention, vous devez sélectionner une année et une pèriode.'."</H6></CENTER>\n";

		formtrafic();
		exit;
	}
	else
	{
		if (isset($_POST["ann"]) and isset($_POST["perio"]) and ($_POST["ann"]<>0) and ($_POST["perio"]<>0))
		{
			echo "<CENTER><H6>".'OK2'."</H6></CENTER>\n";
			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=$bd->ligTabSuivant($rescodan);
			$_POST['ann']=$codannee[0];
			$_SESSION['anc']=$codannee[0];

			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=$bd->ligTabSuivant($rescodper);
			$_POST['perio']=$codperiode[0];
			$_SESSION['perc']=$codperiode[0];

			echo "<CENTER><H6>".$codperiode." , ".$codannee."</H6></CENTER>\n";
			formtrafic($codannee[0],$codperiode[0]);
		}
		else
		{
			formtrafic();
		}
	}
    }
function majcunit()
    {
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	if (isset($_POST["ann"]) and isset($_POST["perio"]) and (($_POST["ann"]==0) or ($_POST["perio"]==0)))
	{
		echo "<CENTER><H6>".'Attention, vous devez sélectionner une année et une pèriode.'."</H6></CENTER>\n";

		formcunit();
		exit;
	}
	else
	{
		if (isset($_POST["ann"]) and isset($_POST["perio"]) and ($_POST["ann"]<>0) and ($_POST["perio"]<>0))
		{
			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=$bd->ligTabSuivant($rescodan);
			$_POST['ann']=$codannee[0];
			$_SESSION['anc']=$codannee[0];

			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=$bd->ligTabSuivant($rescodper);
			$_POST['perio']=$codperiode[0];
			$_SESSION['perc']=$codperiode[0];

			formcunit($codannee[0],$codperiode[0]);
		}
		else
		{
			formcunit();
		}
	}
    }
function majretrnat()
    {
	$_SESSION['anc']="";
	$_SESSION['perc']="";
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	if (isset($_POST["ann"]) and isset($_POST["perio"]) and (($_POST["ann"]==0) or ($_POST["perio"]==0)))
	{
		echo "<CENTER><H6>".'Attention, vous devez sélectionner une année et une pèriode.'."</H6></CENTER>\n";

		formretrnat();
		exit;
	}
	else
	{
		if (isset($_POST["ann"]) and isset($_POST["perio"]) and ($_POST["ann"]<>0) and ($_POST["perio"]<>0))
		{
			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=$bd->ligTabSuivant($rescodan);
			$_POST['ann']=$codannee[0];
			$_SESSION['anc']=$codannee[0];

			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=$bd->ligTabSuivant($rescodper);
			$_POST['perio']=$codperiode[0];
			$_SESSION['perc']=$codperiode[0];

			formretrnat($codannee[0],$codperiode[0]);
		}
		else
		{
			formretrnat();
		}
	}
    }
//----------------------------------------------------------------------Vision DOTC-------------------------------------------------------------------------------
function majcudotc()
    {
	//require ("pconnec.php");
	$_SESSION['anc']="";
	$_SESSION['perc']="";
	$_SESSION['dotcr']="";
	$_SESSION['libdotc']="";
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	//	echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["ann"].";".$_POST["perio"]." 1</H1></CENTER>\n";
	$anc=date('Y')-1;
	if (isset($_POST["dotc"]) and ($_POST["dotc"]==0 or $_POST["ann"]==0 or $_POST["perio"]==0))
	{
		echo "<CENTER><H6>".'Attention, vous devez sélectionner une Dotc, une année et une pèriode.'."</H6></CENTER>\n";

		formcudotc(99);
		exit;
	}
	else
	{
		//		echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["ann"].";".$_POST["perio"]." 2</H1></CENTER>\n";
		if ($_POST["dotc"]!=0 and $_POST["ann"]!=0 and $_POST["perio"]!=0)
		{
			$reqcodep="select codep from dotc where cdotc='".$_POST['dotc']."'";
			$rescodep = $bd->execRequete($reqcodep);
			$codep=$bd->ligTabSuivant($rescodep);
			$_POST['dotcr']=$codep[0];
			$_SESSION['dotcr']=$codep[0];

			$reqlib="select libdotc from dotc where cdotc='".$_POST['dotc']."'";
			$reslib = $bd->execRequete($reqlib);
			$colib=$bd->libTabSuivant($reslib);
			$_POST['libdotc']=$colib[0];
			$_SESSION['libdotc']=$colib[0];

			//echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["ann"].";".$_POST["perio"]." 3</H1></CENTER>\n";
			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=$bd->libTabSuivant($rescodan);
			$_POST['ann']=$codannee[0];
			$_SESSION['anc']=$codannee[0];

			//echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["perio"].";".$lig." 4</H1></CENTER>\n";
			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=$bd->libTabSuivant($rescodper);
			$_POST['perio']=$codperiode[0];
			$_SESSION['perc']=$codperiode[0];

			//echo "<CENTER><H1>".$_POST["dotc"].";".$lig.";".$mois[1]." 5</H1></CENTER>\n";
			formcudotc($codep[0],$codannee[0],$codperiode[0]);
		}
		else
		{
			formcudotc(99);
		}
	}
    }
function majchargedotc()
    {
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	//	echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["ann"].";".$_POST["perio"]." 1</H1></CENTER>\n";
	$anc=date('Y')-1;
			//echo "<CENTER><H6>".'OK'."</H6></CENTER>\n";
	if (isset($_POST["dotc"]) and ($_POST["dotc"]==0 or $_POST["ann"]==0 or $_POST["perio"]==0))
	{
		echo "<CENTER><H6>".'Attention, vous devez s�lectionner une Dotc, une ann�e et une p�riode.'."</H6></CENTER>\n";

		formchargedotc(99);
		exit;
	}
	else
	{
			//	echo "<CENTER><H6>".'OK2'."</H6></CENTER>\n";
		if ($_POST["dotc"]!=0 and $_POST["ann"]!=0 and $_POST["perio"]!=0)
		{
				echo "<CENTER><H6>".'OK3'."</H6></CENTER>\n";
			$reqcodep="select codep from dotc where cdotc='".$_POST['dotc']."'";
			$rescodep = $bd->execRequete($reqcodep);
			$codep=mysql_result($rescodep,0,0);
			$_POST['dotc']=$codep;
			$_SESSION['dotcr']=$codep;

			//	echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["ann"].";".$_POST["perio"]." 3</H1></CENTER>\n";
			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=mysql_result($rescodan,0,0);
			$_POST['ann']=$codannee;
			$_SESSION['anc']=$codannee;

			//	echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["perio"].";".$lig." 4</H1></CENTER>\n";
			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=mysql_result($rescodper,0,0);
			$_POST['perio']=$codperiode;
			$_SESSION['perc']=$codperiode;

			echo "<CENTER><H1>".$_POST["dotc"].";".$lig.";".$mois[1]." 5</H1></CENTER>\n";
			formchargedotc($codep,$codannee,$codperiode,$libdotc);
		}
		else
		{
			formchargedotc(99);
		}
	}
    }
//page cout unitaire dotc
function majtraficdotc()
    {
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	$anc=date('Y')-1;
	if (isset($_POST["dotc"]) and ($_POST["dotc"]==0 or $_POST["ann"]==0 or $_POST["perio"]==0))
	{
		echo "<CENTER><H6>".'Attention, vous devez s�lectionner une Dotc, une ann�e et une p�riode.'."</H6></CENTER>\n";

		formtraficdotc(99);
		exit;
	}
	else
	{
		if ($_POST["dotc"]!=0 and $_POST["ann"]!=0 and $_POST["perio"]!=0)
		{
			echo "<CENTER><H6>".'OK2'."</H6></CENTER>\n";

			$reqcodep="select codep from dotc where cdotc='".$_POST['dotc']."'";
			$rescodep = $bd->execRequete($reqcodep);
			$codep=mysql_result($rescodep,0,0);
			$_POST['dotc']=$codep;
			$_SESSION['dotcr']=$codep;

			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=mysql_result($rescodan,0,0);
			$_POST['ann']=$codannee;
			$_SESSION['anc']=$codannee;

			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=mysql_result($rescodper,0,0);
			$_POST['perio']=$codperiode;
			$_SESSION['perc']=$codperiode;

			echo "<CENTER><H1>".$_POST["dotc"].";".$lig.";".$mois[1]." 5</H1></CENTER>\n";
			formtraficdotc($codep,$codannee,$codperiode);
		}
		else
		{
			formtraficdotc(99);
		}
	}
    }
    //page cout unitaire dotc
function majcunitdotc()
    {
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	//	echo "<CENTER><H1>".$_POST["dotc"]."</H1></CENTER>\n";
	$anc=date('Y')-1;
	//	echo "<CENTER><H6>".'OK1'."</H6></CENTER>\n";
	if (isset($_POST["dotc"]) and ($_POST["dotc"]==0 or $_POST["ann"]==0 or $_POST["perio"]==0))
	{
		echo "<CENTER><H6>".'Attention, vous devez s�lectionner une Dotc, une ann�e et une p�riode.'."</H6></CENTER>\n";

		formcunitdotc(99);
		exit;
	}
	else
	{
		//echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["ann"].";".$_POST["perio"]." 2</H1></CENTER>\n";
		if ($_POST["dotc"]!=0 and $_POST["ann"]!=0 and $_POST["perio"]!=0)
		{
			echo "<CENTER><H6>".'OK2'."</H6></CENTER>\n";

			$reqcodep="select codep from dotc where cdotc='".$_POST['dotc']."'";
			$rescodep = $bd->execRequete($reqcodep);
			$codep=$bd->ligTabSuivant($rescodep);
			$_POST['dotc']=$codep[0];
			$_SESSION['dotcr']=$codep[0];

			//	echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["ann"].";".$_POST["perio"]." 3</H1></CENTER>\n";
			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=$bd->ligTabSuivant($rescodan);
			$_POST['ann']=$codannee[0];
			$_SESSION['anc']=$codannee[0];

			//	echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["perio"].";".$lig." 4</H1></CENTER>\n";
			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=$bd->ligTabSuivant($rescodper);
			$_POST['perio']=$codperiode[0];
			$_SESSION['perc']=$codperiode[0];

			echo "<CENTER><H1>".$_POST["dotc"].";".$lig.";".$mois[1]." 5</H1></CENTER>\n";
			formcunitdotc($codep[0],$codannee[0],$codperiode[0]);
		}
		else
		{
			formcunitdotc(99);
		}
	}
    }
    //page entite
function majent()
    {
	//require ("pconnec.php");
	$_SESSION['anc']="";
	$_SESSION['perc']="";
	$_SESSION['dotcr']="";
	$_SESSION['libdotc']="";
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	//	echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["ann"].";".$_POST["perio"]." 1</H1></CENTER>\n";
	$anc=date('Y')-1;
	if (isset($_POST["dotc"]) and ($_POST["dotc"]==0 or $_POST["ann"]==0 or $_POST["perio"]==0))
	{
		echo "<CENTER><H6>".'Attention, vous devez sélectionner une Dotc, une année et une pèriode.'."</H6></CENTER>\n";

		forment();
		exit;
	}
	else
	{
		//	echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["ann"].";".$_POST["perio"]." 2</H1></CENTER>\n";
		if ($_POST["dotc"]!=0 and $_POST["ann"]!=0 and $_POST["perio"]!=0)
		{
			$reqcodep="select codep from dotc where cdotc='".$_POST['dotc']."'";
			$rescodep = $bd->execRequete($reqcodep);
			$codep=$bd->ligTabSuivant($rescodep);
			$_POST['dotcr']=$codep[0];
			$_SESSION['dotcr']=$codep[0];

			$reqlib="select libdotc from dotc where cdotc='".$_POST['dotc']."'";
			$reslib = $bd->execRequete($reqlib);
			$colib=$bd->ligTabSuivant($reslib);
			$_POST['libdotc']=$colib[0];
			$_SESSION['libdotc']=$colib[0];

			//echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["ann"].";".$_POST["perio"]." 3</H1></CENTER>\n";
			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=$bd->ligTabSuivant($rescodan);
			$_POST['ann']=$codannee[0];
			$_SESSION['anc']=$codannee[0];

			//echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["perio"].";".$lig." 4</H1></CENTER>\n";
			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=$bd->ligTabSuivant($rescodper);
			$_POST['perio']=$codperiode[0];
			$_SESSION['perc']=$codperiode[0];

			//echo "<CENTER><H1>".$_POST["dotc"].";".$lig.";".$mois[1]." 5</H1></CENTER>\n";
			forment($codep[0],$codannee[0],$codperiode[0]);
		}
		else
		{
			forment();
		}
	}
    }
	//onglet operationnel
function majentopera()
    {
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	$anc=date('Y')-1;
	if (isset($_POST["dotc"]) and ($_POST["dotc"]==0 or $_POST["ann"]==0 or $_POST["perio"]==0))
	{
		echo "<CENTER><H6>".'Attention, vous devez sélectionner une Dotc, une année et une pèriode.'."</H6></CENTER>\n";

		formentopera();
		exit;
	}
	else
	{
		if ($_POST["dotc"]!=0 and $_POST["ann"]!=0 and $_POST["perio"]!=0)
		{
			echo "<CENTER><H6>".'OK2'."</H6></CENTER>\n";

			$reqcodep="select codep from dotc where cdotc='".$_POST['dotc']."'";
			$rescodep = $bd->execRequete($reqcodep);
			$codep=$bd->ligTabSuivant($rescodep);
			$_POST['dotc']=$codep[0];
			$_SESSION['dotcr']=$codep[0];

			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=$bd->ligTabSuivant($rescodan);
			$_POST['ann']=$codannee[0];
			$_SESSION['anc']=$codannee[0];

			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=$bd->ligTabSuivant($rescodper);
			$_POST['perio']=$codperiode[0];
			$_SESSION['perc']=$codperiode[0];

			echo "<CENTER><H1>".$_POST["dotc"].";".$lig.";".$mois[1]." 5</H1></CENTER>\n";
			formentopera($codep[0],$codannee[0],$codperiode[0]);
		}
		else
		{
			formentopera();
		}
	}
    }
	//onglet support
function majentsuppo()
    {
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	//	echo "<CENTER><H1>".$_POST["dotc"]."</H1></CENTER>\n";
	$anc=date('Y')-1;
	//	echo "<CENTER><H6>".'OK1'."</H6></CENTER>\n";
	if (isset($_POST["dotc"]) and ($_POST["dotc"]==0 or $_POST["ann"]==0 or $_POST["perio"]==0))
	{
		echo "<CENTER><H6>".'Attention, vous devez sélectionner une Dotc, une année et une pèriode.'."</H6></CENTER>\n";

		formentsuppo();
		exit;
	}
	else
	{
		//echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["ann"].";".$_POST["perio"]." 2</H1></CENTER>\n";
		if ($_POST["dotc"]!=0 and $_POST["ann"]!=0 and $_POST["perio"]!=0)
		{
			echo "<CENTER><H6>".'OK2'."</H6></CENTER>\n";

			$reqcodep="select codep from dotc where cdotc='".$_POST['dotc']."'";
			$rescodep = $bd->execRequete($reqcodep);
			$codep=$bd->ligTabSuivant($rescodep);
			$_POST['dotc']=$codep[0];
			$_SESSION['dotcr']=$codep[0];

			//	echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["ann"].";".$_POST["perio"]." 3</H1></CENTER>\n";
			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=$bd->ligTabSuivant($rescodan);
			$_POST['ann']=$codannee[0];
			$_SESSION['anc']=$codannee[0];

			//	echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["perio"].";".$lig." 4</H1></CENTER>\n";
			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=$bd->ligTabSuivant($rescodper);
			$_POST['perio']=$codperiode[0];
			$_SESSION['perc']=$codperiode[0];

			echo "<CENTER><H1>".$_POST["dotc"].";".$lig.";".$mois[1]." 5</H1></CENTER>\n";
			formentsuppo($codep[0],$codannee[0],$codperiode[0]);
		}
		else
		{
			formentsuppo();
		}
	}
    }
    //page retraitement par dotc
function majretrdotc()
    {
	//require ("pconnec.php");
	$_SESSION['anc']="";
	$_SESSION['perc']="";
	$_SESSION['dotcr']="";
	$_SESSION['libdotc']="";
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
		//echo "<CENTER><H1>".$_POST["dotc"].",".$_POST["ann"].",".$_POST["perio"]." 1</H1></CENTER>\n";
	$anc=date('Y')-1;
	if (isset($_POST["dotc"]) and ($_POST["dotc"]==0 or $_POST["ann"]==0 or $_POST["perio"]==0))
	{
		echo "<CENTER><H6>".'Attention, vous devez sélectionner une Dotc, une année et une pèriode.'."</H6></CENTER>\n";

		formretrdotc();
		exit;
	}
	else
	{
				//echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["ann"].";".$_POST["perio"]." 2</H1></CENTER>\n";
		if ($_POST["dotc"]!=0 and $_POST["ann"]!=0 and $_POST["perio"]!=0)
		{
			$reqcodep="select codep from dotc where cdotc='".$_POST['dotc']."'";
			$rescodep = $bd->execRequete($reqcodep);
			$codep=$bd->ligTabSuivant($rescodep);
			$_POST['dotcr']=$codep[0];
			$_SESSION['dotcr']=$codep[0];

			$reqlib="select libdotc from dotc where cdotc='".$_POST['dotc']."'";
			$reslib = $bd->execRequete($reqlib);
			$colib=$bd->ligTabSuivant($reslib);
			$_POST['libdotc']=$colib[0];
			$_SESSION['libdotc']=$colib[0];

			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=$bd->ligTabSuivant($rescodan);
			$_POST['ann']=$codannee[0];
			$_SESSION['anc']=$codannee[0];

			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=$bd->ligTabSuivant($rescodper);
			$_POST['perio']=$codperiode[0];
			$_SESSION['perc']=$codperiode[0];

			//echo "<CENTER><H1>".$_POST["dotc"].";".$_POST["ann"].";".$_POST["perio"]." 2</H1></CENTER>\n";
			
			formretrdotc($codep[0],$codannee[0],$codperiode[0]);
		}
		else
		{
			formretrdotc();
		}
	}
    }
//---------------------------------------------------------------------Vision ENTITE------------------------------------------------------------------------------
function majcuent()
    {
	//require ("pconnec.php");
	$_SESSION['anc']="";
	$_SESSION['perc']="";
	$_SESSION['ent']="";
	$_SESSION['libent']="";
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	//$anc=date('Y')-1;
	if (isset($_POST["entite"]) and ($_POST["entite"]==0 or $_POST["ann"]==0 or $_POST["perio"]==0))
	{
		echo "<CENTER><H6>".'Attention, vous devez sélectionner un établissement, une année et une pèriode.'."</H6></CENTER>\n";
		formcuent();
		exit;
	}
	else
	{
		if ($_POST["entite"]!=0 and $_POST["ann"]!=0 and $_POST["perio"]!=0)
		{

			$_SESSION['ent']=$_POST['entite'];
			$codentite=$_SESSION['ent'];

			$reqlib="select libentite from entite where cregate='".$_POST["entite"]."'";
			$reslib = $bd->execRequete($reqlib);
			$codlib=$bd->ligTabSuivant($reslib[0]);
			$_SESSION['libent']=$codlib[0];

			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=$bd->ligTabSuivant($rescodan[0]);
			$_SESSION['anc']=$codannee[0];

			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=$bd->ligTabSuivant($rescodper[0]);
			$_SESSION['perc']=$codperiode[0];


			formcuent($codentite[0],$codannee[0],$codperiode[0]);
		}
		else
		{
			formcuent();
		}
	}
    }
function majchargent()
    {
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	//$anc=date('Y')-1;
	if (isset($_POST["entite"]) and ($_POST["entite"]==0 or $_POST["ann"]==0 or $_POST["perio"]==0))
	{
		echo "<CENTER><H6>".'Attention, vous devez s�lectionner un �tablissement, une ann�e et une p�riode.'."</H6></CENTER>\n";
		formchargent();
		exit;
	}
	else
	{
		if ($_POST["entite"]!=0 and $_POST["ann"]!=0 and $_POST["perio"]!=0)
		{
			$reqentite="select cregate from entite where libentite='".$_POST["entite"]."'";
			$resentite = $bd->execRequete($reqentite);
			$codentite=$bd->ligTabSuivant($resentite);
			$_POST['entite']=$codentite[0];
			$_SESSION['ent']=$codentite[0];

			$reqlib="select libentite from entite where cregate='".$_POST["entite"]."'";
			$reslib = $bd->execRequete($reqlib);
			$codlib=$bd->ligTabSuivant($reslib);
			$_POST['entite']=$codlib[0];
			$_SESSION['libent']=$codlib[0];

			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=$bd->ligTabSuivant($rescodan);
			$_POST['ann']=$codannee[0];

			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=$bd->ligTabSuivant($rescodper);
			$_POST['perio']=$codperiode[0];

			formchargent($codentite[0]);
		}
		else
		{
			formchargent();
		}
	}
    }
function majtrafent()
    {
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	//$anc=date('Y')-1;
	if (isset($_POST["entite"]) and ($_POST["entite"]==0 or $_POST["ann"]==0 or $_POST["perio"]==0))
	{
		echo "<CENTER><H6>".'Attention, vous devez s�lectionner un �tablissement, une ann�e et une p�riode.'."</H6></CENTER>\n";
		formtraficent();
		exit;
	}
	else
	{
		if ($_POST["entite"]!=0 and $_POST["ann"]!=0 and $_POST["perio"]!=0)
		{
			$codent=$_POST["entite"];
			$reqentite="select libentite from entite where cregate='".$codent."'";
			$resentite = $bd->execRequete($reqentite);
			$codentite=$bd->ligTabSuivant($resentite);
			$_POST['entite']=$codentite[0];

			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=$bd->ligTabSuivant($rescodan);
			$_POST['ann']=$codannee[0];

			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=$bd->ligTabSuivant($rescodper);
			$_POST['perio']=$codperiode[0];

			formtraficent($codentite[0],$codannee[0],$codperiode[0]);
		}
		else
		{
			formtraficent();
		}
	}
    }
function majcunitent()
    {
	//require ("pconnec.php");
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	//$anc=date('Y')-1;
	if (isset($_POST["entite"]) and ($_POST["entite"]==0 or $_POST["ann"]==0 or $_POST["perio"]==0))
	{
		echo "<CENTER><H6>".'Attention, vous devez s�lectionner un �tablissement, une ann�e et une p�riode.'."</H6></CENTER>\n";
		formcunitent();
		exit;
	}
	else
	{
		if ($_POST["entite"]!=0 and $_POST["ann"]!=0 and $_POST["perio"]!=0)
		{
			$codent=$_POST["entite"];
			$reqentite="select libentite from entite where cregate='".$codent."'";
			$resentite = $bd->execRequete($reqentite);
			$codentite=$bd->ligTabSuivant($resentite);
			$_POST['entite']=$codentite[0];

			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=$bd->ligTabSuivant($rescodan);
			$_POST['ann']=$codannee[0];

			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=$bd->ligTabSuivant($rescodper);
			$_POST['perio']=$codperiode[0];

			formcunitent($codentite[0],$codannee[0],$codperiode[0]);
		}
		else
		{
			formcunitent();
		}
	}
    }
function majretrent()
    {
	//require ("pconnec.php");
	$_SESSION['anc']="";
	$_SESSION['perc']="";
	$_SESSION['ent']="";
	$_SESSION['libent']="";
	$bd = new monsqli(SERVEUR, NOM, PASSE, BASE);
	//$anc=date('Y')-1;
	if (isset($_POST["entite"]) and ($_POST["entite"]==0 or $_POST["ann"]==0 or $_POST["perio"]==0))
	{
		echo "<CENTER><H6>".'Attention, vous devez sélectionner un établissement, une année et une pèriode.'."</H6></CENTER>\n";
		formretrent();
		exit;
	}
	else
	{
		if ($_POST["entite"]!=0 and $_POST["ann"]!=0 and $_POST["perio"]!=0)
		{

			$_SESSION['ent']=$_POST['entite'];
			$codentite=$_SESSION['ent'];

			$reqlib="select libentite from entite where cregate='".$_POST["entite"]."'";
			$reslib = $bd->execRequete($reqlib);
			$codlib=$bd->ligTabSuivant($reslib);
			$_SESSION['libent']=$codlib[0];

			$codan=$_POST["ann"];
			$reqcodan="select liban from annee where can='".$codan."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=$bd->ligTabSuivant($rescodan);
			$_SESSION['anc']=$codannee[0];

			$codper=$_POST["perio"];
			$reqcodper="select libperi from periode where cperi='".$codper."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=$bd->ligTabSuivant($rescodper);
			$_SESSION['perc']=$codperiode[0];


			formretrent($codentite[0],$codannee[0],$codperiode[0]);
		}
		else
		{
			formretrent();
		}
	}
    }
?>