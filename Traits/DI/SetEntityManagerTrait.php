<?php

namespace Gamma\Framework\Traits\DI;

/**
 * Set EntityManager trait
 *
 * @author Evgen Kuzmin <jekccs@gmail.com>
 */
trait SetEntityManagerTrait
{
    /**
     * @DI\Inject("doctrine.orm.entity_manager")
     * @var \Doctrine\ORM\EntityManager $em
     */    
    protected $em;

	/**
	 * @param \Doctrine\ORM\EntityManager $em
	 */
	public function setEm($em)
	{
		$this->em = $em;
	}    
}