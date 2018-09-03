<?php

namespace AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity as Entity;
use AppBundle\Form as Form;

class ManufacturerController extends AbstractController
{
    /**
     * @Route("/admin/manufacturer", name="app.admin.manufacturer.list")
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function renderListAction()
    {
        $manufacturers = $this->getDoctrine()->getRepository(Entity\Manufacturer::class)->findAll();

        return $this->render('@App/admin/manufacturer/list.html.twig', [
            'manufacturers' => $manufacturers
        ]);
    }

    /**
     * @Route("/admin/manufacturer/add", name="app.admin.manufacturer.add")
     */
    public function renderAddNewFormAction()
    {
        $options = [
            'action' => $this->generateUrl('app.admin.manufacturer.handle_form')
        ];

        $form = $this->getForm(
            Form\Manufacturer::class,
            $options
        );

        return $this->render('@App/admin/manufacturer/manage.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/app/admin/manufacturer/edit/{object}", name="app.admin.manufacturer.edit")
     *
     * @param \AppBundle\Entity\Product $object
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function renderEditFormAction($object)
    {
        /** @var \AppBundle\Entity\Manufacturer $manufacturer */
        $manufacturer = $this->getDoctrine()->getRepository(Entity\Manufacturer::class)->findOneById($object);

        $form = $this->getForm(
            Form\Manufacturer::class,
            [
                'action' => $this->generateUrl('app.admin.manufacturer.handle_form', [
                    'object' => $manufacturer->getId()
                ])
            ], null, $manufacturer
        );

        return $this->render('@App/admin/manufacturer/manage.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     *
     * @Route("/admin/manufacturer/handle-form/{manufacturer}",
     *     name="app.admin.manufacturer.handle_form",
     *     defaults={"manufacturer"=null})
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param null $object
     *
     * @return mixed|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function handleFormAction(Request $request, $object = null)
    {
        $form = $this->getForm(Form\Manufacturer::class, [], $request, $object);

        if ($form->isValid()) {
            $formData = $form->getData();

            if (!$object) {
                $this->entityManager->persist($formData);
            }

            try {
                $this->entityManager->flush();
                $this->flashSuccess('Saved');
                return $this->redirectToRoute('app.admin.manufacturer.edit', [
                    'object' => $formData->getId()
                ]);
            } catch (\Exception $exception) {
                $this->flashError($exception->getMessage());
                return $this->redirectToRoute('app.admin.manufacturer.add');
            }
        }
    }

    /**
     * @Route("/admin/manufacturer/delete/{objectId}", name="app.admin.manufacturer.delete")
     *
     * @param string $objectId
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(string $objectId)
    {
        return $this->deleteObject(
            $objectId,
            Entity\Manufacturer::class,
            'app.admin.manufacturer.list',
            'Manufacturer removed'
        );
    }
}