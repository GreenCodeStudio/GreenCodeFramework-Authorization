<?php
/**
 * Created by PhpStorm.
 * User: matri
 * Date: 19.07.2018
 * Time: 13:40
 */

namespace Authorization\Ajax;


class Authorization extends \Common\PageAjaxController
{
    public function login(string $username, string $password)
    {
        $logged = \Authorization\Authorization::login($username, $password);
        return $logged;
    }

    public function logout()
    {
        \Authorization\Authorization::logout();
    }

    public function hasPermission()
    {
        return true;
    }
}