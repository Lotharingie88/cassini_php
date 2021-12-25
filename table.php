<?php
//if (!isset($moduletable))
{
//$moduletable=1;
//module de production de tableau Html

function tbldebut($bordure='1', //bordure
				  $largeur=-1, 
				  $espcell='2', //cellspacing
				  $remplcell='4', //cellpadding
				  $classe=-1)
{
$optionclasse="";
$optionlargeur="";
if ($classe!=-1) $optionclasse="CLASS='$classe'";
if ($largeur!=-1) $optionlargeur="WIDTH='$largeur'";

echo "<TABLE BORDER='$bordure'"
	."CELLSPACING='$espcell' CELLPADDING='$remplcell'"
	.$optionlargeur.$optionclasse.">\n";
}

function tblfin()
{
echo "</TABLE>\n";
}

function tbldebutligne($classe=-1)
{
$optionclasse="";
if ($classe!=-1) $optionclasse=" CLASS='$classe'";
echo "<TR".$optionclasse.">\n";
}

function tblfinligne()
{
echo "</TR>\n";
}

function tblentete ($contenu, $nblig=1, $nbcol=1,$classe=-1)
{
echo "<TH ROWSPAN='$nblig' COLSPAN='$nbcol'>$contenu</TH>\n";
}
function tblenteteFusio ($contenu, $nblig=1, $nbcol=1,$classe=-1)
{
echo "<TH ROWSPAN='$nblig' WIDTH=auto COLSPAN=3>$contenu</TH>\n";
}
/*
function tblentete2 ($contenu, $nblig=1, $nbcol=1)
{
echo "<TH2 ROWSPAN='$nblig' COLSPAN='$nbcol'>$contenu</TH2>\n";
}
*/

function tbldebutcellule($classe=-1)
{
$optionclasse="";
if ($classe!=-1)$optionclasse="CLASS='$classe'";
echo "<TD" . $optionclasse . ">\n";
}

function tblfincellule()	
{
echo "</TD>\n";
}

function tblcellule($contenu, $nblig=1,$nbcol=1, $classe=-1)
{
$optionclasse="";

if ($classe!=-1) $optionclasse="CLASS='$classe'";
echo "<TD ROWSPAN='$nblig' COLSPAN='$nbcol'"
	.$optionclasse.">$contenu</TD>\n";


}
				
}


?>