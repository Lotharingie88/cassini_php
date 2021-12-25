<?php
  // Une classe de gestion des acc�s � une base de donn�e
  abstract class BD
  {
    // ----   Partie priv�e : les propri�t�s
 
    protected $sgbd,$connexion, $nom_base  ;
    protected $codErr,$messErr  ;
    //var $erreurRencontree=0;                          

    // Constructeur de la classe

    function __construct ($serveur,$login,$motDePasse,$base)
        {
	   $this->nom_base=$base;
       $this->codErr=0;
       $this->messErr="";
       $this->sgbd="Inconnu";
      // Connexion au serveur 
      $this->connexion=$this->connect ($serveur, $login, $motDePasse, $base);

      if (!$this->connexion) 
       //$this->message("D�sol�, connexion au serveur $serveur impossible\n");
         throw new monException ("D�sol�, connexion au serveur impossible",$this->sgbd,$this->codErr) ;
      // Connexion � la base
      //if (!mysqli_select_db ($this->connexion, $base)) 
      //{
        //$this->message ("D�sol�, acc�s � la base $base impossible\n");
        //$this->message ("<B>MySQL proteste </B>" .
        //throw new Exception ("D�sol�, acc�s � la base $base impossible\n") ;
                             //mysqli_error($this->connexion));
        //$this->erreurRencontree = 1;
      //}
      // Fin du constructeur
        }

     // ---- Partie priv�e : les m�thodes

        // M�thodes pour affichage des messages
    //function message ($message)
       // {
         // On se contente d'afficher le message en HTML
        //echo "<B>Erreur.</B> $message<BR>\n";
        //}
        abstract protected function connect($serveur, $login, $motDePasse, $base) ;
        
        abstract protected function exec($requete) ;

        // ---- Partie publique -------------------------

        // M�thode d'ex�cution d'une requ�te
    public function execRequete ($requete)
        {
      if (!$resultat=$this->exec($requete) )
        
        //throw new Exception ("Probl�me dans l'execution de la requete :") ;
         throw new monException ("Problème dans l'execution de la requete :$requete.<br/>".$this->messErreur(), $this->sgbd,$this->codErr) ;
        //throw new monException ("Probl�me dans l'execution de la requete : $requete.<br/>".$this->messErreur(),"Mysql",1) ;
        // ("Probl�me dans l'execution de la requete : $requete.<br/>".$this->messageSGBD()) ;
       //$this->message ("probl�me dans l'ex�cution de la requ�te : $requete");
       //$this->message ("<B>MySQL proteste : </B>" .
                          //   mysqli_error($this->connexion));
       //$this->erreurRencontree = 1;
        
      return $resultat;
        }
      //methodes abstraites
        // Acc�s � la ligne suivante, sous forme d'objet
    abstract public function objetSuivant ($resultat);
        // Acc�s � la ligne suivante, sous forme de tableau associatif
    abstract public function ligneSuivante ($resultat);
        // Acc�s � la ligne suivante, sous forme de tableau indic�
    abstract public function ligTabSuivant ($resultat);
       // Acc�s au prochain champs dans resultat 
    abstract public function champSuivant ($resultat) ;
       // Acc�s tableau d objets representant les champs du resultat
    abstract public function champTabl ($resultat) ;
       // Acc�s � toutes les lignes resultats dans un tableau
    abstract public function ligToute ($resultat);
       // libere memoire du resultat requete
    abstract public function libResMem ($resultat) ;
        // Acc�s au nombre de champs dans resultat
    abstract public function tableauSuivant ($resultat) ;
      //nombre de lignes dans un resultat     
    abstract public function nbLigne ($resultat) ;
        // M�thode donnant l'id de la derni�re ligne ins�r�e
    abstract public function idDerniereLigne () ;
        // Acc�s � une ligne resultats dans un tableau
    abstract public function ligTab ($resultat) ;
                                                                                                                                                                                      
         //echappement signe speciaux
    abstract public function prepareChaine($chaine);
    
        // M�thode indiquant si une erreur a �t� rencontr�e
    abstract public function messErreur ();
       

        

        // M�thode donnant le nom d'un attribut
    //function nomAttribut ($res, $position)
        //{
      // Test sur la position
      //if ($position < 0 or $position >= $this->nbrAttributs($res))
     // {
        //$this->message ("Il n'y a pas d'attribut en position $position");
	   //return "Inconnu";
     // }
     // else return  mysqli_field_name ($res, $position);
       // }
       
       //nom du sgbd
    public function getSGBD()
        {
        return $this->sgbd;
        }
    public function setSGBD($sgbd)
        {
        $this->sgbd=$sgbd;
        }
        // D�connexion 
    function quitter ()
        {      @mysqli_close ($this->connexion);    }
 
        // Fin de la classe
 }
?>
