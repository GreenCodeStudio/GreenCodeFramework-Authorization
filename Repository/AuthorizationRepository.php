<?php


namespace Authorization\Repository;


use Core\Database\MiniDB;

class AuthorizationRepository
{
    public function Insert(string $token, $userData)
    {
        MiniDB::GetConnection()->setEx('token_'.$token, $this->GetExpirationSeconds(), serialize($userData));
    }

    protected function GetExpirationSeconds()
    {
        return 60 * 24 * 3600;
    }

    public function Get(string $token)
    {
        $connection = MiniDB::GetConnection();
        $dataSerialized = $connection->get('token_'.$token);

        if ($dataSerialized === false)
            return null;
        else {
            $connection->expire('token_'.$token, $this->GetExpirationSeconds());
            return unserialize($dataSerialized);
        }
    }

    public function Delete(string $token)
    {
        MiniDB::GetConnection()->del('token_'.$token);
    }
}