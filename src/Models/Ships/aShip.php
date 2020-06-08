<?php 

namespace App\Models\Ships;

use App\Interfaces\iShip;
use Symfony\Component\Validator\Constraints as Assert;

abstract class aShip implements iShip
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

    /**
     * @Assert\Type("string")
     * @Assert\Choice({"right", "down"})
     */
    protected $alignment;

    /**
     * @Assert\Type("integer")
     */
    protected $hits = 0;  



    public function __construct( $x, $y, $alignment)
    {
        $this->x = $x;
        $this->y = $y;
        $this->alignment = $alignment;
    }

    public function horizontal(): bool
    {
        if($this->alignment === iShip::RIGHT)
        {
            return true;
        } else if($this->alignment === iShip::DOWN)
        {
            return false;
        }
    }

    public function hit(): void
    {
        !$this->dead()? ($this->hits++) : null; 
    }

    public function dead(): bool
    {
        return $this->hits === $this->getSize()? true : false;    
    }
}
