<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity as Entity;
use AppBundle\Form as Form;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SpecificationGroupController extends AbstractController
{
    /**
     * @Route("/admin/specification_group", name="app.admin.specification_group.list")
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function renderListAction()
    {
        $specificationGroups = $this->getDoctrine()
            ->getRepository(Entity\SpecificationGroup::class)
            ->findAll();

        return $this->render('@App/admin/specification_group/list.html.twig', [
            'specificationGroups' => $specificationGroups
        ]);
    }

    /**
     * @Route("/admin/specification_group/add",
     *     name="app.admin.specification_group.add")
     */
    public function renderAddNewFormAction()
    {
        $options = [
            'action' => $this->generateUrl('app.admin.specification_group.handle_form')
        ];

        $form = $this->getForm(
            Form\SpecificationGroup::class,
            $options
        );

        return $this->render('@App/admin/specification_group/manage.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/app/admin/specification_group/edit/{object}", name="app.admin.specification_group.edit")
     *
     * @param \AppBundle\Entity\SpecificationGroup $object
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function renderEditFormAction($object)
    {
        /** @var \AppBundle\Entity\SpecificationGroup $specificationGroup */
        $specificationGroup = $this->entityManager->getRepository(Entity\SpecificationGroup::class)->findOneById($object);
        $options = [
            'action' => $this->generateUrl('app.admin.specification_group.handle_form', [
                'object' => $specificationGroup->getId()
            ])
        ];
        $form = $this->getForm(
            Form\SpecificationGroup::class,
            $options,
            null,
            $specificationGroup
        );

        return $this->render('@App/admin/specification_group/manage.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/specification_group/handle-form/{object}",
     *     name="app.admin.specification_group.handle_form",
     *     defaults={"object"=null}
     *     )
     * @Method("POST")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param mixed|null $object
     *
     * @return mixed|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function handleFormAction(Request $request, $object)
    {
        /** @var \AppBundle\Entity\SpecificationGroup $specificationGroup */
        $specificationGroup = $this->checkObject($object, Entity\SpecificationGroup::class);

        $form = $this->getForm(
            Form\SpecificationGroup::class,
            [],
            $request,
            $specificationGroup
        );

        if ($form->isValid()) {
            $formData = $form->getData();

            if (!$specificationGroup->getId()) {
                $this->entityManager->persist($formData);
            }

            try {
                $this->entityManager->flush();
                $this->flashSuccess('Saved');

                return $this->redirectToRoute('app.admin.specification_group.list');
            } catch (\Exception $exception) {
                $this->flashError($exception->getMessage());
                return $this->redirectToRoute('app.admin.specification_group.list');
            }

        }
    }

    public function deleteAction(string $objectId)
    {
        // TODO: Implement deleteAction() method.
    }
}