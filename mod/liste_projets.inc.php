<?php

// mod/liste_projets.inc.php : Liste des projets

if(!isset($template))
    die();

// Filtres
if(isset($_GET['filtre_nom']) && $_GET['filtre_nom'] != '')
{
    $where = str_replace("*", "%",
        str_replace("\"", "\\\"",
        str_replace("%", "\\%",
        str_replace("_", "\\_",
        str_replace("\\", "\\\\", $_GET['filtre_nom'])))));
    $where = ' WHERE nom LIKE "%' . $where . '%"';

    $filtre = str_replace("\"", "", $_GET['filtre_nom']);
    $template->assign_var('FILTRE', $filtre);
}
else
{
    $where = '';
    $filtre = '';
}

// Numéro de page
$page = 1;
if(isset($_GET['page']))
{
    if(intval($_GET['page']) > 0)
        $page = intval($_GET['page']);
    else
        erreur_fatale('Erreur : numéro de page invalide');
}

// Requête SQL
$nb = $conf['projets_nb_resultats'];

$res = mysql_query('SELECT * from projets' . $where . ' ORDER BY id LIMIT ' . (($page - 1) * $nb) . ', ' . ($nb+1) . ';');

// Pas de résultat
if(mysql_num_rows($res) == 0)
{
    $template->assign_block_vars('ZERO_PROJETS', array(
        'MSG' => 'Il n\'y a aucun projet à afficher.'));
}
// Résultats : on les affiche
else
{
    $prev = $page > 1;
    $next = mysql_num_rows($res) > $nb;

    $i = 0;
    while( ($row = mysql_fetch_array($res, MYSQL_ASSOC)) && ($i < $nb) )
    {
        $template->assign_block_vars('PROJET', array(
            'ID' => $row['id'],
            'NOM' => $row['nom'],
            'DESCR' => $row['description']));
        $i++;
    }

    if($prev)
        $template->assign_block_vars('PREV_PAGE', array(
            'LIEN' => 'index.php?mod=liste_projets&amp;filtre_nom=' . urlencode($filtre) . '&amp;page=' . ($page - 1),
            'NUMERO' => ($page - 1)));
    if($next)
        $template->assign_block_vars('NEXT_PAGE', array(
            'LIEN' => 'index.php?mod=liste_projets&amp;filtre_nom=' . urlencode($filtre) . '&amp;page=' . ($page + 1),
            'NUMERO' => ($page + 1)));
}

?>
