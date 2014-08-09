<?php

namespace Gamma\Framework\Traits\DI;

/**
 * Set Request trait
 *
 * @author Evgen Kuzmin <jekccs@gmail.com>
 */
trait SetSessionTrait
{
    /**
     * @DI\Inject("session")
     * @var \Symfony\Component\HttpFoundation\Session\Session $session 
     */
    protected $session;

    /**
     * @param  \Symfony\Component\HttpFoundation\Session\Session $session 
     */
    public function setSession($session)
    {
        $this->session = $session;
    }
}
