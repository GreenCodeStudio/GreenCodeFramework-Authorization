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
    static private $userDataReaded = false;

    /**
     * @param string $username
     * @param string $password
     * @throws Exceptions\BadAuthorizationException
     */
    static public function login(string $username, string $password)
    {
        $userDB = new \User\DB\UserDB();
        $userData = $userDB->getByUsername($username, true);
        if (!empty($userData)) {
            if (static::checkPassword($userData, $password)) {
                unset($userData->salt);
                unset($userData->password);
                $token = static::generateToken();
                $file = static::getUserFilePath($token, true);
                $userData->permissions = new Permissions($userData->id);
                file_put_contents($file, serialize($userData));
                setcookie('login', $token, (int)(time() * 2), '/');
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

    private static function getUserFilePath($token, bool $mkdir = false)
    {
        $directory = __DIR__.'/../../tmp';
        if ($mkdir && !is_dir($directory))
            mkdir($directory, 0777, true);
        return $directory.'/'.$token.'user';
    }

    public static function generateSalt()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }

    static public function isLogged()
    {
        if (!empty(self::getUserData()))
            return true;
        else return false;
    }

    static public function getUserData()
    {
        if (empty($_COOKIE['login']))
            return null;

        $token = $_COOKIE['login'];
        if (!self::$userDataReaded) {
            $path = self::getUserFilePath($token);
            if (file_exists($path))
                self::$userData = unserialize(file_get_contents($path));
            else
                self::$userData = null;
            self::$userDataReaded = true;
        }
        return self::$userData;
    }

    public static function logout()
    {
        if (!empty($_COOKIE['login'])) {
            $token = $_COOKIE['login'];
            unlink(self::getUserFilePath($token));
        }
        setcookie('login', NULL, 0, '/');
    }
}