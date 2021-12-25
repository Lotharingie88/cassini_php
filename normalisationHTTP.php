<?php
//supprime tous les echappements automatique des donn�es HTTP dans les tableau
//attention les cl�s ne doivent pas contenir d apostrophes
function normalisationHTTP($tab)
{
    //si on est en echappement auto on corrige
    foreach ($tab as $cle => $val)
    {
        if (!is_array($val)) //on fait
        $tab[$cle]=stripslashes($val);
        else //recursivement
        $tab[$cle]=normalisationHTTP($val);
    }
    return $tab;
}
?>