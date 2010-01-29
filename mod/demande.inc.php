<?php

// mod/demande.inc.php : Affiche les détails d'une demande et permet de voir/ajouter des commentaires

if(!isset($template))
    die();

if(!isset($_GET['id']) || intval($_GET['id']) <= 0)
    erreur_fatale('Erreur : Demande invalide !');

$st = $db->prepare(
'SELECT d.id, d.titre, d.auteur, d.description, d.priorite, d.statut, d.creation, p.nom AS projet_nom, p.id AS projet_id, u.pseudo AS auteur_pseudo, u.nom AS auteur_nom, v.nom AS version
FROM demandes d
    INNER JOIN projets p ON p.id=d.projet
    LEFT OUTER JOIN versions v ON v.id=d.version
    INNER JOIN utilisateurs u ON u.id=d.auteur
WHERE d.id=?');
$st->execute(array(intval($_GET['id'])));

if(!($row = $st->fetch(PDO::FETCH_ASSOC)))
    erreur_fatale('Erreur : Demande inconnue !');

$statut = 'inconnu';
if(is_array($conf['demande_statuts']) && isset($conf['demande_statuts'][$row['statut']]))
    $statut = $conf['demande_statuts'][$row['statut']];

$template->assign_vars(array(
    'DEMANDE_ID' => $row['id'],
    'DEMANDE_TITRE' => htmlentities($row['titre']),
    'AUT_PSEUDO' => $row['auteur_pseudo'],
    'AUT_NOM' => $row['auteur_nom'],
    'DESCRIPTION' => wikicode2html($row['description']),
    'PRIORITE' => $row['priorite'],
    'STATUT' => ($row['statut'] == 0)?"ferme":"ouvert",
    'STATUT_NOM' => $statut,
    'CREATION' => format_date($row['creation']),
    'PROJET' => htmlentities($row['projet_nom']),
    'PROJET_ID' => $row['projet_id']));

if(isset($row['version']))
    $template->assign_block_vars('VERSION', array(
        'NOM' => htmlentities($row['version'])));

?>
