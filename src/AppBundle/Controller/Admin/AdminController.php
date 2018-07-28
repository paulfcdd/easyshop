<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\AppController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AppController
{
    /**
     * @Route("/admin", name="app.admin.index")
     */
    public function indexAction()
    {
        return $this->render('@App/admin/layout/index.html.twig', []);
    }
}