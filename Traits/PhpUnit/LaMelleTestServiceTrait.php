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