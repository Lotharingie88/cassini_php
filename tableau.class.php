<?php
   // Une classe de gestion des tableau
  class Tableau
    {
        /***********************PARTIE PRIVEE*****************/
        //constantes, variables
        private $nb_dimensions;
        //tableau des valeurs a afficher
        private $tabVal;
        //les entetes
        private $entete, $optLig, $optCol ;
        //options de presentation
        private $optTab, $couleurpair, $couleurimpair,$csg, $affichentete, $repetLign=array(),$optDim=array(),$legend;
        //const pour cellules vide
        const VAL_DEF = "&nbsp";
        
        //constructeur
        function Tableau ($nb_dimensions=2, $tabAttr=array())
            {
            //init var privées
            $this->tabVal=array();
            $this->optTab=$this->couleurpair=$this->couleurimpair=$this->legend="";
            //init dimension
            $this->nb_dimensions=$nb_dimensions;
            
            //initial des entete pour chaq dim
            for ($dim=1;$dim<=$this->nb_dimensions;$dim++)
            {
                $this->entete[$dim]=array();
                $this->affichentete[$dim]=TRUE;
            }
            //balise table
            $this->ajAttrTab($tabAttr);
           
            
            }
         //methode ajout attribut html 
        function ajAttrTab($tabAttr=array()) 
            {
            foreach ($tabAttr as $nomAttr=>$valAttr)
              $this->optTab.="$nomAttr='$valAttr'";
            }
        //methode pour couleur ligne
        function setLeg($text)
            {
            $this->legend=$text;
            }
            
        function setCoulPair($couleur)
            {
            $this->couleurpair=$couleur;
            }
        
        function setCoulImp($couleur)
            {
            $this->couleurimpair=$couleur;
            }
         //methode d affiche ou non entete
         function setAffEntet ($dim, $bool)
            {
             $this->affichentete[$dim]=$bool;
            }
         //methode repetition n x ligne
         function setRepetLig($dim,$cle,$nbRepet)
            {
             $this->repetLign[$dim][$cle]=$nbRepet;
            }
         //methode pouroptions ligne ou colonne
         function setOpt($dim,$cle,$opts=array())
            {
             foreach ($opts as $opt => $val )
                 $this->opts[$dim][$cle][$opt]=$val;
            }
         //methode affic coin sup gauche
         function setCoiSupG($texte)
            {
             $this->csg=$texte;
            }
            
          //methode d attribu pour entete ligne et colonne (a completer)
         function ajAttribEntet ($opts){}
         function ajAttribLig ($cleLig,$opts){}
         function ajAttribCol ($cleCol,$opts){}
         
         //Tableau a 2 dimension ajout de val ds cellules
         function ajVal($cleLig,$cleCol,$val)
            {
             //les entetes
            if (!array_key_exists($cleLig, $this->entete[1]))
                $this->entete[1][$cleLig]=$cleLig;
            if (!array_key_exists($cleCol, $this->entete[2]))
                $this->entete[2][$cleCol]=$cleCol;
             
             //stock valeur
            $this->tabVal[$cleLig][$cleCol]=$val; 
             
            }
          //Tableau à n dim
          
         function ajValN ($pos,$val)
            {
              $coord="";
              for ($dim=1;$dim<=$this->nb_dimensions;$dim++)
              {
                  $cle=$pos[$dim];
                  //par def entetes valent clé
                  if (!array_key_exists($cle, $this->entete[$dim]))
                      $this->entete[$dim][$cle]=$cle;
                  $coord.="['$cle']";
              }
              //on construit et execute
              eval ("\$this->tabVal$coord='$val';");
            }
            //methode entete avec text
         function ajEnt($dim,$cle,$text)
            {
            //stocke chaine d entete
            $this->entete[$dim][$cle]=$text;
            }
            
            //production tableau HTML ne marche qu en dim 2
         function tabHTML()
            {
              $chain=$lig="";
              /*pour afficher entete
              print_r ($this->entete[1]);
              print_r ($this->entete[2]); */
              
              //affichage du coin superieur gauche ?
              if ($this->affichentete[1]) $lig="<TH>$this->csg</TH>";
              
              if (!empty($this->legend))
                {
                  $nbCols=count($this->entete[2]);
                  $chain="<TR CLASS='header'>\n<TH colspan=$nbCols>$this->legend"
                      ."</TH>\n</TR>\n";
                  
                }
               //creation des entet de col en dim 2
               if ($this->affichentete[2])
                {
                    foreach ($this->entete[2] as $cle => $text)
                        $lig.="<TH>$text</TH>\n";
                    //ligne entete
                    $chain="<TR class='header'>$lig</TR>\n";
                }
              $i=0;
              //boucle imbriqu sur les 2 tab de cles
              foreach ($this->entete[1] as $cleLig=>$entetLig)//lignes
              {
                 if ($this->affichentete[1])
                     $lig="<TH>$entetLig</TH>\n";
                 else 
                     $lig="";
                 $i++;
                 foreach ($this->entete[2] as $cleCol=>$entetCol)//colonnes
                 {
                   //on prend val si existe sinon defaut
                   if (isset($this->tabVal[$cleLig][$cleCol]))
                       $val=$this->tabVal[$cleLig][$cleCol];
                   else 
                       $val=self::VAL_DEF;
                   //on place la valeur ds cellule
                   $lig.="<TD>$val</TD>\n";
                 }
                 //prise en compte couleur si necessair
                 if ($i%2==0)
                 {
                     $optLig="class='even'";
                     if (!empty($this->couleurpair))
                         $optLig="bgcolor='$this->couleurpair'";
                 }
                     elseif ($i%2==1)
                     {
                         $optLig="class='odd'";
                         if (!empty($this->couleurimpair))
                             $optLig="bgcolor='$this->couleurimpair'";
                     }
                 else $optLig="";
                 //option?
                 if (isset($this->options[1][$cleLig]))
                     foreach ($this->options[1][$cleLig] as $option => $val)
                         $optLig.="$option='$val'";
                     $lig="<TR $optLig>\n$lig\n</TR>\n";
                  //prise en compte repetition ligne
                  if (isset($this->repetLign[1][$cleLig]))
                  {
                      $rlig="";
                      for ($i=0; $i<$this->repetLign[1][$cleLig];$i++)
                          $rlig.=$lig;
                      $lig=$rlig;
                  }
                  //on ajoute lign a chain
                  $chain.=$lig;
              }
              //balise table
              return "<TABLE $this->optTab>\n$chain</TABLE>\n";
              
            }
        /***********************PARTIE PUBLIQUE***************/
    }
?>