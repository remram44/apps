<?php

// mod/versions.inc.php : Versions du projet, avec leur description, leur état d'avancement et les demandes liées

if(!isset($template))
    die();

if(isset($_GET['id']) && intval($_GET['id']) > 0)
    $projet = intval($_GET['id']);
else
    erreur_fatale('Erreur : projet non spécifié.');

// Requête SQL
$st = $db->prepare('SELECT * from versions WHERE projet=? ORDER BY position');
$st->execute(array($projet));

// Pas de résultat
if($st->rowCount() == 0)
{
    $template->assign_block_vars('ZERO_VERSIONS', array());
}
// Résultats : on les affiche
else
{
    // Requête SQL : demandes associées
    $st2 = $db->prepare('SELECT * FROM demandes WHERE projet=:projet AND version=:version');
    while($row = $st->fetch(PDO::FETCH_ASSOC))
    {
        $template->assign_block_vars('VERSION', array(
            'NOM' => htmlentities($row['nom']),
            'DESCR' => wikicode2html($row['description'])));

        // FIXME : trop de requêtes SQL ?
        $st2->execute(array(
            ':projet' => $projet,
            ':version' => $row['id']));

        // Pas de résultat
        if($st2->rowCount() == 0)
        {
            $template->assign_block_vars('VERSION.ZERO_DEMANDES', array());
        }
        // Résultats : on les affiche
        else
        {
            while($demande = $st2->fetch(PDO::FETCH_ASSOC))
            {
                $template->assign_block_vars('VERSION.DEMANDE', array(
                    'ID' => $demande['id'],
                    'TITRE' => htmlentities($demande['titre']),
                    'AUTEUR' => $demande['auteur'],
                    'DESCR' => $demande['description'],
                    'STATUT' => ($demande['statut'] == 0)?'ferme':'ouvert'));
            }
        }
    }
}

?>
