<?php

$conf['titre'] = 'Projets du Rézo';
$conf['default_template'] = 'default';
$conf['html_description'] = '<h1>It works !</h1>
<p>Ceci est la description par défaut de Apps. Si vous voyez ceci, c\'est que le programme est correctement configuré.</p>
<p>Vous pouvez changer cette description en modifiant le fichier data/conf.php</p>';
$conf['db_dsn'] = 'mysql:host=localhost;dbname=apps';
$conf['db_user'] = 'root';
$conf['db_passwd'] = '';
$conf['db_persistent'] = false;
$conf['base_url'] = 'http://www.example.com/apps/';
$conf['debug'] = false;
$conf['index_nb_demandes'] = 8;
$conf['index_nb_utilisateurs'] = 8;
$conf['projet_nb_demandes'] = 8;
$conf['projet_nb_versions'] = 5;
$conf['projets_nb_resultats'] = 30;
$conf['demandes_nb_resultats'] = 50;
$conf['rss_nb_demandes'] = 20;
$conf['demande_statuts'] = array('fermé', 'non-confirmé', 'confirmé', 'en cours');

?>
