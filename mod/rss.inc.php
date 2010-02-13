<?php

// mod/rss.inc.php : Listes les demandes au format RSS

if(!isset($template))
    die();

header('Content-Type: text/xml; charset=ISO-8859-15');

$template->assign_vars(array(
    'CHANNEL_LIEN' => $conf['base_url']));

if(isset($_GET['projet']) && intval($_GET['projet']) > 0)
    $projet = 'WHERE d.projet='.intval($_GET['projet']);
else
    $projet = '';

$st = $db->query(
'SELECT d.id, d.titre, d.description, d.priorite, d.statut, d.creation, d.derniere_activite, d.projet AS projet_id, p.nom AS projet, v.nom AS version, u.pseudo, u.nom AS nom_auteur, u.promotion
FROM demandes d
    INNER JOIN projets p ON d.projet=p.id
    LEFT OUTER JOIN versions v ON d.version=v.id
    INNER JOIN utilisateurs u ON d.auteur=u.id
' . $projet . '
ORDER BY d.derniere_activite DESC
LIMIT ' . $conf['rss_nb_demandes']);

while($row = $st->fetch(PDO::FETCH_ASSOC))
{
    $statut = 'inconnu';
    if(is_array($conf['demande_statuts']) && isset($conf['demande_statuts'][$row['statut']]))
        $statut = $conf['demande_statuts'][$row['statut']];
    $template->assign_block_vars('DEMANDE', array(
        'ID' => $row['id'],
        'TITRE' => htmlentities($row['titre']),
        'AUT_PSEUDO' => $row['pseudo'],
        'AUT_NOM' => $row['nom_auteur'],
        'AUT_PROMO' => $row['promotion'],
        'DESCRIPTION' => str_replace("\n", "&lt;br/&gt;\n", str_replace('&', '&amp;', htmlentities(wikicode2text($row['description'])))),
        'DATE_CREATION' => format_date($row['creation']),
        'STATUT' => $statut));
}

?>
