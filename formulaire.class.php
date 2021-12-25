<?php
  // Classe gérant les formulaires
    require_once ("tableau.class.php");    

  class Formulaire
  {
    // ----   Partie privée : les propriétés
     const VERTICAL = 1;
     const HORIZONTAL = 2;
     //propriétés de la balise FORM
     private $methode, $script, $nom, $transfertFichier=FALSE;

     //propriétés de présentation
     private $orientation="", $centre=TRUE, $classeCSS, $tableau ;
     
     //propriétés stockant les composants
     private $composants=array(), $nbComposants=0 ;
	
    //var $modeTable = FALSE;                                       
    //var $entetes=array();
    //var $nbLignes=0;


    //var $contenuForm  = "";

       

         // Constructeur de la classe
    function __construct ($methode,$script,$centre=true,$classe="Form",$nom="Form")
        {
      $this->methode = $methode;
      $this->script = $script;
      $this->classeCSS = $classe;
      $this->nom = $nom;
      $this->centre = $centre;
        }
        
 /* **********  PARTIE PRIVEE : les méthodes**************** */
        // Méthode pour créer un champ INPUT général
    private function champINPUT ($Type, $Nom, $Val, $Taille, $TailleMax,$action="",$ecr="")
        {
        //attention aux problemes d affichage
        $Val=htmlSpecialChars($Val) ;
        // Création de la balise
     $s = "<INPUT TYPE='$Type' NAME=\"$Nom\" "
         . "VALUE=\"$Val\" SIZE='$Taille' MAXLENGTH='$TailleMax'";
	   if ($action!="")
	 {
	  $s.=$action;
	   }
	   if ($ecr=="R")
	 {

	  $s=$s.' READONLY';
	   }
		else
		if ($ecr=="D")
		{
		    $s.=' DISABLED';
		}

	   $s.=">\n";
        // Renvoi de la chaîne de caractères
         return $s;
        }
        
        
        // Champ de type texte
    private function champTEXTAREA ($Nom, $Val, $Lig, $Col,$action="",$ecr="")
        {
      $s = "<TEXTAREA NAME=\"$Nom\" ROWS='$Lig' "
             . "COLS='$Col'\n";

	   if ($action!="")
	 	{
	  		$s.=$action;
		}
	   if ($ecr=="R")
	 	{

	  		$s.=' READONLY';
		}
			else
			if ($ecr=="D")
			{
		    	$s=$s.' DISABLED';
			}
	   $s.=">$Val</TEXTAREA>\n";
	   return $s;
	   }

        // Champ pour sélectionner dans une liste
    private function  champSELECT ($Nom, $Liste, $Defaut, $Taille=1,$action="",$ecr="")
        {
            $s= "<SELECT NAME=\"$Nom\" SIZE='$Taille'>\n";
            
      if ($action!="")
	  {
	      $s.= "$action>\n";
	  }
	  else 
        {
            $s.= ">\n";
        }
      foreach ($Liste as $Val => $libelle )
      //while (list ($val, $libelle) = each ($pListe))
      {
        if ($Val != $Defaut)
	  $s .=  "<OPTION VALUE=\"$Val\">$libelle</OPTION>\n";
        else
	  $s .= "<OPTION VALUE=\"$Val\" SELECTED='1'>$libelle</OPTION>\n";
      }

      return $s . "</SELECT>\n";
        }

        // Champ CHECKBOX ou RADIO
    private function  champBUTTONS ($pType, $pNom, $pListe, $pDefaut,$params,$action="",$ecr="")
      {
        if ($pType == "checkbox") $length = $params["LENGTH"];
         // Toujours afficher dans une table
        $libelles=$champs="";
        $nbChoix=0;
        $s = "<TABLE BORDER='0' CELLSPACING='5' CELLPADDING='2'><TR>\n";
        foreach ($pListe as $Val => $libelle )
            //while (list ($val, $libelle) = each ($pListe))
            {
                $libelles .= "<TD><B>$libelle</B></TD>";
                $checked=" ";
                if (!is_array($pDefaut))
                    {
                        if ($Val == $pDefaut) $checked = "CHECKED='1'";
                    }
                    else 
                    {
                        if (isset($pDefaut[$Val])) $checked = "CHECKED='1'";
                    }
		         if ($ecr=="R")
	 		        $lect=' READONLY';
		            else
			        if ($ecr=="D")

		    	         $lect=' DISABLED';
			             else
				        $lect=" ";
                $champs .= "<TD><INPUT TYPE='$pType' NAME=\"$pNom\" VALUE=\"$Val\" "
                  . " $checked $lect> </TD>\n";
                $nbChoix++;
            //eventuellement plusieurs lignes dans la table
            if ($pType == "CHECKBOX" and $length == $nbChoix)
                {
                    $s .= "<TR>".$libelles ."</TR><TR>".$champs."</TR>\n";
                    $libelles = $champs = "";
                    $nbChoix = 0;
                }
       
            }
            if (!empty($champs)) 
                return $s .$libelles . "</TR>\n<TR>".$champs."</TR></TABLE>";
                //return  "<TABLE BORDER='0' CELLSPACING='5' CELLPADDING='2'><TR>\n"
                //. $libelles .  "</TR>\n<TR>" . $champs . "</TR></TABLE>";
                else 
                return $s."</TABLE>";
         }

         // Champ de formulaire
    private function champForm ($Type, $Nom, $Val, $params, $Liste=array(),$action="",$ecr="")
        {
      switch ($Type)
      {
        case "TEXT": case "PASSWORD": case "SUBMIT":
        case "RESET": case "FILE": case "HIDDEN" :
            if (isSet($params["SIZE"]))
              $taille = $params["SIZE"];
              else $taille = 0;
              if(isSet($params["MAXLENGTH"]) and $params["MAXLENGTH"] !=0 )
              $tailleMax = $params["MAXLENGTH"];
              else
              $tailleMax = $taille;
              
              // Appel de la méthode champINPUT de l'objet courant
              $champ = $this->champINPUT ($Type, $Nom, $Val, $taille, $tailleMax,$action,$ecr);
              // Si c'est un transfert de fichier: s'en souvenir
              if ($Type == "FILE") $this->transfertFichier=TRUE;
              break;
              
		case "BUTTON":
			  $taille = $params["SIZE"];
              $tailleMax = $params["MAXLENGTH"];
             if ($tailleMax == 0) $tailleMax = $taille;
			  // Appel de la méthode champINPUT de l'objet courant
              $champ = $this->champINPUT ($Type, $Nom,
                                          $Val,$taille, $tailleMax,$action,$ecr);
			  break;
			  
        case "TEXTAREA":
              $lig = $params["ROWS"]; $col = $params["COLS"];
              // Appel de la méthode champTEXTAREA de l'objet courant
              $champ = $this->champTEXTAREA ($Nom, $Val, $lig, $col,$action,$ecr);
              break;

        case "SELECT":
              $taille = $params["SIZE"];
              // Appel de la méthode champSELECT de l'objet courant
              $champ = $this->champSelect ($Nom, $Liste, $Val, $taille,$action,$ecr);
              break;

        case "CHECKBOX": 
              // Appel de la méthode champBUTTONS de l'objet courant
              $champ = $this->champBUTTONS ($Type, $Nom, $Liste, $Val,$params,$action,$ecr);
              break;
              
        case "RADIO":
              // Appel de la méthode champBUTTONS de l'objet courant
              $champ = $this->champBUTTONS ($Type, $Nom, $Liste, $Val,array(),$action,$ecr);
              break; 
            
        default: echo "<B>ERREUR: $Type est un type inconnu</B>\n";
              break;
      }
      return $champ;
        }

        // Affichage d'un champ avec son libellé
    private function champLibelle ($Libelle, $Nom, $Val,  $Type,
                        $params=array(), $Liste=array(),$action="",$ecr="")
        {
      // Création du champ
      $champHTML = $this->champForm ($Type, $Nom, $Val, $params,
                                                $Liste,$action,$ecr);
      
      //on met le libellé en gras
      $Libelle="<B>$Libelle</B>";

      // Affichage du champ en tenant compte de la présentation
      //if ($this->modeTable)
      //{
       // if ($this->orientation == 'VERTICAL')
        //{
          // Nouvelle ligne, avec libellé et champ dans deux cellules
          // On l'ajoute dans le contenu
         // $this->contenuForm .= "<TR><TD><B>$Libelle</B></TD>"
	    // . "<TD>$champHTML</TD></TR>\n";
        //}
       // else

        //{
          // On ne peut pas afficher maintenant : on stocke dans les tableaux
         // $this->entetes[$this->nbChamps] = "<B>" . $Libelle . "</B>";
         // $this->champs[$this->nbChamps] = $champHTML;
         // $this->nbChamps++;
        //}
      //}
      //else
      //{
        // Affichage simple
        //$this->contenuForm .= "$Libelle  $champHTML";
      //}
      //stockage de libellé et balise
      $this->composants[$this->nbComposants]=array("type"=>"CHAMP","libelle"=>$Libelle,"champ"=>$champHTML);
      //renvoi identifiant ligne et incrémente
        return $this->nbComposants++;

    }

 /**************  PARTIE PUBLIQUE ****************/

        //methode recuperant id et champ
   public function getChamp ($idComposant)
    {
      //on recupère composant et champ 
      $composant = $this->composants[$idComposant] ;
      return $composant['champ'];
    }
   
   //creation de champ et libellé
   public function champTexte ($Libelle, $Nom, $Val, $Taille, $TailleMax=0,$action="",$ecr="")
        {
            return $this->champLibelle ($Libelle, $Nom, $Val,
                             "TEXT", array ("SIZE"=>$Taille,
                                            "MAXLENGTH"=>$TailleMax),"",$action,$ecr);
        }

   public function champMotDePasse ($pLibelle, $pNom, $pVal, $pTaille,
                                  $pTailleMax=0,$action="",$ecr="")
        {
            return $this->champLibelle ($pLibelle, $pNom, $pVal,
                             "PASSWORD", array ("SIZE"=>$pTaille,
                                            "MAXLENGTH"=>$pTailleMax));
        }

  	public function champRadio ($Libelle, $Nom, $Val, $Liste, $action="", $ecr="")
        {
            return $this->champLibelle ($Libelle, $Nom, $Val,
                               "RADIO", array (), $Liste,$action,$ecr);
        }
        
     public function champCheckbox ($pLibelle, $pNom, $pVal, $pListe, $length=-1, $action="", $ecr="")
        {
            return $this->champLibelle ($pLibelle, $pNom, $pVal,
                "CHECKBOX", array ("LENGTH"=>$length), $pListe, $action, $ecr);
        }

   public function champListe ($pLibelle, $pNom, $pVal, $pTaille, $pListe, $action="", $ecr="")
        {
            return $this->champLibelle ($pLibelle, $pNom, $pVal, "SELECT",
                           array("SIZE"=>$pTaille), $pListe, $action);
        }

   public function champFenetre ($Libelle, $Nom, $Val, $Lig, $Col, $action="", $ecr="")
        {
            return $this->champLibelle ($Libelle, $Nom, $Val, "TEXTAREA",
                array ("ROWS"=>$Lig,"COLS"=>$Col), "&nbsp", $action, $ecr);
        }

   public function champValider ($pLibelle, $pNom, $action="", $ecr="")
        {
            return $this->champLibelle ("&nbsp", $pNom, $pLibelle, "SUBMIT");
       }
   public function champAnnuler ($pLibelle, $pNom,$action="",$ecr="")
       {
           $this->champLibelle ("&nbsp", $pNom, $pLibelle, "RESET");
       }
       
   public function champBouton ($pLibelle, $pNom,$action="",$ecr="")
        {
            return $this->champLibelle ("&nbsp", $pNom, $pLibelle,"BUTTON");
        }
   public function champFichier ($pLibelle, $pNom, $pTaille,$action="",$ecr="")
        {
            $this->champLibelle ($pLibelle, $pNom, "", "FILE",
                            array ("SIZE"=>$pTaille));
        }

   public function champCache ($Nom, $Valeur,$action="",$ecr="")
        {
            return $this->champLibelle ("&nbsp", $Nom, $Valeur, "HIDDEN");
        }

   // Ajout d'un texte quelconque
   public function ajoutTexte ($texte)
        {
            //$this->contenuForm .= $texte;
            //on ajoute un element dans les composant
            $this->composants[$this->nbComposants] = array("type"=>"TEXTE","texte"=>$texte);
            //on renvoie identifiant et on incrémente
            return $this->nbComposants++;
        }

   // Début d'une table, mode horizontal ou vertical
   //public function debutTable ($orientation=Formulaire::VERTICAL, $nbLignes=1, $border=0)
   public function debutTable ($orientation=Formulaire::VERTICAL, $attrib=array(),$nbLignes=1)
        {
       // on declare un nouv tab
       $tab=new Tableau(2,$attrib);
       
       //pas d affich entete ligne
       $tab->setAffEntet(1, FALSE);
       //action selon orientation
       if ($orientation==Formulaire::HORIZONTAL)
       {
           $tab->setRepetLig(1, "lig", $nbLignes);
           
       }
           else //pas d aff entet colo
           $tab->setAffEntet(2, FALSE);
       //creation composa pour placer tableau
       $this->composants[$this->nbComposants]=array("type"=>"DEBUTTABLE","orientation"=>$orientation,"tableau"=>$tab);
        
       //renvoie ident ligne et incremente
       return $this->nbComposants++;
      // Pas de bordure
      //if ($orientation == 'VERTICAL')
           //$this->contenuForm .= "<TABLE BORDER='$border'>";
     // $this->modeTable = TRUE;
      //$this->orientation = $orientation;
     // $this->nbLignes = $nbLignes;
      //$this->nbChamps = 0;
        }

            // Fin d'une table
    public function finTable ()
    {
        //insert lign marq fin de table
        $this->composants[$this->nbComposants++]=array("type"=>"FINTABLE");
    }
        // fin du formulaire , avec affich si besoin
    public function formulaireHTML ()
        {
        //on met l attrib enctype si trans fichier
        if ($this->transfertFichier) $encTyp="ENCTYPE='multipart/form-data'"  ;
            else $encTyp="";
            
        $form="";    
        //on parcourt les composants et on créée le html
        foreach ($this->composants as $idComposant => $description)
        {
           //selon le type de la ligne
           switch ($description["type"])
           {
               case "CHAMP" :
                   //champ de formulaire
                   $libelle=$description['libelle'];
                   $champ=$description['champ'];
                   if ($this->orientation==Formulaire::VERTICAL)
                   {
                       $this->tableau->ajVal($idComposant,"libelle",$libelle);
                       $this->tableau->ajVal($idComposant,"champ",$champ);
                   }
                   elseif ($this->orientation==Formulaire::HORIZONTAL)
                   {
                       $this->tableau->ajEnt(2,$idComposant,$libelle);
                       $this->tableau->ajVal("ligne",$idComposant,$champ);
                   }
                    else 
                   $form.=$libelle.$champ;
                   break;
               case "TEXTE" :
                   // texte à inserer
                   $form.=$description['texte'];
                   break;
               case "DEBUTTABLE" :
                   //debut tableau html
                   $this->orientation=$description['orientation'];
                   $this->tableau=$description['tableau'];
                   break;
               case "FINTABLE" :
                   //fin tableau html
                   $form.=$this->tableau->tabHTML();
                   $this->orientation="";
                   break;
               default: //ne devrait pas arrivé
                   echo "<p>ERREUR CLASSE FORMULAIRE! <p>";              
                             
           }
            
        }
            
            
      //if ($this->modeTable == TRUE)
      //{
       //if ($this->orientation == 'HORIZONTAL')
       //{
        // Affichage des libelles
       // $this->contenuForm .= "<TABLE><TR>\n";
        // Les entêtes du tableau
        //for ($i=0; $i < $this->nbChamps; $i++)
          // $this->contenuForm .= "<TD>".$this->entetes[$i]."</TD>\n";
       // $this->contenuForm .= "</TR>\n";

        // Affichage des lignes et colonnes
       // for ($j=0; $j < $this->nbLignes; $j++)
        //{
          //$this->contenuForm .= "<TR>\n";
          //for ($i=0; $i < $this->nbChamps; $i++)
           //$this->contenuForm .= "<TD>".$this->champs[$i]."</TD>\n";
          //$this->contenuForm .= "</TR>\n";
       // }
       //}
       //$this->contenuForm .= "</TABLE>\n";
      //}
      //$this->modeTable = FALSE;
        

      //encadrement form par balise
      $form="\n<form method='$this->methode'".$encTyp."action='$this->script' name='$this->nom'>".$form."</form>";
      
      //centrer si necessaire
      if ($this->centre) $form="<center>$form</center>\n";;
      
      //on retoourne la chaine de caract contenant le formulaire
      return $form;
    }
        
        
        // Fin du formulaire, avec affichage éventuel
        
   // public function fin ($affiche = TRUE)
        //{
      // Fin de la table, au cas où on aurait oublié ...
     // $this->finTable();

      // On crée le formulaire final en assemblant (1)
      // la balise d'ouverture, (2) le contenu (3) la balise fermante

      // Balise ouvrante: penser à mettre un attribut ENCTYPE
      // si on transfère un fichier
      //if ($this->transfertFichier)
       // {
        //$encType = "ENCTYPE='multipart/form-data'";


      // Ouverture de la balise
      //$baliseO = "\n<FORM  METHOD='$this->methode' " . $encType
           //. "ACTION='$this->script' NAME='$this->nom'>\n";
        // }
        // else
         //{
         //$baliseO = "\n<FORM  METHOD='$this->methode' " 
         //  . "ACTION='$this->script' NAME='$this->nom'>\n";
        // }
     // $baliseF =  "</FORM>\n";

      //$formulaire = $baliseO . $this->contenuForm . $baliseF;

      // Il faut éventuellement centrer le formulaire
      //if ($this->centre)
       // $formulaire = "<CENTER>\n" . $formulaire . "</CENTER>\n";;

      // Eventuellement on affiche
     // if ($affiche) echo $formulaire;

      // Dans tous les cas on retourne
     // return $formulaire;
       // }

    // Fin de la classe
  }
?>
