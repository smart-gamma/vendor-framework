<?php

namespace Gamma\Framework\Traits\Entity;

/**
 * Enabled item property
 *
 * @author Evgeniy Kuzmin <jekccs@gmail.com>
 */
trait EnabledTrait
{
    /**
     * Show item in shop
     *
     * @ORM\Column(type="boolean")
     * @var                        bool
     */
    private $enabled = true;
    
    /**
     * Set enabled
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Get enabled
     *
     * @return boolean $enabled
     */
    public function getEnabled()
    {
        return $this->enabled;
    }    
}
