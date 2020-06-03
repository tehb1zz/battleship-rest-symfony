<?php

namespace App\Models\Ships;
use App\Traits\ShipCoordinates;




final class Submarine extends aShip 
{
    public function getName(): string   { return 'submarine'; }
    public function getSize(): int      { return 3; }

    use ShipCoordinates; 
}