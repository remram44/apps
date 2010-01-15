<?php

// mod/versions.inc.php : Versions du projet, avec leur description, leur �tat d'avancement et les demandes li�es

if(!isset($template))
    die();

if(isset($_GET['id']) && intval($_GET['id']) > 0)
    $projet = intval($_GET['id']);
else
    erreur_fatale('Erreur : projet non sp�cifi�.');

// Requ�te SQL
$st = $db->prepare('SELECT * from versions WHERE projet=? ORDER BY position');
$st->execute(array($projet));

// Pas de r�sultat
if($st->rowCount() == 0)
{
    $template->assign_block_vars('ZERO_VERSIONS', array(
        'MSG' => 'Il n\'y a aucune version � afficher.'));
}
// R�sultats : on les affiche
else
{
    // Requ�te SQL : demandes associ�es
    $st2 = $db->prepare('SELECT * FROM demandes WHERE projet=:projet AND version=:version');
    while($row = $st->fetch(PDO::FETCH_ASSOC))
    {
        $template->assign_block_vars('VERSION', array(
            'NOM' => $row['nom'],
            'DESCR' => $row['description']));

        // FIXME : trop de requ�tes SQL ?
        $st2->execute(array(
            ':projet' => $projet,
            ':version' => $row['id']));

        // Pas de r�sultat
        if($st2->rowCount() == 0)
        {
            $template->assign_block_vars('VERSION.ZERO_DEMANDES', array(
                'MSG' => 'Aucune demande n\'est associ�e � cette version.'));
        }
        // R�sultats : on les affiche
        else
        {
            while($demande = $st2->fetch(PDO::FETCH_ASSOC))
            {
                $template->assign_block_vars('VERSION.DEMANDE', array(
                    'ID' => $demande['id'],
                    'TITRE' => $demande['titre'],
                    'AUTEUR' => $demande['auteur'],
                    'DESCR' => $demande['description'],
                    'STATUT' => ($demande['statut'] == 0)?'ferme':'ouvert'));
            }
        }
    }
}

?>
