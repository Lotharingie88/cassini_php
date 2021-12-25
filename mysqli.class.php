<?php
  // Une classe de gestion des accès à une base MySQLi
  require_once ("BD.class.php");
  //sous classe de class BD
  class monsqli extends BD
  {
    //propriétés héritées de BD
    //constructeur herité de BD
    
    //méthode connexion Mysqli
    protected function connect ($serveur, $login, $motDePasse, $base)
        {
        
      // Connexion au serveur 
      if (!$this->connexion = mysqli_connect ($serveur, $login, $motDePasse))
        return 0;

      // Connexion à la base
      if (!mysqli_select_db ($this->connexion, $this->nom_base)) 
        return 0;
        
        $this->setSGBD("MySQL");

        // Choix du jeu de caractères
        $this->execRequete("SET CHARACTER SET utf8"); 
        
        
      return $this->connexion;  
                
        }
    //methode execution requete
        protected function exec ($requete)
        {
            return mysqli_query ($this->connexion,$requete);
            
        } 
      
    //partie publique implementation des methodes abstraites
       // Accès à la ligne courante, sous forme d'objet
        public function objetSuivant ($resultat)
            {      return  mysqli_fetch_object ($resultat);    } 
       // Accès à la ligne courante, sous forme de tableau associatif
        public function ligneSuivante ($resultat)
            {   return  mysqli_fetch_assoc ($resultat);  }
      // Accès à la ligne courante, sous forme de tableau indicé
        public function ligTabSuivant ($resultat)
            {   return  mysqli_fetch_row ($resultat);  } 
       // Accès au prochain champs dans resultat 
        public function champSuivant ($resultat)
            {   return  mysqli_fetch_field($resultat);  }
       // Accès tableau d objets representant les champs du resultat
        public function champTabl ($resultat)
            {   return  mysqli_fetch_fields ($resultat);  }  
       // Accès à toutes les lignes resultats dans un tableau
        public function ligToute ($resultat)
            {   return  mysqli_fetch_all ($resultat);  } 
        // Accès à une ligne resultats dans un tableau
        public function ligTab ($resultat)
            {   return  mysqli_fetch_array ($resultat);  }        
                    
       // libere memoire du resultat requete
        public function libResMem ($resultat)
            {   return  mysqli_free_result ($resultat);  }
        // Accès au nombre de champs dans resultat
        public function tableauSuivant ($resultat)
            {   return  mysqli_num_fields ($resultat);  }         
            
      //nombre de lignes dans un resultat     
         public function nbLigne ($resultat)
            {   return  mysqli_num_rows($resultat);  }      
       // Méthode donnant l'id de la dernière ligne insérée
        public function idDerniereLigne ()
         {  return  mysqli_insert_id(); } 
      
      
      //echappement signes speciaux
        public function prepareChaine($chaine)
        { return mysqli_real_escape_string($chaine);}  
    
    
        // Méthode indiquant si une erreur a été rencontrée
        public function messErreur ()
        {return  mysqli_error($this->connexion);}  
     //Destruction de la Class on se deconnecte
     function __destruct()
     { if ($this->connexion) mysqli_close($this->connexion);}
     //fin de la classe  
        
  }
?>