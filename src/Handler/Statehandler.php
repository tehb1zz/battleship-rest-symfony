<?php


namespace App\Handler;

use App\Models\Grid\GridLogic;
use App\Models\Grid\AiGrid;

class Statehandler
{
     /**
     * @var string
     */
    protected  $ownGridPath;

     /**
     * @var string
     */
    protected  $aiGridPath;

    /**
     * @var AiGrid
     */
    private $aiGrid;


    public function __construct(AiGrid $aiGrid)
    {
        $this->aiGrid = $aiGrid;
        $this->ownGridPath = __DIR__ . '\Repo\owngrid';
        $this->aiGridPath =   __DIR__ . '\Repo\computergrid';
    }

    public function renew()
    {
        if(file_exists($this->ownGridPath || $this->aiGridPath))
        {
            unlink($this->ownGridPath && $this->aiGridPath);
        }
    }

    public function getOwnGrid(): GridLogic 
    {
        $oldGrid= $this->getUnserializedGrid($this->ownGridPath);
        if(!$oldGrid)
        {
            $newOwnGrid = new GridLogic(); 
            $this->saveSerializedGrid($newOwnGrid,$this->ownGridPath);
            return $newOwnGrid;
        }
        return $oldGrid;
    }

    public function getAiGrid(): GridLogic
    {
        $oldAiGrid= $this->getUnserializedGrid($this->aiGridPath);
        if(!$oldAiGrid)
        {
            $newAiGrid = $this->aiGrid::construct(); 
            $this->saveSerializedGrid($newAiGrid,$this->aiGridPath);
            return $newAiGrid;
        }
        return $oldAiGrid;
    }

    public function saveOwnGrid(GridLogic $grid) 
    {
        $this->saveSerializedGrid($grid,$this->ownGridPath);
    }

    public function saveAiGrid(GridLogic $grid) 
    {
        $this->saveSerializedGrid($grid,$this->aiGridPath);
    }

    public function getUnserializedGrid($path)
    {
        if(file_exists($path))return unserialize(file_get_contents($path));
        else return null;
    }

    public function saveSerializedGrid($grid, $path): void
    {
        file_put_contents($path,serialize($grid));
    }

}