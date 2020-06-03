<?php

namespace App\Handler;

use App\Models\Grid\GridLogic;
use App\Interfaces\iShip;
use App\Handler\Statehandler;
use App\Models\Ships\Carrier;
use App\Models\Ships\Battleship;
use App\Models\Ships\Cruiser;
use App\Models\Ships\Submarine;
use App\Models\Ships\Destroyer;
use Exception;

class Gamehandler
{

    private $statehandler;
    /*
    * @var GridLogic 
    */
    private $owngrid;
    /*
    * @var GridLogic 
    */
    private $aigrid;

    /*
    * @var iShip; 
    */
    private $ship;

    /*
    * @var bool 
    */
    private $didaihit = null;

    /*
    * @var array 
    */
    private $savedaicoords = [];

    public function __construct(Statehandler $statehandler)
    {
        $this->statehandler = $statehandler;
        $this->owngrid = $statehandler->getOwnGrid();
        $this->aigrid = $statehandler->getAiGrid();
    }

    public function placeOwnShip(iShip $ship) 
    {
        $this->owngrid->shipPlacement($ship);
        $this->statehandler->saveOwnGrid($this->owngrid);
    }

    public function placeShot($x,$y) 
    {
     /*   if(!$this->getOwnGrid()->allShipsPlaced())
        {
            throw new Exception('All Ships must be placed before shooting');
        }
*/
        $ownshot = $this->aigrid->fireOnGrid($x,$y);
        $ownhit = $ownshot instanceof iShip;

        list($x,$y) = $this->underFire();
        $aihit = $this->owngrid->fireOnGrid($x,$y);
        $this->didaihit = $aihit instanceof iShip? true : false;

        $this->statehandler->saveOwnGrid($this->owngrid);
        $this->statehandler->saveAiGrid($this->aigrid);

        return [
            'owndata' => [
                'gameover'  => !$this->owngrid->gameOver(),
                'wracked'   => $ownhit && $ownshot->dead(),
                'hit'       => $ownhit,
                "links" =>
                [
                    'owngrid' =>
                    [ 
                        'href' => 'http://' . $_SERVER["HTTP_HOST"] . '/api/owngrid/',
                        'method' => 'GET'
                    ]
                ]],
            'aidata' =>[
                'gameover'  => !$this->aigrid->gameOver(),
                'wracked'   => ($this->didaihit && $aihit->dead()),
                'hit'       => $this->didaihit,
                'aix'       => $x,
                'aiy'       => $y,
                "links" =>
                [
                    'aigrid' =>
                    [ 
                        'href' => 'http://' . $_SERVER["HTTP_HOST"] . '/api/aigrid/',
                        'method' => 'GET'
                    ]
                ]
            ] 
        ];
    }

    public function underFire()
    {
        if(!$this->didaihit)
        {
            $x = random_int(1,10);
            $y = random_int(1,10);
            $this->savedaicoords = [$x,$y];
            return [$x,$y];
        }
        
        // TODO :: AI LOGIC :: NORTH WEST SOUTH EAST 
       $lastshoot = $this->savedaicoords;
       return [$lastshoot[0]--,$lastshoot[1]];
    }

    public function getOwnGrid() : GridLogic
    {
        return $this->owngrid;
    }

    public function getAiGrid() : GridLogic
    {
        return $this->aigrid;
    }

    public function getShipModelByName($name)
    {
        $ships = [
            'destroyer' => Destroyer::class,
            'submarine' => Submarine::class,
            'cruiser' => Cruiser::class,
            'battleship' => Battleship::class,
            'carrier' => Carrier::class,
        ];

        return $ships[$name];
    }
}