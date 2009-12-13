<?php

include 'inc/session.inc.php';
include 'inc/template.inc.php';
include 'inc/conf.inc.php';

if(file_exists('data/conf.php'))
    include 'data/conf.php';

// Identification de l'utilisateur
$utilisateur = new Utilisateur;

// Choix du module
if(isset($_GET['mod']))
    $mod = $_GET['mod'];
else
    $mod = 'index';

// Initialisation du moteur de template
$template = new Template('data/templates/' . $utilisateur->template());
$template->set_filenames(array(
    'index' => 'index.tpl',
    'projets' => 'projets.tpl',
    'liste_demandes' => 'liste_demandes.tpl',
    'demande' => 'demande.tpl',
    'versions' => 'versions.tpl',
    'erreur' => 'erreur.tpl'
    ));

// Variables globales, ie communes à tous les modules
$template->assign_var('TITRE', $conf['titre']);
$template->assign_var('TEMPLATE_URL', 'data/templates/' . $utilisateur->template());
$template->assign_var('HTML_DESCRIPTION', $conf['html_description']);
// Menu
$template->assign_block_vars('MENU', array(
    'LIEN' => 'index.php?mod=index',
    'TEXTE' => 'Accueil'));
$template->assign_block_vars('MENU', array(
    'LIEN' => 'index.php?mod=projets',
    'TEXTE' => 'Projets'));

if(in_array($mod, array('index', 'projets', 'demande', 'liste_demandes', 'versions')))
{
    // Appel du module spécifié
    include 'mod/' . $mod . '.inc.php';
    $template->pparse($mod);
}
else
{
    // Erreur : pas de module de ce nom
    $template->assign_var('ERREUR_DESCR', 'Erreur : Module invalide !');
    $template->pparse('erreur');
}

?>
