<?php

namespace Gamma\Framework\Traits\PhpUnit;

use LaMelle\PanthermediaBundle\Entity\Image;
use LaMelle\PanthermediaBundle\Entity\SourceFile;

/**
 * Initialize app test global variables for controller unit tests
 *
 * @author Evgen Kuzmin <jekccs@gmail.com>
 */
trait LaMelleTestControllerTrait
{
  use LaMelleTestServiceTrait;

  /**
   * Prepare request context for uri params KernelRequestListener storage from cookie emulation in real app
   * @param bool $isMockEmulation - what we use in test - real twig or mock
   */
  private function buildRequestContext()
  {
    //Uri emulation
    $router = $this->container->get('router');
    $context = $router->getContext();
    $context->setParameter('_window', $this->_window);
    $context->setParameter('_category', $this->_categorySlug);
    $router->setContext($context);

    //twig global vars emulation
     if (!$this->isMockEmulation) {
        $this->twig->addGlobal('_category', $this->_categorySlug);
        $this->twig->addGlobal('_window', $this->_window);
        $this->twig->addGlobal('cart', array('products'=>'dummy'));
        $this->twig->addGlobal('_contentsection', 'products');
        $this->twig->addGlobal('_contentsectioninfo', 'produkte');
        $this->twig->addGlobal('_windowinfo', array('name'=>'dummy product','infoUrl'=> 'dummyUrl'));
        $this->twig->addGlobal('footer_montage', array());
     }

     // Configure Panthermedia Image service instead of EventListener
     Image::setImagePrice($this->container->getParameter('lamelle.panther.config.price'));
     Image::setRawFiles($this->container->getParameter('lamelle.panther.config.raw_files'));
     Image::setWebFiles($this->container->getParameter('lamelle.panther.config.web_files'));
     Image::setWebPath($this->container->getParameter('lamelle.panther.config.web_path'));
     SourceFile::setWebPath($this->container->getParameter('lamelle.panther.config.web_path'));
     SourceFile::setWebFiles($this->container->getParameter('lamelle.panther.config.web_files'));
  }
}
