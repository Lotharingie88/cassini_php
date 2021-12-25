<?php 
  //sous classe de class Exception
  class monException extends Exception
  {
        //propri�t�s
          private $sgbd ; //nom du sgbd
          private $errnum ; //code erreur sgbd
        //constructeur
    function __construct($message,$sgbd,$errnum=0)
        {
            //appel constructeur du parent
            parent::__construct($message);
            
            //affectation � la sous classe
            $this->sgbd=$sgbd ;
            $this->errnum=$errnum ;
        } 
        //methode
        //le sgbd de l erreur   
    public function getBase()
        {
        return $this->sgbd ;
        }
        //le code erreur
    public function codErreur ()
        {
        return $this->errnum ;
        } 
  }
?>