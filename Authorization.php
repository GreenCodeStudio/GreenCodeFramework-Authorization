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
    static private $userData = null;

    static public function login(string $username, string $password)
    {
        $user = new \User\User();
        $userDataArr = $user->getByUsername($username);
        if (!empty($userDataArr)) {
            $userData = (object)$userDataArr;
            if (static::checkPassword($userData, $password)) {
                $token = static::generateToken();
                $file = self::getUserFilePath($token, true);
                file_put_contents($file, serialize($userData));
                setcookie('login', $token, (int)(time()*2),'/');
                exit;
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

    public static function hashPassword(string $password, string $salt)
    {
        return hash('sha512', hash('sha512', $password).$salt.static::salt);
    }

    private static function generateToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }
    public static function generateSalt()
{
    return bin2hex(openssl_random_pseudo_bytes(16));
}

    private static function getUserFilePath($token, bool $mkdir = false)
    {
        $directory = __DIR__.'/../../tmp';
        if ($mkdir && !is_dir($directory))
            mkdir($directory, 0777, true);
        return $directory.'/'.$token.'user';
    }

    static public function isLogged()
    {
        if (empty($_COOKIE['login']))
            return false;
        $token = $_COOKIE['login'];
        if (self::$userData == null) {
            self::$userData = unserialize(file_get_contents(self::getUserFilePath($token)));
        }
        if (!empty(self::$userData))
            return true;
    }
}