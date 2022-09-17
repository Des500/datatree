<?php

class NotifMessage
{

    public static function setStatus ($status = 'success', $content = '') {
        if(($status!=='success')&&($status!=='error'))
            return false;
        $_SESSION[$status] = $content;
        return true;
    }
    public static function getStatus ($status = 'success', $content = '') {
        if(($status!=='success')&&($status!=='error'))
            return '';
        $content = $_SESSION[$status];
        unset($_SESSION[$status]);
        return $content;
    }
}