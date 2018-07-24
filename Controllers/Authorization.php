<?php
/**
 * Created by PhpStorm.
 * User: matri
 * Date: 19.07.2018
 * Time: 10:20
 */

namespace Authorization\Controllers;


class Authorization extends \Common\PageStandardController
{
    public function index()
    {
        $this->addView('Authorization', 'login');
    }

    public function postAction()
    {
        require __DIR__.'/../Views/loginTemplate.php';
    }
}