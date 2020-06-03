<?php

namespace App\Models\Ships;
use App\Traits\ShipCoordinates;




final class Destroyer extends aShip 
{
    public function getName(): string   { return 'destroyer';}
    public function getSize(): int      { return 2; }

    use ShipCoordinates; 
}