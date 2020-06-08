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

/**
* @return string
*/
public static $errormessage = '';



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
        return false;
    }

    $x = $ship->getX();
    $y = $ship->getY();

    for($i = $y; $i <= $ship->getSize() +$y - 1; $i++)
    {
        if($ship->horizontal())
        {
            $this->grid[$x][$i] = $ship->getName(); 
        } else {
            $this->grid[$i][$y] = $ship->getName();
        }
    }

    $this->ships[$ship->getName()] = $ship;

    return true;
}



public function placementConstraints(iShip $ship) : bool
{

    if($this->allShipsPlaced()) return false;
    if(array_key_exists($ship->getName(), $this->ships)) 
    {
        self::$errormessage = 'Ship with the name: ' . $ship->getName() .' already placed'; 
        return false;
    }
    
    if(!$this->inbounds($ship)) 
    {
        self::$errormessage = 'Ship was not placed inbounds'; 
        return false;
    }
    if($this->checkForShipOnCoord($ship->getX(),$ship->getY()) instanceof iShip) 
    {
        self::$errormessage = 'Coordinates already taken'; 
        return false;
    }



    for($i=0; $i < $ship->getSize(); $i++)
    {
        $x = $ship->getX();
        $y = $ship->getY();
        ($ship->horizontal())? $y += $i : $x += $i;
    

        if($this->checkForShipOnCoord($x,$y) != NULL)
        {
            self::$errormessage = 'Coordinates already taken'; 
            return false;
        }
        echo $x . "<br>";
        echo $y . "<br>";
    }

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