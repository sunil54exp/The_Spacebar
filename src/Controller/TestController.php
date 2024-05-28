<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{

    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher=$eventDispatcher;
    }
    /**
     * @Route("/test", name="app_test")
     */
    public function index()
    {
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }

     /**
     * @Route("/EventTest", name="EventTest")
     */
    public function Test() 
    {
        $event=["name"=>"sunil","age"=>"30"];
        $result=$this->eventDispatcher->dispatch($event, 'test.event');
        echo "in test hello";
        echo "edited in local";
        dd($result);
        echo "in test function";
    }
}
