<?php
/**
 * Created by PhpStorm.
 * User: matri
 * Date: 19.07.2018
 * Time: 13:40
 */

namespace Authorization\Ajax;


class Authorization extends \Core\AjaxController
{
    public function login(string $username, string $password)
    {
        $logined = \Authorization\Authorization::login($username, $password);
    }

    public function hasPermission()
    {
        return true;
    }
}