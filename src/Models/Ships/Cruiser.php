<?php

namespace App\Models\Ships;
use App\Traits\ShipCoordinates;




final class Cruiser extends aShip 
{
    public function getName(): string   { return 'cruiser'; }
    public function getSize(): int      { return 3; }

    use ShipCoordinates; 
}