<?php

namespace AppBundle\Controller\Admin\Catalog;

use AppBundle\Controller\AppController;
use AppBundle\Entity\Category;
use AppBundle\Form as Form;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
    public function renderNewCategoryFormAction()
    {

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
        $form = $this->getForm(
            Form\Category::class,
            ['action' => $this->generateUrl('app.admin.category.handle_form', [
                'category' => $category->getId()
            ])],
            null,
            $category
        );

        return $this->render('@App/admin/category/manage.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/category/handle-form/{category}", name="app.admin.category.handle_form", defaults={"category"=null})
     * @Method("POST")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \AppBundle\Entity\Category|null $category
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function handleFormAction(Request $request, ?Category $category)
    {
        $form = $this->getForm(Form\Category::class, [], $request, $category);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            if (!$category) {
                $this->entityManager->persist($formData);
            }

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

    /**
     * @Route("/admin/category/delete/{id}", name="app.admin.category.delete")
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteCategory(Category $category)
    {
        $this->entityManager->remove($category);
        try {
            $this->entityManager->flush();
            $this->flashSuccess('Category removed');
        } catch (\Exception $exception) {
            $this->flashError($exception->getMessage());
        }

        return $this->redirectToRoute('app.admin.category.list');
    }
}