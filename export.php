<?php
require_once ("util.php");
session_start();
sessval();

enteteg("O","CASSINI",'O');
?>
<div id="bandeau">
	<?php
	bandeau();
	?>
	<div id="logocourrier">
            <img id="img1" src="interface/logo_courrier.gif" alt="Retour Accueil" width="30" height="30" />
    </div>
	<div id="titresite">CASSINI</div>
	<div id="cartouche2"><a href="http://www.wac.courrier.intra.laposte.fr"><Img ID="img3" src="interface/logo_wac.gif" width="29" height="11" Alt="WAC" /></a> | <a href="http://www.i-poste.log.intra.laposte.fr"><Img ID="img4" src="interface/logo_iposte.gif" width="48" height="15" Alt="i-poste" /></a> | <a href="mailto:sylvain_thouvenot@laposte.net">Nous écrire</a> | <a href="">Plan du site</a> | <a href="" target="_blank">Aide</a></div>
	<div id="titrepage">Export de données</div>
</div>



	<div id="menugaucheh">
	<?php
	menghaccs();
	confconn();
	?>
	</div>
	<div id="menugauchem">
	<?php

		switch ($_SESSION["usefonc"])
		{
		    case 1:
		        echo "<center><H2>".'VISION PAYS'."</H2></center>";
		        echo "<UL>\n";
		        echo "<LI>".ancre("glob.php",'Global')."</LI>\n";
		        echo "<LI>".ancre("geogr.php",'Géographie')."</LI>\n";
		        echo "<LI>".ancre("econo.php",'Economie')."</LI>\n";
		        echo "<LI>".ancre("polit.php",'Politique')."</LI>\n";
		        echo "</UL>\n";
		        
		        echo "<center><H2>".'VISION COMPARATIVE'."</H2></center>";
		        echo "<UL>\n";
		        echo "<LI>".ancre("compeco.php",'Economie')."</LI>\n";
		        
		        echo "</UL>\n";
		        
		        
		        
		        echo "<center><H2>".'ADMINISTRATION'."</H2></center>";
		        echo "<UL>\n";
		        echo "<LI>".ancre("gestut.php",'Gestion utilisateur')."</LI>\n";
		        echo "<LI>".ancre("import.php",'Import données')."</LI>\n";
		        echo "<LI>".ancre("export.php",'Export données')."</LI>\n";
		        echo "<LI>".ancre("visulog.php",'Visualitionlog')."</LI>\n";
		        echo "<LI>".ancre("stats.php",'Statistique')."</LI>\n";
		        echo "</UL>\n";
		        break;
		        
		    case 2:
		        
		        echo "<center><H2>".'VISION PAYS'."</H2></center>";
		        echo "<UL>\n";
		        echo "<LI>".ancre("glob.php",'Global')."</LI>\n";
		        echo "<LI>".ancre("geogr.php",'Géographie')."</LI>\n";
		        echo "<LI>".ancre("econo.php",'Economie')."</LI>\n";
		        echo "<LI>".ancre("polit.php",'Politique')."</LI>\n";
		        echo "</UL>\n";
		        
		        echo "<center><H2>".'VISION COMPARATIVE'."</H2></center>";
		        echo "<UL>\n";
		        echo "<LI>".ancre("cudotc.php",'Economie')."</LI>\n";
		        
		        echo "</UL>\n";
		        break;
			default:
				echo "<CENTER><H3>Type de fichier non determiné</H3></CENTER>\n";
				break;
		}
		
	?>
	</div>
<div id="logobas"></div>
<div id="cont_princ">
<div class="scContent">

	<?php
	/*if (isset($_GET['action']))
	   {*/
	formexport();
	/*}
	   else
	   {
	   rec();
	   }*/
	?>
	</div>
</div>
	<?php
	finpag();
	?>