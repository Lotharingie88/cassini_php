<?php
	//if (!isset($fichierhtml))
	{
	//$fichierhtml=1;
//fonctions produisant des conteneurs html
function Ancre($url, $libelle, $classe=-1,$act="")
	{
		$optionclasse="";
		if ($classe!=-1) $optionclasse="CLASS='$classe'";
		if ($act!="") 
        {
        $action="$act";
		return"<A HREF='$url'"."$optionclasse"."$action>$libelle</A>\n";
        }
        else
        return"<A HREF='$url'"."$optionclasse>$libelle</A>\n";
	}
function Image($url, $largeur=-1, $hauteur=-1, $bordure=0)
	{
		$attrlargeur="";
		$attrhauteur="";
		if ($largeur!=-1)$attrlargeur="WIDTH='$largeur'";
		if ($hauteur!=-1)$attrhauteur="HEIGHT='$hauteur'";
		return"<IMG SRC='$url'".$attrlargeur
		.$attrhauteur."BORDER='$bordure'>\n";
	}
	}
?>