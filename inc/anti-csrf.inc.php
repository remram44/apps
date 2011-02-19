<?php

/**
 * Crée un bout de code HTML correspondant à un élément hidden à insérer dans un
 * formulaire. Cet élément contient un token prouvant que les données
 * proviennent bel et bien d'un de nos formulaires (voir la fonction
 * check_token()).
 */
function validity_token($expire = 600)
{
    $token = md5(uniqid(rand(), true)); // whatever
    if(!isset($_SESSION['form_tokens']))
        $_SESSION['form_tokens'] = array();
    $_SESSION['form_tokens'][$token] = time() + $expire;

    return '<input type="hidden" name="form_token" value="' . $token . '"/>';
}

/**
 * Vérifie un token de formulaire.
 *
 * @param $remove Indique si le token doit être supprimé après utilisation
 * (pas possible de valider plusieurs fois le même formulaire).
 * @param $token Le token à vérifier (si null ou non-spécifié il est récupéré
 * depuis les variables POST).
 */
function check_token($remove = true, $token = null)
{
    if($token === null)
    {
        if(isset($_POST['form_token']))
            $token = $_POST['form_token'];
        else
            return false;
    }

    if(isset($_SESSION['form_tokens'][$token]))
    {
        if($_SESSION['form_tokens'][$token]  >= time())
        {
            if($remove == true)
                unset($_SESSION['form_tokens'][$token]);
            return true;
        }
        else
            unset($_SESSION['form_tokens'][$token]);
    }

    return false;
}

?>
