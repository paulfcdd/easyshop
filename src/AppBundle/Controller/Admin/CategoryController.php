<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\AppController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AppController
{
    /**
     * @Route("/admin/category", name="app.admin.category.list")
     */
    public function renderCategoryListAction()
    {
        return $this->render('@App/admin/category/list.html.twig', []);
    }
}