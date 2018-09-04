<?php

namespace AppBundle\Controller\Admin\Catalog;

use AppBundle\Controller\AppController;

abstract class AbstractCatalogController extends AppController implements AbstractCatalogInterface
{
    /**
     * @param null|string $objectId
     * @param string $entityFQN
     *
     * @return mixed
     */
    public function checkObject(?string $objectId, string $entityFQN)
    {
        if ($objectId) {
            $object = $this->getDoctrine()->getRepository($entityFQN)->findOneById($objectId);
        } else {
            $object = new $entityFQN();
        }

        return $object;
    }

    /**
     * @param string $objectId
     * @param string $entityFQN
     * @param string $routeToRedirect
     * @param string $messageSuccess
     * @param null|string $messageError
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteObject(
        string $objectId,
        string $entityFQN,
        string $routeToRedirect,
        string $messageSuccess,
        ?string $messageError = null
    )
    {
        $object = $this->checkObject($objectId, $entityFQN);

        $this->entityManager->remove($object);

        try {
            $this->entityManager->flush();
            $this->flashSuccess($messageSuccess);
        } catch (\Exception $exception) {
            $this->flashError($messageError ? $messageError : $exception->getMessage());
        }

        return $this->redirectToRoute($routeToRedirect);
    }
}