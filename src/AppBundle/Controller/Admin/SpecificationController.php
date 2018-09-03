<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity as Entity;
use AppBundle\Form as Form;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SpecificationController extends AbstractController
{
    /**
     * @Route(
     *     "/admin/specification",
     *     name="app.admin.specification.list"
     * )
     */
    public function renderListAction()
    {
        $specifications = $this->getDoctrine()
            ->getRepository(Entity\Specification::class)
            ->findAll();

        return $this->render('@App/admin/specification/list.html.twig', [
            'specifications' => $specifications,
        ]);
    }

    /**
     * @Route("/admin/specification/add",
     *     name="app.admin.specification.add")
     */
    public function renderAddNewFormAction()
    {
        $options = [
            'action' => $this->generateUrl('app.admin.specification.handle_form')
        ];

        $form = $this->getForm(
            Form\Specification::class, $options
        );

        return $this->render('@App/admin/specification/manage.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/app/admin/specification/edit/{object}", name="app.admin.specification.edit")
     *
     * @param \AppBundle\Entity\Specification $object
     *
     * @return mixed|\Symfony\Component\HttpFoundation\Response
     */
    public function renderEditFormAction($object)
    {
        /** @var \AppBundle\Entity\Specification $specification */
        $specification = $this->entityManager
            ->getRepository(Entity\Specification::class)->findOneById($object);
        $options = [
            'action' => $this->generateUrl('app.admin.specification.handle_form', [
                'object' => $specification->getId()
            ])
        ];
        $form = $this->getForm(
            Form\Specification::class,
            $options,
            null,
            $specification
        );

        return $this->render('@App/admin/specification/manage.html.twig', [
            'form' => $form->createView()
        ]);    }

    /**
     * @Route("/admin/specification/handle-form/{object}",
     *     name="app.admin.specification.handle_form",
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
        $options = [];
        /** @var \AppBundle\Entity\Specification $specification */
        $specification = $this->checkObject($object, Entity\Specification::class);

        $form = $this->getForm(
            Form\Specification::class,
            $options,
            $request,
            $specification
        );

        if ($form->isValid()) {
            $formData = $form->getData();

            if (!$specification->getId()) {
                $this->entityManager->persist($formData);
            }

            try {
                $this->entityManager->flush();

                $this->flashSuccess('Saved');

                return $this->redirectToRoute('app.admin.specification.list');
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