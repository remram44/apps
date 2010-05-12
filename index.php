<?php

define('START_TIME', microtime());

include 'inc/session.inc.php';
include 'inc/template.inc.php';
include 'inc/conf.inc.php';

function wikicode2html($code)
{
    $code = htmlentities($code);
    $code = str_replace("\n", "<br />\n", $code);
    $code = preg_replace('#\*\*(.+)\*\*#U', '<strong>$1</strong>', $code);
    $code = preg_replace("#//(.+)//#U", '<em>$1</em>', $code);
    $code = preg_replace("#__(.+)__#U", '<dfn>$1</dfn>', $code);
    $code = preg_replace("#\\[\\[(https?://[^ ]+)\\|(.+)\\]\\]#U", '<a href="$1">$2</a>', $code);
    return $code;
}

function wikicode2text($code)
{
    $code = htmlentities($code);
    $code = preg_replace('#\*\*(.+)\*\*#U', '$1', $code);
    $code = preg_replace("#//(.+)//#U", '$1', $code);
    $code = preg_replace("#__(.+)__#U", '$1', $code);
    $code = preg_replace("#\\[\\[(https?://[^ ]+)\\|(.+)\\]\\]#U", '$2 <$1>', $code);
    return $code;
}

function format_date($date)
{
    return date('d/m/Y H:i:s', strtotime($date));
}

if(file_exists('data/conf.php'))
    include 'data/conf.php';

class MyStatement {

    var $real_st;

    function __construct($st, $sql)
    {
        $this->real_st = $st;
        $this->sql = $sql;
    }

    function execute($params)
    {
        global $db;
        $db->nb_query++;
        $texte = $this->sql;
        if(count($params) > 0)
            $texte = $texte . '<br/>avec';
        foreach($params as $key => $value)
            $texte = $texte . ' [' . $key . ']=>"' . $value . '"';
        $db->queries[] = $texte;
        return $this->real_st->execute($params);
    }

    function fetch($fetch_style)
    {
        return $this->real_st->fetch($fetch_style);
    }

    function rowCount()
    {
        return $this->real_st->rowCount();
    }

}

class MyPDO extends PDO {

    var $nb_query;
    var $queries;

    function __construct($dsn, $username='', $password='', $driver_options=array())
    {
        $this->nb_query = 0;
        $this->queries = array();
        parent::__construct($dsn, $username, $password, $driver_options);
    }

    function query($statement)
    {
        $this->nb_query++;
        $this->queries[] = $statement;
        return parent::query($statement);
    }

    function prepare($statement)
    {
        return new MyStatement(parent::prepare($statement), $statement);
    }

    function report()
    {
        global $template;
        $at = explode(' ', START_TIME);
        $bt = explode(' ', microtime());
        $temps = floor(1000*(($bt[1] - $at[1]) + $bt[0]-$at[0]))/1000.0;
        $template->assign_block_vars('DEBUG', array(
            'NB_REQUETES' => $this->nb_query,
            'TEMPS' => $temps));
        foreach($this->queries as $q)
            $template->assign_block_vars('DEBUG.REQ', array(
                'SQL' => $q));
    }

}

// Connection à la base de données
try {
    $db = new MyPDO($conf['db_dsn'], $conf['db_user'], $conf['db_passwd'], array(
        PDO::ATTR_PERSISTENT => $conf['db_persistent']));
}
catch(PDOException $e) {
    $template = new Template('data/templates/' . $conf['default_template']);
    $template->set_filenames(array('root' => 'root.tpl'));
    $template->assign_var('TITRE', $conf['titre']);
    $template->assign_var('TEMPLATE_URL', 'data/templates/' . $conf['default_template']);
    $template->assign_block_vars('MSG_ERREUR', array(
        'DESCR' => 'Erreur : impossible de se connecter à la base de données !<br/>
Erreur : ' . $e->getMessage()));
    $template->pparse('root');
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
    'root' => 'root.tpl',
    'index' => 'index.tpl',
    'projet' => 'projet.tpl',
    'edit_projet' => 'edit_projet.tpl',
    'liste_projets' => 'liste_projets.tpl',
    'demande' => 'demande.tpl',
    'edit_demande' => 'edit_demande.tpl',
    'liste_demandes' => 'liste_demandes.tpl',
    'versions' => 'versions.tpl',
    'connexion' => 'connexion.tpl',
    'deconnexion' => 'deconnexion.tpl',
    'perso' => 'perso.tpl',
    'admin' => 'admin.tpl',
    'edit_user' => 'edit_user.tpl'
    ));
$template->set_rootdir('inc');
$template->set_filenames(array('rss' => 'rss.tpl'));

// Fonction d'erreur utilisant le template
$erreur = false;
function erreur_fatale($msg)
{
    global $template;
    $template->assign_block_vars('MSG_ERREUR', array('DESCR' => $msg));
    $template->pparse('root');
    exit(0);
}

// Variables globales, ie communes à tous les modules
$template->assign_var('TITRE', $conf['titre']);
$template->assign_var('TEMPLATE_URL', 'data/templates/' . $utilisateur->template());
// Menu
$template->assign_block_vars('MENU', array(
    'LIEN' => 'index.php',
    'TEXTE' => 'Accueil'));
$template->assign_block_vars('MENU', array(
    'LIEN' => 'index.php?mod=liste_projets',
    'TEXTE' => 'Projets'));
// Connexion
if($utilisateur->estAnonyme())
{
    $template->assign_block_vars('MENU2_CONN', array());
}
else
{
    $template->assign_block_vars('MENU2_DECO', array(
        'PSEUDO' => $utilisateur->pseudo()));
}
// Page d'administration
if($utilisateur->autorise(PERM_MANAGE_USERS) || $utilisateur->autorise(PERM_MANAGE_PROJECT))
{
    $template->assign_block_vars('LIEN_ADMIN', array());
}

if(in_array($mod, array(
    'index',
    'projet', 'liste_projets', 'edit_projet', 'versions',
    'demande', 'liste_demandes', 'edit_demande',
    'connexion', 'deconnexion', 'perso', 'admin', 'edit_user')))
{
    // Appel du module spécifié
    include 'mod/' . $mod . '.inc.php';
    $template->assign_var_from_handle('ROOT_CONTENT', $mod);
    if($conf['debug'])
        $db->report();
    $template->pparse('root');
}
else if($mod == 'rss')
{
    include 'mod/' . $mod . '.inc.php';
    $template->pparse($mod);
}
else
{
    // Erreur : pas de module de ce nom
    erreur_fatale('Erreur : Module invalide !');
}

?>
