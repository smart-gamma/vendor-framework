<?php

namespace LaMelle\Framework\Traits\DI;

/**
 * Set Templating trait
 *
 * @author Evgen Kuzmin <jekccs@gmail.com>
 */
trait SetTemplatingTrait
{
    /**
     * @DI\Inject("templating")
     * @var \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
     */    
    public $templating;

	/**
	 * @param \Symfony\Bundle\FrameworkBundle\Templating\EngineInterface $templating
	 */
	public function setTemplating($templating)
	{
		$this->templating = $templating;
	}   
}