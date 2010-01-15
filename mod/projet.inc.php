<?php

// mod/projet.inc.php : Description détaillée d'un projet

if(!isset($template))
    die();

if(!isset($_GET['id']) || intval($_GET['id']) <= 0)
{
    erreur_fatale('Erreur : Projet invalide !');
}
else
{
    // Requête SQL : détails du projet
    $projet = intval($_GET['id']);
    $st = $db->prepare('SELECT * FROM projets WHERE id=?');
    $st->execute(array($projet));
    if($row = $st->fetch(PDO::FETCH_ASSOC))
    {
        $template->assign_vars(array(
            'PROJ_TITRE' => $row['nom'],
            'PROJ_DESCR' => $row['description'],
            'PROJ_ID' => $row['id']));

        // Dernières demandes
        {
            $st2 = $db->prepare('SELECT * FROM demandes WHERE projet=? ORDER BY derniere_activite DESC LIMIT ' . $conf['projet_nb_demandes']);
            $st2->execute(array($projet));
            if($st2->rowCount() == 0)
            {
                $template->assign_block_vars('ZERO_DEMANDES', array(
                    'MSG' => 'Il n\'y a aucune demande à afficher.'));
            }
            else
            {
                while($row2 = $st2->fetch(PDO::FETCH_ASSOC))
                {
                    $template->assign_block_vars('DEMANDE', array(
                        'ID' => $row2['id'],
                        'TITRE' => $row2['titre'],
                        'AUTEUR' => $row2['auteur'],
                        'DESCR' => $row2['description'],
                        'STATUT' => ($row2['statut'] == 0)?'ferme':'ouvert'));
                }
            }
        }

        // Liste des membres
        {
            $st2 = $db->prepare('SELECT u.pseudo AS pseudo, u.nom AS nom, u.promotion AS promotion FROM utilisateurs u INNER JOIN association_utilisateurs_projets a ON u.id=a.utilisateur WHERE a.projet=.');
            $st2->execute(array($projet));
            if($st2->rowCount() == 0)
            {
                $template->assign_block_vars('ZERO_MEMBRES', array(
                    'MSG' => 'Ce projet n\'a aucun membre.'));
            }
            else
            {
                while($row2 = $st2->fetch(PDO::FETCH_ASSOC))
                {
                    $template->assign_block_vars('MEMBRE', array(
                        'PSEUDO' => $row2['pseudo'],
                        'NOM' => $row2['nom'],
                        'PROMOTION' => $row2['promotion']));
                }
            }
        }

        // TODO : Dernières modifications (commits)

        // Liste des versions
        {
            $st2 = $db->prepare('SELECT nom, description FROM versions WHERE projet = ? ORDER BY id DESC LIMIT 0, ' . $conf['projet_nb_versions']);
            $st2->execute(array($projet));
            if($st2->rowCount() == 0)
            {
                $template->assign_block_vars('ZERO_VERSIONS', array(
                    'MSG' => 'Ce projet n\'a défini aucune version.'));
            }
            else
            {
                while($row2 = $st2->fetch(PDO::FETCH_ASSOC))
                {
                    $template->assign_block_vars('VERSION', array(
                        'NOM' => $row2['nom'],
                        'DESCR' => $row2['description']));
                }
            }
        }
    }
    else
        erreur_fatale('Erreur : Ce projet n\'existe pas ou plus.');
}

?>
