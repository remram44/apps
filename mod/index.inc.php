<?php

// mod/index.inc.php : Page d'accueil, activit� r�cente

// Description du site (globale, pas un projet)
$template->assign_var('HTML_DESCRIPTION', $conf['html_description']);

// Derni�res demandes
{
    $res = mysql_query('SELECT * FROM demandes ORDER BY id DESC LIMIT ' . $conf['index_nb_demandes'] . ';');
    if(mysql_num_rows($res) == 0)
    {
        $template->assign_block_vars('ZERO_DEMANDES', array(
            'MSG' => 'Il n\'y a aucune demande � afficher.'));
    }
    else
    {
        while($row = mysql_fetch_array($res, MYSQL_ASSOC))
        {
            var_dump($row);
            $template->assign_block_vars('DEMANDE', array(
                'ID' => $row['id'],
                'TITRE' => $row['titre'],
                'AUTEUR' => $row['auteur'],
                'DESCR' => $row['description'],
                'STATUT' => ($row['statut'] == 0)?'ouvert':'ferme'));
        }
    }
}

// TODO : Utilisateurs actifs

// TODO : Projets actifs

?>
