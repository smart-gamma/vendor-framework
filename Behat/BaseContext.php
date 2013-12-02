<?php

namespace Gamma\Framework\Behat;
use Behat\MinkExtension\Context\MinkContext;

use Behat\Behat\Context\BehatContext;
use Behat\Symfony2Extension\Context\KernelAwareInterface;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Base context class.
 *
 * @author Evgeniy Kuzmin <jekccs@gmail.com>
 */
abstract class BaseContext extends BehatContext implements KernelAwareInterface
{
    /**
     * Kernel.
     *
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * Sets HttpKernel instance.
     * This method will be automatically called by Symfony2Extension ContextInitializer.
     *
     * @param KernelInterface $kernel
     */
    public function setKernel(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    protected function getEntityManager()
    {
        return $this->getService('doctrine.orm.entity_manager');
    }

    protected function generateUrl($route, array $params = array())
    {
        return $this->getRouter()->generate($route, $params);
    }

    protected function getRouter()
    {
        return $this->kernel->getContainer()->get('router');
    }

    protected function getService($id)
    {
        return $this->kernel->getContainer()->get($id);
    }
        
}
