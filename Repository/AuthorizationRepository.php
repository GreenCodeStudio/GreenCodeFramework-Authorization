<?php


namespace Authorization\Repository;


use Core\Database\MiniDB;

class AuthorizationRepository
{
    private string $host;

    public function __construct(string $host)
    {
        $this->host = $host;
    }

    public function Insert(string $token, $userData)
    {
        MiniDB::GetConnection()->setEx($this->host . '_token_' . $token, $this->GetExpirationSeconds(), serialize($userData));
    }

    public function Update(string $token, $userData)
    {
        MiniDB::GetConnection()->set($this->host . '_token_' . $token, serialize($userData));
    }

    protected function GetExpirationSeconds()
    {
        return 60 * 24 * 3600;
    }

    public function Get(string $token)
    {
        $connection = MiniDB::GetConnection();
        $dataSerialized = $connection->get($this->host . '_token_' . $token);

        if ($dataSerialized === false)
            return null;
        else {
            $connection->expire($this->host . '_token_' . $token, $this->GetExpirationSeconds());
            return unserialize($dataSerialized);
        }
    }

    public function Delete(string $token)
    {
        MiniDB::GetConnection()->del($this->host . '_token_' . $token);
    }

    public function GetAll()
    {
        $connection = MiniDB::GetConnection();
        $keys = $connection->keys($this->host . '_token_*');
        foreach ($keys as $key) {
            yield (object)['id' => unserialize($connection->get($key))->id, 'token' => str_replace($this->host . '_token_', '', $key)];
        }
    }
}
