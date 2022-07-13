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
     * @param array $result
     * @return array
     */
    public static function getPositiveKeys(array $data, $result = []): array
    {
        foreach ($data as $item) {
            if (isset($item['sumItems']) && isset($item['code']) && ((int)$item['sumItems'] > 0)) {
                $result[] = $item['code'];
            }
        }

        return $result;
    }

    /**
     * @param array $data
     * @param string $keyField
     * @return array
     */
    public static function makeOneDimensionalArray(array $data, string $keyField): array
    {
        $result = [];
        foreach ($data as $item) {
            if (isset($item[$keyField])) {
                $result[$item[$keyField]] = $item;
            }
        }

        return $result;
    }
}