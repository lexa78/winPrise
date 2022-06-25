<?php

namespace app\core;

class Migration
{
    protected Database $db;

    public function __construct()
    {
        $this->db = Application::$app->db;
    }
}