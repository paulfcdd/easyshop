<?php

namespace AppBundle\Controller\Front\Api;

use AppBundle\Controller\AppController;
use AppBundle\Entity\Product;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiController extends AppController
{
    private const STATUS_SUCCESS = 200;
    private const STATUS_ERROR = 500;

    /**
     * @Route("/get-products", name="app.front.api.get_products")
     * @Method("GET")
     *
     * @return JsonResponse
     */
    public function getProducts()
    {
        $products = $this->entityManager->getRepository(Product::class)->getAllProducts();
        $result = [];
        $i = 0;

        if (!empty($products)) {
            /** @var Product $product */
            foreach ($products as $product) {
                $result[$i]['id'] = $product->getId();
                $result[$i]['slug'] = $product->getSlug();
                $result[$i]['name'] = $product->getName();
                $result[$i]['price'] = $product->getPrice();
                $i++;
            }
        }

        return JsonResponse::create($result, self::STATUS_SUCCESS);
    }
}