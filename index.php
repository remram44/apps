<?php

include 'inc/session.inc.php';
include 'inc/template.inc.php';
include 'inc/conf.inc.php';

$utilisateur = new Utilisateur;
if(isset($_GET['mod']))
    $mod = $_GET['mod'];
else
    $mod = 'index';

$template = new Template('data/templates/' . $utilisateur->template());
$template->set_filenames(array(
    'index' => 'index.tpl',
    //'project' => 'projet.tpl',
    //'demande' => 'demande.tpl',
    //'nouvelle_demande' => 'nouvelle_demande.tpl'
    ));

if(in_array($mod, array('index', 'projet', 'demande', 'nouvelle_demande')))
    include 'mod/' . $mod . '.inc.php';
else
    // TODO
    ;

$template->pparse('index');

?>
