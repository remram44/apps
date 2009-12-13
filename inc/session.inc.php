<?php

class Utilisateur {

    var $pseudo;
    var $template;

    function template()
    { return $this->template; }

    function __construct()
    {
        /*
        session_start();
        if(isset($_SESSION['pseudo']))
        {
            $this->pseudo = $_SESSION['pseudo'];
            $this->template = $_SESSION['template'];
        }
        else
        {
            if(isset($_COOKIE['remember']))
            {
                $infos = explode(':', $_COOKIE['remember']);
                if(count($infos) == 2)
                {
                    $login = $infos[0];
                    $passwd = $infos[1];
                }
            }
            else if(isset($_POST['pseudo']))
            {
                $login = $_POST['pseudo'];
                $passwd = $_POST['passwd'];
            }
            if(isset($login))
            {
                // TODO
            }
        }
        */
        $this->pseudo = "Moi";
        $this->template = "default";
    }

}

?>
