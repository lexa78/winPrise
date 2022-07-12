<?php

namespace app\Services\Game;

/**
 * Interface HandlerInterface
 * @package app\Services\Game
 */
interface HandlerInterface
{
    public function setNext(HandlerInterface $handler): HandlerInterface;

    public function handle(string $request): ?string;
}