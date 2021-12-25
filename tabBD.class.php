<?php
// Une classe de gestion des acc�s � une base de donn�e
abstract class tabBD
{
  //partie priv�e
  //constantes et variables
    const INS_BD=1;
    const MAJ_BD=2;
    const DEL_BD=3;
    const EDITER=4;
    
    protected $bd,$script,$nTable, $sTable,$entet;
    //constructeur
    function __construct($nTable,$bd,$script="test")
    {
        //initialisation des variables priv�es
        $this->bd=$bd;
        $this->nTable=$nTable;
        if ($script=="test")
            $this->script=$_SERVER['PHP_SELF'];
        else 
            $this->script=$script;
        //schema de la table
        $this->sTable=$bd->schemaTable($nTable);
        
        //attribut defaut des tables
        foreach ($this->sTable as $nom=>$options)
            $this->entet[$nom]=$nom;
        
    }
    //methodes
    
    
  //partie public
  
    
}
?>