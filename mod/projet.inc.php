<?php

// mod/projet.inc.php : Description détaillée d'un projet

if(!isset($_GET['id']) || intval($_GET['id']) <= 0)
{
    erreur_fatale('Erreur : Projet invalide !');
}
else
{
    $projet = intval($_GET['id']);
    $res = mysql_query('SELECT * FROM projets WHERE id=' . $projet . ';');
    if($row = mysql_fetch_array($res))
    {
        $template->assign_vars(array(
            'PROJ_TITRE' => $row['nom'],
            'PROJ_DESCR' => $row['description'],
            'PROJ_ID' => $row['id']));

        // Dernières demandes
        {
            $res = mysql_query('SELECT * FROM demandes WHERE projet=' . $projet . ' ORDER BY id DESC LIMIT ' . $conf['projet_nb_demandes'] . ';');
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

        // Liste des membres
        {
            $res = mysql_query('SELECT u.pseudo AS pseudo, u.nom AS nom, u.promotion AS promotion FROM utilisateurs u, association_utilisateurs_projets a WHERE a.projet=' . $projet . ' AND u.id = a.utilisateur;');
            if(mysql_num_rows($res) == 0)
            {
                $template->assign_block_vars('ZERO_MEMBRES', array(
                    'MSG' => 'Ce projet n\'a aucun membre.'));
            }
            else
            {
                while($row = mysql_fetch_array($res, MYSQL_ASSOC))
                {
                    $template->assign_block_vars('MEMBRE', array(
                        'PSEUDO' => $row['pseudo'],
                        'NOM' => $row['nom'],
                        'PROMOTION' => $row['promotion']));
                }
            }
        }

        // TODO : Dernières modifications (commits)

        // Liste des versions
        {
            $res = mysql_query('SELECT nom, description FROM versions WHERE projet=' . $projet . ' ORDER BY id DESC LIMIT 0, ' . $conf['projet_nb_versions'] . ';');
            if(mysql_num_rows($res) == 0)
            {
                $template->assign_block_vars('ZERO_VERSIONS', array(
                    'MSG' => 'Ce projet n\'a défini aucune version.'));
            }
            else
            {
                while($row = mysql_fetch_array($res, MYSQL_ASSOC))
                {
                    $template->assign_block_vars('VERSION', array(
                        'NOM' => $row['nom'],
                        'DESCR' => $row['description']));
                }
            }
        }
    }
    else
        erreur_fatale('Erreur : Ce projet n\'existe pas ou plus.');
}

?>
