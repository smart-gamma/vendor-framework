<?php

namespace LaMelle\Framework\Traits\PhpUnit;

/**
 * Initialize app test global variables for unit tests
 *
 * @author Evgen Kuzmin <jekccs@gmail.com>
 */
trait LaMelleTestAplicationTrait
{
  /**
   * Selector to use real twig or mock engine
   *
   * @var bool
   */ 
  private $isTwigEmulation = true; 

  /**
   * Global variable to selected product at products menu
   *
   * @var string
   */   
  private $_window = 'fotorollo';
  
  /**
   * Global variable to selected category at categories menu
   *
   * @var string
   */ 
  private $_categorySlug = 'sun';
  
  /*
   * Prepare request context for uri params KernelRequestListener storage from cookie emulation in real app
   * @param: bool $isTwigEmulation - what we use in test - real twig or mock
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
     if(!$this->isTwigEmulation)
     {
        $this->twig->addGlobal('_category', $this->_categorySlug);
        $this->twig->addGlobal('_window', $this->_window);
        $this->twig->addGlobal('cart', array('products'=>'dummy'));
        $this->twig->addGlobal('_contentsection', 'products');
        $this->twig->addGlobal('_contentsectioninfo', 'produkte');
        $this->twig->addGlobal('_windowinfo', array('name'=>'dummy product','infoUrl'=> 'dummyUrl'));
        $this->twig->addGlobal('footer_montage', array());    
     }
  }
  
  /*
   * Set of phpunit asserts to test controller action responce ok
   * @param: \Symfony\Component\HttpFoundation\Response $result - controller action answer
   */ 
  private function assertActionResponded($result)
  {
    $this->assertInstanceOf('\Symfony\Component\HttpFoundation\Response', $result);
    $this->assertEquals(200,$result->getStatusCode());
    $this->assertNotNull($result->getContent());
  }
}