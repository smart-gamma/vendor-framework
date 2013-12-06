<?php

namespace Gamma\Framework\Traits\Entity;

/**
 * Id autoincremet trait
 *
 * @author Evgeniy Kuzmin <jekccs@gmail.com>
 */
trait IdAutoincrementTrait
{
    /**
     * Item ID 
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * Get id
     *
     * @return int $id
     */
    public function getId()
    {
        return $this->id;
    }
}
