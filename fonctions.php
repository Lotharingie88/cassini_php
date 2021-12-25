<?php
function valchps($chps)
{
	//teste si chaque champs attendu contient une donnée
	foreach ($chps as $cle => $valeur)
	{
		if (!isset($cle) || ($valeur=="" ))
		{
		   return FALSE;
		}	
	}
	return TRUE;
}
function valpwd($pw1,$pw2)
{
	if ($pw1==$pw2)
	{
		$vpwd=md5($pw1);
		return TRUE;		
	}
	return FALSE;
}
function convdat($dat)
{
	if (strlen($dat)!='10')
	 {
	 alert("format incorrect : 'jj/mm/yyyy'");  
	}
	else
	{
	}
}
function Form()
    {
      	echo "<center><H2>".'EN COURS DE CONSTRUCTION'."</H2></center>";
    }
?>
