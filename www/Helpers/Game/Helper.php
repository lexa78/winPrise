<?php

namespace app\Helpers\Game;

use app\constants\Game as GameConstant;

/**
 * Class Helper
 * @package app\Helpers\Game
 */
class Helper
{
    /**
     * @param array $data
     * @return array
     */
    public static function getPositiveKeys(array $data): array
    {
        $result = [GameConstant::BONUS_POINT_PRISE];
        foreach ($data as $item) {
            if (isset($item['sumItems']) && isset($item['code']) && ((int)$item['sumItems'] > 0)) {
                $result[] = $item['code'];
            }
        }

        return $result;
    }
}