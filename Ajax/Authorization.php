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
    /**
     * @param string $username
     * @param string $password
     * @throws \Authorization\Exceptions\BadAuthorizationException
     */
    public function login(string $username, string $password)
    {
        \Authorization\Authorization::login($username, $password);
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