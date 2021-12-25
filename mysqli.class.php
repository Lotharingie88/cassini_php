<?php
  // Une classe de gestion des acc�s � une base MySQLi
  require_once ("BD.class.php");
  //sous classe de class BD
  class monsqli extends BD
  {
    //propri�t�s h�rit�es de BD
    //constructeur herit� de BD
    
    //m�thode connexion Mysqli
    protected function connect ($serveur, $login, $motDePasse, $base)
        {
        
      // Connexion au serveur 
      if (!$this->connexion = mysqli_connect ($serveur, $login, $motDePasse))
        return 0;

      // Connexion � la base
      if (!mysqli_select_db ($this->connexion, $this->nom_base)) 
        return 0;
        
        $this->setSGBD("MySQL");

        // Choix du jeu de caract�res
        $this->execRequete("SET CHARACTER SET utf8"); 
        
        
      return $this->connexion;  
                
        }
    //methode execution requete
        protected function exec ($requete)
        {
            return mysqli_query ($this->connexion,$requete);
            
        } 
      
    //partie publique implementation des methodes abstraites
       // Acc�s � la ligne courante, sous forme d'objet
        public function objetSuivant ($resultat)
            {      return  mysqli_fetch_object ($resultat);    } 
       // Acc�s � la ligne courante, sous forme de tableau associatif
        public function ligneSuivante ($resultat)
            {   return  mysqli_fetch_assoc ($resultat);  }
      // Acc�s � la ligne courante, sous forme de tableau indic�
        public function ligTabSuivant ($resultat)
            {   return  mysqli_fetch_row ($resultat);  } 
       // Acc�s au prochain champs dans resultat 
        public function champSuivant ($resultat)
            {   return  mysqli_fetch_field($resultat);  }
       // Acc�s tableau d objets representant les champs du resultat
        public function champTabl ($resultat)
            {   return  mysqli_fetch_fields ($resultat);  }  
       // Acc�s � toutes les lignes resultats dans un tableau
        public function ligToute ($resultat)
            {   return  mysqli_fetch_all ($resultat);  } 
        // Acc�s � une ligne resultats dans un tableau
        public function ligTab ($resultat)
            {   return  mysqli_fetch_array ($resultat);  }        
                    
       // libere memoire du resultat requete
        public function libResMem ($resultat)
            {   return  mysqli_free_result ($resultat);  }
        // Acc�s au nombre de champs dans resultat
        public function tableauSuivant ($resultat)
            {   return  mysqli_num_fields ($resultat);  }         
            
      //nombre de lignes dans un resultat     
         public function nbLigne ($resultat)
            {   return  mysqli_num_rows($resultat);  }      
       // M�thode donnant l'id de la derni�re ligne ins�r�e
        public function idDerniereLigne ()
         {  return  mysqli_insert_id(); } 
      
      
      //echappement signes speciaux
        public function prepareChaine($chaine)
        { return mysqli_real_escape_string($chaine);}  
    
    
        // M�thode indiquant si une erreur a �t� rencontr�e
        public function messErreur ()
        {return  mysqli_error($this->connexion);}  
     //Destruction de la Class on se deconnecte
     function __destruct()
     { if ($this->connexion) mysqli_close($this->connexion);}
     //fin de la classe  
        
  }
?>