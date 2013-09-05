<?php

namespace LaMelle\Framework\Traits\DI;

/**
 * Set Router trait
 *
 * @author Evgen Kuzmin <jekccs@gmail.com>
 */
trait SetRouterTrait
{
    /**
     * @DI\Inject("router")
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router $router
     */    
    protected $router;

	/**
	 * @param \Symfony\Bundle\FrameworkBundle\Routing\Router $router
	 */
	public function setRouter($router)
	{
		$this->router = $router;
	}   
}