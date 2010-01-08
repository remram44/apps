<?php

// mod/versions.inc.php : Versions du projet, avec leur description, leur �tat d'avancement et les demandes li�es

if(!isset($template))
    die();

if(isset($_GET['id']) && intval($_GET['id']) > 0)
    $projet = intval($_GET['id']);
else
    erreur_fatale('Erreur : projet non sp�cifi�.');

// Requ�te SQL
$res = mysql_query('SELECT * from versions WHERE projet=' . $projet . ' ORDER BY position;');

// Pas de r�sultat
if(mysql_num_rows($res) == 0)
{
    $template->assign_block_vars('ZERO_VERSIONS', array(
        'MSG' => 'Il n\'y a aucune version � afficher.'));
}
// R�sultats : on les affiche
else
{
    while($row = mysql_fetch_array($res, MYSQL_ASSOC))
    {
        $template->assign_block_vars('VERSION', array(
            'NOM' => $row['nom'],
            'DESCR' => $row['description']));

        // Requ�te SQL : demandes associ�es
        $res2 = mysql_query('SELECT * FROM demandes WHERE projet=' . $projet . ' AND version=' . $row['id'] . ';');

        // Pas de r�sultat
        if(mysql_num_rows($res2) == 0)
        {
            $template->assign_block_vars('VERSION.ZERO_DEMANDES', array(
                'MSG' => 'Aucune demande n\'est associ�e � cette version.'));
        }
        // R�sultats : on les affiche
        else
        {
            while($demande = mysql_fetch_array($res2, MYSQL_ASSOC))
            {
                $template->assign_block_vars('VERSION.DEMANDE', array(
                    'ID' => $demande['id'],
                    'TITRE' => $demande['titre'],
                    'AUTEUR' => $demande['auteur'],
                    'DESCR' => $demande['description'],
                    'STATUT' => ($demande['statut'] == 0)?'ferme':'ouvert'));
            }
        }
    }
}

?>
