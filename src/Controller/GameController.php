<?php

namespace App\Controller;

use App\Handler\Gamehandler;
use App\Handler\Statehandler;
use App\Models\Grid\AiGrid;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/api")
 */

class GameController extends AbstractController
{

    /*
    * @var Gamehandler 
    */
    private $gamehandler;

    /*
    * @var Statehandler 
    */
    private $statehandler;


    public function __construct()
    {
        $this->statehandler = new Statehandler(new AiGrid());
        $this->gamehandler = new Gamehandler($this->statehandler);
    }

     /**
     * 
     * @Route("/" ,methods={"GET"} ,name="index")
     * 
     */
    public function index()
    {

        return $this->json([
            'message' => 'Game initialised',
            "links" =>
            [
                'self' =>
                [ 
                    'href' => 'http://' . $_SERVER["HTTP_HOST"] . '/api/',
                    'method' => 'GET'
                ],
                'place' =>
                [ 
                    'href' => 'http://' . $_SERVER["HTTP_HOST"] . '/api/place/',
                    'method' => 'POST'
                ]
            ]
        ]);
    }

    /**
     * 
     * @Route("/new" ,methods={"POST"} ,name="new")
     * 
     */
    public function new(Request $request)
    {
        $this->statehandler->renew();
        return $this->json([ 'message' => 'Game was restarted' ]);
    }

    /**
     * 
     * @Route("/place" , methods={"POST"} , requirements={"_format"="json"} , name="place")
     * 
     */
    public function place(Request $request): Response
    {
        $shiptype =     $request->request->get('shiptype');
        $direction =    $request->request->get('direction');
        $x =            $request->request->get('x');
        $y =            $request->request->get('y'); 

        
        $shipModel = $this->gamehandler->getShipModelByName($shiptype);
        $newship = new $shipModel((int) $x,(int) $y,(string) $direction);

        $this->gamehandler->placeOwnShip($newship);
        return new JsonResponse([
            'message' =>  'ship successfully placed',
            "links" =>
            [
                'self' =>
                [ 
                    'href' => 'http://' . $_SERVER["HTTP_HOST"] . '/api/place/',
                    'method' => 'POST'
                ],
                'shoot' =>
                [ 
                    'href' => 'http://' . $_SERVER["HTTP_HOST"] . '/api/shoot/',
                    'method' => 'POST'
                ]
            ]     
        ],201);
    
    }

    /**
     * 
     * @Route("/shoot" ,methods={"POST"} , requirements={"_format"="json"} , name="shoot")
     * 
     */
    public function shoot(Request $request): Response 
    {
        $x =            $request->request->get('x');
        $y =            $request->request->get('y'); 
        $outcome = $this->gamehandler->placeShot($x,$y);
        return new JsonResponse($outcome);
    }

     /**
     * 
     * @Route("/owngrid" ,methods={"GET"} , requirements={"_format"="json"} ,name="owngrid")
     * 
     */
    public function owngrid()
    {
        return new JsonResponse($this->gamehandler->getOwnGrid()->getGridMatrix());
    }

     /**
     * 
     * @Route("/aigrid" ,methods={"GET"} , requirements={"_format"="json"} ,name="aigrid")
     * 
     */
    public function aigrid()
    {
        return new JsonResponse($this->gamehandler->getAiGrid()->getGridMatrix());
    }
}