<?php

namespace AppBundle\Controller\Admin\Api;

use AppBundle\Controller\AppController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity as Entity;

/**
 * Class ApiController
 * @package AppBundle\Controller\Admin\Api
 *
 * @Route("/admin/api")
 */
class ApiController extends AppController
{
    /**
     * @Route("/delete-object", name="app.admin.api.delete_object")
     */
    public function deleteAction(Request $request)
    {
        $entity = $request->request->get('entity');
        $objectId = $request->request->get('id');
        $entityFQN = $this->getEntityFQN($entity);

        try {
            $object = $this->entityManager->getRepository($entityFQN)->findOneById($objectId);
            $this->entityManager->remove($object);
            $this->entityManager->flush();
            return JsonResponse::create('deleted', 200);
        } catch (\Exception $exception) {
            return JsonResponse::create('not deleted', 500);

        }
    }

    /**
     * @param string $name
     * @return string
     */
    private function getEntityFQN(string $name) {
        $entityNameArray = explode('_', $name);
        $normalizeEntityName = null;

        foreach ($entityNameArray as $item) {
            $item = ucfirst($item);
            $normalizeEntityName .= $item;
        }

        $entityFQN = sprintf('AppBundle\\Entity\\%s', $normalizeEntityName);

        return $entityFQN;

    }
}