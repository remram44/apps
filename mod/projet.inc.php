<?php

// mod/projet.inc.php : Description détaillée d'un projet

if(!isset($_GET['id']) || intval($_GET['id']) <= 0)
{
    erreur_fatale('Erreur : Projet invalide !');
}
else
{
    $res = mysql_query('SELECT * FROM projets WHERE id=' . intval($_GET['id']) . ';');
    if($row = mysql_fetch_array($res))
    {
        $template->assign_vars(array(
            'PROJ_TITRE' => $row['nom'],
            'PROJ_DESCR' => $row['description']));

        // Dernières demandes
        {
            $res = mysql_query('SELECT * FROM demandes WHERE projet=' . intval($_GET['id']) . ' ORDER BY id DESC LIMIT ' . $conf['projet_nb_demandes'] . ';');
            if(mysql_num_rows($res) == 0)
            {
                $template->assign_block_vars('ZERO_DEMANDES', array(
                    'MSG' => 'Il n\'y a aucune demande à afficher.'));
            }
            else
            {
                while($row = mysql_fetch_array($res, MYSQL_ASSOC))
                {
                    $template->assign_block_vars('DEMANDE', array(
                        'ID' => $row['id'],
                        'TITRE' => $row['titre'],
                        'AUTEUR' => $row['auteur'],
                        'DESCR' => $row['description'],
                        'STATUT' => ($row['statut'] == 0)?'ouvert':'ferme'));
                }
            }
        }

        // TODO : Liste des membres
    }
    else
    {
        erreur_fatale('Erreur : Ce projet n\'existe pas ou plus.');
    }
}

?>
