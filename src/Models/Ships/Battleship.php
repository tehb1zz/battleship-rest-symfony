<?php

namespace App\Models\Ships;
use App\Traits\ShipCoordinates;




final class Battleship extends aShip 
{
    public function getName(): string   { return 'battleship'; }
    public function getSize(): int      { return 4;  }

    use ShipCoordinates; 
}