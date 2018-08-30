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
        $options = [
            'action' => $this->generateUrl('app.admin.product.handle_form')
        ];

        $form = $this->getForm(
            Form\Product::class,
            $options
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
            'product' => $product,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/product/handle-form/{object}",
     *     name="app.admin.product.handle_form",
     *     defaults={"object"=null})
     * @Method("POST")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param mixed $object
     *
     * @return mixed|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function handleFormAction(Request $request, $object)
    {
        /** @var \AppBundle\Entity\Product $product */
        $product = $this->checkObject($object, Entity\Product::class);

        $form = $this->getForm(Form\Product::class, [], $request, $product);

        if ($form->isValid()) {
            /** @var \AppBundle\Entity\Product $formData */
            $formData = $form->getData();

            /** @var \AppBundle\Entity\ProductSpecification $specification */
            foreach ($formData->getSpecifications() as $specification) {
                $specification->setProduct($product);
            }

            if (!$product->getId()) {
                $this->entityManager->persist($formData);
            }

            try {
                $this->entityManager->flush();
                $this->flashSuccess('Saved');
                return $this->redirectToRoute('app.admin.product.edit', [
                    'object' => $formData->getId()
                ]);
            } catch (\Exception $exception) {
                $this->flashError($exception->getMessage());
                return $this->redirectToRoute('app.admin.product.add');
            }
        }
    }

    /**
     * @Route("/admin/product/delete/{objectId}", name="app.admin.product.delete")
     *
     * @param string $objectId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(string $objectId)
    {
        return $this->deleteObject(
            $objectId,
            Entity\Product::class,
            'app.admin.product.list',
            'Product removed'
        );
    }
}