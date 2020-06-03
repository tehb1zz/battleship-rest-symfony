<?php 

namespace App\Models\Grid;

use App\Interfaces\iShip;
use App\Models\Grid\GridLogic;
use App\Models\Ships\Carrier;
use App\Models\Ships\Cruiser;
use App\Models\Ships\Destroyer;
use App\Models\Ships\Submarine;
use App\Models\Ships\Battleship;
use Exception;

class AiGrid 
{
    /**
     * @Assert\Type("integer")
     * @Assert\Range(min = "1", max = "10")
     */
    protected $x;

    /**
     * @Assert\Type("integer")
     * @Assert\Range(min = "1", max = "10")
     */
    protected $y;
    

   private static $ships = [Carrier::class,Battleship::class,Cruiser::class,Submarine::class,Destroyer::class];

    public static function construct(): GridLogic
    {
        $aigrid = new GridLogic();

        while(!$aigrid->allShipsPlaced())
        {
            foreach(self::$ships as $shipType)
            {
                $placementPossible = false;
                while($placementPossible === false)
                {
                    $aiShip = self::shipGenerator($shipType);

                    try 
                    {
                        $placementPossible = $aigrid->shipPlacement($aiShip);
                    } catch (\Exception $e) { 
                        $placementPossible = false;
                    }
                }
            }
        }  
        if(!$aigrid->allShipsPlaced()) 
        {
            throw new Exception('Computer is not ready');
        }  
        return $aigrid;
    }

    public static function shipGenerator($shipType) : iShip 
    {

    /**
     * @Assert\Type("string")
     * @Assert\Choice({"right", "down"})
     */
    $alignment = ['right','down'];

    return new $shipType(random_int(1,10),random_int(1,10),array_rand($alignment));
    }



}