<?php

namespace Gamma\Framework\Traits\DI;

/**
 * Set Request trait
 *
 * @author Evgen Kuzmin <jekccs@gmail.com>
 */
trait SetRequestTrait
{
    /**
     * @DI\Inject("request")
     * @var \Symfony\Component\HttpFoundation\Request $request
     */
    protected $request;

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest($request)
    {
        $this->request = $request;
    }
}
