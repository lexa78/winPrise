<?php

namespace app\Services\Game;

use app\constants\Game;

/**
 * Class BonusPointHandler
 * @package app\Services\Game
 */
class BonusPointHandler extends AbstractHandler
{
    /**
     * @param string $request
     * @return string|null
     */
    public function handle(string $request): ?string
    {
        if ($request === Game::BONUS_POINT_PRISE) {
            return "Monkey: I'll eat the " . $request . ".\n";
        } else {
            return parent::handle($request);
        }
    }
}