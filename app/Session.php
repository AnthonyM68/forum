<?php

namespace App;

class Session
{

    private static $categories = ['error', 'warning', 'success'];

    /* ROLE_USER ROLE_CONTRIBUTOR 
    
       ROLE_AUTHOR ROLE_EDITOR ROLE_ADMIN

    /**
     *   ajoute un message en session, dans la catégorie $categ
     */
    public static function addFlash($categ, $msg)
    {
        $_SESSION[$categ] = $msg;
    }
    public static function ifExistFlash($category)
    {
        return isset($_SESSION[$category]) ? true : false;
    }
    /**
     *   renvoie un message de la catégorie $categ, s'il y en a !
     */
    public static function getFlash($categ)
    {

        if (isset($_SESSION[$categ])) {
            $msg = $_SESSION[$categ];
            unset($_SESSION[$categ]);
        } else
            $msg = "";

        return $msg;
    }

    public static function generateTokenUnique()
    {
        $token = bin2hex(random_bytes(32));
        if (!isset($_SESSION["user"])) {
            $_SESSION["token"] = $token;
        } 
    }

    /**
     *   met un user dans la session (pour le maintenir connecté)
     */
    public static function setUser($user)
    {
        $_SESSION["user"] = $user;
    }

    public static function getUser()
    {
        return (isset($_SESSION['user'])) ? $_SESSION['user'] : false;
    }

    public static function isAdmin()
    {
        // var_dump(self::getUser());

        // attention de bien définir la méthode "hasRole" dans l'entité User 
        // en fonction de la façon dont sont gérés les rôles en base de données
        if (self::getUser() && self::getUser()->hasRole("ROLE_ADMIN")) {
            return true;
        }
        return false;
    }
}
