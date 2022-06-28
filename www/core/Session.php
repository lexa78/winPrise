<?php


namespace app\core;

use app\constants\Session as SessionConstant;

class Session
{
    public function __construct()
    {
        session_start();
        $flashMessages = $_SESSION[SessionConstant::FLASH_MESSAGES_KEYS] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            $flashMessage['remove'] = true;
        }

        $_SESSION[SessionConstant::FLASH_MESSAGES_KEYS] = $flashMessages;
        unset($flashMessage);
    }

    public function setFlash($key, $message)
    {
        $_SESSION[SessionConstant::FLASH_MESSAGES_KEYS][$key] = [
            'remove' => false,
            'value' => $message,
        ];
    }

    public function getFlash($key)
    {
        return $_SESSION[SessionConstant::FLASH_MESSAGES_KEYS][$key]['value'] ?? null;
    }

    public function __destruct()
    {
        $flashMessages = $_SESSION[SessionConstant::FLASH_MESSAGES_KEYS] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }

        $_SESSION[SessionConstant::FLASH_MESSAGES_KEYS] = $flashMessages;
        unset($flashMessage);
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }
}