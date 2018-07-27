<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Template;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AppController extends Controller
{
    public const FRONT_SITE_PART = 'front';

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
     * @param string $pathToFile
     * @param array $parameters
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public final function renderFront(string $pathToFile, array $parameters = [])
    {
        $template = $this->getTemplate(self::FRONT_SITE_PART);
        $themeName = $template->getName();
        $templateFilePath = '@App/'.$themeName.'/'.self::FRONT_SITE_PART.''.$pathToFile.'.html.twig';

        return $this->render($templateFilePath, $parameters);
    }

    /**
     * @param string $sitePart
     * @return \AppBundle\Entity\Template
     */
    private final function getTemplate(string $sitePart)
    {
        $criteria = [
            'sitePart' => $sitePart,
            'isActive' => true,
        ];

        /** @var Template $template */
        $template = $this->entityManager->getRepository(Template::class)->findOneBy($criteria);

        return $template;
    }
}
