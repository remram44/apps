<?php

include 'inc/session.inc.php';
include 'inc/template.inc.php';
include 'inc/conf.inc.php';

if(file_exists('data/conf.php'))
    include 'data/conf.php';

// Connection à la base de données
try {
    $db = new PDO($conf['db_dsn'], $conf['db_user'], $conf['db_passwd'], array(
        PDO::ATTR_PERSISTENT => $conf['db_persistent']));
}
catch(PDOException $e) {
    $template = new Template('data/templates/' . $conf['default_template']);
    $template->set_filenames(array('erreur' => 'erreur.tpl'));
    $template->assign_var('TITRE', $conf['titre']);
    $template->assign_var('TEMPLATE_URL', 'data/templates/' . $conf['default_template']);
    $template->assign_var('ERREUR_DESCR', 'Erreur : impossible de se connecter à la base de données !<br/>
Erreur : ' . $e->getMessage());
    $template->pparse('erreur');
    exit(1);
}

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
    'projet' => 'projet.tpl',
    'edit_projet' => 'edit_projet.tpl',
    'liste_projets' => 'liste_projets.tpl',
    'demande' => 'demande.tpl',
    'edit_demande' => 'edit_demande.tpl',
    'liste_demandes' => 'liste_demandes.tpl',
    'versions' => 'versions.tpl',
    'erreur' => 'erreur.tpl',
//    'connexion' => 'connexion.tpl',
    'deconnexion' => 'deconnexion.tpl',
//    'perso' => 'perso.tpl'
    ));

// Fonction d'erreur utilisant le template
$erreur = false;
function erreur_fatale($msg)
{
    global $template;
    $template->assign_var('ERREUR_DESCR', $msg); $template->pparse('erreur');
    exit(0);
}

// Variables globales, ie communes à tous les modules
$template->assign_var('TITRE', $conf['titre']);
$template->assign_var('TEMPLATE_URL', 'data/templates/' . $utilisateur->template());
// Menu
$template->assign_block_vars('MENU', array(
    'LIEN' => 'index.php?mod=index',
    'TEXTE' => 'Accueil'));
$template->assign_block_vars('MENU', array(
    'LIEN' => 'index.php?mod=liste_projets',
    'TEXTE' => 'Projets'));
// Connexion
if($utilisateur->estAnonyme())
{
    $template->assign_block_vars('MENU', array(
        'LIEN' => 'index.php?mod=connexion',
        'TEXTE' => 'Connexion'));
}
else
{
    $template->assign_block_vars('MENU', array(
        'LIEN' => 'index.php?mod=perso',
        'TEXTE' => 'Identifié comme ' . $utilisateur->pseudo()));
    $template->assign_block_vars('MENU', array(
        'LIEN' => 'index.php?mod=deconnexion',
        'TEXTE' => '(déconnexion)'));
}

if(in_array($mod, array('index', 'projet', 'liste_projets', 'demande', 'liste_demandes', 'versions', 'connexion', 'deconnexion', 'perso')))
{
    // Appel du module spécifié
    include 'mod/' . $mod . '.inc.php';
    $template->pparse($mod);
}
else
{
    // Erreur : pas de module de ce nom
    erreur_fatale('Erreur : Module invalide !');
}

?>
