<?php

namespace LaMelle\Framework\Traits\PhpUnit;

/**
 * Initialize app test global variables for service unit tests
 *
 * @author Evgen Kuzmin <jekccs@gmail.com>
 */
trait LaMelleTestServiceTrait
{
  /**
   * Selector to use real twig and inhouse classes or mock them
   * External api services like Paypal/Fotolia etc are mockering any case
   *  
   * @var bool
   */ 
  private $isMockEmulation = true; 

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

}