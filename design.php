<?php
//fonction de design du site
require_once ("util.php");
//require ("html.php");
function enteteg($scripting="N",$titre="",$refr="N")
	{
	echo "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Frameset//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd\">\n";
	echo "<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\" lang=\"fr\" dir=\"ltr\">\n";
	echo "<head>\n";
	echo "<title>$titre</title>\n";
	if ($refr=="O")
	{
	    echo "<META http-equiv=\"Refresh\" content=\"60\"/>\n";
	}
    echo  "<META http-equiv=\"Content-Type\" content=\text\html; charset=UTF-8\"/>\n";     ;
	echo "<link rel=\"icon\" href=\"interface/saturn.ico\" type=\"image/x-icon\" />\n";
	echo "<link rel=\"stylesheet\" href=\"cassini.css\" type=\"text/css\" />\n";
	echo "<link rel=\"shortcut icon\" href=\"interface/saturn.ico\" type=\"image/x-icon\" />\n";
	if ($scripting=="O")
	{
 		echo "<script type=\"text/javascript\" language=\"JavaScript\" src=\"script.js\"></script>\n";
 		//echo "<script language=\"JavaScript\" src=\"script.js\"></script>\n";
	}
	echo "</head>\n";
	echo "<body>\n";
}
function bandeau()
{

	echo "Nous sommes le ".date('d/m/Y')." à ".date('H')." heures ".date('i')." minutes.";

}
function menghaccs()
{
	echo "<H2><center>"."CASSINI"."</center></H2>";
	echo "<UL>";
	echo"<LI>".ancre("index.php",'Accueil')."</LI>";
	echo "</UL>";
	echo "<P>".image("interface/redbar.jpg","100%",1);

}
function menghacc()
{
	echo "<H2><center>"."CASSINI"."</center></H2>";
	echo "<UL>";
	echo"<LI>".ancre("index.php",'Accueil')."</LI>";
	echo"<LI>".ancre("inscrip.php",'Inscription')."</LI>";

	echo "</UL>";
	echo "<P>".image("interface/redbar.jpg","100%",1);

}
function confconn()
{
     if (isset($_SESSION["useprenok"]))
	{echo "<H5>".$_SESSION["useprenok"].' '.$_SESSION["userok"].', vous êtes connecté(e)'."</H5>"; }
	echo "<center>".ancre("deconnec.php",'Déconnexion')."</center>";
	echo "<center>".ancre("chgpwd.php",'Change mot de passe')."</center>";
	echo "<P>".image("interface/redbar.jpg","100%",1)."</P>";
	echo "<P></P>";
}
function pieddepage($master="")
{
if ( $master!="")
 {
echo "<P>".image("interface/redbar.jpg","10%",1);

tbldebut(0,"100%");
tbldebutligne();
tblentete(ancre("mailto:sylvain_thouvenot@laposte.net","Webmaster CASSINI"),1,1,"CENTRE");

tblfinligne();
tblfin();
}
tbldebut(0,"100%");
	tbldebutligne();
	tblfinligne();
	tbldebutligne();
	tblfinligne();
	tbldebutligne();
	tblfinligne();

tbldebutligne();
tblcellule(image('interface/pied2.gif'));
tblfinligne();
tblfin();
}
function finpag()
{
echo "</body>";
echo "</html>";
}
//fonction de gestion d'affichage de r�sultat de requ�etes sur plusieurs pages
function multipage($ligne,$nbaffich=10,$nblist=10,$reqcod,$appel,$debpag=1)
	{
	$nomb_pages=$ligne/$nbaffich;
    $pages="";
	if ($nomb_pages<=$nblist)
	{
		for ($i=1;$i<$nomb_pages+1;$i++)
		{
			$deb=($i*$nbaffich)-$nbaffich;
			$req=$reqcod." limit ".$deb.",".$nbaffich;
			$requrl=urlencode($req);
			$reqdyn=$appel."?deb=".$deb."&requetc=".$requrl;
			$pages=$pages."[".ancre($reqdyn,$i,'-1','onClick="changecol()"')."]";
		}
	}
	else
	{
		for ($i=1;$i<$nblist+1;$i++)
		{
			$deb=($i*$nbaffich)-$nbaffich;
			$req=$reqcod." limit ".$deb.",".$nbaffich;
			$requrl=urlencode($req);
			$reqdyn=$appel."?deb=".$deb."&requetc=".$requrl;
			$pages=$pages."[".ancre($reqdyn,$i,'-1','onClick="changecol()"')."]";
		}
		if ($i>$nblist)
		{
			$reqdyn=$appel;
			$pages=$pages."[".ancre($reqdyn,'Suivant >>','-1','onClick="changecol()"')."]";
			//$pages=$pages."[".ancre(multipage($nomb_ligtot,$affich,12,$reqbase,"lcli.php"),'Suivant >>')."]";
		}
	}
		echo "<P><CENTER>".$pages."</CENTER></P>";
	}
?>