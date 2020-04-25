<?php


namespace Authorization\Repository;


class AuthorizationRepository
{

    public function Insert(string $token, $userData)
    {
        \Core\MiniDB::GetConnection()->set('token_'.$token, serialize($userData));
    }

    public function Get($token)
    {
       $dataSerialized= \Core\MiniDB::GetConnection()->get('token_'.$token);
       if($dataSerialized===false)
           return null;
       else
           return unserialize($dataSerialized);
    }

    public function Delete($token)
    {
        $dataSerialized= \Core\MiniDB::GetConnection()->del('token_'.$token);
    }
}