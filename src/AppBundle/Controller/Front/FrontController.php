<?php

namespace AppBundle\Controller\Front;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Controller\AppController;
use AppBundle\Form\ReviewType;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AppController
{
    /**
     * @Route("/", name="app.front.index")
     */
    public function indexAction()
    {
        return $this->renderFront('layout/index', []);
    }

    /**
     * @Route("/products/list/{category}", name="app.front.product.list")
     *
     * @param Category $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function productsListAction(Category $category)
    {
        /** @var array $products */
        $products = $this->entityManager
            ->getRepository(Category::class)
            ->getCategoryProducts($category->getId(), $category->getName());

        return $this->renderFront('layout/product_list', [
            'products' => $products,
            'category' => $category,
            'categories' => $this->getCategoriesTree(),
        ]);
    }

    /**
     * @Route("/product/{product}", name="app.front.single_product")
     * @param \AppBundle\Entity\Product $product
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function singleProductAction(Product $product)
    {
        $reviewForm = $this->getForm(ReviewType::class, []);

        return $this->renderFront('layout/single_product', [
            'product' => $product,
            'reviewForm' => $reviewForm->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderMenuAction()
    {
        return $this->render('@App/template/default/partials/_menu.html.twig', [
            'menu' => $this->getCategoriesTree()
        ]);
    }

    /**
     * @return array|null
     */
    private function getCategoriesTree()
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->getMenuCategories();

        $categoriesTree = [];
        /** @var Category $category */
        foreach ($categories as $category) {
            if (!$category->getParent()) {
                $categoriesTree[$category->getId()]['parent'] = $category->getName();
                $categoriesTree[$category->getId()]['id'] = $category->getId();
                $categoriesTree[$category->getId()]['children'] = [];
            }
        }

        $tree = $this->renderNestedTree($categories, $categoriesTree);

        return $tree;
    }
}