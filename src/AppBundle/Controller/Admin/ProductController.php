<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity as Entity;
use AppBundle\Form as Form;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/admin/product", name="app.admin.product.list")
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function renderListAction()
    {
        $products = $this->entityManager->getRepository(Entity\Product::class)->findAll();

        return $this->render('@App/admin/product/list.html.twig', [
            'products' => $products,
        ]);
    }

    /**
     * @Route("/admin/product/add", name="app.admin.product.add")
     */
    public function renderAddNewFormAction()
    {
        $form = $this->getForm(
            Form\Product::class,
            ['action' => $this->generateUrl('app.admin.product.handle_form')]
        );

        return $this->render('@App/admin/product/manage.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/app/admin/product/edit/{object}", name="app.admin.product.edit")
     *
     * @param \AppBundle\Entity\Product $object
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function renderEditFormAction($object)
    {
        /** @var \AppBundle\Entity\Product $product */
        $product = $this->getDoctrine()->getRepository(Entity\Product::class)->findOneById($object);

        $form = $this->getForm(
            Form\Product::class,
            [
                'action' => $this->generateUrl('app.admin.product.handle_form', [
                    'object' => $product->getId()
                ])
            ], null, $product
        );

        return $this->render('@App/admin/product/manage.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/product/handle-form/{product}", name="app.admin.product.handle_form", defaults={"product"=null})
     * @Method("POST")
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param null $object
     *
     * @return mixed|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function handleFormAction(Request $request, $object = null)
    {
        $form = $this->getForm(Form\Product::class, [], $request, $object);

        if ($form->isValid()) {
            $formData = $form->getData();

            if (!$object) {
                $this->entityManager->persist($formData);
            }

            try {
                $this->entityManager->flush();
                $this->flashSuccess('Saved');
                return $this->redirectToRoute('app.admin.product.edit', [
                    'id' => $formData->getId()
                ]);
            } catch (\Exception $exception) {
                $this->flashError($exception->getMessage());
                return $this->redirectToRoute('app.admin.product.add');
            }
       }
    }
}