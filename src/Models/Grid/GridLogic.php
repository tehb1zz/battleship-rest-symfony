<?php 

namespace App\Models\Grid;

use App\Interfaces\iShip;
use Exception;
use InvalidArgumentException;

class GridLogic
{

/**
* @return GridLogic[]
*/
protected $grid = [];


/**
* @return iShip[]
*/
protected $ships = [];

public function __construct() 
{

    $this->grid = array_fill(1,10,array_fill(1,10,NULL));
 
}

public function fireOnGrid($x,$y): ?iShip
{
    $ship = $this->checkForShipOnCoord($x,$y);

    if ($ship instanceof iShip)
    {
        $ship->hit();
        return $ship;
    }
    return null;
}

public function checkForShipOnCoord(int $x,int $y): ?iShip
{
    $shipOnGrid = $this->grid[$x][$y];

    if($shipOnGrid)
    {
        return $this->ships[$shipOnGrid];
    }
    return null;
}

public function shipPlacement(iShip $ship): bool
{
    if(!$this->placementConstraints($ship))
    {
        throw new InvalidArgumentException();
    }

    $x = $ship->getX();
    $y = $ship->getY();

    for($i = $y; $i <= $ship->getSize() +$y -1; $i++)
    {
        if($ship->horizontal())
        {
            $ship->horizontal() ? $this->grid[$x][$i] = $ship->getName() : $this->grid[$i][$y] = $ship->getName();
        }
    }

    $this->ships[$ship->getName()] = $ship;

    return true;
}



public function placementConstraints(iShip $ship) : bool
{

    $x = $ship->getX();
    $y = $ship->getY();

    if($this->allShipsPlaced()) return false;
    if(array_key_exists($ship->getName(), $this->ships)) throw new Exception('Ship with this name already placed');
    if(!$this->inbounds($ship)) throw new Exception('Ship was not placed inbounds');
    if($this->checkForShipOnCoord($ship->getX(),$ship->getY()) instanceof iShip) throw new Exception('Coordinates already taken');

    for($i=0; $i < $ship->getSize(); $i++)
    {
        ($ship->horizontal())? $y =+ $i : $x =+ $i;
    }

    if($this->checkForShipOnCoord($x,$y)) throw new Exception('Coordinates already taken');

    return true;
}

public function allShipsPlaced() : bool
{
    return count($this->ships) === 5;
}

public function inbounds(iShip $ship) : bool
{
    if($ship->horizontal())
    {
        return $ship->getY() + $ship->getSize() <= 10;
    } else {
        return $ship->getX() + $ship->getSize() <= 10;
    }
}

public function getGridMatrix()
{
    return $this->grid;
}

public function gameOver() : bool
{
    foreach($this->ships as $ship)
    {
        if($ship->dead())
        {
            return false;
        }
    }
    return true;
}



}