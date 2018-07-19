<?php
/**
 * Created by PhpStorm.
 * User: matri
 * Date: 19.07.2018
 * Time: 10:21
 */

namespace Authorization;


class Authorization
{
    const salt = 'l(vu$bL2';

    static public function login(string $username, string $password)
    {
        $user = new \User\User();
        $userDataArr = $user->getByUsername($username);
        if (!empty($userDataArr)) {
            $userData = (object)$userDataArr;
            if (static::checkPassword($userData, $password)) {
                $token = static::generateToken();
                $directory = __DIR__.'/../../tmp';
                mkdir($directory, 0777, true);
                $file = $directory.'/'.$token.'user';
                file_put_contents($file, serialize($userData));
                setcookie('login', $token);
            } else {
                throw new Exceptions\BadAuthorizationException();

            }
        } else {
            throw new Exceptions\BadAuthorizationException();
        }
    }

    private static function checkPassword($userData, $password)
    {
        return $userData->password === self::hashPassword($password, $userData->salt);
    }

    private static function hashPassword(string $password, string $salt)
    {
        return hash('sha512', hash('sha512', $password).$salt.static::salt);
    }

    private static function generateToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }
}