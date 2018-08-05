<?php

namespace AppBundle\Controller\Front;

use AppBundle\Controller\AppController;
use AppBundle\Entity\Category;
use AppBundle\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AppController
{
    /**
     * @Route("/", name="app.front.index")
     */
    public function indexAction()
    {
        return $this->renderFront('layout/index', [
            'menu' => $this->renderMenu(),
        ]);
    }

    public function productsListAction()
    {

    }

    /**
     * @return array
     */
    public function renderMenu()
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->getMenuCategories();

        $categoriesTree = [];
        /** @var Category $category */
        foreach ($categories as $category) {
            if (!$category->getParent()) {
                $categoriesTree[$category->getId()]['parent'] = $category->getName();
                $categoriesTree[$category->getId()]['children'] = [];
            }
        }
        $tree = $this->renderNestedTree($categories, $categoriesTree);

        return $tree;
    }
}