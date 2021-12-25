<?php
  // Une classe de gestion des acc�s � une base MySQL
  abstract class BD
  {
    // ----   Partie priv�e : les propri�t�s
 
    protected $connexion, $nom_base  ;
    var $erreurRencontree=0;

    // Constructeur de la classe

    function BD ($serveur,$login,$motDePasse,$base)
        {
	   $this->nom_base=$base;
      // Connexion au serveur 
      $this->connexion = mysqli_connect ($serveur, $login, $motDePasse, $base);

      if ($this->connexion==0) 
       //$this->message("D�sol�, connexion au serveur $serveur impossible\n");
         throw new EXception ("D�sol�, connexion au serveur $serveur impossible\n") ;
      // Connexion � la base
      if (!mysqli_select_db ($this->connexion, $base)) 
      {
        //$this->message ("D�sol�, acc�s � la base $base impossible\n");
        //$this->message ("<B>MySQL proteste </B>" .
        throw new Exception ("D�sol�, acc�s � la base $base impossible\n") ;
                             //mysqli_error($this->connexion));
        $this->erreurRencontree = 1;
      }
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
      //$resultat = mysqli_query ($this->connexion,$requete );
      //$resultat = mysqli_query ($requete );
      if (!$resultat=$this->exec($requete) )
       { 
        throw new Exception 
        ("Probl�me dans l'execution dela requete : $requete.<br/>".$this->messageSGBD()) ;
       //$this->message ("probl�me dans l'ex�cution de la requ�te : $requete");
       //$this->message ("<B>MySQL proteste : </B>" .
                          //   mysqli_error($this->connexion));
       $this->erreurRencontree = 1;
       } 
      return $resultat;
        }
      //methodes publiques
      
        // Acc�s � la ligne suivante, sous forme d'objet
     public function objetSuivant ($resultat)
        {      return  mysqli_fetch_object ($resultat);    } 

        // Acc�s � la ligne suivante, sous forme de tableau associatif
    public function ligneSuivante ($resultat)
        {   return  mysqli_fetch_assoc ($resultat);  }

        // Acc�s � la ligne suivante, sous forme de tableau indic�
     public function tableauSuivant ($resultat)
        {   return  mysqli_fetch_row ($resultat);  }

        // M�thode indiquant si une erreur a �t� rencontr�e
     public function messageSGBD ()
        {  return  $this->erreurRencontree;   }

        // M�thode donnant l'id de la derni�re ligne ins�r�e
    function idDerniereLigne ()
        {  return  mysqli_insert_id(); }

        // M�thode indiquant le nombre d'attributs dans le r�sultat
    function nbrAttributs ($res)
      {  return  mysqli_num_fields ($res); }

        // M�thode donnant le nom d'un attribut
    function nomAttribut ($res, $position)
        {
      // Test sur la position
      if ($position < 0 or $position >= $this->nbrAttributs($res))
      {
        $this->message ("Il n'y a pas d'attribut en position $position");
	   return "Inconnu";
      }
      else return  mysqli_field_name ($res, $position);
        }
    
        // D�connexion 
    function quitter ()
        {      @mysqli_close ($this->connexion);    }
 
        // Fin de la classe
 }
?>
