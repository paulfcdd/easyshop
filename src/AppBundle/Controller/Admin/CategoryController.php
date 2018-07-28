<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\AppController;
use AppBundle\Entity\Category;
use AppBundle\Form as Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AppController
{
    /**
     * @Route("/admin/category", name="app.admin.category.list")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderCategoryListAction()
    {
        $categories = $this->entityManager->getRepository(Category::class)->findAll();

        return $this->render('@App/admin/category/list.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/category/add", name="app.admin.category.add")
     */
    public function renderNewCategoryFormAction() {

        $form = $this->getForm(
            Form\Category::class, [
            'action' => $this->generateUrl('app.admin.category.handle_form')
        ]);

        return $this->render('@App/admin/category/manage.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/category/edit/{id}", name="app.admin.category.edit")
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderEditCategoryFormAction(Category $category)
    {
        $form = $this->getForm(Form\Category::class, [], null, $category);

        return $this->render('@App/admin/category/manage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/handle-form", name="app.admin.category.handle_form")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function handleForm(Request $request)
    {

        $form = $this->getForm(Form\Category::class, [], $request);


        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            $this->entityManager->persist($formData);

            try {
                $this->entityManager->flush();
                $this->flashSuccess('Saved');
                return $this->redirectToRoute('app.admin.category.edit', [
                    'id' => $formData->getId()
                ]);
            } catch (\Exception $exception) {
                $this->flashError($exception->getMessage());
                return $this->redirectToRoute('app.admin.category.add');
            }

        }
    }
}