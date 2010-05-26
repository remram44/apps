<?php

// mod/index.inc.php : Page d'accueil, activité récente

if(!isset($template))
    die();

// Description du site (globale, pas un projet)
$template->assign_var('HTML_DESCRIPTION', $conf['html_description']);

// Dernières demandes
{
    $st = $db->query('SELECT * FROM demandes ORDER BY id DESC LIMIT ' . $conf['index_nb_demandes']);
    if($st->rowCount() == 0)
    {
        $template->assign_block_vars('ZERO_DEMANDES', array());
    }
    else
    {
        while($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $template->assign_block_vars('DEMANDE', array(
                'ID' => $row['id'],
                'TITRE' => htmlentities($row['titre'], ENT_COMPAT, 'UTF-8'),
                'AUTEUR' => $row['auteur'],
                'STATUT' => ($row['statut'] == 0)?'ferme':'ouvert'));
        }
    }
}

// Utilisateurs actifs
{
    $st = $db->query(
    'SELECT a.derniere_activite, u.pseudo, u.nom, u.promotion, a.projet AS projet_id, p.nom AS projet
    FROM association_utilisateurs_projets a
        INNER JOIN projets p ON a.projet=p.id
        INNER JOIN utilisateurs u ON a.utilisateur=u.id
    WHERE derniere_activite IS NOT NULL
    ORDER BY derniere_activite DESC
    LIMIT ' . $conf['index_nb_utilisateurs'] . ';');
    if($st->rowCount() == 0)
    {
        $template->assign_block_vars('ZERO_UTILISATEURS', array());
    }
    else
    {
        while($row = $st->fetch(PDO::FETCH_ASSOC))
        {
            $template->assign_block_vars('UTILISATEUR', array(
                'PSEUDO' => $row['pseudo'],
                'NOM' => $row['nom'],
                'PROMO' => $row['promotion'],
                'PROJET_ID' => $row['projet_id'],
                'PROJET' => $row['projet']));
        }
    }
}

// Projets actifs

?>
