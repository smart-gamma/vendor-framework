<?php

namespace Gamma\Framework\Traits\Entity;

/**
 * Slug item property
 *
 * @author Evgeniy Kuzmin <jekccs@gmail.com>
 */
trait SlugTrait
{
    /**
     * Slug
     *
     * @ORM\Column(type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @var string
     */
    private $slug;

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string $slug
     */
    public function getSlug()
    {
        return $this->slug;
    }    
}
