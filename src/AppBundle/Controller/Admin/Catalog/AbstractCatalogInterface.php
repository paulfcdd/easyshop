<?php

namespace AppBundle\Controller\Admin\Catalog;

use Symfony\Component\HttpFoundation\Request;

interface AbstractCatalogInterface
{
    /**
     * @return mixed
     */
    public function renderListAction();

    /**
     * @return mixed
     */
    public function renderAddNewFormAction();

    /**
     * @param $object
     * @return mixed
     */
    public function renderEditFormAction($object);

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param mixed | null $object
     * @return mixed
     */
    public function handleFormAction(Request $request, $object);

    /**
     * @param string $objectId
     *
     * @return mixed
     */
    public function deleteAction(string $objectId);
}