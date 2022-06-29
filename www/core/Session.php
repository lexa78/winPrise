<?php
declare(strict_types=1);

namespace app\core;

use app\constants\Session as SessionConstant;

use function session_start;

/**
 * Class Session
 * @package app\core
 */
class Session
{
    /**
     * Session constructor.
     */
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

    /**
     * @param string $key
     * @param string $message
     */
    public function setFlash(string $key, string $message): void
    {
        $_SESSION[SessionConstant::FLASH_MESSAGES_KEYS][$key] = [
            'remove' => false,
            'value' => $message,
        ];
    }

    /**
     * @param string $key
     * @return string|null
     */
    public function getFlash(string $key): ?string
    {
        return $_SESSION[SessionConstant::FLASH_MESSAGES_KEYS][$key]['value'] ?? null;
    }

    /**
     * Session destructor.
     */
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

    /**
     * @param string $key
     * @return mixed|null
     */
    public function get(string $key)
    {
        return $_SESSION[$key] ?? null;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     */
    public function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }
}