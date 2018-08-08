<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\Template;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AppController extends Controller
{
    public const FLASH_SUCCESS = 'success';
    public const FLASH_ERROR = 'error';

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    public $entityManager;

    /**
     * AppController constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $fileName
     * @param array $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public final function renderFront(string $fileName, array $parameters = [])
    {
        $template = $this->getActiveTemplate();
        $themeName = $template->getName();

        return $this->render(
            $this->getFullTemplatePath($themeName, $fileName),
            $parameters
        );
    }

    /**
     * @return \AppBundle\Entity\Template
     */
    public final function getActiveTemplate()
    {
        $criteria = [
            'isActive' => true,
        ];

        /** @var Template $template */
        $template = $this->entityManager->getRepository(Template::class)->findOneBy($criteria);

        return $template;
    }

    /**
     * @param string $message
     */
    public final function flashSuccess(string $message)
    {
        $this->addFlash(self::FLASH_SUCCESS, $message);
    }

    /**
     * @param string $message
     */
    public function flashError(string $message)
    {
        $this->addFlash(self::FLASH_ERROR, $message);
    }

    /**
     * @param $categories
     * @param array|null $categoriesTree
     *
     * @return array|null
     */
    public function renderNestedTree($categories, ?array $categoriesTree)
    {
        /** @var \AppBundle\Entity\Category $category */
        foreach ($categories as $category) {
            if ($category->getParent()) {
                if (array_key_exists($category->getParent()->getId(), $categoriesTree)) {
                    $parent = $category->getParent();
                    $categoriesTree[$parent->getId()]['children'][$category->getId()]['parent'] = $category->getName();
                    $categoriesTree[$parent->getId()]['children'][$category->getId()]['id'] = $category->getId();
                    $categoriesTree[$parent->getId()]['children'][$category->getId()]['children'] = [];
                }
            }
        }

        foreach ($categoriesTree as $key => $treeItem) {
            if (!empty($treeItem['children'])) {
                $children = $this->renderNestedTree($categories, $treeItem['children']);
                $categoriesTree[$key]['children'] = $children;
            }
        }

        return $categoriesTree;
    }

    /**
     * @param \AppBundle\Entity\Category $category
     * @param \AppBundle\Entity\Product|null $product
     *
     * @return array
     */
    public function renderBreadcrumbsChain(Category $category, Product $product = null)
    {
        if ($category->getParent()) {
            $breadcrumbs = [];

            /** @var Category $parent */
            $parent = $category->getParent();
            $breadcrumbs[] = $parent->getName();

            $this->renderBreadcrumbsChain($parent, $product);
        }

        $breadcrumbs[] = $category->getName();

        if ($product)
        {
            $breadcrumbs[] = $product->getName();
        }

        return $breadcrumbs;
    }

    /**
     * @param string $formType
     * @param \Symfony\Component\HttpFoundation\Request|null $request
     * @param mixed $data
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected final function getForm(string $formType, array $options = [], Request $request = null, $data = null)
    {
        $form = $this->createForm($formType, $data, $options);

        if ($request) {
            $form->handleRequest($request);
        }

        return $form;
    }

    /**
     * @param string $themeName
     * @param $fileName
     * @return mixed|string
     */
    private function getFullTemplatePath(string $themeName, $fileName)
    {
        $pattern = '@App/template/%theme_name%/%file_name%.html.twig';

        $pattern = str_replace(
            ['%theme_name%', '%file_name%'],
            [$themeName, $fileName],
            $pattern
        );

        return $pattern;
    }
}
