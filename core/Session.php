<?php

class Session
{
    public static function start(): void
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function isLoggedIn(): bool
    {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        return true;
    }
}
