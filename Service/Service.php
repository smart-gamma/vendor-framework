<?php
namespace Gamma\Framework\Service;

use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Base class for services
 *
 * @author Fabian Martin <martin@localdev.de>
 */
abstract class Service extends ContainerAware
{
    /**
     * Initializes a new instance of the LanguageManager class.
     *
     * @param ContainerInterface $container service container
     * @param Logger             $logger    Logger
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Gets a service.
     *
     * @param string $id                The service identifier
     * @param int    $invalidBehavior   The behavior when the service does not exist
     *
     * @throws \InvalidArgumentException if the service is not defined
     *
     * @return mixed The associated service
     */
    protected function get($id, $invalidBehavior = ContainerInterface::EXCEPTION_ON_INVALID_REFERENCE)
    {
        return $this->container->get($id, $invalidBehavior);
    }

    /**
     * Search a record and return them
     *
     * @param int|string $id            ID of the entity
     * @param string     $name          The entity manager name (null for the default one)
     * @param string     $repository    Repository
     *
     * @return mixed
     */
    protected function getEntity($id, $repository, $name = null)
    {
        return $this->getRepository($repository, $name)->findOneBy(array('id' => $id));
    }

    /**
     * Returns the repository
     *
     * @param string $repository  Repository name
     * @param string $name        The entity manager name (null for the default one)
     *
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository($repository, $name = null)
    {
        return $this->getManager($name)->getRepository($repository);
    }

    /**
     * Returns the Entity Manager
     *
     * @param string $name The entity manager name (null for the default one)
     *
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getManager($name = null)
    {
        return $this->getDoctrine()->getManager($name);
    }

    /**
     * Returns the EntityManager
     *
     * @deprecated
     *
     * @param string $name The entity manager name (null for the default one)
     *
     * @return \Doctrine\Common\Persistence\ObjectManager
     */
    protected function getEntityManager($name = null)
    {
        return $this->getManager($name);
    }

    /**
     * Returns the doctrine instance
     *
     * @return \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected function getDoctrine()
    {
        return $this->get('doctrine');
    }

    /**
     * Request
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        return $this->get('request');
    }
}
