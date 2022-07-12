<?php

namespace app\Services\Game;

use app\constants\Game as GameConstant;
use app\constants\Storage as StorageConstant;
use app\models\Storage as StorageModel;

/**
 * Class MoneyHandler
 * @package app\Services\Game
 */
class MoneyHandler extends AbstractHandler
{
    /**
     * @param string $request
     * @return string|null
     */
    public function handle(string $request): ?string
    {
        if ($request === GameConstant::MONEY_PRISE) {
            $leftOfEachMoneyType = (new StorageModel())->findAllLeftGroupBy(
                StorageConstant::GROUP_BY_PRISES_NAMES,
                StorageConstant::getPrefixes()[StorageConstant::GROUP_BY_PRISES_TYPES],
                [
                    StorageConstant::TABLE_FIELD_CODE => GameConstant::MONEY_PRISE
                ],
                [StorageConstant::TABLE_FIELD_NAME]
            );
            echo "<pre>";var_dump($leftOfEachMoneyType);exit;
            return "Monkey: I'll eat the " . $request . ".\n";
        } else {
            return parent::handle($request);
        }
    }
}