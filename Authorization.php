<?php
/**
 * Created by PhpStorm.
 * User: matri
 * Date: 19.07.2018
 * Time: 10:21
 */

namespace Authorization;


use _PHPStan_27631a2e0\Nette\Neon\Exception;
use Authorization\Exceptions\BadAuthorizationException;
use Authorization\Exceptions\ExpiredTokenException;
use Authorization\Repository\AuthorizationRepository;
use User\Repository\TokenRepository;
use User\Repository\UserRepository;
use User\User;
use User\UserPreferences;

class Authorization
{
    const salt = 'l(vu$bL2';
    static private $userData = null;
    static private $isUserDataRead = false;

    /**
     * @throws Exceptions\BadAuthorizationException
     */
    static public function login(string $username, string $password)
    {
        $userRepository = new UserRepository();
        $userData = $userRepository->getByUsername($username, true);
        if (!empty($userData)) {
            if (self::checkPassword($userData, $password)) {
                self::executeLogin($userData);
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

    public static function executeLogin($userData): void
    {
        unset($userData->salt);
        unset($userData->password);
        $token = static::generateToken();
        $userData->permissions = new Permissions($userData->id);
        $userData->preferences = (new UserPreferences())->getByUserIdShort($userData->id);
        (new AuthorizationRepository($_ENV['host'] ?? $_SERVER['HTTP_HOST']))->Insert($token, $userData);
        setcookie('login', $token, (int)(time() * 2), '/');
    }

    private static function generateToken()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }

    /**
     * @throws BadAuthorizationException
     * @throws ExpiredTokenException
     */
    static public function loginByToken(string $token)
    {
        $tokenRepository = new TokenRepository();
        $item = $tokenRepository->getTokenWithUser($token);
        if (empty($item) || $item->type != 'login')
            throw new BadAuthorizationException();
        if (!empty($item->expire) && strtotime($item->expire) < time())
            throw new ExpiredTokenException();
        self::executeLogin($item->user);
    }

    public static function generateSalt()
    {
        return bin2hex(openssl_random_pseudo_bytes(16));
    }

    static public function isLogged()
    {
        return !empty(self::getUserData());
    }

    static public function getUserData()
    {
        if (!self::$isUserDataRead) {
            if (empty($_COOKIE['login']))
                return null;

            $token = $_COOKIE['login'];
            self::$userData = (new AuthorizationRepository($_ENV['host'] ?? $_SERVER['HTTP_HOST']))->Get($token);
            self::$isUserDataRead = true;
        }
        return self::$userData;
    }

    static public function getUserId()
    {
        return self::getUserData()->id;
    }

    public static function logout()
    {
        if (!empty($_COOKIE['login'])) {
            $token = $_COOKIE['login'];
            (new AuthorizationRepository($_ENV['host'] ?? $_SERVER['HTTP_HOST']))->Delete($token);
        }
        self::$userData = null;
        self::$isUserDataRead = true;
        setcookie('login', '', 0, '/');
    }

    public static function resetPassword(string $mailAddress)
    {
        $mail = new \Core\MailSender();
        $mail->Subject = 'Resetowanie hasła';
        $user= (new UserRepository())->getByUsername($mailAddress);
        if(empty($user))
            return;
        $mail->AddAddress($user->mail);
        $code=rand(100000,999999);
        $expiration = new \DateTime();
        $expiration->add(new \DateInterval('PT1H'));
        (new UserRepository())->setResetPasswordCode($user->id, $code, $expiration);
        $link = 'http://'.$_SERVER['HTTP_HOST'].'/Authorization/resetPassword2/'.urlencode($user->mail).'/'.$code;
        $mail->Body = 'Kod do resetowania hasła: <strong>'.$code.'</strong><br><br>Alternatywnie możesz kliknąć w link <a href="'.htmlspecialchars($link).'">'.htmlspecialchars($link).'</a>';
        $mail->Send();
    }
    public static function resetPassword2(string $mailAddress, int $code, string $newPassword){

        $user= (new UserRepository())->getByUsernameForPasswordReset($mailAddress);
        if(empty($user))
            throw new Exception();
        if($user->reset_password_code !== $code)
            throw new Exception();
        if($user->reset_password_expire <(new \DateTime())->format('Y-m-d H:i:s'))
            throw new Exception();

        (new User())->changePassword($user->id, $newPassword);
}


    public function refreshUserData()
    {
        $userRepository = new UserRepository();
        $authorizationRepository = new AuthorizationRepository($_ENV['host'] ?? $_SERVER['HTTP_HOST']);
        $users = $authorizationRepository->GetAll();
        foreach ($users as $user) {
            $userData = $userRepository->getById($user->id, true);
            $userData->permissions = new Permissions($user->id);
            $userData->preferences = (new UserPreferences())->getByUserIdShort($user->id);
            $authorizationRepository->Update($user->token, $userData);
            dump($user);
        }
    }
}
