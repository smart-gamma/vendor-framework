<?php

namespace Gamma\Framework\Traits\DI;

/**
 * Set Twig trait
 *
 * @author Evgen Kuzmin <jekccs@gmail.com>
 */
trait SetTwigTrait
{
    /**
     * @DI\Inject("twig")
     * @var \Twig_Enviroment $twig
     */    
    public $twig;

	/**
	 * @param \Twig_Enviroment $twig
	 */
	public function setTwig($twig)
	{
		$this->twig = $twig;
	}   
}