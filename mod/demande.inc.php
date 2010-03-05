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
    LEFT OUTER JOIN utilisateurs u ON u.id=d.auteur
WHERE d.id=?');
$st->execute(array(intval($_GET['id'])));

if(!($demande = $st->fetch(PDO::FETCH_ASSOC)))
    erreur_fatale('Erreur : Demande inconnue !');

$statut = 'inconnu';
if(is_array($conf['demande_statuts']) && isset($conf['demande_statuts'][$demande['statut']]))
    $statut = $conf['demande_statuts'][$demande['statut']];

$template->assign_vars(array(
    'DEMANDE_ID' => $demande['id'],
    'DEMANDE_TITRE' => htmlentities($demande['titre']),
    'AUT_PSEUDO' => ($demande['auteur_pseudo']!=null)?$demande['auteur_pseudo']:'Inconnu',
    'AUT_NOM' => ($demande['auteur_nom']!=null)?$demande['auteur_nom']:'Anonyme',
    'DESCRIPTION' => wikicode2html($demande['description']),
    'PRIORITE' => $demande['priorite'],
    'STATUT' => ($demande['statut'] == 0)?"ferme":"ouvert",
    'STATUT_NOM' => $statut,
    'CREATION' => format_date($demande['creation']),
    'PROJET' => htmlentities($demande['projet_nom']),
    'PROJET_ID' => $demande['projet_id']));

if(isset($demande['version']))
    $template->assign_block_vars('VERSION', array(
        'NOM' => htmlentities($demande['version'])));

// Lien vers la page de modification de la demande
if($utilisateur->autorise(PERM_MANAGE_REQUESTS, $demande['projet_id']))
{
    $template->assign_block_vars('ADMIN_DEMANDE', array(
        'DEMANDE_ID' => $demande['id']));
}

// Affichage des commentaires
$st = $db->prepare(
'SELECT c.id, c.auteur, c.texte, c.creation, u.pseudo AS auteur_pseudo, u.nom AS auteur_nom, u.promotion AS auteur_promo
FROM commentaires c
    LEFT OUTER JOIN utilisateurs u ON u.id=c.auteur
WHERE c.demande=?');
$st->execute(array($demande['id']));

if($st->rowCount() == 0)
{
    $template->assign_block_vars('ZERO_COMMENTAIRES', array());
}
else while($commentaire = $st->fetch(PDO::FETCH_ASSOC))
{
    $template->assign_block_vars('COMMENTAIRE', array(
        'ID' => $commentaire['id'],
        'AUTEUR' => $commentaire['auteur'],
        'AUTEUR_PSEUDO' => $commentaire['auteur_pseudo'],
        'AUTEUR_NOM' => $commentaire['auteur_nom'],
        'AUTEUR_PROMO' => $commentaire['auteur_promo'],
        'DATE' => format_date($commentaire['creation']),
        'TEXTE' => $commentaire['texte']));
}

?>
