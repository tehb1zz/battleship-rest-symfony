<?php

namespace App\Models\Ships;
use App\Traits\ShipCoordinates;




final class Carrier extends aShip 
{
    public function getName(): string   { return 'carrier'; }
    public function getSize(): int      { return 5; }

    use ShipCoordinates; 
}