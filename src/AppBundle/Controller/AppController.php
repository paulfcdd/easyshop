<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Template;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller
{
    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    public $entityManager;

    /**
     * AppController constructor.
     *
     * @param \Doctrine\ORM\EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param string $fileName
     * @param array $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public final function renderFront(string $fileName, array $parameters = [])
    {
        $template = $this->getActiveTemplate();
        $themeName = $template->getName();

        return $this->render(
            $this->getFullTemplatePath($themeName, $fileName),
            $parameters
        );
    }

    /**
     * @return \AppBundle\Entity\Template
     */
    public final function getActiveTemplate()
    {
        $criteria = [
            'isActive' => true,
        ];

        /** @var Template $template */
        $template = $this->entityManager->getRepository(Template::class)->findOneBy($criteria);

        return $template;
    }

    /**
     * @param string $themeName
     * @param $fileName
     * @return mixed|string
     */
    private function getFullTemplatePath(string $themeName, $fileName)
    {
        $pattern = '@App/template/%theme_name%/%file_name%.html.twig';

        $pattern = str_replace(
            ['%theme_name%', '%file_name%'],
            [$themeName, $fileName],
            $pattern
        );

        return $pattern;
    }
}
