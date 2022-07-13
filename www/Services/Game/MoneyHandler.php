<?php

namespace app\Services\Game;

use app\constants\Game as GameConstant;
use app\constants\Storage as StorageConstant;
use app\core\exception\RuntimeException;
use app\Helpers\Game\Helper as GameHelper;
use app\models\Limit;
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
            $moneyTypesWithPositiveLeft = GameHelper::getPositiveKeys($leftOfEachMoneyType);

            $limits = (new Limit())->findMaxLimitsFor(
                [StorageConstant::TABLE_FIELD_CODE => $moneyTypesWithPositiveLeft]
            );
            $limits = GameHelper::makeOneDimensionalArray(
                $limits,
                StorageConstant::TABLE_FIELD_CODE
            );

            $currencyForWin = $moneyTypesWithPositiveLeft[rand(0, count($moneyTypesWithPositiveLeft) - 1)];
            $currencyForWinInfo = array_filter($leftOfEachMoneyType, function ($item) use ($currencyForWin) {
                return (isset($item[StorageConstant::TABLE_FIELD_CODE])
                    && ($item[StorageConstant::TABLE_FIELD_CODE] === $currencyForWin));
            });
            if (empty($currencyForWinInfo)) {
                throw new RuntimeException(
                    sprintf('Info about currency %s was not found', $currencyForWin),
                    500
                );
            }
            $currencyForWinInfo = array_shift($currencyForWinInfo);
            //если остаток нужной валюты меньше, чем лимит, устанавливаем лимит, равный остатку, чтобы не
            //выиграли больше, чем есть в остатке
            if ($limits[$currencyForWin][StorageConstant::TABLE_FIELD_MAX_VALUE]
                    > $currencyForWinInfo[StorageConstant::TABLE_FIELD_SUM_ITEMS]
            ) {
                $limits[$currencyForWin][StorageConstant::TABLE_FIELD_MAX_VALUE]
                    = $currencyForWinInfo[StorageConstant::TABLE_FIELD_SUM_ITEMS];
            }

            $amountOfCurrencyForWin = rand(
                $limits[$currencyForWin][StorageConstant::TABLE_FIELD_MIN_VALUE],
                $limits[$currencyForWin][StorageConstant::TABLE_FIELD_MAX_VALUE]
            );
            var_dump($currencyForWin);
            var_dump($amountOfCurrencyForWin);exit;
        } else {
            return parent::handle($request);
        }
    }
}