<?php

use Behat\Behat\Context\Context;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class FeatureContext
 * @author Piotr Lewandowski <p.lewandowski@madcoders.pl>
 */
class FeatureContext implements Context
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * @var Response|null
     */
    private $response;

    /**
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * FeatureContext constructor.
     * @param KernelInterface $kernel
     * @param ManagerRegistry $doctrine
     */
    public function __construct(KernelInterface $kernel, ManagerRegistry $doctrine)
    {
        $this->kernel = $kernel;
        $this->doctrine = $doctrine;
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected function getContainer()
    {
        return $this->kernel->getContainer();
    }

    /**
     * @return \Doctrine\Common\Persistence\ManagerRegistry|object
     */
    protected function getDoctrine()
    {
        return $this->doctrine;
    }

    /**
     * @BeforeScenario
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    public function createSchema()
    {
        $schemaTool =  new \Doctrine\ORM\Tools\SchemaTool($this->doctrine->getManager());
        $classes = $this->doctrine->getManager()->getMetadataFactory()->getAllMetadata();

        $schemaTool->dropSchema($classes);
        $schemaTool->createSchema($classes);
    }
}
