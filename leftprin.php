<?php
require_once ("util.php");
enteteg("N","CASSINI" );
?>
<div id="bandeau">
	<?php
bandeau();
?>
	<div id="logocourrier">
            <img id="img1" src="interface/logo_courrier.gif" alt="Retour Accueil" width="30" height="30" />
    </div>
	<div id="titresite">SCRIBE.Co&ucirc;ts unitaires</div>
	<div id="cartouche2"><a href="http://www.wac.courrier.intra.laposte.fr"><Img ID="img3" src="interface/logo_wac.gif" width="29" height="11" Alt="WAC" /></a> | <a href="http://www.i-poste.log.intra.laposte.fr"><Img ID="img4" src="interface/logo_iposte.gif" width="48" height="15" Alt="i-poste" /></a> | <a href="mailto:sylvain.thouvenot@laposte.fr">Nous &eacute;crire</a> | <a href="">Plan du site</a> | <a href="" target="_blank">Aide</a></div>
</div>

	<div id="menugaucheh">
<?php
menghacc();
?>
	</div>
	<div id="menugauchem">
<?php

echo "<center><H2>".'CONNEXION'."</H2></center>";
connex();
pieddepage("master");
?>
	</div>
<div id="cont_princ">
	<?php
tblcellule(image('interface/trantor.jpg',"100%","100%"));
?>
</div>

<div id="logobas"></div>
<?php
finpag();

?>

