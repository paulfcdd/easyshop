<?php

namespace AppBundle\Controller\Front;

use AppBundle\Controller\AppController;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AppController
{
    /**
     * @Route("/", name="app.front.index")
     */
    public function indexAction()
    {
       return $this->renderFront('/layout/index', []);

    }
}