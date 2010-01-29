<?php

// mod/liste_demandes.inc.php : Listes les demandes, avec différents filtres

if(!isset($template))
    die();

// Filtres
if(isset($_GET['filtre_nom']) && $_GET['filtre_nom'] != '')
{
    $filtre_nom = str_replace("*", "%",
        str_replace("\"", "\\\"",
        str_replace("%", "\\%",
        str_replace("_", "\\_",
        str_replace("\\", "\\\\", $_GET['filtre_nom'])))));
    $template->assign_var('FILTRE_NOM', str_replace("\"", "", $_GET['filtre_nom']));
}
if(isset($_GET['projet']) && intval($_GET['projet']) > 0)
    $projet = intval($_GET['projet']);
if(isset($_GET['filtre_statut']) && ($_GET['filtre_statut'] == '0' || intval($_GET['filtre_statut']) != 0) && intval($_GET['filtre_statut']) != -1)
    $filtre_statut = intval($_GET['filtre_statut']);

$filtres = array();
if(isset($filtre_nom)) $filtres[] = ' d.titre LIKE "%' . $filtre_nom . '%"';
if(isset($projet)) $filtres[] = ' d.projet=' . $projet;
if(isset($filtre_statut)) $filtres[] = ' d.statut=' . $filtre_statut;

if(count($filtres) > 0)
    $filtres = 'WHERE' . implode(' AND', $filtres);
else
    $filtres = '';

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
$nb = $conf['demandes_nb_resultats'];

// FIXME : requête pas jolie. Risques d'injection ?
$st = $db->query(
'SELECT d.id, d.titre, d.description, d.priorite, d.statut, d.creation, d.derniere_activite, d.projet AS projet_id, p.nom AS projet, v.nom AS version, u.pseudo, u.nom AS nom_auteur, u.promotion
FROM demandes d
    INNER JOIN projets p ON d.projet=p.id
    LEFT OUTER JOIN versions v ON d.version=v.id
    INNER JOIN utilisateurs u ON d.auteur=u.id
' . $filtres . '
ORDER BY d.priorite, d.derniere_activite DESC
LIMIT ' . (($page-1)*$nb) . ', ' . ($nb+1));

// Pas de résultat
if($st->rowCount() == 0)
{
    $template->assign_block_vars('ZERO_DEMANDES', array());
}
// Résultats : on les affiche
else
{
    $prev = $page > 1;
    $next = $st->rowCount() > $nb;

    $i = 0;
    while( ($row = $st->fetch(PDO::FETCH_ASSOC)) && ($i < $nb) )
    {
        $statut = 'inconnu';
        if(is_array($conf['demande_statuts']) && isset($conf['demande_statuts'][$row['statut']]))
            $statut = $conf['demande_statuts'][$row['statut']];
        $template->assign_block_vars('DEMANDE', array(
            'ID' => $row['id'],
            'PROJET' => $row['projet'],
            'PROJET_ID' => $row['projet_id'],
            'TITRE' => htmlentities($row['titre']),
            'AUT_PSEUDO' => $row['pseudo'],
            'AUT_NOM' => $row['nom_auteur'],
            'AUT_PROMO' => $row['promotion'],
            'PRIORITE' => $row['priorite'],
            'STATUT' => ($row['statut'] == 0)?'ferme':'ouvert',
            'STATUT_NOM' => $statut,
            'CREATION' => format_date($row['creation']),
            'ACTIVITE' => format_date($row['derniere_activite']),
            'PARITE' => ((($i % 2) == 0)?'par':'impar')));
        // TODO 2 : lien "ancre" vers la version dans le .tpl
        if(isset($row['version']) && $row['version'] != '')
            $template->assign_block_vars('DEMANDE.VERSION', array(
                'NOM' => htmlentities($row['version'])));
        $i++;
    }

    if($prev)
        $template->assign_block_vars('PREV_PAGE', array(
            'LIEN' => 'index.php?mod=liste_demandes&amp;filtre_nom=' . (isset($filtre_nom)?urlencode($filtre_nom):'') . '&amp;projet=' . (isset($projet)?$projet:'') . '&amp;filtre_statut=' . (isset($filtre_statut)?$filtre_statut:-1) . '&amp;page=' . ($page - 1),
            'NUMERO' => ($page - 1)));
    if($next)
        $template->assign_block_vars('NEXT_PAGE', array(
            'LIEN' => 'index.php?mod=liste_demandes&amp;filtre_nom=' . urlencode($filtre_nom) . '&amp;projet=' . (isset($projet)?$projet:'') . '&amp;filtre_statut=' . (isset($filtre_statut)?$filtre_statut:-1) . '&amp;page=' . ($page + 1),
            'NUMERO' => ($page + 1)));
}

// Statuts pour filtrage
if(!isset($filtre_statut)) $filtre_statut = -1;
$template->assign_block_vars('FILTRE_STATUT', array(
    'VALEUR' => -1,
    'SELECTED' => ($filtre_statut==-1)?' selected="selected"':'',
    'NOM' => '(tous)'));
if(is_array($conf['demande_statuts']))
    foreach($conf['demande_statuts'] as $valeur => $nom)
    {
        $template->assign_block_vars('FILTRE_STATUT', array(
            'VALEUR' => $valeur,
            'SELECTED' => ($filtre_statut==$valeur)?' selected="selected"':'',
            'NOM' => $nom));
    }

?>
