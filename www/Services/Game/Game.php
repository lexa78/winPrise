<?php

namespace app\Services\Game;

use app\constants\Game as GameConstant;
use app\Helpers\Game\Helper as GameHelper;
use app\models\Storage as StorageModel;
use app\constants\Storage as StorageConstant;

use function rand;
use function count;
/**
 * Class Play
 * @package app\Services\Game
 */
class Game
{
    /**
     * @return HandlerInterface
     */
    protected function getHandler(): HandlerInterface
    {
        $moneyHandler = new MoneyHandler();
        $moneyHandler->setNext(new ValuableThingHandler())->setNext(new BonusPointHandler());
        return $moneyHandler;
    }

    /**
     * @return string
     */
    protected function getRandomPriseType(): string
    {
        $leftOfPrisesTypes = (new StorageModel())->findAllLeftGroupBy(StorageConstant::GROUP_BY_PRISES_TYPES);
        $allPrisesTypes = GameHelper::getPositiveKeys($leftOfPrisesTypes);

        return $allPrisesTypes[rand(0, count($allPrisesTypes) - 1)];
    }

    public function letsPlay()
    {
        var_dump(($this->getHandler())->handle('money'));exit;
        return ($this->getHandler())->handle($this->getRandomPriseType());
    }
}