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
    public function resetPassword(string $username)
    {
        \Authorization\Authorization::resetPassword($username);
    }

    public function resetPassword2(string $username, int $code, string $password)
    {
        \Authorization\Authorization::resetPassword2($username, $code, $password);
    }

    public function hasPermission(string $methodName)
    {
        return true;
    }
}
