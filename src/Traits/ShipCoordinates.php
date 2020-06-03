<?php 

namespace App\Traits;

trait ShipCoordinates
{
    public function getX(): int
    {
        return $this->x;
    }

    
    public function getY(): int
    {
        return $this->y;
    }
    
    public function getAlignment(): string
    {
        return $this->alignment;
    }
}
