<?php

// Technique et bouts de code emprunt�s � l'article "Password Hashing" du PHP Security Consortium
// http://phpsec.org/articles/2005/password-hashing.html

define('SALT_LENGTH', 9);

// Hash le mot de passe, i.e. rend une cha�ne qui ne permet pas de remonter au
// mot de passe original mais permet de le v�rifier
function password_encrypt($password)
{
    $salt = substr(md5(uniqid(rand(), true)), 0, SALT_LENGTH);
    return $salt . sha1($salt . $password);
}

// V�rifie un mot de passe, en fonction d'un hash calcul� via password_encrypt
function password_verify($hash, $password)
{
    $salt = substr($hash, 0, SALT_LENGTH);
    $other_hash = $salt . sha1($salt . $password);
    return $other_hash == $hash;
}

?>
