<?php 

namespace App\Interfaces;

interface iShip
{
    const RIGHT = 'right';
    const DOWN = 'down';

    public function getX(): int;

    public function getY(): int;

    public function getAlignment(): string;

    public function hit(): void;

    public function getName(): string;

    public function getSize(): int;

    public function horizontal(): bool;

    public function dead(): bool;
}