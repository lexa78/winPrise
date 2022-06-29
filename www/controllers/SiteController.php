<?php
declare(strict_types=1);

namespace app\controllers;

use app\core\Controller;

/**
 * Class SiteController
 * @package app\controllers
 */
class SiteController extends Controller
{
    /**
     * @return string
     */
    public function home(): string
    {
        $params = [
            'name' => 'Praga',
        ];

        return $this->render('home', $params);
    }
}