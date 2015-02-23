<?php

namespace Gamma\Framework\Traits\DI;

/**
 * Set JMS Serializer trait
 *
 * @author Evgen Kuzmin <jekccs@gmail.com>
 */
trait SetSerializerTrait
{
    /**
     * @DI\Inject("jms_serializer")
     * @var object $request
     */
    protected $serializer;

    /**
     * @param object $request
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }
}
