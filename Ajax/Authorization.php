<?php

namespace Authorization\Ajax;


use Authorization\Exceptions\BadAuthorizationException;
use Common\PageAjaxController;

class Authorization extends PageAjaxController
{
    /**
     * @throws BadAuthorizationException
     */
    public function login(string $username, string $password)
    {
        \Authorization\Authorization::login($username, $password);
    }

    public function logout()
    {
        \Authorization\Authorization::logout();
    }

    public function hasPermission(string $method)
    {
        return true;
    }
}