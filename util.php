<?php
//if (!isset($fichierutil))
{
//$fichierutil=1;

    //gestion parametre php apache mysql
   //niveau des erreurs
   error_reporting(E_ALL| ~E_STRICT);
   // zone par defaut pour date et temps
   date_default_timezone_set("Europe/Paris");
   //calcul automatiquement le chemin jusquau repertoire courant
   $root=dirname(__FILE__).DIRECTORY_SEPARATOR ;
   //liste des chemins d inclusion
   set_include_path('.'.
     PATH_SEPARATOR.$root.'lib'.DIRECTORY_SEPARATOR.
     PATH_SEPARATOR.$root.'application'.DIRECTORY_SEPARATOR.
     PATH_SEPARATOR.$root.'application/modeles'.DIRECTORY_SEPARATOR.
     PATH_SEPARATOR.$root.'application/fonctions'.DIRECTORY_SEPARATOR.
     PATH_SEPARATOR.$root.'application/classes'.DIRECTORY_SEPARATOR.
     PATH_SEPARATOR.$root.get_include_path()
   );

    // la configuration
    //Constantes
//$ancour;
//$percour;

//require_once ("constantes.php");

    //parametres de connexion serveur
require_once ("pconnec.php");

    //Les biblioth�ques
    
    //fonctions de session
require_once ("fonctions.php");
require_once ("session.php");
    //fonctions g�n�rales
require_once ("html.php");

    //modules et classes
require_once ("table.php");
require_once("monException.class.php");
require_once ("formulaire.class.php");
require_once ("mysqli.class.php");
require_once ("tableau.class.php");
require_once ("tabBD.class.php");

    //fonction generique de site
require_once ("design.php");
//require_once ("screen.php");

    //fonstion specifique au site
require_once ("formCassini.php");
require_once ("foncCassini.php");
    
    //fonctions d erreurs
require_once("normalisationHTTP.php");
//require_once("GestionErreurs.php");
//require_once("GestionExceptions.php");

    //gestion de l echappement
    //if (get_magic_quotes_gpc()) {
      //  $_POST = NormalisationHTTP($_POST);
       // $_GET = NormalisationHTTP($_GET);
       // $_REQUEST = NormalisationHTTP($_REQUEST);
       // $_COOKIE = NormalisationHTTP($_COOKIE);
    //}
    
    //indique si o affiche ou pas les erreurs via constante de config.php
    //ini_set("display_errors", DISPLAY_ERRORS);
    
    //gestion personnalis�e des erreurs et exceptions cf fichier .php correspondants
    //set_error_handler("GestionErreurs") ;
    //set_error_handler("GestionExceptions") ;
    
    //on charge le frontal
    //require_once("Frontal.php");
   // $frontal  = new Frontal();
        //le controleur frontal traite la requete http
    // try {
       //  $frontal->ececute();
        //}   
     //catch (Exception $e) {
        //echo "Exception lev�e dans l application.<br/>"
        //."<b>Message</b>".$e->getMessage()."<br/>"
        //."<b/>Fichier</b>".$e->getFile()
        //."<b>Ligne</b>".$e->getLine()."<br/>";
     //}

}
?>