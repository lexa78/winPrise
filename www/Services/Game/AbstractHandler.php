<?php

namespace app\Services\Game;

/**
 * Class AbstractHandler
 * @package app\Services\Game
 */
abstract class AbstractHandler implements HandlerInterface
{
    /**
     * @var HandlerInterface
     */
    private HandlerInterface $nextHandler;

    /**
     * @param HandlerInterface $handler
     * @return HandlerInterface
     */
    public function setNext(HandlerInterface $handler): HandlerInterface
    {
        $this->nextHandler = $handler;

        return $handler;
    }

    /**
     * @param string $request
     * @return string|null
     */
    public function handle(string $request): ?string
    {
        if (!empty($this->nextHandler)) {
            return $this->nextHandler->handle($request);
        }

        return null;
    }
}