<?php

// mod/deconnexion.inc.php : Déconnexion (retour en anonyme)

if(!isset($template))
    die();

$utilisateur->deconnecte();

if(!$conf['debug'])
{
    header('HTTP/302 Moved Temporarily');
    if(isset($_SERVER['HTTP_REFERER']))
    {
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        $template->assign_var('LIEN', $_SERVER['HTTP_REFERER']);
    }
    else
    {
        header('Location: index.php');
        $template->assign_var('LIEN', 'index.php');
    }
}

?>
