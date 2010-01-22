<?php

// mod/projet.inc.php : Description détaillée d'un projet

if(!isset($template))
    die();

if(!$utilisateur->estAnonyme())
{
    header('HTTP/302 Moved Temporarily');
    header('Location: index.php');
    $template->assign_block_vars('OK_REDIRECT', array(
        'PSEUDO' => $utilisateur->pseudo()));
}
else
{
    if(isset($_POST['conn_nom']))
        $template->assign_block_vars('MSG_ERREUR', array(
            'DESCR' => "Nom d'utilisateur ou mot de passe invalide."));
    $template->assign_block_vars('FORM', array());
}

?>
