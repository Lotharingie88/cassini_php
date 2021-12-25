<?php
  // Une classe de gestion des accès à une base MySQL
  abstract class BD
  {
    // ----   Partie privée : les propriétés
 
    protected $connexion, $nom_base  ;
    var $erreurRencontree=0;

    // Constructeur de la classe

    function BD ($serveur,$login,$motDePasse,$base)
        {
	   $this->nom_base=$base;
      // Connexion au serveur 
      $this->connexion = mysqli_connect ($serveur, $login, $motDePasse, $base);

      if ($this->connexion==0) 
       //$this->message("Désolé, connexion au serveur $serveur impossible\n");
         throw new EXception ("Désolé, connexion au serveur $serveur impossible\n") ;
      // Connexion à la base
      if (!mysqli_select_db ($this->connexion, $base)) 
      {
        //$this->message ("Désolé, accès à la base $base impossible\n");
        //$this->message ("<B>MySQL proteste </B>" .
        throw new Exception ("Désolé, accès à la base $base impossible\n") ;
                             //mysqli_error($this->connexion));
        $this->erreurRencontree = 1;
      }
      // Fin du constructeur
        }

        // ---- Partie privée : les méthodes

        // Méthodes pour affichage des messages
    //function message ($message)
       // {
         // On se contente d'afficher le message en HTML
        //echo "<B>Erreur.</B> $message<BR>\n";
        //}
        abstract protected function connect($serveur, $login, $motDePasse, $base) ;
        abstract protected function exec($requete) ;

        // ---- Partie publique -------------------------

        // Méthode d'exécution d'une requête
    public function execRequete ($requete)
        {
      //$resultat = mysqli_query ($this->connexion,$requete );
      //$resultat = mysqli_query ($requete );
      if (!$resultat=$this->exec($requete) )
       { 
        throw new Exception 
        ("Problème dans l'execution dela requete : $requete.<br/>".$this->messageSGBD()) ;
       //$this->message ("problème dans l'exécution de la requête : $requete");
       //$this->message ("<B>MySQL proteste : </B>" .
                          //   mysqli_error($this->connexion));
       $this->erreurRencontree = 1;
       } 
      return $resultat;
        }
      //methodes publiques
      
        // Accès à la ligne suivante, sous forme d'objet
     public function objetSuivant ($resultat)
        {      return  mysqli_fetch_object ($resultat);    } 

        // Accès à la ligne suivante, sous forme de tableau associatif
    public function ligneSuivante ($resultat)
        {   return  mysqli_fetch_assoc ($resultat);  }

        // Accès à la ligne suivante, sous forme de tableau indicé
     public function tableauSuivant ($resultat)
        {   return  mysqli_fetch_row ($resultat);  }

        // Méthode indiquant si une erreur a été rencontrée
     public function messageSGBD ()
        {  return  $this->erreurRencontree;   }

        // Méthode donnant l'id de la dernière ligne insérée
    function idDerniereLigne ()
        {  return  mysqli_insert_id(); }

        // Méthode indiquant le nombre d'attributs dans le résultat
    function nbrAttributs ($res)
      {  return  mysqli_num_fields ($res); }

        // Méthode donnant le nom d'un attribut
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
    
        // Déconnexion 
    function quitter ()
        {      @mysqli_close ($this->connexion);    }
 
        // Fin de la classe
 }
?>
