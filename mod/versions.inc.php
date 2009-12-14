<?php

// mod/versions.inc.php : Versions du projet, avec leur description, leur état d'avancement et les demandes liées

if(isset($_GET['id']) && intval($_GET['id']) > 0)
    $projet = intval($_GET['id']);
else
    erreur_fatale('Erreur : projet non spécifié.');

$res = mysql_query('SELECT * from versions WHERE projet=' . $projet . ' ORDER BY position;');
if(mysql_num_rows($res) == 0)
{
    $template->assign_block_vars('ZERO_VERSIONS', array(
        'MSG' => 'Il n\'y a aucune version à afficher.'));
}
else
{
    while($row = mysql_fetch_array($res, MYSQL_ASSOC))
    {
        $template->assign_block_vars('VERSION', array(
            'NOM' => $row['nom'],
            'DESCR' => $row['description']));
        $res2 = mysql_query('SELECT * FROM demandes WHERE projet=' . $projet . ' AND version=' . $row['id'] . ';');
        if(mysql_num_rows($res2) == 0)
        {
            $template->assign_block_vars('VERSION.ZERO_DEMANDES', array(
                'MSG' => 'Aucune demande n\'est associée à cette version.'));
        }
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
