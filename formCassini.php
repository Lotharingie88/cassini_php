<?php
//----------------------------------------------------------------VISION NATIONALE------------------------------------------------------------

//Pages cout unitaire nationaux
function formcunat($ancu='2009',$percu="rien")
{
	//require("pconnect.php");
	sessval();
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new Formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Charges"=>"charge.php",
					  "Trafics"=>"trafic.php",
				      "Coûts unitaires"=>"cunit.php");
		echo "<CENTER><H1>".'Coûts Nationaux'."</H1></CENTER>\n";

	//champliste deroulant pour annee et periode
	$requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
	$resultat2 = $bd-> execRequete ($requete2);
	$nomb_ligne2=$bd->nbLigne($resultat2);
	$listannee[0]="";
	for ($j=0;$j<$nomb_ligne2;$j++)
	  {
		$ligne2=$bd->objetSuivant($resultat2);
		$listannee[$ligne2->can]=$ligne2->liban;
	  }
	$requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
	$resultat = $bd->execRequete ($requete);
	$nomb_ligne=$bd->nbLigne($resultat);
	$listPerio[0]="";
	for ($j=0;$j<$nomb_ligne;$j++)
	  {
		$ligne=$bd->objetSuivant($resultat);
		$listPerio[$ligne->cperi]=$ligne->libperi;
	  }
	$Form->debuttable();
	if($ancu<>'2009')
	{
		$reqcodan="select can from annee where liban='".$ancu."'";
		$rescodan = $bd->execRequete($reqcodan);
		$codannee=$bd->ligTabSuivant($rescodan);
		$Form->champliste ("Année :", "ann", $codannee[0],1, $listannee);
		$ancour=$codannee[0];
		//$GLOBALS["ancour"];
	}
	else
	{
		$Form->champliste ("Année :", "ann", "",1, $listannee);
	}
	if($percu<>"rien")
	{
		$reqcodper="select cperi from periode where libperi='".$percu."'";
		$rescodper = $bd->execRequete($reqcodper);
		$codperiode=$bd->ligTabSuivant($rescodper);
		$Form->champliste ("Pèriode :", "perio", $codperiode[0], 1, $listPerio);
		$percour=$codperiode[0];
		//$GLOBALS['percour'];
	}
	else
	{
		$Form->champliste ("Pèriode :", "perio", "", 1, $listPerio);
	}
	$Form->champvalider ("Valider", "valider");
	$Form->fintable();
	////$Form->fin();
	echo $Form->formulaireHTML();
	echo "<H6>".'(En Euros)'."</H6>\n";
	//requete sql alimentant tableau
    if (isset($ancour) and isset($percour))
    {
	$requete3 = "select distinct cordaffich,ordscrib,libprocess,sum(charcaa)
                 	from domaine,processus,sousprocessus,caa,charcaa,periode,mois
		                 where cordaffich<>'0' and cdom=cedom and cprocess=ceprocess
                 			and cssprocess=cessprocess and ccaa=cecaa and anchcaa='".$ancour."'
                 			and cperi='".$percour."' and cperi=ceperi and codmoi=moichcaa
                 				group by cordaffich
                 					order by ordscrib,cordaffich";
  	$resultat3 = $bd->execRequete ($requete3);
  	$nomb_ligne3=$bd->nbLigne($resultat3);
     }
  	$Form = new formulaire ("POST", "","suivconnex");
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Charges brutes"));
	$Form->ajoutTexte (tblentete("Retraitement"));
	$Form->ajoutTexte (tblentete("Charges nettes"));
	$Form->ajoutTexte (tblfinligne());
    if (isset ($nomb_ligne3  ))
  	  {
      for ($j=0;$j<$nomb_ligne3;$j++)
      	{
            $ligne3=$bd->ligTabSuivant($resultat3);
  			tbldebutligne("A0");
      		tblcellule($ligne3[2]);
      		tblcellule(number_format($ligne3[3],0 , ' ' ,  ' ' ));
			tblcellule(" NON FONCTIONNEL");
      		tblcellule(number_format($ligne3[3],0 , ' ' ,  ' ' ));//PLUS LES RETRAITEMENTS
      		tblfinligne();
            tbldebutligne(MPAP);
            tblcellule(" MPAP");
            tblfinligne();
      	}
        }
    $Form->fintable();
   
	$Form->debuttable(tblfinligne());
	$Form->fintable();
    //}
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	
	echo $Form->formulaireHTML();
	//$Form->fin();
    }
function formcharge($ancu=2009,$percu="rien")
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Charges"=>"charge.php",
					  "Trafics"=>"trafic.php",
				      "Coûts unitaires"=>"cunit.php");

	echo "<CENTER><H1>".'Charges Nationales'."</H1></CENTER>\n";
	echo "<CENTER><H3>".'Année traitée : '.$_SESSION['anc']."</H3></CENTER>\n";
	echo "<CENTER><H3>".'Pèriode traitée : '.$_SESSION['perc']."</H3></CENTER>\n";
	$ancour=$_SESSION['anc'];
	$percour=$_SESSION['perc'];

	//requete sql alimentant tableau
	$requete3 = "select distinct cordaffich,ordscrib,libprocess,sum(charcaa)
                 	from domaine,processus,sousprocessus,caa,charcaa,periode,mois
		                 where cordaffich<>'0' and cdom=cedom and cprocess=ceprocess
                 			and cssprocess=cessprocess and ccaa=cecaa
                 			and libperi='".$percour."' and cperi=ceperi
                 			and anchcaa='".$ancour."' and moichcaa=codmoi
                 				group by cordaffich
                 					order by ordscrib,cordaffich";
  	$resultat3 = $bd->execRequete ($requete3);
  	$nomb_ligne3=$bd->nbLigne($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");

	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete("En Euros"));
	$Form->ajoutTexte (tblentete("Charges brutes"));
	$Form->ajoutTexte (tblentete("Retraitement"));
	$Form->ajoutTexte (tblentete("Charges nettes"));
	$Form->ajoutTexte (tblfinligne());
  	for ($j=0;$j<$nomb_ligne3;$j++)
      	{
            $ligne3=$bd->ligTabSuivant($resultat3);
  			tbldebutligne("A0");
  			tblcellule($ligne3[2]);
  			tblcellule(number_format($ligne3[3],0 , ' ' ,  ' ' ));
			tblcellule("NON FONCTIONNEL");
      		tblcellule(number_format($ligne3[3],0 , ' ' ,  ' ' ));//PLUS LES RETRAITEMENTS
      		tblfinligne();
            tbldebutligne(MPAP);
            tblcellule(" MPAP");
            tblfinligne();
      	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
function formtrafic($ancu=2009,$percu="rien")
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","suivconnex");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array("Charges"=>"charge.php",
					"Trafics"=>"trafic.php",
					"Coûts unitaires"=>"cunit.php"
					);

	$requete3 = "select distinct cordaffich,ordscrib,libprocess
                            from domaine,processus
                                 where cordaffich<>'0'
                                       and cdom=cedom
                                           group by cordaffich
                                                 order by ordscrib,cordaffich";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->nbLigne($resultat3);
	$ancour=$_SESSION['anc'];
	$percour=$_SESSION['perc'];
	echo "<CENTER><H1>".'Trafics Nationaux'."</H1></CENTER>\n";
	echo "<CENTER><H3>".'Année traitée : '.$ancour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'Pèriode traitée : '.$percour."</H3></CENTER>\n";

	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete("En nombre d'objets"));
	$Form->ajoutTexte (tblentete("Trafic courrier"));
	$Form->ajoutTexte (tblentete("Trafic IP")); //pna
	$Form->ajoutTexte (tblentete("Trafic colis"));
	$Form->ajoutTexte (tblentete("Trafic total"));
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");


		switch ($ligne3[2])
		{
			case "DISTRIBUTION":
				tblcellule($ligne3[2]." SYCI");
				if ($percour="annuel")
				{
					$requete4 = "select sum(trafsyci)from syci where ansyc='".$ancour."'";
					$resultat4 = $bd->execRequete ($requete4);
					$ligne4=$bd->ligTabSuivant($resultat4);
				}
				else
				{
					$requete4 = "select sum(trafsyci)from syci,periode where ansyc='".$ancour."' and libperi='".$percour."' and trimsyc=cperi";
					$resultat4 = $bd->execRequete ($requete4);
					$ligne4=$bd->ligTabSuivant($resultat4);
				}

				$requete5 = "select sum(trafcolis)from colis,periode,mois where ancol='".$ancour."'and libperi='".$percour."' and cperi=ceperi and codmoi=moicol";
				$resultat5 = $bd->execRequete ($requete5);
				$ligne5=$bd->ligTabSuivant($resultat5);
				$requete6 = "select sum(trafpna)from pna,periode,mois where anpna='".$ancour."'and libperi='".$percour."' and cperi=ceperi and codmoi=moipna";
				$resultat6 = $bd->execRequete ($requete6);
				$ligne6=$bd->ligTabSuivant($resultat6);
				tblcellule(number_format($ligne4[0],0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne6[0],0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne5[0],0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne4[0]+ $ligne6[0]+ $ligne5[0],0 , ' ' ,  ' ' ));
				tblfinligne();
				tbldebutligne(MPAP);
				tblcellule("MPAP");
				tblfinligne();
				tbldebutligne("A0");
				tblcellule($ligne3[2]." PILDI");
				$requete4 = "select sum(trafdistribm),sum(trafdistribc)from pildi,periode,mois where anpi='".$ancour."' and libperi='".$percour."' and cperi=ceperi and codmoi=moipi";
				$resultat4 = $bd->execRequete ($requete4);
				$ligne4=$bd->ligTabSuivant($resultat4);
				tblcellule(number_format($ligne4[0] + $ligne4[1],0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne6[0],0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne5[0],0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne4[0] + $ligne4[1]+ $ligne6[0]+ $ligne5[0],0 , ' ' ,  ' ' ));
				break;
			case "TRAITEMENT":
				tblcellule($ligne3[2]);
				$requete4 = "select sum(traftraitd),sum(traftraita)from syspeo,periode,mois where anpeo='".$ancour."'and libperi='".$percour."' and cperi=ceperi and codmoi=moipeo";
				$resultat4 = $bd->execRequete ($requete4);
				$ligne4=$bd->ligTabSuivant($resultat4);
				tblcellule(number_format($ligne4[0] + $ligne4[1],0 , ' ' ,  ' ' ));
				tblcellule("");
				tblcellule("");
				tblcellule(number_format($ligne4[0] + $ligne4[1],0 , ' ' ,  ' ' ));
				break;
			case "CONCENTRATION":
				tblcellule($ligne3[2]);
				$requete4 = "select sum(trafconc)from syspeo,periode,mois where anpeo='".$ancour."'and libperi='".$percour."' and cperi=ceperi and codmoi=moipeo";
				$resultat4 = $bd->execRequete ($requete4);
				$ligne4=$bd->ligTabSuivant($resultat4);
				$requete5 = "select sum(trafconc)from pildi,mois,periode where anpi='".$ancour."'and libperi='".$percour."' and cperi=ceperi and codmoi=moipi";
				$resultat5 = $bd->execRequete ($requete5);
				$ligne5=$bd->ligTabSuivant($resultat5);
				tblcellule(number_format($ligne5[0] + $ligne4[0],0 , ' ' ,  ' ' ));
				tblcellule("");
				tblcellule("");
				tblcellule(number_format($ligne5[0] + $ligne4[0],0 , ' ' ,  ' ' ));
				break;
			case "TI":
				tblcellule($ligne3[2]);
				$requete4 = "select sum(trafdistribm),sum(trafdistribc)from pildi,mois,periode where anpi='".$ancour."'and libperi='".$percour."' and cperi=ceperi and codmoi=moipi";
				$resultat4 = $bd->execRequete ($requete4);
				$ligne4=$bd->ligTabSuivant($resultat4);
				$requete5 = "select sum(trafcolis)from colis,mois,periode where ancol='".$ancour."'and libperi='".$percour."' and cperi=ceperi and codmoi=moicol";
				$resultat5 = $bd->execRequete ($requete5);
				$ligne5=$bd->ligTabSuivant($resultat5);
				tblcellule(number_format($ligne4[0] + $ligne4[1],0 , ' ' ,  ' ' ));
				tblcellule("");
				tblcellule(number_format($ligne5[0],0 , ' ' ,  ' ' ));    //test separateur millier
				tblcellule(number_format($ligne4[0] + $ligne4[1]+ $ligne5[0],0 , ' ' ,  ' ' ));
				break;
			default:	tblcellule($ligne3[2]);
				break;
		}
		tblfinligne();
		tbldebutligne(MPAP);
		tblcellule("MPAP");
		tblfinligne();

	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
function formcunit($ancu=2009,$percu="rien")
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","suivconnex");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array("Charges"=>"charge.php",
					 "Trafics"=>"trafic.php",
					 "Coûts unitaires"=>"cunit.php");
    //recuperation des processus
	$requete3 = "select distinct cordaffich,ordscrib,libprocess
                            from domaine,processus
                                 where cordaffich<>'0'and cdom=cedom
                                           group by cordaffich
                                                 order by ordscrib,cordaffich";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->nbLigne($resultat3);

	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Coûts unitaires Nationaux'."</H1></CENTER>\n";
	echo "<CENTER><H3>".'Année traitée : '.$_SESSION['anc']."</H3></CENTER>\n";
	echo "<CENTER><H3>".'Pèriode traitée : '.$_SESSION['perc']."</H3></CENTER>\n";
	$ancour=$_SESSION['anc'];
	$percour=$_SESSION['perc'];

	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
    tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	//Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete("En Euros/Objet"));
	$Form->ajoutTexte (tblentete("Coût unitaire direct"));
	$Form->ajoutTexte (tblentete("Coût unitaire indirect"));
	$Form->ajoutTexte (tblfinligne());
	//boucle renseignant les cellules
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");
		switch ($ligne3[2])
		{
			case "DISTRIBUTION":
                tblcellule($ligne3[2]." SYCI");

             	//charges pour le processus distribution
				$reqchar="select distinct sum(charcaa)
                 	from processus,sousprocessus,caa,charcaa,periode,mois
		                 where libprocess='DISTRIBUTION' and cprocess=ceprocess
                 			and cssprocess=cessprocess and ccaa=cecaa
                 			and libperi='".$percour."' and cperi=ceperi
                 			and anchcaa='".$ancour."' and moichcaa=codmoi
                 				group by cprocess";
				$resuchar = $bd->execRequete ($reqchar);
				$ligchar=$bd->ligTabSuivant($resuchar);

				//distribution syci
				if ($percour="annuel")
				{
					$requete4 = "select sum(trafsyci)from syci where ansyc='".$ancour."'";
					$resultat4 = $bd->execRequete ($requete4);
					$ligne4=$bd->ligTabSuivant($resultat4);
				}
				else
				{
					$requete4 = "select sum(trafsyci)from syci,periode where ansyc='".$ancour."' and libperi='".$percour."' and trimsyc=cperi";
					$resultat4 = $bd->execRequete ($requete4);
					$ligne4=$bd->ligTabSuivant($resultat4);
				}

				//distribution pildi
				$requete7 = "select sum(trafdistribm),sum(trafdistribc)from pildi,periode,mois where anpi='".$ancour."' and libperi='".$percour."' and cperi=ceperi and codmoi=moipi ";
				$resultat7 = $bd->execRequete ($requete7);
				$ligne7=$bd->ligTabSuivant($resultat7);


				//distribution colis
				$requete5 = "select sum(trafcolis)from colis,periode,mois where ancol='".$ancour."'and libperi='".$percour."' and cperi=ceperi and codmoi=moicol";
				$resultat5 = $bd->execRequete ($requete5);
				$ligne5=$bd->ligTabSuivant($resultat5);

				//distribution pna
				$requete6 = "select sum(trafpna)from pna,periode,mois where anpna='".$ancour."'and libperi='".$percour."' and cperi=ceperi and codmoi=moipna";
				$resultat6 = $bd->execRequete ($requete6);
				$ligne6=$bd->ligTabSuivant($resultat6);
				$trafSyci=$ligne4[0]+$ligne5[0]+$ligne6[0];
				if($trafSyci<>0)
				{
					tblcellule(number_format($ligchar[0]/($trafSyci),6 , ',' ,  ' ' ));
				}
				else
				{
					tblcellule("Absence de trafic");
				}
				tblcellule("NON FONCTIONNEL");//cout unitaire indirect
				tblfinligne();
				tbldebutligne(MPAP);
				tblcellule("MPAP");
				tblfinligne();
				tbldebutligne(A0);
				tblcellule($ligne3[2]." PILDI"); //processus
				$trafPildi=$ligne7[0]+$ligne7[1]+$ligne5[0]+$ligne4[0];
				if($trafPildi<>0)
				{
					tblcellule(number_format($ligchar[0]/($trafPildi),6 , ',' ,  ' ' ));
				}
				else
				{
					tblcellule("Absence de trafic");
				}
				tblcellule("NON FONCTIONNEL");//cout unitaire indirect
				break;
			case "TRAITEMENT":
			tblcellule($ligne3[2]);
				$reqchar="select distinct sum(charcaa)
                 	from processus,sousprocessus,caa,charcaa,periode,mois
		                 where libprocess='TRAITEMENT' and cprocess=ceprocess
                 			and cssprocess=cessprocess and ccaa=cecaa
                 			and libperi='".$percour."' and cperi=ceperi
                 			and anchcaa='".$ancour."' and moichcaa=codmoi
                 				group by cprocess";
                                $reschar = $bd->execRequete ($reqchar);
				$ligchar=$bd->ligTabSuivant($reschar);
				$requete8 = "select sum(traftraitd),sum(traftraita)from syspeo,periode,mois where anpeo='".$ancour."'and libperi='".$percour."' and cperi=ceperi and codmoi=moipeo";
				$resultat8 = $bd->execRequete ($requete8);
				$ligne8=$bd->ligTabSuivant($resultat8);
				$traftrait=$ligne8[0] + $ligne8[1];
				if($traftrait<>0)
				{
					tblcellule(number_format($ligchar[0]/($ligne8[0] + $ligne8[1]),6 , ',' ,  ' ' ));
				}
				else
				{
					tblcellule("Absence de trafic");
				}
				tblcellule("NON FONCTIONNEL");//cout unitaire indirect
				break;
			case "CONCENTRATION":
			tblcellule($ligne3[2]);
				$reqchar="select distinct sum(charcaa)
                 	from processus,sousprocessus,caa,charcaa,periode,mois
		                 where libprocess='CONCENTRATION' and cprocess=ceprocess
                 			and cssprocess=cessprocess and ccaa=cecaa
                 			and libperi='".$percour."' and cperi=ceperi
                 			and anchcaa='".$ancour."' and moichcaa=codmoi
                 				group by cprocess";
                $reschar = $bd->execRequete ($reqchar);
				$ligchar=$bd->ligTabSuivant($reschar);
				$requete9 = "select sum(trafconc)from syspeo,periode,mois where anpeo='".$ancour."'and libperi='".$percour."' and cperi=ceperi and codmoi=moipeo";
				$resultat9 = $bd->execRequete ($requete9);
				$ligne9=$bd->ligTabSuivant($resultat9);
				$requete9b = "select sum(trafconc)from pildi,mois,periode where anpi='".$ancour."'and libperi='".$percour."' and cperi=ceperi and codmoi=moipi";
				$resultat9b = $bd->execRequete ($requete9b);
				$ligne9b=$bd->ligTabSuivant($resultat9b);
				$trafconc=$ligne9[0] + $ligne9b[0];
				if($trafconc<>0)
				{
					tblcellule(number_format($ligchar[0]/($ligne9[0] + $ligne9b[0]),6 , ',' , ' ' ));
				}
				else
				{
					tblcellule("Absence de trafic");
				}
				tblcellule("NON FONCTIONNEL");//cout unitaire indirect
			break;
			case "TI":
			tblcellule($ligne3[2]);
				$reqchar="select distinct sum(charcaa)
                 	from processus,sousprocessus,caa,charcaa,periode,mois
		                 where libprocess='TI' and cprocess=ceprocess
                 			and cssprocess=cessprocess and ccaa=cecaa
                 			and libperi='".$percour."' and cperi=ceperi
                 			and anchcaa='".$ancour."' and moichcaa=codmoi
                 				group by cprocess ";
                $reschar = $bd->execRequete ($reqchar);
				$ligchar=$bd->ligTabSuivant($reschar);

				$requete10 = "select sum(trafdistribm),sum(trafdistribc)from pildi,mois,periode where anpi='".$ancour."'and libperi='".$percour."' and cperi=ceperi and codmoi=moipi";
				$resultat10 = $bd->execRequete ($requete10);
				$ligne10=$bd->ligTabSuivant($resultat10);

				$requete10b = "select sum(trafcolis)from colis,mois,periode where ancol='".$ancour."'and libperi='".$percour."' and cperi=ceperi and codmoi=moicol";
				$resultat10b = $bd->execRequete ($requete10b);
				$ligne10b=$bd->ligTabSuivant($resultat10b);

				$trafTI=$ligne10[0] + $ligne10b[0];
				if($trafTI<>0)
				{
					tblcellule(number_format($ligchar[0]/($ligne10[0] + $ligne10b[0]),6 , ',' ,  ' ' ));
				}
				else
				{
					tblcellule("Absence de trafic");
				}
				tblcellule("NON FONCTIONNEL");//cout unitaire indirect
				break;
			default:
			tblcellule($ligne3[2]);
		}
		tblfinligne();
		tbldebutligne(MPAP);
		tblcellule("MPAP");
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();
}
//Page de retraitement nationaux
function formretrnat($ancu=2009,$percu="rien")
    {
    
    //require_once ("util.php");
    //require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
    
	// Tableau en mode vertical, pour les champs simples
	//$menuclien=array( "Charges"=>"charge.php", "Trafics"=>"trafic.php", "Co�ts unitaires"=>"cunit.php");
	echo "<CENTER><H1>".'Retraitements Nationaux'."</H1></CENTER>\n";
	$ancour=$_SESSION['anc'];
	$percour=$_SESSION['perc'];

	//champliste deroulant pour annee et periode
	//$requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
    $requete2 = "SELECT distinct can,liban FROM annee ";
	$resultat2 = $bd->execRequete ($requete2);
	$nomb_ligne2 = $bd->nbLigne($resultat2) ;
	$listannee[0]="";
	for ($j=0;$j<$nomb_ligne2;$j++)
	  {
		$ligne2=$bd->objetSuivant($resultat2);
		$listannee[$ligne2->can]=$ligne2->liban;
	  }
	$requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
	$resultat = $bd->execRequete ($requete);
	$nomb_ligne=$bd->nbLigne($resultat);
	$listPerio[0]="";
	for ($j=0;$j<$nomb_ligne;$j++)
	  {
		$ligne=$bd->objetSuivant($resultat);
		$listPerio[$ligne->cperi]=$ligne->libperi;
	  }
	$Form->debuttable();
	if($ancu<>2009)
	{
		$reqcodan="select can from annee where liban='".$ancu."'";
		$rescodan = $bd->execRequete($reqcodan);
		$codannee=$bd->ligTabSuivant($rescodan);
		$Form->champliste ("Ann�e :", "ann", $codannee[0], 1, $listannee);
		$ancour=$codannee[0];
		//$GLOBALS[ancour];
	}
	else
	{
		$Form->champliste ("Année :", "ann", "", 1, $listannee);
	}
	if($percu<>"rien")
	{
		$reqcodper="select cperi from periode where libperi='".$percu."'";
		$rescodper = $bd->execRequete($reqcodper);
		$codperiode=$bd->ligTabSuivant($rescodper);
		$Form->champliste ("Pèriode :", "perio", $codperiode[0], 1, $listPerio);
		$percour=$codperiode[0];
		//$GLOBALS[percour];
	}
	else
	{
		$Form->champliste ("Pèriode :", "perio", "", 1, $listPerio);
	}
	$Form->champvalider ("Valider", "valider");
	//$Form->fin();

	//requete sql alimentant tableau
	$requete3 = "select distinct cordaffich,ordscrib,libprocess
                            from domaine,processus
                                 where cordaffich<>'0'
                                       and cdom=cedom
                                           group by cordaffich
                                                 order by ordscrib,cordaffich";
  	$resultat3 = $bd->execRequete ($requete3);
  	$nomb_ligne3=$bd->nbLigne($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");

	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    //foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	//tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("SP88 - IMMOBILIER"));
	$Form->ajoutTexte (tblentete("SP89 - VEHICULE"));
	$Form->ajoutTexte (tblentete("SP90 - CAA SOCIAL"));
	$Form->ajoutTexte (tblentete("SP91 - CAA RH"));
	$Form->ajoutTexte (tblentete("TOTAL TRANSVERSE"));
	$Form->ajoutTexte (tblfinligne());
    //echo "<CENTER><H1>".$ancour.$percour.'Retraitements Nationaux'."</H1></CENTER>\n";
	$reqpr02 = "select  sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode
								where cecaa=ccaa and cessprocess=cssprocess
									and anchcaa='".$ancour."' and cperi='".$percour."'
									and cperi=ceperi and codmoi=moichcaa
									and ceprocess=cprocess and codprocess='PR02'";
	$respr02 = $bd->execRequete ($reqpr02);
	$PR02=$bd->ligTabSuivant($respr02);

	$reqpr04 = "select  sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode
								where cecaa=ccaa and cessprocess=cssprocess
									and anchcaa='".$ancour."' and cperi='".$percour."'
									and cperi=ceperi and codmoi=moichcaa
									and ceprocess=cprocess and codprocess='PR04'";
	$respr04 = $bd->execRequete ($reqpr04);
	$PR04=$bd->ligTabSuivant($respr04);

	$reqdist = "select  sum(charcaa)
								from charcaa,caa,mois,periode
									where cecaa=ccaa and codcaa='775'
										and anchcaa='".$ancour."' and cperi='".$percour."'
										and cperi=ceperi and codmoi=moichcaa";
	$resdist = $bd->execRequete ($reqdist);
	$ligne=$bd->ligTabSuivant($resdist);

	$reqdist2 = "select  sum(charcaa)
								from charcaa,caa,mois,periode
									where cecaa=ccaa and codcaa='750'
										and anchcaa='".$ancour."' and cperi='".$percour."'
										and cperi=ceperi and codmoi=moichcaa";
	$resdist2 = $bd->execRequete ($reqdist2);
	$ligne2=$bd->ligTabSuivant($resdist2);

	$reqdist3 = "select  sum(charcaa)
								from charcaa,sousprocessus,caa,mois,periode
									where cecaa=ccaa and cessprocess=cssprocess
										and codssprocess='SP90'
											and anchcaa='".$ancour."' and cperi='".$percour."'
											and cperi=ceperi and codmoi=moichcaa";
	$resdist3 = $bd->execRequete ($reqdist3);
	$SP90=$bd->ligTabSuivant($resdist3);

	$reqdist4 = "select  sum(charcaa)
								from charcaa,sousprocessus,caa,mois,periode
									where cecaa=ccaa and cessprocess=cssprocess
										and codssprocess='SP91'
											and anchcaa='".$ancour."' and cperi='".$percour."'
											and cperi=ceperi and codmoi=moichcaa";
	$resdist4 = $bd->execRequete ($reqdist4);
	$SP91=$bd->ligTabSuivant($resdist4);

	$Form->debuttable();

	tblcellule("CHARGES A RETRAITER");
	tblcellule(number_format(-$ligne[0],0 , ' ' ,  ' ' ));
	tblcellule(number_format(-$ligne2[0],0 , ' ' ,  ' ' ));
	tblcellule(number_format(-$SP90[0],0 , ' ' ,  ' ' ));
	tblcellule(number_format(-$SP91[0],0 , ' ' ,  ' ' ));
	tblcellule(number_format(-$ligne[0]-$ligne2[0]-$SP90[0]-$SP91[0],0 , ' ' ,  ' ' ));
	$Form->ajoutTexte (tblfinligne());

	$reqcharge="select sum(charcaa)
					 from charcaa,caa,sousprocessus,processus,mois,periode
					 	where libprocess<>'CAA TRANSVERSES'
							and anchcaa='".$ancour."' and cperi='".$percour."'
							and cperi=ceperi and codmoi=moichcaa
					 		and cecaa=ccaa and cessprocess=cssprocess and ceprocess=cprocess";
	$rescharge = $bd->execRequete ($reqcharge);
	$charges=$bd->ligTabSuivant($rescharge);
	//echo "<CENTER><H3>".'charge : '.$charges."</H3></CENTER>\n";
	$totimm=-$ligne[0];
	$totvehi=-$ligne2[0];
	$totsocial=-$SP90[0];
	$totrh=-$SP91[0];

	for ($j=0;$j<$nomb_ligne3;$j++)
	{

		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");
		tblcellule($ligne3[2]);
		switch ($ligne3[2])
		{
			case "DISTRIBUTION":
			
				$immdist=$ligne[0]*($PR04[0]/$charges);
				$totimm=$totimm+$immdist;
				tblcellule(number_format($immdist,3 , ' ' ,  ' '));

				$vehidist=$ligne2[0]*($PR04[0]/($PR02[0]+$PR04[0]));
				$totvehi=$totvehi+$vehidist;
				tblcellule(number_format($vehidist,3 , ' ' ,  ' '));

				$reqdist = "select sum(charcaa) from charcaa,sousprocessus,processus,caa
								where cecaa=ccaa and cessprocess=cssprocess
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resdist = $bd->execRequete ($reqdist);
				$dist=$bd->ligTabSuivant($resdist);

				$socialdist=$SP90[0]*($dist[0]/$charges);
				$totsocial=$totsocial+$socialdist;
				tblcellule(number_format($socialdist,3 , ' ' ,  ' ' ));

				$rhdist=$SP91[0]*($dist[0]/$charges);
				$totrh=$totrh+$rhdist;
				tblcellule(number_format($rhdist,3 , ' ' ,  ' ' ));

				if($immdist<>0 and $vehidist<>0 and $socialdist<>0 and $rhdist<>0)
					{
						tblcellule(number_format($immdist+$vehidist+$socialdist+$rhdist,3 , ' ' ,  ' '));
					}
				else
					{
						tblcellule("Absence de trafic");
					}
				break;
			case "TRAITEMENT":
				$reqtrait = "select sum(charcaa) from charcaa,sousprocessus,processus,caa
								where cecaa=ccaa and cessprocess=cssprocess
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$restrait = $bd->execRequete ($reqtrait);
				$trait=$bd->ligTabSuivant($restrait);
				$immtrait=$ligne[0]*($trait/$charges);
				$totimm=$totimm+$immtrait;
				tblcellule(number_format($immtrait,3 , ' ' ,  ' '));

				tblcellule("");

				$socialtrait=$SP90[0]*($trait[0]/$charges);
				$totsocial=$totsocial+$socialtrait;
				tblcellule(number_format($socialtrait,3 , ' ' ,  ' ' ));

				$rhtrait=$SP91[0]*($trait[0]/$charges);
				$totrh=$totrh+$rhtrait;
				tblcellule(number_format($rhtrait,3 , ' ' ,  ' ' ));

					if($immtrait<>0 and $socialtrait<>0 and $rhtrait<>0)
						{
							tblcellule(number_format($immtrait+$socialtrait+$rhtrait,3 , ' ' ,  ' '));
						}
					else
						{
							tblcellule("Absence de trafic");
						}

				break;
			case "CONCENTRATION":
				$immconc=$ligne[0]*($PR02[0]/$charges);
				$totimm=$totimm+$immconc;
				tblcellule(number_format($immconc,3 , ' ' ,  ' '));

				$vehiconc=$ligne2[0]*($PR02[0]/($PR02[0]+$PR04[0]));
				$totvehi=$totvehi+$vehiconc;
				tblcellule(number_format($vehiconc,3 , ' ' ,  ' '));

				$reqconc = "select sum(charcaa) from charcaa,sousprocessus,processus,caa
								where cecaa=ccaa and cessprocess=cssprocess
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resconc = $bd->execRequete ($reqconc);
				$conc=$bd->ligTabSuivant($resconc);
				$socialconc=$SP90[0]*($conc[0]/$charges);
				$totsocial=$totsocial+$socialconc;
				tblcellule(number_format($socialconc,3 , ' ' ,  ' ' ));

				$rhconc=$SP91[0]*($conc[0]/$charges);
				$totrh=$totrh+$rhconc;
				tblcellule(number_format($rhconc,3 , ' ' ,  ' ' ));

					if($immconc<>0 and $socialconc<>0 and $rhconc<>0 and $vehiconc<>0)
						{
							tblcellule(number_format($immconc+$vehiconc+$socialconc+$rhconc,3 , ' ' ,  ' '));
						}
					else
						{
							tblcellule("Absence de trafic");
						}

				break;
			case "CAA CORPORATE":
				tblcellule("");
				tblcellule("");
				tblcellule("");
				tblcellule("");
				tblcellule("");
				break;
			case "SOUTIEN OPERATIONNEL":
				$reqst = "select sum(charcaa) from charcaa,sousprocessus,processus,caa
								where cecaa=ccaa and cessprocess=cssprocess
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resst = $bd->execRequete ($reqst);
				$st=$bd->ligTabSuivant($resst);

				$immst=$ligne[0]*($st[0]/$charges);
				$totimm=$totimm+$immst;

				tblcellule(number_format($immst,3 , ' ' ,  ' '));
				tblcellule("");

				$socialst=$SP90[0]*($st[0]/$charges);
				$totsocial=$totsocial+$socialst;
				tblcellule(number_format($socialst,3 , ' ' ,  ' ' ));

				$rhst=$SP91[0]*($st[0]/$charges);
				$totrh=$totrh+$rhst;
				tblcellule(number_format($rhst,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immst+$socialst+$rhst,3 , ' ' ,  ' '));
				break;
			case "COMPTA GESTION FINANCE":
				$reqcompta = "select sum(charcaa) from charcaa,sousprocessus,processus,caa
								where cecaa=ccaa and cessprocess=cssprocess
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$rescompta = $bd->execRequete ($reqcompta);
				$compta=$bd->ligTabSuivant($rescompta);

				$immcompta=$ligne[0]*($compta[0]/$charges);
				$totimm=$totimm+$immcompta;

				tblcellule(number_format($immcompta,3 , ' ' ,  ' '));
				tblcellule("");

				$socialcompta=$SP90[0]*($compta[0]/$charges);
				$totsocial=$totsocial+$socialcompta;
				tblcellule(number_format($socialcompta,3 , ' ' ,  ' ' ));

				$rhcompta=$SP91[0]*($compta[0]/$charges);
				$totrh=$totrh+$rhcompta;
				tblcellule(number_format($rhcompta,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immcompta+$socialcompta+$rhcompta,3 , ' ' ,  ' '));
				break;
			case "MARKETING COMMERCIAL":
				$reqmark = "select sum(charcaa) from charcaa,sousprocessus,processus,caa
								where cecaa=ccaa and cessprocess=cssprocess
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resmark = $bd->execRequete ($reqmark);
				$mark=$bd->ligTabSuivant($resmark);

				$immmark=$ligne[0]*($mark[0]/$charges);
				$totimm=$totimm+$immmark;

				tblcellule(number_format($immmark,3 , ' ' ,  ' '));
				tblcellule("");

				$socialmark=$SP90[0]*($mark[0]/$charges);
				$totsocial=$totsocial+$socialmark;
				tblcellule(number_format($socialmark,3 , ' ' ,  ' ' ));

				$rhmark=$SP91[0]*($mark[0]/$charges);
				$totrh=$totrh+$rhmark;
				tblcellule(number_format($rhmark,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immmark+$socialmark+$rhmark,3 , ' ' ,  ' '));
				break;
			case "PILOTAGE":
				$reqpil = "select sum(charcaa) from charcaa,sousprocessus,processus,caa
								where cecaa=ccaa and cessprocess=cssprocess
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$respil = $bd->execRequete ($reqpil);
				$pil=$bd->ligTabSuivant($respil);

				$immpil=$ligne[0]*($pil[0]/$charges);
				$totimm=$totimm+$immpil;

				tblcellule(number_format($immpil,3 , ' ' ,  ' '));
				tblcellule("");

				$socialpil=$SP90[0]*($pil[0]/$charges);
				$totsocial=$totsocial+$socialpil;
				tblcellule(number_format($socialpil,3 , ' ' ,  ' ' ));

				$rhpil=$SP91[0]*($pil[0]/$charges);
				$totrh=$totrh+$rhpil;
				tblcellule(number_format($rhpil,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immpil+$socialpil+$rhpil,3 , ' ' ,  ' '));
				break;
			case "RH":
				$reqrh = "select sum(charcaa) from charcaa,sousprocessus,processus,caa
								where cecaa=ccaa and cessprocess=cssprocess
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resrh = $bd->execRequete ($reqrh);
				$rh=$bd->ligTabSuivant($resrh);

				$immrh=$ligne[0]*($rh[0]/$charges);
				$totimm=$totimm+$immrh;

				tblcellule(number_format($immrh,3 , ' ' ,  ' '));
				tblcellule("");

				$socialrh=$SP90[0]*($rh[0]/$charges);
				$totsocial=$totsocial+$socialrh;
				tblcellule(number_format($socialrh,3 , ' ' ,  ' ' ));

				$rhrh=$SP91[0]*($rh[0]/$charges);
				$totrh=$totrh+$rhrh;
				tblcellule(number_format($rhrh,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immrh+$socialrh+$rhrh,3 , ' ' ,  ' '));
				break;
			case "SI":
				$reqsi = "select sum(charcaa) from charcaa,sousprocessus,processus,caa
								where cecaa=ccaa and cessprocess=cssprocess
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$ressi = $bd->execRequete ($reqsi);
				$si=$bd->ligTabSuivant($ressi);

				$immsi=$ligne[0]*($si[0]/$charges);
				$totimm=$totimm+$immsi;

				tblcellule(number_format($immsi,3 , ' ' ,  ' '));
				tblcellule("");

				$socialsi=$SP90[0]*($si[0]/$charges);
				$totsocial=$totsocial+$socialsi;
				tblcellule(number_format($socialsi,3 , ' ' ,  ' ' ));

				$rhsi=$SP91[0]*($si[0]/$charges);
				$totrh=$totrh+$rhsi;
				tblcellule(number_format($rhsi,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immsi+$socialsi+$rhsi,3 , ' ' ,  ' '));
				break;
			case "TRANSPORT":
				$reqtransport = "select sum(charcaa) from charcaa,sousprocessus,processus,caa
								where cecaa=ccaa and cessprocess=cssprocess
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$restransport = $bd->execRequete ($reqtransport);
				$transport=$bd->ligTabSuivant($restransport);

				$immtransport=$ligne[0]*($transport[0]/$charges);
				$totimm=$totimm+$immtransport;

				tblcellule(number_format($immtransport,3 , ' ' ,  ' '));
				tblcellule("");

				$socialtransport=$SP90[0]*($transport[0]/$charges);
				$totsocial=$totsocial+$socialtransport;
				tblcellule(number_format($socialtransport,3 , ' ' ,  ' ' ));

				$rhtransport=$SP91[0]*($transport[0]/$charges);
				$totrh=$totrh+$rhtransport;
				tblcellule(number_format($rhtransport,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immtransport+$socialtransport+$rhtransport,3 , ' ' ,  ' '));
				break;
			case "TI":
				$reqti = "select sum(charcaa) from charcaa,sousprocessus,processus,caa
								where cecaa=ccaa and cessprocess=cssprocess
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resti = $bd->execRequete ($reqti);
				$ti=$bd->ligTabSuivant($resti);

				$immti=$ligne[0]*($ti[0]/$charges);
				$totimm=$totimm+$immti;

				tblcellule(number_format($immti,3 , ' ' ,  ' '));
				tblcellule("");

				$socialti=$SP90[0]*($ti[0]/$charges);
				$totsocial=$totsocial+$socialti;
				tblcellule(number_format($socialti,3 , ' ' ,  ' ' ));

				$rhti=$SP91[0]*($ti[0]/$charges);
				$totrh=$totrh+$rhti;
				tblcellule(number_format($rhti,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immti+$socialti+$rhti,3 , ' ' ,  ' '));
				break;
			case "CAA TRANSVERSES":
				tblcellule("");
				tblcellule("");
				tblcellule("");
				tblcellule("");
				tblcellule("");
				break;
			default:
				break;
		}

	/*	switch ($ligne3[2])
		{
			case "DISTRIBUTION":
				tblcellule($ligne3[2]);
				$reqpr02 = "select distinct sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode
								where cecaa=ccaa and cessprocess=cssprocess
									and ceprocess=cprocess and codprocess='PR02'";
				$respr02 = $bd->execRequete ($reqpr02);
				$PR02=$bd->ligTabSuivant($respr02);

				$reqpr04 = "select distinct sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode
								where cecaa=ccaa and cessprocess=cssprocess
									and ceprocess=cprocess and codprocess='PR04'";
				$respr04 = $bd->execRequete ($reqpr04);
				$PR04=$bd->ligTabSuivant($respr04);

				$reqdist = "select distinct sum(charcaa)
								from charcaa,periode,mois,caa
									where cecaa=ccaa and codcaa='775'";
				$resdist = $bd->execRequete ($reqdist);
				$ligne=$bd->ligTabSuivant($resdist);

				$reqdist2 = "select distinct sum(charcaa)
								from charcaa,periode,mois,caa
									where cecaa=ccaa and codcaa='750'";
				$resdist2 = $bd->execRequete ($reqdist2);
				$ligne2=$bd->ligTabSuivant($resdist2);

				$reqdist3 = "select distinct sum(charcaa)
								from charcaa,periode,mois,sousprocessus,caa
									where cecaa=ccaa and cessprocess=cssprocess
										and codssprocess='SP90'";
				$resdist3 = $bd->execRequete ($reqdist3);
				$ligne3=$bd->ligTabSuivant($resdist3);

				$reqdist4 = "select distinct sum(charcaa)
								from charcaa,periode,mois,sousprocessus,caa
									where cecaa=ccaa and cessprocess=cssprocess
										and codssprocess='SP91'";
				$resdist4 = $bd->execRequete ($reqdist4);
				$ligne4=$bd->ligTabSuivant($resdist4);

				$traftrait=$PR02[0]+$PR04[0];
				if($traftrait<>0)
				{
					tblcellule(number_format($ligne[0]+(($PR02[0]/$PR02[0])+$PR04[0]),0 , ' ' ,  ' ' ));
					tblcellule(number_format($ligne2[0]+(($PR02[0]/$PR02[0])+$PR04[0]),0 , ' ' ,  ' ' ));
					tblcellule(number_format($ligne3[0]+(($PR04[0]/$PR04[0])+$PR02[0]),0 , ' ' ,  ' ' ));
					tblcellule(number_format($ligne4[0]+(($PR04[0]/$PR04[0])+$PR02[0]),0 , ' ' ,  ' ' ));
					tblcellule(number_format($ligne[0]+$ligne2[0]+$ligne3[0]+$ligne4[0],0 , ' ' ,  ' ' ));
				}
				else
				{
					tblcellule("Absence de trafic");
				}

				break;

			case "TRAITEMENT":
				tblcellule($ligne3[2]);
				$reqpr02 = "select distinct sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode
								where cecaa=ccaa and cessprocess=cssprocess
									and ceprocess=cprocess and codprocess='PR02'";
				$respr02 = $bd->execRequete ($reqpr02);
				$PR02=$bd->ligTabSuivant($respr02);

				$reqpr04 = "select distinct sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode
								where cecaa=ccaa and cessprocess=cssprocess
									and ceprocess=cprocess and codprocess='PR04'";
				$respr04 = $bd->execRequete ($reqpr04);
				$PR04=$bd->ligTabSuivant($respr04);

				$reqdist = "select distinct sum(charcaa)
								from charcaa,periode,mois,caa
									where cecaa=ccaa and codcaa='775'";
				$resdist = $bd->execRequete ($reqdist);
				$ligne=$bd->ligTabSuivant($resdist);

				$reqdist2 = "select distinct sum(charcaa)
								from charcaa,periode,mois,caa
									where cecaa=ccaa and codcaa='750'";
				$resdist2 = $bd->execRequete ($reqdist2);
				$ligne2=$bd->ligTabSuivant($resdist2);

				$reqdist3 = "select distinct sum(charcaa)
								from charcaa,periode,mois,sousprocessus,caa
									where cecaa=ccaa and cessprocess=cssprocess
										and codssprocess='SP90'";
				$resdist3 = $bd->execRequete ($reqdist3);
				$ligne3=$bd->ligTabSuivant($resdist3);

				$reqdist4 = "select distinct sum(charcaa)
								from charcaa,periode,mois,sousprocessus,caa
									where cecaa=ccaa and cessprocess=cssprocess
										and codssprocess='SP91'";
				$resdist4 = $bd->execRequete ($reqdist4);
				$ligne4=$bd->ligTabSuivant($resdist4);
				tblcellule(number_format($ligne[0]+(($PR02[0]/$PR02[0])+$PR04[0]),0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne2[0]+(($PR02[0]/$PR02[0])+$PR04[0]),0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne3[0]+(($PR04[0]/$PR04[0])+$PR02[0]),0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne4[0]+(($PR04[0]/$PR04[0])+$PR02[0]),0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne[0]+$ligne2[0]+$ligne3[0]+$ligne4[0],0 , ' ' ,  ' ' ));
				break;
			case "CONCENTRATION":
				tblcellule($ligne3[2]);
				tblcellule(number_format($ligne[0]+(($PR02[0]/$PR02[0])+$PR04[0]),0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne2[0]+(($PR02[0]/$PR02[0])+$PR04[0]),0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne3[0]+(($PR04[0]/$PR04[0])+$PR02[0]),0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne4[0]+(($PR04[0]/$PR04[0])+$PR02[0]),0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne[0]+$ligne2[0]+$ligne3[0]+$ligne4[0],0 , ' ' ,  ' ' ));
				break;
			case "TI":
				tblcellule($ligne3[2]);
				tblcellule(number_format($ligne[0]+(($PR02[0]/$PR02[0])+$PR04[0]),0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne2[0]+(($PR02[0]/$PR02[0])+$PR04[0]),0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne3[0]+(($PR04[0]/$PR04[0])+$PR02[0]),0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne4[0]+(($PR04[0]/$PR04[0])+$PR02[0]),0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne[0]+$ligne2[0]+$ligne3[0]+$ligne4[0],0 , ' ' ,  ' ' ));
				break;

			default:	tblcellule($ligne3[2]);
				break;

		}*/
		tblfinligne();
		tbldebutligne(MPAP);
		tblcellule("MPAP");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblfinligne();
	}
	tbldebutligne();
	tblcellule("Total");
	tblcellule(number_format($totimm,3 , ' ', ' '));
	tblcellule(number_format($totvehi,3 , ' ', ' '));
	tblcellule(number_format($totsocial,3 , ' ', ' '));
	tblcellule(number_format($totrh,3 , ' ', ' '));
	tblcellule(number_format(-$ligne[0]-$ligne2[0]-$SP90[0]-$SP91[0]+$immti+$socialti+$rhti
		+$immtransport+$socialtransport+$rhtransport
		+$immsi+$socialsi+$rhsi
		+$immrh+$socialrh+$rhrh
		+$immpil+$socialpil+$rhpil
		+$immmark+$socialmark+$rhmark
		+$immcompta+$socialcompta+$rhcompta
		+$immst+$socialst+$rhst
		+$immconc+$vehiconc+$socialconc+$rhconc
		+$immtrait+$socialtrait+$rhtrait
		+$immdist+$vehidist+$socialdist+$rhdist
		,0 , ' ' ,  ' ' ));
	tblfinligne();
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
    //Page de global Dotc nationaux
function formglobdotc()
    {
	//require("pconnect.php");
    //require_once ("util.php");
    sessval();
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new Formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Distribution Syci"=>"globdotcdist.php",
					  "Distribution Pildi"=>"globdotcdistpildi.php",
					  "Traitement"=>"globdotctrait.php",
					  "Concentration"=>"globdotcconc.php",
				          "TI"=>"globdotcti.php");
	//requete recup processus
	$req = "select cdotc,ville,cprocess
				from dotc,processus
                    where libprocess like 'DISTRIBUTION'
                        order by ville";
	$res = $bd->execRequete ($req);
	$nomb_ligne2= $bd->nbLigne($res);

	$Form = new Formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Global DOTC'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
     //foreach ($pListe as $val => $libelle )
      //while (list ($val, $libelle) = each ($pListe)) 
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Rang"));
	$Form->ajoutTexte (tblentete("Ecart Moyenne"));
	$Form->ajoutTexte (tblentete("Coût unitaire"));
	$Form->ajoutTexte (tblentete("Trafic"));
	$Form->ajoutTexte (tblentete("Données financières av retr"));
	$Form->ajoutTexte (tblentete("Données financières ap retr"));
	$Form->ajoutTexte (tblfinligne());
    $totalchar=0; //!!!!!
	$totaltraf=0;
	$totalcunit=0;
	for ($j=0;$j<$nomb_ligne2;$j++)
	{
//recuperation de la requete dotc ainsi renomme en ligne2
		$ligne2= $bd ->ligTabSuivant($res);
		//$ligne1=$bd->ligTabSuivant($resultat1);
    //Recuperation du trafic
                //recuperation des trafics pildi
		$requete3 = "select distinct sum(trafsyci)
                                from syci,dotc
                                    where cdotc=syci.cedotc
                                    and trafsyci<>0
                                    and dotc.ville='".$ligne2[1]."'
                                        group by ville";
		$resultat3 = $bd->execRequete ($requete3);
		$ligne3= $bd->ligTabSuivant($resultat3);
                //recuperation des trafics pna
		$requete4 = "select sum(trafpna)
                        from dotc,pna,entite
                            where dotc.ville='".$ligne2[1]."'
                                and trafpna<>0
                                and dotc.ville=entite.centite
                                and dotc.cdotc=entite.cedotc
                                    group by ville";
		$resultat4 = $bd->execRequete ($requete4);
		$ligne4=$bd->ligTabSuivant($resultat4);
                //recuperation des trafics colis
                $requete5 = "select sum(trafcolis)
                                    from colis,entite,dotc
                                         where dotc.ville='".$ligne2[1]."'
                                         and trafcolis<>0
                                         and centite=ceentite
                                         and dotc.cdotc=entite.cedotc
                                             group by ville";
		$resultat5 = $bd->execRequete ($requete5);
		$ligne5=$bd->ligTabSuivant($resultat5);

		$requete3b = "select distinct sum(trafsyci)
                                from syci,dotc
                                    where cedotc=cdotc
                                    and trafsyci<>0
                                       and cedotc<>99";
		$resultat3b = $bd->execRequete ($requete3b);
		$ligne3b=$bd->ligTabSuivant($resultat3b);
                //recuperation des trafics pna
		$requete4b = "select sum(trafpna)
                        from pna,entite
                            where trafpna<>0
                                and centite=ceentite and cedotc<>99 ";
		$resultat4b = $bd->execRequete ($requete4b);
		$ligne4b=$bd->ligTabSuivant($resultat4b);
                //recuperation des trafics colis
                $requete5b = "select sum(trafcolis)
                                    from colis,entite
                                    where trafcolis<>0
                                         and centite=ceentite and cedotc<>99 ";
		$resultat5b = $bd->execRequete ($requete5b);
		$ligne5b=$bd->ligTabSuivant($resultat5b);
                $totaltraf=$ligne3b[0]+$ligne4b[0]+$ligne5b[0];
                $totalcunit=$totalchar/$totaltraf;
    //recuperation des couts unitaires
               //totalit� des charges distribution sur les dotc
                $reqchar2="select distinct sum(charcaa)
                                  from charcaa,entite,processus,caa,sousprocessus
                                       where charcaa.ceentite=entite.centite and cecaa=ccaa
                                       and cssprocess=cessprocess and cprocess=ceprocess and libprocess like'DISTRIBUTION'
                                             and cedotc<>99";
                 $reschar2 = $bd->execRequete ($reqchar2);
                 $ligchar2=$bd->ligTabSuivant($reschar2);
                 $totalchar=$ligchar2[0];
                 //charges par ville
                 $reqchar="select distinct sum(charcaa)
                                  from dotc,charcaa,entite,caa,sousprocessus,processus
                                       where dotc.ville='".$ligne2[1]."' and cecaa=ccaa
                                       and cssprocess=cessprocess and cprocess=ceprocess and libprocess like'DISTRIBUTION'
                                             and charcaa.ceentite=entite.centite
                                             and entite.cedotc=dotc.cdotc
                                                 group by ville ";
                 $reschar = $bd->execRequete ($reqchar);
                 $ligchar=$bd->ligTabSuivant($reschar);
    //Aspect visuel des champs
		tbldebutligne("A0");
		tblcellule($ligne2[1]);//ville
		tblcellule("Pas encore en fonction");//rang
		$trafmoye=$ligne3[0]+$ligne3[0]+$ligne4[0]+$ligne5[0];
        //$trafmoye=$ligne3[0]+$ligne4[0]+$ligne5[0];
		if($trafmoye<>0 and $totalcunit<>0)
		{
			tblcellule(number_format(((($ligchar[0]/($ligne3[0]+$ligne3[0]+$ligne4[0]+$ligne5[0]))-$totalcunit)/$totalcunit)*100,2,',','')." %");//ecart moyenne
		}
		else
		{
			tblcellule("Absence de trafic");
		}
		$trafcoutu=$ligne3[0]+$ligne3[0]+$ligne4[0]+$ligne5[0];
		if($trafcoutu<>0)
		{
			tblcellule(number_format($ligchar[0]/($ligne3[0]+$ligne3[0]+$ligne4[0]+$ligne5[0]),5,',',' '));//couts unitaires
		}
		else
		{
			tblcellule("Absence de trafic");
		}
        tblcellule(number_format($ligne3[0]+$ligne3[0]+$ligne4[0]+$ligne5[0],0,' ',' '));//trafics
        tblcellule(number_format($ligchar[0],0,' ',' '));//donn�es financi�res avant retraitement
        tblcellule("Pas encore en fonction");//donn�es financi�res apr�s retraitement
 		tblfinligne();
	}
	tbldebutligne("A0");
	tblcellule("");
	tblfinligne();

	tbldebutligne("A0");
	tblcellule("Total DOTC");
    tblcellule("");
    tblcellule("");
	tblcellule(number_format($totalcunit,5,',',' '));
	tblcellule(number_format($totaltraf,0,' ',' '));
	tblcellule(number_format($totalchar,0,' ',' '));
	tblcellule("");
	tblfinligne();
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
function formglobdotcdist()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Distribution Syci"=>"globdotcdist.php",
					  "Distribution Pildi"=>"globdotcdistpildi.php",
					  "Traitement"=>"globdotctrait.php",
					  "Concentration"=>"globdotcconc.php",
				      "TI"=>"globdotcti.php");
//requete recup processus
	$req = "select cdotc,ville,cprocess
				from dotc,processus
                    where libprocess like 'DISTRIBUTION'
                        order by ville";
	$res = $bd->execRequete ($req);
	$nomb_ligne2=$bd->nbLigne($res);

	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Global DOTC - Distribution Syci'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Rang"));
	$Form->ajoutTexte (tblentete("Ecart Moyenne"));
	$Form->ajoutTexte (tblentete("Coût unitaire"));
	$Form->ajoutTexte (tblentete("Trafic"));
	$Form->ajoutTexte (tblentete("Données financières av retr"));
	$Form->ajoutTexte (tblentete("Données financières ap retr"));
	$Form->ajoutTexte (tblfinligne());
    $totalchar=0; //!!!!!
	$totaltraf=0;
	$totalcunit=0;
	for ($j=0;$j<$nomb_ligne2;$j++)
	{
    //recuperation de la requete dotc ainsi renomme en ligne2
		$ligne2=$bd->ligTabSuivant($res);
		//$ligne1=$bd->ligTabSuivant($resultat1);
    //Recuperation du trafic
                //recuperation des trafics pildi
		$requete3 = "select distinct sum(trafsyci)
                                from syci,dotc
                                    where cdotc=syci.cedotc
                                    and trafsyci<>0
                                    and dotc.ville='".$ligne2[1]."'
                                        group by ville";
		$resultat3 = $bd->execRequete ($requete3);
		$ligne3=$bd->ligTabSuivant($resultat3);
                //recuperation des trafics pna
		$requete4 = "select sum(trafpna)
                        from dotc,pna,entite
                            where dotc.ville='".$ligne2[1]."'
                                and trafpna<>0
                                and dotc.ville=entite.centite
                                and dotc.cdotc=entite.cedotc
                                    group by ville";
		$resultat4 = $bd->execRequete ($requete4);
		$ligne4=$bd->ligTabSuivant($resultat4);
                //recuperation des trafics colis
                $requete5 = "select sum(trafcolis)
                                    from colis,entite,dotc
                                         where dotc.ville='".$ligne2[1]."'
                                         and trafcolis<>0
                                         and centite=ceentite
                                         and dotc.cdotc=entite.cedotc
                                             group by ville";
		$resultat5 = $bd->execRequete ($requete5);
		$ligne5=$bd->ligTabSuivant($resultat5);

		$requete3b = "select distinct sum(trafsyci)
                                from syci,dotc
                                    where cedotc=cdotc
                                    and trafsyci<>0
                                       and cedotc<>99";
		$resultat3b = $bd->execRequete ($requete3b);
		$ligne3b=$bd->ligTabSuivant($resultat3b);
                //recuperation des trafics pna
		$requete4b = "select sum(trafpna)
                        from pna,entite
                            where trafpna<>0
                                and centite=ceentite and cedotc<>99 ";
		$resultat4b = $bd->execRequete ($requete4b);
		$ligne4b=$bd->ligTabSuivant($resultat4b);
                //recuperation des trafics colis
                $requete5b = "select sum(trafcolis)
                                    from colis,entite
                                    where trafcolis<>0
                                         and centite=ceentite and cedotc<>99 ";
		$resultat5b = $bd->execRequete ($requete5b);
		$ligne5b=$bd->ligTabSuivant($resultat5b);
                $totaltraf=$ligne3b[0]+$ligne4b[0]+$ligne5b[0];
                $totalcunit=$totalchar/$totaltraf;
    //recuperation des couts unitaires
               //totalit� des charges distribution sur les dotc
                $reqchar2="select distinct sum(charcaa)
                                  from charcaa,entite,processus,caa,sousprocessus
                                       where charcaa.ceentite=entite.centite and cecaa=ccaa
                                       and cssprocess=cessprocess and cprocess=ceprocess and libprocess like'DISTRIBUTION'
                                             and cedotc<>99";
                 $reschar2 = $bd->execRequete ($reqchar2);
                 $ligchar2=$bd->ligTabSuivant($reschar2);
                 $totalchar=$ligchar2[0];
                 //charges par ville
                 $reqchar="select distinct sum(charcaa)
                                  from dotc,charcaa,entite,caa,sousprocessus,processus
                                       where dotc.ville='".$ligne2[1]."' and cecaa=ccaa
                                       and cssprocess=cessprocess and cprocess=ceprocess and libprocess like'DISTRIBUTION'
                                             and charcaa.ceentite=entite.centite
                                             and entite.cedotc=dotc.cdotc
                                                 group by ville ";
                 $reschar = $bd->execRequete ($reqchar);
                 $ligchar=$bd->ligTabSuivant($reschar);
    //Aspect visuel des champs
		tbldebutligne("A0");
		tblcellule($ligne2[1]);//ville
		tblcellule("Pas encore en fonction");//rang
		$trafmoyedistsy=$ligne3[0]+$ligne3[0]+$ligne4[0]+$ligne5[0];
		if($trafmoyedistsy<>0  and $totalcunit<>0)
		{
			tblcellule(number_format(((($ligchar[0]/($ligne3[0]+$ligne3[0]+$ligne4[0]+$ligne5[0]))-$totalcunit)/$totalcunit)*100,2,',','')." %");//ecart moyenne
		}
		else
		{
			tblcellule("Absence de trafic");
		}
		$trafcoudist=$ligne3[0]+$ligne3[0]+$ligne4[0]+$ligne5[0];
		if($trafcoudist<>0)
		{
			tblcellule(number_format($ligchar[0]/($ligne3[0]+$ligne3[0]+$ligne4[0]+$ligne5[0]),5,',',' '));//couts unitaires
		}
		else
		{
			tblcellule("Absence de trafic");
		}
                tblcellule(number_format($ligne3[0]+$ligne3[0]+$ligne4[0]+$ligne5[0],0,' ',' '));//trafics
                tblcellule(number_format($ligchar[0],0,' ',' '));//donn�es financi�res avant retraitement
		        tblcellule("Pas encore en fonction");//donn�es financi�res apr�s retraitement
 		tblfinligne();
	}
	tbldebutligne("A0");
	tblcellule("");
	tblfinligne();

	tbldebutligne("A0");
	tblcellule("Total DOTC");
        tblcellule("");
        tblcellule("");
	tblcellule(number_format($totalcunit,5,',',' '));
	tblcellule(number_format($totaltraf,0,' ',' '));
	tblcellule(number_format($totalchar,0,' ',' '));
	tblcellule("");
	tblfinligne();
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
function formglobdotcdistpildi()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Distribution Syci"=>"globdotcdist.php",
					  "Distribution Pildi"=>"globdotcdistpildi.php",
					  "Traitement"=>"globdotctrait.php",
					  "Concentration"=>"globdotcconc.php",
				      "TI"=>"globdotcti.php");

//requete recup processus
	$req = "select cdotc,ville,cprocess
				from dotc,processus
                    where libprocess like 'DISTRIBUTION'
                        order by ville";
	$res = $bd->execRequete ($req);
	$nomb_ligne2=$bd->nbLigne($res);

	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Global DOTC - Distribtion Pildi'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Rang"));
	$Form->ajoutTexte (tblentete("Ecart Moyenne"));
	$Form->ajoutTexte (tblentete("Coût unitaire"));
	$Form->ajoutTexte (tblentete("Trafic"));
	$Form->ajoutTexte (tblentete("Données financières av retr"));
	$Form->ajoutTexte (tblentete("Données financières ap retr"));
	$Form->ajoutTexte (tblfinligne());
    $totalchar=0; //!!!!!
	$totaltraf=0;
	$totalcunit=0;
	for ($j=0;$j<$nomb_ligne2;$j++)
	{
    //recuperation de la requete dotc ainsi renomme en ligne2
		$ligne2=$bd->ligTabSuivant($res);
		//$ligne1=$bd->ligTabSuivant($resultat1);
    //Recuperation du trafic
                //recuperation des trafics pildi
		$requete3 = "select distinct sum(trafdistribm),sum(trafdistribc)
                                from pildi,entite,dotc
                                    where ceentite=centite and cedotc=cdotc
                                    and (trafdistribm<>0 or trafdistribc<>0)
                                    and dotc.ville='".$ligne2[1]."'
                                        group by ville";
		$resultat3 = $bd->execRequete ($requete3);
		$ligne3=$bd->ligTabSuivant($resultat3);
                //recuperation des trafics pna
		$requete4 = "select sum(trafpna)
                        from dotc,pna,entite
                            where dotc.ville='".$ligne2[1]."'
                                and trafpna<>0
                                and dotc.ville=entite.centite
                                and dotc.cdotc=entite.cedotc
                                    group by ville";
		$resultat4 = $bd->execRequete ($requete4);
		$ligne4=$bd->ligTabSuivant($resultat4);
                //recuperation des trafics colis
                $requete5 = "select sum(trafcolis)
                                    from colis,entite,dotc
                                         where dotc.ville='".$ligne2[1]."'
                                         and trafcolis<>0
                                         and centite=ceentite
                                         and dotc.cdotc=entite.cedotc
                                             group by ville";
		$resultat5 = $bd->execRequete ($requete5);
		$ligne5=$bd->ligTabSuivant($resultat5);

		$requete3b = "select distinct sum(trafsyci)
                                from syci,dotc
                                    where cedotc=cdotc
                                    and trafsyci<>0
                                       and cedotc<>99";
		$resultat3b = $bd->execRequete ($requete3b);
		$ligne3b=$bd->ligTabSuivant($resultat3b);
                //recuperation des trafics pna
		$requete4b = "select sum(trafpna)
                        from pna,entite
                            where trafpna<>0
                                and centite=ceentite and cedotc<>99 ";
		$resultat4b = $bd->execRequete ($requete4b);
		$ligne4b=$bd->ligTabSuivant($resultat4b);
                //recuperation des trafics colis
                $requete5b = "select sum(trafcolis)
                                    from colis,entite
                                    where trafcolis<>0
                                         and centite=ceentite and cedotc<>99 ";
		$resultat5b = $bd->execRequete ($requete5b);
		$ligne5b=$bd->ligTabSuivant($resultat5b);
                $totaltraf=$ligne3b[0]+$ligne4b[0]+$ligne5b[0];
                $totalcunit=$totalchar/$totaltraf;
    //recuperation des couts unitaires
               //totalit� des charges distribution sur les dotc
                $reqchar2="select distinct sum(charcaa)
                                  from charcaa,entite,processus,caa,sousprocessus
                                       where charcaa.ceentite=entite.centite and cecaa=ccaa
                                       and cssprocess=cessprocess and cprocess=ceprocess and libprocess like'DISTRIBUTION'
                                             and cedotc<>99";
                 $reschar2 = $bd->execRequete ($reqchar2);
                 $ligchar2=$bd->ligTabSuivant($reschar2);
                 $totalchar=$ligchar2[0];
                 //charges par ville
                 $reqchar="select distinct sum(charcaa)
                                  from dotc,charcaa,entite,caa,sousprocessus,processus
                                       where dotc.ville='".$ligne2[1]."' and cecaa=ccaa
                                       and cssprocess=cessprocess and cprocess=ceprocess and libprocess like'DISTRIBUTION'
                                             and charcaa.ceentite=entite.centite
                                             and entite.cedotc=dotc.cdotc
                                                 group by ville ";
                 $reschar = $bd->execRequete ($reqchar);
                 $ligchar=$bd->ligTabSuivant($reschar);
    //Aspect visuel des champs
		tbldebutligne("A0");
		tblcellule($ligne2[1]);//ville
		tblcellule("Pas encore en fonction");//rang
		$trafmoyedistpi=$ligne3[0]+$ligne3[1]+$ligne4[0]+$ligne5[0];
		if($trafmoyedistpi<>0 and $totalcunit<>0)
		{
			tblcellule(number_format(((($ligchar[0]/($ligne3[0]+$ligne3[1]+$ligne4[0]+$ligne5[0]))-$totalcunit)/$totalcunit)*100,2,',','')." %");//ecart moyenne
		}
		else
		{
			tblcellule("Absence de trafic");
		}
		$trafcoudistpi=$ligne3[0]+$ligne3[1]+$ligne4[0]+$ligne5[0];
		if($trafcoudistpi<>0)
		{
			tblcellule(number_format($ligchar[0]/($ligne3[0]+$ligne3[1]+$ligne4[0]+$ligne5[0]),5,',',' '));//couts unitaires
		}
		else
		{
			tblcellule("Absence de trafic");
		}
                tblcellule(number_format($ligne3[0]+$ligne3[1]+$ligne4[0]+$ligne5[0],0,' ',' '));//trafics
                tblcellule(number_format($ligchar[0],0,' ',' '));//donn�es financi�res avant retraitement
		        tblcellule("Pas encore en fonction");//donn�es financi�res apr�s retraitement
 		tblfinligne();
	}
	tbldebutligne("A0");
	tblcellule("");
	tblfinligne();

	tbldebutligne("A0");
	tblcellule("Total DOTC");
        tblcellule("");
        tblcellule("");
	tblcellule(number_format($totalcunit,5,',',' '));
	tblcellule(number_format($totaltraf,0,' ',' '));
	tblcellule(number_format($totalchar,0,' ',' '));
	tblcellule("");
	tblfinligne();
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
    }
function formglobdotctrait()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Distribution Syci"=>"globdotcdist.php",
					  "Distribution Pildi"=>"globdotcdistpildi.php",
					  "Traitement"=>"globdotctrait.php",
					  "Concentration"=>"globdotcconc.php",
				      "TI"=>"globdotcti.php");
	//requete recup processus
	$req = "select cdotc,ville
				from dotc
                        order by ville";
	$res = $bd->execRequete ($req);
	$nomb_ligne2=$bd->nbLigne($res);
	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Global DOTC - Traitement'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
		tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Rang"));
	$Form->ajoutTexte (tblentete("Ecart Moyenne"));
	$Form->ajoutTexte (tblentete("Coût unitaire"));
	$Form->ajoutTexte (tblentete("Trafic"));
	$Form->ajoutTexte (tblentete("Données financières av retr"));
	$Form->ajoutTexte (tblentete("Données financières ap retr"));
	$Form->ajoutTexte (tblfinligne());
		for ($j=0;$j<$nomb_ligne2;$j++)
	{
    //recuperation de la requete dotc ainsi renomme en ligne2
		$ligne2=$bd->ligTabSuivant($res);
		//$ligne1=$bd->ligTabSuivant($resultat1);
    //Recuperation du trafic
               //recuperation des trafics syspeo
	$requete3 = "select distinct sum(traftraitd),sum(traftraita)from syspeo,entite,dotc
                            where centite=syspeo.ceentite
                                and (traftraitd<>0 or traftraita<>0)
                                and dotc.ville='".$ligne2[1]."'
                                and dotc.cdotc=entite.cedotc
                                    group by ville";
	$resultat3 = $bd->execRequete ($requete3);
	$ligne3=$bd->ligTabSuivant($resultat3);
                //recuperation des trafics pna
		$requete4 = "select sum(trafpna)
                        from dotc,pna,entite
                            where dotc.ville='".$ligne2[1]."'
                                and trafpna<>0
                                and dotc.ville=entite.centite
                                and dotc.cdotc=entite.cedotc
                                    group by ville";
		$resultat4 = $bd->execRequete ($requete4);
		$ligne4=$bd->ligTabSuivant($resultat4);
                //recuperation des trafics colis
                $requete5 = "select sum(trafcolis)
                                    from colis,entite,dotc
                                         where dotc.ville='".$ligne2[1]."'
                                         and trafcolis<>0
                                         and centite=ceentite
                                         and dotc.cdotc=entite.cedotc
                                             group by ville";
		$resultat5 = $bd->execRequete ($requete5);
		$ligne5=$bd->ligTabSuivant($resultat5);

		$requete3b = "select distinct sum(trafsyci)
                                from syci,dotc
                                    where cedotc=cdotc
                                    and trafsyci<>0
                                       and cedotc<>99";
		$resultat3b = $bd->execRequete ($requete3b);
		$ligne3b=$bd->ligTabSuivant($resultat3b);
                //recuperation des trafics pna
		$requete4b = "select sum(trafpna)
                        from pna,entite
                            where trafpna<>0
                                and centite=ceentite and cedotc<>99 ";
		$resultat4b = $bd->execRequete ($requete4b);
		$ligne4b=$bd->ligTabSuivant($resultat4b);
                //recuperation des trafics colis
                $requete5b = "select sum(trafcolis)
                                    from colis,entite
                                    where trafcolis<>0
                                         and centite=ceentite and cedotc<>99 ";
		$resultat5b = $bd->execRequete ($requete5b);
		$ligne5b=$bd->ligTabSuivant($resultat5b);
                $totaltraf=$ligne3b[0]+$ligne4b[0]+$ligne5b[0];
                
    //recuperation des couts unitaires
               //totalit� des charges distribution sur les dotc
                $reqchar2="select distinct sum(charcaa)
                                  from charcaa,entite,processus,caa,sousprocessus
                                       where charcaa.ceentite=entite.centite and cecaa=ccaa
                                       and cssprocess=cessprocess and cprocess=ceprocess and libprocess like'TRAITEMENT'
                                             and cedotc<>99";
                 $reschar2 = $bd->execRequete ($reqchar2);
                 $ligchar2=$bd->ligTabSuivant($reschar2);
                 $totalchar=$ligchar2[0];
                 $totalcunit=$totalchar/$totaltraf;
                 //charges par ville
                 $reqchar="select distinct sum(charcaa)
                                  from dotc,charcaa,entite,caa,sousprocessus,processus
                                       where dotc.ville='".$ligne2[1]."' and cecaa=ccaa
                                       and cssprocess=cessprocess and cprocess=ceprocess and libprocess like'TRAITEMENT'
                                             and charcaa.ceentite=entite.centite
                                             and entite.cedotc=dotc.cdotc
                                                 group by ville ";
                 $reschar = $bd->execRequete ($reqchar);
                 $ligchar=$bd->ligTabSuivant($reschar);
                 
    //Aspect visuel des champs
		tbldebutligne("A0");
		tblcellule($ligne2[1]);//ville
		tblcellule("Pas encore en fonction");//rang
		$trafmoyetrait=$ligne3[0]+$ligne3[1]+$ligne4[0]+$ligne5[0];
		if($trafmoyetrait<>0 and $totalcunit<>0)
		{
			tblcellule(number_format(((($ligchar[0]/($ligne3[0]+$ligne3[1]+$ligne4[0]+$ligne5[0]))-$totalcunit)/$totalcunit)*100,2,',','')." %");//ecart moyenne
		}
		else
		{
			tblcellule("Absence de trafic");
		}
		$trafcoutrai=$ligne3[0]+$ligne3[1]+$ligne4[0]+$ligne5[0];
		if($trafcoutrai<>0)
		{
			tblcellule(number_format($ligchar[0]/($ligne3[0]+$ligne3[1]+$ligne4[0]+$ligne5[0]),5,',',' '));//couts unitaires
		}
		else
		{
			tblcellule("Absence de trafic");
		}
		tblcellule(number_format($ligne3[0]+$ligne3[1],0,' ',' '));
                tblcellule(number_format($ligchar[0],0,' ',' '));//donn�es financi�res avant retraitement
		        tblcellule("Pas encore en fonction");//donn�es financi�res apr�s retraitement
 		tblfinligne();
	}
	tbldebutligne("A0");
	tblcellule("");
	tblfinligne();

	tbldebutligne("A0");
	tblcellule("Total DOTC");
        tblcellule("");
        tblcellule("");
	tblcellule(number_format($totalcunit,5,',',' '));
	tblcellule(number_format($totaltraf,0,' ',' '));
	tblcellule(number_format($totalchar,0,' ',' '));
	tblcellule("");
	tblfinligne();
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
function formglobdotcconc()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Distribution Syci"=>"globdotcdist.php",
					  "Distribution Pildi"=>"globdotcdistpildi.php",
					  "Traitement"=>"globdotctrait.php",
					  "Concentration"=>"globdotcconc.php",
				      "TI"=>"globdotcti.php");
	//champliste deroulant pour annee et periode


	//requete recup processus
	$req = "select cdotc,ville
				from dotc
                        order by ville";
	$res = $bd->execRequete ($req);
	$nomb_ligne2=$bd->nbLigne($res);

	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Global DOTC - Concentration'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
		tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Rang"));
	$Form->ajoutTexte (tblentete("Ecart Moyenne"));
	$Form->ajoutTexte (tblentete("Coût unitaire"));
	$Form->ajoutTexte (tblentete("Trafic"));
	$Form->ajoutTexte (tblentete("Données financières av retr"));
	$Form->ajoutTexte (tblentete("Données financières ap retr"));
	$Form->ajoutTexte (tblfinligne());
       	for ($j=0;$j<$nomb_ligne2;$j++)
	{
    //recuperation de la requete dotc ainsi renomme en ligne2
	$ligne2=$bd->ligTabSuivant($res);
	//$ligne1=$bd->ligTabSuivant($resultat1);
    //Recuperation du trafic
               //recuperation des trafics pildi
	$requete3 = "select distinct sum(trafconc)
                        from syspeo,entite,dotc
                            where centite=syspeo.ceentite
                                and trafconc<>0
                                and dotc.ville='".$ligne2[1]."'
                                and dotc.cdotc=entite.cedotc
                                    group by ville";
	$resultat3 = $bd->execRequete ($requete3);
	$ligne3=$bd->ligTabSuivant($resultat3);
                //recuperation des trafics pna
		$requete4 = "select sum(trafpna)
                        from dotc,pna,entite
                            where dotc.ville='".$ligne2[1]."'
                                and trafpna<>0
                                and dotc.ville=entite.centite
                                and dotc.cdotc=entite.cedotc
                                    group by ville";
		$resultat4 = $bd->execRequete ($requete4);
		$ligne4=$bd->ligTabSuivant($resultat4);
                //recuperation des trafics colis
                $requete5 = "select sum(trafcolis)
                                    from colis,entite,dotc
                                         where dotc.ville='".$ligne2[1]."'
                                         and trafcolis<>0
                                         and centite=ceentite
                                         and dotc.cdotc=entite.cedotc
                                             group by ville";
		$resultat5 = $bd->execRequete ($requete5);
		$ligne5=$bd->ligTabSuivant($resultat5);

		$requete3b = "select distinct sum(trafsyci)
                                from syci,dotc
                                    where cedotc=cdotc
                                    and trafsyci<>0
                                       and cedotc<>99";
		$resultat3b = $bd->execRequete ($requete3b);
		$ligne3b=$bd->ligTabSuivant($resultat3b);
                //recuperation des trafics pna
		$requete4b = "select sum(trafpna)
                        from pna,entite
                            where trafpna<>0
                                and centite=ceentite and cedotc<>99 ";
		$resultat4b = $bd->execRequete ($requete4b);
		$ligne4b=$bd->ligTabSuivant($resultat4b);
                //recuperation des trafics colis
                $requete5b = "select sum(trafcolis)
                                    from colis,entite
                                    where trafcolis<>0
                                         and centite=ceentite and cedotc<>99 ";
		$resultat5b = $bd->execRequete ($requete5b);
		$ligne5b=$bd->ligTabSuivant($resultat5b);
                $totaltraf=$ligne3b[0]+$ligne4b[0]+$ligne5b[0];
               
    //recuperation des couts unitaires
               //totalit� des charges distribution sur les dotc
                $reqchar2="select distinct sum(charcaa)
                                  from charcaa,entite,processus,caa,sousprocessus
                                       where charcaa.ceentite=entite.centite and cecaa=ccaa
                                       and cssprocess=cessprocess and cprocess=ceprocess and libprocess like'CONCENTRATION'
                                             and cedotc<>99";
                 $reschar2 = $bd->execRequete ($reqchar2);
                 $ligchar2=$bd->ligTabSuivant($reschar2);
                 $totalchar=$ligchar2[0];
                  $totalcunit=$totalchar/$totaltraf;
                 //charges par ville
                 $reqchar="select distinct sum(charcaa)
                                  from dotc,charcaa,entite,caa,sousprocessus,processus
                                       where dotc.ville='".$ligne2[1]."' and cecaa=ccaa
                                       and cssprocess=cessprocess and cprocess=ceprocess and libprocess like'CONCENTRATION'
                                             and charcaa.ceentite=entite.centite
                                             and entite.cedotc=dotc.cdotc
                                                 group by ville ";
                 $reschar = $bd->execRequete ($reqchar);
                 $ligchar=$bd->ligTabSuivant($reschar);
    //Aspect visuel des champs
		tbldebutligne("A0");
		tblcellule($ligne2[1]);//ville
		tblcellule("Pas encore en fonction");//rang
		$trafmoyeconc=$ligne3[0]+$ligne3[1]+$ligne4[0]+$ligne5[0];
		if ($trafmoyeconc<>0 and $totalcunit<>0)
		{
			tblcellule(number_format(((($ligchar[0]/($ligne3[0]+$ligne3[1]+$ligne4[0]+$ligne5[0]))-$totalcunit)/$totalcunit)*100,2,',','')." %");//ecart moyenne
		}
		else
		{
			tblcellule("Absence de trafic");
		}
		$trafcoutuconc=$ligne3[0]+$ligne3[1]+$ligne4[0]+$ligne5[0];
		if ($trafcoutuconc<>0)
		{
			tblcellule(number_format($ligchar[0]/($ligne3[0]+$ligne3[1]+$ligne4[0]+$ligne5[0]),5,',',' '));//couts unitaires
		}
		else
		{
			tblcellule("Absence de trafic");
		}
		tblcellule(number_format($ligne3[0]+$ligne4[0]+$ligne5[0],0,' ',' '));   //trafic
		//tblcellule(number_format($ligne3[0]+$ligne3[1],0,' ',' '));
        tblcellule(number_format($ligchar[0],0,' ',' '));//donn�es financi�res avant retraitement
        tblcellule("Pas encore en fonction");//donn�es financi�res apr�s retraitement
 		tblfinligne();
	}
        tbldebutligne("A0");
	tblcellule("");
	tblfinligne();

	tbldebutligne("A0");
	tblcellule("Total DOTC");
        tblcellule("");
        tblcellule("");
	tblcellule(number_format($totalcunit,5,',',' '));
	tblcellule(number_format($totaltraf,0,' ',' '));
	tblcellule(number_format($totalchar,0,' ',' '));
	tblcellule("");
	tblfinligne();
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
function formglobdotcti()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Distribution Syci"=>"globdotcdist.php",
					  "Distribution Pildi"=>"globdotcdistpildi.php",
					  "Traitement"=>"globdotctrait.php",
					  "Concentration"=>"globdotcconc.php",
				      "TI"=>"globdotcti.php");
	//requete sql alimentant tableau
	//requete recup processus
	$req = "select cdotc,ville
				from dotc
                        order by ville";
	$res = $bd->execRequete ($req);
	$nomb_ligne2=$bd->nbLigne($res);

	//requete sql alimentant tableau
	//$requete3 = "select distinct cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
	//from domaine,processus,sousprocessus,caa,charcaa,entite
		//where cordaffich<>'0'
			//and cdom=cedom and cprocess=ceprocess
            //and cssprocess=cessprocess and ccaa=cecaa
            //and centite=ceentite and cprocess='".$req2[0]."'group by cregate order by cregate";
	//$resultat3 = $bd->execRequete ($requete3);
	//$nomb_ligne3=mysql_num_rows($resultat3);

	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Global DOTC - TI'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
		tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Rang"));
	$Form->ajoutTexte (tblentete("Ecart Moyenne"));
	$Form->ajoutTexte (tblentete("Coût unitaire"));
	$Form->ajoutTexte (tblentete("Trafic"));
	$Form->ajoutTexte (tblentete("Données financières av retr"));
	$Form->ajoutTexte (tblentete("Données financières ap retr"));
	$Form->ajoutTexte (tblfinligne());
       	for ($j=0;$j<$nomb_ligne2;$j++)
	{
    //recuperation de la requete dotc ainsi renomme en ligne2
	$ligne2=$bd->ligTabSuivant($res);
    //$ligne1=$bd->ligTabSuivant($resultat1);
    //Recuperation du trafic
               //recuperation des trafics pildi
	$requete3 = "select distinct sum(trafconc)
                        from syspeo,entite,dotc
                            where centite=syspeo.ceentite
                                and trafconc<>0
                                and dotc.ville='".$ligne2[1]."'
                                and dotc.cdotc=entite.cedotc
                                    group by ville";
	$resultat3 = $bd->execRequete ($requete3);
	$ligne3=$bd->ligTabSuivant($resultat3);
                //recuperation des trafics pna
		$requete4 = "select sum(trafpna)
                        from dotc,pna,entite
                            where dotc.ville='".$ligne2[1]."'
                                and trafpna<>0
                                and dotc.ville=entite.centite
                                and dotc.cdotc=entite.cedotc
                                    group by ville";
		$resultat4 = $bd->execRequete ($requete4);
		$ligne4=$bd->ligTabSuivant($resultat4);
                //recuperation des trafics colis
                $requete5 = "select sum(trafcolis)
                                    from colis,entite,dotc
                                         where dotc.ville='".$ligne2[1]."'
                                         and trafcolis<>0
                                         and centite=ceentite
                                         and dotc.cdotc=entite.cedotc
                                             group by ville";
		$resultat5 = $bd->execRequete ($requete5);
		$ligne5=$bd->ligTabSuivant($resultat5);

		$requete3b = "select distinct sum(trafsyci)
                                from syci,dotc
                                    where cedotc=cdotc
                                    and trafsyci<>0
                                       and cedotc<>99";
		$resultat3b = $bd->execRequete ($requete3b);
		$ligne3b=$bd->ligTabSuivant($resultat3b);
                //recuperation des trafics pna
		$requete4b = "select sum(trafpna)
                        from pna,entite
                            where trafpna<>0
                                and centite=ceentite and cedotc<>99 ";
		$resultat4b = $bd->execRequete ($requete4b);
		$ligne4b=$bd->ligTabSuivant($resultat4b);
                //recuperation des trafics colis
                $requete5b = "select sum(trafcolis)
                                    from colis,entite
                                    where trafcolis<>0
                                         and centite=ceentite and cedotc<>99 ";
		$resultat5b = $bd->execRequete ($requete5b);
		$ligne5b=$bd->ligTabSuivant($resultat5b);
                $totaltraf=$ligne3b[0]+$ligne4b[0]+$ligne5b[0];
                
    //recuperation des couts unitaires
               //totalit� des charges distribution sur les dotc
                $reqchar2="select distinct sum(charcaa)
                                  from charcaa,entite,processus,caa,sousprocessus
                                       where charcaa.ceentite=entite.centite and cecaa=ccaa
                                       and cssprocess=cessprocess and cprocess=ceprocess and libprocess like'TI'
                                             and cedotc<>99";
                 $reschar2 = $bd->execRequete ($reqchar2);
                 $ligchar2=$bd->ligTabSuivant($reschar2);
                 $totalchar=$ligchar2[0];
                 $totalcunit=$totalchar/$totaltraf;
                 //charges par ville
                 $reqchar="select distinct sum(charcaa)
                                  from dotc,charcaa,entite,caa,sousprocessus,processus
                                       where dotc.ville='".$ligne2[1]."' and cecaa=ccaa
                                       and cssprocess=cessprocess and cprocess=ceprocess and libprocess like'TI'
                                             and charcaa.ceentite=entite.centite
                                             and entite.cedotc=dotc.cdotc
                                                 group by ville ";
                 $reschar = $bd->execRequete ($reqchar);
                 $ligchar=$bd->ligTabSuivant($reschar);
    //Aspect visuel des champs
		tbldebutligne("A0");
		tblcellule($ligne2[1]);//ville
		tblcellule("Pas encore en fonction");//rang
		$trafmoyeti=$ligne3[0]+$ligne3[1]+$ligne4[0]+$ligne5[0];
		if ($trafmoyeti<>0 and $totalcunit<>0)
		{
			tblcellule(number_format(((($ligchar[0]/($ligne3[0]+$ligne3[1]+$ligne4[0]+$ligne5[0]))-$totalcunit)/$totalcunit)*100,2,',','')." %");//ecart moyenne
		}
		else
		{
			tblcellule("Absence de trafic");
		}
		$trafcouti=$ligne3[0]+$ligne3[1]+$ligne4[0]+$ligne5[0];
		if ($trafcouti<>0)
		{
			tblcellule(number_format($ligchar[0]/($ligne3[0]+$ligne3[1]+$ligne4[0]+$ligne5[0]),5,',',' '));//couts unitaires
		}
		else
		{
			tblcellule("Absence de trafic");
		}
		tblcellule("");
        tblcellule(number_format($ligchar[0],0,' ',' '));//donn�es financi�res avant retraitement
        tblcellule("Pas encore en fonction");//donn�es financi�res apr�s retraitement
 		tblfinligne();
	}
	tbldebutligne("A0");
	tblcellule("");
	tblfinligne();
	tbldebutligne("A0");
	tblcellule("Total DOTC");
	tblcellule("");
	tblcellule("");
	tblcellule(number_format($totalcunit,5,',',' '));
	tblcellule(number_format($totaltraf,0,' ',' '));
	tblcellule(number_format($totalchar,0,' ',' '));
	tblcellule("");
	tblfinligne();
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();
}

//------------------------------------------------------------------VISION DOTC---------------------------------------------------------------

    //Pages cout unitaire Dotc
function formcudotc($dotc=99,$ancu=2009,$percu="rien")
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","majcudotc");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array("Charges"=>"chargedotc.php",
					 "Trafics"=>"traficdotc.php",
					 "Co�ts unitaires"=>"cunitdotc.php");

		echo "<CENTER><H1>".'Coûts DOTC'."</H1></CENTER>\n";



	//champlist deroulant annee periode entite
	$requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
	$resultat2 = $bd->execRequete ($requete2);
	$nomb_ligne2=$bd->nbLigne($resultat2);
	$listannee[0]="";
	   for ($j=0;$j<$nomb_ligne2;$j++)
	    {
		   $ligne2=$bd->objetSuivant($resultat2);
		   $listannee[$ligne2->can]=$ligne2->liban;
	    }
	$requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
	$resultat = $bd->execRequete ($requete);
	$nomb_ligne=$bd->nbLigne($resultat);
	$listPerio[0]="";
	   for ($j=0;$j<$nomb_ligne;$j++)
	    {
		   $ligne=$bd->objetSuivant($resultat);
	       $listPerio[$ligne->cperi]=$ligne->libperi;
	    }
	$requete1 = "SELECT distinct cdotc,libdotc,codep FROM dotc order by libdotc ";
    $resultat1 = $bd->execRequete ($requete1);
    $nomb_ligne1=$bd->nbLigne($resultat1);
	$listdo[0]="";
       for ($j=0;$j<$nomb_ligne1;$j++)
        {
           $ligne1=$bd->objetSuivant($resultat1);
           $listdo[$ligne1->cdotc]=$ligne1->libdotc;
        }
	switch ($_SESSION["usefonc"])
	{
		case 1;case 3;
			$Form->debuttable();
			if($dotc<>99)
			{
				$reqdotc="select cdotc from dotc where codep='".$dotc."'";
				$resdotc = $bd->execRequete($reqdotc);
				$coddotc=$bd->ligTabSuivant($resdotc);
				$Form->champliste ("Dotc :", "dotc", $coddotc[0], 1, $listdo);
			}
			else
			{
				$Form->champliste ("Dotc :", "dotc", "", 1, $listdo);
			}
		break;
		case 2:
			$Form->debuttable();
			break;
	}
	if($ancu<>2009)
	{
		$reqcodan="select can from annee where liban='".$ancu."'";
		$rescodan = $bd->execRequete($reqcodan);
		$codannee=$bd->ligTabSuivant($rescodan[0]);
		$Form->champliste ("Année :", "ann", $codannee[0], 1, $listannee);
		$ancour=$codannee[0];
		$GLOBALS["ancour"];
	}
	else
	{
		$Form->champliste ("Année :", "ann", "", 1, $listannee);
	}
	if($percu<>"rien")
	{
		$reqcodper="select cperi from periode where libperi='".$percu."'";
		$rescodper = $bd->execRequete($reqcodper);
		$codperiode=$bd->ligTabSuivant($rescodper);
		$Form->champliste ("Pèriode :", "perio", $codperiode[0], 1, $listPerio);
		$percour=$codperiode[0];
		$GLOBALS["percour"];
	}
	else
	{
		$Form->champliste ("Pèriode :", "perio", "", 1, $listPerio);
	}
//	$Form->champliste ("Ann�e :", "ann", "", 1, $listannee);
//	$Form->champliste ("P�riode :", "perio", "", 1, $listPerio);
	$Form->champvalider ("Valider", "valider");
	//$Form->fin();

	$requete3 = "select distinct cordaffich,ordscrib,libprocess,sum(charcaa)
                   from domaine,processus,sousprocessus,caa,charcaa,periode,mois,dotc,entite
		             where cordaffich<>'0' and cdom=cedom and cprocess=ceprocess and anchcaa='".$ancu."'
		                 and cperi='".$codperiode[0]."' and cperi=ceperi and codmoi=moichcaa
		                 and cdotc='".$coddotc[0]."' and cdotc=cedotc and ceentite=centite
                 			and cssprocess=cessprocess and ccaa=cecaa
                 				group by cordaffich
                 					order by ordscrib,cordaffich";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->nbLigne($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");

	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Charges brutes"));
	$Form->ajoutTexte (tblentete("Retraitement"));
	$Form->ajoutTexte (tblentete("Charges nettes"));
	$Form->ajoutTexte (tblfinligne());
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");
		tblcellule($ligne3[2]);
		tblcellule(number_format($ligne3[3],0 , ' ' ,  ' ' ));
		tblcellule("Pas encore en fonction");
		tblcellule(number_format($ligne3[3],0 , ' ' ,  ' ' ));//Plus les retraitements
		tblfinligne();
		tbldebutligne(MPAP);
		tblcellule(" MPAP");
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
    }
function formchargedotc($dotc)
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","suivconnex");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array("Charges"=>"chargedotc.php",
					"Trafics"=>"traficdotc.php",
					"Coûts unitaires"=>"cunitdotc.php"
					);
	$ancour=$_SESSION['anc'];
	$percour=$_SESSION['perc'];
	$dotcr=$_SESSION['dotcr'];
	$libdotc=$_SESSION['libdotc'];

	echo "<CENTER><H1>".'Charges DOTC'."</H1></CENTER>\n";
	echo "<CENTER><H3>".'Année traitée : '.$ancour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'Pèriode traitée : '.$percour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'Dotc traitée : '.$dotcr." - ".$libdotc."</H3></CENTER>\n";


	$requete3 = "select distinct cordaffich,ordscrib,libprocess,sum(charcaa)
                 	from domaine,processus,sousprocessus,caa,charcaa,periode,mois,dotc,entite
		                 where cordaffich<>'0' and cdom=cedom and cprocess=ceprocess
		                 	and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
                 			and cssprocess=cessprocess and ccaa=cecaa
                 			and libperi='".$percour."' and cperi=ceperi
                 			and anchcaa='".$ancour."' and moichcaa=codmoi
                 				group by cordaffich
                 					order by ordscrib,cordaffich";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=mysql_num_rows($resultat3);
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Charges brutes"));
	$Form->ajoutTexte (tblentete("Retraitement"));
	$Form->ajoutTexte (tblentete("Charges nettes"));
	$Form->ajoutTexte (tblfinligne());
	//boucle renseignant les cellules
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");
		tblcellule($ligne3[2]);
		tblcellule(number_format($ligne3[3],0 , ' ' ,  ' ' ));
		tblcellule("NON FONCTIONNEL");
		tblcellule(number_format($ligne3[3],0 , ' ' ,  ' ' ));//PLUS LES RETRAITEMENTS
		tblfinligne();
		tbldebutligne(MPAP);
		tblcellule(" MPAP");
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
function formtraficdotc($dotc)
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","suivconnex");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array("Charges"=>"chargedotc.php",
					"Trafics"=>"traficdotc.php",
					"Co�ts unitaires"=>"cunitdotc.php"
					);
	//	echo "Co&ucirc;ts unitaires DOTC ". $dotc;

	$requete3 = "select distinct cordaffich,ordscrib,libprocess
                            from domaine,processus
                                 where cordaffich<>'0'
                                       and cdom=cedom
                                           group by cordaffich
                                                 order by ordscrib,cordaffich";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=mysql_num_rows($resultat3);
	$ancour=$_SESSION['anc'];
	$percour=$_SESSION['perc'];
	$dotcr=$_SESSION['dotcr'];
	$libdotc=$_SESSION['libdotc'];
	echo "<CENTER><H1>".'Trafics DOTC'."</H1></CENTER>\n";
	echo "<CENTER><H3>".'Ann�e trait�e : '.$ancour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'P�riode trait�e : '.$percour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'Dotc trait�e : '.$dotcr." - ".$libdotc."</H3></CENTER>\n";

	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Trafic courrier"));
	$Form->ajoutTexte (tblentete("Trafic IP")); //pna
	$Form->ajoutTexte (tblentete("Trafic colis"));
	$Form->ajoutTexte (tblentete("Trafic total"));
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne(A0);

		switch ($ligne3[2])
		{
			case "DISTRIBUTION":
				tblcellule($ligne3[2]." SYCI");
			/*	$requete4 = "select sum(trafsyci)from syci,periode,dotc where ansyc='".$ancour."' and libperi='".$percour."' and cperi=trimsyc and codep='".$dotcr."'";
				$resultat4 = $bd->execRequete ($requete4);
				$ligne4=$bd->ligTabSuivant($resultat4);*/

				if ($percour="annuel")
				{
					$requete4 = "select sum(trafsyci)from syci,dotc where ansyc='".$ancour."' and cdotc=cedotc and codep='".$dotcr."'";
					$resultat4 = $bd->execRequete ($requete4);
					$ligne4=$bd->ligTabSuivant($resultat4);
				}
				else
				{
					$requete4 = "select sum(trafsyci)from syci,periode,dotc where ansyc='".$ancour."' and libperi='".$percour."' and trimsyc=cperi and cdotc=cedotc and codep='".$dotcr."'";
					$resultat4 = $bd->execRequete ($requete4);
					$ligne4=$bd->ligTabSuivant($resultat4);
				}
				$requete5 = "select sum(trafcolis)from colis,periode,mois,dotc,entite where ancol='".$ancour."'and cdotc=cedotc and centite=ceentite and libperi='".$percour."' and cperi=ceperi and codmoi=moicol and codep='".$dotcr."'";
				$resultat5 = $bd->execRequete ($requete5);
				$ligne5=$bd->ligTabSuivant($resultat5);
				$requete6 = "select sum(trafpna)from pna,periode,mois,dotc,entite where anpna='".$ancour."' and cdotc=cedotc and centite=ceentite and libperi='".$percour."' and cperi=ceperi and codmoi=moipna and codep='".$dotcr."'";
				$resultat6 = $bd->execRequete ($requete6);
				$ligne6=$bd->ligTabSuivant($resultat6);
				tblcellule(number_format($ligne4[0],0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne6[0],0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne5[0],0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne4[0]+ $ligne6[0]+ $ligne5[0],0 , ' ' ,  ' ' ));
				tblfinligne();
				tbldebutligne(MPAP);
				tblcellule("MPAP");
				tblfinligne();
				tbldebutligne(A0);
				tblcellule($ligne3[2]." PILDI");
				$requete4 = "select sum(trafdistribm),sum(trafdistribc)from pildi,periode,mois,dotc,entite where anpi='".$ancour."' and cdotc=cedotc and centite=ceentite and codep='".$dotcr."' and libperi='".$percour."' and cperi=ceperi and codmoi=moipi";
				$resultat4 = $bd->execRequete ($requete4);
				$ligne4=$bd->ligTabSuivant($resultat4);
				tblcellule(number_format($ligne4[0] + $ligne4[1],0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne6[0],0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne5[0],0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne4[0] + $ligne4[1]+ $ligne6[0]+ $ligne5[0],0 , ' ' ,  ' ' ));
				break;
			case "TRAITEMENT":
				tblcellule($ligne3[2]);
				$requete4 = "select sum(traftraitd),sum(traftraita)from syspeo,periode,mois,dotc,entite where anpeo='".$ancour."' and cdotc=cedotc and centite=ceentite and codep='".$dotcr."' and libperi='".$percour."' and cperi=ceperi and codmoi=moipeo";
				$resultat4 = $bd->execRequete ($requete4);
				$ligne4=$bd->ligTabSuivant($resultat4);
				tblcellule(number_format($ligne4[0] + $ligne4[1],0 , ' ' ,  ' ' ));
				tblcellule("");
				tblcellule("");
				tblcellule(number_format($ligne4[0] + $ligne4[1],0 , ' ' ,  ' ' ));
				break;
			case "CONCENTRATION":
				tblcellule($ligne3[2]);
				$requete4 = "select sum(trafconc)from syspeo,periode,mois,dotc,entite where anpeo='".$ancour."'and cdotc=cedotc and centite=ceentite and codep='".$dotcr."' and libperi='".$percour."' and cperi=ceperi and codmoi=moipeo";
				$resultat4 = $bd->execRequete ($requete4);
				$ligne4=$bd->ligTabSuivant($resultat4);
				$requete5 = "select sum(trafconc)from pildi,mois,periode,dotc,entite where anpi='".$ancour."' and cdotc=cedotc and centite=ceentite and codep='".$dotcr."' and libperi='".$percour."' and cperi=ceperi and codmoi=moipi";
				$resultat5 = $bd->execRequete ($requete5);
				$ligne5=$bd->ligTabSuivant($resultat5);
				tblcellule(number_format($ligne5[0] + $ligne4[0],0 , ' ' ,  ' ' ));
				tblcellule("");
				tblcellule("");
				tblcellule(number_format($ligne5[0] + $ligne4[0],0 , ' ' ,  ' ' ));
				break;
			case "TI":
				tblcellule($ligne3[2]);
				$requete4 = "select sum(trafdistribm),sum(trafdistribc)from pildi,mois,periode,dotc,entite where anpi='".$ancour."' and cdotc=cedotc and centite=ceentite and codep='".$dotcr."' and libperi='".$percour."' and cperi=ceperi and codmoi=moipi";
				$resultat4 = $bd->execRequete ($requete4);
				$ligne4=$bd->ligTabSuivant($resultat4);
				$requete5 = "select sum(trafcolis)from colis,mois,periode,dotc,entite where ancol='".$ancour."' and codep='".$dotcr."' and cdotc=cedotc and centite=ceentite and libperi='".$percour."' and cperi=ceperi and codmoi=moicol";
				$resultat5 = $bd->execRequete ($requete5);
				$ligne5=$bd->ligTabSuivant($resultat5);
				tblcellule(number_format($ligne4[0] + $ligne4[1],0 , ' ' ,  ' ' ));
				tblcellule("");
				tblcellule(number_format($ligne5[0],0 , ' ' ,  ' ' ));    //test separateur millier
				tblcellule(number_format($ligne4[0] + $ligne4[1]+ $ligne5[0],0 , ' ' ,  ' ' ));
				break;
			default:	tblcellule($ligne3[2]);
				break;
		}
		tblfinligne();
		tbldebutligne(MPAP);
		tblcellule("MPAP");
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
    }
function formcunitdotc($dotc)
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","suivconnex");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array("Charges"=>"chargedotc.php",
				"Trafics"=>"traficdotc.php",
				"Co�ts unitaires"=>"cunitdotc.php"
				);
	//recuperation des processus
	$requete3 = "select distinct cordaffich,ordscrib,libprocess
                            from domaine,processus
                                 where cordaffich<>'0'and cdom=cedom
                                           group by cordaffich
                                                 order by ordscrib,cordaffich";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=mysql_num_rows($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");

	$ancour=$_SESSION['anc'];
	$percour=$_SESSION['perc'];
	$dotcr=$_SESSION['dotcr'];
	$libdotc=$_SESSION['libdotc'];
	echo "<CENTER><H1>".'Couts unitaires DOTC'."</H1></CENTER>\n";
	echo "<CENTER><H3>".'Ann�e trait�e : '.$ancour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'P�riode trait�e : '.$percour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'Dotc trait�e : '.$dotcr." - ".$libdotc."</H3></CENTER>\n";

	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Cout unitaire direct"));
	$Form->ajoutTexte (tblentete("Cout unitaire indirect"));
	$Form->ajoutTexte (tblfinligne());
	//boucle renseignant les cellules
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne(A0);
		switch ($ligne3[2])
		{
			case "DISTRIBUTION":
				tblcellule($ligne3[2]." SYCI");

				//charges pour le processus distribution
				$reqchar = "select distinct sum(charcaa)
                 	from processus,sousprocessus,caa,charcaa,periode,mois,dotc,entite
		                 where  cprocess=ceprocess and libprocess='DISTRIBUTION'
		                 	and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
                 			and cssprocess=cessprocess and ccaa=cecaa
                 			and libperi='".$percour."' and cperi=ceperi
                 			and anchcaa='".$ancour."' and moichcaa=codmoi
                 				group by cprocess";
				$resuchar = $bd->execRequete ($reqchar);
				$ligchar=$bd->ligTabSuivant($resuchar);

				//distribution syci
				if ($percour="annuel")
				{
					$requete4 = "select sum(trafsyci)from dotc, syci where ansyc='".$ancour."' and cdotc=cedotc and codep='".$dotcr."'";
					$resultat4 = $bd->execRequete ($requete4);
					$ligne4=$bd->ligTabSuivant($resultat4);
				}
				else
				{
					$requete4 = "select sum(trafsyci)from dotc,syci,periode where ansyc='".$ancour."'  and cdotc=cedotc  libperi='".$percour."' and trimsyc=cperi and codep='".$dotcr."'";
					$resultat4 = $bd->execRequete ($requete4);
					$ligne4=$bd->ligTabSuivant($resultat4);
				}

				//distribution pildi
				$requete7 = "select sum(trafdistribm),sum(trafdistribc)from dotc,pildi,periode,mois,entite where anpi='".$ancour."' and ceentite=centite and cdotc=cedotc  and libperi='".$percour."' and cperi=ceperi and codmoi=moipi and codep='".$dotcr."' ";
				$resultat7 = $bd->execRequete ($requete7);
				$ligne7=$bd->ligTabSuivant($resultat7);

				//distribution colis
				$requete5 = "select sum(trafcolis)from dotc,colis,periode,mois,entite where ancol='".$ancour."'and cdotc=entite.cedotc and centite=ceentite and libperi='".$percour."' and cperi=ceperi and codmoi=moicol and codep='".$dotcr."'";
				$resultat5 = $bd->execRequete ($requete5);
				$ligne5=$bd->ligTabSuivant($resultat5);

				//distribution pna
				$requete6 = "select sum(trafpna)from dotc,pna,periode,mois,entite where anpna='".$ancour."'and cdotc=entite.cedotc and centite=ceentite and  libperi='".$percour."' and cperi=ceperi and codmoi=moipna and codep='".$dotcr."'";
				$resultat6 = $bd->execRequete ($requete6);
				$ligne6=$bd->ligTabSuivant($resultat6);
				$trafSyci=$ligne4[0]+$ligne5[0]+$ligne6[0];
				if($trafSyci<>0)
				{
					tblcellule(number_format($ligchar[0]/$trafSyci,6 , ',' ,  ' ' ));
				}
				else
				{
					tblcellule("Absence de trafic");
				}
				tblcellule("Pas encore en fonction");//cout unitaire indirect
				tblfinligne();
				tbldebutligne(MPAP);
				tblcellule("MPAP");
				tblfinligne();
				tbldebutligne(A0);
				tblcellule($ligne3[2]." PILDI"); //processus
				$trafPildi=$ligne7[0]+$ligne7[1]+$ligne5[0]+$ligne6[0];
				if($trafPildi<>0)
				{
					tblcellule(number_format($ligchar[0]/$trafPildi,6 , ',' ,  ' ' ));
				}
				else
				{
					tblcellule("Absence de trafic");
				}
				tblcellule("Pas encore en fonction");//cout unitaire indirect
				break;
			case "TRAITEMENT":
				tblcellule($ligne3[2]);
				$reqchar="select distinct sum(charcaa)
                 	from processus,sousprocessus,caa,charcaa,periode,mois,dotc,entite
		                 where  cprocess=ceprocess and libprocess='TRAITEMENT'
		                 	and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
                 			and cssprocess=cessprocess and ccaa=cecaa
                 			and libperi='".$percour."' and cperi=ceperi
                 			and anchcaa='".$ancour."' and moichcaa=codmoi
                 				group by cprocess";
				$reschar = $bd->execRequete ($reqchar);
				$ligchar=$bd->ligTabSuivant($reschar);
				$requete8 = "select sum(traftraitd),sum(traftraita)from dotc,syspeo,periode,mois,entite where anpeo='".$ancour."' and cdotc=cedotc and ceentite=centite and codep='".$dotcr."' and libperi='".$percour."' and cperi=ceperi and codmoi=moipeo";
				$resultat8 = $bd->execRequete ($requete8);
				$ligne8=$bd->ligTabSuivant($resultat8);
				$traftrait=$ligne8[0] + $ligne8[1];
				if($traftrait<>0)
				{
					tblcellule(number_format($ligchar[0]/($traftrait),6 , ',' ,  ' ' ));
				}
				else
				{
					tblcellule("Absence de trafic");
				}
				tblcellule("Pas encore en fonction");//cout unitaire indirect
				break;
			case "CONCENTRATION":
				tblcellule($ligne3[2]);
				$reqchar="select distinct sum(charcaa)
                 	from processus,sousprocessus,caa,charcaa,periode,mois,dotc,entite
		                 where  cprocess=ceprocess and libprocess='CONCENTRATION'
		                 	and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
                 			and cssprocess=cessprocess and ccaa=cecaa
                 			and libperi='".$percour."' and cperi=ceperi
                 			and anchcaa='".$ancour."' and moichcaa=codmoi
                 				group by cprocess";
				$reschar = $bd->execRequete ($reqchar);
				$ligchar=$bd->ligTabSuivant($reschar);
				$requete9 = "select sum(trafconc)from syspeo,periode,mois,entite,dotc where anpeo='".$ancour."'  and codep='".$dotcr."' and cdotc=cedotc and ceentite=centite and libperi='".$percour."' and cperi=ceperi and codmoi=moipeo";
				$resultat9 = $bd->execRequete ($requete9);
				$ligne9=$bd->ligTabSuivant($resultat9);
				$requete9b = "select sum(trafconc)from pildi,mois,periode,entite,dotc where anpi='".$ancour."'and  codep='".$dotcr."' and cdotc=cedotc and ceentite=centite and libperi='".$percour."' and cperi=ceperi and codmoi=moipi";
				$resultat9b = $bd->execRequete ($requete9b);
				$ligne9b=$bd->ligTabSuivant($resultat9b);
				$trafconc=$ligne9[0] + $ligne9b[0];
				if($trafconc<>0)
				{
					tblcellule(number_format($ligchar[0]/($trafconc),6 , ',' , ' ' ));
				}
				else
				{
					tblcellule("Absence de trafic");
				}
				tblcellule("Pas encore en fonction");//cout unitaire indirect
				break;
			case "TI":
				tblcellule($ligne3[2]);
				$reqchar="select distinct sum(charcaa)
                 	from processus,sousprocessus,caa,charcaa,periode,mois,dotc,entite
		                 where  cprocess=ceprocess and libprocess='TI'
		                 	and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
                 			and cssprocess=cessprocess and ccaa=cecaa
                 			and libperi='".$percour."' and cperi=ceperi
                 			and anchcaa='".$ancour."' and moichcaa=codmoi
                 				group by cprocess";
				$reschar = $bd->execRequete ($reqchar);
				$ligchar=$bd->ligTabSuivant($reschar);

				$requete10 = "select sum(trafdistribm),sum(trafdistribc)from pildi,mois,periode,entite,dotc where anpi='".$ancour."' and  codep='".$dotcr."' and cdotc=cedotc and ceentite=centite and libperi='".$percour."' and cperi=ceperi and codmoi=moipi";
				$resultat10 = $bd->execRequete ($requete10);
				$ligne10=$bd->ligTabSuivant($resultat10);

				$requete10b = "select sum(trafcolis)from colis,mois,periode,entite,dotc where ancol='".$ancour."'and  codep='".$dotcr."' and cdotc=cedotc and  ceentite=centite and libperi='".$percour."' and cperi=ceperi and codmoi=moicol";
				$resultat10b = $bd->execRequete ($requete10b);
				$ligne10b=$bd->ligTabSuivant($resultat10b);

				$trafTI=$ligne10[0] +$ligne10[1] + $ligne10b[0];
				if($trafTI<>0)
				{
					tblcellule(number_format($ligchar[0]/($trafTI),6 , ',' ,  ' ' ));
				}
				else
				{
					tblcellule("Absence de trafic");
				}
				tblcellule("Pas encore en fonction");//cout unitaire indirect
				break;
			default:
				tblcellule($ligne3[2]);
		}
		tblfinligne();
		tbldebutligne(MPAP);
		tblcellule("MPAP");
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
    }
    //Page de retraitement Dotc
function formretrdotc($dotc=99,$ancu=2009,$percu="rien")
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	//$menuclien=array( "Charges"=>"charge.php", "Trafics"=>"trafic.php", "Co�ts unitaires"=>"cunit.php");
	echo "<CENTER><H1>".'Retraitements DOTC'."</H1></CENTER>\n";

		//champlist deroulant annee periode entite
		$requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
		$resultat2 = $bd->execRequete ($requete2);
		$nomb_ligne2=$bd->nbLigne($resultat2);
		$listannee[0]="";
		   for ($j=0;$j<$nomb_ligne2;$j++)
		    {
			   $ligne2=$bd->objetSuivant($resultat2);
			   $listannee[$ligne2->can]=$ligne2->liban;
		    }
		$requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
		$resultat = $bd->execRequete ($requete);
		$nomb_ligne=$bd->nbLigne($resultat);
		$listPerio[0]="";
		   for ($j=0;$j<$nomb_ligne;$j++)
		    {
			   $ligne=$bd->objetSuivant($resultat);
		       $listPerio[$ligne->cperi]=$ligne->libperi;
		    }
		$requete1 = "SELECT distinct cdotc,libdotc,codep FROM dotc order by libdotc ";
	    $resultat1 = $bd->execRequete ($requete1);
	    $nomb_ligne1=$bd->nbLigne($resultat1);
		$listdo[0]="";
	       for ($j=0;$j<$nomb_ligne1;$j++)
	        {
	           $ligne1=$bd->objetSuivant($resultat1);
	           $listdo[$ligne1->cdotc]=$ligne1->libdotc;
	        }
		switch ($_SESSION["usefonc"])
		{
			case 1;case 3;
				$Form->debuttable();
				if($dotc<>99)
				{
					$reqdotc="select cdotc from dotc where codep='".$dotc."'";
					$resdotc = $bd->execRequete($reqdotc);
					$coddotc=$bd->ligTabSuivant($resdotc[0]);
					$Form->champliste ("Dotc :", "dotc", $coddotc, 1, $listdo);
				}
				else
				{
					$Form->champliste ("Dotc :", "dotc", "", 1, $listdo);
				}
			break;
			case 2:
				$Form->debuttable();
				break;
		}
		if($ancu<>2009)
		{
			$reqcodan="select can from annee where liban='".$ancu."'";
			$rescodan = $bd->execRequete($reqcodan);
			$codannee=$bd->ligTabSuivant($rescodan[0]);
			$Form->champliste ("Année :", "ann", $codannee, 1, $listannee);
			//$ancour=$codannee;
			$GLOBALS[ancour];
		}
		else
		{
			$Form->champliste ("Année :", "ann", "", 1, $listannee);
		}
		if($percu<>"rien")
		{
			$reqcodper="select cperi from periode where libperi='".$percu."'";
			$rescodper = $bd->execRequete($reqcodper);
			$codperiode=$bd->ligTabSuivant($rescodper[0]);
			$Form->champliste ("Pèriode :", "perio", $codperiode, 1, $listPerio);
			//$percour=$codperiode;
			$GLOBALS[percour];
		}
		else
		{
			$Form->champliste ("Pèriode :", "perio", "", 1, $listPerio);
		}
	//	$Form->champliste ("Ann�e :", "ann", "", 1, $listannee);
	//	$Form->champliste ("P�riode :", "perio", "", 1, $listPerio);
		$Form->champvalider ("Valider", "valider");
		//$Form->fin();

	//requete sql alimentant tableau
	$requete3 = "select distinct cordaffich,ordscrib,libprocess
                            from domaine,processus
                                 where cordaffich<>'0'
                                       and cdom=cedom
                                           group by cordaffich
                                                 order by ordscrib,cordaffich";
		
  	$resultat3 = $bd->execRequete ($requete3);
  	$nomb_ligne3=$bd->nbLigne($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");

	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("SP88 - IMMOBILIER"));
	$Form->ajoutTexte (tblentete("SP89 - VEHICULE"));
	$Form->ajoutTexte (tblentete("SP90 - CAA SOCIAL"));
	$Form->ajoutTexte (tblentete("SP91 - CAA RH"));
	$Form->ajoutTexte (tblentete("TOTAL TRANSVERSE"));
	$Form->ajoutTexte (tblfinligne());
	
	$dotcr=$_SESSION['dotcr'];
	//$libdotc=$_SESSION['libdotc'];

	$reqpr02 = "select  sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,dotc,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and ceprocess=cprocess and codprocess='PR02'";
	$respr02 = $bd->execRequete ($reqpr02);
	$PR02=$bd->ligTabSuivant($respr02);

	$reqpr04 = "select  sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,dotc,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and ceprocess=cprocess and codprocess='PR04'";
	$respr04 = $bd->execRequete ($reqpr04);
	$PR04=$bd->ligTabSuivant($respr04);

	$reqdist = "select  sum(charcaa)
								from charcaa,caa,mois,periode,dotc,entite
									where cecaa=ccaa and codcaa='775'
										and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
										and anchcaa='".$ancu."' and cperi='".$codperiode."'
										and cperi=ceperi and codmoi=moichcaa";
	$resdist = $bd->execRequete ($reqdist);
	$ligne=$bd->ligTabSuivant($resdist);

	$reqdist2 = "select  sum(charcaa)
								from charcaa,caa,mois,periode,dotc,entite
									where cecaa=ccaa and codcaa='750'
											and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
											and anchcaa='".$ancu."' and cperi='".$codperiode."'
											and cperi=ceperi and codmoi=moichcaa";
	$resdist2 = $bd->execRequete ($reqdist2);
	$ligne2=$bd->ligTabSuivant($resdist2);

	$reqdist3 = "select  sum(charcaa)
								from charcaa,sousprocessus,caa,mois,periode,dotc,entite
									where cecaa=ccaa and cessprocess=cssprocess
										and anchcaa='".$ancu."' and cperi='".$codperiode."'
										and cperi=ceperi and codmoi=moichcaa
										and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
										and codssprocess='SP90'";
	$resdist3 = $bd->execRequete ($reqdist3);
	$SP90=$bd->ligTabSuivant($resdist3);

	$reqdist4 = "select  sum(charcaa)
								from charcaa,sousprocessus,caa,mois,periode,dotc,entite
									where cecaa=ccaa and cessprocess=cssprocess
										and anchcaa='".$ancu."' and cperi='".$codperiode."'
										and cperi=ceperi and codmoi=moichcaa
										and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
										and codssprocess='SP91'";
	$resdist4 = $bd->execRequete ($reqdist4);
	$SP91=$bd->ligTabSuivant($resdist4);

	$Form->debuttable();
	
	tblcellule("CHARGES A RETRAITER");
	tblcellule(number_format(-$ligne2[0],0 , ' ' ,  ' ' ));
	tblcellule(number_format(-$ligne2[0],0 , ' ' ,  ' ' ));
	tblcellule(number_format(-$SP90[0],0 , ' ' ,  ' ' ));
	tblcellule(number_format(-$SP91[0],0 , ' ' ,  ' ' ));
	tblcellule(number_format(-$ligne[0]-$ligne2[0]-$SP90[0]-$SP91[0],0 , ' ' ,  ' ' ));
	$Form->ajoutTexte (tblfinligne());
	
	$reqcharge="select sum(charcaa)
					 from charcaa,caa,sousprocessus,processus,mois,periode,dotc,entite
					 	where libprocess<>'CAA TRANSVERSES'
								and anchcaa='".$ancu."' and cperi='".$codperiode."'
								and cperi=ceperi and codmoi=moichcaa
								and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
					 			and cecaa=ccaa and cessprocess=cssprocess and ceprocess=cprocess";
	$rescharge = $bd->execRequete ($reqcharge);
	$charges=$bd->ligTabSuivant($rescharge[0]);
	//echo "<CENTER><H3>".'charge : '.$charges."</H3></CENTER>\n";
	$totimm=-$ligne[0];
	$totvehi=-$ligne2[0];
	$totsocial=-$SP90[0];
	$totrh=-$SP91[0];

	for ($j=0;$j<$nomb_ligne3;$j++)
	{

		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");
		tblcellule($ligne3[2]);
		switch ($ligne3[2])
		{
			case "DISTRIBUTION":
			/*	$reqimmo="select sum(tchar.charcaa) from processus,tca,entite,charcaa,caa,sousprocessus,charcaa as tchar,caa as tcaa
							where tcaa.codcaa='775' and tchar.cecaa=tcaa.ccaa
								and centite=tchar.ceentite and cetca=ctca and charcaa.cecaa=caa.ccaa
								and ceprocess=cprocess and caa.cessprocess=cssprocess
								and cprocprinc=cprocess and libprocess='".$ligne3[2]."'";
				$resimmo = $bd->execRequete ($reqimmo);
				$immo=mysql_result($resimmo,0,0);
				echo "<CENTER><H3>".'immo : '.$immo."</H3></CENTER>\n";
			*/
				$immdist=$ligne[0]*($PR04[0]/$charges);
				$totimm=$totimm+$immdist;
				tblcellule(number_format($immdist,3 , ' ' ,  ' '));

				$vehidist=$ligne2[0]*($PR04[0]/($PR02[0]+$PR04[0]));
				$totvehi=$totvehi+$vehidist;
				tblcellule(number_format($vehidist,3 , ' ' ,  ' '));
				
				
				/*select distinct cordaffich,ordscrib,libprocess,sum(charcaa)
			                 	from domaine,processus,sousprocessus,caa,charcaa,periode,mois,dotc,entite
					                 where cordaffich<>'0' and cdom=cedom and cprocess=ceprocess
					                 	and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
			                 			and cssprocess=cessprocess and ccaa=cecaa
			                 			and libperi='".$percour."' and cperi=ceperi
			                 			and anchcaa='".$ancour."' and moichcaa=codmoi
			                 				group by cordaffich
			                 					order by ordscrib,cordaffich*/
				

				$reqdist = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,dotc,entite
								where cecaa=ccaa and cessprocess=cssprocess 
									and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resdist = $bd->execRequete ($reqdist);
				$dist=$bd->ligTabSuivant($resdist[0]);

				$socialdist=$SP90[0]*($dist/$charges);
				$totsocial=$totsocial+$socialdist;
				tblcellule(number_format($socialdist,3 , ' ' ,  ' ' ));

				$rhdist=$SP91[0]*($dist/$charges);
				$totrh=$totrh+$rhdist;
				tblcellule(number_format($rhdist,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immdist+$vehidist+$socialdist+$rhdist,3 , ' ' ,  ' '));
				break;
			case "TRAITEMENT":
				$reqtrait = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,dotc,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$restrait = $bd->execRequete ($reqtrait);
				$trait=$bd->ligTabSuivant($restrait[0]);
				$immtrait=$ligne[0]*($trait/$charges);
				$totimm=$totimm+$immtrait;
				tblcellule(number_format($immtrait,3 , ' ' ,  ' '));

				tblcellule("");

				$socialtrait=$SP90[0]*($trait/$charges);
				$totsocial=$totsocial+$socialtrait;
				tblcellule(number_format($socialtrait,3 , ' ' ,  ' ' ));

				$rhtrait=$SP91[0]*($trait/$charges);
				$totrh=$totrh+$rhtrait;
				tblcellule(number_format($rhtrait,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immtrait+$socialtrait+$rhtrait,3 , ' ' ,  ' '));
				break;
			case "CONCENTRATION":
				$immconc=$ligne[0]*($PR02[0]/$charges);
				$totimm=$totimm+$immconc;
				tblcellule(number_format($immconc,3 , ' ' ,  ' '));

				$vehiconc=$ligne2[0]*($PR02[0]/($PR02[0]+$PR04[0]));
				$totvehi=$totvehi+$vehiconc;
				tblcellule(number_format($vehiconc,3 , ' ' ,  ' '));

				$reqconc = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,dotc,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resconc = $bd->execRequete ($reqconc);
				$conc=$bd->ligTabSuivant($resconc[0]);
				$socialconc=$SP90[0]*($conc/$charges);
				$totsocial=$totsocial+$socialconc;
				tblcellule(number_format($socialconc,3 , ' ' ,  ' ' ));

				$rhconc=$SP91[0]*($conc/$charges);
				$totrh=$totrh+$rhconc;
				tblcellule(number_format($rhconc,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immconc+$vehiconc+$socialconc+$rhconc,3 , ' ' ,  ' '));
				break;
			case "CAA CORPORATE":
				tblcellule("");
				tblcellule("");
				tblcellule("");
				tblcellule("");
				tblcellule("");
				break;
			case "SOUTIEN OPERATIONNEL":
				$reqst = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,dotc,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resst = $bd->execRequete ($reqst);
				$st=$bd->ligTabSuivant($resst[0]);

				$immst=$ligne[0]*($st/$charges);
				$totimm=$totimm+$immst;

				tblcellule(number_format($immst,3 , ' ' ,  ' '));
				tblcellule("");

				$socialst=$SP90[0]*($st/$charges);
				$totsocial=$totsocial+$socialst;
				tblcellule(number_format($socialst,3 , ' ' ,  ' ' ));

				$rhst=$SP91[0]*($st/$charges);
				$totrh=$totrh+$rhst;
				tblcellule(number_format($rhst,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immst+$socialst+$rhst,3 , ' ' ,  ' '));
				break;
			case "COMPTA GESTION FINANCE":
				$reqcompta = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,dotc,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$rescompta = $bd->execRequete ($reqcompta);
				$compta=mysql_result($rescompta,0,0);

				$immcompta=$ligne[0]*($compta/$charges);
				$totimm=$totimm+$immcompta;

				tblcellule(number_format($immcompta,3 , ' ' ,  ' '));
				tblcellule("");

				$socialcompta=$SP90[0]*($compta/$charges);
				$totsocial=$totsocial+$socialcompta;
				tblcellule(number_format($socialcompta,3 , ' ' ,  ' ' ));

				$rhcompta=$SP91[0]*($compta/$charges);
				$totrh=$totrh+$rhcompta;
				tblcellule(number_format($rhcompta,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immcompta+$socialcompta+$rhcompta,3 , ' ' ,  ' '));
				break;
			case "MARKETING COMMERCIAL":
				$reqmark = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,dotc,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resmark = $bd->execRequete ($reqmark);
				$mark=mysql_result($resmark,0,0);

				$immmark=$ligne[0]*($mark/$charges);
				$totimm=$totimm+$immmark;

				tblcellule(number_format($immmark,3 , ' ' ,  ' '));
				tblcellule("");

				$socialmark=$SP90[0]*($mark/$charges);
				$totsocial=$totsocial+$socialmark;
				tblcellule(number_format($socialmark,3 , ' ' ,  ' ' ));

				$rhmark=$SP91[0]*($mark/$charges);
				$totrh=$totrh+$rhmark;
				tblcellule(number_format($rhmark,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immmark+$socialmark+$rhmark,3 , ' ' ,  ' '));
				break;
			case "PILOTAGE":
				$reqpil = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,dotc,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$respil = $bd->execRequete ($reqpil);
				$pil=mysql_result($respil,0,0);

				$immpil=$ligne[0]*($pil/$charges);
				$totimm=$totimm+$immpil;

				tblcellule(number_format($immpil,3 , ' ' ,  ' '));
				tblcellule("");

				$socialpil=$SP90[0]*($pil/$charges);
				$totsocial=$totsocial+$socialpil;
				tblcellule(number_format($socialpil,3 , ' ' ,  ' ' ));

				$rhpil=$SP91[0]*($pil/$charges);
				$totrh=$totrh+$rhpil;
				tblcellule(number_format($rhpil,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immpil+$socialpil+$rhpil,3 , ' ' ,  ' '));
				break;
			case "RH":
				$reqrh = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,dotc,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resrh = $bd->execRequete ($reqrh);
				$rh=mysql_result($resrh,0,0);

				$immrh=$ligne[0]*($rh/$charges);
				$totimm=$totimm+$immrh;

				tblcellule(number_format($immrh,3 , ' ' ,  ' '));
				tblcellule("");

				$socialrh=$SP90[0]*($rh/$charges);
				$totsocial=$totsocial+$socialrh;
				tblcellule(number_format($socialrh,3 , ' ' ,  ' ' ));

				$rhrh=$SP91[0]*($rh/$charges);
				$totrh=$totrh+$rhrh;
				tblcellule(number_format($rhrh,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immrh+$socialrh+$rhrh,3 , ' ' ,  ' '));
				break;
			case "SI":
				$reqsi = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,dotc,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$ressi = $bd->execRequete ($reqsi);
				$si=mysql_result($ressi,0,0);

				$immsi=$ligne[0]*($si/$charges);
				$totimm=$totimm+$immsi;

				tblcellule(number_format($immsi,3 , ' ' ,  ' '));
				tblcellule("");

				$socialsi=$SP90[0]*($si/$charges);
				$totsocial=$totsocial+$socialsi;
				tblcellule(number_format($socialsi,3 , ' ' ,  ' ' ));

				$rhsi=$SP91[0]*($si/$charges);
				$totrh=$totrh+$rhsi;
				tblcellule(number_format($rhsi,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immsi+$socialsi+$rhsi,3 , ' ' ,  ' '));
				break;
			case "TRANSPORT":
				$reqtransport = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,dotc,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$restransport = $bd->execRequete ($reqtransport);
				$transport=mysql_result($restransport,0,0);

				$immtransport=$ligne[0]*($transport/$charges);
				$totimm=$totimm+$immtransport;

				tblcellule(number_format($immtransport,3 , ' ' ,  ' '));
				tblcellule("");

				$socialtransport=$SP90[0]*($transport/$charges);
				$totsocial=$totsocial+$socialtransport;
				tblcellule(number_format($socialtransport,3 , ' ' ,  ' ' ));

				$rhtransport=$SP91[0]*($transport/$charges);
				$totrh=$totrh+$rhtransport;
				tblcellule(number_format($rhtransport,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immtransport+$socialtransport+$rhtransport,3 , ' ' ,  ' '));
				break;
			case "TI":
				$reqti = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,dotc,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and cdotc=cedotc and centite=ceentite and codep='".$dotcr."'
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resti = $bd->execRequete ($reqti);
				$ti=mysql_result($resti,0,0);

				$immti=$ligne[0]*($ti/$charges);
				$totimm=$totimm+$immti;

				tblcellule(number_format($immti,3 , ' ' ,  ' '));
				tblcellule("");

				$socialti=$SP90[0]*($ti/$charges);
				$totsocial=$totsocial+$socialti;
				tblcellule(number_format($socialti,3 , ' ' ,  ' ' ));

				$rhti=$SP91[0]*($ti/$charges);
				$totrh=$totrh+$rhti;
				tblcellule(number_format($rhti,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immti+$socialti+$rhti,3 , ' ' ,  ' '));
				break;
			case "CAA TRANSVERSES":
				tblcellule("");
				tblcellule("");
				tblcellule("");
				tblcellule("");
				tblcellule("");
				break;
			default:
				break;
		}
		tblfinligne();
		tbldebutligne(MPAP);
		tblcellule("MPAP");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblfinligne();
	}
	tbldebutligne();
	tblcellule("Total");
	tblcellule(number_format($totimm,3 , ' ', ' '));
	tblcellule(number_format($totvehi,3 , ' ', ' '));
	tblcellule(number_format($totsocial,3 , ' ', ' '));
	tblcellule(number_format($totrh,3 , ' ', ' '));
	tblcellule(number_format(-$ligne[0]-$ligne2[0]-$SP90[0]-$SP91[0]+$immti+$socialti+$rhti
		+$immtransport+$socialtransport+$rhtransport
		+$immsi+$socialsi+$rhsi
		+$immrh+$socialrh+$rhrh
		+$immpil+$socialpil+$rhpil
		+$immmark+$socialmark+$rhmark
		+$immcompta+$socialcompta+$rhcompta
		+$immst+$socialst+$rhst
		+$immconc+$vehiconc+$socialconc+$rhconc
		+$immtrait+$socialtrait+$rhtrait
		+$immdist+$vehidist+$socialdist+$rhdist
		,0 , ' ' ,  ' ' ));
	tblfinligne();
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();

    }
    //Page entites
function forment($dotc=99,$ancu=2009,$percu="rien")
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Processus Opérationnels"=>"entoperation.php",
					  "Processus Supports"=>"entsupport.php");
	echo "<CENTER><H1>".'Entité '."</H1></CENTER>\n";
				
					//champlist deroulant annee periode entite
					$requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
					$resultat2 = $bd->execRequete ($requete2);
					$nomb_ligne2=$bd->nbLigne($resultat2);
					$listannee[0]="";
					   for ($j=0;$j<$nomb_ligne2;$j++)
					    {
						   $ligne2=$bd->objetSuivant($resultat2);
						   $listannee[$ligne2->can]=$ligne2->liban;
					    }
					$requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
					$resultat = $bd->execRequete ($requete);
					$nomb_ligne=$bd->nbLigne($resultat);
					$listPerio[0]="";
					   for ($j=0;$j<$nomb_ligne;$j++)
					    {
						   $ligne=$bd->objetSuivant($resultat);
					       $listPerio[$ligne->cperi]=$ligne->libperi;
					    }
					$requete1 = "SELECT distinct cdotc,libdotc,codep FROM dotc order by libdotc ";
				    $resultat1 = $bd->execRequete ($requete1);
				    $nomb_ligne1=$bd->nbLigne($resultat1);
					$listdo[0]="";
				       for ($j=0;$j<$nomb_ligne1;$j++)
				        {
				           $ligne1=$bd->objetSuivant($resultat1);
				           $listdo[$ligne1->cdotc]=$ligne1->libdotc;
				        }
					switch ($_SESSION["usefonc"])
					{
						case 1;case 3;
							$Form->debuttable();
							if($dotc<>99)
							{
								$reqdotc="select cdotc from dotc where codep='".$dotc."'";
								$resdotc = $bd->execRequete($reqdotc);
								$coddotc=$bd->ligTabSuivant($resdotc);
								$Form->champliste ("Dotc :", "dotc", $coddotc[0], 1, $listdo);
							}
							else
							{
								$Form->champliste ("Dotc :", "dotc", "", 1, $listdo);
							}
						break;
						case 2:
							$Form->debuttable();
							break;
					}
					if($ancu<>2009)
					{
						$reqcodan="select can from annee where liban='".$ancu."'";
						$rescodan = $bd->execRequete($reqcodan);
						$codannee=$bd->ligTabSuivant($rescodan);
						$Form->champliste ("Année :", "ann", $codannee[0], 1, $listannee);
						$ancour=$codannee;
						$GLOBALS[ancour];
					}
					else
					{
						$Form->champliste ("Année :", "ann", "", 1, $listannee);
					}
					if($percu<>"rien")
					{
						$reqcodper="select cperi from periode where libperi='".$percu."'";
						$rescodper = $bd->execRequete($reqcodper);
						$codperiode=$bd->ligTabSuivant($rescodper);
						$Form->champliste ("Pèriode :", "perio", $codperiode[0], 1, $listPerio);
						$percour=$codperiode[0];
						$GLOBALS[percour];
					}
					else
					{
						$Form->champliste ("Pèriode :", "perio", "", 1, $listPerio);
					}
				//	$Form->champliste ("Ann�e :", "ann", "", 1, $listannee);
				//	$Form->champliste ("P�riode :", "perio", "", 1, $listPerio);
					$Form->champvalider ("Valider", "valider");
					//$Form->fin();
					
					
					$Form = new formulaire ("POST", "","suivconnex");
					//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
					//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
					tbldebut(0,"100%");
					tbldebutligne("MENU");
					//choix des menus
                    foreach ($menuclien as $libelle => $ancre )
	                   //while (list($libelle,$ancre)=each($menuclien))
					tblcellule(Ancre($ancre,$libelle,"MENU"));
					tblfin();
					tblcellule(image('interface/redbar.jpg',"200%",1));
					// Tableau en mode vertical, pour les champs simples
					$Form->debuttable();
					$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"200%",1)));
					$Form->ajoutTexte (tbldebut(1,"200%"));
					$Form->ajoutTexte (tbldebutligne());
					$Form->ajoutTexte (tblentete("Entité"));
					$Form->ajoutTexte (tblenteteFusio("Concentration"));
					$Form->ajoutTexte (tblenteteFusio("Traitement"));
					$Form->ajoutTexte (tblenteteFusio("Distribution"));
					$Form->ajoutTexte (tblenteteFusio("TI"));
					$Form->ajoutTexte (tblenteteFusio("Transport"));
					$Form->ajoutTexte (tblfinligne());
					$Form->ajoutTexte (tbldebutligne());
					$Form->ajoutTexte (tblentete(""));
					$Form->ajoutTexte (tblentete("Charges"));
					$Form->ajoutTexte (tblentete("Trafics"));
					$Form->ajoutTexte (tblentete("Coûts Unitaires"));
					$Form->ajoutTexte (tblentete("Charges"));
					$Form->ajoutTexte (tblentete("Trafics"));
					$Form->ajoutTexte (tblentete("Coûts Unitaires"));
					$Form->ajoutTexte (tblentete("Charges"));
					$Form->ajoutTexte (tblentete("Trafics"));
					$Form->ajoutTexte (tblentete("Coûts Unitaires"));
					$Form->ajoutTexte (tblentete("Charges"));
					$Form->ajoutTexte (tblentete("Trafics"));
					$Form->ajoutTexte (tblentete("Coûts Unitaires"));
					$Form->ajoutTexte (tblentete("Charges"));
					$Form->ajoutTexte (tblentete("Trafics"));
					$Form->ajoutTexte (tblentete("Coûts Unitaires"));
					$Form->ajoutTexte (tblfinligne());
						//processus----------------------------------------------------------------------------------------
						$req1 = "select cprocess
									from processus
										where libprocess like 'DISTRIBUTION'";
						$res1 = $bd->execRequete ($req1);
						$prdist=$bd->ligTabSuivant($res1);
						
						$req2 = "select cprocess
									from processus
										where libprocess like 'TRAITEMENT'";
						$res2 = $bd->execRequete ($req2);
						$prtrait=$bd->ligTabSuivant($res2);
						
						$req3 = "select cprocess
									from processus
										where libprocess like 'CONCENTRATION'";
						$res3 = $bd->execRequete ($req3);
						$prconc=$bd->ligTabSuivant($res3);
						
						$req4 = "select cprocess
									from processus
										where libprocess like 'TI'";
						$res4 = $bd->execRequete ($req4);
						$prti=$bd->ligTabSuivant($res4);
						
						$req5 = "select cprocess
									from processus
										where libprocess like 'TRANSPORT'";
						$res5 = $bd->execRequete ($req5);
						$prtransp=$bd->ligTabSuivant($res5);

						//charges-----------------------------------------------------------------------------------------
						$requete5 = "select  cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
						from domaine,processus,sousprocessus,caa,charcaa,entite,mois,periode,dotc
							where cordaffich<>'0'
								and anchcaa='".$ancu."' and cperi='".$codperiode."' 
								and cperi=ceperi and codmoi=moichcaa
								and codmoi=moichcaa
				               	and cdotc='".$coddotc."' and cdotc=cedotc
								and cdom=cedom and cprocess=ceprocess
					            and cssprocess=cessprocess and ccaa=cecaa
						           and centite=ceentite and cprocess='".$prtransp[0]."'group by cregate order by cregate";
						$resultat5 = $bd->execRequete ($requete5);
						$nomb_ligne5=$bd->nbLigne($resultat5);
						
						$requete4 = "select  cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
						from domaine,processus,sousprocessus,caa,charcaa,entite,mois,periode,dotc
							where cordaffich<>'0'
								and anchcaa='".$ancu."' and cperi='".$codperiode."' 
								and cperi=ceperi and codmoi=moichcaa
								and codmoi=moichcaa
				                and cdotc='".$coddotc."' and cdotc=cedotc
								and cdom=cedom and cprocess=ceprocess
					            and cssprocess=cessprocess and ccaa=cecaa
					            and centite=ceentite and cprocess='".$prti[0]."'group by cregate order by cregate";
						$resultat4 = $bd->execRequete ($requete4);
						$nomb_ligne4=$bd->nbLigne($resultat4);
						
						$requete3 = "select  cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
						from domaine,processus,sousprocessus,caa,charcaa,entite,mois,periode,dotc
							where cordaffich<>'0'
								and anchcaa='".$ancu."' and cperi='".$codperiode."' 
								and cperi=ceperi and codmoi=moichcaa
								and codmoi=moichcaa
				                and cdotc='".$coddotc."' and cdotc=cedotc
								and cdom=cedom and cprocess=ceprocess
					            and cssprocess=cessprocess and ccaa=cecaa
					            and centite=ceentite and cprocess='".$prdist[0]."'group by cregate order by cregate";
						$resultat3 = $bd->execRequete ($requete3);
						$nomb_ligne3=$bd->nbLigne($resultat3);
						
						$requete2 = "select  sum(charcaa)
						from domaine,processus,sousprocessus,caa,charcaa,entite,mois,periode,dotc
							where cordaffich<>'0'
								and anchcaa='".$ancu."' and cperi='".$codperiode."' 
								and cperi=ceperi and codmoi=moichcaa
								and codmoi=moichcaa
				                and cdotc='".$coddotc."' and cdotc=cedotc
								and cdom=cedom and cprocess=ceprocess
					            and cssprocess=cessprocess and ccaa=cecaa
					            and centite=ceentite and cprocess='".$prtrait[0]."'group by cregate order by cregate";
						$resultat2 = $bd->execRequete ($requete2);
						$nomb_ligne2=$bd->nbLigne($resultat2);
					
						$requete1 = "select  sum(charcaa)
						from domaine,processus,sousprocessus,caa,charcaa,entite,mois,periode,dotc
							where cordaffich<>'0'
								and anchcaa='".$ancu."' and cperi='".$codperiode."' 
								and cperi=ceperi and codmoi=moichcaa
								and codmoi=moichcaa
				                and cdotc='".$coddotc."' and cdotc=cedotc
								and cdom=cedom and cprocess=ceprocess
					            and cssprocess=cessprocess and ccaa=cecaa
					            and centite=ceentite and cprocess='".$prconc[0]."'group by cregate order by cregate";
						$resultat1 = $bd->execRequete ($requete1);	
						$nomb_ligne1=$bd->nbLigne($resultat1);
										
						// trafics-----------------------------------------------------------------------------------------------
						$reqtraf1 = "select sum(trafdistribm),sum(trafdistribc),cregate
						from pildi,entite,dotc,mois,periode
							where centite=pildi.ceentite 
								and anpi='".$ancu."' and cperi='".$codperiode."' 
								and cperi=ceperi and codmoi=moipi
								and cdotc='".$coddotc."' and cdotc=cedotc
								and (trafdistribm<>0 or trafdistribc<>0) 
		            				group by cregate order by cregate";
						$restraf1 = $bd->execRequete ($reqtraf1);
						$traf1=$bd->nbLigne($restraf1);
						
						$reqtraf2 = "select sum(trafpna),cregate
						from pna,entite,dotc,mois,periode
							where trafpna<>0 and centite=ceentite 
								and anpna='".$ancu."' and cperi='".$codperiode."' 
								and cperi=ceperi and codmoi=moipna
								and cdotc='".$coddotc."' and cdotc=cedotc
									group by cregate order by cregate";
						$restraf2 = $bd->execRequete ($reqtraf2);
						$traf2=$bd->nbLigne($restraf2);
					
						$reqtraf3 = "select sum(trafcolis),cregate
						from colis,entite,dotc,mois,periode
							where trafcolis<>0 and centite=ceentite 
								and ancol='".$ancu."' and cperi='".$codperiode."' 
								and cperi=ceperi and codmoi=moicol
								and cdotc='".$coddotc."' and cdotc=cedotc
									group by cregate order by cregate";
						$restraf3 = $bd->execRequete ($reqtraf3);
						$traf3=$bd->nbLigne($restraf3);
						
						$reqtraf4 = "select sum(traftraitd),sum(traftraita),cregate
						from syspeo,entite,dotc,mois,periode
							where centite=ceentite and traftraitd<>0 and traftraita<>0
								and anpeo='".$ancu."' and cperi='".$codperiode."' 
								and cperi=ceperi and codmoi=moipeo
								and cdotc='".$coddotc."' and cdotc=cedotc
									group by cregate order by cregate";
						$restraf4 = $bd->execRequete ($reqtraf4);
						$traf4=$bd->nbLigne($restraf4);
			
						$reqtraf5 = "select sum(trafconc),cregate
						from pildi,entite,dotc,mois,periode
							where centite=ceentite and trafconc<>0
								and anpi='".$ancu."' and cperi='".$codperiode."' 
								and cperi=ceperi and codmoi=moipi
								and cdotc='".$coddotc."' and cdotc=cedotc
									group by cregate order by cregate";
						$restraf5 = $bd->execRequete ($reqtraf5);
						$traf5=$bd->nbLigne($restraf5);
				
						$reqtraf6 = "select sum(trafconc),cregate
						from syspeo,entite,dotc,mois,periode
							where centite=ceentite and trafconc<>0
								and anpeo='".$ancu."' and cperi='".$codperiode."' 
								and cperi=ceperi and codmoi=moipeo
								and cdotc='".$coddotc."' and cdotc=cedotc
									group by cregate order by cregate";
						$restraf6 = $bd->execRequete ($reqtraf6);
						$traf6=$bd->nbLigne($restraf6);
						
					for ($j=0;$j<$nomb_ligne3;$j++)
					{
						//charges
						$ligne5=$bd->ligTabSuivant($resultat5);//transport
						$ligne4=$bd->ligTabSuivant($resultat4);//ti
						$ligne3=$bd->ligTabSuivant($resultat3);//dist
						$ligne2=$bd->ligTabSuivant($resultat2);//trait
						$ligne1=$bd->ligTabSuivant($resultat1);//conc
						
						//trafics
						$traf=$bd->ligTabSuivant($restraf1);//dist
						$traf2=$bd->ligTabSuivant($restraf2);//dist
						$traf3=$bd->ligTabSuivant($restraf3);//dist
						$traf4=$bd->ligTabSuivant($restraf4);//trait
						$traf5=$bd->ligTabSuivant($restraf5);//trait
						$traf6=$bd->ligTabSuivant($restraf6);//trait
				
						tbldebutligne("A0");
						tblcellule($ligne3[2]);//entit� + cregate
						//concentration
						tblcellule(number_format($ligne1[0],0 , ' '  ,  ' ' ));//charges 
						tblcellule(number_format($traf5[0]+$traf6[0],0 , ' ' ,  ' ' ));//trafics
 						tblcellule(number_format($ligne1[0]/($traf5[0]+$traf6[0]),8 , ',' ,  ' ' ));//cout unitaire
						//Traitement
						tblcellule(number_format($ligne2[0],0 , ' ' ,  ' ' ));//charges 
						tblcellule(number_format($traf4[0]+$traf4[1],0 , ' ' ,  ' ' ));//trafics
						tblcellule(number_format($ligne2[0]/($traf4[0]+$traf4[1]),8 , ',' ,  ' ' ));//cout unitaire
						//Distribution
						tblcellule(number_format($ligne3[4],0 , ' ' ,  ' ' ));//charges 
						tblcellule(number_format($traf[0]+$traf[1]+$traf2[0]+$traf3[0],0 , ' ' ,  ' ' ));//trafics
						tblcellule(number_format($ligne3[4]/($traf[0]+$traf[1]+$traf2[0]+$traf3[0]),8 , ',' ,  ' ' ));//cout unitaire
						//TI
						tblcellule(number_format($ligne4[4],0 , '' ,  ' ' ));//charges 
						tblcellule(number_format($traf[0]+$traf[1]+$traf3[0],0 , ' ' ,  ' ' ));//trafic
						tblcellule(number_format($ligne4[4]/($traf[0]+$traf[1]+$traf3[0]),8 , ',' ,  ' ' ));//cout unitaire
						//Transport
						tblcellule(number_format($ligne5[4],0 , '' ,  ' ' ));//charges 
						tblcellule("");//trafic
						tblcellule("");//cout unitaire
						tblfinligne();
					}
					$Form->debuttable(tblfinligne());
					$Form->fintable();
					$Form->ajoutTexte (tblfin());
					$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"200%",1)));
					$Form->ajoutTexte ("<P></P>");
					//$Form->fin();
					echo $Form->formulaireHTML();
    }
function formentopera()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Processus Opérationnels"=>"entoperation.php",
					  "Processus Supports"=>"entsupport.php");
					
	$ancour=$_SESSION['anc'];
	$percour=$_SESSION['perc'];
	$dotcr=$_SESSION['dotcr'];
	$libdotc=$_SESSION['libdotc'];
					
	echo "<CENTER><H1>".'Processus Opérationnels '."</H1></CENTER>\n";
	echo "<CENTER><H3>".'Année traitée : '.$ancour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'Pèriode traitée : '.$percour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'Dotc traitée : '.$dotcr." - ".$libdotc."</H3></CENTER>\n";
			
				$Form = new formulaire ("POST", "","suivconnex");
				//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
				//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
				tbldebut(0,"100%");
				tbldebutligne("MENU");
				//choix des menus
                foreach ($menuclien as $libelle => $ancre )
            	//while (list($libelle,$ancre)=each($menuclien))
				tblcellule(Ancre($ancre,$libelle,"MENU"));
				tblfin();
				tblcellule(image('interface/redbar.jpg',"200%",1));
				// Tableau en mode vertical, pour les champs simples
				$Form->debuttable();
				$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"200%",1)));
				$Form->ajoutTexte (tbldebut(1,"200%"));
				$Form->ajoutTexte (tbldebutligne());
				$Form->ajoutTexte (tblentete("Entité"));
				$Form->ajoutTexte (tblenteteFusio("Concentration"));
				$Form->ajoutTexte (tblenteteFusio("Traitement"));
				$Form->ajoutTexte (tblenteteFusio("Distribution"));
				$Form->ajoutTexte (tblenteteFusio("TI"));
				$Form->ajoutTexte (tblenteteFusio("Transport"));
				$Form->ajoutTexte (tblfinligne());
				$Form->ajoutTexte (tbldebutligne());
				$Form->ajoutTexte (tblentete(""));
				$Form->ajoutTexte (tblentete("Charges"));
				$Form->ajoutTexte (tblentete("Trafics"));
				$Form->ajoutTexte (tblentete("Coûts Unitaires"));
				$Form->ajoutTexte (tblentete("Charges"));
				$Form->ajoutTexte (tblentete("Trafics"));
				$Form->ajoutTexte (tblentete("Coûts Unitaires"));
				$Form->ajoutTexte (tblentete("Charges"));
				$Form->ajoutTexte (tblentete("Trafics"));
				$Form->ajoutTexte (tblentete("Coûts Unitaires"));
				$Form->ajoutTexte (tblentete("Charges"));
				$Form->ajoutTexte (tblentete("Trafics"));
				$Form->ajoutTexte (tblentete("Coûts Unitaires"));
				$Form->ajoutTexte (tblentete("Charges"));
				$Form->ajoutTexte (tblentete("Trafics"));
				$Form->ajoutTexte (tblentete("Coûts Unitaires"));
				$Form->ajoutTexte (tblfinligne());
					//processus----------------------------------------------------------------------------------------
					$req1 = "select cprocess
								from processus
									where libprocess like 'DISTRIBUTION'";
					$res1 = $bd->execRequete ($req1);
					$prdist=$bd->ligTabSuivant($res1);
					
					$req2 = "select cprocess
								from processus
									where libprocess like 'TRAITEMENT'";
					$res2 = $bd->execRequete ($req2);
					$prtrait=$bd->ligTabSuivant($res2);
					
					$req3 = "select cprocess
								from processus
									where libprocess like 'CONCENTRATION'";
					$res3 = $bd->execRequete ($req3);
					$prconc=$bd->ligTabSuivant($res3);
					
					$req4 = "select cprocess
								from processus
									where libprocess like 'TI'";
					$res4 = $bd->execRequete ($req4);
					$prti=$bd->ligTabSuivant($res4);
					
					$req5 = "select cprocess
								from processus
									where libprocess like 'TRANSPORT'";
					$res5 = $bd->execRequete ($req5);
					$prtransp=$bd->ligTabSuivant($res5);

					//charges-----------------------------------------------------------------------------------------
					$requete5 = "select  cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
					from domaine,processus,sousprocessus,caa,charcaa,entite,mois,periode,dotc
						where cordaffich<>'0'
							and anchcaa='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moichcaa
							and codmoi=moichcaa
			               	and codep='".$dotcr."' and cdotc=cedotc
							and cdom=cedom and cprocess=ceprocess
				            and cssprocess=cessprocess and ccaa=cecaa
					           and centite=ceentite and cprocess='".$prtransp[0]."'group by cregate order by cregate";
					$resultat5 = $bd->execRequete ($requete5);
					$nomb_ligne5=$bd->nbLigne($resultat5);
					
					$requete4 = "select  cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
					from domaine,processus,sousprocessus,caa,charcaa,entite,mois,periode,dotc
						where cordaffich<>'0'
							and anchcaa='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moichcaa
							and codmoi=moichcaa
			                and codep='".$dotcr."' and cdotc=cedotc
							and cdom=cedom and cprocess=ceprocess
				            and cssprocess=cessprocess and ccaa=cecaa
				            and centite=ceentite and cprocess='".$prti[0]."'group by cregate order by cregate";
					$resultat4 = $bd->execRequete ($requete4);
					$nomb_ligne4=$bd->nbLigne($resultat4);
					
					$requete3 = "select  cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
					from domaine,processus,sousprocessus,caa,charcaa,entite,mois,periode,dotc
						where cordaffich<>'0'
							and anchcaa='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moichcaa
							and codmoi=moichcaa
			                and codep='".$dotcr."' and cdotc=cedotc
							and cdom=cedom and cprocess=ceprocess
				            and cssprocess=cessprocess and ccaa=cecaa
				            and centite=ceentite and cprocess='".$prdist[0]."'group by cregate order by cregate";
					$resultat3 = $bd->execRequete ($requete3);
					$nomb_ligne3=$bd->nbLigne($resultat3);
					
					$requete2 = "select  sum(charcaa)
					from domaine,processus,sousprocessus,caa,charcaa,entite,mois,periode,dotc
						where cordaffich<>'0'
							and anchcaa='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moichcaa
							and codmoi=moichcaa
			                and codep='".$dotcr."' and cdotc=cedotc
							and cdom=cedom and cprocess=ceprocess
				            and cssprocess=cessprocess and ccaa=cecaa
				            and centite=ceentite and cprocess='".$prtrait[0]."'group by cregate order by cregate";
					$resultat2 = $bd->execRequete ($requete2);
					$nomb_ligne2=$bd->nbLigne($resultat2);
				
					$requete1 = "select  sum(charcaa)
					from domaine,processus,sousprocessus,caa,charcaa,entite,mois,periode,dotc
						where cordaffich<>'0'
							and anchcaa='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moichcaa
							and codmoi=moichcaa
			                and codep='".$dotcr."' and cdotc=cedotc
							and cdom=cedom and cprocess=ceprocess
				            and cssprocess=cessprocess and ccaa=cecaa
				            and centite=ceentite and cprocess='".$prconc[0]."'group by cregate order by cregate";
					$resultat1 = $bd->execRequete ($requete1);	
					$nomb_ligne1=$bd->nbLigne($resultat1);
									
					// trafics-----------------------------------------------------------------------------------------------
					$reqtraf1 = "select sum(trafdistribm),sum(trafdistribc),cregate
					from pildi,entite,dotc,mois,periode
						where centite=pildi.ceentite 
							and anpi='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moipi
							and codep='".$dotcr."' and cdotc=cedotc
							and (trafdistribm<>0 or trafdistribc<>0) 
	            				group by cregate order by cregate";
					$restraf1 = $bd->execRequete ($reqtraf1);
					$traf1=$bd->nbLigne($restraf1);
					
					$reqtraf2 = "select sum(trafpna),cregate
					from pna,entite,dotc,mois,periode
						where trafpna<>0 and centite=ceentite 
							and anpna='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moipna
							and codep='".$dotcr."' and cdotc=cedotc
								group by cregate order by cregate";
					$restraf2 = $bd->execRequete ($reqtraf2);
					$traf2=$bd->nbLigne($restraf2);
				
					$reqtraf3 = "select sum(trafcolis),cregate
					from colis,entite,dotc,mois,periode
						where trafcolis<>0 and centite=ceentite 
							and ancol='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moicol
							and codep='".$dotcr."' and cdotc=cedotc
								group by cregate order by cregate";
					$restraf3 = $bd->execRequete ($reqtraf3);
					$traf3=$bd->nbLigne($restraf3);
					
					$reqtraf4 = "select sum(traftraitd),sum(traftraita),cregate
					from syspeo,entite,dotc,mois,periode
						where centite=ceentite and traftraitd<>0 and traftraita<>0
							and anpeo='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moipeo
							and codep='".$dotcr."' and cdotc=cedotc
								group by cregate order by cregate";
					$restraf4 = $bd->execRequete ($reqtraf4);
					$traf4=$bd->nbLigne($restraf4);
		
					$reqtraf5 = "select sum(trafconc),cregate
					from pildi,entite,dotc,mois,periode
						where centite=ceentite and trafconc<>0
							and anpi='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moipi
							and codep='".$dotcr."' and cdotc=cedotc
								group by cregate order by cregate";
					$restraf5 = $bd->execRequete ($reqtraf5);
					$traf5=$bd->nbLigne($restraf5);
			
					$reqtraf6 = "select sum(trafconc),cregate
					from syspeo,entite,dotc,mois,periode
						where centite=ceentite and trafconc<>0
							and anpeo='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moipeo
							and codep='".$dotcr."' and cdotc=cedotc
								group by cregate order by cregate";
					$restraf6 = $bd->execRequete ($reqtraf6);
					$traf6=$bd->nbLigne($restraf6);
					
				for ($j=0;$j<$nomb_ligne3;$j++)
				{
					//charges
					$ligne5=$bd->ligTabSuivant($resultat5);//transport
					$ligne4=$bd->ligTabSuivant($resultat4);//ti
					$ligne3=$bd->ligTabSuivant($resultat3);//dist
					$ligne2=$bd->ligTabSuivant($resultat2);//trait
					$ligne1=$bd->ligTabSuivant($resultat1);//conc
					
					//trafics
					$traf=$bd->ligTabSuivant($restraf1);//dist
					$traf2=$bd->ligTabSuivant($restraf2);//dist
					$traf3=$bd->ligTabSuivant($restraf3);//dist
					$traf4=$bd->ligTabSuivant($restraf4);//trait
					$traf5=$bd->ligTabSuivant($restraf5);//trait
					$traf6=$bd->ligTabSuivant($restraf6);//trait
			
					tbldebutligne(A0);
					tblcellule($ligne3[2]);//entit� + cregate
					//concentration
					tblcellule(number_format($ligne1[0],0 , ' '  ,  ' ' ));//charges 
					tblcellule(number_format($traf5[0]+$traf6[0],0 , ' ' ,  ' ' ));//trafics
					tblcellule(number_format($ligne1[0]/($traf5[0]+$traf6[0]),8 , ',' ,  ' ' ));//cout unitaire
					//Traitement
					tblcellule(number_format($ligne2[0],0 , ' ' ,  ' ' ));//charges 
					tblcellule(number_format($traf4[0]+$traf4[1],0 , ' ' ,  ' ' ));//trafics
					tblcellule(number_format($ligne2[0]/($traf4[0]+$traf4[1]),8 , ',' ,  ' ' ));//cout unitaire
					//Distribution
					tblcellule(number_format($ligne3[4],0 , ' ' ,  ' ' ));//charges 
					tblcellule(number_format($traf[0]+$traf[1]+$traf2[0]+$traf3[0],0 , ' ' ,  ' ' ));//trafics
					tblcellule(number_format($ligne3[4]/($traf[0]+$traf[1]+$traf2[0]+$traf3[0]),8 , ',' ,  ' ' ));//cout unitaire
					//TI
					tblcellule(number_format($ligne4[4],0 , '' ,  ' ' ));//charges 
					tblcellule(number_format($traf[0]+$traf[1]+$traf3[0],0 , ' ' ,  ' ' ));//trafic
					tblcellule(number_format($ligne4[4]/($traf[0]+$traf[1]+$traf3[0]),8 , ',' ,  ' ' ));//cout unitaire
					//Transport
					tblcellule(number_format($ligne5[4],0 , '' ,  ' ' ));//charges 
					tblcellule("");//trafic
					tblcellule("");//cout unitaire
					tblfinligne();
				}
				$Form->debuttable(tblfinligne());
				$Form->fintable();
				$Form->ajoutTexte (tblfin());
				$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"200%",1)));
				$Form->ajoutTexte ("<P></P>");
				//$Form->fin();
				echo $Form->formulaireHTML();
    }
function formentsuppo()
    {
	
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Processus Opérationnels"=>"entoperation.php",
					  "Processus Supports"=>"entsupport.php");
					
	$ancour=$_SESSION['anc'];
	$percour=$_SESSION['perc'];
	$dotcr=$_SESSION['dotcr'];
	$libdotc=$_SESSION['libdotc'];
					
	echo "<CENTER><H1>".'Processus Supports '."</H1></CENTER>\n";
	echo "<CENTER><H3>".'Année traitée : '.$ancour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'Pèriode traitée : '.$percour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'Dotc traitée : '.$dotcr." - ".$libdotc."</H3></CENTER>\n";
			
				$Form = new formulaire ("POST", "","suivconnex");
				//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
				//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
				tbldebut(0,"250%");
				tbldebutligne("MENU");
				//choix des menus
                foreach ($menuclien as $libelle => $ancre )
	           //while (list($libelle,$ancre)=each($menuclien))
				tblcellule(Ancre($ancre,$libelle,"MENU"));
				tblfin();
				tblcellule(image('interface/redbar.jpg',"250%",1));
				// Tableau en mode vertical, pour les champs simples
				// Tableau en mode vertical, pour les champs simples
					$Form->debuttable();
					$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"250%",1)));
					$Form->ajoutTexte (tbldebut(1,"250%"));
					$Form->ajoutTexte (tbldebutligne());
					$Form->ajoutTexte (tblentete("Entité"));
					$Form->ajoutTexte (tblenteteFusio("RH"));
					$Form->ajoutTexte (tblenteteFusio("SI"));
					$Form->ajoutTexte (tblenteteFusio("Transverses"));
					$Form->ajoutTexte (tblenteteFusio("Commercial"));
					$Form->ajoutTexte (tblenteteFusio("Soutien opérationnel"));
					$Form->ajoutTexte (tblenteteFusio("Pilotage"));
					$Form->ajoutTexte (tblenteteFusio("Finance"));
					$Form->ajoutTexte (tblfinligne());
					$Form->ajoutTexte (tbldebutligne());
					$Form->ajoutTexte (tblentete(""));
					$Form->ajoutTexte (tblentete("Charges"));
					$Form->ajoutTexte (tblentete("Trafics"));
					$Form->ajoutTexte (tblentete("Coûts Unitaires"));
					$Form->ajoutTexte (tblentete("Charges"));
					$Form->ajoutTexte (tblentete("Trafics"));
					$Form->ajoutTexte (tblentete("Coûts Unitaires"));
					$Form->ajoutTexte (tblentete("Charges"));
					$Form->ajoutTexte (tblentete("Trafics"));
					$Form->ajoutTexte (tblentete("Coûts Unitaires"));
					$Form->ajoutTexte (tblentete("Charges"));
					$Form->ajoutTexte (tblentete("Trafics"));
					$Form->ajoutTexte (tblentete("Coûts Unitaires"));
					$Form->ajoutTexte (tblentete("Charges"));
					$Form->ajoutTexte (tblentete("Trafics"));
					$Form->ajoutTexte (tblentete("Coûts Unitaires"));
					$Form->ajoutTexte (tblentete("Charges"));
					$Form->ajoutTexte (tblentete("Trafics"));
					$Form->ajoutTexte (tblentete("Coûts Unitaires"));
					$Form->ajoutTexte (tblentete("Charges"));
					$Form->ajoutTexte (tblentete("Trafics"));
					$Form->ajoutTexte (tblentete("Coûts Unitaires"));
					$Form->ajoutTexte (tblfinligne());
					//processus----------------------------------------------------------------------------------------
					$req1 = "select cprocess
								from processus
									where libprocess like 'RH'";
					$res1 = $bd->execRequete ($req1);
					$prRH=$bd->ligTabSuivant($res1);
					
					$req2 = "select cprocess
								from processus
									where libprocess like 'SI'";
					$res2 = $bd->execRequete ($req2);
					$prSI=$bd->ligTabSuivant($res2);
					
					$req3 = "select cprocess
								from processus
									where libprocess like 'CAA TRANSVERSES'";
					$res3 = $bd->execRequete ($req3);
					$prTRANSV=$bd->ligTabSuivant($res3);
					
					$req4 = "select cprocess
								from processus
									where libprocess like 'MARKETING COMMERCIAL'";
					$res4 = $bd->execRequete ($req4);
					$prCOMMER=$bd->ligTabSuivant($res4);
					
					$req5 = "select cprocess
								from processus
									where libprocess like 'SOUTIEN OPERATIONNEL'";
					$res5 = $bd->execRequete ($req5);
					$prSOUTIEN=$bd->ligTabSuivant($res5);
					
					$req6 = "select cprocess
								from processus
									where libprocess like 'PILOTAGE'";
					$res6 = $bd->execRequete ($req6);
					$prPILOT=$bd->ligTabSuivant($res6);
					
					$req7 = "select cprocess
								from processus
									where libprocess like 'FINANCE'";
					$res7= $bd->execRequete ($req7);
					$prFINAN=$bd->ligTabSuivant($res7);

					//charges-----------------------------------------------------------------------------------------
					$requete7 = "select  cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
					from domaine,processus,sousprocessus,caa,charcaa,entite,mois,periode,dotc
						where cordaffich<>'0'
							and anchcaa='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moichcaa
							and codmoi=moichcaa
			              	and codep='".$dotcr."' and cdotc=cedotc
							and cdom=cedom and cprocess=ceprocess
					       	and cssprocess=cessprocess and ccaa=cecaa
					        and centite=ceentite and cprocess='".$prFINAN[0]."'group by cregate order by cregate";
					$resultat7 = $bd->execRequete ($requete7);
					$nomb_ligne7=$bd->nbLigne($resultat7);
				
					$requete6 = "select  cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
					from domaine,processus,sousprocessus,caa,charcaa,entite,mois,periode,dotc
						where cordaffich<>'0'
							and anchcaa='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moichcaa
							and codmoi=moichcaa
			               	and codep='".$dotcr."' and cdotc=cedotc
							and cdom=cedom and cprocess=ceprocess
				            and cssprocess=cessprocess and ccaa=cecaa
					        and centite=ceentite and cprocess='".$prPILOT[0]."'group by cregate order by cregate";
					$resultat6 = $bd->execRequete ($requete6);
					$nomb_ligne6=$bd->nbLigne($resultat6);
				
					$requete5 = "select  cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
					from domaine,processus,sousprocessus,caa,charcaa,entite,mois,periode,dotc
						where cordaffich<>'0'
							and anchcaa='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moichcaa
							and codmoi=moichcaa
			               	and codep='".$dotcr."' and cdotc=cedotc
							and cdom=cedom and cprocess=ceprocess
				            and cssprocess=cessprocess and ccaa=cecaa
					        and centite=ceentite and cprocess='".$prTRANSV[0]."'group by cregate order by cregate";
					$resultat5 = $bd->execRequete ($requete5);
					$nomb_ligne5=$bd->nbLigne($resultat5);
					
					$requete4 = "select  cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
					from domaine,processus,sousprocessus,caa,charcaa,entite,mois,periode,dotc
						where cordaffich<>'0'
							and anchcaa='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moichcaa
							and codmoi=moichcaa
			                and codep='".$dotcr."' and cdotc=cedotc
							and cdom=cedom and cprocess=ceprocess
				            and cssprocess=cessprocess and ccaa=cecaa
				            and centite=ceentite and cprocess='".$prCOMMER[0]."'group by cregate order by cregate";
					$resultat4 = $bd->execRequete ($requete4);
					$nomb_ligne4=$bd->nbLigne($resultat4);
					
					$requete3 = "select  cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
					from domaine,processus,sousprocessus,caa,charcaa,entite,mois,periode,dotc
						where cordaffich<>'0'
							and anchcaa='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moichcaa
							and codmoi=moichcaa
			                and codep='".$dotcr."' and cdotc=cedotc
							and cdom=cedom and cprocess=ceprocess
				            and cssprocess=cessprocess and ccaa=cecaa
				            and centite=ceentite and cprocess='".$prRH[0]."'group by cregate order by cregate";
					$resultat3 = $bd->execRequete ($requete3);
					$nomb_ligne3=$bd->nbLigne($resultat3);
					
					$requete2 = "select  cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
					from domaine,processus,sousprocessus,caa,charcaa,entite,mois,periode,dotc
						where cordaffich<>'0'
							and anchcaa='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moichcaa
							and codmoi=moichcaa
			                and codep='".$dotcr."' and cdotc=cedotc
							and cdom=cedom and cprocess=ceprocess
				            and cssprocess=cessprocess and ccaa=cecaa
				            and centite=ceentite and cprocess='".$prSI[0]."'group by cregate order by cregate";
					$resultat2 = $bd->execRequete ($requete2);
					$nomb_ligne2=$bd->nbLigne($resultat2);
				
					$requete1 = "select cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
					from domaine,processus,sousprocessus,caa,charcaa,entite,mois,periode,dotc
						where cordaffich<>'0'
							and anchcaa='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moichcaa
							and codmoi=moichcaa
			                and codep='".$dotcr."' and cdotc=cedotc
							and cdom=cedom and cprocess=ceprocess
				            and cssprocess=cessprocess and ccaa=cecaa
				            and centite=ceentite and cprocess='".$prSOUTIEN[0]."'group by cregate order by cregate";
					$resultat1 = $bd->execRequete ($requete1);	
					$nomb_ligne1=$bd->nbLigne($resultat1);
									
					// trafics-----------------------------------------------------------------------------------------------
					$reqtraf1 = "select sum(trafdistribm),sum(trafdistribc),cregate
					from pildi,entite,dotc,mois,periode
						where centite=pildi.ceentite 
							and anpi='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moipi
							and codep='".$dotcr."' and cdotc=cedotc
							and (trafdistribm<>0 or trafdistribc<>0) 
	            				group by cregate order by cregate";
					$restraf1 = $bd->execRequete ($reqtraf1);
					$traf1=$bd->nbLigne($restraf1);
					
					$reqtraf2 = "select sum(trafpna),cregate
					from pna,entite,dotc,mois,periode
						where trafpna<>0 and centite=ceentite 
							and anpna='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moipna
							and codep='".$dotcr."' and cdotc=cedotc
								group by cregate order by cregate";
					$restraf2 = $bd->execRequete ($reqtraf2);
					$traf2=$bd->nbLigne($restraf2);
				
					$reqtraf3 = "select sum(trafcolis),cregate
					from colis,entite,dotc,mois,periode
						where trafcolis<>0 and centite=ceentite 
							and ancol='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moicol
							and codep='".$dotcr."' and cdotc=cedotc
								group by cregate order by cregate";
					$restraf3 = $bd->execRequete ($reqtraf3);
					$traf3=$bd->nbLigne($restraf3);
					
					$reqtraf4 = "select sum(traftraitd),sum(traftraita),cregate
					from syspeo,entite,dotc,mois,periode
						where centite=ceentite and traftraitd<>0 and traftraita<>0
							and anpeo='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moipeo
							and codep='".$dotcr."' and cdotc=cedotc
								group by cregate order by cregate";
					$restraf4 = $bd->execRequete ($reqtraf4);
					$traf4=$bd->nbLigne($restraf4);
		
					$reqtraf5 = "select sum(trafconc),cregate
					from pildi,entite,dotc,mois,periode
						where centite=ceentite and trafconc<>0
							and anpi='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moipi
							and codep='".$dotcr."' and cdotc=cedotc
								group by cregate order by cregate";
					$restraf5 = $bd->execRequete ($reqtraf5);
					$traf5=$bd->nbLigne($restraf5);
			
					$reqtraf6 = "select sum(trafconc),cregate
					from syspeo,entite,dotc,mois,periode
						where centite=ceentite and trafconc<>0
							and anpeo='".$ancour."' and libperi='".$percour."' 
							and cperi=ceperi and codmoi=moipeo
							and codep='".$dotcr."' and cdotc=cedotc
								group by cregate order by cregate";
					$restraf6 = $bd->execRequete ($reqtraf6);
					$traf6=$bd->nbLigne($restraf6);					
					
				for ($j=0;$j<$nomb_ligne3;$j++)
				{
					//charges
					$charFINA=$bd->ligTabSuivant($resultat7);//FINANCE
					$charPILO=$bd->ligTabSuivant($resultat6);//PILOTAGE
					$charTRANS=$bd->ligTabSuivant($resultat5);//TRANSVERSES
					$charCOMM=$bd->ligTabSuivant($resultat4);//COMMERCIAL
					$charRH=$bd->ligTabSuivant($resultat3);//RH
					$charSI=$bd->ligTabSuivant($resultat2);//SI
					$charSOUTIEN=$bd->ligTabSuivant($resultat1);//SOUTIEN OPERATIONNEL
										
					//trafics
					$traf=$bd->ligTabSuivant($restraf1);//dist
					$traf2=$bd->ligTabSuivant($restraf2);//dist
					$traf3=$bd->ligTabSuivant($restraf3);//dist
					$traf4=$bd->ligTabSuivant($restraf4);//trait
					$traf5=$bd->ligTabSuivant($restraf5);//trait
					$traf6=$bd->ligTabSuivant($restraf6);//trait
			
					tbldebutligne("A0");
					tblcellule($charRH[2]);//entit� + cregate
				//RH
					tblcellule(number_format($charRH[4],0 , ' '  ,  ' ' ));//charges 
					//tblcellule(number_format($traf5[0]+$traf6[0],0 , ' ' ,  ' ' ));//trafics
					tblcellule("");
					//tblcellule(number_format($charRH[0]/($traf5[0]+$traf6[0]),8 , ',' ,  ' ' ));//cout unitaire
					tblcellule("");//cout unitaire
				//SI
					tblcellule(number_format($CharSI[4],0 , ' ' ,  ' ' ));//charges 
					//tblcellule(number_format($traf4[0]+$traf4[1],0 , ' ' ,  ' ' ));//trafics
					tblcellule("");
					//tblcellule(number_format($CharSI[4]/($traf4[0]+$traf4[1]),8 , ',' ,  ' ' ));//cout unitaire
					tblcellule("");//cout unitaire
				//Transverse
					tblcellule(number_format($charTRANS[4],0 , ' ' ,  ' ' ));//charges 
					//tblcellule(number_format($traf[0]+$traf[1]+$traf2[0]+$traf3[0],0 , ' ' ,  ' ' ));//trafics
					//tblcellule(number_format($ligne3[4]/($traf[0]+$traf[1]+$traf2[0]+$traf3[0]),8 , ',' ,  ' ' ));//cout unitaire
					tblcellule("");//trafics
					tblcellule("");//cout unitaire
				//Commercial
					tblcellule(number_format($charCOMM[4],0 , '' ,  ' ' ));//charges 
					//tblcellule(number_format($traf[0]+$traf[1]+$traf3[0],0 , ' ' ,  ' ' ));//trafic
					//tblcellule(number_format($ligne4[4]/($traf[0]+$traf[1]+$traf3[0]),8 , ',' ,  ' ' ));//cout unitaire
					tblcellule("");//trafics
					tblcellule("");//cout unitaire
				//Soutient Op�rationnel
					tblcellule(number_format($charSOUTIEN[4],0 , '' ,  ' ' ));//charges 
					tblcellule("");//trafics
					tblcellule("");//cout unitaire
				//Pilotage 
					tblcellule(number_format($charPILO[4],0 , '' ,  ' ' ));//charges 
					tblcellule("");//trafics
					tblcellule("");//cout unitaire
				//Finance
					tblcellule(number_format($charFINA[4],0 , '' ,  ' ' ));//charges 
					tblcellule("");//trafics
					tblcellule("");//cout unitaire
					tblfinligne();
				}
				$Form->debuttable(tblfinligne());
				$Form->fintable();
				$Form->ajoutTexte (tblfin());
				$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"200%",1)));
				$Form->ajoutTexte ("<P></P>");
				//$Form->fin();
	echo $Form->formulaireHTML();
	
	
/*	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new BD($nom,$motdepasse,$base,$serveur);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Processus Op�rationnels"=>"entoperation.php",
					  "Processus Supports"=>"entsupport.php");
	
	$ancour=$_SESSION['anc'];
	$percour=$_SESSION['perc'];
	$dotcr=$_SESSION['dotcr'];
	$libdotc=$_SESSION['libdotc'];
					
	echo "<CENTER><H1>".'Processus Supports '."</H1></CENTER>\n";
	echo "<CENTER><H3>".'Ann�e trait�e : '.$ancour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'P�riode trait�e : '.$percour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'Dotc trait�e : '.$dotcr." - ".$libdotc."</H3></CENTER>\n";
	
	//requete sql alimentant tableau
	$requete3 = "select distinct cordaffich,ordscrib,libprocess,sum(charcaa)
                 	from domaine,processus,sousprocessus,caa,charcaa
		                 where cordaffich<>'0' and cdom=cedom and cprocess=ceprocess
                 			and cssprocess=cessprocess and ccaa=cecaa
                 				group by cordaffich
                 					order by ordscrib,cordaffich";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=mysql_num_rows($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete("Entite"));
	$Form->ajoutTexte (tblentete("RH"));
	$Form->ajoutTexte (tblentete("SI"));
	$Form->ajoutTexte (tblentete("Transverse"));
	$Form->ajoutTexte (tblentete("Commercial"));
	$Form->ajoutTexte (tblentete("Soutien operationnel"));
	$Form->ajoutTexte (tblentete("Pilotage"));
	$Form->ajoutTexte (tblentete("Commercial"));
	$Form->ajoutTexte (tblentete("Finance"));
	$Form->ajoutTexte (tblfinligne());
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne(A0);
		tblcellule($ligne3[2]);
		tblcellule("");
		tblfinligne();
		tbldebutligne(MPAP);
		tblcellule(" MPAP");
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
*/	//$Form->fin();
}
//Pages entite  distribution
function formentdist()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Charges"=>"entdistcharge.php",
					  "Trafics"=>"entdistrafic.php",
				      "Coûts unitaires"=>"entdistunit.php",
				      "Coûts unitaires / natures"=>"entdistuninat.php");


	//requete recup processus
	$req = "select cprocess
				from processus
					where libprocess like 'DISTRIBUTION'";
	$res = $bd->execRequete ($req);
	$req2=$bd->ligTabSuivant($res);

	//requete sql alimentant tableau
	$requete3 = "select distinct cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
	from domaine,processus,sousprocessus,caa,charcaa,entite
		where cordaffich<>'0'
			and cdom=cedom and cprocess=ceprocess
            and cssprocess=cessprocess and ccaa=cecaa
            and centite=ceentite and cprocess='".$req2[0]."'group by cregate order by cregate";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->nbLigne($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Entités participant au processus Distribution'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
		tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Charges directes de processus"));
	$Form->ajoutTexte (tblentete("Charges transverses retraitées"));
	$Form->ajoutTexte (tblentete("Charges nettes de processus"));

	//$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblfinligne());
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");
		tblcellule($ligne3[2]);
		tblcellule(number_format($ligne3[4],0 , ' ' ,  ' ' ));
		tblcellule("");
		tblcellule(number_format($ligne3[4],0 , ' ' ,  ' ' ));//+audessu
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
function formentdistcharge()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Charges"=>"entdistcharge.php",
					  "Trafics"=>"entdistrafic.php",
				      "Coûts unitaires"=>"entdistunit.php",
				      "Coûts unitaires / natures"=>"entdistuninat.php");
	/*
	   //champliste deroulant pour annee et periode
	   $requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
	   $resultat2 = $bd->execRequete ($requete2);
	   $nomb_ligne2=mysql_num_rows($resultat2);
	   $listannee[0]="";
	   for ($j=0;$j<$nomb_ligne2;$j++)
	   {
	   $ligne2=$bd->objetSuivant($resultat2);
	   $listannee[$ligne2->can]=$ligne2->liban;
	   }
	   $requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
	   $resultat = $bd->execRequete ($requete);
	   $nomb_ligne=mysql_num_rows($resultat);
	   $listPerio[0]="";
	   for ($j=0;$j<$nomb_ligne;$j++)
	   {
	   $ligne=$bd->objetSuivant($resultat);
	   $listPerio[$ligne->cperi]=$ligne->libperi;
	   }
	   $Form->debuttable();
	   $Form->champliste ("Ann�e :", "ann", "", 1, $listannee);
	   $Form->champliste ("P�riode :", "perio", "", 1, $listPerio);
	   $Form->champvalider ("Valider", "valider");
	   //$Form->fin();
	*/

	//requete recup processus
	$req = "select cprocess
				from processus
					where libprocess like 'DISTRIBUTION'";
	$res = $bd->execRequete ($req);
	$req2=$bd->ligTabSuivant($res);

	//requete sql alimentant tableau
	$requete3 = "select distinct cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
	from domaine,processus,sousprocessus,caa,charcaa,entite
		where cordaffich<>'0'
			and cdom=cedom and cprocess=ceprocess
            and cssprocess=cessprocess and ccaa=cecaa
            and centite=ceentite and cprocess='".$req2[0]."'group by cregate order by cregate";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->nbLigne($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Charges du Processus : Distribution'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
		tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Charges directes de processus"));
	$Form->ajoutTexte (tblentete("Charges transverses retrait�es"));
	$Form->ajoutTexte (tblentete("Charges nettes de processus"));

	//$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblfinligne());
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");
		tblcellule($ligne3[2]);
		tblcellule(number_format($ligne3[4],0 , ' ' ,  ' ' ));
		tblcellule("");
		tblcellule(number_format($ligne3[4],0 , ' ' ,  ' ' ));//+audessu
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
    }
function formentdistrafic()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Charges"=>"entdistcharge.php",
					  "Trafics"=>"entdistrafic.php",
				      "Coûts unitaires"=>"entdistunit.php",
				      "Coûts unitaires / natures"=>"entdistuninat.php");
	/*
	   //champliste deroulant pour annee et periode
	   $requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
	   $resultat2 = $bd->execRequete ($requete2);
	   $nomb_ligne2=mysql_num_rows($resultat2);
	   $listannee[0]="";
	   for ($j=0;$j<$nomb_ligne2;$j++)
	   {
	   $ligne2=$bd->objetSuivant($resultat2);
	   $listannee[$ligne2->can]=$ligne2->liban;
	   }
	   $requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
	   $resultat = $bd->execRequete ($requete);
	   $nomb_ligne=mysql_num_rows($resultat);
	   $listPerio[0]="";
	   for ($j=0;$j<$nomb_ligne;$j++)
	   {
	   $ligne=$bd->objetSuivant($resultat);
	   $listPerio[$ligne->cperi]=$ligne->libperi;
	   }
	   $Form->debuttable();
	   $Form->champliste ("Ann�e :", "ann", "", 1, $listannee);
	   $Form->champliste ("P�riode :", "perio", "", 1, $listPerio);
	   $Form->champvalider ("Valider", "valider");
	   //$Form->fin();
	*/
	//requete sql alimentant tableau
	$requete3 = "select distinct concat(cregate,' - ',libentite),sum(trafdistribm),sum(trafdistribc),cregate
		from pildi,entite
		where centite=pildi.ceentite and (trafdistribm<>0 or trafdistribc<>0)
            group by cregate order by cregate";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->nbLigne($resultat3);
	
	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Trafic du Processus : Distribution'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Trafics"));
	$Form->ajoutTexte (tblentete("Dont Plis"));
	$Form->ajoutTexte (tblentete("Dont IP"));
	$Form->ajoutTexte (tblentete("Dont Colis"));
		$requete4 = "select sum(trafpna)
		from pna,entite
		where cregate='".$ligne3[3]."' and trafpna<>0 and centite=ceentite group by ceentite";
		$resultat4 = $bd->execRequete ($requete4);
		$ligne4=$bd->ligTabSuivant($resultat4);

		$requete5 = "select sum(trafcolis)
		from colis,entite
		where cregate='".$ligne3[3]."' and trafcolis<>0 and centite=ceentite group by ceentite";
		$resultat5 = $bd->execRequete ($requete5);
		$ligne5=$bd->ligTabSuivant($resultat5);
	//$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblfinligne());
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");



		tblcellule($ligne3[0]);
		tblcellule(number_format($ligne3[1]+$ligne3[2]+$ligne4[0]+$ligne5[0],0 , ' ' ,  ' ' ));
		tblcellule(number_format($ligne3[1]+$ligne3[2],0 , ' ' ,  ' ' ));
		tblcellule($ligne4[0]);
		tblcellule(number_format($ligne5[0],0 , ' ' ,  ' ' ));
	//	tblcellule(number_format($ligne3[4],0 , ' ' ,  ' ' ));//+audessu
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
    }
function formentdistunit()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Charges"=>"entdistcharge.php",
					  "Trafics"=>"entdistrafic.php",
				      "Coûts unitaires"=>"entdistunit.php",
				      "Coûts unitaires / natures"=>"entdistuninat.php");
	/*
	   //champliste deroulant pour annee et periode
	   $requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
	   $resultat2 = $bd->execRequete ($requete2);
	   $nomb_ligne2=mysql_num_rows($resultat2);
	   $listannee[0]="";
	   for ($j=0;$j<$nomb_ligne2;$j++)
	   {
	   $ligne2=$bd->objetSuivant($resultat2);
	   $listannee[$ligne2->can]=$ligne2->liban;
	   }
	   $requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
	   $resultat = $bd->execRequete ($requete);
	   $nomb_ligne=mysql_num_rows($resultat);
	   $listPerio[0]="";
	   for ($j=0;$j<$nomb_ligne;$j++)
	   {
	   $ligne=$bd->objetSuivant($resultat);
	   $listPerio[$ligne->cperi]=$ligne->libperi;
	   }
	   $Form->debuttable();
	   $Form->champliste ("Ann�e :", "ann", "", 1, $listannee);
	   $Form->champliste ("P�riode :", "perio", "", 1, $listPerio);
	   $Form->champvalider ("Valider", "valider");
	   //$Form->fin();
	*/
	$req = "select cprocess
				from processus
					where libprocess like 'DISTRIBUTION'";
	$res = $bd->execRequete ($req);
	$req2=$bd->ligTabSuivant($res);
	//requete sql alimentant tableau
$requete3 = "select distinct concat(cregate,' - ',libentite),libprocess,sum(charcaa),cregate
	from processus,sousprocessus,caa,charcaa,entite
		where cprocess=ceprocess
            and cssprocess=cessprocess and ccaa=cecaa
            and centite=ceentite and cprocess='".$req2[0]."'group by cregate order by cregate";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->nbLigne($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Coût Unitaire du Processus : Distribution'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Coût unitaire direct du processus"));
	$Form->ajoutTexte (tblentete("Coût unitaire complet du processus"));
	//$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblfinligne());
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		//cout unitaire direct
		if($ligne3[2]<>0)
		{
        $requete4 = "select sum(charcaa),sum(trafdistribm),sum(trafdistribc)
						from pildi,entite,charcaa
							where cregate='".$ligne3[3]."'
								and (trafdistribm<>0 or trafdistribc<>0)
								and charcaa<>0
								and centite=charcaa.ceentite
								and centite=pildi.ceentite
									group by pildi.ceentite";
		$resultat4 = $bd->execRequete ($requete4);
		$ligne4=$bd->ligTabSuivant($resultat4);
   		//cout unitaire indirect
			$requete5 = "select sum(charcaa),sum(trafdistribm),sum(trafdistribc)
						from pildi,entite,charcaa
							where cregate='".$ligne3[3]."'
								and (trafdistribm<>0 or trafdistribc<>0)
								and charcaa<>0
								and centite=charcaa.ceentite
								and centite=pildi.ceentite
									group by pildi.ceentite";
		$resultat5 = $bd->execRequete ($requete5);
		$ligne5=$bd->ligTabSuivant($resultat5);
		tbldebutligne("A0");
	//tblcellule(number_format($ligne3[3],0 , ' ' ,  ' ' ));
		tblcellule($ligne3[0]);
			if($ligne4[0]<>0 and ($ligne4[1]+$ligne4[2]<>0))
			{
				tblcellule(number_format($ligne4[0]/($ligne4[1]+$ligne4[2]),5,',' , ' ' ));
			}
			   else
			    {
				    if($ligne4[1]+$ligne4[2]==0)
				   {
					    tblcellule("Pas de trafic");
				   }
		        }
            if   (($ligne5[1]+$ligne5[2])<>0)
		{tblcellule($ligne5[0]/($ligne5[1]+$ligne5[2])); }
		tblfinligne();
		}
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
function formentdistuninat()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Charges"=>"entdistcharge.php",
					  "Trafics"=>"entdistrafic.php",
				      "Coûts unitaires"=>"entdistunit.php",
				      "Coûts unitaires / natures"=>"entdistuninat.php");
	/*
	   //champliste deroulant pour annee et periode
	   $requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
	   $resultat2 = $bd->execRequete ($requete2);
	   $nomb_ligne2=mysql_num_rows($resultat2);
	   $listannee[0]="";
	   for ($j=0;$j<$nomb_ligne2;$j++)
	   {
	   $ligne2=$bd->objetSuivant($resultat2);
	   $listannee[$ligne2->can]=$ligne2->liban;
	   }
	   $requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
	   $resultat = $bd->execRequete ($requete);
	   $nomb_ligne=mysql_num_rows($resultat);
	   $listPerio[0]="";
	   for ($j=0;$j<$nomb_ligne;$j++)
	   {
	   $ligne=$bd->objetSuivant($resultat);
	   $listPerio[$ligne->cperi]=$ligne->libperi;
	   }
	   $Form->debuttable();
	   $Form->champliste ("Ann�e :", "ann", "", 1, $listannee);
	   $Form->champliste ("P�riode :", "perio", "", 1, $listPerio);
	   $Form->champvalider ("Valider", "valider");
	   //$Form->fin();
	*/

	//requete recup processus
	$req = "select cprocess
				from processus
					where libprocess like 'DISTRIBUTION'";
	$res = $bd->execRequete ($req);
	$req2=$bd->ligTabSuivant($res);

	//requete sql alimentant tableau
	$requete3 = "select distinct cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
	from domaine,processus,sousprocessus,caa,charcaa,entite
		where cordaffich<>'0'
			and cdom=cedom and cprocess=ceprocess
            and cssprocess=cessprocess and ccaa=cecaa
            and centite=ceentite and cprocess='".$req2[0]."'group by cregate order by cregate";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->nbLigne($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Coût Unitaire / Nature du Processus : Distribution'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
		tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Charges de Personnels Structurelles"));
	$Form->ajoutTexte (tblentete("Charges de Personnels Conjoncturelles"));
	$Form->ajoutTexte (tblentete("Charges de Fonctionnements"));
	$Form->ajoutTexte (tblentete("Charges de Véhicules"));
	$Form->ajoutTexte (tblentete("Charges Immobilières"));
	$Form->ajoutTexte (tblentete("CAA Tranverses"));

	//$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblfinligne());
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");
		tblcellule($ligne3[2]);
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
    //Pages entite traitement
function formenttrait()
    {
	//require("pconnect.php");
    // Cr�ation du formulaire
    $bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
    $Form = new formulaire ("POST", "","periode");
    // Tableau en mode vertical, pour les champs simples
    $menuclien=array( "Charges"=>"entraicharge.php",
				  "Trafics"=>"entraitrafic.php",
			      "Coûts unitaires"=>"entraitunit.php",
			      "Coûts unitaires / natures"=>"entraituninat.php");
/*
   //champliste deroulant pour annee et periode
   $requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
   $resultat2 = $bd->execRequete ($requete2);
   $nomb_ligne2=mysql_num_rows($resultat2);
   $listannee[0]="";
   for ($j=0;$j<$nomb_ligne2;$j++)
   {
   $ligne2=$bd->objetSuivant($resultat2);
   $listannee[$ligne2->can]=$ligne2->liban;
   }
   $requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
   $resultat = $bd->execRequete ($requete);
   $nomb_ligne=mysql_num_rows($resultat);
   $listPerio[0]="";
   for ($j=0;$j<$nomb_ligne;$j++)
   {
   $ligne=$bd->objetSuivant($resultat);
   $listPerio[$ligne->cperi]=$ligne->libperi;
   }
   $Form->debuttable();
   $Form->champliste ("Ann�e :", "ann", "", 1, $listannee);
   $Form->champliste ("P�riode :", "perio", "", 1, $listPerio);
   $Form->champvalider ("Valider", "valider");
   //$Form->fin();
*/

//requete recup processus
$req = "select cprocess
				from processus
					where libprocess like 'TRAITEMENT'";
$res = $bd->execRequete ($req);
$req2=$bd->ligTabSuivant($res);

//requete sql alimentant tableau
$requete3 = "select distinct cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
	from domaine,processus,sousprocessus,caa,charcaa,entite
		where cordaffich<>'0'
			and cdom=cedom and cprocess=ceprocess
            and cssprocess=cessprocess and ccaa=cecaa
            and centite=ceentite and cprocess='".$req2[0]."'group by cregate order by cregate";
$resultat3 = $bd->execRequete ($requete3);
$nomb_ligne3=$bd->nbLigne($resultat3);
$Form = new formulaire ("POST", "","suivconnex");
echo "<CENTER><H1>".'Entités participant au processus Traitement'."</H1></CENTER>\n";
//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
tbldebut(0,"100%");tbldebutligne("MENU");
//choix des menus
foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
tblfin();
tblcellule(image('interface/redbar.jpg',"100%",1));
// Tableau en mode vertical, pour les champs simples
$Form->debuttable();
$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
$Form->ajoutTexte (tbldebut(1,"100%"));
$Form->ajoutTexte (tbldebutligne());
$Form->ajoutTexte (tblentete(""));
$Form->ajoutTexte (tblentete("Charges directes de processus"));
$Form->ajoutTexte (tblentete("Charges transverses retraitées"));
$Form->ajoutTexte (tblentete("Charges nettes de processus"));

//$Form->ajoutTexte (tblentete(""));
$Form->ajoutTexte (tblfinligne());
for ($j=0;$j<$nomb_ligne3;$j++)
{
	$ligne3=$bd->ligTabSuivant($resultat3);
	tbldebutligne("A0");
	tblcellule($ligne3[2]);
	tblcellule(number_format($ligne3[4],0 , ' ' ,  ' ' ));
	tblcellule("");
	tblcellule(number_format($ligne3[4],0 , ' ' ,  ' ' ));//+audessu
	tblfinligne();
}
$Form->debuttable(tblfinligne());
$Form->fintable();
$Form->ajoutTexte (tblfin());
$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
$Form->ajoutTexte ("<P></P>");
//$Form->fin();
echo $Form->formulaireHTML();
    }
function formentraicharge()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Charges"=>"entraicharge.php",
				  "Trafics"=>"entraitrafic.php",
			      "Coûts unitaires"=>"entraitunit.php",
			      "Coûts unitaires / natures"=>"entraituninat.php");
	/*
	   //champliste deroulant pour annee et periode
	   $requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
	   $resultat2 = $bd->execRequete ($requete2);
	   $nomb_ligne2=mysql_num_rows($resultat2);
	   $listannee[0]="";
	   for ($j=0;$j<$nomb_ligne2;$j++)
	   {
	   $ligne2=$bd->objetSuivant($resultat2);
	   $listannee[$ligne2->can]=$ligne2->liban;
	   }
	   $requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
	   $resultat = $bd->execRequete ($requete);
	   $nomb_ligne=mysql_num_rows($resultat);
	   $listPerio[0]="";
	   for ($j=0;$j<$nomb_ligne;$j++)
	   {
	   $ligne=$bd->objetSuivant($resultat);
	   $listPerio[$ligne->cperi]=$ligne->libperi;
	   }
	   $Form->debuttable();
	   $Form->champliste ("Ann�e :", "ann", "", 1, $listannee);
	   $Form->champliste ("P�riode :", "perio", "", 1, $listPerio);
	   $Form->champvalider ("Valider", "valider");
	   //$Form->fin();
	*/

	//requete recup processus
	$req = "select cprocess
				from processus
					where libprocess like 'TRAITEMENT'";
	$res = $bd->execRequete ($req);
	$req2=$bd->ligTabSuivant($res);

	//requete sql alimentant tableau
	$requete3 = "select distinct cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
	from domaine,processus,sousprocessus,caa,charcaa,entite
		where cordaffich<>'0'
			and cdom=cedom and cprocess=ceprocess
            and cssprocess=cessprocess and ccaa=cecaa
            and centite=ceentite and cprocess='".$req2[0]."'group by cregate order by cregate";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->nbLigne($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Charges du Processus : Traitement'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
		tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Charges directes de processus"));
	$Form->ajoutTexte (tblentete("Charges transverses retraitées"));
	$Form->ajoutTexte (tblentete("Charges nettes de processus"));

	//$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblfinligne());
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");
		tblcellule($ligne3[2]);
		tblcellule(number_format($ligne3[4],0 , ' ' ,  ' ' ));
		tblcellule("");
		tblcellule(number_format($ligne3[4],0 , ' ' ,  ' ' ));//+audessu
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
function formentraitrafic()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Charges"=>"entraicharge.php",
				  "Trafics"=>"entraitrafic.php",
			      "Coûts unitaires"=>"entraitunit.php",
			      "Coûts unitaires / natures"=>"entraituninat.php");
	/*
	   //champliste deroulant pour annee et periode
	   $requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
	   $resultat2 = $bd->execRequete ($requete2);
	   $nomb_ligne2=mysql_num_rows($resultat2);
	   $listannee[0]="";
	   for ($j=0;$j<$nomb_ligne2;$j++)
	   {
	   $ligne2=$bd->objetSuivant($resultat2);
	   $listannee[$ligne2->can]=$ligne2->liban;
	   }
	   $requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
	   $resultat = $bd->execRequete ($requete);
	   $nomb_ligne=mysql_num_rows($resultat);
	   $listPerio[0]="";
	   for ($j=0;$j<$nomb_ligne;$j++)
	   {
	   $ligne=$bd->objetSuivant($resultat);
	   $listPerio[$ligne->cperi]=$ligne->libperi;
	   }
	   $Form->debuttable();
	   $Form->champliste ("Ann�e :", "ann", "", 1, $listannee);
	   $Form->champliste ("P�riode :", "perio", "", 1, $listPerio);
	   $Form->champvalider ("Valider", "valider");
	   //$Form->fin();
	*/

	/*requete recup processus
	//$req = "select cprocess
	//from processus
	//where libprocess like 'DISTRIBUTION'";
	//$res = $bd->execRequete ($req);
	//$req2=$bd->ligTabSuivant($res);

	//requete sql alimentant tableau*/
	/*$requete3 = "select distinct concat(cregate,' - ',libentite),sum(trafdistribm),sum(trafdistribc),sum(trafpna),sum(trafcolis)
	   from colis,pildi,pna,entite
	   where(centite=pildi.ceentite or centite=pna.ceentite or centite=colis.ceentite)
	   group by cregate order by cregate";*/

	$requete3 = "select distinct concat(cregate,' - ',libentite),cregate
		from entite,pildi,syspeo
		where (centite=pildi.ceentite or centite=syspeo.ceentite)
		and ((trafdistribm<>0 or trafdistribc<>0)or(traftraitd<>0 or traftraita<>0))
            group by cregate order by cregate";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->nbLigne($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Trafic du Processus : Traitement'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
		tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Trafics"));
	$Form->ajoutTexte (tblentete("Pildi"));
	$Form->ajoutTexte (tblentete("Syspeo"));
	//$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblfinligne());
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");

		$requete4 = "select sum(trafdistribm),sum(trafdistribc)
		from pildi,entite
		where cregate='".$ligne3[1]."' and (trafdistribm<>0 or trafdistribc<>0) and centite=ceentite group by ceentite";
		$resultat4 = $bd->execRequete ($requete4);
		$ligne4=$bd->ligTabSuivant($resultat4);

		$requete5 = "select sum(traftraitd), sum(traftraita)
		from syspeo,entite
		where cregate='".$ligne3[1]."' and (traftraitd<>0 or traftraita<>0)and centite=ceentite group by ceentite";
		$resultat5 = $bd->execRequete ($requete5);
		$ligne5=$bd->ligTabSuivant($resultat5);

		tblcellule($ligne3[0]);
		tblcellule(number_format($ligne4[0]+$ligne5[0]+$ligne4[1]+$ligne5[1],0 , ' ' ,  ' ' ));
		tblcellule(number_format($ligne4[0]+$ligne4[1],0 , ' ' ,  ' ' ));
		tblcellule(number_format($ligne5[0]+$ligne5[1],0 , ' ' ,  ' ' ));
		//tblcellule(number_format($ligne5[0],0 , ' ' ,  ' ' ));
		//tblcellule(number_format($ligne3[4],0 , ' ' ,  ' ' ));//+audessu
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
function formentraitunit()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Charges"=>"entraicharge.php",
				  "Trafics"=>"entraitrafic.php",
			      "Coûts unitaires"=>"entraitunit.php",
			      "Coûts unitaires / natures"=>"entraituninat.php");
	/*
	   //champliste deroulant pour annee et periode
	   $requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
	   $resultat2 = $bd->execRequete ($requete2);
	   $nomb_ligne2=mysql_num_rows($resultat2);
	   $listannee[0]="";
	   for ($j=0;$j<$nomb_ligne2;$j++)
	   {
	   $ligne2=$bd->objetSuivant($resultat2);
	   $listannee[$ligne2->can]=$ligne2->liban;
	   }
	   $requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
	   $resultat = $bd->execRequete ($requete);
	   $nomb_ligne=mysql_num_rows($resultat);
	   $listPerio[0]="";
	   for ($j=0;$j<$nomb_ligne;$j++)
	   {
	   $ligne=$bd->objetSuivant($resultat);
	   $listPerio[$ligne->cperi]=$ligne->libperi;
	   }
	   $Form->debuttable();
	   $Form->champliste ("Ann�e :", "ann", "", 1, $listannee);
	   $Form->champliste ("P�riode :", "perio", "", 1, $listPerio);
	   $Form->champvalider ("Valider", "valider");
	   //$Form->fin();
	*/

	$req = "select cprocess
				from processus
					where libprocess like 'TRAITEMENT'";
	$res = $bd->execRequete ($req);
	$req2=$bd->ligTabSuivant($res);

	//requete sql alimentant tableau
$requete3 = "select distinct concat(cregate,' - ',libentite),libprocess,sum(charcaa),cregate
	from processus,sousprocessus,caa,charcaa,entite
		where cprocess=ceprocess
            and cssprocess=cessprocess and ccaa=cecaa
            and centite=ceentite and cprocess='".$req2[0]."'group by cregate order by cregate";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->nbLigne($resultat3);

	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Coût Unitaire du Processus : Traitement'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
		tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Coût unitaire direct du processus"));
	$Form->ajoutTexte (tblentete("Coût unitaire complet du processus"));

	//$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblfinligne());
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		//cout unitaire direct
		if($ligne3[2]<>0)
		{
			$requete4 = "select sum(charcaa),sum(trafdistribm),sum(trafdistribc)
						from pildi,entite,charcaa
							where cregate='".$ligne3[3]."'
								and (trafdistribm<>0 or trafdistribc<>0)
								and charcaa<>0
								and centite=charcaa.ceentite
								and centite=pildi.ceentite
									group by pildi.ceentite";
			$resultat4 = $bd->execRequete ($requete4);
			$ligne4=$bd->ligTabSuivant($resultat4);

			//cout unitaire indirect
			$requete5 = "select sum(charcaa),sum(trafdistribm),sum(trafdistribc)
						from pildi,entite,charcaa
							where cregate='".$ligne3[3]."'
								and (trafdistribm<>0 or trafdistribc<>0)
								and charcaa<>0
								and centite=charcaa.ceentite
								and centite=pildi.ceentite
									group by pildi.ceentite";
			$resultat5 = $bd->execRequete ($requete5);
			$ligne5=$bd->ligTabSuivant($resultat5);

			tbldebutligne("A0");
			//	tblcellule(number_format($ligne3[3],0 , ' ' ,  ' ' ));
			tblcellule($ligne3[0]);
			if($ligne4[0]<>0 and ($ligne4[1]+$ligne4[2]<>0))
			{
				tblcellule(number_format($ligne4[0]/($ligne4[1]+$ligne4[2]),5,',' , ' ' ));
			}
			else
			{
				if($ligne4[1]+$ligne4[2]==0)
				{
					tblcellule("Pas de trafic");
				}
			}
             if( ($ligne5[1]+$ligne5[2]) <>0)
			{tblcellule($ligne5[0]/($ligne5[1]+$ligne5[2]));}
			tblfinligne();
		}
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
function formentraituninat()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Charges"=>"entraicharge.php",
				  "Trafics"=>"entraitrafic.php",
			      "Coûts unitaires"=>"entraitunit.php",
			      "Coûts unitaires / natures"=>"entraituninat.php");
	/*
	   //champliste deroulant pour annee et periode
	   $requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
	   $resultat2 = $bd->execRequete ($requete2);
	   $nomb_ligne2=mysql_num_rows($resultat2);
	   $listannee[0]="";
	   for ($j=0;$j<$nomb_ligne2;$j++)
	   {
	   $ligne2=$bd->objetSuivant($resultat2);
	   $listannee[$ligne2->can]=$ligne2->liban;
	   }
	   $requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
	   $resultat = $bd->execRequete ($requete);
	   $nomb_ligne=mysql_num_rows($resultat);
	   $listPerio[0]="";
	   for ($j=0;$j<$nomb_ligne;$j++)
	   {
	   $ligne=$bd->objetSuivant($resultat);
	   $listPerio[$ligne->cperi]=$ligne->libperi;
	   }
	   $Form->debuttable();
	   $Form->champliste ("Ann�e :", "ann", "", 1, $listannee);
	   $Form->champliste ("P�riode :", "perio", "", 1, $listPerio);
	   $Form->champvalider ("Valider", "valider");
	   //$Form->fin();
	*/

	//requete recup processus
	$req = "select cprocess
				from processus
					where libprocess like 'DISTRIBUTION'";
	$res = $bd->execRequete ($req);
	$req2=$bd->ligTabSuivant($res);

	//requete sql alimentant tableau
	$requete3 = "select distinct cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
	from domaine,processus,sousprocessus,caa,charcaa,entite
		where cordaffich<>'0'
			and cdom=cedom and cprocess=ceprocess
            and cssprocess=cessprocess and ccaa=cecaa
            and centite=ceentite and cprocess='".$req2[0]."'group by cregate order by cregate";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->nbLigne($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Coût Unitaire / Nature du Processus : Traitement'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
		tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Charges de Personnels Structurelles"));
	$Form->ajoutTexte (tblentete("Charges de Personnels Conjoncturelles"));
	$Form->ajoutTexte (tblentete("Charges de Fonctionnements"));
	$Form->ajoutTexte (tblentete("Charges de Véhicules"));
	$Form->ajoutTexte (tblentete("Charges Immobilières"));
	$Form->ajoutTexte (tblentete("CAA Tranverses"));

	//$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblfinligne());
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");
		tblcellule($ligne3[2]);
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
    //Pages entite concentration
function formentconc()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Charges"=>"entconcharge.php",
				  "Trafics"=>"entconctrafic.php",
			      "Coûts unitaires"=>"entconcunit.php",
			      "Coûts unitaires / natures"=>"entconcuninat.php");
	/*
	   //champliste deroulant pour annee et periode
	   $requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
	   $resultat2 = $bd->execRequete ($requete2);
	   $nomb_ligne2=mysql_num_rows($resultat2);
	   $listannee[0]="";
	   for ($j=0;$j<$nomb_ligne2;$j++)
	   {
	   $ligne2=$bd->objetSuivant($resultat2);
	   $listannee[$ligne2->can]=$ligne2->liban;
	   }
	   $requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
	   $resultat = $bd->execRequete ($requete);
	   $nomb_ligne=mysql_num_rows($resultat);
	   $listPerio[0]="";
	   for ($j=0;$j<$nomb_ligne;$j++)
	   {
	   $ligne=$bd->objetSuivant($resultat);
	   $listPerio[$ligne->cperi]=$ligne->libperi;
	   }
	   $Form->debuttable();
	   $Form->champliste ("Ann�e :", "ann", "", 1, $listannee);
	   $Form->champliste ("P�riode :", "perio", "", 1, $listPerio);
	   $Form->champvalider ("Valider", "valider");
	   //$Form->fin();
	*/

	//requete recup processus
	$req = "select cprocess
				from processus
					where libprocess like 'CONCENTRATION'";
	$res = $bd->execRequete ($req);
	$req2=$bd->ligTabSuivant($res);

	//requete sql alimentant tableau
	$requete3 = "select distinct cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
	from domaine,processus,sousprocessus,caa,charcaa,entite
		where cordaffich<>'0'
			and cdom=cedom and cprocess=ceprocess
            and cssprocess=cessprocess and ccaa=cecaa
            and centite=ceentite and cprocess='".$req2[0]."'group by cregate order by cregate";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->nbLigne($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Entités participant au processus Concentration'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
		tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Charges directes de processus"));
	$Form->ajoutTexte (tblentete("Charges transverses retraitées"));
	$Form->ajoutTexte (tblentete("Charges nettes de processus"));

	//$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblfinligne());
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");
		tblcellule($ligne3[2]);
		tblcellule(number_format($ligne3[4],0 , ' ' ,  ' ' ));
		tblcellule("");
		tblcellule(number_format($ligne3[4],0 , ' ' ,  ' ' ));//+audessu
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
    }
function formentconcharge()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Charges"=>"entconcharge.php",
				  "Trafics"=>"entconctrafic.php",
			      "Coûts unitaires"=>"entconcunit.php",
			      "Coûts unitaires / natures"=>"entconcuninat.php");
	/*
	   //champliste deroulant pour annee et periode
	   $requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
	   $resultat2 = $bd->execRequete ($requete2);
	   $nomb_ligne2=mysql_num_rows($resultat2);
	   $listannee[0]="";
	   for ($j=0;$j<$nomb_ligne2;$j++)
	   {
	   $ligne2=$bd->objetSuivant($resultat2);
	   $listannee[$ligne2->can]=$ligne2->liban;
	   }
	   $requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
	   $resultat = $bd->execRequete ($requete);
	   $nomb_ligne=mysql_num_rows($resultat);
	   $listPerio[0]="";
	   for ($j=0;$j<$nomb_ligne;$j++)
	   {
	   $ligne=$bd->objetSuivant($resultat);
	   $listPerio[$ligne->cperi]=$ligne->libperi;
	   }
	   $Form->debuttable();
	   $Form->champliste ("Ann�e :", "ann", "", 1, $listannee);
	   $Form->champliste ("P�riode :", "perio", "", 1, $listPerio);
	   $Form->champvalider ("Valider", "valider");
	   //$Form->fin();
	*/

	//requete recup processus
	$req = "select cprocess
				from processus
					where libprocess like 'CONCENTRATION'";
	$res = $bd->execRequete ($req);
	$req2=$bd->ligTabSuivant($res);

	//requete sql alimentant tableau
	$requete3 = "select distinct cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
	from domaine,processus,sousprocessus,caa,charcaa,entite
		where cordaffich<>'0'
			and cdom=cedom and cprocess=ceprocess
            and cssprocess=cessprocess and ccaa=cecaa
            and centite=ceentite and cprocess='".$req2[0]."'group by cregate order by cregate";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->nbLigne($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Charges du Processus : Concentration'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
		tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Charges directes de processus"));
	$Form->ajoutTexte (tblentete("Charges transverses retraitées"));
	$Form->ajoutTexte (tblentete("Charges nettes de processus"));

	//$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblfinligne());
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");
		tblcellule($ligne3[2]);
		tblcellule(number_format($ligne3[4],0 , ' ' ,  ' ' ));
		tblcellule("");
		tblcellule(number_format($ligne3[4],0 , ' ' ,  ' ' ));//+audessu
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
    }
function formentconctrafic()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Charges"=>"entconcharge.php",
				  "Trafics"=>"entconctrafic.php",
			      "Coûts unitaires"=>"entconcunit.php",
			      "Coûts unitaires / natures"=>"entconcuninat.php");

	$requete3 = "select distinct concat(cregate,' - ',libentite),cregate
		from entite,pildi,syspeo
		where (centite=pildi.ceentite or centite=syspeo.ceentite)
		and pildi.trafconc<>0 and syspeo.trafconc<>0
            group by cregate order by cregate";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->nbLigne($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Trafic du Processus : Concentration'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");
	tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Trafics"));
	$Form->ajoutTexte (tblentete("Pildi"));
	$Form->ajoutTexte (tblentete("Syspeo"));
	//$Form->ajoutTexte (tblentete("Dont Colis"));

	//$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblfinligne());
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");

		$requete4 = "select sum(trafconc)
		from pildi,entite
		where cregate='".$ligne3[0]."' and trafconc<>0 and centite=ceentite group by ceentite";
		$resultat4 = $bd->execRequete ($requete4);
		$ligne4=$bd->ligTabSuivant($resultat4);

		$requete5 = "select sum(trafconc)
		from syspeo,entite
		where cregate='".$ligne3[0]."' and trafconc<>0 and centite=ceentite group by ceentite";
		$resultat5 = $bd->execRequete ($requete5);
		$ligne5=$bd->ligTabSuivant($resultat5);

		tblcellule($ligne3[0]);
		tblcellule(number_format($ligne4[0]+$ligne5[0],0 , ' ' ,  ' ' ));
		tblcellule(number_format($ligne4[0],0 , ' ' ,  ' ' ));
		tblcellule(number_format($ligne5[0],0 , ' ' , ' ' ));
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
    }
function formentconcunit()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Charges"=>"entconcharge.php",
				  "Trafics"=>"entconctrafic.php",
			      "Coûts unitaires"=>"entconcunit.php",
			      "Coûts unitaires / natures"=>"entconcuninat.php");
	/*
	   //champliste deroulant pour annee et periode
	   $requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
	   $resultat2 = $bd->execRequete ($requete2);
	   $nomb_ligne2=mysql_num_rows($resultat2);
	   $listannee[0]="";
	   for ($j=0;$j<$nomb_ligne2;$j++)
	   {
	   $ligne2=$bd->objetSuivant($resultat2);
	   $listannee[$ligne2->can]=$ligne2->liban;
	   }
	   $requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
	   $resultat = $bd->execRequete ($requete);
	   $nomb_ligne=mysql_num_rows($resultat);
	   $listPerio[0]="";
	   for ($j=0;$j<$nomb_ligne;$j++)
	   {
	   $ligne=$bd->objetSuivant($resultat);
	   $listPerio[$ligne->cperi]=$ligne->libperi;
	   }
	   $Form->debuttable();
	   $Form->champliste ("Ann�e :", "ann", "", 1, $listannee);
	   $Form->champliste ("P�riode :", "perio", "", 1, $listPerio);
	   $Form->champvalider ("Valider", "valider");
	   //$Form->fin();
	*/

	$req = "select cprocess
				from processus
					where libprocess like 'CONCENTRATION'";
	$res = $bd->execRequete ($req);
	$req2=$bd->ligTabSuivant($res);

	//requete sql alimentant tableau
$requete3 = "select distinct concat(cregate,' - ',libentite),libprocess,sum(charcaa),cregate
	from processus,sousprocessus,caa,charcaa,entite
		where cprocess=ceprocess
            and cssprocess=cessprocess and ccaa=cecaa
            and centite=ceentite and cprocess='".$req2[0]."'group by cregate order by cregate";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->nbLigne($resultat3);


	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Coût Unitaire du Processus : Concentration'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
		tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Coût unitaire direct du processus"));
	$Form->ajoutTexte (tblentete("Coût unitaire complet du processus"));

	//$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblfinligne());
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		//cout unitaire direct
		if($ligne3[2]<>0)
		{
			$requete4 = "select sum(charcaa),sum(trafdistribm),sum(trafdistribc)
						from pildi,entite,charcaa
							where cregate='".$ligne3[3]."'
								and (trafdistribm<>0 or trafdistribc<>0)
								and charcaa<>0
								and centite=charcaa.ceentite
								and centite=pildi.ceentite
									group by pildi.ceentite";
			$resultat4 = $bd->execRequete ($requete4);
			$ligne4=$bd->ligTabSuivant($resultat4);

			//cout unitaire indirect
			$requete5 = "select sum(charcaa),sum(trafdistribm),sum(trafdistribc)
						from pildi,entite,charcaa
							where cregate='".$ligne3[3]."'
								and (trafdistribm<>0 or trafdistribc<>0)
								and charcaa<>0
								and centite=charcaa.ceentite
								and centite=pildi.ceentite
									group by pildi.ceentite";
			$resultat5 = $bd->execRequete ($requete5);
			$ligne5=$bd->ligTabSuivant($resultat5);

			tbldebutligne("A0");
			//	tblcellule(number_format($ligne3[3],0 , ' ' ,  ' ' ));
			tblcellule($ligne3[0]);
			if($ligne4[0]<>0 and ($ligne4[1]+$ligne4[2]<>0))
			{
				tblcellule(number_format($ligne4[0]/($ligne4[1]+$ligne4[2]),5,',' , ' ' ));
			}
			else
			{
				if($ligne4[1]+$ligne4[2]==0)
				{
					tblcellule("Pas de trafic");
				}
			}
			 if(($ligne5[1]+$ligne5[2])<>0)
            {tblcellule($ligne5[0]/($ligne5[1]+$ligne5[2])); }
			tblfinligne();
		}
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
    }
function formentconcuninat()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array( "Charges"=>"entconcharge.php",
				  "Trafics"=>"entconctrafic.php",
			      "Coûts unitaires"=>"entconcunit.php",
			      "Coûts unitaires / natures"=>"entconcuninat.php");
	/*
	   //champliste deroulant pour annee et periode
	   $requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
	   $resultat2 = $bd->execRequete ($requete2);
	   $nomb_ligne2=mysql_num_rows($resultat2);
	   $listannee[0]="";
	   for ($j=0;$j<$nomb_ligne2;$j++)
	   {
	   $ligne2=$bd->objetSuivant($resultat2);
	   $listannee[$ligne2->can]=$ligne2->liban;
	   }
	   $requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
	   $resultat = $bd->execRequete ($requete);
	   $nomb_ligne=mysql_num_rows($resultat);
	   $listPerio[0]="";
	   for ($j=0;$j<$nomb_ligne;$j++)
	   {
	   $ligne=$bd->objetSuivant($resultat);
	   $listPerio[$ligne->cperi]=$ligne->libperi;
	   }
	   $Form->debuttable();
	   $Form->champliste ("Ann�e :", "ann", "", 1, $listannee);
	   $Form->champliste ("P�riode :", "perio", "", 1, $listPerio);
	   $Form->champvalider ("Valider", "valider");
	   //$Form->fin();
	*/

	//requete recup processus
	$req = "select cprocess
				from processus
					where libprocess like 'DISTRIBUTION'";
	$res = $bd->execRequete ($req);
	$req2=$bd->ligTabSuivant($res);

	//requete sql alimentant tableau
	$requete3 = "select distinct cordaffich,ordscrib,concat(cregate,' - ',libentite),libprocess,sum(charcaa)
	from domaine,processus,sousprocessus,caa,charcaa,entite
		where cordaffich<>'0'
			and cdom=cedom and cprocess=ceprocess
            and cssprocess=cessprocess and ccaa=cecaa
            and centite=ceentite and cprocess='".$req2[0]."'group by cregate order by cregate";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->nbLigne($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");
	echo "<CENTER><H1>".'Coût Unitaire / Nature du Processus : Concentration'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
		tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Charges de Personnels Structurelles"));
	$Form->ajoutTexte (tblentete("Charges de Personnels Conjoncturelles"));
	$Form->ajoutTexte (tblentete("Charges de Fonctionnements"));
	$Form->ajoutTexte (tblentete("Charges de Véhicules"));
	$Form->ajoutTexte (tblentete("Charges Immobilières"));
	$Form->ajoutTexte (tblentete("CAA Tranverses"));

	//$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblfinligne());
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");
		tblcellule($ligne3[2]);
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
    }
    //Page simulation
function formsimuent()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","suivconnex");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array("Charges"=>      "charge.php",
					  "Trafics"=>"trafic.php",
				          "Co�ts unitaires"=>"cunit.php"
					);
	echo "<CENTER><H1>".'Coûts unitaires'."</H1></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
		tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	$Form->debuttable();
	// Tableau en mode vertical, pour les champs simples
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Charges brutes"));
	$Form->ajoutTexte (tblentete("Retraitement"));
	$Form->ajoutTexte (tblentete("Charges nettes"));
	$Form->ajoutTexte (tblfinligne());
	$Form->debuttable(tbldebutligne("A1"));
	$Form->debuttable(tblcellule("Distribution"));
	$Form->debuttable(tblfinligne());
	$Form->debuttable(tbldebutligne("A0"));
	$Form->debuttable(tblcellule("Distribution"));
	$Form->debuttable(tblfinligne());
	$Form->debuttable(tbldebutligne("A1"));
	$Form->debuttable(tblcellule("Travaux Internes"));
	$Form->debuttable(tblfinligne());
	$Form->debuttable(tbldebutligne("A0"));
	$Form->debuttable(tblcellule("Concentration"));
	$Form->debuttable(tblfinligne());
	$Form->debuttable(tbldebutligne("A1"));
	$Form->debuttable(tblcellule("Traitement"));
	$Form->debuttable(tbldebutligne("A0"));
	$Form->debuttable(tblcellule("Transport"));
	$Form->debuttable(tblfinligne());
	$Form->debuttable(tbldebutligne("A1"));
	$Form->debuttable(tblcellule("Support production"));
	$Form->debuttable(tblfinligne());
	$Form->debuttable(tbldebutligne("A0"));
	$Form->debuttable(tblcellule("Marketing Commercial"));
	$Form->ajoutTexte (tblfinligne());
	$Form->debuttable(tbldebutligne("A1"));
	$Form->debuttable(tblcellule("Gestion Finance"));
	$Form->debuttable(tblfinligne());
	$Form->debuttable(tbldebutligne("A0"));
	$Form->debuttable(tblcellule("RH"));
	$Form->debuttable(tblfinligne());
	$Form->debuttable(tbldebutligne("A1"));
	$Form->debuttable(tblcellule("Pilotage"));
	$Form->debuttable(tblfinligne());
	$Form->debuttable(tbldebutligne("A0"));
	$Form->debuttable(tblcellule("SI"));
	$Form->debuttable(tblfinligne());
	$Form->ajoutTexte (tblfinligne());
	$Form->debuttable(tbldebutligne("A1"));
	$Form->debuttable(tblcellule("CAA structures"));
	$Form->fintable();
	//$Form->fin();
	echo $Form->formulaireHTML();
    }

//---------------------------------------------------------VISION ETABLISSEMENT---------------------------------------------------------------

    //Pages Entite de donnees
function formcuent($etab=999999,$ancu=2009,$percu="rien")
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","suivconnex");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array("Charges"=>"chargent.php",
					"Trafics"=>"traficent.php",
					"Co�ts unitaires"=>"cunitent.php"
					);
	echo "<CENTER><H1>".'Coûts Entite'."</H1></CENTER>\n";
	//champlist deroulant annee periode entite
	$requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
	$resultat2 = $bd->execRequete ($requete2);
	$nomb_ligne2=$bd->nbLigne($resultat2);
	$listannee[0]="";
    for ($j=0;$j<$nomb_ligne2;$j++)
    {
        $ligne2=$bd->objetSuivant($resultat2);
        $listannee[$ligne2->can]=$ligne2->liban;
    }
	$requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
	$resultat = $bd->execRequete ($requete);
	$nomb_ligne=$bd->nbLigne($resultat);
	$listPerio[0]="";
    for ($j=0;$j<$nomb_ligne;$j++)
    {
        $ligne=$bd->objetSuivant($resultat);
        $listPerio[$ligne->cperi]=$ligne->libperi;
    }
	$requete1 = "SELECT distinct centite,libentite,cregate FROM entite order by libentite ";
    $resultat1 = $bd->execRequete ($requete1);
    $nomb_ligne1=$bd->nbLigne($resultat1);
	$listent[0]="";
    for ($j=0;$j<$nomb_ligne1;$j++)
    {
        $ligne1=$bd->objetSuivant($resultat1);
        $listent[$ligne1->cregate]=$ligne1->libentite;
    }
	switch ($_SESSION["usefonc"])
    {
        case 1;case 3;case 2;
        	$Form->debuttable();
        	if($etab<>999999)
        	{
        		//$reqent="select cregate from entite where libentite='".$etab."'";
        		//$resent = $bd->execRequete($reqent);
        		//$codent=mysql_result($resent,0,0);
        		$Form->champliste ("Entité :", "entite", $etab, 1, $listent);
        	}
        	else
        	{
        		$Form->champliste ("Entité :", "entite", "", 1, $listent);
        	}
        break;
        case 4;
				$Form->debuttable();
		break;
		default:
			$Form->debuttable();
		break;
	}
	if($ancu<>2009)
	{
		$reqcodan="select can from annee where liban='".$ancu."'";
		$rescodan = $bd->execRequete($reqcodan);
		$codannee=$bd->ligTabSuivant($rescodan);
		$Form->champliste ("Année :", "ann", $codannee[0], 1, $listannee);
		$ancour=$codannee[0];
		$GLOBALS[ancour];
	}
	else
	{
		$Form->champliste ("Année :", "ann", "", 1, $listannee);
	}
	if($percu<>"rien")
	{
		$reqcodper="select cperi from periode where libperi='".$percu."'";
		$rescodper = $bd->execRequete($reqcodper);
		$codperiode=$bd->ligTabSuivant($rescodper);
		$Form->champliste ("P�riode :", "perio", $codperiode[0], 1, $listPerio);
		$percour=$codperiode[0];
		$GLOBALS[percour];
	}
	else
	{
		$Form->champliste ("Pèriode :", "perio", "", 1, $listPerio);
	}
	$Form->champvalider ("Valider", "valider");
	//$Form->fin();
	$requete3 = "select distinct cordaffich,ordscrib,libprocess,sum(charcaa)
                   from domaine,processus,sousprocessus,caa,charcaa,periode,mois,entite
		             where cordaffich<>'0' and cdom=cedom and cprocess=ceprocess and anchcaa='".$ancu."'
		                 and cperi='".$codperiode."' and cperi=ceperi and codmoi=moichcaa
		                 and cregate='".$etab."'  and ceentite=centite
                 			and cssprocess=cessprocess and ccaa=cecaa
                 				group by cordaffich
                 					order by ordscrib,cordaffich";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=$bd->ligTabSuivant($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Charges brutes"));
	$Form->ajoutTexte (tblentete("Retraitement"));
	$Form->ajoutTexte (tblentete("Charges nettes"));
	$Form->ajoutTexte (tblfinligne());
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");
		tblcellule($ligne3[2]);
		tblcellule(number_format($ligne3[3],0 , ' ' ,  ' ' ));
		tblcellule("NON FONCTIONNEL");
		tblcellule(number_format($ligne3[3],0 , ' ' ,  ' ' ));//PLUS LES RETRAITEMENTS
		tblfinligne();
		tbldebutligne(MPAP);
		tblcellule(" MPAP");
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
    }
function formchargent($etab=999999)
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","suivconnex");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array("Charges"=>"chargent.php",
					 "Trafics"=>"traficent.php",
					 "Co�ts unitaires"=>"cunitent.php"
					);

	$ancour=$_SESSION['anc'];
	$percour=$_SESSION['perc'];
	$entite=$_SESSION['ent'];
	$libent=$_SESSION['libent'];

	echo "<CENTER><H1>".'Charges Entite'."</H1></CENTER>\n";
	echo "<CENTER><H3>".'Ann�e trait�e : '.$ancour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'P�riode trait�e : '.$percour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'Entit� trait�e : '.$entite." - ".$libent."</H3></CENTER>\n";
	//requete sql alimentant les cellules
	$requete3 = "select distinct cordaffich,ordscrib,libprocess,sum(charcaa)
                 	from domaine,processus,sousprocessus,caa,charcaa,periode,mois,entite
		                 where cordaffich<>'0' and cdom=cedom and cprocess=ceprocess
		                 	and centite=ceentite and cregate='".$entite."'
                 			and cssprocess=cessprocess and ccaa=cecaa
                 			and libperi='".$percour."' and cperi=ceperi
                 			and anchcaa='".$ancour."' and moichcaa=codmoi
                 				group by cordaffich
                 					order by ordscrib,cordaffich";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=mysql_num_rows($resultat3);
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Charges brutes"));
	$Form->ajoutTexte (tblentete("Retraitement"));
	$Form->ajoutTexte (tblentete("Charges nettes"));
	$Form->ajoutTexte (tblfinligne());
	//boucle renseignant les cellules
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne(A0);
		tblcellule($ligne3[2]);
		tblcellule(number_format($ligne3[3],0 , ' ' ,  ' ' ));
		tblcellule("NON FONCTIONNEL");
		tblcellule(number_format($ligne3[3],0 , ' ' ,  ' ' ));//PLUS LES RETRAITEMENTS
		tblfinligne();
		tbldebutligne(MPAP);
		tblcellule(" MPAP");
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
    }
function formtraficent($etab=999999)
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","suivconnex");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array("Charges"=>"chargent.php",
					"Trafics"=>"traficent.php",
					"Co�ts unitaires"=>"cunitent.php"
					);
	$requete3 = "select distinct cordaffich,ordscrib,libprocess
                            from domaine,processus
                                 where cordaffich<>'0'
                                       and cdom=cedom
                                           group by cordaffich
                                                 order by ordscrib,cordaffich";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=mysql_num_rows($resultat3);

	$ancour=$_SESSION['anc'];
	$percour=$_SESSION['perc'];
	$entite=$_SESSION['ent'];
	$libent=$_SESSION['libent'];
	echo "<CENTER><H1>".'Trafics Entite'."</H1></CENTER>\n";
	echo "<CENTER><H3>".'Ann�e trait�e : '.$ancour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'P�riode trait�e : '.$percour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'Entit� trait�e : '.$entite." - ".$libent."</H3></CENTER>\n";

	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Trafic courrier"));
	$Form->ajoutTexte (tblentete("Trafic IP")); //pna
	$Form->ajoutTexte (tblentete("Trafic colis"));
	$Form->ajoutTexte (tblentete("Trafic total"));
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne(A0);
		switch ($ligne3[2])
		{
			case "DISTRIBUTION":
				tblcellule($ligne3[2]." PILDI");
				$requete5 = "select sum(trafcolis)from colis,periode,mois,entite where ancol='".$ancour."' and centite=ceentite and libperi='".$percour."' and cperi=ceperi and codmoi=moicol and cregate='".$entite."'";
				$resultat5 = $bd->execRequete ($requete5);
				$ligne5=$bd->ligTabSuivant($resultat5);
				$requete6 = "select sum(trafpna)from pna,periode,mois,entite where anpna='".$ancour."' and centite=ceentite and libperi='".$percour."' and cperi=ceperi and codmoi=moipna and cregate='".$entite."'";
				$resultat6 = $bd->execRequete ($requete6);
				$ligne6=$bd->ligTabSuivant($resultat6);
				$requete4 = "select sum(trafdistribm),sum(trafdistribc)from pildi,periode,mois,entite where anpi='".$ancour."'  and centite=ceentite and cregate='".$entite."' and libperi='".$percour."' and cperi=ceperi and codmoi=moipi";
				$resultat4 = $bd->execRequete ($requete4);
				$ligne4=$bd->ligTabSuivant($resultat4);
				tblcellule(number_format($ligne4[0] + $ligne4[1],0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne6[0],0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne5[0],0 , ' ' ,  ' ' ));
				tblcellule(number_format($ligne4[0] + $ligne4[1]+ $ligne6[0]+ $ligne5[0],0 , ' ' ,  ' ' ));
				break;
			case "TRAITEMENT":
				tblcellule($ligne3[2]);
				$requete4 = "select sum(traftraitd),sum(traftraita)from syspeo,periode,mois,entite where anpeo='".$ancour."' and centite=ceentite and cregate='".$entite."' and libperi='".$percour."' and cperi=ceperi and codmoi=moipeo";
				$resultat4 = $bd->execRequete ($requete4);
				$ligne4=$bd->ligTabSuivant($resultat4);
				tblcellule(number_format($ligne4[0] + $ligne4[1],0 , ' ' ,  ' ' ));
				tblcellule("");
				tblcellule("");
				tblcellule(number_format($ligne4[0] + $ligne4[1],0 , ' ' ,  ' ' ));
				break;
			case "CONCENTRATION":
				tblcellule($ligne3[2]);
				$requete4 = "select sum(trafconc)from syspeo,periode,mois,entite where anpeo='".$ancour."' and centite=ceentite and cregate='".$entite."' and libperi='".$percour."' and cperi=ceperi and codmoi=moipeo";
				$resultat4 = $bd->execRequete ($requete4);
				$ligne4=$bd->ligTabSuivant($resultat4);
				$requete5 = "select sum(trafconc)from pildi,mois,periode,entite where anpi='".$ancour."'  and centite=ceentite and cregate='".$entite."' and libperi='".$percour."' and cperi=ceperi and codmoi=moipi";
				$resultat5 = $bd->execRequete ($requete5);
				$ligne5=$bd->ligTabSuivant($resultat5);
				tblcellule(number_format($ligne5[0] + $ligne4[0],0 , ' ' ,  ' ' ));
				tblcellule("");
				tblcellule("");
				tblcellule(number_format($ligne5[0] + $ligne4[0],0 , ' ' ,  ' ' ));
				break;
			case "TI":
				tblcellule($ligne3[2]);
				$requete4 = "select sum(trafdistribm),sum(trafdistribc)from pildi,mois,periode,entite where anpi='".$ancour."' and centite=ceentite and cregate='".$entite."' and libperi='".$percour."' and cperi=ceperi and codmoi=moipi";
				$resultat4 = $bd->execRequete ($requete4);
				$ligne4=$bd->ligTabSuivant($resultat4);
				$requete5 = "select sum(trafcolis)from colis,mois,periode,entite where ancol='".$ancour."' and cregate='".$entite."' and centite=ceentite and libperi='".$percour."' and cperi=ceperi and codmoi=moicol";
				$resultat5 = $bd->execRequete ($requete5);
				$ligne5=$bd->ligTabSuivant($resultat5);
				tblcellule(number_format($ligne4[0] + $ligne4[1],0 , ' ' ,  ' ' ));
				tblcellule("");
				tblcellule(number_format($ligne5[0],0 , ' ' ,  ' ' ));    //test separateur millier
				tblcellule(number_format($ligne4[0] + $ligne4[1]+ $ligne5[0],0 , ' ' ,  ' ' ));
				break;
			default:
				tblcellule($ligne3[2]);
			break;
		}
		tblfinligne();
		tbldebutligne(MPAP);
		tblcellule("MPAP");
		tblfinligne();

	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
    }
function formcunitent($etab=999999)
    {
    //require("pconnect.php");
    // Cr�ation du formulaire
    $bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
    $Form = new formulaire ("POST", "","suivconnex");
    // Tableau en mode vertical, pour les champs simples
    $menuclien=array("Charges"=>"chargent.php",
                         "Trafics"=>"traficent.php",
                         "Co�ts unitaires"=>"cunitent.php");
	//recuperation des processus
	$requete3 = "select distinct cordaffich,ordscrib,libprocess
                            from domaine,processus
                                 where cordaffich<>'0'and cdom=cedom
                                           group by cordaffich
                                                 order by ordscrib,cordaffich";
	$resultat3 = $bd->execRequete ($requete3);
	$nomb_ligne3=mysql_num_rows($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");
	$ancour=$_SESSION['anc'];
	$percour=$_SESSION['perc'];
	$entite=$_SESSION['ent'];
	$libent=$_SESSION['libent'];
	echo "<CENTER><H1>".'Couts unitaires Entite'."</H1></CENTER>\n";
	echo "<CENTER><H3>".'Ann�e trait�e : '.$ancour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'P�riode trait�e : '.$percour."</H3></CENTER>\n";
	echo "<CENTER><H3>".'Entit� trait�e : '.$entite." - ".$libent."</H3></CENTER>\n";

	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("Cout unitaire direct"));
	$Form->ajoutTexte (tblentete("Cout unitaire indirect"));
	$Form->ajoutTexte (tblfinligne());
	//boucle renseignant les cellules
	for ($j=0;$j<$nomb_ligne3;$j++)
	{
		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne(A0);
		switch ($ligne3[2])
		{
			case "DISTRIBUTION":

				//charges pour le processus distribution
				$reqchar = "select distinct sum(charcaa)
                 	from processus,sousprocessus,caa,charcaa,periode,mois,entite
		                 where  cprocess=ceprocess and libprocess='DISTRIBUTION'
		                 	and centite=ceentite and cregate='".$entite."'
                 			and cssprocess=cessprocess and ccaa=cecaa
                 			and libperi='".$percour."' and cperi=ceperi
                 			and anchcaa='".$ancour."' and moichcaa=codmoi
                 				group by cprocess";
				$resuchar = $bd->execRequete ($reqchar);
				$ligchar=$bd->ligTabSuivant($resuchar);

				//distribution pildi
				$requete7 = "select sum(trafdistribm),sum(trafdistribc)from pildi,periode,mois,entite where anpi='".$ancour."' and ceentite=centite and libperi='".$percour."' and cperi=ceperi and codmoi=moipi and cregate='".$entite."'";
				$resultat7 = $bd->execRequete ($requete7);
				$ligne7=$bd->ligTabSuivant($resultat7);

				//distribution colis
				$requete5 = "select sum(trafcolis)from colis,periode,mois,entite where ancol='".$ancour."'and centite=ceentite and libperi='".$percour."' and cperi=ceperi and codmoi=moicol and cregate='".$entite."'";
				$resultat5 = $bd->execRequete ($requete5);
				$ligne5=$bd->ligTabSuivant($resultat5);

				//distribution pna
				$requete6 = "select sum(trafpna)from pna,periode,mois,entite where anpna='".$ancour."' and centite=ceentite and  libperi='".$percour."' and cperi=ceperi and codmoi=moipna and cregate='".$entite."'";
				$resultat6 = $bd->execRequete ($requete6);
				$ligne6=$bd->ligTabSuivant($resultat6);
				$trafSyci=$ligne4[0]+$ligne5[0]+$ligne6[0];
				tblcellule($ligne3[2]." PILDI"); //processus
				$trafPildi=$ligne7[0]+$ligne7[1]+$ligne5[0]+$ligne6[0];
				if($trafPildi<>0)
				{
					tblcellule(number_format($ligchar[0]/$trafPildi,6 , ',' ,  ' ' ));
				}
				else
				{
					tblcellule("Absence de trafic");
				}
				tblcellule("NON FONCTIONNEL");//cout unitaire indirect
				break;
			case "TRAITEMENT":
				tblcellule($ligne3[2]);
				$reqchar="select distinct sum(charcaa)
                 	from processus,sousprocessus,caa,charcaa,periode,mois,entite
		                 where  cprocess=ceprocess and libprocess='TRAITEMENT'
		                 	and centite=ceentite and cregate='".$entite."'
                 			and cssprocess=cessprocess and ccaa=cecaa
                 			and libperi='".$percour."' and cperi=ceperi
                 			and anchcaa='".$ancour."' and moichcaa=codmoi
                 				group by cprocess";
				$reschar = $bd->execRequete ($reqchar);
				$ligchar=$bd->ligTabSuivant($reschar);
				$requete8 = "select sum(traftraitd),sum(traftraita)from syspeo,periode,mois,entite where anpeo='".$ancour."' and ceentite=centite and cregate='".$entite."' and libperi='".$percour."' and cperi=ceperi and codmoi=moipeo";
				$resultat8 = $bd->execRequete ($requete8);
				$ligne8=$bd->ligTabSuivant($resultat8);
				$traftrait=$ligne8[0] + $ligne8[1];
				if($traftrait<>0)
				{
					tblcellule(number_format($ligchar[0]/($traftrait),6 , ',' ,  ' ' ));
				}
				else
				{
					tblcellule("Absence de trafic");
				}
				tblcellule("NON FONCTIONNEL");//cout unitaire indirect
				break;
			case "CONCENTRATION":
				tblcellule($ligne3[2]);
				$reqchar="select distinct sum(charcaa)
                 	from processus,sousprocessus,caa,charcaa,periode,mois,entite
		                 where  cprocess=ceprocess and libprocess='CONCENTRATION'
		                 	and centite=ceentite and cregate='".$entite."'
                 			and cssprocess=cessprocess and ccaa=cecaa
                 			and libperi='".$percour."' and cperi=ceperi
                 			and anchcaa='".$ancour."' and moichcaa=codmoi
                 				group by cprocess";
				$reschar = $bd->execRequete ($reqchar);
				$ligchar=$bd->ligTabSuivant($reschar);
				$requete9 = "select sum(trafconc)from syspeo,periode,mois,entite where anpeo='".$ancour."'  and cregate='".$entite."' and ceentite=centite and libperi='".$percour."' and cperi=ceperi and codmoi=moipeo";
				$resultat9 = $bd->execRequete ($requete9);
				$ligne9=$bd->ligTabSuivant($resultat9);
				$requete9b = "select sum(trafconc)from pildi,mois,periode,entite where anpi='".$ancour."'and  cregate='".$entite."'  and ceentite=centite and libperi='".$percour."' and cperi=ceperi and codmoi=moipi";
				$resultat9b = $bd->execRequete ($requete9b);
				$ligne9b=$bd->ligTabSuivant($resultat9b);
				$trafconc=$ligne9[0] + $ligne9b[0];
				if($trafconc<>0)
				{
					tblcellule(number_format($ligchar[0]/($trafconc),6 , ',' , ' ' ));
				}
				else
				{
					tblcellule("Absence de trafic");
				}
				tblcellule("NON FONCTIONNEL");//cout unitaire indirect
				break;
			case "TI":
				tblcellule($ligne3[2]);
				$reqchar="select distinct sum(charcaa)
                 	from processus,sousprocessus,caa,charcaa,periode,mois,entite
		                 where  cprocess=ceprocess and libprocess='TI'
		                 	and centite=ceentite and cregate='".$entite."'
                 			and cssprocess=cessprocess and ccaa=cecaa
                 			and libperi='".$percour."' and cperi=ceperi
                 			and anchcaa='".$ancour."' and moichcaa=codmoi
                 				group by cprocess";
				$reschar = $bd->execRequete ($reqchar);
				$ligchar=$bd->ligTabSuivant($reschar);

				$requete10 = "select sum(trafdistribm),sum(trafdistribc)from pildi,mois,periode,entite where anpi='".$ancour."' and  cregate='".$entite."' and ceentite=centite and libperi='".$percour."' and cperi=ceperi and codmoi=moipi";
				$resultat10 = $bd->execRequete ($requete10);
				$ligne10=$bd->ligTabSuivant($resultat10);

				$requete10b = "select sum(trafcolis)from colis,mois,periode,entite where ancol='".$ancour."'and  cregate='".$entite."' and  ceentite=centite and libperi='".$percour."' and cperi=ceperi and codmoi=moicol";
				$resultat10b = $bd->execRequete ($requete10b);
				$ligne10b=$bd->ligTabSuivant($resultat10b);

				$trafTI=$ligne10[0] +$ligne10[1] + $ligne10b[0];
				if($trafTI<>0)
				{
					tblcellule(number_format($ligchar[0]/($trafTI),6 , ',' ,  ' ' ));
				}
				else
				{
					tblcellule("Absence de trafic");
				}
				tblcellule("NON FONCTIONNEL");//cout unitaire indirect
				break;
			default:
				tblcellule($ligne3[2]);
		}
		tblfinligne();
		tbldebutligne(MPAP);
		tblcellule("MPAP");
		tblfinligne();
	}
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
    }
function formretrent($etab=999999,$ancu=2009,$percu="rien")
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","periode");
	// Tableau en mode vertical, pour les champs simples
	//$menuclien=array( "Charges"=>"charge.php", "Trafics"=>"trafic.php", "Co�ts unitaires"=>"cunit.php");
	echo "<CENTER><H1>".'Retraitements Entité'."</H1></CENTER>\n";

	//champlist deroulant annee periode entite
	$requete2 = "SELECT distinct can,liban FROM annee  group by liban order by liban ";
	$resultat2 = $bd->execRequete ($requete2);
	$nomb_ligne2=$bd->nbLigne($resultat2);
	$listannee[0]="";
    for ($j=0;$j<$nomb_ligne2;$j++)
    {
        $ligne2=$bd->objetSuivant($resultat2);
        $listannee[$ligne2->can]=$ligne2->liban;
    }
	$requete = "SELECT  distinct cperi,libperi FROM periode order by libperi";
	$resultat = $bd->execRequete ($requete);
	$nomb_ligne=$bd->nbLigne($resultat);
	$listPerio[0]="";
    for ($j=0;$j<$nomb_ligne;$j++)
    {
        $ligne=$bd->objetSuivant($resultat);
        $listPerio[$ligne->cperi]=$ligne->libperi;
    }
	$requete1 = "SELECT distinct centite,libentite,cregate FROM entite order by libentite ";
    $resultat1 = $bd->execRequete ($requete1);
    $nomb_ligne1=$bd->nbLigne($resultat1);
	$listent[0]="";
    for ($j=0;$j<$nomb_ligne1;$j++)
    {
        $ligne1=$bd->objetSuivant($resultat1);
        $listent[$ligne1->cregate]=$ligne1->libentite;
    }
	switch ($_SESSION["usefonc"])
    {
        case 1;case 3;case 2;
        	$Form->debuttable();
        	if($etab<>999999)
        	{
        		//$reqent="select cregate from entite where libentite='".$etab."'";
        		//$resent = $bd->execRequete($reqent);
        		//$codent=mysql_result($resent,0,0);
        		$Form->champliste ("Entité :", "entite", $etab, 1, $listent);
        	}
        	else
        	{
        		$Form->champliste ("Entité :", "entite", "", 1, $listent);
        	}
        break;
        case 4;
				$Form->debuttable();
		break;
		default:
			$Form->debuttable();
		break;
	}
	if($ancu<>2009)
	{
		$reqcodan="select can from annee where liban='".$ancu."'";
		$rescodan = $bd->execRequete($reqcodan);
		$codannee=mysql_result($rescodan,0,0);
		$Form->champliste ("Année :", "ann", $codannee, 1, $listannee);
		$ancour=$codannee;
		$GLOBALS[ancour];
	}
	else
	{
		$Form->champliste ("Année :", "ann", "", 1, $listannee);
	}
	if($percu<>"rien")
	{
		$reqcodper="select cperi from periode where libperi='".$percu."'";
		$rescodper = $bd->execRequete($reqcodper);
		$codperiode=mysql_result($rescodper,0,0);
		$Form->champliste ("Pèriode :", "perio", $codperiode, 1, $listPerio);
		$percour=$codperiode;
		$GLOBALS[percour];
	}
	else
	{
		$Form->champliste ("Pèriode :", "perio", "", 1, $listPerio);
	}
	$Form->champvalider ("Valider", "valider");
	//$Form->fin();
	//requete sql alimentant tableau
	$requete3 = "select distinct cordaffich,ordscrib,libprocess
                            from domaine,processus
                                 where cordaffich<>'0'
                                       and cdom=cedom
                                           group by cordaffich
                                                 order by ordscrib,cordaffich";
  	$resultat3 = $bd->execRequete ($requete3);
  	$nomb_ligne3=$bd->nbLigne($resultat3);
	$Form = new formulaire ("POST", "","suivconnex");

	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU");
	//choix des menus
    foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
	tblcellule(Ancre($ancre,$libelle,"MENU"));
	tblfin();
	tblcellule(image('interface/redbar.jpg',"100%",1));
	// Tableau en mode vertical, pour les champs simples
	$Form->debuttable();
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete(""));
	$Form->ajoutTexte (tblentete("SP88 - IMMOBILIER"));
	$Form->ajoutTexte (tblentete("SP89 - VEHICULE"));
	$Form->ajoutTexte (tblentete("SP90 - CAA SOCIAL"));
	$Form->ajoutTexte (tblentete("SP91 - CAA RH"));
	$Form->ajoutTexte (tblentete("TOTAL TRANSVERSE"));
	$Form->ajoutTexte (tblfinligne());

	$entite=$_SESSION['ent'];
	$libent=$_SESSION['libent'];

	$reqpr02 = "select  sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and centite=ceentite and cregate='".$entite."'
									and ceprocess=cprocess and codprocess='PR02'";
	$respr02 = $bd->execRequete ($reqpr02);
	$PR02=$bd->ligTabSuivant($respr02);

	$reqpr04 = "select  sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and centite=ceentite and cregate='".$entite."'
									and ceprocess=cprocess and codprocess='PR04'";
	$respr04 = $bd->execRequete ($reqpr04);
	$PR04=$bd->ligTabSuivant($respr04);

	$reqdist = "select  sum(charcaa)
								from charcaa,caa,mois,periode,entite
									where cecaa=ccaa and codcaa='775'
										and centite=ceentite and cregate='".$entite."'
										and anchcaa='".$ancu."' and cperi='".$codperiode."'
										and cperi=ceperi and codmoi=moichcaa";
	$resdist = $bd->execRequete ($reqdist);
	$ligne=$bd->ligTabSuivant($resdist);

	$reqdist2 = "select  sum(charcaa)
								from charcaa,caa,mois,periode,entite
									where cecaa=ccaa and codcaa='750'
										and centite=ceentite and cregate='".$entite."'
										and anchcaa='".$ancu."' and cperi='".$codperiode."'
										and cperi=ceperi and codmoi=moichcaa";
	$resdist2 = $bd->execRequete ($reqdist2);
	$ligne2=$bd->ligTabSuivant($resdist2);

	$reqdist3 = "select  sum(charcaa)
								from charcaa,sousprocessus,caa,mois,periode,entite
									where cecaa=ccaa and cessprocess=cssprocess
										and centite=ceentite and cregate='".$entite."'
										and anchcaa='".$ancu."' and cperi='".$codperiode."'
										and cperi=ceperi and codmoi=moichcaa
										and codssprocess='SP90'";
	$resdist3 = $bd->execRequete ($reqdist3);
	$SP90=$bd->ligTabSuivant($resdist3);

	$reqdist4 = "select  sum(charcaa)
								from charcaa,sousprocessus,caa,mois,periode,entite
									where cecaa=ccaa and cessprocess=cssprocess
										and centite=ceentite and cregate='".$entite."'
										and anchcaa='".$ancu."' and cperi='".$codperiode."'
										and cperi=ceperi and codmoi=moichcaa
										and codssprocess='SP91'";
	$resdist4 = $bd->execRequete ($reqdist4);
	$SP91=$bd->ligTabSuivant($resdist4);

	$Form->debuttable();
	?>
	<textcolor:red>
	<?php
	tblcellule("CHARGES A RETRAITER");
	tblcellule(number_format(-$ligne[0],0 , ' ' ,  ' ' ));
	tblcellule(number_format(-$ligne2[0],0 , ' ' ,  ' ' ));
	tblcellule(number_format(-$SP90[0],0 , ' ' ,  ' ' ));
	tblcellule(number_format(-$SP91[0],0 , ' ' ,  ' ' ));
	tblcellule(number_format(-$ligne[0]-$ligne2[0]-$SP90[0]-$SP91[0],0 , ' ' ,  ' ' ));
	$Form->ajoutTexte (tblfinligne());
	?>
	<textcolor:red/>
	<?php
	$reqcharge="select sum(charcaa)
					 from charcaa,caa,sousprocessus,processus,mois,periode,entite
					 	where libprocess<>'CAA TRANSVERSES'
							and anchcaa='".$ancu."' and cperi='".$codperiode."'
							and cperi=ceperi and codmoi=moichcaa
							and centite=ceentite and cregate='".$entite."'
					 		and cecaa=ccaa and cessprocess=cssprocess and ceprocess=cprocess";
	$rescharge = $bd->execRequete ($reqcharge);
	$charges=$bd->ligTabSuivant($rescharge);
	//echo "<CENTER><H3>".'charge : '.$charges."</H3></CENTER>\n";
	$totimm=-$ligne[0];
	$totvehi=-$ligne2[0];
	$totsocial=-$SP90[0];
	$totrh=-$SP91[0];

	for ($j=0;$j<$nomb_ligne3;$j++)
	{

		$ligne3=$bd->ligTabSuivant($resultat3);
		tbldebutligne("A0");
		tblcellule($ligne3[2]);
		switch ($ligne3[2])
		{
			case "DISTRIBUTION":
			/*	$reqimmo="select sum(tchar.charcaa) from processus,tca,entite,charcaa,caa,sousprocessus,charcaa as tchar,caa as tcaa
							where tcaa.codcaa='775' and tchar.cecaa=tcaa.ccaa
								and centite=tchar.ceentite and cetca=ctca and charcaa.cecaa=caa.ccaa
								and ceprocess=cprocess and caa.cessprocess=cssprocess
								and cprocprinc=cprocess and libprocess='".$ligne3[2]."'";
				$resimmo = $bd->execRequete ($reqimmo);
				$immo=mysql_result($resimmo,0,0);
				echo "<CENTER><H3>".'immo : '.$immo."</H3></CENTER>\n";
			*/
				$immdist=$ligne[0]*($PR04[0]/$charges[0]);
				$totimm=$totimm+$immdist;
				tblcellule(number_format($immdist,3 , ' ' ,  ' '));

				$vehidist=$ligne2[0]*($PR04[0]/($PR02[0]+$PR04[0]));
				$totvehi=$totvehi+$vehidist;
				tblcellule(number_format($vehidist,3 , ' ' ,  ' '));

				$reqdist = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and centite=ceentite and cregate='".$entite."'
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resdist = $bd->execRequete ($reqdist);
				$dist=$bd->ligTabSuivant($resdist);

				$socialdist=$SP90[0]*($dist[0]/$charges[0]);
				$totsocial=$totsocial+$socialdist;
				tblcellule(number_format($socialdist,3 , ' ' ,  ' ' ));

				$rhdist=$SP91[0]*($dist[0]/$charges[0]);
				$totrh=$totrh+$rhdist;
				tblcellule(number_format($rhdist,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immdist+$vehidist+$socialdist+$rhdist,3 , ' ' ,  ' '));
				break;
			case "TRAITEMENT":
				$reqtrait = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and centite=ceentite and cregate='".$entite."'
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$restrait = $bd->execRequete ($reqtrait);
				$trait=$bd->ligTabSuivant($restrait);
				$immtrait=$ligne[0]*($trait[0]/$charges[0]);
				$totimm=$totimm+$immtrait;
				tblcellule(number_format($immtrait,3 , ' ' ,  ' '));

				tblcellule("");

				$socialtrait=$SP90[0]*($trait[0]/$charges[0]);
				$totsocial=$totsocial+$socialtrait;
				tblcellule(number_format($socialtrait,3 , ' ' ,  ' ' ));

				$rhtrait=$SP91[0]*($trait[0]/$charges[0]);
				$totrh=$totrh+$rhtrait;
				tblcellule(number_format($rhtrait,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immtrait+$socialtrait+$rhtrait,3 , ' ' ,  ' '));
				break;
			case "CONCENTRATION":
				$immconc=$ligne[0]*($PR02[0]/$charges[0]);
				$totimm=$totimm+$immconc;
				tblcellule(number_format($immconc,3 , ' ' ,  ' '));

				$vehiconc=$ligne2[0]*($PR02[0]/($PR02[0]+$PR04[0]));
				$totvehi=$totvehi+$vehiconc;
				tblcellule(number_format($vehiconc,3 , ' ' ,  ' '));

				$reqconc = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and centite=ceentite and cregate='".$entite."'
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resconc = $bd->execRequete ($reqconc);
				$conc=$bd->ligTabSuivant($resconc);
				$socialconc=$SP90[0]*($conc[0]/$charges[0]);
				$totsocial=$totsocial+$socialconc;
				tblcellule(number_format($socialconc,3 , ' ' ,  ' ' ));

				$rhconc=$SP91[0]*($conc[0]/$charges[0]);
				$totrh=$totrh+$rhconc;
				tblcellule(number_format($rhconc,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immconc+$vehiconc+$socialconc+$rhconc,3 , ' ' ,  ' '));
				break;
			case "CAA CORPORATE":
				tblcellule("");
				tblcellule("");
				tblcellule("");
				tblcellule("");
				tblcellule("");
				break;
			case "SOUTIEN OPERATIONNEL":
				$reqst = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and centite=ceentite and cregate='".$entite."'
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resst = $bd->execRequete ($reqst);
				$st=$bd->ligTabSuivant($resst);

				$immst=$ligne[0]*($st[0]/$charges[0]);
				$totimm=$totimm+$immst;

				tblcellule(number_format($immst,3 , ' ' ,  ' '));
				tblcellule("");

				$socialst=$SP90[0]*($st[0]/$charges[0]);
				$totsocial=$totsocial+$socialst;
				tblcellule(number_format($socialst,3 , ' ' ,  ' ' ));

				$rhst=$SP91[0]*($st[0]/$charges[0]);
				$totrh=$totrh+$rhst;
				tblcellule(number_format($rhst,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immst+$socialst+$rhst,3 , ' ' ,  ' '));
				break;
			case "COMPTA GESTION FINANCE":
				$reqcompta = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and centite=ceentite and cregate='".$entite."'
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$rescompta = $bd->execRequete ($reqcompta);
				$compta=$bd->ligTabSuivant($rescompta);

				$immcompta=$ligne[0]*($compta[0]/$charges[0]);
				$totimm=$totimm+$immcompta;

				tblcellule(number_format($immcompta,3 , ' ' ,  ' '));
				tblcellule("");

				$socialcompta=$SP90[0]*($compta[0]/$charges[0]);
				$totsocial=$totsocial+$socialcompta;
				tblcellule(number_format($socialcompta,3 , ' ' ,  ' ' ));

				$rhcompta=$SP91[0]*($compta[0]/$charges[0]);
				$totrh=$totrh+$rhcompta;
				tblcellule(number_format($rhcompta,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immcompta+$socialcompta+$rhcompta,3 , ' ' ,  ' '));
				break;
			case "MARKETING COMMERCIAL":
				$reqmark = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and centite=ceentite and cregate='".$entite."'
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resmark = $bd->execRequete ($reqmark);
				$mark=$bd->ligTabSuivant($resmark);

				$immmark=$ligne[0]*($mark[0]/$charges[0]);
				$totimm=$totimm+$immmark;

				tblcellule(number_format($immmark,3 , ' ' ,  ' '));
				tblcellule("");

				$socialmark=$SP90[0]*($mark[0]/$charges[0]);
				$totsocial=$totsocial+$socialmark;
				tblcellule(number_format($socialmark,3 , ' ' ,  ' ' ));

				$rhmark=$SP91[0]*($mark[0]/$charges[0]);
				$totrh=$totrh+$rhmark;
				tblcellule(number_format($rhmark,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immmark+$socialmark+$rhmark,3 , ' ' ,  ' '));
				break;
			case "PILOTAGE":
				$reqpil = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and centite=ceentite and cregate='".$entite."'
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$respil = $bd->execRequete ($reqpil);
				$pil=$bd->ligTabSuivant($respil);

				$immpil=$ligne[0]*($pil[0]/$charges[0]);
				$totimm=$totimm+$immpil;

				tblcellule(number_format($immpil,3 , ' ' ,  ' '));
				tblcellule("");

				$socialpil=$SP90[0]*($pil[0]/$charges[0]);
				$totsocial=$totsocial+$socialpil;
				tblcellule(number_format($socialpil,3 , ' ' ,  ' ' ));

				$rhpil=$SP91[0]*($pil[0]/$charges[0]);
				$totrh=$totrh+$rhpil;
				tblcellule(number_format($rhpil,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immpil+$socialpil+$rhpil,3 , ' ' ,  ' '));
				break;
			case "RH":
				$reqrh = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and centite=ceentite and cregate='".$entite."'
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resrh = $bd->execRequete ($reqrh);
				$rh=$bd->ligTabSuivant($resrh);

				$immrh=$ligne[0]*($rh[0]/$charges[0]);
				$totimm=$totimm+$immrh;

				tblcellule(number_format($immrh,3 , ' ' ,  ' '));
				tblcellule("");

				$socialrh=$SP90[0]*($rh[0]/$charges[0]);
				$totsocial=$totsocial+$socialrh;
				tblcellule(number_format($socialrh,3 , ' ' ,  ' ' ));

				$rhrh=$SP91[0]*($rh[0]/$charges[0]);
				$totrh=$totrh+$rhrh;
				tblcellule(number_format($rhrh,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immrh+$socialrh+$rhrh,3 , ' ' ,  ' '));
				break;
			case "SI":
				$reqsi = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and centite=ceentite and cregate='".$entite."'
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$ressi = $bd->execRequete ($reqsi);
				$si=$bd->ligTabSuivant($ressi);

				$immsi=$ligne[0]*($si[0]/$charges[0]);
				$totimm=$totimm+$immsi;

				tblcellule(number_format($immsi,3 , ' ' ,  ' '));
				tblcellule("");

				$socialsi=$SP90[0]*($si[0]/$charges[0]);
				$totsocial=$totsocial+$socialsi;
				tblcellule(number_format($socialsi,3 , ' ' ,  ' ' ));

				$rhsi=$SP91[0]*($si[0]/$charges[0]);
				$totrh=$totrh+$rhsi;
				tblcellule(number_format($rhsi,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immsi+$socialsi+$rhsi,3 , ' ' ,  ' '));
				break;
			case "TRANSPORT":
				$reqtransport = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and centite=ceentite and cregate='".$entite."'
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$restransport = $bd->execRequete ($reqtransport);
				$transport=$bd->ligTabSuivant($restransport);

				$immtransport=$ligne[0]*($transport[0]/$charges[0]);
				$totimm=$totimm+$immtransport;

				tblcellule(number_format($immtransport,3 , ' ' ,  ' '));
				tblcellule("");

				$socialtransport=$SP90[0]*($transport[0]/$charges[0]);
				$totsocial=$totsocial+$socialtransport;
				tblcellule(number_format($socialtransport,3 , ' ' ,  ' ' ));

				$rhtransport=$SP91[0]*($transport[0]/$charges[0]);
				$totrh=$totrh+$rhtransport;
				tblcellule(number_format($rhtransport,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immtransport+$socialtransport+$rhtransport,3 , ' ' ,  ' '));
				break;
			case "TI":
				$reqti = "select sum(charcaa) from charcaa,sousprocessus,processus,caa,mois,periode,entite
								where cecaa=ccaa and cessprocess=cssprocess
									and anchcaa='".$ancu."' and cperi='".$codperiode."'
									and cperi=ceperi and codmoi=moichcaa
									and centite=ceentite and cregate='".$entite."'
									and ceprocess=cprocess and libprocess='".$ligne3[2]."'";
				$resti = $bd->execRequete ($reqti);
				$ti=$bd->ligTabSuivant($resti);

				$immti=$ligne[0]*($ti[0]/$charges[0]);
				$totimm=$totimm+$immti;

				tblcellule(number_format($immti,3 , ' ' ,  ' '));
				tblcellule("");

				$socialti=$SP90[0]*($ti[0]/$charges[0]);
				$totsocial=$totsocial+$socialti;
				tblcellule(number_format($socialti,3 , ' ' ,  ' ' ));

				$rhti=$SP91[0]*($ti[0]/$charges[0]);
				$totrh=$totrh+$rhti;
				tblcellule(number_format($rhti,3 , ' ' ,  ' ' ));

				tblcellule(number_format($immti+$socialti+$rhti,3 , ' ' ,  ' '));
				break;
			case "CAA TRANSVERSES":
				tblcellule("");
				tblcellule("");
				tblcellule("");
				tblcellule("");
				tblcellule("");
				break;
			default:
				break;
		}
		tblfinligne();
		tbldebutligne(MPAP);
		tblcellule("MPAP");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblcellule("");
		tblfinligne();
	}
	tbldebutligne();
	tblcellule("Total");
	tblcellule(number_format($totimm,3 , ' ', ' '));
	tblcellule(number_format($totvehi,3 , ' ', ' '));
	tblcellule(number_format($totsocial,3 , ' ', ' '));
	tblcellule(number_format($totrh,3 , ' ', ' '));
	tblcellule(number_format(-$ligne[0]-$ligne2[0]-$SP90[0]-$SP91[0]+$immti+$socialti+$rhti
		+$immtransport+$socialtransport+$rhtransport
		+$immsi+$socialsi+$rhsi
		+$immrh+$socialrh+$rhrh
		+$immpil+$socialpil+$rhpil
		+$immmark+$socialmark+$rhmark
		+$immcompta+$socialcompta+$rhcompta
		+$immst+$socialst+$rhst
		+$immconc+$vehiconc+$socialconc+$rhconc
		+$immtrait+$socialtrait+$rhtrait
		+$immdist+$vehidist+$socialdist+$rhdist
		,0 , ' ' ,  ' ' ));
	tblfinligne();
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$Form->ajoutTexte (tblfin());
	$Form->ajoutTexte (tblcellule(image('interface/redbar.jpg',"100%",1)));
	$Form->ajoutTexte ("<P></P>");
	//$Form->fin();
}

//---------------------------------------------------------VISION ADMINISTRATION--------------------------------------------------------------

    //Page...
function formexport()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	//$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST","","export");
	echo "<CENTER><H3>".'Export de données'."</H3></CENTER>\n";
	echo "\n";
	tblcellule(image('interface/redbar.jpg',"100%",1));
	$Form->debuttable();
	$Form->champFichier("exporter : ","expfich",35);
	$Form->fintable();
	$Form->champvalider ("Valider la saisie", "valider");
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
function formvisulog()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	//$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$Form = new formulaire ("POST", "","suivconnex");
	// Tableau en mode vertical, pour les champs simples
	$menuclien=array(               "Scribe"=>"scribe.log",
					"pildi"=>"pildi.log",
					"toto"=>"pildi.log",
					"tata"=>"pildi.log"
					);
	echo "<CENTER><H3>".'Visualisation de journaux LOG'."</H3></CENTER>\n";
	//affichage du menu : 1ere table avec une ligne et une cellule pour obtenir un fond rouge
	//2�me table imbriqu�e dans la 1�re avec une ligne et autant decellule que de choix menus
	tbldebut(0,"100%");tbldebutligne("MENU2");
	$Form->debuttable();
	//choix des menus
     foreach ($menuclien as $libelle => $ancre )
	//while (list($libelle,$ancre)=each($menuclien))
		tblcellule(Ancre($ancre,$libelle,"MENU2"));
	$Form->fintable();

    }
function formlistuser()
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	//$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$listfichier=array(1=>"Liste user");
	$Form = new formulaire ("POST",impUser(),TRUE);
	//$fic="";
	//$row = 0;
	echo "<CENTER><H3>".'Import de base utilisateurs'."</H3></CENTER>\n";
	echo "\n";
	tblcellule(image('interface/redbar.jpg',"100%",1));
	//echo "<form enctype='multipart/form-data' action=listuser1() method='POST' >\n";
	//echo "< input type='hidden' name='MAX_FILE_SIZE' value='2000'>\n";
	//echo "< input type='file' name='Fichier à importer' size='40'>\n";
	//echo "< input type='submit' value='Envoyer'>\n";
	//echo "</form>\n";
	$Form->debuttable();
	$Form->champFichier("fichier à importer : ","impfich",35);
	$Form->fintable();
	$Form->champvalider ("Lancer l'import", "valider");
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
function formlistuser1($fich,$taille)
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$listfichier=array(1=>"Liste d'utilisateurs");
	$Form = new formulaire ("POST","gesimp","");
	//$fic="";
	$row = 0;
	echo "<CENTER><H3>".'Import de base utilisateurs'."</H3></CENTER>\n";
	echo "\n";
	tblcellule(image('interface/redbar.jpg',"100%",1));
	$Form->debuttable();
	//echo "<form action=listuser1() method='POST' enctype='multipart/form-data'>";
	//echo "< input type='file' name='Fichier � importer' size='40'>";
	//echo "</form>";
	$Form->champFichier("fichier à importer : ","impfich",35);
	//$Form->debuttable();
	$Form->fintable();
	$Form->champvalider ("Lancer l'import", "valider");
	//$Form->fin();
	$ins=0;
	$upt=0;
	$ano=0;
	$nc=0;
	$fp = fopen($fich,'r');
	$ltyp = "Importation d'utilisateurs";
	While (!feof($fp))
	{
		$fpU = fopen ('user.log','a');     //fichier log
		$num = count ($data);
		set_time_limit(120);
		$data = fgetcsv ($fp, 1000,';');
		if (!feof($fp) and $row>0)
		{
			$dotc=strtoupper($data[2]);//dotc_dcn
			//$idrh=strtoupper($data[6]);//idrh
			$nom=strtoupper($data[7]);//nom
			$prenom=strtoupper($data[8]);//prenom
			$etab=strtoupper($data[4]);//etablissement
			$fonction=substr(strtoupper($data[10]),0 ,13);//fonction courante
			$reqetut="select centite,cedotc from entite where cregate='".$etab."%'";
			$resetut = $bd->execRequete($reqetut);
			$lignetab=$bd->ligTabSuivant($resetut);
			//$requsr = "SELECT count(*) FROM utilisateur WHERE idrh='".$idrh."'";
			//$resusr = $bd->execRequete($requsr);
			//$nbmsg=$bd->ligTabSuivant($resusr);
			//if ($nbmsg[0]==0)
			//{
				$reqid="select count(*) from metier where fonction like '".$fonction."%'";
				$resid = $bd->execRequete($reqid);
				$nbfonc=$bd->ligTabSuivant($resid);
				echo "<CENTER><H3>"." retour requ�te: ".$nbfonc." , ".$fonction."</H3></CENTER>\n";
				if($nbfonc[0]==1)
				{
					$reqfonc="select idfonc from metier where fonction like '".$fonction."%'";
					$resfonc = $bd->execRequete($reqfonc);
					$fonc=$bd->ligTabSuivant($resfonc);
					$reqins="insert into utilisateur (nom,prenom,idfonc,centite,dotcdcn,pwd) values ('".$nom."','".$prenom."','".$fonc[0]."','".$lignetab[0]."','".$lignetab[1]."','".$idrh."')";
					$resins= $bd->execRequete ($reqins);
					if ( $resins == 1 )
					    return TRUE;
					    else
					        return FALSE;
					$ins=$ins+1;
				}
				else
				{
					$reqins="insert into utilisateur (nom,prenom) values ('".$nom."','".$prenom. "')";
					$resins= $bd->execRequete ($reqins);
					$ins=$ins+1;
				}
			//}
			//else
			{
				$requsr = "SELECT count(*) FROM utilisateur WHERE nom='".$nom."'and prenom='".$prenom."'";
				$resusr = $bd->execRequete($requsr);
				$nbmsg=$bd->ligTabSuivant($resusr);
				if ($nbmsg[0]<>0)
				{
					$ano=$ano+1;
					$info = $data[0].';user absent de scribe ;'.date("j/m/Y");
					fwrite ($fpU, $info);
					fwrite ($fpU, "\n");
				}
				else
				{
					$requsr = "SELECT count(*) FROM utilisateur WHERE nom='".$nom."'and prenom='".$prenom."'";
					$resusr = $bd->execRequete($requsr);
					$nbmsg=$bd->ligTabSuivant($resusr);
					if ($nbmsg[0]==0)
					{
						$requsr="update utilisateur set nom='".$nom."',prenom='".$prenom."',idfonc='".$fonction."'";
						$resusr= $bd->execRequete ($requsr);
						$upt=$upt+1;
					}
				}
			}
		}
		$row++;
	}
	$tot=$row+1;
	$info = $ano.';lignes en anomalie : detail ci-dessus ;'.date("j/m/Y");
	fwrite ($fpU, $info);
	fwrite ($fpU, "\n");
	$info = $ins.';lignes inseres ;'.date("j/m/Y");
	fwrite ($fpU, $info);
	fwrite ($fpU, "\n");
	$info = $upt.';lignes mises a jours ;'.date("j/m/Y");
	fwrite ($fpU, $info);
	fwrite ($fpU, "\n");
	$info = $nc.';lignes non concernees ;'.date("j/m/Y");
	fwrite ($fpU, $info);
	fwrite ($fpU, "\n");
	$info = $tot.';lignes a traiter ;'.date("j/m/Y");
	fwrite ($fpU, $info);
	fwrite ($fpU, "\n");
	fclose ($fpU);
	$Form->debuttable();
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete("Fich trait�: "));
	$Form->ajoutTexte (tblentete("Type fich: "));
	$Form->ajoutTexte (tblentete("Taille fich(en o): "));
	$Form->ajoutTexte (tblentete("Nb lignes: "));
	$Form->ajoutTexte (tblentete("Lignes ins: "));
	$Form->ajoutTexte (tblentete("Lignes maj: "));
	$Form->ajoutTexte (tblentete("Lignes erreur: "));
	$Form->ajoutTexte (tblentete("NC: "));
	$Form->ajoutTexte (tblfinligne());
	$Form->fintable();
	echo $Form->formulaireHTML();
	$Form->debuttable(tbldebutligne("A1"));
	$Form->debuttable(tblcellule($fich));
	$Form->debuttable(tblcellule($ltyp));
	$Form->debuttable(tblcellule($taille));
	$Form->debuttable(tblcellule($tot));
	$Form->debuttable(tblcellule($ins));
	$Form->debuttable(tblcellule($upt));
	$Form->debuttable(tblcellule($ano));
	$Form->debuttable(tblcellule($nc));
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	echo $Form->formulaireHTML();
	$fp1 = fopen ('scribe.log','a');     //fichier log
	$info = $fich.';'.$ltyp.';'.$taille.';'.$tot.';'.$ins.';'.$upt.';'.$ano.';'.$nc;
    }
function formimport($niv)
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	//$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$listfichier=array(1=>"charge CAA",2=>"charge nature",3=>"CAA",4=>"TCA",5=>"sous processus",6=>"processus",7=>"domaine",8=>"nature");
	$listfichier2=array(9=>"dotc",10=>"entité",11=>"trafic SYCI",12=>"trafic PILDI",13=>"trafic SYSPEO",14=>"trafic SIROPNA",15=>"trafic colis",16=>"Retraitement");
    $Form = new formulaire ("POST","","");
    //$fic="";
    //$row = 0;
	echo "<CENTER><H3>".'Import de données'."</H3></CENTER>\n";
	echo "\n";
		echo "<CENTER><H3>".'formimport'."</H3></CENTER>\n";
	echo "<CENTER><H3> niveau  : ".$niv."</H3></CENTER>\n";
	tblcellule(image('interface/redbar.jpg',"100%",1));
	$Form->debuttable();
	$Form->champRadio ( "Fichier","typfichier",1,$listfichier);
	$Form->champRadio ( "Fichier","typfichier",1,$listfichier2);
	$Form->champFichier("fichier à importer : ","impfich",35);
	$Form->fintable();
	$Form->champCache("niveau",1);
	$Form->debuttable();
	$Form->champvalider ("Valider", "valider");
	$Form->fintable();
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
function formimport1($fich,$taille,$typfic,$niv)
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$listfichier=array(1=>"charge CAA",2=>"charge nature",3=>"CAA",4=>"TCA",5=>"sous processus",6=>"processus",7=>"domaine",8=>"nature");
	$listfichier2=array(9=>"dotc",10=>"entité",11=>"trafic SYCI",12=>"trafic PILDI",13=>"trafic SYSPEO",14=>"trafic SIROPNA",15=>"trafic colis",16=>"Retraitement");
	$Form = new formulaire ("POST","gesimp","");
	$fic="";
	$row = 0;
	echo "<CENTER><H3>".'Import de données'."</H3></CENTER>\n";
	echo "\n";
	echo "<CENTER><H3>".'formimport1'."</H3></CENTER>\n";
	tblcellule(image('interface/redbar.jpg',"100%",1));
	$Form->debuttable();
	$Form->champRadio ( "Fichier","typfichier",1,$listfichier);
	$Form->champRadio ( "Fichier","typfichier",1,$listfichier2);
	$Form->champFichier("fichier à importer : ","impfich",35);
	$Form->fintable();
	echo $Form->formulaireHTML();
	switch ($typfic)
	{
		case 1;2;12;14;15;
		$Form->debuttable();
		$Form->champTexte ("Mois","permois","",15);
		$Form->fintable();
		$Form->debuttable();
		$Form->champTexte ("Année","peran","",4);
		$Form->fintable();
		default:
		break;
	}
	//$Form->fin();
	$ins=0;
	$upt=0;
	$ano=0;
	$nc=0;
	switch ($typfic)
	{
		case 1;2;12;14;15;//A MODIFIER L ORDRE D ENCHAINEMENT DES TACHES D IMPORT!!
			if(isset($_POST['peran'])and isset($_POST['permois']) and $_POST['peran']<>"" and $_POST['permois']<>"")
			{
				$an=$_POST['peran'];
				$mois=$_POST['permois'];
			}
			else
			{
				echo "<CENTER><H3>Vous devez saisir une période</H3></CENTER>\n";
				break;
			}
		default:
		break;
	}
	$an=$_POST['peran'];// A REVOIR, MALPROPRE car d�clarer juste au dessus dans le switch!!
	$mois=$_POST['permois'];// A REVOIR, MALPROPRE car d�clarer juste au dessus dans le switch!!
	echo "<CENTER><H3>"." an : ".$_POST['peran']." mois : ".$_POST['permois']."</H3></CENTER>\n";
	$fp = fopen($fich,'r');
	switch ($typfic)
	{
        case 1:
          	$ltyp = "charge CAA";
        	$fp1 = fopen ('chargecaa.log','a');     //fichier log
         	set_time_limit(700);
			$data = fgetcsv ($fp, 3000,';');
			//$data= str_replace (" ","",$data);
         	$datcaa = array_slice ($data,0);
            echo "<CENTER><H3>"." numirs: ".$data[0]."</H3></CENTER>\n";
            $num = count ($data);
            $countcol=0;
            foreach ($datcaa as $cle)
                {
              		$countcol=$countcol+1;
                }
            	echo "<CENTER><H3>"." COUNT : ".$countcol."</H3></CENTER>\n";
            	for ($x=3;$x<=$countcol;$x++)
                	{
                    	$datcaa[$x]=($datcaa[$x]);
                        echo "<CENTER><H3>"." retour requête B : ".$datcaa[$x]."</H3></CENTER>\n";
                     	$kproc="select ccaa from caa where codcaa='".$datcaa[$x]."'";
                        $resproc=$bd->execRequete($kproc);
                        $nblig=$bd->nbLigne($resproc);
                        if($nblig==1)
                             {
                                	$kcaa=$bd->ligTabSuivant($resproc);
                                	$tcaa[$x]=$kcaa[0];
                                 	echo "<CENTER><H3>"." retour : ".$x." codecaa : ".$tcaa[$x]."</H3></CENTER>\n";
                             }
                  	}
            		while (!feof($fp))
                            {
                              	$data = fgetcsv ($fp,2000,';');

                               	for ($y=0;$y<=$countcol;$y++)
                                  	{
                                    	if($y==0)
                                      		{
                                       			echo "<CENTER><H3>"." V1 : ".$data[0]."</H3></CENTER>\n";
                                      			$kchcaa="select centite from entite where cregate='".$data[$y]."'";
                                      			$reschcaa=$bd->execRequete($kchcaa);
                                      			$nblig=$bd->nbLigne($reschcaa);
                                       			if ($nblig==0)
                                         			{
                                       					$ano=$ano+1;
                                       					$info = $data[0].';Chargecaa absente de scribe ;'.date("j/m/Y");
                                       					fwrite ($fp1, $info);
                                       					fwrite ($fp1, "\n");
                                         			}
                                         			else
                                             			{
                                                 			$moichcaa=0;//moicharprocess
                                                 			$anchcaa=0; //ancharprocess
                                                 			$lig2=$bd->ligTabSuivant($reschcaa);
                                                 			$rescha=$lig2[0];
                                                  			echo "<CENTER><H3>"." Entite :".$rescha."</H3></CENTER>\n";
                                             			}
                                    		}
                                    		else
                                        		{
                                         			if($y>=3 and $data[$y]!=0)
                                           				{
                                             				$mtcaa= str_replace (" ","",$data[$y]);
                                             				$mtcaa= str_replace (",",".",$mtcaa);
                                             				$reqchcaa="insert into charcaa (ceentite,cecaa,charcaa,moichcaa,anchcaa) values ('".$rescha."','".$tcaa[$y]."','".$mtcaa. "','".$mois."','".$an."')";
                                             				$reschcaa= $bd->execRequete ($reqchcaa);
                                             				$ins=$ins+1;
                                           				}
                                       			}
            						}
                                	$row++;
                            }
                            $tot=$row-2;
        					$info = $ano.';lignes en anomalie : détail ci-dessus ;'.date("j/m/Y");
        					fwrite ($fp1, $info);
        					fwrite ($fp1, "\n");
        					$info = $ins.';lignes insérées ;'.date("j/m/Y");
        					fwrite ($fp1, $info);
        					fwrite ($fp1, "\n");
        					$info = $upt.';lignes mises à jours ;'.date("j/m/Y");
        					fwrite ($fp1, $info);
        					fwrite ($fp1, "\n");
        					$info = $nc.';lignes non concernées ;'.date("j/m/Y");
        					fwrite ($fp1, $info);
        					fwrite ($fp1, "\n");
        					$info = $tot.';lignes à traiter ;'.date("j/m/Y");
        					fwrite ($fp1, $info);
        					fwrite ($fp1, "\n");
        					fclose ($fp1);
           break;
        case 2:
                 $ltyp = "charge nature";
                 $data = fgetcsv ($fp, 2000,';');
                 $datcaa = array_slice($data,0);
                  echo "<CENTER><H3>"." Num : ".$data[0]."</H3></CENTER>\n";
                 $num = count ($data);
                 $countcol=0;
                  foreach ($datcaa as $cle)
                            {
                              	$countcol=$countcol+1;
                            }
                   echo "<CENTER><H3>"." COUNT : ".$countcol."</H3></CENTER>\n";
                   for ($x=3;$x<=$countcol;$x++)
                      {
                        $datcaa[$x]=($datcaa[$x]);
                         echo "<CENTER><H3>"." retour requête : ".$datcaa[$x]."</H3></CENTER>\n";
                        $kproc="select cprocess from processus where codprocess='".$datcaa[$x]."'";
                        $resproc=$bd->execRequete($kproc);
                        $nblig=$bd->nbLigne($resproc);
                        if($nblig==1)
                           {
                             	$kproc=$bd->ligTabSuivant($resproc);
                             	$tcaa[$x]=$kproc[0];
                              	echo "<CENTER><H3>"." retour : ".$x." codecaa : ".$tcaa[$x]."</H3></CENTER>\n";
                           }
                      }
                   for ($z=3;$z<=$countcol;$z++)
                      {
                        $datcaa[$z]=($datcaa[$z]);
                         echo "<CENTER><H3>"." retour requête 2 : ".$datcaa[$z]."</H3></CENTER>\n";
                        $kcenat="select cnature from nature where codrub='".$datcaa[$z]."'";
                        $rescenat=$bd->execRequete($kcenat);
                        $nblig=$bd->nbLigne($rescenat);
                        if($nblig==1)
                           {
                             $knat=$bd->ligTabSuivant($rescenat);
                             $tnat[$z]=$knat[0];
                              echo "<CENTER><H3>"." retour : ".$z." codcaa : ".$tcaa[$z]."</H3></CENTER>\n";
                           }
                      }
                       while (!feof($fp))
                             {
                               $data = fgetcsv ($fp,2000,';');
                                for ($y=0;$y<=$countcol;$y++)
                                   {
                                    if($y==0)
                                      {
                                        echo "<CENTER><H3>"." V1 : ".$data[0]."</H3></CENTER>\n";
                                       $kchnat="select centite from entite where cregate='".$data[$y]."'";
                                       $reschnat=$bd->execRequete($kchnat);
                                       $nblig=$bd->nbLigne($reschnat);
                                        if ($nblig==0)
                                          {

                                          }
                                          else
                                             {
                                               $moichnat=0;//moicharnat
                                               $anchnat=0; //ancharnat
                                               $lig2=$bd->ligTabSuivant($reschnat);
                                               $rescha=$lig2[0];
                                                echo "<CENTER><H3>"." Entité :".$rescha."</H3></CENTER>\n";
                                             }
                                      }
                                      else
                                       {
                                        if($y>=3 and $data[$y]!=0)
                                          {
                                            $cenat=$data[$z];
                                            $mtnat=$data[$y];
                                            $reqchnat="insert into charnature (ceentite,ceprocess,charnat,cenature,moichcaa,anchcaa) values ('".$rescha."','".$tcaa[$y]."','".$mtnat. "','".$cenat."','".$moichnat."','".$anchnat."')";
                                            $reschnat= $bd->execRequete ($reqchnat);
                                            $ins=$ins+1;
                                          }
                                       }
                                  }
                                  $row++;
                            }
                            $tot=$row-2;
                break;
        case 3:
          	$ltyp="CAA";
        	$fp3 = fopen ('caa.log','a');     //fichier log
           	while (!feof($fp))
               {
                    $data = fgetcsv ($fp, 1000,';');
			 		$num = count ($data);
			 		if ($row>0 and !feof($fp))
                    {
			 			if ($data[1]<>"")
			 			{

			 				$data[0]=strtoupper($data[0]);
							$lch=strlen($data[0]);
                                                        $lisproc=substr($data[0],0,$lch-5) ;
			        		        $csproc=substr($data[0],-4);
							$data[1]=strtoupper($data[1]);
							$lch=strlen($data[1]);
			 				$data[2]=strtr($data[2],"'"," ");
                                                        $data[2]=strtoupper($data[2]);
							$lch=strlen($data[2]);
							$datdca=$data[3];
                            $datfca=$data[4];
                            $kcaa="";
                            $reqcaa = "select cssprocess from sousprocessus where codssprocess='".$csproc."'";
                            $rescaa = $bd->execRequete($reqcaa);
                            $lig2=$bd->ligTabSuivant($rescaa);
                            $kcaa=$lig2[0];
                             echo "<CENTER><H3>"." retour requête21: ".$kcaa."</H3></CENTER>\n";
                             echo "<CENTER><H3>"." caa : ".$data[2]."</H3></CENTER>\n";
                            $reqcaa = "select count(*) from caa  where libcaa='".$data[2]."' and codcaa='".$data[1]."' and cessprocess='".$kcaa."'";
							$rescaa = $bd->execRequete($reqcaa);
							$nbmsg = mysql_result($rescaa,0,0);
                             echo "<CENTER><H3>"." retour requête: ".$nbmsg."</H3></CENTER>\n";
                            if ($nbmsg == 1)
   	                       {
                               $nc=$nc+1;
                           }
			 			   else
                           {
                               $reqcaa = "select count(*) from caa where libcaa='".$data[2]."'and codcaa<>'".$data[1]."' and cessprocess<>'".$kcaa."'";
			 			   	   $rescaa = $bd->execRequete($reqcaa);
                               $nbmsg2 = mysql_result($rescaa,0,0);
                               echo "<CENTER><H3>"."msg2:".$nbmsg2."</H3></CENTER>\n";
                               if ($nbmsg2 == 1)
                               {
                               	   $reqcaa = "update caa set cessprocess='".$kcaa."' ,codcaa='".$data[1]."' ,andeb= '".$datdca."' , anfin=".$datfca." where libcaa='".$data[2]."'";
                                   $rescaa= $bd->execRequete ($reqcaa);
                                   $upt=$upt+1;
                               }
                               else
                               {
                                   $reqcaa = "select count(*) from caa where libcaa='".$data[2]."' and codcaa<>'".$data[1]."' and cessprocess='".$kcaa."'";
                                   $rescaa = $bd->execRequete($reqcaa);
                                   $nbmsg3 = mysql_result($rescaa,0,0);
                         	       echo "<CENTER><H3>"."msg3:".$nbmsg3."</H3></CENTER>\n";
                         	       if($nbmsg3==1)
                                   {
                                    	$reqcaa1 = "update caa set codcaa='".$data[1]."',andeb= '".$datdca."',anfin='".$datfca." where libcaa ='".$data[2]."'";
                                        $rescaa1=  $bd->execRequete ($reqcaa1);
                                        $upt=$upt+1;
                                   }
                                   else
                                   {
                                   	$reqcaa = "select count(*) from caa where libcaa<>'".$data[2]."'and codcaa='".$data[1]."'";
                                   	$rescaa = $bd->execRequete($reqcaa);
                                   	$nbmsg4 = mysql_result($rescaa,0,0);
                                   	echo "<CENTER><H3>"."msg4:".$nbmsg4."</H3></CENTER>\n";
                                   	if($nbmsg4==1)
                                   	{
                                   		$reqcaa1 = "update caa set libcaa='".$data[2]."',andeb= '".$datdca."',anfin='".$datfca." where codcaa ='".$data[1]."'";
                                   		$rescaa1=  $bd->execRequete ($reqcaa1);
                                   		$upt=$upt+1;
                                   	}
                                   	else
                                   	{
                                   		echo "<CENTER><H3>"." retour requête22: ".$kcaa."</H3></CENTER>\n";
                                        $reqcaa="insert into caa (codcaa,libcaa,cessprocess,andeb,anfin) values ('".$data[1]."','".$data[2]."','".$kcaa. "','".$datdca."','".$datfca."')";
                                        $rescaa= $bd->execRequete ($reqcaa);
                                        $ins=$ins+1;
                                   	  }
                                   }
                               }
                           }
			 			}
			 			if ($nbmsg==0)
			 			{
			 				$ano=$ano+1;
			 				$info = $data[0].';caa absente de scribe ;'.date("j/m/Y");
			 				fwrite ($fp3, $info);
			 				fwrite ($fp3, "\n");
			 			}
			 			else
			 			{
			 				$nc=$nc+1;
			 			}
                       }$row++;
                     }
             	$tot=$row-2 ;
        		$info = $ano.';lignes en anomalie : detail ci-dessus ;'.date("j/m/Y");
        		fwrite ($fp3, $info);
        		fwrite ($fp3, "\n");
        		$info = $ins.';lignes insérées ;'.date("j/m/Y");
        		fwrite ($fp3, $info);
        		fwrite ($fp3, "\n");
        		$info = $upt.';lignes mises à jours ;'.date("j/m/Y");
        		fwrite ($fp3, $info);
        		fwrite ($fp3, "\n");
        		$info = $nc.';lignes non concernées ;'.date("j/m/Y");
        		fwrite ($fp3, $info);
        		fwrite ($fp3, "\n");
        		$info = $tot.';lignes à traiter ;'.date("j/m/Y");
        		fwrite ($fp3, $info);
        		fwrite ($fp3, "\n");
        		fclose ($fp3);
          		$reqcaa2="select ccaa from caa where cessprocess='0'";
          		$rescaa2 = $bd->execRequete($reqcaa2);
          		$nblig=$bd->nbLigne($rescaa2);
          		for($j=0; $j<$nblig; $j++)
          		{
          			$lig=$bd->ligTabSuivant($rescaa2);
          			$progdel="delete from caa where ccaa='".$lig[0]."'";
          			$resultatsprogdel = $bd->execRequete($progdel);
          		}
                break;
       	case 4:
          	$ltyp="TCA";
       		$fp4 = fopen ('tca.log','a');     //fichier log
            While (!feof($fp))
		    	{
					$data = fgetcsv ($fp, 1000,';');
					$num = count ($data);
					if ($row>0 and !feof($fp))
						{
							if ($data[0]<>"")
								{
									$data[0]=strtoupper($data[0]);  //code tca
									$lch=strlen($data[0]);
									$data[1]=strtr($data[1],"'"," ");
				                    $data[1]=strtoupper($data[1]);  //libell� tca
									$lch=strlen($data[1]);
				                    $data[2]=strtoupper($data[2]); //caaprincipal
				                    $lch=strlen($data[2]);
				                    $datdtca=$data[3];             //andeb
				                    $datftca=$data[4];             //anfin
				                    $kpprinc="";
							 		$reqpprinc = "select cprocess from processus,sousprocessus,caa where codcaa='".$data[2]."' and cessprocess=cssprocess and cprocess=ceprocess";
							 		$respprinc = $bd->execRequete($reqpprinc);
							 		$lig2=$bd->ligTabSuivant($respprinc);
							 		$kpprinc=$lig2[0];
									echo "<CENTER><H3>"." tca : ".$data[0]." processus princ :".$data[2]."</H3></CENTER>\n";
									$reqtca = "select count(*) from tca where libtca='".$data[1]."' and codtca='".$data[0]."' and cprocprinc='".$kpprinc."'";
									$restca = $bd->execRequete($reqtca);
									$nbmsg = mysql_result($restca,0,0);
                 					echo "<CENTER><H3>"." retour requête: ".$nbmsg."</H3></CENTER>\n";
                    				if ($nbmsg == 1)
   	               						{
                       						$nc=$nc+1;
                   						}
                   						else
                  							{
						                     	$reqtca = "select count(*) from tca where libtca='".$data[1]."' and codtca<>'".$data[0]."' and cprocprinc<>'".$kpprinc."'";
						                     	$restca = $bd->execRequete($reqtca);
						                     	$nbmsg2 = mysql_result($restca,0,0);
						                     	echo "<CENTER><H3>"."msg2:".$nbmsg2."</H3></CENTER>\n";
                      							if ($nbmsg2 == 1)
                      								{
                           								$reqtca = "update tca set codtca='".$data[0]."' ,cprocprinc='".$kpprinc."',andeb= '".$datdtca."',anfin='".$datftca."' where libtca ='".$data[1]."'";
                           								$restca= $bd->execRequete ($reqtca );
                           								$upt=$upt+1;
                       								}
                       								else
                      									{
                           									$reqtca = "select count(*) from tca where libtca='".$data[1]."' and codtca<>'".$data[0]."' and cprocprinc='".$kpprinc."'";
                           									$restca = $bd->execRequete($reqtca);
                           									$nbmsg3 = mysql_result($restca,0,0);
                           									echo "<CENTER><H3>"."msg3:".$nbmsg3."</H3></CENTER>\n";
                           									if($nbmsg3==1)
                           										{
									                                echo "<CENTER><H3>"." retour requête: ".$kpprinc."</H3></CENTER>\n";
									                                $reqtca = "update tca set codtca='".$data[0]."' ,andeb= '".$datdtca."',anfin='".$datftca."' where libtca='".$data[1]."'";
									                                $restca= $bd->execRequete ($reqtca);
									                                $upt=$upt+1;
                           										}
                           										else
                           											{
										                           		$reqlibtca="select count(*) from tca where codtca='".$data[0]."' and libtca <>'".$data[1]."'";
										                           		$resultatlib = $bd->execRequete($reqlibtca);
										                           		$nbmsg4 = mysql_result($resultatlib,0,0);
										                           		if ($nbmsg4==1)
                           													{
											                           			$reqlibtca = "update tca set libtca='".$data[1]."' where codtca='".$data[0]."'";
											                           			$requetlibtca= $bd->execRequete ($reqlibtca);
											                           			$upt=$upt+1;
                           													}
                           													else
                           														{
									                                                echo "<CENTER><H3>"." retour requête22: ".$kpprinc."</H3></CENTER>\n";
									                                                $reqtca="insert into tca (libtca,codtca,cprocprinc,andeb,anfin) values ('".$data[1]."','".$data[0]."','".$kpprinc. "','".$datdtca."','".$datftca."')";
									                                                $restca= $bd->execRequete ($reqtca);
									                                                $ins=$ins+1;
                           														}
                           											}
                      	 								}
                       						}
                     			}
								if ($nbmsg==0)
									{
										$ano=$ano+1;
										$info = $data[0].';tca absent de scribe ;'.date("j/m/Y");
										fwrite ($fp4, $info);
										fwrite ($fp4, "\n");
									}
                     				else
                     					{
                          					$nc=$nc+1;
                     					}
						}
                     	$row++;
                 }
              	$tot=$row-2 ;
       			$info = $ano.';lignes en anomalie : détail ci-dessus ;'.date("j/m/Y");
       			fwrite ($fp4, $info);
       			fwrite ($fp4, "\n");
       			$info = $ins.';lignes insérées ;'.date("j/m/Y");
       			fwrite ($fp4, $info);
       			fwrite ($fp4, "\n");
       			$info = $upt.';lignes mises à jours ;'.date("j/m/Y");
       			fwrite ($fp4, $info);
       			fwrite ($fp4, "\n");
       			$info = $nc.';lignes non concernées ;'.date("j/m/Y");
       			fwrite ($fp4, $info);
       			fwrite ($fp4, "\n");
       			$info = $tot.';lignes à traiter ;'.date("j/m/Y");
       			fwrite ($fp4, $info);
       			fwrite ($fp4, "\n");
       			fclose ($fp4);
           		$reqtca="select ctca from tca where cprocprinc='0'";
           		$restca = $bd->execRequete($reqtca);
           		$nblig=$bd->nbLigne($restca);
           		for($j=0; $j<$nblig; $j++)
           		  	{
           				$lig=$bd->ligTabSuivant($restca);
           				$progdel="delete from tca where ctca='".$lig[0]."'";
           				$resultatsprogdel = $bd->execRequete($progdel);
           		  	}
                  break;
       	case 5:
          	$ltyp="sous processus";
       		$fp5 = fopen ('Sous processus.log','a');     //fichier log
          	while (!feof($fp))
              	{
                  	$data = fgetcsv ($fp, 1000,';');
			      	$num = count ($data);
			 		if ($row>0 and !feof($fp))
                		{
			 				if ($data[0]<>"")
			 					{
                    				$data[0]=strtoupper($data[0]);
									$lch=strlen($data[0]);
									$lproc=substr($data[0],0,$lch-5) ;
									$cproc=substr($data[0],-4);
                    				$data[1]=strtoupper($data[1]);
									$lch=strlen($data[1]);
									$libsproc=substr($data[1],0,$lch-5) ;
									$csproc=substr($data[1],-4);
									$datedeb=$data[2];
                    				$datefin=$data[3];
			 						$kproc2="";
			 						$requetsproc = "select cprocess from processus where codprocess='".$cproc."'";
			 						$resultatsproc = $bd->execRequete($requetsproc);
			 						$lig2=$bd->ligTabSuivant($resultatsproc);
			 						$kproc2=$lig2[0];
                    				echo "<CENTER><H3>"." sousprocessus : ".$libsproc." processus :".$lproc."</H3></CENTER>\n";
									$requetSproc = "select count(*) from sousprocessus  where libssprocess='".$libsproc."' and codssprocess='".$csproc."' and ceprocess='".$kproc2."'";
									$resultatSproc = $bd->execRequete($requetSproc);
									$nbmsg = mysql_result($resultatSproc,0,0);
                 					echo "<CENTER><H3>"." retour requête: ".$nbmsg."</H3></CENTER>\n";
                    				if ($nbmsg == 1)
   	                					{
                       						$nc=$nc+1;
                    					}
                    					else
                    						{
                       							$requetSproc = "select count(*) from sousprocessus where libssprocess='".$libsproc."'and codssprocess<>'".$csproc."' and ceprocess<>'".$kproc2."'";
                       							$resultatSproc = $bd->execRequete($requetSproc);
                       							$nbmsg2 = mysql_result($resultatSproc,0,0);
                       							echo "<CENTER><H3>"."msg2:".$nbmsg2."</H3></CENTER>\n";
                       							if ($nbmsg2 == 1)
                       								{
                           								$reqSproc = "update sousprocessus set codssprocess='".$csproc."' ,ceprocess='".$kproc2."',datdebsoproc= '".$datedeb."',datfinsoproc='".$datefin."' where libssprocess ='".$libsproc."'";
                           								$resSproc= $bd->execRequete ($reqSproc);
                           								$upt=$upt+1;
                       								}
                       								else
                       									{
                           									$requetSproc = "select count(*) from sousprocessus where libssprocess='".$libsproc."' and codssprocess<>'".$csproc."' and ceprocess='".$kproc2."'";
                           									$resultatSproc = $bd->execRequete($requetSproc);
                           									$nbmsg3 = mysql_result($resultatSproc,0,0);
                           									echo "<CENTER><H3>"."msg3:".$nbmsg3."</H3></CENTER>\n";
                           									if($nbmsg3==1)
                           										{
                                									echo "<CENTER><H3>"." retour requête: ".$kproc2."</H3></CENTER>\n";
                                									$reqsproc1 = "update sousprocessus set codssprocess='".$csproc."' ,datdebsoproc= '".$datedeb."' , datfinsoproc='".$datefin."' where libssprocess='".$libsproc."'";
                                									$ressproc1= $bd->execRequete ($reqsproc1);
                                									$upt=$upt+1;
                           										}
                           										else
                           											{
                           												$reqlibssp="select count(*) from sousprocessus where codssprocess='".$csproc."' and libssprocess <>'".$libsproc."'";
                           												$resultatlib = $bd->execRequete($reqlibssp);
                           												$nbmsg4 = mysql_result($resultatlib,0,0);
                           												echo "<CENTER><H3>"." msg4: ".$nbmsg4."</H3></CENTER>\n";
                           												if ($nbmsg4==1)
                           													{
                           														$reqlibssp = "update sousprocessus set libssprocess='".$libsproc."' where codssprocess='".$csproc."'";
                           														$requetlibssp= $bd->execRequete ($reqlibssp);
                           														$upt=$upt+1;
                           													}
                           													else
                           														{
                           															echo "<CENTER><H3>"." retour requête22: ".$kproc2."</H3></CENTER>\n";
                           															$reqsProc="insert into sousprocessus (libssprocess,codssprocess,ceprocess,datdebsoproc,datfinsoproc) values ('".$libsproc."','".$csproc."','".$kproc2. "','".$datedeb."','".$datefin."')";
                           															$ressProc= $bd->execRequete ($reqsProc);
                           															$ins=$ins+1;
                           														}
                           											}
                       								}
                   							}
			 					}
			 					if ($nbmsg==0)
			 						{
			 							$ano=$ano+1;
			 							$info = $data[0].';Sous processus absent de scribe ;'.date("j/m/Y");
			 							fwrite ($fp5, $info);
			 							fwrite ($fp5, "\n");
			 						}
			 						else
			 							{
			 								$nc=$nc+1;
			 							}
                		}
                 		$row++;
              }
          	$tot=$row-2 ;
       		$info = $ano.';lignes en anomalie : detail ci-dessous ;'.date("j/m/Y");
       		fwrite ($fp5, $info);
       		fwrite ($fp5, "\n");
       		$info = $ins.';lignes insérées ;'.date("j/m/Y");
       		fwrite ($fp5, $info);
       		fwrite ($fp5, "\n");
       		$info = $upt.';lignes mises à jours ;'.date("j/m/Y");
       		fwrite ($fp5, $info);
       		fwrite ($fp5, "\n");
       		$info = $nc.';lignes non concernées ;'.date("j/m/Y");
       		fwrite ($fp5, $info);
       		fwrite ($fp5, "\n");
       		$info = $tot.';lignes à traiter ;'.date("j/m/Y");
       		fwrite ($fp5, $info);
       		fwrite ($fp5, "\n");
       		fclose ($fp5);
           	$reqsproc1="select cssprocess from sousprocessus where codssprocess='0'";
           	$resultatsproc = $bd->execRequete($reqsproc1);
           	$nblig=$bd->nbLigne($resultatsproc);
           	for($j=0; $j<$nblig; $j++)
           	{
           		$lig=$bd->ligTabSuivant($resultatsproc);
           		$progdel="delete from sousprocessus where cssprocess='".$lig[0]."'";
           		$resultatsprogdel = $bd->execRequete($progdel);
           	}
              break;
       	case 6:
         	$ltyp="processus";
       		$fp6 = fopen ('domaine.log','a');     //fichier log
          	While (!feof($fp))
		    	{
					$data = fgetcsv ($fp, 1000,';');
					$num = count ($data);
					if ($row>0 and !feof($fp))
						{
							$data[0]=strtoupper($data[0]);
							$lch=strlen($data[0]);
							$dom=substr($data[0],0,$lch-5) ;
							$codom=substr($data[0],-4);
			                $data[1]=strtoupper($data[1]);
							$lch=strlen($data[1]);
							$proc=substr($data[1],0,$lch-5) ;
							$cproc=substr($data[1],-4);
							$ordaff=$data[4];
							$caaref=$data[5];
			                $datedeb=$data[2];
			                $datret1=explode("/",$datedeb);
							$datdr= $datret1[1]."-".$datret1[0]."-".$datret1[2];
			                $datefin=$data[3];
			                $datret2=explode("/",$datefin);
							$datfr= $datret2[1]."/".$datret2[0]."/".$datret2[2];
							$datfr= date('Y/m/d');
							echo "<CENTER><H3>"."fin :".$datret2[1].",".$datret2[0].",".$datret2[2].",".$datfr." domaine  : ".$dom." processus :".$cproc."</H3></CENTER>\n";
							$requetproc = "select count(*) from processus, domaine where libprocess='".$proc."' and codprocess='".$cproc."' and cedom=cdom and coddom='".$codom."'";
							$resultatproc = $bd->execRequete($requetproc);
							$nbmsg = mysql_result($resultatproc,0,0);
                         	echo "<CENTER><H3>"." retour requête: ".$nbmsg."</H3></CENTER>\n";
                            if ($nbmsg == 1)
   	                        	{
                                    $nc=$nc+1;
                                }
                              	else
                                  {
                                       	$requetproc = "select count(*) from processus,domaine where libprocess='".$proc."' and codprocess<>'".$cproc."' and cedom=cdom and coddom='".$codom."'";
                                       	$resultatproc = $bd->execRequete($requetproc);
                                       	$nbmsg2 = mysql_result($resultatproc,0,0);
										echo "<CENTER><H3>"."msg2:".$nbmsg2."</H3></CENTER>\n";
                                        if ($nbmsg2==1)
                                           {
                                             	$reqproc = "update processus set codprocess='".$cproc."' ,datdebp= '".$datdr."' ,datfinp=".$datfr." where libprocess ='".$proc."'";
                                             	$resproc= $bd->execRequete ($reqproc);
                                             	$upt=$upt+1;
                                           }
                                           else
                                           		{
                                             		$requetproc = "select count(*) from domaine,processus where libprocess='".$proc."' and codprocess<>'".$cproc."' and cedom<>cdom and coddom='".$codom."'";
                                             		$resultatproc = $bd->execRequete($requetproc);
                                             		$nbmsg3 = mysql_result($resultatproc,0,0);
                                           			echo "<CENTER><H3>"."msg3:".$nbmsg3."</H3></CENTER>\n";
                                              		if ($nbmsg3==1)
                                                 		{
                                              				$kdom="";
                                                			$requetproc = "select cdom from domaine where libdom='".$dom."'";
                                                			$resultatproc = $bd->execRequete($requetproc);
                                                			$lig=$bd->ligTabSuivant($resultatproc);
                                                			$kdom=$lig[0];
                                                			echo "<CENTER><H3>"." retour requête: ".$kdom."</H3></CENTER>\n";
                                                			$reqproc1 = "update processus set codprocess='".$cproc."' ,cedom='".$kdom."' ,datdebp= '".$datdr."' , datfinp='".$datfr."' where libprocess='".$proc."'";
                                                			$resproc1= $bd->execRequete ($reqproc1);
                                                			$upt=$upt+1;
                                                 		}
                                              			else
                                                 			{
                                                				$kdom2="";
                                                				$requetproc = "select cdom from domaine where coddom='".$codom."'";
                                                				$resultatproc = $bd->execRequete($requetproc);
                                                				$lig2=$bd->ligTabSuivant($resultatproc);
                                                				$kdom2=$lig2[0];
                                                				echo "<CENTER><H3>"." retour requête22: ".$kdom2."</H3></CENTER>\n";
                                                				$reqProc="insert into processus (libprocess,codprocess,cedom,cordaffich,caaref,datdebp,datfinp) values ('".$proc."','".$cproc."','".$kdom2. "','".$ordaff."','".$caaref."','".$datedebp."','".$datefinp."')";
                                                				$resProc= $bd->execRequete ($reqProc);
                                                				$ins=$ins+1;
                                             				}
                                         		}
                                 }
                      	}
               			$reqlibproc="select count(*) from processus where codprocess='".$cproc."' and libprocess <>'".$proc."'";
               			$resultatlib = $bd->execRequete($reqlibproc);
               			$nbmsg4 = mysql_result($resultatlib,0,0);
               			if ($nbmsg4=1)
                  			{
                     			$reqlibp = "update processus set libprocess='".$proc."' where codprocess='".$cproc."'";
                     			$requetlibp= $bd->execRequete ($reqlibp);
                     			$upt=$upt+1;
                  			}
          					if ($nbmsg==0)
          						{
          							$ano=$ano+1;
          							$info = $data[0].';processus absent de scribe ;'.date("j/m/Y");
          							fwrite ($fp6, $info);
          							fwrite ($fp6, "\n");
          						}
          						else
          						{
          							$nc=$nc+1;
          						}
              					$row++;
        		}
     			$tot=$row-2 ;
       			$info = $ano.';lignes en anomalie : detail ci-dessous ;'.date("j/m/Y");
       			fwrite ($fp6, $info);
       			fwrite ($fp6, "\n");
       			$info = $ins.';lignes insérées ;'.date("j/m/Y");
       			fwrite ($fp6, $info);
       			fwrite ($fp6, "\n");
       			$info = $upt.';lignes mises à jours ;'.date("j/m/Y");
       			fwrite ($fp6, $info);
       			fwrite ($fp6, "\n");
       			$info = $nc.';lignes non concernées ;'.date("j/m/Y");
       			fwrite ($fp6, $info);
       			fwrite ($fp6, "\n");
       			$info = $tot.';lignes à traiter ;'.date("j/m/Y");
       			fwrite ($fp6, $info);
       			fwrite ($fp6, "\n");
       			fclose ($fp6);
       			$reqproc1="select cprocess from processus where codprocess='0'";
       			$resultatproc = $bd->execRequete($reqproc1);
       			$nblig=$bd->nbLigne($resultatproc);
       			for($j=0; $j<$nblig; $j++)
       				{
       					$lig=$bd->ligTabSuivant($resultatproc);
       					$progdel="delete from processus where cprocess='".$lig[0]."'";
       					$resultatprogdel = $bd->execRequete($progdel);
       				}
                break;
        case 7:
           	$ltyp= "domaine";
           	$fp7 = fopen ('domaine.log','a');     //fichier log
		   	While (!feof($fp))
		    	{
					$data = fgetcsv ($fp, 1000,';');
					$num = count ($data);
					if ($row>0 and !feof($fp))
						{
							$data[0]=strtoupper($data[0]);
							$lch=strlen($data[0]);
							$dom=substr($data[0],0,$lch-5) ;
							$cdom=substr($data[0],-4);
							$scrib=$data[2];
							$mdp=$data[1];
							echo "<CENTER><H3>".$data[0]." domaine  : ".$dom."</H3></CENTER>\n";
							$requetnb = "SELECT count(*) FROM domaine WHERE libdom='".$dom."'";
							$resultatnb = $bd->execRequete($requetnb);
							$nbmsg=mysql_result($resultatnb,0,0);
							if ($nbmsg<>0)
								{
									$requetnb2 = "SELECT count(*) FROM domaine WHERE libdom='".$dom."' and coddom='".$cdom."'";
									$resultatnb2 = $bd->execRequete($requetnb2);
									$nbmsg2=mysql_result($resultatnb2,0,0);
									if ($nbmsg2<>0)
				        				{

										}
										else
											{
												$requete = "select  count(*) from domaine where libdom = '".$dom."' and coddom <> '".$cdom."'";
                            					$resultat = $bd->execRequete ($requete);
                            					$nbmsg=mysql_result($resultat,0,0);
                    							if ($nbmsg >0 )
                          							{
                                    					$reqdom="update domaine set coddom='".$cdom."' and ordmdp='".$mdp."' and ordscrib='".$scrib."' where libdom ='".$dom."'";
														$resdom= $bd->execRequete ($reqdom);
														$upt=$upt+1;
                           							}
				         					}
                			}
              				else
                  				{
              						$requete3 = "select  count(*) from domaine where coddom = '".$cdom."' and libdom <> '".$dom."'";
               						$resultat3 = $bd->execRequete ($requete3);
               						$nbmsg3=mysql_result($resultat3,0,0);
                 					if ($nbmsg3 >0 )
                      					{
                       						$reqdom="update domaine set libdom='".$dom."' and ordmdp='".$mdp."' and ordscrib='".$scrib."' where coddom ='".$cdom."'";
					   						$resdom= $bd->execRequete ($reqdom);
					   						$upt=$upt+1;
                      					}
                     					else
                    						{
                       							$reqins="insert into domaine (libdom,coddom,ordmdp,ordscrib) values ('".$dom."','".$cdom."','".$mdp."','".$scrib."')";
                       							$resins= $bd->execRequete ($reqins);
                       							$ins=$ins+1;
                    						}
              					}
						}
		    			else
							{
		    					if ($nbmsg==0)
		    						{
		    							$ano=$ano+1;
		    							$info = $data[0].';domaine absent de scribe ;'.date("j/m/Y");
		    							fwrite ($fp7, $info);
		    							fwrite ($fp7, "\n");
		    						}
		    						else
		    							{
		    								$nc=$nc+1;
		    							}
							}
            				$row++;
          		}
				$tot=$row-2 ;
        		$info = $ano.';lignes en anomalie : detail ci-dessous ;'.date("j/m/Y");
        		fwrite ($fp7, $info);
        		fwrite ($fp7, "\n");
        		$info = $ins.';lignes insérées ;'.date("j/m/Y");
        		fwrite ($fp7, $info);
        		fwrite ($fp7, "\n");
        		$info = $upt.';lignes mises à jours ;'.date("j/m/Y");
        		fwrite ($fp7, $info);
        		fwrite ($fp7, "\n");
        		$info = $nc.';lignes non concernées ;'.date("j/m/Y");
        		fwrite ($fp7, $info);
        		fwrite ($fp7, "\n");
        		$info = $tot.';lignes à traiter ;'.date("j/m/Y");
        		fwrite ($fp7, $info);
        		fwrite ($fp7, "\n");
        		fclose ($fp7);
              break;
        case 8:
            $ltyp= "nature";
        	$fp6 = fopen ('nature.log','a');     //fichier log
         	While (!feof($fp))
		    	{
                     $data = fgetcsv ($fp, 1000,';');
		     		$num = count ($data);
		     		if ($row>0 and !feof($fp))
		      			{
                       		if ($data[0]<>"")
								{
                        			$data[0]=strtoupper($data[0]);      //nature
									$lch=strlen($data[0]);
                					$data[1]=strtoupper($data[1]);
									$lch=strlen($data[1]);
									if ($lch<>3)
										{
											$codrub=substr($data[1],0,8);        //codrub
											$librub=substr($data[1],9,$lch-9);
											$data[2]=strtoupper($data[2]);        //librub
                							$lch1=strlen($data[2]);
                							$codcg=substr($data[2],0,8);  //codcg
											$libcg=substr($data[2],9,$lch1-9);        //libcg
                         				}
                         				else
                         					{
                           						$codrub=$data[1];
                           						$librub=99;
                           						$codcg=99;
                           						$libcg=99;
                         					}
                						$data[3]=strtoupper($data[3]);     //detail
                						$lch=strlen($data[3]);
                						$datdnat=$data[4];                //datdebnat
                						$datfnat=$data[5];                //datfinnat
                   						$reqnat = "select count(*) from nature where nature='".$data[0]."' and codrub='".$codrub."' and librub='".$librub."' and codcg='".$codcg."'and libcg='".$libcg."'and detail='".$data[3]."'";
		   								$resnat = $bd->execRequete($reqnat);
		   								$nbmsg = mysql_result($resnat,0,0);
                    					echo "<CENTER><H3>"." retour requête: ".$nbmsg."</H3></CENTER>\n";
                   						if ($nbmsg == 1)
   	            							{
                        						$nc=$nc+1;
                   							}
                    						else
                      							{
                      								$reqnat = "select count(*) from nature where nature<>'".$data[0]."' and codrub<>'".$codrub."' and librub<>'".$librub."' and codcg<>'".$codcg."'and libcg<>'".$libcg."'and detail<>'".$data[3]."'";
                       								$resnat = $bd->execRequete($reqnat);
                       								$nbmsg2 = mysql_result($resnat,0,0);
                        							echo "<CENTER><H3>"."msg2:".$nbmsg2."</H3></CENTER>\n";
                       								if ($nbmsg2==1)
                        								{
                             								$reqnat = "update nature set nature='".$data[0]."' and codrub='".$codrub."' and librub='".$librub."' and codcg='".$codcg."'and libcg='".$libcg."'and detail='".$data[3]."'";
                             								$resnat= $bd->execRequete ($reqnat);
                             								$upt=$upt+1;
                        								}
                        								else
                           									{
                             									$reqnat = "select count(*) from nature where nature='".$data[0]."' and codrub<>'".$codrub."' and librub<>'".$librub."' and codcg='".$codcg."'and libcg='".$libcg."'and detail='".$data[3]."'";
                             									$resnat = $bd->execRequete($reqnat);
                             									$nbmsg3 = mysql_result($resnat,0,0);
                              									echo "<CENTER><H3>"."msg3:".$nbmsg3."</H3></CENTER>\n";
                    	     									if ($nbmsg3==1)
                              										{
                                										$reqnat = "update nature set nature='".$data[0]."' and codrub='".$codrub."' and librub='".$librub."' and codcg='".$codcg."'and libcg='".$libcg."'and detail='".$data[3]."'";
                                										$resnat= $bd->execRequete ($reqnat);
                                										$upt=$upt+1;
                              										}
                              										else
                                										{
                          	  												$reqnat = "select count(*) from nature where nature='".$data[0]."' and codrub='".$codrub."' and librub='".$librub."' and codcg<>'".$codcg."'and libcg<>'".$libcg."'and detail='".$data[3]."'";
                          	  												$resnat = $bd->execRequete($reqnat);
                          	  												$nbmsg4 = mysql_result($resnat,0,0);
                          	   												echo "<CENTER><H3>"."msg4:".$nbmsg4."</H3></CENTER>\n";
                      	 	  												if ($nbmsg4==1)
                          	   													{
       	 	                    													$reqnat = "update nature set nature='".$data[0]."' and codrub='".$codrub."' and librub='".$librub."' and codcg='".$codcg."'and libcg='".$libcg."'and detail='".$data[3]."'";
													                       	 	    $resnat= $bd->execRequete ($reqnat);
                       	 	    													$upt=$upt+1;
                          	   													}
                          	   													else
                          	      													{
                          	 															$reqnat = "select count(*) from nature where nature<>'".$data[0]."' and codrub='".$codrub."' and librub='".$librub."' and codcg='".$codcg."'and libcg='".$libcg."'and detail='".$data[3]."'";
                          	 															$resnat = $bd->execRequete($reqnat);
                          	 															$nbmsg5 = mysql_result($resnat,0,0);
                          	 	 														echo "<CENTER><H3>"."msg5:".$nbmsg5."</H3></CENTER>\n";
                          	 															if ($nbmsg5==1)
                          	 	  															{
                          	 																	$reqnat = "update nature set nature='".$data[0]."' and codrub='".$codrub."' and librub='".$librub."' and codcg='".$codcg."'and libcg='".$libcg."'and detail='".$data[3]."'";
                          	 																	$resnat= $bd->execRequete ($reqnat);
                          	 																	$upt=$upt+1;
                          	 	  															}
                          	 	   															else
                         	 	      															{
                          	 																		$reqnat = "select count(*) from nature where nature<>'".$data[0]."' and codrub='".$codrub."' and librub='".$librub."' and codcg='".$codcg."'and libcg='".$libcg."'and detail='".$data[3]."'";
                          	 																		$resnat = $bd->execRequete($reqnat);
                          	 																		$nbmsg6 = mysql_result($resnat,0,0);
                          	 		 																echo "<CENTER><H3>"."msg6:".$nbmsg6."</H3></CENTER>\n";
                          	 																		if ($nbmsg6==1)
                          	 		    																{
                          	 																				$reqnat = "update nature set nature='".$data[0]."' and codrub='".$codrub."' and librub='".$librub."' and codcg='".$codcg."'and libcg='".$libcg."'and detail='".$data[3]."'";
                          	 																				$resnat= $bd->execRequete ($reqnat);
                          	 																				$upt=$upt+1;
                          	 		     																}
                          	 		      																else
                         	 	                															{
                          	 		           																	$reqnat = "select count(*) from nature where nature='".$data[0]."' and codrub='".$codrub."' and librub='".$librub."' and codcg='".$codcg."'and libcg='".$libcg."'and detail<>'".$data[3]."'";
                          	 		           																	$resnat = $bd->execRequete($reqnat);
                          	 		           																	$nbmsg6 = mysql_result($resnat,0,0);
                          	 		            																echo "<CENTER><H3>"."msg6:".$nbmsg6."</H3></CENTER>\n";
                          	 		           																	if ($nbmsg6==1)
                          	 		            																	{
																			                          	 			     $reqnat = "update nature set nature='".$data[0]."' and codrub='".$codrub."' and librub='".$librub."' and codcg='".$codcg."'and libcg='".$libcg."'and detail='".$data[3]."'";
																			                          	 			     $resnat= $bd->execRequete ($reqnat);
																			                          	 			     $upt=$upt+1;
                          	 		            																	}
                          	 			    																		else
                         	 			     																			{
                                                               																$reqnat="insert into nature (nature,codrub,librub,codcg,libcg,detail,datdebnat,datfinnat) values ('".$data[0]."','".$codrub."','".$librub."','".$codcg."','".$libcg."','".$data[3]."','".$data[4]."','".$data[5]."')";
																                                                           	$resnat= $bd->execRequete ($reqnat);
																                                                           	$ins=$ins+1;
                        	 			      																			}
                                                           													}
                                                        											}

                                            										}
              			     											}
                               								}
                  								}
								}
		     					if ($nbmsg==0)
		     						{
		     							$ano=$ano+1;
		     							$info = $data[0].';nature absente de scribe ;'.date("j/m/Y");
		     							fwrite ($fp6, $info);
		     							fwrite ($fp6, "\n");
		     						}
									else
		    							{
                       						$nc=$nc+1;
                    					}
      					}
      					$row++;
  				}
  				$tot=$row-2 ;
        		$info = $ano.';lignes en anomalie : detail ci-dessous ;'.date("j/m/Y");
        		fwrite ($fp6, $info);
        		fwrite ($fp6, "\n");
        		$info = $ins.';lignes insérées ;'.date("j/m/Y");
        		fwrite ($fp6, $info);
        		fwrite ($fp6, "\n");
        		$info = $upt.';lignes mises à jours ;'.date("j/m/Y");
        		fwrite ($fp6, $info);
        		fwrite ($fp6, "\n");
        		$info = $nc.';lignes non concernées ;'.date("j/m/Y");
        		fwrite ($fp6, $info);
        		fwrite ($fp6, "\n");
        		$info = $tot.';lignes à traiter ;'.date("j/m/Y");
        		fwrite ($fp6, $info);
        		fwrite ($fp6, "\n");
        		fclose ($fp6);
  				$reqnat="select cnature from nature where nature='0'";
  				$resnat = $bd->execRequete($reqnat);
  				$nblig=$bd->nbLigne($resnat);
  				for($j=0; $j<$nblig; $j++)
    				{
       					$lig=$bd->ligTabSuivant($resnat);
       					$progdel="delete from nature where cnature='".$lig[0]."'";
       					$resprogdel = $bd->execRequete($progdel);
    				}
              break;
        case 9:
          	$ltyp= "dotc";
			$fp9 = fopen ('dotc.log','a');     //fichier log
          	While (!feof($fp))
		    	{
					$data = fgetcsv ($fp, 1000,';');
					$num = count ($data);
					if ($row>0 and !feof($fp))
						{
							if ($data[3]<>"")
								{
									$data[0]=strtoupper($data[0]);  //dept
									$lch=strlen($data[0]);
           							$data[1]=strtoupper($data[1]);  //libdotc
									$data[1]=strtr($data[1],"'"," ");
            						$data[1]=strtr($data[1],"-"," ");
									$lch=strlen($data[1]);
            						$data[2]=strtoupper($data[2]); //ville
									$data[2]=strtr($data[2],"'"," ");
									$data[2]=strtr($data[2],"-"," ");
            						$lch=strlen($data[2]);
            						$data[3]=strtoupper($data[3]); //cregate
            						$lch=strlen($data[3]);
            						$datddotc=$data[4];             //datdebdotc
            						$datfdotc=$data[5];             //datfindotc
									$regdotc=$data[6];
            						$reqdotc = "select count(*) from dotc where libdotc='".$data[1]."' and dept='".$data[0]."' and ville='".$data[2]."' and codep='".$data[3]."'";
									$resdotc = $bd->execRequete($reqdotc);
									$nbmsg = mysql_result($resdotc,0,0);
                					echo "<CENTER><H3>"." retour requête: ".$nbmsg."</H3></CENTER>\n";
                					if ($nbmsg == 1)
   	            						{
                        					$nc=$nc+1;
                         				}
                  						else
                  							{
                       							$reqdotc = "select count(*) from dotc where libdotc='".$data[1]."' and dept<>'".$data[0]."' and ville<>'".$data[2]."' and codep<>'".$data[3]."'";
                       							$resdotc = $bd->execRequete($reqdotc);
                       							$nbmsg2 = mysql_result($resdotc,0,0);
					   							echo "<CENTER><H3>"."msg2:".$nbmsg2."</H3></CENTER>\n";
                       							if ($nbmsg2==1)
                       								{
                             							$reqdotc = "update dotc set codep='".$data[3]."' ,dept= '".$data[0]."' ,ville= '".$data[2]."' ,datdebdotc= '".$data[4]."' ,datfindotc=".$data[5].",regdotc='".$regdotc."' where libdotc ='".$data[1]."'";
                             							$resdotc= $bd->execRequete ($reqdotc);
                             							$upt=$upt+1;
                       								}
                       								else
                       									{
                             								$reqdotc = "select count(*) from dotc where libdotc='".$data[1]."' and dept='".$data[0]."' and ville<>'".$data[2]."' and codep<>'".$data[3]."'";
                             								$resdotc = $bd->execRequete($reqdotc);
                            								$nbmsg3 = mysql_result($resdotc,0,0);
                             								echo "<CENTER><H3>"."msg3:".$nbmsg3."</H3></CENTER>\n";
                          	 								if ($nbmsg3==1)
                             									{
                                									$reqdotc = "update dotc set codep='".$data[3]."' ,ville= '".$data[2]."',datdebdotc= '".$datddotc."' ,datfindotc=".$datfdotc.",regdotc='".$regdotc."' where libdotc ='".$data[1]."'";
                                									$resdotc= $bd->execRequete ($reqdotc);
                                									$upt=$upt+1;
                             									}
                          	 									else
                             										{
                          	 											$reqdotc = "select count(*) from dotc where libdotc='".$data[1]."' and dept='".$data[0]."' and ville='".$data[2]."' and codep<>'".$data[3]."'";
                          	 											$resdotc = $bd->execRequete($reqdotc);
                          	 											$nbmsg4 = mysql_result($resdotc,0,0);
                          	 											echo "<CENTER><H3>"."msg4:".$nbmsg4."</H3></CENTER>\n";
                          	 											if ($nbmsg4==1)
                          	 												{
                          	 													$reqdotc = "update dotc set codep='".$data[3]."',datdebdotc= '".$datddotc."' ,datfindotc=".$datfdotc.",regdotc='".$regdotc."' where libdotc ='".$data[1]."'";
                          	 													$resdotc= $bd->execRequete ($reqdotc);
                          	 													$upt=$upt+1;
                          	 												}
                          	 												else
                          	 													{
                          	 														$reqdotc = "select count(*) from dotc where libdotc<>'".$data[1]."' and dept<>'".$data[0]."' and ville='".$data[2]."' and codep='".$data[3]."'";
                          	 														$resdotc = $bd->execRequete($reqdotc);
                          	 														$nbmsg5 = mysql_result($resdotc,0,0);
                          	 														echo "<CENTER><H3>"."msg5:".$nbmsg5."</H3></CENTER>\n";
                          	 														if ($nbmsg5==1)
                          	 															{
                          	 																$reqdotc = "update dotc set libdotc'".$data[1]."' ,dept= '".$data[0]."',datdebdotc= '".$datddotc."' ,datfindotc=".$datfdotc.",regdotc='".$regdotc."' where codep ='".$data[3]."'";
                          	 																$resdotc= $bd->execRequete ($reqdotc);
                          	 																$upt=$upt+1;
                          	 															}
                          	 															else
                          	 																{
                          	 																	$reqdotc = "select count(*) from dotc where libdotc='".$data[1]."' and dept<>'".$data[0]."' and ville='".$data[2]."' and codep='".$data[3]."'";
                          	 																	$resdotc = $bd->execRequete($reqdotc);
                          	 																	$nbmsg6 = mysql_result($resdotc,0,0);
                          	 																	echo "<CENTER><H3>"."msg6:".$nbmsg6."</H3></CENTER>\n";
                          	 																	if ($nbmsg6==1)
                          	 																		{
                          	 																			$reqdotc = "update dotc set dept= '".$data[0]."',datdebdotc= '".$datddotc."' ,datfindotc=".$datfdotc.",regdotc='".$regdotc."' where codep ='".$data[3]."'";
                          	 																			$resdotc= $bd->execRequete ($reqdotc);
                          	 																			$upt=$upt+1;
                          	 																		}
                          	 																		else
                          	 																			{
                          	 																				$reqlibdotc = "select count(*) from dotc where libdotc<>'".$data[1]."' and dept='".$data[0]."' and ville='".$data[2]."' and codep='".$data[3]."'";
                          	 																				$reslibdotc = $bd->execRequete($reqlibdotc);
                          	 																				$nbmsg7 = mysql_result($reslibdotc,0,0);
                          	 																				echo "<CENTER><H3>"."msg7:".$nbmsg7."</H3></CENTER>\n";
                          	 																				if ($nbmsg7==1)
                          	 																					{
                          	 																						$reqdotc = "update dotc set libdotc='".$data[1]."',datdebdotc= '".$datddotc."' ,datfindotc=".$datfdotc.",regdotc='".$regdotc."' where codep ='".$data[3]."'";
                          	 																						$resdotc= $bd->execRequete ($reqdotc);
                          	 																						$upt=$upt+1;
                          	 																					}
                          	 																					else
                          	 																						{
                                                																		$reqdotc="insert into dotc (libdotc,codep,dept,ville,datdebdotc,datfindotc,regdotc) values ('".$data[1]."','".$data[3]."','".$data[0]."','".$data[2]."','".$datddotc."','".$datfdotc."','".$regdotc."')";
                                                																		$resdotc= $bd->execRequete ($reqdotc);
                                                																		$ins=$ins+1;
                          	 																						}
                             																			}
                       																		}
                     															}
                  													}
                       									}
                  							}
								}
								else
									if ($nbmsg==0)
										{
											$ano=$ano+1;
											$info = $data[0].';dotc absente de scribe ;'.date("j/m/Y");
											fwrite ($fp9, $info);
											fwrite ($fp9, "\n");
										}
										else
											{
                   								$nc=$nc+1;
                							}
						}
            			$row++;
        		}
     			$tot=$row-2 ;
        		$info = $ano.';lignes en anomalie : detail ci-dessous ;'.date("j/m/Y");
        		fwrite ($fp9, $info);
        		fwrite ($fp9, "\n");
        		$info = $ins.';lignes insérées ;'.date("j/m/Y");
        		fwrite ($fp9, $info);
        		fwrite ($fp9, "\n");
        		$info = $upt.';lignes mises à jours ;'.date("j/m/Y");
        		fwrite ($fp9, $info);
        		fwrite ($fp9, "\n");
        		$info = $nc.';lignes non concernées ;'.date("j/m/Y");
        		fwrite ($fp9, $info);
        		fwrite ($fp9, "\n");
        		$info = $tot.';lignes à traiter ;'.date("j/m/Y");
        		fwrite ($fp9, $info);
        		fwrite ($fp9, "\n");
        		fclose ($fp9);
				$reqdotc="select cdotc from dotc where codep='0'";
				$resdotc = $bd->execRequete($reqdotc);
				$nblig=$bd->nbLigne($resdotc);
				for($j=0; $j<$nblig; $j++)
					{
						$lig=$bd->ligTabSuivant($resultatcdotc);
						$progdel="delete from dotc where cdotc='".$lig[0]."'";
						$resultatprogdel = $bd->execRequete($progdel);
					}
        	break;
        case 10:
       		$ltyp= "entité";
        	$fp10 = fopen ('entite.log','a');     //fichier log
        	set_time_limit(420);
       		while (!feof($fp))
       			{
          			$data = fgetcsv ($fp, 1000,';');
      	  			$num = count ($data);
          			if ($row>0 and !feof($fp))
          				{
          					if ($data[1]<>"")
          						{
          							$data[0]=strtoupper($data[0]);          //dotc
          							$lch=strlen($data[0]);
          		   					$data[1]=strtoupper($data[1]);      //cregate
          		   					$lch=strlen($data[1]);
          		   					$data[2]=strtr($data[2],"'"," ");   //libentite
          		   					$data[2]=strtoupper($data[2]);
          		   					$lch=strlen($data[2]);
          		  					$cetca=$data[3];                    //cetca
          		   					$datdebent=$data[4];                //datdebent
          		   					$datfinent=$data[5];                //datfinent
          		   					$kent="";
          							$req1 = "select count(*) from dotc where regdotc='".$data[0]."'";
          							$res1 = $bd->execRequete($req1);
          							$lig1=mysql_result($res1,0,0);
          							echo "<CENTER><H3>"." Dotc: ".$lig1."</H3></CENTER>\n";
          		    				if ($lig1<>0)
          		      					{
          									$reqent = "select cdotc from dotc where regdotc='".$data[0]."'";
          									$resent = $bd->execRequete($reqent);
          									$lig2=$bd->ligTabSuivant($resent);
          									$kent=$lig2[0];
          		      					}
          		      					else $kent="99";
          		       					$kent2="";  //cetca
          		       					$reqent = "select ctca from tca where codtca='".$cetca."'";
          		       					$resent = $bd->execRequete($reqent);
          		       					$lig2=$bd->ligTabSuivant($resent);
          		       					$kent2=$lig2[0];
        		        				echo "<CENTER><H3>"." retour requête21: ".$kent."+".$kent2. "</H3></CENTER>\n";
        		       					$reqent = "select count(*) from entite  where cregate='".$data[1]."' and libentite='".$data[2]."' and cedotc='".$kent."'and cetca='".$kent2."'";
         		       					$resent = $bd->execRequete($reqent);
          		       					$nbmsg = mysql_result($resent,0,0);
          								echo "<CENTER><H3>"." retour requ�te: ".$nbmsg."</H3></CENTER>\n";
          		          				if ($nbmsg == 1)
          			    					{
          										$nc=$nc+1;
          			    					}
          			     					else
          										{
          											$reqent= "select count(*) from entite where cregate<>'".$data[1]."' and libentite='".$data[2]."' and cedotc<>'".$kent."'and cetca<>'".$kent2."'";
          											$resent = $bd->execRequete($reqent);
          											$nbmsg2 = mysql_result($resent,0,0);
          											echo "<CENTER><H3>"."msg2:".$nbmsg2."</H3></CENTER>\n";
          											if ($nbmsg2 == 1)
          					  							{
          													$reqent = "update entite set cedotc='".$kent."' ,cetca='".$kent2."',cregate='".$data[1]."' ,datdebent= '".$datdebent."' , datfinent=".$datfinent." where libentite='".$data[2]."'";
          													$resent= $bd->execRequete ($reqent);
          													$upt=$upt+1;
        					  							}
							          					else
          					   								{
          														$reqent = "select count(*) from entite where  cregate<>'".$data[1]."'and libentite='".$data[2]."' and cedotc='".$kent."'and cetca='".$kent2."'";
          														$resent = $bd->execRequete($reqent);
          														$nbmsg3 = mysql_result($resent,0,0);
          						 								echo "<CENTER><H3>"."msg3:".$nbmsg3."</H3></CENTER>\n";
          														if($nbmsg3==1)
          						    								{
          																$reqent = "update entite set cregate='".$data[1]."',datdebent= '".$datdebent."',datfinent='".$datfinent." where libentite ='".$data[2]."'";
          																$resent=  $bd->execRequete ($reqent);
          																$upt=$upt+1;
          						     								}
          															else
          						   										{
          																	$reqent = "select count(*) from entite where  cregate='".$data[1]."' and cedotc='".$kent."'and cetca<>'".$kent2."'";
          																	$resent = $bd->execRequete($reqent);
          																	$nbmsg5 = mysql_result($resent,0,0);
          																	echo "<CENTER><H3>"."msg5:".$nbmsg5."</H3></CENTER>\n";
          																	if($nbmsg5==1)
          							   											{
          																			$reqent = "update entite set cetca='".$kent2."',datdebent= '".$datdebent."',datfinent='".$datfinent." where cregate ='".$data[1]."'";
          																			$resent=  $bd->execRequete ($reqent);
          																			$upt=$upt+1;
          							  											}
          																		else
          							 												{
          							   													$reqent = "select count(*) from entite where  cregate='".$data[1]."' and cedotc<>'".$kent."'and cetca='".$kent2."'";
          							   													$resent = $bd->execRequete($reqent);
          							   													$nbmsg4 = mysql_result($resent,0,0);
          							    												echo "<CENTER><H3>"."msg4:".$nbmsg4."</H3></CENTER>\n";
          							   													if($nbmsg4==1)
          							     													{
          																						$reqent = "update entite set cedotc='".$kent."',datdebent= '".$datdebent."',datfinent='".$datfinent." where cregate ='".$data[1]."'";
          																						$resent=  $bd->execRequete ($reqent);
          																						$upt=$upt+1;
          							     													}
          							    													else
          							       														{
          								 															echo "<CENTER><H3>"." retour requ�te22: ".$kent."</H3></CENTER>\n";
          																							$reqent="insert into entite (cregate,libentite,cetca,cedotc,datdebent,datfinent) values ('".$data[1]."','".$data[2]."','".$kent2. "','".$kent. "','".$datdebent."','".$datfinent."')";
          																							$resent= $bd->execRequete ($reqent);
          																							$ins=$ins+1;
          							       														}
          						          											}
          					     	  								}
          					 								}
          										}
          						}
          						else
          							if ($nbmsg==0)
          								{
          									$ano=$ano+1;
          									$info = $data[0].';entite absente de scribe ;'.date("j/m/Y");
          									fwrite ($fp10, $info);
          									fwrite ($fp10, "\n");
          								}
          								else
          									{
          										$nc=$nc+1;
          									}
          		    	}
          		     	$row++;
          		}
      	     	$tot=$row-2;
        		$info = $ano.';lignes en anomalie : detail ci-dessous ;'.date("j/m/Y");
        		fwrite ($fp10, $info);
        		fwrite ($fp10, "\n");
        		$info = $ins.';lignes insérées ;'.date("j/m/Y");
        		fwrite ($fp10, $info);
        		fwrite ($fp10, "\n");
        		$info = $upt.';lignes mises à jours ;'.date("j/m/Y");
        		fwrite ($fp10, $info);
        		fwrite ($fp10, "\n");
        		$info = $nc.';lignes non concernées ;'.date("j/m/Y");
        		fwrite ($fp10, $info);
        		fwrite ($fp10, "\n");
        		$info = $tot.';lignes à traiter ;'.date("j/m/Y");
        		fwrite ($fp10, $info);
        		fwrite ($fp10, "\n");
        		fclose ($fp10);
  		 		$reqent="select centite from entite where cetca='0'and cedotc='0'";
  		 		$resent = $bd->execRequete($reqent);
  		 		$nblig=$bd->nbLigne($resent);
  		 		for($j=0; $j<$nblig; $j++)
  		   			{
  						$lig=$bd->ligTabSuivant($resent);
  						$progdel="delete from entite where centite='".$lig[0]."'";
  						$resprogdel = $bd->execRequete($progdel);
  		   			}
      	break;
        case 11:
            $ltyp="trafic SYCI";
        	$fp11 = fopen ('syci.log','a');     //fichier log
            while (!feof($fp))
                {
                    $data = fgetcsv ($fp, 1000,';');
		     		$num = count ($data);
		     		if ($row==3 and $data[0]<>"")
		        		{
                           $trimsy=substr($data[0],14,1);
                           $ansy=substr($data[0],16,4);
                        }
		     			if ($row>5 and !feof($fp)and ($data[0]<>99 and $data[0]<>100))
                     		{
                       		if ($data[1]<>"")
                       			{
									$data[0]=strtoupper($data[0]);      //rub42
									$lch=strlen($data[0]);
									$data[1]=strtoupper($data[1]);      //DOTC distribution
									$data[1]=strtr($data[1],"'"," ");
              				        $lch=strlen($data[1]);
									$data[2]=strtoupper($data[2]);      //trafic total
									$lch=strlen($data[2]);
             			            $type1=getType($data[2]);
									$data[4]=strtoupper($data[4]);      //trafic colis
                       				$lch=strlen($data[4]);
                        			$type2=getType($data[4]);
                        			$data[5]=strtoupper($data[5]);      //trafic colis
                        			$lch=strlen($data[5]);
                        			$type2=getType($data[5]);
                         			echo "<CENTER><H3>"." type: ".$type1."et".$type2."</H3></CENTER>\n";
                            		$ksy="";
                            		$reqsy = "select cdotc from dotc where ville='".$data[1]."'";
                            		$ressy = $bd->execRequete($reqsy);
                            		$nblig=$bd->nbLigne($ressy);
                            		if ($nblig==1 )
                            			{
                            				$lig2=$bd->ligTabSuivant($ressy);
                            				$ksy=$lig2[0];
                            			}
                            			else
                            				{
                             					$reqsy = "select cdotc from dotc where libdotc='".$data[1]."'";
                             					$ressy = $bd->execRequete($reqsy);
                             					$lig2=$bd->ligTabSuivant($ressy);
                             					$ksy=$lig2[0];
                            				}
                             				echo "<CENTER><H3>"." retour requête21: ".$ksy."</H3></CENTER>\n";
                            				$reqsy="insert into syci (cedotc,trafsyci,trafcolsyc,ansyc,trimsyc) values ('".$ksy."','".$data[2]."','".$data[4]. "','".$ansy."','".$trimsy."')";
                            				$ressy= $bd->execRequete ($reqsy);
                            				$ins=$ins+1;
		       					}
		   		     			if ($nbmsg==0)
		     						{
		     							$ano=$ano+1;
		     							$info = $data[0].';Syci absente de scribe ;'.date("j/m/Y");
		     							fwrite ($fp11, $info);
		     							fwrite ($fp11, "\n");
		     						}
		     						else
		     							{
		     								$nc=$nc+1;
		     							}
		     			}
                        $row++;
               }
             	$tot=$row-2 ;
        	$info = $ano.';lignes en anomalie : detail ci-dessous ;'.date("j/m/Y");
        	fwrite ($fp11, $info);
        	fwrite ($fp11, "\n");
        	$info = $ins.';lignes insérées ;'.date("j/m/Y");
        	fwrite ($fp11, $info);
        	fwrite ($fp11, "\n");
        	$info = $upt.';lignes mises à jours ;'.date("j/m/Y");
        	fwrite ($fp11, $info);
        	fwrite ($fp11, "\n");
        	$info = $nc.';lignes non concernées ;'.date("j/m/Y");
        	fwrite ($fp11, $info);
        	fwrite ($fp11, "\n");
        	$info = $tot.';lignes à traiter ;'.date("j/m/Y");
        	fwrite ($fp11, $info);
        	fwrite ($fp11, "\n");
        	fclose ($fp11);
          break;
        case 12:
              $ltyp="trafic pildi";
              $fp12 = fopen ('pildi.log','a');     //fichier log
              set_time_limit(240);
              while (!feof($fp))
                {
                    $data = fgetcsv ($fp, 1000,';');
		     		$num = count ($data);
		     		if ($row==1)
                       {
                        	$typi="";
                        	$typi=strtoupper($data[4]);
                        	$typi=substr($typi,7,8);
                         	if ($typi=="CONCENTR")
                           		{
                            		$typil=2;
                           		}
                            	else $typil=1;
                       	}
		     			if($data[3]<>"" and $row>=2)
							{
                            	$kpi="";
                            	$reqpi = "select centite from entite where cregate='".$data[3]."'";
                            	$respi = $bd->execRequete($reqpi);
                            	$lig=$bd->ligTabSuivant($respi);
                            	$kpi=$lig[0];
                             	echo "<CENTER><H3>"." retour requête: ".$kpi." et ".$typi."et".$an." , ".$mois."</H3></CENTER>\n";
                        	}
		      				if ($row>=2 and !feof($fp)and $typil==1 and $kpi<>0)
                       		{
                        		$data[0]=strtoupper($data[0]);      //
								$lch=strlen($data[0]);
								$data[1]=strtoupper($data[1]);      //
                        		$lch=strlen($data[1]);
								$data[2]=strtoupper($data[2]);      //
								$lch=strlen($data[2]);
                        		$data[3]=strtoupper($data[3]);      //
								$lch=strlen($data[3]);
								$data[4]=strtoupper($data[4]);      //
                        		$lch=strlen($data[4]);
                        		$data[5] =strtoupper($data[5]);     //
								$lch=strlen($data[5]);
                        		$data[6] =strtoupper($data[6]);     //
								$lch=strlen($data[6]);
								$moipi=0;
								$anpi=0;
                            	$reqpi="insert into pildi (ceentite,trafdistribm,trafdistribc,anpi,moipi) values ('".$kpi."','".$data[5]."','".$data[4]."','".$an."','".$mois."')";
                            	$respi= $bd->execRequete ($reqpi);
                            	$ins=$ins+1;
		       				}
		       				else
		       					{
                         			$reg=substr($data[3],0,2);
                         			echo "<CENTER><H3>"." REG: ".$reg." </H3></CENTER>\n";
                            		if ($row>=2 and !feof($fp)and $typil==1 and $kpi==0 and ($reg!='20' and $reg!='97' ))
                           				{
                             				$ano=$ano+1;
  			      							$info = $data[3].";".$data[0].';entité absente de scribe;pildi dist;'.date("j/m/Y");
	                      					fwrite ($fp12, $info);
                              				fwrite ($fp12, "\n");
                           				}
                           				else
                           					{
                            				 if ($row>=2 and !feof($fp)and $typil==2 and $kpi<>0)
                               					{
                               						$reqpi = "update pildi set trafconc='".$data[4]."'where ceentite='".$kpi."'";
        	               							$respi= $bd->execRequete ($reqpi);
      			       								$upt=$upt+1;
                               					}
                               					else
                                 					{
                                   						$reg2=substr($data[3],0,2);
                                   						echo "<CENTER><H3>"." REG2: ".$reg2." </H3></CENTER>\n";
                                    					if ($row>=2 and !feof($fp)and $typil==2 and $kpi==0 and ($reg2!='20' and $reg2!='97' ))
                                    						{
                                    							$ano=$ano+1;
  			      	    										$info = $data[3].";".$data[1].';entite absente de scribe;pildi conc;'.date("j/m/Y");
	                            								fwrite ($fp12, $info);
                               	    							fwrite ($fp12, "\n");
                               	    						}
                               	     						else
                                        						{
			                 										$nc=$nc+1;
                                          							$info = $data[3].';NC;'.date("j/m/Y");
	                            									fwrite ($fp12, $info);
                               	    								fwrite ($fp12, "\n");
			                									}
                                 					}
                           					}
                      			}
                      			$row++;
               	}
           	$tot=$row-2 ;
        	$info = $ano.';lignes en anomalie : detail ci-dessous ;'.date("j/m/Y");
        	fwrite ($fp12, $info);
        	fwrite ($fp12, "\n");
        	$info = $ins.';lignes insérées ;'.date("j/m/Y");
        	fwrite ($fp12, $info);
        	fwrite ($fp12, "\n");
        	$info = $upt.';lignes mises à jours ;'.date("j/m/Y");
        	fwrite ($fp12, $info);
        	fwrite ($fp12, "\n");
        	$info = $nc.';lignes non concernées ;'.date("j/m/Y");
        	fwrite ($fp12, $info);
        	fwrite ($fp12, "\n");
        	$info = $tot.';lignes à traiter ;'.date("j/m/Y");
        	fwrite ($fp12, $info);
        	fwrite ($fp12, "\n");
        	fclose ($fp12);
              break;
        case 13:
           	$ltyp="trafic SYSPEO";
           	$fp13 = fopen ('syspeo.log','a');     //fichier log
           	set_time_limit(130);
        	while (!feof($fp))
                {
          			$data = fgetcsv ($fp, 1000,';');
		     		$num = count ($data);
                	if($data[1]<>"" and $row==0)
                	{
                		$moipeo=substr($data[1],4,2);
		     			$anpeo=substr($data[1],0,4);
                	}
                	echo "<CENTER><H3>"." MOIS: ".$moipeo." ANNEE: ".$anpeo."</H3></CENTER>\n";
		     		if ($row==1)
                       {
                        	$typeo="";
                        	$typeo=strtr($data[3],"�","e");
                        	$typeo=strtoupper(trim($typeo));
                         	if ($typeo=="PREPA")
                           		{
                            		$typsysp=2;
                           		}
                            	else $typsysp=1;
                       }
		     			if($data[1]<>""and $row>3)
							{
                            	$kpeo="";
                            	$reqpeo = "select centite from entite where cregate='".$data[1]."'";
                            	$respeo = $bd->execRequete($reqpeo);
                            	$lig=$bd->ligTabSuivant($respeo);
                            	$kpeo=$lig[0];
                             	echo "<CENTER><H3>"." retour requête: ".$kpeo." et ".$typeo."</H3></CENTER>\n";
                        	}
		       			if ($row>=2 and !feof($fp)and $typsysp==1 and $kpeo<>0)
                       		{
                        		$data[0]=strtoupper($data[0]);      //
								$lch=strlen($data[0]);
								$data[1]=strtoupper($data[1]);      //
                        		$lch=strlen($data[1]);
								$data[2]=strtoupper($data[2]);      //
								$lch=strlen($data[2]);
                        		$data[3]=strtoupper($data[3]);      //
								$lch=strlen($data[3]);
								$data[4]=strtoupper($data[4]);      //
                        		$lch=strlen($data[4]);
                        		$data[5] =strtoupper($data[5]);     //
								$lch=strlen($data[5]);
                        		$data[6] =strtoupper($data[6]);     //
								$lch=strlen($data[6]);
                        		$reqpeo="insert into syspeo (ceentite,traftraitd,traftraita,moipeo,anpeo) values ('".$kpeo."','".$data[4]."','".$data[6]."','".$moipeo."','".$anpeo."')";
                        		$respeo= $bd->execRequete ($reqpeo);
                        		$ins=$ins+1;
		       				}
		       				else
		       					{
                            		if ($row>=3 and !feof($fp)and $typsysp==1 and $kpeo<>0)
                           				{
                             				$ano=$ano+1;
  			      							$info = $data[1].';entité absente de scribe;syspeo trait;'.date("j/m/Y");
	                     					fwrite ($fp13, $info);
                              				fwrite ($fp13, "\n");
                           				}
                           				else
                           					{
                            					if ($row>=3 and !feof($fp)and $typsysp==2 and $kpeo<>0)
                               						{
                               							$reqpeo = "update syspeo set trafconc='".$data[3]."'where ceentite='".$kpeo."'";
        	               								$respeo= $bd->execRequete ($reqpeo);
      			       									$upt=$upt+1;
                              						 }
                               						else
                                 						{
                                  							if ($row>=3 and !feof($fp)and $typsysp==2 and $kpeo==0)
                                    							{
                                    								$ano=$ano+1;
  			      	   												$info = $data[1].';entité absente de scribe;syspeo conc;'.date("j/m/Y");
	                            									fwrite ($fp13, $info);
                               	    								fwrite ($fp13, "\n");
                               	    							}
                               	     							else
                                        							{
			                 											$nc=$nc+1;
			                										}
                                 						}
                           					}
                      		}
                      		$row++;
               }
               	$tot=$row-2 ;
        		$info = $ano.';lignes en anomalie : detail ci-dessous ;'.date("j/m/Y");
        		fwrite ($fp13, $info);
        		fwrite ($fp13, "\n");
        		$info = $ins.';lignes insérées ;'.date("j/m/Y");
        		fwrite ($fp13, $info);
        		fwrite ($fp13, "\n");
        		$info = $upt.';lignes mises à jours ;'.date("j/m/Y");
        		fwrite ($fp13, $info);
        		fwrite ($fp13, "\n");
        		$info = $nc.';lignes non concernées ;'.date("j/m/Y");
        		fwrite ($fp13, $info);
        		fwrite ($fp13, "\n");
        		$info = $tot.';lignes à traiter ;'.date("j/m/Y");
        		fwrite ($fp13, $info);
        		fwrite ($fp13, "\n");
        		fclose ($fp13);
              break;
        case 14:
           	$ltyp="trafic SIROPNA";
           	$fp14 = fopen ('siropna.log','a');     //fichier log
        	set_time_limit(140);
            While (!feof($fp))
		    {
				$data = fgetcsv ($fp, 1000,';');
				$num = count ($data);
				if ($row>0 and !feof($fp))
			 		{
                          if($data[1]<>"" )
			   				{
                            	$kpna="";
                            	$reqpna = "select centite from entite where cregate='".$data[1]."'";
                            	$respna = $bd->execRequete($reqpna);
                            	$lig=$bd->ligTabSuivant($respna);
                            	$kpna=$lig[0];
                            	echo "<CENTER><H3>"." retour requête: ".$kpna."</H3></CENTER>\n";
                           }
                           if ($row>=1 and !feof($fp)and $kpna<>"")
                           	{
                				$data[1]=strtoupper($data[1]);
								$lch=strlen($data[1]);
								$data[2]=strtoupper($data[2]);
								$data[2]=strtr($data[2]," ","");
								$lch=strlen($data[2]);
								$moipna=0;
			    				$anpna=0;
                				$reqco="insert into pna (ceentite,trafpna,anpna,moipna) values ('".$kpna."','".$data[2]."','".$an."','".$mois."')";
                				$resco= $bd->execRequete ($reqco);
                				$ins=$ins+1;
                           }
                           else
                               {
                                	if ($row>=1 and !feof($fp) and $kpna=="")
                                    {
                                    	$ano=$ano+1;
  			      	    				$info = $data[1].';entité absente de scribe;syspna;'.date("j/m/Y");
	                             		fwrite ($fp14, $info);
                               	   		fwrite ($fp14, "\n");
                               	    }
                               	    else
                                       {
                                        	$nc=$nc+1;
                                       }
                               }
                          }
                          $row++;
            }
  		    $tot=$row-2 ;
        	$info = $ano.';lignes en anomalie : detail ci-dessous ;'.date("j/m/Y");
        	fwrite ($fp14, $info);
        	fwrite ($fp14, "\n");
        	$info = $ins.';lignes insérées ;'.date("j/m/Y");
        	fwrite ($fp14, $info);
        	fwrite ($fp14, "\n");
        	$info = $upt.';lignes mises à jours ;'.date("j/m/Y");
        	fwrite ($fp14, $info);
        	fwrite ($fp14, "\n");
        	$info = $nc.';lignes non concernées ;'.date("j/m/Y");
        	fwrite ($fp14, $info);
        	fwrite ($fp14, "\n");
        	$info = $tot.';lignes à traiter ;'.date("j/m/Y");
        	fwrite ($fp14, $info);
        	fwrite ($fp14, "\n");
        	fclose ($fp14);
             break;
        case 15:
           $ltyp="trafic colis";
           $fp15 = fopen ('colis.log','a');     //fichier log
           set_time_limit(200);
           While (!feof($fp))
			{
				$data = fgetcsv ($fp, 1000,';');
				$num = count ($data);
				if ($row>1 and !feof($fp))
			 		{
                          if($data[1]<>"" )
			   				{
                            	$kco="";
                            	$reqco = "select centite from entite where cregate='".$data[1]."'";
                            	$resco = $bd->execRequete($reqco);
                            	$lig2=$bd->ligTabSuivant($resco);
                            	$kco=$lig2[0];
                            	echo "<CENTER><H3>"." retour requête: ".$kco."</H3></CENTER>\n";
                           }
                           if ($kco<>0)
                           	{
                                $data[1]=strtoupper($data[1]);
								$lch=strlen($data[1]);
								$data[2]=strtoupper($data[2]);
								$lch=strlen($data[2]);
								$moico=0;
			        			$anco=0;
                                $reqco="insert into colis (ceentite,trafcolis,ancol,moicol) values ('".$kco."','".$data[2]."','".$an."','".$mois."')";
                                $resco= $bd->execRequete ($reqco);
                                $ins=$ins+1;
                           }
                          else
                             {
                               if ($kco==0)
                                    {
                                     	$ano=$ano+1;
  			      	     				$info = $data[2].';entité absente de scribe;colis;'.date("j/m/Y");
	                            		fwrite ($fp4, $info);
                               	     	fwrite ($fp4, "\n");
                               	    }
                               	    else
                                       {
                                           	$nc=$nc+1;
                                       }
                             }
                 	}
                     $row++;
            }
     		$tot=$row-2 ;
        	$info = $ano.';lignes en anomalie : detail ci-dessous ;'.date("j/m/Y");
        	fwrite ($fp15, $info);
        	fwrite ($fp15, "\n");
        	$info = $ins.';lignes insérées ;'.date("j/m/Y");
        	fwrite ($fp15, $info);
        	fwrite ($fp15, "\n");
        	$info = $upt.';lignes mises à jours ;'.date("j/m/Y");
        	fwrite ($fp15, $info);
        	fwrite ($fp15, "\n");
        	$info = $nc.';lignes non concernées ;'.date("j/m/Y");
        	fwrite ($fp15, $info);
        	fwrite ($fp15, "\n");
        	$info = $tot.';lignes à traiter ;'.date("j/m/Y");
        	fwrite ($fp15, $info);
        	fwrite ($fp15, "\n");
        	fclose ($fp15);
              break;
        case 16:
         	$ltyp="Retraitement";
         	While (!feof($fp))
         	{
         		$data = fgetcsv ($fp, 1000,';');
         		if (!feof($fp) and $row>0)
         		{
         			$ssproc=$data[0];
         			$scaa=$data[1];
         			$caaretr=$data[2];
         			$procret=$data[3];
         			$prata=$data[4];
         			$natretr=$data[5];
         			$an=$data[6];

         			$reqret = "SELECT count(*) FROM regretrait WHERE ssproc='".$ssproc."'and procret='".$procret."'";
         			$resret = $bd->execRequete($reqret);
         			$nbmsg2=mysql_result($resret,0,0);
         			if ($nbmsg2==0)
         			{
         				$reqins="insert into regretrait (ssproc,scaa,caaretr,procret,prata,natretr,annee) values ('".$ssproc."','".$scaa."','".$caaretr. "','".$procret."','".$prata."','".$natretr."','".$an."')";
         				$resins= $bd->execRequete ($reqins);
         				$ins=$ins+1;
         			}
         			else
         			{
         			$reqret = "SELECT count(*) FROM regretrait WHERE annee='".$an."'and procret='".$procret."' and ssproc='".$ssproc."'";
         			$resret = $bd->execRequete($reqret);
         			$nbmsg2=mysql_result($resret,0,0);
         			if ($nbmsg2==0)
         			{

         			}
         			else
         			{
        			$reqret = "SELECT count(*) FROM regretrait WHERE annee<>'".$an."'and procret='".$procret."' and ssproc='".$ssproc."'";
         			$resret = $bd->execRequete($reqret);
         			$nbmsg2=mysql_result($resret,0,0);
         				if ($nbmsg2>0)
         				{
         					$reqret="update regretrait set scaa='".$scaa."',annee='".$an."',caaretr='".$caaretr."',procret='".$caaretr."',prata='".$prata."',natretr='".$natretr."'";
         					$resret= $bd->execRequete ($reqret);
         					$upt=$upt+1;
         				}
         			}

         		}
         		}
         		$row++;
	$tot=$row+1 ;
         }


         /*
         	$reqret = "select  annee from regretrait where annee <> '".$an."'";
         	$resret = $bd->execRequete ($reqret);
         	$nbmsg=mysql_result($resret,0,0);
         	$reqret="update regretrait set ssproc='".$ssproc."',scaa='".$scaa."'";
         	$resret= $bd->execRequete ($reqret);
         	$upt=$upt+1;
         	*/


       	break;
        default:
                      echo "<CENTER><H3>type de fichier non determiné</H3></CENTER>\n";
    }
     fclose ($fp);
	$Form->debuttable();
	$Form->ajoutTexte (tbldebut(1,"100%"));
	$Form->ajoutTexte (tbldebutligne());
	$Form->ajoutTexte (tblentete("Fichier traité : "));
	$Form->ajoutTexte (tblentete("Type de fichier : "));
	$Form->ajoutTexte (tblentete("Taille du fichier (en o) : "));
	$Form->ajoutTexte (tblentete("Nombre de lignes : "));
	$Form->ajoutTexte (tblentete("Lignes insérées : "));
	$Form->ajoutTexte (tblentete("Lignes mises à jour : "));
	$Form->ajoutTexte (tblentete("Lignes en erreur : "));
    $Form->ajoutTexte (tblentete("Non Communiqué : "));
	$Form->ajoutTexte (tblfinligne());
	$Form->fintable();
	$Form->debuttable(tbldebutligne("A1"));
	$Form->debuttable(tblcellule($fich));
	$Form->debuttable(tblcellule($ltyp));
	$Form->debuttable(tblcellule($taille));
	$Form->debuttable(tblcellule($tot));
	$Form->debuttable(tblcellule($ins));
	$Form->debuttable(tblcellule($upt));
	$Form->debuttable(tblcellule($ano));
    $Form->debuttable(tblcellule($nc));
	$Form->debuttable(tblfinligne());
	$Form->fintable();
	$fp1 = fopen ('scribe.log','a');     //fichier log
	$info = $fich.';'.$ltyp.';'.$taille.';'.$tot.';'.$ins.';'.$upt.';'.$ano.';'.$nc;
	fwrite ($fp1, $info);
	fwrite ($fp1, "\n");
	fclose ($fp1);
	echo $Form->formulaireHTML();
    }
function formimport2($fichn,$fichs,$tfich,$niv)
    {
	//require("pconnect.php");
	// Cr�ation du formulaire
	//$bd = new monsqli(SERVEUR,NOM,PASSE,BASE);
	$listfichier=array(1=>"charge CAA",2=>"charge nature",3=>"CAA",4=>"TCA",5=>"sous processus",6=>"processus",7=>"domaine",8=>"nature");
	$listfichier2=array(9=>"dotc",10=>"entité",11=>"trafic SYCI",12=>"trafic PILDI",13=>"trafic SYSPEO",14=>"trafic SIROPNA",15=>"trafic colis",16=>"Retraitement");
	$Form = new formulaire ("POST","","gesimp");
	$fic="";
	$row = 0;
	echo "<CENTER><H3>".'Import de données'."</H3></CENTER>\n";
	echo "\n";
	echo "<CENTER><H3>".'formimport2'."</H3></CENTER>\n";
	echo "<CENTER><H3> niveau  : ".$niv."</H3></CENTER>\n";
	tblcellule(image('interface/redbar.jpg',"100%",1));
	$Form->debuttable();
	$Form->champRadio ( "Fichier","typfichier",1,$listfichier);
	$Form->champRadio ( "Fichier","typfichier",1,$listfichier2);
	$Form->champFichier("fichier à importer : ","impfich",35);
	$Form->fintable();
	$Form->debuttable();
	$Form->champTexte ("Mois","permois","",15);
	$Form->fintable();
	$Form->debuttable();
	$Form->champTexte ("Année","peran","",4);
	$Form->fintable();
	$Form->debuttable();
	$Form->champvalider ("Lancer l'import", "valider");
	$Form->fintable();
	//$Form->fin();
	echo $Form->formulaireHTML();
    }
?>